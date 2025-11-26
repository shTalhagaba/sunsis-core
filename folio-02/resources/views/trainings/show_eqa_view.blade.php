@extends('layouts.master')
@section('title', 'Training Record')
@section('page-plugin-styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">

    <style>
        .modal {
            display:    none;
            position:   fixed;
            z-index:    1000;
            top:        0;
            left:       0;
            height:     100%;
            width:      100%;
            background: rgba( 255, 255, 255, .8 )
            url('{{ asset('images/ajax-loader.gif') }}')
            50% 50%
            no-repeat;
        }

        body.loading .modal {
            overflow: hidden;
        }

        body.loading .modal {
            display: block;
        }

        .popover{
            max-width:600px;
        }
    </style>
@endsection

@section('breadcrumbs')

@endsection

@section('page-content')
    @include('trainings.partials.tr_header')
    <!-- /.page-header -->
    <div class="row">
        <div class="col-xs-12">
            <!-- PAGE CONTENT BEGINS -->

            <div class="row">
                <div class="col-sm-1 col-xs-12">
                    <div style="">
                        <img class="img-responsive img-thumbnail" alt="{{ $student->firstnames}}'s Avatar" id="avatar2" src="{{ asset($student->avatar_url) }}" />
                    </div>
                    @if ($student->isOnline())
                        <label class="label label-success">Online</label>
                    @else
                        <label class="label label-default">Offline</label>
                    @endif
                </div>
                <div class="col-sm-3 col-xs-12">
                    <strong class="lead">{{ $student->full_name }}</strong>
                    <div class="info-div info-div-striped">
                        <div class="info-div-row">
                            <div class="info-div-name">Primary <i class="ace-icon fa fa-envelope blue"></i></div>
                            <div class="info-div-value">{{ $student->primary_email }}</div>
                        </div>
                        <div class="info-div-row">
                            <div class="info-div-name">Home <i class="ace-icon fa fa-mobile fa-lg blue"></i></div>
                            <div class="info-div-value">{{ $homeAddress->mobile }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4 col-xs-12">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tr><th>Start Date</th><th>Planned End Date</th><th>Actual End Date</th><th>Learner Reference</th></tr>
                            <tr>
                                <td>
                                    {{ $training->start_date }}
                                </td>
                                <td>
                                    {{ $training->planned_end_date }}
                                </td>
                                <td>
                                    {{ \Carbon\Carbon::parse($training->actual_end_date)->format('d/m/Y') }}
                                    <small>
                                        @if($training->getOriginal('status_code') == App\Models\Lookups\TrainingStatusLookup::STATUS_CONTINUING)
                                            <span class="label label-md label-info arrowed-in arrowed-in-right">{{ $training->status_code }}</span>
                                        @elseif($training->getOriginal('status_code') == App\Models\Lookups\TrainingStatusLookup::STATUS_COMPLETED)
                                            <span class="label label-md label-success arrowed-in arrowed-in-right">{{ $training->status_code }}</span>
                                        @elseif($training->getOriginal('status_code') == App\Models\Lookups\TrainingStatusLookup::STATUS_WITHDRAWN)
                                            <span class="label label-md label-danger arrowed-in arrowed-in-right">{{ $training->status_code }}</span>
                                        @elseif($training->getOriginal('status_code') == App\Models\Lookups\TrainingStatusLookup::STATUS_TEMP_WITHDRAWN)
                                            <span class="label label-md label-warning arrowed-in arrowed-in-right">{{ $training->status_code }}</span>
                                        @endif
                                    </small>
                                </td>
                                <td>
                                    {{ $training->learner_ref }}
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

            <div class="space-6"></div>

            <div class="row">
                <div class="col-sm-12">
                    <div class="tabbable">
                        <ul id="myTab" class="nav nav-tabs tab-color-blue background-blue padding-18 tab-size-bigger">
                            @foreach($portfolios AS $portfolio)
                                <li class="{{ $loop->first ? 'active' : '' }}">
                                    <a href="#tab{{ $portfolio->qan }}" data-toggle="tab" class="linkPortfolioTab">
                                        <i class="fa fa-graduation-cap"></i>
                                        {{ $portfolio->qan }} <span class="badge badge-info">{{ $portfolio->signedOffUnits() }}/{{ $portfolio->units->count() }}</span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                        <div class="tab-content">
                            @foreach($portfolios AS $portfolio)
                                <div class="tab-pane {{ $loop->first ? 'in active' : '' }}" id="tab{{ $portfolio->qan }}">
                                    <div class="row">
                                        <div class="col-sm-10">
                                            <h4 class="lighter bolder"><i class="fa fa-graduation-cap"></i> {{ $portfolio->title }}</h4>
                                            <div class="space-4"></div>
                                            @include('trainings.partials.entity_progress_bar', ['entity' => $portfolio])
                                        </div>
                                        <div class="col-sm-2">
                                            <div class="pull-right">
                                                <span class="btn btn-app btn-sm btn-success no-hover">
                                                    <span class="line-height-1 bigger-170"> {{ $portfolio->signedOffUnits() }}/{{ $portfolio->units->count() }}
                                                    </span><br>
                                                    <span class="line-height-1 smaller-90"> Units </span>
                                                </span>
                                                <br>
                                                <span data-rel="tooltip" title="Number of mandatory units" class="badge badge-success">M: {{ $portfolio->units->where('unit_group', 1)->count() }}</span>
                                                <span data-rel="tooltip" title="Number of optional units" class="badge badge-info">O: {{ $portfolio->units->where('unit_group', 2)->count() }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="table-responsive">
                                                <table class="table table-bordered tblPortfolioUnits">
                                                    <thead>
                                                    <tr><th align="center" class="text-center">Units of this portfolio</th></tr>
                                                    </thead>
                                                    <tbody>
                                                    @foreach($portfolio->units AS $unit)
                                                        @php
                                                            $unit->load([
                                                                'pcs' => function ($query) {
                                                                    $query->orderBy('pc_sequence');
                                                                },
                                                                'pcs.mapped_evidences',
                                                                'pcs.mapped_evidences.media'
                                                            ]);
                                                        @endphp
                                                        <tr>
                                                            <td>
                                                                @include('trainings.partials.unit')
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>{{-- tab-content --}}
                    </div>{{-- tabbable --}}
                </div> {{-- tab col --}}
            </div>{{-- tab row --}}
            <div class="modal"><!-- Place at bottom of page --></div>
            <!-- PAGE CONTENT ENDS -->
        </div><!-- /.col -->
    </div><!-- /.row -->

@endsection

@section('page-plugin-scripts')
    <script src="{{ asset('assets/js/jquery.easypiechart.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
    <script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
@endsection

@section('page-inline-scripts')
    <script type="text/javascript">

        $(function(){
            $('[data-rel=tooltip]').tooltip();
            $('[data-rel=popover]').popover({
                html:true,
                placement:"auto"
            });

            /*$('.tblPortfolioUnits').DataTable({
                "lengthChange": false,
                "paging" : false,
                "info" : false,
                "order": false
            });*/
        });

        $body = $("body");

        $(document).on({
            ajaxStart: function() { $body.addClass("loading");    },
            ajaxStop: function() { $body.removeClass("loading"); }
        });

    </script>
@endsection
