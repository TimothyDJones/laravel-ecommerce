                <div class="form-group floating-label-form-group">
                    {{ Form::label('shipping_option', 'Delivery Method', array('class' => 'control-label control-label-reqd col-xs-3')) }}
                    <div class="input-group col-xs-6">
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
                        <span class="input-group-addon"><i class="fa fa-file-text-o fa-fw"></i></span>
                        <textarea id="order_notes" name="order_notes"
                                  style="width: 100%;" rows="4"
                                  class="form-control input-sm input-sm-reqd floatlabel">Order Notes</textarea>
                    </div>
                </div>