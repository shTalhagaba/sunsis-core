@extends('layouts.master')

@section('title', 'Programme Training Plan Template')

@section('page-plugin-styles')
<link rel="stylesheet" href="{{ asset('assets/css/toastr.min.css') }}" />

@endsection

@section('breadcrumbs')
{{ Breadcrumbs::render('programmes.training_plans.update', $programme) }}
@endsection

@section('page-content')
<div class="page-header"><h1>Training Plans Template</h1></div><!-- /.page-header -->
<div class="row">
    <div class="col-xs-12">
        <div class="well well-sm">
            <button class="btn btn-sm btn-white btn-primary btn-round" type="button" onclick="window.location.href='{{ route('programmes.show', $programme) }}'">
                <i class="ace-icon fa fa-arrow-left bigger-110"></i> Back
            </button>
        </div>

        <div class="row">
            <div class="col-xs-6">
                <div class="widget-box transparent">
                    <div class="widget-header"><h5 class="widget-title">Programme Details</h5></div>
                    <div class="widget-body">
                        <div class="widget-main">
                            <div class="info-div info-div-striped">
                                <div class="info-div-row">
                                    <div class="info-div-name"> Title </div>
                                    <div class="info-div-value"><span>{{ $programme->title }}</span></div>
                                </div>
                                <div class="info-div-row">
                                    <div class="info-div-name"> Date </div>
                                    <div class="info-div-value">
                                        <span>
                                            {{ \Carbon\Carbon::parse($programme->start_date)->format('d/m/Y') }} -
                                            {{ \Carbon\Carbon::parse($programme->end_date)->format('d/m/Y') }}
                                        </span>
                                    </div>
                                </div>
                                <div class="info-div-row">
                                    <div class="info-div-name"> Training Records Count </div>
                                    <div class="info-div-value"><span>{{ $programme->training_records->count() }}</span></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xs-6">
                <div class="widget-box transparent">
                    <div class="widget-header"><h5 class="widget-title">Programme Qualifications</h5></div>
                    <div class="widget-body">
                        <div class="widget-main">
                            <div class="info-div info-div-striped">
                                @foreach($programme->qualifications AS $qualification)
                                <div class="info-div-row" style="cursor: pointer;" onclick="$('#Units{{ $qualification->qan }}').toggle();">
                                    <div class="info-div-name"> {{ $qualification->qan }} </div>
                                    <div class="info-div-value">
                                        <span>{{ $qualification->title }}</span>
                                    </div>
                                </div>
                                <div class="info-div-row" id="Units{{ $qualification->qan }}" style="display: none;">
                                    <div class="info-div-name"> {{ $qualification->qan }} Units </div>
                                    <div class="info-div-value">
                                        @foreach ($qualification->units as $q_unit)
                                        <p class="small">{{ $q_unit->unique_ref_number }}: {{ $q_unit->title }}</p>
                                        @endforeach
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @include('partials.session_message')
        @include('partials.session_error')

        <div class="row">
            <div class="col-xs-12">
                <div class="widget-box transparent">
                    <div class="widget-header">
                        <h5 class="widget-title">
                            Training Plans
                            <span data-rel="tooltip" title="Number of optional units" class="badge badge-info">{{ $programme->training_plans->count() }}</span>
                        </h5>
                    </div>
                    <div class="widget-body">
                        <div class="widget-main">
                            {!! Form::open([
                                'url' => route('programmes.update_number_of_training_plans', [$programme]),
                                'class' => 'form-horizontal',
                                'role' => 'form',
                                'method' => 'POST'
                            ]) !!}
                            {!! Form::hidden('number_of_training_plans', 1) !!}
                            <div class="col-xs-12">
                                Start Date: {!! Form::date('training_plan_start_date', '', ['required']) !!} &nbsp;
                                End Date: {!! Form::date('training_plan_end_date', '', ['required']) !!} &nbsp;
                                <button class="btn btn-sm btn-round btn-info" type="submit" id="btnAddPlans">
                                    <i class="ace-icon fa fa-plus"></i> Add Plan
                                </button>
                            </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12">
                <span class="hr"></span>
                <button class="btn btn-sm btn-round btn-success" type="button" id="btnTest">
                    <i class="ace-icon fa fa-save"></i> Save Changes
                </button>
                <span class="hr"></span>
            </div>
        </div>

        <div class="row">
            @foreach($programme->training_plans AS $plan)
            <div class="col-xs-4">
                @include('programmes.partials.training_plan_box', ['_plan' => $plan, 'edit_button' => true])
            </div>
            @endforeach
        </div>


    </div>
