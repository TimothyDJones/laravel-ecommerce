@section('main')
    <h2>Create Order</h2>
    <h3>{{ $customer->first_name }}&nbsp;{{ $customer->last_name }}</h3>
    
    {{ Form::open( array('action' => array('OrdersController@adminOrderSave', $customer->id),
                'method' => 'POST',
                'class' => 'inline')) }}
                
        @include('orders/partials/_order_options_form')
                
        {{ Form::submit('Create', array('class' => 'btn btn-primary btn-sm')) }}
    
        @for ( $i = 0; $i < 15; $i++ )
            <div class="row">
                <div class="col-md-2">
                    {{ Form::text('form_id[]', null, array('size' => 8, 'maxlength' => 8, 'class' => 'form-control input-sm')) }}
                </div>
                <div class="col-md-2">
                    {{ Form::text('qty[]', '1', array('size' => 2, 'maxlength' => 2, 'class' => 'form-control input-sm')) }}
                </div>
            </div>
        @endfor    
    
        {{ Form::submit('Create', array('class' => 'btn btn-primary btn-sm')) }}
    
    {{ Form::close() }}
@stop

