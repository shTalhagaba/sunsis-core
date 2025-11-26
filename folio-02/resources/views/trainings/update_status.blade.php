@extends('layouts.master')

@section('title', 'Update Training Record Status')

@section('page-content')
    <div class="page-header">
        <h1>Update Training Record Status</h1>
    </div>
    <div class="row">
        <div class="col-xs-12">
            <!-- PAGE CONTENT BEGINS -->
            <div class="row">
                <div class="col-xs-12">
                    <button class="btn btn-sm btn-white btn-default btn-round" type="button"
                        onclick="window.location.href='{{ route('trainings.show', $training) }}'">
                        <i class="ace-icon fa fa-arrow-left bigger-110"></i> Cancel
                    </button>
                    <div class="hr hr-12 hr-dotted"></div>
                </div>
            </div>

            @include('trainings.partials.training_quick_details', ['showOverallPercentage' => true])

            @include('partials.session_message')

            @include('partials.session_error')

            <div class="row">
                <div class="col-sm-6">
                    <div class="space"></div>

                    {!! Form::model($training->getAttributes(), [
                        'method' => 'PATCH',
                        'url' => route('trainings.statuses.storeUpdate', $training),
                        'class' => 'form-horizontal',
                        'role' => 'form',
                        'name' => 'frmEditTrainingRecordStatus',
                        'id' => 'frmEditTrainingRecordStatus',
                    ]) !!}
                    <div class="widget-box widget-color-green">
                        <div class="widget-header">
                            <h4 class="widget-title">Update Training Record Status</h4>
                        </div>
                        <div class="widget-body">
                            <div class="widget-main">
                                <div class="form-group row required {{ $errors->has('status_code') ? 'has-error' : '' }}">
                                    {!! Form::label('status_code', 'Training Status', ['class' => 'col-sm-4 control-label']) !!}
                                    <div class="col-sm-8">
                                        {!! Form::select('status_code', $statuses, '', [
                                            'class' => 'form-control',
                                            'required',
                                            'id' => 'status_code',
                                            'placeholder' => '',
                                        ]) !!}
                                        {!! $errors->first('status_code', '<p class="text-danger">:message</p>') !!}
                                    </div>
                                </div>
                                <div class="statusFields" id="statusBil" style="display: none;">
                                    <div
                                        class="form-group row required {{ $errors->has('last_day_of_learning') ? 'has-error' : '' }}">
                                        {!! Form::label('last_day_of_learning', 'Last Day of Learning', ['class' => 'col-sm-4 control-label']) !!}
                                        <div class="col-sm-8">
                                            {!! Form::date('last_day_of_learning', null, ['class' => 'form-control', 'required']) !!}
                                            {!! $errors->first('last_day_of_learning', '<p class="text-danger">:message</p>') !!}
                                        </div>
                                    </div>
                                    <div
                                        class="form-group row {{ $errors->has('existing_bil_reason_id') ? 'has-error' : '' }}">
                                        {!! Form::label('existing_bil_reason_id', 'Break in Learning Reason', ['class' => 'col-sm-4 control-label']) !!}
                                        <div class="col-sm-8">
                                            @include('partials.select_control_with_add_btn', [
                                                'lookupDbTable' => 'lookup_tr_bil_reasons',
                                                'lookupDbTableCtrlName' => 'existing_bil_reason_id',
                                                'lookupDbTableOptions' => $bilReasons,
                                            ])
                                        </div>
                                    </div>
                                    <div
                                        class="form-group row {{ $errors->has('expected_return_date') ? 'has-error' : '' }}">
                                        {!! Form::label('expected_return_date', 'Expected Return Date', ['class' => 'col-sm-4 control-label']) !!}
                                        <div class="col-sm-8">
                                            {!! Form::date('expected_return_date', null, ['class' => 'form-control']) !!}
                                            {!! $errors->first('expected_return_date', '<p class="text-danger">:message</p>') !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="statusFields" id="statusCont" style="display: none;">
                                    <div
                                        class="form-group row required {{ $errors->has('restart_date') ? 'has-error' : '' }}">
                                        {!! Form::label('restart_date', 'Restart Date', ['class' => 'col-sm-4 control-label']) !!}
                                        <div class="col-sm-8">
                                            {!! Form::date('restart_date', null, ['class' => 'form-control', 'required']) !!}
                                            {!! $errors->first('restart_date', '<p class="text-danger">:message</p>') !!}
                                        </div>
                                    </div>
                                    <div
                                        class="form-group row required {{ $errors->has('revised_planned_end_date') ? 'has-error' : '' }}">
                                        {!! Form::label('revised_planned_end_date', 'Revised Planned End Date', ['class' => 'col-sm-4 control-label']) !!}
                                        <div class="col-sm-8">
                                            {!! Form::date('revised_planned_end_date', null, ['class' => 'form-control', 'required']) !!}
                                            {!! $errors->first('revised_planned_end_date', '<p class="text-danger">:message</p>') !!}
                                        </div>
                                    </div>
                                    <div
                                        class="form-group row {{ $errors->has('revised_epa_date') ? 'has-error' : '' }}">
                                        {!! Form::label('revised_epa_date', 'Revised EPA Date', ['class' => 'col-sm-4 control-label']) !!}
                                        <div class="col-sm-8">
                                            {!! Form::date('revised_epa_date', null, ['class' => 'form-control']) !!}
                                            {!! $errors->first('revised_epa_date', '<p class="text-danger">:message</p>') !!}
                                        </div>
                                    </div>
                                </div>
                                <div class="statusFields" id="statusComp" style="display: none;">
                                    <div
                                        class="form-group row required {{ $errors->has('completion_date') ? 'has-error' : '' }}">
                                        {!! Form::label('completion_date', 'Completion Date', ['class' => 'col-sm-4 control-label']) !!}
                                        <div class="col-sm-8">
                                            {!! Form::date('completion_date', null, ['class' => 'form-control', 'required']) !!}
                                            {!! $errors->first('completion_date', '<p class="text-danger">:message</p>') !!}
                                        </div>
                                    </div>
                                    <div class="form-group row {{ $errors->has('achievement_date') ? 'has-error' : '' }}">
                                        {!! Form::label('achievement_date', 'Achievement Date', ['class' => 'col-sm-4 control-label']) !!}
                                        <div class="col-sm-8">
                                            {!! Form::date('achievement_date', null, ['class' => 'form-control']) !!}
                                            {!! $errors->first('achievement_date', '<p class="text-danger">:message</p>') !!}
                                        </div>
                                    </div>
                                    <div
                                        class="form-group row {{ $errors->has('learning_outcome_completion') ? 'has-error' : '' }}">
                                        {!! Form::label('learning_outcome_completion', 'Learning Outcome', ['class' => 'col-sm-4 control-label']) !!}
                                        <div class="col-sm-8">
                                            @include('partials.select_control_with_add_btn', [
                                                'lookupDbTable' => 'lookup_tr_learning_outcome',
                                                'lookupDbTableCtrlName' => 'learning_outcome_completion',
                                                'lookupDbTableOptions' => $learningOutcomes,
                                            ])
                                        </div>
                                    </div>
                                </div>
                                <div class="statusFields" id="statusWithdraw" style="display: none;">
                                    <div
                                        class="form-group row required {{ $errors->has('withdraw_date') ? 'has-error' : '' }}">
                                        {!! Form::label('withdraw_date', 'Withdraw Date', ['class' => 'col-sm-4 control-label']) !!}
                                        <div class="col-sm-8">
                                            {!! Form::date('withdraw_date', null, ['class' => 'form-control', 'required']) !!}
                                            {!! $errors->first('withdraw_date', '<p class="text-danger">:message</p>') !!}
                                        </div>
                                    </div>
                                    <div
                                        class="form-group row {{ $errors->has('learning_outcome_withdraw') ? 'has-error' : '' }}">
                                        {!! Form::label('learning_outcome_withdraw', 'Learning Outcome', ['class' => 'col-sm-4 control-label']) !!}
                                        <div class="col-sm-8">
                                            @include('partials.select_control_with_add_btn', [
                                                'lookupDbTable' => 'lookup_tr_learning_outcome',
                                                'lookupDbTableCtrlName' => 'learning_outcome_withdraw',
                                                'lookupDbTableOptions' => $learningOutcomes,
                                            ])
                                        </div>
                                    </div>
                                    <div class="form-group row {{ $errors->has('withdrawal_reason') ? 'has-error' : '' }}">
                                        {!! Form::label('withdrawal_reason', 'Withdrawal Reason', ['class' => 'col-sm-4 control-label']) !!}
                                        <div class="col-sm-8">
                                            @include('partials.select_control_with_add_btn', [
                                                'lookupDbTable' => 'lookup_tr_withdrawl_reasons',
                                                'lookupDbTableCtrlName' => 'withdrawal_reason',
                                                'lookupDbTableOptions' => $withdrawalReasons,
                                            ])
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="widget-toolbox padding-8 clearfix">
                                <div class="center">
                                    <button class="btn btn-sm btn-success btn-round" type="submit">
                                        <i class="ace-icon fa fa-save bigger-110"></i> Save Information
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    {!! Form::close() !!}

                </div><!-- /.span -->
                <div class="col-sm-6">
                    <div class="widget-box transparent">
                        <div class="widget-header">
                            <h4 class="widget-title">Training Status Logs</h4>
                        </div>
                        <div class="widget-body">
                            <div class="widget-main table-responsive">
                                @include('trainings.partials.training_status_changes_table', [
                                    'training' => $training,
                                ])
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- PAGE CONTENT ENDS -->
        </div><!-- /.col -->
    </div><!-- /.row -->
    <div id="dialog" style="display:none;">
        <input type="text" id="newOptionInput">
        <button id="saveOptionBtn">Save</button>
    </div>
@endsection


@section('page-plugin-scripts')
    <script src="{{ asset('assets/js/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('assets/js/jquery-additional-methods.min.js') }}"></script>
@endsection

@section('page-inline-scripts')

    <script>
        $(function() {
            $('#frmEditTrainingRecordStatus').validate({
                errorElement: 'div',
                errorClass: 'help-block',
                focusInvalid: false,
                rules: {
                    status_code: {
                        required: true
                    },
                    last_day_of_learning: {
                        required: function(element) {
                            return $("#status_code").val() ==
                                {{ App\Models\Lookups\TrainingStatusLookup::STATUS_BIL }};
                        }
                    },
                    existing_bil_reason_id: {
                        required: true
                    },
                    restart_date: {
                        required: function(element) {
                            return $("#status_code").val() ==
                                {{ App\Models\Lookups\TrainingStatusLookup::STATUS_CONTINUING }};
                        }
                    },
                    revised_planned_end_date: {
                        required: function(element) {
                            return $("#status_code").val() ==
                                {{ App\Models\Lookups\TrainingStatusLookup::STATUS_CONTINUING }};
                        }
                    },
                    completion_date: {
                        required: function(element) {
                            return $("#status_code").val() ==
                                {{ App\Models\Lookups\TrainingStatusLookup::STATUS_COMPLETED }};
                        }
                    },
                    withdraw_date: {
                        required: function(element) {
                            return $("#status_code").val() ==
                                {{ App\Models\Lookups\TrainingStatusLookup::STATUS_WITHDRAWN }};
                        }
                    }
                },
                messages: {
                    existing_bil_reason_id: "Select break in learning reason or enter new"
                },

                highlight: function(e) {
                    $(e).closest('.form-group').removeClass('has-info').addClass('has-error');
                },

                success: function(e) {
                    $(e).closest('.form-group').removeClass('has-error').addClass('has-info');
                    $(e).remove();
                },

                errorPlacement: function(error, element) {
                    error.insertAfter(element);
                },
                submitHandler: function(form) {
                    $(form).find(':submit').attr("disabled", true);
                    $(form).find(':submit').html('<i class="fa fa-spinner fa-spin"></i> Saving');
                    $(form).closest('div.widget-box').widget_box('reload');
                    form.submit();
                }
            });

            $('#status_code').on('change', function() {
                showHideDivs(this.value);
            });

            var validationErrors = $.parseJSON('{!! $errors !!}');
            $.each(validationErrors, function(index, item) {
                if ($.inArray(index, ["last_day_of_learning", "expected_return_date", "existing_bil_reason_id"]) !== -1) {
                    $('#statusBil').show();
                    return;
                }
                if ($.inArray(index, ["restart_date", "revised_planned_end_date", "revised_epa_date"]) !== -1) {
                    $('#statusCont').show();
                    return;
                }
                if ($.inArray(index, ["completion_date", "learning_outcome_completion"]) !== -1) {
                    $('#statusComp').show();
                    return;
                }
                if ($.inArray(index, ["withdraw_date", "learning_outcome_withdraw"]) !== -1) {
                    $('#statusWithdraw').show();
                    return;
                }
            });

            // showHideDivs({{ $training->status_code }});
        });

        function showHideDivs(option)
        {
            $('.statusFields').hide();
            if (option == '{{ App\Models\Lookups\TrainingStatusLookup::STATUS_BIL }}') {
                $('#statusBil').show();
            }
            if (option == '{{ App\Models\Lookups\TrainingStatusLookup::STATUS_CONTINUING }}') {
                $('#statusCont').show();
            }
            if (option == '{{ App\Models\Lookups\TrainingStatusLookup::STATUS_COMPLETED }}') {
                $('#statusComp').show();
            }
            if (option == '{{ App\Models\Lookups\TrainingStatusLookup::STATUS_WITHDRAWN }}') {
                $('#statusWithdraw').show();
            }
        }

    </script>

@endsection
