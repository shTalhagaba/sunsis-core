@extends('layouts.master')

@section('title', 'Signoff Portfolio Progress')

@section('page-plugin-styles')
<link rel="stylesheet" href="{{ asset('assets/css/toastr.min.css') }}" />
<style>
.popover{
        max-width:600px;
    }
</style>
@endsection

@section('breadcrumbs')
{{ Breadcrumbs::render('students.training.signoffProgress', $student, $training_record, $portfolio) }}
@endsection

@section('page-content')
<div class="page-header">
    <h1>Signoff Progress <small>{{ $training_record->system_ref }}</small></h1>
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
                                <div class="info-div-name"> Work Contact </div>
                                <div class="info-div-value">
                                    <span>
                                        <i class="fa fa-envelope blue bigger-110"></i> {{ $student->primary_email }}{!! $student->homeAddress()->telephone != '' ? '<br><i class="fa fa-phone light-orange bigger-110"></i> <span>' . $student->homeAddress()->telephone . '</span>' : '' !!}
                                        {!! $student->homeAddress()->mobile != '' ? '<br><i class="fa fa-mobile light-orange bigger-110"></i> <span>' . $student->homeAddress()->mobile . '</span><br>' : '' !!}
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
                                    @foreach($training_record->portfolios AS $_p)
                                    <span><i class="fa fa-graduation-cap"></i> {{ $_p->qan }} - {{ $_p->title }}</span><br>
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

    @include('partials.session_message')

    @include('partials.session_error')

    <div class="row">
        <div class="col-sm-12">
            <div class="widget-box widget-color-green">
                <div class="widget-header">
                    <h5 class="widget-title">
                        Signoff Progress
                        <small class="white"><i class="ace-icon fa fa-angle-double-right"></i> select the performance criteria you want to signoff.</small>
                    </h5>
                </div>
                <div class="widget-body">
                    {!! Form::open([
                        'url' => route('students.training.saveSignoffProgress',
                        [$student, $training_record, $portfolio]), 'class' => 'form-horizontal']) !!}
                    <div class="widget-main">
                        <p class="alert alert-info">Following are the units and performance criteria of this portfolio which have been mapped to the evidences.
                            You can now signoff the performance criteria using this functionality.
                        </p>
                        <div class="responsive">
                                <div class="widget-box transparent ui-sortable-handle">
                                    <div class="widget-header">
                                        <h5 class="widget-title">
                                            <i class="fa fa-graduation-cap"></i> <strong>{{ $portfolio->qan }} {{ $portfolio->title }}</strong>
                                        </h5>
                                        <div class="widget-toolbar">
                                            <a href="#" data-action="collapse"><i class="ace-icon fa fa-chevron-down"></i></a>
                                        </div>
                                    </div>
                                    <div class="widget-body">
                                        <div class="widget-main">
                                            @foreach($portfolio->units AS $unit)
                                            <table class="table table-bordered table-hover">
                                                <tr>
                                                    <th class="brown" colspan="3"><i class="fa fa-folder fa-lg"></i> [{{ $unit->unit_owner_ref }}, {{ $unit->unique_ref_number }}] <h5 style="display: inline;">{{ $unit->title }}</h5></th>
                                                    <th class="center" style="width: 8%;">
                                                        @if(!$unit->isSignedOff() && $unit->isAnyPCReadyForSignoff() > 0)
                                                        <div class="checkbox">
                                                            <label>
                                                                <input name="chkUnit[]" id="chkUnit{{ $unit->id }}" value="{{ $unit->id }}" class="ace ace-checkbox-2 chkUnit" type="checkbox" />
                                                                <span class="lbl"> </span>
                                                            </label>
                                                        </div>
                                                        @endif
                                                    </th>
                                                </tr>
                                                @foreach($unit->pcs AS $pc)
                                                <tr style="cursor: pointer;">
                                                    <td class="{{ $pc->assessor_signoff == 0 ? 'blue' : 'green' }}" style="width: 75%;">
                                                        <i class="fa fa-folder-open"></i> [{{ $pc->reference }}] {!! nl2br($pc->title) !!}</span>
                                                    </td>
                                                    <td>
                                                        @foreach($pc->mapped_evidences AS $evidence)
                                                            @include('students.training.evidences.partials.evidence_popover', ['_evidence_popover' => $evidence])
                                                        @endforeach
                                                    </td>
                                                    <td title="Number of evidences accepted / Number of evidences required">
                                                        {{ $pc->mapped_evidences()->where('evidence_status', App\Models\Training\TrainingRecordEvidence::STATUS_ASSESSOR_ACCEPTED)->count() }}/{{ $pc->min_req_evidences }}
                                                    </td>
                                                    <td class="center">
                                                        @if($pc->isReadyForSignOff())
                                                        <div class="checkbox">
                                                            <label>
                                                            <input name="chkPC[]" id="pc{{ $pc->id }}OfUnit{{ $unit->id }}" value="{{ $pc->id }}"
                                                                class="ace ace-checkbox-2 chkPC" type="checkbox" />
                                                                <span class="lbl"> </span>
                                                            </label>
                                                        </div>
                                                        @endif
                                                        @if($pc->assessor_signoff == 1)
                                                        <i class="fa fa-check-circle green fa-2x" data-rel="tooltip" title="This PC has been signed off"></i>
                                                        @endif
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </table>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>



                        </div>
                    </div>
                    <div class="widget-toolbox padding-8 clearfix">
                        <div class="center">
                            <button class="btn btn-sm btn-success btn-round" type="submit"><i class="ace-icon fa fa-save bigger-110"></i>Save Signoff</button>&nbsp; &nbsp; &nbsp;
                            <button class="btn btn-sm btn-round" type="reset"><i class="ace-icon fa fa-undo bigger-110"></i>Reset</button>
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


@section('page-plugin-scripts')
<script src="{{ asset('assets/js/toastr.min.js') }}"></script>
@endsection

@section('page-inline-scripts')
<script type="text/javascript">

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
});

</script>
@endsection

