@section('main')
	<h2>{{ $heading or 'Edit Address' }}</h2>
        @if ($customer)
            <h3>{{ $customer->first_name }} {{ $customer->last_name }}</h3>
        @endif

        {{ Form::model($address, ['method' => 'PATCH', 'route' => ['customers.addresses.update', $customer->id, $address->id], 'role' => 'form', 'class' => 'form-horizontal']) }}
            @include('addresses/partials/_form', array('submit_button_text' => 'Update'))
	{{ Form::close() }}
@stop
