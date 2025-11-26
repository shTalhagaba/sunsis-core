@extends('layouts.master')

@section('title', 'Enrol Student')

@section('page-plugin-styles')
    <link rel="stylesheet" href="{{ asset('assets/css/toastr.min.css') }}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
    <style type="text/css">
        .avatar {
            vertical-align: middle;
            width: 50px;
            height: 50px;
            border-radius: 50%;
        }
    </style>
@endsection

{{-- @section('breadcrumbs')
    {{ Breadcrumbs::render('students.singleEnrolment.step1.show', $student) }}
@endsection --}}

@section('page-content')
    <div class="page-header">
        <h1>Enrol Single Learner - Step 1</h1>
    </div><!-- /.page-header -->
    <div class="row">
        <div class="col-xs-12">
            <!-- PAGE CONTENT BEGINS -->

            <div class="row">
                <div class="col-sm-12">
                    <div class="alert alert-info">
                        <i class="fa fa-info-circle"></i> Step 1<br>
                        <i class="fa fa-hand-o-right"></i> <small>Complete enrolment form and select qualifications
                            (portfolios) for the training.</small><br>
                        <i class="fa fa-hand-o-right"></i> <small>Click on 'Continue to Step 2' to proceed to the next
                            step.</small>
                    </div>
                </div>
            </div>

            @include('partials.session_message')

            @include('partials.session_error')

            <div class="row">
                <div class="col-sm-12">
                    <div class="space"></div>

                    <div class="col-xs-12 col-sm-8">
                        <div class="widget-box">
                            <div class="widget-header">
                                <h4 class="smaller">Enrolment Form</h4>
                            </div>
                            <div class="widget-body">
                                {!! Form::open([
                                    'url' => route('students.singleEnrolment.step1.post', $student),
                                    'class' => 'form-horizontal',
                                    'role' => 'form',
                                    'id' => 'frmEnrolmentS1',
                                    'method' => 'POST',
                                ]) !!}
                                <div class="widget-main">

                                    <div
                                        class="form-group row required {{ $errors->has('programme_id') ? 'has-error' : '' }}">
                                        {!! Form::label('programme_id', 'Programme', ['class' => 'col-sm-4 control-label']) !!}
                                        <div class="col-sm-8">
                                            {!! Form::select('programme_id', $programmes, !is_null($enrolmentDto) ? ($enrolmentDto->programme)->id : null, [
                                                'class' => 'form-control',
                                                'required',
                                                'placeholder' => '',
                                            ]) !!}
                                            {!! $errors->first('programme_id', '<p class="text-danger">:message</p>') !!}
                                        </div>
                                    </div>
                                    <div
                                        class="form-group row required {{ $errors->has('start_date') ? 'has-error' : '' }}">
                                        {!! Form::label('start_date', 'Start Date', ['class' => 'col-sm-4 control-label']) !!}
                                        <div class="col-sm-8">
                                            {!! Form::date('start_date', optional($enrolmentDto)->startDate, ['class' => 'form-control', 'required']) !!}
                                            {!! $errors->first('start_date', '<p class="text-danger">:message</p>') !!}
                                        </div>
                                    </div>
                                    <div
                                        class="form-group row required {{ $errors->has('planned_end_date') ? 'has-error' : '' }}">
                                        {!! Form::label('planned_end_date', 'Planned End Date', ['class' => 'col-sm-4 control-label']) !!}
                                        <div class="col-sm-8">
                                            {!! Form::date('planned_end_date', optional($enrolmentDto)->plannedEndDate, ['class' => 'form-control', 'required']) !!}
                                            {!! $errors->first('planned_end_date', '<p class="text-danger">:message</p>') !!}
                                        </div>
                                    </div>
                                    <div class="form-group row {{ $errors->has('epa_date') ? 'has-error' : '' }}">
                                        {!! Form::label('epa_date', 'End Point Assessment Date', ['class' => 'col-sm-4 control-label']) !!}
                                        <div class="col-sm-8">
                                            {!! Form::date('epa_date', optional($enrolmentDto)->epaDate, ['class' => 'form-control']) !!}
                                            {!! $errors->first('epa_date', '<p class="text-danger">:message</p>') !!}
                                        </div>
                                    </div>
                                    <div
                                        class="form-group row required {{ $errors->has('employer_location') ? 'has-error' : '' }}">
                                        {!! Form::label('employer_location', 'Employer', ['class' => 'col-sm-4 control-label']) !!}
                                        <div class="col-sm-8">
                                            {!! Form::select(
                                                'employer_location',
                                                App\Models\LookupManager::getEmployersLocationsDDL(),
                                                !is_null($enrolmentDto) ? ($enrolmentDto->employerLocation)->id : $student->employer_location,
                                                ['class' => 'form-control', 'placeholder' => '', 'required', 'id' => 'employer_location'],
                                            ) !!}
                                            {!! $errors->first('employer_location', '<p class="text-danger">:message</p>') !!}
                                        </div>
                                    </div>
                                    <div
                                        class="form-group row required {{ $errors->has('primary_assessor') ? 'has-error' : '' }}">
                                        {!! Form::label('primary_assessor', 'Primary Assessor', ['class' => 'col-sm-4 control-label']) !!}
                                        <div class="col-sm-8">
                                            {!! Form::select('primary_assessor', $assessors, !is_null($enrolmentDto) ? ($enrolmentDto->primaryAssessor)->id : null, ['class' => 'form-control', 'required']) !!}
                                            {!! $errors->first('primary_assessor', '<p class="text-danger">:message</p>') !!}
                                        </div>
                                    </div>
                                    <div class="form-group row {{ $errors->has('secondary_assessor') ? 'has-error' : '' }}">
                                        {!! Form::label('secondary_assessor', 'Secondary Assessor', ['class' => 'col-sm-4 control-label']) !!}
                                        <div class="col-sm-8">
                                            {!! Form::select('secondary_assessor', ['' => ''] + $assessors, isset($enrolmentDto->secondaryAssessor) ? optional($enrolmentDto->secondaryAssessor)->id : null, ['class' => 'form-control']) !!}
                                            {!! $errors->first('secondary_assessor', '<p class="text-danger">:message</p>') !!}
                                        </div>
                                    </div>
                                    <div class="form-group row {{ $errors->has('tutor') ? 'has-error' : '' }}">
                                        {!! Form::label('tutor', 'Tutor', ['class' => 'col-sm-4 control-label']) !!}
                                        <div class="col-sm-8">
                                            {!! Form::select('tutor', ['' => ''] + $tutors, isset($enrolmentDto->tutor) ? optional($enrolmentDto->tutor)->id : null, ['class' => 'form-control']) !!}
                                            {!! $errors->first('tutor', '<p class="text-danger">:message</p>') !!}
                                        </div>
                                    </div>
                                    <div class="form-group row required {{ $errors->has('verifier') ? 'has-error' : '' }}">
                                        {!! Form::label('verifier', 'Verifier', ['class' => 'col-sm-4 control-label']) !!}
                                        <div class="col-sm-8">
                                            {!! Form::select('verifier', ['' => ''] + $verifiers, !is_null($enrolmentDto) ? optional($enrolmentDto->verifier)->id : null, ['class' => 'form-control', 'required']) !!}
                                            {!! $errors->first('verifier', '<p class="text-danger">:message</p>') !!}
                                        </div>
                                    </div>
                                </div> 
                                <div class="widget-toolbox padding-8 clearfix">
                                    <div class="center">
                                        <button class="btn btn-sm btn-primary btn-round" type="submit">
                                            <i class="ace-icon fa fa-arrow-right bigger-110"></i> Continue to Step 2
                                        </button>&nbsp; &nbsp; &nbsp;
                                    </div>
                                </div>
                                {!! Form::close() !!}
                            </div>
                        </div>

                    </div>

                    <div class="col-xs-12 col-sm-3 center">
                        @include('students.enrolment.partials.student_basic_details')
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

    <script type="text/javascript">
        $(function() {

            $('#frmEnrolmentS1').validate({
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

            $("input[name=start_date]").on("focusout", function(e) {
                e.preventDefault();

                var startDate = this.value;
                var programmeId = $("select[name=programme_id]").val();
                var plannedEndDateCtrl = $("input[name=planned_end_date]");
                var epaDateCtrl = $("input[name=epa_date]");

                if (startDate !== '') {
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                        },
                        beforeSend: function() {
                            plannedEndDateCtrl.attr('disabled', true);
                            epaDateCtrl.attr('disabled', true);
                        },
                        url: '{{ route('calcualteEndDate') }}',
                        data: {
                            programme_id: programmeId,
                            start_date: startDate
                        },
                        success: function(response) {
                            if (response.planned_end_date !== undefined && plannedEndDateCtrl
                                .val() == '') {
                                plannedEndDateCtrl.val(response.planned_end_date);
                            }
                            if (response.epa_date !== undefined && epaDateCtrl.val() == '') {
                                epaDateCtrl.val(response.epa_date);
                            }
                        },
                        error: function(errorInfo, code, errorMessage) {
                            // 
                        },
                        complete: function(data) {
                            plannedEndDateCtrl.attr('disabled', false);
                            epaDateCtrl.attr('disabled', false);
                        }
                    });

                }


            });

        });
    </script>

@endsection
