@php
    $startDate = isset($event) ? \Carbon\Carbon::parse($event->start)->format('Y-m-d') : null;
    $startTime = isset($event) ? \Carbon\Carbon::parse($event->start)->format('H:i') : null;
    $endDate = isset($event) ? \Carbon\Carbon::parse($event->end)->format('Y-m-d') : null;
    $endTime = isset($event) ? \Carbon\Carbon::parse($event->end)->format('H:i') : null;
    $participants = isset($event) ? $event->participants()->pluck('user_id')->toArray() : [];
    
@endphp
<div class="row">

    <div class="col-xs-12 ">
        <div class="widget-box widget-color-green">
            <div class="widget-header">
                <h4 class="smaller">
                    @if(@$event)
                        {{ $event->type === 'task' ? 'Task Details' : 'Event Details' }}
                    @else
                         {{ auth()->user()->isQualityManager() ? 'Event/Task Details' : 'Event Details' }}
                    @endif
                </h4>
            </div>
            <div class="widget-body">
                <div class="widget-main">

                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group row {{ $errors->has('color') ? 'has-error' : '' }}">
                                {!! Form::label('color', 'Color', ['class' => 'col-sm-4 control-label no-padding-right']) !!}
                                <div class="col-sm-8">
                                    {!! Form::text('color', null, ['class' => 'colorpicker form-control', 'placeholder' => '']) !!}
                                    {!! $errors->first('color', '<p class="text-danger">:message</p>') !!}
                                </div>
                            </div>
                        @if (auth()->user()->isQualityManager())
                            <div class="form-group row required">
                                {!! Form::label('type', 'Type', ['class' => 'col-sm-4 control-label']) !!}
                                <div class="col-sm-8">
                                    {!! Form::select(
                                        'type',
                                        ['task' => 'Task', 'event' => 'Event'],
                                        old('type', $event->type ?? 'event'),
                                        [
                                            'class' => 'form-control',
                                            'id' => 'typeSelect',
                                            'required',
                                            $mode === 'edit' ? 'disabled' : '',
                                        ]
                                    ) !!}

                                    @if ($mode === 'edit')
                                        {!! Form::hidden('type', $event->type ?? 'event') !!}
                                    @endif
                                </div>
                            </div>
                        @endif
                            <div class="form-group row required {{ $errors->has('title') ? 'has-error' : '' }}">
                                {!! Form::label('title', 'Title', ['class' => 'col-sm-4 control-label no-padding-right']) !!}
                                <div class="col-sm-8">
                                    {!! Form::text('title', null, ['class' => 'form-control  inputLimiter', 'required', 'maxlength' => '250']) !!}
                                    {!! $errors->first('title', '<p class="text-danger">:message</p>') !!}
                                </div>
                            </div>
                            <div class="form-group row required {{ $errors->has('start_date') ? 'has-error' : '' }}">
                                {!! Form::label('start_date', 'Start Date', ['class' => 'col-sm-4 control-label']) !!}
                                <div class="col-sm-8">
                                    {!! Form::date('start_date', $startDate, ['class' => 'form-control', 'required']) !!}
                                    {!! $errors->first('start_date', '<p class="text-danger">:message</p>') !!}
                                </div>
                            </div>
                            <div class="form-group row required {{ $errors->has('start_time') ? 'has-error' : '' }}">
                                {!! Form::label('start_time', 'Start Time', ['class' => 'col-sm-4 control-label']) !!}
                                <div class="col-sm-8">
                                    {!! Form::time('start_time', $startTime, ['class' => 'form-control', 'required']) !!}
                                    {!! $errors->first('start_time', '<p class="text-danger">:message</p>') !!}
                                </div>
                            </div>
                            <div class="form-group row required {{ $errors->has('end_date') ? 'has-error' : '' }}">
                                {!! Form::label('end_date', 'End Date', ['class' => 'col-sm-4 control-label']) !!}
                                <div class="col-sm-8">
                                    {!! Form::date('end_date', $endDate, ['class' => 'form-control', 'required']) !!}
                                    {!! $errors->first('end_date', '<p class="text-danger">:message</p>') !!}
                                </div>
                            </div>
                            <div class="form-group row required {{ $errors->has('end_time') ? 'has-error' : '' }}">
                                {!! Form::label('end_time', 'End Time', ['class' => 'col-sm-4 control-label']) !!}
                                <div class="col-sm-8">
                                    {!! Form::time('end_time', $endTime, ['class' => 'form-control', 'required']) !!}
                                    {!! $errors->first('end_time', '<p class="text-danger">:message</p>') !!}
                                </div>
                            </div>
                            <div id="eventType" class="form-group row required {{ $errors->has('event_type') ? 'has-error' : '' }}">
                                {!! Form::label('event_type', 'Event Type', ['class' => 'col-sm-4 control-label']) !!}
                                <div class="col-sm-8">
                                    {!! Form::select('event_type', \App\Helpers\AppHelper::getUserEventsTypes(), null, [
                                        'class' => 'form-control',
                                        'required',
                                        'placeholder' => '',
                                    ]) !!}
                                    {!! $errors->first('event_type', '<p class="text-danger">:message</p>') !!}
                                </div>
                            </div>

                            <div id="taskType" class="form-group row required hidden">
                                {!! Form::label('task_type', 'Task Type', ['class' => 'col-sm-4 control-label']) !!}
                                <div class="col-sm-8">
                                    {!! Form::select('task_type', \App\Helpers\AppHelper::getUserTaskTypes(), null, [
                                        'class' => 'form-control',
                                        'id' => 'taskTypeSelect',
                                        'placeholder' => 'Select Task Type',
                                    ]) !!}
                                </div>
                            </div>

                            <div id="assignIqa" class="form-group row required hidden">
                                {!! Form::label('assign_iqa_id', 'Assign IQA', ['class' => 'col-sm-4 control-label']) !!}
                                <div class="col-sm-8">
                                    {!! Form::select('assign_iqa_id[]', $verifiers, null, [
                                        'id' => 'assign_iqa_id',
                                        'class' => 'form-control select2',
                                        'multiple' => true,
                                    ]) !!}
                                </div>
                            </div>
                            <div id="location" class="form-group row {{ $errors->has('location') ? 'has-error' : '' }}">
                                {!! Form::label('location', 'Location', ['class' => 'col-sm-4 control-label no-padding-right']) !!}
                                <div class="col-sm-8">
                                    {!! Form::textarea('location', null, ['class' => 'form-control  inputLimiter', 'maxlength' => '250', 'rows' => 2]) !!}
                                    {!! $errors->first('location', '<p class="text-danger">:message</p>') !!}
                                </div>
                            </div>
                            @if ($mode == 'create')
                                {!! Form::hidden('event_status', \App\Models\UserEvents\UserEvent::STATUS_BOOKED, ['id' => 'eventStatusInput']) !!}
                                {!! Form::hidden('task_status', \App\Models\UserEvents\UserEvent::STATUS_ASSIGNED, ['id' => 'taskStatusInput']) !!}
                            @elseif( $event->type === "event")
                                <div class="form-group row required {{ $errors->has('event_status') ? 'has-error' : '' }}">
                                    {!! Form::label('event_status', 'Event Status', ['class' => 'col-sm-4 control-label']) !!}
                                    <div class="col-sm-8">
                                        {!! Form::select('event_status', \App\Helpers\AppHelper::getUserEventsStatus(), $event->event_status, [
                                            'class' => 'form-control',
                                            'required',
                                            'placeholder' => '',
                                        ]) !!}
                                        {!! $errors->first('event_status', '<p class="text-danger">:message</p>') !!}
                                    </div>
                                </div>
                            @endif
                            <div id="personal" class="form-group row {{ $errors->has('personal') ? 'has-error' : '' }}">
                                {!! Form::label('personal', 'Personal', ['class' => 'col-sm-4 control-label']) !!}
                                <div class="col-sm-8">
                                    {!! Form::select('personal', ['0' => 'No', '1' => 'Yes'], null, ['class' => 'form-control', 'required']) !!}
                                    {!! $errors->first('personal', '<p class="text-danger">:message</p>') !!}
                                </div>
                            </div>
                        </div>                        
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="form-group row required {{ $errors->has('description') ? 'has-error' : '' }}">
                                {!! Form::label('description', 'Detail *', ['class' => 'col-sm-12']) !!}
                                <div class="col-sm-12">
                                    {!! Form::textarea('description', null, [
                                        'class' => 'form-control inputLimiter',
                                        'maxlength' => 800,
                                        'rows' => 5,
                                    ]) !!}
                                    {!! $errors->first('description', '<p class="text-danger">:message</p>') !!}
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="widget-toolbox padding-8 clearfix">
                    <div class="center">

                        <button class="btn btn-sm btn-round btn-success" type="submit">
                            <i class="ace-icon fa fa-save bigger-110"></i>
                            Save
                        </button>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

