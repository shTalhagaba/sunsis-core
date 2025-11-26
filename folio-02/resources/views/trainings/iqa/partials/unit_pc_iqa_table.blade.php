<div class="table-responsive">
    <table class="table table-bordered table-hover">
        <caption class="bolder text-info center">
            [{{ $unit->portfolio->qan }}] {{ $unit->portfolio->title }}
        </caption>
        <thead>
            <tr>
                <th>Title</th>
                <th style="width: 15%">Evidences</th>
                <th title="Mapped / Required">Map./Req.</th>
                <th style="width: 25%">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Action&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                </th>
            </tr>
        </thead>
        @foreach ($unit->pcs as $pc)
            @php
                $trClass = '';
                if (in_array($pc->id, $acceptedPcsInLastAssessment))
                {
                    $trClass = 'bg-success';
                }
                elseif(in_array($pc->id, $rejectedPcsInLastAssessment))
                {
                    $trClass = 'bg-danger';
                }
            @endphp
            <tr class="{{ $trClass }}">
                <td style="width: 75%;">
                    <i class="fa fa-folder-open"></i> [{{ $pc->reference }}]
                    {!! nl2br($pc->title) !!}</span>
                </td>
                <td>
                    @foreach ($pc->mapped_evidences as $evidence)
                        @include(
                            'trainings.evidences.partials.evidence_popover',
                            ['_evidence_popover' => $evidence]
                        )
                    @endforeach
                </td>
                <td title="Number of evidences accepted / Number of evidences required">
                    {{ $pc->getAcceptedEvidencesCount() }}/{{ $pc->min_req_evidences }}
                </td>
                <td>
                    @if(! $unit->iqa_completed && auth()->user()->can('iqa-assessment'))
                        @if (in_array($pc->id, $acceptedPcsInLastAssessment))
                            {!! Form::select('pc_iqa_status_' . $pc->id, 
                                App\Models\Training\PortfolioUnitIqa::getStatusList(), 
                                App\Models\Training\PortfolioUnitIqa::STATUS_IQA_ACCEPTED, [
                                'class' => 'form-control pc_iqa_status',
                                'placeholder' => 'Not Sampled',
                            ]) !!}
                        @elseif(in_array($pc->id, $rejectedPcsInLastAssessment))
                            {!! Form::select('pc_iqa_status_' . $pc->id, 
                                App\Models\Training\PortfolioUnitIqa::getStatusList(), 
                                App\Models\Training\PortfolioUnitIqa::STATUS_IQA_REFERRED, [
                                'class' => 'form-control pc_iqa_status',
                                'placeholder' => 'Not Sampled',
                            ]) !!}
                        @else
                            {!! Form::select('pc_iqa_status_' . $pc->id, App\Models\Training\PortfolioUnitIqa::getStatusList(), null, [
                                'class' => 'form-control pc_iqa_status',
                                'placeholder' => 'Not Sampled',
                            ]) !!}
                        @endif
                    @else
                        @if (in_array($pc->id, $acceptedPcsInLastAssessment))
                            IQA Accepted
                        @elseif(in_array($pc->id, $rejectedPcsInLastAssessment))
                            IQA Referred
                        @endif
                    @endif
                </td>
            </tr>
        @endforeach
    </table>

    <div class="info-div info-div-striped">
        <div class="info-div-row">
            <div class="info-div-name"> Number of Performance Criteria - IQA Accepted: </div>
            <div class="info-div-value"><span class="{{ $statsLabels ? 'lblIqaAcceptedPcs' : '' }} bolder">{{ count($acceptedPcsInLastAssessment) }}</span></div>
        </div>
        <div class="info-div-row">
            <div class="info-div-name"> Number of Performance Criteria - IQA Referred: </div>
            <div class="info-div-value"><span class="{{ $statsLabels ? 'lblIqaRejectedPcs' : '' }} bolder">{{ count($rejectedPcsInLastAssessment) }}</span></div>
        </div>
        <div class="info-div-row">
            <div class="info-div-name"> Total Checked: </div>
            <div class="info-div-value"><span class="{{ $statsLabels ? 'lblIqaTotalSampled' : '' }} bolder">{{ count($acceptedPcsInLastAssessment) + count($rejectedPcsInLastAssessment) }}</span></div>
        </div>
    </div>
</div>
