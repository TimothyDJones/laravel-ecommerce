@section('main')

    <h2>{{ $heading or 'Admin Orders' }}</h2>
    @include('admin/partials/_search_form)
    
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
                                        <td>{{ link_to_route('orders.show', $order->id, array('id' => $order->id )) }}</td>
                                        <td>{{ $customer->first_name }}&nbsp;{{ $customer->last_name }}</td>
                                        <td>{{ $customer->email }}</td>
                                        <td>
                                            {{ $customer->address->addr1 }}
                                            @if ( $customer->address->addr2 )
                                            <br />{{ $customer->address->addr2 }}<br />
                                            @endif
                                            {{ $customer->address->city }}, {{ $customer->address->state }} {{ $customer->address->postal_code }}
                                        </td>
                                        <td>
                                            {{ link_to_route('customers.edit', 'Edit Customer', array('id' => $customer->id), array('class' => 'btn btn-info btn-sm')) }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>    

@stop