<?php

class OrdersController extends \BaseController {
        
        private $order_id = 0;
        private $customer_id = 0;

        private static $shipping_options_master = array(
                'ship_together' => 'Ship CDs and DVDs together', 
                'ship_separately' => 'Ship CDs and DVDs separately', 
                'ship_dvd_only' => 'Pick up CDs at Workshop/Ship DVDs', 
                'ship_cd' => 'Ship CDs',
                'pickup' => 'Pick up CDs at Workshop',
                'ship_dvd' => 'Ship DVDs',
                'mp3_only' => 'MP3s only',
            );
        
        private static $order_status_list = array(
                'Created' => 'Created',
                'Payment Received' => 'Payment Received',
                'Pending' => 'Pending Delivery/Shipment',
                'Completed' => 'Completed',
            );

        public function __construct() {
           /**
            * Prevent CSRF for 'POST' actions.
            */
            $this->beforeFilter('csrf', array('on' => 'post'));
            
            // Populate master list of shipping options from configuration file.
            //$shipping_options_master = Config::get('workshop.shipping_options');
        }            
            
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		//
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
            if ( Auth::check() ) {
                $cartContents = Cart::contents();
                $shipping_options = OrdersController::getShippingOptions();
                $shipping_charge_note = 'Shipping charges are $1 per disk, with a minimum of $'. Config::get('workshop.minimum_shipping_charge') . ' and maximum of $' . Config::get('workshop.maximum_shipping_charge') . ' per shipment.';
                $this->layout->content = View::make('orders.create', compact('cartContents', 'shipping_options'))
                        ->with(array('orderVerification' => FALSE, 'shipping_charge_note' => $shipping_charge_note));
            } else { // Redirect to login page
                return Redirect::route('login')->with('message', 'Please log in to complete your order.');
            }
            
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
            $order = new Order();
            $order->delivery_terms = Input::get('shipping_option');
            $order->order_notes = Input::get('order_notes');
            if ( $order->order_notes == 'Order Notes' ) $order->order_notes = NULL;
            $order->customer_id = Auth::id();
            $order->order_date = date('Y-m-d');
            $order->online_order_ind = TRUE;
            $order = OrdersController::getOrderCharges($order);
            
