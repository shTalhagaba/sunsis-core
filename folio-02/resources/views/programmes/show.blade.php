@extends('layouts.master')

@section('title', 'Programme')

@section('breadcrumbs')
    {{ Breadcrumbs::render('programmes.show', $programme) }}
@endsection

@section('page-content')
    <div class="page-header">
        <h1>View Programme</h1>
    </div><!-- /.page-header -->
    <div class="row">
        <div class="col-xs-12">
            <button class="btn btn-sm btn-white btn-default btn-round" type="button"
                onclick="window.location.href='{{ route('programmes.index') }}'">
                <i class="ace-icon fa fa-times bigger-110"></i> Close
            </button>
            <button class="btn btn-sm btn-primary btn-bold btn-round" type="button"
                onclick="window.location.href='{{ route('programmes.edit', $programme) }}'">
                <i class="ace-icon fa fa-edit bigger-120"></i> Edit Programme
            </button>
	    @can('submenu-view-training-records')
            <button class="btn btn-sm btn-info btn-bold btn-round" type="button"
                onclick="window.location.href='{{ route('trainings.index' ) }}?_reset=2&programme_id={{ $programme->id }}'">
                 View Learners
            </button>
            @endcan

            <div class="hr hr-12 hr-dotted"></div>

            @include('partials.session_message')

            <div class="row">
                <div class="col-sm-4">
                    <div class="info-div info-div-striped">
                        <div class="info-div-row">
                            <div class="info-div-name"> Title </div>
                            <div class="info-div-value"><span>{{ $programme->title }}</span></div>
                        </div>
                        <div class="info-div-row">
                            <div class="info-div-name"> Duration </div>
                            <div class="info-div-value"><span>{{ $programme->duration }}
                                    {{ \Str::plural('month', $programme->duration) }}</span></div>
                        </div>
                        <div class="info-div-row">
                            <div class="info-div-name"> EPA Duration </div>
                            <div class="info-div-value"><span>{{ $programme->epa_duration }}
                                    {{ \Str::plural('month', $programme->epa_duration) }}</span></div>
                        </div>
                        <div class="info-div-row">
                            <div class="info-div-name"> Programme Type </div>
                            <div class="info-div-value"><span>{{ optional($programme->programmeType)->description }}</span>
                            </div>
                        </div>
                        <div class="info-div-row">
                            <div class="info-div-name"> Reference Number </div>
                            <div class="info-div-value"><span>{{ $programme->reference_number }}</span></div>
                        </div>
                        <div class="info-div-row">
                            <div class="info-div-name"> LARS Standard Code </div>
                            <div class="info-div-value"><span>{{ $programme->lars_standard_code }}</span></div>
                        </div>
                        <div class="info-div-row">
                            <div class="info-div-name"> Off-the-job Hours </div>
                            <div class="info-div-value"><span>{{ $programme->otj_hours }}</span></div>
                        </div>
                        <div class="info-div-row">
                            <div class="info-div-name"> First Review </div>
                            <div class="info-div-value"><span>{{ $programme->first_review }}
                                    {{ \Str::plural('week', $programme->first_review) }}</span></div>
                        </div>
                        <div class="info-div-row">
                            <div class="info-div-name"> Review Frequency </div>
                            <div class="info-div-value"><span>{{ $programme->review_frequency }}
                                    {{ \Str::plural('week', $programme->review_frequency) }}</span></div>
                        </div>
                        <div class="info-div-row">
                            <div class="info-div-name"> Leeway Period </div>
                            <div class="info-div-value"><span>{{ $programme->leeway }}
                                    {{ \Str::plural('week', $programme->leeway) }}</span></div>
                        </div>
                        <div class="info-div-row">
                            <div class="info-div-name"> Status </div>
                            <div class="info-div-value">
                                <span>{{ $programme->status == 1 ? 'Active' : 'Not Active' }}</span></div>
                        </div>
                        <div class="info-div-row">
                            <div class="info-div-name"> Comments </div>
                            <div class="info-div-value"><span>{!! nl2br(e($programme->comments)) !!}</span></div>
                        </div>

                    </div>
                    @include('partials.tags_widget', [
                        '_entity' => $programme,
                        'tagTypeDesc' => 'Programme',
                    ])
                </div>
                <div class="col-sm-8">
                    <div class="tabbable">
                        <ul id="myTab" class="nav nav-tabs">
                            <li class="active">
                                <a class="tabbedLink" href="#tabProgQuals" data-toggle="tab">Qualifications</a>
                            </li>
                            <li>
                                <a class="tabbedLink" href="#tabProgDeliveryPlan" data-toggle="tab">Delivery Plan</a>
                            </li>
                            <li>
                                <a class="tabbedLink" href="#tabFileRepo" data-toggle="tab">File Repository</a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane in active" id="tabProgQuals">
                                @if ( count($programme->qualifications) > 0 )
                                <a title="Export qualifications of this programme" href="{{ route('programmes.qualifications.export', [$programme]) }}" >
                                    <i class="fa fa-file-excel-o pull-right fa-2x"></i>
                                </a> &nbsp;
                                @endif
                                <span class="btn btn-primary btn-sm btn-round"
                                    onclick="window.location.href='{{ route('programmes.qualifications.manage', $programme) }}'">
                                    <i class="fa fa-edit"></i> Add/Remove Qualifications
                                </span>
                                <div class="hr hr-12 hr-dotted"></div>
                                @include('programmes.partials.qualifications_tabbed', [
                                    'programme' => $programme,
                                ])
                            </div>
                            <div class="tab-pane" id="tabProgDeliveryPlan">
                                <span class="btn btn-primary btn-sm btn-round"
                                    onclick="window.location.href='{{ route('programmes.sessions.create', $programme) }}'">
                                    <i class="fa fa-plus"></i> Add Session
                                </span>
                                <span class="btn btn-info btn-sm btn-round pull-right"
                                    onclick="window.location.href='{{ route('programmes.sessions.create', $programme) }}?is_template=1'">
                                    <i class="fa fa-plus"></i> Add Session Template
                                </span>
                                <div class="hr hr-12 hr-dotted"></div>
                                @if (count($programme->sessions) > 0)
                                    <h4 class="bigger blue text-center">{{ count($programme->sessions) }}
                                        {{ \Str::plural('Session', count($programme->sessions)) }}</h4>
                                @endif
                                @foreach ($programme->sessions as $session)
                                    @include('programmes.partials.dp_session_widget', ['programme' => $programme, 'dpSession' => $session])
                                @endforeach

                                <hr>
                                @if (count($programme->templateSessions) > 0)
                                    <h4 class="bigger blue text-center">{{ count($programme->templateSessions) }}
                                        Template {{ \Str::plural('Session', count($programme->templateSessions)) }}</h4>
                                @endif
                                @foreach ($programme->templateSessions as $templateSession)
                                    @include('programmes.partials.dp_session_widget', ['programme' => $programme, 'dpSession' => $templateSession])
                                @endforeach
                            </div>
                            <div class="tab-pane" id="tabFileRepo">
                                <div class="row">
                                    <div class="col-sm-4">
                                        <div class="widget-box transparent">
                                            <div class="widget-header">
                                                <h4 class="widget-title lighter">Sections</h4>
                                                <div class="widget-toolbar no-border">
                                                    <a href="#" class="btn btn-xs btn-info btn-round"
                                                        title="Add new section" id="btnCreateSection">
                                                        <i class="ace-icon fa fa-plus"></i>
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="widget-body" style="display: block;">
                                                <div class="widget-main padding-6 no-padding-left no-padding-right">
                                                    <div class="widget-box {{ $sectionName == '' ? 'widget-color-blue' : '' }}" style="border-radius: 2%; cursor: pointer;" onclick="window.location.href='{{ route('programmes.show', $programme) }}'">
                                                        <div class="widget-body">
                                                            <div class="widget-main {{ $sectionName == '' ? 'bg-info' : '' }}">
                                                                <h5 class="bolder blue"><i class="fa fa-folder{{ $sectionName == '' ? '-open' : '' }}"></i> /</h5>
                                                                <small>{{ $sectionFilesCount['main'] }} {{ \Str::plural('File', $sectionFilesCount['main']) }}</small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @foreach ($programme->mediaSections as $mediaSection)
                                                        <div class="widget-box {{ $sectionName == $mediaSection->slug ? 'widget-color-blue' : '' }}" style="border-radius: 2%; cursor: pointer;" 
                                                            onclick="window.location.href='{{ route('programmes.show', $programme) }}?section={{ urlencode($mediaSection->slug) }}'">
                                                            <div class="widget-body">
                                                                <div class="widget-main {{ $sectionName == $mediaSection->slug ? 'bg-info' : '' }}">
                                                                    <h5 class="bolder blue"><i class="fa fa-folder{{ $sectionName == $mediaSection->slug ? '-open' : '' }}"></i>
                                                                        {{ $mediaSection->name }}</h5>
                                                                    <small>{{ $sectionFilesCount[$mediaSection->slug] }} {{ \Str::plural('File', $sectionFilesCount[$mediaSection->slug]) }}</small>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-8">
                                        <div class="widget-box transparent">
                                            <div class="widget-header">
                                                <h4 class="widget-title lighter">Files</h4>
                                            </div>
                                            <div class="widget-body" style="display: block;">
                                                <div class="widget-main padding-6 no-padding-left no-padding-right">
                                                    <div class="row">
                                                        <div class="col-sm-12">
                                                            @include('partials.upload_file_form', [
                                                                'associatedModel' => $programme, 
                                                                'sectionName' => $sectionName
                                                                ])
                                                        </div>
                                                        <div class="col-sm-12">
                                                            <hr class="hr hr-dotted">
                                                            <h4 class="text-info center bolder">{{ $mediaFiles->count() }} {{ \Str::plural('File', $mediaFiles->count()) }}</h4>
                                                            
                                                            @include('partials.model_media_items', ['mediaFiles' => $mediaFiles, 'model' => $programme])

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('partials.create_media_section', ['model' => $programme])

