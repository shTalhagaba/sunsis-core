@extends('layouts.master')

@section('title', 'Edit Training Record')

@section('page-plugin-styles')
<link rel="stylesheet" href="{{ asset('assets/css/toastr.min.css') }}" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
@endsection

@section('breadcrumbs')
{{ Breadcrumbs::render('students.training.edit', $student, $training_record) }}
@endsection

@section('page-content')
<div class="page-header"><h1>Edit Training Record</h1>
</div><!-- /.page-header -->
<div class="row">
   <div class="col-xs-12">
        <!-- PAGE CONTENT BEGINS -->
        <div class="row">
            <div class="col-xs-12">
                <div class="well well-sm">
                    <button class="btn btn-sm btn-white btn-primary btn-round" type="button" onclick="window.location.href='{{ route('students.training.show', [$student, $training_record]) }}'">
                        <i class="ace-icon fa fa-arrow-left bigger-110"></i> Back
                    </button>
		    <button class="btn btn-sm btn-success btn-round" type="button" onclick="saveInformation();">
                        <i class="ace-icon fa fa-save bigger-110"></i> Save
                    </button>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <div class="widget-box">
                    <div class="widget-header"><h4 class="smaller">Student Details</h4></div>
                    <div class="widget-body">
                        <div class="widget-main">
                            <div class="info-div info-div-striped">
                                <div class="info-div-row">
                                    <div class="info-div-name"> Name </div>
                                    <div class="info-div-value"><span>{{ $student->full_name }}</span></div>
                                    <div class="info-div-name"> ULN </div>
                                    <div class="info-div-value"><span>{{ $student->uln }}</span></div>
                                    <div class="info-div-name"> NI </div>
                                    <div class="info-div-value"><span>{{ $student->ni }}</span></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @include('partials.session_message')

        @include('partials.session_error')

         <div class="row">
            <div class="col-sm-12">
               <div class="space"></div>

               {!! Form::model($training_record->getAttributes(), [
                   'method' => 'PATCH',
                   'url' => route('students.training.update', [$student, $training_record]),
                   'class' => 'form-horizontal',
                   'role' => 'form',
                   'name' => 'frmEditTrainingRecord',
                   'id' => 'frmEditTrainingRecord'
                   ])
                !!}
                <div class="widget-box widget-color-green">
                    <div class="widget-header">
                        <h4 class="widget-title">Edit Training Details</h4>
                    </div>
                    <div class="widget-body">
                        <div class="widget-main">
                            <div class="row">
                                <div class="col-sm-8">
                                    <div class="form-group row required {{ $errors->has('status_code') ? 'has-error' : ''}}">
                                        {!! Form::label('status_code', 'Training Status', ['class' => 'col-sm-4 control-label']) !!}
                                        <div class="col-sm-8">
                                            {!! Form::select('status_code',
                                            App\Models\LookupManager::getTrainingRecordStatus(),
                                            $student->status_code,
                                            ['class' => 'form-control', 'required', 'id' => 'status_code']) !!}
                                            {!! $errors->first('status_code', '<p class="text-danger">:message</p>') !!}
                                        </div>
                                    </div>
                                    <div class="form-group row required {{ $errors->has('start_date') ? 'has-error' : ''}}">
                                        {!! Form::label('start_date', 'Start Date', ['class' => 'col-sm-4 control-label']) !!}
                                        <div class="col-sm-8">
                                            {!! Form::date('start_date', null, ['class' => 'form-control', 'required']) !!}
                                            {!! $errors->first('start_date', '<p class="text-danger">:message</p>') !!}
                                        </div>
                                    </div>
                                    <div class="form-group row required {{ $errors->has('planned_end_date') ? 'has-error' : ''}}">
                                        {!! Form::label('planned_end_date', 'Planned End Date', ['class' => 'col-sm-4 control-label']) !!}
                                        <div class="col-sm-8">
                                            {!! Form::date('planned_end_date', null, ['class' => 'form-control', 'required']) !!}
                                            {!! $errors->first('planned_end_date', '<p class="text-danger">:message</p>') !!}
                                        </div>
                                    </div>
                                    <div class="form-group row {{ $errors->has('actual_end_date') ? 'has-error' : ''}}">
                                        {!! Form::label('actual_end_date', 'Completion Date', ['class' => 'col-sm-4 control-label']) !!}
                                        <div class="col-sm-8">
                                            {!! Form::date('actual_end_date', null, ['class' => 'form-control']) !!}
                                            {!! $errors->first('actual_end_date', '<p class="text-danger">:message</p>') !!}
                                        </div>
                                    </div>
                                    <div class="form-group row required {{ $errors->has('employer_location') ? 'has-error' : ''}}">
                                        {!! Form::label('employer_location', 'Employer', ['class' => 'col-sm-4 control-label']) !!}
                                        <div class="col-sm-8">
                                            {!! Form::select('employer_location',
                                            \App\Models\LookupManager::getEmployersLocationsDDL(),
                                            $training_record->employer_location,
                                            ['class' => 'form-control', 'placeholder' => '', 'required', 'id' => 'employer_location']) !!}
                                            {!! $errors->first('employer_location', '<p class="text-danger">:message</p>') !!}
                                        </div>
                                    </div>
                                    <div class="form-group row required {{ $errors->has('primary_assessor') ? 'has-error' : ''}}">
                                        {!! Form::label('primary_assessor', 'Primary Assessor', ['class' => 'col-sm-4 control-label']) !!}
                                        <div class="col-sm-8">
                                            {!! Form::select('primary_assessor', $assessors, null, ['class' => 'form-control', 'required']) !!}
                                            {!! $errors->first('primary_assessor', '<p class="text-danger">:message</p>') !!}
                                        </div>
                                    </div>
                                    <div class="form-group row {{ $errors->has('secondary_assessor') ? 'has-error' : ''}}">
                                        {!! Form::label('secondary_assessor', 'Secondary Assessor', ['class' => 'col-sm-4 control-label']) !!}
                                        <div class="col-sm-8">
                                            {!! Form::select('secondary_assessor', ['' => ''] + $secondary_assessors, null, ['class' => 'form-control']) !!}
                                            {!! $errors->first('secondary_assessor', '<p class="text-danger">:message</p>') !!}
                                        </div>
                                    </div>
                                    <div class="form-group row {{ $errors->has('tutor') ? 'has-error' : ''}}">
                                        {!! Form::label('tutor', 'Tutor', ['class' => 'col-sm-4 control-label']) !!}
                                        <div class="col-sm-8">
                                            {!! Form::select('tutor', ['' => ''] + $tutors, null, ['class' => 'form-control']) !!}
                                            {!! $errors->first('tutor', '<p class="text-danger">:message</p>') !!}
                                        </div>
                                    </div>
                                    <div class="form-group row required {{ $errors->has('verifier') ? 'has-error' : ''}}">
                                        {!! Form::label('verifier', 'Verifier', ['class' => 'col-sm-4 control-label']) !!}
                                        <div class="col-sm-8">
                                            {!! Form::select('verifier', ['' => ''] + $verifiers, null, ['class' => 'form-control', 'required']) !!}
                                            {!! $errors->first('verifier', '<p class="text-danger">:message</p>') !!}
                                        </div>
                                    </div>
                                    <div class="form-group row {{ $errors->has('otj_hours') ? 'has-error' : ''}}">
                                        {!! Form::label('otj_hours', 'OTJ Hours', ['class' => 'col-sm-4 control-label']) !!}
                                        <div class="col-sm-8">
                                            {!! Form::text('otj_hours', null, ['class' => 'form-control']) !!}
                                            {!! $errors->first('otj_hours', '<p class="text-danger">:message</p>') !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4"></div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="widget-box transparent">
                                        <div class="widget-header"><h4 class="widget-title">Portfolios/Qualifications</h4></div>
                                        <div class="widget-body">
                                            <div class="widget-main table-responsive">
                                                <table class="table table-bordered">
                                                    <tr class="bg-green">
                                                        <th>Portfolio</th>
                                                        <th>Edit Portfolio Details</th>
                                                    </tr>
                                                    @foreach($training_record->portfolios AS $portfolio)
                                                    <tr>
                                                        <td>
                                                            {{ $portfolio->qan }} {{ $portfolio->title }}
                                                        </td>
                                                        <td>
                                                            <table class="table">
                                                                <tr>
                                                                    <th>Status</th>
                                                                    <td>
                                                                        {!! Form::select('status_code'.$portfolio->id, ['1' => 'CONTINUING', '2' => 'COMPLETED', '3' => 'WITHDRAWN', '4' => 'TEMORARILY WITHDRAWN'],
                                                                        $portfolio->getOriginal('status_code'), ['class' => 'form-control']) !!}
                                                                        {!! $errors->first('status_code'.$portfolio->id, '<p class="text-danger">:message</p>') !!}
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Start Date</th>
                                                                    <td>
                                                                        {!! Form::date('start_date'.$portfolio->id, $portfolio->getOriginal('start_date'), ['class' => 'form-control']) !!}
                                                                        {!! $errors->first('start_date'.$portfolio->id, '<p class="text-danger">:message</p>') !!}
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Planned End Date</th>
                                                                    <td>
                                                                        {!! Form::date('planned_end_date'.$portfolio->id, $portfolio->getOriginal('planned_end_date'), ['class' => 'form-control']) !!}
                                                                        {!! $errors->first('planned_end_date'.$portfolio->id, '<p class="text-danger">:message</p>') !!}
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Actual End Date</th>
                                                                    <td>
                                                                        {!! Form::date('actual_end_date'.$portfolio->id, $portfolio->getOriginal('actual_end_date'), ['class' => 'form-control']) !!}
                                                                        {!! $errors->first('actual_end_date'.$portfolio->id, '<p class="text-danger">:message</p>') !!}
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Awarding Body Registration Number</th>
                                                                    <td>
                                                                        {!! Form::text('ab_registration_number'.$portfolio->id, $portfolio->ab_registration_number, ['class' => 'form-control inputLimiter', 'maxlength' => 15]) !!}
                                                                        {!! $errors->first('ab_registration_number'.$portfolio->id, '<p class="text-danger">:message</p>') !!}
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Awarding Body Registration Date</th>
                                                                    <td>
                                                                        {!! Form::date('ab_registration_date'.$portfolio->id, $portfolio->ab_registration_date, ['class' => 'form-control']) !!}
                                                                        {!! $errors->first('ab_registration_date'.$portfolio->id, '<p class="text-danger">:message</p>') !!}
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Certificate Applied Date</th>
                                                                    <td>
                                                                        {!! Form::date('cert_applied'.$portfolio->id, $portfolio->cert_applied, ['class' => 'form-control']) !!}
                                                                        {!! $errors->first('cert_applied'.$portfolio->id, '<p class="text-danger">:message</p>') !!}
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Certificate Received Date</th>
                                                                    <td>
                                                                        {!! Form::date('cert_received'.$portfolio->id, $portfolio->cert_received, ['class' => 'form-control']) !!}
                                                                        {!! $errors->first('cert_received'.$portfolio->id, '<p class="text-danger">:message</p>') !!}
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <th>Certificate Sent to Learner Date</th>
                                                                    <td>
                                                                        {!! Form::date('cert_sent_to_learner'.$portfolio->id, $portfolio->cert_sent_to_learner, ['class' => 'form-control']) !!}
                                                                        {!! $errors->first('cert_sent_to_learner'.$portfolio->id, '<p class="text-danger">:message</p>') !!}
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
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="widget-box transparent">
                                        <div class="widget-header"><h4 class="widget-title">EPA (End Point Assessment)</h4></div>
                                        <div class="widget-body">
                                            <div class="widget-main">
                                                <div class="row">
                                                    <div class="col-sm-4">
                                                        <div class="widget-box widget-color-green">
                                                            <div class="widget-header"><h5 class="widget-title">Gateway 1</h5></div>
                                                            <div class="widget-body">
                                                                <div class="widget-main">
                                                                    <div class="form-group row {{ $errors->has('epa1_date') ? 'has-error' : ''}}">
                                                                        {!! Form::label('epa1_date', 'Date', ['class' => 'col-sm-3 control-label']) !!}
                                                                        <div class="col-sm-9">
                                                                            {!! Form::date('epa1_date', null, ['class' => 'form-control']) !!}
                                                                            {!! $errors->first('epa1_date', '<p class="text-danger">:message</p>') !!}
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group row {{ $errors->has('epa1_status') ? 'has-error' : ''}}">
                                                                        {!! Form::label('epa1_status', 'Status', ['class' => 'col-sm-3 control-label']) !!}
                                                                        <div class="col-sm-9">
                                                                            {!! Form::select('epa1_status', ['' => '', 'P' => 'Pass', 'F' => 'Fail', 'NR' => 'Not Ready', 'R' => 'Referred'],
                                                                            null, ['class' => 'form-control']) !!}
                                                                            {!! $errors->first('epa1_status', '<p class="text-danger">:message</p>') !!}
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group row {{ $errors->has('epa1_comments') ? 'has-error' : ''}}">
                                                                        {!! Form::label('epa1_comments', 'Comments', ['class' => 'col-sm-12']) !!}
                                                                        <div class="col-sm-12">
                                                                            {!! Form::textarea('epa1_comments', null, ['class' => 'form-control']) !!}
                                                                            {!! $errors->first('epa1_comments', '<p class="text-danger">:message</p>') !!}
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <div class="widget-box widget-color-green">
                                                            <div class="widget-header"><h5 class="widget-title">Gateway 2</h5></div>
                                                            <div class="widget-body">
                                                                <div class="widget-main">
                                                                    <div class="form-group row {{ $errors->has('epa2_date') ? 'has-error' : ''}}">
                                                                        {!! Form::label('epa2_date', 'Date', ['class' => 'col-sm-3 control-label']) !!}
                                                                        <div class="col-sm-9">
                                                                            {!! Form::date('epa2_date', null, ['class' => 'form-control']) !!}
                                                                            {!! $errors->first('epa2_date', '<p class="text-danger">:message</p>') !!}
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group row {{ $errors->has('epa2_status') ? 'has-error' : ''}}">
                                                                        {!! Form::label('epa2_status', 'Status', ['class' => 'col-sm-3 control-label']) !!}
                                                                        <div class="col-sm-9">
                                                                            {!! Form::select('epa2_status', ['' => '', 'P' => 'Pass', 'F' => 'Fail', 'NR' => 'Not Ready', 'R' => 'Referred'],
                                                                            null, ['class' => 'form-control']) !!}
                                                                            {!! $errors->first('epa2_status', '<p class="text-danger">:message</p>') !!}
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group row {{ $errors->has('epa2_comments') ? 'has-error' : ''}}">
                                                                        {!! Form::label('epa2_comments', 'Comments', ['class' => 'col-sm-12']) !!}
                                                                        <div class="col-sm-12">
                                                                            {!! Form::textarea('epa2_comments', null, ['class' => 'form-control']) !!}
                                                                            {!! $errors->first('epa2_comments', '<p class="text-danger">:message</p>') !!}
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-sm-4">
                                                        <div class="widget-box widget-color-green">
                                                            <div class="widget-header"><h5 class="widget-title">Gateway 3</h5></div>
                                                            <div class="widget-body">
                                                                <div class="widget-main">
                                                                    <div class="form-group row {{ $errors->has('epa3_date') ? 'has-error' : ''}}">
                                                                        {!! Form::label('epa3_date', 'Date', ['class' => 'col-sm-3 control-label']) !!}
                                                                        <div class="col-sm-9">
                                                                            {!! Form::date('epa3_date', null, ['class' => 'form-control']) !!}
                                                                            {!! $errors->first('epa3_date', '<p class="text-danger">:message</p>') !!}
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group row {{ $errors->has('epa3_status') ? 'has-error' : ''}}">
                                                                        {!! Form::label('epa3_status', 'Status', ['class' => 'col-sm-3 control-label']) !!}
                                                                        <div class="col-sm-9">
                                                                            {!! Form::select('epa3_status', ['' => '', 'P' => 'Pass', 'F' => 'Fail', 'NR' => 'Not Ready', 'R' => 'Referred'],
                                                                            null, ['class' => 'form-control']) !!}
                                                                            {!! $errors->first('epa3_status', '<p class="text-danger">:message</p>') !!}
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group row {{ $errors->has('epa3_comments') ? 'has-error' : ''}}">
                                                                        {!! Form::label('epa3_comments', 'Comments', ['class' => 'col-sm-12']) !!}
                                                                        <div class="col-sm-12">
                                                                            {!! Form::textarea('epa3_comments', null, ['class' => 'form-control']) !!}
                                                                            {!! $errors->first('epa3_comments', '<p class="text-danger">:message</p>') !!}
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
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

            </div><!-- /.span -->
         </div><!-- /.user-profile -->


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
$(function(){

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

        highlight: function (e) {
            $(e).closest('.form-group').removeClass('has-info').addClass('has-error');
        },

        success: function (e) {
            $(e).closest('.form-group').removeClass('has-error').addClass('has-info');
            $(e).remove();
        },

        errorPlacement: function (error, element) {
            if(element.is('input[type=checkbox]') || element.is('input[type=radio]')) {
                var controls = element.closest('div[class*="col-"]');
                if(controls.find(':checkbox,:radio').length > 1) controls.append(error);
                else error.insertAfter(element.nextAll('.lbl:eq(0)').eq(0));
            }
            else
                error.insertAfter(element);
        },

        submitHandler: function(form) {
            $.ajax({
                url: form.action,
                type: form.method,
                data: $(form).serialize()
            }).done(function(response, textStatus) {
                $.alert({
                    title: (response.success) ? 'Success' : 'Error',
                    content: response.message,
                    type: (response.success) ? 'green' : 'red',
                    buttons: {
                        'OK': {
                            action: function(){
                                if(response.success)
                                    window.location.href="{{ route('students.training.show', [$student, $training_record]) }}";
                            }
                        }
                    }
                });
            }).fail(function(jqXHR, textStatus, errorThrown){
                $.alert({
                    title: 'Encountered an error!',
                    content: textStatus + ': '+ errorThrown ,
                    icon: 'fa fa-warning',
                    theme: 'supervan',
                    type: 'red'
                });
            });
        }
    });

    jQuery.validator.addMethod("greaterThan", function(value, element, params) {
        if($(params).val() == '')
            return true;
        if (!/Invalid|NaN/.test(new Date(value))) {
            return new Date(value) > new Date($(params).val());
        }
        return isNaN(value) && isNaN($(params).val()) || (Number(value) > Number($(params).val()));
    }, "Planned end date must be after the start date.");


});

function saveInformation()
{
    var form = document.forms["frmEditTrainingRecord"];
    $.ajax({
        url: form.action,
        type: form.method,
        data: $(form).serialize()
    }).done(function(response, textStatus) {
        $.alert({
            title: (response.success) ? 'Success' : 'Error',
            content: response.message,
            type: (response.success) ? 'green' : 'red',
            buttons: {
                'OK': {
                    action: function(){
                        if(response.success)
                            window.location.href="{{ route('students.training.show', [$student, $training_record]) }}";
                    }
                }
            }
        });
    }).fail(function(jqXHR, textStatus, errorThrown){
        $.alert({
            title: 'Encountered an error!',
            content: textStatus + ': '+ errorThrown ,
            icon: 'fa fa-warning',
            theme: 'supervan',
            type: 'red'
        });
    });
}

</script>

@endsection

