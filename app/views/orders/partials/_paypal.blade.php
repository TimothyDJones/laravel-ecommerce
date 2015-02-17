        {{ Form::open( array('url' => $paypal_attrs['form_action_url'] . 'cgi-bin/webscr', 'method' => 'POST') ) }}
            @foreach ( array_except($paypal_attrs, 'form_action_url' as $key => $value )
            {{ Form::hidden($key, $value) }}
            @endforeach
            
        {{ Form::close() }}

