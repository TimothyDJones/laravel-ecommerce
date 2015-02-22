<?php

class IpnController extends \BaseController {

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
            Log::debug('IPN receiver - $_POST array: ' . print_r($_POST, TRUE));
            $ipnOrder = IPN::getOrder();
            
            Log::debug('IPN receiver - After processing... IPN order: ' . print_r($ipnOrder, TRUE));
            
            $orderItem = $ipnOrder->items->first();
            
            $order = Order::find((int) $orderItem->item_number);
            $order->paypal_txn_id = $ipnOrder->txn_id;
            $order->paypal_fee = $ipnOrder->mc_fee;
            $order->ipn_order_id = $ipnOrder->id;
            //$order->id = (int) $orderItem->item_number;
            if ( $ipnOrder->memo ) $order->order_notes .= "\n\n" . $ipnOrder->memo;
            $order->order_status = 'Payment Received';
            $order->save();
            
            // After the order is persisted to IPN tables,
            // we redirect to the main order processing passing
            // the txn_id as the key.
            return Redirect::route('orders-complete', $order->id);
	}


}
