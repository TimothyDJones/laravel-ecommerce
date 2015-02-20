@section('main')
	<h2>Add Person</h2>

        {{ Form::model(new Person, ['route' => ['persons.store'], 'role' => 'form', 'class' => 'form-horizontal']) }}
            @include('persons/partials/_form', array('submit_button_label' => 'Add'))
	{{ Form::close() }}

@stop