            if ( $order->save() ) {
                Log::debug('New order #' . $order->id . ' saved.');
                $this->order_id = $order->id;
                $this->customer_id = $order->customer_id;
                
                if ( OrdersController::persistCart($order) ) {
                    return Redirect::route('orders.show', array('order' => $order->id))
                            ->with('message', 'Order #' . $order->id . ' created.');
                } else {
                    // Error!
                }
            } else {
                Log::error('Error saving order for customer ID ' . $order->customer_id . '. Error message: ' . print_r($order->errors(), TRUE));
                return Redirect::route('orders.create')
                        ->withInput()
                        ->withErrors( $order->errors() );
            }
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  Order $order
	 * @return Response
	 */
	public function show(Order $order)
	{
            $this->order_id = $order->id;
            $this->customer_id = $order->customer_id;
            
            if ( Input::has('hashkey') ) {
                Session::put('hashkey', urldecode(Input::get('hashkey')));
            }
            
            if ( OrdersController::checkAdminOrOrderUser($order) ) {
                $order->shipping_option_display = OrdersController::$shipping_options_master[$order->delivery_terms];
                if ( Cart::totalItems() > 0 ) {
                    $cartContents = Cart::contents();
                } else {
                    $cartContents = OrdersController::convertOrderItemsToCartItems($order->orderItems);
                }                
                
                if ( $order->order_status == 'Created' ) {
                    $paypal_attrs = OrdersController::getPaypalAttributes($order);

                    $this->layout->content = View::make('orders.show-verification', compact('order', 'cartContents', 'paypal_attrs'))->with(array('orderVerification' => TRUE));
                } else {
                    $this->layout->content = View::make('orders.show-complete', compact('order', 'cartContents'))->with(array('orderVerification' => TRUE));
                }
            } else {
                return Redirect::route('login');
            }
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  Order $order
	 * @return Response
	 */
	public function edit(Order $order)
	{
            return Redirect::route('admin-order-edit', $order->id);
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//
	}


	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy(Order $order)
	{
            if ( OrdersController::checkAdminOrOrderUser($order) ) {
                $order->orderItems()->delete();
                $order->delete();
                // Empty cart, just in case it is still populated.
                return Redirect::route('cart-empty');
            }
	}
        
        public function adminOrderSave(Customer $customer, Order $order = NULL) {
            
            // Set flag indicating if we are creating a new order or
            // updating an existing order.
            $isCreateAction = FALSE;
            if ( is_null($order) ) $isCreateAction = TRUE;
            
            // First, save the order header.
            if ( $isCreateAction ) $order = new Order();
            
            $order->delivery_terms = Input::get('shipping_option');
            $order->order_notes = Input::get('order_notes');
            if ( $order->order_notes == 'Order Notes' ) $order->order_notes = NULL;
            $order->order_status = Input::get('order_status');
            $order->customer_id = $customer->id;
            if ( $isCreateAction ) {
                $order->order_date = date('Y-m-d');
                $order->online_order_ind = FALSE;
                $order->save();
            }
            
            $this->order_id = $order->id;
            $this->customer_id = $order->customer->id;                    
            
            $formIdList = Input::get('form_id');
            $qtyList = Input::get('qty');
            
            //$now = Carbon::now('utc')->toDateTimeString();
            $orderItemList = array();
            
            for ( $i = 0; $i < count($formIdList); $i++ ) {
                if ( empty($formIdList[$i]) ) break;
                $formIdList[$i] = strtoupper($formIdList[$i]);
                if ( !in_array(substr($formIdList[$i], 0, 1), array('C', 'D'))
                        && !strstr($formIdList[$i], 'SET') ) {
                    $formIdList[$i] = 'CD' . str_pad($formIdList[$i], 2, '0', STR_PAD_LEFT);
                }
                $query = Product::where('form_id', '=', $formIdList[$i]);
                $query->where('workshop_year', '=', Config::get('workshop.current_workshop_year'));
                $product = $query->get()->first();
                
                // Do bulk database insert from array for better performance.
                if ( $product && $product->id > 0 ) {
                    $orderItemList[] = array('product_id' => $product->id,
                                                'order_id' => $order->id,
                                                'qty' => $qtyList[$i],
                                                'mp3_ind' => FALSE);
                }
                
            }
            
            // Delete existing order items, if any,
            // if this is an 'update' action.
            if ( !$isCreateAction ) {
                $items = array();
                foreach ( $order->orderItems as $item ) {
                    $items[] = $item->id;
                }
                OrderItem::destroy($items);
            }
            
            // Bulk insert!
            OrderItem::insert($orderItemList);
            
            // We must get charges AFTER adding/inserting the order items.
            $order = OrdersController::getOrderCharges($order);
            $override_values = array(
                    'subtotal_amt' => Input::get('subtotal_amt'),
                    'shipping_charge' => Input::get('shipping_charge'),
                    'discounts' => Input::get('discounts'),
                    'order_total' => Input::get('order_total'),
                );
            if ( !empty($override_values['subtotal_amt']) )
                $order->subtotal_amt = $override_values['subtotal_amt'];
            if ( !empty($override_values['shipping_charge']) )
                $order->shipping_charge = $override_values['shipping_charge'];
            if ( !empty($override_values['discounts']) )
                $order->discounts = $override_values['discounts'];
            if ( !empty($override_values['order_total']) )
                $order->order_total = $override_values['order_total'];

            if ( $order->updateUniques() ) {
                // Re-direct to display the order details.
                return Redirect::route('orders.show', $order->id);
            }
        }
        

	/**
	 * Process the user's order through to payment.
	 *
	 * @param  int  $id
	 * @return Response
	 */        
        public function checkout() {
            // If user is not logged in, then re-direct to log in.
            if ( Auth::guest() ) {
                // Set a session variable to indicate that we are in the
                // checkout process.
                Session::put('checkOutInProgress', TRUE);
                Redirect::route('login');
            }
            
            // If user is logged in, but doesn't have address and is ordering
            // physical media (not MP3s ONLY!), then re-direct to add address.
            if ( Auth::check() ) {
                $query = Address::where('customer_id', '=', Auth::id());
                $addr = $query->get()->first();
                if ( !$addr->id ) {
                    Redirect::route('customers.address.create', array('id' => Auth::id()));
                }
            }
            
            // If everything with customer and address is good, we re-direct
            // to creating the shell order, including choosing shipping method
            // and any notes.
            return Redirect::route('orders.create');
            
            //OrdersController::createShellOrder();
            //OrdersController::persistCart();
        }
        
        public function complete(Order $order) {
            Log::debug('In OrdersController::complete()...  order.id: ' . $order->id);
            
            if ( Input::get('hashkey') ) {
                Session::put('hashkey', urldecode(Input::get('hashkey')));
            }
            
            Log::debug('OrdersController::complete() - After saving hashkey to session: ' . print_r(Session::all(), TRUE));
            
            // Send e-mail of order summary
            // Only send e-mail if order is beyond 'Created' status.
            if ( !$order->email_sent_ind 
                    && $order->order_status <> 'Created' ) {
                $email_result = OrdersController::sendEmailConfirmation($order);
            }
            
            // Display completed order if launched from GUI
            if ( OrdersController::checkAdminOrOrderUser($order) ) {
                return Redirect::route('orders.show', $order->id);
            } else {
                return Redirect::route('login')->with('message', 'Please log in to view order.');
            }            
        }
        
        public function cancel(Order $order) {
            if ( OrdersController::checkAdminOrOrderUser($order) ) {
                return Redirect::route('orders.destroy', $order->id);
            }
        }
        
        public function resendConfirmationEmail(Order $order) {
            if ( OrdersController::checkAdminOrOrderUser($order) ) {
                OrdersController::sendEmailConfirmation($order);
            }
            
            if ( isset($_SERVER['HTTP_REFERER']) ) {
                return Redirect::back()->with('message', 'Confirmation e-mail re-sent.');
            } else {
                return Redirect::route('orders.show', $order->id)->with('message', 'Confirmation e-mail re-sent.');
            }
        }
        
        public function adminOrderCreate(Customer $customer) {
            if ( Utility::isAdminUser() ) {
                $shipping_options = OrdersController::$shipping_options_master;
                $order_status_list = OrdersController::$order_status_list;
                
                $this->layout->content = View::make('orders.admin-create', compact('customer', 'shipping_options', 'order_status_list'))
                                            ->with(array('shipping_charge_note' => ''));
            } else {
                return Redirect::route('login');
            }
        }
        
        public function adminOrderEdit(Order $order) {
            if ( Utility::isAdminUser() ) {
                $shipping_options = OrdersController::$shipping_options_master;
                $order_status_list = OrdersController::$order_status_list;
                $cartContents = OrdersController::convertOrderItemsToCartItems($order->orderItems);
                
                $this->layout->content = View::make('orders.admin-edit', 
                        compact('order', 'shipping_options', 'order_status_list', 'cartContents'))
                        ->with(array('submit_button_label' => 'Update',
                                        'orderVerification' => TRUE,
                                        'shipping_charge_note' => ''));
            }
        }
        
        /**
         * Determine if logged in user is either an administrator
         * or the user who owns the current order.
         * 
	 * @param  Order $order
	 * @return Boolean
         */
        private function checkAdminOrOrderUser(Order $order) {
            
            Log::debug('OrdersController::checkAdminOrOrderUser() - session data: ' . print_r(Session::all(), TRUE));
            Log::debug('OrdersController::checkAdminOrOrderUser() - hash of email address '. $order->customer->email . ': ' . print_r(Hash::make($order->customer->email), TRUE));

            if ( Session::has('hashkey') 
                            && Hash::check($order->customer->email, Session::get('hashkey'))) {
                return TRUE;
            } elseif ( Auth::check() && Customer::find(Auth::id())->admin_ind ) {
                return TRUE;
            } elseif ( Auth::check() && Auth::id() == $order->customer->id ) {
                return TRUE;
            } elseif ( Utility::isAdminUser() ) {
                return TRUE;
            }
            
            return FALSE;
        }
        
        
        
        private function sendEmailConfirmation(Order $order) {
            
            $orderVerification = TRUE;
            $cartContents = OrdersController::convertOrderItemsToCartItems($order->orderItems);
            
            $order->shipping_option_display = OrdersController::$shipping_options_master[$order->delivery_terms];
            
            $order->show_url = route('orders.show', $order->id);
            if ( Session::has('hashkey') ) {
                $order->show_url .= '?hashkey=' . Session::get ('hashkey');
            }
            
            // Since we are using a closure in the Mail::send() method,
            // we must use the 'use' method to pass in parameters array.
            // Reference:  http://forumsarchive.laravel.io/viewtopic.php?id=8264
            $params = array('email' => $order->customer->email,
                            'name' => $order->customer->first_name . ' ' . $order->customer->last_name,
                            'order_id' => $order->id);
            $mail_result = Mail::send('orders.email', compact('cartContents', 'orderVerification', 'order'),
                function($message) use ($params) {
                    $message->to($params['email'], $params['name']);
                    $message->bcc('orders@workshopmultimedia.com');
                    $message->bcc('tdjones74021@yahoo.com');
                    $message->from('orders@workshopmultimedia.com', 'Workshop Multimedia');
                    $message->subject('Workshop Multimedia CD/DVD/MP3 Order #' . $params['order_id']);
                });

            if ( $mail_result ) {
                // Update 'email sent' flag in database.
                $order = Order::find($order->id);
                $order->email_sent_ind = TRUE;
                $order->save();
            } else {
                Log::error('Error sending notification e-mail for order #' . $order->id . ' - Result of send: ' . print_r($mail_result, TRUE));
                return FALSE;
            }
            
            return TRUE;
        }
        
        private function persistCart(Order $order) {
            
            if ( OrdersController::checkAdminOrOrderUser($order)
                    && Cart::totalItems() > 0) {
                $orderItemBatch = array();
                foreach ( Cart::contents() as $item ) {
                    $orderItem = array();
                    $orderItem['order_id'] = $order->id;
                    $orderItem['product_id'] = $item->id;
                    $orderItem['qty'] = $item->quantity;
                    //$orderItemBatch[] = new OrderItem($orderItem);
                    $oi = new OrderItem($orderItem);
                    $oi->save();
                    Log::debug('persistCart - Order->id = ' . $order->id . '  OrderItem->id = ' . $oi->id);
                }
                
                // Empty cart
                Cart::destroy();
            }
            
            return TRUE;
        }
        
        private function getOrderCharges(Order $order) {
            $itemSummary = OrdersController::getCountOfItems();
            
            $order->product_count = $itemSummary['CD']['count']
                    + $itemSummary['DVD']['count']
                    + $itemSummary['MP3']['count'];
            $order->subtotal_amt = $itemSummary['CD']['sub_total_amt']
                    + $itemSummary['DVD']['sub_total_amt']
                    + $itemSummary['MP3']['sub_total_amt'];
            $order->shipping_charge = OrdersController::calculateShipping($order->delivery_terms);
            $order->discounts = OrdersController::calculateDiscounts();
            $order->order_total = ($order->subtotal_amt - $order->discounts) + $order->shipping_charge;
                    
            return $order;
        }
        
        private function createShellOrder() {
            $order = new Order();
            $order->customer_id = Input::get('customer_id');
            $order->order_status = 'Created';
        }
        
        private function getShippingOptions() {
            $shipping_options = array();
            
            $count = OrdersController::getCountOfItems();
            
            if ( $count['CD']['count'] > 0 && $count['DVD']['count'] > 0 ) {
                $shipping_options['ship_together'] = OrdersController::$shipping_options_master['ship_together'];
                $shipping_options['ship_separately'] = OrdersController::$shipping_options_master['ship_separately'];
                
                // If this is before last day to order and pick up at workshop,
                // add that item to the array.
                if ( strtotime(date('Y-m-d')) < strtotime(Config::get('workshop.last_pickup_order_date')) ) {
                    $shipping_options['ship_dvd_only'] = OrdersController::$shipping_options_master['ship_dvd_only'];
                }
            } elseif ( $count['CD']['count'] > 0 ) {
                $shipping_options['ship_cd'] = 'Ship CDs';
                if ( strtotime(date('Y-m-d')) < strtotime(Config::get('workshop.last_pickup_order_date')) ) {        
                    $shipping_options['pickup'] = OrdersController::$shipping_options_master['pickup'];
                }
            } elseif ( $count['DVD']['count'] > 0 ) {
                $shipping_options['ship_dvd'] = OrdersController::$shipping_options_master['ship_dvd'];
            } elseif ( $count['MP3']['count'] > 0 ) {
                $shipping_options['mp3_only'] = OrdersController::$shipping_options_master['mp3_only'];
            }
            
            return $shipping_options;          
        }
        
        private function getCountOfItems($excludeSets = FALSE, $currentWorkshopYearOnly = FALSE) {
            $count = array(
                'CD' => array('count' => 0, 'sub_total_amt' => 0.0),
                'DVD' => array('count' => 0, 'sub_total_amt' => 0.0),
                'MP3' => array('count' => 0, 'sub_total_amt' => 0.0),
            );
            $currentWorkshopYear = Config::get('workshop.current_workshop_year');
            
            // Use shopping cart, if still populated...
            if ( Cart::contents() ) {
                $cartContents = Cart::contents();

/*                foreach ( $cartContents as $cartItem ) {
                    if ( !$currentWorkshopYearOnly || 
                            ( $currentWorkshopYear && $cartItem->workshop_year == $currentWorkshopYear ) ) {
                        if ( $cartItem->prod_type == 'SET' && !$excludeSets ) {
                            if ( substr($cartItem->form_id, 0, 1) == 'C' ) {
                                $count['CD']['count'] += $cartItem->unit_count * $cartItem->quantity;
                                $count['CD']['sub_total_amt'] += $cartItem->price * $cartItem->quantity;
                            } else {
                                $count[substr($cartItem->form_id, 0, 3)]['count'] += $cartItem->unit_count * $cartItem->quantity;
                                $count[substr($cartItem->form_id, 0, 3)]['sub_total_amt'] += $cartItem->price * $cartItem->quantity;
                            }
                        } else {
                            $count[$cartItem->prod_type]['count'] += $cartItem->unit_count * $cartItem->quantity;
                            $count[$cartItem->prod_type]['sub_total_amt'] += $cartItem->price * $cartItem->quantity;
                        }
                    }
                }
 * 
 */
            // ... Otherwise, use order items.
            } else {
                if ( $this->order_id > 0 ) {
                    $orderItems = Order::find($this->order_id)->orderItems;
                    $cartContents = OrdersController::convertOrderItemsToCartItems($orderItems);
                    
/*                    foreach ( $orderItems as $orderItem ) {
                        $product = $orderItem->product();
                        if ( !$currentWorkshopYearOnly || 
                            ( $currentWorkshopYear && $product->workshop_year == $currentWorkshopYear ) ) {
                            if ( $product->prod_type == 'SET' && !$excludeSets ) {
                                if ( substr($product->form_id, 0, 1) == 'C' ) {
                                    $count['CD']['count'] += $product->unit_count * $orderItem->quantity;
                                    $count['CD']['sub_total_amt'] += $product->price * $orderItem->quantity;
                                } else {
                                    $count[substr($product->form_id, 0, 3)]['count'] += $product->unit_count * $orderItem->quantity;
                                    $count[substr($product->form_id, 0, 3)]['sub_total_amt'] += $product->price * $orderItem->quantity;
                                }
                            } else {
                                $count[$product->prod_type]['count'] += $product->unit_count * $orderItem->quantity;
                                $count[$product->prod_type]['sub_total_amt'] += $product->price * $orderItem->quantity;
                            }
                        }
                    }
 * 
 */
                }
            }
            
                foreach ( $cartContents as $cartItem ) {
                    if ( !$currentWorkshopYearOnly || 
                            ( $currentWorkshopYear && $cartItem->workshop_year == $currentWorkshopYear ) ) {
                        if ( $cartItem->prod_type == 'SET' && !$excludeSets ) {
                            if ( substr($cartItem->form_id, 0, 1) == 'C' ) {
                                $count['CD']['count'] += $cartItem->unit_count * $cartItem->quantity;
                                $count['CD']['sub_total_amt'] += $cartItem->price * $cartItem->quantity;
                            } else {
                                $count[substr($cartItem->form_id, 0, 3)]['count'] += $cartItem->unit_count * $cartItem->quantity;
                                $count[substr($cartItem->form_id, 0, 3)]['sub_total_amt'] += $cartItem->price * $cartItem->quantity;
                            }
                        } else {
                            $count[$cartItem->prod_type]['count'] += $cartItem->unit_count * $cartItem->quantity;
                            $count[$cartItem->prod_type]['sub_total_amt'] += $cartItem->price * $cartItem->quantity;
                        }
                    }
                }
            
            return $count;
        }
        
        private function calculateShipping($shipping_option) {
            $numDisks = 0;
            $shippingCharge = 0.0;
            $itemCount = OrdersController::getCountOfItems();
            
            if ( $shipping_option == 'ship_separately' ) {
                $shippingCharge += OrdersController::calculateShippingFee($itemCount['CD']['count']);
                $shippingCharge += OrdersController::calculateShippingFee($itemCount['DVD']['count']);
            } else {
                switch ( $shipping_option ) {
                    case 'ship_together':
                        $numDisks = $itemCount['CD']['count'] + $itemCount['DVD']['count'];
                        break;
                    case 'ship_dvd':
                    case 'ship_dvd_only':
                        $numDisks = $itemCount['DVD']['count'];
                        break;
                    case 'ship_cd':
                        $numDisks = $itemCount['CD']['count'];
                        break;
                }
                
                $shippingCharge = OrdersController::calculateShippingFee($numDisks);
            }
            
            return $shippingCharge;            
        }
        
        private function calculateShippingFee($numDisks) {
            $fee = 0.0;
            
            if ( $numDisks > 0 ) {
                $fee = (float) Config::get('workshop.minimum_shipping_charge') 
                        + ($numDisks - 1) * 1.0;   

                $max_shipping_charge = (float) Config::get('workshop.maximum_shipping_charge');
                if ( $fee > $max_shipping_charge ) {
                    $fee = $max_shipping_charge; 
                }
            }
            
            return $fee;
        }
        
        private function calculateDiscounts() {
            $freeCDDiscount = 0.0;
            $preorderDiscount = 0.0;
            
            //$unit_price_list = array();
            $unit_price_list = Config::get('workshop.unit_price_list');
            
            $itemCount = OrdersController::getCountOfItems(TRUE, TRUE);  // Exclude count of disks from sets and only CDs from current workshop year.
            
            $numberFreeCDs = (int) floor($itemCount['CD']['count']/((float) Config::get('workshop.free_cd_count')));
            $freeCDDiscount = ((float) $unit_price_list['CD']) * $numberFreeCDs;
            
            // Pre-order discount applies ***ONLY*** to CDs/DVDs from current year's workshop!
            if ( strtotime(date('Y-m-d')) < strtotime(Config::get('workshop.last_preorder_discount_date')) ) {
                $preorderDiscount = ((float) Config::get('workshop.preorder_discount')) *
                        (($itemCount['CD']['sub_total_amt'] - $freeCDDiscount)
                            + $itemCount['DVD']['sub_total_amt']
                            + $itemCount['MP3']['sub_total_amt']);
            }
            
            return ($freeCDDiscount + $preorderDiscount);
        }
        
        private function getPaypalAttributes(Order $order) {
            $paypal_attrs = array(
                'cmd'           => '_xclick',
                'charset'       => 'utf-8',
                'currency_code' => 'USD',
                'bn'            => 'WorkshopMultimedia_BuyNow_WPS_US',
                'lc'            => 'US',
                'cn'            => 'Please add any notes or instructions for Workshop Multimedia about your order.',
                'no_shipping'   => 1,
                'rm'            => 0,   // Return method is 'GET'
                'cbt'           => 'Return to Workshop Multimedia to complete order.',
            );            
            
            $paypal_attrs['item_name'] = 'Workshop Multimedia CD/DVD/MP3 Order #' . $order->id;
            $paypal_attrs['item_number'] = $order->id;
            
            $order = OrdersController::getOrderCharges($order);
            $paypal_attrs['amount'] = $order->subtotal_amt;
            $paypal_attrs['discount_amount'] = $order->discounts;
            $paypal_attrs['shipping'] = $order->shipping_charge;
            
            $paypal_attrs['business'] = Config::get('workshop.paypal_acct_email');
                    
            // Attributes for customer and customer address
            // https://developer.paypal.com/webapps/developer/docs/classic/paypal-payments-standard/integration-guide/Appx_websitestandard_htmlvariables/#id08A6HI0J0VU
            // https://developer.paypal.com/webapps/developer/docs/classic/paypal-payments-standard/integration-guide/formbasics/#id08A6F0SJ04Y
            $paypal_attrs['email'] = $order->customer->email;
            $paypal_attrs['first_name'] = $order->customer->first_name;
            $paypal_attrs['last_name'] = $order->customer->last_name;
            
            // We save hash of customer e-mail in the Paypal 'custom' (hidden)
            // attribute.  Then we can check this when user returns from 
            // Paypal to ensure that this is same user.
            $email_hash = Hash::make($order->customer->email);
            $paypal_attrs['custom'] = $email_hash;
            Session::put('email_hash', $email_hash);
            Log::debug('OrdersController::getPaypalAttributes() - hash of email address '. $order->customer->email . ': ' . print_r($email_hash, TRUE));
            
            $paypal_attrs['night_phone_a'] = substr(preg_replace("/[^0-9]/", "", $order->customer->telephone1), 0, 3);
            $paypal_attrs['night_phone_b'] = substr(preg_replace("/[^0-9]/", "", $order->customer->telephone1), 3, 3);
            $paypal_attrs['night_phone_c'] = substr(preg_replace("/[^0-9]/", "", $order->customer->telephone1), 6, 4);
            
            $paypal_attrs['address1'] = $order->customer->address->addr1;
            $paypal_attrs['address2'] = $order->customer->address->addr2;
            $paypal_attrs['city'] = $order->customer->address->city;
            $paypal_attrs['state'] = $order->customer->address->state;
            $paypal_attrs['zip'] = $order->customer->address->postal_code;
            $paypal_attrs['country'] = substr($order->customer->address->country, 0, 2);
            
            // URLs for processing Paypal transaction
            $paypal_attrs['return'] = route('order-complete', array('order' => $order->id, 'hashkey' => $email_hash));
            $paypal_attrs['cancel_return'] = route('order-cancel', $order->id);
            $paypal_attrs['notify_url'] = route('ipn');
                    
            if ( Config::get('app.debug') ) {
                $paypal_attrs['form_action_url'] = 'https://www.sandbox.paypal.com/';
            } else {
                $paypal_attrs['form_action_url'] = 'https://www.paypal.com/';
            }
            
            Log::debug('Paypal attributes for order #' . $order->id . ':  ' . print_r($paypal_attrs, TRUE));
            
            return $paypal_attrs;
        }
        
        /**
         * Convert an individual OrderItem to Cart Item.
         * 
         * @param OrderItem $orderItem
         * @return \stdClass $cartItem
         */
        private function mapOrderItemToCartItem(OrderItem $orderItem) {
            $product = Product::find($orderItem->product_id);
            $cartItemArray = array(
                    'id' => $product->id,
                    'name' => Utility::truncateStringWithEllipsis($product->session_title, 35)
                        . ' - ' . $product->speaker_first_name
                        . ' ' . $product->speaker_last_name
                        . ' - ' . $product->prod_code,
                    'price' => $product->price,
                    'quantity' => $orderItem->qty,
                    'prod_type' => $product->prod_type,
                    'unit_count' => $product->unit_count,
                    'prod_code' => $product->prod_code,
                    'form_id' => $product->form_id,
                    'workshop_year' => $product->workshop_year,
                    'session_title' => $product->session_title, //Utility::truncateStringWithEllipsis($product->session_title, 35),
                    'speaker_name' => $product->speaker_first_name . ' ' . $product->speaker_last_name,  
                );
            
            $cartItem = new stdClass();
            foreach ( $cartItemArray as $key => $value ) {
                $cartItem->$key = $value;
            }
            
            return $cartItem;
        }
        
        /**
         * Convert a list (array) of 'OrderItem' objects into
         * list (array) of Cart Items.
         * 
         * @param array of OrderItem $orderItemArray
         * @return array of Cart Items $cartContents
         */
        private function convertOrderItemsToCartItems($orderItemArray = array()) {
            $cartContents = array();
            foreach ( $orderItemArray as $orderItem ) {
                $cartContents[] = OrdersController::mapOrderItemToCartItem($orderItem);
            }
            
            return $cartContents;
        }
}
