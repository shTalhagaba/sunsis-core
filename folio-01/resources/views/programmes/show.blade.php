@extends('layouts.master')

@section('title', 'Programme')

@section('page-plugin-styles')
<link rel="stylesheet" href="{{ asset('assets/css/toastr.min.css') }}" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-treeview/1.2.0/bootstrap-treeview.min.css" />
@endsection

@section('breadcrumbs')
{{ Breadcrumbs::render('programmes.show', $programme) }}
@endsection

@section('page-content')
<div class="page-header"><h1>{{ $programme->title }}</h1></div><!-- /.page-header -->
<div class="row">
    <div class="col-xs-12">
        <div class="well well-sm">
            <button class="btn btn-sm btn-white btn-primary btn-round" type="button" onclick="window.location.href='{{ route('programmes.index') }}'">
                <i class="ace-icon fa fa-arrow-left bigger-110"></i> Back
            </button>
            <button class="btn btn-sm btn-white btn-primary btn-bold btn-round" type="button" onclick="window.location.href='{{ route('programmes.edit', $programme) }}'">
                <i class="ace-icon fa fa-edit bigger-120 blue"></i> Edit Programme
            </button>
        </div>
        @include('partials.session_message')
        <div class="row">
            <div class="col-xs-12">
                <div class="row">
                    <div class="col-xs-6">
                        <div class="info-div info-div-striped">
                            <div class="info-div-row">
                                <div class="info-div-name"> Title </div><div class="info-div-value"><span>{{ $programme->title }}</span></div>
                            </div>
                            <div class="info-div-row">
                                <div class="info-div-name"> Dates </div>
                                <div class="info-div-value">
                                    <span>
                                        {{ \Carbon\Carbon::parse($programme->start_date)->format('d/m/Y') }} -
                                        {{ \Carbon\Carbon::parse($programme->end_date)->format('d/m/Y') }}
                                    </span>
                                </div>
                            </div>
                            <div class="info-div-row">
                                <div class="info-div-name"> Programme Type </div>
                                <div class="info-div-value"><span>{{ \App\Models\Programmes\Programme::getProgrammeTypeDescription($programme->programme_type) }}</span></div>
                            </div>
                            <div class="info-div-row">
                                <div class="info-div-name"> Status </div>
                                <div class="info-div-value">
                                    <span>
                                        <label class="label label-{{ $programme->status == 1 ? 'success' : 'danger' }}">{{ $programme->status == 1 ? 'Active' : 'Not Active' }}</label>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-6">
                        <div class="info-div info-div-striped">
                            <div class="info-div-row">
                                <div class="info-div-name"> Comments </div><div class="info-div-value"><span>{{ $programme->comments }}</span></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="space-6"></div>
        <div class="row">
            <div class="col-sm-12">
                <div class="tabbable">
                    <ul id="programmeTab" class="nav nav-tabs tab-color-blue background-blue padding-18 tab-size-bigger">
                        <li class="active">
                            <a href="#tabQualifications" data-toggle="tab">Qualifications <span class="badge badge-info">{{ count($programme->qualifications) }}</span></a>
                        </li>
                        <li>
                            <a href="#tabTrainingPlans" data-toggle="tab">Training Plans <span class="badge badge-info">{{ count($programme->training_plans) }}</span></a>
                        </li>
                        <li>
                            <a href="#tabLearners" data-toggle="tab">Training Records <span class="badge badge-info">{{ count($programme->training_records) }}</span></a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane in active" id="tabQualifications">
                            <div class="row">
                                <div class="col-sm-12">
                                    <span class="btn btn-primary btn-sm btn-round" onclick="window.location.href='{{ route('programmes.qualifications.add', $programme) }}'">
                                        <i class="fa fa-plus"></i><i class="fa fa-graduation-cap"></i> Add Qualification
                                    </span>
                                </div>
                                <div class="space-6"></div>
                                <div class="col-sm-12">
                                    <div class="space-6"></div>
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
                                                                <span data-rel="tooltip" title="Number of total units" class="badge badge-default">T: {{ $qualification->units->count() }}</span>
                                                                <span data-rel="tooltip" title="Number of mandatory units" class="badge badge-success">M: {{ $qualification->units()->where('unit_group', 1)->count() }}</span>
                                                                <span data-rel="tooltip" title="Number of optional units" class="badge badge-info">O: {{ $qualification->units()->where('unit_group', 2)->count() }}</span>
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
                                                                <div class="widget-main">
                                                                    @foreach($qualification->units AS $unit)
                                                                    @include('programmes.partials.unit_with_pcs', ['with_buttons_toolbar' => true])
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

                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="tabTrainingPlans">
                            <div class="row">
                                <div class="col-xs-12">
                                    <span class="btn btn-primary btn-sm btn-round" onclick="window.location.href='{{ route('programmes.training_plans.edit', $programme) }}'">
                                        <i class="fa fa-edit"></i> {{ is_null($programme->training_plans) ? 'Create' : 'Edit' }} Training Plans Template
                                    </span>
                                    <p></p>
                                </div>
                            </div>
                            <div class="row">
                                @foreach($programme->training_plans AS $plan)
                                <div class="col-xs-4">
                                    @include('programmes.partials.training_plan_box', ['_plan' => $plan, 'edit_button' => false])
                                </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="tab-pane" id="tabLearners">
                            <div class="space6">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        @forelse($programme->training_records AS $training_record)
                                        <tr>
                                            <td>
                                                <h5 class="widget-title">
                                                    {{ $training_record->student->surname  }}, {{ $training_record->student->firstnames  }} |
                                                    <small> {{ $training_record->start_date }} - {{ $training_record->planned_end_date }}</small> |
                                                    <small>
                                                        @if($training_record->getOriginal('status_code') == App\Models\Training\TrainingRecord::STATUS_CONTINUING)
                                                        <span class="label label-md label-info arrowed-in arrowed-in-right">{{ $training_record->status_code }}</span>
                                                        @elseif($training_record->getOriginal('status_code') == App\Models\Training\TrainingRecord::STATUS_COMPLETED)
                                                        <span class="label label-md label-success arrowed-in arrowed-in-right">{{ $training_record->status_code }}</span>
                                                        @elseif($training_record->getOriginal('status_code') == App\Models\Training\TrainingRecord::STATUS_WITHDRAWN)
                                                        <span class="label label-md label-danger arrowed-in arrowed-in-right">{{ $training_record->status_code }}</span>
                                                        @elseif($training_record->getOriginal('status_code') == App\Models\Training\TrainingRecord::STATUS_TEMP_WITHDRAWN)
                                                        <span class="label label-md label-warning arrowed-in arrowed-in-right">{{ $training_record->status_code }}</span>
                                                        @endif
                                                    </small>
                                                </h5>
                                                <i class="ace-icon fa fa-envelope bigger-120 pink"></i> {{ $training_record->student->primary_email }}
                                                <br><strong>Employer:</strong>
                                                @php $loc = $training_record->location @endphp
                                                {{ $training_record->employer->legal_name }} |
                                                {!! $loc->postcode != '' ? '<i class="fa fa-map-marker light-orange bigger-110"></i>
                                                <span>' . $loc->postcode . '</span><br>' : '' !!}
                                            </td>
                                            <td>
                                                @foreach($training_record->portfolios AS $portfolio)
                                                {{ $portfolio->qan }} {{ $portfolio->title }} |
                                                <label for="" class="label label-success">{{ $training_record->signedOffPercentage() }}%</label> <br>
                                                @endforeach
                                            </td>
                                        </tr>
                                        @empty
                                        <tr><td colspan="2"><span class="alert alert-info"><h4>No training records found.</h4></span></td></tr>
                                        @endforelse
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


@section('page-plugin-scripts')
<script src="{{ asset('assets/js/toastr.min.js') }}"></script>
<script type="text/javascript" charset="utf8" src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-treeview/1.2.0/bootstrap-treeview.min.js"></script>



@endsection

@section('page-inline-scripts')

<script type="text/javascript">
$('[data-rel=tooltip]').tooltip();
$('[data-rel=popover]').popover({
    html:true,
    placement:"auto"
});

$.ajaxSetup({
	headers: {
		'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	}
});


$(document).ready(function(){

/*
    $("div[id^=treeview]").each(function(){
        var divId = this.id.replace('treeview', '');
        var url = "/render_programme_qualification_tree/"+divId;
        $.ajax({
            url: url,
            method: "GET",
            dataType: "json",
            async: false,
            success: function(data)
            {
                $('#treeview'+divId).treeview({
                    data: data,
                    selectedBackColor: '#9966FF'
                });
            }
        });

    });
*/


});
</script>

@endsection

