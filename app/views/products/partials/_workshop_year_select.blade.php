    <!-- jQuery script to auto-update product list when workshop year drop-down is changed. -->
    <script type="text/javascript">
        $(document).ready(
            function() {
                $("#workshop_year_select").change(
                    function() {
                        var year_text = $("#workshop_year_select").text();
                        var year_val = $("#workshop_year_select").val();
                        $.get("products/" + year_val);
                    });
                
            });
    </script>
    
    {{ Form::open(array('route' => 'products', 'method' => 'post', 'role' => 'form', 'class' => 'form-horizontal')) }}
        {{ Form::label('workshop_year_select', 'Display Items for Workshop Year', array('class' => 'control-label control-label-reqd col-xs-5')) }}
            {{ Form::select('workshop_year_select', $workshop_year_list, $workshop_year_selected, array('class' => 'form-control input-sm input-sm-reqd floatlabel')) }}
    {{ Form::close() }}

    
    {{-- Form::open(array('route' => 'products', 'method' => 'post', 'role' => 'form', 'class' => 'form-horizontal')) }}
    <div class="form-group floating-label-form-group">
        {{ Form::label('workshop_year_select', 'Display Items for Workshop Year', array('class' => 'control-label control-label-reqd col-xs-5')) }}
        <div class="input-group col-xs-6">
            <span class="input-group-addon"><i class="fa fa-calendar-o fa-fw"></i></span>
            {{ Form::select('workshop_year_select', $workshop_year_list, $workshop_year_selected, array('class' => 'form-control input-sm input-sm-reqd floatlabel')) }}
        </div>
    </div>
    {{ Form::close() --}}    

