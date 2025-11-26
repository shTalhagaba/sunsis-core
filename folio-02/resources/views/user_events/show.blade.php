@extends('layouts.master')
@section('title', 'Event')
@section('page-plugin-styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
@endsection

@php
    $statusColor = $event->task_status == App\Models\UserEvents\UserEvent::STATUS_SIGNOFF ? '#87B87F' : // green
    ($event->task_status == App\Models\UserEvents\UserEvent::STATUS_COMPLETED ? '#FFB752' : // red
    ($event->task_status == App\Models\UserEvents\UserEvent::STATUS_ASSIGNED ? '#428BCA' : // blue
    '#ffc107')); 
@endphp

@section('page-content')
    <div class="page-header">
        <h1>{{ $event->type === 'task' ? 'View Task' : 'View Event' }}</h1>
    </div><!-- /.page-header -->
    <div class="row">
        <div class="col-xs-12">
            <!-- PAGE CONTENT BEGINS -->
            <div class="btn-toolbar" role="toolbar">

                {{-- Left side group (Close, Mark as Completed, Edit) --}}
                <div class="btn-toolbar" role="toolbar">

                    <div class="btn-group">
                        {{-- Close Button --}}
                        <button 
                            class="btn btn-sm btn-white btn-default btn-round" 
                            type="button"
                            onclick="window.location.href='{{ $event->type === 'task' ? route('home') : route('user_events.index') }}'">
                            <i class="ace-icon fa fa-times bigger-110"></i> Close
                        </button>

                        {{-- Mark as Completed --}}
                        @if ($event->task_status === 1 && !auth()->user()->isQualityManager())
                            <button 
                                type="button" 
                                class="btn btn-sm btn-success btn-bold btn-round" 
                                style="margin-left:5px"
                                onclick="updateTaskStatus({{ $event->id }}, 3)">
                                <i class="ace-icon fa fa-check bigger-120"></i> Mark as Completed
                            </button>
                        @endif

                        {{-- Edit Button --}}
                        @if ($event->creator->id == auth()->id())
                            @if (($event->type === 'task' && $event->task_status === 1) || $event->type === 'event')
                                <button class="btn btn-sm btn-primary btn-bold btn-round" 
                                    type="button" onclick="window.location.href='{{ route('user_events.edit', $event) }}'">
                                        <i class="ace-icon fa fa-edit bigger-120"></i>
                                    {{ $event->type === 'task' ? 'Edit Task' : 'Edit Event' }}
                                </button>
                            @endif

                            @if ($event->task_status === 3)
                                <button 
                                    type="button" 
                                    class="btn btn-sm btn-success btn-bold btn-round" 
                                    style="margin-left:5px"
                                    onclick="updateTaskStatus({{ $event->id }}, 4)">
                                    <i class="ace-icon fa fa-check bigger-120"></i> Sign Off
                                </button>
                            @endif
                        @endif
                    </div>

                    {{-- Right side group (Delete button only) --}}
                    @if ($event->creator->id === auth()->id() && $event->canBeDeleted())
                        <div class="btn-group pull-right">
                            {!! Form::open([
                                'method' => 'DELETE',
                                'url' => route('user_events.destroy', $event),
                                'style' => 'display:inline;',
                                'class' => 'form-inline frmDeleteEvent'
                            ]) !!}
                                {!! Form::button(
                                    '<i class="ace-icon fa fa-trash bigger-120"></i>' . 
                                    ($event->isAssigned() ? 'Delete Task' : 'Delete Event'),
                                    [
                                        'class' => 'btn btn-danger btn-xs btn-round btnDeleteEvent',
                                        'id' => 'btnDeleteEvent' . $event->id,
                                        'type' => 'submit',
                                        'data-type' => $event->isAssigned() ? 'task' : 'event',
                                    ]
                                ) !!}
                            {!! Form::close() !!}
                        </div>
                    @endif
                </div>

        
            <div class="hr hr-12 hr-dotted"></div>

            @include('partials.session_message')

            @include('partials.session_error')

            <div class="row">
                <div class="col-sm-6">
                    <div class="widget-box transparent">
                        <div class="widget-header">
                            <h5 class="widget-title">{{ $event->type === 'task' ? 'Task Details' : 'Event Details' }}</h5>
                        </div>
                        <div class="widget-body">
                            <div class="widget-main">
                                <div class="info-div info-div-striped">
                                    <div class="info-div-row">
                                        <div class="info-div-name"> Color </div>
                                        <div class="info-div-value">
                                            <span>
                                                <span style="background-color: {{ $event->color }}"> &nbsp; &nbsp; &nbsp; &nbsp;
                                                    &nbsp; &nbsp; </span>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="info-div-row">
                                        <div class="info-div-name"> Created By </div>
                                        <div class="info-div-value">
                                            <span>{{ $event->creator->full_name }}
                                                ({{ $event->creator->systemUserType->description }})</span>
                                        </div>
                                    </div>
                                    <div class="info-div-row">
                                        <div class="info-div-name"> Status </div>
                                        <div class="info-div-value">
                                            @if ($event->type === 'event')
                                            <span class="label label-{{ $event->event_status == App\Models\UserEvents\UserEvent::STATUS_CLOSED ? 'success' : ($event->event_status == App\Models\UserEvents\UserEvent::STATUS_CANCELLED ? 'warning' : 'primary') }}">
                                                {{ App\Helpers\AppHelper::getUserEventsStatus($event->event_status) }}
                                            </span>
                                            @else
                                               <span style="color: white; background-color: {{ $statusColor }};"  class="label label-info">
                                                                {{ \App\Helpers\AppHelper::getUserTasksStatus($event->task_status) }}
                                                            </span>
                                            </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="info-div-row">
                                        <div class="info-div-name"> Type </div>
                                        <div class="info-div-value">
                                            <span>
                                                @if ($event->type === 'event')
                                                    {{ \App\Helpers\AppHelper::getUserEventsTypes($event->event_type) }}
                                                @else
                                                    {{ \App\Helpers\AppHelper::getUserTaskTypes($event->task_type) }}
                                                @endif
                                            </span>
                                        </div>
                                    </div>
                                    <div class="info-div-row">
                                        <div class="info-div-name"> Title </div>
                                        <div class="info-div-value"><span>{{ $event->title }}</span></div>
                                    </div>
                                    <div class="info-div-row">
                                        <div class="info-div-name"> {!! App\Helpers\AppHelper::replaceWithNbsp('Start Date & Time') !!} </div>
                                        <div class="info-div-value">
                                            <span>{{ \Carbon\Carbon::parse($event->start)->format('d/m/Y H:i') }}</span>
                                        </div>
                                    </div>
                                    <div class="info-div-row">
                                        <div class="info-div-name"> {!! App\Helpers\AppHelper::replaceWithNbsp('End Date & Time') !!} </div>
                                        <div class="info-div-value">
                                            <span>{{ \Carbon\Carbon::parse($event->end)->format('d/m/Y H:i') }}</span>
                                        </div>
                                    </div>
                                    @if ($event->type === 'task')
                                        <div class="info-div-row">
                                            <div class="info-div-name"> Assigned To </div>
                                            <div class="info-div-value">
                                                @php
                                                    $iqaUser = \App\Models\User::find($event->assign_iqa_id);
                                                @endphp
                                               <span>{{ $iqaUser ? $iqaUser->firstnames . ' ' . $iqaUser->surname : 'N/A' }}</span>
                                            </div>
                                        </div>
                                    @endif
                                    @if ($event->type === 'event')
                                        <div class="info-div-row">
                                            <div class="info-div-name"> Location </div>
                                            <div class="info-div-value">
                                                <span>{!! nl2br(e($event->location)) !!}</span>
                                            </div>
                                        </div>
                                    @endif
                                    <div class="info-div-row">
                                        <div class="info-div-name"> Detail </div>
                                        <div class="info-div-value">
                                            <span>{!! nl2br(e($event->description)) !!}</span>
                                        </div>
                                    </div>
                                    @if ($event->type === 'event')
                                    <div class="info-div-row">
                                        <div class="info-div-name"> Participants </div>
                                        <div class="info-div-value">
                                            <div class="table-responsive">
                                                <table class="table table-bordered">
                                                    @forelse($event->participants AS $participantEntry)
                                                        <tr>
                                                            <td>
                                                                {{ $participantEntry->full_name }} 
                                                                [{{ App\Models\Lookups\UserTypeLookup::getDescription($participantEntry->user_type) }}]
                                                            </td>
                                                            @if ($event->creator->id == auth()->user()->id && $event->canBeDeleted() || auth()->user()->isQualityManager())
                                                                <td>
                                                                    {!! App\Models\UserEvents\UserEventParticipant::renderStatus($participantEntry->pivot->status) !!}
                                                                </td>
                                                                <td class="center">
                                                                    @if ($participantEntry->pivot->status != App\Models\UserEvents\UserEventParticipant::STATUS_ACCEPTED)
                                                                    <button type="button" class="btn btn-danger btn-minier btn-round btnRemoveParticipant"   
                                                                        data-participant-id="{{ encrypt($participantEntry->pivot->user_id) }}" 
                                                                        data-tr-id="{{ encrypt($participantEntry->pivot->tr_id) }}" 
                                                                        title="Remove this participant">
                                                                        <i class="fa fa-times"></i>
                                                                    </button>                                                            
                                                                    @endif
                                                                </td>
                                                            @endif
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="4">No user is added to the event.</td>
                                                        </tr>
                                                    @endforelse
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6">
                    @if ($event->creator->id != auth()->user()->id && $event->isBooked())
                    @include('user_events.invitation_feedback_form', ['event' => $event])
                    @endif
                    @if ($event->user_id == auth()->user()->id && !$event->isPast() && $event->isBooked())
                        @include('user_events.add_participants_form', ['event' => $event])
                    @endif
                </div>
            </div>

            <!-- PAGE CONTENT ENDS -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
@endsection

@section('page-plugin-scripts')
    <script src="{{ asset('assets/js/toastr.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
@endsection

@section('page-inline-scripts')

    <script type="text/javascript">
        $(function() {
            $('[data-rel=tooltip]').tooltip();

        });

        $(".btnDeleteEvent").on('click', function(e) {
            e.preventDefault();

            var form = this.closest('form');
            var type = $(this).data('type'); 

            bootbox.confirm({
                title: 'Sure to Remove?',
                message: 'Are you sure you want to delete this ' + type + '?',
                buttons: {
                    cancel: {
                        label: '<i class="fa fa-times"></i> Cancel',
                        className: 'btn-xs btn-round'
                    },
                    confirm: {
                        label: '<i class="fa fa-check-o"></i> Yes Delete',
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

        $("button.btnRemoveParticipant").on('click', function(e){
                e.preventDefault();

                var btn = $(this);
                var participantId = $(this).attr('data-participant-id');

                bootbox.confirm({
                    title: 'Sure to Remove?',
                    message: 'Are you sure you want to remove this participant from this event?',
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
                            $.ajax({
                                type: 'PATCH',
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                                    '_method': 'PATCH'
                                },
                                beforeSend: function() {
                                    btn.attr('disabled', true);
                                    btn.html('<i class="fa fa-spinner fa-spin"></i>');
                                },
                                url: '{{ route('user_events.remove_participant', [$event]) }}',
                                data: {
                                    event_id: {{ $event->id }},
                                    participant_id: participantId
                                },
                                success: function(response) {
                                    bootbox.alert('Participant has been removed from the portfolio.', function() {
                                        window.location.reload();
                                    });

                                },
                                error: function(errorInfo, code, errorMessage) {
                                    btn.attr('disabled', false);
                                    btn.html('<i class="fa fa-plus fa-lg"></i>');
                                    bootbox.alert({
                                        title: "Error: " + (errorInfo.statusText !== undefined ? errorInfo.statusText : code),
                                        message: errorInfo.responseJSON.message !==
                                            undefined ? errorInfo.responseJSON.message :
                                            errorMessage
                                    });
                                }
                            });
                        }
                    }
                });
                
            });
    
    function updateTaskStatus(eventId, status) {

        let actionLabel = "";
        if (status === 3) {
            actionLabel = "completed";
        } else if (status === 4) {
            actionLabel = "signed off";
        } else {
            actionLabel = "updated"; 
        }

        if (!confirm(`Are you sure you want to mark this task as ${actionLabel}?`)) {
            return;
        }
        
        $.ajax({
            url: "{{ route('user_tasks.updateStatus', ':id') }}".replace(':id', eventId),
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                _method: "POST",  
                status: status
            },
            success: function (response) {
                alert(`Task marked as ${actionLabel}!`);
                location.reload(); 
            },
            error: function (xhr) {
                alert("Something went wrong. Please try again.");
                console.log(xhr.responseText);
            }
        });
    }
    
    </script>
@endsection
