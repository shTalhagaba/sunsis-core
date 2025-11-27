@php
$widget_color = '';
$signoff_status = '';
$iqa_status = '';
$pc_progress = $pc->getProgressPercentage();
$pc_awaiting = $pc->getAwaitingPercentage();
if($pc->assessor_signoff == 1)
{
    $widget_color = 'widget-color-green';
    $signoff_status = '<span class="label label-info arrowed-in arrowed-in-right pull-right">signed off</span>';
}
elseif($pc->mapped_evidences->count() > 0)
{
    $widget_color = 'widget-color-orange';
}
if($pc->iqa_status == 1)
{
    $iqa_status = '<span class="label label-info arrowed-in arrowed-in-right ">IQA <i class="fa fa-check-square"></i></span>';
}
elseif($pc->iqa_status == 0 && !is_null($pc->iqa_status))
{
    $iqa_status = '<span class="label label-warning arrowed arrowed-right ">IQA <i class="fa fa-remove"></i></span>';
}
@endphp
<div class="widget-box {{ $widget_color }} collapsed UnitPanel{{ $pcBelongsToUnit->id }}">
    <div class="widget-header widget-header-flat">
        <span class="widget-title" title="{{ $pc->id }}"><strong>{{ $pc->reference }}</strong></span>
        <div class="widget-toolbar">
            <a href="#" data-action="collapse">
                <i class="ace-icon fa fa-chevron-down"></i>
            </a>
        </div>
    </div>
    <div class="widget-body">
        <div class="widget-main">
            {!! nl2br($pc->title) !!}<br>
            @if (!is_null($pc->description))
            {!! nl2br($pc->description) !!}
            @endif
        </div>
        <div class="widget-toolbox padding-2 clearfix">
            <span class="small">Min. required evidences: {{ $pc->min_req_evidences }}</span><br>
            @if($pc->mapped_evidences->count() == 0)
            <p class="text-center"><i data-rel="tooltip" title="Not yet mapped any evidence" class="fa fa-exclamation fa-2x red"></i></p>
            @endif
            @foreach($pc->mapped_evidences AS $evidence)
                @include('students.training.evidences.partials.evidence_popover', ['_evidence_popover' => $evidence])
            @endforeach
        </div>
        @if($pc->assessor_signoff == 1)
        <div style="margin-bottom: 0px;" class="center" title="This pc has been signed off." data-rel="tooltip"><i class="fa fa-thumbs-up fa-2x green"></i></div>
        @else
        <div style="margin-bottom: 0px;" class="progress">
            <div data-rel="tooltip" title="Evidences accepted {{ $pc_progress == 100 ? ' - Ready for signoff' : '' }}"
                class="progress-bar progress-bar-blue" style="width: {{ $pc_progress }}%;">
                <small>{{ $pc_progress }}%</small>
            </div>
            <div data-rel="tooltip" title="Awaiting" class="progress-bar progress-bar-warning" style="width: {{ $pc_awaiting }}%;">
                <small>{{ $pc_awaiting }}%</small>
            </div>
        </div>
        @endif
    </div>
</div>
