<div class="row">
    <div class="col-sm-4">
        <div class="widget-box transparent">
            <div class="widget-header">
                <h4 class="widget-title lighter">Sections</h4>
                @if(! auth()->user()->isStudent())
                <div class="widget-toolbar no-border">
                    <a href="#" class="btn btn-xs btn-info btn-round"
                        title="Add new section" id="btnCreateSection">
                        <i class="ace-icon fa fa-plus"></i>
                    </a>
                </div>
                @endif
            </div>
            <div class="widget-body" style="display: block;">
                <div class="widget-main padding-6 no-padding-left no-padding-right">
                    <div class="widget-box {{ $sectionName == '' ? 'widget-color-blue' : '' }}" style="border-radius: 2%; cursor: pointer;" onclick="window.location.href='{{ route('trainings.show', $training) }}'">
                        <div class="widget-body">
                            <div class="widget-main {{ $sectionName == '' ? 'bg-info' : '' }}">
                                <h5 class="bolder blue"><i class="fa fa-folder{{ $sectionName == '' ? '-open' : '' }}"></i> /</h5>
                                <small>{{ $sectionFilesCount['main'] }} {{ \Str::plural('File', $sectionFilesCount['main']) }}</small>
                            </div>
                        </div>
                    </div>
                    @foreach ($training->mediaSections as $mediaSection)
                        <div class="widget-box {{ $sectionName == $mediaSection->slug ? 'widget-color-blue' : '' }}" style="border-radius: 2%; cursor: pointer;" 
                            onclick="window.location.href='{{ route('trainings.show', $training) }}?section={{ urlencode($mediaSection->slug) }}'">
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
                        @if(! auth()->user()->isStudent())
                        <div class="col-xs-12">
                            @include('partials.upload_file_form', [
                                'associatedModel' => $training, 
                                'sectionName' => $sectionName
                                ])
                        </div>
                        @endif 
                        <div class="col-xs-12">
                            <hr class="hr hr-dotted">
                            <h4 class="text-info center bolder">{{ $mediaFiles->count() }} {{ \Str::plural('File', $mediaFiles->count()) }}</h4>
                            
                            @include('partials.model_media_items', ['mediaFiles' => $mediaFiles, 'model' => $training])

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('partials.create_media_section', ['model' => $training])