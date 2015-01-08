@section('main')
	<h2>Addresses for {{ $customer->first_name }} {{ $customer->last_name }}</h2>
	@if ( !$customer->addresses->count() )
		No addresses!
                <p>{{ link_to_route('customers.addressess.create', 'Create Address', array('customer' => $customer->id)) }}</p>
	@else
		<ul>
			@foreach ( $customer->addresses as $address )
				<li>
                                    {{ Kint::dump($address) }}
					<a href="{{ route('customers.addresses.show', array('customer' => $customer->id, 'address' => $address->id)) }}">Address ID {{ $address->id }}</a>
                                        (
						{{ Form::open(array('class' => 'inline', 'method' => 'DELETE', 'route' => array('customers.addresses.destroy', $customer->id, $address->id ))) }}
							{{ link_to_route('customers.addresses.edit', 'Edit', array('customer' => $customer->id, 'address' => $address->id), array('class' => 'btn btn-info')) }},
							{{ Form::submit('Delete', array('class' => 'btn btn-danger')) }}
						{{ Form::close() }}
					)
                                        
				</li>
			@endforeach
		</ul>
	@endif
	
	<p>{{ link_to_route('customers.create', 'Create Customer') }}</p>
@stop