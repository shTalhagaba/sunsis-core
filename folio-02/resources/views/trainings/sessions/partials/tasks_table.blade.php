<div class="row">
    <div class="col-xs-12">
        <div class="widget-box collapsed">
            <div class="widget-header">
                <h4 class="widget-title">Other Tasks</h4>
                <div class="widget-toolbar">
                    <div class="widget-menu">
                        <a href="#" data-action="collapse">
                            <i class="ace-icon fa fa-chevron-down"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="widget-body">
                <div class="widget-main table-responsive">
                    <table class="table table-bordered small">
                        <tr>
                            <th>#</th>
                            <th>Details</th>
                            <th>Start Date</th>
                            <th>Complete By</th>
                            <th>Criteria</th>
                        </tr>
                        @foreach($session->tasks as $otherTask)
                            <tr class="{!! isset($task) && $otherTask->id === $task->id ? 'text-info' : '' !!}">
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <span class="text-info bolder">Title: </span><br>{{ $otherTask->title }}<br>
                                    <span class="text-info bolder">Details: </span><br>{!! nl2br(e($otherTask->details)) !!}
                                </td>
                                <td>{{ optional($otherTask->start_date)->format('d/m/Y') }}</td>
                                <td>{{ optional($otherTask->complete_by)->format('d/m/Y') }}</td>
                                <td>
                                    @php
                                        if(count($otherTask->pcs()) > 0)
                                        {
                                            $otherTaskPcs = App\Models\Training\PortfolioPC::whereIn('id', $otherTask->pcs())->get();
                                            foreach($otherTaskPcs AS $otherTaskPC)
                                            {
                                                echo nl2br(e($otherTaskPC->title)) . '<hr style="margin-top: 10px; margin-bottom: 10px">';
                                            }
                                        }
                                    @endphp
                                </td>
                            </tr>
                        @endforeach
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
