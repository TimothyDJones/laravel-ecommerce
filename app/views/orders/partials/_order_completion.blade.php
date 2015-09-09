        <h2>Order Completion Confirmation</h2>
        <h3>Workshop Multimedia CD/DVD/MP3 Order #{{ $order->id }}</h3>
        
        @if ( !Utility::isAdminUser() )
        <div class="row">
            <div class="col-md-8 alert alert-info">
                <p>
                    <strong>Thank you for your order!</strong>
                    @if ( $order->delivery_terms == 'mp3_only' )
                    Your payment has been received and your order is complete.
                    Links to download MP3 files are shown below.  <strong>
                    Links are valid for <i>{{ (24 * \Config::get('workshop.mp3_download_link_expiry')) }}
                    hours</i>; please download promptly.
                    </strong>
                    @else
                    Your payment has been received and your order is being 
                    processed.  
                    @endif
                </p>
                <p>
                    If you have any questions about your order, do not hesitate to contact 
                    us by telephone at <strong>(918) 260 9084</strong> or e-mail at 
                    <strong><a href='mailto:orders@workshopmultimedia.com'>orders@workshopmultimedia.com</a></strong>.
                </p>
            </div>
        </div>
        @endif

        @include('orders/partials/_order_details')
        
        @if ( Utility::isAdminUser() )
        <div class="row pull-right">
            {{ link_to_route('resend-order-email', 'Resend E-mail', $order->id, array('class' => 'btn btn-sm btn-danger outline',
                                                                    'data-toggle' => 'tooltip',
                                                                    'data-placement' => 'left',
                                                                    'data-original-title' => 'Resend order confirmation e-mail to customer.')) }}
            &nbsp;
            {{ link_to_route('admin-order-edit', 'Edit', $order->id, array('class' => 'btn btn-sm btn-info',
                                                                    'data-toggle' => 'tooltip',
                                                                    'data-placement' => 'left',
                                                                    'data-original-title' => 'Edit order.')) }}            
        </div>                                                    
        @endif

