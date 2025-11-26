@extends('layouts.perspective.master')

@section('title', 'Edit Permission')

@section('page-plugin-styles')
<link rel="stylesheet" href="{{ asset('assets/css/toastr.min.css') }}" />
@endsection

@section('breadcrumbs')

@endsection

@section('page-content')
<div class="page-header">
   <h1>
      Edit permission
   </h1>
</div><!-- /.page-header -->
<div class="row">
    <div class="col-sm-12">
        <!-- PAGE CONTENT BEGINS -->
        <div class="well well-sm">
            <button class="btn btn-sm btn-white btn-primary btn-round" type="button" onclick="window.location.href='{{ route('perspective.support.view_permissions') }}'">
                <i class="ace-icon fa fa-arrow-left bigger-110"></i> Back
            </button>
        </div>

        @include('partials.session_message')

        <div class="row">
            <div class="col-sm-12">
                {!! Form::model($permission, ['url' => route('perspective.support.permissions.update', $permission->id), 'class' => 'form-horizontal', 'role' => 'form', 'method' => 'PATCH']) !!}
                    @include ('perspective.form-permission')
                {!! Form::close() !!}
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
@endsection

