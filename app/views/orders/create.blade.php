@section('main')
	<h2>Create Order</h2>
        
        @include('products/partials/_cart')

    {{ Auth::user()->first_name }}&nbsp;{{ Auth::user()->last_name }}
    
    
    {{ Form::model(new Order, array('route' => array('orders.store'), 'method' => 'post', 'role' => 'form', 'class' => 'form-horizontal')) }}
            <div class='col-xs-9'>
                <div class="form-group floating-label-form-group">
                    {{ Form::label('shipping_option', 'Delivery Method', array('class' => 'control-label control-label-reqd col-xs-5')) }}
                    <div class="input-group col-xs-6">
                        <!--<span class="hovertext">Please enter your first (given) name.<div class="triangle"></div></span> -->
                        <span class="input-group-addon"><i class="fa fa-plane fa-fw"></i></span> 
                        {{ Form::select('shipping_option', $shipping_options, NULL, array('class' => 'form-control input-sm input-sm-reqd floatlabel')) }}
                    </div>
                </div>                
                <div class="form-group floating-label-form-group">
                    {{ Form::label('order_notes', 'Order Notes', array('class' => 'control-label control-label-reqd col-xs-5')) }}
                    <div class="input-group col-xs-6">
                        <!--<span class="hovertext">Please enter your last name (surname).<div class="triangle"></div></span> -->
                        <span class="input-group-addon"><i class="fa fa-file-text-o fa-fw"></i></span>
                        {{ Form::textarea('order_notes', 
                                    'Order Notes',
                                    null, array('class' => 'form-control input-sm input-sm-reqd floatlabel', 
                                            'placeholder' => 'Order Notes', 
                                            'data-label' => 'Please enter any notes or special instructions about your order. (Optional)')) }}
                    </div>
                </div>
            </div>
    {{ Form::close() }}
@stop
