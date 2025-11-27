@php
    $already_mapped_pcs = $evidence->mappings->pluck('portfolio_pc_id')->toArray();
    $currently_accepted = $evidence->getOriginal('evidence_status') == App\Models\Training\TrainingRecordEvidence::STATUS_ASSESSOR_ACCEPTED ? true : false;
@endphp
<div class="table-responsive">
    @foreach($training_record->portfolios AS $portfolio)
    <div class="widget-box transparent ui-sortable-handle">
        <div class="widget-header">
            <h5 class="widget-title">
                <i class="fa fa-graduation-cap"></i> {{ $portfolio->qan }} {{ $portfolio->title }}
            </h5>
            <div class="widget-toolbar">
                <a href="#" data-action="collapse"><i class="ace-icon fa fa-chevron-up"></i></a>
            </div>
        </div>
        <div class="widget-body">
            <div class="widget-main">
                @foreach($portfolio->units()->orderBy('unit_sequence')->get() AS $unit)
                <table class="table table-bordered table-hover">
                    <tr>
                        <th class="center" style="width: 8%;">
                            @if(!$currently_accepted)
                            <div class="checkbox">
                                <label>
                                    <input name="chkUnit[]" id="chkUnit{{ $unit->id }}" value="{{ $unit->id }}" class="ace ace-checkbox-2 chkUnit" type="checkbox" />
                                    <span class="lbl"> </span>
                                </label>
                            </div>
                            @endif
                        </th>
                        <th class="brown" colspan="3">
							<i class="fa fa-folder fa-lg"></i> 
							<h5 style="display: inline;">[{{ $unit->unit_owner_ref }}, {{ $unit->unique_ref_number }}] {{ $unit->title }}</h5>
							<span class="pull-right"><i class="ace-icon fa fa-chevron-down" onclick="showUnitEvidencesRows('{{ $unit->id }}', this);"></i></span>
						</th>
                    </tr>
                    @foreach($unit->pcs()->orderBy('pc_sequence')->get() AS $pc)
                    <tr style="cursor: pointer; display: none;" id="RowOfUnit{{ $unit->id }}Evidence{{ $pc->id }}">
                        <td class="center" style="width: 8%;">
                            @if($pc->assessor_signoff == 0 || $unit->iqa_status == 2)
                            <div class="checkbox">
                                <label>
                                <input name="chkPC[]" id="pc{{ $pc->id }}OfUnit{{ $unit->id }}" value="{{ $pc->id }}" class="ace ace-checkbox-2 chkPC" type="checkbox"
                                    {{ in_array($pc->id, $already_mapped_pcs) ? 'checked="checked"' : '' }}

                                    />
                                    <span class="lbl"> </span>
                                </label>
                            </div>
                            @else
                            <i class="fa fa-check-circle green fa-lg" data-rel="tooltip" title="This PC has been signed off"></i>
                            @endif
                        </td>
                        <td class="{{ $pc->assessor_signoff == 0 ? 'blue' : 'green' }}" style="width: 75%;">
                            <i class="fa fa-folder-open"></i>[{{ $pc->reference }}] {!! nl2br($pc->title) !!}
                        </td>
                        <td title="Number of evidences accepted / Number of evidences required">
                            {{ $pc->mapped_evidences()->where('evidence_status', App\Models\Training\TrainingRecordEvidence::STATUS_ASSESSOR_ACCEPTED)->count() }}/{{ $pc->min_req_evidences }}
                        </td>
                        <td>
                            @foreach($pc->mapped_evidences AS $pc_evi)
                                @include('students.training.evidences.partials.evidence_popover', ['_evidence_popover' => $pc_evi, 'current_screen_evidence' => $evidence])
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
{{--    if it is student then only enable these buttons if evidence status is submitted or resubmitted--}}
    @if( !\Auth::user()->isStudent() || (\Auth::user()->isStudent() && in_array($evidence->getOriginal('evidence_status'), [App\Models\Training\TrainingRecordEvidence::STATUS_LEARNER_SUBMITTED, App\Models\Training\TrainingRecordEvidence::STATUS_LEARNER_RESUBMITTED])) )
    <div class="clearfix form-actions center">
        <button class="btn btn-sm btn-success btn-round" type="submit"><i class="ace-icon fa fa-save bigger-110"></i>Save</button>&nbsp; &nbsp; &nbsp;
        <button class="btn btn-sm btn-round" type="reset"><i class="ace-icon fa fa-undo bigger-110"></i>Reset</button>
    </div>
    @endif
</div>

