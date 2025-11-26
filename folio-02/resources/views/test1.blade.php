@extends('layouts.master')

@section('page-inline-styles')
    <style>
        .bg-gold {
            background-color: #FFFACD
        }
    </style>
@endsection

@section('title', 'View OTJH entry')

@php
    $questions = DB::table('lookup_questions_four_week_audit_form')
        ->where('version', 'V2 March 25')
        ->orderBy('order_pos')
        ->get();

@endphp

@section('page-content')
    <div class="row">
        <div class="col-sm-12">
            <div class="widget-box transparent">
                <div class="widget-header">
                    <div class="widget-title"></div>
                </div>
                <div class="widget-body">
                    <div class="widget-main table-responsive">
                        <table class="table table-bordered">
                            <tr>
                                <th class="bg-success text-right">Learning & Development Coach</th>
                                <td>
                                    {!! Form::select('type', [], null, ['class' => 'form-control', 'placeholder' => '']) !!}
                                    {!! $errors->first('type', '<p class="text-danger">:message</p>') !!}
                                </td>
                            </tr>
                            <tr>
                                <th class="bg-success text-right">Standard/Course</th>
                                <td>
                                    {!! Form::select('type', [], null, ['class' => 'form-control', 'placeholder' => '']) !!}
                                    {!! $errors->first('type', '<p class="text-danger">:message</p>') !!}
                                </td>
                            </tr>
                            <tr>
                                <th class="bg-success text-right">Stage of course/standard</th>
                                <td>
                                    {!! Form::text('type', null, ['class' => 'form-control']) !!}
                                    {!! $errors->first('type', '<p class="text-danger">:message</p>') !!}
                                </td>
                            </tr>
                            <tr>
                                <th class="bg-success text-right">Number of Attendees</th>
                                <td>
                                    <div>
                                        <label for="form-field-8">Registered</label>
                                        {!! Form::number('type', null, ['class' => 'form-control']) !!}
                                        {!! $errors->first('type', '<p class="text-danger">:message</p>') !!}
                                    </div>
                                    <div>
                                        <label for="form-field-8">Actual</label>
                                        {!! Form::number('type', null, ['class' => 'form-control']) !!}
                                        {!! $errors->first('type', '<p class="text-danger">:message</p>') !!}
                                    </div>
                                    <div>
                                        <label for="form-field-8">Male</label>
                                        {!! Form::number('type', null, ['class' => 'form-control']) !!}
                                        {!! $errors->first('type', '<p class="text-danger">:message</p>') !!}
                                    </div>
                                    <div>
                                        <label for="form-field-8">Female</label>
                                        {!! Form::number('type', null, ['class' => 'form-control']) !!}
                                        {!! $errors->first('type', '<p class="text-danger">:message</p>') !!}
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th class="bg-success text-right">Observer/s</th>
                                <td>
                                    {!! Form::textarea('type', null, ['class' => 'form-control', 'rows' => 3]) !!}
                                    {!! $errors->first('type', '<p class="text-danger">:message</p>') !!}
                                </td>
                            </tr>
                            <tr>
                                <th class="bg-success text-right">Learner Type</th>
                                <td>
                                    <div>
                                        <label for="form-field-8">16-18</label>
                                        {!! Form::number('type', null, ['class' => 'form-control']) !!}
                                        {!! $errors->first('type', '<p class="text-danger">:message</p>') !!}
                                    </div>
                                    <div>
                                        <label for="form-field-8">19+</label>
                                        {!! Form::number('type', null, ['class' => 'form-control']) !!}
                                        {!! $errors->first('type', '<p class="text-danger">:message</p>') !!}
                                    </div>
                                    <div>
                                        <label for="form-field-8">Apps</label>
                                        {!! Form::number('type', null, ['class' => 'form-control']) !!}
                                        {!! $errors->first('type', '<p class="text-danger">:message</p>') !!}
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <th class="bg-success text-right">Session Type</th>
                                <td>
                                    <div class="control-group">
                                        <div class="checkbox">
                                            <label>
                                                <input name="evidence_categories[]" class="ace ace-checkbox-2"
                                                    type="checkbox" value="Review">
                                                <span class="lbl"> Review</span>
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label>
                                                <input name="evidence_categories[]" class="ace ace-checkbox-2"
                                                    type="checkbox" value="Review">
                                                <span class="lbl"> Lesson</span>
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label>
                                                <input name="evidence_categories[]" class="ace ace-checkbox-2"
                                                    type="checkbox" value="Review">
                                                <span class="lbl"> Observation</span>
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label>
                                                <input name="evidence_categories[]" class="ace ace-checkbox-2"
                                                    type="checkbox" value="Review">
                                                <span class="lbl"> PD</span>
                                            </label>
                                        </div>
                                        <div class="checkbox">
                                            <label>
                                                <input name="evidence_categories[]" class="ace ace-checkbox-2"
                                                    type="checkbox" value="Review">
                                                <span class="lbl"> Other</span>
                                            </label>
                                        </div>
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
        <div class="col-sm-12">
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
                                <tr>
                                    <th>{{ $i }}. </th>
                                    <td style="width: 80%">
                                        {!! Form::text('type', null, ['class' => 'form-control']) !!}
                                        {!! $errors->first('type', '<p class="text-danger">:message</p>') !!}
                                    </td>
                                    <td>
                                        {!! Form::date('type', null, ['class' => 'form-control']) !!}
                                        {!! $errors->first('type', '<p class="text-danger">:message</p>') !!}
                                    </td>
                                </tr>
                            @endfor
                            <tr>
                                <th colspan="2" class="text-right">Overall grade of last observation:</th>
                                <td>
                                    {!! Form::text('type', null, ['class' => 'form-control']) !!}
                                    {!! $errors->first('type', '<p class="text-danger">:message</p>') !!}
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
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
                                    {!! Form::textarea('type', null, ['class' => 'form-control', 'rows' => 5]) !!}
                                    {!! $errors->first('type', '<p class="text-danger">:message</p>') !!}
                                </td>
                            </tr>
                            <tr>
                                <th class="bg-success text-right">Grade: </th>
                                <td>
                                    {!! Form::select('type', [], null, ['class' => 'form-control', 'placeholder' => '']) !!}
                                    {!! $errors->first('type', '<p class="text-danger">:message</p>') !!}
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
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
                                    {!! Form::textarea('type', null, ['class' => 'form-control', 'rows' => 5]) !!}
                                    {!! $errors->first('type', '<p class="text-danger">:message</p>') !!}
                                </td>
                            </tr>
                            <tr>
                                <th class="bg-success text-right">Grade: </th>
                                <td>
                                    {!! Form::select('type', [], null, ['class' => 'form-control', 'placeholder' => '']) !!}
                                    {!! $errors->first('type', '<p class="text-danger">:message</p>') !!}
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
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
                                    {!! Form::textarea('type', null, ['class' => 'form-control', 'rows' => 5]) !!}
                                    {!! $errors->first('type', '<p class="text-danger">:message</p>') !!}
                                </td>
                            </tr>
                            <tr>
                                <th class="bg-success text-right">Grade: </th>
                                <td>
                                    {!! Form::select('type', [], null, ['class' => 'form-control', 'placeholder' => '']) !!}
                                    {!! $errors->first('type', '<p class="text-danger">:message</p>') !!}
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
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
                                    {!! Form::textarea('type', null, ['class' => 'form-control', 'rows' => 5]) !!}
                                    {!! $errors->first('type', '<p class="text-danger">:message</p>') !!}
                                </td>
                            </tr>
                            <tr>
                                <th class="bg-success text-right">Grade: </th>
                                <td>
                                    {!! Form::select('type', [], null, ['class' => 'form-control', 'placeholder' => '']) !!}
                                    {!! $errors->first('type', '<p class="text-danger">:message</p>') !!}
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
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
                                    {!! Form::textarea('type', null, ['class' => 'form-control', 'rows' => 5]) !!}
                                    {!! $errors->first('type', '<p class="text-danger">:message</p>') !!}
                                </td>
                            </tr>
                            <tr>
                                <th class="bg-success text-right">Grade: </th>
                                <td>
                                    {!! Form::select('type', [], null, ['class' => 'form-control', 'placeholder' => '']) !!}
                                    {!! $errors->first('type', '<p class="text-danger">:message</p>') !!}
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
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
                                    {!! Form::textarea('type', null, ['class' => 'form-control', 'rows' => 5]) !!}
                                    {!! $errors->first('type', '<p class="text-danger">:message</p>') !!}
                                </td>
                            </tr>
                            <tr>
                                <th class="bg-success text-right">Grade: </th>
                                <td>
                                    {!! Form::select('type', [], null, ['class' => 'form-control', 'placeholder' => '']) !!}
                                    {!! $errors->first('type', '<p class="text-danger">:message</p>') !!}
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
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
                                    {!! Form::textarea('type', null, ['class' => 'form-control', 'rows' => 5]) !!}
                                    {!! $errors->first('type', '<p class="text-danger">:message</p>') !!}
                                </td>
                            </tr>
                            <tr>
                                <th class="bg-success text-right">Grade: </th>
                                <td>
                                    {!! Form::select('type', [], null, ['class' => 'form-control', 'placeholder' => '']) !!}
                                    {!! $errors->first('type', '<p class="text-danger">:message</p>') !!}
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
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
                                    {!! Form::textarea('type', null, ['class' => 'form-control', 'rows' => 5]) !!}
                                    {!! $errors->first('type', '<p class="text-danger">:message</p>') !!}
                                </td>
                            </tr>
                            <tr>
                                <th class="bg-success text-right">Grade: </th>
                                <td>
                                    {!! Form::select('type', [], null, ['class' => 'form-control', 'placeholder' => '']) !!}
                                    {!! $errors->first('type', '<p class="text-danger">:message</p>') !!}
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
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
                                <th class="bg-success text-right">OTLA Overall Grade: </th>
                                <td>
                                    {!! Form::select('type', [], null, ['class' => 'form-control', 'placeholder' => '']) !!}
                                    {!! $errors->first('type', '<p class="text-danger">:message</p>') !!}
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
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
                                <tr>
                                    <td>{{ $i }}. </td>
                                    <td>
                                        {!! Form::textarea('type', null, ['class' => 'form-control', 'rows' => 2]) !!}
                                        {!! $errors->first('type', '<p class="text-danger">:message</p>') !!}
                                    </td>
                                    <td>
                                        {!! Form::textarea('type', null, ['class' => 'form-control', 'rows' => 2]) !!}
                                        {!! $errors->first('type', '<p class="text-danger">:message</p>') !!}
                                    </td>
                                    <td>
                                        {!! Form::date('type', null, ['class' => 'form-control']) !!}
                                        {!! $errors->first('type', '<p class="text-danger">:message</p>') !!}
                                    </td>
                                    <td>
                                        {!! Form::date('type', null, ['class' => 'form-control']) !!}
                                        {!! $errors->first('type', '<p class="text-danger">:message</p>') !!}
                                    </td>
                                </tr>
                            @endfor
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
