@extends('layouts.perspective.master')

@section('title', 'Create Permission')

@section('page-plugin-styles')
<link rel="stylesheet" href="{{ asset('assets/css/toastr.min.css') }}" />
@endsection

@section('breadcrumbs')

@endsection

@section('page-content')
<div class="page-header">
   <h1>
      Create a new permission
   </h1>
</div><!-- /.page-header -->
<div class="row">
    <div class="col-lg-8 col-md-8 col-sm-12">
        <!-- PAGE CONTENT BEGINS -->
        @include('partials.session_message')

        @include('partials.session_error')

        {!! Form::open(['url' => route('perspective.support.permissions.store'), 'class' => 'form-horizontal', 'role' => 'form']) !!}
            @include ('perspective.form-permission')
        {!! Form::close() !!}

        <!-- PAGE CONTENT ENDS -->
    </div><!-- /.col -->
</div><!-- /.row -->
@endsection
