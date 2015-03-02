@section('main')
    @if ( Config::get('app.debug') && Session::has('hashkey') )
        {{ Kint::dump( Session::get('hashkey') ) }}
    @endif
    @include('orders/partials/_order_completion')
@stop

