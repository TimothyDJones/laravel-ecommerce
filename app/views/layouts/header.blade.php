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
                <li class="active">{{ link_to('/', 'Home') }}</li>
                <li><a href="#">Profile</a></li>
                <li class="dropdown">
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#">Customers <b class="caret"></b></a>
                    <ul role="menu" class="dropdown-menu">
                        <li>{{ link_to('customers/create', 'Create') }}</li>
                        @if ( Auth::check() )
                            <li>{{ link_to('profile', 'Profile') }}</li>
                        @endif
                        <li><a href="#">Sent Items</a></li>
                        <li class="divider"></li>
                        <li><a href="#">Trash</a></li>
                    </ul>
                </li>
            </ul>
            {{ Form::open(array('route' => 'search', 'method' => 'get', 'role' => 'search', 'class' => 'navbar-form navbar-left')) }}
                <div class="form-group">
                    {{ Form::text('nav-search', null, array('placeholder' => 'Search', 'class' => 'form-control')) }}
                </div>
            {{ Form::close() }}
            <ul class="nav navbar-nav navbar-right">
                @if ( Auth::check() )
                    <li>{{ link_to('profile', 'Profile') }}</li>
                    <li>{{ link_to('logout', 'Log Out') }}</li>
                @else
                    <li>{{ link_to('login', 'Log In') }}</li>
                @endif
            </ul>
        </div>
    </nav>



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