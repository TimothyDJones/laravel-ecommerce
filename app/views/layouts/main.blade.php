<!DOCTYPE html>

<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<title>Workshop Multimedia</title>
	<meta name="viewport" content="width=device-width">
	
        @include("layouts.partials._assets")
        
</head>
<body>
        @include("layouts.header")
	<div id="wrapper">
		<!--<header>
			
		</header>-->
            <div class="container">
		<div id="content" class="content row">
                    <div class="col-md-10">

			@if (Session::has('message'))
				<div class="flash alert alert-info">
					<p>{{ Session::get('message') }}</p>
				</div>
			@endif
                        
                        @if ($errors->any() )
                        <div class="flash alert alert-danger">
                        <ul class="list-group">
                            {{ implode('', $errors->all('<li class="list-group-item">:message</li>')) }}
                        </ul>
                        </div>
                        @endif
			
			@yield('main')	
                        
                    </div>
                    <div class="col-md-2">
                        @include("layouts.sidebar")
                    </div>
		</div>
            </div>
	</div>
        @include("layouts.footer")
</body>

</html>