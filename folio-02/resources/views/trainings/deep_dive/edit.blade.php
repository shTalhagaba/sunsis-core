@extends('layouts.master')

@section('title', 'Deep Dive')

@section('page-inline-styles')
    <style>
        th {
            text-align: center;
            vertical-align: middle;
            background-color: lightgreen;
        }
    </style>
@endsection

@section('page-content')
    <div class="page-header">
        <h1>
            View Deep Dive Form Details
        </h1>
    </div><!-- /.page-header -->
    <div class="row">
        <div class="col-xs-12">
            <!-- PAGE CONTENT BEGINS -->
            <button class="btn btn-sm btn-white btn-default btn-round" type="button"
                onclick="window.location.href='{{ route('trainings.deep_dives.show', ['training' => $training, 'deep_dive' => $deepDive]) }}'">
                <i class="ace-icon fa fa-arrow-left bigger-110"></i> Cancel
            </button>

            <div class="hr hr-12 hr-dotted"></div>

            @include('partials.session_message')
            @include('partials.session_error')

            {!! Form::model($deepDive, [
                'method' => 'PATCH',
                'url' => route('trainings.deep_dives.update', ['training' => $training, 'deep_dive' => $deepDive]),
                'class' => 'form-horizontal',
                'files' => true,
                'id' => 'frmDeepDive',
            ]) !!}
            <div class="widget-box transparent">
                <div class="widget-header">
                    <h5 class="widget-title">Deep Dive Form</h5>
                </div>
                <div class="widget-body">
                    <div class="widget-main table-responsive">
                        @include('trainings.deep_dive.form')
                    </div>
                </div>
                <div class="widget-toolbox padding-8 clearfix">
                    <div class="center">
                        <button class="btn btn-sm btn-success btn-round" type="submit"><i
                                class="ace-icon fa fa-save bigger-110"></i>Save Information</button>&nbsp; &nbsp;
                        &nbsp;
                    </div>
                </div>
            </div>
            {!! Form::close() !!}

            <!-- PAGE CONTENT ENDS -->
        </div><!-- /.col -->
    </div><!-- /.row -->
@endsection

@push('after-scripts')
@endpush
