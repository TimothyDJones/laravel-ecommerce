    <div class="row form-inline">
        {{ Form::open(array('method' => 'get', 'route' => 'search-order', 'role' => 'form')) }}
            <div class="col-xs-8">
                <div class="input-group col-xs-12">
                        <span class="input-group-addon"><i class="fa fa-list-ul"></i></span> 
                        {{ Form::text('search_order', null, array('class' => 'form-control', 'placeholder' => 'Enter order numbers.')) }}
                </div>
                <span class="help-block">Separate search terms with commas.</span>
            </div>
            <div class="col-xs-2">
                {{ Form::submit('Search', array('class' => 'btn btn-primary btn-sm')) }}
            </div>
	{{ Form::close() }}
    </div>

    <div class="row">&nbsp;</div>

    <div class="row form-inline">
        {{ Form::open(array('method' => 'get', 'route' => 'search-customer', 'role' => 'form')) }}
            <div class="col-xs-8">
                <div class="input-group col-xs-12">
                        <span class="input-group-addon"><i class="fa fa-users"></i></span> 
                        {{ Form::text('search_customer', null, array('class' => 'form-control', 'placeholder' => 'Enter customer names and/or e-mail addresses.')) }}
                </div>
                <span class="help-block">Separate search terms with commas.  Partial search criteria (e.g., last name) is OK.</span>
            </div>
            <div class="col-xs-2">
                {{ Form::submit('Search', array('class' => 'btn btn-primary btn-sm')) }}
            </div>
	{{ Form::close() }}
    </div>