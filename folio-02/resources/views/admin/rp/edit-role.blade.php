@extends('layouts.master')

@section('title', 'Edit Role')

@section('page-plugin-styles')
<link rel="stylesheet" href="{{ asset('assets/css/toastr.min.css') }}" />
@endsection

@section('breadcrumbs')
{{ Breadcrumbs::render('roles.edit', $role) }}
@endsection

@section('page-content')
<div class="page-header"><h1>Edit role</h1></div><!-- /.page-header -->
<div class="row">
    <div class="col-sm-12">
        <!-- PAGE CONTENT BEGINS -->

        <button class="btn btn-sm btn-white btn-default btn-round" type="button" onclick="window.location.href='{{ route('rp.index') }}'">
            <i class="ace-icon fa fa-arrow-left bigger-110"></i> Back
        </button>
        <div class="hr hr-12 hr-dotted"></div>

        @include('partials.session_message')

        <div class="row">
            <div class="col-sm-8">
                {!! Form::model($role, ['url' => route('roles.update', $role->id), 'class' => 'form-horizontal', 'role' => 'form', 'method' => 'PATCH', 'name' => 'frmUpdateRole']) !!}
                    @include ('admin.rp.form-role')
                {!! Form::close() !!}
            </div>
            <div class="col-sm-4">
                <div class="widget-box">
                    <div class="widget-header widget-header-flat">
                        <h4 class="widget-title">Other System Roles</h4>
                    </div>
                    <div class="widget-body">
                        <div class="widget-main">
                            <ul class="list-unstyled  spaced">
                                @forelse($roles AS $role)
                                <li>
                                    {{ $role->name }} {{ $role->description != '' ? '(' . $role->description . ')' : ''}}
                                </li>
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

    $("form[name=frmUpdateRole]").on('submit', function(){
        var form = $(this);
        form.find(':submit').attr("disabled", true);
        form.find(':submit').html('<i class="fa fa-spinner fa-spin"></i> Saving');
        return true;
    });
</script>
@endsection

