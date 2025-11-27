@extends('layouts.master')

@section('title', 'Change your Password')

@section('page-plugin-styles')
<link rel="stylesheet" href="{{ asset('assets/css/toastr.min.css') }}" />

@endsection

@section('breadcrumbs')
{{ Breadcrumbs::render('change_password.show') }}
@endsection

@section('page-content')
<div class="page-header"><h1>Change Password<small><i class="ace-icon fa fa-angle-double-right"></i>update your password</small></h1></div><!-- /.page-header -->

<div class="row">
   <div class="col-xs-12">
        <!-- PAGE CONTENT BEGINS -->
        @include('partials.session_message')

        <div class="row">
            <div class="col-xs-12">
                <div class="space"></div>
                {!! Form::open([
                    'url' => route('change_password.store'),
                    'class' => 'form-horizontal',
                    'role' => 'form',
                    'method' => 'POST',
                    'id' => 'frmChangePassword'
                    ]) !!}
                        <div class="form-group row required {{ $errors->has('current-password') ? 'has-error' : ''}}">
                            {!! Form::label('current-password', 'Current Password', ['class' => 'col-sm-4 control-label']) !!}
                            <div class="col-sm-4">
                                {!! Form::password('current-password', ['class' => 'form-control', 'required' => 'required']) !!}
                                {!! $errors->first('current-password', '<p class="text-danger">:message</p>') !!}
                            </div>
                        </div>
                        <div class="form-group row required {{ $errors->has('new-password') ? 'has-error' : ''}}">
                            {!! Form::label('new-password', 'New Password', ['class' => 'col-sm-4 control-label']) !!}
                            <div class="col-sm-4">
                                {!! Form::password('new-password', ['class' => 'form-control', 'required' => 'required']) !!}
                                {!! $errors->first('new-password', '<p class="text-danger">:message</p>') !!}
                            </div>
                        </div>
                        <div class="form-group row required {{ $errors->has('confirm-password') ? 'has-error' : ''}}">
                            {!! Form::label('confirm-password', 'Confirm Password', ['class' => 'col-sm-4 control-label']) !!}
                            <div class="col-sm-4">
                                {!! Form::password('confirm-password', ['class' => 'form-control', 'required' => 'required', 'name' => 'new-password_confirmation']) !!}
                                {!! $errors->first('confirm-password', '<p class="text-danger">:message</p>') !!}
                            </div>
                        </div>
                        <div class="clearfix form-actions center">

                            <button class="btn btn-sm btn-success btn-round" type="submit">
                                <i class="ace-icon fa fa-save bigger-110"></i>
                                Save
                            </button>

                            &nbsp; &nbsp; &nbsp;
                            <button class="btn btn-sm btn-round" type="reset">
                                <i class="ace-icon fa fa-undo bigger-110"></i>
                                Reset
                            </button>

                        </div>
                {!! Form::close() !!}
            </div><!-- /.span -->
        </div><!-- /.user-profile -->

      <!-- PAGE CONTENT ENDS -->
   </div><!-- /.col -->
</div><!-- /.row -->
@endsection


@section('page-plugin-scripts')
<script src="{{ asset('assets/js/toastr.min.js') }}"></script>
<script src="{{ asset('assets/js/jquery.validate.min.js') }}"></script>
@endsection

@section('page-inline-scripts')

<script>
    $('#frmChangePassword').validate({
        errorElement: 'div',
        errorClass: 'help-block',
        focusInvalid: false,
        rules: {
            "new-password": {
                minlength: 8
            },
            "new-password_confirmation": {
                equalTo: "#new-password"
            }
        },

        messages: {
            "current-password": {
                required: "Please enter your current password."
            },
            "new-password": {
                required: "Please enter your new password."
            },
            "new-password_confirmation": {
                required: "Please confirm your new password."
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
            if(element.is('input[type=checkbox]') || element.is('input[type=radio]')) {
                var controls = element.closest('div[class*="col-"]');
                if(controls.find(':checkbox,:radio').length > 1) controls.append(error);
                else error.insertAfter(element.nextAll('.lbl:eq(0)').eq(0));
            }
            else
                error.insertAfter(element);
        }
    });
</script>


@endsection

