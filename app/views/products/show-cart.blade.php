@section('main')
	<h2>Shopping Cart</h2>
        <div class="row">
            @include('products/partials/_cart')
        </div>
        
        <div class="row">
            <div class="form-horizontal pull-right">
                {{ link_to_route('products.index', 'Continue Shopping', NULL, array('class' => 'btn btn-lg btn-primary')) }}
                @if ( Auth::user() )
                {{ link_to_route('checkout', 'Check Out', NULL, array('class' => 'btn btn-lg btn-success')) }}
                @else
                {{ link_to_route('login', 'Log In', NULL, array('class' => 'btn btn-lg btn-primary')) }}
                @endif
                {{ link_to_route('cart-empty', 'Empty Cart', NULL, array('class' => 'btn btn-primary btn-lg')) }}
            </div>
        </div>
@stop        