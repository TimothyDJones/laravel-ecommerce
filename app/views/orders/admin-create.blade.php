@section('main')
    <h2>Create Order</h2>
    <h3>{{ $customer->first_name }}&nbsp;{{ $customer->last_name }}</h3>
    
    <div class="row">&nbsp;</div>
    
    {{ Form::open( array('route' => array('admin-order-save', $customer->id),
                'method' => 'POST',
                'class' => 'inline')) }}
        
        <div class="row">                
            {{ Form::submit('Create', array('class' => 'btn btn-primary btn-sm')) }}
        </div>
                
        <div class="row">&nbsp;</div>
        
        <div class="row">
            <div class="col-md-4">
        
                <div class="row">
                    <div class="col-md-6 text-center">Form ID</div>
                    <div class="col-md-6 text-center">Qty</div>
                </div>
                @for ( $i = 0; $i < 15; $i++ )
                    <div class="row">
                        <div class="col-md-6">
                            {{ Form::text('form_id[]', null, array('size' => 1, 'maxlength' => 8, 'class' => 'form-control input-sm',
                                                                                    'data-toggle' => 'tooltip',
                                                                                    'data-placement' => 'top',
                                                                                    'data-original-title' => 'Form ID of product (e.g., "CD01", "D3", etc.).')) }}
                        </div>
                        <div class="col-md-6">
                            {{ Form::text('qty[]', '1', array('size' => 1, 'maxlength' => 2, 'class' => 'form-control input-sm',
                                                                'data-toggle' => 'tooltip',
                                                                'data-placement' => 'right',
                                                                'data-original-title' => 'Desired quantity of this item.',)) }}
                        </div>
                    </div>
                @endfor
            </div>
            
            <div class="col-md-8">
                <div class="col-md-12">
                    
                    @include('orders/partials/_order_options_form')
                    
                    <div class="form-group floating-label-form-group">
                        {{ Form::label('order_status', 'Order Status', array('class' => 'control-label control-label-reqd col-xs-5')) }}
                        <div class="input-group col-xs-6">
                            <span class="input-group-addon"><i class="fa fa-star fa-fw"></i></span> 
                            {{ Form::select('order_status', $order_status_list, NULL, array('class' => 'form-control input-sm input-sm-reqd floatlabel', 'placeholder' => 'Subtotal', )) }}
                        </div>
                    </div>                    
                    
                    <h3>Order Amount Overrides</h3>
                    <p>All charges will be automatically calculated.  <em>Only enter values here if the order form indicates that they need to be overridden.</em></p>
                    <div class="form-group floating-label-form-group">
                        {{ Form::label('subtotal_amt', 'Subtotal', array('class' => 'control-label control-label-reqd col-xs-5')) }}
                        <div class="input-group col-xs-6">
                            <span class="input-group-addon"><i class="fa fa-dollar fa-fw"></i></span> 
                            {{ Form::text('subtotal_amt', null, array('class' => 'form-control input-sm input-sm-reqd floatlabel', 'placeholder' => 'Subtotal', )) }}
                        </div>
                    </div>
                    <div class="form-group floating-label-form-group">
                        {{ Form::label('shipping_charge', 'Shipping & Handling', array('class' => 'control-label control-label-reqd col-xs-5')) }}
                        <div class="input-group col-xs-6">
                            <span class="input-group-addon"><i class="fa fa-dollar fa-fw"></i></span> 
                            {{ Form::text('shipping_charge', null, array('class' => 'form-control input-sm input-sm-reqd floatlabel', 'placeholder' => 'Shipping & Handling', )) }}
                        </div>
                    </div>
                    <div class="form-group floating-label-form-group">
                        {{ Form::label('discounts', 'Discounts', array('class' => 'control-label control-label-reqd col-xs-5')) }}
                        <div class="input-group col-xs-6">
                            <span class="input-group-addon"><i class="fa fa-dollar fa-fw"></i></span> 
                            {{ Form::text('discounts', null, array('class' => 'form-control input-sm input-sm-reqd floatlabel', 'placeholder' => 'Discounts', )) }}
                        </div>
                    </div>
                    <div class="form-group floating-label-form-group">
                        {{ Form::label('order_total', 'Total', array('class' => 'control-label control-label-reqd col-xs-5')) }}
                        <div class="input-group col-xs-6">
                            <span class="input-group-addon"><i class="fa fa-dollar fa-fw"></i></span> 
                            {{ Form::text('order_total', null, array('class' => 'form-control input-sm input-sm-reqd floatlabel', 'placeholder' => 'Total', )) }}
                        </div>
                    </div>
                </div>                 
            </div>
            
        </div>
        
        <div class="row">&nbsp;</div>
        
        <div class="row">
        {{ Form::submit('Create', array('class' => 'btn btn-primary btn-sm',)) }}
        </div>
    
    {{ Form::close() }}
@stop

