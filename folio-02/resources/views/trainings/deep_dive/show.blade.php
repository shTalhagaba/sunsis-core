@extends('layouts.master')

@section('title', 'Deep Dive')

@section('page-inline-styles')
    <style>
        th {
            text-align: center;
            vertical-align: middle;
            background-color: lightgreen;
        }
        td {
            color: blue;
        }
        .text-bold {
            font-weight: bold;
        }
        .text-blue {
            color: blue;
        }
    </style>
@endsection

@section('page-content')
    <div class="page-header">
        <h1>
            View Deep Dive Form Details
        </h1>
    </div><!-- /.page-header -->
    <div class="row">
        <div class="col-xs-12">
            <!-- PAGE CONTENT BEGINS -->
            <button class="btn btn-sm btn-white btn-default btn-round" type="button"
                onclick="window.location.href='{{ route('trainings.deep_dives.index', $training) }}'">
                <i class="ace-icon fa fa-times bigger-110"></i> Close
            </button>
            <button class="btn btn-xs btn-info btn-round"
                onclick="window.location.href='{{ route('trainings.deep_dives.edit', ['training' => $training, 'deep_dive' => $deepDive]) }}'"><i
                    class="fa fa-edit"></i> Edit Information</button>

            <div class="hr hr-12 hr-dotted"></div>

            @include('partials.session_message')

            @include('partials.session_error')

            <div class="row">
                <div class="col-xs-12">
                    <table class="table table-bordered">
                        <col style="width: 20%" />
                        <col style="width: 25%" />
                        <col style="width: 20%" />
                        <col style="width: 25%" />
                        <tr>
                            <th>Apprentice</th>
                            <td>{{ $training->student->full_name }}</td>
                            <th>L&D Coach</th>
                            <td>{{ optional($deepDive->primaryAssessor)->full_name }}</td>
                        </tr>
                        <tr>
                            <th>Date of Deep Dive</th>
                            <td>{{ optional($deepDive->deep_dive_date)->format('d/m/Y') }}</td>
                            <th>Quality Assurance Officer</th>
                            <td>{{ optional($deepDive->verifierUser)->full_name }}</td>
                        </tr>
                        <tr>
                            <th>Employer</th>
                            <td>
                                {{ optional($training->employer)->legal_name }}
                            </td>
                            <th>Operations Manager</th>
                            <td>{{ optional($deepDive->operationsManager)->full_name }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-12">
                    <h2>SECTION ONE: APPRENTICESHIP DETAILS</h2>
                    <table class="table table-bordered">
                        <col style="width: 20%" />
                        <col style="width: 25%" />
                        <col style="width: 20%" />
                        <col style="width: 25%" />
                        <tr>
                            <th>Programme Level</th>
                            <td colspan="3">{{ $training->programme->title }}</td>
                        </tr>
                        <tr>
                            <th>Start Date</th>
                            <td>{{ $training->start_date->format('d/m/Y') }}</td>
                            <th>Practical Period Planned End Date</th>
                            <td>{{ $training->planned_end_date->format('d/m/Y') }}</td>
                        </tr>
                        <tr>
                            <th>Seconday L&D Coach</th>
                            <td>{{ optional($deepDive->secondaryAssessor)->full_name }}</td>
                            <th>Mentor/Employer Name</th>
                            <td>{{ optional($deepDive->employerUser)->full_name }}</td>
                        </tr>
                        <tr>
                            <th>Target Progress</th>
                            <td>{{ $deepDive->target_progress }}%</td>
                            <th>Actual Progress</th>
                            <td>{{ $deepDive->actual_progress }}%</td>
                        </tr>
                        <tr>
                            <th>Target OTJH</th>
                            <td>{{ $deepDive->expected_otj }}</td>
                            <th>Actual OTJH</th>
                            <td>{{ $deepDive->completed_otj }}</td>
                        </tr>
                        <tr>
                            <th>Overall RAG Rating</th>
                            <td colspan="3">{{ $deepDive->overall_rag_rating }}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-12">
                    <h2>SECTION TWO: INITIAL ASSESSMENT & INDUCTION</h2>
                    <span class="text-info">
                        A review of all initial assessments will be conducted and will be referenced against the Onboarding
                        documentation and Delivery Plan.
                        This will look to ensure that the correct information, advice and guidance has been provided to the
                        apprentice to ensure that they
                        are enrolled onto the correct apprenticeship programme and that the correct elements are being
                        included
                        to meet the needs of the apprentice and employer.
                    </span>

                    <table class="table table-bordered">
                        <tr>
                            <th>Subject Area</th>
                            <th>Opt In</th>
                            <th>Opt Out</th>
                            <th>Level</th>
                            <th>Date of IA</th>
                            <th>Outcome of IA</th>
                            <th>Date of DA</th>
                            <th style="width: 20%">Comments</th>
                        </tr>
                        <tr>
                            <th>English</th>
                            <td class="text-center">
                                {!! isset($formData['ia_english_opt_in']) && $formData['ia_english_opt_in'] == 'opt-in' ? '<i class="fa fa-check-circle fa-2x"></i>' : '' !!}
                            </td>
                            <td class="text-center">
                                {!! isset($formData['ia_english_opt_in']) && $formData['ia_english_opt_in'] == 'opt-out' ? '<i class="fa fa-check-circle fa-2x"></i>' : '' !!}
                            </td>
                            <td>{{ $formData['ia_english_level'] ?? '' }}</td>
                            <td>{{ isset($formData['ia_english_date']) && $formData['ia_english_date'] != '' ? Carbon\Carbon::parse($formData['ia_english_date'])->format('d/m/Y') : '' }}</td>
                            <td>{{ $formData['ia_english_outcome'] ?? '' }}</td>
                            <td>{{ isset($formData['ia_english_da']) && $formData['ia_english_da'] != '' ? Carbon\Carbon::parse($formData['ia_english_da'])->format('d/m/Y') : '' }}</td>
                            <td>{!! nl2br(e($formData['ia_english_comments'])) ?? '' !!}</td>
                        </tr>
                        <tr>
                            <th>Maths</th>
                            <td class="text-center">
                                {!! isset($formData['ia_maths_opt_in']) && $formData['ia_maths_opt_in'] == 'opt-in' ? '<i class="fa fa-check-circle fa-2x"></i>' : '' !!}
                            </td>
                            <td class="text-center">
                                {!! isset($formData['ia_maths_opt_in']) && $formData['ia_maths_opt_in'] == 'opt-out' ? '<i class="fa fa-check-circle fa-2x"></i>' : '' !!}
                            </td>
                            <td>{{ $formData['ia_maths_level'] ?? '' }}</td>
                            <td>{{ isset($formData['ia_maths_date']) && $formData['ia_maths_date'] != '' ? Carbon\Carbon::parse($formData['ia_maths_date'])->format('d/m/Y') : '' }}</td>
                            <td>{{ $formData['ia_maths_outcome'] ?? '' }}</td>
                            <td>{{ isset($formData['ia_maths_da']) && $formData['ia_maths_da'] != '' ? Carbon\Carbon::parse($formData['ia_maths_da'])->format('d/m/Y') : '' }}</td>
                            <td>{!! nl2br(e($formData['ia_maths_comments'])) ?? '' !!}</td>
                        </tr>
                        <tr>
                            <th>Digital Skills</th>
                            <td class="text-center"></td>
                            <td class="text-center"></td>
                            <td>{{ $formData['ia_digital_level'] ?? '' }}</td>
                            <td>{{ isset($formData['ia_digital_date']) && $formData['ia_digital_date'] != '' ? Carbon\Carbon::parse($formData['ia_digital_date'])->format('d/m/Y') : '' }}</td>
                            <td>{{ $formData['ia_digital_outcome'] ?? '' }}</td>
                            <td>{{ isset($formData['ia_digital_da']) && $formData['ia_digital_da'] != '' ? Carbon\Carbon::parse($formData['ia_digital_da'])->format('d/m/Y') : '' }}</td>
                            <td>{!! nl2br(e($formData['ia_digital_comments'])) ?? '' !!}</td>
                        </tr>
                        <tr>
                            <th>Subject Skills Scan</th>
                            <td class="text-center"></td>
                            <td class="text-center"></td>
                            <td>{{ $formData['ia_skills_scan_level'] ?? '' }}</td>
                            <td>{{ isset($formData['ia_skills_scan_date']) && $formData['ia_skills_scan_date'] != '' ? Carbon\Carbon::parse($formData['ia_skills_scan_date'])->format('d/m/Y') : '' }}</td>
                            <td>{{ $formData['ia_skills_scan_outcome'] ?? '' }}</td>
                            <td>{{ isset($formData['ia_skills_scan_da']) && $formData['ia_skills_scan_da'] != '' ? Carbon\Carbon::parse($formData['ia_skills_scan_da'])->format('d/m/Y') : '' }}</td>
                            <td>{!! nl2br(e($formData['ia_skills_scan_comments'])) ?? '' !!}</td>
                        </tr>
                    </table>

                    <h3>Initial Information, Advice and Guidance</h3>
                    <table class="table table-bordered">
                        <col style="width: 35%" />
                        <col style="width: 15%" />
                        <col style="width: 15%" />
                        <col style="width: 35%" />
                        <thead>
                            <tr>
                                <th>Checks</th>
                                <th>Yes</th>
                                <th>No</th>
                                <th>Comments</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $iagQuestions = [
                                    1 => 'Has the apprentice completed an interview or IAG session?',
                                    2 => 'Have outcomes of initial interviews/IAG been recorded?',
                                    3 => 'Have the outcomes of initial assessments been discussed and agreed with the apprentice and employer?',
                                    4 => 'Has a programme induction been completed?',
                                    5 => 'Has the Touchbase Checklist been completed?',
                                    6 => 'Has the delivery plan been completed to show all elements of the programme?',
                                    7 => 'Does the learner have an ALS support plan in place?',
                                ];
                            @endphp
                            @foreach ($iagQuestions as $iagQK => $iagQV)
                                <tr>
                                    <th>{{ $iagQV }}</th>
                                    <td class="text-center">
                                        {!! isset($formData["iag_q{$iagQK}"]) && $formData["iag_q{$iagQK}"] == 'Yes' ? '<i class="fa fa-check-circle fa-2x"></i>' : '' !!}
                                    </td>
                                    <td class="text-center">
                                        {!! isset($formData["iag_q{$iagQK}"]) && $formData["iag_q{$iagQK}"] == 'No' ? '<i class="fa fa-check-circle fa-2x"></i>' : '' !!}
                                    </td>
                                    <td>{!! nl2br(e($formData["iag_q{$iagQK}_comments"])) ?? '' !!}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-12">
                    <h2>SECTION THREE: ON PROGRAMME LEARNING & DEVELOPMENT</h2>
                    <h3>Programme Structure and Work Scrutiny</h3>
                    <span class="text-info">
                        A sample of assessments/activities will be taken from across the programme to review planning and
                        feedback which has been provided
                        to the apprentice. Please record all elements of the programme below
                    </span>
                    <table class="table table-bordered">
                        <col style="width: 35%" />
                        <col style="width: 15%" />
                        <col style="width: 15%" />
                        <col style="width: 35%" />
                        <tr>
                            <th>Programme Element</th>
                            <th>Target Progress</th>
                            <th>Actual Progress</th>
                            <th>Comments</th>
                        </tr>
                        @foreach ($training->portfolios as $portfolio)
                            <tr>
                                <td>{{ $portfolio->title }}</td>
                                <td>{{ isset($formData["pf_{$portfolio->id}_target_progress"]) ? $formData["pf_{$portfolio->id}_target_progress"] : $portfolio->target_progress }}%</td>
                                <td>{{ isset($formData["pf_{$portfolio->id}_actual_progress"]) ? $formData["pf_{$portfolio->id}_actual_progress"] : $portfolio->actual_progress }}%</td>
                                <td>{!! nl2br(e($formData["pf_{$portfolio->id}_comments"])) ?? '' !!}</td>
                            </tr>
                        @endforeach
                    </table>

                    <h3>Monthly Teaching and Learning Records</h3>
                    <span class="text-info">
                        A sample of session plans will be reviewed across the programme, this will be taken over a cross
                        section
                        of the programme from the
                        start to the present stage of the apprentice
                    </span>
                    <table class="table table-bordered" id="tblIlp">
                        <col style="width: 10%" />
                        <col style="width: 30%" />
                        <col style="width: 30%" />
                        <col style="width: 30%" />
                        <tr>
                            <th>Date of Plan</th>
                            <th>Activities Planned</th>
                            <th>Activities Completed</th>
                            <th>Comments</th>
                        </tr>
                        @php
                            $maxTlps = collect($formData)
                                ->keys()
                                ->map(function($k) {
                                    return preg_match('/^tlp_date_(\d+)$/', $k, $m) ? (int) $m[1] : null;
                                })
                                ->filter()
                                ->max();
                        @endphp
                        @for($i = 1; $i <= $maxTlps; $i++)
                        <tr>
                            <td>
                                {{ !empty($formData["tlp_date_$i"]) 
                                    ? \Carbon\Carbon::parse($formData["tlp_date_$i"])->format('d/m/Y') 
                                    : '' }}
                            </td>
                            <td>{!! nl2br(e($formData["tlp_activities_planned_$i"])) ?? '' !!}</td>
                            <td>{!! nl2br(e($formData["tlp_activities_completed_$i"])) ?? '' !!}</td>
                            <td>{!! nl2br(e($formData["tlp_comments_$i"])) ?? '' !!}</td>
                        </tr>
                        @endfor                        
                    </table>

                    <h3>Reviews of Progress</h3>
                    <span class="text-info">
                        A review will be completed of a sample of tri-partite progress reviews, this will be taken over a
                        cross
                        section of the programme
                        from the start to the present stage of the apprentice.
                    </span>
                    <table class="table table-bordered" id="tblReviews">
                        <col style="width: 10%" />
                        <col style="width: 10%" />
                        <col style="width: 25%" />
                        <col style="width: 25%" />
                        <col style="width: 25%" />
                        <tr>
                            <th>Planned Date of Review</th>
                            <th>Actual Date of Review</th>
                            <th>Comments</th>
                            <th>Feedback provided by all parties?</th>
                            <th>Signatures collected?</th>
                        </tr>
                        @php
                            $maxReviews = collect($formData)
                                ->keys()
                                ->map(function($k) {
                                    return preg_match('/^review_planned_date_(\d+)$/', $k, $m) ? (int) $m[1] : null;
                                })
                                ->filter()
                                ->max();
                        @endphp
                        @for($i = 1; $i <= $maxReviews; $i++)
                            <tr>
                                <td>
                                    {{ !empty($formData["review_planned_date_$i"]) 
                                        ? \Carbon\Carbon::parse($formData["review_planned_date_$i"])->format('d/m/Y') 
                                        : '' }}
                                </td>
                                <td>
                                    {{ !empty($formData["review_actual_date_$i"]) 
                                        ? \Carbon\Carbon::parse($formData["review_actual_date_$i"])->format('d/m/Y') 
                                        : '' }}
                                </td>
                                <td>{!! nl2br(e($formData["review_comments_$i"] ?? '')) !!}</td>
                                <td>{!! nl2br(e($formData["review_feedback_provided_$i"] ?? '')) !!}</td>
                                <td>{!! nl2br(e($formData["review_signs_collected_$i"] ?? '')) !!}</td>
                            </tr>
                        @endfor
                    </table>
                </div>
            </div>

            <div class="form-group row {{ $errors->has('behind_30_percent') ? 'has-error' : '' }}">
                {!! Form::label('behind_30_percent', 'Learner is more than 30% behind expected progress?', [
                    'class' => 'col-sm-4 control-label text-bold',
                ]) !!}
                <div class="col-sm-8 text-blue">
                    {!! $formData["behind_30_percent"] ?? '' !!}
                </div>
            </div>
            <div class="form-group row {{ $errors->has('is_action_plan_inplace') ? 'has-error' : '' }}">
                {!! Form::label('is_action_plan_inplace', 'Is there an action plan in place?', [
                    'class' => 'col-sm-4 control-label text-bold',
                ]) !!}
                <div class="col-sm-8 text-blue">
                    {!! $formData["is_action_plan_inplace"] ?? '' !!}
                </div>
            </div>
            <div class="form-group row {{ $errors->has('action_plan_comments') ? 'has-error' : '' }}">
                {!! Form::label('action_plan_comments', 'Action Plan Comments', ['class' => 'col-sm-4 control-label text-bold']) !!}
                <div class="col-sm-8 text-blue">
                    {!! nl2br(e($formData["action_plan_comments"])) ?? '' !!}
                </div>
            </div>

            <div class="row">
                <div class="col-xs-12">
                    <h3>Off the Job Training Hours</h3>
                    <span class="text-info">
                        A sample of Learning Log entries will be reviewed for quality, relevance and compliance
                    </span>
                    <table class="table table-bordered">
                        <tr>
                            {{-- <th>Contract Hours</th>
                            <td>{{ $training->totalContrcatedHours() }}</td> --}}
                            <th>Weeks on Programme</th>
                            <td>{{ $training->actual_weeks_on_programme }}</td>
                            <th>Total Hours Required</td>
                            <td>{{ $deepDive->expected_otj }}</td>
                            <th>Total Hours Recorded</td>
                            <td>{{ $deepDive->completed_otj }}</td>
                        </tr>
                    </table>
                    <table class="table table-bordered">
                        <col style="width: 25%" />
                        <col style="width: 25%" />
                        <col style="width: 25%" />
                        <tr>
                            <th>Activities towards Standard</th>
                            <th>Reasonable Hours Recorded</th>
                            <th>New Learning towards KSBs evidence</th>
                            <th>Comments</th>
                        </tr>
                        @php
                            $maxOtjs = collect($formData)
                                ->keys()
                                ->map(function($k) {
                                    return preg_match('/^otj_activity_(\d+)$/', $k, $m) ? (int) $m[1] : null;
                                })
                                ->filter()
                                ->max();
                        @endphp
                        @for($i = 1; $i <= $maxOtjs; $i++)
                        <tr>
                            <td>{!! nl2br(e($formData["otj_activity_$i"])) ?? '' !!}</td>
                            <td>{!! nl2br(e($formData["otj_recorded_hours_$i"])) ?? '' !!}</td>
                            <td>{!! nl2br(e($formData["otj_ksb_learning_$i"])) ?? '' !!}</td>
                            <td>{!! nl2br(e($formData["otj_comments_$i"])) ?? '' !!}</td>
                        </tr>
                        @endfor
                        <tr>
                            <th colspan="2">
                                If opted in for functional skills, is there evidence of learning and reflection and has
                                OTJ
                                been de-selected in learning log for these entries
                                (a minimum of 1 x reflection per month required in the Learning Log)?
                            </th>
                            <td colspan="2">
                                {!! nl2br(e($formData["otj_evidence_of_learning_and_reflection"])) ?? '' !!}
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-12">
                    <h3>Learner Interview</h3>
                    <table class="table tabl-bordered">
                        <tr>
                            <th>Was a Learner interview completed?</th>
                            <td>{{ !empty($formData["learner_intv_comp"]) ? $formData["learner_intv_comp"] : ''  }}</td>                            
                        </tr>
                        <tr>
                            <th>Please record outcomes of Learner and provide the interview form with the
                                form.
                            </th>
                            <td>
                                {!! nl2br(e($formData["learner_interview_outcome"])) ?? '' !!}
                            </td>
                        </tr>
                    </table>

                </div>
            </div>

            <div class="row">
                <div class="col-xs-12">
                    <h3>Employer Interview</h3>
                    <table class="table tabl-bordered">
                        <tr>
                            <th>Was an employer interview completed?</th>
                            <td>{{ !empty($formData["employer_intv_comp"]) ? $formData["employer_intv_comp"] : ''  }}</td>                            
                        </tr>
                        <tr>
                            <th>Please record outcomes of Learner and provide the interview form with the
                                form.
                            </th>
                            <td>
                                {!! nl2br(e($formData["emplpoyer_interview_outcome"])) ?? '' !!}
                            </td>
                        </tr>
                    </table>

                </div>
            </div>

            <div class="row">
                <div class="col-xs-12">
                    <h2>SECTION FOUR: Feedback, Action Plan and Review</h2>
                    <span class="text-info">
                        Note: If an OTLA has been completed in the previous 6 months to date, please record this in the
                        general
                        comments section and make reference to the OTLA document completed
                        (it is not necessary to list all the actions and areas of good practice/development from the OTLA).
                    </span>
                    <table class="table table-bordered">
                        <tr>
                            <th>Date of feedback Session</th>
                            <td>{{ isset($formData['feedback_date']) && $formData['feedback_date'] != '' ? Carbon\Carbon::parse($formData['feedback_date'])->format('d/m/Y') : '' }}</td>
                            <th>Attended by</th>
                            <td>{!! nl2br(e($formData["feedback_comments"])) ?? '' !!}</td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-12">
                    <h3>General Comments</h3>
                    <table class="table table-bordered">
                        <tr>
                            <th style="text-align: left">Good Practice</th>
                        </tr>
                        <tr>
                            <td>
                                <ul>
                                    <li>Reviews have been completed on time once the learner returned from a BIL</li>
                                    <li>An action plan was put in place as soon as the learner fell behind</li>
                                </ul>
                            </td>
                        </tr>
                        <tr>
                            <th style="text-align: left">Areas for Development</th>
                        </tr>
                        <tr>
                            <td>
                                <ul>
                                    <li>The action plan has not proved effective in keeping the learner on track</li>
                                    <li>The employer needs to engage in the review process</li>
                                </ul>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-12">
                    <h3>Actions and Developments</h3>
                    <span class="text-info">All agreed actions will be discussed and reviewed at programme team
                        meetings</span>

                    <table class="table table-bordered" id="tblAD">
                        <col style="width: 60%" />
                        <col style="width: 20%" />
                        <col style="width: 20%" />
                        <tr>
                            <th>Action/Development Required and impact of action</th>
                            <th>Who</th>
                            <th>Review Date</th>
                        </tr>
                        @php
                            $maxADs = collect($formData)
                                ->keys()
                                ->map(function($k) {
                                    return preg_match('/^action_and_develop_(\d+)$/', $k, $m) ? (int) $m[1] : null;
                                })
                                ->filter()
                                ->max();
                        @endphp
                        @for($i = 1; $i <= $maxADs; $i++)
                        <tr>
                            <td>{!! nl2br(e($formData["action_and_develop_$i"])) ?? '' !!}</td>
                            <td>{{ $formData["action_and_develop_by_who_$i"] ?? '' }}</td>
                            <td>
                                {{ !empty($formData["action_and_develop_review_date_$i"]) 
                                    ? \Carbon\Carbon::parse($formData["action_and_develop_review_date_$i"])->format('d/m/Y') 
                                    : '' }}
                            </td>
                        </tr>
                        @endfor
                    </table>
                </div>
            </div>

            <div class="row">
                <div class="col-xs-12">
                    <h3>Review</h3>
                    <table class="table tabl-bordered">
                        <tr>
                            <th class="text-right">Date of Review</th>
                            <td>
                                {{ isset($formData['date_of_review']) && $formData['date_of_review'] != '' ? Carbon\Carbon::parse($formData['date_of_review'])->format('d/m/Y') : '' }}
                            </td>
                        <tr>
                            <th class="text-right">All Actions Completed</th>
                            <td>{{ !empty($formData["all_actions_comp"]) ? $formData["all_actions_comp"] : ''  }}</td>
                        </tr>
                        <tr>
                            <th>Further Action Required, if any</th>
                            <td>{!! nl2br(e($formData["further_action"])) ?? '' !!}</td>
                        </tr>
                    </table>

                </div>
            </div>

            <!-- PAGE CONTENT ENDS -->
        </div><!-- /.col -->
    </div><!-- /.row -->
@endsection


