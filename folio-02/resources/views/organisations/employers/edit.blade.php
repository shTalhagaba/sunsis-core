@extends('layouts.master')

@section('title', 'Edit Employer')

@section('page-plugin-styles')
<link rel="stylesheet" href="{{ asset('assets/css/toastr.min.css') }}" />
@endsection

@section('breadcrumbs')
{{ Breadcrumbs::render('employers.edit', $organisation) }}
@endsection

@section('page-content')
<div class="page-header"><h1>Edit Employer</h1></div><!-- /.page-header -->
<div class="row">
    <div class="col-xs-12">
        <!-- PAGE CONTENT BEGINS -->
        <div class="row">
            <div class="col-xs-12">
                <button class="btn btn-sm btn-white btn-default btn-round" type="button" onclick="window.location.href='{{ route('employers.show', $organisation) }}'">
                    <i class="ace-icon fa fa-arrow-left bigger-110"></i> Cancel
                </button>
                <div class="hr hr-12 hr-dotted"></div>
            </div>
        </div>
        @include('partials.session_message')
        @include('partials.session_error')

        <div class="row">
            <div class="col-xs-12">
                <div class="space"></div>
                {!! Form::model($organisation->getAttributes(),
                [
                    'method' => 'PATCH',
                    'url' => route('employers.update', $organisation->id),
                    'class' => 'form-horizontal',
                    'role' => 'form'])
                !!}
                    @include('organisations.form')
                {!! Form::close() !!}
            </div><!-- /.span -->
        </div><!-- /.user-profile -->


        <!-- PAGE CONTENT ENDS -->
    </div><!-- /.col -->
</div><!-- /.row -->
@endsection
