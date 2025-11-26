<?php
/* @var $ob_learner OnboardingLearner  */
/* @var $tr TrainingRecord */
/* @var $skills_analysis SkillsAnalysis */

$planned_reviews_start_date = $tr->practical_period_start_date;
$planned_reviews_end_date = $tr->practical_period_end_date;

?>

<p><br></p>
<div class="col-sm-12">
    <table class="table table-bordered">
        <col width="40%" />
        <col width="60%" />
        <tr>
            <th class="text-bold">Learner Name</th>
            <td><?php echo $ob_learner->firstnames . ' ' . $ob_learner->surname; ?></td>
        </tr>
        <tr>
            <th class="text-bold">Programme Title</th>
            <td><?php echo $framework->title; ?></td>
        </tr>
        <tr>
            <th class="text-bold">Programme Level</th>
            <td><?php echo $framework->framework_type; ?></td>
        </tr>
        <tr>
            <th class="text-bold">Contracted Hours per week</th>
            <td><?php echo $tr->contracted_hours_per_week; ?></td>
        </tr>
    </table>
</div>

<div class="row">
    <div class="col-sm-12">
        <div class="table-responsive" style="font-size: medium;">
            <table class="table table-bordered">
                <tr class="bg-light-blue">
                    <th>Training to be delivered</th>
                    <th>Exempt</th>
                    <th>Level</th>
                    <th>Details</th>
                    <th>Start Date</th>
                    <th>Planned End Date</th>
                    <th>Number of months</th>
                </tr>
                <?php
                $ob_quals_sql = <<<SQL
SELECT 
    ob_learner_quals.*,
    framework_qualifications.level,
    framework_qualifications.qualification_type,
    TIMESTAMPDIFF(MONTH, qual_start_date, qual_end_date) AS no_of_months,
    framework_qualifications.`main_aim`
FROM
    ob_learner_quals
    LEFT JOIN framework_qualifications ON REPLACE(ob_learner_quals.qual_id, '/', '') = REPLACE(framework_qualifications.id, '/', '') 
WHERE
    ob_learner_quals.tr_id = '{$tr->id}' AND 
    framework_qualifications.framework_id = '{$tr->framework_id}'   
    ORDER BY framework_qualifications.sequence
SQL;
                $ob_quals = DAO::getResultset($link, $ob_quals_sql, DAO::FETCH_ASSOC);
                foreach($ob_quals AS $qual)
                {
                    echo $qual['qual_exempt'] == 1 ? '<tr class="disabledRow">' : '<tr>';
                    echo '<td>' . $qual['qual_id'] .  ': ' . $qual['qual_title'] . '</td>';
                    echo $qual['qual_exempt'] == 1 ? '<td>Yes</td>' : '<td></td>';
                    echo '<td>' . $qual['level'] . '</td>';
                    echo '<td>' . DAO::getSingleValue($link, "SELECT description FROM lookup_qual_type WHERE id = '{$qual['qualification_type']}'") . '</td>';
                    echo '<td>' . Date::toShort($qual['qual_start_date']) . '</td>';
                    echo '<td>' . Date::toShort($qual['qual_end_date']) . '</td>';
                    echo '<td>' . $qual['no_of_months'] . ' months</td>';
                    if($qual['main_aim'] == 1)
                    {
                        $planned_reviews_start_date = $qual['qual_start_date'];
                        $planned_reviews_end_date = $qual['qual_end_date'];
                    }
                    echo '</tr>';
                }
                ?>
            </table>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <h4 class="text-bold">Planned Reviews - (main provider, employer, apprentice must be present)</h4>
        <p>The first review should take place at week <?php echo $framework->first_review; ?>, and all other reviews every <?php echo $framework->review_frequency; ?> weeks.</p>
        <p>Reviews should discuss progress to date against the training plan and the immediate next steps required.</p>

        <div class="table-responsive" style="font-size: medium;">
            <table class="table table-bordered">
                <caption class="text-bold">
                    Planned Reviews<br>
                    Start Date: <?php echo Date::toShort($tr->practical_period_start_date); ?><br>
                    End Date: <?php echo Date::toShort($tr->practical_period_end_date); ?>
                </caption>
                <thead>
                <tr>
                    <td>Review No.</td>
                    <td>Review Date</td>
                </tr>
                </thead>
                <tbody>
                <?php
                $first_review_days = $framework->first_review != '' ? intval($framework->first_review)*7 : 28;
                $subsequent_review_days = $framework->review_frequency != '' ? intval($framework->review_frequency)*7 : 96;
                $_review_dates = OnboardingHelper::getReviewsDates($planned_reviews_start_date, $planned_reviews_end_date, $first_review_days, $subsequent_review_days);
                foreach($_review_dates AS $_review_number => $_review_date)
                {
                    echo "<tr><td>{$_review_number}</td><td>{$_review_date}</td></tr>";
                }
                ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <?php if($framework->fund_model_extra == Framework::FUNDING_STREAM_LEARNER_LOAN){ ?>
            <div class="form-group">
                <label for="commercial_fee" class="col-sm-3 control-label fieldLabel_optional">Commercial Fee:</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" value="<?php echo $tr->commercial_fee; ?>" name="commercial_fee" id="commercial_fee" onkeypress="return numbersonlywithpoint();" maxlength="8" />
                </div>
            </div>
            <div class="form-group">
                <label for="commercial_fee_emp_cont" class="col-sm-3 control-label fieldLabel_optional">Employer paying any part of fee:</label>
                <div class="col-sm-9">
                    <?php echo HTML::selectChosen('commercial_fee_emp_cont', [['Yes', 'Yes'], ['No', 'No']], $tr->commercial_fee_emp_cont, true); ?>
                </div>
            </div>
            <div class="form-group">
                <label for="all_amount" class="col-sm-3 control-label fieldLabel_optional">Advanced Learner Loan Amount:</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" value="<?php echo $tr->all_amount; ?>" name="all_amount" id="all_amount" onkeypress="return numbersonlywithpoint();" maxlength="8" />
                </div>
            </div>
            <div class="form-group">
                <label for="all_before" class="col-sm-3 control-label fieldLabel_optional">Learner had Advanced Learner Loan before:</label>
                <div class="col-sm-9">
                    <?php echo HTML::selectChosen('all_before', [['Yes', 'Yes'], ['No', 'No']], $tr->all_before, true); ?>
                </div>
            </div>
        <?php } ?>
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <?php if($framework->fund_model_extra == Framework::FUNDING_STREAM_COMMERCIAL){ ?>
            <div class="form-group">
                <label for="commercial_fee" class="col-sm-3 control-label fieldLabel_optional">Commercial Fee:</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" value="<?php echo $tr->commercial_fee; ?>" name="commercial_fee" id="commercial_fee" onkeypress="return numbersonlywithpoint();" maxlength="8" />
                </div>
            </div>
            <div class="form-group">
                <label for="commercial_fee_emp_cont" class="col-sm-3 control-label fieldLabel_optional">Employer paying any part of fee:</label>
                <div class="col-sm-9">
                    <?php echo HTML::selectChosen('commercial_fee_emp_cont', [['Yes', 'Yes'], ['No', 'No']], $tr->commercial_fee_emp_cont, true); ?>
                </div>
            </div>
            <div class="form-group">
                <label for="purchase_order_no" class="col-sm-3 control-label fieldLabel_optional">Purchase Order No.</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" value="<?php echo $tr->purchase_order_no; ?>" name="purchase_order_no" id="purchase_order_no" maxlength="25" />
                </div>
            </div>
        <?php } ?>
    </div>
</div>