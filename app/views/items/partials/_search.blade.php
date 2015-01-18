    <div class="row form-inline">
        {{ Form::open(array('method' => 'get', 'action' => 'ItemsController@search', 'role' => 'form')) }}
            <div class="col-xs-6">
                <div class="input-group col-xs-12">
                    <!--{{ Form::label('search', 'Search Criteria', ['class' => 'control-label']) }}
                        <span class="hovertext">Please enter your first (given) name.<div class="triangle"></div></span> -->
                        <span class="input-group-addon"><i class="fa fa-binoculars"></i></span> 
                        {{ Form::text('search', null, ['class' => 'form-control', 'placeholder' => 'Search Criteria']) }}
                </div>
                <span class="help-block">Separate search terms with commas.  Search criteria applies to title and speaker name.</span>
            </div>
            <div class="col-xs-2">
                {{ Form::submit('Search', ['class' => 'btn btn-primary btn-sm']) }}
            </div>
            <div class="col-xs-4 pull-right">
                {{ Form::select('session_type', array('A' => 'All', 'S' => 'Sermons', 'C' => 'Classes', 'M' => 'Music', 'O' => 'Other'), NULL, array('class' => 'form-control')) }}
            </div>
	{{ Form::close() }}
    </div>




