@php
    $already_mapped_pcs = $evidence->mappings->pluck('portfolio_pc_id')->toArray();
    $already_mapped_units_ids = App\Models\Training\PortfolioPC::whereIn('id', $already_mapped_pcs)
        ->distinct()
        ->pluck('portfolio_unit_id')
        ->toArray();
    $currently_accepted =
        $evidence->getOriginal('evidence_status') ==
        App\Models\Training\TrainingRecordEvidence::STATUS_ASSESSOR_ACCEPTED
            ? true
            : false;
    // tutors can be assigned at training record as well as individual aim level. so if assigned to individual aim level then only those should be allowed for mapping.
    $portfoiosAllowedForMapping = $training->portfolios()->pluck('id')->toArray();
    if (auth()->user()->isTutor() && $training->tutor != auth()->user()->id) {
        $portfoiosAllowedForMapping = $training
            ->portfolios()
            ->where('fs_tutor_id', auth()->user()->id)
            ->pluck('id')
            ->toArray();
    }
@endphp
<div class="table-responsive">
    @foreach ($training->portfolios as $portfolio)
        @if (!in_array($portfolio->id, $portfoiosAllowedForMapping))
            @continue
        @endif
        <div class="widget-box transparent ui-sortable-handle">
            <div class="widget-header">
                <h5 class="widget-title bolder">
                    <i class="fa fa-graduation-cap"></i> {{ $portfolio->qan }} {{ $portfolio->title }}
                </h5>
                <div class="widget-toolbar">
                    <a href="#" data-action="collapse"><i class="ace-icon fa fa-chevron-up"></i></a>
                </div>
            </div>
            <div class="widget-body">
                <div class="widget-main">
                    @foreach ($portfolio->units()->orderBy('unit_sequence')->get() as $unit)
                        <table class="table table-bordered table-hover">
                            <tr>
                                <th class="center" style="width: 8%;">
                                    @if (!$currently_accepted)
                                        <div class="checkbox">
                                            <label>
                                                <input name="chkUnit[]" id="chkUnit{{ $unit->id }}"
                                                    value="{{ $unit->id }}" class="ace ace-checkbox-2 chkUnit"
                                                    type="checkbox" />
                                                <span class="lbl"> </span>
                                            </label>
                                        </div>
                                    @endif
                                </th>
                                <th class="brown" colspan="3">
                                    <i class="fa fa-folder fa-lg"></i>
                                    <h5 style="display: inline;">[{{ $unit->unit_owner_ref }},
                                        {{ $unit->unique_ref_number }}] {{ $unit->title }}</h5>
                                    <span class="pull-right">
                                        <i class="ace-icon fa fa-chevron-{{ in_array($unit->id, $already_mapped_units_ids) ? 'up' : 'down' }}"
                                            onclick="showUnitEvidencesRows('{{ $unit->id }}', this);"></i>
                                    </span>
                                </th>
                            </tr>
                            @foreach ($unit->pcs()->orderBy('pc_sequence')->get() as $pc)
                                <tr style="cursor: pointer; display: {{ in_array($unit->id, $already_mapped_units_ids) ? '' : 'none' }};"
                                    id="RowOfUnit{{ $unit->id }}Evidence{{ $pc->id }}">
                                    <td class="center" style="width: 8%;">
                                        @if ($pc->assessor_signoff == 0 || $unit->iqa_status == 2)
                                            <div class="checkbox">
                                                <label>
                                                    <input name="chkPC[]"
                                                        id="pc{{ $pc->id }}OfUnit{{ $unit->id }}"
                                                        value="{{ $pc->id }}" class="ace ace-checkbox-2 chkPC"
                                                        type="checkbox"
                                                        {{ in_array($pc->id, $already_mapped_pcs) ? 'checked="checked"' : '' }} />
                                                    <span class="lbl"> </span>
                                                </label>
                                            </div>
                                        @else
                                            <i class="fa fa-check-circle green fa-lg" data-rel="tooltip"
                                                title="This PC has been signed off"></i>
                                        @endif
                                    </td>
                                    <td class="{{ $pc->assessor_signoff == 0 ? 'blue' : 'green' }}"
                                        style="width: 75%;">
                                        <i class="fa fa-folder-open"></i> [{{ $pc->reference }}]
                                        {!! nl2br($pc->title) !!}
                                    </td>
                                    <td title="Number of evidences accepted / Number of evidences required">
                                        {{ $pc->mapped_evidences()->where('evidence_status', App\Models\Training\TrainingRecordEvidence::STATUS_ASSESSOR_ACCEPTED)->count() }}/{{ $pc->min_req_evidences }}
                                    </td>
                                    <td>
                                        @foreach ($pc->mapped_evidences as $pc_evi)
                                            @include('trainings.evidences.partials.evidence_popover', [
                                                '_evidence_popover' => $pc_evi,
                                                'current_screen_evidence' => $evidence,
                                            ])
                                        @endforeach
                                    </td>
                                </tr>
                            @endforeach
                        </table>
                    @endforeach
                </div>
            </div>
        </div>
    @endforeach
</div>
