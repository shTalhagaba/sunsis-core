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
        <small><i class="ace-icon fa fa-angle-double-right"></i> add/remove qualifications into the programme</small>
    </h1>
</div><!-- /.page-header -->
<div class="row">
    <div class="col-xs-12">
        <!-- PAGE CONTENT BEGINS -->
        <button class="btn btn-sm btn-white btn-default btn-round" type="button" onclick="window.location.href='{{ route('programmes.show', [$programme]) }}'">
            <i class="ace-icon fa fa-arrow-left bigger-110"></i> Cancel
        </button>
        <div class="hr hr-12 hr-dotted"></div>

        <div class="row">
            <div class="col-sm-12">
                <div class="widget-box transparent">
                    <div class="widget-header"><h5 class="widget-title">Programme Details</h5></div>
                    <div class="widget-body">
                        <div class="widget-main">
                            <div class="info-div info-div-striped">
                                <div class="info-div-row">
                                    <div class="info-div-name"> Title </div><div class="info-div-value"><span>{{ $programme->title }}</span></div>
                                </div>
                                <div class="info-div-row">
                                    <div class="info-div-name"> Programme Type </div>
                                    <div class="info-div-value"><span>{{ optional($programme->programmeType)->description }}</span></div>
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
        </div>

        <div class="space-12"></div>

        @include('partials.session_message')

        @include('partials.session_error')

        <div class="row">
            <div class="col-sm-12">
                <div class="widget-box transparent">
                    <div class="widget-header"><h5 class="widget-title">Manage Qualifications</h5></div>
                    <div class="widget-body">
                        <div class="widget-main">

                            {!! Form::open([
                                'url' => route('programmes.qualifications.add', [$programme]),
                                'method' => 'POST',
                                'class' => 'form-horizontal',
                                'role' => 'form',
                                'id' => 'frmAddProgrammeQualification',
                                'name' => 'frmAddProgrammeQualification',
                            ]) !!}
                            {!! Form::hidden('programme_id', $programme->id) !!}
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group row required {{ $errors->has('qualification_to_add') ? 'has-error' : ''}}">
                                        {!! Form::label('qualification_to_add', 'Select Qualification', ['class' => 'col-sm-3 control-label']) !!}
                                        <div class="col-sm-6">
                                            {!! Form::select('qualification_to_add', $activeQualifications, '', ['class' => 'form-control', 'placeholder' => '', 'required']) !!}
                                            {!! $errors->first('qualification_to_add', '<p class="text-danger">:message</p>') !!}
                                        </div>
                                        <div class="col-sm-3">
                                            <button class="btn btn-sm btn-round btn-primary" type="submit">
                                                Click to Add
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {!! Form::close() !!}

                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th></th>
                                            <th>QAN</th>
                                            <th>Title</th>
                                            <th>Sequence</th>
                                            <th>Proportion</th>
                                            <th>Duration (months)</th>
                                            <th>Offset (months)</th>
                                            <th>Main</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($programme->qualifications AS $prog_qual)
                                        @php
                                            $prefix = "row_" . $loop->iteration . "_qualid_" . $prog_qual->id;
                                        @endphp
                                        <tr id="{{ $prefix }}">
                                            <td>
                                                {!! Form::open([
                                                    'method' => 'DELETE', 
                                                    'url' => route('programmes.qualifications.remove', [$programme]), 
                                                    'style' => 'display: inline;', 
                                                    'class' => 'frmRemoveQual'
                                                    ]) !!}
                                                    {!! Form::hidden('programme_id', $programme->id) !!}
                                                    {!! Form::hidden('qualification_to_remove', $prog_qual->id) !!}
                                                    {!! Form::button('<i class="fa fa-trash"></i>', [
                                                        'class' => 'btn btn-sm btn-danger btn-round', 
                                                        'type' => 'submit', 
                                                        'style' => 'display: inline',
                                                        'title' => 'Remove this qualification from the programme',
                                                        ]) !!}
                                                {!! Form::close() !!}
                                            </td>
                                            <td>{{ $prog_qual->qan }}</td>
                                            <td>{{ $prog_qual->title }}</td>
                                            <td>
                                                {!! Form::select($prefix.'_sequence', range(0, $loop->count), $prog_qual->sequence ?? $loop->iteration, ['class' => 'form-control form-select-sm', 'required', 'id' => $prefix.'_sequence']) !!}
                                            </td>
                                            <td>
                                                {!! Form::select($prefix.'_proportion', range(0, 100), $prog_qual->proportion ?? 0, ['class' => 'form-control form-select-sm', 'required', 'id' => $prefix.'_proportion']) !!}
                                            </td>
                                            <td>
                                                {!! Form::select($prefix.'_duration', range(0, $programme->duration), $prog_qual->duration ?? $programme->duration, ['class' => 'form-control form-select-sm', 'required', 'id' => $prefix.'_duration']) !!}
                                            </td>
                                            <td>
                                                {!! Form::number($prefix.'_offset', $prog_qual->offset ?? 0, ['class' => 'form-control form-control-sm', 'required', 'min' => 0, 'max' => 99, 'id' => $prefix.'_offset']) !!}
                                            </td>
                                            <td>
                                                <input type="radio" name="main" id="main" value="{{ $prog_qual->id }}" {{ $prog_qual->main == 1 ? 'checked' : '' }} />
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="widget-toolbox padding-8 clearfix">
                            <div class="center">
                                @if ($programme->qualifications()->count() > 0)
                                    <button class="btn btn-sm btn-success btn-round" id="btnUpdateProgrammeQualifcations" type="button" onclick="update_programme_qualifcations();">
                                        <i class="fa fa-save"></i> Save Information
                                    </button>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {!! Form::open([
            'url' => route('programmes.update_qualifications_details', $programme),
            'method' => 'POST',
            'class' => 'form-horizontal',
            'role' => 'form',
            'id' => 'frmManageProgrammeQualification',
            'name' => 'frmManageProgrammeQualification',
        ]) !!}
        {!! Form::hidden('programme_id', $programme->id) !!}
        {!! Form::hidden('data_to_update', null) !!}
        {!! Form::hidden('main_aim', null) !!}
    
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
    $("form[name=frmAddProgrammeQualification]").on('submit', function(){
        var form = $(this);
        form.find(':submit').attr("disabled", true);
        form.find(':submit').html('<i class="fa fa-spinner fa-spin"></i> Saving');
        return true;
    });
});

