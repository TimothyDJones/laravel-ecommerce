@section('main')
    <h2 style="background-color: #CCC; margin: 10px 50px 10px 50px; text-align: center; padding: 5px 5px 5px 5px; border-radius: 5px; font-weight: 900;">{{ link_to('products', 'Click here to order CDs and DVDs of sessions from 2015 Tulsa Workshop!') }}</h2>
    <h2>Welcome!</h2>
    
    <h3>Workshop Multimedia welcomes you to our site.</h3>
    <h3><strong>
        @if ( strtotime(date('Y-m-d')) <= strtotime(Config::get('workshop.last_preorder_discount_date')) )
            {{ link_to('products', 'Pre-order') }}
        @else
            {{ link_to('products', 'Order') }}
        @endif
        </strong> 2015 Workshop CDs and DVDs for Extra Discount!
    </h3>
    <p class="lead">We are now taking 
        <strong>
        @if ( strtotime(date('Y-m-d')) <= strtotime(Config::get('workshop.last_preorder_discount_date')) )
            {{ link_to('products', 
                    'pre-orders for CDs of all sessions and DVDs of selected sessions') }}
        @else
            {{ link_to('products', 
                    'orders for CDs of all sessions and DVDs of selected sessions') }}
        @endif
        </strong>, 
        including <em>all</em> 11 AM and 7 PM Pavilion keynote
        sessions, for 2015 Workshop.  
        @if ( strtotime(date('Y-m-d')) <= strtotime(Config::get('workshop.last_preorder_discount_date')) )
        Orders placed <em><mark class="aqua">before the start of Workshop</mark></em> (Wed., March 18, 2015),
        will receive an <mark class="aqua"><em>additional</em> <strong>10%</strong> discount</mark> on all 2015 sessions ordered,
        including sets.
        @endif
    </p>
    <p class="lead">Prices for 2015 Workshop CDs and DVDs are:</p>
        @include('products/partials/_price_table')
    
    <h4>If you have any questions, please contact us via e-mail
        at <a href="mailto:orders@workshopmultimedia.com">
            orders@workshopmultimedia.com</a> or via telephone
            at <strong>(918) 260 9084</strong>.</h4>

@stop

