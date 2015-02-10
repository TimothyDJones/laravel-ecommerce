<?php

class OrdersController extends \BaseController {
        
        private $order_id = 0;
        private $customer_id = 0;
        
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
                $this->layout->content = View::make('orders.create', compact('cartContents', 'shipping_options'))
                        ->with(array('orderVerification' => FALSE));
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
            
            if ( $order->save() ) {
                Redirect::route('orders.show', $order->id)
                        ->with('message', 'Order created.');
            } else {
                Redirect::route('orders.create')
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
            $order = OrdersController::getOrderCharges($order);
            //$customer = Customer::find($order->customer_id);
            $cartContents = Cart::contents();
            
            $this->layout->content = View::make('orders.show', compact('order', 'cartContents'))->with(array('orderVerification' => TRUE));
	}


	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
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
            $order->orderItems()->delete();
            $order->delete();
            // Empty cart, just in case it is still populated.
            return Redirect::route('cart-empty');
            //return Redirect::route('products');
	}

	/**
	 * Process the user's order through to payment.
	 *
	 * @param  int  $id
	 * @return Response
	 */        
        public function checkout() {
            // If user is not logged in, then re-direct to create account and
            // address, if necessary.
            if ( Auth::guest() ) {
                // Set a session variable to indicate that we are in the
                // checkout process.
                Session::put('checkOutInProgress', TRUE);
                Redirect::route('customers.create');
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
        
        private function persistCart() {
            
        }
        
        private function getOrderCharges(Order $order) {
            $itemSummary = OrdersController::getCountOfItems();
            
            $order->subtotal_amt = $itemSummary['CD']['sub_total_amt']
                    + $itemSummary['DVD']['sub_total_amt']
                    + $itemSummary['MP3']['sub_total_amt'];
            $order->shipping_charge = OrdersController::calculateShipping($order->delivery_terms);
            $order->discounts = OrdersController::calculateDiscounts();
                    
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
            
            if ( $count['CD'] > 0 && $count['DVD'] > 0 ) {
                $shipping_options['ship_together'] = 'Ship CDs and DVDs together';
                $shipping_options['ship_separately'] = 'Ship CDs and DVDs separately';
                
                // If this is before last day to order and pick up at workshop,
                // add that item to the array.
                if ( strtotime(date('Y-m-d')) < strtotime(Config::get('workshop.last_pickup_order_date')) ) {
                    $shipping_options['ship_dvd_only'] = 'Pick up CDs at Workshop/Ship DVDs';
                }
            } elseif ( $count['CD'] > 0 ) {
                $shipping_options['ship_cd'] = 'Ship CDs';
                if ( strtotime(date('Y-m-d')) < strtotime(Config::get('workshop.last_pickup_order_date')) ) {        
                    $shipping_options['pickup'] = 'Pick up CDs at Workshop';
                }
            } elseif ( $count['DVD'] > 0 ) {
                $shipping_options['ship_dvd'] = 'Ship DVDs';
            } elseif ( $count['MP3'] > 0 ) {
                $shipping_options['mp3_only'] = 'MP3s only';
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
            // ... Otherwise, use order items.
            } else {
                if ( $order_id > 0 ) {
                    $orderItems = Order::find($order_id)->orderItems();
                    
                    foreach ( $orderItems as $orderItem ) {
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
                }
            }
            
            return $count;
        }
        
        private function calculateShipping($shipping_option) {
            $numDisks = 0;
            $shippingCharge = 0.0;
            $itemCount = OrdersController::getCountOfItems();
            
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
            
            if ( $shipping_option == 'ship_separately' 
                    && $numDisks > 0) {
                $shippingCharge = OrdersController::calculateShippingFee($numDisks);
            } else {
                $shippingCharge += OrdersController::calculateShippingFee($itemCount['CD']['count']);
                $shippingCharge += OrdersController::calculateShippingFee($itemCount['DVD']['count']);
            }
            
            return $shippingCharge;            
        }
        
        private function calculateShippingFee($numDisks) {
            $fee = 0.0;
            
            $fee = (float) Config::get('workshop.minimum_shipping_charge') 
                    + ($numDisks - 1) * 1.0;   
            
            $max_shipping_charge = (float) Config::get('workshop.maximum_shipping_charge');
            if ( $fee > $max_shipping_charge ) {
                $fee = $max_shipping_charge; 
            }
            
            return $fee;
        }
        
        private function calculateDiscounts() {
            $freeCDDiscount = 0.0;
            $preorderDiscount = 0.0;
            
            $unit_price_list = array();
            $unit_price_list = Config::get('workshop.unit_price_array');
            
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
}
