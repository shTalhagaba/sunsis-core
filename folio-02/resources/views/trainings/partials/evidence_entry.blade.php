@php
    $evidence_status = '';
    $widget_color = '';
    $iqa_status = '';
    if ($evidence->isLearnerSubmitted()) {
        if (count($evidence->mapped_pcs) == 0) {
            $evidence_status = '<span class="label label-md label-info arrowed-in arrowed-in-right">Learner Submitted/Created</span>';
            $widget_color = '';
        } else {
            $evidence_status = '<span class="label label-md label-info arrowed-in arrowed-in-right">Learner Submitted/Created</span>';
            $widget_color = 'widget-color-orange';
        }
    } elseif ($evidence->isAssessorAccepted()) {
        $evidence_status = '<span class="label label-md label-info arrowed-in arrowed-in-right ">Assessor Accepted</span>';
        $widget_color = 'widget-color-blue2';
    } elseif (
        $evidence->getOriginal('evidence_status') ==
        \App\Models\Training\TrainingRecordEvidence::STATUS_ASSESSOR_REJECTED
    ) {
        $evidence_status =
            '<span class="label label-md label-info arrowed-in arrowed-in-right">Assessor Rejected</span>';
        $widget_color = 'widget-color-red2';
    } elseif (
        $evidence->getOriginal('evidence_status') ==
        \App\Models\Training\TrainingRecordEvidence::STATUS_LEARNER_RESUBMITTED
    ) {
        $evidence_status =
            '<span class="label label-md label-info arrowed-in arrowed-in-right">Learner Resubmitted</span>';
        $widget_color = 'widget-color-orange';
    }
    if ($evidence->isIqaAccpeted()) {
        $iqa_status = '<span class="label label-md label-success arrowed-in arrowed-in-right">IQA Accepted</span>';
    } elseif ($evidence->iqa_status == App\Models\Training\PortfolioUnitIqa::STATUS_IQA_REFERRED) {
        $iqa_status = '<span class="label label-md label-danger arrowed-in arrowed-in-right">IQA Referred</span>';
    }
    $verifier_id = !is_null($training->verifierUser) ? $training->verifierUser->id : 0;
    $listEvidenceCategories = App\Models\Lookups\TrainingEvidenceCategoryLookup::getSelectData();
    $evidenceCategpriesDescription = collect($listEvidenceCategories)->only($evidence->categories->pluck('id')->toArray())->values()->implode(', ');
