@section('main')
	<h2>{{ $heading or 'Product List' }}</h2>
	@if ( !$products->count() )
		No products!
	@else
		<ul>
			@foreach ( $products as $product )
				<li>
                                    {{ Kint::dump($product) }}
					<a href="{{ route('products.show', array('product' => $product->id)) }}">Product ID {{ $product->id }}</a>
                                        (
						{{ Form::open(array('class' => 'inline', 'method' => 'DELETE', 'route' => array('products.destroy', $product->id ))) }}
							{{ link_to_route('products.edit', 'Edit', array('product' => $product->id), array('class' => 'btn btn-info')) }},
							{{ Form::submit('Delete', array('class' => 'btn btn-danger')) }}
						{{ Form::close() }}
					)
                                        
				</li>
			@endforeach
		</ul>
                @if ( $products->links() )
                    {{ $products->links() }}
                @endif
	@endif
	
	<p>{{ link_to_route('products.create', 'Create Product') }}</p>
@stop