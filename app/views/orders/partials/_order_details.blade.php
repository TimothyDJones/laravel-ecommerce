        <div class="row">
            @include('orders/partials/_order_customer_info')
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
