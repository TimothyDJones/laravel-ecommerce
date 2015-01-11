    <div class="row">
        {{ Form::open(array('action' => 'CustomersController@login', 'role' => 'form')) }}
            <div class="col-xs-9">
                <div class="form-group floating-label-form-group">
                    {{ Form::label('search', 'Search Criteria', ['class' => 'control-label control-label-reqd col-xs-5']) }}
                    <div class="input-group col-xs-6">
                        <!--<span class="hovertext">Please enter your first (given) name.<div class="triangle"></div></span> -->
                        <span class="input-group-addon"><i class="fa fa-binoculars fa-fw"></i></span> 
                        {{ Form::text('search', null, ['class' => 'form-control input-sm input-sm-reqd floatlabel', 'placeholder' => 'Enter terms to search by.', 'data-label' => 'Please enter your first (given) name.']) }}
                    </div>
                </div>
            </div>
            {{ Form::submit('Search', ['class' => 'btn btn-primary btn-sm']) }}
	{{ Form::close() }}
    </div>




