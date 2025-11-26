@extends('layouts.master')

@section('title', 'IQA Sample Plan')

@section('page-plugin-styles')
    <link rel="stylesheet" href="{{ asset('assets/css/chosen.min.css') }}" />
@endsection

@section('page-inline-styles')
    <style>
        .table-responsive-wrapper {
            overflow-x: auto;
            position: relative;
            /*
                                                max-width: 100%;
                                                overflow-x: auto;
                                                */
        }

        .table th:first-child,
        .table td:first-child {
            position: sticky;
            left: 0;
            z-index: 2;
            background: #fff;
            /* or your preferred background */
            border-right: 1px solid #ddd;
        }

        /* Optional: Make header cell above sticky column sit above all */
        .table thead th:first-child {
            z-index: 3;
            background: #f9f9f9;
            /* ensure it looks like other header cells */
        }

        .chosen-container {
            width: 100% !important;
        }
    </style>
@endsection

@section('page-content')
    <div class="page-header">
        <h1>IQA Planning</h1>
    </div><!-- /.page-header -->
    <div class="row">
        <div class="col-xs-12">
            <button class="btn btn-sm btn-white btn-default btn-round" type="button"
                onclick="window.location.href='{{ route('iqa_sample_plans.index') }}'">
                <i class="ace-icon fa fa-times bigger-110"></i> Close
            </button>
            @if ((auth()->user()->isAdmin() || auth()->user()->id === $plan->created_by) && $plan->entries->count() === 0)
                {!! Form::open([
                    'method' => 'DELETE',
                    'url' => route('iqa_sample_plans.destroy', $plan),
                    'id' => 'frmDeletePlan',
                    'style' => 'display: inline;',
                    'class' => 'form-inline',
                ]) !!}
                {!! Form::hidden('plan_id_to_del', $plan->id) !!}
                {!! Form::button('<i class="ace-icon fa fa-trash-o bigger-110"></i> Delete', [
                    'data-rel' => 'tooltip',
                    'class' => 'btn btn-danger btn-xs btn-round',
                    'type' => 'click',
                    'id' => 'btnDeletePlan',
                ]) !!}
                {!! Form::close() !!}
            @endif

            <div class="hr hr-12 hr-dotted"></div>

            @include('partials.session_message')

            <div class="row">
                <div class="col-sm-8">
                    <h4 class="text-info bolder">IQA Plan</h4>
                    <span class="bolder text-info">IQA/Verifier: </span>{{ optional($plan->verifier)->full_name }} |
                    <span class="bolder text-info">Assessor: </span>{{ optional($plan->assessor)->full_name }} |
                    <span class="bolder text-info">Learning Aim: </span>{{ $plan->learning_aim_qan }}
                    {{ $plan->learning_aim_title }}
                </div>
                @if ($plan->assessor->rag_rating != '')
                    <div class="col-sm-4">
                        <div class="pull-right">
                            @include('partials.traffic_lights_horizontal', [
                                'trafficLightColor' => $plan->assessor->rag_rating,
                            ])
                        </div>
                    </div>
                @endif
            </div>

            <div class="hr hr-12 hr-dotted"></div>

            <div class="row">

                <div class="col-sm-12">
                    <div class="pull-right">
                        <div class="form-group">
                            <label class="control-label no-padding-right" for="toggleMultiMode"> Multi Mode: </label>
                            <label>
                                <input id="toggleMultiMode" class="ace ace-switch ace-switch-3" type="checkbox"
                                    value="1">
                                <span class="lbl"></span>
                            </label>
                        </div>

                    </div>
                </div>

                <div class="col-sm-12" style="display: none;" id="panelMultiMode">
                    <div class="space"></div>
                    {!! Form::open([
                        'url' => route('iqa_sample_plans.savePlanMultiEntry', $plan),
                        'class' => 'form-horizontal',
                        'name' => 'frmMultiMode',
                        'id' => 'frmMultiMode',
                    ]) !!}

                    <div class="widget-box widget-color-green">
                        <div class="widget-header">
                            <h4 class="widget-title">Select Details</h4>
                        </div>
                        <div class="widget-body">
                            <div class="widget-main">
                                <div class="form-group row required ">
                                    {!! Form::label('planned_completion_date', 'Planned Completion Date', [
                                        'class' => 'col-sm-4 control-label no-padding-right',
                                    ]) !!}
                                    <div class="col-sm-8">
                                        {{ Form::date('planned_completion_date', null, ['class' => 'form-control', 'required']) }}
                                    </div>
                                </div>

                                <div class="form-group row {{ $errors->has('assessment_methods') ? 'has-error' : '' }}">
                                    {!! Form::label('assessment_methods', 'Assessment Methods', ['class' => 'col-sm-4 control-label']) !!}
                                    <div class="col-sm-8">
                                        {!! Form::select(
                                            'assessment_methods[]',
                                            $assessmentMethods,
                                            [],
                                            [
                                                'class' => 'form-control chosen-select',
                                                'required',
                                                'multiple',
                                                'id' => 'assessment_methods',
                                            ],
                                        ) !!}
                                        {!! $errors->first('assessment_methods', '<p class="text-danger">:message</p>') !!}
                                    </div>
                                </div>

                                <div class="form-group row ">
                                    {!! Form::label('reminder_date', 'Reminder Date', [
                                        'class' => 'col-sm-4 control-label no-padding-right',
                                    ]) !!}
                                    <div class="col-sm-8">
                                        {{ Form::date('reminder_date', null, ['class' => 'form-control']) }}
                                    </div>
                                </div>
                            </div>
                            <div class="widget-toolbox padding-8 clearfix">
                                <div class="center">
                                    <button class="btn btn-sm btn-success btn-round" type="submit">
                                        <i class="ace-icon fa fa-save bigger-110"></i> Save Details
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    {!! Form::close() !!}
                </div>

                <div class="col-sm-12 table-responsive">
                    <h4 class="bolder text-primary" style="margin-top: 2%">Learners</h4>

                    <div class="form-group">
                        <label class="control-label no-padding-right" for="toggleShowAllLearners"> Show All: </label>
                        <label>
                            <input id="toggleShowAllLearners" class="ace ace-switch ace-switch-3" type="checkbox"
                                value="1">
                            <span class="lbl"></span>
                        </label>
                    </div>

                    <div class="table-responsive-wrapper">
                        <table class="table table-bordered small">
                            <thead>
                                <tr>
                                    <th>Learner</th>
                                    <th>Dates</th>
                                    @foreach ($units as $unit)
                                        <th title="{{ $unit->title }}">{{ $unit->unit_owner_ref }}
                                            [{{ $unit->unique_ref_number }}]</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($trainings as $training)
                                    <tr {!! $training->status_code != App\Models\Lookups\TrainingStatusLookup::STATUS_CONTINUING
                                        ? 'class="trNonContRec" style="display: none;"'
                                        : '' !!}>
                                        <th>
                                            {{ $training->firstnames }} {{ $training->surname }}
                                            @include('trainings.partials.training_status_label', [
                                                'statusCode' => $training->status_code,
                                            ])
                                        </th>
                                        <td>
                                            <span class="text-info"
                                                title="Start date of training">SD:</span>{{ Carbon\Carbon::parse($training->start_date)->format('d/m/Y') }}<br>
                                            <span class="text-warning"
                                                title="Planned end date of training">PED:</span>{{ Carbon\Carbon::parse($training->planned_end_date)->format('d/m/Y') }}
                                            @if ($training->actual_end_date)
                                                <br>
                                                <span class="text-success"
                                                    title="Actual end date of training">AED:</span>{{ Carbon\Carbon::parse($training->actual_end_date)->format('d/m/Y') }}
                                            @endif
                                        </td>
                                        @foreach ($units as $unit)
                                            <td
                                                title="{{ $unit->unit_owner_ref }} [{{ $unit->unique_ref_number }}] {{ $unit->title }}">
                                                @if (isset($unitsTrs[$unit->system_code][$training->id]))
                                                    {{-- learner is doing this unit --}}
                                                    @php
                                                        $entry = $plan->entries
                                                            ->where('training_id', $training->id)
                                                            ->where('unit_unique_ref_number', $unit->unique_ref_number)
                                                            ->where('unit_owner_ref', $unit->unit_owner_ref)
                                                            ->where(
                                                                'portfolio_unit_id',
                                                                $unitsTrs[$unit->system_code][$training->id][
                                                                    'portfolio_unit_id'
                                                                ],
                                                            )
                                                            ->first();
                                                    @endphp

                                                    {{-- @if (in_array($unitsTrs[$unit->system_code][$training->id]['portfolio_unit_id'], $portfolioUnitWithIqaNotes))
                                                        <span class="pull-left" style="cursor: pointer;" id="btnViewIqaNotes"
                                                            title="Click to view IQA notes/comments and history"
                                                            onclick="viewIqaNotes(
                                                                '{{ $training->id }}',
                                                                '{{ $unitsTrs[$unit->system_code][$training->id]['portfolio_unit_id'] }}'
                                                            )">
                                                            <i class="fa fa-comments"></i>
                                                        </span>
                                                    @endif --}}

                                                    @if (
                                                        $portfolioUnitWithIqaNotes->contains(
                                                            'portfolio_unit_id',
                                                            $unitsTrs[$unit->system_code][$training->id]['portfolio_unit_id']))
                                                        <span class="pull-left"
                                                            style="cursor: pointer; white-space: nowrap;"
                                                            title="Click to view IQA notes/comments and history"
                                                            onclick="viewIqaNotes('{{ $training->id }}', '{{ $unitsTrs[$unit->system_code][$training->id]['portfolio_unit_id'] }}')">
                                                            <i class="fa fa-comments fa-lg"></i>
                                                            {{ optional($portfolioUnitWithIqaNotes->where('portfolio_unit_id', $unitsTrs[$unit->system_code][$training->id]['portfolio_unit_id'])->first())->iqa_type }}
                                                            on
                                                            {{ optional($portfolioUnitWithIqaNotes->where('portfolio_unit_id', $unitsTrs[$unit->system_code][$training->id]['portfolio_unit_id'])->first())->created_at->format('d/m/Y') }}
                                                        </span>
                                                    @endif

                                                    @if (!$unitsTrs[$unit->system_code][$training->id]['iqa_completed'])
                                                        <a class="pull-right" style="cursor: pointer;"
                                                            title="Edit information"
                                                            href="{{ route('iqa_sample_plans.showPlanSingleEntry', $plan) .
                                                                '?TrainingID=' .
                                                                $training->id .
                                                                '&UnitOwnerRef=' .
                                                                urlencode($unit->unit_owner_ref) .
                                                                '&UniqueRefNumber=' .
                                                                urlencode($unit->unique_ref_number) .
                                                                '&portfolio_unit_id=' .
                                                                $unitsTrs[$unit->system_code][$training->id]['portfolio_unit_id'] }}">
                                                            <i class="fa fa-edit"></i>
                                                        </a>
                                                    @endif

                                                    @if ($entry)
                                                        <br>
                                                        <p>
                                                            <span class="text-info"
                                                                title="Planned Date">PD:</span>{{ $entry->planned_completion_date->format('d/m/Y') }}
                                                        </p>
                                                        @if ($unitsTrs[$unit->system_code][$training->id]['iqa_completed'])
                                                            <p>
                                                                <span class="text-info"
                                                                    title="Completion Date">CD:</span>{{ optional($entry->completion_date)->format('d/m/Y') }}
                                                            </p>
                                                        @endif
                                                        <p>
                                                            @php
                                                                $ams = !is_null($entry->assessment_methods)
                                                                    ? json_decode($entry->assessment_methods)
                                                                    : [];
                                                                if (
                                                                    $unitsTrs[$unit->system_code][$training->id][
                                                                        'iqa_completed'
                                                                    ]
                                                                ) {
                                                                    // if unit is iqa'd then show the actual ams
                                                                    $ams = !is_null($entry->actual_assessment_methods)
                                                                        ? json_decode($entry->actual_assessment_methods)
                                                                        : [];
                                                                }
                                                            @endphp
                                                            @if (is_array($ams) && count($ams) > 0)
                                                                @foreach ($ams as $am)
                                                                    <abbr
                                                                        title="{{ App\Models\IQA\IqaPlanEntry::getAssessmentMethodDesc($am, false) }}">{{ App\Models\IQA\IqaPlanEntry::getAssessmentMethodDesc($am) }}</abbr>
                                                                @endforeach
                                                            @endif
                                                        </p>
                                                        @can('iqa-assessment')
                                                            <button type="button" data-val="{{ $entry->portfolio_unit_id }}"
                                                                class="btn btn-minier btn-round btnIqaCheck {{ $unitsTrs[$unit->system_code][$training->id]['iqa_completed'] ? 'btn-success' : 'btn-primary' }}"
                                                                onclick="window.location.href='{{ route('trainings.unit.iqa.show', ['training' => $training->id, 'unit' => $entry->portfolio_unit_id, 'iqa_sample_id' => $plan->id, 'iqa_entry_id' => $entry->id]) }}'">
                                                                IQA Check
                                                            </button>
                                                        @endcan
                                                    @endif

                                                    <br>
                                                    @if (!$unitsTrs[$unit->system_code][$training->id]['iqa_completed'])
                                                        <p class="multi-mode-checkbox text-center" style="display: none;">
                                                            <input type="checkbox" name="multi_mode_checks[]"
                                                                class="multi-checkbox"
                                                                data-training-id="{{ $training->id }}"
                                                                data-portfolio-unit-id="{{ $unitsTrs[$unit->system_code][$training->id]['portfolio_unit_id'] }}"
                                                                data-unit-owner-ref="{{ $unit->unit_owner_ref }}"
                                                                data-unique-ref-number="{{ $unit->unique_ref_number }}"
                                                                value="1" />
                                                            {{-- <input type="checkbox" name="multi_mode_checks[]"
                                                                value="chk|training_{{ $training->id }}|portfoliounitid_{{ $unitsTrs[$unit->system_code][$training->id]['portfolio_unit_id'] }}|unitownerref_{{ urlencode($unit->unit_owner_ref) }}|uniquerefnumber_{{ urlencode($unit->unique_ref_number) }}" /> --}}
                                                        </p>
                                                    @endif
                                                @else
                                                    <p class="text-center text-danger"
                                                        title="Learner is not doing this unit.">
                                                        NA</p>
                                                @endif
                                            </td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>

            </div>

        </div>
    </div>
