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
                <p><strong>Shipping Option</strong>: {{ $order->shipping_option_display }}</p>
                <p><strong>Order Notes</strong>: {{ $order->order_notes }}</p>
            </div>            
        </div>
        <div class="row">
            <h3>Order Summary</h3>
            {{-- @include('products/partials/_cart') --}}
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Form ID</th>
                                        <th>Title</th>
                                        <th>Speaker</th>
                                        <th>Qty</th>
                                        <th>Price</th>
                                        <th>Ext. Price</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ( $cartContents as $cartItem )
                                    <tr>
                                        <td>{{ $cartItem->form_id }}</td>
                                        <td>{{ $cartItem->session_title }}</td>
                                        <td>{{ $cartItem->speaker_name }}</td>
                                        <td class="table-align-right">{{ $cartItem->quantity }}</td>
                                        <td class="table-align-right">{{ money_format("%.2n", $cartItem->price) }}</td>
                                        <td class="table-align-right">{{ money_format("%.2n", $cartItem->quantity * $cartItem->price) }}</td>
                                        <td>
                                            @if ( !$orderVerification )
                                            {{ link_to_route('cart-remove', 'Remove', array('id' => $cartItem->id), array('class' => 'btn btn-info btn-sm')) }}
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                    @if ( $orderVerification == TRUE )
                                    <tr>
                                        <td colspan="3" class="table-align-right">Subtotal</td>
                                        <td class="table-align-right">{{ $order->product_count }}</td>
                                        <td colspan="2" class="table-align-right">{{ money_format("%.2n", $order->subtotal_amt) }}</td>
                                        <td></td>
                                    </tr>
                                        @if ( $order->shipping_charge > 0.0 )
                                        <tr>
                                            <td colspan="3" class="table-align-right">Shipping & Handling</td>
                                            <td colspan="3" class="table-align-right">{{ money_format("%.2n", $order->shipping_charge) }}</td>
                                            <td></td>
                                        </tr>
                                        @endif
                                        @if ( $order->discounts > 0.0 )
                                        <tr>
                                            <td colspan="3" class="table-align-right">Discounts</td>
                                            <td colspan="3" class="table-align-right">({{ money_format("%.2n", $order->discounts) }})</td>
                                            <td></td>
                                        </tr>
                                        @endif
                                    <tr>
                                        <td colspan="3" class="table-align-right">Total</td>
                                        <td colspan="3" class="table-align-right"><strong>{{ money_format("%.2n", $order->order_total) }}</strong></td>
                                        <td></td>
                                    </tr>
                                    @else
                                    <tr class="info">
                                        <td colspan="3" class="table-align-right">Total (<em><strong>not</strong> including shipping and/or discounts, if any</em>)</td>
                                        <td class="table-align-right">{{ Cart::totalItems() }}</td>
                                        <td colspan="2" class="table-align-right">{{ money_format("%.2n", Cart::total()) }}</td>
                                        <td></td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>            
        </div>