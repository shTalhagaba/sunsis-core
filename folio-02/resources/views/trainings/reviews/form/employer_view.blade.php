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
            <div class="hr hr-12 hr-dotted"></div>

            @include('trainings.partials.training_quick_details', ['showOverallPercentage' => true])

            @include('trainings.reviews.partials.review_basic_details', ['review' => $review])

            <div class="space-12"></div>

            @include('trainings.reviews.form.' . $formFolder . '.' . $formVersionFolder . '.show')

            @include('partials.session_message')

            @include('partials.session_error')

            @if($reviewForm->readyForEmployerSign())
            {!! Form::open([
                'method' => 'PATCH',
                'url' => route('trainings.reviews.form.update', [
                    'training' => $training,
                    'review' => $review, 
                ]),
                'class' => 'form-horizontal',
                'role' => 'form',
                'id' => 'frmReview',
            ]) !!}

            {!! Form::hidden('form_id', $reviewForm->id) !!}

            <div class="row">
                <div class="col-sm-12">
                    <div class="widget-box widget-color-green">
                        <div class="widget-header">
                            <h4 class="widget-title">Signatures</h4>
                        </div>
                        <div class="widget-body">
                            <div class="widget-main">                               
                                <div class="form-group row required{{ $errors->has('employer_comments') ? 'has-error' : '' }}">
                                    {!! Form::label('employer_comments', 'Employer comments', [
                                        'class' => 'col-sm-4 control-label no-padding-right',
                                    ]) !!}
                                    <div class="col-sm-8">
                                        {!! Form::textarea('employer_comments', $formData['employer_comments'] ?? null, [
                                            'class' => 'form-control',
                                            'placeholder' => 'Employer/Line Manager comments',
                                            'maxlength' => '1500',
                                            'required',
                                        ]) !!}
                                        {!! $errors->first('employer_comments', '<p class="text-danger">:message</p>') !!}
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-sm-8 col-sm-offset-4">
                                        <div class="control-group">
                                            <div class="checkbox">
                                                <label>
                                                    <input name="employer_signed"  type="checkbox" value="1" required>
                                                    <span class="lbl bolder"> &nbsp; Tick this option to confirm your signature.</span>
                                                </label>
                                            </div>
                                        </div>
                                        <br>
                                        {!! $errors->first('employer_signed', '<p class="text-danger">:message</p>') !!}
                                    </div>
                                </div>
                            </div>
                            <div class="widget-toolbox padding-8 clearfix">
                                <div class="center">
                                    <button class="btn btn-sm btn-success btn-round" type="submit">
                                        <i class="ace-icon fa fa-save bigger-110"></i> Save Information
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {!! Form::close() !!}
            @endif
        </div>
    </div>
@endsection