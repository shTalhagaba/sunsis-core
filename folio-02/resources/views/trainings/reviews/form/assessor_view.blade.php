@extends('layouts.master')

@section('title', 'Review Details')

@section('page-inline-styles')
<style>
    input[type=radio], input[type=checkbox] {
        transform: scale(1.7);
    }
</style>

@endsection

@section('page-content')
    <div class="page-header">
        <h1>
            Review Details
        </h1>
    </div><!-- /.page-header -->
    <div class="row">
        <div class="col-xs-12">
            <button class="btn btn-sm btn-white btn-default btn-round" type="button"
                onclick="window.location.href='{{ route('trainings.show', $training) }}'">
                <i class="ace-icon fa fa-times bigger-110"></i> Close
            </button>
            @if ($reviewForm->readyForEmployerSign())
            <button class="btn btn-sm btn-white btn-primary btn-round" type="button" id="btnSendReviewEmail">
                <i class="ace-icon fa fa-envelope bigger-110"></i> Email link to Employer
            </button>
            @endif
            @if ($reviewForm->completed() && false)
            <button class="btn btn-sm btn-white btn-info btn-round" type="button" id="btnExportReview" onclick="window.open('{{ route('trainings.reviews.form.export', [$training, $review]) }}')">
                <i class="ace-icon fa fa-file-pdf-o bigger-110"></i> Export
            </button>
            @endif
            @if($reviewForm->locked() && !auth()->user()->isStudent())
                {!! Form::model($reviewForm, [
                    'method' => 'PATCH',
                    'url' => route('trainings.reviews.form.update', [
                        'training' => $training,
                        'review' => $review, 
                    ]),
                    'id' => 'frmUnlockSession',
                    'style' => 'display: inline;',
                    'class' => 'form-inline',
                ]) !!}
                {!! Form::hidden('form_id', $reviewForm->id) !!}
                {!! Form::hidden('review_id', $review->id) !!}
                {!! Form::hidden('training_id', $training->id) !!}
                {!! Form::hidden('subaction', 'unlock_review_form') !!}
                {!! Form::button('<i class="ace-icon fa fa-unlock bigger-110"></i> Unlock', [
                    'class' => 'btn btn-primary btn-xs btn-round pull-right',
                    'type' => 'click',
                    'id' => 'btnUnlockReview',
                ]) !!}
                {!! Form::close() !!}
            @endif
            <div class="hr hr-12 hr-dotted"></div>

            @include('trainings.partials.training_quick_details', ['showOverallPercentage' => true])

            @include('trainings.reviews.partials.review_basic_details', ['review' => $review])

            <div class="space-12"></div>

            @include('partials.session_message')

            @include('partials.session_error')

            @if(! $reviewForm->locked())
            {!! Form::open([
                'method' => 'PATCH',
                'url' => route('trainings.reviews.form.update', [
                    'training' => $training,
                    'review' => $review, 
                ]),
                'class' => 'form-horizontal',
                'files' => true,
                'role' => 'form',
                'id' => 'frmReview',
            ]) !!}

            {!! Form::hidden('form_id', $reviewForm->id) !!}

            <div class="row">
                <div class="col-sm-12">
                    <div class="widget-box widget-color-green">
                        <div class="widget-header">
                            <h4 class="widget-title">Review Details for {{ $training->student->full_name }}</h4>
                        </div>
                        <div class="widget-body">
                            <div class="widget-main">

                                @include('trainings.reviews.form.' . $formFolder . '.' . $formVersionFolder . '.form')

				<div class="form-group row {{ $errors->has('review_form_attachment') ? 'has-error' : '' }}">
                                    {!! Form::label('review_form_attachment', 'File', ['class' => 'col-sm-4 control-label no-padding-right']) !!}
                                    <div class="col-sm-8">
                                        @include(
                                            'partials.ace_file_control',
                                            ['aceFileControlRequired' => false, 'aceFileControlId' => 'review_form_attachment', 'aceFileControlName' => 'review_form_attachment']
                                        )
                                        {!! $errors->first('review_form_attachment', '<p class="text-danger">:message</p>') !!}
                                    </div>
                                </div>

                                <div class="form-group row required{{ $errors->has('assessor_comments') ? 'has-error' : '' }}">
                                    {!! Form::label('assessor_comments', 'Assessor/Tutor comments', [
                                        'class' => 'col-sm-4 control-label no-padding-right',
                                    ]) !!}
                                    <div class="col-sm-8">
                                        {!! Form::textarea('assessor_comments', $formData['assessor_comments'] ?? null, [
                                            'class' => 'form-control',
                                            'placeholder' => 'Assessor/Tutor comments',
                                            'maxlength' => '5000',
                                        ]) !!}
                                        {!! $errors->first('assessor_comments', '<p class="text-danger">:message</p>') !!}
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-8 col-sm-offset-4">
                                        <div class="control-group">
                                            <div class="checkbox">
                                                <label>
                                                    <input name="assessor_signed"  type="checkbox" value="1" >
                                                    <span class="lbl bolder"> &nbsp; Tick this option to confirm your signature if the form is fully completed.</span>
                                                    <div class="space-2"></div>
                                                    <span class="text-info small" style="margin-left: 2%"> 
                                                        &nbsp; <i class="fa fa-info-circle"></i> 
                                                        After you tick this option and save then form will be locked for further changes.
                                                    </span>
                                                </label>
                                            </div>
                                        </div>
                                        <br>
                                        {!! $errors->first('assessor_signed', '<p class="text-danger">:message</p>') !!}
                                    </div>
                                </div>
                            </div>

                            @if(auth()->user()->isAssessor() || auth()->user()->can('save-review-form'))
                            <div class="widget-toolbox padding-8 clearfix">
                                <div class="center">
                                    <button class="btn btn-sm btn-success btn-round" type="submit">
                                        <i class="ace-icon fa fa-save bigger-110"></i> Save Information
                                    </button>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {!! Form::close() !!}

            @else
            
            @include('trainings.reviews.form.' . $formFolder . '.' . $formVersionFolder . '.show')

            @endif
        </div>
    </div>

