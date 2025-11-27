@extends('layouts.master')

@section('title', 'Map Evidence to PCs')

@section('page-plugin-styles')
<link rel="stylesheet" href="{{ asset('assets/css/toastr.min.css') }}" />
<style>

</style>
@endsection

@section('breadcrumbs')
{{ Breadcrumbs::render('students.training.evidences.mapping', $student, $training_record, $evidence) }}
@endsection

@section('page-content')
<div class="page-header">
    <h1>Map Evidence <small>{{ $training_record->system_ref }}</small></h1>
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
                <div class="widget-header">
                    <h5 class="widget-title smaller">Evidence Mapping <small style="color: white"><i class="ace-icon fa fa-angle-double-right"></i> Select all the PCs this evidence satisfies</small></h5>
                </div>
                <div class="widget-body">
                    <div class="widget-main">
                        {!! Form::open([
                            'url' => route('students.training.evidences.saveMapping', [$student, $training_record, $evidence]),
                            'class' => 'form-horizontal']) !!}
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

