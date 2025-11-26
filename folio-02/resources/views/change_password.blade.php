@extends('layouts.master')

@section('title', 'Change your Password')


@section('breadcrumbs')
    {{ Breadcrumbs::render('change_password.show') }}
@endsection

@section('page-content')
    <div class="page-header">
        <h1>Change Password</h1>
    </div>

    <div class="row">
        <div class="col-xs-12">
            <!-- PAGE CONTENT BEGINS -->
            
            <div class="col-xs-12 col-sm-6 widget-container-col">
                {!! Form::open([
                    'url' => route('change_password.store'),
                    'class' => 'form-horizontal',
                    'role' => 'form',
                    'method' => 'POST',
                    'id' => 'frmChangePassword',
                ]) !!}
                <div class="widget-box">
                    <div class="widget-body">
                        <div class="widget-main">
                            @include('partials.session_message')
                            <div class="space"></div>
                            <div class="form-group row required {{ $errors->has('current-password') ? 'has-error' : '' }}">
                                {!! Form::label('current-password', 'Current Password', ['class' => 'col-sm-4 control-label']) !!}
                                <div class="col-sm-8">
                                    {!! Form::password('current-password', ['class' => 'form-control', 'required' => 'required', 'maxlength' => 25]) !!}
                                    {!! $errors->first('current-password', '<p class="text-danger">:message</p>') !!}
                                </div>
                            </div>
                            <div class="form-group row required {{ $errors->has('new-password') ? 'has-error' : '' }}">
                                {!! Form::label('new-password', 'New Password', ['class' => 'col-sm-4 control-label']) !!}
                                <div class="col-sm-8">
                                    {!! Form::password('new-password', ['class' => 'form-control', 'required' => 'required', 'maxlength' => 25]) !!}
                                    {!! $errors->first('new-password', '<p class="text-danger">:message</p>') !!}
                                </div>
                            </div>
                            <div class="form-group row required {{ $errors->has('confirm-password') ? 'has-error' : '' }}">
                                {!! Form::label('confirm-password', 'Confirm Password', ['class' => 'col-sm-4 control-label']) !!}
                                <div class="col-sm-8">
                                    {!! Form::password('confirm-password', [
                                        'class' => 'form-control',
                                        'required' => 'required',
                                        'name' => 'new-password_confirmation',
                                        'maxlength' => 25,
                                    ]) !!}
                                    {!! $errors->first('confirm-password', '<p class="text-danger">:message</p>') !!}
                                </div>
                            </div>
                        </div>

                        <div class="widget-toolbox padding-8 clearfix">
                            <div class="clearfix center">
                                <button class="btn btn-sm btn-success btn-round" type="submit">
                                    <i class="ace-icon fa fa-save bigger-110"></i>
                                    Update Password
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
            
            <div class="col-xs-12 col-sm-6 widget-container-col">
                <div class="widget-box">
                    <div class="widget-header">
                        <h4 class="widget-title">Password Rules</h4>
                    </div>

                    <div class="widget-body">
                        <div class="widget-main">
                            <div class="alert alert-info">
                                <p>Your password must meet the following guidelines:</p>
                                <ul style="margin-left: 15px;">
                                    <li>be at least 12 characters and no more than 25</li>
                                    <li>contain one number from [0-9]</li>
                                    <li>contain one lowercase letter [a-z]</li>
                                    <li>contain one uppercase letter [A-Z]</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- PAGE CONTENT ENDS -->
        </div><!-- /.col -->
    </div><!-- /.row -->
@endsection


@section('page-plugin-scripts')
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
                    minlength: 12
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

            highlight: function(e) {
                $(e).closest('.form-group').removeClass('has-info').addClass('has-error');
            },

            success: function(e) {
                $(e).closest('.form-group').removeClass('has-error').addClass('has-info');
                $(e).remove();
            },

            errorPlacement: function(error, element) {
                error.insertAfter(element);
            }
        });
    </script>


@endsection
