<?php

class PersonController extends \BaseController {

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
            $this->layout->content = View::make('persons.create');
	}


	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
            $input = array_except(Input::all(), array('_token') );
            $validator = Validator::make($input, Person::$validation_rules);
            
            if ( $validator->passes()
                    && ($input['password'] === $input['password_confirmation'])) {
                $person = new Person($input); //new Customer(array_except($input, array('password_confirmation')));
                $person->password = Hash::make(Input::get('password'));
                
                if ( $person->save() ) {
                    return Redirect::route('persons.show', $person->id)
                            ->with('message', 'Person created.');
                } else {
                    return Redirect::route('persons.create')
                            ->withInput()
                            ->withErrors( $person->errors() )
                            ->with(array('message' => 'Error with saving.'));
                }
                //return Redirect::route('customers.show', $customer->id);
            } else {
                //return Redirect::route('customers.create')->withInput()->withErrors( $customer->errors() );
                return Redirect::route('persons.create')
                        ->withInput()
                        ->withErrors( $validator->errors() )
                        ->with(array('message' => 'Error with validation.'));
            }            
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


}
