<?php

use GetId3\GetId3Core as GetId3;

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
             if ( !empty( $input ) ) {
                $searchTerms = explode(' ', $input);
                
                $query = Item::orderBy('id', 'desc');
                // Assign index 0 search terms directly...
                $query->where('speaker_name', 'LIKE', '%' . $searchTerms[0] . '%');
                $query->whereOr('session_title', 'LIKE', '%' . $searchTerms[0] . '%');
                foreach ( $searchTerms as $key => $value ) {
                    if ($key > 0)   // Skip index 0; see above
                    {
                        $query->whereOr('speaker_name', 'LIKE', '%' . $searchTerms[$key] . '%');
                        $query->whereOr('session_title', 'LIKE', '%' . $searchTerms[$key] . '%');
                    }
                }
                
                $items = $query->get();  //->paginate(20);
                
                $this->layout->content = View::make('items.index', compact('items'));
             }
             
         }
         
         public function download() {
             $input = Input::get('item');
             Kint::dump($input);
             
             $item = Item::find($input);
             
             /*
              * Notes:
              * 12/19/2010 and before (Session IDs 1 - 386):  On Dot 5 Hosting site at 128kbps.
              * After 12/19/2010 - 04/17/2011 (Session IDs 387 - 430):  On Dot 5 Hosting site at 64kbps.
              * After 04/17/2011 (Session IDs 431 and greater):  On Dreamhost site at 64kbps.
              */
             
             if ( $item->id < 387 ) {
                 $dl_url = 'http://www.workshopmultimedia.com/memorial_drive/' . str_ireplace('.mp3', '', $item->filename_base) . '_128kbps.mp3';
             } elseif ( $item->id < 431 ) {
                 $dl_url = 'http://www.workshopmultimedia.com/memorial_drive/' . str_ireplace('.mp3', '', $item->filename_base) . '_64kbps.mp3';
             } else {
                 $dl_url = 'http://www.workshopmultimedia.net/memorial_drive/' . str_ireplace('.mp3', '', $item->filename_base) . '_64kbps.mp3';
             }
             
             // Update the download count
             $item->num_downloads += 1;
             $item->save();
             
             $id3 = new GetId3();
             $audio = $id3->setOptionMD5Data(TRUE)
                        ->setOptionMD5DataSource(TRUE)
                        ->setEncoding('UTF-8')
                        ->analyze($dl_url);
             
             Kint::dump($audio);
             
             //if ( !isset($audio['error']) )
                return Response::download($dl_url, urlencode( $item->session_title . ' - ' . $item->speaker_name . ' - ' . $item->session_date . '.mp3' ));
         }


}
