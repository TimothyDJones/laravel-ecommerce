        {{ Form::open( array('url' => $paypal_attrs['form_action_url'] . 'cgi-bin/webscr', 'method' => 'POST') ) }}
            {{ Form::hidden('cmd', '_xclick') }}
            {{ Form::hidden('business', Config::get('workshop.paypal_acct_email')) }}
            {{ Form::hidden('cmd', '_xclick') }}
        {{ Form::close() }}

