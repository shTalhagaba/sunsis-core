@extends('layouts.master')

@section('title', 'View OTLA Record')

@section('page-content')
    <div class="page-header">
        <h1>
            View OTLA Record
        </h1>
    </div><!-- /.page-header -->
    <div class="row">
        <div class="col-xs-12">
            <!-- PAGE CONTENT BEGINS -->
            <button class="btn btn-sm btn-white btn-default btn-round" type="button"
                onclick="window.location.href='{{ route('otla.index') }}'">
                <i class="ace-icon fa fa-times bigger-110"></i> Close
            </button>
            @if(!auth()->user()->isQualityManager())
                <button class="btn btn-sm btn-primary btn-round" type="button"
                    onclick="window.location.href='{{ route('otla.edit', $otla) }}'">
                    <i class="ace-icon fa fa-edit bigger-110"></i> Edit
                </button>
            @endif
            @if(auth()->user()->isAdmin() || auth()->user()->created_by == $otla->created_by)
            {!! Form::open([
                'method' => 'DELETE',
                'url' => route('otla.destroy', $otla),
                'id' => 'frmDeleteOtla',
                'style' => 'display: inline;',
                'class' => 'form-inline',
            ]) !!}
            {!! Form::hidden('otla_id_to_del', $otla->id) !!}
            {!! Form::button('<i class="ace-icon fa fa-trash-o bigger-110"></i> Delete', [
                'data-rel' => 'tooltip',
                'class' => 'btn btn-danger btn-xs btn-round',
                'type' => 'click',
                'id' => 'btnDeleteOtla',
            ]) !!}
            {!! Form::close() !!}
            @endif
            <div class="hr hr-12 hr-dotted"></div>


            @include('partials.session_message')

            @include('partials.session_error')

            <div class="row">
                <div class="col-xs-12">
                    <div class="widget-box transparent">
                        <div class="widget-header">
                            <div class="widget-title"></div>
                        </div>
                        <div class="widget-body">
                            <div class="widget-main table-responsive">
                                <table class="table table-bordered">
                                    <col width="50%">
                                    <col width="50%">
                                    <tr>
                                        <th class="bg-success text-right">Learning & Development Coach</th>
                                        <td>
                                            {{ $otla->coach->full_name }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="bg-success text-right">Standard/Course</th>
                                        <td>
                                            {{ $otla->programme->title }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="bg-success text-right">Stage of course/standard</th>
                                        <td>
                                            {{ $otla->stage_of_programme }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="bg-success text-right">Number of Attendees</th>
                                        <td>
                                            <div>
                                                <span class="text-info">Registered: </span>
                                                {{ $otla->reg_attendees }}
                                            </div>
                                            <div>
                                                <span class="text-info">Actual: </span>
                                                {{ $otla->actual_attendees }}
                                            </div>
                                            <div>
                                                <span class="text-info">Male: </span>
                                                {{ $otla->male_attendees }}
                                            </div>
                                            <div>
                                                <span class="text-info">Female: </span>
                                                {{ $otla->female_attendees }}
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="bg-success text-right">Observer/s</th>
                                        <td>
                                            <div>
                                                <span class="text-info">Observer 1: </span>
                                                {{ optional($otla->observer1)->full_name }}
                                            </div>
                                            <div>
                                                <span class="text-info">Observer 2: </span>
                                                {{ optional($otla->observer2)->full_name }}
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="bg-success text-right">Learner Type</th>
                                        <td>
                                            <div>
                                                <span class="text-info">16-18: </span>
                                                {{ $otla->lt_1618 }}
                                            </div>
                                            <div>
                                                <span class="text-info">19+: </span>
                                                {{ $otla->lt_19plus }}
                                            </div>
                                            <div>
                                                <span class="text-info">Apps: </span>
                                                {{ $otla->lt_apps }}
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="bg-success text-right">Session Type</th>
                                        <td>
                                            {{ is_array($otla->session_type) ? implode(', ', $otla->session_type) : $otla->session_type }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="bg-success text-right">Observation Time</th>
                                        <td>
                                            <div>
                                                <span class="text-info">Start: </span>
                                                {{ $otla->observation_start ? Carbon\Carbon::parse($otla->observation_start)->format('H:i') : '' }}
                                            </div>
                                            <div>
                                                <span class="text-info">End: </span>
                                                {{ $otla->observation_end ? Carbon\Carbon::parse($otla->observation_end)->format('H:i') : '' }}
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-12">
                    <div class="widget-box transparent">
                        <div class="widget-header">
                            <div class="widget-title"></div>
                        </div>
                        <div class="widget-body">
                            <div class="widget-main table-responsive">
                                <table class="table table-bordered">
                                    <tr>
                                        <th colspan="3" class="bg-success">Specific Areas for Development Identified from
                                            last
                                            observation </th>
                                    </tr>
                                    <tr>
                                        <th colspan="2"><i>Actions to be completed:</i></th>
                                        <th><i>By When?</i></th>
                                    </tr>
                                    @for ($i = 1; $i <= 5; $i++)
                                    @php
                                        $actionPrefix = 'action_' . $i;
                                        $actionDatePrefix = 'action_' . $i . '_tbc';
                                    @endphp
                                        <tr>
                                            <th>{{ $i }}. </th>
                                            <td style="width: 80%">
                                                {{ $formData[$actionPrefix] ?? null }}
                                            </td>
                                            <td>
                                                @if (isset($formData[$actionDatePrefix]))
                                                    {{ Carbon\Carbon::parse($formData[$actionDatePrefix])->format('d/m/Y') }}
                                                @endif
                                            </td>
                                        </tr>
                                    @endfor
                                    <tr>
                                        <th colspan="2" class="text-right">Overall grade of last observation:</th>
                                        <td>
                                            {{ $formData['overall_grade_last_obs'] ?? null }}
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-12">
                    <div class="widget-box transparent">
                        <div class="widget-header">
                            <div class="widget-title"></div>
                        </div>
                        <div class="widget-body">
                            <div class="widget-main table-responsive">
                                <table class="table table-bordered">
                                    <col width="50%">
                                    <col width="50%">
                                    <tr>
                                        <th colspan="2" class="bg-success text-center">Summary of Observation</th>
                                    </tr>
                                    <tr>
                                        <td>
                                            <ul>
                                                <li>Set scene. Group profile</li>
                                                <li>Timings.</li>
                                                <li>Engagement levels</li>
                                                <li>Summary of each stage of session observed. </li>
                                                <li>What was learnt? How do you know?</li>
                                            </ul>
                                        </td>
                                        <td>
                                            {!! isset($formData['summary_of_obs']) ? nl2br(e($formData['summary_of_obs'])) : '' !!}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="bg-success text-right">Grade: </th>
                                        <td>
                                            {{ $formData['summary_of_obs_grade'] ?? null }}
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-12">
                    <div class="widget-box transparent">
                        <div class="widget-header">
                            <div class="widget-title"></div>
                        </div>
                        <div class="widget-body">
                            <div class="widget-main">
                                <table class="table table-bordered">
                                    <col width="50%">
                                    <col width="50%">
                                    <tr>
                                        <th colspan="2" class="bg-success text-center">Planning and objectives</th>
                                    </tr>
                                    <tr>
                                        <td>
                                            <ul>

                                                <li>Has the session been clearly defined and planned to meet the needs of
                                                    (each)
                                                    learner/s? </li>
                                                <li>Has the intention of the session been clearly explained; outcomes,
                                                    targets and
                                                    expectations communicated? </li>
                                                <li>Have expectations of work been communicated? </li>
                                                <li>Are objectives measurable?</li>
                                                <li> Was lesson content sufficiently challenging?</li>
                                                <li>Session management, using (accurate/extensive) subject knowledge,
                                                    professionalism to engage and promote personal and professional
                                                    development.
                                                </li>
                                                <li>Planned for development of functional skills and wider curriculum within
                                                    session?</li>
                                            </ul>
                                        </td>
                                        <td>
                                            {!! isset($formData['planning_and_obj']) ? nl2br(e($formData['planning_and_obj'])) : '' !!}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="bg-success text-right">Grade: </th>
                                        <td>
                                            {{ $formData['planning_and_obj_grade'] ?? null }}
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-12">
                    <div class="widget-box transparent">
                        <div class="widget-header">
                            <div class="widget-title"></div>
                        </div>
                        <div class="widget-body">
                            <div class="widget-main ">
                                <table class="table table-bordered">
                                    <col width="50%">
                                    <col width="50%">
                                    <tr>
                                        <th colspan="2" class="bg-success text-center">Assessment</th>
                                    </tr>
                                    <tr>
                                        <td>
                                            <ul>
                                                <li>Measurement of progress and impact? How was this measured? Scale, tasks,
                                                    Q&A?
                                                </li>
                                                <li>How has the L&DC assessed the teaching and learning throughout the
                                                    session?
                                                </li>
                                                <li>How has new and pre-existing learning been demonstrated? </li>
                                                <li>How has learning been applied and confirmed? </li>
                                                <li>How is feedback given to the learner within the sessions? How is that
                                                    structured
                                                    and is it clear?</li>
                                                <li>Is feedback linked to KSBs, criteria and objectives?</li>
                                            </ul>
                                        </td>
                                        <td>
                                            {!! isset($formData['assessment']) ? nl2br(e($formData['assessment'])) : '' !!}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="bg-success text-right">Grade: </th>
                                        <td>
                                            {{ $formData['assessment_grade'] ?? null }}
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-12">
                    <div class="widget-box transparent">
                        <div class="widget-header">
                            <div class="widget-title"></div>
                        </div>
                        <div class="widget-body">
                            <div class="widget-main ">
                                <table class="table ">
                                    <col width="50%">
                                    <col width="50%">
                                    <tr>
                                        <th colspan="2" class="bg-success text-center">Embedding of Safeguarding,
                                            Prevent, EDI
                                        </th>
                                    </tr>
                                    <tr>
                                        <td>
                                            <ul>
                                                <li>How was Safeguarding, Prevent and EDI content planned for in the
                                                    session?</li>
                                                <li>Were natural opportunities seized in the session?</li>
                                                <li>Were safeguarding, Prevent and EDI areas explored appropriate, relevant
                                                    and
                                                    current,
                                                    specific to the learner, standard and role?</li>
                                                <li>Were any checks completed to ensure the learner was safe and knows how
                                                    to keep
                                                    safe?</li>
                                                <li>Were safeguarding and EDI matters considered in the planning and
                                                    delivery of the
                                                    lesson (i.e. online protocols, confidentiality)</li>
                                            </ul>
                                        </td>
                                        <td>
                                            {!! isset($formData['safeguarding']) ? nl2br(e($formData['safeguarding'])) : '' !!}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="bg-success text-right">Grade: </th>
                                        <td>
                                            {{ $formData['safeguarding_grade'] ?? null }}
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-12">
                    <div class="widget-box transparent">
                        <div class="widget-header">
                            <div class="widget-title"></div>
                        </div>
                        <div class="widget-body">
                            <div class="widget-main ">
                                <table class="table ">
                                    <col width="50%">
                                    <col width="50%">
                                    <tr>
                                        <th colspan="2" class="bg-success text-center">Use of ILT (Information &
                                            Learning
                                            Technology)</th>
                                    </tr>
                                    <tr>
                                        <td>
                                            <ul>
                                                <li>What forms of ILT were used during the sessions? </li>
                                                <li>How were tools used to support learning, progress or learner digital
                                                    skills?
                                                </li>
                                                <li>Were the tools appropriate to the learning or assessment activity?</li>
                                                <li>Was AI used in planning the lesson, tasks or by the learner in work
                                                    generation?
                                                    How was this addressed?</li>
                                            </ul>
                                        </td>
                                        <td>
                                            {!! isset($formData['ilt']) ? nl2br(e($formData['ilt'])) : '' !!}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="bg-success text-right">Grade: </th>
                                        <td>
                                            {{ $formData['ilt_grade'] ?? null }}
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-12">
                    <div class="widget-box transparent">
                        <div class="widget-header">
                            <div class="widget-title"></div>
                        </div>
                        <div class="widget-body">
                            <div class="widget-main ">
                                <table class="table ">
                                    <col width="50%">
                                    <col width="50%">
                                    <tr>
                                        <th colspan="2" class="bg-success text-center">Personal Development</th>
                                    </tr>
                                    <tr>
                                        <td>
                                            <ul>
                                                <li>How were softer skills developed? These may be areas such as team
                                                    leading,
                                                    communication, time management, dealing with conflict, self-awareness,
                                                    presentation skills.</li>
                                                <li>Were external activities, enrichment or personal projects discussed that
                                                    support
                                                    learner progression?</li>
                                            </ul>
                                        </td>
                                        <td>
                                            {!! isset($formData['pd']) ? nl2br(e($formData['pd'])) : '' !!}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="bg-success text-right">Grade: </th>
                                        <td>
                                            {{ $formData['pd_grade'] ?? null }}
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-12">
                    <div class="widget-box transparent">
                        <div class="widget-header">
                            <div class="widget-title"></div>
                        </div>
                        <div class="widget-body">
                            <div class="widget-main ">
                                <table class="table ">
                                    <col width="50%">
                                    <col width="50%">
                                    <tr>
                                        <th colspan="2" class="bg-success text-center">Adaptation - Stretch and
                                            Challenge,
                                            Adjustment for ALN, meets individual ILP</th>
                                    </tr>
                                    <tr>
                                        <td>
                                            <ul>
                                                <li>How have 'stretch and challenge' questions been used? </li>
                                                <li>Does the learner have any ALN identified and how were adjustments made
                                                    to
                                                    support individual support needs? </li>
                                                <li>Does the learning, content, assessment link to the learners ILP/ILPs?
                                                </li>
                                                <li>Did you see signs of any identified ALN? </li>
                                                <li>Functional Skills - based upon learner ILP (Where applicable)</li>
                                                <li>English and maths embedded into tasks, lessons, projects and activities?
                                                </li>
                                            </ul>
                                        </td>
                                        <td>
                                            {!! isset($formData['adaptation']) ? nl2br(e($formData['adaptation'])) : '' !!}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="bg-success text-right">Grade: </th>
                                        <td>
                                            {{ $formData['adaptation_grade'] ?? null }}
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-12">
                    <div class="widget-box transparent">
                        <div class="widget-header">
                            <div class="widget-title"></div>
                        </div>
                        <div class="widget-body">
                            <div class="widget-main ">
                                <table class="table ">
                                    <col width="50%">
                                    <col width="50%">
                                    <tr>
                                        <th colspan="2" class="bg-success text-center">Attitude to learning -
                                            behaviour,
                                            punctuality, engagement, tasks completed, respect</th>
                                    </tr>
                                    <tr>
                                        <td>
                                            <ul>
                                                <li>Were learners punctual?</li>
                                                <li>Were they engaged? How do you know this? </li>
                                                <li>What behaviours were displayed to show respectful lessons/sessions?</li>
                                                <li>Was there good rapport with the L&D Coach and learner/learners? How was
                                                    this
                                                    displayed?</li>

                                            </ul>
                                        </td>
                                        <td>
                                            {!! isset($formData['attitude_to_learning']) ? nl2br(e($formData['attitude_to_learning'])) : '' !!}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="bg-success text-right">Grade: </th>
                                        <td>
                                            {{ $formData['attitude_to_learning_grade_grade'] ?? null }}
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-12">
                    <div class="widget-box transparent">
                        <div class="widget-header">
                            <div class="widget-title"></div>
                        </div>
                        <div class="widget-body">
                            <div class="widget-main ">
                                <table class="table ">
                                    <col width="50%">
                                    <col width="50%">
                                    <tr>
                                        <th class="bg-success text-right">OTLA Overall Grade: </th>
                                        <td>
                                            {{ $formData['overall_grade'] ?? null }}
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-12">
                    <div class="widget-box transparent">
                        <div class="widget-header">
                            <div class="widget-title"></div>
                        </div>
                        <div class="widget-body">
                            <div class="widget-main table-responsive">
                                <table class="table table-bordered">
                                    <col width="10%">
                                    <col width="30%">
                                    <col width="30%">
                                    <col width="15%">
                                    <col width="15%">
                                    <tr class="bg-success">
                                        <th>Action</th>
                                        <th>Target to develop </th>
                                        <th>Planned outcome </th>
                                        <th>By When </th>
                                        <th>Date of Review</th>
                                    </tr>
                                    @for ($i = 1; $i <= 5; $i++)
                                    @php
                                        $taPrefix = 'target_action' . $i;
                                        $taPlannedOutcomePrefix = 'target_action_planned_outcome' . $i;
                                        $taTbcPrefix = 'target_action_tbc' . $i;
                                        $taReviewDatePrefix = 'target_action_review_date' . $i;
                                    @endphp
                                        <tr>
                                            <td>{{ $i }}. </td>
                                            <td>
                                                {{ $formData[$taPrefix] ?? null }}
                                            </td>
                                            <td>
                                                {{ $formData[$taPlannedOutcomePrefix] ?? null }}
                                            </td>
                                            <td>
                                                @if (isset($formData[$taTbcPrefix]))
                                                    {{ Carbon\Carbon::parse($formData[$taTbcPrefix])->format('d/m/Y') }}
                                                @endif
                                            </td>
                                            <td>
                                                @if (isset($formData[$taReviewDatePrefix]))
                                                    {{ Carbon\Carbon::parse($formData[$taReviewDatePrefix])->format('d/m/Y') }}
                                                @endif
                                            </td>
                                        </tr>
                                    @endfor
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <div class="col-sm-12">
                    <table class="table table-bordered">
                        @if(isset($otla) && $otla->isIqaSigned())
                        <tr>
                            <th class="bg-success text-right">IQA</th>
                            <td>
                                <span class="text-info">Signed: </span> 
                                {!! $otla->isIqaSigned() ? '<i class="fa fa-check-circle green fa-2x"></i>' : '<i class="fa fa-times-circle red fa-2x"></i>' !!}
                                <br>
                                <span class="text-info">Signed Date: </span>
                                {{ optional($otla->iqa_signed_date)->format('d/m/Y') ?? '' }}
                                <br>
                                <span class="text-info">Name: </span>
                                {{ optional($otla->creator)->full_name ?? '' }}                                
                            </td>
                        </tr>
                        @endif
                        @if(isset($otla) && $otla->isObserver2Signed())
                        <tr>
                            <th class="bg-success text-right">Observer 2</th>
                            <td>
                                <span class="text-info">Signed: </span> 
                                {!! $otla->isObserver2Signed() ? '<i class="fa fa-check-circle green fa-2x"></i>' : '<i class="fa fa-times-circle red fa-2x"></i>' !!}
                                <br>
                                <span class="text-info">Signed Date: </span>
                                {{ optional($otla->observer_2_signed_date)->format('d/m/Y') ?? '' }}
                                <br>
                                <span class="text-info">Name: </span>
                                {{ optional($otla->observer2)->full_name ?? '' }}                                
                            </td>
                        </tr>
                        @endif
                        @if(isset($otla) && $otla->isCoachSigned())
                        <tr>
                            <th class="bg-success text-right">Coach</th>
                            <td>
                                <span class="text-info">Signed: </span> 
                                {!! $otla->isCoachSigned() ? '<i class="fa fa-check-circle green fa-2x"></i>' : '<i class="fa fa-times-circle red fa-2x"></i>' !!}
                                <br>
                                <span class="text-info">Signed Date: </span>
                                {{ optional($otla->coach_signed_date)->format('d/m/Y') ?? '' }}
                                <br>
                                <span class="text-info">Name: </span>
                                {{ optional($otla->coach)->full_name ?? '' }}                                
                            </td>
                        </tr>
                        @endif
                    </table>        
                </div>
            </div>

            <!-- PAGE CONTENT ENDS -->
        </div><!-- /.col -->
    </div><!-- /.row -->
@endsection

@push('after-scripts')
    <script>
        $("button#btnDeleteOtla").on('click', function(e) {
            e.preventDefault();

            var form = $(this).closest('form');

            bootbox.confirm({
                title: 'Sure to Remove?',
                message: 'This action is irreversible, are you sure you want to continue?',
                buttons: {
                    cancel: {
                        span: '<i class="fa fa-times"></i> Cancel',
                        className: 'btn-xs btn-round'
                    },
                    confirm: {
                        span: '<i class="fa fa-check"></i> Yes Remove',
                        className: 'btn-danger btn-xs btn-round'
                    }
                },
                callback: function(result) {
                    if (result) {
                        form.submit();
                    }
                }
            });
        });
    </script>
@endpush
