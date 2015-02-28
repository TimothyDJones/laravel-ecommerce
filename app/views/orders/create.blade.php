@section('main')
	<h2>Create Order</h2>
        
        @include('products/partials/_cart')
    <div class="row">
        {{ Auth::user()->first_name }}&nbsp;{{ Auth::user()->last_name }}
    </div>
    
    <div class="row">
        {{ Form::model(new Order, array('route' => array('orders.store'), 'method' => 'post', 'role' => 'form', 'class' => 'form-horizontal')) }}
            <div class='col-xs-9'>
                @include ('orders/partials/_order_options_form')
                {{ Form::submit('Submit', array('class' => 'btn btn-primary pull-right')) }}
            </div>
            <div class="col-xs-3">
                <div class="alert alert-info">
                    <p><strong>{{ $shipping_charge_note }}</strong></p>
                </div>
            </div>
        {{ Form::close() }}
    </div>
@stop
