@extends('layouts.master')

@section('title', 'Assess Evidence')

@section('page-plugin-styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
<style>
.circle-icon {
    background: #ffc0c0;
    padding:5px;
    border-radius: 50%;
}
.popover{
        max-width:600px;
    }
</style>
@endsection

@section('breadcrumbs')
{{ Breadcrumbs::render('students.training.evidences.assess', $student, $training_record, $evidence) }}
@endsection

@section('page-content')
<div class="page-header">
    <h1>Check Evidence <small>{{ $training_record->system_ref }}</small></h1>
</div><!-- /.page-header -->
<div class="row">
   <div class="col-xs-12">

    <!-- PAGE CONTENT BEGINS -->
    <div class="well well-sm">
        <button class="btn btn-sm btn-white btn-primary btn-round" type="button" onclick="window.location.href='{{ route('students.training.show', [$student, $training_record]) }}'">
            <i class="ace-icon fa fa-arrow-left bigger-110"></i> Back
        </button>
    </div>
    <div class="row">
        <div class="col-sm-6">
            <div class="widget-box transparent">
                <div class="widget-header"><h5 class="widget-title">Learner Details</h5></div>
                <div class="widget-body">
                    <div class="widget-main">
                        <div class="info-div info-div-striped">
                            <div class="info-div-row">
                                <div class="info-div-name"> Learner </div>
                                <div class="info-div-value"><span>{{ $student->full_name }}</span></div>
                            </div>
                            <div class="info-div-row">
                                <div class="info-div-name"> Primary Email </div>
                                <div class="info-div-value">
                                    <span>
                                        <i class="fa fa-envelope blue bigger-110"></i> {{ $student->primary_email }}
                                    </span>
                                </div>
                            </div>
                            <div class="info-div-row">
                                <div class="info-div-name"> Employer </div>
                                <div class="info-div-value"><span>{{ $student->employer->legal_name }}</span></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-sm-6">
            <div class="widget-box transparent">
                <div class="widget-header"><h5 class="widget-title">Training Details</h5></div>
                <div class="widget-body">
                    <div class="widget-main">
                        <div class="info-div info-div-striped">
                            <div class="info-div-row">
                                <div class="info-div-name"> Status </div>
                                <div class="info-div-value"><span><span class="label label-md label-info arrowed-in arrowed-in-right">{{ $training_record->status_code }}</span></span></div>
                            </div>
                            <div class="info-div-row">
                                <div class="info-div-name"> Dates </div>
                                <div class="info-div-value">
                                    <span>{{ $training_record->start_date }} - {{ $training_record->planned_end_date }}</span>
                                </div>
                            </div>
                            <div class="info-div-row">
                                <div class="info-div-name"> Portfolio(s) </div>
                                <div class="info-div-value">
                                    @foreach($training_record->portfolios AS $portfolio)
                                    <span><i class="fa fa-graduation-cap"></i> {{ $portfolio->qan }} - {{ $portfolio->title }}</span><br>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="space-12"></div>

   <div class="row">
        <div class="col-sm-12">
            <div class="widget-box transparent">
                <div class="widget-header"><h5 class="widget-title smaller">Evidence Details</h5></div>
                <div class="widget-body">
                    <div class="widget-main">
                        @include('students.training.evidences.partials.evidence-details', ['_evi_details' => $evidence])
                    </div>
                </div>
            </div><hr>
        </div>
    </div>

    @include('partials.session_message')

    @include('partials.session_error')

    <div class="row">
        <div class="col-sm-12">
            <div class="widget-box  widget-color-blue2 light-border">
                <div class="widget-header"><h5 class="widget-title">Assessment Information</h5></div>
                <div class="widget-body">
                    <div class="widget-main">
                        {!! Form::open([
                            'url' => route('students.training.evidences.saveAssessment', [$student, $training_record, $evidence]),
                            'class' => 'frmEvidenceAssessment form-horizontal',
                            ]) !!}
                        <div class="form-group row {{ $errors->has('evidence_status') ? 'has-error' : ''}}">
                            {!! Form::label('evidence_status', 'Evidence Status', ['class' => 'col-sm-4 control-label']) !!}
                            <div class="col-sm-8">
                                {!! Form::select('evidence_status', $assessment_ddl, null, ['class' => 'form-control', 'placeholder' => '']) !!}
                                {!! $errors->first('evidence_status', '<p class="text-danger">:message</p>') !!}
                            </div>
                        </div>
                        <div class="form-group row {{ $errors->has('assessor_comments') ? 'has-error' : ''}}">
                            {!! Form::label('assessor_comments', 'Assessor Comments', ['class' => 'col-sm-4 control-label']) !!}
                            <div class="col-sm-8">
                                {!! Form::textarea('assessor_comments', null, ['class' => 'form-control', 'rows' => '10', 'id' => 'assessor_comments']) !!}
                                {!! $errors->first('assessor_comments', '<p class="text-danger">:message</p>') !!}
                            </div>
                        </div>
                        @include('students.training.evidences.partials.mapping_form', [$student, $training_record, $evidence])
                        {!! Form::close() !!}
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
@endsection

@section('page-inline-scripts')
<script type="text/javascript">

$('input[type=radio][name=evidence_type]').on('click', function(){
    $('input[type=radio][name=evidence_type]').each(function(){
        $('#'+this.value).hide();
    });
    $('#'+this.value).show();
});

$(function(){
    $('[data-rel=tooltip]').tooltip();
    $('[data-rel=popover]').popover({html:true});

    $('input[type="checkbox"][name="chkPC[]"]').each(function(){
        if(this.checked)
        {
            var unit_number = this.id.replace('pc'+this.value+'OfUnit', '');
            $('input[type="checkbox"][id="chkUnit'+unit_number+'"]').prop('checked', true);
        }
    });

    $('input[type=checkbox][id^=chkUnit]').on('click', function(){
        var unit_number = this.id.replace('chkUnit', '');
        if(this.checked)
        {
            $("input[type='checkbox'][id$='OfUnit"+unit_number+"']").each(function() {
                $(this).prop('checked', true);
            });
        }
        else
        {
            $("input[type='checkbox'][id$='OfUnit"+unit_number+"']").each(function() {
                $(this).prop('checked', false);
            });
        }
    });

    $('input[type="checkbox"][name="chkPC[]"]').on('click', function(){
        var unit_number = this.id.replace('pc'+this.value+'OfUnit', '');
        if(this.checked) // if pc is clicked then check the Unit checkbox too.
        {
            $('input[type="checkbox"][id="chkUnit'+unit_number+'"]').prop('checked', true);
        }
        else // if all pcs of a unit are unticked then untick the unit
        {
            var allPCUnChecked = true;
            $("input[type='checkbox'][id$='OfUnit"+unit_number+"']").each(function() {
                if(this.checked)
                {
                    allPCUnChecked = false;
                    return false;
                }
            });
            if(allPCUnChecked)
            {
                $('input[type="checkbox"][id="chkUnit'+unit_number+'"]').prop('checked', false);
            }
        }

    });

    $('.frmEvidenceAssessment').submit(function(e) {
        e.preventDefault();
        var currentForm = this;
        var evidence_status = currentForm.evidence_status.value;
        if(evidence_status == '')
        {
            return $.alert({
                title: 'Validation Error!',
                content: 'Please select the evidence status.',
                type: 'red',
                action: function(){
                    return false;
                }
            });
        }
        if(currentForm.assessor_comments.value == '')
        {
            return $.alert({
                title: 'Validation Error!',
                content: 'Please provide your comments.',
                type: 'red',
                action: function(){
                    return false;
                }
            });
        }
        if($(this).find('input[name="chkPC[]"]:checked').length == 0 && evidence_status == 2)
        {
            return $.alert({
                title: 'Validation Error!',
                content: 'You are going to accept this evidence but you have not selected any performance criteria. Please select the criteria this evidence satisfies.',
                type: 'red',
                action: function(){
                    return false;
                }
            });
        }

        var message = 'You are going to accept this evidence, are you sure you want to continue?';
        if(evidence_status == 3)
            message = 'You are going to reject this evidence, are you sure you want to continue?';


        $.confirm({
            title: "Confirmation",
            content: message,
            icon: 'fa fa-question-circle',
            animation: 'scale',
            theme: 'bootstrap',
            closeIcon: true,
            type: 'orange',
            buttons: {
                Cancel: {},
                Confirm: function () {
                    currentForm.submit();
                }
            }
        });
    });

});

function showUnitEvidencesRows(unit_id, element)
{
    var rows_id = 'RowOfUnit'+unit_id+'Evidence';
    $("tr[id^=" + rows_id + "]").toggle();
    $(element).toggleClass('fa-chevron-down fa-chevron-up');
}
</script>
@endsection

