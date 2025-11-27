@php
$months_elapsed_percentage = round(($entity->months_elapsed/$entity->total_months)*100);
$bar_color = 'bar-success';
if($entity->getOriginal('status_code') == \App\Models\Training\TrainingRecord::STATUS_CONTINUING && $entity->months_elapsed > $entity->total_months)
{
    $bar_color = 'bar-red';
}
@endphp
<div class="progress">
    <div data-rel="tooltip" class="progress-bar progress-{{ $bar_color }}" title="Months elapsed"
        style="width: {{ $months_elapsed_percentage }}%;">Months elapsed: {{ $entity->months_elapsed }} / {{ $entity->total_months }} months
    </div>
</div>
