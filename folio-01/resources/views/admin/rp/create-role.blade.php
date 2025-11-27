@extends('layouts.master')

@section('title', 'Create Role')

@section('page-plugin-styles')
@endsection

@section('breadcrumbs')
<div class="breadcrumbs ace-save-state" id="breadcrumbs">
    <ul class="breadcrumb">
        <li>
            <i class="ace-icon fa fa-home home-icon"></i>
            <a href="/">Home</a>
        </li>
        <li>
            <a href="{{ route('rp.index') }}">Roles & Permissions</a>
        </li>
        <li class="active">Create Role</li>
    </ul><!-- /.breadcrumb -->
</div>
@endsection

@section('page-content')
<div class="page-header">
    <h1>
        Create a new role
    </h1>
</div><!-- /.page-header -->
<div class="row">
    <div class="col-xs-12">
        <!-- PAGE CONTENT BEGINS -->
        <div class="well well-sm">
            <button class="btn btn-sm btn-white btn-primary btn-round" type="button"
                onclick="window.location.href='{{ route('rp.index') }}'">
                <i class="ace-icon fa fa-arrow-left bigger-110"></i> Back
            </button>
        </div>

        @include('partials.session_message')
        @include('partials.session_error')

        <div class="row">
            <div class="col-sm-8">
                {!! Form::open(['url' => route('roles.store'), 'class' => 'form-horizontal', 'role' => 'form']) !!}
                @include ('admin.rp.form-role')
                {!! Form::close() !!}
            </div>
            <div class="col-sm-4">
                <div class="widget-box">
                    <div class="widget-header widget-header-flat">
                        <h4 class="widget-title">Existing System Roles</h4>
                    </div>

                    <div class="widget-body">
                        <div class="widget-main">
                            <ul class="list-unstyled  spaced">
                                @forelse($roles AS $role)
                                <li><i class="ace-icon fa fa-user green"></i> {{ $role->name }}</li>
                                @empty
                                <li><i class="text-muted">No other roles found.</i></li>
                                @endforelse
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
<script src="{{ asset('assets/js/toastr.min.js') }}"></script>
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
</script>
@endsection
