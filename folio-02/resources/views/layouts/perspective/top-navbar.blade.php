<div id="navbar" class="navbar navbar-default navbar-collapse ace-save-state">
    <div class="navbar-container ace-save-state" id="navbar-container">
        <button type="button" class="navbar-toggle menu-toggler pull-left" id="menu-toggler" data-target="#sidebar">
            <span class="sr-only">Toggle sidebar</span>

            <span class="icon-bar"></span>

            <span class="icon-bar"></span>

            <span class="icon-bar"></span>
        </button>

        <div class="navbar-header pull-left">
            <img class="img-rounded" height="40px;" src="{{ asset('images/logos/'.App\Facades\AppConfig::get('FOLIO_LOGO_NAME')) }}" alt="">
        </div>

        <div class="navbar-buttons navbar-header pull-right" role="navigation">
            <ul class="nav ace-nav">

                <li class="light-blue dropdown-modal">
                    <a data-toggle="dropdown" href="#" class="dropdown-toggle">
                        @if( Auth::user() )
                        <img class="nav-user-photo" src="{{ asset(\Auth::user()->avatar_url) }}" alt="Jason's Photo" />
                        <span class="user-info">
                            <small>Welcome,</small>
                            {{ Auth::user()->firstnames }}
                        </span>
                        @endif
                        <i class="ace-icon fa fa-caret-down"></i>
                    </a>

                    <ul
                        class="user-menu dropdown-menu-right dropdown-menu dropdown-yellow dropdown-caret dropdown-close">

                        <li>
                            <a href="{{ route('logout') }}"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="ace-icon fa fa-power-off"></i>
                                Logout
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </li>

                    </ul>
                </li>
            </ul>
        </div>
    </div><!-- /.navbar-container -->
</div>
