@extends('layouts.master')

@section('title', 'Users')

@section('page-plugin-styles')
<link rel="stylesheet" href="{{ asset('assets/css/toastr.min.css') }}" />
@endsection

@section('breadcrumbs')
{{ Breadcrumbs::render('users.index') }}
@endsection

@section('page-content')
<div class="page-header"><h1>Users</h1></div><!-- /.page-header -->
<div class="row">
    <div class="col-xs-12">
        <!-- PAGE CONTENT BEGINS -->
        @can('add-system-user')
        <div class="well well-sm">
            <button class="btn btn-sm btn-white btn-bold btn-primary btn-round" type="button" onclick="window.location.href='{{ route('users.create') }}'">
                <i class="ace-icon fa fa-user-plus bigger-120"></i> Add New User
            </button>
        </div>
        @endcan
        @include('partials.session_message')
        <div class="widget-box transparent ui-sortable-handle collapsed">
            <div class="widget-header widget-header-small">
               <h5 class="widget-title smaller">Search Filters</h5>
               <div class="widget-toolbar">
                   <a title="Export view to Excel" href="{{ request()->url() . '/export' . str_replace(request()->url(), '', request()->fullUrl()) }}">
                    <i class="ace-icon fa fa-file-excel-o bigger-125"></i>
                   </a> &nbsp;
                   <a href="#" data-action="collapse"><i class="ace-icon fa fa-chevron-down bigger-125"></i></a>
                </div>
            </div>
            <div class="widget-body">
                <div class="widget-main">
                    <small> @include('admin.users.filter')</small>
                </div>
            </div>
        </div>
        <div class="table-header">List of system users</div>
        <div class="table-responsive">
            <table id="tblEmployers" class="table table-striped table-bordered table-hover">
                <thead>
                    <tr>
                        <th>User</th><th>System User Type</th><th>System Access</th><th>Permissions Count</th><th>Total Logins</th><th>Last Login</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users AS $user)
                    <tr class="" onclick="window.location.href='{{ route('users.show', $user) }}';"
                    onmouseover="if(window.viewrow_onmouseover){window.viewrow_onmouseover(this, arguments.length > 0 ? arguments[0] : window.event)};"
                    onmouseout="if(window.viewrow_onmouseout){window.viewrow_onmouseout(this, arguments.length > 0 ? arguments[0] : window.event)};">
                        <td>
                            {{ $user->surname }}, {{ $user->firstnames }}<br><span class="ace-icon fa fa-user"></span> {{ $user->email }}
                            @if ($user->isOnline())
                            <label class="pull-right label label-success">Online</label>
                            @else
                            <label class="pull-right label label-default">Offline</label>
                            @endif
                        </td>
                        <td align="center">{{ $user->user_type }}</td>
                        <td align="center">{!! $user->web_access == '1' ? '<i class="fa fa-check green fa-lg"></i>' : '<i class="fa fa-remove red fa-lg"></i>' !!}</td>
                        <td align="center">
                            @if($user->permissions->count() == 0)
                            <i>No permission has been granted to the user.</i>
                            @else
                            {{ $user->permissions->count() }}
                            @endif
                        </td>
                        <td class="center">{{ $user->authentications->count() }}</td>
                        <td>{{ $user->lastLoginAt() }}<br>{{ $user->lastLoginIp() }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="6">No user found in the system.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="well well-sm">
            {{ $users->appends($_GET)->links() }}<br>
            Showing <strong>{{ ($users->currentpage()-1)*$users->perpage()+1 }}</strong> to <strong>{{ $users->currentpage()*$users->perpage() > $users->total() ? $users->total() : $users->currentpage()*$users->perpage() }}</strong> of <strong>{{ $users->total() }}</strong> entries
        </div>
        <!-- PAGE CONTENT ENDS -->
    </div><!-- /.col -->
</div><!-- /.row -->
@endsection
