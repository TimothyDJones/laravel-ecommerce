            <div class='col-xs-9'>
                <div class="form-group floating-label-form-group">
                    {{ Form::label('first_name', 'First Name', array('class' => 'control-label control-label-reqd col-xs-5')) }}
                    <div class="input-group col-xs-6">
                        <!--<span class="hovertext">Please enter your first (given) name.<div class="triangle"></div></span> -->
                        <span class="input-group-addon"><i class="fa fa-user fa-fw"></i></span> 
                        {{ Form::text('first_name', null, array('class' => 'form-control input-sm input-sm-reqd floatlabel', 'placeholder' => 'First Name', 'data-label' => 'Please enter your first (given) name.')) }}
                    </div>
                </div>                
                <div class="form-group floating-label-form-group">
                    {{ Form::label('last_name', 'Last Name', array('class' => 'control-label control-label-reqd col-xs-5')) }}
                    <div class="input-group col-xs-6">
                        <!--<span class="hovertext">Please enter your last name (surname).<div class="triangle"></div></span> -->
                        <span class="input-group-addon"><i class="fa fa-user fa-fw"></i></span>
                        {{ Form::text('last_name', null, array('class' => 'form-control input-sm input-sm-reqd floatlabel', 'placeholder' => 'Last Name', 'data-label' => 'Please enter your last name (surname).')) }}
                    </div>
                </div>                
                <div class="form-group floating-label-form-group">
                    {{ Form::label('email', 'E-mail Address', array('class' => 'control-label control-label-reqd col-xs-5')) }}
                    <div class="input-group col-xs-6">
                        <!--<span class="hovertext">You must enter a valid e-mail address in the format 'name@example.com'.<div class="triangle"></div></span> -->
                        <span class="input-group-addon"><i class="fa fa-envelope-o fa-fw"></i></span>
                        {{ Form::email('email', null, array('class' => 'form-control input-sm input-sm-reqd floatlabel', 'placeholder' => 'E-Mail Address', 'data-label' => 'You must enter a valid e-mail address in the format \'name@example.com\'.')) }}
                    </div>
                </div>
                @if ( !$updateFlag && !Utility::isAdminUser() )
                <div class="form-group floating-label-form-group">
                    {{ Form::label('password', 'Password', array('class' => 'control-label control-label-reqd col-xs-5')) }}
                    <div class="input-group col-xs-6">
                        <!--<span class="hovertext">Enter a password.  Choose something easy for you to remember, but difficult for others to guess.  Your password is encrypted before it is stored.<div class="triangle"></div></span> -->
                        <span class="input-group-addon"><i class="fa fa-key fa-fw"></i></span>
                        {{ Form::password('password', array('class' => 'form-control input-sm input-sm-reqd floatlabel', 'placeholder' => 'Password')) }}
                    </div>
                </div>
                <div class="form-group floating-label-form-group">
                    {{ Form::label('password_confirmation', 'Confirm Password', array('class' => 'control-label control-label-reqd col-xs-5')) }}
                    <div class="input-group col-xs-6">
                        <!--<span class="hovertext">Re-enter the password from the 'Password' field.<div class="triangle"></div></span> -->
                        <span class="input-group-addon"><i class="fa fa-key fa-fw"></i></span>
                        {{ Form::password('password_confirmation', array('class' => 'form-control input-sm input-sm-reqd floatlabel', 'placeholder' => 'Confirm Password')) }}
                    </div>
                </div>
                @endif
                <div class="form-group floating-label-form-group">
                    {{ Form::label('telephone1', 'Primary Telephone', array('class' => 'control-label control-label-reqd col-xs-5')) }}
                    <div class="input-group col-xs-6">
                        <!--<span class="hovertext">Your telephone number is <em>only</em> used if we have questions about your order.<div class="triangle"></div></span> -->
                        <span class="input-group-addon"><i class="fa fa-phone fa-fw"></i></span>
                        {{ Form::text('telephone1', null, array('class' => 'form-control input-sm floatlabel', 'placeholder' => 'Primary Telephone')) }}
                    </div>
                </div>
                <div class="form-group floating-label-form-group">
                    {{ Form::label('telephone2', 'Other Telephone', array('class' => 'control-label col-xs-5')) }}
                    <div class="input-group col-xs-6">
                        <!--<span class="hovertext">If you have a secondary telephone number that you would like us to use, if necessary, enter it here.<div class="triangle"></div></span> -->
                        <span class="input-group-addon"><i class="fa fa-phone-square fa-fw"></i></span>
                        {{ Form::text('telephone2', null, array('class' => 'form-control input-sm floatlabel', 'placeholder' => 'Secondary Telephone')) }}
                    </div>
                </div>
                {{ Form::submit($submit_button_label, array('class' => 'btn btn-primary pull-right')) }}
            </div>
