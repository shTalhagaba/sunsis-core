<div class="profile-user-info profile-user-info-striped">
    <div class="profile-info-row">
        <div class="profile-info-name"> Name </div><div class="profile-info-value"><span>{{ $_evi_details->evidence_name }}</span></div>
    </div>
    <div class="profile-info-row">
        <div class="profile-info-name"> Evidence Type </div><div class="profile-info-value"><span>{{ $_evi_details->evidence_type }}</span></div>
    </div>
    @if($evidence->getOriginal('evidence_type') == \App\Models\Training\TrainingRecordEvidence::TYPE_FILE)
        <div class="profile-info-row">
            <div class="profile-info-name"> File </div>
            <div class="profile-info-value">
		@if(!is_null($evidence->media->first()))
                <span>
                    <a href="{{ route('files.download',  $evidence->media->first()) }}" target="_blank" style="cursor: pointer;">
                        {{ $evidence->media->first()->file_name }}
                    </a>
                </span><br>
                <span class="small">{{ $evidence->media->first()->human_readable_size }}</span><br>
                <span class="small"><i class="fa fa-clock-o"></i> {{ \Carbon\Carbon::parse($evidence->media->first()->updated_at)->format('d/m/Y H:i:s') }}</span>
		@endif
            </div>
        </div>
    @elseif($evidence->getOriginal('evidence_type') == \App\Models\Training\TrainingRecordEvidence::TYPE_URL)
        <div class="profile-info-row">
            <div class="profile-info-name"> URL </div>
            <div class="profile-info-value">
                <span><a href="{{ $evidence->evidence_url }}" target="_blank"><i data-trigger="hover" data-rel="popover" data-original-title="External URL"
                    class='fa fa-external-link-square'></i> {{ \Str::limit($evidence->evidence_url, 250) }}
                </a> &nbsp;</span>
            </div>
        </div>
    @elseif($evidence->getOriginal('evidence_type') == \App\Models\Training\TrainingRecordEvidence::TYPE_REFERENCE)
        <div class="profile-info-row">
            <div class="profile-info-name"> Reference </div>
            <div class="profile-info-value"><span>{{ $evidence->evidence_ref }}</span></div>
        </div>
    @endif
    <div class="profile-info-row">
        <div class="profile-info-name"> Evidence Status </div>
        <div class="profile-info-value">
            <span>{{ $_evi_details->evidence_status }}</span>
            @can ('view-iqa-feedback')
            @if ($evidence->iqa_status == 1)
            <span class="label label-md label-success arrowed-in arrowed-in-right">IQA Accepted</span>
            @elseif (!is_null($evidence->iqa_status) && $evidence->iqa_status == 0)
            <span class="label label-md label-danger arrowed-in arrowed-in-right">IQA Rejected</span>
            @endif
            @endcan
        </div>
    </div>
    <div class="profile-info-row">
        <div class="profile-info-name"> Description </div><div class="profile-info-value"><span>{{ $_evi_details->evidence_desc }}</span></div>
    </div>
    <div class="profile-info-row">
        <div class="profile-info-name"> Learner Declaration </div><div class="profile-info-value"><span><i class="fa fa-check green fa-2x"></i></span></div>
    </div>
    <div class="profile-info-row">
        <div class="profile-info-name"> Learner Comments </div><div class="profile-info-value"><span><small>{!! nl2br($_evi_details->learner_comments )!!}</small></span></div>
    </div>
    @if (trim($_evi_details->assessor_comments) != '')
    <div class="profile-info-row">
        <div class="profile-info-name"> Assessor Comments </div><div class="profile-info-value"><span><small>{!! nl2br($_evi_details->assessor_comments )!!}</small></span></div>
    </div>
    @endif
    @can ('view-iqa-feedback')
    <div class="profile-info-row">
        <div class="profile-info-name"> IQA Comments </div><div class="profile-info-value"><span><small>{!! nl2br($_evi_details->verifier_comments )!!}</small></span></div>
    </div>
    @endcan
</div>
