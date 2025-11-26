@extends('layouts.master')

@section('title', 'IQA Planning')

@section('page-content')
    <div class="page-header">
        <h1>
            IQA Planning - Set Entry
        </h1>
    </div><!-- /.page-header -->
    <div class="row">
        <div class="col-xs-12">
            <!-- PAGE CONTENT BEGINS -->
            <button class="btn btn-sm btn-white btn-default btn-round" type="button"
                onclick="window.location.href='{{ route('iqa_sample_plans.show', $plan) }}'">
                <i class="ace-icon fa fa-arrow-left bigger-110"></i> Cancel
            </button>
            @if (isset($savedEntry) && (auth()->user()->isAdmin() || auth()->user()->id === $savedEntry->created_by))
                {!! Form::open([
                    'method' => 'DELETE',
                    'url' => route('iqa_sample_plans.deletePlanEntry', ['plan' => $plan, 'entry' => $savedEntry]),
                    'id' => 'frmDeletePlanEntry',
                    'style' => 'display: inline;',
                    'class' => 'form-inline',
                ]) !!}
                {!! Form::hidden('entry_id_to_del', $savedEntry->id) !!}
                {!! Form::button('<i class="ace-icon fa fa-trash-o bigger-110"></i> Delete', [
                    'data-rel' => 'tooltip',
                    'class' => 'btn btn-danger btn-xs btn-round',
                    'type' => 'click',
                    'id' => 'btnDeletePlanEntry',
                ]) !!}
                {!! Form::close() !!}
            @endif

            <div class="hr hr-12 hr-dotted"></div>

            @include('trainings.partials.training_quick_details', ['showOverallPercentage' => true])

            @include('partials.session_message')

            @include('partials.session_error')

            <div class="row">
                <div class="col-xs-12">
                    <div class="widget-box transparent">
                        <div class="widget-header">
                            <h5 class="widget-title">Unit Details</h5>
                        </div>
                        <div class="widget-body">
                            <div class="widget-main">
                                <div class="info-div info-div-striped">
                                    <div class="info-div-row">
                                        <div class="info-div-name"> Unit References </div>
                                        <div class="info-div-value"><span>{{ optional($unit)->unit_owner_ref }}
                                                [{{ optional($unit)->unique_ref_number }}] </span></div>
                                    </div>
                                    <div class="info-div-row">
                                        <div class="info-div-name"> Unit Title </div>
                                        <div class="info-div-value"><span>{{ optional($unit)->title }}</span></div>
                                    </div>
                                    <div class="info-div-row">
                                        <div class="info-div-name"> Learning Aim </div>
                                        <div class="info-div-value"><span>{{ $plan->learning_aim_qan }}
                                                {{ $plan->learning_aim_title }}</span></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="space"></div>
                    {!! Form::open([
                        'url' =>
                            route('iqa_sample_plans.savePlanSingleEntry', $plan) .
                            '?TrainingID=' .
                            $training->id .
                            '&UnitOwnerRef=' .
                            urlencode($unit->unit_owner_ref) .
                            '&UniqueRefNumber=' .
                            urlencode($unit->unique_ref_number),
                        'class' => 'form-horizontal',
                        'files' => true,
                        'id' => 'frmIqaPlanSingleEntry',
                    ]) !!}

                    {!! Form::hidden('training_id', $training->id) !!}
                    {!! Form::hidden('unit_owner_ref', $unit->unit_owner_ref) !!}
                    {!! Form::hidden('unique_ref_number', $unit->unique_ref_number) !!}
                    {!! Form::hidden('portfolio_unit_id', $portfolioUnitId) !!}

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
                                        {{ Form::date('planned_completion_date', optional($savedEntry)->planned_completion_date, ['class' => 'form-control', 'required']) }}
                                    </div>
                                </div>

                                <div class="form-group row">
                                    {!! Form::label('assessment_methods', 'Assessment Method', [
                                        'class' => 'col-sm-4 control-label no-padding-right',
                                    ]) !!}
                                    <div class="col-sm-8">
                                        <table class="table table-bordered">
                                            <col width="10%" />
                                            <col width="90%" />
                                            @foreach ($assessmentMethods as $mk => $md)
                                                @php
                                                    $savedAms =
                                                        $savedEntry && !is_null($savedEntry->assessment_methods)
                                                            ? json_decode($savedEntry->assessment_methods)
                                                            : [];
                                                @endphp
                                                <tr>
                                                    <td><input type="checkbox" name="assessment_methods[]"
                                                            {{ in_array($mk, $savedAms) ? 'checked' : '' }}
                                                            value="{{ $mk }}" /></td>
                                                    <td>{{ $md }}</td>
                                                </tr>
                                            @endforeach
                                        </table>
                                    </div>
                                </div>

                                <div class="form-group row ">
                                    {!! Form::label('reminder_date', 'Reminder Date', [
                                        'class' => 'col-sm-4 control-label no-padding-right',
                                    ]) !!}
                                    <div class="col-sm-8">
                                        {{ Form::date('reminder_date', optional($savedEntry)->reminder_date, ['class' => 'form-control']) }}
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

                </div><!-- /.span -->
            </div>

            <!-- PAGE CONTENT ENDS -->
        </div><!-- /.col -->
    </div><!-- /.row -->
@endsection


@push('after-scripts')
    <script>
        $("form[name=frmIqaPlanSingleEntry]").on('submit', function() {
            var form = $(this);
            form.find(':submit').attr("disabled", true);
            form.find(':submit').html('<i class="fa fa-spinner fa-spin"></i> Saving');
            return true;
        });
        $("button#btnDeletePlanEntry").on('click', function(e) {
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
    </script>
@endpush
