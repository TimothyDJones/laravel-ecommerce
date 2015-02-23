<?php //namespace ;

use Illuminate\Routing\Controller;

class CustomersController extends BaseController {
        
        /**
         * Prevent CSRF for 'POST' actions.
         */
        public function __construct() {
            $this->beforeFilter('csrf', array('on' => 'post'));
        }
        
        /**
         * Display customer login screen.
         * 
         * @return Response
         */
        public function login() {
            
            if ( Auth::check() ) {
                return Redirect::to('/profile');
            } elseif ( Request::isMethod('post') ) {
                $loginValidator = Validator::make(Input::all(), array(
                    'email'     => 'required',
                    'password'  => 'required',
                ));
                
                if ( $loginValidator->passes() ) {
                    $inputCredentials = array(
                        'email'     => Input::get('email'),
                        'password'  => Input::get('password'),
                    );
                    
                    if ( Auth::attempt($inputCredentials) ) {
                        $customer = Customer::find(Auth::id());
                        if ( $customer->admin_ind ) {
                            Session::put('AdminUser', TRUE);
                        }
                        if ( is_null($customer->address) ) {  // If customer does not have address, redirect to create address.
                            return Redirect::route('customer.address.create', Auth::id())
                                ->with('message', 'No address found for account.  Please enter a valid address.');
                        }
                        return Redirect::intended('profile')->with('message', 'Login successful.');
                    }
                    
                    return Redirect::back()->withInput()->withErrors( array('password' => array('Credentials invalid.')) );
                } else {
                    return Redirect::back()->withInput()->withErrors($loginValidator);
                }
            }
            
            $this->layout->content = View::make('customers.login');
            
        }
        
        /**
         * Display the customer's profile page after successful login.
         */
        public function profile(Customer $customer = NULL) {
            
            if ( is_null($customer) ) {
                if (Auth::check()) {
                    //echo Auth::user()->email;
                    $customer = Customer::find(Auth::id());
                    $this->layout->content = View::make('customers.profile', compact('customer'));
                } else {
                    return Redirect::to('login');
                }
            } else {
                $this->layout->content = View::make('customers.profile', compact('customer'));
            }
        }
        
        /**
         * Log user out of system.
         */
        public function logout() {
            Auth::logout();
            Session::flush();   // Clear *ALL* session data!
            
            return Redirect::to('login')->with('message', 'You have logged out.');
        }
    
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
            $this->layout->content = View::make('customers.index');
	}


	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
            if ( !Auth::check() )
                $this->layout->content = View::make('customers.create')->with('updateFlag', FALSE);
            else
                return Redirect::to('profile')->with('message', 'You are already a customer!');
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
            $input = array_except(Input::all(), array('_token') );
            $validator = Validator::make($input, Customer::$validation_rules);
            
            if ( $validator->passes()
                    && ($input['password'] === $input['password_confirmation'])) {
                $customer = new Customer($input); //new Customer(array_except($input, array('password_confirmation')));
                $customer->password = Hash::make(Input::get('password'));
                
                if ( $customer->save() ) {
                    return Redirect::route('customers.addresses.create', $customer->id)
                            ->with('message', 'Customer created.');
                } else {
                    return Redirect::route('customers.create')
                            ->withInput()
                            ->withErrors( $customer->errors() )
                            ->with(array('message' => 'Error with saving.', 'updateFlag' => FALSE));
                }
                //return Redirect::route('customers.show', $customer->id);
            } else {
                //return Redirect::route('customers.create')->withInput()->withErrors( $customer->errors() );
                return Redirect::route('customers.create')
                        ->withInput()
                        ->withErrors( $validator->errors() )
                        ->with(array('message' => 'Error with validation.', 'updateFlag' => FALSE));
            }
	}


	/**
	 * Display the specified resource.
	 *
	 * @param  Customer $customer
	 * @return Response
	 */
	public function show(Customer $customer)
	{
            //echo $id;
            //$customer = Customer::findOrFail($id);
            //echo $customer;
            //return Redirect::route('profile', array($customer->id));
            $this->layout->content = View::make('customers.profile', compact('customer'))
                    ->with('heading', 'Show Customer');
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit(Customer $customer)
	{
            $this->layout->content = View::make('customers.edit', compact('customer'))->with('updateFlag', TRUE);
	}


	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(Customer $customer)
	{
            $input = array_except(Input::all(), array( '_method', 'password', 'password_confirmation', ) );
            $customer->fill($input);
            $validation_rules = array_except(Customer::$validation_rules, array('password', 'password_confirmation'));
            $validation_rules['email'] = $validation_rules['email'] . ',' . $customer->id;
            Log::debug('customer - update - validation rule', $validation_rules);
            $validator = Validator::make($input, $validation_rules);
            
            if ( $validator->passes() ) {
                if ( $customer->update() )
                    return Redirect::route('customers.show', $customer->id)->with('message', 'Customer updated.');
                else
                    return Redirect::route('customers.edit', array_get($customer->getOriginal(), 'id'))->withInput()->withErrors( $customer->errors() );
            } else {
                return Redirect::route('customers.edit', array_get($customer->getOriginal(), 'id'))->withInput()->withErrors( $validator->errors() );
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
