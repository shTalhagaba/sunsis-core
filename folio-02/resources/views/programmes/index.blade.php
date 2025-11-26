@extends('layouts.master')

@section('title', 'Programmes')

@section('breadcrumbs')
    {{ Breadcrumbs::render('programmes.index') }}
@endsection

@section('page-content')
    <div class="page-header">
        <h1>Programmes</h1>
    </div><!-- /.page-header -->
    <div class="row">
        <div class="col-xs-12">
            @if (auth()->user()->can('create-programme'))
                <button class="btn btn-sm btn-bold btn-primary btn-round" type="button"
                    onclick="window.location.href='{{ route('programmes.create') }}'">
                    <i class="ace-icon fa fa-plus bigger-120"></i> Add New Programme
                </button>
            
                <div class="hr hr-12 hr-dotted"></div>
            @endif
            <div class="widget-box transparent ui-sortable-handle collapsed">
                <div class="widget-header widget-header-small">
                    <h5 class="widget-title smaller">Search Filters</h5>
                    <div class="widget-toolbar">
                        <a title="Export view to Excel"
                            href="{{ route('programmes.export') }}">
                            <i class="ace-icon fa fa-file-excel-o bigger-125"></i>
                        </a> &nbsp;
                        <a href="#" data-action="collapse"><i class="ace-icon fa fa-chevron-down bigger-125"></i></a>
                    </div>
                </div>
                @include('partials.filter_crumbs')
                <div class="widget-body">
                    <div class="widget-main small">
                        <small> @include('programmes.filter')</small>
                    </div>
                </div>
            </div>

            @include('partials.session_message')

            @include('partials.session_error')

            <div class="table-header">List of programmes</div>

            <div class="table-responsive">
                <table id="tblprogrammes" class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>Programme Type</th>
                            <th>Status</th>
                            <th>Qualifications Count</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($programmes AS $programme)
                            <tr onclick="window.location.href='{{ route('programmes.show', $programme) }}';"
                                onmouseover="if(window.viewrow_onmouseover){window.viewrow_onmouseover(this, arguments.length > 0 ? arguments[0] : window.event)};"onmouseout="if(window.viewrow_onmouseout){window.viewrow_onmouseout(this, arguments.length > 0 ? arguments[0] : window.event)};">
                                <td>{{ $programme->title }}</td>
                                <td>{{ optional($programme->programmeType)->description }}</td>
                                <td>{{ $programme->status == '1' ? 'Active' : 'Not Active' }}</td>
                                <td class="center">{{ $programme->qualifications_count }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9">No programme found in the system.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="well well-sm">
                @include('partials.pagination', ['collection' => $programmes])
            </div>
        </div><!-- /.col -->
    </div><!-- /.row -->
@endsection
