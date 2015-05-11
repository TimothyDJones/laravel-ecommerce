                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Year</th>
                                        <th>Form ID</th>
                                        <th>Type</th>
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
                                        <td>{{ $cartItem->workshop_year }}</td>
                                        <td>{{ $cartItem->form_id }}</td>
                                        <td>{{ $cartItem->prod_type }}</td>
                                        <td>{{ $cartItem->session_title }}</td>
                                        <td>{{ $cartItem->speaker_name }}</td>
                                        <td class="table-align-right">{{ $cartItem->quantity }}</td>
                                        <td class="table-align-right">{{ money_format("%.2n", $cartItem->price) }}</td>
                                        <td class="table-align-right">{{ money_format("%.2n", $cartItem->quantity * $cartItem->price) }}</td>
                                        <td>
                                            @if ( !$orderVerification )
                                            {{ link_to_route('cart-remove', 'Remove', array('id' => $cartItem->id), array('class' => 'btn btn-info btn-sm')) }}
                                            @endif
                                            
                                            @if ( $orderVerification == TRUE && $order->order_status === 'Completed' && $cartItem->prod_type === 'MP3' )
                                            <a href="{{ $cartItem->mp3dlUrl }}" class="btn btn-success btn-sm">
                                                <i class="fa fa-download fa-fw"></i>Download MP3
                                            </a>
                                            {{-- link_to($cartItem->mp3dlUrl, 'Download', array('class' => 'btn btn-success btn-sm')) --}}                                            
                                            @endif
                                        </td>
                                    </tr>
                                    @endforeach
                                    @if ( $orderVerification == TRUE )
                                    <tr>
                                        <td colspan="5" class="table-align-right">Subtotal</td>
                                        <td class="table-align-right">{{ $order->product_count }}</td>
                                        <td colspan="2" class="table-align-right">{{ money_format("%.2n", $order->subtotal_amt) }}</td>
                                        <td></td>
                                    </tr>
                                        @if ( $order->shipping_charge > 0.0 )
                                        <tr>
                                            <td colspan="5" class="table-align-right">Shipping & Handling</td>
                                            <td colspan="3" class="table-align-right">{{ money_format("%.2n", $order->shipping_charge) }}</td>
                                            <td></td>
                                        </tr>
                                        @endif
                                        @if ( $order->discounts > 0.0 )
                                        <tr>
                                            <td colspan="5" class="table-align-right">Discounts</td>
                                            <td colspan="3" class="table-align-right">({{ money_format("%.2n", $order->discounts) }})</td>
                                            <td></td>
                                        </tr>
                                        @endif
                                    <tr>
                                        <td colspan="5" class="table-align-right"><em><strong>Total</strong></em></td>
                                        <td colspan="3" class="table-align-right"><strong>{{ money_format("%.2n", $order->order_total) }}</strong></td>
                                        <td></td>
                                    </tr>
                                    @else
                                    <tr>
                                        <td colspan="5" class="table-align-right">Total (<em><strong>not</strong> including shipping and/or discounts, if any</em>)</td>
                                        <td class="table-align-right">{{ Cart::totalItems() }}</td>
                                        <td colspan="2" class="table-align-right">{{ money_format("%.2n", Cart::total()) }}</td>
                                        <td></td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>