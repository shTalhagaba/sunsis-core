@extends('layouts.master')

@section('title', 'Users')

@section('breadcrumbs')
    {{ Breadcrumbs::render('users.index') }}
@endsection

@section('page-content')
    <div class="page-header">
        <h1>Users</h1>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <!-- PAGE CONTENT BEGINS -->

            @can('create-system-user')
                <div class="clearfix">
                    <div class="pull-left tableTools-container">
                        <button class="btn btn-sm btn-bold btn-primary btn-round" type="button"
                            onclick="window.location.href='{{ route('users.create') }}'">
                            <i class="ace-icon fa fa-user-plus bigger-120"></i> Add New User
                        </button>
                    </div>
                </div>
                <div class="hr hr-12 hr-dotted"></div>
            @endcan

            @include('partials.session_message')

            <div class="widget-box transparent ui-sortable-handle collapsed">
                <div class="widget-header widget-header-small">
                    <h5 class="widget-title smaller">Search Filters</h5>
                    <div class="widget-toolbar">
                        <a title="Export view to Excel" href="{{ route('users.export') }}">
                            <i class="ace-icon fa fa-file-excel-o bigger-125"></i>
                        </a> &nbsp;
                        <a href="#" data-action="collapse"><i class="ace-icon fa fa-chevron-down bigger-125"></i></a>
                    </div>
                </div>
                @include('partials.filter_crumbs')
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
                            <th>User</th>
                            <th>Username</th>
                            <th>System User Type</th>
                            <th>System Access</th>
                            <th>Total Logins</th>
                            <th>Last Login</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users AS $user)
                            <tr class="" onclick="window.location.href='{{ route('users.show', $user) }}';"
                                onmouseover="if(window.viewrow_onmouseover){window.viewrow_onmouseover(this, arguments.length > 0 ? arguments[0] : window.event)};"
                                onmouseout="if(window.viewrow_onmouseout){window.viewrow_onmouseout(this, arguments.length > 0 ? arguments[0] : window.event)};">
                                <td>
                                    {{ $user->full_name }}<br>
                                    <span class="ace-icon fa fa-envelope"></span> {{ $user->primary_email }}
                                    @if ($user->user_type == App\Models\Lookups\UserTypeLookup::TYPE_EMPLOYER_USER)
                                        <br><i class="fa fa-building"></i> {{ $user->employer->legal_name }}
                                    @endif
                                    @if ($user->isOnline())
                                        <label class="pull-right label label-success">Online</label>
                                    @else
                                        <label class="pull-right label label-default">Offline</label>
                                    @endif
                                </td>
                                <td><code>{{ $user->username }}</code></td>
                                <td align="center">{{ $user->systemUserType->description }}</td>
                                <td align="center">{!! $user->web_access == '1'
                                    ? '<i class="fa fa-check green fa-lg"></i>'
                                    : '<i class="fa fa-remove red fa-lg"></i>' !!}</td>
                                <td class="center">{{ $user->authentications_count }}</td>
                                <td>{{ optional($user->latestAuth)->login_at }}<br>{{ optional($user->latestAuth)->ip_address }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6">No user found in the system.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="well well-sm">
                @include('partials.pagination', ['collection' => $users])
            </div>
            <!-- PAGE CONTENT ENDS -->
        </div><!-- /.col -->
    </div><!-- /.row -->
@endsection
