@extends('layouts.master')

@section('title', 'Create Programme')

@section('page-plugin-styles')
    <style>
        .dataTable > thead > tr > th[class*="sort"]:before,
        .dataTable > thead > tr > th[class*="sort"]:after {
            content: "" !important;
        }
    </style>
@endsection

@section('page-content')
    <div class="page-header">
        <h1>
            Edit Programme Delivery Plan Session
        </h1>
    </div><!-- /.page-header -->
    <div class="row">
        <div class="col-xs-12">
            <!-- PAGE CONTENT BEGINS -->
            <div class="well well-sm">
                <button class="btn btn-sm btn-white btn-primary btn-round" type="button"
                    onclick="window.location.href='{{ route('programmes.show', $programme) }}'">
                    <i class="ace-icon fa fa-arrow-left bigger-110"></i> Cancel
                </button>
            </div>

            @include('programmes.partials.programme_detail', ['programme' => $programme])

            <div id="row">
                <div class="col-xs-12">

                    <div class="space"></div>

                    @include('partials.session_message')

                    @include('partials.session_error')
                    
                    {!! Form::model($session, [
                     'method' => 'PATCH',
                     'url' => route('programmes.sessions.update', [$programme, $session]),
                     'class' => 'form-horizontal',
                     'id' => 'frmProgrammeSession',
                     'role' => 'form']) !!}
                    
                        {!! Form::hidden('id', $session->id) !!}
                        {!! Form::hidden('programme_id', $programme->id) !!}
                        {!! Form::hidden('is_template', $isTemplate) !!}
                        
                        @include('programmes.sessions.form')

                    {!! Form::close() !!}

                </div><!-- /.span -->
            </div>


            <!-- PAGE CONTENT ENDS -->
        </div><!-- /.col -->
    </div><!-- /.row -->
@endsection

@include('programmes.sessions.scripts')
