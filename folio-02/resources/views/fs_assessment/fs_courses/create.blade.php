@extends('layouts.master')

@section('title', 'Create FS Course')


@section('page-content')
    <div class="page-header">
        <h1>
            Create FS Course
            <small>
                <i class="ace-icon fa fa-angle-double-right"></i>
                add new functional skills course in the system
            </small>
        </h1>
    </div><!-- /.page-header -->
    <div class="row">
        <div class="col-xs-12">
            <!-- PAGE CONTENT BEGINS -->
            <button class="btn btn-sm btn-white btn-default btn-round" type="button"
                onclick="window.location.href='{{ route('fs_courses.index') }}'">
                <i class="ace-icon fa fa-arrow-left bigger-110"></i> Cancel
            </button>
            <div class="hr hr-12 hr-dotted"></div>

            @include('partials.session_message')
            @include('partials.session_error')

            <div class="row">
                <div class="col-xs-12">
                    <div class="space"></div>

                    {!! Form::open(['url' => route('fs_courses.store'), 'class' => 'form-horizontal']) !!}
                    @include('fs_assessment.fs_courses.form')
                    {!! Form::close() !!}

                </div>
            </div>
            <!-- PAGE CONTENT ENDS -->
        </div>
    </div>
@endsection