@endphp
<div class="widget-box {{ $widget_color }} collapsed widgetEvidences" style="border-radius:2px">
    <div class="widget-header widget-header-flat">
        <h5 class="widget-title bolder">
            {!! $evidence->getIcon() !!} {{ $evidence->evidence_name }}
        </h5>
        <div class="widget-toolbar">
            @if ($iqa_status != '')
                @can('view-iqa-feedback')
                    {!! $iqa_status !!}
                @endcan
            @endif
            <span class="">{!! $evidence_status !!}</span> | 
            <a class="btn btn-xs btn-round btn-default" title="Click to view detail"
                href="{{ route('trainings.evidences.show', [$training, $evidence]) }}"
                target="_blank">
                View Detail
            </a> |  
            <a data-action="collapse" href="#"><i class="ace-icon fa fa-chevron-down"></i></a>
        </div>
    </div>
    <div class="widget-body">
        <div class="widget-toolbox padding-8 clearfix">
            @if (\Auth::user()->isStudent() && $training->isEditableByStudent())
		@if (! $evidence->learner_declaration)
                <button data-rel="tooltip" title="You need to validate this evidence."
                    type="button" class="btn btn-xs btn-primary btn-round"
                    onclick="window.location.href='{{ route('trainings.evidences.studentValidation', [$training, $evidence]) }}'">
                    <i class="ace-icon fa fa-check bigger-120"></i> Validate
                </button>
                @endif
                @switch($evidence->getOriginal('evidence_status'))
                    @case(\App\Models\Training\TrainingRecordEvidence::STATUS_LEARNER_SUBMITTED)
                        @if ($evidence->learner_declaration)
                        <button data-rel="tooltip" title="Map this evidence to the units and performance criteria"
                            type="button" class="btn btn-xs btn-primary btn-round"
                            onclick="window.location.href='{{ route('trainings.evidences.mapping', [$training, $evidence]) }}'">
                            <i class="ace-icon fa fa-paperclip bigger-120"></i> Mapping
                        </button>
                        @endif
                        {!! Form::open([
                            'method' => 'DELETE',
                            'url' => route('trainings.evidences.destroy', [$training, $evidence]),
                            'id' => 'frmDeleteEvidence' . $evidence->id,
                            'style' => 'display: inline;',
                            'class' => 'form-inline',
                        ]) !!}
                        {!! Form::hidden('evidence_id_to_del', $evidence->id) !!}
                        {!! Form::button('<i class="ace-icon fa fa-trash-o"></i> Delete', [
                            'data-rel' => 'tooltip',
                            'title' => 'Delete this evidence',
                            'class' => 'btn btn-danger btn-xs btn-round btnDelEvi',
                            'type' => 'submit',
                        ]) !!}
                        {!! Form::close() !!}
                    @break

                    @default
                @endswitch
            @endif

            @if (!\Auth::user()->isStudent())
                @switch($evidence->getOriginal('evidence_status'))
                    @case(\App\Models\Training\TrainingRecordEvidence::STATUS_LEARNER_SUBMITTED)
                        @can('assess-evidence')
                            <button data-rel="tooltip" title="Check this evidence and save your comments" type="button"
                                class="btn btn-xs btn-primary btn-round"
                                onclick="window.location.href='{{ route('trainings.evidences.assess', [$training, $evidence]) }}'">
                                <i class="ace-icon fa fa-check bigger-110"></i> Assess &nbsp;
                            </button> &nbsp;
                        @endcan
                        @can('delete-evidence')
                            {!! Form::open([
                                'method' => 'DELETE',
                                'url' => route('trainings.evidences.destroy', [$training, $evidence]),
                                'id' => 'frmDeleteEvidence' . $evidence->id,
                                'style' => 'display: inline;',
                                'class' => 'form-inline',
                            ]) !!}
                            {!! Form::hidden('evidence_id_to_del', $evidence->id) !!}
                            {!! Form::button('<i class="ace-icon fa fa-trash-o"></i> Delete', [
                                'data-rel' => 'tooltip',
                                'title' => 'Delete this evidence',
                                'class' => 'btn btn-danger btn-xs btn-round btnDelEvi',
                                'type' => 'submit',
                            ]) !!}
                            {!! Form::close() !!}
                        @endcan
                    @break

                    @case(\App\Models\Training\TrainingRecordEvidence::STATUS_ASSESSOR_ACCEPTED)
                        @can('assess-evidence')
                            <button data-rel="tooltip" title="This evidence is accepted but you can still reject this evidence"
                                type="button" class="btn btn-xs btn-primary btn-round"
                                onclick="window.location.href='{{ route('trainings.evidences.assess', [$training, $evidence]) }}'">
                                <i class="ace-icon fa fa-check bigger-110"></i> Assess Again &nbsp;
                            </button> &nbsp;
                        @endcan
                        @if (
                            !is_null($training->secondaryAssessor) &&
                                in_array(auth()->user()->id, [
                                    $training->primaryAssessor->id,
                                    $training->secondaryAssessor->id,
                                    $verifier_id,
                                ]))
                            <button data-rel="tooltip"
                                title="View communication regarding this evidence between primary and seconday assessors"
                                type="button" class="btn btn-xs btn-default btn-round"
                                onclick="window.location.href='{{ route('trainings.evidences.assessors_communication', [$training, $evidence]) }}'">
                                <i class="ace-icon fa fa-comment bigger-110"></i> Assessors Observations
                            </button> &nbsp;
                        @endif
                    @break

		    @case(\App\Models\Training\TrainingRecordEvidence::STATUS_ASSESSOR_REJECTED)
                        @can('delete-evidence')
                            {!! Form::open([
                                'method' => 'DELETE',
                                'url' => route('trainings.evidences.destroy', [$training, $evidence]),
                                'id' => 'frmDeleteEvidence' . $evidence->id,
                                'style' => 'display: inline;',
                                'class' => 'form-inline',
                            ]) !!}
                            {!! Form::hidden('evidence_id_to_del', $evidence->id) !!}
                            {!! Form::button('<i class="ace-icon fa fa-trash-o"></i> Delete', [
                                'data-rel' => 'tooltip',
                                'title' => 'Delete this evidence',
                                'class' => 'btn btn-danger btn-xs btn-round btnDelEvi',
                                'type' => 'submit',
                            ]) !!}
                            {!! Form::close() !!}
                        @endcan
                    @break

                    @default
                @endswitch
            @endif

            @if (auth()->user()->isVerifier() && auth()->user()->can('iqa-assessment') && $evidence->isAssessorAccepted())
                <button data-rel="tooltip" title="Verify this evidence and save your comments" type="button"
                    class="btn btn-xs btn-primary btn-round"
                    onclick="window.location.href='{{ route('trainings.evidences.iqa', [$training, $evidence]) }}'">
                    <i class="ace-icon fa fa-check bigger-110"></i> IQA Check &nbsp;
                </button> &nbsp;
            @endif
        </div>
        <div class="widget-main">
            <div class="row">
                <div class="col-sm-7">
                    <div class="profile-user-info profile-user-info-striped">
                        <div class="profile-info-row">
                            <div class="profile-info-name"> Evidence Category </div>
                            <div class="profile-info-value"><span>{{ $evidenceCategpriesDescription }}</span></div>
                        </div>
                        @if ($evidence->getOriginal('evidence_type') == \App\Models\Training\TrainingRecordEvidence::TYPE_FILE)
                            <div class="profile-info-row">
                                <div class="profile-info-name"> {{ \Str::plural('File', $evidence->media->count()) }}
                                </div>
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
                                    <span><a href="{{ $evidence->evidence_url }}" target="_blank">
                                            <i data-trigger="hover" data-rel="popover"
                                                data-original-title="External URL"
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
                            <div class="profile-info-name"> Description </div>
                            <div class="profile-info-value"><span>{!! nl2br($evidence->evidence_desc) !!}</span></div>
                        </div>
                        <div class="profile-info-row">
                            <div class="profile-info-name"> Learner Comments </div>
                            <div class="profile-info-value">{!! nl2br($evidence->learner_comments) !!}<span></span></div>
                        </div>
                        <div class="profile-info-row">
                            <div class="profile-info-name"> Learner Declaration </div>
                            <div class="profile-info-value">
                                <span>
                                    {!! $evidence->learner_declaration == 1 ? 
                                        '<i class="fa fa-check green fa-2x"></i>' : 
                                        '<span class="red"><i class="fa fa-warning fa-lg"></i> <i>Awaiting learner validation</i></span>' 
                                    !!}
                                </span>
                            </div>
                        </div>
                        <div class="profile-info-row">
                            <div class="profile-info-name"> Dates & Created By </div>
                            <div class="profile-info-value">
                                <i class="blue">Created on:
                                </i>{{ \Carbon\Carbon::parse($evidence->created_at)->format('d/m/Y') }}
                                <i class="blue">at:
                                </i>{{ \Carbon\Carbon::parse($evidence->created_at)->format('H:i:s') }}
                                <i class="blue">by: </i>{{ $evidence->creator->full_name }}
                                ({{ App\Models\Lookups\UserTypeLookup::getDescription($evidence->creator->user_type) }}) |
                                <i class="blue">Last updated on:
                                </i>{{ \Carbon\Carbon::parse($evidence->updated_at)->format('d/m/Y') }}
                                <i class="blue">at:
                                </i>{{ \Carbon\Carbon::parse($evidence->updated_at)->format('H:i:s') }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-5">
                    <div style="border:1px solid #CCC;padding:1px;">
                        <h5 style="color: #336199;background-color: #EDF3F4;padding: 6px 10px 6px 4px;font-weight: 400;">
                            Mapped Performance Criteria <span class="badge badge-info">{{ count($evidence->mapped_pcs) }}</span> </h5>
                        <div style="max-height: 200px; overflow-y: scroll;" class="small">
                            @forelse ($evidence->mapped_pcs as $pc)
                                [{{ $pc->reference }}] {!! nl2br(\Str::limit($pc->title, 150)) !!} 
                                @if($pc->assessor_signoff)
                                <i class="fa fa-check-circle green fa-2x" title="PC has been signed off"></i>
                                @endif
                                <hr style="margin-top: 10px; margin-bottom: 10px">
                            @empty
                                <i>Not yet mapped to any criteria</i>
                            @endforelse
                        </div>
                    </div>                    
                </div>
            </div>
        </div>
    </div>
</div>
