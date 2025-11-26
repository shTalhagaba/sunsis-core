@extends('layouts.master')

@section('title', 'Dashboard')

@section('page-plugin-styles')
    <link rel="stylesheet" href="{{ asset('assets/css/toastr.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/fullcalendar.min.css') }}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
@endsection

@section('breadcrumbs')
    {{ Breadcrumbs::render('home') }}
@endsection

@section('page-content')
    <div class="page-header">
        <h1>
            {{ \Auth::user()->surname }}, {{ \Auth::user()->firstnames }}
            <small class="small">
                <i class="ace-icon fa fa-angle-double-right"></i> Last login at {{ \Auth::user()->previousLoginAt() }} from
                {{ \Auth::user()->previousLoginIp() }}
            </small>
        </h1>
    </div><!-- /.page-header -->
    <div class="row">
        <div class="col-xs-12">
            <!-- PAGE CONTENT BEGINS -->

            <div class="row">
                <div class="col-sm-6">

                    @if (in_array(auth()->user()->user_type, [
                            \App\Models\Lookups\UserTypeLookup::TYPE_ASSESSOR,
                            \App\Models\Lookups\UserTypeLookup::TYPE_ADMIN,
                        ]))

                        <div class="widget-box" id="widgetTrainingRecordsByStatusYear">
                            <div class="widget-header">
                                <div class="widget-title">Training Records by Status</div>
                                <div class="widget-toolbar">
                                    Year:
                                    <select name="trainingRecordsByStatusYear" id="trainingRecordsByStatusYear">
                                        @for ($i = now()->year - 5; $i <= now()->year; $i++)
                                            <option value="{{ $i }}" {{ $i == now()->year ? 'selected' : '' }}>
                                                {{ $i }}-{{ $i + 1 }}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                            <div class="widget-main">
                                <div class="widget-body">
                                    <div class="table-responsive infobox-container" id="tblTrainingRecordsByStatusYear">
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if (in_array(auth()->user()->user_type, [\App\Models\Lookups\UserTypeLookup::TYPE_ASSESSOR]))
                        <div style="margin: 10px; display: flex; gap: 10px;" class="infobox-container">
                            <div style="width: 300px;" class="infobox infobox-green">
                                <div class="infobox-icon">
                                    <i class="ace-icon fa fa-file-text"></i>
                                </div>
                                <div class="infobox-data">
                                    <span class="infobox-data-number" style="cursor: pointer"
                                        onclick="window.location.href='{{ route('trainings.evidences.index') }}?evidence_status={{ App\Models\Training\TrainingRecordEvidence::STATUS_LEARNER_SUBMITTED }}'">
                                        {{ $number_of_evidences_to_assess ?? 0 }}
                                        {{ $number_of_evidences_to_assess == 1 ? 'Evidence' : 'Evidences' }}
                                    </span>
                                    <div class="small">to assess for continuing learners</div>
                                </div>
                            </div>

                            <div style="width: 300px;" class="infobox infobox-blue">
                                <div class="infobox-icon">
                                    <i class="ace-icon fa fa-file-text"></i>
                                </div>
                                <div class="infobox-data">
                                    <span class="infobox-data-number" style="cursor: pointer"
                                        onclick="window.location.href='{{ route('trainings.index') }}?over_due=true'">
                                        {{ $over_due_reviews ?? 0 }}
                                        {{ $over_due_reviews == 1 ? 'Review' : 'Reviews' }}
                                    </span>
                                    <div class="small">Overdue Progress Reviews</div>
                                </div>
                            </div>
                        </div>
                    @endif

                    @if (auth()->user()->isStaff())
                        <div class="widget-box">
                            <div class="widget-header">
                                <div class="widget-title">New starts over the previous 6 months</div>
                            </div>
                            <div class="widget-body">
                                <div class="widget-main table-responsive">
                                    @if (count($newStartsInLast6Months) > 0)
                                        <table class="table table-bordered">
                                            <tr>
                                                @foreach ($newStartsInLast6Months as $data)
                                                    <th class="text-center">{{ $data['month'] }}</th>
                                                @endforeach
                                                <th class="text-center">Total</th>
                                            </tr>
                                            <tr>
                                                @foreach ($newStartsInLast6Months as $data)
                                                    <td class="text-center">
                                                        <a
                                                            href="{{ route('trainings.index') }}?_reset=2&status_code=&from_start_date={{ $data['month_start_date'] }}&to_start_date={{ $data['month_end_date'] }}">
                                                            {{ $data['count'] }}
                                                        </a>
                                                    </td>
                                                @endforeach
                                                <td class="text-center">
                                                    <a
                                                        href="{{ route('trainings.index') }}?_reset=2&status_code=&from_start_date={{ now()->subMonths(5)->startOfMonth()->format('Y-m-d') }}&to_start_date={{ now()->endOfMonth()->format('Y-m-d') }}">
                                                        {{ array_sum(array_column($newStartsInLast6Months, 'count')) }}
                                                    </a>
                                                </td>
                                            </tr>
                                        </table>
                                    @else
                                        <span class="text-info">No data to show</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif

                    @if (auth()->user()->isStaff())
                        <div class="widget-box">
                            <div class="widget-header">
                                <div class="widget-title">Completions due by planned end month in next 6 months</div>
                            </div>
                            <div class="widget-body">
                                <div class="widget-main table-responsive">
                                    @if (count($continuingStudentsPlannedToFinish) > 0)
                                        <table class="table table-bordered">
                                            <tr>
                                                @foreach ($continuingStudentsPlannedToFinish as $data)
                                                    <th class="text-center">{{ $data['month'] }}</th>
                                                @endforeach
                                            </tr>
                                            <tr>
                                                @foreach ($continuingStudentsPlannedToFinish as $data)
                                                    <td class="text-center {{ $data['overstayer'] ? 'bg-danger' : '' }}">
                                                        <a
                                                            href="{{ route('trainings.index') }}?_reset=2&status_code={{ App\Models\Lookups\TrainingStatusLookup::STATUS_CONTINUING }}&from_planned_end_date={{ $data['month_start_date'] }}&to_planned_end_date={{ $data['month_end_date'] }}">
                                                            {{ $data['count'] }}
                                                        </a>
                                                    </td>
                                                @endforeach
                                            </tr>
                                        </table>
                                        {{-- <span class="text-info">
                                    Continuing Learners: {{ $totalContinuing }} | Overstayers: {{ $overstayers }} | Overstayers Percentage: {{ $percentageOfOverstayers }}%
                                </span>    --}}
                                    @else
                                        <span class="text-info">No data to show</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif

                    @if (auth()->user()->isQualityManager() || auth()->user()->isVerifier())
                        <div class="widget-box">
                            <div class="widget-header">
                                <div class="widget-title">Over Due Tasks</div>
                            </div>
                            <div class="widget-body">
                                <div class="widget-main table-responsive">
                                    @if (count($incompleteTasks) > 0)
                                        <table class="table table-bordered">
                                            <tr>
                                                <th class="text-center">Task Title</th>
                                                <th class="text-center">Task Type</th>
                                                <th class="text-center">Start Date</th>
                                                <th class="text-center">Start Time</th>
                                                <th class="text-center">End Date</th>
                                                <th class="text-center">End Time</th>
                                                <th class="text-center">Assign IQA</th>
                                                <th class="text-center">status</th>
                                            </tr>
                                            @foreach ($incompleteTasks as $data)
                                                @php
                                                    $statusColor =
                                                        $data['task_status'] ==
                                                        App\Models\UserEvents\UserEvent::STATUS_SIGNOFF
                                                            ? '#87B87F' // green
                                                            : ($data['task_status'] ==
                                                            App\Models\UserEvents\UserEvent::STATUS_COMPLETED
                                                                ? '#FFB752' // red
                                                                : ($data['task_status'] ==
                                                                App\Models\UserEvents\UserEvent::STATUS_ASSIGNED
                                                                    ? '#428BCA' // blue
                                                                    : '#ffc107')); // yellow for warning
                                                @endphp


                                                <tr onclick="window.location='{{ route('user_events.show', $data['id']) }}'"
                                                    style="cursor: pointer;">
                                                    <td class="text-center">
                                                        {{ $data['title'] }}
                                                    </td>
                                                    <td class="text-center">
                                                        {{ \App\Helpers\AppHelper::getUserTaskTypes($data['task_type']) }}
                                                    </td>
                                                    <td class="text-center">
                                                        {{ \Carbon\Carbon::parse($data['start'])->format('Y-m-d') }}
                                                    </td>
                                                    <td class="text-center">
                                                        {{ \Carbon\Carbon::parse($data['start'])->format('h:i A') }}
                                                    </td>
                                                    <td class="text-center">
                                                        {{ \Carbon\Carbon::parse($data['end'])->format('Y-m-d') }}
                                                    </td>
                                                    <td class="text-center">
                                                        {{ \Carbon\Carbon::parse($data['end'])->format('h:i A') }}
                                                    </td>
                                                    <td class="text-center">
                                                        {{ optional(\App\Models\User::find($data['assign_iqa_id']))->firstnames ?? 'N/A' }}
                                                    </td>
                                                    <td class="text-center">
                                                        <span style="color: white; background-color: {{ $statusColor }};"
                                                            class="label label-info">
                                                            {{ \App\Helpers\AppHelper::getUserTasksStatus($data['task_status']) }}
                                                        </span>

                                                    </td>
                                                </tr>
                                            @endforeach
                                        </table>
                                    @else
                                        <span class="text-info">No data to show</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif
                    @if (auth()->user()->isAssessor())
                        <div class="widget-box" id="widgetAssessorActions">
                            <div class="widget-header">
                                <div class="widget-title">Submitted Tasks by Learners</div>
                                <div class="widget-toolbar">
                                    <a href="#" data-action="reload">
                                        <i class="ace-icon fa fa-refresh"></i>
                                    </a>
                                </div>
                            </div>
                            <div class="widget-main">
                                <div class="widget-body">
                                    <div class="table-responsive" id="tblAssessorActions">
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if (auth()->user()->isVerifier())
                        {{-- <div class="widget-box" id="widgetVerifierActions">
                        <div class="widget-header">
                            <div class="widget-title">Incoming Scheduled Plans</div>
                            <div class="widget-toolbar">
                                <a href="#" data-action="reload">
                                    <i class="ace-icon fa fa-refresh"></i>
                                </a>
                            </div>
                        </div>
                        <div class="widget-main">
                            <div class="widget-body">
                                <div class="table-responsive" id="tblVerifierActions">
                                </div>
                            </div>
                        </div>
                    </div> --}}
                    @endif

                    @if (in_array(auth()->user()->user_type, [
                            \App\Models\Lookups\UserTypeLookup::TYPE_ADMIN,
                            \App\Models\Lookups\UserTypeLookup::TYPE_MANAGER,
                        ]))
                        <div class="widget-box">
                            <div class="widget-header">
                                <div class="widget-title">Reporting on Visit Type</div>
                            </div>
                            <div class="widget-body">
                                <div class="widget-main table-responsive">
                                    @if (count($assessor_type_report) > 0)
                                        <table class="table table-bordered">
                                            <tr>
                                                <th class="text-center">Role</th>
                                                <th class="text-center">Face-to-Face Sessions</th>
                                                <th class="text-center">Remote Sessions</th>
                                                <th class="text-center">Total Sessions</th>
                                            </tr>
                                            @foreach ($assessor_type_report as $row)
                                                <tr>
                                                    <td class="text-center">

                                                        {{ ucwords(str_replace('_', ' ', $row->role)) }}

                                                    </td>

                                                    <td class="text-center">
                                                        <a
                                                            href="{{ route('reports.visit_type') }}?role={{ $row->role }}&type=face_to_face">
                                                            {{ $row->face_to_face }}
                                                        </a>
                                                    </td>

                                                    <td class="text-center">
                                                        <a
                                                            href="{{ route('reports.visit_type') }}?role={{ $row->role }}&type=remote">
                                                            {{ $row->remote }}
                                                        </a>
                                                    </td>

                                                    <td class="text-center">
                                                        <a
                                                            href="{{ route('reports.visit_type') }}?role={{ $row->role }}&type=all">
                                                            {{ $row->face_to_face + $row->remote }}
                                                        </a>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </table>
                                    @else
                                        <span class="text-info">No data to show</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif

                </div>
                <div class="col-sm-6">
                    @can('view-license-info')
                        <div class="pricing-box">
                            <div class="widget-box">
                                <div class="widget-header">
                                    <h4 class="widget-title">License Information</h4>
                                </div>
                                <div class="widget-body">
                                    <div class="widget-main">
                                        @php
                                            $licensePurchased = \App\Models\License::sum('number_of_licenses');
                                            $licenseUsed = \App\Models\Training\TrainingRecord::count();
                                            $licenseRemaining = ((int) $licensePurchased) - (int) $licenseUsed;
                                        @endphp
                                        <ul class="list-unstyled spaced2">
                                            <li>
                                                <i class="ace-icon fa fa-check green"></i>
                                                Number of licenses: <span class="price">{{ $licensePurchased }}</span>
                                            </li>
                                            <li>
                                                <i class="ace-icon fa fa-check green"></i>
                                                Used: <span class="price">{{ $licenseUsed }}</span>
                                            </li>
                                            <li>
                                                <i class="ace-icon fa fa-check green"></i>
                                                Remaining: <span class="price">{{ $licenseRemaining }}</span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endcan

                    <div class="space-12"></div>

                    <div class="widget-box">
                        <div class="widget-header">
                            <div class="widget-title"><i class="ace-icon fa fa-search bigger-110"></i> Quick Search
                                Training
                                Records</div>
                        </div>
                        <div class="widget-body">
                            {!! Form::open([
                                'url' => route('trainings.index'),
                                'class' => 'form-horizontal',
                                'method' => 'GET',
                                'role' => 'form',
                            ]) !!}
                            {!! Form::hidden('_reset', 2) !!}
                            <div class="widget-main">
                                <div class="row">
                                    <div class="col-md-4">
                                        {{ Form::label('firstnames', 'First Name', ['class' => 'control-label']) }}
                                        {{ Form::text('firstnames', null, ['class' => 'form-control', 'maxlength' => '70']) }}
                                    </div>
                                    <div class="col-md-4">
                                        {{ Form::label('surname', 'Surname', ['class' => 'control-label']) }}
                                        {{ Form::text('surname', null, ['class' => 'form-control', 'maxlength' => '70']) }}
                                    </div>
                                    <div class="col-md-4">
                                        {{ Form::label('learner_ref', 'Learner Reference', ['class' => 'control-label']) }}
                                        {{ Form::text('learner_ref', null, ['class' => 'form-control', 'maxlength' => '12']) }}
                                    </div>
                                </div>
                            </div>
                            <div class="widget-toolbox clearfix">
                                <div class="center">
                                    <button class="btn btn-xs btn-success btn-round" type="submit">
                                        <i class="ace-icon fa fa-search bigger-110"></i>
                                        Search
                                    </button>
                                </div>
                            </div>
                            {!! Form::close() !!}
                        </div>
                    </div>

                    <div class="space-12"></div>

                    <div class="widget-box" id="your_diary">
                        <div class="widget-header">
                            <div class="widget-title">Your Diary</div>
                            <div class="widget-toolbar">
                                <button type="button"
                                    onclick="document.location.href='{{ route('user_events.index') }}'"
                                    class="btn btn-xs btn-purple btn-round">
                                    {{ auth()->user()->isQualityManager() ? 'View Your Events/Tasks' : 'View Your Event' }}
                                </button>
                            </div>
                        </div>
                        <div class="widget-body">
                            <div class="widget-main">
                                <div class="container" style="max-width: 100%">
                                    <div class="response"></div>
                                    <div id='calendar'></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div id="eventContent" title="Event Details" style="display:none;">
                Start: <span id="startTime"></span><br>
                Assessor/Interviewer: <span id="e_assessor"></span><br><br>
                <p id="eventInfo"></p>
            </div>

            <!-- PAGE CONTENT ENDS -->
        </div><!-- /.col -->
    </div><!-- /.row -->
