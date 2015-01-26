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
                                    <tr>
                                        <td colspan="3" class="table-align-right">Total (not including shipping, if any)</td>
                                        <td class="table-align-right">{{ Cart::totalItems() }}</td>
                                        <td colspan="2" class="table-align-right">{{ money_format("%.2n", Cart::total()) }}</td>
                                        <td></td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>