@section('main')
	<h2>Create Address</h2>
        @if ($customer)
            <h3>{{ $customer->first_name }} {{ $customer->last_name }}</h3>
        @endif

        {{ Form::model(new Address, ['route' => ['customers.addresses.store', $customer->id], 'role' => 'form', 'class' => 'form-horizontal']) }}
            @include('addresses/partials/_form', array('submit_button_text' => 'Submit'))
	{{ Form::close() }}
@stop
