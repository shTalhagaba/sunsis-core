@extends('layouts.master')

@section('title', 'Add Unit in Programme Qualification')

@section('page-content')
    <div class="page-header">
        <h1>Add Unit in Programme Qualification</h1>
    </div><!-- /.page-header -->
    <div class="row">
        <div class="col-xs-12">
            <!-- PAGE CONTENT BEGINS -->
            <div class="row">

                <div class="col-sm-12">
                    <button class="btn btn-sm btn-white btn-default btn-round" type="button"
                        onclick="window.location.href='{{ route('programmes.show', $programme) }}'">
                        <i class="ace-icon fa fa-arrow-left bigger-110"></i> Cancel
                    </button>
                    <div class="hr hr-12 hr-dotted"></div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <div class="widget-box transparent">
                        <div class="widget-header"><h5 class="widget-title">Programme</h5></div>
                        <div class="widget-body">
                            <div class="widget-main">
                                <div class="info-div info-div-striped">
                                    <div class="info-div-row">
                                        <div class="info-div-name"> Title </div>
                                        <div class="info-div-value"><span>{{ $programme->title }}</span></div>
                                    </div>
                                    <div class="info-div-row">
                                        <div class="info-div-name"> Qualification </div>
                                        <div class="info-div-value"><span>{{ $qualification->qan }} - {{ $qualification->title }}</span></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <div class="widget-box transparent collapsed">
                        <div class="widget-header">
                            <h5 class="widget-title">Existing Units</h5> &nbsp;
                            <span data-rel="tooltip" title="Number of total units" class="badge badge-default">Total Units:
                                {{ $qualification->units->count() }}</span>
                            <span data-rel="tooltip" title="Number of mandatory units" class="badge badge-success">Mandaotry
                                Units:
                                {{ $qualification->mandatoryUnitsCount() }}</span>
                            <span data-rel="tooltip" title="Number of optional units" class="badge badge-info">Optional Units:
                                {{ $qualification->optionalUnitsCount() }}</span>
                            <div class="widget-toolbar">
                                <a data-action="collapse" href="#"><i class="ace-icon fa fa-chevron-down"></i></a>
                            </div>
                        </div>
                        <div class="widget-body">
                            <div class="widget-main">
                                @foreach($qualification->units AS $existingUnit)
                                <small>@include('partials.qualification_unit_with_pcs', [
                                    'withButtonsToolbar' => false,
                                    'qualification' => $qualification,
                                    'unit' => $existingUnit,
                                    'panelShowHide' => false,
                                ])</small>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="space-8"></div>
                </div>
            </div>

            @include('partials.session_message')

            @include('partials.session_error')

            @include('partials.create_edit_qualification_unit', [
                'frmAddEditUnitUrl' => route('programmes.qualifications.units.store', [$programme, $qualification])
            ])

            <!-- PAGE CONTENT ENDS -->
        </div><!-- /.col -->
    </div><!-- /.row -->
@endsection
