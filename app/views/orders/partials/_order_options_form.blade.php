                <div class="form-group floating-label-form-group">
                    {{ Form::label('shipping_option', 'Delivery Method', array('class' => 'control-label control-label-reqd col-xs-3')) }}
                    <div class="input-group col-xs-6">
                        <!--<span class="hovertext">Please enter your first (given) name.<div class="triangle"></div></span> -->
                        <span class="input-group-addon"><i class="fa fa-plane fa-fw"></i></span> 
                        {{ Form::select('shipping_option', $shipping_options, NULL, array('class' => 'form-control input-sm input-sm-reqd floatlabel',
                                                                                            'data-toggle' => 'tooltip',
                                                                                            'data-placement' => 'top',
                                                                                            'data-original-title' => $shipping_charge_note)) }}
                    </div>
                </div>                
                <div class="form-group floating-label-form-group">
                    {{ Form::label('order_notes', 'Order Notes', array('class' => 'control-label control-label-reqd col-xs-3')) }}
                    <div class="input-group col-xs-6">
                        <!--<span class="hovertext">Please enter your last name (surname).<div class="triangle"></div></span> -->
                        <span class="input-group-addon"><i class="fa fa-file-text-o fa-fw"></i></span>
                        {{ Form::textarea('order_notes', 
                                    'Order Notes',
                                    null, array('class' => 'form-control input-sm input-sm-reqd floatlabel', 
                                            'placeholder' => 'Order Notes', 
                                            'data-label' => 'Please enter any notes or special instructions about your order. (Optional)')) }}
                    </div>
                </div>