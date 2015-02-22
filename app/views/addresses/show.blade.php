@section('main')
    <h2>Address for {{ $customer->first_name }}&nbsp{{ $customer->last_name }}</h2>
    <h3>{{ $address->addr1 }}</h3>
    <h3>{{ $address->addr2 or "***No 'addr2' data.***" }}</h3>
    <h3>{{ $address->city }}, {{ $address->state }} {{ $address->postal_code }}</h3>
    
    {{ link_to_route('customers.addresses.edit', 'Edit', array($customer->id, $address->id), array('class' => 'btn btn-info')) }}
@stop


