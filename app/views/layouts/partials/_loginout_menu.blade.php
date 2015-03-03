                        @if ( Auth::guest() )
                            @if ( strpos(Route::currentRouteName(), 'login') !== FALSE )
                            <li class="active">
                            @else
                            <li>
                            @endif                         
                                {{ link_to('login', 'Log In') }}</li>
                        @endif
                        @if ( Auth::check() )
                            @if ( Auth::user()->admin_ind )
                                @if ( strpos(Route::currentRouteName(), 'admin') !== FALSE )
                                <li class="active">
                                @else
                                <li>
                                @endif 
                                    {{ link_to('admin', 'Admin') }}</li>
                            @endif
                            @if ( strpos(Route::currentRouteName(), 'profile') !== FALSE )
                            <li class="active">
                            @else
                            <li>
                            @endif 
                                {{ link_to('profile', 'Profile', array('title' => Auth::user()->email)) }}</li>
                            <li>{{ link_to('logout', 'Log Out', array('title' => Auth::user()->email)) }}</li>
                        @endif

