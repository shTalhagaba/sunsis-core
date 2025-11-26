@extends('layouts.master')

@section('title', 'Add Task')

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
            Add Task into Delivery Plan Session
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

            @include('trainings.sessions.partials.tasks_table', ['session' => $session])
            @include('trainings.sessions.partials.template_tasks_table', ['training' => $training,'session' => $session])

            <div id="row">
                <div class="col-xs-12">
                    <div class="space"></div>

                    {!! Form::open([
                        'url' => route('trainings.sessions.tasks.store', [$training, $session]), 
                        'class' => 'form-horizontal',
                        'files' => true,
                        ]) !!}

                    {!! Form::hidden('tr_id', $training->id) !!}
                    {!! Form::hidden('dp_session_id', $session->id) !!}

                    @include('trainings.sessions.tasks.form', [
                        'training' => $training,
                        'session' => $session
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
        $(function () {
            $('#tblPcs').DataTable({
                "lengthChange": false,
                "paging": false,
                "info": false,
                "order": false
            });

            $('.dataTables_filter input[type="search"]').css({
                'width': '350px', 'display': 'inline-block'
            });

        });


        function useTemplate(templateTaskId) {
            $.ajax({
                data: {trainingId: {{ $training->id }}, templateTaskId: templateTaskId},
                url: "{{ route('getProgrammeDeliveryPlanSessionTaskTemplate') }}",
            }).done(function (response) {
                console.log(response.data);
                $('#title').val(response.data.title);
                $('#details').val(response.data.details);

                // response.data.pc_ids is an array. Each element of this array has tr_pc_id. We need to set the checked property of the elements[] checkboxes
                // based on the tr_pc_id values.
                var pcIds = response.data.pc_ids;
                $('input[name="elements[]"]').each(function () {
                    if (pcIds.includes(parseInt($(this).val()))) {
                        $(this).prop('checked', true);
                        $(this).closest('tr').addClass('bg-info');

                        // select the table of this checkbox and then select the first row of the table. This row is the unit checkbox. Check the unit checkbox
                        $(this).closest('table').find('input[type="checkbox"]').prop('checked', true);
                        // style="cursor: pointer; display: none;" change this for all rows of the table to display: 'table-row'
                        $(this).closest('table').find('tr').css('display', 'table-row');
                    } else {
                        $(this).prop('checked', false);
                        $(this).closest('tr').removeClass('bg-info');
                    }
                });

            }).fail(function (jqXHR, textStatus, errorThrown) {
                $.alert(errorThrown, textStatus);
            });
        }
    </script>

@endsection
