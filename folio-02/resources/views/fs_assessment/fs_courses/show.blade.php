@extends('layouts.master')

@section('title', 'Question')

@section('page-inline-styles')
    <style>
        .custom-list {
            list-style-type: none;
            counter-reset: custom-counter;
            padding-left: 0;
        }

        .custom-list li {
            counter-increment: custom-counter;
            margin-bottom: 10px;
        }

        .custom-list li::before {
            content: counter(custom-counter, lower-alpha) ") ";
            font-weight: bold;
            margin-right: 10px;
        }
    </style>
@endsection

@section('page-content')
    <div class="page-header">
        <h1>View FS Course</h1>
    </div><!-- /.page-header -->
    <div class="row">
        <div class="col-xs-12">
            <button class="btn btn-sm btn-white btn-default btn-round" type="button"
                onclick="window.location.href='{{ route('fs_courses.index') }}'">
                <i class="ace-icon fa fa-times bigger-110"></i> Close
            </button>
            @can('create-update-delete-fs-courses')
            <button class="btn btn-sm btn-primary btn-bold btn-round" type="button"
                onclick="window.location.href='{{ route('fs_courses.edit', $fsCourse) }}'">
                <i class="ace-icon fa fa-edit bigger-120"></i> Edit Course
            </button>
            {!! Form::open([
                'method' => 'DELETE',
                'route' => ['fs_courses.destroy', $fsCourse],
                'style' => 'display: inline;',
                'class' => 'form-inline',
                'id' => 'frmDeleteCourse',
            ]) !!}
            {!! Form::button('<i class="ace-icon fa fa-trash-o bigger-120"></i> Delete Course', [
                'class' => 'btn btn-sm btn-danger btn-bold btn-round',
                'type' => 'submit',
                'style' => 'display: inline',
            ]) !!}
            {!! Form::close() !!}
            @endcan

            <div class="hr hr-12 hr-dotted"></div>

            @include('partials.session_message')
            @include('partials.session_error')

            <div class="row">
                <div class="col-sm-7">
                    <h3>Details</h3>
                    @include('fs_assessment.partials.course_details_panel', ['fsCourse' => $fsCourse])
                </div>
                <div class="col-sm-5">
                    <div
                        style="width: 330px; height: 210px; display: flex; align-items: center; justify-content: center; border: 2px solid #ddd; border-radius: 8px; overflow: hidden;">
                        @if (!is_null($fsCourse->getYoutubeId()))
                            <iframe width="330" height="210"
                                src="https://www.youtube.com/embed/{{ $fsCourse->getYoutubeId() }}" frameborder="0"
                                allowfullscreen>
                            </iframe>
                        @else
                            <i class="fa fa-video-camera" style="font-size: 50px; color: #888;"></i>
                        @endif
                    </div>
                </div>

                <div class="col-sm-12">
                    <h3>Questions (Total: {{ $fsCourse->questions()->count() }})</h3>
                    @if( auth()->user()->isTutor() )
                    <button class="btn btn-sm btn-bold btn-primary btn-round" type="button"
                        onclick="window.location.href='{{ route('fs_courses.questions.create', $fsCourse) }}'">
                        <i class="ace-icon fa fa-plus bigger-120"></i> Add New Question
                    </button>
                    @endif
                    @foreach ($fsCourse->questions as $question)
                        @include('fs_assessment.partials.question_panel', [
                            'question' => $question,
                            'questionNumber' => $loop->iteration,
                            'fsCourse' => $fsCourse,
                        ])
                    @endforeach
                </div>
            </div>
        </div>
    </div>

@endsection


@section('page-inline-scripts')
    <script type="text/javascript">
        $('#frmDeleteCourse, .frmDeleteQuestion').submit(function(e) {
            var currentForm = this;
            e.preventDefault();

            bootbox.confirm({
                title: "Confirmation",
                message: 'This action is irreversible, are you sure you want to continue?',
                buttons: {
                    cancel: {
                        label: '<i class="fa fa-times"></i> Cancel',
                        className: "btn-sm btn-round",
                    },
                    confirm: {
                        label: '<i class="fa fa-trash"></i> Confirm',
                        className: "btn-danger btn-sm btn-round",
                    }
                },
                callback: function(result) {
                    if (result) {
                        $(currentForm).find(':submit').attr("disabled", true);
                        $(currentForm).find(':submit').attr("title", "Deleting ... ");
                        $(currentForm).find(':submit').html('<i class="fa fa-spinner fa-spin"></i>');

                        currentForm.submit();
                    }
                }
            });
        });
    </script>

@endsection
