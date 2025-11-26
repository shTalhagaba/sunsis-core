@if ($plan->status != App\Models\IQA\IqaSamplePlan::STATUS_COMPLETED)
    <p><i class="text-info small" style="margin: 0; padding: 0;">{{ $plan->completed_by_date->diffForHumans() }}</i></p>
@endif