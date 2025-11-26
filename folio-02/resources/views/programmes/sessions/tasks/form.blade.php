@push('after-styles')
    <style>
        input[type=checkbox] {
            transform: scale(1.4);
        }
    </style>
@endpush

<div class="row">
    <div class="col-sm-12 ">
        <div class="widget-box">
            <div class="widget-header">
                <h4 class="smaller">Task Details</h4>
            </div>
            <div class="widget-body">
                <div class="widget-main">
                    <div class="form-group row required {{ $errors->has('title') ? 'has-error' : ''}}">
                        {!! Form::label('title', 'Task Title', ['class' => 'col-sm-4 control-label']) !!}
                        <div class="col-sm-8">
                            {!! Form::text('title', null, ['class' => 'form-control', 'required', 'maxlength' => '255']) !!}
                            {!! $errors->first('title', '<p class="text-danger">:message</p>') !!}
                        </div>
                    </div>
                    <div class="form-group row required {{ $errors->has('details') ? 'has-error' : '' }}">
                        {!! Form::label('details', 'Details', ['class' => 'col-sm-4 control-label no-padding-right']) !!}
                        <div class="col-sm-8">
                            {!! Form::textarea('details', null, [
                                'class' => 'form-control',
                                'rows' => '15',
                                'id' => 'details',
                            ]) !!}
                            {!! $errors->first('details', '<p class="text-danger">:message</p>') !!}
                        </div>
                    </div>
                    <div class="form-group row required">
                        <div class="col-sm-4 control-label no-padding-right">
                                Status
                        </div>
                        <div class="col-sm-8">
                            <label class="inline">
                                <input type="hidden" name="status" value="0">
                                <input type="checkbox" class="ace" value="1" name="status"
                                       id="status" {{ old('status',(isset($task)? $task->status : 1)) ? 'checked' : '' }} />
                                <span class="lbl"> Active</span>
                            </label>
                        </div>
                    </div>

                    <div class="form-group row {{ $errors->has('programme_dp_task_files') ? 'has-error' : '' }}">
                        {!! Form::label('programme_dp_task_files', 'File / Resource', ['class' => 'col-sm-4 control-label no-padding-right']) !!}
                        <div class="col-sm-8">
                            @include(
                                'partials.ace_file_control',
                                ['aceFileControlRequired' => false, 'aceFileControlId' => 'programme_dp_task_files', 'aceFileControlName' => 'programme_dp_task_files']
                            )
                            {!! $errors->first('programme_dp_task_files', '<p class="text-danger">:message</p>') !!}
                        </div>
                    </div>
                    <div class="form-group row {{ $errors->has('task_pcs') ? 'has-error' : ''}}">
                        {!! Form::label('task_pcs', 'Performance Criteria', ['class' => 'col-sm-3 control-label
                        no-padding-right']) !!}
                        <div class="col-sm-9">
                            @include('programmes.sessions.tasks.pc_selection_table', [
                                'programme' => $programme,
                                'selectedProgrammeQualificationUnitPcIds' => $selectedElements ?? [],
                                'selectedProgrammeQualificationUnitIds' => $selectedElementsUnitIds ?? []
                            ])
                            {!! $errors->first('task_pcs', '<p class="text-danger">:message</p>') !!}
                        </div>
                    </div>


                </div>

                <div class="widget-toolbox padding-8 clearfix">
                    <div class="center">
                        <button class="btn btn-sm btn-round btn-success" type="submit">
                            <i class="ace-icon fa fa-save bigger-110"></i>
                            Save Information
                        </button>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>