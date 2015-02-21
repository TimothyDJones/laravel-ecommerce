@section('main')
    <h2>Log In To Your Account</h2>
    <p>&nbsp;</p>
    {{ Form::open(array('action' => 'CustomersController@login')) }}
        {{ Form::label('email', 'E-mail Address:') }}
        {{ Form::text('email', null, array('placeholder' => 'E-mail Address')) }}
        {{ Form::label('password', 'Password:') }}
        {{ Form::password('password', null, array('placeholder' => 'Password')) }}
        {{ Form::submit('Log In', array('class' => 'btn btn-primary btn-sm')) }}
    {{ Form::close() }}
    
    @if ( Auth::guest() )
    <h3>
        If you do not have an account, please {{ link_to_route('customers.create', 'click here') }} to create one.
    </h3>
    @endif
@stop