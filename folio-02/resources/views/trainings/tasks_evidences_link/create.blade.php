@extends('layouts.master')

@section('title', 'Create Evidence from Task')

@section('page-content')
<div class="page-header">
   <h1>Create Evidence from Task </h1>
</div><!-- /.page-header -->
<div class="row">
    <div class="col-xs-12">
        <!-- PAGE CONTENT BEGINS -->
        <button class="btn btn-sm btn-white btn-default btn-round" type="button"
            onclick="window.location.href='{{ route('trainings.sessions.tasks.show', [$training, $session, $task]) }}'">
            <i class="ace-icon fa fa-times bigger-110"></i> Close
        </button>

        <div class="hr hr-12 hr-dotted"></div>

        @include('partials.session_message')

        @include('partials.session_error')

        @include('trainings.partials.training_quick_details', ['showOverallPercentage' => true])

        <div class="space-12"></div>

        @include('trainings.sessions.partials.session_detail', ['session' => $session, 'collapse' => true])
        
        @include('trainings.sessions.partials.task_detail', ['session' => $session, 'task' => $task])

        @include('trainings.sessions.tasks.uploaded_files', ['task' => $task])

        @include('trainings.sessions.tasks.task_history', ['task' => $task])

        <div class="row">
            <div class="col-sm-12 ">
                <div class="widget-box widget-color-green">
                    <div class="widget-header">
                        <h4 class="smaller">Create and Link Evidence</h4>
                    </div>
                    <div class="widget-body">
                        {!! Form::open([
                            'url' => route('trainings.tasks_evidences_link.store', [$training, $session, $task]),
                            'class' => 'form-horizontal',
                            'id' => 'frmEvidence'])
                        !!}
                        {!! Form::hidden('training_id', $training->id) !!}
                        {!! Form::hidden('session_id', $session->id) !!}
                        {!! Form::hidden('task_id', $task->id) !!}

                        <div class="widget-main">
                            <div class="form-group row required {{ $errors->has('evidence_name') ? 'has-error' : ''}}">
                                {!! Form::label('evidence_name', 'Evidence Name', ['class' => 'col-sm-4 control-label']) !!}
                                <div class="col-sm-8">
                                    {!! Form::text('evidence_name', substr($task->title, 0, 250), ['class' => 'form-control', 'id' => 'evidence_name', 'maxlength' => 250, 'required']) !!}
                                    {!! $errors->first('evidence_name', '<p class="text-danger">:message</p>') !!}
                                </div>
                            </div>
                            <div class="form-group row {{ $errors->has('evidence_desc') ? 'has-error' : ''}}">
                                {!! Form::label('evidence_desc', 'Evidence Description', ['class' => 'col-sm-4 control-label']) !!}
                                <div class="col-sm-8">
                                    {!! Form::textarea('evidence_desc', substr($task->details, 0, 500), ['class' => 'form-control', 'rows' => '3', 'id' => 'evidence_desc', 'maxlength' => 500]) !!}
                                    {!! $errors->first('evidence_desc', '<p class="text-danger">:message</p>') !!}
                                </div>
                            </div>
                            <div class="form-group row {{ $errors->has('evidence_categories') ? 'has-error' : ''}}">
                                {!! Form::label('evidence_categories', 'Evidence Categories', ['class' => 'col-sm-4 control-label']) !!}
                                <div class="col-sm-8">
                                    @foreach(App\Models\Lookups\TrainingEvidenceCategoryLookup::getSelectData() AS $categoryKey => $categoryValue)
                                    <div class="col-sm-4">
                                        <div class="control-group">
                                            <div class="checkbox">
                                                <label>
                                                    <input name="evidence_categories[]" class="ace ace-checkbox-2" type="checkbox" value="{{ $categoryKey }}">
                                                    <span class="lbl"> {{ $categoryValue }}</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="form-group row {{ $errors->has('assessor_comments') ? 'has-error' : ''}}">
                                {!! Form::label('assessor_comments', 'Assessor Comments', ['class' => 'col-sm-4 control-label']) !!}
                                <div class="col-sm-8">
                                    {!! Form::textarea(
                                        'assessor_comments', 
                                        optional($task->history()->where('created_by', '!=', $training->id)->latest()->first())->comments, 
                                        ['class' => 'form-control', 'rows' => '10', 'id' => 'assessor_comments']) 
                                    !!}
                                    {!! $errors->first('assessor_comments', '<p class="text-danger">:message</p>') !!}
                                </div>
                            </div>
                        </div>
                        <div class="widget-toolbox padding-8 clearfix">
                            <div class="center">
                                <button id="btnSubmitFrmEvidence" class="btn btn-sm btn-success btn-round"
                                    type="submit">
                                    <i class="ace-icon fa fa-save bigger-110"></i>Save Evidence
                                </button>
                            </div>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>

        

    </div><!-- /.col -->
</div><!-- /.row -->
@endsection


@section('page-plugin-scripts')
<script src="{{ asset('assets/js/jquery.validate.min.js') }}"></script>
<script src="{{ asset('assets/js/jquery-additional-methods.min.js') }}"></script>
@endsection

@push('after-scripts')
<script>
    $("form[id=frmEvidence]").on('submit', function() {
        var form = $(this);
        form.find(':submit').attr("disabled", true);
        form.find(':submit').html('<i class="fa fa-spinner fa-spin"></i> Saving');
        return true;
    });
</script>
@endpush

