@extends('layouts.master')

@section('title', 'Edit Question')

@section('page-content')
    <div class="page-header">
        <h1>
            Edit Course Question
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

                    {!! Form::model($question->getAttributes(), [
                        'method' => 'PATCH',
                        'url' => route('fs_courses.questions.update', [$fsCourse, $question]),
                        'class' => 'form-horizontal',
                        'role' => 'form',
                        'id' => 'frmQuestion',
                        'files' => true,
                    ]) !!}

                    @include('fs_assessment.questions.edit_form', ['fsCourse' => $fsCourse, 'question' => $question])

                    {!! Form::close() !!}

                </div><!-- /.span -->
            </div>


            <!-- PAGE CONTENT ENDS -->
        </div><!-- /.col -->
    </div><!-- /.row -->
@endsection

@section('page-inline-scripts')

    <script type="text/javascript">

    </script>

@endsection
