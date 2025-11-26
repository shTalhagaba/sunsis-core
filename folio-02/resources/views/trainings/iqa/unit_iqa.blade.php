@extends('layouts.master')

@section('title', 'IQA Portfolio Unit')

@section('page-plugin-styles')
    <link rel="stylesheet" href="{{ asset('assets/css/chosen.min.css') }}" />
@endsection

@section('page-content')
    <div class="page-header">
        <h1>IQA Portfolio Unit</h1>
    </div><!-- /.page-header -->
    <div class="row">
        <div class="col-xs-12">
            <!-- PAGE CONTENT BEGINS -->
            <button class="btn btn-sm btn-white btn-default btn-round" type="button"
                onclick="window.location.href='{{ $cancelUrl }}'">
                <i class="ace-icon fa fa-arrow-left bigger-110"></i> Cancel
            </button>
            <div class="hr hr-12 hr-dotted"></div>
            @include('trainings.partials.training_quick_details', ['showOverallPercentage' => true])

            <div class="space-12"></div>

            @include('partials.session_message')

            @include('partials.session_error')

            <div class="row">
                <div class="col-sm-12">
                    @include('trainings.iqa.partials.unit_details', ['unit' => $unit])
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="widget-box transparent">
                        <div class="widget-header">
                            <h5 class="widget-title">Evidences mapped to the performance criteria of this unit</h5>
                        </div>
                        <div class="widget-body">
                            <div class="widget-main">
                                Total number of <abbr title="Performance Criteria">PCs</abbr> in this unit: <strong>{{ $unit->pcs->count() }}</strong><br>
                                Total number of evidences mapped to this unit: <strong>{{ count($distinctEvidences) }}</strong><br>
                                <div class="row">
                                    @forelse ($evidencesMapped as $evidenceMapped)
                                    <div class="col-sm-4">
                                        @include('trainings.iqa.partials.evidence_well', [
                                            'evidenceMapped' => $evidenceMapped, 
                                            'training' => $training
                                            ])
                                    </div>
                                    @empty
                                        <i>No evidences are mapped to any criteria of this unit yet.</i>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    @include('trainings.iqa.partials.unit_iqa_history', [
                            'training' => $training,
                            'unit' => $unit,
                        ])
 
                    @if (auth()->user()->can('iqa-assessment') && !$unit->iqa_completed)
                        @include('trainings.iqa.partials.unit_iqa_form', [
                            'unit' => $unit,
                            'training' => $training,
                            'rejectedPcsInLastAssessment' => $rejectedPcsInLastAssessment,
                            'iqaSamplePlan' => $iqaSamplePlan,
                            'acceptedPcsInLastAssessment' => $acceptedPcsInLastAssessment,
                        ])                        
                    @endif

                    @if (auth()->user()->isAssessor() && !$unit->iqa_completed)
                        @include('trainings.iqa.partials.unit_iqa_reply_form_assessor', [
                            'unit' => $unit,
                            'training' => $training,
                            'rejectedPcsInLastAssessment' => $rejectedPcsInLastAssessment,
                            'iqaSamplePlan' => $iqaSamplePlan,
                            'acceptedPcsInLastAssessment' => $acceptedPcsInLastAssessment,
                        ])                        
                    @endif
                    
                    @if ($unit->iqa_completed)
                        @include('trainings.iqa.partials.unit_pc_iqa_table', [
                            'unit' => $unit,
                            'acceptedPcsInLastAssessment' => $acceptedPcsInLastAssessment,
                            'rejectedPcsInLastAssessment' => $rejectedPcsInLastAssessment,
                            'statsLabels' => false,
                        ])
                    @endif
                </div>
            </div>

        </div><!-- /.col -->
    </div><!-- /.row -->
@endsection

@section('page-plugin-scripts')
    <script src="{{ asset('assets/js/chosen.jquery.min.js') }}"></script>
@endsection

@section('page-inline-scripts')

    <script type="text/javascript">

        if(!ace.vars['touch'])
        {
            $('.chosen-select').chosen({allow_single_deselect:true});

            $(window)
                .off('resize.chosen')
                .on('resize.chosen', function() {
                    $('.chosen-select').each(function() {
                        var $this = $(this);
                        $this.next().css({'width': $this.parent().width()});
                    })
                }).trigger('resize.chosen');
            $(document).on('settings.ace.chosen', function(e, event_name, event_val) {
                if(event_name != 'sidebar_collapsed') return;
                $('.chosen-select').each(function() {
                    var $this = $(this);
                    $this.next().css({'width': $this.parent().width()});
                })
            });
        }

        $(".chkEvidenceStatus").on("change", function(e) {
            e.preventDefault();
            const checkedStatus = !!this.checked;
            const evidenceId = $(this).data('tr-evidence-id');
            $.ajax({
                type: 'POST',
                url: '{{ route('ajax.updateTrainingEvidenceIqaCheckStatus') }}',
                data: {
                    tr_evidence_id: evidenceId,
                    iqa_plan_id: '{{ isset($iqaPlanEntry) ? $iqaPlanEntry->id : '' }}',
                    checked: checkedStatus ? 1 : 0,
                    _token: '{{ csrf_token() }}'
                }
            });
        });
        
    </script>

@endsection
