@extends('layouts.master')

@section('title', 'Employer')

@section('breadcrumbs')
    {{ Breadcrumbs::render('employers.show', $organisation) }}
@endsection

@section('page-content')
    <div class="page-header">
        <h1>View Employer</h1>
    </div>

    <div class="row">
        <div class="col-xs-12">
            <button class="btn btn-sm btn-white btn-default btn-round" type="button"
                onclick="window.location.href='{{ route('employers.index') }}'">
                <i class="ace-icon fa fa-times bigger-110"></i> Close
            </button>
            <button class="btn btn-sm btn-primary btn-bold btn-round" type="button"
                onclick="window.location.href='{{ route('employers.edit', $organisation) }}'">
                <i class="ace-icon fa fa-edit bigger-120"></i> Edit
            </button>
            <div class="hr hr-12 hr-dotted"></div>

            @include('partials.session_message')

            <div class="row">
                <div class="col-md-4">
                    @include('organisations.organisation_basic_details')
                    @include('partials.tags_widget', ['_entity' => $organisation, 'tagTypeDesc' => 'Organisation'])
                </div>
                <div class="col-md-8">
                    <div class="tabbable">
                        <ul id="myTab" class="nav nav-tabs tab-color-blue background-blue padding-18 tab-size-bigger">
                            <li class="active">
                                <a class="tabbedLink" href="#tabLocations" data-toggle="tab">Locations <span
                                        class="badge badge-info">{{ $organisation->locations->count() }}</span></a>
                            </li>
                            <li>
                                <a class="tabbedLink" href="#tabSystemUsers" data-toggle="tab">System Users <span
                                        class="badge badge-info">{{ count($systemUsers) }}</span></a>
                            </li>
                            <li>
                                <a class="tabbedLink" href="#tabContacts" data-toggle="tab">CRM Contacts <span
                                        class="badge badge-info">{{ $organisation->contacts->count() }}</span></a>
                            </li>
                            <li>
                                <a class="tabbedLink" href="#tabFileRepo" data-toggle="tab">File Repository</a>
                            </li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane in active" id="tabLocations">
                                <button class="btn btn-sm btn-primary btn-bold btn-round" type="button"
                                    onclick="window.location.href='{{ route('organisations.locations.create', $organisation) }}'">
                                    Add Location
                                </button>
                                <div class="hr hr-12 hr-dotted"></div>
                                <div class="row">
                                    @foreach ($organisation->locations()->orderBy('is_legal_address', 'DESC')->get() as $location)
                                        <div class="col-sm-6">
                                            @include('organisations.employers.partials.location', [
                                                'location' => $location,
                                            ])
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="tab-pane" id="tabSystemUsers">
                                <div class="row">
                                    @foreach ($systemUsers as $systemUser)
                                        <div class="col-sm-6">
                                            @include('organisations.employers.partials.users', [
                                                'systemUser' => $systemUser,
                                            ])
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <div class="tab-pane" id="tabContacts">
                                <button class="btn btn-sm btn-primary btn-bold btn-round" type="button"
                                    onclick="window.location.href='{{ route('organisations.contacts.create', $organisation) }}'">
                                    Add CRM Contact
                                </button>
                                <div class="hr hr-12 hr-dotted"></div>
                                <div class="row">
                                    @foreach ($organisation->contacts()->with(['location'])->orderBy('firstnames')->get() as $contact)
                                        <div class="col-sm-6">
                                            @include('organisations.employers.partials.contacts', [
                                                'contact' => $contact,
                                            ])
                                        </div>
                                    @endforeach
                                </div>
                            </div>       
                            <div class="tab-pane" id="tabFileRepo">
                                <div class="row">
                                    <div class="col-md-4">
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
                                                    <div class="widget-box {{ $sectionName == '' ? 'widget-color-blue' : '' }}" style="border-radius: 2%; cursor: pointer;" onclick="window.location.href='{{ route('employers.show', ['id' => $organisation->id]) }}'">
                                                        <div class="widget-body">
                                                            <div class="widget-main {{ $sectionName == '' ? 'bg-info' : '' }}">
                                                                <h5 class="bolder blue"><i class="fa fa-folder{{ $sectionName == '' ? '-open' : '' }}"></i> /</h5>
                                                                <small>{{ $sectionFilesCount['main'] }} {{ \Str::plural('File', $sectionFilesCount['main']) }}</small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    @foreach ($organisation->mediaSections as $mediaSection)
                                                        <div class="widget-box {{ $sectionName == $mediaSection->slug ? 'widget-color-blue' : '' }}" style="border-radius: 2%; cursor: pointer;" 
                                                            onclick="window.location.href='{{ route('employers.show', ['id' => $organisation->id]) }}?section={{ urlencode($mediaSection->slug) }}'">
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
                                    <div class="col-md-8">
                                        <div class="widget-box transparent">
                                            <div class="widget-header">
                                                <h4 class="widget-title lighter">Files</h4>
                                            </div>
                                            <div class="widget-body" style="display: block;">
                                                <div class="widget-main padding-6 no-padding-left no-padding-right">
                                                    @include('partials.upload_file_form', [
                                                        'associatedModel' => $organisation, 
                                                        'sectionName' => $sectionName
                                                    ])
                                                    <h4 class="text-info center bolder">{{ $mediaFiles->count() }} {{ \Str::plural('File', $mediaFiles->count()) }}</h4>
                                                            
                                                    @include('partials.model_media_items', ['mediaFiles' => $mediaFiles, 'model' => $organisation])
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

    @include('partials.create_media_section', ['model' => $organisation])
@endsection


@section('page-inline-scripts')
    <script>
        var selectedTab = "{{ session()->get('read_employer_tab') }}";
        if(selectedTab != '')
        {
            $('#myTab a[href="#' + selectedTab + '"]').tab('show');
        }

        $('.tabbedLink').click(function() {
            $.ajax({
                type: "POST",
                url: "{{ route('saveTabInSession') }}",
                data: {_token: '{{ csrf_token() }}', screen: 'read_employer', selectedTab: $(this).attr('href').replace('#', '')}
            });
        });

        $('[data-rel=popover]').popover({
            html:true,
            placement:"auto"
        });
    </script>
@endsection
