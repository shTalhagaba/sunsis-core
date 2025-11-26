@php
    $progressPercentageGreen = $entity->getProgressPercentageGreen();
    $progressPercentageBlue = $entity->getProgressPercentageBlue();
    $awaitingPercentage = $entity->getAwaitingPercentage();

    $bd = !auth()->user()->isAssessor() ? "" : "<p>Please click <span class='btn btn-success btn-xs btn-round'><i class='fa fa-check-circle'></i> Signoff Progress</span> button to signoff the criteria. </p>";
    $ad = !auth()->user()->isAssessor() ? "" : "<p>Please go to the Evidences tab to assess the evidences. </p>";
@endphp

<div class="progress {{ $extraProgressBarClasses ?? '' }} ">
    <div data-rel="popover" data-trigger="hover" data-original-title="Signed off Progress" data-placement="auto" class="progress-bar progress-bar-success" 
        data-content="<p>Percentage of criteria which have been signed off.</p>"
        style="width: {{ $progressPercentageGreen }}%;">{{ $progressPercentageGreen }}%
    </div>

    <div data-rel="popover" data-trigger="hover" data-original-title="Evidence Accepted" data-placement="auto" class="progress-bar progress-bar-blue" 
        data-content="<p>Percentage of criteria which have evidences fully assessed by an assessor.</p> {{ $bd }}"
        style="width: {{ $progressPercentageBlue }}%;">{{ $progressPercentageBlue }}%
    </div>

    <div data-rel="popover" data-trigger="hover" data-original-title="Awaiting Assessment" data-placement="auto" class="progress-bar progress-bar-warning" 
        data-content="<p>Percentage of criteria which have evidences still to be assessed by an assessor.</p> {{ $ad }}"
        style="width: {{ $awaitingPercentage }}%;">{{ $awaitingPercentage }}%</div>
</div>
