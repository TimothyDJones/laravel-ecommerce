        <div class="row">                
            {{ Form::submit($order_action, array('class' => 'btn btn-primary btn-sm')) }}
        </div>
                
        <div class="row">&nbsp;</div>
        
        <div class="row">
            <div class="col-md-4">
        
                <div class="row">
                    <div class="col-md-6 text-center">Form ID</div>
                    <div class="col-md-6 text-center">Qty</div>
                </div>
                <div class="row">
                @for ( $i = 0; $i < max( array(15, count($cartContents)) ); $i++ )
                    <div class="row input-qty">
                        @if ( $order_action == 'Update' && $i < count($cartContents) )
                        
                        <div class="col-md-6">
                            {{ Form::text('form_id[]', $cartContents[$i]->form_id, array('size' => 1, 'maxlength' => 8, 'class' => 'form-control input-sm',)) }}
                        </div>
                        <div class="col-md-6">
                            {{ Form::text('qty[]', $cartContents[$i]->quantity, array('size' => 1, 'maxlength' => 2, 'class' => 'form-control input-sm',)) }}
                        </div>
                        
                        @else

                        <div class="col-md-6">
                            {{ Form::text('form_id[]', null, array('size' => 1, 'maxlength' => 8, 'class' => 'form-control input-sm',)) }}
                        </div>
                        <div class="col-md-6">
                            {{ Form::text('qty[]', '1', array('size' => 1, 'maxlength' => 2, 'class' => 'form-control input-sm',)) }}
                        </div>
                        
                        @endif
                    </div>
                @endfor
                </div>
                {{ Form::button('Add Fields', array('class' => 'btn btn-sm btn-primary',
                                                    'onclick' => 'addFormFields()')) }}
            </div>
            
            {{-- Kint::dump($order) --}}
            <div class="col-md-8">
                <div class="col-md-12">
                    
                    <h3>Order Options</h3>
                    <div class="form-group floating-label-form-group">
                        {{ Form::label('shipping_option', 'Delivery Method', array('class' => 'control-label control-label-reqd col-xs-3')) }}
                        <div class="input-group col-xs-6">
                            <span class="input-group-addon"><i class="fa fa-plane fa-fw"></i></span> 
                            {{ Form::select('shipping_option', $shipping_options, $order->delivery_terms, array('class' => 'form-control input-sm input-sm-reqd floatlabel',
                                                                                                'data-toggle' => 'tooltip',
                                                                                                'data-placement' => 'top',
                                                                                                'data-original-title' => $shipping_charge_note)) }}
                        </div>
                    </div>                
                    <div class="form-group floating-label-form-group">
                        {{ Form::label('order_notes', 'Order Notes', array('class' => 'control-label control-label-reqd col-xs-3')) }}
                        <div class="input-group col-xs-6">
                            <span class="input-group-addon"><i class="fa fa-file-text-o fa-fw"></i></span>
                            <textarea id="order_notes" name="order_notes"
                                      style="width: 100%;" rows="4"
                                      class="form-control input-sm input-sm-reqd floatlabel">{{ $order->order_notes }}</textarea>
                        </div>
                    </div>
                    
                    <div class="form-group floating-label-form-group">
                        {{ Form::label('order_status', 'Order Status', array('class' => 'control-label control-label-reqd col-xs-3')) }}
                        <div class="input-group col-xs-6">
                            <span class="input-group-addon"><i class="fa fa-star fa-fw"></i></span> 
                            {{ Form::select('order_status', $order_status_list, $order->order_status, array('class' => 'form-control input-sm input-sm-reqd floatlabel', 'placeholder' => 'Subtotal', )) }}
                        </div>
                    </div>                    
                    
                    <h3>Order Amount Overrides</h3>
                    <p>All charges will be automatically calculated.</p>
                    <p>Click the 'Clear' button to remove current values and enter/update any values which need to be overridden below. 
                        (Values will <em>only</em> be overridden if they are specified (<em>not</em> blank) and the 'Override?' check box is checked/enabled.)</p>
                    {{ Form::button('Clear', array('class' => 'btn btn-outline btn-sm', 'onclick' => 'clearAmounts()')) }}
                    <div class="form-group floating-label-form-group">
                        {{ Form::label('override_amounts', 'Override?', array('class' => 'control-label control-label-reqd col-xs-3')) }}
                        <div class="input-group col-xs-6">
                            {{ Form::checkbox('override_amounts', 1, FALSE, array('class' => 'pull-left')) }}
                        </div>                        
                    </div>                    
                    <div class="form-group floating-label-form-group">
                        {{ Form::label('subtotal_amt', 'Subtotal', array('class' => 'control-label control-label-reqd col-xs-3')) }}
                        <div class="input-group col-xs-6">
                            <span class="input-group-addon"><i class="fa fa-dollar fa-fw"></i></span> 
                            {{ Form::text('subtotal_amt', null, array('class' => 'form-control input-sm input-sm-reqd floatlabel', 'placeholder' => 'Subtotal', )) }}
                        </div>                        
                    </div>
                    <div class="form-group floating-label-form-group">
                        {{ Form::label('shipping_charge', 'Shipping & Handling', array('class' => 'control-label control-label-reqd col-xs-3')) }}
                        <div class="input-group col-xs-6">
                            <span class="input-group-addon"><i class="fa fa-dollar fa-fw"></i></span> 
                            {{ Form::text('shipping_charge', null, array('class' => 'form-control input-sm input-sm-reqd floatlabel', 'placeholder' => 'Shipping & Handling', )) }}
                        </div>
                    </div>
                    <div class="form-group floating-label-form-group">
                        {{ Form::label('discounts', 'Discounts', array('class' => 'control-label control-label-reqd col-xs-3')) }}
                        <div class="input-group col-xs-6">
                            <span class="input-group-addon"><i class="fa fa-dollar fa-fw"></i></span> 
                            {{ Form::text('discounts', null, array('class' => 'form-control input-sm input-sm-reqd floatlabel', 'placeholder' => 'Discounts', )) }}
                        </div>
                    </div>
                    <div class="form-group floating-label-form-group">
                        {{ Form::label('order_total', 'Total', array('class' => 'control-label control-label-reqd col-xs-3')) }}
                        <div class="input-group col-xs-6">
                            <span class="input-group-addon"><i class="fa fa-dollar fa-fw"></i></span> 
                            {{ Form::text('order_total', null, array('class' => 'form-control input-sm input-sm-reqd floatlabel', 'placeholder' => 'Total', )) }}
                        </div>
                    </div>
                </div>                 
            </div>
            
        </div>
        
        <div class="row">&nbsp;</div>
        
        <div class="row">
        {{ Form::submit($order_action, array('class' => 'btn btn-primary btn-sm pull-right',)) }}
        </div>
    
    {{ Form::close() }}
    
    <script type='text/javascript'>
        function clearAmounts() {
            $('#subtotal_amt').val("");
            $('#shipping_charge').val("");
            $('#discounts').val("");
            $('#order_total').val("");
            $('#override_amounts').prop('checked', true);
        }
    </script>
    
    <script type="text/javascript">
        function addFormFields() {
            var newFields = "<div class='row input-qty'></div>"
            + "<div class='col-md-6'>"
            + "<input size='1' maxlength='8' class='form-control input-sm' name='form_id[]' type='text'>"
            + "</div><div class='col-md-6'>"
            + "<input size='1' maxlength='2' class='form-control input-sm' name='qty[]' type='text' value='1'>"
            + "</div></div>";
            //$( ".row.input-qty" ).last().after(newFields);
            for ( i=0; i < 5; i++ ) {
                $( ".row.input-qty" ).last().after(newFields);
            }
        }
    </script>
    