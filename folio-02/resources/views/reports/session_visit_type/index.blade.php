@extends('layouts.master')

@section('title', 'Visit Type Report')

@section('page-content')
    <div class="page-header">
        <h1>Visit Type Report</h1>
    </div><!-- /.page-header -->

    <div class="row">
        <div class="col-xs-12">
            <!-- PAGE CONTENT BEGINS -->
            @if (!\Auth::user()->isStudent())
                <div class="widget-box transparent ui-sortable-handle">
                    <div class="widget-header widget-header-small widget-header-no-bottom">
                        <h5 class="widget-title smaller">Search Filters</h5>
                        <div class="widget-toolbar">
                           
                            <a href="#" data-action="collapse"><i
                                    class="ace-icon fa fa-chevron-down bigger-125"></i></a>
                        </div>
                    </div>
                   @if(request()->filled('actual_date'))
                        @include('partials.filter_crumbs')
                    @endif
                    <div class="widget-body">
                        <div class="widget-main">
                            <small> @include('reports.session_visit_type.filter')</small>
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
                            <th>Learner Name</th>
                            <th>Start Time</th>
                            <th>End Time</th>
                            <th>Programme</th>
                            <th>Assessor</th>
                            <th>Role</th>
                            <th>IQA </th>
                            <th>Session date</th>
                            <th>Type</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($records AS $row)
                            <tr>
                                <td>{{ optional($row->training->student)->firstnames }} {{ optional($row->training->student)->surname }}</td>
                                <td>{{ $row->session_start_time ? date('H:i', strtotime($row->session_start_time)) : '' }}</td>
                                <td>{{ $row->session_end_time ? date('H:i', strtotime($row->session_end_time)) : '' }}</td>
                                <td>{{ optional($row->training->programme)->title }}</td>
                                <td>{{ optional($row->assessor)->firstnames }} {{ optional($row->assessor)->surname }}</td>
                                <td>{{ optional($row->assessor)->assessor_type }}</td>
                                <td>{{ optional($row->training->verifierUser)->firstnames }} {{ optional($row->training->student)->surname }}</td>
                                <td>{{ optional($row->actual_date)->format('d-m-Y') }}</td>
                                
                                <td>{{ ucwords(str_replace('_', ' ', $row->session_type)) }}</td>
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

