@extends('layouts.master')

@section('title', 'Push Learner in Sunesis')

@section('page-content')
    <div class="page-header">
        <h1>
            Push Learner in Sunesis
        </h1>
    </div><!-- /.page-header -->
    <div class="row">
        <div class="col-xs-12">
            <!-- PAGE CONTENT BEGINS -->
            <button class="btn btn-sm btn-white btn-default btn-round" type="button"
                onclick="window.location.href='{{ route('trainings.show', $training) }}'">
                <i class="ace-icon fa fa-arrow-left bigger-110"></i> Cancel
            </button>
            <div class="hr hr-12 hr-dotted"></div>
            @include('partials.session_message')
            @include('partials.session_error')

            <div id="row justify-content-center">
                <div class="col-md-5">
                    <div class="info-div info-div-striped">
                        <div class="info-div-row">
                            <div class="info-div-name"> Learner </div>
                            <div class="info-div-value"><span>{{ $training->student->full_name }}</span></div>
                        </div>
                        <div class="info-div-row">
                            <div class="info-div-name"> Email </div>
                            <div class="info-div-value">
                                <span>
                                    <i class="fa fa-envelope blue bigger-110"></i> {{ $training->student->primary_email }}
                                </span>
                            </div>
                        </div>
                        <div class="info-div-row">
                            <div class="info-div-name"> Employer </div>
                            <div class="info-div-value">
                                <span>
                                    {{ optional($training->employer)->legal_name }}<br>
                                    @include('partials.address_lines', ['address' => $training->location])
                                </span>
                            </div>
                        </div>
                        <div class="info-div-row">
                            <div class="info-div-name"> Training Dates </div>
                            <div class="info-div-value">
                                <span>{{ $training->start_date->format('d/m/Y') }} - {{ $training->planned_end_date->format('d/m/Y') }}</span>
                            </div>
                        </div>
                        <div class="info-div-row">
                            <div class="info-div-name"> Programme </div>
                            <div class="info-div-value">
                                {{ $training->programme->title }} 
                            </div>
                        </div>
                        <div class="info-div-row">
                            <div class="info-div-name"> Primary Assessor </div>
                            <div class="info-div-value">
                                {{ $training->primaryAssessor->full_name }} 
                            </div>
                        </div>
                        <div class="info-div-row">
                            <div class="info-div-name"> Secondary Assessor </div>
                            <div class="info-div-value">
                                {{ optional($training->primaryAssessor)->full_name }} 
                            </div>
                        </div>
                        <div class="info-div-row">
                            <div class="info-div-name"> Verifier </div>
                            <div class="info-div-value">
                                {{ $training->verifierUser->full_name }} 
                            </div>
                        </div>
                    </div>
                </div>
    
                @if(is_null($folioSunesisRecord))
                <div class="col-md-7">
                    {!! Form::open([
                        'method' => 'POST',
                        'url' => route("sunesis.pushLearner", $training),
                        'class' => 'form-horizontal',
                        'role' => 'form',
                        'id' => 'frmSunesisLearner',
                    ]) !!}

                    <div class="widget-box widget-color-green">
                        <div class="widget-header">
                            <div class="widget-title">Select Sunesis Options</div>
                        </div>
                        <div class="widget-body">
                            <div class="widget-main">
				<div class="alert alert-info">
                                    <i class="fa fa-info-circle"></i> Please make sure that the learner is not already in Sunesis. <br>
                                    <i class="fa fa-info-circle"></i> Select the following options and system will create and enrol learner in Sunesis. 
                                </div>
                                <div class="form-group row required {{ $errors->has('ProviderLocationID') ? 'has-error' : '' }}">
                                    {!! Form::label('ProviderLocationID', 'Provider', ['class' => 'col-sm-4 control-label']) !!}
                                    <div class="col-sm-8">
                                        {!! Form::select('ProviderLocationID', [], null, ['class' => 'form-control', 'required', 'placeholder' => '']) !!}
                                        {!! $errors->first('ProviderLocationID', '<p class="text-danger">:message</p>') !!}
                                    </div>
                                </div>
                                <div class="form-group row required {{ $errors->has('EmployerLocationID') ? 'has-error' : '' }}">
                                    {!! Form::label('EmployerLocationID', 'Employer', ['class' => 'col-sm-4 control-label']) !!}
                                    <div class="col-sm-8">
                                        {!! Form::select('EmployerLocationID', [], null, ['class' => 'form-control', 'required', 'placeholder' => '']) !!}
                                        {!! $errors->first('EmployerLocationID', '<p class="text-danger">:message</p>') !!}
                                    </div>
                                </div>
                                <div class="form-group row required {{ $errors->has('CourseID') ? 'has-error' : '' }}">
                                    {!! Form::label('CourseID', 'Course', ['class' => 'col-sm-4 control-label']) !!}
                                    <div class="col-sm-8">
                                        {!! Form::select('CourseID', [], null, ['class' => 'form-control', 'required', 'placeholder' => '']) !!}
                                        {!! $errors->first('CourseID', '<p class="text-danger">:message</p>') !!}
                                    </div>
                                </div>
                                <div class="form-group row required {{ $errors->has('ContractID') ? 'has-error' : '' }}">
                                    {!! Form::label('ContractID', 'Contract', ['class' => 'col-sm-4 control-label']) !!}
                                    <div class="col-sm-8">
                                        {!! Form::select('ContractID', [], null, ['class' => 'form-control', 'required', 'placeholder' => '']) !!}
                                        {!! $errors->first('ContractID', '<p class="text-danger">:message</p>') !!}
                                    </div>
                                </div>
                                <div class="form-group row {{ $errors->has('AssessorID') ? 'has-error' : '' }}">
                                    {!! Form::label('AssessorID', 'Assessor', ['class' => 'col-sm-4 control-label']) !!}
                                    <div class="col-sm-8">
                                        {!! Form::select('AssessorID', [], null, ['class' => 'form-control', 'placeholder' => '']) !!}
                                        {!! $errors->first('AssessorID', '<p class="text-danger">:message</p>') !!}
                                    </div>
                                </div>
                                <div class="form-group row {{ $errors->has('TutorID') ? 'has-error' : '' }}">
                                    {!! Form::label('TutorID', 'Tutor', ['class' => 'col-sm-4 control-label']) !!}
                                    <div class="col-sm-8">
                                        {!! Form::select('TutorID', [], null, ['class' => 'form-control', 'placeholder' => '']) !!}
                                        {!! $errors->first('TutorID', '<p class="text-danger">:message</p>') !!}
                                    </div>
                                </div>
                                <div class="form-group row {{ $errors->has('VerifierID') ? 'has-error' : '' }}">
                                    {!! Form::label('VerifierID', 'Verifier', ['class' => 'col-sm-4 control-label']) !!}
                                    <div class="col-sm-8">
                                        {!! Form::select('VerifierID', [], null, ['class' => 'form-control', 'placeholder' => '']) !!}
                                        {!! $errors->first('VerifierID', '<p class="text-danger">:message</p>') !!}
                                    </div>
                                </div>
                            </div>
                            <div class="widget-toolbox padding-8 clearfix">
                                <div class="center">
                                    <button class="btn btn-sm btn-success btn-round" type="submit"><i
                                            class="ace-icon fa fa-save bigger-110"></i> Push in Sunesis</button>&nbsp; &nbsp;
                                    &nbsp;
                                </div>
                            </div>
                        </div>
                    </div>

                    {!! Form::close() !!}
                </div>
                @else
                <div class="col-md-7">
                    <h3 class="bolder text-info">This record is already linked with Sunesis.</h3>
                    <span class="bolder text-info">Sunesis Learner ID: </span> {{ $folioSunesisRecord->sunesis_learner_id }}<br>
                    <span class="bolder text-info">Sunesis Training ID: </span> {{ $folioSunesisRecord->sunesis_tr_id }}<br>
                    <span class="bolder text-info">Created in Sunesis: </span> {{ \Carbon\Carbon::parse($folioSunesisRecord->created_at)->format('d/m/Y H:i:s') }}<br>
                </div>
                @endif
            </div>


            <!-- PAGE CONTENT ENDS -->
        </div><!-- /.col -->
    </div><!-- /.row -->
