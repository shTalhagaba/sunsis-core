@extends('layouts.master')

@section('title', 'IQA Sample Plan')

@section('page-content')
    <div class="page-header">
        <h1>IQA Sample Plans</h1>
    </div><!-- /.page-header -->
    <div class="row">
        <div class="col-xs-12">
            <button class="btn btn-sm btn-bold btn-primary btn-round" type="button"
                onclick="window.location.href='{{ route('iqa_sample_plans.create') }}'">
                <i class="ace-icon fa fa-plus bigger-120"></i> Create New Sample
            </button>

            <div class="hr hr-12 hr-dotted"></div>

            <div class="widget-box transparent ui-sortable-handle collapsed">
                <div class="widget-header widget-header-small">
                    <h5 class="widget-title smaller">Search Filters</h5>
                    <div class="widget-toolbar">
                        <a title="Export view to Excel"
                            href="{{ route('iqa_sample_plans.export') }}">
                            <i class="ace-icon fa fa-file-excel-o bigger-125"></i>
                        </a> &nbsp;
                        <a href="#" data-action="collapse"><i class="ace-icon fa fa-chevron-down bigger-125"></i></a>
                    </div>
                </div>
                @include('partials.filter_crumbs')
                <div class="widget-body">
                    <div class="widget-main small">
                        <small> @include('iqa.sample.filter')</small>
                    </div>
                </div>
            </div>

            @include('partials.session_message')

            @include('partials.session_error')

            <div class="table-header">List of IQA Sample Plans</div>

            <div class="table-responsive">
                <table id="tblSamplePlans" class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Title</th>
                            <th>IQA Personnel</th>
                            <th>Programme</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th>Complete By</th>
                            <th>Number of Units</th>
                            <th>Number of Learners</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($plans AS $plan)
                        <tr onclick="window.location.href='{{ route('iqa_sample_plans.show', $plan) }}';"
                            onmouseover="if(window.viewrow_onmouseover){window.viewrow_onmouseover(this, arguments.length > 0 ? arguments[0] : window.event)};"onmouseout="if(window.viewrow_onmouseout){window.viewrow_onmouseout(this, arguments.length > 0 ? arguments[0] : window.event)};">
                                <td>{{ $plan->title }}</td>
                                <td>{{ $plan->verifier->full_name }}</td>
                                <td>{{ $plan->programme->title }}</td>
                                <td>{{ ucwords($plan->type) }}</td>
                                <td>{!! $plan->getStatusLabel() !!}</td>
                                <td>
                                    {{ optional($plan->completed_by_date)->format('d/m/Y') }}
                                    @include('iqa.sample.remaining_days', ['plan' => $plan])
                                </td>
                                <td>{{ $plan->units_count }}</td>
                                <td>{{ $plan->trainings_count }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9">No records found in the system.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="well well-sm">
                @include('partials.pagination', ['collection' => $plans])
            </div>
        </div><!-- /.col -->
    </div><!-- /.row -->
@endsection

@push('after-scripts')

    <script>
        
        
    </script>
@endpush
