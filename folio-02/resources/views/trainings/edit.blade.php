@extends('layouts.master')

@section('title', 'Edit Training Record')

@section('page-plugin-styles')
    <link rel="stylesheet" href="{{ asset('assets/css/toastr.min.css') }}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
@endsection

@section('page-content')
    <div class="page-header">
        <h1>Edit Training Record</h1>
    </div><!-- /.page-header -->
    <div class="row">
        <div class="col-xs-12">
            <!-- PAGE CONTENT BEGINS -->
            <div class="row">
                <div class="col-xs-12">
                    <button class="btn btn-sm btn-white btn-default btn-round" type="button"
                        onclick="window.location.href='{{ route('trainings.show', $training) }}'">
                        <i class="ace-icon fa fa-arrow-left bigger-110"></i> Cancel
                    </button>
                    <div class="hr hr-12 hr-dotted"></div>
                </div>
            </div>
            
            @include('trainings.partials.training_quick_details', ['showOverallPercentage' => true])

            @include('partials.session_message')

            @include('partials.session_error')

            <div class="row">
                <div class="col-sm-7">
                    <div class="space"></div>

                    {!! Form::model($training->getAttributes(), [
                        'method' => 'PATCH',
                        'url' => route('trainings.update', $training),
                        'class' => 'form-horizontal',
                        'role' => 'form',
                        'name' => 'frmEditTrainingRecord',
                        'id' => 'frmEditTrainingRecord',
                    ]) !!}
                    <div class="widget-box widget-color-green">
                        <div class="widget-header">
                            <h4 class="widget-title">Edit Training Details</h4>
                        </div>
                        <div class="widget-body">
                            <div class="widget-main">
                                <div class="row">
                                    <div class="col-sm-12">
                                        {{-- <div
                                            class="form-group row required {{ $errors->has('start_date') ? 'has-error' : '' }}">
                                            {!! Form::label('start_date', 'Start Date', ['class' => 'col-sm-4 control-label']) !!}
                                            <div class="col-sm-8">
                                                {!! Form::date('start_date', null, ['class' => 'form-control', 'required']) !!}
                                                {!! $errors->first('start_date', '<p class="text-danger">:message</p>') !!}
                                            </div>
                                        </div>
                                        <div
                                            class="form-group row required {{ $errors->has('planned_end_date') ? 'has-error' : '' }}">
                                            {!! Form::label('planned_end_date', 'Planned End Date', ['class' => 'col-sm-4 control-label']) !!}
                                            <div class="col-sm-8">
                                                {!! Form::date('planned_end_date', null, ['class' => 'form-control', 'required']) !!}
                                                {!! $errors->first('planned_end_date', '<p class="text-danger">:message</p>') !!}
                                            </div>
                                        </div>
                                        <div
                                            class="form-group row {{ $errors->has('actual_end_date') ? 'has-error' : '' }}">
                                            {!! Form::label('actual_end_date', 'Actual End Date', ['class' => 'col-sm-4 control-label']) !!}
                                            <div class="col-sm-8">
                                                {!! Form::date('actual_end_date', null, ['class' => 'form-control']) !!}
                                                {!! $errors->first('actual_end_date', '<p class="text-danger">:message</p>') !!}
                                            </div>
                                        </div> --}}
                                        <div
                                            class="form-group row required {{ $errors->has('employer_location') ? 'has-error' : '' }}">
                                            {!! Form::label('employer_location', 'Employer', ['class' => 'col-sm-4 control-label']) !!}
                                            <div class="col-sm-8">
                                                {!! Form::select(
                                                    'employer_location',
                                                    \App\Models\LookupManager::getEmployersLocationsDDL(),
                                                    $training->employer_location,
                                                    ['class' => 'form-control', 'placeholder' => '', 'required', 'id' => 'employer_location'],
                                                ) !!}
                                                {!! $errors->first('employer_location', '<p class="text-danger">:message</p>') !!}
                                            </div>
                                        </div>
                                        <div
                                            class="form-group row required {{ $errors->has('primary_assessor') ? 'has-error' : '' }}">
                                            {!! Form::label('primary_assessor', 'Primary Assessor', ['class' => 'col-sm-4 control-label']) !!}
                                            <div class="col-sm-8">
                                                {!! Form::select('primary_assessor', $assessors, null, ['class' => 'form-control', 'required']) !!}
                                                {!! $errors->first('primary_assessor', '<p class="text-danger">:message</p>') !!}
                                            </div>
                                        </div>
                                        <div
                                            class="form-group row {{ $errors->has('secondary_assessor') ? 'has-error' : '' }}">
                                            {!! Form::label('secondary_assessor', 'Secondary Assessor', ['class' => 'col-sm-4 control-label']) !!}
                                            <div class="col-sm-8">
                                                {!! Form::select('secondary_assessor', $secondary_assessors, null, ['class' => 'form-control', 'placeholder' => '']) !!}
                                                {!! $errors->first('secondary_assessor', '<p class="text-danger">:message</p>') !!}
                                            </div>
                                        </div>
                                        <div class="form-group row {{ $errors->has('tutor') ? 'has-error' : '' }}">
                                            {!! Form::label('tutor', 'Tutor', ['class' => 'col-sm-4 control-label']) !!}
                                            <div class="col-sm-8">
                                                {!! Form::select('tutor', $tutors, null, ['class' => 'form-control', 'placeholder' => '']) !!}
                                                {!! $errors->first('tutor', '<p class="text-danger">:message</p>') !!}
                                            </div>
                                        </div>
                                        <div
                                            class="form-group row required {{ $errors->has('verifier') ? 'has-error' : '' }}">
                                            {!! Form::label('verifier', 'Verifier', ['class' => 'col-sm-4 control-label']) !!}
                                            <div class="col-sm-8">
                                                {!! Form::select('verifier', $verifiers, null, ['class' => 'form-control', 'required', 'placeholder' => '']) !!}
                                                {!! $errors->first('verifier', '<p class="text-danger">:message</p>') !!}
                                            </div>
                                        </div>
                                        <div
                                            class="form-group row {{ $errors->has('employer_user_id') ? 'has-error' : '' }}">
                                            {!! Form::label('employer_user_id', 'Employer User', ['class' => 'col-sm-4 control-label']) !!}
                                            <div class="col-sm-8">
                                                {!! Form::select('employer_user_id', $employerUsers, null, ['class' => 'form-control', 'placeholder' => '']) !!}
                                                {!! $errors->first('employer_user_id', '<p class="text-danger">:message</p>') !!}
                                            </div>
                                        </div>
                                        <div class="form-group row {{ $errors->has('otj_hours') ? 'has-error' : '' }}">
                                            {!! Form::label('otj_hours', 'OTJ Hours', ['class' => 'col-sm-4 control-label']) !!}
                                            <div class="col-sm-8">
                                                {!! Form::number('otj_hours', $training->otj_hours ?? $training->otjHours(), ['class' => 'form-control', 'max' => 1000]) !!}
                                                {!! $errors->first('otj_hours', '<p class="text-danger">:message</p>') !!}
                                            </div>
                                        </div>
                                        <div class="form-group row {{ $errors->has('contracted_hours_per_week') ? 'has-error' : '' }}">
                                            {!! Form::label('contracted_hours_per_week', 'Contracted Hours per Week', ['class' => 'col-sm-4 control-label']) !!}
                                            <div class="col-sm-8">
                                                {!! Form::number('contracted_hours_per_week', null, ['class' => 'form-control', 'max' => 99]) !!}
                                                {!! $errors->first('contracted_hours_per_week', '<p class="text-danger">:message</p>') !!}
                                            </div>
                                        </div>
                                        <div class="form-group row {{ $errors->has('weeks_to_worked_per_year') ? 'has-error' : '' }}">
                                            {!! Form::label('weeks_to_worked_per_year', 'Weeks to be worked per Year', ['class' => 'col-sm-4 control-label']) !!}
                                            <div class="col-sm-8">
                                                {!! Form::number('weeks_to_worked_per_year', null, ['class' => 'form-control', 'max' => 50]) !!}
                                                {!! $errors->first('weeks_to_worked_per_year', '<p class="text-danger">:message</p>') !!}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="widget-box transparent">
                                            <div class="widget-header">
                                                <h4 class="widget-title">Portfolios/Qualifications</h4>
                                            </div>
                                            <div class="widget-body">
                                                <div class="widget-main table-responsive">
                                                    <table class="table table-bordered">
                                                        <tr class="bg-success">
                                                            <th style="width: 40%">Portfolio</th>
                                                            <th style="width: 60%">Edit Portfolio Details</th>
                                                        </tr>
                                                        @foreach ($training->portfolios as $portfolio)
                                                            <tr>
                                                                <td>
                                                                    {{ $portfolio->qan }} {{ $portfolio->title }}
                                                                </td>
                                                                <td>
                                                                    <table class="table">
                                                                        <tr>
                                                                            <th>Status</th>
                                                                            <td>
                                                                                {!! Form::select(
                                                                                    'status_code' . $portfolio->id,
                                                                                    App\Models\Lookups\PortfolioStatusLookup::getSelectData(),
                                                                                    $portfolio->getOriginal('status_code'),
                                                                                    ['class' => 'form-control'],
                                                                                ) !!}
                                                                                {!! $errors->first('status_code' . $portfolio->id, '<p class="text-danger">:message</p>') !!}
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <th>Outcome</th>
                                                                            <td>
                                                                                {!! Form::select(
                                                                                    'learning_outcome' . $portfolio->id,
                                                                                    App\Models\LookupManager::getCompletionStatus(),
                                                                                    $portfolio->getOriginal('learning_outcome'),
                                                                                    ['class' => 'form-control', 'placeholder' => ''],
                                                                                ) !!}
                                                                                {!! $errors->first('learning_outcome' . $portfolio->id, '<p class="text-danger">:message</p>') !!}
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <th>Start Date</th>
                                                                            <td>
                                                                                {!! Form::date('start_date' . $portfolio->id, $portfolio->getOriginal('start_date'), [
                                                                                    'class' => 'form-control',
                                                                                ]) !!}
                                                                                {!! $errors->first('start_date' . $portfolio->id, '<p class="text-danger">:message</p>') !!}
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <th>Planned End Date</th>
                                                                            <td>
                                                                                {!! Form::date('planned_end_date' . $portfolio->id, $portfolio->getOriginal('planned_end_date'), [
                                                                                    'class' => 'form-control',
                                                                                ]) !!}
                                                                                {!! $errors->first('planned_end_date' . $portfolio->id, '<p class="text-danger">:message</p>') !!}
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <th>Actual End Date</th>
                                                                            <td>
                                                                                {!! Form::date('actual_end_date' . $portfolio->id, $portfolio->getOriginal('actual_end_date'), [
                                                                                    'class' => 'form-control',
                                                                                ]) !!}
                                                                                {!! $errors->first('actual_end_date' . $portfolio->id, '<p class="text-danger">:message</p>') !!}
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <th>Tutor</th>
                                                                            <td>
                                                                                {!! Form::select('fs_tutor_id' . $portfolio->id, $tutors, $portfolio->getOriginal('fs_tutor_id'), [
                                                                                    'class' => 'form-control', 'placeholder' => ''
                                                                                ]) !!}
                                                                                {!! $errors->first('fs_tutor_id' . $portfolio->id, '<p class="text-danger">:message</p>') !!}
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <th>Verifier</th>
                                                                            <td>
                                                                                {!! Form::select('fs_verifier_id' . $portfolio->id, $verifiers, $portfolio->getOriginal('fs_verifier_id'), [
                                                                                    'class' => 'form-control', 'placeholder' => ''
                                                                                ]) !!}
                                                                                {!! $errors->first('fs_verifier_id' . $portfolio->id, '<p class="text-danger">:message</p>') !!}
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <th>Awarding Body Registration Number</th>
                                                                            <td>
                                                                                {!! Form::text('ab_registration_number' . $portfolio->id, $portfolio->ab_registration_number, [
                                                                                    'class' => 'form-control inputLimiter',
                                                                                    'maxlength' => 15,
                                                                                ]) !!}
                                                                                {!! $errors->first('ab_registration_number' . $portfolio->id, '<p class="text-danger">:message</p>') !!}
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <th>Awarding Body Registration Date</th>
                                                                            <td>
                                                                                {!! Form::date('ab_registration_date' . $portfolio->id, $portfolio->ab_registration_date, [
                                                                                    'class' => 'form-control',
                                                                                ]) !!}
                                                                                {!! $errors->first('ab_registration_date' . $portfolio->id, '<p class="text-danger">:message</p>') !!}
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <th>Certificate Number</th>
                                                                            <td>
                                                                                {!! Form::text('certificate_no' . $portfolio->id, $portfolio->certificate_no, [
                                                                                    'class' => 'form-control inputLimiter',
                                                                                    'maxlength' => 50,
                                                                                ]) !!}
                                                                                {!! $errors->first('certificate_no' . $portfolio->id, '<p class="text-danger">:message</p>') !!}
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <th>Certificate Applied Date</th>
                                                                            <td>
                                                                                {!! Form::date('cert_applied' . $portfolio->id, $portfolio->cert_applied, ['class' => 'form-control']) !!}
                                                                                {!! $errors->first('cert_applied' . $portfolio->id, '<p class="text-danger">:message</p>') !!}
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <th>Certificate Received Date</th>
                                                                            <td>
                                                                                {!! Form::date('cert_received' . $portfolio->id, $portfolio->cert_received, ['class' => 'form-control']) !!}
                                                                                {!! $errors->first('cert_received' . $portfolio->id, '<p class="text-danger">:message</p>') !!}
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <th>Certificate Sent to Learner Date</th>
                                                                            <td>
                                                                                {!! Form::date('cert_sent_to_learner' . $portfolio->id, $portfolio->cert_sent_to_learner, [
                                                                                    'class' => 'form-control',
                                                                                ]) !!}
                                                                                {!! $errors->first('cert_sent_to_learner' . $portfolio->id, '<p class="text-danger">:message</p>') !!}
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <th>Certificate Expiry Date</th>
                                                                            <td>
                                                                                {!! Form::date('cert_expiry_date' . $portfolio->id, $portfolio->cert_expiry_date, [
                                                                                    'class' => 'form-control',
                                                                                ]) !!}
                                                                                {!! $errors->first('cert_expiry_date' . $portfolio->id, '<p class="text-danger">:message</p>') !!}
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <th>Batch Number</th>
                                                                            <td>
                                                                                {!! Form::text('batch_no' . $portfolio->id, $portfolio->batch_no, [
                                                                                    'class' => 'form-control inputLimiter',
                                                                                    'maxlength' => 50,
                                                                                ]) !!}
                                                                                {!! $errors->first('batch_no' . $portfolio->id, '<p class="text-danger">:message</p>') !!}
                                                                            </td>
                                                                        </tr>
                                                                        <tr>
                                                                            <th>Candidate Number</th>
                                                                            <td>
                                                                                {!! Form::text('candidate_no' . $portfolio->id, $portfolio->candidate_no, [
                                                                                    'class' => 'form-control inputLimiter',
                                                                                    'maxlength' => 20,
                                                                                ]) !!}
                                                                                {!! $errors->first('candidate_no' . $portfolio->id, '<p class="text-danger">:message</p>') !!}
                                                                            </td>
                                                                        </tr>
                                                                    </table>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </table>
                                                </div>
                                            </div>
                                        </div>

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
                    {!! Form::close() !!}

                </div>
                <div class="col-sm-5">
                    <div class="widget-box transparent">
                        <div class="widget-header">
                            <h4 class="widget-title">Training Status Logs</h4>
                        </div>
                        <div class="widget-body">
                            <div class="widget-main table-responsive">
                                @include('trainings.partials.training_status_changes_table', [
                                    'training' => $training,
                                ])
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <!-- PAGE CONTENT ENDS -->
        </div><!-- /.col -->
    </div><!-- /.row -->
