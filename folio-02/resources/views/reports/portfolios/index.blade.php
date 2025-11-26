@extends('layouts.master')

@section('title', request()->is('reports/sampling') ? 'Sampling Report' : 'Portfolios Report')

@section('page-content')
    <div class="page-header">
       
        @if(request()->is('reports/sampling'))
            <h1> Sampling Report</h1>
        @else
            <h1>Portfolios Report</h1>
        @endif
    </div><!-- /.page-header -->

    <div class="row">
        <div class="col-xs-12">
            <!-- PAGE CONTENT BEGINS -->
            @if (!\Auth::user()->isStudent())
                <div class="widget-box transparent ui-sortable-handle collapsed">
                    <div class="widget-header widget-header-small">
                        <h5 class="widget-title smaller">Search Filters</h5>
                        <div class="widget-toolbar">
                           @if(request()->is('reports/sampling'))
                                <a title="Export view to Excel" href="{{ route('reports.sampling.summary.export') }}">
                                    <i class="ace-icon fa fa-file-excel-o bigger-125"></i>
                                </a>&nbsp;
                            @else
                                <a title="Export view to Excel" href="{{ route('reports.portfolios.summary.export') }}">
                                    <i class="ace-icon fa fa-file-excel-o bigger-125"></i>
                                </a>&nbsp;
                            @endif
                            <a href="#" data-action="collapse"><i
                                    class="ace-icon fa fa-chevron-down bigger-125"></i></a>
                        </div>
                    </div>
                    @include('partials.filter_crumbs')
                    <div class="widget-body">
                        <div class="widget-main">
                            <small> @include('reports.portfolios.filter')</small>
                        </div>
                    </div>
                </div>
            @endif
            <div class="table-header">
                Showing <strong>{{ ($portfolios->currentpage() - 1) * $portfolios->perpage() + 1 }}</strong>
                to
                <strong>{{ $portfolios->currentpage() * $portfolios->perpage() > $portfolios->total() ? $portfolios->total() : $portfolios->currentpage() * $portfolios->perpage() }}</strong>
                of <strong>{{ $portfolios->total() }}</strong>
                entries
            </div>

            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Student Name</th>
                            <th>Primary Assessor</th>
                            <th>Secondary Assessor</th>
                            <th>Verifier</th>
                            <th>Programme</th>
                            <th>QAN</th>
                            @if(request()->is('reports/portfolios'))
                                <th>Awarding Body</th>
                                
                            @endif
                            <th>Title</th>
                            <th>Status</th>
                            <th>Outcome</th>
                            @if(request()->is('reports/sampling'))
                                <th>Date of Sample</th>
                                <th>Sampling Feedback</th>
                                <th>Sampling Type</th>
                            @endif
                            <th>Start Date</th>
                            <th>Planned End Date</th>
                            <th>Actual End Date</th>
                            <th>Total Units</th>
                            <th title="Total number of Performance Criteria of all units">Total PCs</th>
                            <th title="Signed off Performance Criteria of all units">Signed Off PCs</th>
                            <th>Progress</th>
                            <th>Units IQA Passed</th>
                            <th>Units IQA Referred</th>

                            @if(request()->is('reports/portfolios'))
                                <th title="Awarding Body Registration Number">AB Reg Number</th>
                                <th title="Awarding Body Registration Date">AB Reg Date</th>
                                <th title="Certificate Applied Date">Cert App Date</th>
                                <th title="Certification Received Date">Cert Rec Date</th>
                                <th>Cert Sent to Learner Date</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($portfolios AS $portfolio)
                            <tr>
                                <td>{{ $portfolio->full_name }}</td>
                                <td>{{ $portfolio->primary_assessor_name }}</td>
                                <td>{{ $portfolio->secondary_assessor_name }}</td>
                                <td>{{ $portfolio->verifier_name }}</td>
                                <td>{{ $portfolio->programme_title }}</td>
                                <td>{{ $portfolio->qan }}</td>
                                @if(request()->is('reports/portfolios'))
                                    <td>{{ \App\Models\LookupManager::getQualificationOwnersAcronym($portfolio->owner_org_rn) }}</td>
                                
                                @endif
                                <td>{{ $portfolio->title }}</td>
                                <td>{{ $portfolio->status_code }}</td>
                                <td>{{ $portfolio->learning_outcome != '' ? App\Models\Lookups\TrainingOutcomeLookup::getDescription($portfolio->learning_outcome) : '' }}</td>
                                @if(request()->is('reports/sampling'))
                                    <td>{{ $portfolio->sample_date }}</td>
                                    <td>{{ $portfolio->iqa_comment }}</td>
                                    <td>{{ $portfolio->iqa_type }}</td>
                                @endif

                               
                                <td>{{ $portfolio->start_date }}</td>
                                <td>{{ $portfolio->planned_end_date }}</td>
                                <td>{{ $portfolio->actual_end_date ? $portfolio->actual_end_date : '' }}</td>
                                <td class="text-center">{{ $portfolio->units->count() }}</td>
                                <td class="text-center">{{ $portfolio->total_pcs }}</td>
                                <td class="text-center">{{ $portfolio->signed_off_pcs }}</td>
                                <td class="text-center">
                                    {{ $portfolio->total_pcs > 0 ? round(($portfolio->signed_off_pcs / $portfolio->total_pcs) * 100) : 0 }}%
                                </td>
                                <td class="text-center">{{ $portfolio->iqa_passed_units }}</td>
                                <td class="text-center">{{ $portfolio->iqa_referred_units }}</td>
                                @if(request()->is('reports/portfolios'))
                                    <td>{{ $portfolio->ab_registration_number }}</td>
                                    <td>{{ $portfolio->ab_registration_date ? Carbon\Carbon::parse($portfolio->ab_registration_date)->format('d/m/Y') : '' }}</td>
                                    <td>{{ $portfolio->cert_applied ? Carbon\Carbon::parse($portfolio->cert_applied)->format('d/m/Y') : '' }}</td>
                                    <td>{{ $portfolio->cert_received ? Carbon\Carbon::parse($portfolio->cert_received)->format('d/m/Y') : '' }}</td>
                                    <td>{{ $portfolio->cert_sent_to_learner ? Carbon\Carbon::parse($portfolio->cert_sent_to_learner)->format('d/m/Y') : '' }}</td>
                                @endif
                            </tr>
                        @empty
                            <tr><td colspan="15"><h4>No records found.</h4></td></tr>                            
                        @endforelse
                    </tbody>
                </table>                
            </div>

            <div class="well well-sm">
                @include('partials.pagination', ['collection' => $portfolios])
            </div>

            <!-- PAGE CONTENT ENDS -->
        </div><!-- /.col -->
    </div><!-- /.row -->
@endsection

