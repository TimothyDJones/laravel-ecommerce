        <div class="row">
            <div class="col-xs-6">
                <h3>Ordered By</h3>
                <p>{{ $order->customer->first_name }} {{ $order->customer->last_name }}</p>
                <p>{{ $order->customer->email }}</p>
                <p>{{ $order->customer->address->addr1 }}</p>
                @if ( $order->customer->address->addr2 )
                <p>{{ $order->customer->address->addr2 }}</p>
                @endif
                <p>{{ $order->customer->address->city }}, {{ $order->customer->address->state }} {{ $order->customer->address->postal_code }} {{ $order->customer->address->country }}</p>
            </div>
            <div class="col-xs-6">
                <h3>Order Options</h3>
                <p><strong>Order Number</strong>: {{ $order->id }}</p>
                <p><strong>Shipping Option</strong>: {{ $order->shipping_option_display }}</p>
                <p><strong>Order Notes</strong>: {{ $order->order_notes or '[None]' }}</p>
            </div>            
        </div>
        <div class="clear"></div>
        <div class="row">
            <h3>Order Summary</h3>
            @include('products/partials/_cart')
        </div>