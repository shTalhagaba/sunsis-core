@extends('layouts.master')

@section('title', 'FS Test')

@section('page-plugin-styles')
<link rel="stylesheet" href="{{ asset('assets/css/toastr.min.css') }}" />
@endsection

@section('page-content')
    <div class="page-header">
        <h1>
            FS Test Session
        </h1>
    </div><!-- /.page-header -->
    <div class="row">
        <div class="col-xs-12">
            <!-- PAGE CONTENT BEGINS -->
            <button class="btn btn-sm btn-white btn-default btn-round" type="button"
                onclick="window.location.href='{{ route('trainings.show', $training) }}'">
                <i class="ace-icon fa fa-arrow-left bigger-110"></i> Cancel
            </button>
            <div class="hr hr-12 hr-dotted"></div>

            @include('trainings.partials.training_quick_details', [
                'showOverallPercentage' => true,
                'training' => $training,
            ])

            @include('partials.session_message')
            @include('partials.session_error')

            <div class="row">
                <div class="col-sm-7">
                    <h4>Course Details</h4>
                    <div class="info-div info-div-striped">
                        <div class="info-div-row">
                            <div class="info-div-name"> Title </div>
                            <div class="info-div-value"><span>{{ $fsTest->course->title }}</span></div>
                        </div>
                        <div class="info-div-row">
                            <div class="info-div-name"> Video Link </div>
                            <div class="info-div-value">
                                <span>{{ $fsTest->course->video_link }}</span>
                                @if (!is_null($fsTest->course->video_link))
                                <br><a class="btn btn-xs btn-info btn-round" href="{{ $fsTest->course->video_link }}" target="_blank">Open Link</a>
                                @endif
                            </div>
                        </div>
                        <div class="info-div-row">
                            <div class="info-div-name"> Details </div>
                            <div class="info-div-value"><span> {!! nl2br(e($fsTest->course->details)) !!}</span></div>
                        </div>
                        <div class="info-div-row">
                            <div class="info-div-name"> Number of Questions </div>
                            <div class="info-div-value"><span>{{ $fsTest->course->questions()->where('active', true)->count() }}</span></div>
                        </div>
                    </div>                    
                </div>
                <div class="col-sm-5">
                    <div
                        style="width: 330px; height: 210px; display: flex; align-items: center; justify-content: center; border: 2px solid #ddd; border-radius: 8px; overflow: hidden;">
                        @if (!is_null($fsTest->course->getYoutubeId()))
                            <iframe width="330" height="210"
                                src="https://www.youtube.com/embed/{{ $fsTest->course->getYoutubeId() }}" frameborder="0"
                                allowfullscreen>
                            </iframe>
                        @else
                            <i class="fa fa-video-camera" style="font-size: 50px; color: #888;"></i>
                        @endif
                    </div>                    
                </div>
            </div>

            @if($fsTest->isPending())
            <div class="row">
                <div class="col-sm-12">
                    <div style="margin: 2%">
                        {!! Form::model($fsTest, [
                            'method' => 'PATCH',
                            'url' => route('trainings.fs_tests.start_test', [$training, $fsTest]),
                            'class' => 'form-horizontal',
                            'role' => 'form',
                            'name' => 'frmStartTest',
                            'id' => 'frmStartTest',
                        ]) !!}
                        <button type="submit" class="btn btn-xs btn-primary btn-round" 
                            onclick="window.location.href='{{ route('trainings.fs_tests.start_test', [$training, $fsTest]) }}'">
                            <i class="fa fa-play"></i> Start Test </button>
                        {!! Form::close() !!}                    
                    </div>
                </div>
            </div>
            @endif

            @if($fsTest->isStarted())
            @include('trainings.fs_tests.test_form', ['training' => $training, 'fsTest' => $fsTest, 'fsCourse' => $fsTest->course])
            @endif

            @if($fsTest->isSubmitted())
            <div class="row">
                <div class="col-sm-8">
                    <h4>Test Details</h4>
                    <div class="info-div info-div-striped">
                        <div class="info-div-row">
                            <div class="info-div-name"> Status </div>
                            <div class="info-div-value"><span>{{ ucfirst($fsTest->status) }}</span></div>
                        </div>
                        <div class="info-div-row">
                            <div class="info-div-name"> Attempt No. </div>
                            <div class="info-div-value"><span>{{ $fsTest->attempt_no }}</span></div>
                        </div>
                        <div class="info-div-row">
                            <div class="info-div-name"> Started At </div>
                            <div class="info-div-value"><span>{{ $fsTest->started_at->format('d/m/Y H:i:s') }}</span></div>
                        </div>
                        <div class="info-div-row">
                            <div class="info-div-name"> Completed At </div>
                            <div class="info-div-value"><span>{{ $fsTest->completed_at->format('d/m/Y H:i:s') }}</span></div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            @if(!auth()->user()->isStudent() && $fsTest->isSubmitted())
            @include('trainings.fs_tests.test_assess', ['training' => $training, 'fsTest' => $fsTest, 'fsCourse' => $fsTest->course])
            @endif

            @if($fsTest->isApproved())
            @include('trainings.fs_tests.view_answers', ['training' => $training, 'fsTest' => $fsTest, 'fsCourse' => $fsTest->course, 'showCorrectAnsInfo' => true])
            @endif

            @if($fsTest->isRedo())
            @include('trainings.fs_tests.view_answers', ['training' => $training, 'fsTest' => $fsTest, 'fsCourse' => $fsTest->course, 'showCorrectAnsInfo' => false])
            @endif
            <!-- PAGE CONTENT ENDS -->
        </div>
    </div>
@endsection


@section('page-plugin-scripts')
<script src="{{ asset('assets/js/toastr.min.js') }}"></script>
@endsection