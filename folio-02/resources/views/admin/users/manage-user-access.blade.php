@extends('layouts.master')

@section('title', 'Manage User Access')

@section('breadcrumbs')
{{ Breadcrumbs::render('users.manage-user-access', $user) }}
@endsection

@section('page-content')
<div class="page-header">
    <h1>Manage User Access</h1>
</div><!-- /.page-header -->
<div class="row">
    <div class="col-xs-12">
        <!-- PAGE CONTENT BEGINS -->
        <div class="row">
            <div class="col-sm-12">
                <button class="btn btn-sm btn-white btn-default btn-round" type="button"
                    onclick="window.location.href='{{ route('users.show', $user) }}'">
                    <i class="ace-icon fa fa-arrow-left bigger-110"></i> Cancel
                </button>
                <div class="hr hr-12 hr-dotted"></div>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12">
                <div class="info-div info-div-striped">
                    <div class="info-div-row">
                        <div class="info-div-name"> Name </div>
                        <div class="info-div-value"><span>{{ $user->firstnames }} {{ $user->surname }}</span></div>
                        <div class="info-div-name"> User Type </div>
                        <div class="info-div-value"><span class="label label-info">{{ $user->systemUserType->description }}</span></div>
                        <div class="info-div-name"> Total Logins </div>
                        <div class="info-div-value">{{ $user->authentications->count() }}</div>
                        <div class="info-div-name"> Last Login At </div>
                        <div class="info-div-value"><span>{{ optional($user->latestAuth)->login_at }}</span></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="space-8"></div>

        @include('partials.session_error')
        @include('partials.session_message')

        <div class="row">
            <div class="col-xs-6">
                <div class="row">
                    <div class="col-sm-12">
                        <div class="widget-box widget-color-green">
                            <div class="widget-header">
                                <h4 class="smaller">Update Username</h4>
                            </div>
                            <div class="widget-body">
                                {!! Form::model($user, [
                                    'method' => 'PATCH',
                                    'url' => route('users.updateUsername', $user),
                                    'class' => 'form-horizontal',
                                    'role' => 'form',
                                    'id' => 'frmUpdateUserUsername'
                                    ]) !!}
                                <div class="widget-main">
                                    @if(Session::has('message_username'))
                                    <div class="alert {{ Session::get('alert-class', 'alert-info') }}">
                                        <button class="close" data-dismiss="alert">
                                            <i class="ace-icon fa fa-times"></i>
                                        </button>
                                        <i class="ace-icon fa {{ Session::get('alert-icon', 'fa fa-check') }}"></i>
                                        {{ Session::get('message_username') }}
                                    </div>
                                    @endif
                                    <div class="alert alert-info">
                                        <i class="fa fa-info-circle"></i> Updating username email will result in the following actions:<br>
                                        <i class="fa fa-hand-o-right"></i> <small>User <b>{{ $user->firstnames }} {{ $user->surname }}</b> will be logged
                                            out straightaway from all devices.</small> <br>
                                        <i class="fa fa-hand-o-right"></i> <small>Password will be reset and emailed to this email.</small><br>
                                        <i class="fa fa-hand-o-right"></i> <small>User can then get password from that email and login.</small>
                                    </div>

                                    <div
                                        class="form-group row required {{ $errors->has('username') ? 'has-error' : ''}}">
                                        {!! Form::label('username', 'Username', ['class' => 'col-sm-4
                                        control-label']) !!}
                                        <div class="col-sm-8">
                                            @include('partials.username_input_control', ['onfocus' => false, 'onkeypress' => true])
                                        </div>
                                    </div>
                                </div>
                                <div class="widget-toolbox padding-8 clearfix">
                                    <div class="center">
                                        <button class="btn btn-sm btn-success btn-round" type="submit"><i class="ace-icon fa fa-save bigger-110"></i>Update Username</button>
                                    </div>
                                </div>
                                {!! Form::close() !!}
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="space"></div>
                        <div class="widget-box widget-color-green">
                            <div class="widget-header">
                                <h4 class="widget-title">Reset Password</h4>
                            </div>
                            <div class="widget-body">
                                @if(\Session::has('message_reset_password'))
                                <div class="alert {{ \Session::get('alert-class', 'alert-info') }}">
                                    <button class="close" data-dismiss="alert">
                                        <i class="ace-icon fa fa-times"></i>
                                    </button>
                                    <i class="ace-icon fa {{ \Session::get('alert-icon', 'fa fa-check') }}"></i>
                                    {{ \Session::get('message_reset_password') }}
                                </div>
                                @endif
                                @if ( false && $user->authentications()->count() > 0 )
                                <div class="alert alert-info">
                                    <i class="fa fa-info-circle"></i>
                                    <small>
                                        This user has {{ $user->authentications()->count() }} login(s) in the system and the last login was at {{ optional($user->latestAuth)->login_at }}
                                        User can use 'Forgot Password' functionality on Login page to reset the password.
                                    </small>
                                </div>
                                @else
                                <div class="widget-main">
                                    {!! Form::model($user, [
                                        'method' => 'PATCH',
                                        'url' => route('users.resetPassword', $user),
                                        'class' => 'form-horizontal',
                                        'id' => 'frmResetPassword'
                                        ]) !!}
                                    <button class="btn btn-sm btn-success btn-round" type="button" id="btnResetPassword">
                                        <i class="ace-icon fa fa-save bigger-110"></i>Send Reset Password Email
                                    </button>
                                    {!! Form::close() !!}
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-12">
                        <div class="space"></div>
                        <div class="widget-box widget-color-green">
                            <div class="widget-header">
                                <h4 class="widget-title">Enable/Disable User Access to System</h4>
                            </div>
                            <div class="widget-body">
                                {!! Form::model($user, [
                                    'method' => 'PATCH',
                                    'url' => route('users.updateWebAccess', $user),
                                    'class' => 'form-horizontal',
                                    'id' => 'frmEnableDisableAccess'
                                    ]) !!}
                                <div class="widget-main">
                                    @if(\Session::has('message_access'))
                                    <div class="alert {{ \Session::get('alert-class', 'alert-info') }}">
                                        <button class="close" data-dismiss="alert">
                                            <i class="ace-icon fa fa-times"></i>
                                        </button>
                                        <i class="ace-icon fa {{ \Session::get('alert-icon', 'fa fa-check') }}"></i>
                                        {{ \Session::get('message_access') }}
                                    </div>
                                    @endif
                                    <div class="alert alert-info">
                                        <i class="fa fa-info-circle"></i> Use this feature to enable or disable user ({{ $user->full_name }}) access to the system.<br>
                                        @if($user->web_access == '1')
                                        <i class="fa fa-hand-o-right"></i> <small>User <b>{{ $user->full_name }}</b> can currenlty access the system. Use this feature to disable access for the user.</small> <br>
                                        @else
                                        <i class="fa fa-hand-o-right"></i> <small>User <b>{{ $user->firstnames }} {{ $user->surname }}</b> cannot currenlty access the system.</small> <br>
                                        <i class="fa fa-hand-o-right"></i> <small>Use this feature to enable access for the user.</small><br>
                                        @endif
                                    </div>
                                </div>
                                <div class="widget-toolbox clearfix padding-8 center">
                                    @if($user->web_access == '1')
                                    <button class="btn btn-sm btn-danger btn-round " type="button" id="btnDisableAccess">
                                        <i class="ace-icon fa fa-toggle-off bigger-110"></i>
                                        Disable Access
                                    </button>
                                    @else
                                    <button class="btn btn-sm btn-success " type="button" id="btnEnableAccess">
                                        <i class="ace-icon fa fa-toggle-on bigger-110"></i>
                                        Enable Access
                                    </button>
                                    @endif
                                </div>
                                {!! Form::close() !!}
                            </div>
                        </div>
                    </div>

                    @if($user->isStaff())
                    <div class="col-sm-12">
                        <div class="space"></div>
                        @include('admin.users.partials.user_multi_account_link_panel', [
                            'linkedUserAccounts' => $linkedUserAccounts
                        ])
                    </div>
                    @endif
                    
                    @if ($user->user_type == App\Models\Lookups\UserTypeLookup::TYPE_EMPLOYER_USER)
                    <div class="col-sm-12">
                        <div class="space"></div>
                        <div class="widget-box widget-color-green">
                            <div class="widget-header">
                                <h4 class="widget-title">Link/Unlink with Assessors for Caseloading</h4>
                            </div>
                            <div class="widget-body">
                                <div class="widget-main table-responsive">
                                    @if(\Session::has('message_access'))
                                    <div class="alert {{ \Session::get('alert-class', 'alert-info') }}">
                                        <button class="close" data-dismiss="alert">
                                            <i class="ace-icon fa fa-times"></i>
                                        </button>
                                        <i class="ace-icon fa {{ \Session::get('alert-icon', 'fa fa-check') }}"></i>
                                        {{ \Session::get('message_unlink') }}
                                    </div>
                                    @endif
                                    <div class="alert alert-info">
                                        <i class="fa fa-info-circle"></i> Use this feature to link or unlink this employer user account with assessors for caseloading.<br>
                                        <i class="fa fa-hand-o-right"></i><small> Employer user can be linked with multiple assessors.</small><br>
                                    </div>
                                    <div class="space"></div>
                                    @if($employerLinkedAssessors->count() > 0)
                                    <p class="text-info">
                                        This employer user account ({{ $user->full_name }}) is currently linked with the following {{ $employerLinkedAssessors->count() }} {{ Str::plural('assessor', $employerLinkedAssessors->count()) }}.
                                    </p>
                                    <table class="table table-bordered">
                                        <tr><th>Assessor</th><th>Action</th></tr>
                                        @foreach($employerLinkedAssessors AS $employerLinkedAssessor)
                                        <tr>
                                            <td>
                                                <i class="fa fa-user"></i> {{ $employerLinkedAssessor->firstnames }} {{ $employerLinkedAssessor->surname }}<br>
                                                <i class="fa fa-envelope"></i> {{ $employerLinkedAssessor->primary_email }}<br>
                                                <code> {{ $employerLinkedAssessor->username }}</code>
                                            </td>
                                            <td>
                                                {!! Form::open([
                                                    'method' => 'POST',
                                                    'url' => route('employer_users.unlinkAssessor', ['employer_user' => $user]),
                                                    'class' => 'form-horizontal',
                                                    'name' => 'frmUnlinkAccount'
                                                    ]) !!}
                                                    {!! Form::hidden('employer_user_id', $user->id) !!}
                                                    {!! Form::hidden('assessor_id', $employerLinkedAssessor->id) !!}
                                                    <button class="btn btn-xs btn-primary btn-round" type="submit"><i class="fa fa-unlink"></i> Unlink</button>
                                                {!! Form::close() !!}
                                            </td>
                                        </tr>
                                        @endforeach
                                    </table>
                                    @else
                                    <p class="text-info">
                                        <i class="fa fa-info-circle"></i> This employer user account ({{ $user->full_name }}) is currently not linked with any assessors.
                                    </p>
                                    <div class="space"></div>
                                    @endif
                                    
                                    {!! Form::open([
                                        'method' => 'POST',
                                        'url' => route('employer_users.linkAssessor', ['employer_user' => $user]),
                                        'class' => 'form-horizontal',
                                        'name' => 'frmLinkAccount'
                                        ]) !!}
                                        {!! Form::hidden('employer_user_id', $user->id) !!}
                                        <div class="form-group row required {{ $errors->has('assessor_id') ? 'has-error' : '' }}">
                                            {!! Form::label('assessor_id', 'Select Assessor', ['class' => 'col-sm-4 control-label']) !!}
                                            <div class="col-sm-8">
                                                {!! Form::select('assessor_id', $assessorsList, null, [
                                                    'class' => 'form-control',
                                                    'required',
                                                    'placeholder' => '',
                                                ]) !!}
                                                {!! $errors->first('assessor_id', '<p class="text-danger">:message</p>') !!}
                                            </div>
                                        </div>
                                        <div class="clearfix center">
                                            <button class="btn btn-sm btn-success btn-round" type="submit">
                                                <i class="ace-icon fa fa-link bigger-110"></i>
                                                Link
                                            </button>
                                        </div>
                                    {!! Form::close() !!}
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    
                    @if ($user->user_type == App\Models\Lookups\UserTypeLookup::TYPE_MANAGER)
                    <div class="col-sm-12">
                        <div class="space"></div>
                        @include('admin.users.partials.user_caseload_panel', [
                            'userLinkedAccounts' => $managerLinkedAccounts,
                        ])
                    </div>
                    @endif

                </div>
            </div>
            <div class="col-xs-6">
                @if($user->isStaff())
                <div class="widget-box widget-color-green">
                    <div class="widget-header">
                        <h4 class="widget-title">User Permissions</h4>
                    </div>
                    <div class="widget-body">
                        {!! Form::model($user, [
                            'method' => 'PATCH',
                            'url' => route('users.updatePermissions', $user),
                            'class' => 'form-horizontal',
                            'id' => 'frmUpdateUserPermissions']) !!}
                        <div class="widget-main">
                            <div class="alert alert-info">
                                <i class="fa fa-info-circle"></i> Use this feature to grant or revoke user
                                permissions.<br>
                            </div>
                            <div class="table-responsive">
                                <table id="tblPermissions" class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th class="center">
                                                <label class="pos-rel">
                                                    <input type="checkbox" class="ace" />
                                                    <span class="lbl"></span>
                                                </label>
                                            </th>
                                            <th>Name</th>
                                            <th>Description</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($permissions AS $permission)
                                        <tr>
                                            <td class="center">
                                                <label class="pos-rel">
                                                    <input type="checkbox" class="ace" name="permissions[]"
                                                        value="{{ $permission->id }}"
                                                        {{ $user->hasPermissionTo($permission) ? 'checked' : '' }} />
                                                    <span class="lbl"></span>
                                                </label>
                                            </td>
                                            <td>{{ $permission->name }}</td>
                                            <td class="small">{{ $permission->description }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="widget-toolbox padding-8 center clearfix">
                            <button class="btn btn-sm btn-success btn-round" type="button" id="btnUpdateUserPemissions"><i
                                class="ace-icon fa fa-save bigger-110"></i>Update Permissions</button>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- PAGE CONTENT ENDS -->
    </div><!-- /.col -->
</div><!-- /.row -->
@endsection


@section('page-inline-scripts')

<script type="text/javascript">
    $('#tblPermissions > thead > tr > th input[type=checkbox]').eq(0).on('click', function(){
        var th_checked = this.checked;

        $(this).closest('table').find('tbody > tr').each(function(){
            var row = this;
            if(th_checked) $(row).find('input[type=checkbox]').eq(0).prop('checked', true);
            else $(row).find('input[type=checkbox]').eq(0).prop('checked', false);
        });
    });

    $("button[id=btnDisableAccess], button[id=btnEnableAccess], button[id=btnResetPassword], button[id=btnUpdateUserPemissions]").on('click', function(e){
        e.preventDefault();
        form = $(this).closest('form');
        bootbox.confirm({
            title: "Confirmation",
            message: "Are you sure you want to continue?",
            buttons: {
                cancel: {
                    label: '<i class="fa fa-times"></i> Cancel',
                    className: 'btn-xs btn-round'
                },
                confirm: {
                    label: '<i class="fa fa-check"></i> Confirm',
                    className: 'btn-success btn-xs btn-round'
                }
            },
            callback: function(result) {
                if(result)
                    form.submit();
            }
        });
    });

</script>

@endsection
