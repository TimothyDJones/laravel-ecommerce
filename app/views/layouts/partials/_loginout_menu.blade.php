                        @if ( Auth::guest() )
                            <li>{{ link_to('login', 'Log In') }}</li>
                        @endif
                        @if ( Auth::check() )
                            <li>{{ link_to('profile', 'Profile', array('title' => Auth::user()->email)) }}</li>
                            <li>{{ link_to('logout', 'Log Out', array('title' => Auth::user()->email)) }}</li>
                        @endif

