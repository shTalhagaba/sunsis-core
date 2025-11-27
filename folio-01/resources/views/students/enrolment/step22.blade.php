@extends('layouts.master')

@section('title', 'Single Enrolment - Step 2')

@section('page-plugin-styles')
<link rel="stylesheet" href="{{ asset('assets/css/toastr.min.css') }}" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
<style>
    hr {
        padding: 0px;
        margin: 0px;
    }

    input[type=checkbox] {
        transform: scale(1.4);
    }
    .avatar {
		vertical-align: middle;
		width: 50px;
		height: 50px;
		border-radius: 50%;
	}
</style>
@endsection

@section('breadcrumbs')
{{ Breadcrumbs::render('students.singleEnrolment.step2.show', $student) }}
@endsection

@section('page-content')
<div class="page-header"><h1>Enrol Single Learner - Step 2</h1></div><!-- /.page-header -->
<div class="row">
    <div class="col-xs-12">
        <!-- PAGE CONTENT BEGINS -->
        <div class="row">
            <div class="col-sm-12">
                <div class="alert alert-info">
                    <i class="fa fa-info-circle"></i> Step 2<br>
                    <i class="fa fa-hand-o-right"></i> <small>Select units and elements for each qualification (portfolio) which you have selected in Step 1.</small><br>
                    <i class="fa fa-hand-o-right"></i> <small>Click on 'Continue to Step 3' to proceed to the next step.</small>
                 </div>
            </div>
        </div>

        @include('partials.session_message')

        @include('partials.session_error')

        <div class="space-4"></div>

        <div class="row">

        </div>

        <div class="row">
            <div class="col-xs-9">
                <div class="widget-box transparent">
                    <div class="widget-header"><h5 class="widget-title">Training Details</h5></div>
                    <div class="widget-body">
                        <div class="widget-main">
                            <div class="info-div info-div-striped">
                                <div class="info-div-row">
                                    <div class="info-div-name"> Programme </div>
                                    <div class="info-div-value">{{ $tr->programme->title }}</span></div>
                                </div>
                                <div class="info-div-row">
                                    <div class="info-div-name"> Dates </div>
                                    <div class="info-div-value"><span>{{ $tr->start_date }} - {{ $tr->planned_end_date }}</span></div>
                                </div>
                                <div class="info-div-row">
                                    <div class="info-div-name"> Employer </div>
                                    <div class="info-div-value"><span>{{ $tr->employer->legal_name }}</span></div>
                                </div>
                                <div class="info-div-row">
                                    <div class="info-div-name"> Primary Assessor </div>
                                    <div class="info-div-value"><span>{{ $tr->primaryAssessor->full_name }}</span></div>
                                </div>
                                @if(!is_null($tr->secondary_assessor))
                                <div class="info-div-row">
                                    <div class="info-div-name"> Secondary Assessor </div>
                                    <div class="info-div-value"><span>{{ $tr->secondaryAssessor->full_name }}</span></div>
                                </div>
                                @endif
                                @if(!is_null($tr->tutor))
                                <div class="info-div-row">
                                    <div class="info-div-name"> Tutor </div>
                                    <div class="info-div-value"><span>{{ $tr->tutorUser->full_name }}</span></div>
                                </div>
                                @endif
                                <div class="info-div-row">
                                    <div class="info-div-name"> Verifier </div>
                                    <div class="info-div-value"><span>{{ $tr->verifierUser->full_name }}</span></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <p></p>
                {!! Form::open([
                    'url' => route('students.singleEnrolment.step2.store', $student),
                    'class' => 'form-horizontal',
                    'role' => 'form',
                    'id' => 'frmEnrolmentS2',
                    'method' => 'POST'
                ]) !!}
                <div class="col-xs-12">
                    <div class="alert alert-info">
                        <i class="fa fa-info-circle"></i> Following are the qualifications in your selected programme. You can select which one(s) to add and their optional units.
                    </div>
                    @foreach ($tr->programme->qualifications as $programme_qualification)
                    <div class="widget-box widget-color-green3 ui-sortable-handle">
                        <div class="widget-header">
                            <h5 class="widget-title">
                                <i class="fa fa-graduation-cap"></i> {{ $programme_qualification->qan }} {{ $programme_qualification->title }}
                            </h5>
                            <div class="widget-toolbar">
                                <a href="#" data-action="collapse"><i class="ace-icon fa fa-chevron-down"></i></a>
                            </div>
                        </div>
                        <div class="widget-body">
                            <div class="widget-main">
                                <div class="form-group row">
                                    {!! Form::label('add_qual_'.$programme_qualification->id, 'Add Qualification', ['class' => 'col-sm-4 control-label']) !!}
                                    <div class="col-sm-8">
                                        {!! Form::select('add_qual_'.$programme_qualification->id, ['1' => 'Yes', '0' => 'No'], null, ['class' => 'form-control']) !!}
                                    </div>
                                </div>
                                <div class="form-group row">
                                    {!! Form::label('start_date_qual_'.$programme_qualification->id, 'Start Date', ['class' => 'col-sm-4 control-label']) !!}
                                    <div class="col-sm-8">
                                        {!! Form::date('start_date_qual_'.$programme_qualification->id,
                                        \Carbon\Carbon::createFromFormat('d/m/Y', $tr->start_date)->format('Y-m-d'),
                                        ['class' => 'form-control']) !!}
                                    </div>
                                </div>
                                <div class="form-group row">
                                    {!! Form::label('planned_end_date_qual_'.$programme_qualification->id, 'Planned End Date', ['class' => 'col-sm-4 control-label']) !!}
                                    <div class="col-sm-8">
                                        {!! Form::date('planned_end_date_qual_'.$programme_qualification->id,
                                        \Carbon\Carbon::createFromFormat('d/m/Y', $tr->planned_end_date)->format('Y-m-d'),
                                        ['class' => 'form-control']) !!}
                                    </div>
                                </div>
                                @foreach($programme_qualification->units()->orderBy('unit_sequence')->get() AS $unit)
                                <table class="table table-bordered table-hover">
                                    <tr>
                                        @if ($unit->getOriginal('unit_group') == 1)
                                        <th style="width: 8%" class="center">
                                            <i class="fa fa-check-circle green fa-2x" data-rel="tooltip" title="This unit is mandatory and will be added automatically."></i>
                                            <input name="chkUnit[]" id="chkUnit{{ $unit->id }}" value="{{ $unit->id }}" type="checkbox" checked style="display: none;" />
                                        </th>
                                        <th class="text-success"><i class="fa fa-folder fa-lg"></i> <strong>{{ $unit->title }}</strong></th>
                                        @else
                                        <th style="width: 8%" class="center">
                                            <div class="checkbox">
                                                <label>
                                                    <input name="chkUnit[]" id="chkUnit{{ $unit->id }}" value="{{ $unit->id }}"
                                                        class="ace ace-checkbox-2 chkUnit" type="checkbox" />
                                                    <span class="lbl"> </span>
                                                </label>
                                            </div>
                                        </th>
                                        <th class="text-info"><i class="fa fa-folder fa-lg"></i> <strong>{{ $unit->title }}</strong></th>
                                        @endif
                                    </tr>
                                    @foreach($unit->pcs()->orderBy('pc_sequence')->get() AS $pc)
                                    <tr style="cursor: pointer;">
                                        @if ($unit->getOriginal('unit_group') == 1)
                                        <td class="center">
                                            <i class="fa fa-check-circle green " data-rel="tooltip" title="This pc is mandatory and will be added automatically."></i>
                                            <input name="chkPC[]" id="pc{{ $pc->id }}OfUnit{{ $unit->id }}" value="{{ $pc->id }}" type="checkbox" checked style="display: none;" />
                                        </td>
                                        <td class="text-success"><i class="fa fa-folder-open"></i> {{ $pc->title }}</span></td>
                                        @else
                                        <td class="center">
                                            <div class="checkbox">
                                                <label>
                                                    <input name="chkPC[]" id="pc{{ $pc->id }}OfUnit{{ $unit->id }}"
                                                        value="{{ $pc->id }}" class="ace ace-checkbox-2 chkPC" type="checkbox" />
                                                    <span class="lbl"> </span>
                                                </label>
                                            </div>
                                        </td>
                                        <td class="text-info"><i class="fa fa-folder-open"></i> {{ $pc->title }}</span></td>
                                        @endif
                                    </tr>
                                    @endforeach
                                </table>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                <div class="col-xs-12 form-actions center">
                    <button class="btn btn-sm btn-success btn-round" type="submit">
                        <i class="ace-icon fa fa-arrow-right bigger-110"></i> Continue to Step 3
                    </button> &nbsp; &nbsp; &nbsp;
                    <button class="btn btn-sm btn-round" type="reset">
                        <i class="ace-icon fa fa-undo bigger-110"></i> Reset
                    </button>
                </div>
                {!! Form::close() !!}
            </div>
            <div class="col-xs-3 center">
                <div>
                    <span class="profile-picture">
                        <img class="avatar img-responsive" src="{{ asset($student->avatar_url) }}" alt="{{ $student->firstnames }}" />
                    </span>

                    <div class="space-4"></div>

                    <div class="width-80 label label-info label-xlg arrowed-in arrowed-in-right">
                        <div class="inline position-relative">
                            <span class="white">{{ $student->firstnames  }} {{ $student->surname  }}</span>
                        </div>
                    </div>
                </div>

                <div class="hr hr16 dotted"></div>

                <div class="profile-user-info">
                    <div class="profile-info-row">
                        <div class="profile-info-name">Primary Email:</div>
                        <div class="profile-info-value"><span>{{ $student->primary_email }}</span></div>
                    </div>
                    @if($student->secondary_email != '')
                    <div class="profile-info-row">
                        <div class="profile-info-name">Secondary Email:</div>
                        <div class="profile-info-value"><span>{{ $student->secondary_email }}</span></div>
                    </div>
                    @endif
                    <div class="profile-info-row">
                        <div class="profile-info-name">ULN:</div>
                        <div class="profile-info-value"><span>{{ $student->uln }}</span></div>
                    </div>
                    <div class="profile-info-row">
                        <div class="profile-info-name">NI:</div>
                        <div class="profile-info-value"><span>{{ $student->ni }}</span></div>
                    </div>
                    <div class="profile-info-row">
                        <div class="profile-info-name">Telephone:</div>
                        <div class="profile-info-value"><span>{{ $student->homeAddress()->telephone ?? '' }}</span></div>
                    </div>
                    <div class="profile-info-row">
                        <div class="profile-info-name">Mobile:</div>
                        <div class="profile-info-value"><span>{{ $student->homeAddress()->mobile ?? '' }}</span></div>
                    </div>
                    <div class="profile-info-row">
                        <div class="profile-info-name">Training Records Count:</div>
                        <div class="profile-info-value"><span>{{ $student->training_records()->count() }}</span></div>
                    </div>
                </div>
            </div>
        </div>


        <!-- PAGE CONTENT ENDS -->
    </div><!-- /.col -->
