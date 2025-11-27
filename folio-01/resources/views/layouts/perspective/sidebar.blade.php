<div class="sidebar responsive compact" id="sidebar">

    <div class="sidebar-shortcuts" id="sidebar-shortcuts">
        <div class="sidebar-shortcuts-large" id="sidebar-shortcuts-large">
            <button title="Go to homepage" class="btn btn-success" type="button"
                onclick="window.location.href='{{ route('perspective.support.home') }}'">
                <i class="ace-icon fa fa-home"></i>
            </button>
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
        <li>
            <a href="{{ route('perspective.support.home') }}">
                <i class="menu-icon fa fa-home"></i><span class="menu-text"> Home </span><b
                    class="arrow fa fa-angle-down"></b>
            </a>
        </li>
        <li>
            <a href="{{ route('perspective.support.view_permissions') }}">
                <i class="menu-icon fa fa-key"></i><span class="menu-text"> Permissions </span><b
                    class="arrow fa fa-angle-down"></b>
            </a>
        </li>
        <li>
            <a href="{{ route('perspective.support.view_licenses') }}">
                <i class="menu-icon fa fa-credit-card"></i><span class="menu-text"> Licenses </span><b
                    class="arrow fa fa-angle-down"></b>
            </a>
        </li>
        <li>
            <a href="{{ route('perspective.support.view_users') }}">
                <i class="menu-icon fa fa-users"></i><span class="menu-text"> Users </span><b
                    class="arrow fa fa-angle-down"></b>
            </a>
        </li>
    </ul><!-- ./nav nav-list -->

    <div class="sidebar-toggle sidebar-collapse">
        <i id="sidebar-toggle-icon" class="ace-icon fa fa-angle-double-left ace-save-state"
            data-icon1="ace-icon fa fa-angle-double-left" data-icon2="ace-icon fa fa-angle-double-right"></i>
    </div><!-- ./sidebar-toggle -->

</div><!-- /.sidebar -->
