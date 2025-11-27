<div class="page-header">
    <h1>Training Record
        <small>
            @if($training_record->getOriginal('status_code') == App\Models\Training\TrainingRecord::STATUS_CONTINUING)
            <span class="label label-md label-info arrowed-in arrowed-in-right">{{ $training_record->status_code }}</span>
            @elseif($training_record->getOriginal('status_code') == App\Models\Training\TrainingRecord::STATUS_COMPLETED)
            <span class="label label-md label-success arrowed-in arrowed-in-right">{{ $training_record->status_code }}</span>
            @elseif($training_record->getOriginal('status_code') == App\Models\Training\TrainingRecord::STATUS_WITHDRAWN)
            <span class="label label-md label-danger arrowed-in arrowed-in-right">{{ $training_record->status_code }}</span>
            @elseif($training_record->getOriginal('status_code') == App\Models\Training\TrainingRecord::STATUS_TEMP_WITHDRAWN)
            <span class="label label-md label-warning arrowed-in arrowed-in-right">{{ $training_record->status_code }}</span>
            @elseif($training_record->getOriginal('status_code') == App\Models\Training\TrainingRecord::STATUS_ASSESSMENT_COMPLETED)
            <span class="label label-md label-warning arrowed-in arrowed-in-right">{{ $training_record->status_code }}</span>
            @elseif($training_record->getOriginal('status_code') == App\Models\Training\TrainingRecord::STATUS_BREAK_IN_LEARNING)
            <span class="label label-md label-warning arrowed-in arrowed-in-right">{{ $training_record->status_code }}</span>
            @else
            <span class="label label-md label-info arrowed-in arrowed-in-right">{{ $training_record->status_code }}</span>
            @endif
        </small>
    </h1>
</div>
