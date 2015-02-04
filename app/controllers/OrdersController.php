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
                $this->layout->content = View::make('orders.create', compact('cartContents', 'shipping_options'));
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
                Redirect::route('order.show', $order->id)
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
            //$shipping_
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
	public function destroy($id)
	{
		//
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
        
        private function getCountOfItems() {
            $count = array(
                'CD' => 0,
                'DVD' => 0,
                'MP3' => 0,
            );
            
            // Use shopping cart, if still populated...
            if ( Cart::contents() ) {
                $cartContents = Cart::contents();

                foreach ( $cartContents as $cartItem ) {
                    if ( $cartItem->prod_type == 'SET' ) {
                        if ( substr($cartItem->form_id, 0, 1) == 'C' ) {
                            $count['CD'] += $cartItem->unit_count * $cartItem->quantity;
                        } else {
                            $count[substr($cartItem->form_id, 0, 3)] += $cartItem->unit_count * $cartItem->quantity;
                        }
                    } else {
                        $count[$cartItem->prod_type] += $cartItem->unit_count * $cartItem->quantity;
                    }
                }
            // ... Otherwise, use order items.
            } else {
                if ( $order_id > 0 ) {
                    $orderItems = Order::find($order_id)->orderItems();
                    
                    foreach ( $orderItems as $orderItem ) {
                        $product = $orderItem->product();
                        if ( $product->prod_type == 'SET' ) {
                            if ( substr($product->form_id, 0, 1) == 'C' ) {
                                $count['CD'] += $product->unit_count * $cartItem->quantity;
                            } else {
                                $count[substr($cartItem->form_id, 0, 3)] += $cartItem->unit_count * $cartItem->quantity;
                            }
                        } else {
                            $count[$cartItem->prod_type] += $cartItem->unit_count * $cartItem->quantity;
                        }
                    }
                }
            }
            
            return $count;
        }
}
