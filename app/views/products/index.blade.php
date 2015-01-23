@section('main')
    {{-- @include('items.partials._search') --}}
        <div class="row">
            
            {{ Kint::dump(Cart::contents()) }}
            <div class="items-container">
                <!-- Display "tile" (box) for each item with button to add to cart.  -->
                @foreach ( $products as $product )
                    @if ( (int) $product->available_ind > 0 )
                        <div class="item item-6">
                            <h3>
                                @if ( $product->prod_type == 'DVD' )
                                    <strong>{{ $product->form_id }}</strong>
                                @elseif ( $product->prod_type == 'SET' )
                                    <span style="color: burlywood;"><strong>{{ $product->form_id }}</strong></span>
                                @else
                                    {{ $product->form_id }}
                                @endif
                                &nbsp;&nbsp;{{ $product->workshop_year }}
                            </h3>
                            <h2>{{ $product->session_title }}</h2>
                            <h3>{{ $product->speaker_first_name }} {{ $product->speaker_last_name }}</h3>
                            <h3>Price: ${{ (int) $product->price }} ea.</h3>
                            <div class="form-inline">
                                {{ Form::open(array('route' => 'cart-add', 'method' => 'post', 'role' => 'form', 'class' => 'form-horizontal')) }}
                                    {{ Form::hidden('session_id', $product->id) }}
                                    {{ Form::hidden('session_title', $product->session_title) }}
                                    {{ Form::hidden('speaker_last_name', $product->speaker_last_name) }}
                                    {{ Form::hidden('speaker_first_name', $product->speaker_first_name) }}
                                    {{ Form::hidden('prod_code', $product->prod_code) }}
                                    {{ Form::hidden('prod_type', $product->prod_type) }}
                                    {{ Form::hidden('price', (int) $product->price) }}
                                    {{ Form::text('qty', '1', array('size' => 2, 'maxlength' => 2, 'class' => 'form-control input-sm')) }}
                                    {{ Form::submit('Add to Cart', array('class' => 'btn btn-sm btn-primary pull-right')) }}
                                {{ Form::close() }}
                            </div>
                        </div>
                    @endif
                @endforeach
        
            </div>
                <!-- Display pagination links -->
                @if ( $products->links() )
                <span style="text-align: center;">
                    @if ( $search_criteria )
                        {{ $products->appends(array('search' => $search_criteria))->links() }}
                    @else
                        {{ $products->links() }}
                    @endif
                </span>
                @endif
        </div>
@stop