@section('main')
    <h2>{{ $heading or 'Edit Order' }}</h2>
    
    @include('orders/partials/_order_details')
    
    <div class="row">&nbsp;</div>
    
    {{ Form::model($order, array('route' => array('admin-order-update', $order->customer->id, $order->id),
                'method' => 'POST',
                'class' => 'inline')) }}
        
    @include('orders/partials/_admin_order_form')
    
@stop

