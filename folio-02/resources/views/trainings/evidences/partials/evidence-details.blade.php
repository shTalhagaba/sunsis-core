<i class="blue">Created on:
</i>{{ \Carbon\Carbon::parse($_evi_details->created_at)->format('d/m/Y') }}
<i class="blue">at:
</i>{{ \Carbon\Carbon::parse($_evi_details->created_at)->format('H:i:s') }}
<i class="blue">by: </i>{{ $_evi_details->creator->full_name }}
({{ $_evi_details->creator->systemUserType->description }}) |
<i class="blue">Last updated on:
</i>{{ \Carbon\Carbon::parse($_evi_details->updated_at)->format('d/m/Y') }}
<i class="blue">at:
</i>{{ \Carbon\Carbon::parse($_evi_details->updated_at)->format('H:i:s') }}

<div class="profile-user-info profile-user-info-striped">
    <div class="profile-info-row">
        <div class="profile-info-name"> Name </div>
        <div class="profile-info-value"><span>{{ $_evi_details->evidence_name }}</span></div>
    </div>
    <div class="profile-info-row">
        <div class="profile-info-name"> Evidence Category </div>
        <div class="profile-info-value">
            <span>{{ $_evi_details->categories()->pluck('description')->implode(', ') }}</span></div>
    </div>
    @if ($evidence->getOriginal('evidence_type') == \App\Models\Training\TrainingRecordEvidence::TYPE_FILE)
        <div class="profile-info-row">
            <div class="profile-info-name"> {{ \Str::plural('File', $evidence->media()->count()) }} </div>
            <div class="profile-info-value">
                @foreach ($evidence->media as $evidenceMedia)
                    @include('partials.file_media_well', ['fileMedia' => $evidenceMedia])
                @endforeach
            </div>
        </div>
    @elseif($evidence->getOriginal('evidence_type') == \App\Models\Training\TrainingRecordEvidence::TYPE_URL)
        <div class="profile-info-row">
            <div class="profile-info-name"> URL </div>
            <div class="profile-info-value">
                <span><a href="{{ $evidence->evidence_url }}" target="_blank"><i data-trigger="hover"
                            data-rel="popover" data-original-title="External URL"
                            class='fa fa-external-link-square'></i>
                        {{ \Str::limit($evidence->evidence_url, 250) }}
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
            @can('view-iqa-feedback')
                @if ($evidence->isIqaAccpeted())
                    <span class="label label-md label-success arrowed-in arrowed-in-right">{{ App\Models\Training\PortfolioUnitIqa::getDescription($evidence->iqa_status) }}</span>
                @elseif ($evidence->iqa_status == App\Models\Training\PortfolioUnitIqa::STATUS_IQA_REFERRED)
                    <span class="label label-md label-danger arrowed-in arrowed-in-right">{{ App\Models\Training\PortfolioUnitIqa::getDescription($evidence->iqa_status) }}</span>
                @endif
            @endcan
        </div>
    </div>
    <div class="profile-info-row">
        <div class="profile-info-name"> Description </div>
        <div class="profile-info-value"><span>{!! nl2br(e($_evi_details->evidence_desc)) !!}</span></div>
    </div>
    <div class="profile-info-row">
        <div class="profile-info-name"> Learner Declaration </div>
        <div class="profile-info-value">
            <span>
                {!! $_evi_details->learner_declaration == 1 ? 
                    '<i class="fa fa-check green fa-2x"></i>' : 
                    '<span class="red"><i class="fa fa-warning fa-lg"></i> <i>Awaiting learner validation</i></span>' 
                !!}
            </span>
        </div>
    </div>
    <div class="profile-info-row">
        <div class="profile-info-name"> Learner Comments </div>
        <div class="profile-info-value"><span>{!! nl2br(e($_evi_details->learner_comments)) !!}</span></div>
    </div>
    @if($evidence->getOriginal('evidence_type') == \App\Models\Training\TrainingRecordEvidence::TYPE_TYPED_SUBMISSION)
    <div class="profile-info-row">
        <div class="profile-info-name"> Typed Submission </div>
        <div class="profile-info-value"><span>{!! $_evi_details->typed_submission_content !!}</span></div>
    </div>
    @endif
</div>
