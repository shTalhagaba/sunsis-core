<div class="row">
    <div class="col-sm-12 ">
        <div class="widget-box">
            <div class="widget-header">
                <h4 class="smaller">Details</h4>
            </div>
            <div class="widget-body">
                <div class="widget-main">
                    @if(isset($task))
                        @if ($task->createdByUser->id !== auth()->user()->id)
                        <div class="alert alert-info">
                            <i class="fa fa-info-circle"></i>
                            This task is created by your
                            {{ strtolower(App\Models\Lookups\UserTypeLookup::getDescription($task->createdByUser->user_type)) }},
                            {{ $task->createdByUser->full_name }}
                        </div>
                        @elseif ($task->createdByUser->id === auth()->user()->id && $task->belongsToUser->id !== auth()->user()->id)
                        <div class="alert alert-info">
                            <i class="fa fa-info-circle"></i>
                            You created this task for your student, {{ $task->belongsToUser->full_name }}
                        </div>
                        @endif
                    @endif
                    <div class="form-group row required {{ $errors->has('completed') ? 'has-error' : ''}}">
                        {!! Form::label('completed', 'Status', ['class' => 'col-sm-4 control-label']) !!}
                        <div class="col-sm-8">
                            {!! Form::select('completed', ['0' => 'Not Completed', '1' => 'Completed'], null, ['class' => 'form-control']) !!}
                            {!! $errors->first('completed', '<p class="text-danger">:message</p>') !!}
                        </div>
                    </div>
                    <div class="form-group row required {{ $errors->has('title') ? 'has-error' : ''}}">
                        {!! Form::label('title', 'Title', ['class' => 'col-sm-4 control-label
                        no-padding-right']) !!}
                        <div class="col-sm-8">
                            {!! Form::text('title', null, ['class' => 'form-control',
                            'required', 'maxlength' => '70']) !!}
                            {!! $errors->first('title', '<p class="text-danger">:message</p>') !!}
                        </div>
                    </div>
                    <div class="form-group row {{ $errors->has('description') ? 'has-error' : ''}}">
                        {!! Form::label('description', 'Description', ['class' => 'col-sm-4 control-label
                        no-padding-right']) !!}
                        <div class="col-sm-8">
                            {!! Form::textarea('description', null, ['class' => 'form-control required',
                            'maxlength' => '255']) !!}
                            {!! $errors->first('description', '<p class="text-danger">:message</p>') !!}
                        </div>
                    </div>
                    @if ( isset($relatedLearners) && (auth()->user()->isAssessor() || auth()->user()->isTutor()) )
                    <div class="form-group row {{ $errors->has('belongs_to') ? 'has-error' : ''}}">
                        {!! Form::label('belongs_to', 'Student', ['class' => 'col-sm-4 control-label']) !!}
                        <div class="col-sm-8">
                            {!! Form::select('belongs_to', $relatedLearners, null, ['class' => 'form-control', 'placeholder' => '']) !!}
                            {!! $errors->first('belongs_to', '<p class="text-danger">:message</p>') !!}
                            <div class="space-2"></div>
                            <span class="text-info small" style="margin-left: 2%"> 
                                <i class="fa fa-info-circle"></i> 
                                Only select, if you're creating this task for one of your students. 
                            </span>
                        </div>
                    </div>
                    @endif
                </div>
                <div class="widget-toolbox padding-8 clearfix">
                    <div class="center">
                        <button class="btn btn-sm btn-success btn-round" type="submit">
                            <i class="ace-icon fa fa-save bigger-110"></i>
                            Save Information
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

