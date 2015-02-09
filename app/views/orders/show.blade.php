@section('main')
        <h2>Order Confirmation</h2>
        <p>Please review your order below.  After you are satisfied that the order is correct, press the <span class="btn btn-primary">Make Payment</span> button.  You will be transferred to the PayPal site to <strong>securely</strong> make your payment.  You do <strong>not</strong> need a PayPal account, as PayPal accepts all major credit cards.  (Of course, you can use a PayPal account, if you prefer.)</p>
        
        {{ Kint::dump($customer) }}
        {{ Kint::dump($order) }}
        
        <div class="row">
            <div class="col-xs-7">
                <h3>Ordered By</h3>
                {{ $customer->first_name }} {{ $customer->last_name }}
                {{ $customer->email }}
                {{ $customer->address()->addr1 }}
                @if ( $customer->address()->addr2 )
                {{ $customer->address()->addr2 }}
                @endif
                {{ $customer->address()->city }}, {{ $customer->address()->state }} {{ $customer->address()->postal_code }} {{ $customer->address()->country }}
            </div>
            <div class="col-xs-4">
                <h3>Order Options</h3>
                <p><strong>Shipping Option</strong>: {{ $order->delivery_terms }}</p>
                <p><strong>Order Notes</strong>: {{ $order->order_notes }}</p>
            </div>            
        </div>
        <div class="row">
            {{-- @include('products/partials/_cart') --}}
        </div>
        <div>
            {{ link_to_route('make-payment', 'Make Payment', NULL, array('class' => 'btn btn-lg btn-success')) }}
            {{ link_to_route('orders.destroy', 'Cancel Order', NULL, array('class' => 'btn btn-lg btn-danger')) }}
        </div>
@stop

