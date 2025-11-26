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
                            <div class="info-div-name"> Start Date </div>
                            <div class="info-div-value">
                                {{ optional($task->start_date)->format('d/m/Y') }}
                            </div>
                        </div>
                        <div class="info-div-row">
                            <div class="info-div-name"> Complete By </div>
                            <div class="info-div-value">
                                {{ optional($task->complete_by)->format('d/m/Y') }}
                            </div>
                        </div>
                        <div class="info-div-row">
                            <div class="info-div-name"> Status </div>
                            <div class="info-div-value">
                                @include('trainings.sessions.partials.task_status_label', ['task' => $task])
                            </div>
                        </div>
                        <div class="info-div-row">
                            <div class="info-div-name"> Details </div>
                            <div class="info-div-value">
                                {!! nl2br(e($task->details)) !!} 
                            </div>
                        </div>
                        <div class="info-div-row">
                            <div class="info-div-name"> Criteria ({{ count($task->pcs()) }}) </div>
                            <div class="info-div-value">
                                @php
                                    $taskPcs = App\Models\Training\PortfolioPC::whereIn('id', $task->pcs())->get();
                                    foreach($taskPcs AS $taskPc)
                                    {
                                        echo nl2br(e($taskPc->title)) . '<hr style="margin-top: 10px; margin-bottom: 10px">';
                                    }
                                @endphp
                            </div>
                        </div>
                        <div class="info-div-row">
                            <div class="info-div-name"> File/Resource </div>
                            <div class="info-div-value">
                                @if($task->media()->where('collection_name', 'tr_task_files')->count() > 0)
                                <div class="col-xs-12">
                                    @include('partials.model_media_items', ['mediaFiles' => $task->media()->where('collection_name', 'tr_task_files')->get(), 'model' => $task])
                                </div>
                                @endif
                                
                                @if(!auth()->user()->isStudent())
                                <div class="col-xs-12">
                                    @include('partials.upload_file_form', [
                                        'associatedModel' => $task, 
                                        'sectionName' => '',
                                        'collectionName' => 'tr_task_files'
                                        ])
                                </div>
                                @endif

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>                
    </div>
</div>