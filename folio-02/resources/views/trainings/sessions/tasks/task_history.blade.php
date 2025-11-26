<div class="row">
    <div class="col-xs-12">
        <div class="widget-box transparent">
            <div class="widget-header">
                <h5 class="widget-title">
                    Comments & Feedback
                </h5>                
            </div>
            <div class="widget-body">
                <div class="widget-main">
                    @foreach ($task->history as $entry)
                        <span class="text-info bolder">On</span> {{ $entry->created_at->format('d-M-Y h:i A') }}
                        <span class="text-info bolder">By </span> {{ optional($entry->createdBy)->full_name }} [{{ App\Models\Lookups\UserTypeLookup::getDescription(optional($entry->createdBy)->user_type) }}]
                        <div class="space-6"></div>
                        <div class="well well-sm">
                            {!! nl2br(e($entry->comments)) !!}
                            <br>
                            <span class="text-info bolder">Status</span> {{ $entry->statusDescription() }}
                        </div>
                        <div class="space-12"></div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>