<div class="row">
    <div class="col-xs-12">
        {!! Form::model($task->getAttributes(), [
            'url' => route('trainings.sessions.tasks.save_learner_work', [$training, $session, $task]),
            'class' => 'form-horizontal',
            'role' => 'form',
            'files' => true,
            'id' => 'frmSessionTaskUploadEvidence']) !!}

        {!! Form::hidden('id', $task->id) !!}
        {!! Form::hidden('tr_id', $training->id) !!}
        {!! Form::hidden('dp_session_id', $session->id) !!}
        {!! Form::hidden('file_upload_only', 1) !!}

        <div class="widget-box widget-color-green">
            <div class="widget-header"><h4 class="widget-title">Upload Evidence</h4></div>
            <div class="widget-body">
                <div class="widget-main">
                    <p class="text-center text-info">
                        <i class="fa fa-info-circle"></i> 
                        You can upload <strong>evidences</strong> for this task here. You can upload multiple files one by one.
                    </p>
                    <div class="form-group row {{ $errors->has('tr_task_evidence') ? 'has-error' : '' }}">
                        {!! Form::label('tr_task_evidence', 'Evidence', ['class' => 'col-sm-4 control-label no-padding-right']) !!}
                        <div class="col-sm-8">
                            @include(
                                'partials.ace_file_control',
                                ['aceFileControlRequired' => false, 'aceFileControlId' => 'tr_task_evidence', 'aceFileControlName' => 'tr_task_evidence']
                            )
                            {!! $errors->first('tr_task_evidence', '<p class="text-danger">:message</p>') !!}
                        </div>
                    </div>
                    <p class="text-center text-info">
                        <i class="fa fa-info-circle"></i> 
                        Please click on 'Upload Evidence' to upload your selected file in this panel.
                    </p>
                </div>
                <div class="widget-toolbox clearfix">
                    <div class="center">
                        <button class="btn btn-xs btn-success btn-round" type="submit">
                            <i class="ace-icon fa fa-save bigger-110"></i> Upload Evidence
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {!! Form::close() !!}
    </div>
 </div> 

<div class="row" style="margin-top: 2%">
    <div class="col-xs-12">
        {!! Form::model($task->getAttributes(), [
            'url' => route('trainings.sessions.tasks.save_learner_work', [$training, $session, $task]),
            'class' => 'form-horizontal',
            'role' => 'form',
            'id' => 'frmSessionTask']) !!}

        {!! Form::hidden('id', $task->id) !!}
        {!! Form::hidden('tr_id', $training->id) !!}
        {!! Form::hidden('dp_session_id', $session->id) !!}

        <div class="widget-box widget-color-green">
            <div class="widget-header"><h4 class="widget-title">Submit Task</h4></div>
            <div class="widget-body">
                <div class="widget-main">
                    <p class="text-center text-info">
                        <i class="fa fa-info-circle"></i> 
                        If all the required evidences are uploaded, then you can enter your comments and submit the task for assessment.
                    </p>
                    <div class="form-group row required {{ $errors->has('comments') ? 'has-error' : ''}}">
                        {!! Form::label('comments', 'Enter Your Comments', ['class' => 'col-sm-4 control-label no-padding-right']) !!}
                        <div class="col-sm-8">
                            {!! Form::textarea('comments', null, ['class' => 'form-control', 'required']) !!}
                            {!! $errors->first('comments', '<p class="text-danger">:message</p>') !!}
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-sm-8 col-sm-offset-4">
                            <div class="control-group">
                                <div class="checkbox">
                                    <label>
                                        <input name="learner_signed"  type="checkbox" value="1" required >
                                        <span class="lbl bolder"> &nbsp; Tick this option to confirm your signature if you have completed this task.</span>
                                        <div class="space-2"></div>
                                        <span class="text-info small" style="margin-left: 2%"> 
                                            &nbsp; <i class="fa fa-info-circle"></i> 
                                            After you tick this option and save then this taks will be locked for further changes. 
                                        </span>
                                    </label>
                                </div>
                            </div>
                            <br>
                            {!! $errors->first('learner_signed', '<p class="text-danger">:message</p>') !!}
                        </div>
                    </div>
                </div>
                <div class="widget-toolbox padding-8 clearfix">
                    <div class="center">
                        <button class="btn btn-sm btn-success btn-round" type="submit">
                            <i class="ace-icon fa fa-save bigger-110"></i> Save Information
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        {!! Form::close() !!}
    </div>
</div>


@section('page-inline-scripts')

<script type="text/javascript">
    $(function(){
        
    });
</script>

@endsection
