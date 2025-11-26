<div class="row">
    <div class="col-xs-12">
        <div class="widget-box transparent">
            <div class="widget-header">
                <h5 class="widget-title">
                    Task Details
                </h5>                
            </div>
            <div class="widget-body">
                <div class="widget-main">
                    <div class="info-div info-div-striped">
                        <div class="info-div-row">
                            <div class="info-div-name"> Title </div>
                            <div class="info-div-value">
                                {!! nl2br(e($task->title)) !!}
                            </div>
                        </div>
                        <div class="info-div-row">
                            <div class="info-div-name"> Status</div>
                            <div class="info-div-value">
                                @if($task->status)
                                    <span class="label label-success arrowed-in arrowed-in-right">Active</span>
                                @else
                                    <span class="label label-warning arrowed-in arrowed-in-right">Inactive</span>
                                @endif
                            </div>
                        </div>
                        <div class="info-div-row">
                            <div class="info-div-name"> Details</div>
                            <div class="info-div-value">
                                {!! nl2br(e($task->details)) !!}
                            </div>
                        </div>
                        <div class="info-div-row">
                            <div class="info-div-name"> Criteria ({{ $task->pcs->count() }})</div>
                            <div class="info-div-value">
                                @php
                                    foreach($task->pcs AS $taskPc)
                                    {
                                        echo nl2br(e($taskPc->title)) . '<hr style="margin-top: 10px; margin-bottom: 10px">';
                                    }
                                @endphp
                            </div>
                        </div>
                        <div class="info-div-row">
                            <div class="info-div-name"> File/Resource</div>
                            <div class="info-div-value">
                                @if($task->media()->where('collection_name', 'programme_dp_task_files')->count() > 0)
                                    <div class="col-xs-12">
                                        @include('partials.model_media_items', ['mediaFiles' => $task->media()->where('collection_name', 'programme_dp_task_files')->get(), 'model' => $task])
                                    </div>
                                @endif
                                <div class="col-xs-12">
                                    @include('partials.upload_file_form', [
                                        'associatedModel' => $task, 
                                        'sectionName' => '',
                                        'collectionName' => 'programme_dp_task_files'
                                        ])
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>                
    </div>
</div>