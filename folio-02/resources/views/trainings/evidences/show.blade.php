<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>Folio | @yield('title', 'Evidence Full Details')</title>

        <link rel="shortcut icon" href="{{ asset('images/favicon.ico') }}">
        <!-- bootstrap & fontawesome -->
        <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}" />
        <link rel="stylesheet" href="{{ asset('assets/font-awesome/4.5.0/css/font-awesome.min.css') }}" />

        <!-- page specific plugin styles -->

        <!-- text fonts -->
        <link rel="stylesheet" href="{{ asset('assets/css/fonts.googleapis.com.css') }}" />

        <!-- ace styles -->
        <link rel="stylesheet" href="{{ asset('assets/css/ace.min.css') }}" class="ace-main-stylesheet" id="main-ace-style" />

        <!--[if lte IE 9]>
            <link rel="stylesheet" href="assets/css/ace-part2.min.css" class="ace-main-stylesheet" />
        <![endif]-->
        <link rel="stylesheet" href="{{ asset('assets/css/ace-skins.min.css') }}" />
        <link rel="stylesheet" href="{{ asset('assets/css/ace-rtl.min.css') }}" />

        <!--[if lte IE 9]>
        <link rel="stylesheet" href="{{ asset('assets/css/ace-ie.min.css') }}" />
        <![endif]-->

        <style type="text/css">
            .form-group.required .control-label:after {
                content:" *";
                color:red;
            }
            input{
                border-radius: 5px;
            }
            .info-div {
                width: 100%;
                display: table;
                width: 98%;
                width: calc(100% - 24px);
                margin: 0 auto;
            }
            .info-div-row {
                display: table-row
            }
            .info-div-name,
            .info-div-value {
                display: table-cell;
                border-top: 1px dotted #D5E4F1
            }
            .info-div-name {
                text-align: right;
                padding: 6px 10px 6px 4px;
                font-weight: 400;
                color: #667E99;
                background-color: transparent;
                /* width: 210px; */
                vertical-align: middle
            }
            .info-div-value {
                padding: 6px 4px 6px 6px
            }
            .info-div-value>span+span:before {
                display: inline;
                /* content: ","; */
                margin-left: 1px;
                margin-right: 3px;
                color: #666;
                border-bottom: 1px solid #FFF
            }
            .info-div-value>span+span.editable-container:before {
                display: none
            }
            .info-div-row:first-child .info-div-name,
            .info-div-row:first-child .info-div-value {
                border-top: none
            }
            .info-div-striped {
                border: 1px solid #DCEBF7
            }

            .info-div-striped .info-div-name {
                color: #336199;
                background-color: #EDF3F4;
                border-top: 1px solid #F7FBFF
            }

            .info-div-striped .info-div-value {
                border-top: 1px dotted #DCEBF7;
                padding-left: 12px
            }
        </style>
        <!-- Scripts -->
        <!-- <script src="https://kit.fontawesome.com/a2c32245af.js"></script> -->

        <!-- HTML5shiv and Respond.js for IE8 to support HTML5 elements and media queries -->
        <!--[if lte IE 8]>
        <script src="{{ asset('assets/js/html5shiv.min.js') }}"></script>
        <script src="{{ asset('assets/js/respond.min.js') }}"></script>
        <![endif]-->

        <script src="{{ asset('assets/js/ace-extra.min.js') }}"></script>
    </head>

    <body class="no-skin">
        <div class="navbar navbar-fixed-top" id="navbar">
            <div id="navbar-container" class="navbar-container">
                <div class="navbar-header pull-left">
                    <img class="img-rounded" height="40px;" src="{{ asset('images/logos/'.App\Facades\AppConfig::get('FOLIO_LOGO_NAME')) }}" alt="Logo">
                </div>
                <a href="#" class="navbar-brand" style="margin-left: 30%">
                    <small> Evidence Details  </small>
                </a>
            </div>
        </div>

        <div class="main-container" id="main-container">
            <div class="main-content">
                <div class="page-content">
                    <!-- setting box goes here if needed -->
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="page-header">
                                <h1>Evidence Details </h1>
                            </div><!-- /.page-header -->

                            <div class="row">
                                <div class="col-xs-12">
                                    <button class="btn btn-sm btn-white btn-default btn-round" type="button" onclick="window.close();">
                                        <i class="ace-icon fa fa-close bigger-110"></i> Close Tab
                                    </button>
				                    @if(!is_null($linkedTask))
                                        <span class="btn btn-info btn-sm btn-round pull-right" 
                                            onclick="window.location.href='{{ route('trainings.sessions.tasks.show', [$linkedTask->session->training, $linkedTask->session, $linkedTask]) }}'">
                                            <i class="fa fa-folder-open"></i> View Linked Task
                                        </span>
                                    @endif
                                    <div class="hr hr-12 hr-dotted"></div>
                                </div>
                            </div>

                            @include('trainings.partials.training_quick_details', ['showOverallPercentage' => true])

                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="widget-box transparent">
                                        <div class="widget-header"><h5 class="widget-title smaller">Evidence Details</h5></div>
                                        <div class="widget-body">
                                            <div class="widget-main">
                                                @include('trainings.evidences.partials.evidence-details', ['_evi_details' => $evidence])
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="widget-box transparent">
                                        <div class="widget-header"><h5 class="widget-title smaller">Evidence Assessment</h5></div>
                                        <div class="widget-body">
                                            <div class="widget-main">
                                                @if (! is_null($evidence->assessor_comments) && $evidence->assessments()->count() == 0)
                                                <div class="profile-user-info profile-user-info-striped">
                                                    <div class="profile-info-row">
                                                        <div class="profile-info-name" style="vertical-align: top;"> Assessment Comments </div>
                                                        <div class="profile-info-value">
                                                            <span>{!! nl2br(e($evidence->assessor_comments)) !!}</span><br>
                                                        </div>
                                                    </div>
                                                </div> <div class="space-4"></div>
                                                @endif
                                                @forelse ($evidence->assessments()->where('assessment_by', App\Models\Training\TrainingRecordEvidenceAssessment::ASSESSMENT_BY_ASSESSOR)->get() as $assessment)
                                                <div class="profile-user-info profile-user-info-striped">
                                                    <div class="profile-info-row">
                                                        <div class="profile-info-name"> Assessment By </div>
                                                        <div class="profile-info-value">
                                                            <span>
                                                                <i>By</i> {{ App\Models\LookupManager::nameOfUser($assessment->created_by) }} 
                                                                <i>On</i> {{ $assessment->created_at->format('d/m/Y') }} <i>at</i> {{ $assessment->created_at->format('H:i') }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <div class="profile-info-row">
                                                        <div class="profile-info-name"> Assessment Status </div>
                                                        <div class="profile-info-value">
                                                            <span>{{ $assessment->statusDescription() }}</span></div>
                                                    </div>
                                                    <div class="profile-info-row">
                                                        <div class="profile-info-name" style="vertical-align: top;"> Assessment Comments </div>
                                                        <div class="profile-info-value">
                                                            <span>{!! nl2br(e($assessment->assessment_comments)) !!}</span>
                                                            <hr>
                                                            <div class="col-sm-6">
                                                                @if( $assessment->media()->count() > 0 )                                                                    
                                                                    @foreach($assessment->media AS $_assessmentMedia)
                                                                        @include('partials.file_media_well', ['fileMedia' => $_assessmentMedia])
                                                                    @endforeach
                                                                @endif
                                                            </div>
                                                            <div class="col-sm-6">
                                                                @include('partials.upload_file_form', [
                                                                    'associatedModel' => $assessment, 
                                                                    'sectionName' => '',
                                                                    'collectionName' => 'assessment_feedback'
                                                                    ])
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div> <div class="space-4"></div>
                                                @empty
                                                @if (is_null($evidence->assessor_comments))<i class="fa fa-info-circle"></i> This evidence has not been assessed yet.@endif
                                                @endforelse
                                            </div>
                                        </div>
                                    </div><hr>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="widget-box transparent ui-sortable-handle">
                                        <div class="widget-header">
                                            <h5 class="widget-title">Mapping <small><i class="ace-icon fa fa-angle-double-right"></i> Units and PCs this evidence is mapped to</small></h5>
                                        </div>
                                        <div class="widget-body">
                                            <div class="widget-main">
                                                <table class="table table-bordered {{ $evidence->getOriginal('evidence_status') == 3 ? 'red' : '' }}">

                                                    @forelse ($result AS $row)
                                                    @if ($loop->first)
                                                        <tr>
                                                            <td><i class="fa fa-graduation-cap fa-2x"></i></td>
                                                            <td colspan="3">[{{ $row->qan }}] {{ $row->portfolio_title }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td></td>
                                                            <td><i class="fa fa-folder fa-lg"></i></td>
                                                            <td colspan="2">[{{ $row->unit_owner_ref }}, {{ $row->unique_ref_number }}] {!! nl2br($row->unit_title) !!}</td>
                                                        </tr>
                                                        <tr>
                                                            <td></td>
                                                            <td></td>
                                                            <td><i class="fa fa-folder-open"></i></td>
                                                            <td>
                                                                [{{ $row->reference }}] {!! nl2br($row->pc_title) !!}
                                                                @if ($row->assessor_signoff == '1')
                                                                &nbsp; <span class="label label-success arrowed-in arrowed-in-right pull-right">signed off</span>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endif

                                                    @if($loop->last && $result->count() > 1)
                                                    <tr>
                                                        <td></td>
                                                        <td></td>
                                                        <td><i class="fa fa-folder-open"></i></td>
                                                        <td>
                                                            [{{ $row->reference }}] {!! nl2br($row->pc_title) !!}
                                                            @if ($row->assessor_signoff == '1')
                                                            &nbsp; <span class="label label-success arrowed-in arrowed-in-right pull-right">signed off</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                    @endif

                                                    @if(!$loop->first && !$loop->last)
                                                        @if ($row->portfolio_title != $result[$loop->index - 1]->portfolio_title)
                                                        <tr>
                                                            <td><i class="fa fa-graduation-cap fa-2x"></i></td>
                                                            <td colspan="3">[{{ $row->qan }}] {{ $row->portfolio_title }}</td>
                                                        </tr>
                                                        @endif
                                                        @if ($row->unit_title != $result[$loop->index - 1]->unit_title)
                                                        <tr>
                                                            <td></td>
                                                            <td><i class="fa fa-folder fa-lg"></i></td>
                                                            <td colspan="2">[{{ $row->unit_owner_ref }}, {{ $row->unique_ref_number }}] {!! nl2br($row->unit_title) !!}</td>
                                                        </tr>
                                                        @endif
                                                        <tr>
                                                            <td></td>
                                                            <td></td>
                                                            <td><i class="fa fa-folder-open"></i></td>
                                                            <td>
                                                                [{{ $row->reference }}] {!! nl2br($row->pc_title) !!}
                                                                @if ($row->assessor_signoff == '1')
                                                                &nbsp; <span class="label label-success arrowed-in arrowed-in-right pull-right">signed off</span>
                                                                @endif
                                                            </td>
                                                        </tr>
                                                    @endif

                                                    @empty
                                                        <tr><td colspan="3">Not mapped to any unit or pc.</td></tr>
                                                    @endforelse
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- page content goes here -->
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div><!-- /.page-content -->
            </div><!-- /.main-content -->
            <!-- footer area -->
            @include('layouts.footer')

            <a href="#" title="Go to top" id="btn-scroll-up" class="btn-scroll-up btn btn-sm btn-inverse">
                <i class="ace-icon fa fa-angle-double-up icon-only bigger-110"></i>
            </a>
        </div><!-- /.main-container -->
        <!-- list of script files -->
        <!-- basic scripts -->
        <!--[if !IE]> -->
        <script src="{{ asset('assets/js/jquery-2.1.4.min.js') }}"></script>
        <!-- <![endif]-->

        <!--[if IE]>
        <script src="{{ asset('assets/js/jquery-1.11.3.min.js') }}"></script>
        <![endif]-->
        <script type="text/javascript">
            if('ontouchstart' in document.documentElement) document.write("<script src='{{ asset('assets/js/jquery.mobile.custom.min.js') }}'>"+"<"+"/script>");
         </script>
         <script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
        <script src="{{ asset('assets/js/ace-elements.min.js') }}"></script>
        <script src="{{ asset('assets/js/acet.js') }}"></script>
     </body>

</html>
