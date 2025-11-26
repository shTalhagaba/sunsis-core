@extends('layouts.master')

@section('title', 'Fetch Learner from Onefile')

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
            Fetch Learner from Onefile
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
                                'url' => route('onefile.searchLearners'), 
                                'class' => 'form-horizontal', 
                                'role' => 'form',
                                'name' => 'frmSearchLearners',
                                'id' => 'frmSearchLearners',
                                ]) !!}
                            <div class="widget-main">
                                <div class="row">
                                    <div class="col-md-4">
                                        {{ Form::label('FirstName', 'First Name', ['class' => 'control-label']) }}
                                        {{ Form::text('FirstName', $FirstName ?? null, ['class' => 'form-control', 'maxlength' => '70']) }}
                                    </div>
                                    <div class="col-md-4">
                                        {{ Form::label('LastName', 'Last Name', ['class' => 'control-label']) }}
                                        {{ Form::text('LastName', $LastName ?? null, ['class' => 'form-control', 'maxlength' => '70']) }}
                                    </div>
                                    <div class="col-md-4">
                                        {{ Form::label('MISID', 'MISID', ['class' => 'control-label']) }}
                                        {{ Form::text('MISID', $MISID ?? null, ['class' => 'form-control', 'maxlength' => '12']) }}
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
                                'url' => route('onefile.fetchLearner'), 
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
                                            <th style="width: 30%">Onefile Learner</th>
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
                                                        <i class="fa fa-check-circle green fa-2x" title="There is already a record with this Onefile ID [{{ $learner->ID }}]"></i>
                                                    @else
                                                    <input type="radio" name="onefileLearnerID" value="{{ $learner->ID }}">
                                                    @endif                                                    
                                                </td>
                                                <td>
                                                    <span class="text-info">ID: </span> {{ $learner->ID }}<br>
                                                    <span class="text-info">First Name: </span> {{ $learner->FirstName ?? '' }}<br>
                                                    <span class="text-info">Last Name: </span> {{ $learner->LastName ?? '' }}<br>
                                                    <span class="text-info">MISID: </span> {{ $learner->MISID ?? '' }}<br>
                                                </td>
                                                <td>
                                                    {!! Form::select('programme_for_' . $learner->ID, $programmes, null, [
                                                        'class' => 'form-control',
                                                        'placeholder' => '',
                                                        'id' => 'programme_for_' . $learner->ID,
                                                    ]) !!}
                                                    {!! $errors->first('programme_for_' . $learner->ID, '<p class="text-danger">:message</p>') !!}
                                                </td>
                                                <td>
                                                    {!! Form::select('assessor_for_' . $learner->ID, $assessors, null, [
                                                        'class' => 'form-control',
                                                        'placeholder' => '',
                                                        'id' => 'assessor_for_' . $learner->ID,
                                                    ]) !!}
                                                    {!! $errors->first('assessor_for_' . $learner->ID, '<p class="text-danger">:message</p>') !!}
                                                </td>
                                                <td>
                                                    {!! Form::select('verifier_for_' . $learner->ID, $verifiers, null, [
                                                        'class' => 'form-control',
                                                        'placeholder' => '',
                                                        'id' => 'verifier_for_' . $learner->ID,
                                                    ]) !!}
                                                    {!! $errors->first('verifier_for_' . $learner->ID, '<p class="text-danger">:message</p>') !!}
                                                </td>
                                                <td>
                                                    {!! Form::select('tutor_for_' . $learner->ID, $tutors, null, [
                                                        'class' => 'form-control',
                                                        'placeholder' => '',
                                                        'id' => 'tutor_for_' . $learner->ID,
                                                    ]) !!}
                                                    {!! $errors->first('tutor_for_' . $learner->ID, '<p class="text-danger">:message</p>') !!}
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
        var firstName = document.getElementById('FirstName').value.trim(); 
        var lastName = document.getElementById('LastName').value.trim(); 
        var misid = document.getElementById('MISID').value.trim(); 
        var errorMessage = document.getElementById('frmSearchLearnersErrMsg'); 
        if (firstName === '' && lastName === '' && misid === '') { 
            event.preventDefault();
            errorMessage.textContent = 'At least one of the following fields is required: First Name, Last Name, or MIS ID.'; 
        } else { 
            errorMessage.textContent = '';
        } 
    });

    document.getElementById('frmFetchLearner').addEventListener('submit', function (event) { 
        var errorMessage = document.getElementById('frmFetchLearnersErrMsg');
        const radioButtons = document.querySelectorAll('input[name="onefileLearnerID"]');
        let isChecked = false;
        let onefileLearnerID = '';

        radioButtons.forEach(radioButton => {
            if (radioButton.checked) {
                isChecked = true;
                onefileLearnerID = radioButton.value;
                return;
            }
        });

        if (!isChecked) {
            event.preventDefault();
            errorMessage.textContent = 'Please select a learner.';
        } 

        if(onefileLearnerID != '')
        {
            var programme = document.getElementById('programme_for_' + onefileLearnerID).value.trim(); 
            var assessor = document.getElementById('assessor_for_' + onefileLearnerID).value.trim(); 
            var verifier = document.getElementById('verifier_for_' + onefileLearnerID).value.trim(); 

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