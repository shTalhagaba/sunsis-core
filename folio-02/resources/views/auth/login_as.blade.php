@extends('layouts.master')

@section('title', 'Login As')

@section('page-content')
<div class="page-header">
    <h1>Login As Other Account
        <small><i class="ace-icon fa fa-angle-double-right"></i> login as your other linked account</small>
    </h1>
</div><!-- /.page-header -->

<div class="row">
    <div class="col-xs-12">
        <!-- PAGE CONTENT BEGINS -->
        <button class="btn btn-sm btn-white btn-default btn-round" type="button" onclick="window.location.href='{{ route('home') }}'">
            <i class="ace-icon fa fa-arrow-left bigger-110"></i> Cancel
        </button>
        <div class="hr hr-12 hr-dotted"></div>

        <div class="space-12"></div>

        @include('partials.session_message')

        @include('partials.session_error')

        <div class="row">
            <div class="col-sm-12">
                {!! Form::open([
                    'url' => route('login_as.execute'),
                    'class' => 'form-horizontal',
                    'role' => 'form',
                    'method' => 'POST',
                    'id' => 'frmLoginAs',
                    'name' => 'frmLoginAs',
                ]) !!}
                <div class="widget-box">
                    <div class="widget-body">
                        <div class="widget-main">
                            <div class="alert alert-info center">
                                You have other linked accounts in the system, select your linked account from the list and enter that account password to switch session.
                                You will be logged out from the current session and logged in as your selected linked account.
                            </div>
                            <div class="form-group row required {{ $errors->has('linked_account_id') ? 'has-error' : '' }}">
                                {!! Form::label('linked_account_id', 'Select Linked Account', ['class' => 'col-sm-4 control-label']) !!}
                                <div class="col-sm-8">
                                    {!! Form::select('linked_account_id', $linkedUsersList, null, [
                                        'class' => 'form-control',
                                        'required',
                                        'placeholder' => '',
                                    ]) !!}
                                    {!! $errors->first('linked_account_id', '<p class="text-danger">:message</p>') !!}
                                </div>
                            </div>
                            <div class="form-group row required {{ $errors->has('linked_account_password') ? 'has-error' : '' }}">
                                {!! Form::label('linked_account_password', 'Enter Linked Account Password', ['class' => 'col-sm-4 control-label']) !!}
                                <div class="col-sm-8">
                                    {!! Form::password('linked_account_password', ['class' => 'form-control', 'required' => 'required', 'maxlength' => 25]) !!}
                                    {!! $errors->first('linked_account_password', '<p class="text-danger">:message</p>') !!}
                                </div>
                            </div>
                        </div>

                        <div class="widget-toolbox padding-8 clearfix">
                            <div class="clearfix center">
                                <button class="btn btn-sm btn-success btn-round" type="submit">
                                    <i class="ace-icon fa fa-sign-in bigger-110"></i>
                                    Sign In
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div>

        <!-- PAGE CONTENT ENDS -->
    </div><!-- /.col -->
</div><!-- /.row -->
@endsection


@section('page-inline-scripts')
<script>
    $("form[name=frmLoginAs]").on('submit', function(){
        var form = $(this);
        form.find(':submit').attr("disabled", true);
        form.find(':submit').html('<i class="fa fa-spinner fa-spin"></i> Signing In');
        return true;
    });
</script>
@endsection
