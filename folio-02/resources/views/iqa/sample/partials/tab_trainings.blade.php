@if(!$plan->isCompleted())
<span class="btn btn-primary btn-sm btn-round {{ count($plan->units) == 0 ? 'disabled' : '' }}"
    title="{{ count($plan->units) == 0 ? 'Please add units to this plan and then add training records.' : '' }}" 
    onclick="{{ count($plan->units) > 0 ? "window.location.href='" . route('iqa_sample_plans.trainings.manage', $plan) . "'" : "" }}">
    <i class="fa fa-edit"></i> Add Training Records
</span>
@endif
<div class="hr hr-12 hr-dotted"></div>

<h5 class="text-primary bolder text-center">
    {{ $plan->trainings->count() }} {{ Str::plural('Training Record', $plan->trainings->count()) }} in this plan
</h5>
<table class="table table-bordered table-hover">
    <tr>
        <th>Learner Name</th><th>Status</th><th>Dates</th><th>Primary Assessor</th>
        @if($plan->isScheduled())
        <th></th>
        @endif
    </tr>
    @foreach ($plan->trainings as $training)
    <tr>
        <td>
            {{ optional($training->record->student)->full_name }}
        </td>
        <td>
            @include('trainings.partials.training_status_label', ['statusCode' => $training->record->status_code])
        </td>
        <td>
            <span class="text-info">Start Date: </span>{{ $training->record->start_date->format('d/m/Y') }}<br>
            <span class="text-info">Planned End Date: </span>{{ $training->record->planned_end_date->format('d/m/Y') }}<br>
            <span class="text-info">Actual End Date: </span>{{ optional($training->record->actual_end_date)->format('d/m/Y') }}
        </td>
        <td>
            {{ App\Models\LookupManager::getAssessors($training->record->primary_assessor) }}
        </td>
        @if($plan->isScheduled())
        <td>
            {!! Form::open([
                'method' => 'DELETE',
                'url' => route('iqa_sample_plans.trainings.delete', [$plan, $training]),
                'style' => 'display: inline;',
                'class' => 'form-inline frmDeleteTraining',
            ]) !!}
            {!! Form::button('<i class="ace-icon fa fa-trash"></i>', [
                'class' => 'btn btn-danger btn-xs pull-right btn-round btnDeleteTraining',
                'id' => 'btnDeleteTraining' . $training->id,
                'type' => 'submit',
                'style' => 'display: inline',
            ]) !!}
            {!! Form::close() !!}
        </td>
        @endif
    </tr>
    @endforeach
</table>                                 

@push('after-scripts')
    <script>
        $('.btnDeleteTraining').on('click', function(e) {
            e.preventDefault();
            var form = this.closest('form');
            bootbox.confirm({
                title: 'Confirm Remove?',
                message: 'Are you sure you want to remove this training record from the plan?',
                buttons: {
                    cancel: {
                        label: '<i class="fa fa-times"></i> Cancel',
                        className: 'btn-default btn round btn-xs btn-round'
                    },
                    confirm: {
                        label: '<i class="fa fa-trash-o"></i> Yes Remove',
                        className: 'btn-danger btn round btn-xs btn-round'
                    }
                },
                callback: function(result) {
                    if (result) {
                        $(form).find(':submit').attr("disabled", true);
                        $(form).find(':submit').html('<i class="fa fa-spinner fa-spin"></i>');
                        form.submit();
                    }
                }
            });
        });
       
    </script>
@endpush