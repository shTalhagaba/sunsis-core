@extends('layouts.master')

@section('title', 'Training Records')

@section('breadcrumbs')
{{ Breadcrumbs::render('students.training.index') }}
@endsection

@section('page-content')
<div class="page-header"><h1>Training Records</h1></div><!-- /.page-header -->

<div class="row">
    <div class="col-xs-12">
        <!-- PAGE CONTENT BEGINS -->
        @can('add-student')
        <div class="well well-sm">
            <button class="btn btn-sm btn-white btn-bold btn-primary btn-round" type="button" onclick="window.location.href='{{ route('students.create') }}'">
                <i class="ace-icon fa fa-user-plus bigger-120"></i> Add New Student
            </button>
        </div>
        @endcan
	@if(!\Auth::user()->isStudent())
        <div class="widget-box transparent ui-sortable-handle collapsed">
            <div class="widget-header widget-header-small">
                <h5 class="widget-title smaller">Search Filters</h5>
                <div class="widget-toolbar"> 
                    <a title="Export view to Excel" href="#" onclick="exportView();"
                        <i class="ace-icon fa fa-file-excel-o bigger-125"></i>
                    </a> &nbsp;
                    <a href="#" data-action="collapse"><i class="ace-icon fa fa-chevron-down bigger-125"></i></a>
                </div>
            </div>
            <div class="widget-body">
                <div class="widget-main">
                    <small> @include('students.training.filter')</small>
                </div>
            </div>
        </div>
	@endif
        <div class="table-header">
            List of training records <br>
            Showing <strong>{{ ($training_records->currentpage()-1)*$training_records->perpage()+1 }}</strong>
            to <strong>{{ $training_records->currentpage()*$training_records->perpage() > $training_records->total() ? $training_records->total() : $training_records->currentpage()*$training_records->perpage() }}</strong>
            of <strong>{{ $training_records->total() }}</strong>
            entries
        </div>

        <div class="table-responsive">
            @forelse($training_records AS $training_record)
            <div class="widget-box">
                <div class="widget-header">
                    <h5 class="widget-title">
                        {{ $training_record->student->surname  }}, {{ $training_record->student->firstnames  }} |
                        <small> {{ $training_record->start_date }} - {{ $training_record->planned_end_date }}</small> |
                        <small>
                            @if($training_record->getOriginal('status_code') == App\Models\Training\TrainingRecord::STATUS_CONTINUING)
                            <span class="label label-md label-info arrowed-in arrowed-in-right">{{ $training_record->status_code }}</span>
                            @elseif($training_record->getOriginal('status_code') == App\Models\Training\TrainingRecord::STATUS_COMPLETED)
                            <span class="label label-md label-success arrowed-in arrowed-in-right">{{ $training_record->status_code }}</span>
                            @elseif($training_record->getOriginal('status_code') == App\Models\Training\TrainingRecord::STATUS_WITHDRAWN)
                            <span class="label label-md label-danger arrowed-in arrowed-in-right">{{ $training_record->status_code }}</span>
                            @elseif($training_record->getOriginal('status_code') == App\Models\Training\TrainingRecord::STATUS_TEMP_WITHDRAWN)
                            <span class="label label-md label-warning arrowed-in arrowed-in-right">{{ $training_record->status_code }}</span>
                            @else
                            <span class="label label-md label-info arrowed-in arrowed-in-right">{{ $training_record->status_code }}</span>
                            @endif
                        </small>
                    </h5>
                    <div class="widget-toolbar">
                        <a class="btn btn-xs btn-round btn-primary" href="{{ route('students.training.show', [$training_record->student, $training_record]) }}">
                            <i class="ace-icon fa fa-folder-open"></i> Open
                        </a>
                    </div>
                </div>
                <div class="widget-body">
                    <div class="widget-main">
                        <div class="row">
                            <div class="col-sm-4 center">
                                <div>
                                    <span class="profile-picture">
                                        <img class="avatar img-responsive"
                                        width="50px;" height="50px"
                                        src="{{ $training_record->student->avatar_url }}"
                                        alt="{{ $training_record->student->firstnames }}" />
                                    </span>
                                    <br>{{ $training_record->student->firstnames }} {{ $training_record->student->surname }}
                                    <br>
                                    @if ($training_record->student->isOnline())
                                    <label class="label label-success">Online</label>
                                    @else
                                    <label class="label label-default">Offline</label>
                                    @endif
                                    <br><i class="ace-icon fa fa-envelope bigger-120 pink"></i> {{ $training_record->student->primary_email }}
                                </div>
                            </div>
                            <div class="col-sm-8">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <tbody>
                                            @foreach($training_record->portfolios AS $portfolio)
                                            <tr>
                                                <td>
                                                    {{ $portfolio->qan }}<br>{{ $portfolio->title }}<br>
                                                </td>
                                                <td>
                                                    Start:&nbsp;{{ $portfolio->start_date }}<br>
                                                    Planned&nbsp;End:&nbsp;{{ $portfolio->planned_end_date }}<br>
                                                    Completed:&nbsp;{{ $portfolio->actual_end_date }}
                                                </td>
                                                <td>
						    @php
                                                    $portfolio_signed_off_pc_percentage = $portfolio->signedOffPCsPercentage();
                                                    @endphp
                                                    <div class="easy-pie-chart percentage" data-percent="{{ $portfolio_signed_off_pc_percentage }}" data-color="#CA5952">
                                                        <span class="percent">{{ $portfolio_signed_off_pc_percentage }}</span>%
                                                    </div>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div> {{-- table-responsive --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="space-6"></div>
            @empty
            <span class="alert alert-info">
                <h4>No training records found.</h4>
            </span>
            @endforelse
        </div>

        <div class="well well-sm">
            @include('partials.pagination', ['collection' => $training_records])
        </div>

        <!-- PAGE CONTENT ENDS -->
    </div><!-- /.col -->
</div><!-- /.row -->
@endsection

@section('page-plugin-scripts')
<script src="{{ asset('assets/js/jquery.easypiechart.min.js') }}"></script>
@endsection


@section('page-inline-scripts')
<script>
    $('.easy-pie-chart.percentage').each(function(){
        var barColor = '#50C878';
        var trackColor = '#E2E2E2';
        var size = parseInt($(this).data('size')) || 92;
        $(this).easyPieChart({
            barColor: barColor,
            trackColor: trackColor,
            scaleColor: false,
            lineCap: 'butt',
            lineWidth: parseInt(size/10),
            animate:{duration: 1500, enabled: true},
            size: size
        }).css('color', barColor);
    });

    @if(!\Auth::user()->isStudent())
    function exportView()
    {
        var form = document.forms["frmTrainingFilters"];
        var url = "{{ route('reports.portfolios.export', ['filters' => 'replace_filters']) }}";
        url = url.replace('replace_filters', $(form).serialize());
        window.location.href=url;
    }
    @endif
</script>
@endsection
