@extends('layouts.master')

@section('title', 'OTJ Hours Report')

@section('page-content')
    <div class="page-header">
        <h1>Off-the-Job Hours Report</h1>
    </div><!-- /.page-header -->

    <div class="row">
        <div class="col-xs-12">
            <!-- PAGE CONTENT BEGINS -->
            @if (!\Auth::user()->isStudent())
                <div class="widget-box transparent ui-sortable-handle collapsed">
                    <div class="widget-header widget-header-small">
                        <h5 class="widget-title smaller">Search Filters</h5>
                        <div class="widget-toolbar">
                            <a title="Export view to Excel" href="{{ route('reports.otj.export') }}" <i
                                class="ace-icon fa fa-file-excel-o bigger-125"></i>
                            </a> &nbsp;
                            <a href="#" data-action="collapse"><i
                                    class="ace-icon fa fa-chevron-down bigger-125"></i></a>
                        </div>
                    </div>
                    @include('partials.filter_crumbs')
                    <div class="widget-body">
                        <div class="widget-main">
                            <small> @include('reports.otj.filter')</small>
                        </div>
                    </div>
                </div>
            @endif
            <div class="table-header">
                Showing <strong>{{ ($records->currentpage() - 1) * $records->perpage() + 1 }}</strong>
                to
                <strong>{{ $records->currentpage() * $records->perpage() > $records->total() ? $records->total() : $records->currentpage() * $records->perpage() }}</strong>
                of <strong>{{ $records->total() }}</strong>
                entries
            </div>

            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Student Name</th>
                            <th>Programme</th>
                            <th>Employer</th>
                            <th>Training Status</th>
                            <th>Start Date</th>
                            <th>Planned End Date</th>
                            <th>Actual End Date</th>
                            <th>Primary Assessor</th>
                            <th>Secondary Assessor</th>
                            <th>Verifier</th>
                            <th>OTJ Hours Due</th>
                            <th>OTJ Hours Actual</th>
                            <th>OTJ Progress</th>
                            <th>Last OTJ Activity Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($records AS $training)
                            <tr>
                                <td>{{ $training->student->full_name }}</td>
                                <td>{{ $training->programme->title }}</td>
                                <td>{{ optional($training->employer)->legal_name }}</td>
                                <td>{{ App\Models\Lookups\TrainingStatusLookup::getDescription($training->status_code) }}</td>
                                <td>{{ $training->start_date->format('d/m/Y') }}</td>
                                <td>{{ $training->planned_end_date->format('d/m/Y') }}</td>
                                <td>{{ optional($training->actual_end_date)->format('d/m/Y') }}</td>
                                <td>{{ $training->primaryAssessor->full_name }}</td>
                                <td>{{ optional($training->secondaryAssessor)->full_name }}</td>
                                <td>{{ optional($training->verifierUser)->full_name }}</td>
                                <td>{{ $training->otj_hours_due }}</td>
                                <td>{{ $training->otj_hours_actual }}</td>
                                <td>{{ (int)$training->otj_hours_due > 0 ? $training->otj_progress : '' }}</td>
                                <td>{{ $training->latest_otj_activity_date }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="15"><h4>No records found.</h4></td></tr>                            
                        @endforelse
                    </tbody>
                </table>                
            </div>

            <div class="well well-sm">
                @include('partials.pagination', ['collection' => $records])
            </div>

            <!-- PAGE CONTENT ENDS -->
        </div><!-- /.col -->
    </div><!-- /.row -->
@endsection

