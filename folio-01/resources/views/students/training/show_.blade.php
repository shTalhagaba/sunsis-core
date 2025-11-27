@extends('layouts.master')
@section('title', 'Training Record')
@section('page-plugin-styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">

<style>
.modal {
    display:    none;
    position:   fixed;
    z-index:    1000;
    top:        0;
    left:       0;
    height:     100%;
    width:      100%;
    background: rgba( 255, 255, 255, .8 )
                url('{{ asset('images/ajax-loader.gif') }}')
                50% 50%
                no-repeat;
}

body.loading .modal {
    overflow: hidden;
}

body.loading .modal {
    display: block;
}

.popover{
        max-width:600px;
}
</style>
@endsection

@section('breadcrumbs')
@if(in_array(\Auth::user()->getOriginal('user_type'), [\App\Models\User::TYPE_ADMIN]))
{{ Breadcrumbs::render('students.training.show', $student, $training_record) }}
@endif
@endsection

@section('page-content')
@include('students.training.partials.tr_header')
<!-- /.page-header -->
<div class="row">
    <div class="col-xs-12">
        <!-- PAGE CONTENT BEGINS -->
        <div class="row">
            <div class="col-xs-12">
                <div class="well well-sm">
		    @if(!\Auth::user()->isStudent())
                    <button class="btn btn-sm btn-white btn-primary btn-round" type="button"
                    onclick="window.location.href='{{ route('students.training.index') }}'">
                        <i class="ace-icon fa fa-arrow-left bigger-110"></i> Back
                    </button>
		    @endif
                    @can('edit-training-record')
                    <button class="btn btn-sm btn-white btn-primary btn-bold btn-round" type="button" onclick="window.location.href='{{ route('students.training.edit', [$student, $training_record]) }}'">
                        <i class="ace-icon fa fa-edit bigger-120 blue"></i> Edit
                    </button>
                    @endcan
                    @can('delete-training-record')
                    {!! Form::open(['method' => 'DELETE', 'url' => route('students.training.destroy', [$student, $training_record]), 'style' => 'display: inline;', 'class' => 'form-inline', 'id' => 'frmDeleteTR' ]) !!}
                        {!! Form::button('<i class="ace-icon fa fa-trash-o bigger-120 orange"></i> Delete', ['class' => 'btn btn-sm btn-white btn-danger btn-bold btn-round btnDelTR', 'type' => 'submit', 'style' => 'display: inline']) !!}
                    {!! Form::close() !!}
                    @endcan
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-1 col-xs-12">
                <div style="">
                    <img class="img-responsive img-thumbnail" alt="{{ $student->firstnames}}'s Avatar" id="avatar2" src="{{ asset($student->avatar_url) }}" />
                </div>
                @if ($student->isOnline())
                <label class="label label-success">Online</label>
                @else
                <label class="label label-default">Offline</label>
                @endif
            </div>
            <div class="col-sm-3 col-xs-12">
                <strong class="lead">{{ $student->full_name }}</strong>
                <div class="info-div info-div-striped">
                    <div class="info-div-row">
                        <div class="info-div-name">Primary <i class="ace-icon fa fa-envelope blue"></i></div>
                        <div class="info-div-value">{{ $student->primary_email }}</div>
                    </div>
                    <div class="info-div-row">
                        <div class="info-div-name">Home <i class="ace-icon fa fa-mobile fa-lg blue"></i></div>
                        <div class="info-div-value">{{ $homeAddress->mobile }}</div>
                    </div>
                </div>
            </div>
            <div class="col-sm-4 col-xs-12">
                <div class="info-div info-div-striped">
                    <div class="info-div-row">
                        <div class="info-div-name"> Start Date</div>
                        <div class="info-div-value"><span>{{ $training_record->start_date }}</span></div>
                    </div>
                    <div class="info-div-row">
                        <div class="info-div-name"> Planned End Date</div>
                        <div class="info-div-value">
                            {{ $training_record->planned_end_date }}
                            @if (is_null($training_record->actual_end_date))
                            <small>({{ \Carbon\Carbon::parse($training_record->getOriginal('planned_end_date'))->diffForHumans() }})</small>
                            @endif
                        </div>
                    </div>
                    @if($training_record->actual_end_date != '')
                    <div class="info-div-row">
                        <div class="info-div-name"> Completion Date</div>
                        <div class="info-div-value">
                            {{ \Carbon\Carbon::parse($training_record->actual_end_date)->format('d/m/Y') }}
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
                        </div>
                    </div>
                    @endif
                    <div class="info-div-row">
                        <div class="info-div-name"> Learner Reference</div>
                        <div class="info-div-value">{{ $training_record->learner_ref }}</div>
                    </div>
                    <div class="info-div-row">
                        <div class="info-div-name"> ULN</div>
                        <div class="info-div-value">{{ $training_record->student->uln }}</div>
                    </div>
                </div>
            </div>
            <div class="col-sm-4 col-xs-12 text-center">
                <div style="height: 210px;" id="progressChart"></div>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12">
                <div class="widget-box transparent collapsed" id="widget-box-{{ $training_record->id }}">
                    <div class="widget-header">
                        <h5 class="widget-title smaller">{{ $training_record->system_ref }}</h5>
                        <div class="widget-toolbar">
                            <a href="#" data-action="collapse"><i class="ace-icon fa fa-chevron-down"></i></a>
                        </div>
                    </div>
                    <div class="widget-body">
                        <div class="widget-main">
                            <div class="row">
                                <div class="col-sm-4">
                                    <div class="widget-box transparent">
                                        <div class="widget-header"><h4 class="smaller">Addresses</h4></div>
                                        <div class="widget-body">
                                            <div class="widget-main">
                                                <div class="info-div info-div-striped">
                                                    <div class="info-div-row">
                                                        <div class="info-div-name"> Work</div>
                                                        <div class="info-div-value">
                                                            {!! $workAddress->address_line_1 != '' ? '<span>' .
                                                                $workAddress->address_line_1 . '</span><br>' : '' !!}
                                                            {!! $workAddress->address_line_2 != '' ? '<span>' .
                                                                $workAddress->address_line_2 . '</span><br>' : '' !!}
                                                            {!! $workAddress->address_line_3 != '' ? '<span>' .
                                                                $workAddress->address_line_3 . '</span><br>' : '' !!}
                                                            {!! $workAddress->address_line_4 != '' ? '<span>' .
                                                                $workAddress->address_line_4 . '</span><br>' : '' !!}
                                                            {!! $workAddress->postcode != '' ? '<i
                                                                class="fa fa-map-marker light-orange bigger-110"></i> <span>' .
                                                                $workAddress->postcode . '</span><br>' : '' !!}
                                                            {!! $workAddress->telephone != '' ? '<i
                                                                class="fa fa-phone light-orange bigger-110"></i> <span>' .
                                                                $workAddress->telephone . '</span><br>' : '' !!}
                                                            {!! $workAddress->mobile != '' ? '<i
                                                                class="fa fa-mobile light-orange bigger-110"></i> <span>' .
                                                                $workAddress->mobile . '</span><br>' : '' !!}
                                                        </div>
                                                    </div>
                                                    <div class="info-div-row">
                                                        <div class="info-div-name"> Home</div>
                                                        <div class="info-div-value">
                                                            {!! $homeAddress->address_line_1 != '' ? '<span>' .
                                                                $homeAddress->address_line_1 . '</span><br>' : '' !!}
                                                            {!! $homeAddress->address_line_2 != '' ? '<span>' .
                                                                $homeAddress->address_line_2 . '</span><br>' : '' !!}
                                                            {!! $homeAddress->address_line_3 != '' ? '<span>' .
                                                                $homeAddress->address_line_3 . '</span><br>' : '' !!}
                                                            {!! $homeAddress->address_line_4 != '' ? '<span>' .
                                                                $homeAddress->address_line_4 . '</span><br>' : '' !!}
                                                            {!! $homeAddress->postcode != '' ? '<i
                                                                class="fa fa-map-marker light-orange bigger-110"></i> <span>' .
                                                                $homeAddress->postcode . '</span><br>' : '' !!}
                                                            {!! $homeAddress->telephone != '' ? '<i
                                                                class="fa fa-phone light-orange bigger-110"></i> <span>' .
                                                                $homeAddress->telephone . '</span><br>' : '' !!}
                                                            {!! $homeAddress->mobile != '' ? '<i
                                                                class="fa fa-mobile light-orange bigger-110"></i> <span>' .
                                                                $homeAddress->mobile . '</span><br>' : '' !!}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="widget-box transparent">
                                        <div class="widget-header"><h4 class="smaller">Related Users</h4></div>
                                        <div class="widget-body">
                                            <div class="widget-main">
                                                <div class="info-div info-div-striped">
                                                    <div class="info-div-row">
                                                        <div class="info-div-name"> Primary Assessor</div>
                                                        <div class="info-div-value">
                                                            <span class="profile-picture">
                                                                <img class="avatar img-responsive" width="50px;" height="50px;" src="{{ $avatar_assessor }}" alt="{{ $training_record->primaryAssessor->firstnames }}" />
                                                            </span><br>
                                                            <span>{{ $training_record->primaryAssessor->full_name }}</span><br>
                                                            @if ($training_record->primaryAssessor->isOnline())
                                                            <label class="pull-right label label-success">Online</label>
                                                            @else
                                                            <label class="pull-right label label-default">Offline</label>
                                                            @endif
                                                            <span class="small"><i class="ace-icon fa fa-envelope blue"></i> {{ $training_record->primaryAssessor->primary_email }}</span><br>
                                                            @if(isset($training_record->primaryAssessor->workAddress()->mobile) && $training_record->primaryAssessor->workAddress()->mobile != '')
                                                            <span class="small"><i class="ace-icon fa fa-mobile blue"></i> {{ $training_record->primaryAssessor->workAddress()->mobile }}</span><br>
                                                            @endif
                                                            @if(isset($training_record->primaryAssessor->workAddress()->telephone) && $training_record->primaryAssessor->workAddress()->telephone != '')
                                                            <span class="small"><i class="ace-icon fa fa-phone blue"></i> {{ $training_record->primaryAssessor->workAddress()->telephone }}</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    @if(!is_null($training_record->secondaryAssessor))
                                                    <div class="info-div-row">
                                                        <div class="info-div-name"> Secondary Assessor</div>
                                                        <div class="info-div-value">
                                                            <span class="profile-picture">
                                                                <img class="avatar img-responsive" width="50px;" height="50px;" src="{{ $avatar_assessor_sec }}" alt="{{ $training_record->secondaryAssessor->firstnames }}" />
                                                            </span><br>
                                                            <span>{{ $training_record->secondaryAssessor->full_name }}</span><br>
                                                            @if ($training_record->secondaryAssessor->isOnline())
                                                            <label class="pull-right label label-success">Online</label>
                                                            @else
                                                            <label class="pull-right label label-default">Offline</label>
                                                            @endif
                                                            <span class="small"><i class="ace-icon fa fa-envelope blue"></i> {{ $training_record->secondaryAssessor->primary_email }}</span><br>
                                                            @if(isset($training_record->secondaryAssessor->workAddress()->mobile) && $training_record->secondaryAssessor->workAddress()->mobile != '')
                                                            <span class="small"><i class="ace-icon fa fa-mobile blue"></i> {{ $training_record->secondaryAssessor->workAddress()->mobile }}</span><br>
                                                            @endif
                                                            @if(isset($training_record->secondaryAssessor->workAddress()->telephone) && $training_record->secondaryAssessor->workAddress()->telephone != '')
                                                            <span class="small"><i class="ace-icon fa fa-phone blue"></i> {{ $training_record->secondaryAssessor->workAddress()->telephone }}</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    @endif
                                                    @if(!is_null($training_record->verifierUser) && !\Auth::user()->isStudent())
                                                    <div class="info-div-row">
                                                        <div class="info-div-name"> Verifier</div>
                                                        <div class="info-div-value">
                                                            <span class="profile-picture">
                                                                <img class="avatar img-responsive" width="50px;" height="50px;" src="{{ $avatar_verifier }}" alt="{{ $training_record->verifierUser->firstnames }}" />
                                                            </span><br>
                                                            <span>{{ $training_record->verifierUser->full_name }}</span><br>
                                                            @if ($training_record->verifierUser->isOnline())
                                                            <label class="pull-right label label-success">Online</label>
                                                            @else
                                                            <label class="pull-right label label-default">Offline</label>
                                                            @endif
                                                            <span class="small"><i class="ace-icon fa fa-envelope blue"></i> {{ $training_record->verifierUser->primary_email }}</span><br>
                                                            @if(isset($training_record->verifierUser->workAddress()->mobile) && $training_record->verifierUser->workAddress()->mobile != '')
                                                            <span class="small"><i class="ace-icon fa fa-mobile blue"></i> {{ $training_record->verifierUser->workAddress()->mobile }}</span><br>
                                                            @endif
                                                            @if(isset($training_record->verifierUser->workAddress()->telephone) && $training_record->verifierUser->workAddress()->telephone != '')
                                                            <span class="small"><i class="ace-icon fa fa-phone blue"></i> {{ $training_record->verifierUser->workAddress()->telephone }}</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="widget-box transparent">
                                        <div class="widget-header"><h4 class="smaller">Related Organisations</h4></div>
                                        <div class="widget-body">
                                            <div class="widget-main">
                                                <div class="info-div info-div-striped">
                                                    <div class="info-div-row">
                                                        <div class="info-div-name"> Employer</div>
                                                        <div class="info-div-value">
                                                            <strong>{{ $training_record->employer->legal_name }}</strong><br>
                                                            {!! $training_record->location->address_line_1 != '' ? '<span>' . $training_record->location->address_line_1 . '</span>' : '' !!}<br>
                                                            {!! $training_record->location->address_line_2 != '' ? '<span>' . $training_record->location->address_line_2 . '</span>' : '' !!}<br>
                                                            {!! $training_record->location->address_line_3 != '' ? '<span>' . $training_record->location->address_line_3 . '</span>' : '' !!}<br>
                                                            {!! $training_record->location->address_line_4 != '' ? '<span>' . $training_record->location->address_line_4 . '</span>' : '' !!}<br>
                                                            {!! $training_record->location->postcode != '' ? '<span>' . $training_record->location->postcode . '</span>' : '' !!}<br>
                                                            {!! $training_record->location->telephone != '' ? '<span>' . $training_record->location->telephone . '</span>' : '' !!}<br>
                                                            {!! $training_record->location->mobile != '' ? '<span>' . $training_record->location->mobile . '</span>' : '' !!}<br>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
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
                    <ul id="myTab" class="nav nav-tabs tab-color-blue background-blue padding-18 tab-size-bigger">
                        @foreach($portfolios AS $portfolio)
                        <li class="{{ $loop->first ? 'active' : '' }}">
                            <a href="#tab{{ $portfolio->qan }}" data-toggle="tab" class="linkPortfolioTab">
                                <i class="fa fa-graduation-cap"></i>
                                {{ $portfolio->qan }} <span class="badge badge-info">{{ $portfolio->signedOffUnits() }}/{{ $portfolio->units->count() }}</span>
                            </a>
                        </li>
                        @endforeach
                        <li><a href="#tabEvidences" data-toggle="tab"><i class="fa fa-file-text"></i> Evidences <span class="badge badge-info">{{ $training_record->evidences->count() }}</span></a></li>
                        @can('add-remove-tr-elements')
                        <li><a href="#tabAddRemoveElements" data-toggle="tab">Add/Remove Elements</a></li>
                        @endcan
                        <li><a href="#tabTrainingPlans" data-toggle="tab">Training Plans <span class="badge badge-info">{{ $training_record->training_plans->count() }}</a></li>
                        @if(\Session::get('configuration')['FOLIO_CLIENT_NAME'] == "Demo")
                        <li><a href="#tabOtj" data-toggle="tab">OTJ Hours</a></li>
                        <li><a href="#tabReviews" data-toggle="tab">Reviews</a></li>
                        @endif
                    </ul>
                    <div class="tab-content">
                        @foreach($portfolios AS $portfolio)
                        <div class="tab-pane {{ $loop->first ? 'in active' : '' }}" id="tab{{ $portfolio->qan }}">
                            <div class="row">
                                <div class="col-sm-10">
                                    <h4 class="lighter bolder"><i class="fa fa-graduation-cap"></i> {{ $portfolio->title }}</h4>
                                    @if(!\Auth::user()->isStudent())
                                    @can('signoff-progress')
                                    <span class="btn btn-success btn-sm btn-round" data-rel="tooltip" title="Signoff the progress for this qualification."
                                    onclick="window.location.href='{{ route('students.training.signoffProgress', [$student, $training_record, $portfolio]) }}'">
                                        <i class="fa fa-check-circle"></i> Signoff Progress
                                    </span>
                                    @endcan
                                    @endif
                                    <div class="space-4"></div>
                                    @include('students.training.partials.entity_progress_bar', ['entity' => $portfolio])
                                </div>
                                <div class="col-sm-2">
                                    <div class="pull-right">
                                        <span class="btn btn-app btn-sm btn-success no-hover">
                                            <span class="line-height-1 bigger-170"> {{ $portfolio->signedOffUnits() }}/{{ $portfolio->units->count() }}
                                            </span><br>
                                            <span class="line-height-1 smaller-90"> Units </span>
                                        </span>
                                        <br>
                                        <span data-rel="tooltip" title="Number of mandatory units" class="badge badge-success">M: {{ $portfolio->units->where('unit_group', 1)->count() }}</span>
                                        <span data-rel="tooltip" title="Number of optional units" class="badge badge-info">O: {{ $portfolio->units->where('unit_group', 2)->count() }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="table-responsive">
                                        <table class="table table-bordered tblPortfolioUnits">
                                            <thead>
                                                <tr><th align="center" class="text-center">Units of this portfolio</th></tr>
                                            </thead>
                                            <tbody>
                                                @foreach($portfolio->units AS $unit)
                                                @php
                                                    $unit->load([
                                                        'pcs' => function ($query) {
                                                            $query->orderBy('pc_sequence');
                                                        },
                                                        'pcs.mapped_evidences',
                                                        'pcs.mapped_evidences.media'
                                                    ]);
                                                @endphp
                                                <tr>
                                                    <td>
                                                        @include('students.training.partials.unit')
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                        <div class="tab-pane" id="tabEvidences">
                            <div class="row">
                                <div class="col-sm-12">
                                    <h4 class="lighter">Evidence Repository <small><i class="ace-icon fa fa-angle-double-right"></i> Here you can view and upload your evidences</small></h4> &nbsp;
                                    @can('create-new-evidence')
                                    <span class="btn btn-primary btn-sm btn-round" onclick="window.location.href='{{ route('students.training.evidences.create', [$student, $training_record]) }}'">
                                        <i class="fa fa-plus"></i><i class="fa fa-file-text"></i> Create New Evidence
                                    </span>
                                    @endcan
                                    <i class="ace-icon fa-lg fa fa-chevron-down pull-right" title="Expand All" style="cursor: pointer;" onclick="$('.widgetEvidences').widget_box('show');"></i>
                                    <i class="ace-icon fa-lg fa fa-chevron-up pull-right" title="Collapse All" style="cursor: pointer;" onclick="$('.widgetEvidences').widget_box('hide');"></i>
                                    <div class="space-6"></div>
                                    <div class="table-responsive">
                                        @foreach($training_record->evidences AS $evidence)
                                        @include('students.training.partials.evidence_entry')
                                        <div class="space-6"></div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>{{-- tabEvidences --}}
                        @can('add-remove-tr-elements')
                        <div class="tab-pane" id="tabAddRemoveElements">
                            @include('students.training.partials.add_remove_elements')
                        </div> {{-- tabAddRemoveElements --}}
                        @endcan
                        <div class="tab-pane" id="tabTrainingPlans">
                            <div class="row">
                                <div class="col-xs-12">
				@if(!\Auth::user()->isStudent() && \Auth::user()->can('edit-training-record'))	
                                    <span class="btn btn-primary btn-sm btn-round" onclick="window.location.href='{{ route('students.training.training_plans.edit', [$student, $training_record]) }}'">
                                        <i class="fa fa-edit"></i> {{ is_null($training_record->training_plans) ? 'Create' : 'Edit' }} Training Plans
                                    </span>
				@endif
                                    <p></p>
                                </div>
                            </div>
                            <div class="row">
                                @foreach($training_record->training_plans AS $plan)
                                <div class="col-xs-6">
                                    @include('students.training.partials.training_plan_box', ['_plan' => $plan, 'edit_button' => false])
                                </div>
                                @endforeach
                            </div>
                        </div> 
                        @if(\Session::get('configuration')['FOLIO_CLIENT_NAME'] == "Demo")
                        <div class="tab-pane" id="tabOtj">
                            @include('students.training.partials.training_otj', ['model' => $training_record])
                        </div> {{-- tabOtj --}}
                        <div class="tab-pane" id="tabReviews">
                            @include('students.training.partials.training_reviews', ['model' => $training_record])
                        </div> {{-- tabReviews --}}
                        @endif
                    </div>{{-- tab-content --}}
                </div>{{-- tabbable --}}
            </div> {{-- tab col --}}
        </div>{{-- tab row --}}
        <div class="modal"><!-- Place at bottom of page --></div>
        <!-- PAGE CONTENT ENDS -->
    </div><!-- /.col -->
</div><!-- /.row -->

@endsection

@section('page-plugin-scripts')
<script src="{{ asset('assets/js/jquery.easypiechart.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
<script src="https://code.highcharts.com/7.0.0/highcharts.js"></script>
<script src="https://code.highcharts.com/7.0.0/highcharts-more.js"></script>
<script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
@endsection

@section('page-inline-scripts')
<script type="text/javascript">

var isReloadRequiredForPortfolioTabs = false;

$(function(){
    $('[data-rel=tooltip]').tooltip();
    $('[data-rel=popover]').popover({
        html:true,
        placement:"auto"
    });

    /*$('.tblPortfolioUnits').DataTable({
        "lengthChange": false,
        "paging" : false,
        "info" : false,
        "order": false
    });*/

    $('.easy-pie-chart.percentage').each(function(){
        var barColor = '#59A84B';
        var trackColor = '#E2E2E2';
        var size = parseInt($(this).data('size')) || 92;
        $(this).easyPieChart({
            barColor: barColor,
            trackColor: trackColor,
            scaleColor: false,
            lineCap: 'butt',
            lineWidth: parseInt(size/10),
            animate:{duration: 2500, enabled: true},
            size: size
        }).css('color', barColor);
    });


});

$( ".btnDelPC, .btnDelUnit, .btnDelEvi, .btnDeletePortfolio" ).on('click', function(e) {
    e.preventDefault();

    var form = this.closest('form');

    $.confirm({
        title: 'Confirm!',
        content: 'This action is irreversible, are you sure you want to continue?',
        icon: 'fa fa-question-circle',
        animation: 'scale',
        closeAnimation: 'scale',
        theme: 'supervan',
        opacity: 0.5,
        buttons: {
            'confirm': {
                text: 'Yes',
                btnClass: 'btn-red',
                action: function () {
                    $.ajax({
                        url: form.action,
                        type: form.method,
                        data: $(form).serialize()
                    }).done(function(response, textStatus) {
                        $.alert({
                            title: textStatus == 'success'  ? 'Success' : 'Error',
                            content: response.message,
                            type: textStatus == 'success'  ? 'green' : 'red',
                            buttons: {
                                'OK': {
                                    action: function(){
                                        if(response.success)
                                            window.location.reload();
                                    }
                                }
                            }
                        });
                    }).fail(function(jqXHR, textStatus, errorThrown){
                        $.alert({
                            title: 'Encountered an error!',
                            content: textStatus + ': '+ errorThrown ,
                            icon: 'fa fa-warning',
                            theme: 'supervan',
                            type: 'red'
                        });
                    });
                }
            },
            cancel: function () {
            }
        }
    });
});

$( ".btnDelTR" ).on('click', function(e) {
    e.preventDefault();	
    alert("This action is currently unavailble for you, please contact Perspective (UK) Ltd.");
    return;
    var form = this.closest('form');

    $.confirm({
        title: 'Confirm!',
        content: 'This action is irreversible, are you sure you want to continue?',
        icon: 'fa fa-question-circle',
        animation: 'scale',
        closeAnimation: 'scale',
        theme: 'supervan',
        opacity: 0.5,
        buttons: {
            'confirm': {
                text: 'Yes',
                btnClass: 'btn-red',
                action: function () {
                    $.ajax({
                        url: form.action,
                        type: form.method,
                        data: $(form).serialize()
                    }).done(function(response, textStatus) {
                        $.alert({
                            title: response.success == 'success'  ? 'Success' : 'Error',
                            content: response.message,
                            type: textStatus == 'success'  ? 'green' : 'red',
                            buttons: {
                                'OK': {
                                    action: function(){
                                        if(response.success)
                                            window.location.href="{{ route('students.show', $student) }}";
                                    }
                                }
                            }
                        });
                    }).fail(function(jqXHR, textStatus, errorThrown){
                        $.alert({
                            title: 'Encountered an error!',
                            content: textStatus + ': '+ errorThrown ,
                            icon: 'fa fa-warning',
                            theme: 'supervan',
                            type: 'red'
                        });
                    });
                }
            },
            cancel: function () {
            }
        }
    });
});

$body = $("body");

$(document).on({
    ajaxStart: function() { $body.addClass("loading");    },
    ajaxStop: function() { $body.removeClass("loading"); }
});

function addRemoveElement(form_name)
{
    var form = document.forms[form_name];
    var portfolio_id = form.elements['portfolio_id'].value;

    $.ajax({
        url: form.action,
        type: form.method,
        data: $(form).serialize()
    }).done(function(response, textStatus) {
        if(response.success != undefined)
        {
            $.alert({
                title: 'Encountered an error!',
                content: response.message ,
                icon: 'fa fa-warning',
                theme: 'supervan',
                type: 'red'
            });
        }
        else
        {
            $('#addRemoveElementsQualBody'+portfolio_id).html(response);
            isReloadRequiredForPortfolioTabs = true;
        }
    }).fail(function(jqXHR, textStatus, errorThrown){
        $.alert({
            title: 'Encountered an error!',
            content: textStatus + ': '+ errorThrown ,
            icon: 'fa fa-warning',
            theme: 'supervan',
            type: 'red'
        });
    });
}


$(function(){

    $('.qualification-widget').on('show.ace.widget', function(ev) {
        var id = this.id;
        var portfolio_id = this.id.replace('addRemoveElementsQualBox', '');

        $.ajax({
            url: '/add_remove_training_elements/'+portfolio_id,
        }).done(function(content) {
            $('#'+id + '>.widget-body>.widget-main').html(content);
        }).fail(function(jqXHR, textStatus, errorThrown){
            $.alert(errorThrown, textStatus);
        });
    });

    $('.linkPortfolioTab').on('click', function (){
        if(isReloadRequiredForPortfolioTabs)
            window.location.reload();
    });

    var chart = new Highcharts.chart('progressChart', {!! $progressChart !!});


});


</script>
@endsection
