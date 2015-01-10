@section('main')
    <h2>Hello, {{ Auth::getUser()->first_name }} {{ Auth::getUser()->last_name }}.</h2>
    <p>Welcome to the default profile page.</p>
@stop

