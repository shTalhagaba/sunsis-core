@extends('layouts.master')

@section('title', 'Reports')

@section('breadcrumbs')
{{ Breadcrumbs::render('dashboard.showDrillDown') }}
@endsection

@section('page-content')

<div class="page-header"><h1>Report</h1></div>

<div class="row">
    <div class="col-xs-12">

        {{-- <div class="widget-box transparent collapsed">
            <div class="widget-header widget-header-small">
                <h5 class="widget-title smaller">Search Filters</h5>
                <div class="widget-toolbar">
                    <a title="Export view to Excel" href="{{ request()->url() . '/export' . str_replace(request()->url(), '', request()->fullUrl()) }}">
                        <i class="ace-icon fa fa-file-excel-o bigger-125"></i>
                    </a> &nbsp;
                    <a href="#" data-action="collapse"><i class="ace-icon fa fa-chevron-down bigger-125"></i></a>
                </div>
            </div>
            <div class="widget-body">
                <div class="widget-main small">
                    <small> </small>
                </div>
            </div>
        </div> --}}

        <div class="table-responsive">
            <table id="tblRecords" class="table table-striped table-bordered table-hover">
                <thead>
                    <tr>
                        <th>Name</th><th>Start Date</th><th>Planned End Date</th><th>Actual End Date</th><th>Status</th><th>Last Logged In</th><th>Number of Evidences to Assess</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($trainings AS $training)
                    <tr onclick="window.location.href='{{ route('trainings.show', $training) }}';" onmouseover="if(window.viewrow_onmouseover){window.viewrow_onmouseover(this, arguments.length > 0 ? arguments[0] : window.event)};"onmouseout="if(window.viewrow_onmouseout){window.viewrow_onmouseout(this, arguments.length > 0 ? arguments[0] : window.event)};">
                        <td>{{ $training->student->firstnames }} {{ $training->student->surname }}</td>
                        <td>{{ $training->start_date->format('d/m/Y') }}</td>
                        <td>{{ $training->planned_end_date->format('d/m/Y') }}</td>
                        <td>{{ optional($training->actual_end_date)->format('d/m/Y') }}</td>
                        <td>
                            <small>
                                @if($training->getOriginal('status_code') == App\Models\Lookups\TrainingStatusLookup::STATUS_CONTINUING)
                                <span class="label label-md label-info arrowed-in arrowed-in-right">{{ $training->trainingStatus->description }}</span>
                                @elseif($training->getOriginal('status_code') == App\Models\Lookups\TrainingStatusLookup::STATUS_COMPLETED)
                                <span class="label label-md label-success arrowed-in arrowed-in-right">{{ $training->trainingStatus->description }}</span>
                                @elseif($training->getOriginal('status_code') == App\Models\Lookups\TrainingStatusLookup::STATUS_WITHDRAWN)
                                <span class="label label-md label-danger arrowed-in arrowed-in-right">{{ $training->trainingStatus->description }}</span>
                                @else
                                <span class="label label-md label-warning arrowed-in arrowed-in-right">{{ $training->trainingStatus->description }}</span>
                                @endif
                            </small>
                        </td>
                        <td>{{ $training->student->latestAuth->login_at ?? '' }}</td>
                        <td class="center">{{ $training->evidence_count }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="9">No record found in the system.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

    </div><!-- /.col -->
</div>

@endsection

@section('page-plugin-scripts')
<script src="{{ asset('assets/js/jquery.easypiechart.min.js') }}"></script>
@endsection


@section('page-inline-scripts')
<script>

</script>
@endsection
