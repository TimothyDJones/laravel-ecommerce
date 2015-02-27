@section('main')
	<h2>Register Customer</h2>

        {{ Form::model(new Customer, array('route' => ['customers.store'], 'role' => 'form', 'class' => 'form-horizontal')) }}
            @include('customers/partials/_form', array('submit_button_label' => 'Register'))
	{{ Form::close() }}

@stop
