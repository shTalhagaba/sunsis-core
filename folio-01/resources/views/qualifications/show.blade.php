@extends('layouts.master')
@section('title', 'Qualifications')
@section('page-plugin-styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
@endsection

@section('breadcrumbs')
{{ Breadcrumbs::render('qualifications.show', $qualification) }}
@endsection

@section('page-content')
<div class="page-header"><h1>{{ $qualification->title }}</h1></div><!-- /.page-header -->
<div class="row">
    <div class="col-xs-12">
        <!-- PAGE CONTENT BEGINS -->

        <div class="row">
            <div class="well well-sm">
                <button class="btn btn-sm btn-white btn-primary btn-round" type="button" onclick="window.location.href='{{ route('qualifications.index') }}'">
                    <i class="ace-icon fa fa-arrow-left bigger-110"></i> Back
                </button>
                <button class="btn btn-sm btn-white btn-primary btn-bold btn-round" type="button" onclick="window.location.href='{{ route('qualifications.edit', $qualification) }}'">
                    <i class="ace-icon fa fa-edit bigger-120 blue"></i> Edit Qualification
                </button>
                <button class="btn btn-sm btn-white btn-primary btn-bold btn-round" type="button" onclick="window.location.href='{{ route('qualifications.units.createMultiple', $qualification) }}'">
                    <i class="ace-icon fa fa-plus bigger-120 blue"></i> Add Multiple Units
                </button>
		<button class="btn btn-sm btn-white btn-primary btn-bold btn-round" type="button" onclick="window.location.href='{{ route('qualifications.copy', $qualification) }}'">
                    <i class="ace-icon fa fa-copy bigger-120 blue"></i> Copy Qualification
                </button>
                {!! Form::open([
                    'method' => 'DELETE',
                    'url' => route('qualifications.destroy', [$qualification]),
                    'style' => 'display: inline;',
                    'class' => 'form-inline frmDeleteQualification' ]) !!}
                    {!! Form::button('<i class="ace-icon fa fa-trash bigger-120 red"></i> Delete Qualification', [
                        'class' => 'btn btn-white btn-danger btn-xs pull-right btn-round btnDeleteQualification',
                        'id' => 'btnDeleteQualification' . $qualification->id,
                        'type' => 'submit',
                        'style' => 'display: inline']) !!}
                {!! Form::close() !!}
            </div>

            <div class="col-xs-12">
                <div class="widget-box">
                    <div class="widget-header">
                        <h5 class="widget-title">Qualification Details</h5>
                    </div>
                    <div class="widget-body">
                        <div class="widget-main">
                            <div class="info-div info-div-striped">
                                <div class="info-div-row">
                                    <div class="info-div-name"> Level & Status</div>
                                    <div class="info-div-value">
                                        <span>
                                            <span class="label label-md label-info arrowed-in-right arrowed-in">{{ $qualification->level }}</span>
                                            @php
                                                switch($qualification->getOriginal('status'))
                                                {
                                                    case '2':
                                                        $status_color = 'warning';
                                                    break;
                                                    case '3':
                                                        $status_color = 'danger';
                                                    break;
                                                    case '4':
                                                        $status_color = 'default';
                                                    break;
                                                    default:
                                                        $status_color = 'success';
                                                    break;
                                                }
                                            @endphp
                                            <span class="label label-md label-{{ $status_color }} arrowed-in-right arrowed-in">{{ $qualification->status }}</span>
                                        </span>
                                    </div>
                                </div>
                                <div class="info-div-row">
                                    <div class="info-div-name"> Qualification Number </div>
                                    <div class="info-div-value"><span>{{ $qualification->qan }}</span></div>
                                </div>
                                <div class="info-div-row">
                                    <div class="info-div-name"> Awarding Org. </div>
                                    <div class="info-div-value"><span>{{ $qualification->owner_org_name }}</span></div>
                                </div>
                                <div class="info-div-row">
                                    <div class="info-div-name"> Type </div>
                                    <div class="info-div-value"><span>{{ $qualification->type }}</span></div>
                                </div>
                                <div class="info-div-row">
                                    <div class="info-div-name"> SSA </div>
                                    <div class="info-div-value"><span>{{ $qualification->ssa }}</span></div>
                                </div>
                                <div class="info-div-row">
                                    <div class="info-div-name"> Status </div>
                                    <div class="info-div-value"><span>{{ $qualification->status }}</span></div>
                                </div>
                                <div class="info-div-row">
                                    <div class="info-div-name"> Regulation Start Date </div>
                                    <div class="info-div-value"><span>{{ $qualification->regulation_start_date }}</span></div>
                                </div>
                                <div class="info-div-row">
                                    <div class="info-div-name"> Operational Dates </div>
                                    <div class="info-div-value"><span>{{ $qualification->operational_start_date }} - {{ $qualification->operational_end_date }}</span></div>
                                </div>
                                <div class="info-div-row">
                                    <div class="info-div-name"> Certification End Date </div>
                                    <div class="info-div-value"><span>{{ $qualification->certification_end_date }}</span></div>
                                </div>
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
                                    <div class="info-div-value">
                                        <span>{{ $qualification->glh }}</span> &nbsp; {!! $qualification->glh == $qualification->units->sum('glh')
                                        ? '<i data-rel="tooltip" title="Sum of units GLH equals this" class="fa fa-check-circle fa-lg"
                                            style="color: green;"></i>' : '<i data-rel="tooltip" title="Sum of units GLH does not equal this"
                                            class="fa fa-warning fa-lg" style="color: red;"></i>' !!}
                                    </div>
                                </div>
                                <div class="info-div-row">
                                    <div class="info-div-name"> Total Credits </div>
                                    <div class="info-div-value">
                                        <span>{{ $qualification->total_credits }}</span> &nbsp; {!! $qualification->total_credits
                                        == $qualification->units->sum('unit_credit_value') ? '<i data-rel="tooltip" title="Sum of units credit values equals this"
                                            class="fa fa-check-circle fa-lg" style="color: green;"></i>' : '<i data-rel="tooltip"
                                            title="Sum of units credit values does not equal this" class="fa fa-warning fa-lg"
                                            style="color: red;"></i>' !!}
                                    </div>
                                </div>
                                <div class="info-div-row">
                                    <div class="info-div-name"> Total Qualification Time </div>
                                    <div class="info-div-value"><span>{{ $qualification->total_qual_time != '' ? $qualification->total_qual_time . ' (hours)' : '' }}</span></div>
                                </div>
                                <div class="info-div-row">
                                    <div class="info-div-name"> Overall Grading Type </div>
                                    <div class="info-div-value"><span>{{ $qualification->overall_grading_type }}</span></div>
                                </div>
                                <div class="info-div-row">
                                    <div class="info-div-name"> Assessment Methods </div>
                                    <div class="info-div-value"><span>{{ $qualification->assessment_methods }}</span></div>
                                </div>
                                <div class="info-div-row">
                                    <div class="info-div-name"> Specification </div>
                                    <div class="info-div-value"><span><a target="_blank" href="{{ $qualification->link_to_specs }}"> {{ str_limit($qualification->link_to_specs, 25) }}</a></span></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xs-12">

                <div class="widget-box" id="my-widget">
                    <div class="widget-header">
                        <h5 class="widget-title">Units</h5> &nbsp;
                        <span data-rel="tooltip" title="Number of total units" class="badge badge-default">T: {{ $qualification->units->count() }}</span>
                        <span data-rel="tooltip" title="Number of mandatory units" class="badge badge-success">M: {{ $mandatory_units->count() }}</span>
                        <span data-rel="tooltip" title="Number of optional units" class="badge badge-info">O: {{ $optional_units->count() }}</span>
                    </div>
                    <div class="widget-body">
                        <div class="widget-toolbox padding-8 clearfix">
                            <button type="button" class="btn btn-white btn-primary btn-round btn-bold btn-xs pull-left"
                            onclick="window.location.href='{{ route('qualifications.units.create', $qualification) }}'">
                                <i class="ace-icon fa fa-plus bigger-120 blue"></i>
                                <span class="bigger-110">Add Single Unit</span>
                            </button>
                        </div>
                        <div class="widget-main">
                            @foreach($qualification->units AS $unit)
                            @include('qualifications.partials.unit_with_pcs', ['with_buttons_toolbar' => true])
                            @endforeach
                        </div>
                    </div>
                </div>

            </div>

        </div>


        <!-- PAGE CONTENT ENDS -->
    </div>
    <!-- /.col -->
</div>
<!-- /.row -->
@endsection

@section('page-plugin-scripts')
<script src="{{ asset('assets/js/toastr.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
@endsection

@section('page-inline-scripts')

<script type="text/javascript">

	$(function(){
			$('.show-details-btn').on('click', function(e) {
					e.preventDefault();
					$(this).closest('tr').next().toggleClass('open');
					$(this).find(ace.vars['.icon']).toggleClass('fa-angle-double-down').toggleClass('fa-angle-double-up');
				});
			$('[data-rel=tooltip]').tooltip();

	});

	function showHideAll()
	{
			console.log('inside function');
			$('.show-details-btn').each(function(i, obj) {
					 $(this).closest('tr').next().toggleClass('open');
					$(this).find(ace.vars['.icon']).toggleClass('fa-angle-double-down').toggleClass('fa-angle-double-up');
			});
	}



$( ".btnDeleteUnit, .btnDeleteUnitPC" ).on('click', function(e) {
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

$( ".btnDeleteQualification" ).on('click', function(e) {
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
		    $('#my-widget').widget_box('reload');
                    $( "button" ).attr('disabled', true);
                    $( ".btnDeleteQualification" ).html('<i class="fa fa-refresh fa-spin"></i> Deleteing ...');
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
                                            window.location.href="{{ route('qualifications.index') }}";
                                    }
                                }
                            }
                        });
                    }).fail(function(jqXHR, textStatus, errorThrown){
			$('#my-widget').widget_box('show');
                        $( "button" ).attr('disabled', false);
                        $( ".btnDeleteQualification" ).attr('disabled', false);
                        $( ".btnDeleteQualification" ).html('<i class="ace-icon fa fa-trash bigger-120 red"></i> Delete Qualification');
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

</script>
@endsection