@endsection


@section('page-plugin-scripts')
    <script src="{{ asset('assets/js/toastr.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery-additional-methods.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
@endsection

@section('page-inline-scripts')

    <script>
        $(function() {

            $('#frmEditTrainingRecord').validate({
                errorElement: 'div',
                errorClass: 'help-block',
                focusInvalid: false,
                rules: {
                    start_date: {
                        required: true
                    },
                    planned_end_date: {
                        required: true,
                        greaterThan: "#start_date"
                    },
                    employer_location: {
                        required: true
                    },
                    primary_assessor: {
                        required: true
                    },
                    verifier: {
                        required: true
                    }
                },

                highlight: function(e) {
                    $(e).closest('.form-group').removeClass('has-info').addClass('has-error');
                },

                success: function(e) {
                    $(e).closest('.form-group').removeClass('has-error').addClass('has-info');
                    $(e).remove();
                },

                errorPlacement: function(error, element) {
                    if (element.is('input[type=checkbox]') || element.is('input[type=radio]')) {
                        var controls = element.closest('div[class*="col-"]');
                        if (controls.find(':checkbox,:radio').length > 1) controls.append(error);
                        else error.insertAfter(element.nextAll('.lbl:eq(0)').eq(0));
                    } else
                        error.insertAfter(element);
                }
            });

            jQuery.validator.addMethod("greaterThan", function(value, element, params) {
                if ($(params).val() == '')
                    return true;
                if (!/Invalid|NaN/.test(new Date(value))) {
                    return new Date(value) > new Date($(params).val());
                }
                return isNaN(value) && isNaN($(params).val()) || (Number(value) > Number($(params).val()));
            }, "Planned end date must be after the start date.");

	    $("form[name=frmEditTrainingRecord]").on('submit', function() {
                var form = $(this);
                form.find(':submit').attr("disabled", true);
                form.find(':submit').html('<i class="fa fa-spinner fa-spin"></i> Saving');
                return true;
            });
        });

        
    </script>

@endsection
