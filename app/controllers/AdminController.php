<?php

class AdminController extends \BaseController {
    
    
        public function __construct() {
          /**
            * Ensure user is 'admin' before doing anything else.
            */
            $this->beforeFilter('admin');
        }

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
            $this->layout->content = View::make('admin/search');
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
        
        public function searchOrder() {
            
            Log::debug('Entering AdminController::searchOrder() method... GET array: ' . print_r($_GET, TRUE));
            
            $searchCriteria = trim(urldecode(Input::get('search_order')));
            
            if ( strlen($searchCriteria) > 2 ) {
                $orders = array();
                
                $criteria = array_map("trim", explode(',', $searchCriteria));
                $query = Order::orderBy('id', 'ASC');
                foreach ( $criteria as $key => $value ) {
                        $query->orWhere('id', '=', $value);
                }
                $orders = $query->remember(5)->get();
                
                Log::debug('Order search results: ' . print_r($orders, TRUE));
                
                if ( count($orders) == 1 ) {
                    return Redirect::route('orders.show', $orders[0]->id);
                } elseif ( count($orders) > 1 ) {
                    $this->layout->content = View::make('admin.orders', compact('orders'));
                } else {
                    return Redirect::back()->with('message', 'No <em><strong>orders</strong></em> found for search criteria: "' . $searchCriteria . '".');
                }                    
            } else {
                return Redirect::back()->with('message', 'Insufficent search criteria.');
            }
        }
        
        public function searchCustomer() {
            
            Log::debug('Entering AdminController::searchCustomer() method... GET array: ' . print_r($_GET, TRUE));

            $searchCriteria = trim(urldecode(Input::get('search_customer')));
            
            Log::debug('AdminController::searchCustomer() - search criteria after extraction: ' . print_r($searchCriteria, TRUE));
            
            if ( strlen($searchCriteria) > 2 ) {
                $customers = array();
                
                $criteria = array_map("trim", explode(',', $searchCriteria));
                
                $query = Customer::orderBy('last_name', 'ASC')
                        ->orderBy('first_name', 'ASC')
                        ->orderBy('email', 'ASC');
                
                // Must use MySQL CONCAT() function to add wildcards to search criteria.
                // See http://blog.mclaughlinsoftware.com/2010/02/21/php-binding-a-wildcard/ for details.
                
                foreach ( $criteria as $key => $value ) {
                    $searchText = strtolower(trim($value));
                    $query->orWhere( function ( $q2 ) use ( $searchText ) {
                        $q2->orWhereRaw( "LOWER(`last_name`) LIKE CONCAT('%',?,'%')", array( $searchText ));
                        $q2->orWhereRaw( "LOWER(`first_name`) LIKE CONCAT('%',?,'%')", array( $searchText ));
                        $q2->orWhereRaw( "LOWER(`email`) LIKE CONCAT('%',?,'%')", array( $searchText ));
                    });
                }
                
                /*
                $raw_query = "SELECT `id` FROM `customers` WHERE ";
                foreach ( $criteria as $key => $value ) {
                    if ( $key > 0 ) $raw_query .= " OR ";
                    $raw_query .= "( LOWER(`last_name`) LIKE CONCAT('%',?,'%') OR ";
                    $raw_query .= " LOWER(`first_name`) LIKE CONCAT('%',?,'%') OR ";
                    $raw_query .= " LOWER(`email`) LIKE CONCAT('%',?,'%') ) ";
                    //$raw_query .= "( LOWER(`last_name`) LIKE '%" . $value . "%' OR ";
                    //$raw_query .= " LOWER(`first_name`) LIKE '%" . $value ."%' OR ";
                    //$raw_query .= " LOWER(`first_name`) LIKE '%" . $value ."%' ) ";                    
                    //$bind_vars[] = trim($value);
                    //$bind_vars[] = trim($value);
                    //$bind_vars[] = trim($value);
                    for ( $i = 0; $i < 3; $i++ ) $bind_vars[] = trim($value);
                }
                
                $results = DB::select( DB::raw($raw_query), $bind_vars);
                 * 
                 */

                $customers = $query->remember(5)->get();
                
                Log::debug('AdminController::searchCustomer() - query log: ' . print_r(DB::getQueryLog(), TRUE));
                
                if ( count($customers) == 1 ) {
                    return Redirect::route('profile', $customers[0]->id);
                } elseif ( count($customers) > 1 ) {
                    //$this->layout->content = View::make('admin.temp');
                    $this->layout->content = View::make('admin.customers', compact('customers'))
                            ->with('message', 'Found ' . count($customers) . ' <em><strong>customers</strong></em> for search criteria: "' . $searchCriteria . '".');
                } else {
                    return Redirect::back()->with('message', 'No <em><strong>customers</strong></em> found for search criteria: "' . $searchCriteria . '".');
                }                    
            } else {
                return Redirect::back()->with('message', 'Insufficent search criteria.');
            }
            
        }
        
        public function showAllCustomers() {
            $customers = Customers::all();
            
            $this->layout->content = View::make('admin/customers', compact('customers'))->with(array('heading' => 'List of All Customers'));
        }


}
