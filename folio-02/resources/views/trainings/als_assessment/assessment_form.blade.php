@extends('layouts.master')

@section('title', 'Individual Learning Support Assessment and Plan')

@section('page-plugin-styles')
<style>
    input[type=checkbox] {
        transform: scale(1.4);
    }
    textarea {
        border: 1px solid #3366FF;
        border-radius: 5px;
        border-left: 5px solid #3366FF;
    }
</style>
@endsection

@section('page-content')
    <div class="page-header">
        <h1>
            Individual Learning Support Assessment and Plan
        </h1>
    </div><!-- /.page-header -->
    <div class="row">
        <div class="col-xs-12">
            <!-- PAGE CONTENT BEGINS -->
            <button class="btn btn-sm btn-white btn-default btn-round" type="button"
                onclick="window.location.href='{{ route('trainings.als_assessment.show', ['training' => $training, 'als_assessment' => $alsAssessment]) }}'">
                <i class="ace-icon fa fa-times bigger-110"></i> Close
            </button>

            <div class="hr hr-12 hr-dotted"></div>

            @include('trainings.partials.training_quick_details', ['showOverallPercentage' => true])

            @include('partials.session_message')

            @include('partials.session_error')

            <div class="row">
                <div class="col-sm-12">
                    <div class="space"></div>
                    {!! Form::model($alsAssessment, [
                        'method' => 'PATCH',
                        'url' => route('trainings.als_assessment.save_form', [$training, $alsAssessment]),
                        'class' => 'form-horizontal',
                        'files' => true,
                        'id' => 'frmAlsAssessment',
                    ]) !!}

                    <!-- Users -->
                    <div class="card" style="margin-bottom: 4px">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table row-border">
                                    <tr>
                                        <td>
                                            <dl>
                                                <dt>Assessor</dt>
                                                <dd>{{ $alsAssessment->assessorName() }}</dd>
                                            </dl>
                                        </td>
                                        <td>
                                            <dl>
                                                <dt>Functional Skills Tutor</dt>
                                                <dd>{{ $alsAssessment->tutorName() }}</dd>
                                            </dl>
                                        </td>
                                        <td>
                                            <dl>
                                                <dt>IQA</dt>
                                                <dd>{{ $alsAssessment->iqaName() }}</dd>
                                            </dl>
                                        </td>
                                        <td>
                                            <dl>
                                                <dt>ALS Tutor</dt>
                                                <dd>{{ $alsAssessment->alsTutorName() }}</dd>
                                            </dl>
                                        </td>
                                    </tr>
                                </table>
                            </div>

                        </div>
                    </div>

                    <!-- Dates -->
                    <div class="card" style="margin-bottom: 4px">
                        <div class="card-header">
                            <h3 class="header lighter smaller">
                                Dates
                            </h3>
                        </div>
                        <div class="card-body">
                            <table class="table table-bordered">
                                <col width="50%" />
                                <col width="50%" />
                                <tr>
                                    <th>Referral Date</th>
                                    <td>
                                        {!! Form::date('referral_date', $alsAssessment->referral_date ?? null, ['class' => 'form-control']) !!}
                                    </td>
                                </tr>
                                <tr>
                                    <th>Date of ALS Meeting</th>
                                    <td>
                                        {!! Form::date('als_meeting_date', $alsAssessment->als_meeting_date ?? null, ['class' => 'form-control']) !!}
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <!-- History and background -->
                    <div class="card" style="margin-bottom: 4px">
                        <div class="card-header">
                            <h3 class="header lighter smaller">
                                History and Background
                            </h3>
                        </div>
                        <div class="card-body">

                            @php
                                $habQuestions = [
                                    1 => 'Do you have a diagnosis of learning difficulty, disability or cognitive processing difficulty?',
                                    2 => 'Do you think you have a learning difficulty, disability or cognitive processing difficulty?',
                                    3 => 'What difficulties do you have with English?',
                                    4 => 'What difficulties do you have with maths?',
                                    5 => 'What difficulties have you had with other qualifications in the past?',
                                    6 => 'Did you receive any support at school or college?  If so, please provide details.  If you did not receive support at school or college, what support do you think you needed?',
                                    7 => 'What support do you think you will need for the Standard?  Normal ways of working are one session per month with your assessor. ',
                                    8 => 'Have any family members been diagnosed with a SpLD?',
                                    9 => 'If applicable, did you have difficulty with any subjects in your home country? ',
                                    10 => 'Do you feel that you are good at your job?  If so, why?',
                                ];
                            @endphp

                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <col width="50%" />
                                    <col width="50%" />
                                    <thead>
                                        <tr>
                                            <th class="text-center bg-success">Question</th>
                                            <th class="text-center bg-success">Detail</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($habQuestions as $_Id => $_Desc)
                                            <tr>
                                                <td>{{ $_Desc }}</td>
                                                <td>
                                                    {!! Form::textarea('hab_question_' . $_Id, null, ['class' => 'form-control', 'rows' => 3]) !!}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>

                    <!-- Assessment outcomes -->
                    <div class="card" style="margin-bottom: 4px">
                        <div class="card-header">
                            <h3 class="header lighter smaller">
                                Assessment Outcomes
                            </h3>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <col width="50%" />
                                    <col width="50%" />
                                    <thead>
                                        <tr>
                                            <th colspan="2" class="text-center bg-success">English and maths Initial
                                                Assessment</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>English Initial Assessment score</td>
                                            <td>
                                                {!! Form::text('score_ia_english', null, ['class' => 'form-control', 'maxlength' => 70]) !!}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Maths Initial Assessment score</td>
                                            <td>
                                                {!! Form::text('score_ia_maths', null, ['class' => 'form-control', 'maxlength' => 70]) !!}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Free writing score</td>
                                            <td>
                                                {!! Form::text('score_free_writing', null, ['class' => 'form-control', 'maxlength' => 70]) !!}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>

                    <p class="bolder text-info">If a learner declares indicative signs, a screening checklist must be
                        completed.
                    </p>

                    <!-- Screener/assessment -->
                    <div class="card" style="margin-bottom: 4px">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <col width="50%" />
                                    <col width="50%" />
                                    <thead>
                                        <tr>
                                            <th class="text-center">Screener/assessment</th>
                                            <th class="text-center">Indicative outcome results</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Dyslexia</td>
                                            <td>
                                                {!! Form::text('indicative_outcome_dyslexia', null, ['class' => 'form-control', 'maxlength' => 70]) !!}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>ADHD</td>
                                            <td>
                                                {!! Form::text('indicative_outcome_adhd', null, ['class' => 'form-control', 'maxlength' => 70]) !!}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Dyscalculia</td>
                                            <td>
                                                {!! Form::text('indicative_outcome_dyscalculia', null, ['class' => 'form-control', 'maxlength' => 70]) !!}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>ASD</td>
                                            <td>
                                                {!! Form::text('indicative_outcome_asd', null, ['class' => 'form-control', 'maxlength' => 70]) !!}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>

                    <!-- Adult reading test 2 outcome -->
                    <div class="card" style="margin-bottom: 4px">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <col width="50%" />
                                    <col width="50%" />
                                    <thead>
                                        <tr>
                                            <th colspan="2" class="text-center">Adult Reading Test 2 outcome</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <th>Test</th>
                                            <th>Standard score
                                                A score of 85 or below is considered below average.
                                            </th>
                                        </tr>
                                        <tr>
                                            <td>Accuracy</td>
                                            <td>
                                                {!! Form::text('art2_outcome_accuracy', null, ['class' => 'form-control', 'maxlength' => 70]) !!}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Speed of reading (aloud)</td>
                                            <td>
                                                {!! Form::text('art2_outcome_reading_speed', null, ['class' => 'form-control', 'maxlength' => 70]) !!}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>

                    <!-- Test of Information Processing Speed outcome -->
                    <div class="card" style="margin-bottom: 4px">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <col width="50%" />
                                    <col width="50%" />
                                    <thead>
                                        <tr>
                                            <th colspan="2" class="text-center">Test of Information Processing Speed
                                                outcome
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <th>Test</th>
                                            <th>Standard score
                                                A score of 85 or below is considered below average.
                                            </th>
                                        </tr>
                                        <tr>
                                            <td>Visual Modality</td>
                                            <td>
                                                {!! Form::text('toips_outcome_visual', null, ['class' => 'form-control', 'maxlength' => 70]) !!}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Auditory Modality </td>
                                            <td>
                                                {!! Form::text('toips_outcome_auditory', null, ['class' => 'form-control', 'maxlength' => 70]) !!}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Delayed Recall</td>
                                            <td>
                                                {!! Form::text('toips_outcome_delayed', null, ['class' => 'form-control', 'maxlength' => 70]) !!}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Word Fluency </td>
                                            <td>
                                                {!! Form::text('toips_outcome_word', null, ['class' => 'form-control', 'maxlength' => 70]) !!}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>

                    <!-- More trouble with maths assessment -->
                    <div class="card" style="margin-bottom: 4px">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <col width="50%" />
                                    <col width="50%" />
                                    <thead>
                                        <tr>
                                            <th colspan="2" class="text-center">More trouble with maths assessment <br>
                                                (Percentile below 50% is considered below average)
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Score</td>
                                            <td>
                                                {!! Form::text('maths_trouble_score', null, ['class' => 'form-control', 'maxlength' => 70]) !!}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Average for year group </td>
                                            <td>
                                                {!! Form::text('maths_trouble_group_avg', null, ['class' => 'form-control', 'maxlength' => 70]) !!}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Percentile </td>
                                            <td>
                                                {!! Form::text('maths_trouble_percentile', null, ['class' => 'form-control', 'maxlength' => 70]) !!}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>

                    <!-- Will the learner receive Additional Learning Support? -->
                    <div class="card" style="margin-bottom: 4px">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <col width="50%" />
                                    <col width="50%" />
                                    <thead>
                                        <tr>
                                            <th colspan="2" class="text-center">Will the learner receive Additional
                                                Learning
                                                Support?
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>The learner has a learning difficulty or disability as defined in Section
                                                15Za(6) of the Education Act 1996, who, as a result of this learning
                                                difficulty
                                                or disability, requires reasonable adjustments in order to complete their
                                                apprenticeship.</td>
                                            <td>
                                                {!! Form::text('receive_als_1', null, ['class' => 'form-control', 'maxlength' => 70]) !!}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Outcomes of the assessments identify indicative signs of a potential
                                                learning
                                                difficulty/difference which would directly affect the apprentice's ability
                                                to
                                                complete their apprenticeship in accordance with section 15Za (6) of the
                                                Education Act 1996.</td>
                                            <td>
                                                {!! Form::text('receive_als_2', null, ['class' => 'form-control', 'maxlength' => 70]) !!}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Intended duration of ALS </td>
                                            <td>
                                                {!! Form::text('receive_als_3', null, ['class' => 'form-control', 'maxlength' => 70]) !!}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Will the learner be entitled to reasonable adjustments for external
                                                examinations?</td>
                                            <td>
                                                {!! Form::text('receive_als_4', null, ['class' => 'form-control', 'maxlength' => 70]) !!}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>

                    <!-- Impact of Learning -->
                    <div class="card" style="margin-bottom: 4px">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <col width="50%" />
                                    <col width="50%" />
                                    <thead>
                                        <tr>
                                            <th colspan="2" class="text-center">Impact of Learning
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td colspan="2">
                                                <i class="text-info"><strong>Standard:</strong>
                                                    Consider assessment outcomes and learner comments specific to
                                                    qualification
                                                    and needs</i>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">
                                                <i class="text-info"><strong>English:</strong>
                                                    Consider IA/diagnostics, free writing , learner comments and assessment
                                                    outcomes specific to qualification and needs</i>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">
                                                <i class="text-info"><strong>Maths:</strong>
                                                    Consider IA/diagnostics, free writing , learner comments and assessment
                                                    outcomes specific to qualification and needs.</i>
                                                <i class="text-info">How many levels distance to travel to reach required
                                                    level?
                                                </i>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Standard</td>
                                            <td>
                                                {!! Form::textarea('iol_diploma', null, ['class' => 'form-control', 'rows' => 3]) !!}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>English</td>
                                            <td>
                                                {!! Form::textarea('iol_english', null, ['class' => 'form-control', 'rows' => 3]) !!}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>Maths</td>
                                            <td>
                                                {!! Form::textarea('iol_maths', null, ['class' => 'form-control', 'rows' => 3]) !!}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>

                    <!-- Recommendations for monthly sessions -->
                    <div class="card" style="margin-bottom: 4px">
                        <div class="card-body">

                            @php
                                $monthlySessionRecommendationsList = [
                                    1 => 'Additional sessions ',
                                    2 => 'Shorter more frequent sessions',
                                    3 => 'Extra time for reading ',
                                    4 => 'Extra time for writing ',
                                    5 => 'Pre-reading materials ',
                                    6 => 'Rest breaks ',
                                    7 => 'Support with note taking',
                                    8 => 'Scribe',
                                    9 => 'Additional resources ',
                                    10 => 'Adapted resources, such as colour, breaking down of tasks/presentations',
                                    11 => 'Multisensory teaching',
                                    12 => 'Memory strategies ',
                                    13 => 'Support with a glossary ',
                                    14 => 'Visual and auditory teaching ',
                                    15 => 'Professional discussions',
                                    16 => 'Repetition (more than one occasion)',
                                    17 => 'Extra time for mock exams ',
                                    18 => 'Check in calls ',
                                ];
                            @endphp

                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <col width="50%" />
                                    <col width="50%" />
                                    <thead>
                                        <tr>
                                            <th colspan="2" class="text-center">Recommendations for monthly sessions
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php $selectedRecommendations = empty($alsAssessment->recommendations) ? [] : json_decode($alsAssessment->recommendations); @endphp
                                        @foreach ($monthlySessionRecommendationsList as $_Id => $_Desc)
                                            <tr>
                                                <td>{{ $_Desc }}</td>
                                                <td>
                                                    <input class="" type="checkbox" name="recommendations[]"
                                                        value="{{ $_Id }}" {{ in_array($_Id, $selectedRecommendations) ? 'checked' : '' }}>
                                                </td>
                                            </tr>
                                        @endforeach
                                        <tr>
                                            <td>Any additional not stated above</td>
                                            <td>
                                                {!! Form::textarea('recommendation_additional', null, ['class' => 'form-control', 'rows' => 3]) !!}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>

                    <!-- Support plan Standard -->
                    <div class="card" style="margin-bottom: 4px">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <col width="20%" />
                                    <col width="40%" />
                                    <col width="40%" />
                                    <thead>
                                        <tr>
                                            <th colspan="3" class="text-center">Support plan Standard <br>
                                                The learner has agreed that the following reasonable adjustments are needed
                                                to support achievement. A learner can refuse ALS in any month not
                                                needed.<br>
                                                For details of delivery topics, please refer to the delivery planner.
                                            </th>
                                        </tr>
                                        <tr>
                                            <th>Month</th>
                                            <th>Intended Topic</th>
                                            <th>Reasonable adjustments agreed with learner</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @for ($i = 1; $i <= 5; $i++)
                                            <tr>
                                                <td>
                                                    {!! Form::text('spd_month' . $i, null, ['class' => 'form-control', 'maxlength' => 70]) !!}
                                                </td>
                                                <td>
                                                    {!! Form::text('spd_intended_topic' . $i, null, ['class' => 'form-control', 'maxlength' => 70]) !!}
                                                </td>
                                                <td>
                                                    {!! Form::text('spd_agreed_adjustment' . $i, null, ['class' => 'form-control', 'maxlength' => 70]) !!}
                                                </td>
                                            </tr>
                                        @endfor
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>

                    <!-- Functional Skill Flexibilities -->
                    <div class="card" style="margin-bottom: 4px">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <col width="20%" />
                                    <col width="40%" />
                                    <col width="40%" />
                                    <thead>
                                        <tr>
                                            <th colspan="3" class="text-center">Functional Skill Flexibilities<br>
                                                Initial programme for English and maths ALN flexibilities
                                            </th>
                                        </tr>
                                        <tr>
                                            <th>Date</th>
                                            <th>Intended Topic</th>
                                            <th>Reasonable adjustments agreed with learner</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @for ($i = 1; $i <= 5; $i++)
                                        @php
                                        $fsf_date = 'fsf_date_' . $i;
                                        $fsf_intended_topic = 'fsf_intended_topic_' . $i;
                                        $fsf_agreed_adjustment = 'fsf_agreed_adjustment_' . $i;
                                        @endphp
                                            <tr>
                                                <td>
                                                    {!! Form::date('fsf_date_' . $i, $alsAssessment->$fsf_date ?? null, ['class' => 'form-control']) !!}
                                                </td>
                                                <td>
                                                    {!! Form::text('fsf_intended_topic_' . $i, $alsAssessment->$fsf_intended_topic ?? null, ['class' => 'form-control', 'maxlength' => 70]) !!}
                                                </td>
                                                <td>
                                                    {!! Form::text('fsf_agreed_adjustment_' . $i, $alsAssessment->$fsf_agreed_adjustment ?? null, ['class' => 'form-control', 'maxlength' => 70]) !!}
                                                </td>
                                            </tr>
                                        @endfor
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>

                    <!-- Functional Skill Flexibilities Review -->
                    <div class="card" style="margin-bottom: 4px">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <col width="50%" />
                                    <col width="50%" />
                                    <thead>
                                        <tr>
                                            <th colspan="2" class="text-center">Functional Skill Flexibilities Review
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <th colspan="2">
                                                <strong>Outcome of review</strong>
                                                (once above plan has been completed and IA retaken)
                                            </th>
                                        </tr>
                                        <tr>
                                            <th>Review date</th>
                                            <td>
                                                {!! Form::date('fsfr_date', $alsAssessment->fsfr_date ?? null, ['class' => 'form-control']) !!}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th>Are flexibilities being applied for?</th>
                                            <td>
                                                {!! Form::text('fsfr_applied', null, ['class' => 'form-control', 'maxlength' => 70]) !!}
                                            </td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">Details of review, including Functional Skills Specialist
                                                comments and evidence of progress</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>

                    <!-- Support plan Functional Skills -->
                    <div class="card" style="margin-bottom: 4px">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <col width="33%" />
                                    <col width="33%" />
                                    <col width="33%" />
                                    <thead>
                                        <tr>
                                            <th colspan="3" class="text-center">Support plan Functional Skills<br>
                                                The learner has agreed that the following reasonable adjustments are needed
                                                to support achievement. A learner can refuse ALS in any month not
                                                needed.<br>
                                                For details of delivery topics, please refer to the ILP.
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>English and/or maths flexibilities being applied?</td>
                                            <td>English<br>State Yes/No/Level </td>
                                            <td>Maths<br>State Yes/No/Level </td>
                                        </tr>
                                        <tr>
                                            <th>Month</th>
                                            <th>Intended Topic</th>
                                            <th>Reasonable adjustments agreed with learner</th>
                                        </tr>
                                        @for ($i = 1; $i <= 5; $i++)
                                            <tr>
                                                <td>
                                                    {!! Form::text('spfs_month' . $i, null, ['class' => 'form-control', 'maxlength' => 70]) !!}
                                                </td>
                                                <td>
                                                    {!! Form::text('spfs_intended_topic' . $i, null, ['class' => 'form-control', 'maxlength' => 70]) !!}
                                                </td>
                                                <td>
                                                    {!! Form::text('spfs_agreed_adjustment' . $i, null, ['class' => 'form-control', 'maxlength' => 70]) !!}
                                                </td>
                                            </tr>
                                        @endfor
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>

                    <!-- Learner Confirmation -->
                    @if(auth()->user()->isStudent())
                    <div class="card" style="margin-bottom: 4px">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <col width="33%" />
                                    <col width="33%" />
                                    <col width="33%" />
                                    <thead>
                                        <tr>
                                            <th colspan="3" class="text-center">Confirmation</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <th colspan="3" class="text-center">
                                                <i>
                                                    I agree that the above Learning Support Plan is necessary to support my
                                                    apprenticeship and will attend all agreed sessions and monthly
                                                    reviews.<br>
                                                    I am aware that if I do not attend sessions, I may be withdrawn from the
                                                    apprenticeship.<br>
                                                    I am aware that I have not received a formal diagnosis of any learning
                                                    difficulty.
                                                </i>
                                            </th>
                                        </tr>
                                        <tr>
                                            <td>Learner Signature</td>
                                            <td>
                                                <input type="checkbox" name="learner_sign" 
                                                    value="1" {{ auth()->user()->id !== $training->student_id ? 'disabled' : '' }}>
                                            </td>
                                            <td>
                                                {{ optional($alsAssessment->learner_sign_date)->format('d/m/Y') }}
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="text-center">
                                                <i>
                                                    Do you consent to sharing this support assessment and plan with my
                                                    employer?
                                                </i>
                                            </th>
                                            <td>
                                                {!! Form::select('share_with_employer', ['Yes' => 'Yes - share with employer', 'No' => 'No - not share with employer'], null, [
                                                    'class' => 'form-control',
                                                    'placeholder' => '',
                                                ]) !!}
                                                <label class="block" style="padding: 2%">
                                                    <input type="checkbox" name="learner_confirm_choice" class="ace input-lg"
                                                        value="1" {{ auth()->user()->id !== $training->student_id ? 'disabled' : '' }}>
                                                    <span class="lbl"> Tick to confirm your choice</span>
                                                </label>
                                            </td>
                                            <td>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                    @else
                    @include('trainings.als_assessment.partials.learner_confirmation_read', ['alsAssessment' => $alsAssessment])
                    @endif

                    <!-- Assessor Confirmation -->
                    @if($alsAssessment->assessor_id === auth()->user()->id)
                    <div class="card" style="margin-bottom: 4px">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <col width="33%" />
                                    <col width="33%" />
                                    <col width="33%" />
                                    <thead>
                                        <tr>
                                            <th colspan="3" class="text-center">Assessor and Functional Skills Tutor
                                                Confirmation</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <th colspan="3" class="text-center">
                                                <i>
                                                    I agree that I will adhere to the above plan to support the learner in
                                                    achieving their qualification in a timely manner.<br>
                                                    I will provide all necessary reasonable adjustments and complete monthly
                                                    reviews.
                                                </i>
                                            </th>
                                        </tr>
                                        <tr>
                                            <td>Assessor Signature</td>
                                            <td>
                                                <input type="checkbox" name="assessor_sign" {{ $alsAssessment->assessor_sign ? 'checked' : '' }}
                                                    value="1" {{ auth()->user()->id !== $alsAssessment->assessor_id ? 'disabled' : '' }}>
                                            </td>
                                            <td>
                                                {{ optional($alsAssessment->assessor_sign_date)->format('d/m/Y') }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                    @else
                    @include('trainings.als_assessment.partials.assessor_confirmation_read', ['alsAssessment' => $alsAssessment])
                    @endif

                    <!-- FS Tutor Confirmation -->
                    @if($alsAssessment->fs_tutor_id === auth()->user()->id)
                    <div class="card" style="margin-bottom: 4px">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <col width="33%" />
                                    <col width="33%" />
                                    <col width="33%" />
                                    <thead>
                                        <tr>
                                            <th colspan="3" class="text-center">Assessor and Functional Skills Tutor
                                                Confirmation</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <th colspan="3" class="text-center">
                                                <i>
                                                    I agree that I will adhere to the above plan to support the learner in
                                                    achieving their qualification in a timely manner.<br>
                                                    I will provide all necessary reasonable adjustments and complete monthly
                                                    reviews.
                                                </i>
                                            </th>
                                        </tr>
                                        <tr>
                                            <td>Functional Skills Tutor Signature</td>
                                            <td>
                                                <input type="checkbox" name="fs_tutor_sign"
                                                    value="1" {{ auth()->user()->id !== $alsAssessment->fs_tutor_id ? 'disabled' : '' }} >
                                            </td>
                                            <td>
                                                {{ optional($alsAssessment->fs_tutor_sign_date)->format('d/m/Y') }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                    @else
                    @include('trainings.als_assessment.partials.fs_tutor_confirmation_read', ['alsAssessment' => $alsAssessment])
                    @endif

                    <!-- IQA and ALS Tutor Confirmation -->
                    @if($alsAssessment->iqa_id === auth()->user()->id)
                    <div class="card" style="margin-bottom: 4px">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <col width="33%" />
                                    <col width="33%" />
                                    <col width="33%" />
                                    <thead>
                                        <tr>
                                            <th colspan="3" class="text-center">IQA Confirmation....</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <th colspan="3" class="text-center">
                                                <i>
                                                    I will support the assessor/learner as appropriate according to the
                                                    above plan
                                                </i>
                                            </th>
                                        </tr>
                                        <tr>
                                            <td>IQA Signature</td>
                                            <td>
                                                <input type="checkbox" name="iqa_sign" 
                                                value="1" {{ auth()->user()->id !== $alsAssessment->iqa_id ? 'disabled' : '' }}>
                                            </td>
                                            <td>
                                                {{ optional($alsAssessment->iqa_sign_date)->format('d/m/Y') }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                    @else
                    @include('trainings.als_assessment.partials.iqa_confirmation_read', ['alsAssessment' => $alsAssessment])
                    @endif

                    <!-- IQA and ALS Tutor Confirmation -->
                    @if($alsAssessment->als_tutor_id === auth()->user()->id)
                    <div class="card" style="margin-bottom: 4px">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <col width="33%" />
                                    <col width="33%" />
                                    <col width="33%" />
                                    <thead>
                                        <tr>
                                            <th colspan="3" class="text-center">ALS Tutor Confirmation</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <th colspan="3" class="text-center">
                                                <i>
                                                    I will support the assessor/learner as appropriate according to the
                                                    above plan
                                                </i>
                                            </th>
                                        </tr>
                                        <tr>
                                            <td>ALS Tutor Signature</td>
                                            <td>
                                                <input type="checkbox" name="als_tutor_sign"
                                                    value="1" {{ auth()->user()->id !== $alsAssessment->als_tutor_id ? 'disabled' : '' }}>
                                            </td>
                                            <td>
                                                {{ optional($alsAssessment->als_tutor_sign_date)->format('d/m/Y') }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                    @else
                    @include('trainings.als_assessment.partials.als_tutor_confirmation_read', ['alsAssessment' => $alsAssessment])
                    @endif

                    <!-- Submit Button -->
                    <div class="text-center">
                        <button type="submit" class="btn btn-success btn-md btn-round">
                            <i class="fa fa-save"></i> Save Information
                        </button>
                    </div>

                    {!! Form::close() !!}
                </div>
            </div>

            <!-- PAGE CONTENT ENDS -->
        </div><!-- /.col -->
    </div><!-- /.row -->
@endsection

@push('after-scripts')
    <script></script>
@endpush
