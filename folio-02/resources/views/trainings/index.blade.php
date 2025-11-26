@extends('layouts.master')

@section('title', 'Training Records')

@section('page-content')
    <div class="page-header">
        <h1>Training Records</h1>
    </div><!-- /.page-header -->

    <div class="row">
        <div class="col-xs-12">
            <!-- PAGE CONTENT BEGINS -->
            @if (!\Auth::user()->isStudent())
                <div class="widget-box transparent ui-sortable-handle collapsed">
                    <div class="widget-header widget-header-small">
                        <h5 class="widget-title smaller">Search Filters</h5>
                        <div class="widget-toolbar">
                            <a title="Export view to Excel" href="{{ route('trainings.export', ['check_over_due' => $check_over_due]) }}">
                                <i class="ace-icon fa fa-file-excel-o bigger-125"></i>
                            </a> &nbsp;
                            <a href="#" data-action="collapse"><i
                                    class="ace-icon fa fa-chevron-down bigger-125"></i></a>
                        </div>
                    </div>
                    @include('partials.filter_crumbs')
                    <div class="widget-body">
                        <div class="widget-main">
                            <small> @include('trainings.filter')</small>
                        </div>
                    </div>
                </div>
            @endif
            <div class="table-header">
                List of training records <br>
                @if ($trainings->count() > 0)
                Showing <strong>{{ ($trainings->currentpage() - 1) * $trainings->perpage() + 1 }}</strong>
                to
                <strong>{{ $trainings->currentpage() * $trainings->perpage() > $trainings->total() ? $trainings->total() : $trainings->currentpage() * $trainings->perpage() }}</strong>
                of <strong>{{ $trainings->total() }}</strong>
                entries
                @endif
            </div>

            <div class="table-responsive">
                @forelse($trainings AS $training)
                    <div class="widget-box" style="border-radius:3px">
                        <div class="widget-header">
                            <h5 class="widget-title">
                                {{ $training->student->surname }}, {{ $training->student->firstnames }} |
                                <small> {{ $training->start_date->format('d/m/Y') }} -
                                    {{ $training->planned_end_date->format('d/m/Y') }}</small> |
                                <small>
                                    @include('trainings.partials.tr_status_description')
                                </small>
                                @if ($training->isContinuing() && $training->start_date->isPast())
                                 | <small class="text-info">({{ $training->start_date->diffInDays(now()) }} days elapsed)</small>
                                @endif
                            </h5>
                            <div class="widget-toolbar">
                                <a class="btn btn-xs btn-round btn-primary" href="{{ route('trainings.show', $training) }}">
                                    <i class="ace-icon fa fa-folder-open"></i> View Record
                                </a>
                            </div>
                        </div>
                        <div class="widget-body">
                            <div class="widget-main">
                                <div class="row">
                                    <div class="col-sm-4 center">
                                        <div class="pull-left" style="margin-right: 2%">
                                            <span class="profile-picture" style="width: 100px;">
                                                <img class="img-responsive" alt="{{ $training->student->firstnames}}'s Avatar" id="avatar2" src="{{ asset($training->student->avatar_url) }}" />
                                            </span>
                                            <br>
                                            @include('partials.user_login_status', ['user' => $training->student])
                                        </div>
                                        <div>
                                            <strong class="bigger-225 grey lighter">{{ $training->student->full_name }}</strong><br>
                                            {{ $training->student->primary_email }}<br>
                                            {{ $training->learner_ref }}<br>
                                            {{ $training->programme->title }}<br>
                                        </div>
                                    </div>
                                    <div class="col-sm-8">
                                        <div class="table-responsive">
                                            <table class="table table-bordered">
                                                <tbody>
                                                    @foreach ($training->portfolios as $portfolio)
                                                        <tr>
                                                            <td style="width: 65%">
                                                                {{ $portfolio->qan }}<br>{{ $portfolio->title }}<br>
                                                            </td>
                                                            <td style="width: 20%">
                                                                Start:&nbsp;{{ $portfolio->start_date }}<br>
                                                                Planned&nbsp;End:&nbsp;{{ $portfolio->planned_end_date }}<br>
                                                                Actual End Date:&nbsp;{{ $portfolio->actual_end_date }}
                                                            </td>
                                                            <td style="width: 15%">
                                                                @php
                                                                    $portfolio_signed_off_pc_percentage = $portfolio->signedOffPCsPercentage();
                                                                @endphp
                                                                <div class="easy-pie-chart percentage"
                                                                    data-percent="{{ $portfolio_signed_off_pc_percentage }}"
                                                                    data-color="#CA5952">
                                                                    <span
                                                                        class="percent">{{ $portfolio_signed_off_pc_percentage }}</span>%
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div> {{-- table-responsive --}}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="space-6"></div>
                @empty
                <h4 class="alert alert-info">No training records found.</h4>
                @endforelse
            </div>

            <div class="well well-sm">
                @include('partials.pagination', ['collection' => $trainings])
            </div>

            <!-- PAGE CONTENT ENDS -->
        </div><!-- /.col -->
    </div><!-- /.row -->
@endsection

@section('page-plugin-scripts')
    <script src="{{ asset('assets/js/jquery.easypiechart.min.js') }}"></script>
@endsection


@section('page-inline-scripts')
    <script>
        $('.easy-pie-chart.percentage').each(function() {
            var barColor = '#50C878';
            var trackColor = '#E2E2E2';
            var size = parseInt($(this).data('size')) || 92;
            $(this).easyPieChart({
                barColor: barColor,
                trackColor: trackColor,
                scaleColor: false,
                lineCap: 'butt',
                lineWidth: parseInt(size / 10),
                animate: {
                    duration: 1500,
                    enabled: true
                },
                size: size
            }).css('color', barColor);
        });       
    
    </script>
@endsection
