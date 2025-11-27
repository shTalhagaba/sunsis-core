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
            <i class="ace-icon fa fa-angle-double-right"></i> Last login at {{ \Auth::user()->previousLoginAt() }} from {{ \Auth::user()->previousLoginIp() }}
        </small>
    </h1>
</div><!-- /.page-header -->
<div class="row">
    <div class="col-xs-12">
        <!-- PAGE CONTENT BEGINS -->

        <div class="row">
            <div class="col-sm-6">
		        @if(auth()->user()->isStaff())
                <div class="widget-box widget-color-green" id="learners_planned_to_finish_in_next_3_months">
                    <div class="widget-header">
                        <div class="widget-title">Continuing Learners Planned to Finish on or before {{ \Carbon\Carbon::now()->addMonths(3)->format('d/m/Y') }}</div>
                        <div class="widget-toolbar">
                            <span class="label label-info"><strong>{{ count($continuing_students_planned_to_finish_within_next_3_months) }}</strong></span>
                        </div>
                    </div>
                    <div class="widget-body">
                        <div class="widget-main">
                            <div class="table-responsive" style="max-height: 500px;">
                                <table class="table table-bordered">
                                    <thead><tr><th>Student</th><th>Dates</th><th>Days Left</th><th>Progress</th></tr></thead>
                                    <tbody>
                                        @forelse ($continuing_students_planned_to_finish_within_next_3_months as $tr)
                                        <tr>
                                            <td>
                                                @if('view-training-record')
                                                <a href="{{ route('students.training.show', ['student' => $tr->student, 'training_record' => $tr]) }}">{{ $tr->student->full_name }}</a>
                                                @else
                                                {{ $tr->student->full_name }}
                                                @endif
                                            </td>
                                            <td>{{ $tr->start_date }} - {{ $tr->planned_end_date }}</td>
                                            <td>{{ \Carbon\Carbon::parse($tr->getOriginal('planned_end_date'))->diffForHumans() }}</td>
                                            <td>{{ $tr->signedOffPercentage() }}%</td>
                                        </tr>
                                        @empty
                                        <tr><td colspan="4">No records found.</td></tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
		@endif
            </div>
            <div class="col-sm-6">
                @can ('view-license-info')
                <div class="pricing-box">
                    <div class="widget-box">
                        <div class="widget-header">
                            <h4 class="widget-title">License Information</h4>
                        </div>
                        <div class="widget-body">
                            <div class="widget-main">
                                @php
                                    $license = \App\Models\License::latest('id')->first();
                                    $stats = \App\Models\License::getStats();
                                @endphp
                                @if ($license)
                                <ul class="list-unstyled spaced2">
                                    <li>
                                        <i class="ace-icon fa fa-check green"></i>
                                        Number of licenses: <span class="price">{{ $license->number_of_licenses }}</span>
                                    </li>
                                    <li><i class="ace-icon fa fa-check green"></i>PO Number: {{ $license->po_number }}</li>
                                </ul>
                                <hr>
                                <ul class="list-unstyled spaced2">
                                    <li><i class="ace-icon fa fa-check green"></i>Used: <span class="price">{{ $stats['used'] }}</span></li>
                                    <li><i class="ace-icon fa fa-check green"></i>
                                        Remaining: <span class="price">{{  $stats['remaining'] }}</span>
                                    </li>
                                </ul>
                                @else
                                <i class="fa fa-warning"></i> No license information added.
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @endcan

                <div class="widget-box widget-color-green" id="your_diary">
                    <div class="widget-header">
                        <div class="widget-title">Your Diary</div>
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

    $(function(){

        var SITEURL = "{{url('/')}}/";
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('#calendar').fullCalendar({
            header    : {
                left  : 'prev,next today',
                center: 'title',
                right : 'month,agendaWeek,agendaDay'
            },
            buttonText: {
                today: 'today',
                month: 'month',
                week : 'week',
                day  : 'day'
            },
            loading: function( isLoading, view ) {
                if(isLoading) {
                    $('#your_diary').addClass("position-relative");
                    $('#your_diary').append('<div class="widget-box-overlay"><i class="ace loading-icon fa fa-spinner fa-spin fa-2x white"></i></div>');
                } else {
                    $('#your_diary').find(".widget-box-overlay").remove();
                    $('#your_diary').removeClass("position-relative");
                    console.log('stop');
                }
            },
            weekends: false,
            events: '{{ route('calendar.show') }}',
            editable  : true,
            droppable : false,
            eventClick: function (event, element) {
                $.alert({
                    title: 'Calendar Event',
                    icon: 'fa fa-info',
                    content: '<strong>Title:</strong> ' + event.title +
                        '<br><strong>' + 'Start: </strong>' + moment(event.start).format('MMM Do h:mm A') +
                        '<br><strong>' + 'End: </strong>' + moment(event.end).format('MMM Do h:mm A') +
                        '<br><strong>' + 'Desc.: </strong>' + event.description
                });
            }
        });

    });

</script>

@endsection

