{!! Form::open([
    'url' => route('trainings.unit.iqa.reply.store', [$training, $unit]),
    'class' => 'frmUnitIqaAssessment form-vertical',
    'name' => 'frmIqaAssessment',
    'id' => 'frmIqaAssessment',
    'method' => 'POST',
]) !!}
{!! Form::hidden('portfolio_unit_id', $unit->id) !!}
{!! Form::hidden('accepted_pcs', 0) !!}
{!! Form::hidden('rejected_pcs', 0) !!}
{!! Form::hidden('iqa_sample_id', $iqaSamplePlan->id) !!}

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
        $("button#btnSubmitIqaAssessment").click(function(event) {
            event.preventDefault();

            if ($('textarea[name=comments]').val().trim() == '') {
                bootbox.alert({
                    title: "Error: Information Incomplete",
                    message: 'Please provide your comments.'
                });
                $('textarea[name=comments]').focus();
                return false;
            }

            $(this).attr("disabled", true);
            $(this).html('<i class="fa fa-spinner fa-spin"></i> Saving');
            $("form[name=frmIqaAssessment]").submit();
        });
    </script>
@endpush
