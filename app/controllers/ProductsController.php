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
            
            ProductsController::makeIndexView($results, array('workshop_year_selected' => $workshop_year));

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
        
        public function home() {
            $this->layout->content = View::make('home');
        }
        
        public function changeWorkshopYear() {
            $workshop_year = Input::get('workshop_year_select');
            return Redirect::to('products/' . $workshop_year);
        }
        
        public function search() {
            $search_year_ind = Utility::nvl(Input::get('search_year_ind'), 'All');
            $input = urldecode(Input::get('search'));
            if ( !empty( $input ) ) {
                $searchTerms = explode(',', $input);
                
                $query = Product::orderBy('workshop_year', 'desc')
                        ->orderBy('id', 'asc');
                // Assign index 0 search terms directly...
                $query->where('speaker_last_name', 'LIKE', '%' . $searchTerms[0] . '%')
                        ->orWhere('speaker_first_name', 'LIKE', '%' . $searchTerms[0] . '%')
                        ->orWhere('session_title', 'LIKE', '%' . $searchTerms[0] . '%');
                foreach ( $searchTerms as $key => $value ) {
                    if ($key > 0)   // Skip index 0; see above
                    {
                        $query->orWhere('speaker_last_name', 'LIKE', '%' . $searchTerms[$key] . '%')
                        ->orWhere('speaker_first_name', 'LIKE', '%' . $searchTerms[$key] . '%')
                        ->orWhere('session_title', 'LIKE', '%' . $searchTerms[$key] . '%');
                    }
                }
                
                $current_workshop_year = Config::get('workshop.current_workshop_year');
                if ( $search_year_ind == 'Current' ) {
                    $query->where('workshop_year', '=', $current_workshop_year);
                }
                
                $results = $query->remember(5)->get();  //->paginate(20);
                
                $message = "Search results for search criteria '" . $input . "'.";
                
                ProductsController::makeIndexView($results, 
                        array('search_criteria' => $input, 
                            'search_year_ind' => $search_year_ind,
                            'message' => $message,
                            ));

            }            
            
        }

	/**
	 * Build display of products from search results, default, etc.
         * 
         * @param $results - array of 'Product' items from search results
	 * @param $params - array of additional view variables to send (e.g.,
         *          search criteria, workshop year selection, etc.)
         * 
	 * @return Response
	 */        
        private function makeIndexView($results, $params) {
            // Paginate the results of the custom query by using 'Paginator::make()'.
            // http://stackoverflow.com/a/23881516
            $paginator = json_decode($results);
            $perPage = 20;
            $page = Input::get('page', 1);
            if ( $page > count($paginator) or $page < 1 ) { $page = 1; }
            $offset = ($page * $perPage) - $perPage;
            $dataSubset = array_slice($paginator, $offset, $perPage);
            $products = Paginator::make($dataSubset, count($paginator), $perPage);
            
            $params['heading'] = 'Product List';
            $params['workshop_year_list'] = ProductsController::getWorkshopYearList();
            $params['orderVerification'] = FALSE;
            $params['mp3_tooltip'] = "Enable/check to order MP3 for $" 
                    . \Config::get('workshop.unit_price_list')['MP3'] 
                    . " instead of CD.";
            if (!isset($params['workshop_year_selected']))
                $params['workshop_year_selected'] = Config::get('workshop.current_workshop_year');
            if (!isset($params['search_year_ind']))
                $params['search_year_ind'] = 'All';
            if (!isset($params['search_criteria']))
                $params['search_criteria'] = NULL;
            
            $this->layout->content = View::make('products.index', compact('products'))->with($params);            
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
            
            Log::debug('Form input array for addToCart(): ' . print_r($input, TRUE));
            
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
                    'session_title' => $product->session_title, //Utility::truncateStringWithEllipsis($product->session_title, 35),
                    'speaker_name' => $product->speaker_first_name . ' ' . $product->speaker_last_name,
                );
                if (Input::has('MP3') && $input['MP3'] === 'mp3') {
                    $cart_item['prod_type'] = 'MP3';
                    $cart_item['quantity'] = 1;
                    $cart_item['price'] = \Config::get('workshop.unit_price_list')['MP3'];
                }
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