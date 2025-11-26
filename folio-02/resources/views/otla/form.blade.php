@push('after-styles')
    <style>
        input[type=checkbox] {
            transform: scale(1.4);
        }
    </style>
@endpush

<div class="row">
    <div class="col-xs-12">
        <div class="widget-box transparent">
            <div class="widget-header">
                <div class="widget-title"><h5 class="bolder text-center">OTLA Record</h5></div>
            </div>
            <div class="widget-body">
                <div class="widget-main table-responsive">
                    <table class="table table-bordered">
                        <col width="50%">
                        <col width="50%">
                        <tr>
                            <th class="bg-success text-right">Learning & Development Coach</th>
                            <td>
                                {!! Form::select('ld_coach', $assessors, null, ['class' => 'form-control', 'placeholder' => '', 'required']) !!}
                                {!! $errors->first('ld_coach', '<p class="text-danger">:message</p>') !!}
                            </td>
                        </tr>
                        <tr>
                            <th class="bg-success text-right">Standard/Course</th>
                            <td>
                                {!! Form::select('programme_id', $programmes, null, ['class' => 'form-control', 'placeholder' => '', 'required']) !!}
                                {!! $errors->first('programme_id', '<p class="text-danger">:message</p>') !!}
                            </td>
                        </tr>
                        <tr>
                            <th class="bg-success text-right">Stage of course/standard</th>
                            <td>
                                {!! Form::text('stage_of_programme', null, ['class' => 'form-control']) !!}
                                {!! $errors->first('stage_of_programme', '<p class="text-danger">:message</p>') !!}
                            </td>
                        </tr>
                        <tr>
                            <th class="bg-success text-right">Number of Attendees</th>
                            <td>
                                <div>
                                    <label for="reg_attendees">Registered</label>
                                    {!! Form::number('reg_attendees', null, ['class' => 'form-control']) !!}
                                    {!! $errors->first('reg_attendees', '<p class="text-danger">:message</p>') !!}
                                </div>
                                <div>
                                    <label for="actual_attendees">Actual</label>
                                    {!! Form::number('actual_attendees', null, ['class' => 'form-control']) !!}
                                    {!! $errors->first('actual_attendees', '<p class="text-danger">:message</p>') !!}
                                </div>
                                <div>
                                    <label for="male_attendees">Male</label>
                                    {!! Form::number('male_attendees', null, ['class' => 'form-control']) !!}
                                    {!! $errors->first('male_attendees', '<p class="text-danger">:message</p>') !!}
                                </div>
                                <div>
                                    <label for="female_attendees">Female</label>
                                    {!! Form::number('female_attendees', null, ['class' => 'form-control']) !!}
                                    {!! $errors->first('female_attendees', '<p class="text-danger">:message</p>') !!}
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th class="bg-success text-right">Observer/s</th>
                            <td>
                                <div>
                                    <label for="observer_1">Observer 1</label>
                                    {!! Form::select('observer_1', $observers, $otla->observer_1 ?? auth()->user()->id, ['class' => 'form-control', 'disabled' ]) !!}
                                    {!! $errors->first('observer_1', '<p class="text-danger">:message</p>') !!}
                                </div>
                                <div>
                                    <label for="observer_2">Observer 2</label>
                                    {!! Form::select('observer_2', $observers, null, ['class' => 'form-control', 'placeholder' => '']) !!}
                                    {!! $errors->first('observer_2', '<p class="text-danger">:message</p>') !!}
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th class="bg-success text-right">Learner Type</th>
                            <td>
                                <div>
                                    <label for="">16-18</label>
                                    {!! Form::number('lt_1618', null, ['class' => 'form-control']) !!}
                                    {!! $errors->first('lt_1618', '<p class="text-danger">:message</p>') !!}
                                </div>
                                <div>
                                    <label for="">19+</label>
                                    {!! Form::number('lt_19plus', null, ['class' => 'form-control']) !!}
                                    {!! $errors->first('lt_19plus', '<p class="text-danger">:message</p>') !!}
                                </div>
                                <div>
                                    <label for="">Apps</label>
                                    {!! Form::number('lt_apps', null, ['class' => 'form-control']) !!}
                                    {!! $errors->first('lt_apps', '<p class="text-danger">:message</p>') !!}
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th class="bg-success text-right">Session Type</th>
                            <td>
                                <div class="control-group">
                                    <div class="checkbox">
                                        <label>
                                            <input name="session_type[]" class="ace ace-checkbox-2" type="checkbox" 
                                                {{ isset($sessionTypes) && in_array('Review', $sessionTypes) ? 'checked' : '' }}
                                                value="Review">
                                            <span class="lbl"> Review</span>
                                        </label>
                                    </div>
                                    <div class="checkbox">
                                        <label>
                                            <input name="session_type[]" class="ace ace-checkbox-2" type="checkbox"
                                                {{ isset($sessionTypes) && in_array('Lesson', $sessionTypes) ? 'checked' : '' }}
                                                value="Lesson">
                                            <span class="lbl"> Lesson</span>
                                        </label>
                                    </div>
                                    <div class="checkbox">
                                        <label>
                                            <input name="session_type[]" class="ace ace-checkbox-2" type="checkbox"
                                                {{ isset($sessionTypes) && in_array('Observation', $sessionTypes) ? 'checked' : '' }}
                                                value="Observation">
                                            <span class="lbl"> Observation</span>
                                        </label>
                                    </div>
                                    <div class="checkbox">
                                        <label>
                                            <input name="session_type[]" class="ace ace-checkbox-2" type="checkbox"
                                                {{ isset($sessionTypes) && in_array('PD', $sessionTypes) ? 'checked' : '' }}
                                                value="PD">
                                            <span class="lbl"> PD</span>
                                        </label>
                                    </div>
                                    <div class="checkbox">
                                        <label>
                                            <input name="session_type[]" class="ace ace-checkbox-2" type="checkbox"
                                                {{ isset($sessionTypes) && in_array('Other', $sessionTypes) ? 'checked' : '' }}
                                                value="Other">
                                            <span class="lbl"> Other</span>
                                        </label>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th class="bg-success text-right">Observation Time</th>
                            <td>
                                <div>
                                    <label for="observation_start">Start</label>
                                    {!! Form::time('observation_start', $formData['observation_start'] ?? null, ['class' => 'form-control']) !!}
                                    {!! $errors->first('observation_start', '<p class="text-danger">:message</p>') !!}
                                </div>
                                <div>
                                    <label for="observation_end">End</label>
                                    {!! Form::time('observation_end', $formData['observation_end'] ?? null, ['class' => 'form-control']) !!}
                                    {!! $errors->first('observation_end', '<p class="text-danger">:message</p>') !!}
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
                            <th colspan="3" class="bg-success">Specific Areas for Development Identified from last
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
                                    {!! Form::text('action_' . $i, $formData[$actionPrefix] ?? null, ['class' => 'form-control']) !!}
                                    {!! $errors->first('action_' . $i, '<p class="text-danger">:message</p>') !!}
                                </td>
                                <td>
                                    {!! Form::date('action_' . $i . '_tbc', $formData[$actionDatePrefix] ?? null, ['class' => 'form-control']) !!}
                                    {!! $errors->first('action_' . $i . '_tbc', '<p class="text-danger">:message</p>') !!}
                                </td>
                            </tr>
                        @endfor
                        <tr>
                            <th colspan="2" class="text-right">Overall grade of last observation:</th>
                            <td>
                                {!! Form::select('overall_grade_last_obs', $grades, $formData['overall_grade_last_obs'] ?? null, ['class' => 'form-control', 'placeholder' => '']) !!}
                                {!! $errors->first('overall_grade_last_obs', '<p class="text-danger">:message</p>') !!}
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
                                {!! Form::textarea('summary_of_obs', $formData['summary_of_obs'] ?? null, ['class' => 'form-control', 'rows' => 5]) !!}
                                {!! $errors->first('summary_of_obs', '<p class="text-danger">:message</p>') !!}
                            </td>
                        </tr>
                        <tr>
                            <th class="bg-success text-right">Grade: </th>
                            <td>
                                {!! Form::select('summary_of_obs_grade', $grades, $formData['summary_of_obs_grade'] ?? null, ['class' => 'form-control', 'placeholder' => '']) !!}
                                {!! $errors->first('summary_of_obs_grade', '<p class="text-danger">:message</p>') !!}
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

                                    <li>Has the session been clearly defined and planned to meet the needs of (each)
                                        learner/s? </li>
                                    <li>Has the intention of the session been clearly explained; outcomes, targets and
                                        expectations communicated? </li>
                                    <li>Have expectations of work been communicated? </li>
                                    <li>Are objectives measurable?</li>
                                    <li> Was lesson content sufficiently challenging?</li>
                                    <li>Session management, using (accurate/extensive) subject knowledge,
                                        professionalism to engage and promote personal and professional development.
                                    </li>
                                    <li>Planned for development of functional skills and wider curriculum within
                                        session?</li>
                                </ul>
                            </td>
                            <td>
                                {!! Form::textarea('planning_and_obj', $formData['planning_and_obj'] ?? null, ['class' => 'form-control', 'rows' => 5]) !!}
                                {!! $errors->first('planning_and_obj', '<p class="text-danger">:message</p>') !!}
                            </td>
                        </tr>
                        <tr>
                            <th class="bg-success text-right">Grade: </th>
                            <td>
                                {!! Form::select('planning_and_obj_grade', $grades, $formData['planning_and_obj_grade'] ?? null, ['class' => 'form-control', 'placeholder' => '']) !!}
                                {!! $errors->first('planning_and_obj_grade', '<p class="text-danger">:message</p>') !!}
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
                                    <li>Measurement of progress and impact? How was this measured? Scale, tasks, Q&A?
                                    </li>
                                    <li>How has the L&DC assessed the teaching and learning throughout the session?
                                    </li>
                                    <li>How has new and pre-existing learning been demonstrated? </li>
                                    <li>How has learning been applied and confirmed? </li>
                                    <li>How is feedback given to the learner within the sessions? How is that structured
                                        and is it clear?</li>
                                    <li>Is feedback linked to KSBs, criteria and objectives?</li>
                                </ul>
                            </td>
                            <td>
                                {!! Form::textarea('assessment', $formData['assessment'] ?? null, ['class' => 'form-control', 'rows' => 5]) !!}
                                {!! $errors->first('assessment', '<p class="text-danger">:message</p>') !!}
                            </td>
                        </tr>
                        <tr>
                            <th class="bg-success text-right">Grade: </th>
                            <td>
                                {!! Form::select('assessment_grade', $grades, $formData['assessment_grade'] ?? null, ['class' => 'form-control', 'placeholder' => '']) !!}
                                {!! $errors->first('assessment_grade', '<p class="text-danger">:message</p>') !!}
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
                            <th colspan="2" class="bg-success text-center">Embedding of Safeguarding, Prevent, EDI
                            </th>
                        </tr>
                        <tr>
                            <td>
                                <ul>
                                    <li>How was Safeguarding, Prevent and EDI content planned for in the session?</li>
                                    <li>Were natural opportunities seized in the session?</li>
                                    <li>Were safeguarding, Prevent and EDI areas explored appropriate, relevant and
                                        current,
                                        specific to the learner, standard and role?</li>
                                    <li>Were any checks completed to ensure the learner was safe and knows how to keep
                                        safe?</li>
                                    <li>Were safeguarding and EDI matters considered in the planning and delivery of the
                                        lesson (i.e. online protocols, confidentiality)</li>
                                </ul>
                            </td>
                            <td>
                                {!! Form::textarea('safeguarding', $formData['safeguarding'] ?? null, ['class' => 'form-control', 'rows' => 5]) !!}
                                {!! $errors->first('safeguarding', '<p class="text-danger">:message</p>') !!}
                            </td>
                        </tr>
                        <tr>
                            <th class="bg-success text-right">Grade: </th>
                            <td>
                                {!! Form::select('safeguarding_grade', $grades, $formData['safeguarding_grade'] ?? null, ['class' => 'form-control', 'placeholder' => '']) !!}
                                {!! $errors->first('safeguarding_grade', '<p class="text-danger">:message</p>') !!}
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
                            <th colspan="2" class="bg-success text-center">Use of ILT (Information & Learning
                                Technology)</th>
                        </tr>
                        <tr>
                            <td>
                                <ul>
                                    <li>What forms of ILT were used during the sessions? </li>
                                    <li>How were tools used to support learning, progress or learner digital skills?
                                    </li>
                                    <li>Were the tools appropriate to the learning or assessment activity?</li>
                                    <li>Was AI used in planning the lesson, tasks or by the learner in work generation?
                                        How was this addressed?</li>
                                </ul>
                            </td>
                            <td>
                                {!! Form::textarea('ilt', $formData['ilt'] ?? null, ['class' => 'form-control', 'rows' => 5]) !!}
                                {!! $errors->first('ilt', '<p class="text-danger">:message</p>') !!}
                            </td>
                        </tr>
                        <tr>
                            <th class="bg-success text-right">Grade: </th>
                            <td>
                                {!! Form::select('ilt_grade', $grades, $formData['ilt_grade'] ?? null, ['class' => 'form-control', 'placeholder' => '']) !!}
                                {!! $errors->first('ilt_grade', '<p class="text-danger">:message</p>') !!}
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
                                    <li>How were softer skills developed? These may be areas such as team leading,
                                        communication, time management, dealing with conflict, self-awareness,
                                        presentation skills.</li>
                                    <li>Were external activities, enrichment or personal projects discussed that support
                                        learner progression?</li>
                                </ul>
                            </td>
                            <td>
                                {!! Form::textarea('pd', $formData['pd'] ?? null, ['class' => 'form-control', 'rows' => 5]) !!}
                                {!! $errors->first('pd', '<p class="text-danger">:message</p>') !!}
                            </td>
                        </tr>
                        <tr>
                            <th class="bg-success text-right">Grade: </th>
                            <td>
                                {!! Form::select('pd_grade', $grades, $formData['pd_grade'] ?? null, ['class' => 'form-control', 'placeholder' => '']) !!}
                                {!! $errors->first('pd_grade', '<p class="text-danger">:message</p>') !!}
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
                            <th colspan="2" class="bg-success text-center">Adaptation - Stretch and Challenge,
                                Adjustment for ALN, meets individual ILP</th>
                        </tr>
                        <tr>
                            <td>
                                <ul>
                                    <li>How have 'stretch and challenge' questions been used? </li>
                                    <li>Does the learner have any ALN identified and how were adjustments made to
                                        support individual support needs? </li>
                                    <li>Does the learning, content, assessment link to the learners ILP/ILPs?</li>
                                    <li>Did you see signs of any identified ALN? </li>
                                    <li>Functional Skills - based upon learner ILP (Where applicable)</li>
                                    <li>English and maths embedded into tasks, lessons, projects and activities?</li>
                                </ul>
                            </td>
                            <td>
                                {!! Form::textarea('adaptation', $formData['adaptation'] ?? null, ['class' => 'form-control', 'rows' => 5]) !!}
                                {!! $errors->first('adaptation', '<p class="text-danger">:message</p>') !!}
                            </td>
                        </tr>
                        <tr>
                            <th class="bg-success text-right">Grade: </th>
                            <td>
                                {!! Form::select('adaptation_grade', $grades, $formData['adaptation_grade'] ?? null, ['class' => 'form-control', 'placeholder' => '']) !!}
                                {!! $errors->first('adaptation_grade', '<p class="text-danger">:message</p>') !!}
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
                            <th colspan="2" class="bg-success text-center">Attitude to learning - behaviour,
                                punctuality, engagement, tasks completed, respect</th>
                        </tr>
                        <tr>
                            <td>
                                <ul>
                                    <li>Were learners punctual?</li>
                                    <li>Were they engaged? How do you know this? </li>
                                    <li>What behaviours were displayed to show respectful lessons/sessions?</li>
                                    <li>Was there good rapport with the L&D Coach and learner/learners? How was this
                                        displayed?</li>

                                </ul>
                            </td>
                            <td>
                                {!! Form::textarea('attitude_to_learning', $formData['attitude_to_learning'] ?? null, ['class' => 'form-control', 'rows' => 5]) !!}
                                {!! $errors->first('attitude_to_learning', '<p class="text-danger">:message</p>') !!}
                            </td>
                        </tr>
                        <tr>
                            <th class="bg-success text-right">Grade: </th>
                            <td>
                                {!! Form::select('attitude_to_learning_grade', $grades, $formData['attitude_to_learning_grade'] ?? null, ['class' => 'form-control', 'placeholder' => '']) !!}
                                {!! $errors->first('attitude_to_learning_grade', '<p class="text-danger">:message</p>') !!}
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
                                {!! Form::select('overall_grade', $grades, $formData['overall_grade'] ?? null, ['class' => 'form-control', 'placeholder' => '']) !!}
                                {!! $errors->first('overall_grade', '<p class="text-danger">:message</p>') !!}
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
                                    {!! Form::textarea('target_action' . $i, $formData[$taPrefix] ?? null, ['class' => 'form-control', 'rows' => 2]) !!}
                                    {!! $errors->first('target_action' . $i, '<p class="text-danger">:message</p>') !!}
                                </td>
                                <td>
                                    {!! Form::textarea('target_action_planned_outcome' . $i, $formData[$taPlannedOutcomePrefix] ?? null, ['class' => 'form-control', 'rows' => 2]) !!}
                                    {!! $errors->first('target_action_planned_outcome' . $i, '<p class="text-danger">:message</p>') !!}
                                </td>
                                <td>
                                    {!! Form::date('target_action_tbc' . $i, $formData[$taTbcPrefix] ?? null, ['class' => 'form-control']) !!}
                                    {!! $errors->first('target_action_tbc' . $i, '<p class="text-danger">:message</p>') !!}
                                </td>
                                <td>
                                    {!! Form::date('target_action_review_date' . $i, $formData[$taReviewDatePrefix] ?? null, ['class' => 'form-control']) !!}
                                    {!! $errors->first('target_action_review_date' . $i, '<p class="text-danger">:message</p>') !!}
                                </td>
                            </tr>
                        @endfor
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@if(isset($otla) && $otla->created_by == auth()->user()->id && !$otla->isIqaSigned())
<div class="row">
    <div class="col-sm-12">
        <div class="control-group text-center">
            <div class="checkbox">
                <label>
                    <input name="iqa_signed"  type="checkbox" value="1" >
                    <span class="lbl bolder"> &nbsp; Tick this option to confirm your signature if the form is fully completed.</span>
                    <div class="space-2"></div>
                    <span class="text-info small" style="margin-left: 2%"> 
                        &nbsp; <i class="fa fa-info-circle"></i> 
                        After you tick this option and save then form will be locked for further changes.
                    </span>
                </label>
            </div>
        <br>
        {!! $errors->first('iqa_signed', '<p class="text-danger">:message</p>') !!}
    </div>
