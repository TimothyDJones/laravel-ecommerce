@section('main')

    {{-- @include('products.partials._workshop_year_select') --}}
        <!-- jQuery script to auto-update product list when workshop year drop-down is changed. -->
    <script type="text/javascript">
        $(document).ready(
            function() {
                $("#workshop_year_select").change(
                    function() {
                        var year_text = $("#workshop_year_select").text();
                        var year_val = $("#workshop_year_select").val();
                        //alert('change fired: ' + year_text + '\n' + year_val);
                        var url = "http://localhost:8080/products/" + year_val + "?ajax=1";
                        //alert('get URL: ' + url);
                        $.get(url);
                    });
            // Display tooltip on hover.
            $('[data-toggle="tooltip"]').tooltip();
            });

    </script>
    <div class="row">
    {{ Form::open(array('route' => 'year-update', 'method' => 'post', 'role' => 'form', 'class' => 'form-inline')) }}
    <div class="form-group floating-label-form-group">
        {{ Form::label('workshop_year_select', 'Display Items for Workshop Year', array('class' => 'control-label control-label-reqd ')) }}
        <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-calendar-o fa-fw"></i></span>
            {{ Form::select('workshop_year_select', $workshop_year_list, $workshop_year_selected, array('class' => 'form-control input-sm input-sm-reqd floatlabel')) }}
        </div>
    </div>
     {{ Form::submit('Change Year', array('class' => 'btn btn-primary')) }}
    {{ Form::close() }}
    </div>
           
            {{ Kint::dump(Cart::contents()) }}
            <!-- Display shopping cart button if it has items. -->
            @if ( Cart::contents() )
            <div class="row">
                <div class="col-xs-4">
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
                <div class="form-horizontal pull-right">
                    <button class="btn btn-primary btn-lg" data-toggle="modal" data-target="#shoppingCartModal">
                        <i class="fa fa-shopping-cart fa-fw"></i>
                        Show Cart <span class="badge">&nbsp;${{ money_format("%.2n", Cart::total()) }}&nbsp;({{ Cart::totalItems() }})&nbsp;</span>
                    </button>
                    @if ( Auth::user() )
                    {{ link_to_route('checkout', 'Check Out', NULL, array('class' => 'btn btn-lg btn-success')) }}
                    @else
                    {{ link_to_route('login', 'Log In', NULL, array('class' => 'btn btn-lg btn-primary')) }}
                    @endif
                    {{ link_to_route('cart-empty', 'Empty Cart', NULL, array('class' => 'btn btn-primary btn-lg')) }}
                </div>
            </div>
            @endif
            
        <div class="row">    
            <div class="items-container">
                <!-- Display "tile" (box) for each item with button to add to cart.  -->
                @foreach ( $products as $product )
                    @if ( (int) $product->available_ind > 0 )
                        @if ( $product->prod_type == 'DVD' )
                        <div class="item item-15">
                        @elseif ( $product->prod_type == 'SET' )
                        <div class="item item-16">
                        @else
                        <div class="item item-14">
                        @endif
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
                                    {{ Form::submit('Add', array('class' => 'btn btn-sm btn-primary pull-right',
                                                                            'data-toggle' => 'tooltip',
                                                                            'data-placement' => 'bottom',
                                                                            'data-original-title' => 'Add item to shopping cart.')) }}
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
                        @include('products/partials/_cart', array('cartContents' => Cart::contents()))
                    </div>
                    <div class="modal-footer">
                        @if ( Auth::user() )
                        {{ link_to_route('checkout', 'Check Out', NULL, array('class' => 'btn btn-lg btn-success')) }}
                        @else
                        {{ link_to_route('login', 'Log In', NULL, array('class' => 'btn btn-lg btn-primary')) }}
                        @endif
                        {{ link_to_route('cart-empty', 'Empty Cart', NULL, array('class' => 'btn btn-primary btn-lg')) }}
                        <button type="button" class="btn btn-primary btn-lg" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
            
        </div>
@stop