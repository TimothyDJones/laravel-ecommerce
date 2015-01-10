@section('main')
    {{ Form::open(array('action' => 'CustomersController@login')) }}
        {{ Form::label('email', 'E-mail Address:') }}
        {{ Form::text('email', null, ['placeholder' => 'E-mail Address']) }}
        {{ Form::label('password', 'Password:') }}
        {{ Form::password('password', null, ['placeholder' => 'Password']) }}
        {{ Form::submit('Log In', ['class' => 'btn btn-success']) }}
    {{ Form::close() }}
@stop