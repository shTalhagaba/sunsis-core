@extends('layouts.master')

@section('title', 'Edit Task')

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
            Edit Task of Delivery Plan Session {!! $isTemplate ? '<span class="text-success">Template</span>' : '' !!}
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

            @include('programmes.partials.session_detail', ['programme' => $programme])

            <div id="row">
                <div class="col-xs-12">

                    <div class="space"></div>
                    @include('partials.session_message')
                    @include('partials.session_error')
                    {!! Form::model($task, [
                        'method' => 'PATCH',
                        'url' => route('programmes.sessions.tasks.update', [$programme, $session, $task]),
                        'class' => 'form-horizontal',
                        'files' => true,
                        'id' => 'frmProgrammeSessionTask',
                        'role' => 'form'
                        ]) !!}

                    {!! Form::hidden('id', $task->id) !!}
                    {!! Form::hidden('dp_session_id', $session->id) !!}

                    @include('programmes.sessions.tasks.form', [
                        'programme' => $programme,
                        'session' => $session,
                        'task'=> $task,
                    ])
                    {!! Form::close() !!}
                </div><!-- /.span -->
            </div>
            <!-- PAGE CONTENT ENDS -->
        </div><!-- /.col -->
    </div><!-- /.row -->
@endsection
@include('programmes.sessions.tasks.scripts')
