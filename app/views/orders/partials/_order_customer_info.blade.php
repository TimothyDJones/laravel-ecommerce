            <div class="col-xs-6">
                <h3>Ordered By</h3>
                <p>{{ $order->customer->first_name }} {{ $order->customer->last_name }}</p>
                <p>{{ $order->customer->email }}</p>
                <p>{{ $order->customer->telephone1 }}
                    @if ( $order->customer->telephone2 )
                        / {{ $order->customer->telephone2 }}
                    @endif
                </p>
                <p>{{ $order->customer->address->addr1 }}</p>
                @if ( $order->customer->address->addr2 )
                <p>{{ $order->customer->address->addr2 }}</p>
                @endif
                <p>{{ $order->customer->address->city }}, {{ $order->customer->address->state }} {{ $order->customer->address->postal_code }} {{ $order->customer->address->country }}</p>
            </div>