</div>

<div id="plan-modal-form" class="modal" tabindex="-1">
	<form name="frmEditTrainingPlan" id="frmEditTrainingPlan" action="" method="POST">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="blue bigger">Training Plan Edit - <small>update dates</small></h4>
			</div>
			<div class="modal-body">
                <p class="text-center lead"><input disabled name="plan_number" id="plan_number" type="text" value=""></p>
				<div class="row">
					<div class="col-sm-12">
						@csrf
                        {!! Form::hidden('id', null, ['id' => 'id']) !!}

                        <div class="form-group row {{ $errors->has('training_plan_start_date') ? 'has-error' : ''}}">
                            {!! Form::label('training_plan_start_date', 'Start Date', ['class' => 'col-sm-4 control-label']) !!}
                            <div class="col-sm-8">
                                {!! Form::date('training_plan_start_date', null, ['class' => 'form-control', 'id' => 'training_plan_start_date']) !!}
                                {!! $errors->first('training_plan_start_date', '<p class="text-danger">:message</p>') !!}
                            </div>
                        </div>
                        <div class="form-group row {{ $errors->has('training_plan_end_date') ? 'has-error' : ''}}">
                            {!! Form::label('training_plan_end_date', 'End Date', ['class' => 'col-sm-4 control-label']) !!}
                            <div class="col-sm-8">
                                {!! Form::date('training_plan_end_date', null, ['class' => 'form-control', 'id' => 'training_plan_end_date']) !!}
                                {!! $errors->first('training_plan_end_date', '<p class="text-danger">:message</p>') !!}
                            </div>
                        </div>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="submit" class="btn btn-sm btn-round" data-dismiss="modal">
					<i class="ace-icon fa fa-times"></i> Cancel
				</button>
				<button class="btn btn-sm btn-success btn-round">
					<i class="ace-icon fa fa-check"></i> Save
				</button>
			</div>
		</div>
	</div>
	</form>
</div>

@endsection


@section('page-plugin-scripts')
<script src="{{ asset('assets/js/toastr.min.js') }}"></script>
<script src="{{ asset('assets/js/jquery.nestable.js') }}"></script>

@endsection

@section('page-inline-scripts')

<script type="text/javascript">

$(document).ready(function(){

var plans = [];

$('#btnTest').on('click', function(){
    plans = [];

    $("div[id^='nestable']").each(function(index, value){
        var e = $('#'+this.id);
        var plan_number = this.id.replace('nestable', '');
        var start_date_of_plan = $("input[name=start_date_of_plan_number"+plan_number+"]").val();
        var end_date_of_plan = $("input[name=end_date_of_plan_number"+plan_number+"]").val();
        var plan = {};
        plan.plan_number = plan_number;
        plan.start_date = start_date_of_plan;
        plan.end_date = end_date_of_plan;
        plan.plan_units = e.nestable('serialize');
        plans.push(plan);
    });

    $.ajax({
        url: '{{ route('programmes.training_plans.update', $programme) }}',
        type: 'POST',
        data: {plans: plans},
        success: function(response) {
            toastr.options.positionClass = 'toast-bottom-right';
            toastr.success(response.message);
            window.location.reload();
        },
        error: function (response) {
            alert(response.status + ': ' + response.statusText + '\r\n' + 'Please refresh the page and try again and raise a support request if problem persists.');
        }
    });
});


// activate Nestable for list 1
$("div[id^='nestable']").nestable({
    maxDepth: 1
});



});

///////////////////////////////////////////////

function preparePlanModalForEdit(plan)
{
    var form = document.forms['frmEditTrainingPlan'];
    form.reset();
    form.plan_number.value = "Plan " + plan.plan_number;
    form.id.value = plan.id;
    form.training_plan_start_date.value = plan.start_date;
    form.training_plan_end_date.value = plan.end_date;
    form.action = '/programmes/{{ $programme->id }}/plans/'+plan.id;
    $('#plan-modal-form').modal('show');
}


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

</script>

@endsection

