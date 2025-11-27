<div class="widget-box {{ $unit->getOriginal('unit_group') == 1 ? 'widget-color-green' : 'widget-color-blue' }} collapsed UnitPanel{{ $unit->programme_qualification_id }}">
    <div class="widget-header">
        <span class="widget-title"><strong>{{ $unit->unit_owner_ref }}, {{ $unit->unique_ref_number }}</strong></span>
        <div class="widget-toolbar">
            <a href="#" data-action="collapse">
                <i class="ace-icon fa fa-chevron-down"></i>
            </a>
        </div>
    </div>
    <div class="widget-body">
        <div class="widget-main">
            <div class="info-div info-div-striped">
                <div class="info-div-row">
                    <div class="info-div-name"> Unit Title </div>
                    <div class="info-div-value"><span>{!! nl2br($unit->title) !!}</span></div>
                </div>
                <div class="info-div-row">
                    <div class="info-div-name"> Unit Group </div>
                    <div class="info-div-value"><span>{{ $unit->unit_group }}</span></div>
                </div>
                <div class="info-div-row">
                    <div class="info-div-name"> Unit GLH </div>
                    <div class="info-div-value"><span>{{ $unit->glh }}</span></div>
                </div>
                <div class="info-div-row">
                    <div class="info-div-name"> Unit Credit Value </div>
                    <div class="info-div-value"><span>{{ $unit->unit_credit_value }}</span></div>
                </div>
                <div class="info-div-row">
                    <div class="info-div-name"> Unit Learning Outcome </div>
                    <div class="info-div-value"><span>{!! nl2br($unit->learning_outcomes) !!}</span></div>
                </div>
                <div class="info-div-row">
                    <div class="info-div-name"> Performance Criteria </div>
                    <div class="info-div-value">
                        @forelse ($unit->pcs as $pc)
                        <div class="profile-user-info profile-user-info-striped">
                            <div class="profile-info-row">
                                <div class="profile-info-name"> PC Reference </div>
                                <div class="profile-info-value"><span>{{ $pc->reference }}</span></div>
                            </div>
                            <div class="profile-info-row">
                                <div class="profile-info-name"> PC Category </div>
                                <div class="profile-info-value"><span>{{ $pc->category }}</span></div>
                            </div>
                            <div class="profile-info-row">
                                <div class="profile-info-name"> PC Title </div>
                                <div class="profile-info-value"><span>{!! nl2br($pc->title) !!}</span></div>
                            </div>
                            <div class="profile-info-row">
                                <div class="profile-info-name"> PC Req. Evidences </div>
                                <div class="profile-info-value"><span>{{ $pc->min_req_evidences }}</span></div>
                            </div>
                            @if(trim($pc->description) != '')
                            <div class="profile-info-row">
                                <div class="profile-info-name"> PC Description </div>
                                <div class="profile-info-value"><span>{!! nl2br($pc->description) !!}</span></div>
                            </div>
                            @endif
                        </div>
                        @empty
                            <i class="fa fa-warning" style="color: red;"></i> <i class="text-red">No PCs have been created for this unit.</i>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>
