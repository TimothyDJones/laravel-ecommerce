<?php //namespace ;

use Illuminate\Routing\Controller;

class AddressesController extends BaseController {
        
        /**
         * Prevent CSRF for 'POST' actions.
         */
        public function __construct() {
            $this->beforeFilter('csrf', array('on' => 'post'));
        }
        
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index(Customer $customer)
	{
            $address = $customer->address;
            $this->layout->content = View::make('addresses.index', compact('customer'));
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create(Customer $customer)
	{
            if ( $customer->address <> NULL ) {
                return Redirect::route('customers.addresses.show', array($customer->id, $customer->address->id))->with('message', '<strong>Customer already has address.  See below.</strong>');
            } else {
                $this->layout->content = View::make('addresses.create', compact('customer'));
            }
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(Customer $customer)
	{
            $input = array_except(Input::all(), array('_token') );
            $input['customer_id'] = $customer->id;
            $address = new Address($input);
            
            if ( $address->save() ) {
                //return Redirect::route('customers.show', $customer)->with('message', 'Customer created.');
                return Redirect::route('profile', array($customer->id))->with('message', 'Address created.');
            } else {
                //return Redirect::route('customers.create')->withInput()->withErrors( $customer->errors() );
                return Redirect::route('customers.addresses.create', $customer->id)->withInput()->withErrors( $address->errors() );
            }
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  Address $address
	 * @return Response
	 */
	public function show(Customer $customer, Address $address)
	{
            //echo $id;
            //$customer = Customer::findOrFail($id);
            //echo $customer;
            $this->layout->content = View::make('addresses.show', compact('customer', 'address'))->with('heading', 'Show Address');
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit(Customer $customer, Address $address)
	{
            $this->layout->content = View::make('addresses.edit', compact('customer', 'address'))->with('heading', 'Edit Address');
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(Customer $customer, Address $address)
	{
                $input = array_except(Input::all(), '_method');
                $address->fill($input);
                
                if ( $address->updateUniques() ) {
                    return Redirect::route('profile', array($customer->id))
                            ->with('message', 'Address updated.');
                } else {
                    return Redirect::route('customers.addresses.edit', array($customer->id, array_get($address->getOriginal(), 'id')))
                            ->withInput()
                            ->withErrors( $address->errors() );
                }
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
}

