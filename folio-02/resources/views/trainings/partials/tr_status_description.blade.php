@php
    $trainingStatusDesc = App\Models\LookupManager::getTrainingRecordStatus($training->status_code);
@endphp

@if($training->status_code == App\Models\Lookups\TrainingStatusLookup::STATUS_CONTINUING)
<span class="label label-md label-info arrowed-in arrowed-in-right">{{ $trainingStatusDesc }}</span>
@elseif($training->status_code == App\Models\Lookups\TrainingStatusLookup::STATUS_COMPLETED)
<span class="label label-md label-success arrowed-in arrowed-in-right">{{ $trainingStatusDesc }}</span>
@elseif($training->status_code == App\Models\Lookups\TrainingStatusLookup::STATUS_WITHDRAWN)
<span class="label label-md label-danger arrowed-in arrowed-in-right">{{ $trainingStatusDesc }}</span>
@elseif($training->status_code == App\Models\Lookups\TrainingStatusLookup::STATUS_BIL)
<span class="label label-md label-warning arrowed-in arrowed-in-right">{{ $trainingStatusDesc }}</span>
@else
<span class="label label-md label-light arrowed-in arrowed-in-right">{{ $trainingStatusDesc }}</span>
@endif