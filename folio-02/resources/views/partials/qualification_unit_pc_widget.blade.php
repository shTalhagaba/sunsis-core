<div class="widget-box">
    <div class="widget-header">
        <span class="widget-title">
            Unit References: <strong>[{{ $unit->unit_owner_ref }}, {{ $unit->unique_ref_number }}]</strong> | 
            Criteria Reference: <strong>[{{ $pc->reference }}]</strong>
        </span>
    </div>
    <div class="widget-body">
        @if (isset($withButtonsToolbar) && $withButtonsToolbar)
        <div class="widget-toolbox padding-8 clearfix">
            @php
                $typeOfPc = basename(get_class($pc));
                $editPcUrl = '';
                $deletePcUrl = '';
                $hiddenElements = '';
                if ($typeOfPc == 'QualificationUnitPC') {
                    $editPcUrl = route('qualifications.units.pcs.edit', [
                        $qualification,
                        $unit,
                        $pc,
                    ]);
                    $deletePcUrl = route('qualifications.units.pcs.destroy', [
                        $qualification,
                        $unit,
                        $pc,
                    ]);
                    $hiddenElements = Form::hidden(
                        'qualification_id',
                        $qualification->id,
                    );
                    $hiddenElements .= Form::hidden('unit_id', $unit->id);
                    $hiddenElements .= Form::hidden('pc_id', $pc->id);
                } elseif ($typeOfPc == 'ProgrammeQualificationUnitPC') {
                    $editPcUrl = route(
                        'programmes.qualifications.units.pcs.edit',
                        [$programme, $qualification, $unit, $pc],
                    );
                    $deletePcUrl = route(
                        'programmes.qualifications.units.pcs.destroy',
                        [$programme, $qualification, $unit, $pc],
                    );
                    $hiddenElements = Form::hidden('programme_id', $programme->id);
                    $hiddenElements .= Form::hidden(
                        'qualification_id',
                        $qualification->id,
                    );
                    $hiddenElements .= Form::hidden('unit_id', $unit->id);
                    $hiddenElements .= Form::hidden('pc_id', $pc->id);
                }
            @endphp
            {{-- <button class="btn btn-primary btn-xs btn-default btn-round"
                onclick="window.location.href='{{ $editPcUrl }}'">
                <i class="ace-icon fa fa-edit bigger-120"></i>
                <span class="bigger-110">Edit Criteria</span>
            </button> --}}
            {!! Form::open([
                'method' => 'DELETE',
                'url' => $deletePcUrl,
                'style' => 'display: inline;',
                'class' => 'form-inline frmDeleteUnitPC',
            ]) !!}
            {!! $hiddenElements !!}
            {!! Form::button('<i class="ace-icon fa fa-trash bigger-120"></i> Delete Criteria', [
                'class' => 'btn btn-danger btn-xs pull-right btn-round btnDeleteUnitPC',
                'data-rel-id' => $pc->id,
                'type' => 'submit',
                'style' => 'display: inline',
            ]) !!}
            {!! Form::close() !!}
        </div>
        @endif
    </div>
    <div class="widget-main">
        <div class="profile-user-info profile-user-info-striped">
            <div class="profile-info-row">
                <div class="profile-info-name"> Title </div>
                <div class="profile-info-value"><span>{!! nl2br(e($pc->title)) !!}</span></div>
            </div>
            <div class="profile-info-row">
                <div class="profile-info-name"> Category </div>
                <div class="profile-info-value"><span>{{ $pc->category }}</span></div>
            </div>
            <div class="profile-info-row">
                <div class="profile-info-name"> Minimum Req. Evidences </div>
                <div class="profile-info-value"><span>{{ $pc->min_req_evidences }}</span></div>
            </div>
            @if (trim($pc->description) != '')
                <div class="profile-info-row">
                    <div class="profile-info-name"> PC Description </div>
                    <div class="profile-info-value"><span>{!! nl2br(e($pc->description)) !!}</span></div>
                </div>
            @endif
        </div>
    </div>
</div>