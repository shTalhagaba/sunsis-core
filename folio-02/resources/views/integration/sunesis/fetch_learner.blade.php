@extends('layouts.master')

@section('title', 'Fetch Learner from Sunesis')

@section('page-inline-styles')
<style>
    input[type=radio] {
        transform: scale(1.4);
    }
</style>
@endsection

@section('page-content')
    <div class="page-header">
        <h1>
            Fetch Learner from Sunesis
        </h1>
    </div><!-- /.page-header -->
    <div class="row">
        <div class="col-xs-12">
            <!-- PAGE CONTENT BEGINS -->
            <button class="btn btn-sm btn-white btn-default btn-round" type="button"
                onclick="">
                <i class="ace-icon fa fa-arrow-left bigger-110"></i> Cancel
            </button>
            <div class="hr hr-12 hr-dotted"></div>
            @include('partials.session_message')
            @include('partials.session_error')

            <div id="row justify-content-center">
                <div class="col-sm-8 col-sm-offset-2">
                    <div class="widget-box">
                        <div class="widget-header"><h5 class="widget-title">Enter Learner Information</h5></div>
                        <div class="widget-body">
                            {!! Form::open([
                                'url' => route('sunesis.searchLearners'), 
                                'class' => 'form-horizontal', 
                                'role' => 'form',
                                'name' => 'frmSearchLearners',
                                'id' => 'frmSearchLearners',
                                ]) !!}
                            <div class="widget-main">
                                <div class="row">
                                    <div class="col-md-4">
                                        {{ Form::label('firstnames', 'First Name', ['class' => 'control-label']) }}
                                        {{ Form::text('firstnames', $FirstName ?? null, ['class' => 'form-control', 'maxlength' => '70']) }}
                                    </div>
                                    <div class="col-md-4">
                                        {{ Form::label('surname', 'Surname', ['class' => 'control-label']) }}
                                        {{ Form::text('surname', $LastName ?? null, ['class' => 'form-control', 'maxlength' => '70']) }}
                                    </div>
                                    <div class="col-md-4">
                                        {{ Form::label('username', 'Sunesis Username', ['class' => 'control-label']) }}
                                        {{ Form::text('username', $SunesisUsername ?? null, ['class' => 'form-control', 'maxlength' => '45']) }}
                                    </div>
                                    <div class="col-md-4">
                                        {{ Form::label('tr_id', 'Sunesis Training ID', ['class' => 'control-label']) }}
                                        {{ Form::number('tr_id', $SunesisTrainingID ?? null, ['class' => 'form-control']) }}
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="text-danger text-center" id="frmSearchLearnersErrMsg"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="widget-toolbox clearfix">
                                <div class="center">
                                    <button class="btn btn-xs btn-success btn-round" type="submit">
                                        <i class="ace-icon fa fa-search bigger-110"></i>
                                        Search
                                    </button>
                                </div>
                            </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
                
                <div class="col-sm-12" style="margin-top: 2%">
                    <div class="widget-box">
                        <div class="widget-header"><h5 class="widget-title">Select Learner to Download</h5></div>
                        <div class="widget-body">
                            {!! Form::open([
                                'url' => route('sunesis.fetchLearner'), 
                                'class' => 'form-horizontal', 
                                'role' => 'form',
                                'name' => 'frmFetchLearner',
                                'id' => 'frmFetchLearner',
                                ]) !!}
                            <div class="widget-main table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Select</th>
                                            <th style="width: 30%">Sunesis Learner</th>
                                            <th style="width: 20%">Programme *</th>
                                            <th style="width: 15%">Assessor *</th>
                                            <th style="width: 15%">Verifier *</th>
                                            <th style="width: 15%">Tutor</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if(is_array($learnersResult) && count($learnersResult) > 0)
                                            @foreach($learnersResult AS $learner)
                                            <tr>
                                                <td align="center">
                                                    @if ($learner->AlreadyLinked == 1)
                                                        <i class="fa fa-check-circle green fa-2x" title="This is a linked record [Sunesis Username: {{ $learner->username }}, Sunesis Training ID: {{ $learner->tr_id }}]"></i>
                                                    @else
                                                    <input type="radio" name="sunesisTrainingID" value="{{ $learner->tr_id }}">
                                                    @endif                                                    
                                                </td>
                                                <td>
                                                    <span class="text-info">Sunesis Username: </span> {{ $learner->username }}<br>
                                                    <span class="text-info">Sunesis Training ID: </span> {{ $learner->tr_id }}<br>
                                                    <span class="text-info">First Name: </span> {{ $learner->firstnames ?? '' }}<br>
                                                    <span class="text-info">Surname: </span> {{ $learner->surname ?? '' }}<br>
                                                    <span class="text-info">Employer: </span> {{ $learner->legal_name ?? '' }}<br>
                                                    <span class="text-info">Assessor: </span> {{ $learner->primary_assessor ?? '' }}<br>
                                                    <span class="text-info">Verifier/IQA: </span> {{ $learner->iqa ?? '' }}<br>
                                                    <span class="text-info">Programme: </span> {{ $learner->framework_title ?? '' }}<br>
                                                    <span class="text-info">Start Date: </span> {{ Carbon\Carbon::parse($learner->start_date)->format('d/m/Y') ?? '' }}<br>
                                                    <span class="text-info">Planned End Date: </span> {{ Carbon\Carbon::parse($learner->planned_end_date)->format('d/m/Y') ?? '' }}<br>
                                                    <span class="text-info">Training Status: </span> {{ $learner->training_status ?? '' }}<br>
                                                </td>
                                                <td>
                                                    {!! Form::select('programme_for_' . $learner->tr_id, $programmes, $programmesIdsToSelect[$learner->framework_id] ?? null, [
                                                        'class' => 'form-control',
                                                        'placeholder' => '',
                                                        'id' => 'programme_for_' . $learner->tr_id,
                                                    ]) !!}
                                                    {!! $errors->first('programme_for_' . $learner->tr_id, '<p class="text-danger">:message</p>') !!}
                                                </td>
                                                <td>
                                                    {!! Form::select('assessor_for_' . $learner->tr_id, $assessors, $usersIdsToSelect[$learner->assessor_id] ?? null, [
                                                        'class' => 'form-control',
                                                        'placeholder' => '',
                                                        'id' => 'assessor_for_' . $learner->tr_id,
                                                    ]) !!}
                                                    {!! $errors->first('assessor_for_' . $learner->tr_id, '<p class="text-danger">:message</p>') !!}
                                                </td>
                                                <td>
                                                    {!! Form::select('verifier_for_' . $learner->tr_id, $verifiers, $usersIdsToSelect[$learner->verifier_id] ?? null, [
                                                        'class' => 'form-control',
                                                        'placeholder' => '',
                                                        'id' => 'verifier_for_' . $learner->tr_id,
                                                    ]) !!}
                                                    {!! $errors->first('verifier_for_' . $learner->tr_id, '<p class="text-danger">:message</p>') !!}
                                                </td>
                                                <td>
                                                    {!! Form::select('tutor_for_' . $learner->tr_id, $tutors, $usersIdsToSelect[$learner->tutor_id] ?? null, [
                                                        'class' => 'form-control',
                                                        'placeholder' => '',
                                                        'id' => 'tutor_for_' . $learner->tr_id,
                                                    ]) !!}
                                                    {!! $errors->first('tutor_for_' . $learner->tr_id, '<p class="text-danger">:message</p>') !!}
                                                </td>
                                            </tr>
                                            @endforeach
                                        @elseif($noRecordFound)
                                        <tr>
                                            <td colspan="6" align="center">
                                                <span class="text-info bolder">{!! $noRecordFound ? '<i class="fa fa-info-circle"></i> No records found.' : '' !!}</span>
                                            </td>
                                        </tr>
                                        @endif
                                    </tbody>
                                </table>
                                <div class="text-danger text-center" id="frmFetchLearnersErrMsg"></div>
                            </div>
                            <div class="widget-toolbox clearfix">
                                <div class="center">
                                    <button class="btn btn-xs btn-success btn-round" type="submit">
                                        Download Learner
                                    </button>
                                </div>
                            </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
            </div>


            <!-- PAGE CONTENT ENDS -->
        </div><!-- /.col -->
    </div><!-- /.row -->
