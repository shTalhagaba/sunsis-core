{!! Form::open([
    'url' => route('trainings.unit.iqa.store', [$training, $unit]),
    'class' => 'frmUnitIqaAssessment form-vertical',
    'name' => 'frmIqaAssessment',
    'id' => 'frmIqaAssessment',
    'method' => 'POST',
]) !!}
{!! Form::hidden('portfolio_unit_id', $unit->id) !!}
{!! Form::hidden('accepted_pcs', 0) !!}
{!! Form::hidden('rejected_pcs', 0) !!}
{!! Form::hidden('iqa_sample_id', $iqaSamplePlan->id) !!}
{!! Form::hidden('iqa_sample_entry_id', optional($iqaPlanEntry)->id) !!}

<div class="widget-box  widget-color-blue2 light-border">
    <div class="widget-header">
        <h5 class="widget-title">IQA Assessment</h5>
    </div>
    <div class="widget-body">
        <div class="widget-main">
            @include('trainings.iqa.partials.unit_pc_iqa_table', [
                'unit' => $unit,
                'acceptedPcsInLastAssessment' => $acceptedPcsInLastAssessment,
                'rejectedPcsInLastAssessment' => $rejectedPcsInLastAssessment,
                'statsLabels' => true,
            ])

            <div class="space-12"></div>
            <p class="text-center">
                <span class="alert alert-danger alertIqaRejectedPcs" style="display: none;">
                    <i class="fa fa-info-circle"></i>
                    There are <span class="lblIqaRejectedPcs bolder">0</span> referred PC's. So, the status of
                    this unit
                    will be 'IQA Referred'.
                </span>
                <span class="alert alert-success alertIqaAcceptedPcs" style="display: none;">
                    <i class="fa fa-info-circle"></i>
                    There are no referred PC's. So, the status of this unit
                    will be 'IQA Accepted'.
                </span>
            </p>
            <div class="space-12"></div>
            <hr>
            
            <div class="form-group required {{ $errors->has('comments') ? 'has-error' : '' }}">
                {!! Form::label(
                    'comments',
                    'Enter your comments. Please explain thoroughly for the assessor to understand your assessment and take corrective actions if required. ',
                    ['class' => 'control-label'],
                ) !!}
                {!! Form::textarea('comments', null, [
                    'class' => 'form-control',
                    'rows' => '10',
                    'id' => 'comments',
                    'maxlength' => 2000,
                    'required'
                ]) !!}
                {!! $errors->first('comments', '<p class="text-danger">:message</p>') !!}
            </div>

            <div class="form-group row {{ $errors->has('actual_assessment_methods') ? 'has-error' : ''}}">
                {!! Form::label('actual_assessment_methods', 'Assessment Methods', ['class' => 'col-sm-4 control-label']) !!}
                <div class="col-sm-8">
                    {!! Form::select('actual_assessment_methods[]', $assessmentMethods, $plannedAssessmentMethods, ['class' => 'form-control chosen-select', 'required', 'multiple', 'id' => 'actual_assessment_methods']) !!}
                    {!! $errors->first('actual_assessment_methods', '<p class="text-danger">:message</p>') !!}
                </div>
            </div>

            <div class="form-group row {{ $errors->has('iqa_type') ? 'has-error' : '' }}">
                {!! Form::label('iqa_type', 'IQA Type', ['class' => 'col-sm-4 control-label']) !!}
                <div class="col-sm-8">
                    {!! Form::select(
                        'iqa_type',
                        App\Models\IQA\IqaSamplePlan::getTypeList(),
                        $iqaSamplePlan->type,
                        ['class' => 'form-control', 'placeholder' => '', 'required', 'id' => 'iqa_type'],
                    ) !!}
                    {!! $errors->first('iqa_type', '<p class="text-danger">:message</p>') !!}
                </div>
            </div>

            <div class="form-group row {{ $errors->has('fully_completed') ? 'has-error' : '' }}">
                {!! Form::label('fully_completed', 'Fully Completed', ['class' => 'col-sm-4 control-label']) !!}
                <div class="col-sm-8">
                    {!! Form::select('fully_completed', ['0' => 'No', '1' => 'Yes'], null, [
                        'class' => 'form-control',
                        'required',
                        'id' => 'fully_completed',
                    ]) !!}
                    {!! $errors->first('fully_completed', '<p class="text-danger">:message</p>') !!}
                </div>
            </div>

        </div>

        <div class="widget-toolbox padding-8 clearfix">
            <div class="center">
                <button class="btn btn-sm btn-success btn-round" type="button"
                    id="btnSubmitIqaAssessment">
                    <i class="ace-icon fa fa-save bigger-110"></i>
                    Save Information
                </button>&nbsp; &nbsp; &nbsp;
            </div>
        </div>
    </div>
