@section('main')
	<h2>{{ $heading or 'Create Address' }}</h2>
        @if ($customer)
            <h3>{{ $customer->first_name }} {{ $customer->last_name }}</h3>
        @endif
        <div class="row">
        {{ Form::model(new Address, array('route' => array('customers.addresses.store', $customer->id), 'method' => 'post', 'role' => 'form', 'class' => 'form-horizontal')) }}
            @include('addresses/partials/_form', array('submit_button_text' => 'Submit'))
	{{ Form::close() }}
        </div>
@stop
