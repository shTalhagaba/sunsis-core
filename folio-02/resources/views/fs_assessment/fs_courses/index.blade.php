@extends('layouts.master')

@section('title', 'Functional Skills Courses')

@section('page-content')
    <div class="page-header">
        <h1>Functional Skills Courses</h1>
    </div><!-- /.page-header -->
    <div class="row">
        <div class="col-xs-12">

            @can('create-update-delete-fs-courses')
            <button class="btn btn-sm btn-bold btn-primary btn-round" type="button"
                onclick="window.location.href='{{ route('fs_courses.create') }}'">
                <i class="ace-icon fa fa-plus bigger-120"></i> Add New Course
            </button>
            @endcan
            
            <div class="hr hr-12 hr-dotted"></div>

            <div class="widget-box transparent ui-sortable-handle collapsed">
                <div class="widget-header widget-header-small">
                    <h5 class="widget-title smaller">Search Filters</h5>
                    <div class="widget-toolbar">

                        <a href="#" data-action="collapse"><i class="ace-icon fa fa-chevron-down bigger-125"></i></a>
                    </div>
                </div>
                @include('partials.filter_crumbs')
                <div class="widget-body">
                    <div class="widget-main small">
                        <small> @include('fs_assessment.fs_courses.filter')</small>
                    </div>
                </div>
            </div>

            @include('partials.session_message')

            @include('partials.session_error')

            <div class="table-header">List of functional skills courses</div>

            <div class="center">
                @include('partials.pagination', ['collection' => $fsCourses])
            </div>

            {{-- <ul class="ace-thumbnails clearfix">
                @foreach ($fsCourses as $course)
                    <li>
                        <div class="widget">
                            <div class="widget-body">
                                <div class="widget-main">
                                    
                                </div>
                                <div class="widget-footer text-center">
                                    {{ \Str::limit($course->title, 55, '...') }}
                                </div>
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul> --}}

            <div style="display: flex; flex-wrap: wrap; gap: 15px;">
                @foreach ($fsCourses as $fsCourse)
                    <div style="width: 330px; text-align: center;">
                        <div style="width: 100%; height: 210px; display: flex; align-items: center; justify-content: center; border: 2px solid #ddd; border-radius: 8px; overflow: hidden;">
                            @if (!is_null($fsCourse->getYoutubeId()))
                                <a href="{{ route('fs_courses.show', $fsCourse) }}" title="{{ $fsCourse->title }}"
                                    data-rel="colorbox" class="cboxElement">
                                    <img width="330" height="275" alt="{{ $fsCourse->title }}"
                                        src="{{ $fsCourse->getThumbnail() }}" alt="Course Video/Image"
                                        class="object-cover w-full h-full">
                                </a>
                            @else
                                <a href="{{ route('fs_courses.show', $fsCourse) }}" title="{{ $fsCourse->title }}">
                                    <i class="fa fa-video-camera" style="font-size: 50px; color: #888;"></i>
                                </a>
                            @endif
                        </div>
                        <p style="margin-top: 5px; font-size: 14px; cursor: pointer;" 
                            onclick="window.location.href='{{ route('fs_courses.show', $fsCourse) }}'">{{ \Str::limit($fsCourse->title, 55, '...') }}</p>
                    </div>
                @endforeach
            </div>
            

            <div class="center">
                @include('partials.pagination', ['collection' => $fsCourses])
            </div>

        </div><!-- /.col -->
    </div><!-- /.row -->
@endsection
