        {{ Form::open( array('class' => 'inline', 'url' => $paypal_attrs['form_action_url'] . 'cgi-bin/webscr', 'method' => 'POST') ) }}
            @foreach ( array_except($paypal_attrs, 'form_action_url') as $key => $value )
            {{ Form::hidden($key, $value, array('id' => $key)) }}{{ "\n" }}
            @endforeach
            {{ Form::submit('Pay Now', array('class' => 'btn btn-lg btn-success',
                                                    'data-toggle' => 'tooltip',
                                                    'data-placement' => 'top',
                                                    'data-original-title' => 'Go to PayPal to securely make payment.')) }}
        {{ Form::close() }} 

