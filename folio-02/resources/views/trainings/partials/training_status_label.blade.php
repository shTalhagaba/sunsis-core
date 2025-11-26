@php
    $_statusDescription = App\Models\LookupManager::getTrainingRecordStatus($statusCode);
@endphp

@if($statusCode == App\Models\Lookups\TrainingStatusLookup::STATUS_CONTINUING)
<span class="label label-md label-info arrowed-in arrowed-in-right">{{ $_statusDescription }}</span>
@elseif($statusCode == App\Models\Lookups\TrainingStatusLookup::STATUS_COMPLETED)
<span class="label label-md label-success arrowed-in arrowed-in-right">{{ $_statusDescription }}</span>
@elseif($statusCode == App\Models\Lookups\TrainingStatusLookup::STATUS_WITHDRAWN)
<span class="label label-md label-danger arrowed-in arrowed-in-right">{{ $_statusDescription }}</span>
@elseif($statusCode == App\Models\Lookups\TrainingStatusLookup::STATUS_BIL)
<span class="label label-md label-warning arrowed-in arrowed-in-right">{{ $_statusDescription }}</span>
@else
<span class="label label-md label-light arrowed-in arrowed-in-right">{{ $_statusDescription }}</span>
@endif