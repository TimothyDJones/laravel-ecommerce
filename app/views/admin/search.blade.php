@section('main')

    <div class="row">
        <div class="col-md-10">
            <h2>Admin Search</h2>
            @include('admin/partials/_search_form')
        </div>
        
        <div class="col-md-2">
            @include('admin/partials/_add_customer_order')
        </div>
    </div>

@stop

