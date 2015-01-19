            <div class='col-xs-9'>
                <div class="form-group">
                    {{ Form::label('addr1', 'Address', ['class' => 'control-label col-xs-5']) }}
                    <div class="input-group col-xs-6">
                        <span class="input-group-addon"><i class="fa fa-home fa-fw"></i></span>
                        {{ Form::text('addr1', null, ['class' => 'form-control input-sm']) }}
                    </div>
                </div>
                <div class="form-group">
                    {{ Form::label('addr2', 'Other Address Info', ['class' => 'control-label col-xs-5']) }}
                    <div class="input-group col-xs-6">
                        <span class="input-group-addon"><i class="fa fa-home fa-fw"></i></span>
                        {{ Form::text('addr2', null, ['class' => 'form-control input-sm']) }}
                    </div>
                </div>
                <div class="form-group">
                    {{ Form::label('city', 'City', ['class' => 'control-label col-xs-5']) }}
                    <div class="input-group col-xs-6">
                        <span class="input-group-addon"><i class="fa fa-home fa-fw"></i></span>
                        {{ Form::text('city', null, ['class' => 'form-control input-sm']) }}
                    </div>
                </div>
                <div class="form-group">
                    {{ Form::label('state', 'State/Province/District', ['class' => 'control-label col-xs-5']) }}
                    <div class="input-group col-xs-6">
                        <span class="input-group-addon"><i class="fa fa-home fa-fw"></i></span>
                        {{ Form::text('state', null, ['class' => 'form-control input-sm']) }}
                    </div>
                </div>
                <div class="form-group">
                    {{ Form::label('postal_code', 'ZIP/Postal Code', ['class' => 'control-label col-xs-5']) }}
                    <div class="input-group col-xs-6">
                        <span class="input-group-addon"><i class="fa fa-home fa-fw"></i></span>
                        {{ Form::text('postal_code', null, ['class' => 'form-control input-sm']) }}
                    </div>
                </div>
                {{ Form::submit($submit_button_text, ['class' => 'btn btn-primary pull-right']) }}
            </div>

