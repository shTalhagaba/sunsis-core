@extends('layouts.master')

@section('title', 'Todo Task')

@section('page-content')
    <div class="page-header">
        <h1>View Todo Task</h1>
    </div><!-- /.page-header -->
    <div class="row">
        <div class="col-xs-12">
            <button class="btn btn-xs btn-white btn-default btn-round" type="button"
                onclick="window.location.href='{{ route('todo_tasks.index') }}'">
                <i class="ace-icon fa fa-times bigger-110"></i> Close
            </button>
            @if ($task->createdByUser->id === auth()->user()->id)
                <button class="btn btn-xs btn-primary btn-bold btn-round" type="button"
                    onclick="window.location.href='{{ route('todo_tasks.edit', ['todo_task' => $task]) }}'">
                    <i class="ace-icon fa fa-edit bigger-120"></i> Edit Todo Task
                </button>
            @endif
            @if($task->createdByUser->id === auth()->user()->id)
            {!! Form::open([
                'method' => 'DELETE',
                'url' => route('todo_tasks.destroy', [$task]),
                'style' => 'display: inline;',
                'class' => 'form-inline frmDeleteTask',
            ]) !!}
            {!! Form::button('<i class="ace-icon fa fa-trash bigger-120"></i> Delete', [
                'class' => 'btn btn-danger btn-xs pull-right btn-round btnDeleteTask',
                'id' => 'btnDeleteTask',
                'type' => 'submit',
                'style' => 'display: inline',
            ]) !!}
            {!! Form::close() !!}
            @endif

            <div class="hr hr-12 hr-dotted"></div>

            @include('partials.session_message')

            <div class="row">
                <div class="col-sm-6">
                    <div class="widget-box transparent">
                        <div class="widget-header">
                            <h5 class="widget-title">Todo Task Details</h5>
                        </div>
                        <div class="widget-body">
                            <div class="widget-main">
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

                                <div class="info-div info-div-striped">
                                    <div class="info-div-row">
                                        <div class="info-div-name"> Title </div>
                                        <div class="info-div-value"><span>{{ $task->title }}</span></div>
                                    </div>
                                    <div class="info-div-row">
                                        <div class="info-div-name"> Description </div>
                                        <div class="info-div-value"><span>{!! nl2br(e($task->description)) !!}</span></div>
                                    </div>
                                    <div class="info-div-row">
                                        <div class="info-div-name"> Status </div>
                                        <div class="info-div-value">
                                            <span class="text-{{ $task->completed ? 'success' : 'primary' }}">
                                                {{ $task->completed ? 'Completed' : 'Active' }}
                                            </span>
                                            @if ($task->createdByUser->id !== auth()->user()->id)
                                            {!! Form::model($task->getAttributes(), [
                                                'method' => 'PATCH',
                                                'url' => route('todo_tasks.update', $task),
                                            ]) !!}
                                                {!! Form::hidden('title', $task->title) !!}
                                                {!! Form::hidden('completed', !$task->completed) !!}
                                                <button class="btn btn-minier btn-primary {{ $task->completed ? 'btn-white' : '' }} btn-round" type="submit">
                                                    Mark as {{ $task->completed ? 'Active' : 'Complete' }}
                                                </button>
                                            {!! Form::close() !!}                                            
                                            @endif 
                                        </div>
                                    </div>
                                    <div class="info-div-row">
                                        <div class="info-div-name"> Created At </div>
                                        <div class="info-div-value">
                                            <span>{{ $task->created_at->format('d/m/Y H:i:s') }}</span>
                                        </div>
                                    </div>
                                    <div class="info-div-row">
                                        <div class="info-div-name"> Updated&nbsp;At </div>
                                        <div class="info-div-value">
                                            <span>{{ $task->updated_at->format('d/m/Y H:i:s') }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <div class="col-sm-6">
                    @if(count($task->communications) > 0)
                    <div class="timeline-container">
                        <div class="timeline-items">
                            @foreach($task->communications AS $comm)
                            <div class="timeline-item clearfix">
                                <div class="timeline-info">
                                    <i class="timeline-indicator ace-icon fa fa-comment btn btn-primary no-hover green"></i>
                                </div>
                                <div class="widget-box transparent">
                                    <div class="widget-header widget-header-small">
                                        <h5 class="widget-title smaller">{{ optional($comm->user)->full_name }}</h5>
                                        <span class="widget-toolbar no-border">
                                            <i class="ace-icon fa fa-clock-o bigger-110"></i>
                                            {{ $comm->created_at->format('d/m/Y H:i:s') }}
                                        </span>
                                    </div>
                                    <div class="widget-body">
                                        <div class="widget-main">
                                            {!! nl2br(e($comm->message)) !!}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div><!-- /.timeline-items -->
                    </div>
                    @else
                    <div class="space-12"></div>
                    <div class="space-12"></div>
                    <div class="space-4"></div>
                    @endif
                    @if ($task->createdByUser->id !== auth()->user()->id || (count($task->communications) > 0))
                    {!! Form::open(['url' => route('todo_tasks.communications.store', $task), 'class' => 'form-horizontal']) !!}
                    <div class="form-group {{ $errors->has('message') ? 'has-error' : ''}}">
                        {!! Form::textarea('message', null, ['class' => 'form-control required', 'maxlength' => '255', 'rows' => 2, 'placeholder' => 'Message box']) !!}
                        {!! $errors->first('message', '<p class="text-danger">:message</p>') !!}
                    </div>
                    <button class="btn btn-xs btn-primary btn-round" type="submit">
                        <i class="ace-icon fa fa-comment bigger-110"></i>
                        Save 
                    </button>
                    {!! Form::close() !!}
                    @endif 
                </div>
            </div>
        </div>        
    </div>
@endsection

@push('after-scripts')
<script>
    $(function(){
        $('#btnDeleteTask').on('click', function(e) {
            e.preventDefault();
            var form = this.closest('form');
            bootbox.confirm({
                title: 'Confirm Delete?',
                message: 'This action is irreversible, are you sure you want to continue?',
                buttons: {
                    cancel: {
                        label: '<i class="fa fa-times"></i> Cancel'
                    },
                    confirm: {
                        label: '<i class="fa fa-trash-o"></i> Yes Delete',
                        className: 'btn-danger'
                    }
                },
                callback: function(result) {
                    if (result) {
                        $('.loader').show();
                        $(form).find(':submit').attr("disabled", true);
                        $(form).find(':submit').html('<i class="fa fa-spinner fa-spin"></i>');
                        form.submit();
                    }
                }
            });
        });
    });
</script>
@endpush
