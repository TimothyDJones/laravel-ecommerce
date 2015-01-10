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
        public function profile() {
            
            if (Auth::check())
                //echo Auth::user()->email;
                $this->layout->content = View::make('customers.profile');
            else
                return Redirect::to('login');
            //echo "This is the 'profile' function of CustomersController.";
        }
        
        /**
         * Log user out of system.
         */
        public function logout() {
            Auth::logout();
            
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
                $this->layout->content = View::make('customers.create');
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
            $customer = new Customer($input);
            
            if ( $customer->save() ) {
                return Redirect::route('customers.addresses.create', $customer->id)->with('message', 'Customer created.');
                //return Redirect::route('customers.show', $customer->id);
            } else {
                //return Redirect::route('customers.create')->withInput()->withErrors( $customer->errors() );
                return Redirect::route('customers.create')->withInput()->withErrors( $customer->errors() );
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
            $this->layout->content = View::make('customers.show', compact('customer'))->with('heading', 'Show Customer');
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

    
}
