@section('main')
    <h2>{{ $heading }}</h2>
    <h3>{{ $address->addr1 }}</h3>
    <h3>{{ $address->addr2 or "***No 'addr2' data.***" }}</h3>
    <h3>{{ $address->city }}, {{ $address->state }} {{ $address->postal_code }}</h3>
@stop


