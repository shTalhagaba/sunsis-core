@extends('layouts.master')

@section('title', 'View Development Support Entry')

@section('page-content')
    <div class="page-header">
        <h1>
            View Development Support Entry
        </h1>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <button class="btn btn-sm btn-white btn-default btn-round" type="button"
                onclick="window.location.href='{{ route('staff_development_support.index') }}'">
                <i class="ace-icon fa fa-times bigger-110"></i> Close
            </button>

            @if(!$staffDevelopmentSupport->signedBySupportPersonnel())
            <button class="btn btn-sm btn-primary btn-round" type="button"
                onclick="window.location.href='{{ route('staff_development_support.edit', ['staff_development_support' => $staffDevelopmentSupport]) }}'">
                <i class="ace-icon fa fa-edit bigger-110"></i> Edit 
            </button>
            @endif

            @if($staffDevelopmentSupport->canBeSignedBy(auth()->user()))
            <button class="btn btn-sm btn-primary btn-round" type="button"
                onclick="window.location.href='{{ route('staff_development_support.showSupportToSignForm', ['staff_development_support' => $staffDevelopmentSupport]) }}'">
                <i class="ace-icon fa fa-check bigger-110"></i> Sign 
            </button>
            @endif
            <div class="hr hr-12 hr-dotted"></div>

            @include('partials.session_message')

            @include('partials.session_error')

            <div class="row">
                <div class="col-sm-12">

                    @include('staff_development_support.view_details')

                    <div class="space-4"></div>
                    @include('staff_development_support.view_staff_comments', ['staff_comments' => $details->staff_comments ?? '' ])

                    <div class="space-4"></div>
                    @include('staff_development_support.view_signatures')

                </div>
            </div>
        </div><!-- /.col -->
    </div><!-- /.row -->
@endsection
