@extends('layouts.perspective.master')

@section('title', 'Perspective Support - View Users')

@section('page-plugin-styles')
<link rel="stylesheet" href="{{ asset('assets/css/toastr.min.css') }}" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
@endsection

@section('breadcrumbs')

@endsection

@section('page-content')

<div class="row">
    <div class="col-xs-12">
        <!-- PAGE CONTENT BEGINS -->

        <div class="row">
            <div class="col-xs-12">
                <div class="well well-sm">
                    <button class="btn btn-sm btn-white btn-bold btn-primary btn-round" type="button"
                        onclick="window.location.href='{{ route('perspective.support.create_user') }}'">
                        <i class="ace-icon fa fa-user-plus bigger-120"></i> Add New User
                    </button>
                </div>
            </div>
        </div>

        @include('partials.session_message')

        <div class="widget-box transparent ui-sortable-handle collapsed">
            <div class="widget-header widget-header-small">
                <h5 class="widget-title smaller">Search Filters</h5>
                <div class="widget-toolbar">
                    <a href="#" data-action="collapse"><i class="ace-icon fa fa-chevron-down bigger-125"></i></a>
                </div>
            </div>
            <div class="widget-body">
                <div class="widget-main">
                    {!! Form::open(['url' => route('perspective.support.view_users'), 'class' => 'form-horizontal',
                    'method' => 'GET']) !!}
                    <div class="row">
                        <div style="float: none; padding-top: 5px;" class="col-sm-12 center-block">
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group row {{ $errors->has('firstnames') ? 'has-error' : ''}}">
                                        {!! Form::label('firstnames', 'Firstname(s)', ['class' => 'col-sm-4
                                        control-label no-padding-right']) !!}
                                        <div class="col-sm-8">
                                            {!! Form::text('firstnames', $filters->firstnames, ['class' => 'form-control
                                            col-xs-10 col-sm-5', 'maxlength' => '150']) !!}
                                            {!! $errors->first('firstnames', '<p class="text-danger">:message</p>') !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group row {{ $errors->has('surname') ? 'has-error' : ''}}">
                                        {!! Form::label('surname', 'Surname', ['class' => 'col-sm-4 control-label
                                        no-padding-right']) !!}
                                        <div class="col-sm-8">
                                            {!! Form::text('surname', $filters->surname, ['class' => 'form-control
                                            col-xs-10 col-sm-5', 'maxlength' => '150']) !!}
                                            {!! $errors->first('surname', '<p class="text-danger">:message</p>') !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group row {{ $errors->has('user_type') ? 'has-error' : ''}}">
                                        {!! Form::label('user_type', 'System User Type', ['class' => 'col-sm-4
                                        control-label no-padding-right']) !!}
                                        <div class="col-sm-8">
                                            {!! Form::select('user_type', \App\Models\LookupManager::getUserTypes(),
                                            $filters->user_type, ['class' => 'form-control', 'placeholder' => '']) !!}
                                            {!! $errors->first('user_type', '<p class="text-danger">:message</p>') !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group row {{ $errors->has('email') ? 'has-error' : ''}}">
                                        {!! Form::label('email', 'Email', ['class' => 'col-sm-4 control-label
                                        no-padding-right']) !!}
                                        <div class="col-sm-8">
                                            {!! Form::text('email', $filters->email, ['class' => 'form-control col-xs-10
                                            col-sm-5', 'maxlength' => '150']) !!}
                                            {!! $errors->first('email', '<p class="text-danger">:message</p>') !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="form-group row {{ $errors->has('sortBy') ? 'has-error' : ''}}">
                                        {!! Form::label('sortBy', 'Sort By', ['class' => 'col-sm-4 control-label
                                        no-padding-right']) !!}
                                        <div class="col-sm-8">
                                            {!! Form::select('sortBy', ['surname' => 'Surname', 'firstnames' => 'First
                                            Name', 'created_at' => 'Creation Date'], $filters->sortBy, ['class' =>
                                            'form-control']) !!}
                                            {!! $errors->first('sortBy', '<p class="text-danger">:message</p>') !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group row {{ $errors->has('orderBy') ? 'has-error' : ''}}">
                                        {!! Form::label('orderBy', 'Sort By', ['class' => 'col-sm-4 control-label
                                        no-padding-right']) !!}
                                        <div class="col-sm-8">
                                            {!! Form::select('orderBy', ['ASC' => 'Ascending', 'DESC' => 'Descending'],
                                            $filters->orderBy, ['class' => 'form-control']) !!}
                                            {!! $errors->first('orderBy', '<p class="text-danger">:message</p>') !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="form-group row {{ $errors->has('perPage') ? 'has-error' : ''}}">
                                        {!! Form::label('perPage', 'Records per Page', ['class' => 'col-sm-4
                                        control-label']) !!}
                                        <div class="col-sm-8">
                                            {!! Form::select('perPage', \App\Models\LookupManager::getPerPageDDL(),
                                            $filters->perPage, ['class' => 'form-control']) !!}
                                            {!! $errors->first('perPage', '<p class="text-danger">:message</p>') !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="clearfix form-actions center">

                        <button class="btn btn-sm btn-success btn-round" type="submit">
                            <i class="ace-icon fa fa-search bigger-110"></i>
                            Search
                        </button>

                        &nbsp; &nbsp; &nbsp;
                        <button class="btn btn-sm btn-round" type="reset">
                            <i class="ace-icon fa fa-undo bigger-110"></i>
                            Reset
                        </button>

                    </div>
                    {!! Form::close() !!}

                </div>
            </div>
        </div>

        <div class="table-responsive">
            <table id="tblUsers" class="table table-striped table-bordered table-hover">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>System User Type</th>
                        <th>System Access</th>
                        <th>Total Logins</th>
                        <th>Impersonate</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users AS $user)
                    <tr>
                        <td>
                            {{ $user->surname }}, {{ $user->firstnames }}
                            <br><span class="ace-icon fa fa-user"></span>{{ $user->email }}
                            @if ($user->isOnline())
                            <label class="pull-right label label-success">Online</label>
                            @else
                            <label class="pull-right label label-default">Offline</label>
                            @endif
                        </td>
                        <td align="center">{{ $user->user_type }}</td>
                        <td align="center">{!! $user->web_access == '1' ? '<i class="fa fa-check green fa-lg"></i>' : '<i class="fa fa-remove red fa-lg"></i>' !!}</td>
                        <td class="center">{{ $user->authentications->count() }}</td>
                        <td>
                            {!! Form::open(['route' => ['perspective.support.impersonate', $user] ]) !!}
                            {!! Form::button('<i class="ace-icon fa fa-sign-in"></i> Login As', ['class' => 'btn
                            btn-primary btn-round btn-xs', 'type' => 'submit']) !!}
                            {!! Form::close() !!}
                        </td>
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
            {{ $users->appends($_GET)->links() }}<br>
            Showing <strong>{{ ($users->currentpage()-1)*$users->perpage()+1 }}</strong> to
            <strong>{{ $users->currentpage()*$users->perpage() > $users->total() ? $users->total() : $users->currentpage()*$users->perpage() }}</strong>
            of <strong>{{ $users->total() }}</strong> entries
        </div>

        <!-- PAGE CONTENT ENDS -->
    </div><!-- /.col -->
</div><!-- /.row -->
@endsection


@section('page-plugin-scripts')
<script src="{{ asset('assets/js/toastr.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
@endsection

@section('page-inline-scripts')
<script>


</script>

@endsection
