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


}
