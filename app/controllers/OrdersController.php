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
                $shipping_charge_note = 'Shipping charges are $1 per disk, with a minimum of $'. Config::get('workshop.minimum_shipping_charge') . ' and maximum of $' . Config::get('workshop.maximum_shipping_charge') . ' per order.';
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
            
            if ( OrdersController::checkAdminOrOrderUser($order) ) {
                $order = OrdersController::getOrderCharges($order);
                $order->shipping_option_display = OrdersController::$shipping_options_master[$order->delivery_terms];
                //$customer = Customer::find($order->customer_id);
                if ( Cart::totalItems() > 0 ) {
                    $cartContents = Cart::contents();
                } else {
                    foreach ( $order->orderItems as $orderItem ) {
                        $cartContents[] = OrdersController::mapOrderItemToCartItem($orderItem);
                    }
                }
                    
                $paypal_attrs = OrdersController::getPaypalAttributes($order);

                $this->layout->content = View::make('orders.show', compact('order', 'cartContents', 'paypal_attrs'))->with(array('orderVerification' => TRUE));
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
            if ( OrdersController::checkAdminOrOrderUser($order) ) {
                
            }
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
            
        }
        
        public function cancel(Order $order) {
            if ( OrdersController::checkAdminOrOrderUser($order) ) {
                return Redirect::route('orders.destroy', $order->id);
            }
        }
        
        /*
         * Determine if logged in user is either an administrator
         * or the user who owns the current order.
         * 
	 * @param  Order $order
	 * @return Response
         */
        private function checkAdminOrOrderUser(Order $order) {
            if ( Customer::find(Auth::id())->admin_ind || Auth::id() == $order->customer->id ) {
                return TRUE;
            }
            
            return FALSE;
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
                    foreach ( $orderItems as $orderItem ) {
                        $cartContents[] = OrdersController::mapOrderItemToCartItem($orderItem);
                    }
                    
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
            $paypal_attrs['return'] = route('order-complete', $order->id);
            $paypal_attrs['cancel_return'] = route('order-cancel', $order->id);
                    
            if ( Config::get('app.debug') ) {
                $paypal_attrs['form_action_url'] = 'https://www.sandbox.paypal.com/';
            } else {
                $paypal_attrs['form_action_url'] = 'https://www.paypal.com/';
            }
            
            Log::debug('Paypal attributes for order #' . $order->id . ':  ' . print_r($paypal_attrs, TRUE));
            
            return $paypal_attrs;
        }
        
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
                    'session_title' => Utility::truncateStringWithEllipsis($product->session_title, 35),
                    'speaker_name' => $product->speaker_first_name . ' ' . $product->speaker_last_name,  
                );
            
            $cartItem = new stdClass();
            foreach ( $cartItemArray as $key => $value ) {
                $cartItem->$key = $value;
            }
            
            return $cartItem;
        }
}
