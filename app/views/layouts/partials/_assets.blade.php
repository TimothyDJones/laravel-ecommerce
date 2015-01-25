	{{ HTML::style('css/bootstrap.min.css') }}
        {{ HTML::style('css/font-awesome.min.css') }}
        {{ HTML::style('//fonts.googleapis.com/css?family=Open+Sans') }}
        {{ HTML::style('css/layout.css') }}
	{{ HTML::script('js/jquery-1.11.1.min.js') }}
        {{ HTML::script('js/bootstrap.min.js') }}
        {{ HTML::script('js/jquery-match-height/jquery.matchHeight-min.js') }}
	
        <script>
            (function () {
            $.ready( {
                /*$(function () {
                    $('[data-toggle="tooltip"]').tooltip()
                });*/
                
                // apply matchHeight to each item container's items
                //var byRow = true;
                (function() {
                    $('.items-container').each(function() {
                        $(this).children('.item').matchHeight();
                    });
                });
            });
        });
        </script>