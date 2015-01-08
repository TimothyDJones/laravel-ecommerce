	{{ HTML::style('css/bootstrap.min.css') }}
        {{ HTML::style('css/font-awesome.min.css') }}
        {{ HTML::style('css/layout.css') }}
	{{ HTML::script('js/jquery-1.11.1.min.js') }}
        {{ HTML::script('js/bootstrap.min.js') }}
	
	<style>
		#wrapper {width: 960px; max-width: 100%; margin: auto}
		.inline {display: inline}
		.error {color: red}
                
                /* 
                    Styling for hover tooltips for form elements 
                    http://html5beginners.com/css-tooltip-tutorial-for-website/
                */
                .hovertext {display: block;}
                span.hovertext {
                    border: 3px solid #c00000;
                    margin: -100px -20px 0px -20px;
                    padding: 10px;
                    border-top-left-radius: 10px;
                    border-top-right-radius: 10px;
                    background: #c00000;
                    color:#fcfcfc;
                    font-size:14px;
                    display:none;
                    position: absolute;
                    z-index: 8;
                }
                .input-group:hover .hovertext {display: block;}
                .triangle {
                    width: 0px;
                    height: 0px;
                    border-style: solid;
                    border-width: 10px 10px 0 10px;
                    border-color: #c00000 transparent transparent transparent;
                    margin-top: 12px;
                    margin-left: 10px;
                    position: absolute;
                    z-index: 8;
                }
                
                
	</style>
        
        <script>
            $.ready( {
                $(function () {
                    $('[data-toggle="tooltip"]').tooltip()
                });
            });
        </script>