</div>
@endif

@if(isset($otla) && $otla->observer_2 == auth()->user()->id && !$otla->isObserver2Signed())
<div class="row">
    <div class="col-sm-12">
        <div class="control-group text-center">
            <div class="checkbox">
                <label>
                    <input name="observer_2_signed"  type="checkbox" value="1" >
                    <span class="lbl bolder"> &nbsp; Tick this option to confirm your signature if the form is fully completed.</span>
                    <div class="space-2"></div>
                    <span class="text-info small" style="margin-left: 2%"> 
                        &nbsp; <i class="fa fa-info-circle"></i> 
                        After you tick this option and save then form will be locked for further changes.
                    </span>
                </label>
            </div>
        <br>
        {!! $errors->first('observer_2_signed', '<p class="text-danger">:message</p>') !!}
    </div>
</div>
@endif

@if(isset($otla) && $otla->ld_coach == auth()->user()->id && !$otla->isCoachSigned())
<div class="row">
    <div class="col-sm-12">
        <div class="control-group text-center">
            <div class="checkbox">
                <label>
                    <input name="coach_signed"  type="checkbox" value="1" >
                    <span class="lbl bolder"> &nbsp; Tick this option to confirm your signature if the form is fully completed.</span>
                    <div class="space-2"></div>
                    <span class="text-info small" style="margin-left: 2%"> 
                        &nbsp; <i class="fa fa-info-circle"></i> 
                        After you tick this option and save then form will be locked for further changes.
                    </span>
                </label>
            </div>
        <br>
        {!! $errors->first('coach_signed', '<p class="text-danger">:message</p>') !!}
    </div>
</div>
@endif

@if(!isset($otla))
<div class="row">
    <div class="col-xs-12">
        <div class="form-actions center">
            <button type="submit" class="btn btn-sm btn-success btn-round" id="btnSaveOTLA">
                <i class="ace-icon fa fa-save"></i>
                Save OTLA Record
            </button>
        </div>
    </div>
</div>
@elseif(
    ( $otla->created_by == auth()->user()->id && !$otla->isIqaSigned() ) || 
    ( $otla->observer_2 == auth()->user()->id && !$otla->isObserver2Signed() ) || 
    ( $otla->ld_coach == auth()->user()->id && !$otla->isCoachSigned() )
)
<div class="row">
    <div class="col-xs-12">
        <div class="form-actions center">
            <button type="submit" class="btn btn-sm btn-success btn-round" id="btnSaveOTLA">
                <i class="ace-icon fa fa-save"></i>
                Save OTLA Record
            </button>
        </div>
    </div>
</div>
@endif