
{{ Form::hidden('tr_id', $training->id) }}
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
                <td>
                    {!! Form::select('assessor_id', $assessorsList, $formData["assessor_id"] ?? $training->primary_assessor, ['class' => 'form-control', 'placeholder' => '']) !!}
                    {!! $errors->first('assessor_id', '<p class="text-danger">:message</p>') !!}
                    {{-- @if(isset($deepDive))
                    {{ optional($deepDive->primaryAssessor)->full_name }}
                    @else
                    {!! Form::select('assessor_id', $assessorsList, $formData["assessor_id"] ?? null, ['class' => 'form-control', 'placeholder' => '']) !!}
                    {!! $errors->first('assessor_id', '<p class="text-danger">:message</p>') !!}
                    @endif --}}
                </td>
            </tr>
            <tr>
                <th>Date of Deep Dive *</th>
                <td>
                    {!! Form::date('deep_dive_date', $formData["deep_dive_date"] ?? now()->format('Y-m-d'), ['class' => 'form-control', 'required']) !!}
                    {!! $errors->first('deep_dive_date', '<p class="text-danger">:message</p>') !!}
                </td>
                <th>Quality Assurance Officer</th>
                <td>
                    {!! Form::select('verifier_id', $verifiersList, $formData["verifier_id"] ?? $training->verifier, ['class' => 'form-control', 'placeholder' => '']) !!}
                    {!! $errors->first('verifier_id', '<p class="text-danger">:message</p>') !!}
                    {{-- @if(isset($deepDive))
                    {{ optional($deepDive->verifierUser)->full_name }}
                    @else
                    {!! Form::select('verifier_id', $verifiersList, $formData["verifier_id"] ?? null, ['class' => 'form-control', 'placeholder' => '']) !!}
                    {!! $errors->first('verifier_id', '<p class="text-danger">:message</p>') !!}
                    @endif --}}
                </td>
            </tr>
            <tr>
                <th>Employer</th>
                <td>
                    {{ optional($training->employer)->legal_name }}
                </td>
                <th>Operations Manager</th>
                <td>
                    {!! Form::select('ops_manager_id', $opsManagersList, $formData["ops_manager_id"] ?? null, ['class' => 'form-control', 'placeholder' => '']) !!}
                    {!! $errors->first('ops_manager_id', '<p class="text-danger">:message</p>') !!}
                </td>
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
                <td>
                    {!! Form::select('secondary_assessor_id', $assessorsList, $formData["secondary_assessor_id"] ?? $training->secondary_assessor, ['class' => 'form-control', 'placeholder' => '']) !!}
                    {!! $errors->first('secondary_assessor_id', '<p class="text-danger">:message</p>') !!}
                    {{-- @if(isset($deepDive))
                    {{ optional($deepDive->secondaryAssessor)->full_name }}
                    @else
                    {!! Form::select('secondary_assessor_id', $assessorsList, $formData["secondary_assessor_id"] ?? null, ['class' => 'form-control', 'placeholder' => '']) !!}
                    {!! $errors->first('secondary_assessor_id', '<p class="text-danger">:message</p>') !!}
                    @endif --}}
                </td>
                <th>Mentor/Employer Name</th>
                <td>
                    {!! Form::select('employer_user_id', $employerUsers, $formData["employer_user_id"] ?? $training->employer_user_id, ['class' => 'form-control', 'placeholder' => '']) !!}
                    {!! $errors->first('employer_user_id', '<p class="text-danger">:message</p>') !!}
                </td>
            </tr>
            <tr>
                <th>Target Progress</th>
                <td>{{ isset($deepDive) ? $deepDive->target_progress : $training->target_progress }}%</td>
                <th>Actual Progress</th>
                <td>{{ isset($deepDive) ? $deepDive->actual_progress : $training->signedOffPercentage() }}%</td>
            </tr>
            <tr>
                <th>Target OTJH</th>
                <td>{{ isset($deepDive) ? $deepDive->expected_otj : $otjStats['expectedOtjHours'] }}</td>
                <th>Actual OTJH</th>
                <td>{{ isset($deepDive) ? $deepDive->completed_otj : $otjStats['completedOtjHoursFormatted'] }}</td>
            </tr>
            <tr>
                <th>Overall RAG Rating</th>
                <td colspan="3">{{ isset($deepDive) ? $deepDive->overall_rag_rating : $training->overallRagRating() }}</td>
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
            are enrolled onto the correct apprenticeship programme and that the correct elements are being included to
            meet the needs of the apprentice and employer.
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
                    <label class="pos-rel"><input type="radio" class="ace" name="ia_english_opt_in" value="opt-in" {{ isset($formData["ia_english_opt_in"]) && $formData["ia_english_opt_in"] == "opt-in" ? 'checked' : '' }} /> <span
                            class="lbl"></span></label>
                </td>
                <td class="text-center">
                    <label class="pos-rel"><input type="radio" class="ace" name="ia_english_opt_in" value="opt-out" {{ isset($formData["ia_english_opt_in"]) && $formData["ia_english_opt_in"] == "opt-out" ? 'checked' : '' }} /> <span
                            class="lbl"></span></label>
                </td>
                <td>
                    {!! Form::select('ia_english_level', $gcseGradesList, $formData["ia_english_level"] ?? null, ['class' => 'form-control', 'placeholder' => '']) !!}
                    {!! $errors->first('ia_english_level', '<p class="text-danger">:message</p>') !!}
                </td>
                <td>
                    {!! Form::date('ia_english_date', $formData["ia_english_date"] ?? null, ['class' => 'form-control']) !!}
                    {!! $errors->first('ia_english_date', '<p class="text-danger">:message</p>') !!}
                </td>
                <td>
                    {!! Form::text('ia_english_outcome', $formData["ia_english_outcome"] ?? null, ['class' => 'form-control', 'placeholder' => '']) !!}
                    {!! $errors->first('ia_english_outcome', '<p class="text-danger">:message</p>') !!}
                </td>
                <td>
                    {!! Form::date('ia_english_da', $formData["ia_english_da"] ?? null, ['class' => 'form-control']) !!}
                    {!! $errors->first('ia_english_da', '<p class="text-danger">:message</p>') !!}
                </td>
                <td>
                    {!! Form::textarea('ia_english_comments', $formData["ia_english_comments"] ?? null, ['class' => 'form-control', 'placeholder' => '', 'rows' => 3]) !!}
                    {!! $errors->first('ia_english_comments', '<p class="text-danger">:message</p>') !!}
                </td>
            </tr>
            <tr>
                <th>Maths</th>
                <td class="text-center">
                    <label class="pos-rel"><input type="radio" class="ace" name="ia_maths_opt_in" value="opt-in" value="opt-in" {{ isset($formData["ia_maths_opt_in"]) && $formData["ia_maths_opt_in"] == "opt-in" ? 'checked' : '' }} /> <span
                            class="lbl"></span></label>
                </td>
                <td class="text-center">
                    <label class="pos-rel"><input type="radio" class="ace" name="ia_maths_opt_in" value="opt-out" value="opt-in" {{ isset($formData["ia_maths_opt_in"]) && $formData["ia_maths_opt_in"] == "opt-out" ? 'checked' : '' }} /> <span
                            class="lbl"></span></label>
                </td>
                <td>
                    {!! Form::select('ia_maths_level', $gcseGradesList, $formData["ia_english_level"] ?? null, ['class' => 'form-control', 'placeholder' => '']) !!}
                    {!! $errors->first('ia_maths_level', '<p class="text-danger">:message</p>') !!}
                </td>
                <td>
                    {!! Form::date('ia_maths_date', $formData["ia_maths_date"] ?? null, ['class' => 'form-control']) !!}
                    {!! $errors->first('ia_maths_date', '<p class="text-danger">:message</p>') !!}
                </td>
                <td>
                    {!! Form::text('ia_maths_outcome', $formData["ia_maths_outcome"] ?? null, ['class' => 'form-control', 'placeholder' => '']) !!}
                    {!! $errors->first('ia_maths_outcome', '<p class="text-danger">:message</p>') !!}
                </td>
                <td>
                    {!! Form::date('ia_maths_da', $formData["ia_maths_da"] ?? null, ['class' => 'form-control']) !!}
                    {!! $errors->first('ia_maths_da', '<p class="text-danger">:message</p>') !!}
                </td>
                <td>
                    {!! Form::textarea('ia_maths_comments', $formData["ia_maths_comments"] ?? null, ['class' => 'form-control', 'placeholder' => '', 'rows' => 3]) !!}
                    {!! $errors->first('ia_maths_comments', '<p class="text-danger">:message</p>') !!}
                </td>
            </tr>
            <tr>
                <th>Digital Skills</th>
                <td class="text-center"></td>
                <td class="text-center"></td>
                <td>
                    {!! Form::select('ia_digital_level', $gcseGradesList, $formData["ia_digital_level"] ?? null, ['class' => 'form-control', 'placeholder' => '']) !!}
                    {!! $errors->first('ia_digital_level', '<p class="text-danger">:message</p>') !!}
                </td>
                <td>
                    {!! Form::date('ia_digital_date', $formData["ia_digital_date"] ?? null, ['class' => 'form-control']) !!}
                    {!! $errors->first('ia_digital_date', '<p class="text-danger">:message</p>') !!}
                </td>
                <td>
                    {!! Form::text('ia_digital_outcome', $formData["ia_digital_outcome"] ?? null, ['class' => 'form-control', 'placeholder' => '']) !!}
                    {!! $errors->first('ia_digital_outcome', '<p class="text-danger">:message</p>') !!}
                </td>
                <td>
                    {!! Form::date('ia_digital_da', $formData["ia_digital_da"] ?? null, ['class' => 'form-control']) !!}
                    {!! $errors->first('ia_digital_da', '<p class="text-danger">:message</p>') !!}
                </td>
                <td>
                    {!! Form::textarea('ia_digital_comments', $formData["ia_digital_comments"] ?? null, ['class' => 'form-control', 'placeholder' => '', 'rows' => 3]) !!}
                    {!! $errors->first('ia_digital_comments', '<p class="text-danger">:message</p>') !!}
                </td>
            </tr>
            <tr>
                <th>Subject Skills Scan</th>
                <td class="text-center"></td>
                <td class="text-center"></td>
                <td>
                    {!! Form::select('ia_skills_scan_level', $gcseGradesList, $formData["ia_skills_scan_level"] ?? null, ['class' => 'form-control', 'placeholder' => '']) !!}
                    {!! $errors->first('ia_skills_scan_level', '<p class="text-danger">:message</p>') !!}
                </td>
                <td>
                    {!! Form::date('ia_skills_scan_date', $formData["ia_skills_scan_date"] ?? null, ['class' => 'form-control']) !!}
                    {!! $errors->first('ia_skills_scan_date', '<p class="text-danger">:message</p>') !!}
                </td>
                <td>
                    {!! Form::text('ia_skills_scan_outcome', $formData["ia_skills_scan_outcome"] ?? null, ['class' => 'form-control', 'placeholder' => '']) !!}
                    {!! $errors->first('ia_skills_scan_outcome', '<p class="text-danger">:message</p>') !!}
                </td>
                <td>
                    {!! Form::date('ia_skills_scan_da', $formData["ia_skills_scan_da"] ?? null, ['class' => 'form-control']) !!}
                    {!! $errors->first('ia_skills_scan_da', '<p class="text-danger">:message</p>') !!}
                </td>
                <td>
                    {!! Form::textarea('ia_skills_scan_comments', $formData["ia_skills_scan_comments"] ?? null, [
                        'class' => 'form-control',
                        'placeholder' => '',
                        'rows' => 3,
                    ]) !!}
                    {!! $errors->first('ia_skills_scan_comments', '<p class="text-danger">:message</p>') !!}
                </td>
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
                        <td>{{ $iagQV }}</td>
                        <td class="text-center">
                            <label class="pos-rel"><input type="radio" class="ace" value="Yes" {{ isset($formData["iag_q$iagQK"]) && $formData["iag_q$iagQK"] == "Yes" ? 'checked' : '' }}
                                    name="iag_q{{ $iagQK }}" /> <span class="lbl"></span></label>
                        </td>
                        <td class="text-center">
                            <label class="pos-rel"><input type="radio" class="ace" value="No" {{ isset($formData["iag_q$iagQK"]) && $formData["iag_q$iagQK"] == "No" ? 'checked' : '' }}
                                    name="iag_q{{ $iagQK }}" /> <span class="lbl"></span></label>
                        </td>
                        <td>
                            {!! Form::textarea("iag_q{$iagQK}_comments", $formData["iag_q{$iagQK}_comments"] ?? null, [
                                'class' => 'form-control',
                                'placeholder' => '',
                                'rows' => 3,
                            ]) !!}
                            {!! $errors->first("iag_q{$iagQK}_comments", '<p class="text-danger">:message</p>') !!}
                        </td>
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
            A sample of assessments/activities will be taken from across the programme to review planning and feedback
            which has been provided
            to the apprentice. Please record all elements of the programme below
        </span>
        <table class="table table-bordered">
            <col style="width: 35%" />
            <col style="width: 15%" />
            <col style="width: 15%" />
            <col style="width: 35%" />
            <thead>
                <tr>
                    <th>Programme Element</th>
                    <th>Target Progress</th>
                    <th>Actual Progress</th>
                    <th>Comments</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($training->portfolios as $portfolio)
                    <tr>
                        <td>{{ $portfolio->title }}</td>
                        <td>{{ isset($formData["pf_{$portfolio->id}_target_progress"]) ? $formData["pf_{$portfolio->id}_target_progress"] : $portfolio->target_progress }}%</td>
                        <td>{{ isset($formData["pf_{$portfolio->id}_actual_progress"]) ? $formData["pf_{$portfolio->id}_actual_progress"] : $portfolio->actual_progress }}%</td>
                        <td>
                            {!! Form::textarea("pf_{$portfolio->id}_comments", $formData["pf_{$portfolio->id}_comments"] ?? null, [
                                'class' => 'form-control',
                                'placeholder' => '',
                                'rows' => 3,
                            ]) !!}
                            {!! $errors->first("pf_{$portfolio->id}_comments", '<p class="text-danger">:message</p>') !!}
                        </td>
                        {!! Form::hidden("pf_{$portfolio->id}_target_progress", 
                            isset($formData["pf_{$portfolio->id}_target_progress"]) ? $formData["pf_{$portfolio->id}_target_progress"] : $portfolio->target_progress ) !!}
                        {!! Form::hidden("pf_{$portfolio->id}_actual_progress", 
                            isset($formData["pf_{$portfolio->id}_actual_progress"]) ? $formData["pf_{$portfolio->id}_actual_progress"] : $portfolio->actual_progress ) !!}
                    </tr>
                @endforeach
            </tbody>
        </table>

        <h3>Monthly Teaching and Learning Records</h3>
        <span class="text-info">
            A sample of session plans will be reviewed across the programme, this will be taken over a cross section of
            the programme from the
            start to the present stage of the apprentice
        </span>
        <table class="table table-bordered" id="tblIlp">
            <col style="width: 10%" />
            <col style="width: 30%" />
            <col style="width: 30%" />
            <col style="width: 30%" />
            <caption>
                <span class="btn btn-xs btn-primary btn-round btnAddRow" title="Add new row" data-target="#tblIlpBody">
                    <i class="fa fa-plus"></i>
                </span>
            </caption>
            <thead id="tblIlpHead">
                <tr>
                    <th>Date of Plan</th>
                    <th>Activities Planned</th>
                    <th>Activities Completed</th>
                    <th>Comments</th>
                </tr>
            </thead>
            <tbody id="tblIlpBody">
                @php
                    $maxTlps = collect($formData)
                        ->keys()
                        ->map(function($k) {
                            return preg_match('/^tlp_date_(\d+)$/', $k, $m) ? (int) $m[1] : null;
                        })
                        ->filter()
                        ->max();
                    $maxTlps = !is_null($maxTlps) ? $maxTlps : 1;
                @endphp
                @for($i = 1; $i <= $maxTlps; $i++)
                <tr>
                    <td>
                        {!! Form::date("tlp_date_$i", $formData["tlp_date_$i"] ?? null, ['class' => 'form-control']) !!}
                        {!! $errors->first("tlp_date_$i", '<p class="text-danger">:message</p>') !!}
                    </td>
                    <td>
                        {!! Form::textarea("tlp_activities_planned_$i", $formData["tlp_activities_planned_$i"] ?? null, [
                            'class' => 'form-control',
                            'placeholder' => '',
                            'rows' => 5,
                        ]) !!}
                        {!! $errors->first("tlp_activities_planned_$i", '<p class="text-danger">:message</p>') !!}
                    </td>
                    <td>
                        {!! Form::textarea("tlp_activities_completed_$i", $formData["tlp_activities_completed_$i"] ?? null, [
                            'class' => 'form-control',
                            'placeholder' => '',
                            'rows' => 5,
                        ]) !!}
                        {!! $errors->first("tlp_activities_completed_$i", '<p class="text-danger">:message</p>') !!}
                    </td>
                    <td>
                        {!! Form::textarea("tlp_comments_$i", $formData["tlp_comments_$i"] ?? null, ['class' => 'form-control', 'placeholder' => '', 'rows' => 5]) !!}
                        {!! $errors->first("tlp_comments_$i", '<p class="text-danger">:message</p>') !!}
                    </td>
                </tr>
                @endfor
            </tbody>
        </table>

        <h3>Reviews of Progress</h3>
        <span class="text-info">
            A review will be completed of a sample of tri-partite progress reviews, this will be taken over a cross
            section of the programme
            from the start to the present stage of the apprentice.
        </span>
        <table class="table table-bordered" id="tblReviews">
            <col style="width: 10%" />
            <col style="width: 10%" />
            <col style="width: 25%" />
            <col style="width: 25%" />
            <col style="width: 25%" />
            <caption>
                <span class="btn btn-xs btn-primary btn-round btnAddRow" title="Add new row"
                    data-target="#tblReviewsBody">
                    <i class="fa fa-plus"></i>
                </span>
            </caption>
            <thead id="tblReviewsHead">
                <tr>
                    <th>Planned Date of Review</th>
                    <th>Actual Date of Review</th>
                    <th>Comments</th>
                    <th>Feedback provided by all parties?</th>
                    <th>Signatures collected?</th>
                </tr>
            </thead>
            <tbody id="tblReviewsBody">
                @php
                    $maxReviews = collect($formData)
                        ->keys()
                        ->map(function($k) {
                            return preg_match('/^review_planned_date_(\d+)$/', $k, $m) ? (int) $m[1] : null;
                        })
                        ->filter()
                        ->max();
                    $maxReviews = !is_null($maxReviews) ? $maxReviews : 1;
                @endphp
                @for($i = 1; $i <= $maxReviews; $i++)
                <tr>
                    <td>
                        {!! Form::date("review_planned_date_$i", $formData["review_planned_date_$i"] ?? null, ['class' => 'form-control']) !!}
                        {!! $errors->first("review_planned_date_$i", '<p class="text-danger">:message</p>') !!}
                    </td>
                    <td>
                        {!! Form::date("review_actual_date_$i", $formData["review_actual_date_$i"] ?? null, ['class' => 'form-control']) !!}
                        {!! $errors->first("review_actual_date_$i", '<p class="text-danger">:message</p>') !!}
                    </td>
                    <td>
                        {!! Form::textarea("review_comments_$i", $formData["review_comments_$i"] ?? null, ['class' => 'form-control', 'placeholder' => '', 'rows' => 3]) !!}
                        {!! $errors->first("review_comments_$i", '<p class="text-danger">:message</p>') !!}
                    </td>
                    <td>
                        {!! Form::textarea("review_feedback_provided_$i", $formData["review_feedback_provided_$i"] ?? null, [
                            'class' => 'form-control',
                            'placeholder' => '',
                            'rows' => 3,
                        ]) !!}
                        {!! $errors->first("review_feedback_provided_$i", '<p class="text-danger">:message</p>') !!}
                    </td>
                    <td>
                        {!! Form::textarea("review_signs_collected_$i", $formData["review_signs_collected_$i"] ?? null, [
                            'class' => 'form-control',
                            'placeholder' => '',
                            'rows' => 3,
                        ]) !!}
                        {!! $errors->first("review_signs_collected_$i", '<p class="text-danger">:message</p>') !!}
                    </td>
                </tr>
                @endfor
            </tbody>
        </table>
    </div>
