@extends('layouts.master')

@section('title', 'EQA Samples')

@section('page-plugin-styles')
    <link rel="stylesheet" href="{{ asset('assets/css/toastr.min.css') }}" />
@endsection

@section('breadcrumbs')
    {{ Breadcrumbs::render('eqa_samples.index') }}
@endsection

@section('page-content')
    <div class="page-header"><h1>EQA Samples</h1></div><!-- /.page-header -->
    <div class="row">
        <div class="col-xs-12">
            <button class="btn btn-sm btn-bold btn-primary btn-round" type="button" onclick="window.location.href='{{ route('eqa_samples.create') }}'">
                <i class="ace-icon fa fa-plus bigger-120"></i> Add New Sample
            </button>
            <div class="hr hr-12 hr-dotted"></div>
            @include('partials.session_message')
            <div class="widget-box transparent collapsed">
                <div class="widget-header widget-header-small">
                    <h5 class="widget-title smaller">Search Filters</h5>
                    <div class="widget-toolbar">
<!--                        <a title="Export view to Excel" href="{{ request()->url() . '/export' . str_replace(request()->url(), '', request()->fullUrl()) }}">
                            <i class="ace-icon fa fa-file-excel-o bigger-125"></i>
                        </a> &nbsp;-->
                        <a href="#" data-action="collapse"><i class="ace-icon fa fa-chevron-down bigger-125"></i></a>
                    </div>
                </div>
                <div class="widget-body">
                    <div class="widget-main small">
                        <small> @include('eqa_samples.filter')</small>
                    </div>
                </div>
            </div>
            <div class="table-header">List of EQA Samples</div>
            <div class="table-responsive">
                <table id="tblprogrammes" class="table table-bordered table-hover">
                    <thead>
                    <tr>
                        <th>ID</th><th>Title</th><th>Active From</th><th>Active To</th><th>Created By</th><th>EQA Personnels</th><th>Training Records</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($samples AS $sample)
                        <tr class="{{ $sample->active == 1 ? 'bg-info' : '' }}" onclick="window.location.href='{{ route('eqa_samples.edit', $sample) }}';"
                            onmouseover="if(window.viewrow_onmouseover){window.viewrow_onmouseover(this, arguments.length > 0 ? arguments[0] : window.event)};"
                            onmouseout="if(window.viewrow_onmouseout){window.viewrow_onmouseout(this, arguments.length > 0 ? arguments[0] : window.event)};">
                            <td>{{ $sample->id }}</td>
                            <td>{{ $sample->title }}</td>
                            <td>{{ \Carbon\Carbon::parse($sample->active_from)->format('d/m/Y') }}</td>
                            <td>{{ \Carbon\Carbon::parse($sample->active_to)->format('d/m/Y') }}</td>
                            <td>{{ $sample->creator->full_name }}</td>
                            <td>{{ $sample->eqa_personnels->count() }}</td>
                            <td>{{ $sample->training_records->count() }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="7">No sample found in the system.</td></tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
            <div class="well well-sm">
                @include('partials.pagination', ['collection' => $samples])
            </div>
        </div><!-- /.col -->
    </div><!-- /.row -->
@endsection

@section('page-plugin-scripts')
    <script src="{{ asset('assets/js/toastr.min.js') }}"></script>
@endsection
