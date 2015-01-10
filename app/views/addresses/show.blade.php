@section('main')
    <h2>{{ $heading }}</h2>
    <h2>{{ $address->addr1 }}</h2>
    <h2>{{ $address->addr2 or "***No 'addr2' data.***" }}</h2>
    <h2>{{ $address->city, $address->state $address->postal_code }}</h2>
@stop