@endsection


@section('page-plugin-scripts')
    <script src="{{ asset('assets/js/toastr.min.js') }}"></script>
    <script src="{{ asset('assets/js/moment.min.js') }}"></script>
    <script src="{{ asset('assets/js/fullcalendar.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
@endsection

@section('page-inline-scripts')
    <script>
        $(function() {

            var SITEURL = "{{ url('/') }}/";
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $('#calendar').fullCalendar({
                header: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'month,agendaWeek,agendaDay'
                },
                buttonText: {
                    today: 'today',
                    month: 'month',
                    week: 'week',
                    day: 'day'
                },
                weekends: false,
                editable: true,
                droppable: false,
                eventLimit: 3,
                eventOrder: function(a, b) {
                    let diff = moment(a.start).valueOf() - moment(b.start).valueOf();
                    if (diff !== 0) return diff;

                    diff = moment(a.end || a.start).valueOf() - moment(b.end || b.start).valueOf();
                    if (diff !== 0) return diff;

                    return (a.id || 0) - (b.id || 0);
                },
                events: function(start, end, timezone, callback) {
                    $.ajax({
                        url: '{{ route('calendar.diary') }}',
                        data: {
                            start: start.format('YYYY-MM-DD'),
                            end: end.format('YYYY-MM-DD')
                        },
                        success: function(events) {
                            // ✅ sort before passing
                            events.sort(function(a, b) {
                                const aTime = a.start_time || "";
                                const bTime = b.start_time || "";
                                return aTime.localeCompare(bTime) || (a.id - b.id);
                            });
                            callback(events);
                        }
                    });
                },

                eventRender: function(event, element) {
                    console.log(event);
                    element.find('.fc-time').remove();
                    const typeLabel = event.type === 'task' ? 'Task' : 'Event';
                    const time = moment(event.start).format('HH:mm');
                    element.find('.fc-title').text(`${typeLabel}: ${time} ${event.title}`);
                },

                eventClick: function(event, element) {
                    const typeLabel = event.type === 'task' ? event.task_type : event.event_type;
                    const statusLabel = event.type === 'task' ? event.task_status : event.event_status;

                    let statusColor = "#428BCA";
                    if (event.task_status === "Assigned") statusColor = "#428BCA";
                    else if (event.task_status === "Completed") statusColor = "#FFB752";
                    else if (event.task_status === "Sign-off") statusColor = "#87B87F";

                    $.alert({
                        title: `${typeLabel} <span class="label label-info" style="color:white; background-color:${statusColor}">${statusLabel}</span>`,
                        content: (function() {
                            let html = `
                            <p><strong>Created By:</strong> ${event.created_by}</p>
                            <p><strong>Title:</strong> ${event.title}</p>
                            <p><strong>From:</strong> ${moment(event.actual_start).format('MMM Do h:mm A')}
                            <strong>To:</strong> ${moment(event.actual_end).format('MMM Do h:mm A')}</p>
                            <p><strong>Description:</strong> ${event.description || ''}</p>
                        `;
                            if (event.link && event.link.trim() !== '') {
                                html +=
                                    `<div class="btn-actions" style="margin-top: 10px; display:inline-flex; gap:6px;">
                                        <button class="btn btn-primary btn-xs btn-round" onclick="window.location.href='${event.link}'">View Details</button>`;
                            } else {
                                html +=
                                    `<div class="btn-actions" style="margin-top: 10px; display:inline-flex; gap:6px;">`;
                            }

                            @if (!auth()->user()->isVerifier())
                                if (event.task_status !== null && event.task_status ==
                                    "Completed") {
                                    html +=
                                        `<button class="btn btn-success btn-xs btn-round mark-task" data-id='${event.id}' data-status="4">Sign Off</button>`;
                                }
                            @else
                                if (event.type === "task" && event.task_status ==
                                    "Assigned") {
                                    html +=
                                        `<button class="btn btn-success btn-xs btn-round mark-task" data-id='${event.id}' data-status="3">Mark as Completed</button>`;
                                }
                            @endif

                            html += `</div>`;
                            return html;
                        })()
                    });
                },

                eventLimitClick: function(cellInfo, jsEvent) {
                    const allEvents = cellInfo.segs.map(seg => seg.event);

                    // Number of events visible in the cell (eventLimit)
                    const eventLimit = 2; // or your configured eventLimit

                    // Sort by start_time first
                    const sortedEvents = allEvents.sort((a, b) => a.start_time.localeCompare(b
                        .start_time));

                    // Slice to get hidden events (everything beyond the visible limit)
                    const hiddenEvents = sortedEvents.slice(eventLimit);
                    // First 2 events are "visible", the rest are "hidden"

                    let contentHtml = '<div style="font-family: Arial, sans-serif;">';

                    hiddenEvents.forEach(function(event) {
                        const typeLabel = event.type === 'task' ? 'Task' : 'Event';
                        const startDate = moment(event.actual_start).format("DD MMM");
                        const endDate = event.actual_end ? moment(event.actual_end).format(
                            "DD MMM") : startDate;
                        const startTime = moment(event.actual_start).format("HH:mm");
                        const endTime = event.actual_end ? moment(event.end).format("HH:mm") :
                            startTime;

                        let statusColor = "#428BCA";
                        if (event.task_status === "Assigned") statusColor = "#428BCA";
                        else if (event.task_status === "Completed") statusColor = "#FFB752";
                        else if (event.task_status === "Sign-off") statusColor = "#87B87F";

                        contentHtml +=
                            `
                    <div style="background:#f4f9fd;border-radius:8px;padding:10px;margin-bottom:10px;display:flex;align-items:flex-start;box-shadow:0 1px 3px rgba(0,0,0,0.1);">
                        <div style="width:10px;height:10px;background:#3ba6e9;border-radius:50%;margin-right:10px;margin-top:6px;"></div>
                        <div>
                            <div style="font-size:13px;color:#666;">
                                ${startDate} – ${endDate} ${startTime} – ${endTime} 
                                ${event.type === 'task' 
                                    ? (event.task_status ? `<span style="color:white; background-color:${statusColor}" class="label label-info">${event.task_status}</span>` : '') 
                                    : (event.event_status ? `<span style="color:white; background-color:${statusColor}" class="label label-info">${event.event_status}</span>` : '')}
                            </div>
                            <div style="font-weight:bold;color:#333;">${typeLabel}: ${event.title}</div>
                            <div style="font-weight:bold;color:#333; text-transform:capitalize;">${event.assign_iqa || ''}</div>`;
                        // Start button container
                        contentHtml +=
                            `<div class="btn-actions" style="margin-top: 10px; display: inline-flex; gap: 6px;">`;

                        // Add View Details button if link exists
                        if (event.link && event.link.trim() !== '') {
                            contentHtml +=
                                `<button class="btn btn-primary btn-xs btn-round" onclick="window.location.href='${event.link}'">View Details</button>`;
                        }

                        // Add Sign Off / Mark as Completed button depending on user & task status
                        @if (!auth()->user()->isVerifier())
                            if (event.task_status !== null && event.task_status ==
                                "Completed") {
                                contentHtml +=
                                    `<button class="btn btn-success btn-xs btn-round mark-task" data-id='${event.id}' data-status="4">Sign Off</button>`;
                            }
                        @else
                            if (event.type === "task" && event.task_status == "Assigned") {
                                contentHtml +=
                                    `<button class="btn btn-success btn-xs btn-round mark-task" data-id='${event.id}' data-status="3">Mark as Completed</button>`;
                            }
                        @endif
                        contentHtml += `</div>
                        </div>
                    </div>`;
                    });

                    contentHtml += '</div>';

                    $.alert({
                        title: 'More Events',
                        content: contentHtml || '<div>No hidden events for this day.</div>',
                        columnClass: 'medium'
                    });

                    return false; // default popover ko band kar do
                }
            });

            $("select[name=trainingRecordsByStatusYear]").on("change", function() {
                refreshTrainingRecordsByStatusYear(this.value);
            });

            @if (auth()->user()->isStaff())
                refreshTrainingRecordsByStatusYear('');
                renderAssessorPanel();
                renderVerifierPanel();
            @endif

            $('#widgetAssessorActions').on('reload.ace.widget', function(ev) {
                ev.preventDefault();
                renderAssessorPanel();
            });
            $('#widgetVerifierActions').on('reload.ace.widget', function(ev) {
                ev.preventDefault();
                renderVerifierPanel();
            });

            // Event delegation: handles dynamically loaded content
            $(document).on('click', '.mark-task', function(e) {
                e.preventDefault();

                const taskId = $(this).data('id');
                const status = $(this).data('status'); // Dynamic status from button
                const actionLabel = $(this).text().trim();


                // Optional: confirm first
                if (!confirm(`Are you sure you want to ${actionLabel.toLowerCase()} this task?`)) return;
                $.ajax({
                    method: 'POST',
                    url: '{{ route('user_tasks.updateStatus', ':id') }}'.replace(':id', taskId),
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    data: {
                        status: status
                    }
                }).done(function(response) {
                    $.alert(`Task ${actionLabel.toLowerCase()} successfully.`);
                    setTimeout(function() {
                        location.reload();
                    }, 800); // wait 0.8s so the alert shows before reload
                }).fail(function(jqXHR, textStatus, errorThrown) {
                    $.alert(errorThrown, textStatus);
                });
            });

        });

        function refreshTrainingRecordsByStatusYear(year) {
            $('#widgetTrainingRecordsByStatusYear').widget_box('reload');
            $.ajax({
                data: {
                    year: year
                },
                url: "{{ route('home.getTrainingRecordStatusByYear') }}",
            }).done(function(response) {
                $("div#tblTrainingRecordsByStatusYear").html('');
                $("div#tblTrainingRecordsByStatusYear").html(response);
                $('#widgetTrainingRecordsByStatusYear').trigger('reloaded.ace.widget');
            }).fail(function(jqXHR, textStatus, errorThrown) {
                $.alert(errorThrown, textStatus);
            });
        }

        function renderAssessorPanel() {
            $('#widgetAssessorActions').widget_box('reload');
            $.ajax({
                url: "{{ route('home.getAssessorActions') }}",
            }).done(function(response) {
                $("div#tblAssessorActions").html('');
                $("div#tblAssessorActions").html(response);
                $('#widgetAssessorActions').trigger('reloaded.ace.widget');
            }).fail(function(jqXHR, textStatus, errorThrown) {
                $.alert(errorThrown, textStatus);
            });
        }

        function renderVerifierPanel() {
            $('#widgetVerifierActions').widget_box('reload');
            $.ajax({
                url: "{{ route('home.getVerifierActions') }}",
            }).done(function(response) {
                console.log(response);
                $("div#tblVerifierActions").html('');
                $("div#tblVerifierActions").html(response);
                $('#widgetVerifierActions').trigger('reloaded.ace.widget');
            }).fail(function(jqXHR, textStatus, errorThrown) {
                $.alert(errorThrown, textStatus);
            });
        }
    </script>

@endsection
