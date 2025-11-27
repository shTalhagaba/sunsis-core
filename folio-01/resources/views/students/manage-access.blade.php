@extends('layouts.master')

@section('title', 'Manage Student Access')

@section('page-plugin-styles')
<link rel="stylesheet" href="{{ asset('assets/css/toastr.min.css') }}" />
@endsection

@section('breadcrumbs')
{{ Breadcrumbs::render('students.manage-access', $student) }}
@endsection

@section('page-content')
<div class="page-header"><h1>Manage Student Access</h1></div><!-- /.page-header -->
<div class="row">
    <div class="col-xs-12">
        <!-- PAGE CONTENT BEGINS -->

        <div class="row">
            <div class="col-xs-12">
                <div class="well well-sm">
                    <button class="btn btn-sm btn-white btn-primary btn-round" type="button" onclick="window.location.href='{{ route('students.show', $student) }}'">
                        <i class="ace-icon fa fa-arrow-left bigger-110"></i> Back
                    </button>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12">
                <div class="info-div info-div-striped">
                    <div class="info-div-row">
                        <div class="info-div-name"> Name </div>
                        <div class="info-div-value"><span>{{ $student->firstnames }} {{ $student->surname }}</span></div>
                        <div class="info-div-name"> Total Logins </div>
                        <div class="info-div-value">{{ $student->authentications->count() }}</div>
                        <div class="info-div-name"> Last Login At </div>
                        <div class="info-div-value"><span>{{ $student->lastLoginAt() }}</span></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="space-8"></div>

        <div class="row">
            <div class="col-xs-12">
                <div class="widget-box widget-color-green">
                    <div class="widget-header">
                        <h4 class="widget-title">Update Username</h4>
                    </div>
                    <div class="widget-body">
                        {!! Form::model($student, [
                            'method' => 'PATCH',
                            'url' => route('students.updateUsername', $student),
                            'class' => 'form-horizontal',
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
                                <i class="fa fa-hand-o-right"></i> <small>Student <b>{{ $student->full_name }}</b> will be logged out straightaway from all devices.</small> <br>
                                <i class="fa fa-hand-o-right"></i> <small>Password will be reset and emailed to this email.</small><br>
                                <i class="fa fa-hand-o-right"></i> <small>Student can then get password from that email and login.</small>
                             </div>
                             <div class="form-group row required {{ $errors->has('email') ? 'has-error' : ''}}">
                                {!! Form::label('email', 'Username Email', ['class' => 'col-sm-4 control-label']) !!}
                                <div class="col-sm-8">
                                    {!! Form::email('email', null, ['class' => 'form-control inputLimiter', 'required' => 'required', 'maxlength' => 191]) !!}
                                </div>
                            </div>
                        </div>
                        <div class="widget-toolbox padding-8 clearfix center">
                            <button class="btn btn-sm btn-success btn-round" type="submit"><i class="ace-icon fa fa-save bigger-110"></i>Update Username</button>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>

        <div class="space-8"></div>

        <div class="row">
            <div class="col-xs-12">
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
                        @if ( $student->authentications()->count() > 0 )
                        <div class="alert alert-info">
                            <i class="fa fa-info-circle"></i>
                            <small>
                                This student has {{ $student->authentications()->count() }} login(s) in the system and the last login was at {{ $student->lastLoginAt() }}
                                Student can use 'Forgot Password' functionality on Login page to reset the password.
                            </small>
                        </div>
                        @else
                        <div class="widget-main">
                            {!! Form::model($student, [
                                'method' => 'PATCH',
                                'url' => route('students.resetPassword', $student),
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
        </div>

        <div class="space-8"></div>

        <div class="row">
            <div class="col-xs-12">
                <div class="widget-box widget-color-green">
                    <div class="widget-header">
                        <h4 class="widget-title">Enable/Disable Student Access to System</h4>
                    </div>
                    <div class="widget-body">
                        {!! Form::model($student, [
                            'method' => 'PATCH',
                            'url' => route('students.updateWebAccess', $student),
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
                                <i class="fa fa-info-circle"></i> Use this feature to enable or disable student access to the system.<br>
                                @if($student->web_access == '1')
                                <i class="fa fa-hand-o-right"></i> <small>Student <b>{{ $student->full_name }}</b> can currenlty access the system. Use this feature to disable access for the student.</small> <br>
                                @else
                                <i class="fa fa-hand-o-right"></i> <small>Student <b>{{ $student->firstnames }} {{ $student->surname }}</b> cannot currenlty access the system.</small> <br>
                                <i class="fa fa-hand-o-right"></i> <small>Use this feature to enable access for the student.</small><br>
                                @endif
                            </div>
                        </div>
                        <div class="widget-toolbox clearfix padding-8 center">
                            @if($student->web_access == '1')
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
        </div>

      <!-- PAGE CONTENT ENDS -->
   </div><!-- /.col -->
</div><!-- /.row -->
@endsection


@section('page-plugin-scripts')
<script src="{{ asset('assets/js/toastr.min.js') }}"></script>
<script src="{{ asset('assets/js/jquery.inputlimiter.min.js') }}"></script>
<script src="{{ asset('assets/js/jquery.validate.min.js') }}"></script>
@endsection

@section('page-inline-scripts')

   <script type="text/javascript">

    $('.inputLimiter').inputlimiter();

    $('#frmUpdateUserUsername').validate({
        errorElement: 'div',
        errorClass: 'help-block',
        focusInvalid: false,

        messages: {
            email: {
                required: "Please provide a valid email.",
                email: "Please provide a valid email."
            }
        },

        highlight: function (e) {
            $(e).closest('.form-group').removeClass('has-info').addClass('has-error');
        },

        success: function (e) {
            $(e).closest('.form-group').removeClass('has-error').addClass('has-info');
            $(e).remove();
        },

        errorPlacement: function (error, element) {
            error.insertAfter(element);
        },

        submitHandler: function(form) {
            bootbox.confirm({
                title: "Confirmation",
                message: "Are you sure you want to continue?",
                buttons: {
                    cancel: {
                        label: '<i class="fa fa-times"></i> Cancel'
                    },
                    confirm: {
                        label: '<i class="fa fa-check"></i> Confirm',
                        className: 'btn-success'
                    }
                },
                callback: function(result) {
                    if(result)
                       form.submit();
                }
            });
        }
    });

    $("button[id=btnDisableAccess], button[id=btnEnableAccess], button[id=btnResetPassword]").on('click', function(e){
        e.preventDefault();
        form = $(this).closest('form');
        bootbox.confirm({
            title: "Confirmation",
            message: "Are you sure you want to continue?",
            buttons: {
                cancel: {
                    label: '<i class="fa fa-times"></i> Cancel'
                },
                confirm: {
                    label: '<i class="fa fa-check"></i> Confirm',
                    className: 'btn-success'
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