</div>

<div class="form-group row {{ $errors->has('behind_30_percent') ? 'has-error' : '' }}">
    {!! Form::label('behind_30_percent', 'Learner is more than 30% behind expected progress?', [
        'class' => 'col-sm-4 control-label',
    ]) !!}
    <div class="col-sm-8">
        {!! Form::select('behind_30_percent', ['No' => 'No', 'Yes' => 'Yes'], $formData["behind_30_percent"] ?? null, [
            'class' => 'form-control',
            'placeholder' => '',
        ]) !!}
        {!! $errors->first('behind_30_percent', '<p class="text-danger">:message</p>') !!}
    </div>
</div>
<div class="form-group row {{ $errors->has('is_action_plan_inplace') ? 'has-error' : '' }}">
    {!! Form::label('is_action_plan_inplace', 'Is there an action plan in place?', [
        'class' => 'col-sm-4 control-label',
    ]) !!}
    <div class="col-sm-8">
        {!! Form::select('is_action_plan_inplace', ['N/A' => 'N/A', 'No' => 'No', 'Yes' => 'Yes'], $formData["is_action_plan_inplace"] ?? null, [
            'class' => 'form-control',
            'placeholder' => '',
        ]) !!}
        {!! $errors->first('is_action_plan_inplace', '<p class="text-danger">:message</p>') !!}
    </div>