@endsection

@section('page-plugin-scripts')
    <script src="{{ asset('assets/js/chosen.jquery.min.js') }}"></script>
@endsection

@section('page-inline-scripts')
    <script>
        if (!ace.vars['touch']) {
            $('.chosen-select').chosen({
                allow_single_deselect: true
            });

            $(window)
                .off('resize.chosen')
                .on('resize.chosen', function() {
                    $('.chosen-select').each(function() {
                        var $this = $(this);
                        $this.next().css({
                            'width': $this.parent().width()
                        });
                    })
                }).trigger('resize.chosen');
            $(document).on('settings.ace.chosen', function(e, event_name, event_val) {
                if (event_name != 'sidebar_collapsed') return;
                $('.chosen-select').each(function() {
                    var $this = $(this);
                    $this.next().css({
                        'width': $this.parent().width()
                    });
                })
            });
        }

        $("input[id=toggleMultiMode]").on("change", function() {
            if (this.checked) {
                $(".multi-mode-checkbox").show();
                $("#panelMultiMode").show();
            } else {
                $(".multi-mode-checkbox").hide();
                $("#panelMultiMode").hide();
            }
            $('.chosen-select').trigger('chosen:updated');
        });

        $("input[id=toggleShowAllLearners]").on("change", function() {
            if (this.checked) {
                $(".trNonContRec").show();
            } else {
                $(".trNonContRec").hide();
            }
        });

        $("form[name=frmMultiMode]").on('submit', function(e) {
            var form = $(this);
            form.find(".dynamic-checkbox").remove();

            var selectedChecks = $("input[name='multi_mode_checks[]']:checked");

            if (selectedChecks.length === 0) {
                alert("Please select at least one checkbox.");
                e.preventDefault();
                return false;
            }

            selectedChecks.each(function(index) {
                const trainingId = $(this).data('training-id');
                const portfolioUnitId = $(this).data('portfolio-unit-id');
                const unitOwnerRef = $(this).data('unit-owner-ref');
                const uniqueRefNumber = $(this).data('unique-ref-number');

                form.append($('<input>', {
                    type: 'hidden',
                    name: `multi_mode_checks[${index}][training_id]`,
                    class: 'dynamic-checkbox',
                    value: trainingId
                }));

                form.append($('<input>', {
                    type: 'hidden',
                    name: `multi_mode_checks[${index}][portfolio_unit_id]`,
                    class: 'dynamic-checkbox',
                    value: portfolioUnitId
                }));

                form.append($('<input>', {
                    type: 'hidden',
                    name: `multi_mode_checks[${index}][unit_owner_ref]`,
                    class: 'dynamic-checkbox',
                    value: unitOwnerRef
                }));

                form.append($('<input>', {
                    type: 'hidden',
                    name: `multi_mode_checks[${index}][unique_ref_number]`,
                    class: 'dynamic-checkbox',
                    value: uniqueRefNumber
                }));
            });

            form.find(':submit').attr("disabled", true).html('<i class="fa fa-spinner fa-spin"></i> Saving');
            return true;
        });

        $("button#btnDeletePlan").on('click', function(e) {
            e.preventDefault();

            var form = $(this).closest('form');

            bootbox.confirm({
                title: 'Sure to Remove?',
                message: 'This action is irreversible, are you sure you want to continue?',
                buttons: {
                    cancel: {
                        label: '<i class="fa fa-times"></i> Cancel',
                        className: 'btn-xs btn-round'
                    },
                    confirm: {
                        label: '<i class="fa fa-check"></i> Yes Remove',
                        className: 'btn-danger btn-xs btn-round'
                    }
                },
                callback: function(result) {
                    if (result) {
                        form.submit();
                    }
                }
            });
        });

        function viewIqaNotes(trainingId, portfolioUnitId) {
            $.ajax({
                url: '/iqa-notes-on-grid/' + trainingId + '/' + portfolioUnitId,
                type: 'GET',
                success: function (response) {
                    bootbox.dialog({
                        title: "IQA Notes",
                        message: response,
                        size: 'large',
                        buttons: {
                            ok: {
                                label: "OK",
                                className: 'btn-primary'
                            }
                        }
                    });
                },
                error: function () {
                    bootbox.alert("Failed to load IQA notes.");
                }
            });
        }
    </script>
@endsection
