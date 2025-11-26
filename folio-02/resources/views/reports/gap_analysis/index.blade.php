@extends('layouts.master')

@section('title', 'Visit Type Report')

@section('page-content')
    <div class="page-header">
        <h1>Gap Analysis</h1>
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
                   {{-- @if(request()->filled('actual_date'))
                        @include('partials.filter_crumbs')
                    @endif
                    <div class="widget-body">
                        <div class="widget-main">
                            <small> @include('reports.session_visit_type.filter')</small>
                        </div>
                    </div> --}}
                </div>
            @endif
            {{-- <div class="table-header">
                Showing <strong>{{ ($records->currentpage() - 1) * $records->perpage() + 1 }}</strong>
                to
                <strong>{{ $records->currentpage() * $records->perpage() > $records->total() ? $records->total() : $records->currentpage() * $records->perpage() }}</strong>
                of <strong>{{ $records->total() }}</strong>
                entries
            </div> --}}

            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Unit Title</th>
                            <th>PC Reference</th>
                            <th>PC Title</th>
                            <th>Minimum Req</th>
                            <th>Evidences</th>
                            <th>Count of Evidences Submitted</th>
                            <th>Evidence Status </th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($records AS $row)

                            <tr>
                                <td>{{ $row->pc_id }}</td>
                                <td>{{ $row->title }}</td>
                                <td>{{ $row->reference }}</td>
                                <td>{{ $row->pc_title }}</td>
                                <td>{{ $row->min_req_evidences }}</td>
                                <td> </td>
                                <td>{{ $row->mapped_evidences->count() }}</td>
                                <td>{{ $row->mapped_evidences->pluck('evidence_status')->implode(', ') }}</td>
                                <td></td>
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

