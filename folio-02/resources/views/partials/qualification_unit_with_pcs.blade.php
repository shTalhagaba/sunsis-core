<span id="{{ $unit->unit_owner_ref }}" ></span>
<div class="widget-box {{ $unit->getOriginal('unit_group') == 1 ? 'widget-color-green' : 'widget-color-blue' }} {{ $extraUnitPanelClasses ?? '' }} ">
    <div class="widget-header">
        <span class="widget-title"><strong>{{ $unit->unit_owner_ref }}, {{ $unit->unique_ref_number }}</strong></span>
        <div class="widget-toolbar">
            @if ($panelShowHide)
            <a href="#" data-action="collapse">
                <i class="ace-icon fa fa-chevron-down"></i>
            </a>
            @endif
        </div>
    </div>
    <div class="widget-body">
        @if (isset($withButtonsToolbar) && $withButtonsToolbar)
            <div class="widget-toolbox padding-8 clearfix">
                <button class="btn btn-primary btn-xs btn-default btn-round"
                    onclick="window.location.href='{{ $unitEditUrl }}'">
                    <i class="ace-icon fa fa-edit bigger-120"></i>
                    <span class="bigger-110">Edit Unit</span>
                </button>
                {!! Form::open([
                    'method' => 'DELETE',
                    'url' => $unitDeleteUrl,
                    'style' => 'display: inline;',
                    'class' => 'form-inline frmDeleteUnit',
                ]) !!}
                {!! Form::button('<i class="ace-icon fa fa-trash bigger-120"></i> Delete Unit', [
                    'class' => 'btn btn-danger btn-xs pull-right btn-round btnDeleteUnit',
                    'data-rel-id' => $unit->id,
                    'type' => 'submit',
                    'style' => 'display: inline',
                ]) !!}
                {!! Form::close() !!}
            </div>
        @endif
        <div class="widget-main">
            <div class="info-div info-div-striped">
                <div class="info-div-row">
                    <div class="info-div-name"> Unit Title </div>
                    <div class="info-div-value"><span>{!! nl2br(e($unit->title)) !!}</span></div>
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
                    <div class="info-div-value"><span>{!! nl2br(e($unit->learning_outcomes)) !!}</span></div>
                </div>
                <div class="info-div-row">
                    <div class="info-div-name"> PCs Count </div>
                    <div class="info-div-value"><span>{{ count($unit->pcs) }}</span></div>
                </div>
                <div class="info-div-row">
                    <div class="info-div-name"> Performance Criteria </div>
                    <div class="info-div-value">
                        @forelse ($unit->pcs as $pc)
                            @include('partials.qualification_unit_pc_widget', [
                                'withButtonsToolbar' => isset($withButtonsToolbar) ? $withButtonsToolbar : false,
                                'pc' => $pc,
                            ])
                        @empty
                            <i class="fa fa-warning" style="color: red;"></i> <i class="text-red">No PCs have been
                                created for this unit.</i>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

