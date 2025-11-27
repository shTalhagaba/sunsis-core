<div class="progress">
    <div data-rel="tooltip" class="progress-bar progress-bar-success" title="Signed off"
        style="width: {{ $entity->getProgressPercentageGreen() }}%;">{{ $entity->getProgressPercentageGreen() }}%
    </div>

    <div data-rel="tooltip" class="progress-bar progress-bar-blue" title="Evidences accepted"
        style="width: {{ $entity->getProgressPercentageBlue() }}%;">{{ $entity->getProgressPercentageBlue() }}%
    </div>

    <div data-rel="tooltip" class="progress-bar progress-bar-warning" title="Awaiting"
        style="width: {{ $entity->getAwaitingPercentage() }}%;">{{ $entity->getAwaitingPercentage() }}%</div>
</div>
