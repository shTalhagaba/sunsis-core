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
        <div class="table-responsive">
            <table class="table table-bordered">
                <col width="50%" />
                <col width="50%" />
                <tr>
                    <th>Referral Date</th>
                    <td>{{ optional($alsAssessment->referral_date)->format('d/m/Y') }}</td>
                </tr>
                <tr>
                    <th>Date of ALS Meeting</th>
                    <td>{{ optional($alsAssessment->als_meeting_date)->format('d/m/Y') }}</td>
                </tr>
            </table>
        </div>
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
                                @php $question = 'hab_question_' . $_Id @endphp
                                {!! nl2br(e($alsAssessment->$question)) !!}
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
                            {{ $alsAssessment->score_ia_english }}
                        </td>
                    </tr>
                    <tr>
                        <td>Maths Initial Assessment score</td>
                        <td>
                            {{ $alsAssessment->score_ia_maths }}
                        </td>
                    </tr>
                    <tr>
                        <td>Free writing score</td>
                        <td>
                            {{ $alsAssessment->score_free_writing }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

    </div>
</div>

<p class="bolder text-info">If a learner declares indicative signs, a screening checklist must be completed.
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
                            {{ $alsAssessment->indicative_outcome_dyslexia }}
                        </td>
                    </tr>
                    <tr>
                        <td>ADHD</td>
                        <td>
                            {{ $alsAssessment->indicative_outcome_adhd }}
                        </td>
                    </tr>
                    <tr>
                        <td>Dyscalculia</td>
                        <td>
                            {{ $alsAssessment->indicative_outcome_dyscalculia }}
                        </td>
                    </tr>
                    <tr>
                        <td>ASD</td>
                        <td>
                            {{ $alsAssessment->indicative_outcome_asd }}
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
                            {{ $alsAssessment->art2_outcome_accuracy }}
                        </td>
                    </tr>
                    <tr>
                        <td>Speed of reading (aloud)</td>
                        <td>
                            {{ $alsAssessment->art2_outcome_reading_speed }}
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
                        <th colspan="2" class="text-center">Test of Information Processing Speed outcome
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
                            {{ $alsAssessment->toips_outcome_visual }}
                        </td>
                    </tr>
                    <tr>
                        <td>Auditory Modality </td>
                        <td>
                            {{ $alsAssessment->toips_outcome_auditory }}
                        </td>
                    </tr>
                    <tr>
                        <td>Delayed Recall</td>
                        <td>
                            {{ $alsAssessment->toips_outcome_delayed }}
                        </td>
                    </tr>
                    <tr>
                        <td>Word Fluency </td>
                        <td>
                            {{ $alsAssessment->toips_outcome_word }}
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
                            {{ $alsAssessment->maths_trouble_score }}
                        </td>
                    </tr>
                    <tr>
                        <td>Average for year group </td>
                        <td>
                            {{ $alsAssessment->maths_trouble_group_avg }}
                        </td>
                    </tr>
                    <tr>
                        <td>Percentile </td>
                        <td>
                            {{ $alsAssessment->maths_trouble_percentile }}
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
                        <th colspan="2" class="text-center">Will the learner receive Additional Learning
                            Support?
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>The learner has a learning difficulty or disability as defined in Section
                            15Za(6) of the Education Act 1996, who, as a result of this learning difficulty
                            or disability, requires reasonable adjustments in order to complete their
                            apprenticeship.</td>
                        <td>
                            {{ $alsAssessment->receive_als_1 }}
                        </td>
                    </tr>
                    <tr>
                        <td>Outcomes of the assessments identify indicative signs of a potential learning
                            difficulty/difference which would directly affect the apprentice's ability to
                            complete their apprenticeship in accordance with section 15Za (6) of the
                            Education Act 1996.</td>
                        <td>
                            {{ $alsAssessment->receive_als_2 }}
                        </td>
                    </tr>
                    <tr>
                        <td>Intended duration of ALS </td>
                        <td>
                            {{ $alsAssessment->receive_als_3 }}
                        </td>
                    </tr>
                    <tr>
                        <td>Will the learner be entitled to reasonable adjustments for external
                            examinations?</td>
                        <td>
                            {{ $alsAssessment->receive_als_4 }}
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
                                Consider assessment outcomes and learner comments specific to qualification
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
                            <i class="text-info">How many levels distance to travel to reach required level?
                            </i>
                        </td>
                    </tr>
                    <tr>
                        <td>Diploma</td>
                        <td>
                            {{ $alsAssessment->iol_diploma }}
                        </td>
                    </tr>
                    <tr>
                        <td>English</td>
                        <td>
                            {{ $alsAssessment->iol_english }}
                        </td>
                    </tr>
                    <tr>
                        <td>Maths</td>
                        <td>
                            {{ $alsAssessment->iol_maths }}
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
                        <th colspan="2" class="text-center">Recommendations for monthly sessions</th>
                    </tr>
                </thead>
                <tbody>
                    @php $selectedRecommendations = empty($alsAssessment->recommendations) ? [] : json_decode($alsAssessment->recommendations); @endphp
                    @foreach ($monthlySessionRecommendationsList as $_Id => $_Desc)
                        <tr>
                            <td>{{ $_Desc }}</td>
                            <td>
                                {{ in_array($_Id, $selectedRecommendations) ? 'Yes' : '' }}
                            </td>
                        </tr>
                    @endforeach
                    <tr>
                        <td>Any additional not stated above</td>
                        <td>
                            {{ nl2br(e($alsAssessment->recommendation_additional)) }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

    </div>
</div>

<!-- Support plan Diploma -->
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
                            The learner has agreed that the following reasonable adjustments are needed to
                            support achievement. A learner can refuse ALS in any month not needed.<br>
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
                        @php
                            $spd_month = 'spd_month' . $i;
                            $spd_intended_topic = 'spd_intended_topic' . $i;
                            $spd_agreed_adjustment = 'spd_agreed_adjustment' . $i;
                        @endphp
                        <tr>
                            <td>
                                {{ $alsAssessment->$spd_month }}
                            </td>
                            <td>
                                {{ $alsAssessment->$spd_intended_topic }}
                            </td>
                            <td>
                                {{ $alsAssessment->$spd_agreed_adjustment }}
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
                                {{ optional($alsAssessment->$fsf_date)->format('d/m/Y') }}
                            </td>
                            <td>
                                {{ $alsAssessment->$fsf_intended_topic }}
                            </td>
                            <td>
                                {{ $alsAssessment->$fsf_agreed_adjustment }}
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
                            {{ optional($alsAssessment->fsfr_date)->format('d/m/Y') }}
                        </td>
                    </tr>
                    <tr>
                        <th>Are flexibilities being applied for?</th>
                        <td>
                            {{ $alsAssessment->fsfr_applied }}
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
                            The learner has agreed that the following reasonable adjustments are needed to
                            support achievement. A learner can refuse ALS in any month not needed.<br>
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
                        @php
                            $spfs_month = 'spfs_month' . $i;
                            $spfs_intended_topic = 'spfs_intended_topic' . $i;
                            $spfs_agreed_adjustment = 'spfs_agreed_adjustment' . $i;
                        @endphp
                        <tr>
                            <td>
                                {{ $alsAssessment->$spfs_month }}
                            </td>
                            <td>
                                {{ $alsAssessment->$spfs_intended_topic }}
                            </td>
                            <td>
                                {{ $alsAssessment->$spfs_agreed_adjustment }}
                            </td>
                        </tr>
                    @endfor
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Learner Confirmation -->
@include('trainings.als_assessment.partials.learner_confirmation_read', [
    'alsAssessment' => $alsAssessment,
])

<!-- Assessor Confirmation -->
@include('trainings.als_assessment.partials.assessor_confirmation_read', [
    'alsAssessment' => $alsAssessment,
])

<!-- FS Tutor Confirmation -->
@include('trainings.als_assessment.partials.fs_tutor_confirmation_read', [
    'alsAssessment' => $alsAssessment,
])

<!-- IQA Confirmation -->
@include('trainings.als_assessment.partials.iqa_confirmation_read', [
    'alsAssessment' => $alsAssessment,
])

<!-- ALS Tutor Confirmation -->
@include('trainings.als_assessment.partials.als_tutor_confirmation_read', [
    'alsAssessment' => $alsAssessment,
])
