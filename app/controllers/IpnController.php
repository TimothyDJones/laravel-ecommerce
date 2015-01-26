<?php

class IpnController extends \BaseController {

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
            $order = IPN::getOrder();
            
            // After the order is persisted to IPN tables,
            // we redirect to the main order processing passing
            // the txn_id as the key.
            return Redirect::route('orders.complete', array());
	}


}