</div>
<div class="form-group row {{ $errors->has('action_plan_comments') ? 'has-error' : '' }}">
    {!! Form::label('action_plan_comments', 'Action Plan Comments', ['class' => 'col-sm-4 control-label']) !!}
    <div class="col-sm-8">
        {!! Form::textarea('action_plan_comments', $formData["action_plan_comments"] ?? null, ['class' => 'form-control', 'placeholder' => '', 'rows' => 3]) !!}
        {!! $errors->first('action_plan_comments', '<p class="text-danger">:message</p>') !!}
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
                <td>{{ isset($deepDive) ? $deepDive->expected_otj : $otjStats['expectedOtjHours'] }}</td>
                <th>Total Hours Recorded</td>
                <td>{{ isset($deepDive) ? $deepDive->completed_otj : $otjStats['completedOtjHoursFormatted'] }}</td>
            </tr>
        </table>
        <table class="table table-bordered" id="tblOTJT">
            <col style="width: 25%" />
            <col style="width: 25%" />
            <col style="width: 25%" />
            <caption>
                <span class="btn btn-xs btn-primary btn-round btnAddRow" title="Add new row"
                    data-target="#tblOTJTBody">
                    <i class="fa fa-plus"></i>
                </span>
            </caption>
            <thead id="tblOTJTHead">
                <tr>
                    <th>Activities towards Standard</th>
                    <th>Reasonable Hours Recorded</th>
                    <th>New Learning towards KSBs evidence</th>
                    <th>Comments</th>
                </tr>
            </thead>
            <tbody id="tblOTJTBody">
                @php
                    $maxOtjs = collect($formData)
                        ->keys()
                        ->map(function($k) {
                            return preg_match('/^otj_activity_(\d+)$/', $k, $m) ? (int) $m[1] : null;
                        })
                        ->filter()
                        ->max();
                    $maxOtjs = !is_null($maxOtjs) ? $maxOtjs : 1;
                @endphp
                @for($i = 1; $i <= $maxOtjs; $i++)
                <tr>
                    <td>
                        {!! Form::textarea("otj_activity_$i", $formData["otj_activity_$i"] ?? null, ['class' => 'form-control', 'placeholder' => '', 'rows' => 3]) !!}
                        {!! $errors->first("otj_activity_$i", '<p class="text-danger">:message</p>') !!}
                    </td>
                    <td>
                        {!! Form::textarea("otj_recorded_hours_$i", $formData["otj_recorded_hours_$i"] ?? null, ['class' => 'form-control', 'placeholder' => '', 'rows' => 3]) !!}
                        {!! $errors->first("otj_recorded_hours_$i", '<p class="text-danger">:message</p>') !!}
                    </td>
                    <td>
                        {!! Form::textarea("otj_ksb_learning_$i", $formData["otj_ksb_learning_$i"] ?? null, ['class' => 'form-control', 'placeholder' => '', 'rows' => 3]) !!}
                        {!! $errors->first("otj_ksb_learning_$i", '<p class="text-danger">:message</p>') !!}
                    </td>
                    <td>
                        {!! Form::textarea("otj_comments_$i", $formData["otj_comments_$i"] ?? null, ['class' => 'form-control', 'placeholder' => '', 'rows' => 3]) !!}
                        {!! $errors->first("otj_comments_$i", '<p class="text-danger">:message</p>') !!}
                    </td>
                </tr>
                @endfor
            </tbody>
        </table>
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <table class="table table-bordered">
            <tr>
                <th colspan="2">
                    If opted in for functional skills, is there evidence of learning and reflection and has OTJ been
                    de-selected in learning log for these entries
                    (a minimum of 1 x reflection per month required in the Learning Log)?
                </th>
                <td colspan="2">
                    {!! Form::textarea('otj_evidence_of_learning_and_reflection', $formData["otj_evidence_of_learning_and_reflection"] ?? null, [
                        'class' => 'form-control',
                        'placeholder' => '',
                        'rows' => 3,
                    ]) !!}
                    {!! $errors->first('otj_evidence_of_learning_and_reflection', '<p class="text-danger">:message</p>') !!}
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
                <th class="text-right">Was a Learner interview completed?</th>
                <td class="text-center">
                    <label class="pos-rel"><input type="radio" class="ace" name="learner_intv_comp" value="Yes" {{ isset($formData["learner_intv_comp"]) && $formData["learner_intv_comp"] == "Yes" ? 'checked' : ''  }} /> <span
                            class="lbl"></span></label> Yes
                </td>
                <td class="text-center">
                    <label class="pos-rel"><input type="radio" class="ace" name="learner_intv_comp" value="No" {{ isset($formData["learner_intv_comp"]) && $formData["learner_intv_comp"] == "No" ? 'checked' : ''  }} /> <span
                            class="lbl"></span></label> No
                </td>
            </tr>
            <tr>
                <th colspan="3">Please record outcomes of Learner and provide the interview form with the form.</th>
                <td colspan="3">
                    {!! Form::textarea('learner_interview_outcome', $formData["learner_interview_outcome"] ?? null, [
                        'class' => 'form-control',
                        'placeholder' => '',
                        'rows' => 5,
                    ]) !!}
                    {!! $errors->first('learner_interview_outcome', '<p class="text-danger">:message</p>') !!}
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
                <th class="text-right">Was an employer interview completed?</th>
                <td class="text-center">
                    <label class="pos-rel"><input type="radio" class="ace" name="employer_intv_comp" value="Yes" {{ isset($formData["employer_intv_comp"]) && $formData["employer_intv_comp"] == "Yes" ? 'checked' : ''  }} /> <span
                            class="lbl"></span></label> Yes
                </td>
                <td class="text-center">
                    <label class="pos-rel"><input type="radio" class="ace" name="employer_intv_comp" value="No" {{ isset($formData["employer_intv_comp"]) && $formData["employer_intv_comp"] == "No" ? 'checked' : ''  }} /> <span
                            class="lbl"></span></label> No
                </td>
            </tr>
            <tr>
                <th colspan="3">Please record outcomes of Learner and provide the interview form with the form.</th>
                <td colspan="3">
                    {!! Form::textarea('emplpoyer_interview_outcome', $formData["emplpoyer_interview_outcome"] ?? null, [
                        'class' => 'form-control',
                        'placeholder' => '',
                        'rows' => 5,
                    ]) !!}
                    {!! $errors->first('emplpoyer_interview_outcome', '<p class="text-danger">:message</p>') !!}
                </td>
            </tr>
        </table>

    </div>