</div><!-- /.row -->
@endsection


@section('page-plugin-scripts')
<script src="{{ asset('assets/js/toastr.min.js') }}"></script>
<script src="{{ asset('assets/js/jquery.validate.min.js') }}"></script>
<script src="{{ asset('assets/js/jquery-additional-methods.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
@endsection

@section('page-inline-scripts')

<script type="text/javascript">
$(function(){

    $('input[type="checkbox"][name="chkPC[]"]').each(function(){
        if(this.checked)
        {
            var unit_number = this.id.replace('pc'+this.value+'OfUnit', '');
            $('input[type="checkbox"][id="chkUnit'+unit_number+'"]').prop('checked', true);
        }
    });

    $('input[type=checkbox][id^=chkUnit]').on('click', function(){
        var unit_number = this.id.replace('chkUnit', '');
        if(this.checked)
        {
            $("input[type='checkbox'][id$='OfUnit"+unit_number+"']").each(function() {
                $(this).prop('checked', true);
            });
        }
        else
        {
            $("input[type='checkbox'][id$='OfUnit"+unit_number+"']").each(function() {
                $(this).prop('checked', false);
            });
        }

    });

    $('input[type="checkbox"][name="chkPC[]"]').on('click', function(){
        var unit_number = this.id.replace('pc'+this.value+'OfUnit', '');
        if(this.checked) // if pc is clicked then check the Unit checkbox too.
        {
            $('input[type="checkbox"][id="chkUnit'+unit_number+'"]').prop('checked', true);
        }
        else // if all pcs of a unit are unticked then untick the unit
        {
            var allPCUnChecked = true;
            $("input[type='checkbox'][id$='OfUnit"+unit_number+"']").each(function() {
                if(this.checked)
                {
                    allPCUnChecked = false;
                    return false;
                }
            });
            if(allPCUnChecked)
            {
                $('input[type="checkbox"][id="chkUnit'+unit_number+'"]').prop('checked', false);
            }
        }

    });

});

$(function(){
    $('#frmEnrolmentS2').validate({
        rules: {
            "chkPC[]": {
                required: true,
                minLength: 1
            }
        },
        messages: {
            "chkPC[]": "Please select at least one performance criteria."
        },
        errorPlacement: function (error, element) {
            $.alert(error.text(), 'Validation Error');
        }
    });
});
</script>

@endsection
