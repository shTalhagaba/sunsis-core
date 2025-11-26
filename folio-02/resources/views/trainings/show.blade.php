@extends('layouts.master')

@section('title', 'Training Record')

@section('page-plugin-styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
@endsection

@section('page-content')
    @include('trainings.partials.tr_header')

    <!-- /.page-header -->
    <div class="row">
        <div class="col-xs-12">
            <!-- PAGE CONTENT BEGINS -->
            <div class="row">
                <div class="col-xs-12">
                    @if(!\Auth::user()->isStudent())
                        <button class="btn btn-sm btn-white btn-default btn-round" type="button"
                                onclick="window.location.href='{{ route('trainings.index') }}'">
                            <i class="ace-icon fa fa-times bigger-110"></i> Close
                        </button>
                    @endif
                    @can('update-training-record')
                        <button class="btn btn-sm btn-primary btn-bold btn-round" type="button"
                                onclick="window.location.href='{{ route('trainings.edit', $training) }}'">
                            <i class="ace-icon fa fa-edit bigger-120"></i> Edit
                        </button>
                    @endcan
                    @if(App\Helpers\AppHelper::requestFromOffice())
                        @can('delete-training-record')
                            {!! Form::open(['method' => 'DELETE', 'url' => route('trainings.destroy', $training), 'style' => 'display: inline;', 'class' => 'form-inline', 'id' => 'frmDeleteTR' ]) !!}
                            {!! Form::button('<i class="ace-icon fa fa-trash-o bigger-120"></i> Delete', ['class' => 'btn btn-sm btn-danger btn-bold btn-round btnDelTR', 'type' => 'submit', 'style' => 'display: inline']) !!}
                            {!! Form::close() !!}
                        @endcan
                    @endif
                    @if(auth()->user()->isAdmin() && in_array( config('app.DB_NAME'), ['folio_training', 'folio_crackerjack']) )
                        <button class="btn btn-sm btn-primary btn-bold btn-round" type="button"
                                onclick="window.location.href='{{ route('sunesis.showPushLearnerForm', $training) }}'">
                            <i class="ace-icon fa fa-envelope bigger-120"></i> Push in Sunesis
                        </button>
                    @endif
                    <div class="pull-right">
                        @if(auth()->user()->isAdmin() || auth()->user()->id == $training->verifierUser->id)
                            <button class="btn btn-sm btn-primary btn-bold btn-round " type="button"
                                    onclick="window.location.href='{{ route('trainings.deep_dives.index', ['training' => $training]) }}'">
                                <i class="ace-icon fa fa-edit bigger-120"></i> Deep Dive
                            </button>
                        @endif
                        @if(auth()->user()->isAdmin() || auth()->user()->id == $training->verifierUser->id)
                            <button class="btn btn-sm btn-primary btn-bold btn-round " type="button"
                                    onclick="window.location.href='{{ $training->four_week_audit ? route('trainings.four_week_audit.show', ['training' => $training, 'audit' => $training->four_week_audit->id]) : route('trainings.four_week_audit.create', ['training' => $training]) }}'">
                                <i class="ace-icon fa fa-edit bigger-120"></i> 4 weeks Audit
                            </button>
                        @endif
                        @if(auth()->user()->isAdmin())
                            <button class="btn btn-sm btn-primary btn-bold btn-round " type="button"
                                    onclick="window.location.href='{{ route('trainings.statuses.showUpdate', $training) }}'">
                                <i class="ace-icon fa fa-edit bigger-120"></i> Update Status
                            </button>
                        @endif
                    </div>

                    <div class="hr hr-12 hr-dotted"></div>
                </div>
            </div>

            @include('partials.session_message')

            <div class="row">
                <div class="col-sm-3 col-xs-12">
                    <div class="pull-left" style="margin-right: 2%">
                    <span class="profile-picture" style="width: 100px;">
                        <img class="img-responsive" alt="{{ $student->firstnames}}'s Avatar" id="avatar2"
                             src="{{ asset($student->avatar_url) }}"/>
                    </span>
                        <br>
                        @include('partials.user_login_status', ['user' => $student])
                    </div>
                    <div>
                        <strong class="bigger-225 grey lighter">{{ $student->full_name }}</strong><br>
                        {{ $student->primary_email }}<br>
                        {{ $homeAddress->mobile }}
                    </div>
                </div>
                <div class="col-sm-5 col-xs-12">
                    <div class="info-div info-div-striped">
                        <div class="info-div-row">
                            <div class="info-div-name"> Start Date</div>
                            <div class="info-div-value">
                                {{ $training->start_date->format('d/m/Y') }}
                                @if ($training->isContinuing() && $training->start_date->isPast())
                                    <small class="text-info">({{ $training->start_date->diffInDays(now()) }} days
                                        elapsed)</small>
                                @endif
                            </div>
                        </div>
                        <div class="info-div-row">
                            <div class="info-div-name"> Planned End Date</div>
                            <div class="info-div-value">
                                {{ $training->planned_end_date->format('d/m/Y') }}
                                @if (is_null($training->actual_end_date))
                                    <small class="text-info">({{ \Carbon\Carbon::parse($training->getOriginal('planned_end_date'))->diffForHumans() }}
                                        )</small>
                                @endif
                            </div>
                        </div>
                        @if($training->isCompleted() || $training->isWithdrawn())
                            <div class="info-div-row">
                                <div class="info-div-name"> Actual End Date</div>
                                <div class="info-div-value">
                                    {{ \Carbon\Carbon::parse($training->actual_end_date)->format('d/m/Y') }}
                                    <small>
                                        @include('trainings.partials.tr_status_description')
                                    </small>
                                </div>
                            </div>
                        @endif
                        @if($training->isContinuing() && !is_null($training->epa_date))
                            <div class="info-div-row">
                                <div class="info-div-name"> EPA Date</div>
                                <div class="info-div-value">
                                    {{ \Carbon\Carbon::parse($training->epa_date)->format('d/m/Y') }}
                                    <small class="text-info">({{ \Carbon\Carbon::parse($training->getOriginal('epa_date'))->diffForHumans() }}
                                        )</small>
                                </div>
                            </div>
                        @endif
                        <div class="info-div-row">
                            <div class="info-div-name"> Learner Ref.</div>
                            <div class="info-div-value">{{ $training->learner_ref }}</div>
                        </div>
                        <div class="info-div-row">
                            <div class="info-div-name"> Programme</div>
                            <div class="info-div-value">{{ $training->programme->title }}</div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4 col-xs-12" style="border: 2px solid #007BFF;">
                    <div class="grid2">
                        <div style="height: 170px; margin: 1px;" id="progressChart"></div>
                    </div>
                    <div class="grid2 {{ $overallProgress >= 100 ? 'green' : '' }}">
                        <span class="bolder">Progress:</span><br>
                        Target: {{ $training->target_progress  }}%<br>
                        Actual: {{ $overallProgress }}%<br>
                        <span class="bolder">Criteria:</span><br>
                        Total: {{ $allPcs }}<br>
                        Signed Off: {{ $signedOffPcs }} ({{ $overallProgress }}%)<br>
                        @if( $assessment_complete_units_count > 0 )
                            <span id="assessment_complete_units_count" data-rel="tooltip" title=""
                                  data-original-title="Number of units where assessment complete status is checked">
                        <i class="fa fa-flag fa-lg red"></i> AC Units: {{ $assessment_complete_units_count }}
                    </span>
                        @endif
                        @if($overallProgress < 100)
                            <span class="text-info">Ready to Sign Off: {{ $readyToSignoffPcs }} ({{ $readyToSignoffPcsPercentage }}%)</span>
                        @endif
                    </div>
                </div>
            </div>

            @if(auth()->user()->isStaff())
                <div class="row">
                    <div class="col-xs-12">
                        @include('partials.tags_widget', ['_entity' => $training, 'tagTypeDesc' => 'TrainingRecord'])
                    </div>
                </div>
            @endif

            <div class="row">
                <div class="col-xs-12">
                    <div class="widget-box transparent collapsed" id="widget-box-{{ $training->id }}">
                        <div class="widget-header">
                            <h5 class="widget-title smaller">&nbsp;</h5>
                            <div class="widget-toolbar">
                                <a href="#" data-action="collapse"><i class="ace-icon fa fa-chevron-down"></i></a>
                            </div>
                        </div>
                        <div class="widget-body">
                            <div class="widget-main">
                                <div class="col-sm-4">
                                    <div class="info-div info-div-striped">
                                        <div class="info-div-row">
                                            <div class="info-div-name"> Date of Birth</div>
                                            <div class="info-div-value">
                                                <span>{{ optional($student->date_of_birth)->format('d/m/Y') }}</span>
                                            </div>
                                        </div>
                                        <div class="info-div-row">
                                            <div class="info-div-name"> Ethnicity</div>
                                            <div class="info-div-value">
                                                <span>{{ App\Models\Lookups\EthnicityLookup::getDescription($student->ethnicity) }}</span>
                                            </div>
                                        </div>
                                        <div class="info-div-row">
                                            <div class="info-div-name"> National Insurance</div>
                                            <div class="info-div-value"><span>{{ $student->ni }}</span></div>
                                        </div>
                                        <div class="info-div-row">
                                            <div class="info-div-name"> ULN</div>
                                            <div class="info-div-value"><span>{{ $student->uln }}</span></div>
                                        </div>
                                        <div class="info-div-row">
                                            <div class="info-div-name"> Work Address</div>
                                            <div class="info-div-value">
                                                @include('partials.address_lines', ['address' => $workAddress])
                                            </div>
                                        </div>
                                        <div class="info-div-row">
                                            <div class="info-div-name"> Home Address</div>
                                            <div class="info-div-value">
                                                @include('partials.address_lines', ['address' => $homeAddress])
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="info-div info-div-striped">
                                        <div class="info-div-row">
                                            <div class="info-div-name"> Primary Assessor</div>
                                            <div class="info-div-value">
                                                @include('partials.user_brief_work_details', ['user' => $training->primaryAssessor])
                                            </div>
                                        </div>
                                        @if(!is_null($training->secondaryAssessor))
                                            <div class="info-div-row">
                                                <div class="info-div-name"> Secondary Assessor</div>
                                                <div class="info-div-value">
                                                    @include('partials.user_brief_work_details', ['user' => $training->secondaryAssessor])
                                                </div>
                                            </div>
                                        @endif
                                        @if(!is_null($training->verifierUser) && !\Auth::user()->isStudent())
                                            <div class="info-div-row">
                                                <div class="info-div-name"> Verifier</div>
                                                <div class="info-div-value">
                                                    @include('partials.user_brief_work_details', ['user' => $training->verifierUser])
                                                </div>
                                            </div>
                                        @endif
                                        @if(!is_null($training->tutorUser) && !\Auth::user()->isStudent())
                                            <div class="info-div-row">
                                                <div class="info-div-name"> Tutor</div>
                                                <div class="info-div-value">
                                                    @include('partials.user_brief_work_details', ['user' => $training->tutorUser])
                                                </div>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-sm-4">
                                    <div class="info-div info-div-striped">
                                        <div class="info-div-row">
                                            <div class="info-div-name"> Employer</div>
                                            <div class="info-div-value">
                                                <strong>{{ optional($training->employer)->legal_name }}</strong><br>
                                                @include('partials.address_lines', ['address' => $training->location])
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="space-6"></div>

            <div class="row">
                <div class="col-xs-12">
                    <div class="tabbable">
                        <ul id="mainTab" class="nav nav-tabs tab-color-blue background-blue padding-18 tab-size-bigger">
                            <li class="active"><a class="tabbedLink" href="#tabPortfolios" data-toggle="tab">Portfolios
                                    <span class="badge badge-info">{{ $training->portfolios->count() }}</span></a></li>
                            <li><a class="tabbedLink" href="#tabEvidences" data-toggle="tab">Evidences <span
                                            class="badge badge-info">{{ $training->evidences->count() }}</span></a></li>
                            <li><a class="tabbedLink" href="#tabDeliveryPlan" data-toggle="tab">Delivery Plan </a></li>
                            <li><a class="tabbedLink" href="#tabOtj" data-toggle="tab">OTJ Hours </a></li>
                            <li><a class="tabbedLink" href="#tabReviews" data-toggle="tab">Reviews </a></li>
                            <li><a class="tabbedLink" href="#tabCrmNotes" data-toggle="tab">CRM Notes</a></li>
                            <li><a class="tabbedLink" href="#tabFsCourses" data-toggle="tab"><abbr
                                            title="Functional Skills">FS</abbr> Courses</a></li>
                            <li><a class="tabbedLink" href="#tabAlsReviews" data-toggle="tab">ALS Reviews</a></li>
                            <li><a class="tabbedLink" href="#tabFileRepo" data-toggle="tab">File Repository</a></li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="tabPortfolios">
                                @include('trainings.partials.tab_portfolios')
                            </div>
                            <div class="tab-pane" id="tabEvidences">
                                <div class="row">
                                    <div class="col-xs-12">
                                        <h4 class="lighter">Evidence Repository <small><i
                                                        class="ace-icon fa fa-angle-double-right"></i> Here you can view
                                                and upload your evidences</small></h4> &nbsp;
                                        @if(
                                            (auth()->user()->isStudent() && $training->isEditableByStudent()) ||
                                            (auth()->user()->isStaff() && auth()->user()->can('create-evidence'))
                                        )
                                            <span class="btn btn-primary btn-sm btn-round"
                                                  onclick="window.location.href='{{ route('trainings.evidences.create', $training) }}'">
                                        <i class="fa fa-plus"></i><i class="fa fa-file-text"></i> Create New Evidence
                                    </span>
                                            <div class="hr hr-12 hr-dotted"></div>
                                        @endif
                                        <i class="ace-icon fa-lg fa fa-chevron-down pull-right" title="Expand All"
                                           style="cursor: pointer;"
                                           onclick="$('.widgetEvidences').widget_box('show');"></i>
                                        <i class="ace-icon fa-lg fa fa-chevron-up pull-right" title="Collapse All"
                                           style="cursor: pointer;"
                                           onclick="$('.widgetEvidences').widget_box('hide');"></i>
                                        <div class="space-6"></div>
                                        <div class="table-responsive">
                                            @foreach($training->evidences AS $evidence)
                                                @include('trainings.partials.evidence_entry')
                                                <div class="space-6"></div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="tabDeliveryPlan">
                                @include('trainings.partials.tab_delivery_plan')
                            </div>
                            <div class="tab-pane" id="tabOtj">
                                @include('trainings.partials.training_otj', ['completedOtjSeconds' => $completedOtjSeconds, 'otj_filters' => $otj_filters])
                            </div>
                            <div class="tab-pane" id="tabReviews">
                                @include('trainings.partials.training_reviews')
                            </div>
                            <div class="tab-pane" id="tabCrmNotes">
                                @include('trainings.partials.training_crm_notes')
                            </div>
                            <div class="tab-pane" id="tabFsCourses">
                                @include('trainings.partials.training_fs_courses')
                            </div>
                            <div class="tab-pane" id="tabAlsReviews">
                                @include('trainings.partials.training_als_reviews')
                            </div>
                            <div class="tab-pane" id="tabFileRepo">
                                @include('trainings.partials.training_file_repo')
                            </div>
                        </div>
                    </div>
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
    <script src="https://code.highcharts.com/7.0.0/highcharts.js"></script>
    <script src="https://code.highcharts.com/7.0.0/highcharts-more.js"></script>
    <script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
@endsection

@section('page-inline-scripts')
    <script type="text/javascript">

        var isReloadRequiredForPortfolioTabs = false;

        $(function () {
            $('[data-rel=popover]').popover({
                html: true,
                placement: "auto"
            });

            /*$('.tblPortfolioUnits').DataTable({
                "lengthChange": false,
                "paging" : false,
                "info" : false,
                "order": false
            });*/

            $('.easy-pie-chart.percentage').each(function () {
                var barColor = '#59A84B';
                var trackColor = '#E2E2E2';
                var size = parseInt($(this).data('size')) || 92;
                $(this).easyPieChart({
                    barColor: barColor,
                    trackColor: trackColor,
                    scaleColor: false,
                    lineCap: 'butt',
                    lineWidth: parseInt(size / 10),
                    animate: {duration: 2500, enabled: true},
                    size: size
                }).css('color', barColor);
            });


        });

        $(".btnDelEvi, .btnDeletePortfolio").on('click', function (e) {
            e.preventDefault();

            var form = this.closest('form');

            $.confirm({
                title: 'Confirm!',
                content: 'This action is irreversible, are you sure you want to continue?',
                icon: 'fa fa-question-circle',
                animation: 'scale',
                closeAnimation: 'scale',
                theme: 'supervan',
                opacity: 0.5,
                buttons: {
                    'confirm': {
                        text: 'Yes',
                        btnClass: 'btn-red',
                        action: function () {
                            $.ajax({
                                beforeSend: function () {
                                    $('.loader').show();
                                },
                                url: form.action,
                                type: form.method,
                                data: $(form).serialize()
                            }).done(function (response, textStatus) {
                                loader_fade_out();
                                $.alert({
                                    title: response.success ? 'Success' : 'Error',
                                    content: response.message,
                                    type: textStatus == 'success' ? 'green' : 'red',
                                    buttons: {
                                        'OK': {
                                            action: function () {
                                                if (response.success)
                                                    window.location.reload();
                                            }
                                        }
                                    }
                                });
                            }).fail(function (jqXHR, textStatus, errorThrown) {
                                loader_fade_out();
                                $.alert({
                                    title: 'Encountered an error!',
                                    content: textStatus + ': ' + errorThrown,
                                    icon: 'fa fa-warning',
                                    theme: 'supervan',
                                    type: 'red'
                                });
                            });
                        }
                    },
                    Cancel: function () {
                    }
                }
            });
        });

        @if(App\Helpers\AppHelper::requestFromOffice())
        $(".btnDelTR").on('click', function (e) {
            e.preventDefault();

            var btn = $(this);
            var form = this.closest('form');

            bootbox.confirm({
                title: 'Confirm Deletion?',
                message: 'This action is irreversible, are you sure you want to remove this training and its associated records?',
                buttons: {
                    cancel: {
                        label: '<i class="fa fa-times"></i> Cancel'
                    },
                    confirm: {
                        label: '<i class="fa fa-check-o"></i> Yes, Delete',
                        className: 'btn-danger'
                    }
                },
                callback: function (result) {
                    if (result) {
                        $('#screenLockLoadingOverlay').show();
                        $.ajax({
                            type: form.method,
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                                '_method': 'PATCH'
                            },
                            beforeSend: function () {
                                btn.attr('disabled', true);
                                btn.html('<i class="fa fa-spinner fa-spin"></i> Deleting');
                            },
                            url: form.action,
                            data: $(form).serialize(),
                            success: function (response) {
                                if (!response.success) {
                                    $('#screenLockLoadingOverlay').hide();
                                    btn.attr('disabled', false);
                                    btn.html('<i class="ace-icon fa fa-trash-o bigger-120"></i> Delete');
                                    bootbox.alert(response.message);
                                } else {
                                    $('#screenLockLoadingOverlay').hide();
                                    bootbox.alert('Training record and its associated records have been deleted from the system completely.', function () {
                                        window.location.href = "{{ route('students.show', $student) }}";
                                    });
                                }
                            },
                            error: function (errorInfo, code, errorMessage) {
                                $('#screenLockLoadingOverlay').hide();
                                btn.attr('disabled', false);
                                btn.html('<i class="ace-icon fa fa-trash-o bigger-120"></i> Delete');
                                bootbox.alert({
                                    title: "Error: " + (errorInfo.statusText !== undefined ? errorInfo.statusText : code),
                                    message: errorInfo.responseJSON.message !==
                                    undefined ? errorInfo.responseJSON.message :
                                        errorMessage
                                });
                            }
                        });
                    }
                }
            });
        });
        @endif

            $body = $("body");

        $(document).on({
            ajaxStart: function () {
                $body.addClass("loading");
            },
            ajaxStop: function () {
                $body.removeClass("loading");
            }
        });


        $(function () {

            $('.linkPortfolioTab').on('click', function () {
                if (isReloadRequiredForPortfolioTabs)
                    window.location.reload();
            });

            var chart = new Highcharts.chart('progressChart', {!! $progressChart !!});

            $(".chkUnitAssessmentStatus").on("change", function () {
                var assessment_complete = this.checked ? 1 : 0;
                var unit_id = $(this).attr('data-value');

                $.ajax({
                    data: {unit_id: unit_id, assessment_complete: assessment_complete},
                    url: "{{ route('unit.updateUnitAssessmentStatus', [$student, $training]) }}",
                }).done(function (response) {
                    $("#assessment_complete_units_count").html('');
                    var ac_units_count = response.ac_units_count;
                    if (ac_units_count > 0) {
                        // $("#assessment_complete_units_count").html(response.ac_units_count + ' AC');
                        $("#assessment_complete_units_count").html('<i class="fa fa-flag fa-lg red"></i> AC Units: ' + response.ac_units_count);
                    }
                    console.log(response);
                }).fail(function (jqXHR, textStatus, errorThrown) {
                    $.alert(errorThrown, textStatus);
                });

            });

        });

        function refreshDeliveryPlanSessionsFromProgramme() {
            // if(!confirm('This action will remove the current delivery plan sessions and refresh from the programme. Are you sure you want to continue?'))
            // {
            //     return;
            // }

            bootbox.confirm({
                title: "Confirmation",
                message: 'This action will refresh sessions from the programme. Are you sure you want to continue?',
                buttons: {
                    cancel: {
                        label: '<i class="fa fa-times"></i> Cancel',
                        className: "btn-sm btn-round",
                    },
                    confirm: {
                        label: '<i class="fa fa-check"></i> Confirm',
                        className: "btn-danger btn-sm btn-round",
                    }
                },
                callback: function (result) {
                    if (result) {
                        $.ajax({
                            method: 'POST',
                            data: {tr_id: {{ $training->id }}, "_token": "{{ csrf_token() }}"},
                            url: "{{ route('trainings.sessions.refresh') }}",
                        }).done(function (response) {
                            console.log(response);
                            if (response.alert_success) {
                                window.location.reload();
                            } else if (response.alert_danger) {
                                alert(response.alert_danger)
                            }
                        }).fail(function (jqXHR, textStatus, errorThrown) {
                            $.alert(errorThrown, textStatus);
                            console.log('here');
                        });
                    }
                }
            });


        }

        var selectedTab = "{{ session()->get('read_training_tab') }}";
        var selectedSubTab = "{{ session()->get('read_training_sub_tab') }}";
        if (selectedTab != '') {
            $('#mainTab a[href="#' + selectedTab + '"]').tab('show');
        }
        if (selectedSubTab != '') {
            if ($('#subTab a[href="#' + selectedSubTab + '"]') !== undefined) {
                $('#subTab a[href="#' + selectedSubTab + '"]').tab('show');
            }
        }
        $('.tabbedLink').click(function () {
            $.ajax({
                type: "POST",
                url: "{{ route('saveTabInSession') }}",
                data: {
                    _token: '{{ csrf_token() }}',
                    screen: 'read_training',
                    selectedTab: $(this).attr('href').replace('#', '')
                }
            });
        });
        $('.subTabbedLink').click(function () {
            $.ajax({
                type: "POST",
                url: "{{ route('saveTabInSession') }}",
                data: {
                    _token: '{{ csrf_token() }}',
                    screen: 'read_training_sub',
                    selectedTab: $(this).attr('href').replace('#', '')
                }
            });
        });


    </script>
@endsection
