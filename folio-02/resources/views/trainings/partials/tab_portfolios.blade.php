@can('update-training-record')
<p>
    <span class="btn btn-primary btn-xs btn-round pull-right" onclick="window.location.href='{{ route('trainings.portfolios.create', $training) }}'">
        <i class="fa fa-plus"></i> Add Portfolios
    </span>
</p>
<div class="space-6"></div>
<div class="space-12"></div>
@endcan

<div class="tabbable">
    <ul id="subTab" class="nav nav-tabs padding-18">
        @foreach($portfolios AS $portfolio)
        <li class="{{ $loop->first ? 'active' : '' }}">
            <a class="subTabbedLink" href="#tab{{ str_replace(' ', '', $portfolio->qan) }}" data-toggle="tab" class="linkPortfolioTab">
                <i class="fa fa-graduation-cap"></i>
                {{ $portfolio->qan }} <span class="badge badge-info">{{ $portfolio->signedOffUnits() }}/{{ $portfolio->units->count() }}</span>
            </a>
        </li>
        @endforeach
    </ul>

    <div class="tab-content">
        @foreach($portfolios AS $portfolio)
        <div class="tab-pane {{ $loop->first ? 'in active' : '' }}" id="tab{{ str_replace(' ', '', $portfolio->qan) }}">
            <div class="row">
                <div class="col-xs-12">
                    @include('trainings.partials.entity_progress_bar', ['entity' => $portfolio, 'extraProgressBarClasses' => 'progress-striped'])
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12">
                    @if(auth()->user()->isStaff())
                        @can('signoff-progress')
                            <span class="btn btn-success btn-xs btn-round" data-rel="tooltip" title="Signoff the progress for this qualification."
                            onclick="window.location.href='{{ route('trainings.portfolios.signoffProgress', [$training, $portfolio]) }}'">
                                <i class="fa fa-check-circle"></i> Signoff Progress
                            </span>
                        @endcan
                        @can('update-training-record')
                            <div class="btn-group pull-right">
                                <button data-toggle="dropdown" class="btn btn-primary btn-xs dropdown-toggle btn-round" aria-expanded="false">
                                    <i class="fa fa-edit"></i> Manage Elements
                                    <i class="ace-icon fa fa-angle-down icon-on-right"></i>
                                </button>

                                <a href="{{ route('reports.gap-analysis', [$training, $portfolio]) }}" class="btn btn-warning btn-xs btn-round">
                                    <i class="fa fa-chart-line"></i> Gap Analysis
                                </a> 

                                <ul class="dropdown-menu">
                                    <li>
                                        <a href="{{ route('trainings.portfolios.edit', [$training, $portfolio]) }}?subaction=add_elements"><i class="fa fa-plus"></i> Add Elements</a>
                                    </li>
                                    <li>
                                        <a href="{{ route('trainings.portfolios.edit', [$training, $portfolio]) }}?subaction=remove_elements"><i class="fa fa-minus"></i> Remove Elements</a>
                                    </li>
                                    <li class="divider"></li>
                                    @if (!in_array($portfolio->id, $disabledDeletePortfolioIds) && auth()->user()->can('delete-training-record'))
                                    {!! Form::open(['method' => 'DELETE', 'url' => route('trainings.portfolios.destroy', [$training, $portfolio]), 'style' => 'display: inline;', 'class' => 'form-inline', 'id' => 'frmDeleteTR' ]) !!}
                                        <li class="{{ in_array($portfolio->id, $disabledDeletePortfolioIds) ? 'disabled' : '' }} text-center">
                                            {!! Form::button('<i class="ace-icon fa fa-trash-o "></i> Delete', ['class' => 'btn btn-xs btn-danger btn-round btnDeletePortfolio', 'type' => 'submit', 'style' => 'display: inline']) !!}
                                        </li>
                                    {!! Form::close() !!}
                                    @endif
                                </ul>
                            </div>
                        @endcan
                    @endif
                </div>
            </div>
            <div class="row">
                <div class="col-sm-10">
                    <h4 class=" bolder"><i class="fa fa-graduation-cap"></i> {{ $portfolio->title }}</h4>
                    <span class="text-info bolder">Status:</span> {{ App\Models\Lookups\PortfolioStatusLookup::getDescription($portfolio->getOriginal('status_code')) }} | 
                    <span class="text-info bolder">Start Date:</span> {{ $portfolio->start_date }} | 
                    <span class="text-info bolder">Planned End Date:</span> {{ $portfolio->planned_end_date }} | 
                    @if($portfolio->actual_end_date)
                        <span class="text-info bolder">Actual End Date:</span> {{ $portfolio->actual_end_date }} |  
                    @endif
                    @if($portfolio->tutor)
                        <span class="text-info bolder">Tutor Name:</span> {{ $portfolio->tutor->full_name }} | 
                    @endif
                    @if($portfolio->verifier)
                        <span class="text-info bolder">Verifier Name:</span> {{ optional($portfolio->verifier)->full_name }} | 
                    @endif
                    <br>
                    @if($portfolio->ab_registration_number)
                        <span class="text-info bolder"><abbr title="Awarding Body registration Number">AB Reg.</abbr>:</span> {{ $portfolio->ab_registration_number }} | 
                    @endif
                    @if($portfolio->ab_registration_date)
                        <span class="text-info bolder"><abbr title="Awarding Body registration Date">AB Reg. Date</abbr>:</span> {{ Carbon\Carbon::parse($portfolio->ab_registration_date)->format('d/m/Y') }} | 
                    @endif
                    @if($portfolio->batch_no)
                        <span class="text-info bolder">Batch No:</span> {{ $portfolio->batch_no }} | 
                    @endif
                    @if($portfolio->certificate_no)
                        <span class="text-info bolder">Certificate No:</span> {{ $portfolio->certificate_no }}<br> 
                    @endif
                    @if($portfolio->cert_applied)
                        <span class="text-info bolder">Certificate Applied:</span> {{ Carbon\Carbon::parse($portfolio->cert_applied)->format('d/m/Y') }} | 
                    @endif
                    @if($portfolio->cert_received)
                        <span class="text-info bolder">Certificate Received:</span> {{ Carbon\Carbon::parse($portfolio->cert_received)->format('d/m/Y') }} | 
                    @endif
                    @if($portfolio->cert_sent_to_learner)
                        <span class="text-info bolder">Certificate Sent:</span> {{ Carbon\Carbon::parse($portfolio->cert_sent_to_learner)->format('d/m/Y') }} | 
                    @endif
                </div>
                <div class="col-sm-2">
                    <div class="pull-right">
                        <span class="btn btn-app btn-sm btn-success no-hover">
                            <span class="line-height-1 bigger-170"> {{ $portfolio->signedOffUnits() }}/{{ $portfolio->units->count() }}</span><br>
                            <span class="line-height-1 smaller-90"> Units </span>
                        </span>
                        <br>
                        <span data-rel="tooltip" title="Number of mandatory units" class="badge badge-success">M: {{ $portfolio->units->where('unit_group', 1)->count() }}</span>
                        <span data-rel="tooltip" title="Number of optional units" class="badge badge-info">O: {{ $portfolio->units->where('unit_group', 2)->count() }}</span>
                    </div>
                </div>
            </div>

            

            <div class="row">
                <div class="col-sm-4 center"><div class="hr hr-12 hr-dotted"></div></div>
                <div class="col-sm-4 center"><h4 class="bolder text-info">Units of this portfolio</h4></div>
                <div class="col-sm-4 center"><div class="hr hr-12 hr-dotted"></div></div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    @foreach($portfolio->units AS $unit)
                    @include('trainings.partials.unit')
                    @endforeach
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>