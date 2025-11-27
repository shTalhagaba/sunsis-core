@php
$evidence_status = '';
$widget_color = '';
$iqa_status = '';
if($evidence->getOriginal('evidence_status') == \App\Models\Training\TrainingRecordEvidence::STATUS_LEARNER_SUBMITTED)
{
    if($evidence->mapped_pcs()->count() == 0)
    {
        $evidence_status = '<span class="label label-md label-info arrowed-in arrowed-in-right">Learner Submitted</span>';
        $widget_color = '';
    }
    else
    {
        $evidence_status = '<span class="label label-md label-info arrowed-in arrowed-in-right">Learner Submitted</span>';
        $widget_color = 'widget-color-orange';
    }
}
elseif ($evidence->getOriginal('evidence_status') == \App\Models\Training\TrainingRecordEvidence::STATUS_ASSESSOR_ACCEPTED)
{
    $evidence_status = '<span class="label label-md label-info arrowed-in arrowed-in-right ">Assessor Accepted</span>';
    $widget_color = 'widget-color-blue';
}
elseif ($evidence->getOriginal('evidence_status') == \App\Models\Training\TrainingRecordEvidence::STATUS_ASSESSOR_REJECTED)
{
    $evidence_status = '<span class="label label-md label-info arrowed-in arrowed-in-right">Assessor Rejected</span>';
    $widget_color = 'widget-color-red2';
}
elseif ($evidence->getOriginal('evidence_status') == \App\Models\Training\TrainingRecordEvidence::STATUS_LEARNER_RESUBMITTED)
{
    $evidence_status = '<span class="label label-md label-info arrowed-in arrowed-in-right">Learner Resubmitted</span>';
    $widget_color = 'widget-color-orange';
}
if ($evidence->iqa_status == 1)
{
    $iqa_status = '<span class="label label-md label-success arrowed-in arrowed-in-right">IQA Accepted</span>';
}
elseif (!is_null($evidence->iqa_status) && $evidence->iqa_status == 0)
{
    $iqa_status = '<span class="label label-md label-danger arrowed-in arrowed-in-right">IQA Rejected</span>';
}
$verifier_id = !is_null($training_record->verifierUser) ? $training_record->verifierUser->id : 0;
@endphp
<div class="widget-box {{ $widget_color }} collapsed widgetEvidences">
    <div class="widget-header widget-header-flat">
        <h5 class="widget-title bolder">
            {{ $evidence->evidence_name }}
        </h5>
        <div class="widget-toolbar">
            @if($iqa_status != '')
            @can('view-iqa-feedback')
            {!! $iqa_status !!}
            @endcan
            @endif
            <span class="">{!! $evidence_status !!}</span>
            <a title="Click to view detail" href="{{ route('students.training.evidence.show', [$student, $training_record, $evidence]) }}" target="_blank">
                <i class="ace-icon fa fa-folder-open fa-lg white"></i>
            </a>
            <a data-action="collapse" href="#"><i class="ace-icon fa fa-chevron-down"></i></a>
        </div>
    </div>
    <div class="widget-body">
        <div class="widget-toolbox padding-8 clearfix">
            @if(\Auth::user()->isStudent())
                @switch($evidence->getOriginal('evidence_status'))
                    @case(\App\Models\Training\TrainingRecordEvidence::STATUS_LEARNER_SUBMITTED)
                        <button data-rel="tooltip" title="Map this evidence to the units and performance criteria" type="button" class="btn btn-xs btn-primary btn-round"
                            onclick="window.location.href='{{ route('students.training.evidences.mapping', [$student, $training_record, $evidence]) }}'">
                            <i class="ace-icon fa fa-paperclip bigger-120"></i> Mapping
                        </button>
                        {!! Form::open(['method' => 'DELETE',
                            'url' => route('students.training.evidences.destroy', [$student, $training_record, $evidence]),
                            'id' => 'frmDeleteEvidence'.$evidence->id,
                            'style' => 'display: inline;',
                            'class' => 'form-inline' ]) !!}
                            {!! Form::hidden('evidence_id_to_del', $evidence->id) !!}
                            {!! Form::button('<i class="ace-icon fa fa-trash-o"></i> Delete', ['data-rel'=> 'tooltip',
                                'title' => 'Delete this evidence',
                                'class' => 'btn btn-danger btn-xs btn-round btnDelEvi',
                                'type' => 'submit']) !!}
                        {!! Form::close() !!}
                        @break
                    {{-- @case(\App\Models\Training\TrainingRecordEvidence::STATUS_ASSESSOR_REJECTED)
                        <button data-rel="tooltip" title="Resubmit this evidence" type="button" class="btn btn-xs btn-primary btn-round"
                            onclick="window.location.href='{{ route('students.training.evidences.resubmit', [$student, $training_record, $evidence]) }}'">
                            <i class="ace-icon fa fa-repeat" bigger-120"></i> Resubmit
                        </button>
                        @break --}}
                    @default
                @endswitch
            @endif

            @if(!\Auth::user()->isStudent())
                @switch($evidence->getOriginal('evidence_status'))
                    @case(\App\Models\Training\TrainingRecordEvidence::STATUS_LEARNER_SUBMITTED)
                        @can('assess-evidence')
                        <button data-rel="tooltip"
                            title="Check this evidence and save your comments" type="button"
                            class="btn btn-xs btn-primary btn-round"
                            onclick="window.location.href='{{ route('students.training.evidences.assess', [$student, $training_record, $evidence]) }}'">
                            <i class="ace-icon fa fa-check bigger-110"></i> Assess &nbsp;
                        </button> &nbsp;
                        @endcan
                        @can('delete-evidence')
                        {!! Form::open(['method' => 'DELETE',
                            'url' => route('students.training.evidences.destroy', [$student, $training_record, $evidence]),
                            'id' => 'frmDeleteEvidence'.$evidence->id,
                            'style' => 'display: inline;',
                            'class' => 'form-inline' ]) !!}
                            {!! Form::hidden('evidence_id_to_del', $evidence->id) !!}
                            {!! Form::button('<i class="ace-icon fa fa-trash-o"></i> Delete', ['data-rel'=> 'tooltip',
                                'title' => 'Delete this evidence',
                                'class' => 'btn btn-danger btn-xs btn-round btnDelEvi',
                                'type' => 'submit']) !!}
                        {!! Form::close() !!}
                        @endcan
                        @break
                    @case(\App\Models\Training\TrainingRecordEvidence::STATUS_ASSESSOR_ACCEPTED)
                        @can('assess-evidence')
                        <button data-rel="tooltip"
                            title="This evidence is accepted but you can still reject this evidence" type="button"
                            class="btn btn-xs btn-primary btn-round"
                            onclick="window.location.href='{{ route('students.training.evidences.assess', [$student, $training_record, $evidence]) }}'">
                            <i class="ace-icon fa fa-check bigger-110"></i> Assess Again &nbsp;
                        </button> &nbsp;
			@endcan
			@if(!is_null($training_record->secondaryAssessor) &&
                            in_array(auth()->user()->id, [$training_record->primaryAssessor->id, $training_record->secondaryAssessor->id, $verifier_id])
                            )
                        <button data-rel="tooltip"
                            title="View communication regarding this evidence between primary and seconday assessors" type="button"
                            class="btn btn-xs btn-default btn-round"
                            onclick="window.location.href='{{ route('students.training.evidences.assessors_communication', [$student, $training_record, $evidence]) }}'">
                            <i class="ace-icon fa fa-comment bigger-110"></i> Assessors Observations&nbsp;
                        </button> &nbsp;
                        @endif                        
                        @break
                    @default
                @endswitch
            @endif

            @if (\Auth::user()->getOriginal('user_type') == '4' /* && $training_record->getOriginal('status_code') == '2' */ && $evidence->getOriginal('evidence_status') == \App\Models\Training\TrainingRecordEvidence::STATUS_ASSESSOR_ACCEPTED)
            @can('iqa-assessment')
            <button data-rel="tooltip"
                title="Verify this evidence and save your comments" type="button"
                class="btn btn-xs btn-primary btn-round"
                onclick="window.location.href='{{ route('students.training.evidences.iqa', [$student, $training_record, $evidence]) }}'">
                <i class="ace-icon fa fa-check bigger-110"></i> IQA Check &nbsp;
            </button> &nbsp;
            @endcan
            @endif
        </div>
        <div class="widget-main">
            <div class="row">
                <div class="col-sm-7">
                    <div class="profile-user-info profile-user-info-striped">
                        <div class="profile-info-row">
                            <div class="profile-info-name"> Evidence Type </div>
                            <div class="profile-info-value"><span>{{ $evidence->evidence_type }}</span></div>
                        </div>
                        @if($evidence->getOriginal('evidence_type') == \App\Models\Training\TrainingRecordEvidence::TYPE_FILE)
                        <div class="profile-info-row">
                            <div class="profile-info-name"> File </div>
                            <div class="profile-info-value">
                                <span>
                                    @if ($evidence->media->first())
                                    <a href="{{ route('files.download',  $evidence->media->first()) }}" target="_blank" style="cursor: pointer;">
                                        <i class="fa fa-cloud-download"></i> {{ $evidence->media->first()->file_name }}
                                    </a>
                                    <br>
                                    <span class="small">{{ $evidence->media->first()->human_readable_size }}</span><br>
                                    <span class="small"><i class="fa fa-clock-o"></i> {{ \Carbon\Carbon::parse($evidence->media->first()->updated_at)->format('d/m/Y H:i:s') }}</span>
                                    @endif
                                </span>
                            </div>
                        </div>
                        @elseif($evidence->getOriginal('evidence_type') == \App\Models\Training\TrainingRecordEvidence::TYPE_URL)
                        <div class="profile-info-row">
                            <div class="profile-info-name"> URL </div>
                            <div class="profile-info-value">
                                <span><a href="{{ $evidence->evidence_url }}" target="_blank">
                                    <i data-trigger="hover" data-rel="popover" data-original-title="External URL"
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
                            <div class="profile-info-name"> Description </div>
                            <div class="profile-info-value"><span>{!! nl2br($evidence->evidence_desc) !!}</span></div>
                        </div>
                        <div class="profile-info-row">
                            <div class="profile-info-name"> Learner Comments </div>
                            <div class="profile-info-value">{!! nl2br($evidence->learner_comments) !!}<span></span></div>
                        </div>
                        <div class="profile-info-row">
                            <div class="profile-info-name"> Assessor Comments </div>
                            <div class="profile-info-value"><span>{!! nl2br($evidence->assessor_comments) !!}</span></div>
                        </div>
                        @if (!is_null($evidence->verifier_comments))
                        @can('view-iqa-feedback')
                        <div class="profile-info-row">
                            <div class="profile-info-name"> IQA/Verifier Comments </div>
                            <div class="profile-info-value"><span>{!! nl2br($evidence->verifier_comments) !!}</span></div>
                        </div>
                        @endcan
                        @endif
                        <div class="profile-info-row">
                            <div class="profile-info-name"> Dates & Created By </div>
                            <div class="profile-info-value">
                                <i class="blue">Created on: </i>{{ \Carbon\Carbon::parse($evidence->created_at)->format('d/m/Y') }}
                                <i class="blue">at: </i>{{ \Carbon\Carbon::parse($evidence->created_at)->format('H:i:s') }}
                                <i class="blue">by: </i>{{ $evidence->creator->full_name }} ({{ $evidence->creator->user_type }}) |
                                <i class="blue">Last updated on: </i>{{ \Carbon\Carbon::parse($evidence->updated_at)->format('d/m/Y') }}
                                <i class="blue">at: </i>{{ \Carbon\Carbon::parse($evidence->updated_at)->format('H:i:s') }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-5">
                    <h5 style="color: #336199;background-color: #EDF3F4;padding: 6px 10px 6px 4px;font-weight: 400;">Mapped PCs</h5>
                    <div style="max-height: 200px; overflow-y: scroll;" class="small">
                        @forelse ($evidence->mapped_pcs()->orderBy('pc_sequence')->get() as $pc)
                        <li>[{{ $pc->reference }}] {{ \Str::limit($pc->title, 150) }}</li>
                        @empty
                        <i>Not yet mapped to any pc (performance criteria)</i>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
