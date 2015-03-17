@section('main')

    <h2>{{ $heading or 'Admin Customers' }}</h2>
    @include('admin/partials/_search_form')
    
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Last Name</th>
                                        <th>First Name</th>
                                        <th>E-mail</th>
                                        <th>Address</th>
                                        <th>City</th>
                                        <th>State</th>
                                        <th>Postal Code</th>
                                        <th></th>
                                        <th>Orders</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ( $customers as $customer )
                                    <tr>
                                        <td>{{ $customer->last_name }}</td>
                                        <td>{{ $customer->first_name }}</td>
                                        <td>{{ $customer->email }}</td>
                                        <td>
                                            {{ $customer->address->addr1 }}
                                            @if ( $customer->address->addr2 )
                                            <br />{{ $customer->address->addr2 }}
                                            @endif                                            
                                        </td>
                                        <td>{{ $customer->address->city }}</td>
                                        <td>{{ $customer->address->state }}</td>
                                        <td>{{ $customer->address->postal_code }}</td>
                                        <td>
                                            {{ link_to_route('customers.edit', 'Edit', array('id' => $customer->id), array('class' => 'btn btn-info btn-sm')) }}
                                            <br />
                                            {{ link_to_route('profile', 'Profile', array('id' => $customer->id), array('class' => 'btn btn-warning btn-sm')) }}
                                        </td>
                                        <td>
                                            {{ link_to_route('admin-order-create', 'New Order', array('id' => $customer->id), array('class' => 'btn btn-primary btn-sm')) }}
                                            @foreach ( $customer->orders as $order )
                                            {{ link_to_route('orders.show', $order->id, array('id' => $order->id )) }}<br />
                                            @endforeach
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>    

@stop