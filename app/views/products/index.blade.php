@section('main')
    {{-- @include('items.partials._search') --}}
        <div class="row">
            
            {{ Kint::dump(Cart::contents()) }}
            <!-- Display shopping cart button if it has items. -->
            @if ( Cart::contents() )
                <button class="btn btn-primary btn-lg" data-toggle="modal" data-target="#shoppingCartModal">
                    Show Cart
                </button>
            @endif
            
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
    
        <!-- Modal dialog for shopping cart -->
        <div id="shoppingCartModal" class="modal fade" tabindex="-1" role="dialog" aria-labeledby="shoppingCartModal" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal">
                            <span aria-hidden="true">&times;</span>
                            <span class="sr-only">Close</span>
                        </button>
                        <h4 class="modal-title" id="shoppingCartModalTitle">{{ $modal_title or 'Shopping Cart' }}</h4>
                    </div>
                    <div class="modal-body">
                        <div class="table-responsive">
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>Form ID</th>
                                        <th>Title</th>
                                        <th>Speaker</th>
                                        <th>Qty</th>
                                        <th>Price</th>
                                        <th>Ext. Price</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ( Cart::contents() as $cartItem )
                                    <tr>
                                        <td>{{ $cartItem->form_id }}</td>
                                        <td>{{ $cartItem->session_title }}</td>
                                        <td>{{ $cartItem->speaker_name }}</td>
                                        <td class="table-align-right">{{ $cartItem->quantity }}</td>
                                        <td class="table-align-right">{{ money_format("%.2n", $cartItem->price) }}</td>
                                        <td class="table-align-right">{{ money_format("%.2n", $cartItem->quantity * $cartItem->price) }}</td>
                                    </tr>
                                    @endforeach
                                    <tr>
                                        <td colspan="3" class="table-align-right">Total (not including shipping, if any)</td>
                                        <td class="table-align-right">{{ Cart::totalItems() }}</td>
                                        <td colspan="2" class="table-align-right">{{ money_format("%.2n", Cart::total()) }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        {{ link_to_route('checkout', 'Checkout', NULL, array('class' => 'btn btn-lg btn-success')) }}
                        <button type="button" class="btn btn-primary btn-lg" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
            
        </div>
@stop