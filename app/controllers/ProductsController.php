<?php

class ProductsController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index($workshop_year = NULL)
	{
            $current_workshop_year = (int) Config::get('workshop.current_workshop_year');
            /*
            if ( !$workshop_year ) {
                $products = Product::paginate(20);
                //$products = Product::all();
            } else { */
            if ( $workshop_year < 2008  || $workshop_year > $current_workshop_year ) {
                $workshop_year = $current_workshop_year;
            }
            
            // If this is an AJAX request from the workshop year drop-down
            // on the product page, then refresh the display using the new
            // year passed in.
            if ( Input::get('ajax') == 1 ) {
                return Redirect::route('products.index', array('workshop_year' => $workshop_year));
            }
            
            // Get products for the specified workshop year.
            $query = Product::where('workshop_year', '=', $workshop_year);
            $query->orderBy('workshop_year', 'DESC')
                    ->orderBy('id', 'ASC');
            $results = $query->remember(5)->get();

            Log::info('ProductsController@index - Count of query results: ' . $results->count(), array($results));

            // Paginate the results of the custom query by using 'Paginator::make()'.
            // http://stackoverflow.com/a/23881516
            $paginator = json_decode($results);
            $perPage = 20;
            $page = Input::get('page', 1);
            if ( $page > count($paginator) or $page < 1 ) { $page = 1; }
            $offset = ($page * $perPage) - $perPage;
            $dataSubset = array_slice($paginator, $offset, $perPage);
            $products = Paginator::make($dataSubset, count($paginator), $perPage);   
            
            $this->layout->content = View::make('products.index', compact('products'))
                    ->with(array(
                        'heading' => 'Product List', 
                        'search_criteria' => NULL,
                        'workshop_year_selected' => $workshop_year,
                        'workshop_year_list' => ProductsController::getWorkshopYearList(),
                    ));
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		//
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
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
        
        public function changeWorkshopYear() {
            $workshop_year = Input::get('workshop_year_select');
            return Redirect::to('products/' . $workshop_year);
        }
        
        public function showCart() {
            $cartContents = Cart::contents();
            
            $this->layout->contents = View::make('products.show-cart', compact('cartContents'))->with(array('orderVerification' => FALSE));
        }
        
        public function calculateDiscount() {
            
        }
        
        public function calculateShipping() {
            
        }
        
        public function addToCart() {
            $input = Input::all();
            $product = Product::find($input['session_id']);
            
            // Check to see if item is already in cart.
            // Don't add, if it already is.
            if ( !Cart::find($input['session_id']) ) {
                $cart_item = array(
                    'id' => $product->id,
                    'name' => Utility::truncateStringWithEllipsis($product->session_title, 35)
                        . ' - ' . $product->speaker_first_name
                        . ' ' . $product->speaker_last_name
                        . ' - ' . $product->prod_code,
                    'price' => $product->price,
                    'quantity' => $input['qty'],
                    'prod_type' => $product->prod_type,
                    'unit_count' => $product->unit_count,
                    'prod_code' => $product->prod_code,
                    'form_id' => $product->form_id,
                    'workshop_year' => $product->workshop_year,
                    'session_title' => Utility::truncateStringWithEllipsis($product->session_title, 35),
                    'speaker_name' => $product->speaker_first_name . ' ' . $product->speaker_last_name,
                );
                Cart::insert($cart_item);
                
                // Get cart contents and update pop-up (modal) cart window with 
                // full cart details.
                
                $message = 'Item added to cart.';
            } else {
                      
                $message = '<strong>Item already in cart.</strong>';
            }
            // Return user to previous page 
            return Redirect::back()->with('message', $message);
        }
        
        public function getCart() {
            
        }
        
        public function removeFromCart($id) {
            $cartItem = Cart::find($id);
            $cartItem->remove();
            return Redirect::back()->with('message', 'Item removed from cart.');
        }
        
        public function emptyCart() {
            Cart::destroy();
            
            return Redirect::route('products.index')->with('message', 'Shopping cart emptied.');
        }
        
        private function getWorkshopYearList() {
            $list = range(2008, Config::get('workshop.current_workshop_year'));
            $return_list = array();
            /* Use the year value as the key, as well as value, in list. */
            foreach ( $list as $key => $value ) {
                $return_list[$value] = $value;
            }
            
            return $return_list;
        }
        



}