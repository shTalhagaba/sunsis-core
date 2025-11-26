@if($task->statusDescription() == 'COMPLETED')
    <span class="label label-success arrowed-in arrowed-in-right">COMPLETED</span>
@elseif($task->statusDescription() == 'PENDING')
    <span class="label label-warning arrowed-in arrowed-in-right">PENDING</span>
@else
    <span class="label label-info arrowed-in arrowed-in-right">{{ $task->statusDescription() }}</span>
@endif
