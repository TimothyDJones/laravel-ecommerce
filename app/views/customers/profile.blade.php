@section('main')
    <h2>Customer Profile</h2>
    <div class="row">
        <div class="col-md-6">
            <h3>Account Details</h3>
            <p>{{ $customer->first_name }} {{ $customer->last_name }}</p>
            <p>{{ $customer->email }}</p>
            <p>{{ $customer->telephone1 }}</p>
            @if ( $customer->telephone2 )
            <p>{{ $customer->telephone2 }}</p>
            @endif
            
            @if ( Auth::id() == $customer->id || (int) $customer->admin_ind > 0 )
            {{ link_to_route('customers.edit', 'Edit', $customer->id, array('class' => 'btn btn-info')) }}
            @endif
        </div>
        @if ( !is_null($customer->address) )
        <div class="col-md-6">
            <h3>Address Details</h3>
            <p>{{ $customer->address->addr1 }}</p>
            @if ( $customer->address->addr2 )
            <p>{{ $customer->address->addr2 }}</p>
            @endif
            <p>{{ $customer->address->city }}, {{ $customer->address->state }} {{ $customer->address->postal_code }} {{ $customer->address->country }}</p>
            
            @if ( Auth::id() == $customer->id || (int) $customer->admin_ind > 0 )
            {{ link_to_route('customers.addresses.edit',
                        'Edit', array($customer->id, $customer->address->id), array('class' => 'btn btn-info')) }}
            @endif            
        </div>
        @endif
    </div>    
@stop

