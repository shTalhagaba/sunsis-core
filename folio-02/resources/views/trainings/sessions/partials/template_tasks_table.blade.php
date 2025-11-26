<?php
$programeSession = \App\Models\Programmes\ProgrammeDeliveryPlanSession::where('programme_id', $training->programme->id)
    ->where('session_number', $session->session_number)
    ->first();
$taskCount = $programeSession ? $programeSession->templateTasks->count() : 0;
?>

@if($taskCount)
    <div class="row">
        <div class="col-xs-12">
            <div class="widget-box collapsed">
                <div class="widget-header">
                    <h4 class="widget-title">Available Task Template(s)</h4>
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
                                <th>Action</th>
                                <th>Task Details</th>
                                <th>Criteria</th>
                            </tr>

                            @foreach($programeSession->templateTasks as $templateTask)
                                <tr>
                                    <td>
                                    <span onclick="useTemplate({{ $templateTask->id }})"
                                          class="btn btn-xs btn-info btn-round">
                                        Use Template
                                    </span>
                                    </td>
                                    <td>
                                        <span class="text-info bolder">Title: </span><br>{{ $templateTask->title }}<br>
                                        <span class="text-info bolder">Details: </span><br>{!! nl2br(e($templateTask->details)) !!}
                                    </td>
                                    <td>
                                        @php
                                            if($templateTask->pcs->count())
                                            {
                                                foreach($templateTask->pcs AS $templateTaskPC)
                                                {
                                                    echo nl2br(e($templateTaskPC->title)) . '<hr style="margin-top: 10px; margin-bottom: 10px">';
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
@endif
