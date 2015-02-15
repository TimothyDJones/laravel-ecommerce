@section('main')
	<h2>Edit Customer</h2>

        {{ Form::model($customer, array('method' => 'PATCH', 'route' => array('customers.update', $customer->id), 'role' => 'form', 'class' => 'form-horizontal')) }}
            @include('customers/partials/_form', array('submit_button_label' => 'Update'))
	{{ Form::close() }}

@stop