@endsection


@section('page-inline-scripts')
<script>
    document.getElementById('frmSearchLearners').addEventListener('submit', function (event) { 
        var firstName = document.getElementById('firstnames').value.trim(); 
        var lastName = document.getElementById('surname').value.trim(); 
        var username = document.getElementById('username').value.trim(); 
        var tr_id = document.getElementById('tr_id').value.trim(); 
        var errorMessage = document.getElementById('frmSearchLearnersErrMsg'); 
        if (firstName === '' && lastName === '' && username === '' && tr_id === '') { 
            event.preventDefault();
            errorMessage.textContent = 'At least one of the following fields is required: First Name, Last Name, Sunesis Username or Sunesis Training ID.'; 
        } else { 
            errorMessage.textContent = '';
        } 
    });

    document.getElementById('frmFetchLearner').addEventListener('submit', function (event) { 
        var errorMessage = document.getElementById('frmFetchLearnersErrMsg');
        const radioButtons = document.querySelectorAll('input[name="sunesisTrainingID"]');
        let isChecked = false;
        let sunesisTrainingID = '';

        radioButtons.forEach(radioButton => {
            if (radioButton.checked) {
                isChecked = true;
                sunesisTrainingID = radioButton.value;
                return;
            }
        });

        if (!isChecked) {
            event.preventDefault();
            errorMessage.textContent = 'Please select a learner.';
        } 

        if(sunesisTrainingID != '')
        {
            var programme = document.getElementById('programme_for_' + sunesisTrainingID).value.trim(); 
            var assessor = document.getElementById('assessor_for_' + sunesisTrainingID).value.trim(); 
            var verifier = document.getElementById('verifier_for_' + sunesisTrainingID).value.trim(); 

            var errorMessage = document.getElementById('frmFetchLearnersErrMsg'); 
            if (programme === '' || assessor === '' || verifier === '') { 
                event.preventDefault();
                errorMessage.textContent = 'Please select programme, assessor and verifier for the selected learner.'; 
            } else { 
                errorMessage.textContent = '';
            }
        }
    });
</script>
@endsection