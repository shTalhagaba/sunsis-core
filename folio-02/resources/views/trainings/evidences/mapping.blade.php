@extends('layouts.master')

@section('title', 'Map Evidence to PCs')

@section('page-plugin-styles')
<link rel="stylesheet" href="{{ asset('assets/css/toastr.min.css') }}" />
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

@section('page-content')
<div class="page-header">
    <h1>Map Evidence <small>{{ $training->system_ref }}</small></h1>
</div><!-- /.page-header -->
<div class="row">
    <div class="col-xs-12">

    <!-- PAGE CONTENT BEGINS -->

    <button class="btn btn-sm btn-white btn-default btn-round" type="button" onclick="window.location.href='{{ route('trainings.show', $training) }}'">
        <i class="ace-icon fa fa-arrow-left bigger-110"></i> Cancel
    </button>
    <div class="hr hr-12 hr-dotted"></div>
    
    @include('trainings.partials.training_quick_details', ['showOverallPercentage' => true])

    <div class="space-12"></div>

    <div class="row">
        <div class="col-sm-12">
            <div class="widget-box transparent">
                <div class="widget-header"><h5 class="widget-title smaller">Evidence Details</h5></div>
                <div class="widget-body">
                    <div class="widget-main">
                        @include('trainings.evidences.partials.evidence-details', ['_evi_details' => $evidence])
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
                <div class="widget-header">
                    <h5 class="widget-title smaller">Evidence Mapping <small style="color: white"><i class="ace-icon fa fa-angle-double-right"></i> Select all the PCs this evidence satisfies</small></h5>
                </div>
                <div class="widget-body">
                    {!! Form::open([
                        'url' => route('trainings.evidences.saveMapping', [$training, $evidence]),
                        'class' => 'form-horizontal']) !!}
                    <div class="widget-main">
                        @include('trainings.evidences.partials.mapping_form', [
                            'training' => $training,
                            'evidence' => $evidence,
                            'btnMapping' => 'Mapping'
                            ])
                    </div>
                    <div class="widget-toolbox padding-8 clearfix">
                        <div class="center">
                            @if( auth()->user()->isStaff() && auth()->user()->can('assess-evidence') )
                            <button class="btn btn-sm btn-success btn-round" type="submit">
                                <i class="ace-icon fa fa-save bigger-110"></i>
                                Save Assessment
                            </button>
                            @endif
                            @if( auth()->user()->isStudent() && in_array($evidence->getOriginal('evidence_status'), [App\Models\Training\TrainingRecordEvidence::STATUS_LEARNER_SUBMITTED, App\Models\Training\TrainingRecordEvidence::STATUS_LEARNER_RESUBMITTED]))
                            <button class="btn btn-sm btn-success btn-round" type="submit">
                                <i class="ace-icon fa fa-save bigger-110"></i>
                                Save Mapping
                            </button>
                            @endif
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
function showUnitEvidencesRows(unit_id, element)
{
    var rows_id = 'RowOfUnit'+unit_id+'Evidence';
    $("tr[id^=" + rows_id + "]").toggle();
    $(element).toggleClass('fa-chevron-down fa-chevron-up');
}
</script>
@endsection

