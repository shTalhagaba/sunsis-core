@extends('layouts.master')

@section('title', 'Add Qualifications')

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
{{ Breadcrumbs::render('programmes.qualifications.add', $programme) }}
@endsection

@section('page-content')
<div class="page-header">
    <h1>Add Qualifications
        <small><i class="ace-icon fa fa-angle-double-right"></i> add new qualifications into the programme</small>
    </h1>
</div><!-- /.page-header -->
<div class="row">
    <div class="col-xs-12">
        <!-- PAGE CONTENT BEGINS -->
        <div class="well well-sm">
            <button class="btn btn-sm btn-white btn-primary btn-round" type="button" onclick="window.location.href='{{ route('programmes.show', [$programme]) }}'">
                <i class="ace-icon fa fa-arrow-left bigger-110"></i> Back
            </button>
        </div>

        <div class="row">
            <div class="col-sm-6">
                <div class="widget-box transparent">
                    <div class="widget-header"><h5 class="widget-title">Programme Details</h5></div>
                    <div class="widget-body">
                        <div class="widget-main">
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
                    </div>
                </div>
            </div>

            <div class="col-sm-6">
                <div class="widget-box transparent">
                    <div class="widget-header"><h5 class="widget-title">Qualifications</h5></div>
                    <div class="widget-body">
                        <div class="widget-main">
                            <div class="info-div info-div-striped">
                                <div class="info-div-row">
                                    <div class="info-div-name"> Qualifications </div>
                                    <div class="info-div-value">
                                        @foreach($programme->qualifications AS $qualification)
                                        <span><i class="fa fa-graduation-cap"></i> {{ $qualification->qan }} - {{ $qualification->title }}</span><br>
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
                        {!! Form::select('qualification_to_load', $active_qualifications, '', ['class' => 'form-control', 'placeholder' => '', 'required']) !!}
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
            'url' => route('programmes.qualifications.save', [$programme]),
            'class' => 'form-horizontal',
            'role' => 'form',
            'name' => 'frmAddQualification',
            'id' => 'frmAddQualification',
            'method' => 'POST'
        ]) !!}
        {!! Form::hidden('qualification_id', 'Inaam') !!}

        <div class="row">
            <div class="col-sm-12">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <tbody id="tbodyQualificationDetail">
                            <tr><td><div class="alert alert-info small"><i class="fa fa-info-circle"> Please select qualification from list and press 'Click to load'</i></div></td></tr>
                        </tbody>
                    </table>
                    <div class="clearfix form-actions center">
                        <div class="alert alert-info alert-sm"><i class="fa fa-info-circle"> System will create a new training plan and add all your selected units into it.</i></div>
                        <button class="btn btn-sm btn-success btn-round" type="submit" id="btnSubmitFrmAddQualification" disabled>
                            <i class="ace-icon fa fa-save bigger-110"></i> Add Qualification
                        </button> &nbsp; &nbsp; &nbsp;
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
        $('#btnSubmitFrmAddQualification').attr('disabled', false);
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
            var postForm = document.forms["frmAddQualification"];
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

    $('#frmAddQualification').validate({
        errorElement: 'div',
        errorClass: 'help-block',
        focusInvalid: false,
        rules: {
        },

        highlight: function (e) {
            $(e).closest('.form-group').removeClass('has-info').addClass('has-error');
        },

        success: function (e) {
            $(e).closest('.form-group').removeClass('has-error').addClass('has-info');
            $(e).remove();
        },

        errorPlacement: function (error, element) {
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
