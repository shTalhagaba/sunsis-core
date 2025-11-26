@extends('layouts.master')

@section('title', 'Validate Evidence')

@section('page-content')
    <div class="page-header">
        <h1>Map Evidence <small>{{ $training->system_ref }}</small></h1>
    </div><!-- /.page-header -->
    <div class="row">
        <div class="col-xs-12">

            <button class="btn btn-sm btn-white btn-default btn-round" type="button"
                onclick="window.location.href='{{ route('trainings.show', $training) }}'">
                <i class="ace-icon fa fa-arrow-left bigger-110"></i> Cancel
            </button>
            <div class="hr hr-12 hr-dotted"></div>

            @include('trainings.partials.training_quick_details', ['showOverallPercentage' => true])

            <div class="space-12"></div>

            <div class="row">
                <div class="col-sm-12">
                    <div class="widget-box transparent">
                        <div class="widget-header">
                            <h5 class="widget-title smaller">Evidence Details</h5>
                        </div>
                        <div class="widget-body">
                            <div class="widget-main">
                                @include('trainings.evidences.partials.evidence-details', [
                                    '_evi_details' => $evidence,
                                ])
                            </div>
                        </div>
                    </div>
                    <hr>
                </div>
            </div>

            @include('partials.session_message')

            @include('partials.session_error')

            <div class="row">
                <div class="col-sm-12">
                    <div class="widget-box widget-color-green light-border">
                        <div class="widget-header">
                            <h5 class="widget-title smaller">Validate Evidence </h5>
                        </div>
                        <div class="widget-body">
                            <div class="widget-main">

                                <p class="blue">
                                    <i class="fa fa-info-circle"></i> 
                                    This evidence is created by {{ $evidence->creator->full_name }} ({{ $evidence->creator->systemUserType->description }}).
                                    Please enter your comments and validate.
                                </p>
                                {!! Form::open([
                                    'url' => route('trainings.evidences.saveStudentValidation', [$training, $evidence]),
                                    'class' => 'form-horizontal',
                                ]) !!}
                                <div class="form-group row {{ $errors->has('learner_comments') ? 'has-error' : ''}}">
                                    {!! Form::label('learner_comments', 'Learner Comments', ['class' => 'col-sm-4 control-label']) !!}
                                    <div class="col-sm-8">
                                        {!! Form::textarea('learner_comments', null, ['class' => 'form-control inputLimiter', 'rows' => '5', 'id' => 'learner_comments', 'maxlength' => 500]) !!}
                                        {!! $errors->first('learner_comments', '<p class="text-danger">:message</p>') !!}
                                    </div>
                                </div>
                                <div class="form-group row required {{ $errors->has('learner_declaration') ? 'has-error' : ''}}">
                                    {!! Form::label('learner_declaration', 'Click to validate', ['class' => 'col-sm-4 control-label']) !!}
                                    <div class="col-sm-8">
                                        <div class="checkbox">
                                            <label class="block">
                                            <input name="learner_declaration" type="checkbox" class="ace input-lg" value="1" required>
                                            <span class="lbl bigger-120"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="widget-toolbox padding-8 clearfix">
                                <div class="center">
                                    <button id="btnSubmitFrmEvidence" class="btn btn-sm btn-success btn-round" type="submit">
                                        <i class="ace-icon fa fa-save bigger-110"></i> Save Information
                                    </button>
                                </div>
                            </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>

            <!-- PAGE CONTENT ENDS -->

        </div><!-- /.col -->
    </div><!-- /.row -->
@endsection

