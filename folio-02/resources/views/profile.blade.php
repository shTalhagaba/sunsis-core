@extends('layouts.master')
@section('title', 'Profile')
@section('page-plugin-styles')
<link rel="stylesheet" href="{{ asset('assets/css/toastr.min.css') }}" />
@endsection
@section('breadcrumbs')
{{ Breadcrumbs::render('profile.show') }}
@endsection
@section('page-content')
<div class="page-header">
    <h1>Your Profile <small><i class="ace-icon fa fa-angle-double-right"></i> View/Edit your profile </small></h1>
</div>
<!-- /.page-header -->
<div class="row">
    <div class="col-xs-12">
        <!-- PAGE CONTENT BEGINS -->

        @include('partials.session_message')
        @include('partials.session_error')

        <div class="row">
            <div class="col-sm-12">
                <div id="user-profile-2" class="user-profile">
                    <div class="tabbable">
                        <ul class="nav nav-tabs padding-18">
                            <li class="active"><a data-toggle="tab" href="#home"><i class="green ace-icon fa fa-user bigger-120"></i>Profile</a></li>
                            <li><a data-toggle="tab" href="#edit-profile"><i class="orange ace-icon fa fa-edit bigger-120"></i>Edit</a></li>
                        </ul>
                        <div class="tab-content no-border padding-24">
                            <div id="home" class="tab-pane in active">
                                <div class="row">
                                    <div class="col-xs-12 col-sm-3 center">
                                        <span class="profile-picture">
                                            <img class="img-responsive" alt="{{ $user->firstnames}}'s Avatar" id="avatar2" src="{{ $user->avatar_url }}" />
                                        </span>
                                    </div>
                                    <div class="col-xs-12 col-sm-9">
                                        <h4 class="blue">
                                            <span class="middle">{{ $user->full_name }}</span>
                                            <span class="label label-purple arrowed-in-right">{{ \App\Models\Lookups\UserTypeLookup::find($user->user_type)->description }}</span>
                                        </h4>
                                        <div class="profile-user-info">
                                            <div class="profile-info-row">
                                                <div class="profile-info-name"> Username </div>
                                                <div class="profile-info-value"><span><code>{{ $user->username }}</code></span></div>
                                            </div>
                                            <div class="profile-info-row">
                                                <div class="profile-info-name"> Home Address </div>
                                                <div class="profile-info-value">
                                                    {!! $homeAddress->address_line_1 != '' ? $homeAddress->address_line_1 . '<br>' : '' !!}
                                                    {!! $homeAddress->address_line_2 != '' ? $homeAddress->address_line_2 . '<br>' : '' !!}
                                                    {!! $homeAddress->address_line_3 != '' ? $homeAddress->address_line_3 . '<br>' : '' !!}
                                                    {!! $homeAddress->address_line_4 != '' ? $homeAddress->address_line_4 . '<br>' : '' !!}
                                                    {!! $homeAddress->postcode != '' ? '<i class="fa fa-map-marker light-orange bigger-110"></i> <span>' . $homeAddress->postcode . '<br>' : '' !!}
                                                    {!! $homeAddress->telephone != '' ? '<i class="fa fa-phone light-orange bigger-110"></i> <span>' . $homeAddress->telephone . '<br>' : '' !!}
                                                    {!! $homeAddress->mobile != '' ? '<i class="fa fa-mobile light-orange bigger-110"></i> <span>' . $homeAddress->mobile . '<br>' : '' !!}
                                                </div>
                                            </div>
                                            <div class="profile-info-row">
                                                <div class="profile-info-name"> Work Address </div>
                                                <div class="profile-info-value">
                                                    {!! $workAddress->address_line_1 != '' ? $workAddress->address_line_1 . '<br>' : '' !!}
                                                    {!! $workAddress->address_line_2 != '' ? $workAddress->address_line_2 . '<br>' : '' !!}
                                                    {!! $workAddress->address_line_3 != '' ? $workAddress->address_line_3 . '<br>' : '' !!}
                                                    {!! $workAddress->address_line_4 != '' ? $workAddress->address_line_4 . '<br>' : '' !!}
                                                    {!! $workAddress->postcode != '' ? '<i class="fa fa-map-marker light-orange bigger-110"></i> <span>' . $workAddress->postcode . '<br>' : '' !!}
                                                    {!! $workAddress->telephone != '' ? '<i class="fa fa-phone light-orange bigger-110"></i> <span>' . $workAddress->telephone . '<br>' : '' !!}
                                                    {!! $workAddress->mobile != '' ? '<i class="fa fa-mobile light-orange bigger-110"></i> <span>' . $workAddress->mobile . '<br>' : '' !!}
                                                </div>
                                            </div>
                                            <div class="profile-info-row">
                                                <div class="profile-info-name"> Record Created </div>
                                                <div class="profile-info-value"><span>{{ $user->created_at }}</span></div>
                                            </div>
                                            <div class="profile-info-row">
                                                <div class="profile-info-name"> Last Login </div>
                                                <div class="profile-info-value">
                                                    <span>{{ optional($user->latestAuth)->login_at }} from
                                                        <u>{{ optional($user->latestAuth)->ip_address }}</u></span>
                                                </div>
                                            </div>
                                            <div class="profile-info-row">
                                                <div class="profile-info-name"><i class="middle ace-icon fa fa-facebook-square bigger-150 blue"></i></div>
                                                <div class="profile-info-value">
                                                    @if($user->fb_id != '')
                                                    <a target="_blank" href="https://www.facebook.com/{{ $user->fb_id }}">Find me on Facebook</a>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="profile-info-row">
                                                <div class="profile-info-name"><i class="middle ace-icon fa fa-twitter-square bigger-150 light-blue"></i></div>
                                                <div class="profile-info-value">
                                                    @if($user->twitter_handle != '')
                                                    <a target="_blank" href="https://twitter.com/{{ $user->twitter_handle }}">Follow me on Twitter</a>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>{{-- home tab --}}
                            <div id="edit-profile" class="tab-pane">
                                {!! Form::model($user->getAttributes(), [
                                    'method' => 'PATCH',
                                    'url' => route('profile.update', $user),
                                    'class' => 'form-horizontal',
                                    'role' => 'form',
                                    'id' => 'frmUpdateProfile',
                                    'files' => true]) !!}
                                <div class="widget-box widget-color-green">
                                    <div class="widget-header">
                                        <h4 class="widget-title">Your Profile Information</h4>
                                    </div>
                                    <div class="widget-body">
                                        <div class="widget-main">
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <div class="widget-box">
                                                        <div class="widget-header"><h4 class="smaller">Details</h4></div>
                                                        <div class="widget-body">
                                                            <div class="widget-main">
                                                                {!! Form::hidden('user_type', $user->getAttributes()['user_type']) !!}
                                                                <div class="form-group row required {{ $errors->has('firstnames') ? 'has-error' : ''}}">
                                                                    {!! Form::label('firstnames', 'Firstname(s)', ['class' => 'col-sm-4 control-label no-padding-right']) !!}
                                                                    <div class="col-sm-8">
                                                                        {!! Form::text('firstnames', null, ['class' => 'form-control col-xs-8', 'required' => 'required', 'maxlength' => '50']) !!}
                                                                        {!! $errors->first('firstnames', '<p class="text-danger">:message</p>') !!}
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row required {{ $errors->has('surname') ? 'has-error' : ''}}">
                                                                    {!! Form::label('surname', 'Surname', ['class' => 'col-sm-4 control-label no-padding-right']) !!}
                                                                    <div class="col-sm-8">
                                                                        {!! Form::text('surname', null, ['class' => 'form-control col-xs-8', 'required' => 'required', 'maxlength' => '50']) !!}
                                                                        {!! $errors->first('surname', '<p class="text-danger">:message</p>') !!}
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row required {{ $errors->has('gender') ? 'has-error' : ''}}">
                                                                    {!! Form::label('gender', 'Gender', ['class' => 'col-sm-4 control-label']) !!}
                                                                    <div class="col-sm-8">
                                                                        {!! Form::select('gender', array_merge(\App\Models\LookupManager::getGenderDDL(), ['U' => 'Unknown']), null, ['class' => 'form-control']) !!}
                                                                        {!! $errors->first('gender', '<p class="text-danger">:message</p>') !!}
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row {{ $errors->has('date_of_birth') ? 'has-error' : ''}}">
                                                                    {!! Form::label('date_of_birth', 'Date of Birth', ['class' => 'col-sm-4 control-label']) !!}
                                                                    <div class="col-sm-8">
                                                                        {!! Form::date('date_of_birth', null, ['class' => 'form-control']) !!}
                                                                        {!! $errors->first('date_of_birth', '<p class="text-danger">:message</p>') !!}
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row required {{ $errors->has('primary_email') ? 'has-error' : ''}}">
                                                                    {!! Form::label('primary_email', 'Primary Email', ['class' => 'col-sm-4 control-label', 'maxlength' => '191']) !!}
                                                                    <div class="col-sm-8">
                                                                        {!! Form::email('primary_email', null, ['class' => 'form-control', 'required' => 'required']) !!}
                                                                        {!! $errors->first('primary_email', '<p class="text-danger">:message</p>') !!}
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row {{ $errors->has('secondary_email') ? 'has-error' : ''}}">
                                                                    {!! Form::label('secondary_email', 'Secondary Email', ['class' => 'col-sm-4 control-label', 'maxlength' => '191']) !!}
                                                                    <div class="col-sm-8">
                                                                        {!! Form::email('secondary_email', null, ['class' => 'form-control']) !!}
                                                                        {!! $errors->first('secondary_email', '<p class="text-danger">:message</p>') !!}
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row {{ $errors->has('fb_id') ? 'has-error' : ''}}">
                                                                    {!! Html::decode(Form::label('fb_id', 'Facebook ID <i class="ace-icon fa fa-facebook-square blue"></i>', ['class' => 'col-sm-4 control-label', 'maxlength' => '191'])) !!}
                                                                    <div class="col-sm-8">
                                                                        {!! Form::text('fb_id', null, ['class' => 'form-control']) !!}
                                                                        {!! $errors->first('fb_id', '<p class="text-danger">:message</p>') !!}
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row {{ $errors->has('twitter_handle') ? 'has-error' : ''}}">
                                                                    {!! Html::decode(Form::label('twitter_handle', 'Twitter Handle <i class="ace-icon fa fa-twitter-square light-blue"></i>', ['class' => 'col-sm-4 control-label', 'maxlength' => '191'])) !!}
                                                                    <div class="col-sm-8">
                                                                        {!! Form::text('twitter_handle', null, ['class' => 'form-control']) !!}
                                                                        {!! $errors->first('twitter_handle', '<p class="text-danger">:message</p>') !!}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            <div class="widget-box">
                                                                <div class="widget-header"><h4 class="smaller">Profile Picture</h4></div>
                                                                <div class="widget-body">
                                                                    <div class="widget-main">
                                                                        <div class="user">
                                                                            <img width="50px;" height="50px;" src="{{ $user->avatar_url }}" alt="profile">
                                                                            <small class="text-info"><i class="fa fa-info-circle"></i> Max. Size: 2MB</small>
                                                                        </div>
                                                                        <div class="form-group row">
                                                                            {!! Form::label('avatar', __('Profile Picture'), ['class' => 'col-sm-4 control-label  text-sm-right']) !!}
                                                                            <div class="col-sm-8">
                                                                                <input type="file" class="form-control" name="avatar" id="avatar">
                                                                                {!! $errors->first('avatar', '<p class="text-danger">:message</p>') !!}
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-sm-6">
                                                    <div class="widget-box">
                                                        <div class="widget-header"><h4 class="smaller">Work Address</h4></div>
                                                        <div class="widget-body">
                                                            <div class="widget-main">
                                                                <div class="form-group row {{ $errors->has('work_address_line_1') ? 'has-error' : ''}}">
                                                                    {!! Form::label('work_address_line_1', 'Line 1', ['class' => 'col-sm-4 control-label']) !!}
                                                                    <div class="col-sm-8">
                                                                        {!! Form::text('work_address_line_1', $workAddress->address_line_1, ['class' => 'form-control', 'maxlength' => 50]) !!}
                                                                        {!! $errors->first('work_address_line_1', '<p class="text-danger">:message</p>') !!}
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row {{ $errors->has('work_address_line_2') ? 'has-error' : ''}}">
                                                                    {!! Form::label('work_address_line_2', 'Line 2', ['class' => 'col-sm-4 control-label']) !!}
                                                                    <div class="col-sm-8">
                                                                        {!! Form::text('work_address_line_2', $workAddress->address_line_2, ['class' => 'form-control', 'maxlength' => 50]) !!}
                                                                        {!! $errors->first('work_address_line_2', '<p class="text-danger">:message</p>') !!}
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row {{ $errors->has('work_address_line_3') ? 'has-error' : ''}}">
                                                                    {!! Form::label('work_address_line_3', 'Line 3', ['class' => 'col-sm-4 control-label']) !!}
                                                                    <div class="col-sm-8">
                                                                        {!! Form::text('work_address_line_3', $workAddress->address_line_3, ['class' => 'form-control', 'maxlength' => 50]) !!}
                                                                        {!! $errors->first('work_address_line_3', '<p class="text-danger">:message</p>') !!}
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row {{ $errors->has('work_address_line_4') ? 'has-error' : ''}}">
                                                                    {!! Form::label('work_address_line_4', 'Line 4', ['class' => 'col-sm-4 control-label']) !!}
                                                                    <div class="col-sm-8">
                                                                        {!! Form::text('work_address_line_4', $workAddress->address_line_4, ['class' => 'form-control', 'maxlength' => 50]) !!}
                                                                        {!! $errors->first('work_address_line_4', '<p class="text-danger">:message</p>') !!}
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row {{ $errors->has('work_postcode') ? 'has-error' : ''}}">
                                                                    {!! Form::label('work_postcode', 'Postcode', ['class' => 'col-sm-4 control-label']) !!}
                                                                    <div class="col-sm-8">
                                                                        {!! Form::text('work_postcode', $workAddress->postcode, ['class' => 'form-control', 'maxlength' => 15]) !!}
                                                                        {!! $errors->first('work_postcode', '<p class="text-danger">:message</p>') !!}
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row {{ $errors->has('work_telephone') ? 'has-error' : ''}}">
                                                                    {!! Form::label('work_telephone', 'Telephone', ['class' => 'col-sm-4 control-label']) !!}
                                                                    <div class="col-sm-8">
                                                                        {!! Form::text('work_telephone', $workAddress->telephone, ['class' => 'form-control', 'maxlength' => 20]) !!}
                                                                        {!! $errors->first('work_telephone', '<p class="text-danger">:message</p>') !!}
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row {{ $errors->has('work_mobile') ? 'has-error' : ''}}">
                                                                    {!! Form::label('work_mobile', 'Mobile', ['class' => 'col-sm-4 control-label']) !!}
                                                                    <div class="col-sm-8">
                                                                        {!! Form::text('work_mobile', $workAddress->mobile, ['class' => 'form-control', 'maxlength' => 20]) !!}
                                                                        {!! $errors->first('work_mobile', '<p class="text-danger">:message</p>') !!}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-6">
                                                    <div class="widget-box">
                                                        <div class="widget-header"><h4 class="smaller">Home Address</h4></div>
                                                        <div class="widget-body">
                                                            <div class="widget-main">
                                                                <div class="form-group row {{ $errors->has('home_address_line_1') ? 'has-error' : ''}}">
                                                                    {!! Form::label('home_address_line_1', 'Line 1', ['class' => 'col-sm-4 control-label']) !!}
                                                                    <div class="col-sm-8">
                                                                        {!! Form::text('home_address_line_1', $homeAddress->address_line_1, ['class' => 'form-control', 'maxlength' => 50]) !!}
                                                                        {!! $errors->first('home_address_line_1', '<p class="text-danger">:message</p>') !!}
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row {{ $errors->has('home_address_line_2') ? 'has-error' : ''}}">
                                                                    {!! Form::label('home_address_line_2', 'Line 2', ['class' => 'col-sm-4 control-label']) !!}
                                                                    <div class="col-sm-8">
                                                                        {!! Form::text('home_address_line_2', $homeAddress->address_line_2, ['class' => 'form-control', 'maxlength' => 50]) !!}
                                                                        {!! $errors->first('home_address_line_2', '<p class="text-danger">:message</p>') !!}
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row {{ $errors->has('home_address_line_3') ? 'has-error' : ''}}">
                                                                    {!! Form::label('home_address_line_3', 'Line 3', ['class' => 'col-sm-4 control-label']) !!}
                                                                    <div class="col-sm-8">
                                                                        {!! Form::text('home_address_line_3', $homeAddress->address_line_3, ['class' => 'form-control', 'maxlength' => 50]) !!}
                                                                        {!! $errors->first('home_address_line_3', '<p class="text-danger">:message</p>') !!}
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row {{ $errors->has('home_address_line_4') ? 'has-error' : ''}}">
                                                                    {!! Form::label('home_address_line_4', 'Line 4', ['class' => 'col-sm-4 control-label']) !!}
                                                                    <div class="col-sm-8">
                                                                        {!! Form::text('home_address_line_4', $homeAddress->address_line_4, ['class' => 'form-control', 'maxlength' => 50]) !!}
                                                                        {!! $errors->first('home_address_line_4', '<p class="text-danger">:message</p>') !!}
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row {{ $errors->has('home_postcode') ? 'has-error' : ''}}">
                                                                    {!! Form::label('home_postcode', 'Postcode', ['class' => 'col-sm-4 control-label']) !!}
                                                                    <div class="col-sm-8">
                                                                        {!! Form::text('home_postcode', $homeAddress->postcode, ['class' => 'form-control', 'maxlength' => 15]) !!}
                                                                        {!! $errors->first('home_postcode', '<p class="text-danger">:message</p>') !!}
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row {{ $errors->has('home_telephone') ? 'has-error' : ''}}">
                                                                    {!! Form::label('home_telephone', 'Telephone', ['class' => 'col-sm-4 control-label']) !!}
                                                                    <div class="col-sm-8">
                                                                        {!! Form::text('home_telephone', $homeAddress->telephone, ['class' => 'form-control', 'maxlength' => 20]) !!}
                                                                        {!! $errors->first('home_telephone', '<p class="text-danger">:message</p>') !!}
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row {{ $errors->has('home_mobile') ? 'has-error' : ''}}">
                                                                    {!! Form::label('home_mobile', 'Mobile', ['class' => 'col-sm-4 control-label']) !!}
                                                                    <div class="col-sm-8">
                                                                        {!! Form::text('home_mobile', $homeAddress->mobile, ['class' => 'form-control', 'maxlength' => 20]) !!}
                                                                        {!! $errors->first('home_mobile', '<p class="text-danger">:message</p>') !!}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="widget-toolbox padding-8 clearfix">
                                            <div class="center">
                                                <button class="btn btn-sm btn-success btn-round" type="submit"><i class="ace-icon fa fa-save bigger-110"></i> Save Information</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                  {!! Form::close() !!}
                            </div>{{-- edit-info tab --}}
                        </div>
                    </div>{{-- tabbable --}}
                </div>
            </div>
        </div>
        <!-- PAGE CONTENT ENDS -->
    </div>
    <!-- /.col -->
</div>
<!-- /.row -->
@endsection
@section('page-plugin-scripts')
<script src="{{ asset('assets/js/toastr.min.js') }}"></script>
<script src="{{ asset('assets/js/jquery.validate.min.js') }}"></script>
@endsection

@section('page-inline-scripts')
<script type="text/javascript">
$('#frmUpdateProfile').validate({
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
