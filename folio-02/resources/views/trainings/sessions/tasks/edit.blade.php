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
            Edit Delivery Plan Session Task
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

            @include('trainings.partials.training_quick_details', ['training' => $training, 'showOverallPercentage' => true])

            <div class="space-12"></div>

            @include('partials.session_message')
            @include('partials.session_error')

            @include('trainings.sessions.partials.session_detail', ['session' => $session])

            @include('trainings.sessions.partials.tasks_table', ['session' => $session, 'task' => $task])

            <div id="row">
                <div class="col-sm-12">
                    <div class="space"></div>

                    {!! Form::model($task, [
                        'method' => 'PATCH',
                        'url' => route('trainings.sessions.tasks.update', [$training, $session, $task]), 
                        'class' => 'form-horizontal',
                        'files' => true,
                        ]) !!}

                        {!! Form::hidden('id', $task->id) !!}
                        {!! Form::hidden('tr_id', $training->id) !!}
                        {!! Form::hidden('dp_session_id', $session->id) !!}
                        
                        @include('trainings.sessions.tasks.form', [
                            'training' => $training, 
                            'session' => $session, 
                            'task'=> $task,
                            'already_selected_pcs' => $selectedElements,
                            'already_selected_units_ids' => $selectedElementsUnitIds,
                        ])

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
    $(function(){
        $('#tblPcs').DataTable({
            "lengthChange": false,
            "paging" : false,
            "info" : false,
            "order": false
        });

        $('.dataTables_filter input[type="search"]').css({
            'width':'350px','display':'inline-block'
        });
        
    });
</script>

@endsection
