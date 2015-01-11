<?php

class ItemsController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
            $items = Item::orderBy('id', 'DESC')->paginate(20);
            //$items->paginate(20);
            //$items->get();
            //$items = Item::all();
            
            $this->layout->content = View::make('items.index', compact('items'));
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
        
        
        /**
	 * Handle searching by session title and/or speaker name.
	 *
	 * @param  int  $id
	 * @return Response
	 */
        public function search()
         {
             $input = Input::get('search');
             $searchTerms = explode();
             
         }
         
         public function download() {
             $input = Input::get('item');
             Kint::dump($input);
         }


}