@endsection


@section('page-inline-scripts')
    <script>
        var selectedTab = "{{ session()->get('read_programme_tab') }}";
        if (selectedTab != '') {
            $('#myTab a[href="#' + selectedTab + '"]').tab('show');
        }

        $('.tabbedLink').click(function() {
            $.ajax({
                type: "POST",
                url: "{{ route('saveTabInSession') }}",
                data: {
                    _token: '{{ csrf_token() }}',
                    screen: 'read_programme',
                    selectedTab: $(this).attr('href').replace('#', '')
                }
            });
        }); 

        $('[data-rel=popover]').popover({
            html:true,
            placement:"auto"
        });

        $("button.btnDeleteDpSession").on('click', function(e){
            e.preventDefault();

            var form = $(this).closest('form');

            bootbox.confirm({
                title: 'Sure to Remove?',
                message: 'This action is irreversible, are you sure you want to continue?',
                buttons: {
                    cancel: {
                        label: '<i class="fa fa-times"></i> Cancel',
                        className: 'btn-xs btn-round'
                    },
                    confirm: {
                        label: '<i class="fa fa-check"></i> Yes Remove',
                        className: 'btn-danger btn-xs btn-round'
                    }
                },
                callback: function(result) {
                    if (result) {
                        form.submit();
                    } 
                }
            });        
        });
    </script>

    @include('partials.qualification_unit_with_pcs_script')

@endsection
