@extends('layouts.master')

@section('title', 'Complete Delivery Plan Session')

@section('page-content')
    <div class="page-header">
        <h1>Delivery Plan Session </h1>
    </div><!-- /.page-header -->
    <div class="row">
        <div class="col-xs-12">
            <!-- PAGE CONTENT BEGINS -->
            <button class="btn btn-sm btn-white btn-default btn-round" type="button"
                    onclick="window.location.href='{{ route('trainings.show', $training) }}'">
                <i class="ace-icon fa fa-times bigger-110"></i> Close
            </button>
            @if (
                !$session->isLocked() &&
                (
                    auth()->user()->isAdmin() || in_array(auth()->user()->id, [$training->primaryAssessor->id, optional($training->secondaryAssessor)->id, $training->tutor])
                )
            )
                <button class="btn btn-sm btn-info btn-round" type="button"
                        onclick="window.location.href='{{ route('trainings.sessions.edit', [$training, $session]) }}'">
                    <i class="ace-icon fa fa-edit bigger-110"></i> Edit
                </button>
            @endif
            @if($session->isLocked() && !auth()->user()->isStudent())
                {!! Form::model($session, [
                    'method' => 'PATCH',
                    'url' => route('trainings.sessions.update', [$training, $session]),
                    'id' => 'frmUnlockSession',
                    'style' => 'display: inline;',
                    'class' => 'form-inline',
                ]) !!}
                {!! Form::hidden('session_id_to_unlock', $session->id) !!}
                {!! Form::hidden('subaction', 'unlock_session') !!}
                {!! Form::button('<i class="ace-icon fa fa-unlock bigger-110"></i> Unlock', [
                    'class' => 'btn btn-primary btn-xs btn-round pull-right',
                    'type' => 'click',
                    'id' => 'btnUnlockSession',
                ]) !!}
                {!! Form::close() !!}
            @endif
            @if(!auth()->user()->isStudent() && ! $session->hasLearnerSigned() && ! $session->hasAssessorSigned())
                {!! Form::open([
                    'method' => 'DELETE',
                    'url' => route('trainings.sessions.destroy', [$training, $session]),
                    'id' => 'frmDeleteSession',
                    'style' => 'display: inline;',
                    'class' => 'form-inline',
                ]) !!}
                {!! Form::hidden('session_id_to_del', $session->id) !!}
                {!! Form::button('<i class="ace-icon fa fa-trash-o bigger-110"></i> Delete', [
                    'data-rel' => 'tooltip',
                    'class' => 'btn btn-danger btn-xs btn-round',
                    'type' => 'click',
                    'id' => 'btnDeleteSession',
                ]) !!}
                {!! Form::close() !!}
            @endif
            <div class="hr hr-12 hr-dotted"></div>

            @include('partials.session_message')

            @include('partials.session_error')

            @include('trainings.partials.training_quick_details', ['showOverallPercentage' => true])

            <div class="space-12"></div>

            @include('trainings.sessions.partials.session_detail', ['session' => $session])

            @if(auth()->user()->isStudent() && ! $session->student_sign && $training->isEditableByStudent())
                @include('trainings.sessions.learner_form')
            @endif

            @if( (auth()->user()->isAssessor() || auth()->user()->isTutor()) && ! $session->assessor_sign )
                @include('trainings.sessions.assessor_form')
            @endif

            <div class="row">
                <div class="col-xs-12">
                    <div class="widget-box transparent">
                        <div class="widget-header">
                            <h5 class="widget-title">
                                Tasks ({{ $session->tasks->count() }})
                            </h5>
                        </div>
                        <div class="widget-body">
                            <div class="widget-main">
                                @if(auth()->user()->isAdmin() || auth()->user()->isAssessor() || auth()->user()->isTutor())
                                        <?php
                                        $programmeSession = \App\Models\Programmes\ProgrammeDeliveryPlanSession::where('programme_id', $training->programme->id)
                                            ->where('session_number', $session->session_number)
                                            ->first();
                                        $taskCount = $programmeSession ? $programmeSession->tasks->count() : 0;
                                        ?>

                                    <button class="btn btn-sm btn-primary btn-round" type="button"
                                            onclick="window.location.href='{{ route('trainings.sessions.tasks.create', [$training, $session]) }}'">
                                        <i class="ace-icon fa fa-plus bigger-110"></i> Add Task
                                    </button>
                                        <?php

                                        ?>

                                    @if($taskCount)
                                        <div class="btn btn-primary btn-sm btn-round"
                                             @if ($taskCount > 0)
                                                 onclick="refreshDeliveryPlanSessionTasksFromProgramme();"
                                             @else
                                                 disabled
                                             title="No tasks found in the programme session. Please add tasks to the programme session first."
                                                @endif
                                        >
                                            <i class="fa fa-refresh"></i> Refresh from Programme session
                                        </div>
                                    @endif
                                @endif
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <tr>
                                            <th style="width: 5%;">#</th>
                                            <th>Action</th>
                                            <th>Details</th>
                                            <th>Start Date</th>
                                            <th>Complete By</th>
                                            <th>Status</th>
                                            <th>Criteria</th>
                                        </tr>
                                        @foreach($session->tasks as $task)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>
                                                    <p>
                                                        <button class="btn btn-xs btn-white btn-info btn-round"
                                                                type="button"
                                                                onclick="window.location.href='{{ route('trainings.sessions.tasks.show', [$training, $session, $task]) }}'">
                                                            <i class="ace-icon fa fa-folder-open bigger-110"></i> View
                                                            Details
                                                        </button>
                                                    </p>
                                                    @if(!auth()->user()->isStudent() && !$task->isCompleted())
                                                        <p>
                                                            <button class="btn btn-xs btn-info btn-round" type="button"
                                                                    onclick="window.location.href='{{ route('trainings.sessions.tasks.edit', [$training, $session, $task]) }}'">
                                                                <i class="ace-icon fa fa-edit bigger-110"></i> Edit
                                                            </button>
                                                        </p>
                                                    @endif
                                                    @if(!auth()->user()->isStudent() && !$task->isCompleted())
                                                        <p>
                                                            {!! Form::open([
                                                                'method' => 'DELETE',
                                                                'url' => route('trainings.sessions.tasks.destroy', [$training, $session, $task]),
                                                                'id' => 'frmDeleteSessionTask',
                                                            ]) !!}
                                                            {!! Form::hidden('task_id_to_del', $task->id) !!}
                                                            {!! Form::button('<i class="ace-icon fa fa-trash-o bigger-110"></i> Delete', [
                                                                'data-rel' => 'tooltip',
                                                                'class' => 'btn btn-danger btn-xs btn-round',
                                                                'type' => 'click',
                                                                'id' => 'btnDeleteSessionTask',
                                                            ]) !!}
                                                            {!! Form::close() !!}
                                                        </p>
                                                    @endif
                                                </td>
                                                <td>
                                                    <span class="text-info bolder">Title: </span><br>{{ $task->title }}
                                                    <br>
                                                    <span class="text-info bolder">Details: </span><br>{!! nl2br(e($task->details)) !!}
                                                </td>
                                                <td>{{ optional($task->start_date)->format('d/m/Y') }}</td>
                                                <td>{{ optional($task->complete_by)->format('d/m/Y') }}</td>
                                                <td>
                                                    @include('trainings.sessions.partials.task_status_label', ['task' => $task])
                                                </td>
                                                <td>
                                                    @php
                                                        if(count($task->pcs()) > 0)
                                                        {
                                                            $taskPcs = App\Models\Training\PortfolioPC::whereIn('id', $task->pcs())->get();
                                                            foreach($taskPcs AS $taskPC)
                                                            {
                                                                echo ($taskPC->reference ? '['.$taskPC->reference.'] ' : '') . nl2br(e($taskPC->title)) . '<hr style="margin-top: 10px; margin-bottom: 10px">';
                                                            }
                                                        }
                                                    @endphp
                                                </td>
                                            </tr>
                                        @endforeach
                                    </table>
                                </div>
                            </div>
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
        $("button#btnDeleteSession, button#btnDeleteSessionTask").on('click', function (e) {
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
                callback: function (result) {
                    if (result) {
                        form.submit();
                    }
                }
            });
        });

        $("button#btnUnlockSession").on('click', function (e) {
            e.preventDefault();

            var form = $(this).closest('form');

            bootbox.confirm({
                title: 'Sure to Unlock?',
                message: 'This action will remove learner and assessor comments and their timestamps, are you sure you want to continue?',
                buttons: {
                    cancel: {
                        label: '<i class="fa fa-times"></i> Cancel',
                        className: 'btn-xs btn-round'
                    },
                    confirm: {
                        label: '<i class="fa fa-check"></i> Yes Unlock',
                        className: 'btn-primary btn-xs btn-round'
                    }
                },
                callback: function (result) {
                    if (result) {
                        form.submit();
                    }
                }
            });
        });



        function refreshDeliveryPlanSessionTasksFromProgramme() {
            <?php
            $inputOptions = [];
            $programmeSession = $training->programme->sessions()->where('session_number', $session->session_number)->first();
            if ($programmeSession && $session->tasks->count()) {
                $tasks = $programmeSession->tasks->pluck('title', 'id');
                foreach ($tasks as $id => $title) {
                    $inputOptions[] = [
                        'text' => $title,
                        'value' => $id,
                    ];
                }
            }
            ?>

            @if(!empty($inputOptions))
            bootbox.prompt({
                title: "Confirmation",
                message: 'This action will refresh selected tasks from the programme session. Are you sure you want to continue?',
                inputType: 'checkbox',
                inputOptions: @json($inputOptions),
                callback: function (result) {
                    if (result != null) {
                        if (result.length) {
                            refreshTasksAjax(result);
                        } else {
                            alert("Select at least one task.")
                            return false;
                        }
                    }
                }
            });
            @else
            bootbox.confirm({
                title: "Confirmation",
                message: 'This action will refresh tasks from the programme session. Are you sure you want to continue?',
                buttons: {
                    cancel: {
                        label: '<i class="fa fa-times"></i> Cancel',
                        className: "btn-sm btn-round",
                    },
                    confirm: {
                        label: '<i class="fa fa-check"></i> Confirm',
                        className: "btn-danger btn-sm btn-round",
                    }
                },
                callback: function (result) {
                    if (result) {
                        refreshTasksAjax();
                    }
                }
            });
            @endif
        }

        function refreshTasksAjax(task_ids = null) {
            $.ajax({
                method: 'POST',
                data: {
                    tr_id: {{ $training->id }},
                    session_id: {{ $session->id }},
                    task_ids: task_ids,
                    "_token": "{{ csrf_token() }}"
                },
                url: "{{ route('trainings.sessions.tasks.refresh') }}",
            }).done(function (response) {
                var status = response.status || null;
                if (status == 'success') {
                    window.location.reload();
                } else if(response.alert) {
                    bootbox.alert(response.alert);
                }
            }).fail(function (jqXHR, textStatus, errorThrown) {
                bootbox.alert(errorThrown, textStatus);
                console.log('here');
            });
        }
    </script>
@endpush

