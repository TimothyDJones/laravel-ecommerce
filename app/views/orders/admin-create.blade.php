@section('main')
    <h2>{{ $heading or 'Create Order' }}</h2>
    <div class="row">
        @include('orders/partials/_order_customer_info')
    </div>
    
    <div class="row">&nbsp;</div>
    
    {{ Form::open( array('route' => array('admin-order-save', $order->customer->id),
                'method' => 'POST',
                'class' => 'inline')) }}
        
    @include('orders/partials/_admin_order_form')
    
@stop

