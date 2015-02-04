@section('main')
	<h2>Shopping Cart</h2>
        <div class="row">
            @include('products/partials/_cart')
        </div>
        
        <div class="row">
            <div class="form-horizontal pull-right">
                {{ link_to_route('products.index', 'Continue Shopping', NULL, array('class' => 'btn btn-lg btn-primary')) }}
                {{ link_to_route('checkout', 'Checkout', NULL, array('class' => 'btn btn-lg btn-success')) }}
            </div>
        </div>
@stop        