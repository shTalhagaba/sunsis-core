@extends('layouts.master')

@section('title', 'Create Event')

@section('page-plugin-styles')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-colorpicker/2.5.3/css/bootstrap-colorpicker.min.css" rel="stylesheet">
@endsection

@section('page-content')
    <div class="page-header">
        <h1>
            Create {{ auth()->user()->isQualityManager() ? 'Event/Task' : 'Event' }}
        </h1>
    </div><!-- /.page-header -->
    <div class="row">
        <div class="col-xs-12">
            <!-- PAGE CONTENT BEGINS -->
            <button class="btn btn-sm btn-white btn-default btn-round" type="button" onclick="window.location.href='{{ route('user_events.index') }}'">
                <i class="ace-icon fa fa-arrow-left bigger-110"></i> Cancel
            </button>
            <div class="hr hr-12 hr-dotted"></div>

            @include('partials.session_message')

            @include('partials.session_error')

            <div class="row">
                <div class="col-sm-12">
                    <div class="space"></div>

                    {!! Form::open(['url' => route('user_events.store'), 'class' => 'form-horizontal']) !!}
                    @include('user_events.form')
                    {!! Form::close() !!}

                </div><!-- /.span -->
            </div><!-- /.user-profile -->


            <!-- PAGE CONTENT ENDS -->
        </div><!-- /.col -->
    </div><!-- /.row -->
@endsection


@section('page-plugin-scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-colorpicker/2.5.3/js/bootstrap-colorpicker.min.js"></script>
    <!-- Select2 CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet" />

    <!-- Select2 JS (must be after jQuery 2.1.4) -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.full.min.js"></script>

    
@endsection

@section('page-inline-scripts')

    <script type="text/javascript">
        $(function () {
            $('.colorpicker').colorpicker();

            $('#assign_iqa_id').select2({
                width: '100%'
            });

            const $typeSelect       = $('#typeSelect');
            const $taskType         = $('#taskType');
            const $eventType        = $('#eventType');
            const $assignIqa        = $('#assignIqa');
            const $taskTypeSelect   = $('#taskTypeSelect');
            const $location         = $('#location');
            const $personal         = $('#personal');
            const $eventStatusInput = $('#eventStatusInput');
            const $taskStatusInput  = $('#taskStatusInput');

            function toggleVisibility($el, show = true) {
                $el.toggle(show);
                if (show) $el.removeClass('hidden');
            }

            function updateVisibility() {
                const type     = $typeSelect.val();
                const taskType = $taskTypeSelect.val();

                if (type === 'task') {
                    toggleVisibility($taskType, true);
                    toggleVisibility($eventType, false);
                    toggleVisibility($location, false);
                    toggleVisibility($personal, false);

                    $('[name="event_type"]').prop('required', false);
                    $('[name="task_type"]').prop('required', true);

                    $eventStatusInput.val('');
                    $taskStatusInput.val(1); // STATUS_ASSIGNED

                    toggleVisibility($assignIqa, !!taskType);

                } else {
                    toggleVisibility($taskType, false);
                    toggleVisibility($eventType, true);
                    toggleVisibility($location, true);
                    toggleVisibility($personal, true);

                    $('[name="event_type"]').prop('required', true);
                    $('[name="task_type"]').prop('required', false);

                    $taskStatusInput.val('');
                    $eventStatusInput.val(1); // STATUS_BOOKED

                    $assignIqa.hide();
                }
            }

            $typeSelect.add($taskTypeSelect).on('change', updateVisibility);

            updateVisibility(); // init
        });
    </script>

@endsection