</div>

<div class="row">
    <div class="col-xs-12">
        <h2>SECTION FOUR: Feedback, Action Plan and Review</h2>
        <span class="text-info">
            Note: If an OTLA has been completed in the previous 6 months to date, please record this in the general
            comments section and make reference to the OTLA document completed
            (it is not necessary to list all the actions and areas of good practice/development from the OTLA).
        </span>
        <table class="table table-bordered">
            <tr>
                <th>Date of feedback Session</th>
                <td>
                    {!! Form::date('feedback_date', $formData["feedback_date"] ?? null, ['class' => 'form-control']) !!}
                    {!! $errors->first('feedback_date', '<p class="text-danger">:message</p>') !!}
                </td>
                <th>Attended by</th>
                <td>
                    {!! Form::textarea('feedback_comments', $formData["feedback_comments"] ?? null, ['class' => 'form-control', 'placeholder' => '', 'rows' => 5]) !!}
                    {!! $errors->first('feedback_comments', '<p class="text-danger">:message</p>') !!}
                </td>
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
        <span class="text-info">All agreed actions will be discussed and reviewed at programme team meetings</span>

        <table class="table table-bordered" id="tblAD">
            <col style="width: 60%" />
            <col style="width: 20%" />
            <col style="width: 20%" />
            <caption>
                <span class="btn btn-xs btn-primary btn-round btnAddRow" title="Add new row"
                    data-target="#tblADBody">
                    <i class="fa fa-plus"></i>
                </span>
            </caption>
            <thead id="tblADHead">
                <tr>
                    <th>Action/Development Required and impact of action</th>
                    <th>Who</th>
                    <th>Review Date</th>
                </tr>
            </thead>
            <tbody id="tblADBody">
                @php
                    $maxADs = collect($formData)
                        ->keys()
                        ->map(function($k) {
                            return preg_match('/^action_and_develop_(\d+)$/', $k, $m) ? (int) $m[1] : null;
                        })
                        ->filter()
                        ->max();
                    $maxADs = !is_null($maxADs) ? $maxADs : 1;
                @endphp
                @for($i = 1; $i <= $maxADs; $i++)
                <tr>
                    <td>
                        {!! Form::textarea("action_and_develop_$i", $formData["action_and_develop_$i"] ?? null, ['class' => 'form-control', 'placeholder' => '', 'rows' => 3]) !!}
                        {!! $errors->first("action_and_develop_$i", '<p class="text-danger">:message</p>') !!}
                    </td>
                    <td>
                        {!! Form::text("action_and_develop_by_who_$i", $formData["action_and_develop_by_who_$i"] ?? null, ['class' => 'form-control', 'placeholder' => '']) !!}
                        {!! $errors->first("action_and_develop_by_who_$i", '<p class="text-danger">:message</p>') !!}
                    </td>
                    <td>
                        {!! Form::date("action_and_develop_review_date_$i", $formData["action_and_develop_review_date_$i"] ?? null, ['class' => 'form-control']) !!}
                        {!! $errors->first("action_and_develop_review_date_$i", '<p class="text-danger">:message</p>') !!}
                    </td>
                </tr>
                @endfor
            </tbody>
        </table>
    </div>
