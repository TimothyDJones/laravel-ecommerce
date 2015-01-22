@section('main')
    {{-- @include('items.partials._search') --}}
        <div class="row">
            <div class="items-container">
                <!-- Display "tile" (box) for each item with button to add to cart.  -->
                @foreach ( $products as $product )
                    @if ( (int) $product->available_ind > 0 )
                        <div class="item item-6">
                            <h3>{{ $product->form_id }}  {{ $product->workshop_year }}</h3>
                            <h2>{{ $product->session_title }}</h2>
                            <h3>{{ $product->speaker_first_name }} {{ $product->speaker_last_name }}</h3>
                            <h3>Price: ${{ (int) $product->price }} ea.</h3>
                            <div class="item-link">
                                <p>{{-- link_to_route('download', 'Download', array('item' => $item->id)) --}}</p>
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