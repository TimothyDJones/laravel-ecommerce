@section('main')
        <h2>Order Confirmation</h2>
        <p>Please review your order below.  After you are satisfied that the order is correct, press the <span class="btn btn-success">Make Payment</span> button.  You will be transferred to the PayPal site to <strong>securely</strong> make your payment.  You do <strong>not</strong> need a PayPal account, as PayPal accepts all major credit cards.  (Of course, you can use a PayPal account, if you prefer.)</p>

        {{ Kint::dump($order) }}
        
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
                <p><strong>Shipping Option</strong>: {{ $order->delivery_terms }}</p>
                <p><strong>Order Notes</strong>: {{ $order->order_notes }}</p>
            </div>            
        </div>
        <div class="row">
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
                                            {{ link_to_route('cart-remove', 'Remove', array('id' => $cartItem->id), array('class' => 'btn btn-info btn-sm')) }}
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
        <div>
            {{ Form::open(array('class' => 'inline pull-right', 'method' => 'DELETE', 'route' => array('orders.destroy', $order->id))) }}
                    {{ link_to_route('make-payment', 'Make Payment', NULL, array('class' => 'btn btn-success')) }}<i class="fa fa-paypal fa-lg"></i>&nbsp;
                    {{ Form::submit('Cancel Order', array('class' => 'btn btn-sm btn-danger')) }}<i class="fa fa-trash-o fa-lg"></i>
            {{ Form::close() }}
        </div>
@stop