@endsection

@section('page-plugin-scripts')
<script>
    
    var trainingId = '{{ $training->id }}';
    var reviewId = '{{ $review->id }}';

    var emailRoute = '{{ route('trainings.reviews.form.employerSignatureEmail', ['training' => ':training', 'review' => ':review']) }}';
    emailRoute = emailRoute.replace(':training', trainingId);
    emailRoute = emailRoute.replace(':review', reviewId);

    $('#btnSendReviewEmail').on('click', function(e) {
        e.preventDefault();
        bootbox.prompt({
            title: 'Please select a user from the list to send email',
            message: '<p>Please select an option below:</p>',
            inputType: 'select',
            inputOptions: [{
                text: 'Choose one...',
                value: ''
            },
            @php
                foreach($employerUsers AS $employerUser)
                {
                    echo '{';
                    echo 'text: \'' . $employerUser->firstnames . ' ' . $employerUser->surname . ' [' . $employerUser->primary_email . ']\', ';
                    echo 'value: \'' . $employerUser->id . '\'';
                    echo '},';
                }
            @endphp
            ],
            buttons: {
                cancel: {
                    label: '<i class="fa fa-times"></i> Cancel',
                    className: "btn-sm",
                },
                confirm: {
                    label: '<i class="fa fa-check"></i> Confirm',
                    className: "btn-success btn-sm",
                }
            },
            callback: function (result) {
                if(result) {
                    $.ajax({
                        type: 'post',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        url: emailRoute,
                        data: {
                            employer_user: result
                        },
                        success: function (response, textStatus, xhr) {
                            bootbox.alert({
                                title: "Success",
                                message: response.message
                            });
                        },
                        error: function (errorInfo) {
                            bootbox.alert({
                                title: "Error: " + errorInfo.statusText,
                                message: errorInfo.responseJSON.message !== undefined ? errorInfo.responseJSON.message : 'Email is not sent.'
                            });
                        }
                    });
                }
            }
        });
    });

    $("button#btnUnlockReview").on('click', function(e){
        e.preventDefault();

        var form = $(this).closest('form');

        bootbox.confirm({
            title: 'Sure to Unlock?',
            message: 'This action will remove learner and assessor comments and their timestamps, are you sure you want to continue?',
            buttons: {
                cancel: {
                    label: '<i class="fa fa-times"></i> Cancel',
                    className: 'btn-xs btn-round'
                },
                confirm: {
                    label: '<i class="fa fa-check"></i> Yes Unlock',
                    className: 'btn-primary btn-xs btn-round'
                }
            },
            callback: function(result) {
                if (result) {
                    form.submit();
                } 
            }
        });        
    });
</script>
@endsection