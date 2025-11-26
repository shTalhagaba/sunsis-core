@extends('layouts.master')

@section('title', 'Edit Course')

@section('page-content')
    <div class="page-header">
        <h1>
            Edit Course
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
                    <div class="space"></div>

                    @if($usedInTests)
                    <div class="alert alert-warning text-center bolder">
                        <i class="fa fa-warning"></i> 
                        This course has already been completed by learners. Editing its details may cause inconsistencies in existing test records. 
                        Please proceed with caution.                         
                    </div>
                    @endif

                    {!! Form::model($fsCourse->getAttributes(), [
                        'method' => 'PATCH',
                        'url' => route('fs_courses.update', $fsCourse),
                        'class' => 'form-horizontal',
                        'role' => 'form',
                        'id' => 'frmQuestion',
                    ]) !!}

                    @include('fs_assessment.fs_courses.form')

                    {!! Form::close() !!}

                </div><!-- /.span -->
            </div>


            <!-- PAGE CONTENT ENDS -->
        </div><!-- /.col -->
    </div><!-- /.row -->
@endsection


@section('page-plugin-scripts')
    <script src="{{ asset('assets/js/toastr.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.inputlimiter.min.js') }}"></script>
@endsection

@section('page-inline-scripts')

    <script type="text/javascript">
        $('.inputLimiter').inputlimiter();
    </script>

@endsection