@endsection


@section('page-inline-scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    @if(is_null($folioSunesisRecord))
    populateDropdown('ProviderLocationID', 'providers', formatTitleText);
    populateDropdown('EmployerLocationID', 'employers', formatTitleText);
    @endif
});

let coursesPopulated = false;
let contractsPopulated = false;
let assessorsPopulated = false;
let tutorsPopulated = false;
let verifiersPopulated = false;

$('#CourseID').on('mouseover', function() {
    if(!coursesPopulated) {
        populateDropdown('CourseID', 'courses', formatTitleText);
        coursesPopulated = true;
    }
});
$('#ContractID').on('mouseover', function() {
    if(!contractsPopulated) {
        populateDropdown('ContractID', 'contracts&year={{ App\Helpers\AppHelper::getContractYear($training->start_date) }}', formatTitleText);
        contractsPopulated = true;
    }
});
$('#AssessorID').on('mouseover', function() {
    if(!assessorsPopulated) {
        populateDropdown('AssessorID', 'assessors', formatUsersNamesText);
        assessorsPopulated = true;
    }
});
$('#TutorID').on('mouseover', function() {
    if(!tutorsPopulated) {
        populateDropdown('TutorID', 'tutors', formatUsersNamesText);
        tutorsPopulated = true;
    }
});
$('#VerifierID').on('mouseover', function() {
    if(!verifiersPopulated) {
        populateDropdown('VerifierID', 'verifiers', formatUsersNamesText);
        verifiersPopulated = true;
    }
});

function populateDropdown(dropdownId, endpoint, textCallback) {
    fetch(`{{ route('sunesis.fetchOptions') }}?endpoint=${endpoint}`)
        .then(response => response.json())
        .then(data => {
            let dropdown = document.getElementById(dropdownId);
            //dropdown.options.remove(0);
            data.forEach(item => {
                if(item.optgroup !== undefined) {
                    const optgroup = document.createElement('optgroup');
                    optgroup.label = item.optgroup; 
                    item.options.forEach(opt => {
                        let option = document.createElement('option');
                        option.value = opt.id;
                        option.text = opt.address
                        optgroup.appendChild(option);
                    });
                    dropdown.appendChild(optgroup);
                } else {
                    let option = document.createElement('option');
                    option.value = item.id;
                    option.text = textCallback(item);
                    dropdown.appendChild(option);
                }
            });
        })
        .catch(error => {
            console.error(`Error fetching data for ${dropdownId}: `, error);
        });
}

function formatUsersNamesText(item) {
    return item.firstnames + ' ' + item.surname;
}

function formatTitleText(item) {
    return item.title;
}

$("form[id=frmSunesisLearner]").on('submit', function() {
    var form = $(this);
    form.find(':submit').attr("disabled", true);
    form.find(':submit').html('<i class="fa fa-spinner fa-spin"></i> Saving');
    return true;
});


</script>
@endsection