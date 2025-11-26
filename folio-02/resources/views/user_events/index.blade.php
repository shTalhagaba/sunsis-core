@extends('layouts.master')

@section('title', 'User Events')

@section('page-content')
    <div class="page-header">
        @if (auth()->user()->isQualityManager())
            <h1>User Events/Tasks</h1>
        @else
            <h1>User Events</h1>
        @endif

    </div><!-- /.page-header -->
    <div class="row">

        <div class="col-xs-12">
            <!-- PAGE CONTENT BEGINS -->            
            <button class="btn btn-sm btn-bold btn-primary btn-round" type="button" onclick="window.location.href='{{ route('user_events.create') }}'">
            <i class="ace-icon fa fa-plus bigger-120"></i> 
                {{ auth()->user()->isQualityManager() ? 'Add New Event/Task' : 'Add New Event' }}
            </button>
            <div class="hr hr-12 hr-dotted"></div>

            <div class="widget-box transparent ui-sortable-handle collapsed">
                <div class="widget-header widget-header-small">
                    <h5 class="widget-title smaller">Search Filters</h5>
                    <div class="widget-toolbar">
                        <a href="#" data-action="collapse"><i class="ace-icon fa fa-chevron-down bigger-125"></i></a>
                    </div>
                </div>
                @include('partials.filter_crumbs')
                <div class="widget-body">
                    <div class="widget-main">
                        <small>@include('user_events.filter')</small>
                    </div>
                </div>
            </div>

            @include('partials.session_message')

            @include('partials.session_error')

           <div class="table-header">
                List of {{ auth()->user()->isQualityManager() ? 'events/tasks' : 'events' }}
            </div>

            <div class="table-responsive">
                <table id="tblEvents" class="table table-bordered table-hover">
                    <thead>
                    <tr>
                        <th>Start</th>
                        <th>End</th>
                        <th>Created By</th>
                        <th>Title</th>
                        <th>Type</th>
                        @if (auth()->user()->isQualityManager())
                            <th>Event/Task Type</th>
                            <th>Assign To</th>
                        @endif
                        <th>Status</th>
                        <th>Detail</th>
                        <th>Participants</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($events AS $event)
                    @php
                        $statusColor = $event->task_status == App\Models\UserEvents\UserEvent::STATUS_SIGNOFF ? '#87B87F' : // green
                        ($event->task_status == App\Models\UserEvents\UserEvent::STATUS_COMPLETED ? '#FFB752' : // red
                        ($event->task_status == App\Models\UserEvents\UserEvent::STATUS_ASSIGNED ? '#428BCA' : // blue
                        '#ffc107')); // yellow for warning
                    @endphp
                        <tr
                            onclick="window.location.href='{{ route('user_events.show', $event) }}';"
                            onmouseover="if(window.viewrow_onmouseover){window.viewrow_onmouseover(this, arguments.length > 0 ? arguments[0] : window.event)};"
                            onmouseout="if(window.viewrow_onmouseout){window.viewrow_onmouseout(this, arguments.length > 0 ? arguments[0] : window.event)};">
                            <td>{{ \Carbon\Carbon::parse($event->start)->format('d/m/Y H:i') }}</td>
                            <td>{{ \Carbon\Carbon::parse($event->end)->format('d/m/Y H:i') }}</td>
                            <td>{{ $event->creator->full_name }}</td>
                            <td>{{ $event->title }}</td>
                             @if (auth()->user()->isQualityManager())
                                <td>
                                    {{ ucfirst($event->type) }}
                                </td>
                            @endif
                            <td>
                                @if ($event->type === 'event')
                                    {{ \App\Helpers\AppHelper::getUserEventsTypes($event->event_type) }}
                                @else
                                    {{ \App\Helpers\AppHelper::getUserTaskTypes($event->task_type) }}
                                @endif
                            </td>
                            @if (auth()->user()->isQualityManager())
                                <td>
                                    @php
                                        $iqaUser = \App\Models\User::find($event->assign_iqa_id);
                                    @endphp
                                    {{ $iqaUser ? $iqaUser->firstnames . ' ' . $iqaUser->surname : '' }}
                                </td>
                            @endif
                            <td>
                                @if ($event->type === 'event')
                                    <span class="label label-{{ $event->event_status == App\Models\UserEvents\UserEvent::STATUS_CLOSED ? 'success' : ($event->event_status == App\Models\UserEvents\UserEvent::STATUS_CANCELLED ? 'warning' : 'primary') }}">
                                    {{ \App\Helpers\AppHelper::getUserEventsStatus($event->event_status) }}
                                </span>
                                @else
                                    <span style="color: white; background-color: {{ $statusColor }};"  class="label label-info">
                                        {{ \App\Helpers\AppHelper::getUserTasksStatus($event->task_status) }}
                                    </span>
                                @endif

                            </td>
                            
                            <td>{{ \Str::limit($event->description, 150) }}</td>
                            
                            <td align="center">{{ count($event->participants) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="7">No event found in the system.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            <div class="well well-sm">
                @include('partials.pagination', ['collection' => $events])
            </div>

            <!-- PAGE CONTENT ENDS -->
        </div><!-- /.col -->
    </div><!-- /.row -->
@endsection


@section('page-inline-scripts')

    <script type="text/javascript">




    </script>

@endsection

