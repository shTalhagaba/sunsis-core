@extends('layouts.master')

@section('title', 'Qualifications')

@section('breadcrumbs')
    {{ Breadcrumbs::render('qualifications.index') }}
@endsection

@section('page-content')
    <div class="page-header">
        <h1>Qualifications</h1>
    </div><!-- /.page-header -->
    <div class="row">

        <div class="col-xs-12">
            <!-- PAGE CONTENT BEGINS -->

            @can('create-qualification')
                <div class="clearfix">
                    <div class="pull-left tableTools-container">
                        <button class="btn btn-sm btn-bold btn-primary btn-round" type="button"
                            onclick="window.location.href='{{ route('qualifications.create') }}'">
                            <i class="ace-icon fa fa-plus bigger-120"></i> Add New Qualification
                        </button>
                    </div>
                     <div class="pull-right tableTools-container">
                        <button class="btn btn-sm btn-bold btn-default btn-round btn-white" type="button"
                            onclick="window.location.href='{{ route('download_qualification.index') }}'">
                            <i class="ace-icon fa fa-download bigger-120"></i> Download Qualification
                        </button>
                    </div>
                </div>
                <div class="hr hr-12 hr-dotted"></div>
            @endcan

            @include('partials.session_error')
            @include('partials.session_message')

            <div class="widget-box transparent ui-sortable-handle collapsed">
                <div class="widget-header widget-header-small">
                    <h5 class="widget-title smaller">
                        Search Filters
                    </h5>
                    
                    <div class="widget-toolbar">
                        <a title="Export view to Excel"
                            href="{{ route('qualifications.export') }}">
                            <i class="ace-icon fa fa-file-excel-o bigger-125"></i>
                        </a> &nbsp;
                        <a href="#" data-action="collapse"><i class="ace-icon fa fa-chevron-down bigger-125"></i></a>
                    </div>
                </div>
                @include('partials.filter_crumbs')
                <div class="widget-body">
                    <div class="widget-main">
                        <small>@include('qualifications.filter')</small>
                    </div>
                </div>
            </div>

            <div class="table-header">
                List of qualifications
            </div>

            <div class="table-responsive">
                <table id="tblQualifications" class="table table-striped table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>QAN</th>
                            <th>Owner</th>
                            <th>Level</th>
                            <th>Sector Subject Area</th>
                            <th>Total Units</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($qualifications AS $q)
                            <tr onclick="window.location.href='{{ route('qualifications.show', $q) }}';"
                                onmouseover="if(window.viewrow_onmouseover){window.viewrow_onmouseover(this, arguments.length > 0 ? arguments[0] : window.event)};"onmouseout="if(window.viewrow_onmouseout){window.viewrow_onmouseout(this, arguments.length > 0 ? arguments[0] : window.event)};">
                                <td>{{ $q->title }}</td>
                                <td>{{ $q->qan }}</td>
                                <td title="{{ $q->owner_org_name }}">{{ $q->owner_org_acronym }}</td>
                                <td>{{ $q->level }}</td>
                                <td>{{ $q->ssa }}</td>
                                <td align="center">{{ $q->units_count }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7">No qualification found in the system.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="well well-sm">
                @include('partials.pagination', ['collection' => $qualifications])
            </div>

            <!-- PAGE CONTENT ENDS -->
        </div><!-- /.col -->
    </div><!-- /.row -->
@endsection
