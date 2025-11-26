
@php
$otjService = new \App\Services\Students\Trainings\Otj\OtjStatisticsService($training);
$otjStats = $otjService->getStatistics();
@endphp

{{-- <div class="progress">
    <div data-rel="tooltip" class="progress-bar progress-bar-success" title="Accepted"
        style="width: {{ $completedOtjPercentage }}%;">{{ $completedOtjPercentage }}%
    </div>
</div> --}}

<span class="text-info">Total OTJ Hours to Complete: </span>{{ round($otjStats['totalOtjSeconds']/3600) }} <br>
<span class="text-info">Expected Progress: </span>{{ $otjStats['expectedOtjPercentage'] }}% | 
<span class="text-info">Actual Progress: </span>{{ $otjStats['completedOtjPercentage'] }}% | 
<span class="text-info">Progress Status: </span>{!! $otjStats['progressStatus'] == 1 ? '<span class="text-success">On Track</span>' : '<span class="text-danger">Behind</span>' !!} <br> 
<span class="text-info">Expected OTJ Hours: </span>{{ $otjStats['expectedOtjHours'] }} | 
<span class="text-info">Completed OTJ Hours: </span>{{ $otjStats['completedOtjHoursFormatted'] }} |  
@if($otjStats['completedOtjHours'] != $otjStats['expectedOtjHours'])
    <span class="text-info">Expected Hours vs Actual Hours: </span>{!! $otjStats['symbol'] !!} {{ $otjStats['diffFormatted'] }} 
@endif
<br> 
<span class="text-info">Last OTJ Activity: </span>{{ $otjStats['lastOtjActivity'] }}

