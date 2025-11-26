@extends('layouts.master')

@section('title', 'Complete Delivery Plan Session')

@section('page-content')
<div class="page-header">
   <h1>Delivery Plan Session Task </h1>
</div><!-- /.page-header -->
<div class="row">
    <div class="col-xs-12">
        <!-- PAGE CONTENT BEGINS -->
        <button class="btn btn-sm btn-white btn-default btn-round" type="button"
            onclick="window.location.href='{{ route('trainings.sessions.show', [$training, $session]) }}'">
            <i class="ace-icon fa fa-times bigger-110"></i> Close
        </button>
        @if(!auth()->user()->isStudent() && !$task->isCompleted())
        <button class="btn btn-xs btn-info btn-round" type="button" 
            onclick="window.location.href='{{ route('trainings.sessions.tasks.edit', [$training, $session, $task]) }}'">
            <i class="ace-icon fa fa-edit bigger-110"></i> Edit
        </button>
        @endif
        @if(!auth()->user()->isStudent() && !$task->isCompleted())
        {!! Form::open([
            'method' => 'DELETE',
            'url' => route('trainings.sessions.tasks.destroy', [$training, $session, $task]),
            'id' => 'frmDeleteSessionTask',
            'style' => 'display: inline;',
            'class' => 'form-inline',
        ]) !!}
        {!! Form::hidden('task_id_to_del', $task->id) !!}
        {!! Form::button('<i class="ace-icon fa fa-trash-o bigger-110"></i> Delete', [
            'data-rel' => 'tooltip',
            'class' => 'btn btn-danger btn-xs btn-round',
            'type' => 'click',
            'id' => 'btnDeleteSessionTask',
        ]) !!}
        {!! Form::close() !!}
        @endif
	    @if(!is_null($linkedEvidenceId))
        <span class="btn btn-info btn-sm btn-round pull-right" onclick="window.open('{{ route('trainings.evidences.show', [$training->id, $linkedEvidenceId]) }}', '_blank')">
            <i class="fa fa-folder-open"></i> View Linked Evidence
        </span>
        @elseif((auth()->user()->isAdmin() || auth()->user()->isAssessor() || auth()->user()->isTutor()) && $task->isCompleted())
        <span class="btn btn-primary btn-sm btn-round pull-right" onclick="window.location.href='{{ route('trainings.tasks_evidences_link.create', [$training, $session, $task]) }}'">
            <i class="fa fa-plus"></i><i class="fa fa-file-text"></i> Create Evidence
        </span>
        @endif

        <div class="hr hr-12 hr-dotted"></div>

        @include('partials.session_message')

        @include('partials.session_error')

        @include('trainings.partials.training_quick_details', ['showOverallPercentage' => true])

        <div class="space-12"></div>

        @include('trainings.sessions.partials.session_detail', ['session' => $session, 'collapse' => true])
        
        @include('trainings.sessions.partials.task_detail', ['session' => $session, 'task' => $task])

        @include('trainings.sessions.tasks.uploaded_files', ['task' => $task])

        @include('trainings.sessions.tasks.task_history', ['task' => $task])

        @if(auth()->user()->isStudent() && $task->isEditableByLearner())
            @include('trainings.sessions.tasks.learner_form', ['training' => $training, 'session' => $session, 'task' => $task])
        @endif

        @if( (auth()->user()->isAssessor() || auth()->user()->isTutor()) && $task->isReadyToAssess() )
            @include('trainings.sessions.tasks.assessor_form', ['training' => $training, 'session' => $session, 'task' => $task])
        @endif



    </div><!-- /.col -->
</div><!-- /.row -->
@endsection


@section('page-plugin-scripts')
<script src="{{ asset('assets/js/jquery.validate.min.js') }}"></script>
<script src="{{ asset('assets/js/jquery-additional-methods.min.js') }}"></script>
@endsection

@push('after-scripts')
<script>
    $("button#btnDeleteSessionTask").on('click', function(e){
        e.preventDefault();

        var form = $(this).closest('form');

        bootbox.confirm({
            title: 'Sure to Remove?',
            message: 'This action is irreversible, are you sure you want to continue?',
            buttons: {
                cancel: {
                    label: '<i class="fa fa-times"></i> Cancel',
                    className: 'btn-xs btn-round'
                },
                confirm: {
                    label: '<i class="fa fa-check-o"></i> Yes Remove',
                    className: 'btn-danger btn-xs btn-round'
                }
            },
            callback: function(result) {
                if (result) {
                    form.submit();
                } 
            }
        });        
    });
</script>
@endpush

