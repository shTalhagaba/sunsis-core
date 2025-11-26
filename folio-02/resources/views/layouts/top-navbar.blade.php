@php
    $_nk = 'user:' . auth()->user()->id . ':unread_notifications_count';
    $unreadNotifications = \Cache::remember($_nk, 600, function () {
        return auth()->user()->unreadNotifications;
    });

    $messages = auth()->user()->received()->unSeen()->get();
@endphp
<div id="navbar" class="navbar navbar-default navbar-collapse ace-save-state">

    <div class="navbar-container ace-save-state" id="navbar-container">
        <button type="button" class="navbar-toggle menu-toggler pull-left" id="menu-toggler" data-target="#sidebar">
            <span class="sr-only">Toggle sidebar</span>

            <span class="icon-bar"></span>

            <span class="icon-bar"></span>

            <span class="icon-bar"></span>
        </button>

        <div class="navbar-header pull-left">
            <img class="img-rounded" height="40px;" src="{{ asset('images/logos/' . App\Facades\AppConfig::get('FOLIO_LOGO_NAME')) }}" alt="Logo">
        </div>

        <div class="navbar-buttons navbar-header pull-right" role="navigation">
            <ul class="nav ace-nav">

                <li class="purple dropdown-modal">
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                        <i class="ace-icon fa fa-bell icon-animated-bell"></i>
                        <span class="badge badge-important">{{ $unreadNotifications->count() }}</span>
                    </a>

                    <ul class="dropdown-menu-right dropdown-navbar navbar-pink dropdown-menu dropdown-caret dropdown-close">
                        <li class="dropdown-header">
                            <i class="ace-icon fa fa-exclamation-triangle"></i>
                            {{ $unreadNotifications->count() }} Notifications
                        </li>

                        <li class="dropdown-content">
                            <ul class="dropdown-menu dropdown-navbar navbar-pink">
                                @forelse ($unreadNotifications as $notification)
                                <li>
                                    <a href="{{ isset($notification->data['type']) && $notification->data['type'] === 'task' 
                                    ? route('user_events.show', $notification->data['task_id']) : '#' }}">
                                        <i class="btn btn-xs btn-primary fa fa-file"></i>
                                        <small>
                                            @php
                                            $title = isset($notification->data['title'])
                                            ? $notification->data['title']
                                            : '';
                                            @endphp
                                            {!! \Str::limit($title, 100) !!}
                                        </small>
                                    </a>
                                </li>
                                @empty
                                <li>
                                    <a href="#">
                                        <i class="btn btn-xs btn-primary fa fa-info-circle"></i>
                                        No new notification
                                    </a>
                                </li>
                                @endforelse
                            </ul>

                        </li>

                        <li class="dropdown-footer">
                            <a href="{{ route('notifications.index') }}">
                                See all notifications
                                <i class="ace-icon fa fa-arrow-right"></i>
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="green dropdown-modal">
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                        <i class="ace-icon fa fa-envelope icon-animated-vertical"></i>
                        <span class="badge badge-success">{{ count($messages) }}</span>
                    </a>

                    <ul class="dropdown-menu-right dropdown-navbar dropdown-menu dropdown-caret dropdown-close">
                        <li class="dropdown-header">
                            <i class="ace-icon fa fa-envelope-o"></i>{{ count($messages) }} messages
                        </li>

                        <li class="dropdown-content">
                            <ul class="dropdown-menu dropdown-navbar">
                                @forelse ($messages as $message)
                                <li>
                                    <a href="{{ route('messages.show', $message) }}" class="clearfix">
                                        @php
                                        $avatar_url =
                                        $message->sender->gender == 'F'
                                        ? asset('images/avatars/default_female.png')
                                        : asset('images/avatars/default_male.png');
                                        if (!is_null($message->sender->getMedia('avatars')->first())) {
                                        $avatar_url = $message->sender->getFirstMediaUrl('avatars');
                                        }
                                        @endphp
                                        <img src="{{ $avatar_url }}" class="msg-photo" />
                                        <span class="msg-body">
                                            <span class="msg-title">
                                                <span class="blue">{{ $message->sender->firstnames }}:</span>
                                                {{ \Str::limit($message->subject, 40) }}
                                            </span>

                                            <span class="msg-time">
                                                <i class="ace-icon fa fa-clock-o"></i>
                                                <span>{{ \Carbon\Carbon::parse($message->created_at)->diffForHumans() }}</span>
                                            </span>
                                        </span>
                                    </a>
                                </li>
                                @empty
                                <li><small>No unread message.</small></li>
                                @endforelse
                            </ul>
                        </li>

                        <li class="dropdown-footer">
                            <a href="{{ route('messages.index') }}?tab=inbox&order_by=date">
                                See all messages
                                <i class="ace-icon fa fa-arrow-right"></i>
                            </a>
                            <a href="{{ route('messages.compose') }}">
                                Compose message
                            </a>
                        </li>
                    </ul>
                </li>

                <li class="light-blue dropdown-modal">
                    <a data-toggle="dropdown" href="#" class="dropdown-toggle">
                        @if (auth()->user())
                        <img class="nav-user-photo" src="{{ asset(auth()->user()->avatar_url) }}" alt="{{ auth()->user()->firstnames }} Photo" />
                        <span class="user-info">
                            <small>Welcome,</small>
                            {{ auth()->user()->firstnames }}
                        </span>
                        @endif
                        <i class="ace-icon fa fa-caret-down"></i>
                    </a>

                    <ul class="user-menu dropdown-menu-right dropdown-menu dropdown-yellow dropdown-caret dropdown-close">
                        {{-- <li>
                            <a href="#" onclick="alert('Under development.');">
                                <i class="ace-icon fa fa-cog"></i>
                                Settings
                            </a>
                            </li> --}}
                        @if (auth()->user())
                        <li>
                            <a href="{{ route('profile.show') }}">
                                <i class="ace-icon fa fa-user"></i>
                                Profile
                            </a>
                        </li>
                        @endif

                        @if (\Session::has('impersonate'))
                        <li class="divider"></li>
                        <li><a href="{{ route('perspective.support.impersonate.destroy') }}">Stop Impersonate</a>
                        </li>
                        @endif

                        @if (!\Session::has('impersonate'))
                        @if(auth()->user()->linkedUsers()->count() > 0 || auth()->user()->linkedByUsers()->count() > 0)
                        <li class="divider"></li>
                        <li>
                            <a href="{{ route('login_as.show') }}">
                                <i class="ace-icon fa fa-sign-in"></i>
                                Login As
                            </a>
                        </li>
                        @endif
                        <li class="divider"></li>

                        <li>
                            <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="ace-icon fa fa-power-off"></i>
                                Logout
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </li>
                        @endif
                    </ul>
                </li>
            </ul>
        </div>
    </div><!-- /.navbar-container -->
</div>
