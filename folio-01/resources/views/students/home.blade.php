@extends('layouts.master')

@section('title', 'Dashboard')

@section('page-plugin-styles')
<link rel="stylesheet" href="{{ asset('assets/css/toastr.min.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/css/fullcalendar.min.css') }}" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
@endsection

@section('breadcrumbs')
{{ Breadcrumbs::render('home', $student) }}
@endsection

@section('page-content')
<div class="page-header">
    <h1>
        {{ \Auth::user()->surname }}, {{ \Auth::user()->firstnames }}
        <small class="small">
            <i class="ace-icon fa fa-angle-double-right"></i>
            Last login at {{ \Auth::user()->previousLoginAt() }} from {{ \Auth::user()->previousLoginIp() }}
        </small>
    </h1>
</div><!-- /.page-header -->
<div class="row">
    <div class="col-xs-12">
        <!-- PAGE CONTENT BEGINS -->
        <div class="col-sm-8">
            <div class="widget-box transparent" id="widget-box-trs">
                <div class="widget-header widget-header-large">
                    <h4 class="widget-title">Training Records <span class="badge badge-info">{{ $student->training_records->count() }}</span></h4>
                </div>
                <div class="widget-body">
                    <div class="widget-main">
                        @foreach($student->training_records AS $training_record)
                        <div class="table-responsive">
                            <div class="widget-box" id="widget-box-{{ $training_record->id }}">
                            <div class="widget-header">
                                <h5 class="widget-title smaller">
                                    {{ $training_record->system_ref }} | {{ $training_record->start_date }} - {{ $training_record->planned_end_date }} | <span class="label label-lg label-info arrowed-in arrowed-in-right">{{ $training_record->status_code }}</span>
                                </h5>
                                <div class="widget-toolbar">
                                    <a class="btn btn-xs btn-round btn-primary" href="{{ route('students.training.show', [$student, $training_record]) }}">
                                        <i class="ace-icon fa fa-folder-open"></i> Open
                                    </a>
                                    &nbsp;
                                    <a href="#" data-action="collapse"><i class="ace-icon fa fa-chevron-up"></i></a>
                                </div>
                            </div>

                            <div class="widget-body">
                                <div class="widget-main">
                                    <div class="row">
                                        <div class="col-sm-12">
                                        <small>
                                            <strong>Employer:</strong>
                                            @php $loc = $training_record->location @endphp
                                            {{ $training_record->employer->legal_name }} | {!! $loc->postcode != '' ? '<i class="fa fa-map-marker light-orange bigger-110"></i> <span>' . $loc->postcode . '</span><br>' : '' !!}
                                        </small>
                                        <table class="table table-striped table-bordered table-hover">
					    <caption>Completed: <span class="label label-success"> {{ $training_record->signedOffPercentage() }}% </span></caption>
                                            <thead><tr><th>Portfolio</th><th>Dates</th><th>Progress</th></tr></thead>
                                            <tbody>
                                                @foreach($training_record->portfolios AS $portfolio)
                                                <tr>
                                                    <td>{{ $portfolio->qan }}<br>{{ $portfolio->title }}</td>
                                                    <td>
                                                        Start:&nbsp;{{ $portfolio->start_date }}<br>
                                                        Planned&nbsp;End:&nbsp;{{ $portfolio->planned_end_date }}<br>
                                                        Completed:&nbsp;{{ $portfolio->actual_end_date }}
                                                    </td>
                                                    <td>
                                                        <div class="easy-pie-chart percentage" data-percent="{{ $portfolio->signedOffPCsPercentage() }}" data-color="#CA5952">
                                                            <span class="percent">{{ $portfolio->signedOffPCsPercentage() }}</span>%
                                                        </div>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- PAGE CONTENT ENDS -->

    </div><!-- /.col -->
</div><!-- /.row -->
@endsection


@section('page-plugin-scripts')
<script src="{{ asset('assets/js/toastr.min.js') }}"></script>
<script src="{{ asset('assets/js/jquery.easypiechart.min.js') }}"></script>
<script src="{{ asset('assets/js/moment.min.js') }}"></script>
<script src="{{ asset('assets/js/fullcalendar.min.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
@endsection

@section('page-inline-scripts')
<script type="text/javascript">

$('.easy-pie-chart.percentage').each(function(){
    //var barColor = $(this).data('color') || '#555';
    var barColor = '#50C878';
    var trackColor = '#E2E2E2';
    var size = parseInt($(this).data('size')) || 72;
    $(this).easyPieChart({
      barColor: barColor,
      trackColor: trackColor,
      scaleColor: false,
      lineCap: 'butt',
      lineWidth: parseInt(size/10),
      animate:false,
      size: size
    }).css('color', barColor);
});

$(function(){

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

