<div class="tabbable">
    <ul id="qualificationsTab" class="nav nav-tabs">
        @foreach ($programme->qualifications as $qualification)
        <li class="{{ $loop->first ? 'active' : '' }}">
            <a href="#tabQualification{{ $qualification->id }}" data-toggle="tab">{{ $qualification->qan }}</a>
        </li>
        @endforeach
    </ul>

    <div class="tab-content">
        @foreach ($programme->qualifications as $qualification)
        <div class="tab-pane {{ $loop->first ? 'in active' : '' }}" id="tabQualification{{ $qualification->id }}">
            <a title="Export this programme qualification to Excel" href="{{ route('programmes.qualifications.single.export', [$programme, $qualification]) }}" >
                <i class="fa fa-file-excel-o pull-right fa-2x"></i>
            </a> &nbsp;
            <h4>{{ $qualification->title }}</h4>
            <div class="row">
                <div class="col-sm-6">
                    <div class="info-div info-div-striped">
                        <div class="info-div-row">
                            <div class="info-div-name"> Min. GLH </div>
                            <div class="info-div-value"><span>{{ $qualification->min_glh }}</span></div>
                        </div>
                        <div class="info-div-row">
                            <div class="info-div-name"> Max. GLH </div>
                            <div class="info-div-value"><span>{{ $qualification->max_glh }}</span></div>
                        </div>
                        <div class="info-div-row">
                            <div class="info-div-name"> GLH </div>
                            <div class="info-div-value"><span>{{ $qualification->glh }}</span></div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    <div class="info-div info-div-striped">
                        <div class="info-div-row">
                            <div class="info-div-name"> Total Credits </div>
                            <div class="info-div-value"><span>{{ $qualification->total_credits }}</span> </div>
                        </div>
                        <div class="info-div-row">
                            <div class="info-div-name"> Assessment Methods </div>
                            <div class="info-div-value"><span>{{ $qualification->assessment_methods }}</span></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="space-6"></div>
            <div class="row">
                <div class="col-sm-12">
                    <div class="widget-box">
                        <div class="widget-header">
                            <h5 class="widget-title">Units</h5> &nbsp;
                            <span data-rel="tooltip" title="Number of total units" class="badge badge-default">Total Units:
                                {{ $qualification->units->count() }}</span>
                            <span data-rel="tooltip" title="Number of mandatory units" class="badge badge-success">Mandaotry
                                Units:
                                {{ $qualification->mandatoryUnitsCount() }}</span>
                            <span data-rel="tooltip" title="Number of optional units" class="badge badge-info">Optional Units:
                                {{ $qualification->optionalUnitsCount() }}</span>
                            <div class="widget-toolbar">
                                <a href="#">
                                    <i class="ace-icon fa-lg fa fa-chevron-down" title="Expand All" style="cursor: pointer;"
                                    onclick="$('.UnitPanel{{ $qualification->id }}').widget_box('show');">
                                    </i>
                                </a>
                                <a href="#">
                                    <i class="ace-icon fa-lg fa fa-chevron-up" title="Collapse All" style="cursor: pointer;"
                                    onclick="$('.UnitPanel{{ $qualification->id }}').widget_box('hide');">
                                    </i>
                                </a>
                            </div>
                        </div>
                        <div class="widget-body">
                            <div class="widget-toolbox padding-8 clearfix">
                                <button type="button" class="btn btn-primary btn-round btn-bold btn-xs pull-left"
                                    onclick="window.location.href='{{ route('programmes.qualifications.units.create', [$programme, $qualification]) }}'">
                                    <i class="ace-icon fa fa-plus bigger-120"></i>
                                    <span class="bigger-110">Add Single Unit</span>
                                </button>
                            </div>
                            <div class="widget-main">
                                @foreach ($qualification->units as $unit)
                                    @include('partials.qualification_unit_with_pcs', [
                                        'unit' => $unit,
                                        'withButtonsToolbar' => true,
                                        'unitEditUrl' => route('programmes.qualifications.units.edit', [$programme, $qualification, $unit]),
                                        'unitDeleteUrl' => route('programmes.qualifications.units.destroy', [$programme, $qualification, $unit]),
                                        'extraUnitPanelClasses' => 'collapsed UnitPanel' . $qualification->id,
                                        'panelShowHide' => true,
                                    ])
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
