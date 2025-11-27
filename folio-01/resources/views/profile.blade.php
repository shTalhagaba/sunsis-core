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
                                            <span class="middle">{{ $user->firstnames }} {{ $user->surname }}</span>
                                            <span class="label label-purple arrowed-in-right">{{ $user->user_type }}</span>
                                        </h4>
                                        <div class="profile-user-info">
                                            <div class="profile-info-row">
                                                <div class="profile-info-name"> Username </div>
                                                <div class="profile-info-value"><span><code>{{ $user->email }}</code></span></div>
                                            </div>
                                            <div class="profile-info-row">
                                                <div class="profile-info-name"> Home Address </div>
                                                <div class="profile-info-value">
                                                    {!! $home_address->address_line_1 != '' ? $home_address->address_line_1 . '<br>' : '' !!}
                                                    {!! $home_address->address_line_2 != '' ? $home_address->address_line_2 . '<br>' : '' !!}
                                                    {!! $home_address->address_line_3 != '' ? $home_address->address_line_3 . '<br>' : '' !!}
                                                    {!! $home_address->address_line_4 != '' ? $home_address->address_line_4 . '<br>' : '' !!}
                                                    {!! $home_address->postcode != '' ? '<i class="fa fa-map-marker light-orange bigger-110"></i> <span>' . $home_address->postcode . '<br>' : '' !!}
                                                    {!! $home_address->telephone != '' ? '<i class="fa fa-phone light-orange bigger-110"></i> <span>' . $home_address->telephone . '<br>' : '' !!}
                                                    {!! $home_address->mobile != '' ? '<i class="fa fa-mobile light-orange bigger-110"></i> <span>' . $home_address->mobile . '<br>' : '' !!}
                                                </div>
                                            </div>
                                            <div class="profile-info-row">
                                                <div class="profile-info-name"> Work Address </div>
                                                <div class="profile-info-value">
                                                    {!! $work_address->address_line_1 != '' ? $work_address->address_line_1 . '<br>' : '' !!}
                                                    {!! $work_address->address_line_2 != '' ? $work_address->address_line_2 . '<br>' : '' !!}
                                                    {!! $work_address->address_line_3 != '' ? $work_address->address_line_3 . '<br>' : '' !!}
                                                    {!! $work_address->address_line_4 != '' ? $work_address->address_line_4 . '<br>' : '' !!}
                                                    {!! $work_address->postcode != '' ? '<i class="fa fa-map-marker light-orange bigger-110"></i> <span>' . $work_address->postcode . '<br>' : '' !!}
                                                    {!! $work_address->telephone != '' ? '<i class="fa fa-phone light-orange bigger-110"></i> <span>' . $work_address->telephone . '<br>' : '' !!}
                                                    {!! $work_address->mobile != '' ? '<i class="fa fa-mobile light-orange bigger-110"></i> <span>' . $work_address->mobile . '<br>' : '' !!}
                                                </div>
                                            </div>
                                            <div class="profile-info-row">
                                                <div class="profile-info-name"> Record Created </div>
                                                <div class="profile-info-value"><span>{{ $user->created_at }}</span></div>
                                            </div>
                                            <div class="profile-info-row">
                                                <div class="profile-info-name"> Last Login </div>
                                                <div class="profile-info-value"><span>{{ $user->lastLoginAt() }} from <u>{{ $user->lastLoginIp() }}</u></span></div>
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
                                                                        {!! Form::text('firstnames', null, ['class' => 'form-control col-xs-8', 'required' => 'required', 'maxlength' => '100']) !!}
                                                                        {!! $errors->first('firstnames', '<p class="text-danger">:message</p>') !!}
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row required {{ $errors->has('surname') ? 'has-error' : ''}}">
                                                                    {!! Form::label('surname', 'Surname', ['class' => 'col-sm-4 control-label no-padding-right']) !!}
                                                                    <div class="col-sm-8">
                                                                        {!! Form::text('surname', null, ['class' => 'form-control col-xs-8', 'required' => 'required', 'maxlength' => '100']) !!}
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
                                                                <div class="form-group row {{ $errors->has('ethnicity') ? 'has-error' : ''}}">
                                                                    {!! Form::label('ethnicity', 'Ethnicity', ['class' => 'col-sm-4 control-label']) !!}
                                                                    <div class="col-sm-8">
                                                                        {!! Form::select('ethnicity', \App\Models\LookupManager::getEthnicities(), null, ['class' => 'form-control', 'placeholder' => '']) !!}
                                                                        {!! $errors->first('ethnicity', '<p class="text-danger">:message</p>') !!}
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row {{ $errors->has('ni') ? 'has-error' : ''}}">
                                                                    {!! Form::label('ni', 'National Insurance', ['class' => 'col-sm-4 control-label', 'maxlength' => '17']) !!}
                                                                    <div class="col-sm-8">
                                                                        {!! Form::text('ni', null, ['class' => 'form-control']) !!}
                                                                        {!! $errors->first('ni', '<p class="text-danger">:message</p>') !!}
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row {{ $errors->has('uln') ? 'has-error' : ''}}">
                                                                    {!! Form::label('uln', 'ULN (Unique Learner Number)', ['class' => 'col-sm-4 control-label', 'maxlength' => '10']) !!}
                                                                    <div class="col-sm-8">
                                                                        {!! Form::text('uln', null, ['class' => 'form-control']) !!}
                                                                        {!! $errors->first('uln', '<p class="text-danger">:message</p>') !!}
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
                                                        <div class="col-sm-12">
                                                            <div class="widget-box">
                                                                <div class="widget-header"><h4 class="smaller">Change Password</h4></div>
                                                                <div class="widget-body">
                                                                    <div class="widget-main">
                                                                        <div class="alert alert-info">
                                                                            <i class="fa fa-info-circle"></i> Leave these fields blank if you don't want to update your password.
                                                                        </div>
                                                                        <div class="form-group row {{ $errors->has('current-password') ? 'has-error' : ''}}">
                                                                            {!! Form::label('current-password', 'Current Password', ['class' => 'col-sm-4 control-label']) !!}
                                                                            <div class="col-sm-8">
                                                                                {!! Form::password('current-password', ['class' => 'form-control']) !!}
                                                                                {!! $errors->first('current-password', '<p class="text-danger">:message</p>') !!}
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group row {{ $errors->has('new-password') ? 'has-error' : ''}}">
                                                                            {!! Form::label('new-password', 'New Password', ['class' => 'col-sm-4 control-label']) !!}
                                                                            <div class="col-sm-8">
                                                                                {!! Form::password('new-password', ['class' => 'form-control']) !!}
                                                                                {!! $errors->first('new-password', '<p class="text-danger">:message</p>') !!}
                                                                            </div>
                                                                        </div>
                                                                        <div class="form-group row {{ $errors->has('confirm-password') ? 'has-error' : ''}}">
                                                                            {!! Form::label('confirm-password', 'Confirm Password', ['class' => 'col-sm-4 control-label']) !!}
                                                                            <div class="col-sm-8">
                                                                                {!! Form::password('confirm-password', ['class' => 'form-control', 'name' => 'new-password_confirmation']) !!}
                                                                                {!! $errors->first('confirm-password', '<p class="text-danger">:message</p>') !!}
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
                                                                        {!! Form::text('work_address_line_1', $work_address->address_line_1, ['class' => 'form-control']) !!}
                                                                        {!! $errors->first('work_address_line_1', '<p class="text-danger">:message</p>') !!}
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row {{ $errors->has('work_address_line_2') ? 'has-error' : ''}}">
                                                                    {!! Form::label('work_address_line_2', 'Line 2', ['class' => 'col-sm-4 control-label']) !!}
                                                                    <div class="col-sm-8">
                                                                        {!! Form::text('work_address_line_2', $work_address->address_line_2, ['class' => 'form-control']) !!}
                                                                        {!! $errors->first('work_address_line_2', '<p class="text-danger">:message</p>') !!}
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row {{ $errors->has('work_address_line_3') ? 'has-error' : ''}}">
                                                                    {!! Form::label('work_address_line_3', 'Line 3', ['class' => 'col-sm-4 control-label']) !!}
                                                                    <div class="col-sm-8">
                                                                        {!! Form::text('work_address_line_3', $work_address->address_line_3, ['class' => 'form-control']) !!}
                                                                        {!! $errors->first('work_address_line_3', '<p class="text-danger">:message</p>') !!}
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row {{ $errors->has('work_address_line_4') ? 'has-error' : ''}}">
                                                                    {!! Form::label('work_address_line_4', 'Line 4', ['class' => 'col-sm-4 control-label']) !!}
                                                                    <div class="col-sm-8">
                                                                        {!! Form::text('work_address_line_4', $work_address->address_line_4, ['class' => 'form-control']) !!}
                                                                        {!! $errors->first('work_address_line_4', '<p class="text-danger">:message</p>') !!}
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row {{ $errors->has('work_postcode') ? 'has-error' : ''}}">
                                                                    {!! Form::label('work_postcode', 'Postcode', ['class' => 'col-sm-4 control-label']) !!}
                                                                    <div class="col-sm-8">
                                                                        {!! Form::text('work_postcode', $work_address->postcode, ['class' => 'form-control']) !!}
                                                                        {!! $errors->first('work_postcode', '<p class="text-danger">:message</p>') !!}
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row {{ $errors->has('work_telephone') ? 'has-error' : ''}}">
                                                                    {!! Form::label('work_telephone', 'Telephone', ['class' => 'col-sm-4 control-label']) !!}
                                                                    <div class="col-sm-8">
                                                                        {!! Form::text('work_telephone', $work_address->telephone, ['class' => 'form-control', 'maxlength' => 20]) !!}
                                                                        {!! $errors->first('work_telephone', '<p class="text-danger">:message</p>') !!}
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row {{ $errors->has('work_mobile') ? 'has-error' : ''}}">
                                                                    {!! Form::label('work_mobile', 'Mobile', ['class' => 'col-sm-4 control-label']) !!}
                                                                    <div class="col-sm-8">
                                                                        {!! Form::text('work_mobile', $work_address->mobile, ['class' => 'form-control', 'maxlength' => 20]) !!}
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
                                                                        {!! Form::text('home_address_line_1', $home_address->address_line_1, ['class' => 'form-control']) !!}
                                                                        {!! $errors->first('home_address_line_1', '<p class="text-danger">:message</p>') !!}
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row {{ $errors->has('home_address_line_2') ? 'has-error' : ''}}">
                                                                    {!! Form::label('home_address_line_2', 'Line 2', ['class' => 'col-sm-4 control-label']) !!}
                                                                    <div class="col-sm-8">
                                                                        {!! Form::text('home_address_line_2', $home_address->address_line_2, ['class' => 'form-control']) !!}
                                                                        {!! $errors->first('home_address_line_2', '<p class="text-danger">:message</p>') !!}
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row {{ $errors->has('home_address_line_3') ? 'has-error' : ''}}">
                                                                    {!! Form::label('home_address_line_3', 'Line 3', ['class' => 'col-sm-4 control-label']) !!}
                                                                    <div class="col-sm-8">
                                                                        {!! Form::text('home_address_line_3', $home_address->address_line_3, ['class' => 'form-control']) !!}
                                                                        {!! $errors->first('home_address_line_3', '<p class="text-danger">:message</p>') !!}
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row {{ $errors->has('home_address_line_4') ? 'has-error' : ''}}">
                                                                    {!! Form::label('home_address_line_4', 'Line 4', ['class' => 'col-sm-4 control-label']) !!}
                                                                    <div class="col-sm-8">
                                                                        {!! Form::text('home_address_line_4', $home_address->address_line_4, ['class' => 'form-control']) !!}
                                                                        {!! $errors->first('home_address_line_4', '<p class="text-danger">:message</p>') !!}
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row {{ $errors->has('home_postcode') ? 'has-error' : ''}}">
                                                                    {!! Form::label('home_postcode', 'Postcode', ['class' => 'col-sm-4 control-label']) !!}
                                                                    <div class="col-sm-8">
                                                                        {!! Form::text('home_postcode', $home_address->postcode, ['class' => 'form-control']) !!}
                                                                        {!! $errors->first('home_postcode', '<p class="text-danger">:message</p>') !!}
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row {{ $errors->has('home_telephone') ? 'has-error' : ''}}">
                                                                    {!! Form::label('home_telephone', 'Telephone', ['class' => 'col-sm-4 control-label']) !!}
                                                                    <div class="col-sm-8">
                                                                        {!! Form::text('home_telephone', $home_address->telephone, ['class' => 'form-control', 'maxlength' => 20]) !!}
                                                                        {!! $errors->first('home_telephone', '<p class="text-danger">:message</p>') !!}
                                                                    </div>
                                                                </div>
                                                                <div class="form-group row {{ $errors->has('home_mobile') ? 'has-error' : ''}}">
                                                                    {!! Form::label('home_mobile', 'Mobile', ['class' => 'col-sm-4 control-label']) !!}
                                                                    <div class="col-sm-8">
                                                                        {!! Form::text('home_mobile', $home_address->mobile, ['class' => 'form-control', 'maxlength' => 20]) !!}
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
                                                <button class="btn btn-sm btn-success btn-round" type="submit"><i class="ace-icon fa fa-save bigger-110"></i>Save</button>
                                                &nbsp; &nbsp; &nbsp;
                                                <button class="btn btn-sm btn-round" type="reset"><i class="ace-icon fa fa-undo bigger-110"></i>Reset</button>
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
