@extends('layouts.master')

@section('title', 'Add Qualification Unit')

@section('breadcrumbs')
    {{ Breadcrumbs::render('qualifications.units.create', $qualification) }}
@endsection

@section('page-content')
    <div class="page-header">
        <h1>Add Qualification Unit</h1>
    </div><!-- /.page-header -->
    <div class="row">
        <div class="col-xs-12">
            <!-- PAGE CONTENT BEGINS -->
            <div class="row">

                <div class="col-sm-12">
                    <button class="btn btn-sm btn-white btn-default btn-round" type="button"
                        onclick="window.location.href='{{ route('qualifications.show', $qualification) }}'">
                        <i class="ace-icon fa fa-arrow-left bigger-110"></i> Cancel
                    </button>
                    <div class="hr hr-12 hr-dotted"></div>
                </div>
            </div>

            <div class="row">
                <div class="col-sm-12">
                    <div class="widget-box transparent collapsed">
                        <div class="widget-header">
                            <h5 class="widget-title">Qualification</h5> &nbsp;
                            <div class="widget-toolbar">
                                <a data-action="collapse" href="#"><i class="ace-icon fa fa-chevron-down"></i></a>
                            </div>
                        </div>
                        <div class="widget-body">
                            <div class="widget-main">
                                <div class="profile-user-info profile-user-info-striped">
                                    <div class="profile-info-row">
                                        <div class="profile-info-name"> Level </div>
                                        <div class="profile-info-value"><span><span
                                                    class="label label-md label-info arrowed-in-right arrowed-in">{{ $qualification->level }}</span></span>
                                        </div>
                                    </div>
                                    <div class="profile-info-row">
                                        <div class="profile-info-name"> Qualification Number & Title</div>
                                        <div class="profile-info-value"><span>{{ $qualification->qan }}
                                                {{ $qualification->title }}</span></div>
                                    </div>
                                    <div class="profile-info-row">
                                        <div class="profile-info-name"> Awarding Org. </div>
                                        <div class="profile-info-value"><span>{{ $qualification->owner_org_name }}</span>
                                        </div>
                                    </div>
                                    <div class="profile-info-row">
                                        <div class="profile-info-name"> Learning Aim type </div>
                                        <div class="profile-info-value"><span>{{ $qualification->type }}</span></div>
                                    </div>
                                    <div class="profile-info-row">
                                        <div class="profile-info-name"> SSA </div>
                                        <div class="profile-info-value"><span>{{ $qualification->ssa }}</span></div>
                                    </div>
                                    <div class="profile-info-row">
                                        <div class="profile-info-name"> Status </div>
                                        <div class="profile-info-value"><span>{{ $qualification->status }}</span></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="space-8"></div>
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
                                @foreach ($qualification->units as $existingUnit)
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
                'frmAddEditUnitUrl' => route('qualifications.units.store', [$qualification]),
            ])

            <!-- PAGE CONTENT ENDS -->
        </div><!-- /.col -->
    </div><!-- /.row -->
@endsection
