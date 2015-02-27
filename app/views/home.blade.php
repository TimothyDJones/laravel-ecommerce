@section('main')

    <h2>Welcome!</h2>
    <h3>Workshop Multimedia welcomes you to our site.</h3>
    <h3><strong>{{ link_to('products', 'Pre-order') }}</strong> 2015 Workshop CDs and DVDs for Extra Discount!</h3>
    <p class="lead">We are now taking 
        <strong>{{ link_to('products', 
                    'pre-orders for CDs of all sessions and DVDs of selected sessions') }}</strong>, 
        including <em>all</em> 11 AM and 7 PM Pavilion keynote
        sessions, for 2015 Workshop.  Orders placed <em><mark class="aqua">before the start of Workshop</mark></em> (Wed., March 18, 2015),
        will receive an <mark class="aqua"><em>additional</em> <strong>10%</strong> discount</mark> on all 2015 sessions ordered,
        including sets.</p>
    <p class="lead">Prices for 2015 Workshop CDs and DVDs are:</p>
        @include('products/partials/_price_table')
    
    <h4>If you have any questions, please contact us via e-mail
        at <a href="mailto:orders@workshopmultimedia.com">
            orders@workshopmultimedia.com</a> or via telephone
            at <strong>(918) 260 9084</strong>.</h4>

@stop

