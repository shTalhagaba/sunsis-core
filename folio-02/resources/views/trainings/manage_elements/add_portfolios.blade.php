@extends('layouts.master')

@section('title', 'Add Portfolio')

@section('page-plugin-styles')
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

@section('page-content')
<div class="page-header">
    <h1>Add Portfolios
        <small><i class="ace-icon fa fa-angle-double-right"></i> add new portfolio into the training record</small>
    </h1>
</div><!-- /.page-header -->
<div class="row">
    <div class="col-xs-12">
        <button class="btn btn-sm btn-white btn-default btn-round" type="button" onclick="window.location.href='{{ route('trainings.show', $training) }}'">
            <i class="ace-icon fa fa-arrow-left bigger-110"></i> Cancel
        </button>
        <div class="hr hr-12 hr-dotted"></div>

        @include('trainings.partials.training_quick_details', ['showOverallPercentage' => true])

        <div class="space-4"></div>

        @include('partials.session_message')

        @include('partials.session_error')

        <div class="space-4"></div>

        {!! Form::open([
            'url' => route('trainings.portfolios.store', $training),
            'class' => 'form-horizontal',
            'role' => 'form',
            'name' => 'frmAddPortfolios',
            'id' => 'frmAddPortfolios',
            'method' => 'POST'
        ]) !!}
        {!! Form::hidden('training_id', $training->id) !!}
        <div class="row">
            <div class="col-xs-12">
                <div class="alert alert-info">
                    <i class="fa fa-info-circle"></i> Following qualifications are available to add in this training record.
                    You can select which one(s) to add and their optional units.
                </div>
                @foreach ($availableQualifications as $programmeQualification)
                @include('students.enrolment.partials.qualification_selection_panel', [
                    'programmeQualification' => $programmeQualification,
                    'programmeQualificationStartDate' => $training->start_date,
                    'programmeQualificationPlannedEndDate' => $training->planned_end_date,
                    'tutors' => $tutors,
                ])
                <div class="space-6"></div>
                @endforeach
            </div>
            <div class="col-xs-12 form-actions center">
                <button class="btn btn-sm btn-primary btn-round" type="submit" {{ count($availableQualifications) == 0 ? 'disabled' : '' }}>
                    <i class="ace-icon fa fa-save bigger-110"></i> Add Selected Qualifications
                </button> &nbsp; &nbsp; &nbsp;
            </div>
        </div>
        {!! Form::close() !!}
        
    </div><!-- /.col -->
</div><!-- /.row -->
@endsection


@section('page-plugin-scripts')
@endsection

@section('page-inline-scripts')

<script type="text/javascript">
$(function(){

    $("form[name=frmAddPortfolios]").on('submit', function(){
        var form = $(this);
        form.find(':submit').attr("disabled", true);
        form.find(':submit').html('<i class="fa fa-spinner fa-spin"></i> Saving');
        return true;
    });

});

</script>

@endsection
