@extends('layouts.master')

@section('title', 'Create Delivery Plan Session')

@section('page-plugin-styles')
    <style>
        .dataTable>thead>tr>th[class*="sort"]:before,
        .dataTable>thead>tr>th[class*="sort"]:after {
            content: "" !important;
        }
    </style>
@endsection

@section('page-content')
    <div class="page-header">
        <h1>
            Edit Delivery Plan Session
        </h1>
    </div><!-- /.page-header -->
    <div class="row">
        <div class="col-xs-12">
            <!-- PAGE CONTENT BEGINS -->
            <button class="btn btn-sm btn-white btn-default btn-round" type="button"
                onclick="window.location.href='{{ route('trainings.sessions.show', [$training, $session]) }}'">
                <i class="ace-icon fa fa-arrow-left bigger-110"></i> Cancel
            </button>
            <div class="hr hr-12 hr-dotted"></div>

            @include('trainings.partials.training_quick_details', [
                'training' => $training,
                'showOverallPercentage' => true,
            ])

            <div class="space-12"></div>

            @include('partials.session_error')
            @include('partials.session_message')

            <div id="row">
                <div class="col-xs-12">
                    <div class="space"></div>

                    {!! Form::model($session, [
                        'method' => 'PATCH',
                        'url' => route('trainings.sessions.update', [$training, $session]),
                        'class' => 'form-horizontal',
                    ]) !!}

                    {!! Form::hidden('id', $session->id) !!}
                    {!! Form::hidden('tr_id', $training->id) !!}

                    {{-- @include('trainings.sessions.form', ['training' => $training, 'session' => $session]) --}}

                    <div class="row">
                        <div class="col-sm-12 ">
                            <div class="widget-box">
                                <div class="widget-header">
                                    <h4 class="widget-title">Session Details</h4>
                                </div>
                                <div class="widget-body">
                                    <div class="widget-main">
                                        <div
                                            class="form-group row required {{ $errors->has('session_number') ? 'has-error' : '' }}">
                                            {!! Form::label('session_number', 'Session Number', ['class' => 'col-sm-3 control-label']) !!}
                                            <div class="col-sm-9">
                                                {!! Form::text('session_number', null, ['class' => 'form-control', 'required', 'maxlength' => 5]) !!}
                                                {!! $errors->first('session_number', '<p class="text-danger">:message</p>') !!}
                                            </div>
                                        </div>
                                        {{-- <div
                                            class="form-group row required {{ $errors->has('session_sequence') ? 'has-error' : '' }}">
                                            {!! Form::label('session_sequence', 'Session Sequence', ['class' => 'col-sm-3 control-label', 'required']) !!}
                                            <div class="col-sm-9">
                                                {!! Form::number('session_sequence', null, ['class' => 'form-control', 'min' => 1]) !!}
                                                {!! $errors->first('session_sequence', '<p class="text-danger">:message</p>') !!}
                                            </div>
                                        </div> --}}
                                        <div
                                            class="form-group row required {{ $errors->has('session_start_date') ? 'has-error' : '' }}">
                                            {!! Form::label('session_start_date', 'Start/Planned Date', [
                                                'class' => 'col-sm-3 control-label no-padding-right',
                                            ]) !!}
                                            <div class="col-sm-9">
                                                {!! Form::date('session_start_date', $session->session_start_date ?? null, [
                                                    'class' => 'form-control',
                                                    'required',
                                                ]) !!}
                                                {!! $errors->first('session_start_date', '<p class="text-danger">:message</p>') !!}
                                            </div>
                                        </div>
                                        <div
                                            class="form-group required row {{ $errors->has('session_type') ? 'has-error' : '' }}">
                                            {!! Form::label('session_type', 'Visit Type', ['class' => 'col-sm-3 control-label no-padding-right']) !!}
                                            <div class="col-sm-9">
                                                {!! Form::select(
                                                    'session_type',
                                                    [
                                                        'face_to_face' => 'Face-to-Face',
                                                        'remote' => 'Remote',
                                                    ],
                                                    null,
                                                    [
                                                        'class' => 'form-control',
                                                        'required',
                                                    ],
                                                ) !!}
                                                {!! $errors->first('session_type', '<p class="text-danger">:message</p>') !!}
                                            </div>
                                        </div>
                                        <div
                                            class="form-group row {{ $errors->has('session_details_1') ? 'has-error' : '' }}">
                                            {!! Form::label('session_details_1', 'Details / Heading 1', [
                                                'class' => 'col-sm-3 control-label
                                                                                                                no-padding-right',
                                            ]) !!}
                                            <div class="col-sm-9">
                                                {!! Form::textarea('session_details_1', null, ['class' => 'form-control inputLimiter', 'maxlength' => '1600']) !!}
                                                {!! $errors->first('session_details_1', '<p class="text-danger">:message</p>') !!}
                                            </div>
                                        </div>
                                        <div
                                            class="form-group row {{ $errors->has('session_details_2') ? 'has-error' : '' }}">
                                            {!! Form::label('session_details_2', 'Details / Heading 2', [
                                                'class' => 'col-sm-3 control-label
                                                                                                                no-padding-right',
                                            ]) !!}
                                            <div class="col-sm-9">
                                                {!! Form::textarea('session_details_2', null, ['class' => 'form-control inputLimiter', 'maxlength' => '1600']) !!}
                                                {!! $errors->first('session_details_2', '<p class="text-danger">:message</p>') !!}
                                            </div>
                                        </div>
                                    </div>

                                    <div class="widget-toolbox padding-8 clearfix">
                                        <div class="center">
                                            <button class="btn btn-sm btn-round btn-success" type="submit">
                                                <i class="ace-icon fa fa-save bigger-110"></i>
                                                Save Information
                                            </button>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>

                    </div>

                    {!! Form::close() !!}

                </div><!-- /.span -->
            </div>

            <!-- PAGE CONTENT ENDS -->
        </div><!-- /.col -->
    </div><!-- /.row -->
@endsection


@section('page-plugin-scripts')
    <script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
@endsection

@section('page-inline-scripts')

    <script type="text/javascript">
        $(function() {
            $('#tblPcs').DataTable({
                "lengthChange": false,
                "paging": false,
                "info": false,
                "order": false
            });

            $('.dataTables_filter input[type="search"]').css({
                'width': '350px',
                'display': 'inline-block'
            });

        });
    </script>

@endsection
