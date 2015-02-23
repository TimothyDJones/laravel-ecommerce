@section('main')
        <h2>Order Confirmation</h2>
        <div class="row">
            <div class="col-md-8 alert alert-info">
                <p>
                    <strong>Thank you for your order!</strong>
                    Please carefully review the order details below.  After you are satisfied that the order is correct, press the <em><strong>Pay Now</strong></em> button.  
                    You will be transferred to the PayPal site to <strong>securely</strong> make your payment.  
                    You do <strong>not</strong> need a PayPal account, as PayPal accepts all major credit cards.  
                    (Of course, you <em>can</em> use a PayPal account, if you prefer.)
                </p>
            </div>
        </div>
        
        {{-- Kint::dump($order) --}}
        
        @include('orders/partials/_order_details')
        <div class="row pull-right">
                    @include('orders/partials/_paypal')
                    &nbsp;            
            {{ Form::open(array('class' => 'inline', 'method' => 'DELETE', 'route' => array('orders.destroy', $order->id))) }}
                    {{ Form::submit('Cancel Order', array('class' => 'btn btn-sm btn-danger outline',
                                                                    'data-toggle' => 'tooltip',
                                                                    'data-placement' => 'top',
                                                                    'data-original-title' => 'Cancel order and remove all items from shopping cart.')) }}
            {{ Form::close() }}
        </div>
@stop

