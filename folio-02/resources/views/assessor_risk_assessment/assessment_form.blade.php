@extends('layouts.master')

@section('title', 'Assessor Risk Assessment Form')

@section('page-content')
    <div class="page-header">
        <h1>
            Assessor Risk Assessment Form
        </h1>
    </div><!-- /.page-header -->
    <div class="row">
        <div class="col-xs-12">
            <!-- PAGE CONTENT BEGINS -->
            <button class="btn btn-sm btn-white btn-default btn-round" type="button"
                onclick="window.location.href='{{ route('assessor_risk_assessment.show', $riskAssessment) }}'">
                <i class="ace-icon fa fa-times bigger-110"></i> Close
            </button>

            <div class="hr hr-12 hr-dotted"></div>

            @include('partials.session_message')

            @include('partials.session_error')

            <div class="row">
                {!! Form::model($riskAssessment, [
                    'method' => 'PATCH',
                    'url' => route('assessor_risk_assessment.saveForm', $riskAssessment),
                    'class' => 'form-horizontal',
                    'role' => 'form',
                    'name' => 'frmRiskAssessment',
                    'id' => 'frmRiskAssessment',
                ]) !!}
                <div class="col-xs-12">
                    <div class="widget-box transparent">                        
                        <div class="widget-body">
                            <div class="widget-main table-responsive">
                                <table class="table table-bordered">
                                    <col width="50%">
                                    <col width="50%">
                                    <tr>
                                        <th class="bg-success text-right">Assessor</th>
                                        <td>
                                            {{ $riskAssessment->assessor->full_name ?? '' }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="bg-success text-right">Date of Rating</th>
                                        <td>
                                            {{ $riskAssessment->date_of_observation ? $riskAssessment->date_of_observation->format('d/m/Y') : '' }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="bg-success text-right">Date of Last Observation</th>
                                        <td>
                                            {{ $riskAssessment->date_of_last_observation ? $riskAssessment->date_of_last_observation->format('d/m/Y') : '' }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <th class="bg-success text-right">Risk</th>
                                        <td>
                                            Risk
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Grade</th>
                                    <th>1 (10%)</th>
                                    <th>2 (30%)</th>
                                    <th>3 (60%)</th>
                                    <th>4 (100%)</th>
                                    <th style="width: 12%">Grade awarded</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th class="bg-success">1. Qualified/Experienced Assessor</th>
                                    <td>
                                        <ul>
                                            <li>Qualified - has been assessing for 6+ months.</li>
                                            <li>No identified support requirements.</li>
                                        </ul>
                                    </td>
                                    <td>
                                        <ul>
                                            <li>Qualified - has been assessing for 6+ months.</li>
                                            <li>Some identified support requirements.</li>
                                        </ul>
                                    </td>
                                    <td>
                                        <ul>
                                            <li>Qualified - has NOT been assessing for 6+months. </li>
                                            <li>Qualified - not active within the last 12 months.</li>
                                        </ul>
                                    </td>
                                    <td>
                                        <ul>
                                            <li>Unqualified - receiving second line assessment.</li>
                                        </ul>
                                    </td>
                                    <td>
                                        {!! Form::select('grade_1', $grades, $form['grade_1'] ?? null, ['class' => 'form-control gradesSelect']) !!}
                                    </td>
                                </tr>
                                <tr>
                                    <th class="bg-success">2. Standardisation of assessment practices and CPD</th>
                                    <td>
                                        <ul>
                                            <li>100% attendance to Standardisation meetings </li>
                                            <li>All annual mandatory CPD completed</li>
                                            <li>Additional CPD covering vocational, occupational and TLA hours</li>
                                            <li>Consistently records CPD and is fully completed </li>
                                            <li>Relevant skills upgraded</li>
                                        </ul>
                                    </td>
                                    <td>
                                        <ul>
                                            <li>75% attendance to Standardisation meetings</li>
                                            <li>Mandatory CPD completed and recorded</li>
                                            <li>No additional CPD completed</li>
                                            <li>CPD is Regularly updated</li>
                                            <li>All elements of the CPD record are fully completed and up to date</li>
                                        </ul>
                                    </td>
                                    <td>
                                        <ul>
                                            <li>50% attendance to Standardisation meetings</li>
                                            <li>Mandatory CPD part completed </li>
                                            <li>Irregular CPD entries</li>
                                            <li>CPD record up to date</li>
                                        </ul>
                                    </td>
                                    <td>
                                        <ul>
                                            <li>25% attendance to Standardisation meetings</li>
                                            <li>No CPD recorded</li>
                                            <li>New starter</li>
                                        </ul>
                                    </td>
                                    <td>
                                        {!! Form::select('grade_2', $grades, $form['grade_2'] ?? null, ['class' => 'form-control gradesSelect']) !!}
                                    </td>
                                </tr>
                                <tr>
                                    <th class="bg-success">3. Planning of assessment activities</th>
                                    <td>
                                        <ul>
                                            <li>Is consistently meeting the AO required qualification specification. </li>
                                            <li>Detailed planning, providing good audit trail.</li>
                                            <li>Consistently shows the ability to differentiate according to learner style
                                                and learner additional support needs.</li>
                                            <li> Consistently takes enrichment opportunities into consideration. </li>
                                            <li>Consistent evidence of holistic planning for formative and summative
                                                assessment methods required by the AO.</li>
                                        </ul>
                                    </td>
                                    <td>
                                        <ul>
                                            <li>Is consistently meeting the AO required qualification specification. </li>
                                            <li>Detailed planning, providing good audit trail.</li>
                                            <li>Consistently shows the ability to differentiate according to learner style
                                                and learner additional support needs.</li>
                                            <li> Consistently takes enrichment opportunities into consideration. </li>
                                            <li>Consistent evidence of holistic planning for formative and summative
                                                assessment methods required by the AO.</li>
                                        </ul>
                                    </td>
                                    <td>
                                        <ul>
                                            <li>Is consistently meeting the AO required qualification specification. </li>
                                            <li>Detailed planning, providing good audit trail.</li>
                                            <li>Consistently shows the ability to differentiate according to learner style
                                                and learner additional support needs.</li>
                                            <li> Consistently takes enrichment opportunities into consideration. </li>
                                            <li>Consistent evidence of holistic planning for formative and summative
                                                assessment methods required by the AO.</li>
                                        </ul>
                                    </td>
                                    <td>
                                        <ul>
                                            <li>Is consistently meeting the AO required qualification specification. </li>
                                            <li>Detailed planning, providing good audit trail.</li>
                                            <li>Consistently shows the ability to differentiate according to learner style
                                                and learner additional support needs.</li>
                                            <li> Consistently takes enrichment opportunities into consideration. </li>
                                            <li>Consistent evidence of holistic planning for formative and summative
                                                assessment methods required by the AO.</li>
                                        </ul>
                                    </td>
                                    <td>
                                        {!! Form::select('grade_3', $grades, $form['grade_3'] ?? null, ['class' => 'form-control gradesSelect']) !!}
                                    </td>
                                </tr>
                                <tr>
                                    <th class="bg-success">4.Assessment practices</th>
                                    <td>
                                        <ul>
                                            <li>Assessment of evidence is consistently adhering to ELA's 2-week turnaround
                                                policy.</li>
                                            <li>Evidence of the impact of teaching is consistently seen</li>
                                            <li>Learners being stretched and challenged through feedback and assessments is
                                                consistent</li>
                                            <li>Evidence of embedding core skills and social responsibilities is consistent
                                            </li>
                                            <li>Consistent use of 3 Is demonstrating learner-centered feedback</li>
                                            <li>Consistent use of a variety of assessment methods carried out</li>
                                        </ul>
                                    </td>
                                    <td>
                                        <ul>
                                            <li>Assessment of evidence is regular, adhering to ELA's 2-week turnaround
                                                policy.</li>
                                            <li>Evidence of the impact of teaching is regularly seen</li>
                                            <li>Evidence that learners have been stretched and challenged through feedback
                                                and assessments is regularly seen.</li>
                                            <li>Evidence of embedding core skills and social responsibilities is regularly
                                                seen </li>
                                            <li>Regular use of 3 I's demonstrating learner-centered feedback</li>
                                            <li>Regular use of a variety of assessment methods carried out</li>
                                        </ul>
                                    </td>
                                    <td>
                                        <ul>
                                            <li>Assessment of evidence is irregular, non-adherence to ELA's 2-week
                                                turnaround policy.</li>
                                            <li>Evidence of the impact of teaching is irregular</li>
                                            <li>Evidence that learners have been stretched and challenged through feedback
                                                and assessments is irregular.</li>
                                            <li>Evidence of embedding core skills and social responsibilities is irregular
                                            </li>
                                            <li>Evidence of learner-centered feedback, based around the 3 I's is irregular
                                            </li>
                                            <li>Irregular use of a variety of assessment methods carried out</li>
                                        </ul>
                                    </td>
                                    <td>
                                        <ul>
                                            <li>Assessment of evidence is not adhering to ELA's 2-week turnaround policy.
                                            </li>
                                            <li>Evidence of the impact of teaching is not seen</li>
                                            <li>Evidence that learners have been stretched and challenged through feedback
                                                and assessments is not seen.</li>
                                            <li>Evidence of embedding core skills and social responsibilities is not seen
                                            </li>
                                            <li>Evidence of learner-centered feedback, based around the 3 I's is not seen
                                            </li>
                                            <li>Variations to assessment methods are not seen</li>
                                        </ul>
                                    </td>
                                    <td>
                                        {!! Form::select('grade_4', $grades, $form['grade_4'] ?? null, ['class' => 'form-control gradesSelect']) !!}
                                    </td>
                                </tr>
                                <tr>
                                    <th class="bg-success">5. IQA requirements</th>
                                    <td>
                                        <ul>
                                            <li>Fully consistent with no pending TLAPs/tasks</li>
                                            <li>No reviews exceeding 8 weeks </li>
                                            <li>No submission (feedback) more than 2 weeks</li>
                                        </ul>
                                    </td>
                                    <td>
                                        <ul>
                                            <li>No more than 10% learners pending TLAPs/tasks </li>
                                            <li>Less than 10% outstanding 10 week reviews</li>
                                            <li>Less than 10% outstanding submissions (feedback) more than 2 weeks</li>
                                        </ul>
                                    </td>
                                    <td>
                                        <ul>
                                            <li>More than 10% learners pending TLAPs/tasks </li>
                                            <li>More than 10% outstanding 8 week reviews</li>
                                            <li>More than10% outstanding submissions (feedback) more than 2 weeks</li>
                                        </ul>
                                    </td>
                                    <td>
                                        <ul>
                                            <li>As for 60% with no improvements after 2 months</li>
                                            <li>New starter</li>
                                        </ul>
                                    </td>
                                    <td>
                                        {!! Form::select('grade_5', $grades, $form['grade_5'] ?? null, ['class' => 'form-control gradesSelect']) !!}
                                    </td>
                                </tr>
                                <tr>
                                    <th class="bg-success">6. Action points</th>
                                    <td>
                                        <ul>
                                            <li>Consistently few action points on Folio</li>
                                            <li>No assessment decisions overturned </li>
                                            <li>No actions from audits</li>
                                            <li>Unit summaries by assessor and learner are consistently signed off timely
                                            </li>
                                        </ul>
                                    </td>
                                    <td>
                                        <ul>
                                            <li>Irregular action points on Folio</li>
                                            <li>Decisions overturned are irregular </li>
                                            <li>1-2 actions from audits</li>
                                            <li> Unit summaries by assessor and learner are regularly signed off timely</li>
                                        </ul>
                                    </td>
                                    <td>
                                        <ul>
                                            <li>Regular or reoccurring action points on Folio.</li>
                                            <li>Regular decisions overturned </li>
                                            <li>3 or more actions from audits</li>
                                        </ul>
                                    </td>
                                    <td>
                                        <ul>
                                            <li>Regular and reoccurring action points on Folio.</li>
                                            <li>Consistent decisions overturned </li>
                                            <li>No improvement of actions from audits</li>
                                            <li>New starter</li>
                                        </ul>
                                    </td>
                                    <td>
                                        {!! Form::select('grade_6', $grades, $form['grade_6'] ?? null, ['class' => 'form-control gradesSelect']) !!}
                                    </td>
                                </tr>
                                <tr>
                                    <th class="bg-success">7. Managing learner progress</th>
                                    <td>
                                        <ul>
                                            <li>Completes learners timely.</li>
                                            <li>80% of caseload is on or ahead of target.</li>
                                        </ul>
                                    </td>
                                    <td>
                                        <ul>
                                            <li>Completes learners one month over EED.</li>
                                            <li>60% of caseload is on target</li>
                                        </ul>
                                    </td>
                                    <td>
                                        <ul>
                                            <li>Completes learners more than one month over EED.</li>
                                            <li>40% of caseload is on target</li>
                                        </ul>
                                    </td>
                                    <td>
                                        <ul>
                                            <li>New employee </li>
                                            <li>Less than 40% of caseload on target</li>
                                        </ul>
                                    </td>
                                    <td>
                                        {!! Form::select('grade_7', $grades, $form['grade_7'] ?? null, ['class' => 'form-control gradesSelect']) !!}
                                    </td>
                                </tr>
                                <tr>
                                    <th class="bg-success">8. Learner reviews</th>
                                    <td>
                                        <ul>
                                            <li>Reviews are consistently timely at 8-10 weeks -Reviews are informative (all
                                                areas have been covered in detail inc. evidence of employer feedback) </li>
                                            <li>Identifies consistent progression between review</li>
                                        </ul>
                                    </td>
                                    <td>
                                        <ul>
                                            <li>Reviews are regularly timely at 8-12 weeks </li>
                                            <li>Reviews are informative (some areas have been covered in detail inc.
                                                evidence of employer feedback) </li>
                                            <li>Regular progression between reviews</li>
                                        </ul>
                                    </td>
                                    <td>
                                        <ul>
                                            <li>Reviews are irregular and failing the contractual requirements of 12 weeks
                                            </li>
                                            <li>Reviews are basic in detail </li>
                                            <li>No evidence of employer feedback </li>
                                            <li>Identifies minimal progression between reviews</li>
                                            <li>No amendments to CANO planner</li>
                                        </ul>
                                    </td>
                                    <td>
                                        <ul>
                                            <li>Reviews are consistently falling behind contractual requirements or not
                                                taking place at all</li>
                                            <li> No amendments to CANO planner</li>
                                            <li>Learner not being seen beyond 8 weeks</li>
                                        </ul>
                                    </td>
                                    <td>
                                        {!! Form::select('grade_8', $grades, $form['grade_8'] ?? null, ['class' => 'form-control gradesSelect']) !!}
                                    </td>
                                </tr>
                                <tr>
                                    <th class="bg-success">9. Stakeholder complaints/concerns</th>
                                    <td>
                                        <ul>
                                            <li>No concerns/complaints</li>
                                        </ul>
                                    </td>
                                    <td>
                                        <ul>
                                            <li>1-2 concerns/complaints</li>
                                        </ul>
                                    </td>
                                    <td>
                                        <ul>
                                            <li>More than 2 concerns/complaints</li>
                                        </ul>
                                    </td>
                                    <td>
                                        <ul>
                                            <li>New employee</li>
                                        </ul>
                                    </td>
                                    <td>
                                        {!! Form::select('grade_9', $grades, $form['grade_9'] ?? null, ['class' => 'form-control gradesSelect']) !!}
                                    </td>
                                </tr>
                                <tr>
                                    <th class="bg-success">10. OTLA Grading</th>
                                    <td>
                                        <ul>
                                            <li>Grade 1 - Exceptional Teaching and Learning Practice. Consistently above and
                                                beyond requirements</li>
                                        </ul>
                                    </td>
                                    <td>
                                        <ul>
                                            <li>Grade 2 - Good quality of Teaching and Learning Practice. Has some
                                                exceptional practice</li>
                                        </ul>
                                    </td>
                                    <td>
                                        <ul>
                                            <li>Grade 3 - Sufficient Teaching and Learning Practice. Requires areas of
                                                improvement</li>
                                        </ul>
                                    </td>
                                    <td>
                                        <ul>
                                            <li>Grade 4 - Insufficient Teaching and Learning Practice. Considered as
                                                ineffective</li>
                                        </ul>
                                    </td>
                                    <td>
                                        {!! Form::select('grade_10', $grades, $form['grade_10'] ?? null, ['class' => 'form-control gradesSelect']) !!}
                                    </td>
                                </tr>
                                <tr>
                                    <td class="bg-success text-center" colspan="6">
                                        <span class="bolder text-info">Total Score: </span><span class="bolder"
                                            id="overallScore">{{ $riskAssessment->total_score ?? '' }}</span><br>
                                        <span class="bolder text-info">Overall Risk Grade: </span><span class="bolder"
                                            id="overallRiskGrade">
                                            @if(isset($riskAssessment->overall_grade) && !is_null($riskAssessment->overall_grade))
                                            {{ $riskAssessment->overall_grade }} ({{ $riskAssessment->overallGradePercentage() }})
                                            @endif
                                        </span><br>
                                    </td>
                                </tr>
                                
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-xs-12">
                    <div class="widget-box transparent">
                        <div class="widget-body">
                            <div class="widget-main">
                                <div class="form-group row {{ $errors->has('comments') ? 'has-error' : '' }}">
                                    {!! Form::label('comments', 'Comments', ['class' => 'col-sm-4 control-label']) !!}
                                    <div class="col-sm-8">
                                        {!! Form::textarea('comments', $form['comments'] ?? null, [
                                            'class' => 'form-control',
                                            'rows' => '3',
                                            'id' => 'comments',
                                        ]) !!}
                                        {!! $errors->first('comments', '<p class="text-danger">:message</p>') !!}
                                    </div>
                                </div>
                                <div class="form-group row {{ $errors->has('completed') ? 'has-error' : '' }}">
                                    {!! Form::label('completed', 'Assessment Complete', ['class' => 'col-sm-4 control-label']) !!}
                                    <div class="col-sm-8">
                                        {!! Form::select('completed', ['0' => 'No', 1 => 'Yes'], $riskAssessment->overall_grade ?? null, ['class' => 'form-control']) !!}                                    
                                        {!! $errors->first('comments', '<p class="text-danger">:message</p>') !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-xs-12">
                    <div class="center">
                        <button class="btn btn-sm btn-success btn-round" type="submit">
                            <i class="ace-icon fa fa-save bigger-110"></i>Save Information
                        </button>
                    </div>
                </div>
                {!! Form::close() !!}
            </div>
        </div><!-- /.col -->
    </div><!-- /.row -->
@endsection

@push('after-scripts')
    <script>
        $('.gradesSelect').on('change', function() {
            updateGrade();
        });

        function updateGrade()
        {
            var totalScore = 0;
            var grades = $('.gradesSelect');

            grades.each(function() {
                var gradeValue = parseInt($(this).val());
                if (!isNaN(gradeValue)) {
                    totalScore += gradeValue;
                }
            });

            $('#overallScore').text(totalScore);

            var overallRiskGrade = '';
            if (totalScore <= 10) {
                overallRiskGrade = '1 (20%)';
            } else if (totalScore <= 20) {
                overallRiskGrade = '2 (30%)';
            } else if (totalScore <= 30) {
                overallRiskGrade = '3 (50%)';
            } else if (totalScore <= 40) {
                overallRiskGrade = '4 (100%)';
            } else {
                overallRiskGrade = '';
            }

            $('#overallRiskGrade').text(overallRiskGrade);            
        }

        $(document).ready(function() {
            updateGrade();
        });
    </script>
@endpush
