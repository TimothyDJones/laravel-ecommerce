<?php

class ProductsController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index($workshop_year = NULL)
	{
            /*
            if ( !$workshop_year ) {
                $products = Product::paginate(20);
                //$products = Product::all();
            } else { */
                $workshop_year = 2014;   // ***TEMPORARY***
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
            //}
            $this->layout->content = View::make('products.index', compact('products'))->with(array('heading' => 'Product List', 'search_criteria' => NULL));
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
        
        public function calculateDiscount() {
            
        }
        
        public function calculateShipping() {
            
        }
        
        public static function truncateStringWithEllipsis($string, $max_length) {
            if (strlen($string) > ($max_length - 3)) {
                // Truncate string on word or line break.
                $parts = preg_split('/([\s\n\r]+)/', $string, null, PREG_SPLIT_DELIM_CAPTURE);
                $parts_count = count($parts);

                $length = 0;
                $last_part = 0;
                for (; $last_part < $parts_count; ++$last_part) {
                  $length += strlen($parts[$last_part]);
                  if ($length > ($max_length - 3)) { break; }
                }

                $string = implode(array_slice($parts, 0, $last_part)) . '...';
            }
            
            return $string;
        }        
        
        public function addToCart() {
            $input = Input::all();
            
            // Check to see if item is already in cart.
            // Don't add, if it already is.
            if ( !Cart::find($input['session_id']) ) {
                $cart_item = array(
                    'id' => $input['session_id'],
                    'name' => ProductsController::truncateStringWithEllipsis($input['session_title'], 35)
                        . ' - ' . $input['speaker_first_name'] 
                        . ' ' . $input['speaker_last_name']
                        . ' - ' . $input['prod_code'],
                    'price' => $input['price'],
                    'quantity' => $input['qty'],
                    'prod_type' => $input['prod_type'],
                    'prod_code' => $input['prod_code'],
                    'session_title' => ProductsController::truncateStringWithEllipsis($input['session_title'], 35),
                    'speaker_name' => $input['speaker_first_name'] . ' ' . $input['speaker_last_name']
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
        



}
