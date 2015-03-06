@section('main')
    <h2>Create Order</h2>
    <h3>{{ $customer->first_name }}&nbsp;{{ $customer->last_name }}</h3>
    
    <div class="row">&nbsp;</div>
    
    {{ Form::open( array('route' => array('admin-order-save', $customer->id),
                'method' => 'POST',
                'class' => 'inline')) }}
        
        <div class="row">
        @include('orders/partials/_order_options_form')
                
        {{ Form::submit('Create', array('class' => 'btn btn-primary btn-sm')) }}
        </div>
                
        <div class="row">&nbsp;</div>
        
        <div class="row">
            <div class="col-md-2 text-center">Form ID</div>
            <div class="col-md-2 text-center">Qty</div>
        </div>
        @for ( $i = 0; $i < 15; $i++ )
            <div class="row">
                <div class="col-md-2">
                    {{ Form::text('form_id[]', null, array('size' => 1, 'maxlength' => 8, 'class' => 'form-control input-sm',
                                                                            'data-toggle' => 'tooltip',
                                                                            'data-placement' => 'top',
                                                                            'data-original-title' => 'Form ID of product (e.g., "CD01", "D03", etc.).')) }}
                </div>
                <div class="col-md-2">
                    {{ Form::text('qty[]', '1', array('size' => 1, 'maxlength' => 2, 'class' => 'form-control input-sm',
                                                        'data-toggle' => 'tooltip',
                                                        'data-placement' => 'right',
                                                        'data-original-title' => 'Desired quantity of this item.',)) }}
                </div>
            </div>
        @endfor
        
        <div class="row">&nbsp;</div>
        
        <div class="row">
        {{ Form::submit('Create', array('class' => 'btn btn-primary btn-sm',)) }}
        </div>
    
    {{ Form::close() }}
@stop