</div>

<div class="row">
    <div class="col-xs-12">
        <h3>Review</h3>
        <table class="table tabl-bordered">
            <tr>
                <th class="text-right">Date of Review</th>
                <td colspan="2">
                    {!! Form::date('date_of_review', $formData["date_of_review"] ?? null, ['class' => 'form-control']) !!}
                    {!! $errors->first('date_of_review', '<p class="text-danger">:message</p>') !!}
                </td>
            <tr>
                <th class="text-right">All Actions Completed</th>
                <td class="text-center">
                    <label class="pos-rel"><input type="radio" class="ace" name="all_actions_comp" value="Yes" {{ isset($formData["all_actions_comp"]) && $formData["all_actions_comp"] == "Yes" ? 'checked' : ''  }} /> <span
                            class="lbl"></span></label> Yes
                </td>
                <td class="text-center">
                    <label class="pos-rel"><input type="radio" class="ace" name="all_actions_comp" value="No" {{ isset($formData["all_actions_comp"]) && $formData["all_actions_comp"] == "No" ? 'checked' : ''  }} /> <span
                            class="lbl"></span></label> No
                </td>
            </tr>
            <tr>
                <th colspan="3">Further Action Required, if any</th>
                <td colspan="3">
                    {!! Form::textarea('further_action', $formData["further_action"] ?? null, ['class' => 'form-control', 'placeholder' => '', 'rows' => 5]) !!}
                    {!! $errors->first('further_action', '<p class="text-danger">:message</p>') !!}
                </td>
            </tr>
        </table>

    </div>
</div>

@push('after-scripts')
<script>
    $(document).ready(function() {
        $(".btnAddRow").click(function() {
            let target = $($(this).data("target")); // tbody selector
            let rowCount = target.find("tr").length + 1;

            // clone last row
            let newRow = target.find("tr:last").clone();

            // clear values in inputs, textareas, selects
            newRow.find("input, textarea").val("");
            newRow.find("select").prop("selectedIndex", 0);

            // update name attributes with rowCount
            newRow.find("input, textarea, select").each(function() {
                let name = $(this).attr("name");
                if (name) {
                    let newName = name.replace(/_\d+$/, "_" + rowCount);
                    $(this).attr("name", newName);
                }
            });

            target.append(newRow);
        });
    });
</script>
@endpush