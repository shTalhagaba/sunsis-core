@extends('layouts.master')

@section('title', 'Training Records Evidences')

@section('page-content')
    <div class="page-header">
        <h1>Training Records Evidences</h1>
    </div><!-- /.page-header -->

    <div class="row">
        <div class="col-xs-12">

            @if (!auth()->user()->isStudent())
                <div class="widget-box transparent ui-sortable-handle collapsed">
                    <div class="widget-header widget-header-small">
                        <h5 class="widget-title smaller">Search Filters</h5>
                        <div class="widget-toolbar">
                            <a title="Export view to Excel" href="{{ route('trainings.evidences.export') }}" <i
                                class="ace-icon fa fa-file-excel-o bigger-125"></i>
                            </a> &nbsp;
                            <a href="#" data-action="collapse"><i
                                    class="ace-icon fa fa-chevron-down bigger-125"></i></a>
                        </div>
                    </div>
                    @include('partials.filter_crumbs')
                    <div class="widget-body">
                        <div class="widget-main">
                            <small> @include('trainings.evidences.filter')</small>
                        </div>
                    </div>
                </div>
            @endif

            <div class="center">
                @include('partials.pagination', ['collection' => $evidences])
            </div>
            
            
            <table class="table table-bordered">
                @forelse($evidences AS $evidence)
                    <tr>
                        <td style="padding: 1%; width: 20%;">
                            @if (auth()->user()->isStudent())
                                @switch($evidence->getOriginal('evidence_status'))
                                    @case(\App\Models\Training\TrainingRecordEvidence::STATUS_LEARNER_SUBMITTED)
                                        @if (!$evidence->learner_declaration)
                                            <button data-rel="tooltip" title="You need to validate this evidence." type="button"
                                                class="btn btn-xs btn-primary btn-round"
                                                onclick="window.location.href='{{ route('trainings.evidences.studentValidation', [$evidence->training_record, $evidence]) }}'">
                                                <i class="ace-icon fa fa-check bigger-120"></i> Validate
                                            </button>
                                        @else
                                            <button data-rel="tooltip"
                                                title="Map this evidence to the units and performance criteria" type="button"
                                                class="btn btn-xs btn-primary btn-round"
                                                onclick="window.location.href='{{ route('trainings.evidences.mapping', [$evidence->training_record, $evidence]) }}'">
                                                <i class="ace-icon fa fa-paperclip bigger-120"></i> Mapping
                                            </button>
                                        @endif
                                    @break

                                    @default
                                @endswitch
                            @endif

                            @if (auth()->user()->isStaff())
                                @switch($evidence->getOriginal('evidence_status'))
                                    @case(\App\Models\Training\TrainingRecordEvidence::STATUS_LEARNER_SUBMITTED)
                                        @can('assess-evidence')
                                            <button data-rel="tooltip" title="Check this evidence and save your comments" type="button"
                                                class="btn btn-xs btn-primary btn-round"
                                                onclick="window.location.href='{{ route('trainings.evidences.assess', [$evidence->training_record, $evidence]) }}'">
                                                <i class="ace-icon fa fa-check bigger-110"></i> Assess Evidence
                                            </button> &nbsp;
                                            <div class="space-6"></div>
                                        @endcan
                                    @break

                                    @case(\App\Models\Training\TrainingRecordEvidence::STATUS_ASSESSOR_ACCEPTED)
                                        @can('assess-evidence')
                                            <button data-rel="tooltip"
                                                title="This evidence is accepted but you can still reject this evidence" type="button"
                                                class="btn btn-xs btn-primary btn-round"
                                                onclick="window.location.href='{{ route('trainings.evidences.assess', [$evidence->training_record, $evidence]) }}'">
                                                <i class="ace-icon fa fa-check bigger-110"></i> Re-Assess Evidence
                                            </button>
                                            <div class="space-6"></div>
                                        @endcan
                                        @if (
                                            !is_null($evidence->training_record->secondaryAssessor) &&
                                                in_array(auth()->user()->id, [
                                                    $evidence->training_record->primaryAssessor->id, 
                                                    $evidence->training_record->secondary_assessor ?? 0, 
                                                    $evidence->training_record->verifier->id ?? 0,
                                                    ]))
                                            <button data-rel="tooltip"
                                                title="View communication regarding this evidence between primary and seconday assessors"
                                                type="button" class="btn btn-xs btn-default btn-round"
                                                onclick="window.location.href='{{ route('trainings.evidences.assessors_communication', [$evidence->training_record, $evidence]) }}'">
                                                <i class="ace-icon fa fa-comment bigger-110"></i> Assessors Observations&nbsp;
                                            </button> &nbsp;
                                        @endif
                                    @break

                                    @default
                                @endswitch
                            @endif

                            @if (
                                auth()->user()->isVerifier() &&
                                    $evidence->getOriginal('evidence_status') ==
                                        \App\Models\Training\TrainingRecordEvidence::STATUS_ASSESSOR_ACCEPTED)
                                @can('iqa-assessment')
                                    <button data-rel="tooltip" title="Verify this evidence and save your comments"
                                        type="button" class="btn btn-xs btn-primary btn-round"
                                        onclick="window.location.href='{{ route('trainings.evidences.iqa', [$evidence->training_record, $evidence]) }}'">
                                        <i class="ace-icon fa fa-check bigger-110"></i> IQA Check &nbsp;
                                    </button> &nbsp;
                                @endcan
                            @endif
                            
                            <div class="space-6"></div>
                            <button type="button"
                                class="btn btn-xs btn-info btn-round"
                                onclick="window.location.href='{{ route('trainings.show', [$evidence->training_record]) }}'">
                                View Training
                            </button>
                        </td>
                        <td style="padding: 1%">
                            <span class="blue">Created on: </span>{{ \Carbon\Carbon::parse($evidence->created_at)->format('d/m/Y') }}
                            <span class="blue">at: </span>{{ \Carbon\Carbon::parse($evidence->created_at)->format('H:i:s') }}
                            <span class="blue">by: </span>{{ $evidence->creator->full_name }} ({{ App\Models\Lookups\UserTypeLookup::getDescription($evidence->creator->user_type) }})
                            <span class="blue">Last updated on: </span>{{ \Carbon\Carbon::parse($evidence->updated_at)->format('d/m/Y') }}
                            <span class="blue">at: </span>{{ \Carbon\Carbon::parse($evidence->updated_at)->format('H:i:s') }}

                            <div class="profile-user-info profile-user-info-striped">
                                <div class="profile-info-row">
                                    <div class="profile-info-name"> Student Name </div>
                                    <div class="profile-info-value">
                                        <h5 class="bolder">{{ $evidence->training_record->student->full_name }}</h5>
                                    </div>
                                </div>
                                <div class="profile-info-row">
                                    <div class="profile-info-name"> Training Details </div>
                                    <div class="profile-info-value">
                                        <span class="blue">Status: </span>@include('trainings.partials.tr_status_description', [
                                            'training' => $evidence->training_record,
                                        ])
                                        <span class="blue">Programme:
                                        </span>{{ $evidence->training_record->programme->title }}
                                        <span class="blue">Start Date:
                                        </span>{{ $evidence->training_record->start_date->format('d/m/Y') }}
                                        <span class="blue">Planned End Date:
                                        </span>{{ $evidence->training_record->planned_end_date->format('d/m/Y') }}
                                        @if ($evidence->training_record->actual_end_date != '')
                                        <span class="blue">Actual End Date:
                                        </span>{{ optional($evidence->training_record->actual_end_date)->format('d/m/Y') }}
                                        @endif
                                    </div>
                                </div>
                                <div class="profile-info-row">
                                    <div class="profile-info-name"> Evidence Status </div>
                                    <div class="profile-info-value">
                                        <span>{{ $evidence->evidence_status }}</span>
                                        @can('view-iqa-feedback')
                                            @if ($evidence->iqa_status == 1)
                                                <span class="label label-md label-success arrowed-in arrowed-in-right">IQA
                                                    Accepted</span>
                                            @elseif (!is_null($evidence->iqa_status) && $evidence->iqa_status == 0)
                                                <span class="label label-md label-danger arrowed-in arrowed-in-right">IQA
                                                    Rejected</span>
                                            @endif
                                        @endcan
                                    </div>
                                </div>
                                <div class="profile-info-row">
                                    <div class="profile-info-name"> Evidence Name </div>
                                    <div class="profile-info-value"><span>{{ $evidence->evidence_name }}</span></div>
                                </div>
                                <div class="profile-info-row">
                                    <div class="profile-info-name"> Evidence Category </div>
                                    <div class="profile-info-value">
                                        <span>{{ $evidence->categories->pluck('description')->implode(', ') }}</span>
                                    </div>
                                </div>
                                <div class="profile-info-row">
                                    <div class="profile-info-name"> Evidence Description </div>
                                    <div class="profile-info-value"><span>{{ $evidence->evidence_desc }}</span></div>
                                </div>
                                <div class="profile-info-row">
                                    <div class="profile-info-name"> Learner Declaration </div>
                                    <div class="profile-info-value">
                                        <span>
                                            {!! $evidence->learner_declaration == 1
                                                ? '<i class="fa fa-check green fa-2x"></i>'
                                                : '<span class="red"><i class="fa fa-warning fa-lg"></i> <i>Awaiting learner validation</i></span>' !!}
                                        </span>
                                    </div>
                                </div>
                                <div class="profile-info-row">
                                    <div class="profile-info-name"> Learner Comments </div>
                                    <div class="profile-info-value"><span><small>{!! nl2br(e($evidence->learner_comments)) !!}</small></span>
                                    </div>
                                </div>
                                @if (isset($evidence->latestAssessment->id))
                                <div class="profile-info-row">
                                    <div class="profile-info-name"> Assessment </div>
                                    <div class="profile-info-value">
                                        <div class="profile-user-info profile-user-info-striped">
                                            <div class="profile-info-row">
                                                <div class="profile-info-name"> Assessment By </div>
                                                <div class="profile-info-value">
                                                    <span>
                                                        <i>By</i> {{ App\Models\LookupManager::nameOfUser($evidence->latestAssessment->created_by) }} 
                                                        <i>On</i> {{ $evidence->latestAssessment->created_at->format('d/m/Y') }} <i>at</i> {{ $evidence->latestAssessment->created_at->format('H:i') }}
                                                    </span>
                                                </div>
                                            </div>
                                            <div class="profile-info-row">
                                                <div class="profile-info-name"> Assessment Status </div>
                                                <div class="profile-info-value">
                                                    <span>{{ $evidence->latestAssessment->statusDescription() }}</span></div>
                                            </div>
                                            <div class="profile-info-row">
                                                <div class="profile-info-name"> Assessment Comments </div>
                                                <div class="profile-info-value">
                                                    <span>{!! nl2br(e($evidence->latestAssessment->assessment_comments)) !!}</span><br>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @elseif(trim($evidence->assessor_comments) != '')
                                <div class="profile-info-row">
                                    <div class="profile-info-name"> Assessment </div>
                                    <div class="profile-info-value">
                                        <span>{!! nl2br(e($evidence->assessor_comments)) !!}</span><br>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                        <tr>
                            <td>
                                <h4 class="alert alert-info">No training evidences found.</h4>
                            </td>
                        </tr>
                @endforelse
            </table>


            <div class="center">
                @include('partials.pagination', ['collection' => $evidences])
            </div>

            </div><!-- /.col -->
        </div><!-- /.row -->
    @endsection
