@extends('layouts.master')

@section('title', 'Create Question')


@section('page-content')
    <div class="page-header">
        <h1>
            Create Question
            <small>
                <i class="ace-icon fa fa-angle-double-right"></i>
                add new question into the course
            </small>
        </h1>
    </div><!-- /.page-header -->
    <div class="row">
        <div class="col-xs-12">
            <!-- PAGE CONTENT BEGINS -->
            <button class="btn btn-sm btn-white btn-default btn-round" type="button"
                onclick="window.location.href='{{ route('fs_courses.show', $fsCourse) }}'">
                <i class="ace-icon fa fa-arrow-left bigger-110"></i> Cancel
            </button>
            <div class="hr hr-12 hr-dotted"></div>

            @include('partials.session_message')
            @include('partials.session_error')

            <div id="row">
                <div class="col-xs-12">
                    <h3>Course</h3>
                    @include('fs_assessment.partials.course_details_panel', ['fsCourse' => $fsCourse])
                </div>
                <div class="col-xs-12">
                    <div class="space"></div>

                    {!! Form::open(['url' => route('fs_courses.questions.store', $fsCourse), 'class' => 'form-horizontal', 'files' => true]) !!}
                    @include('fs_assessment.questions.create_form')
                    {!! Form::close() !!}

                </div>
            </div>
            <!-- PAGE CONTENT ENDS -->
        </div>
    </div>
@endsection