                        @if ( Auth::guest() )
                            <li>{{ link_to('login', 'Log In') }}</li>
                        @endif
                        @if ( Auth::check() )
                            @if ( Auth::user()->admin_ind )
                            <li>{{ link_to('admin', 'Admin') }}</li>
                            @endif
                            <li>{{ link_to('profile', 'Profile', array('title' => Auth::user()->email)) }}</li>
                            <li>{{ link_to('logout', 'Log Out', array('title' => Auth::user()->email)) }}</li>
                        @endif