function update_programme_qualifcations()
{
    var items = [];
    $("tr[id^=row_]").each(function(index, value){
        var prefix = $(this).attr("id");
        var qualification_id = prefix.split("_")[3];
        var sequence = $("#"+prefix+"_sequence").val();
        var proportion = $("#"+prefix+"_proportion").val();
        var duration = $("#"+prefix+"_duration").val();
        var offset = $("#"+prefix+"_offset").val();

        items.push({
            qualification_id: qualification_id,
            sequence: sequence,
            proportion: proportion,
            duration: duration,
            offset: offset,
        });
    });

    var data = { data: items };

    $("form[name=frmManageProgrammeQualification] input[type=hidden][name=data_to_update]").val($.param(data));
    $("form[name=frmManageProgrammeQualification] input[type=hidden][name=main_aim]").val($('input[type=radio][name=main]:checked').val());
    $("#btnUpdateProgrammeQualifcations").attr("disabled", true);
    $("#btnUpdateProgrammeQualifcations").html('<i class="fa fa-spinner fa-spin"></i> Saving');
    $("form[name=frmManageProgrammeQualification]").submit();
}

$('.frmRemoveQual').submit(function(e) {
    var currentForm = this;
    e.preventDefault();

    bootbox.confirm({
        title: "Confirmation",
        message: "Are you sure you want to remove this qualification?",
        buttons: {
            cancel: {
                label: '<i class="fa fa-times"></i> Cancel',
                className: "btn-sm btn-round",
            },
            confirm: {
                label: '<i class="fa fa-trash"></i> Confirm',
                className: "btn-danger btn-sm btn-round",
            }
        },
        callback: function(result) {
            if (result){
                $(currentForm).find(':submit').attr("disabled", true);
                $(currentForm).find(':submit').attr("title", "Removing qualification ... ");
                $(currentForm).find(':submit').html('<i class="fa fa-spinner fa-spin"></i>');

                currentForm.submit();
            }
        }
    });
});

</script>

@endsection
