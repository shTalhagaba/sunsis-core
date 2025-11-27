<div class="sidebar responsive ace-save-state" id="sidebar">
    <script type="text/javascript">
        try{ace.settings.loadState('sidebar')}catch(e){}
    </script>
    <div class="sidebar-shortcuts" id="sidebar-shortcuts">
        <div class="sidebar-shortcuts-large" id="sidebar-shortcuts-large">
            <button title="Go to homepage" class="btn btn-success" type="button"
                onclick="window.location.href='{{ route('home') }}'">
                <i class="ace-icon fa fa-home"></i>
            </button>
            <button title="Edit your profile" class="btn btn-info" type="button"
                onclick="window.location.href='{{ route('profile.show') }}'">
                <i class="ace-icon fa fa-pencil"></i>
            </button>
            {{-- <button class="btn btn-danger" type="button" onclick="alert('Under development.');">
                <i class="ace-icon fa fa-cogs"></i>
            </button> --}}
            {!! Form::open(['url' => route('logout'), 'method' => 'POST', 'id' => 'logout-form-sidebar', 'style' =>
            'display: inline' ]) !!}
            <button class="btn btn-warning" type="submit">
                <i class="ace-icon fa fa-sign-out"></i>
            </button>
            {!! Form::close() !!}
        </div>
        <div class="sidebar-shortcuts-mini" id="sidebar-shortcuts-mini">
            <span class="btn btn-success"></span>
            <span class="btn btn-info"></span>
            <span class="btn btn-warning"></span>
            <span class="btn btn-danger"></span>
        </div>
    </div><!-- ./sidebar-shortcuts -->

    <ul class="nav nav-list">
        <li class="{{ (request()->is('my*')) ? 'active open' : '' }}">
            <a href="#" class="dropdown-toggle">
                <i class="menu-icon fa fa-home"></i><span class="menu-text"> My Account </span><b
                    class="arrow fa fa-angle-down"></b>
            </a>
            <b class="arrow"></b>
            <ul class="submenu">
                <li class="{{ request()->is('*dashboard') ? 'active' : '' }}">
                    <a href="{{ route('home') }}"><i class="menu-icon fa fa-caret-right"></i> Dashboard</a>
                    <b class="arrow"></b>
                </li>
                <li class="{{ request()->is('*signature') ? 'active' : '' }}">
                    <a href="{{ route('signature.manage') }}"><i class="menu-icon fa fa-caret-right"></i> Signature</a>
                    <b class="arrow"></b>
                </li>
                <li class="{{ request()->is('*cp') ? 'active' : '' }}">
                    <a href="{{ route('change_password.show') }}"><i class="menu-icon fa fa-caret-right"></i> Change
                        Password</a>
                    <b class="arrow"></b>
                </li>
                <li class="{{ request()->is('*logout-other-devices') ? 'active' : '' }}">
                    <a href="{{ route('logout-other-devices.show') }}"><i class="menu-icon fa fa-caret-right"></i>
                        Logout all other devices</a>
                    <b class="arrow"></b>
                </li>
                <li class="{{ request()->is('*logout') ? 'active' : '' }}">
                    <a href="{{ route('logout') }}"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="menu-icon fa fa-caret-right"></i> Logout
                    </a>
                    <b class="arrow"></b>
                </li>
            </ul>
        </li>

        @can('menu-system-admin')
        <li class="{{ request()->is('system/*') ? 'active open' : '' }}">
            <a href="#" class="dropdown-toggle">
                <i class="menu-icon fa fa-cogs"></i><span class="menu-text"> System </span><b
                    class="arrow fa fa-angle-down"></b>
            </a>
            <b class="arrow"></b>
            <ul class="submenu">
                @can('submenu-roles-permissions')
                <li class="{{ request()->is('system/rp*') ? 'active' : '' }}">
                    <a href="{{ route('rp.index') }}"><i class="menu-icon fa fa-caret-right"></i> Roles &
                        Permissions</a>
                    <b class="arrow"></b>
                </li>
                @endcan
                @can('submenu-system-users')
                <li class="{{ request()->is('system/users*') ? 'active' : '' }}">
                    <a href="{{ route('users.index') }}"><i class="menu-icon fa fa-caret-right"></i> System Users</a>
                    <b class="arrow"></b>
                </li>
                @endcan
{{--                @can('submenu-eqa-samples')--}}
                <li class="{{ request()->is('system/eqa_samples*') ? 'active' : '' }}">
                    <a href="{{ route('eqa_samples.index') }}"><i class="menu-icon fa fa-caret-right"></i> EQA Samples</a>
                    <b class="arrow"></b>
                </li>
{{--                @endcan    --}}
                @can('submenu-logins')
                <li class="{{ request()->is('system/logins/successful') ? 'active' : '' }}">
                    <a href="{{ route('logins.successful.index') }}"><i class="menu-icon fa fa-caret-right"></i>
                        Logins</a>
                    <b class="arrow"></b>
                </li>
                @endcan
                @can('submenu-failed-logins')
                <li class="{{ request()->is('system/logins/failed') ? 'active' : '' }}">
                    <a href="{{ route('logins.failed.index') }}"><i class="menu-icon fa fa-caret-right"></i> Failed
                        Logins</a>
                    <b class="arrow"></b>
                </li>
                @endcan
            </ul>
        </li>
        @endcan

        @can('menu-organisations')
        <li class="{{ request()->is('organisations*') ? 'active open' : '' }}">
            <a href="#" class="dropdown-toggle">
                <i class="menu-icon fa fa-building"></i><span class="menu-text"> Organisations </span><b
                    class="arrow fa fa-angle-down"></b>
            </a>
            <b class="arrow"></b>
            <ul class="submenu">
                @can('submenu-employers')
                <li class="{{ request()->is('*employers*') ? 'active' : '' }}">
                    <a href="{{ route('employers.index') }}"><i class="menu-icon fa fa-caret-right"></i> Employers</a>
                    <b class="arrow"></b>
                </li>
                @endcan
            </ul>
        </li>
        @endcan

        @can('menu-programmes')
        <li class="{{ request()->is('programmes*') ? 'active open' : '' }}">
            <a href="#" class="dropdown-toggle">
                <i class="menu-icon fa fa-graduation-cap"></i><span class="menu-text"> Programmes </span><b
                    class="arrow fa fa-angle-down"></b>
            </a>
            <b class="arrow"></b>
            <ul class="submenu">
                @can('submenu-programmes')
                <li class="{{ request()->is('*programmes*') ? 'active' : '' }}">
                    <a href="{{ route('programmes.index') }}"><i class="menu-icon fa fa-caret-right"></i> Programmes</a>
                    <b class="arrow"></b>
                </li>
                @endcan
            </ul>
        </li>
        @endcan

        @can('menu-students')
        <li
            class="{{ (request()->is('students*') && !request()->is('students/training_records')) ? 'active open' : '' }}">
            <a href="#" class="dropdown-toggle">
                <i class="menu-icon fa fa-users"></i><span class="menu-text"> Students </span><b
                    class="arrow fa fa-angle-down"></b>
            </a>
            <b class="arrow"></b>
            <ul class="submenu">
                @can('submenu-view-students')
                <li class="{{ request()->is('students*') ? 'active' : '' }}">
                    <a href="{{ route('students.index') }}"><i class="menu-icon fa fa-caret-right"></i> View
                        Students</a>
                    <b class="arrow"></b>
                </li>
                @endcan
            </ul>
        </li>
        @endcan

        @can('menu-training-records')
        <li class="{{ request()->is('students/training_records*') ? 'active open' : '' }}">
            <a href="#" class="dropdown-toggle">
                <i class="menu-icon fa fa-users"></i><span class="menu-text"> Training Records </span><b
                    class="arrow fa fa-angle-down"></b>
            </a>
            <b class="arrow"></b>
            <ul class="submenu">
                @can('submenu-view-training-records')
                <li class="{{ request()->is('students/training_records*') ? 'active' : '' }}">
                    <a href="{{ route('students.training.index') }}"><i class="menu-icon fa fa-caret-right"></i> View
                        Training Records</a>
                    <b class="arrow"></b>
                </li>
                @endcan
            </ul>
        </li>
        @endcan

        @can('menu-qualifications')
        <li class="{{ request()->is('qualifications*') ? 'active open' : '' }}">
            <a href="#" class="dropdown-toggle">
                <i class="menu-icon fa fa-graduation-cap"></i><span class="menu-text"> Qualifications </span><b
                    class="arrow fa fa-angle-down"></b>
            </a>
            <b class="arrow"></b>
            <ul class="submenu">
                @can('submenu-view-qualifications')
                <li class="{{ request()->is('qualifications') ? 'active' : '' }}">
                    <a href="{{ route('qualifications.index') }}"><i class="menu-icon fa fa-caret-right"></i> View
                        Qualifications</a>
                    <b class="arrow"></b>
                </li>
                @endcan
            </ul>
        </li>
        @endcan

        {{-- <li class="{{ request()->is('support*') ? 'active open' : '' }}">
        <a href="#" class="dropdown-toggle">
            <i class="menu-icon fa fa-stethoscope"></i><span class="menu-text"> Support </span><b
                class="arrow fa fa-angle-down"></b>
        </a>
        <b class="arrow"></b>
        <ul class="submenu">
            <li class="{{ request()->is('*tickets*') ? 'active' : '' }}">
                <a href="{{ route('support.tickets.index') }}"><i class="menu-icon fa fa-caret-right"></i> Tickets</a>
                <b class="arrow"></b>
            </li>
        </ul>
        </li> --}}

    </ul><!-- ./nav nav-list -->

    <div class="sidebar-toggle sidebar-collapse">
        <i id="sidebar-toggle-icon" class="ace-icon fa fa-angle-double-left ace-save-state"
            data-icon1="ace-icon fa fa-angle-double-left" data-icon2="ace-icon fa fa-angle-double-right"></i>
    </div><!-- ./sidebar-toggle -->

</div><!-- /.sidebar -->
