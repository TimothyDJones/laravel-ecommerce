@section('main')
    @if ( Auth::guest() )
    <div class="row">
        <div class="col-md-6">&nbsp;
        </div>
        <div class="col-md-4 div-grey">
             <h4>If you already have an account, please
             {{ link_to_route('login', 'click here') }}
             to log in.
             </h4>
        </div>
    </div>
    @endif
    <div class="row">
        <h2>Register Customer</h2>
    </div>

        {{ Form::model(new Customer, array('route' => ['customers.store'], 'role' => 'form', 'class' => 'form-horizontal')) }}
            @include('customers/partials/_form', array('submit_button_label' => 'Register'))
	{{ Form::close() }}

@stop
