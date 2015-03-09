@section('main')

    <h2>{{ $heading or 'Admin Orders' }}</h2>
    @include('admin/partials/_search_form')
    
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Order ID</th>
                                        <th>Customer Name</th>
                                        <th>E-mail</th>
                                        <th>Address</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ( $orders as $order )
                                    <tr>
                                        <td>{{ link_to_route('orders.show', $order->id, $order->id, array('id' => $order->id )) }}</td>
                                        <td>{{ $order->customer->first_name }}&nbsp;{{ $order->customer->last_name }}</td>
                                        <td>{{ $order->customer->email }}</td>
                                        <td>
                                            {{ $order->customer->address->addr1 }}
                                            @if ( $order->customer->address->addr2 )
                                            <br />{{ $order->customer->address->addr2 }}<br />
                                            @endif
                                            {{ $order->customer->address->city }}, {{ $order->customer->address->state }} {{ $order->customer->address->postal_code }}
                                        </td>
                                        <td>
                                            {{ link_to_route('customers.edit', 'Edit Customer', array('id' => $order->customer->id), array('class' => 'btn btn-info btn-sm')) }}
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>    

@stop