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
                
                $criteria = explode(',', $searchCriteria);
                $query = Order::where('id', '=', $criteria[0]);
                foreach ( $criteria as $key => $value ) {
                    if ( $key > 0 )
                        $query->whereOr('id', '=', $value);
                }
                $query->orderBy('id', 'ASC');
                $orders = $query->remember(5)->get();
                
                Log::debug('Order search results: ' . print_r($orders, TRUE));
                
                if ( count($orders) == 1 ) {
                    return Redirect::route('orders.show', $orders[0]->id);
                } elseif ( count($orders) > 1 ) {
                    $this->layout->content = View::make('admin/orders', compact('orders'));
                } else {
                    return Redirect::back()->with('message', 'No <em><strong>orders</strong></em> found for search criteria: "' . $searchCriteria . '".');
                }                    
            }
            
            return Redirect::back()->with('message', 'Insufficent search criteria.');
        }
        
        public function searchCustomer() {
            
            Log::debug('Entering AdminController::searchCustomer() method... GET array: ' . print_r($_GET, TRUE));

            $searchCriteria = trim(urldecode(Input::get('search_customer')));
            
            if ( strlen($searchCriteria) > 2 ) {
                $customers = array();
                
                $criteria = explode(',', $searchCriteria);
                $query = Customer::where('last_name', 'LIKE', '%' . $criteria[0] . '%');
                $query->whereOr('email', 'LIKE', '%' . $criteria[0] . '%');
                foreach ( $criteria as $key => $value ) {
                    $query->whereOr('last_name', 'LIKE', '%' . $value . '%');
                    $query->whereOr('email', 'LIKE', '%' . $value . '%');
                }
                $query->orderBy('last_name', 'ASC')
                        ->orderBy('first_name', 'ASC')
                        ->orderBy('email', 'ASC');
                $customers = $query->remember(5)->get();
                
                if ( count($customers) == 1 ) {
                    return Redirect::route('profile', $customers[0]->id);
                } elseif ( count($customers) > 1 ) {
                    $this->layout->content = View::make('admin/customers', compact('customers'));
                } else {
                    return Redirect::back()->with('message', 'No <em><strong>customers</strong></em> found for search criteria: "' . $searchCriteria . '".');
                }                    
            }
            
            return Redirect::back()->with('message', 'Insufficent search criteria.');
            
        }
        
        public function showAllCustomers() {
            $customers = Customers::all();
            
            $this->layout->content = View::make('admin/customers', compact('customers'))->with(array('heading' => 'List of All Customers'));
        }


}
