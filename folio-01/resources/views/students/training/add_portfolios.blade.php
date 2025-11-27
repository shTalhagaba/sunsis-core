@extends('layouts.master')

@section('title', 'Add Portfolio')

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
</style>
@endsection

@section('breadcrumbs')
{{ Breadcrumbs::render('students.training.portfolios.show', $student, $training_record) }}
@endsection

@section('page-content')
<div class="page-header">
    <h1>Add Portfolios
        <small><i class="ace-icon fa fa-angle-double-right"></i> add new portfolio into the training record</small>
    </h1>
</div><!-- /.page-header -->
<div class="row">
    <div class="col-xs-12">
        <!-- PAGE CONTENT BEGINS -->
        <div class="well well-sm">
            <button class="btn btn-sm btn-white btn-primary btn-round" type="button" onclick="window.location.href='{{ route('students.training.show', [$student, $training_record]) }}'">
                <i class="ace-icon fa fa-arrow-left bigger-110"></i> Back
            </button>
        </div>

        <div class="row">
            <div class="col-sm-6">
                <div class="widget-box transparent">
                    <div class="widget-header"><h5 class="widget-title">Learner Details</h5></div>
                    <div class="widget-body">
                        <div class="widget-main">
                            <div class="info-div info-div-striped">
                                <div class="info-div-row">
                                    <div class="info-div-name"> Learner </div>
                                    <div class="info-div-value"><span>{{ $student->full_name }}</span></div>
                                </div>
                                <div class="info-div-row">
                                    <div class="info-div-name"> Primary Email </div>
                                    <div class="info-div-value">
                                        <span>
                                            <i class="fa fa-envelope blue bigger-110"></i> {{ $student->primary_email }}
                                        </span>
                                    </div>
                                </div>
                                <div class="info-div-row">
                                    <div class="info-div-name"> Employer </div>
                                    <div class="info-div-value"><span>{{ $student->employer->legal_name }}</span></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-sm-6">
                <div class="widget-box transparent">
                    <div class="widget-header"><h5 class="widget-title">Training Details</h5></div>
                    <div class="widget-body">
                        <div class="widget-main">
                            <div class="info-div info-div-striped">
                                <div class="info-div-row">
                                    <div class="info-div-name"> Status </div>
                                    <div class="info-div-value"><span><span class="label label-md label-info arrowed-in arrowed-in-right">{{ $training_record->status_code }}</span></span></div>
                                </div>
                                <div class="info-div-row">
                                    <div class="info-div-name"> Dates </div>
                                    <div class="info-div-value">
                                        <span>{{ $training_record->start_date }} - {{ $training_record->planned_end_date }}</span>
                                    </div>
                                </div>
                                <div class="info-div-row">
                                    <div class="info-div-name"> Portfolio(s) </div>
                                    <div class="info-div-value">
                                        @foreach($training_record->portfolios AS $portfolio)
                                        <span><i class="fa fa-graduation-cap"></i> {{ $portfolio->qan }} - {{ $portfolio->title }}</span><br>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="space-12"></div>

        @include('partials.session_message')

        @include('partials.session_error')

        {!! Form::open([
            'url' => route('ajax.load.qualifications'),
            'class' => 'form-horizontal',
            'role' => 'form',
            'method' => 'GET'
        ]) !!}
        <div class="row">
            <div class="col-sm-12">
                <div class="form-group row required {{ $errors->has('qualification_to_load') ? 'has-error' : ''}}">
                    {!! Form::label('qualification_to_load', 'Select Qualification', ['class' => 'col-sm-3 control-label']) !!}
                    <div class="col-sm-6">
                        {!! Form::select('qualification_to_load', $active_qualifications, '', ['class' => 'form-control', 'placeholder' => '', 'required' => 'required']) !!}
                        {!! $errors->first('qualification_to_load', '<p class="text-danger">:message</p>') !!}
                    </div>
                    <div class="col-sm-3">
                        <button class="btn btn-sm btn-round btn-info" type="button" id="btnLoadQualification">
                            <i class="ace-icon fa fa-arrow-right"></i> Click to load
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="space-2"></div>
        <hr class="dotted">
        <div class="space-2"></div>
        {!! Form::close() !!}

        {!! Form::open([
            'url' => route('students.training.portfolios.add', [$student, $training_record]),
            'class' => 'form-horizontal',
            'role' => 'form',
            'name' => 'frmAddPortfolio',
            'id' => 'frmAddPortfolio',
            'method' => 'POST'
        ]) !!}
        {!! Form::hidden('qualification_id', 'INaam') !!}
        <div class="row">
            <div class="col-sm-6">
                    <div class="form-group row required {{ $errors->has('start_date') ? 'has-error' : ''}}">
                            {!! Form::label('start_date', 'Start Date', ['class' => 'col-sm-4 control-label']) !!}
                            <div class="col-sm-8">
                                {!! Form::date('start_date', null, ['class' => 'form-control', 'required' => 'required']) !!}
                                {!! $errors->first('start_date', '<p class="text-danger">:message</p>') !!}
                            </div>
                        </div>
            </div>
            <div class="col-sm-6">
                    <div class="form-group row required {{ $errors->has('planned_end_date') ? 'has-error' : ''}}">
                            {!! Form::label('planned_end_date', 'Planned End Date', ['class' => 'col-sm-4 control-label']) !!}
                            <div class="col-sm-8">
                                {!! Form::date('planned_end_date', null, ['class' => 'form-control', 'required' => 'required']) !!}
                                {!! $errors->first('planned_end_date', '<p class="text-danger">:message</p>') !!}
                            </div>
                        </div>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <tbody id="tbodyQualificationDetail">
                            <tr><td><div class="alert alert-info"><i class="fa fa-info-circle"> Please select qualification from list and press 'Click to load'</i></div></td></tr>
                        </tbody>
                    </table>
                    <div class="clearfix form-actions center">
                        @can('add-remove-tr-elements')
                        <button class="btn btn-sm btn-success btn-round" type="submit" id="btnSubmitFrmAddPortfolio" disabled>
                            <i class="ace-icon fa fa-save bigger-110"></i> Add Portfolio
                        </button> &nbsp; &nbsp; &nbsp;
                        @endcan
                    </div>
                </div>
            </div>
        </div>
        {!! Form::close() !!}

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

    $( "#btnLoadQualification" ).on('click', function(e) {
        if($('#qualification_to_load').val() == '')
        {
            $.alert('Please select the qualification', 'Validation');
            return false;
        }
        $('#btnSubmitFrmAddPortfolio').attr('disabled', false);
        e.preventDefault();

        var form = this.closest('form');
        $.ajax({
            url: form.action,
            type: form.method,
            data: $(form).serialize()
        }).done(function(response, textStatus) {
            $('table>tbody#tbodyQualificationDetail>tr').remove();
            // console.log(response.message.units);
            var markup = '';
            var qualification = response.message.qualification;
            var units = response.message.units;
            markup += '<tr><td class="center" colspan="2">';
            markup += '<h4 class="bigger green" style="display: inline;">' + qualification.qan + ' ' + qualification.title + '</h4>';
            markup += '</td></tr>';

            $.each(units, function(index, unit){
                //console.log(unit.title);
                markup += '<tr>';
                markup += '<th class="center">';
                markup += '<div class="checkbox">';
                markup += '<label>';
                markup += '<input onclick = "chkUnitClicked(this);" name="chkUnit[]" id="chkUnit' + unit.id + '" value="' + unit.id + '" class="ace ace-checkbox-2 chkUnit" type="checkbox" />';
                markup += '<span class="lbl"> </span>';
                markup += '</label>';
                markup += '</div>';
                markup += '</th>';
                markup += '<th class="brown"><i class="fa fa-folder fa-lg"></i><h5 style="display: inline;"> ' + unit.title + '</h5>';
                markup += '</th>';
                markup += '</tr>';
                $.each(unit.pcs, function(index, pc){
                    // console.log(pc.title);
                    markup += '<tr>';
                    markup += '<td class="center">';
                    markup += '<div class="checkbox">';
                    markup += '<label>';
                    markup += '<input onclick = "chkPCClicked(this);" name="chkPC[]" id="pc' + pc.id + 'OfUnit' + unit.id + '" value="' + pc.id + '" class="ace ace-checkbox-2 chkPC" type="checkbox" />';
                    markup += '<span class="lbl"> </span>';
                    markup += '</label>';
                    markup += '</div>';
                    markup += '</td>';
                    markup += '<td class="blue"><i class="fa fa-folder-open"></i> ' + pc.title + '</span></td>';
                });
            });
            $("table>tbody#tbodyQualificationDetail").append(markup);
            var postForm = document.forms["frmAddPortfolio"];
            postForm.elements["qualification_id"].value = $('#qualification_to_load').val();
        }).fail(function(jqXHR, textStatus, errorThrown){
            $.alert({
                title: 'Encountered an error!',
                content: textStatus + ': '+ errorThrown ,
                icon: 'fa fa-warning',
                theme: 'supervan',
                type: 'red'
            });
        });
    });

    $('#frmAddPortfolio').validate({
        errorElement: 'div',
        errorClass: 'help-block',
        focusInvalid: false,
        rules: {
            start_date: {
                required: true
            },
            planned_end_date: {
                required: true,
                greaterThan: "#start_date"
            }
        },

        highlight: function (e) {
            $(e).closest('.form-group').removeClass('has-info').addClass('has-error');
        },

        success: function (e) {
            $(e).closest('.form-group').removeClass('has-error').addClass('has-info');
            $(e).remove();
        },

        errorPlacement: function (error, element) {
            if(element.is('input[type=checkbox]') || element.is('input[type=radio]')) {
                var controls = element.closest('div[class*="col-"]');
                if(controls.find(':checkbox,:radio').length > 1) controls.append(error);
                else error.insertAfter(element.nextAll('.lbl:eq(0)').eq(0));
            }
            else
                error.insertAfter(element);
        },

        submitHandler: function(form) {
            $.ajax({
                url: form.action,
                type: form.method,
                data: $(form).serialize()
            }).done(function(response, textStatus) {
                $.alert({
                    title: (response.success) ? 'Success' : 'Error',
                    content: response.message,
                    type: (response.success) ? 'green' : 'red',
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
    });

    jQuery.validator.addMethod("greaterThan", function(value, element, params) {
        if($(params).val() == '')
            return true;
        if (!/Invalid|NaN/.test(new Date(value))) {
            return new Date(value) > new Date($(params).val());
        }
        return isNaN(value) && isNaN($(params).val()) || (Number(value) > Number($(params).val()));
    }, "Planned end date must be after the start date.");

});

function chkUnitClicked(element)
{
    var unit_number = element.id.replace('chkUnit', '');
    if(element.checked)
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
}

function chkPCClicked(element)
{
    var unit_number = element.id.replace('pc'+element.value+'OfUnit', '');

    if(element.checked) // if pc is clicked then check the Unit checkbox too.
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
}

</script>

@endsection
