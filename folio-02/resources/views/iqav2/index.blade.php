@extends('layouts.master')

@section('title', 'IQA Plans')

@section('page-content')
    <div class="page-header">
        <h1>IQA Plans</h1>
    </div><!-- /.page-header -->
    <div class="row">
        <div class="col-xs-12">
            @if (!\Auth::user()->isQualityManager())
                <button class="btn btn-sm btn-bold btn-primary btn-round" type="button"
                    onclick="window.location.href='{{ route('iqa_sample_plans.create') }}'">
                    <i class="ace-icon fa fa-plus bigger-120"></i> Create New Plan
                </button>

                <div class="hr hr-12 hr-dotted"></div>
            @endif

            <div class="widget-box transparent ui-sortable-handle collapsed">
                <div class="widget-header widget-header-small">
                    <h5 class="widget-title smaller">Search Filters</h5>
                    <div class="widget-toolbar">

                        <a href="#" data-action="collapse"><i class="ace-icon fa fa-chevron-down bigger-125"></i></a>
                    </div>
                </div>
                @include('partials.filter_crumbs')
                <div class="widget-body">
                    <div class="widget-main small">
                        <small> @include('iqav2.filter')</small>
                    </div>
                </div>
            </div>

            @include('partials.session_message')

            @include('partials.session_error')

            <div class="table-header">List of IQA Plans</div>

            <div class="table-responsive">
                <table id="tblPlans" class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>Learning Aim Title</th>
                            <th>Learning Aim QAN</th>
                            <th>IQA</th>
                            <th>Assessor</th>
                            <th>Created By</th>
                            <th>Created At</th>
                            <th>Updated At</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($plans AS $plan)
                        <tr onclick="window.location.href='{{ route('iqa_sample_plans.show', $plan) }}';"
                            onmouseover="if(window.viewrow_onmouseover){window.viewrow_onmouseover(this, arguments.length > 0 ? arguments[0] : window.event)};"onmouseout="if(window.viewrow_onmouseout){window.viewrow_onmouseout(this, arguments.length > 0 ? arguments[0] : window.event)};">
                                <td>{{ $plan->learning_aim_title }}</td>
                                <td>{{ $plan->learning_aim_qan }}</td>
                                <td>{{ $plan->verifier->full_name }}</td>
                                <td>{{ $plan->assessor->full_name }}</td>
                                <td>{{ $plan->creator->full_name }}</td>
                                <td>{{ optional($plan->created_at)->format('d/m/Y H:i:s') }}</td>
                                <td>{{ optional($plan->updated_at)->format('d/m/Y H:i:s') }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7">No records found in the system.</td>
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
