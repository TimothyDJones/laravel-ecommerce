@section("header")
    <nav role="navigation" class="navbar navbar-default">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" data-target="#navbarCollapse" data-toggle="collapse" class="navbar-toggle">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            {{ link_to('http://www.workshopmultimedia.com/', 'Workshop Multimedia', array('class' => 'navbar-brand')) }}
        </div>
        <!-- Collection of nav links, forms, and other content for toggling -->
        <div id="navbarCollapse" class="collapse navbar-collapse">
            <ul class="nav navbar-nav">
                @if ( strpos(Route::currentRouteName(), 'home') !== FALSE )
                <li class="active">
                @else
                <li>
                @endif                    
                    {{ link_to('/', 'Home') }}</li>
                @if ( strpos(Route::currentRouteName(), 'products') !== FALSE )
                <li class="active">
                @else
                <li>
                @endif                 
                    {{ link_to_route('products.index', 'Products') }}</li>
                
                {{-- <li>{{ link_to_route('items.index', 'Free Downloads') }}</li>
                <li class="dropdown">
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#">Customers <b class="caret"></b></a>
                    <ul role="menu" class="dropdown-menu">
                        <li>{{ link_to('customers/create', 'Create') }}</li>
                        @include('layouts.partials._loginout_menu')
                        <li><a href="#">Sent Items</a></li>
                        <li class="divider"></li>
                        <li><a href="#">Trash</a></li>
                    </ul>
                </li> --}}
            </ul>
            {{ Form::open(array('action' => 'ProductsController@search', 'method' => 'get', 'role' => 'search', 'class' => 'navbar-form navbar-left')) }}
                <div class="form-group">
                    {{ Form::text('search', null, array('placeholder' => 'Search', 'class' => 'form-control')) }}
                </div>
            {{ Form::close() }}
            <ul class="nav navbar-nav navbar-right">
                @if ( Cart::contents() )
                    <a href="{{ route('show-cart') }}" class="btn btn-primary btn-sm">
                        <i class="fa fa-shopping-cart fa-fw"></i>
                        Show Cart <span class="badge">&nbsp;${{ money_format("%.2n", Cart::total()) }}&nbsp;({{ Cart::totalItems() }})&nbsp;</span>
                    </a>
                @endif
                @include('layouts.partials._loginout_menu')
            </ul>
        </div>
    </nav>

    @if ( Config::get('app.debug') )
        {{ Kint::dump(Route::currentRouteName()) }}
    @endif

<!--
<div class="header">
    <div class="container">
        <h1>Customer Authentication Tutorial
        
        @if ( Auth::check() )
            <li>{{ link_to('profile', 'Profile') }}</li>
            <li>{{ link_to('logout', 'Log Out') }}</li>
        @else
            <li>{{ link_to('login', 'Log In') }}</li>
        @endif
        </h1>
    </div>
</div>
-->

@show