</div>


{!! Form::close() !!}


@push('after-scripts')
    <script type="text/javascript">
        var lblIqaAcceptedPcs = 0;
        var lblIqaRejectedPcs = 0;
        var lblIqaTotalSampled = 0;

        $(function() {

            $('[data-rel=tooltip]').tooltip();
            $('[data-rel=popover]').popover({
                html: true
            });

            $('.pc_iqa_status').on('change', function() {
                $(this).closest('tr').removeClass('bg-success');
                $(this).closest('tr').removeClass('bg-danger');
                if (this.value == '1')
                    $(this).closest('tr').addClass('bg-success');
                else if (this.value == '2')
                    $(this).closest('tr').addClass('bg-danger');

                updateStats();
            });

            screenLoad();
        });

        function screenLoad() {
            $('.pc_iqa_status').each(function() {
                $(this).closest('tr').removeClass('bg-success');
                $(this).closest('tr').removeClass('bg-danger');
                if (this.value == '1')
                    $(this).closest('tr').addClass('bg-success');
                else if (this.value == '2')
                    $(this).closest('tr').addClass('bg-danger');
            });

            updateStats();
        }

        function updateStats() {
            var a = 0;
            var r = 0;
            $('.pc_iqa_status').each(function() {
                if (this.value == '1')
                    a++;
                else if (this.value == '2')
                    r++;
            });

            $('span.lblIqaAcceptedPcs').html(a);
            $('span.lblIqaRejectedPcs').html(r);
            $('span.lblIqaTotalSampled').html(a + r);

            if (r == 0) {
                $('span.alertIqaAcceptedPcs').show();
                $('span.alertIqaRejectedPcs').hide();
            }
            if (r > 0) {
                $('span.alertIqaAcceptedPcs').hide();
                $('span.alertIqaRejectedPcs').show();
            }
            $('input[type=hidden][name=accepted_pcs]').val(a);
            $('input[type=hidden][name=rejected_pcs]').val(r);

        }
/*
        $("button#btnSubmitIqaAssessment").click(function(event) {
            event.preventDefault();

            if ($('input[type=hidden][name=accepted_pcs]').val() == 0 && $('input[type=hidden][name=rejected_pcs]')
                .val() == 0) {
                bootbox.alert({
                    title: "Error: Assessment Incomplete",
                    message: 'You have not selected the status for any PC.'
                });
                return false;
            }
            if ($('textarea[name=comments]').val().trim() == '') {
                bootbox.alert({
                    title: "Error: Assessment Incomplete",
                    message: 'Please provide your comments.'
                });
                $('textarea[name=comments]').focus();
                return false;
            }

            $(this).attr("disabled", true);
            $(this).html('<i class="fa fa-spinner fa-spin"></i> Saving');
            $("form[name=frmIqaAssessment]").submit();
        });
*/
        $("button#btnSubmitIqaAssessment").click(function (event) {
            event.preventDefault();

            const $btn = $(this);
            const $form = $("form[name=frmIqaAssessment]");
            const $comments = $('textarea[name=comments]');

            if ($comments.val().trim() === '') {
                bootbox.alert({
                    title: "Error: Assessment Incomplete",
                    message: 'Please provide your comments.'
                });
                $comments.focus();
                return;
            }

            const accepted = $('input[type=hidden][name=accepted_pcs]').val();
            const rejected = $('input[type=hidden][name=rejected_pcs]').val();
            const fullyCompleted = $('#fully_completed').val();

            const submitForm = () => {
                $btn.prop("disabled", true);
                $btn.html('<i class="fa fa-spinner fa-spin"></i> Saving');
                $form.submit();
            };

            if (accepted == 0 && rejected == 0 && fullyCompleted == '1') {
                bootbox.confirm({
                    title: 'Sure to Submit?',
                    message: 'You have not selected the status for any PC, are you sure you want to continue?',
                    buttons: {
                        cancel: {
                            label: '<i class="fa fa-times"></i> Cancel',
                            className: 'btn-xs btn-round'
                        },
                        confirm: {
                            label: '<i class="fa fa-check-o"></i> Yes Submit',
                            className: 'btn-success btn-xs btn-round'
                        }
                    },
                    callback: function (result) {
                        if (result) {
                            submitForm();
                        }
                    }
                });
                return; // prevent continuing to normal submission
            }

            submitForm(); // Normal case
        });


    </script>
@endpush
