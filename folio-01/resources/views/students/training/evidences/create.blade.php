@extends('layouts.master')

@section('title', 'Add New Evidence')

@section('page-plugin-styles')
<link rel="stylesheet" href="{{ asset('assets/css/toastr.min.css') }}" />
<style>

</style>
@endsection

@section('breadcrumbs')
{{ Breadcrumbs::render('students.training.evidences.create', $student, $training_record) }}
@endsection

@section('page-content')
<div class="page-header">
   <h1>Training Record <small>{{ $training_record->start_date }} - {{ $training_record->planned_end_date }} | <span class="label label-md label-info arrowed-in arrowed-in-right">{{ $training_record->status_code }}</span></small></h1>
</div><!-- /.page-header -->
<div class="row">
   <div class="col-xs-12">

    <!-- PAGE CONTENT BEGINS -->

    <div class="row">
        <div class="col-sm-6">
            <div class="widget-box">
                <div class="widget-header">
                    <h5 class="widget-title">Learner Details</h5>
                </div>
                <div class="widget-body">
                    <div class="widget-toolbox padding-8 clearfix">
                        <button class="btn btn-white btn-xs btn-default pull-left btn-round" onclick="window.location.href='{{ route('students.training.show', [$student, $training_record]) }}'">
                            <i class="ace-icon fa fa-arrow-left"></i><span class="bigger-110">Go back</span>
                        </button>
                    </div>
                    <div class="widget-main">
                        <div class="profile-user-info profile-user-info-striped">
                            <div class="profile-info-row">
                                <div class="profile-info-name"> Learner </div>
                                <div class="profile-info-value"><span>{{ $student->full_name }}</span></div>
                            </div>
                            <div class="profile-info-row">
                                <div class="profile-info-name"> Contact </div>
                                <div class="profile-info-value">
                                    <span>
                                        <i class="fa fa-envelope blue bigger-110"></i> {{ $student->primary_email }}
                                        {!! $home_address->telephone != '' ? '<br><i class="fa fa-phone light-orange bigger-110"></i> <span>' . $home_address->telephone . '</span>' : '' !!}
                                        {!! $home_address->mobile != '' ? '<br><i class="fa fa-mobile light-orange bigger-110"></i> <span>' . $home_address->mobile . '</span><br>' : '' !!}
                                    </span>
                                </div>
                            </div>
                            <div class="profile-info-row">
                                <div class="profile-info-name"> Employer </div>
                                <div class="profile-info-value"><span>{{ $student->employer->legal_name }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="widget-box">
                <div class="widget-header">
                    <h5 class="widget-title">Training Details</h5>
                </div>
                <div class="widget-body">
                    <div class="widget-main">
                        <div class="profile-user-info profile-user-info-striped">
                            <div class="profile-info-row">
                                <div class="profile-info-name"> Status </div>
                                <div class="profile-info-value"><span><span class="label label-md label-info arrowed-in arrowed-in-right">{{ $training_record->status_code }}</span></span></div>
                            </div>
                            <div class="profile-info-row">
                                <div class="profile-info-name"> Dates </div>
                                <div class="profile-info-value">
                                    <span>{{ $training_record->start_date }} - {{ $training_record->planned_end_date }}</span>
                                </div>
                            </div>
                            <div class="profile-info-row">
                                <div class="profile-info-name"> Portfolio(s) </div>
                                <div class="profile-info-value">
                                    @foreach($training_record->portfolios AS $portfolio)
                                    <span>{{ $portfolio->qan }} - {{ $portfolio->title }}</span>
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

    <div class="row">
        <div class="col-sm-12">
            <div class="widget-box widget-color-blue2 light-border">
                <div class="widget-header"><h5 class="widget-title">Evidence Details</h5></div>
                <div class="widget-body">
                    <div class="widget-main">
                        <div class="row">
                            {!! Form::open(['url' => route('students.training.evidences.store', [$student, $training_record]), 'class' => 'form-horizontal', 'files' => true]) !!}
                            <div class="col-sm-4">
                                <span class="blue">Evidence Type:</span>
                                <div class="radio">
                                    <label>
                                        <input name="evidence_type" type="radio" class="ace input-lg" value="rowEvidenceFile" checked />
                                        <span class="lbl bigger-110 blue"> File Upload</span>
                                    </label>
                                </div>
                                <div class="radio">
                                    <label>
                                        <input name="evidence_type" type="radio" class="ace input-lg" value="rowEvidenceURL" />
                                        <span class="lbl bigger-110 blue"> External URL</span>
                                    </label>
                                </div>
                                <div class="radio">
                                    <label>
                                        <input name="evidence_type" type="radio" class="ace input-lg" value="rowEvidenceRef" />
                                        <span class="lbl bigger-110 blue"> Reference to evidence</span>
                                    </label>
                                </div>
                            </div>
                            <div class="col-sm-8" style="border-left: 1px solid #333;">
                                <div class="form-group row {{ $errors->has('evidence_file') ? 'has-error' : ''}}" id="rowEvidenceFile">
                                    {!! Form::label('evidence_file', 'File', ['class' => 'col-sm-4 control-label']) !!}
                                    <div class="col-sm-8">
                                        <input type="file" class="form-control" name="evidence_file" id="evidence_file">
                                    </div>
                                </div>
                                <div class="form-group row {{ $errors->has('evidence_url') ? 'has-error' : ''}}" id="rowEvidenceURL" style="display: none;">
                                    {!! Form::label('evidence_url', 'Evidence URL', ['class' => 'col-sm-4 control-label']) !!}
                                    <div class="col-sm-8">
                                        {!! Form::text('evidence_url', null, ['class' => 'form-control', 'id' => 'evidence_url']) !!}
                                        {!! $errors->first('evidence_url', '<p class="text-danger">:message</p>') !!}
                                    </div>
                                </div>
                                <div class="form-group row {{ $errors->has('evidence_ref') ? 'has-error' : ''}}" id="rowEvidenceRef" style="display: none;">
                                    {!! Form::label('evidence_ref', 'Evidence Reference', ['class' => 'col-sm-4 control-label']) !!}
                                    <div class="col-sm-8">
                                        {!! Form::text('evidence_ref', null, ['class' => 'form-control', 'id' => 'evidence_ref']) !!}
                                        {!! $errors->first('evidence_ref', '<p class="text-danger">:message</p>') !!}
                                    </div>
                                </div>
                                <div class="form-group row required {{ $errors->has('evidence_name') ? 'has-error' : ''}}">
                                    {!! Form::label('evidence_name', 'Evidence Name', ['class' => 'col-sm-4 control-label']) !!}
                                    <div class="col-sm-8">
                                        {!! Form::text('evidence_name', null, ['class' => 'form-control', 'required' => 'required', 'id' => 'evidence_name']) !!}
                                        {!! $errors->first('evidence_name', '<p class="text-danger">:message</p>') !!}
                                    </div>
                                </div>
                                <div class="form-group row required {{ $errors->has('evidence_desc') ? 'has-error' : ''}}">
                                    {!! Form::label('evidence_desc', 'Evidence Description', ['class' => 'col-sm-4 control-label']) !!}
                                    <div class="col-sm-8">
                                        {!! Form::textarea('evidence_desc', null, ['class' => 'form-control', 'rows' => '3', 'id' => 'evidence_desc']) !!}
                                        {!! $errors->first('evidence_desc', '<p class="text-danger">:message</p>') !!}
                                    </div>
                                </div>
                                <div class="form-group row {{ $errors->has('learner_comments') ? 'has-error' : ''}}">
                                    {!! Form::label('learner_comments', 'Learner Comments', ['class' => 'col-sm-4 control-label']) !!}
                                    <div class="col-sm-8">
                                        {!! Form::textarea('learner_comments', null, ['class' => 'form-control', 'rows' => '5', 'id' => 'learner_comments']) !!}
                                        {!! $errors->first('learner_comments', '<p class="text-danger">:message</p>') !!}
                                    </div>
                                </div>
                                <div class="form-group row {{ $errors->has('assessment_method') ? 'has-error' : ''}}">
                                    {!! Form::label('assessment_method', 'Assessment Method', ['class' => 'col-sm-4 control-label']) !!}
                                    <div class="col-sm-8">
                                        {!! Form::select('assessment_method', \App\TrainingRecordEvidence::getDDLEvidenceAssessmentMethods(), null, ['class' => 'form-control', 'placeholder' => '']) !!}
                                        {!! $errors->first('assessment_method', '<p class="text-danger">:message</p>') !!}
                                    </div>
                                </div>
                                <div class="form-group row {{ $errors->has('learner_declaration') ? 'has-error' : ''}}">
                                    {!! Form::label('learner_declaration', 'Tick this box to confirm that this is your own work', ['class' => 'col-sm-4 control-label small']) !!}
                                    <div class="col-sm-8">
                                        <div class="checkbox">
                                            <label class="block">
                                            <input name="learner_declaration" type="checkbox" class="ace input-lg" value="1">
                                            <span class="lbl bigger-120"></span>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="clearfix form-actions center">
                                    <button class="btn btn-sm btn-success" type="submit">
                                        <i class="ace-icon fa fa-save bigger-110"></i>Save Evidence
                                    </button>
                                </div>
                            </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
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
@endsection

@section('page-inline-scripts')
<script type="text/javascript">

$('input[type=radio][name=evidence_type]').on('click', function(){
    $('input[type=radio][name=evidence_type]').each(function(){
        $('#'+this.value).hide();
    });
    $('#'+this.value).show();
});

</script>
@endsection

