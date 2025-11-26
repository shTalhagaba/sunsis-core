@extends('layouts.master')

@section('title', 'Learning Resource')

@section('page-content')
    <div class="page-header">
        <h1><i class="fa fa-book fa-lg"></i> Learning Resources</h1>
    </div><!-- /.page-header -->
    <div class="row">
        <div class="col-xs-12">
            @if(auth()->user()->isStaff() && !auth()->user()->isQualityManager())
            <button class="btn btn-sm btn-bold btn-primary btn-round" type="button"
                onclick="window.location.href='{{ route('learning_resources.create') }}'">
                <i class="ace-icon fa fa-plus bigger-120"></i> Add New Resource
            </button>
            @endif

            <div class="hr hr-12 hr-dotted"></div>

            @include('partials.session_message')

            <div>
                <div class="row search-page" id="search-page-1">
                    <div class="col-xs-12">
                        <div class="row">
                            <div class="col-xs-12 col-sm-3">
                                <div class="search-area well well-sm">
                                    {!! Form::open([
                                        'url' => route('learning_resources.index'), 
                                        'method' => 'GET',
                                        'role' => 'form',
                                        'name' => 'formFilters',
                                        ]) !!}
                                        <input name="_reset" type="hidden" value="0">

                                        <div class="search-filter-header bg-primary">
                                            <h5 class="smaller no-margin-bottom">
                                                <i class="ace-icon fa fa-sliders light-green bigger-130"></i>&nbsp; Search
                                            </h5>
                                        </div>

                                        <div class="space-4"></div>

                                        <div class="row">
                                            <div class="col-xs-12 ">
                                                <input type="text" class="form-control" name="keyword" placeholder="Search by keywords" value="{{ $selectedFilters['keyword'] }}">
                                            </div>
                                        </div>

                                        <div class="space-4"></div>

                                        <div class="row">
                                            <div class="col-xs-12 ">
                                                <div class="form-group ">
                                                    <label for="is_featured" class="control-label">Featured Resources Only</label>
                                                    <div>
                                                        <input name="is_featured" class="ace ace-switch ace-switch-2" type="checkbox" value="1" {{ $selectedFilters['is_featured'] ? 'checked' : '' }}>
                                                        <span class="lbl"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-xs-12 ">
                                                <div class="form-group ">
                                                    <label for="sort_by" class="control-label">Sort By</label>
                                                    <select class="form-control" id="sort_by" name="sort_by">
                                                        @foreach ([
                                                            'learning_resources.likes' => 'Resource Rating',
                                                            'learning_resources.created_at' => 'Resource Creation Date',
                                                            'learning_resources.resource_type' => 'Resource Type',
                                                            'learning_resources.resource_name' => 'Resource Name',
                                                        ] as $key => $value)
                                                            <option value="{{ $key }}" {{ $selectedFilters['sort_by'] == $key ? 'selected="selected"' : '' }}>{{ $value }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-xs-12 ">
                                                <div class="form-group ">
                                                    <label for="direction" class="control-label">Order</label>
                                                    <select class="form-control" id="direction" name="direction">
                                                        <option value="DESC" {{ $selectedFilters['direction'] == 'DESC' ? 'selected="selected"' : '' }}>Descending</option>
                                                        <option value="ASC" {{ $selectedFilters['direction'] == 'ASC' ? 'selected="selected"' : '' }}>Ascending</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="row">
                                            <div class="col-xs-12 ">
                                                <div class="form-group ">
                                                    <label for="direction" class="control-label">Tags</label>
                                                    <div class="control-group" style="max-height: 150px; overflow-y: scroll">
                                                        @foreach (App\Models\Tags\Tag::orderBy('name')->whereType('LearningResource')->get() as $tag)
                                                        <div class="checkbox">
                                                            <label>
                                                                <input name="tags[]" type="checkbox" class="ace" value="{{ $tag->id }}"
                                                                @if( $selectedFilters['tags'] && is_array($selectedFilters['tags']) && in_array($tag->id, $selectedFilters['tags']))
                                                                checked="checked"
                                                                @endif
                                                                >
                                                                <span class="lbl"> {{ $tag->name }}</span>
                                                            </label>
                                                        </div>    
                                                        @endforeach
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="text-center">
                                            <button type="button" class="btn btn-default btn-round btn-sm btn-white" onclick="resetFiltersForm(this);">
                                                <i class="ace-icon fa fa-remove red2"></i>
                                                Reset
                                            </button>

                                            <button type="submit" class="btn btn-default btn-round btn-white">
                                                <i class="ace-icon fa fa-refresh green"></i>
                                                Update
                                            </button>
                                        </div>

                                        <div class="space-4"></div>
                                    {!! Form::close() !!}
                                </div>
                                
                            </div>

                            <div class="col-xs-12 col-sm-9">

                                <div class="row">
                                    @foreach ($learningResources as $learningResource)
                                    <div class="col-xs-12">
                                        <div class="media search-media">
                                            <div class="media-left">
                                                <a href="{{ route('learning_resources.show', $learningResource) }}">
                                                    <i class="fa {{ $learningResource->icon() }} fa-3x"></i>
                                                </a>
                                            </div>
                    
                                            <div class="media-body">
                                                <div>
                                                    <h4 class="media-heading">
                                                        <a href="{{ route('learning_resources.show', $learningResource) }}" class="blue">{{ $learningResource->resource_name }}</a>
                                                        {!! $learningResource->is_featured ? '<i class="fa fa-star orange" title="Featured resource"></i>' : '' !!}
                                                    </h4>
                                                </div>
                                                <p>{{ Str::limit($learningResource->resource_short_description, 250, '...') }}</p>
                                                @foreach ($learningResource->tags as $tag)
                                                    <h6 role="entity_tag" style="display: inline;">
                                                        <span class="label label-info label-sm" style="display: margin-right: 5px; margin-bottom: 5px;">
                                                            <i class="fa fa-tag"></i> {{ $tag->name }} &nbsp;                                                            
                                                        </span>
                                                    </h6>
                                                @endforeach
                                                <div class="search-actions text-center">
                                                    <div class="action-buttons bigger-125">
                                                        <a id="likeIt" href="#" data-resource-id="{{ $learningResource->id }}">
                                                            @if($learningResource->userLike && $learningResource->userLike->liked)
                                                                <i class="ace-icon fa fa-thumbs-up green fa-lg"></i>
                                                            @else
                                                                <i class="ace-icon fa fa-thumbs-o-up green fa-lg"></i>
                                                            @endif
                                                        </a>                    
                                                        <a id="bookmarkIt" href="#" data-resource-id="{{ $learningResource->id }}">
                                                            @if($learningResource->userBookmark && $learningResource->userBookmark->bookmarked)
                                                                <i class="ace-icon fa fa-bookmark green fa-lg"></i>
                                                            @else
                                                                <i class="ace-icon fa fa-bookmark-o green fa-lg"></i>
                                                            @endif
                                                        </a>
                                                    </div>
                                                    <a class="search-btn-action btn btn-sm btn-block btn-info" href="{{ route('learning_resources.show', $learningResource) }}">View Detail</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>    
                                    @endforeach    
                                    @include('partials.pagination', ['collection' => $learningResources])        
                                </div>
                    
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

@endsection


@section('page-inline-scripts')
    <script>
        $("a#likeIt").on('click', function(e){
            e.preventDefault();

            $(this).children('.ace-icon').toggleClass('fa-thumbs-o-up fa-thumbs-up');

            const resourceId = $(this).attr('data-resource-id');

            let url = '{{ route("learning_resources.users.like", ":learning_resource") }}';
            url = url.replace(':learning_resource', resourceId);

            $.ajax({ 
                method: 'POST', 
                url: url,
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
            });
        });

        $("a#bookmarkIt").on('click', function(e){
            e.preventDefault();

            $(this).children('.ace-icon').toggleClass('fa-bookmark-o fa-bookmark');

            const resourceId = $(this).attr('data-resource-id');

            let url = '{{ route("learning_resources.users.bookmark", ":learning_resource") }}';
            url = url.replace(':learning_resource', resourceId);

            $.ajax({ 
                method: 'POST', 
                url: url,
                headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
            });
        });

        function resetFiltersForm(resetButton)
        {
            $("input[type=checkbox][name='tags[]']").each(function(){
                $(this).attr('checked', false);
            });
            resetViewFilters(resetButton);
        }
    </script>

@endsection
