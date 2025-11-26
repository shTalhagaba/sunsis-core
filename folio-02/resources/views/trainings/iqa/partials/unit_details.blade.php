<div class="widget-box transparent">
    <div class="widget-header">
        <h5 class="widget-title">Unit Details</h5>
    </div>
    <div class="widget-body">
        <div class="widget-main">
            <div class="info-div info-div-striped">
                <div class="info-div-row">
                    <div class="info-div-name"> Unit References </div>
                    <div class="info-div-value"><span>{{ $unit->unit_owner_ref }},
                            {{ $unit->unique_ref_number }}</span></div>
                </div>
                <div class="info-div-row">
                    <div class="info-div-name"> Unit Title </div>
                    <div class="info-div-value"><span>{!! nl2br($unit->title) !!}</span></div>
                </div>
                <div class="info-div-row">
                    <div class="info-div-name"> Portfolio Title </div>
                    <div class="info-div-value"><span>{{ $unit->portfolio->title }}</span></div>
                </div>
                <div class="info-div-row">
                    <div class="info-div-name"> Unit Group </div>
                    <div class="info-div-value">
                        <span>{{ \App\Models\LookupManager::getQualificationUnitGroups($unit->unit_group) }}</span>
                    </div>
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
            </div>
        </div>
    </div>
</div>