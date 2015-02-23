        <h2>Order Completion Confirmation</h2>
        <h3>Workshop Multimedia CD/DVD/MP3 Order #{{ $order->id }}</h3>
        <div class="row">
            <div class="col-md-8 alert alert-info">
                <p>
                    <strong>Thank you for your order!</strong>
                    Your payment has been received and your order is being processed.  Details of your order are shown below.
                </p>
                <p>
                    If you have any questions about your order, do not hesitate to contact 
                    us by telephone at <strong>(918) 260 9084</strong> or e-mail at 
                    <strong><a href='mailto:orders@workshopmultimedia.com'>orders@workshopmultimedia.com</a></strong>.
                </p>
            </div>
        </div>

        @include('orders/partials/_order_details')
        
        <div class="row">
            <p>Regards,</p>
            <p><a href="http://www.workshopmultimedia.com">Workshop Multimedia</a></p>
        </div>
        
        @if ( Session::get('AdminUser') )
        <div class="row pull-right">
            {{ link_to_route('resend-order-email', 'Resend E-mail', $order->id, array('class' => 'btn btn-sm btn-danger outline',
                                                                    'data-toggle' => 'tooltip',
                                                                    'data-placement' => 'left',
                                                                    'data-original-title' => 'Resend order confirmation e-mail to customer.')) }}
        </div>                                                    
        @endif

