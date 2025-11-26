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
            <th class="text-bold">Apprentice Name</th>
            <td><?php echo $ob_learner->firstnames . ' ' . $ob_learner->surname; ?></td>
        </tr>
        <tr>
            <th class="text-bold">Employer Name</th>
            <td>
                <?php echo in_array(DB_NAME, ["am_superdrug", "am_sd_demo"]) ? $employer->brandDescription($link) : $employer->legal_name; ?><br>
                <small>
                <?php 
                echo $employer_location->address_line_1 != '' ? $employer_location->address_line_1 . '<br>' : ''; 
                echo $employer_location->address_line_2 != '' ? $employer_location->address_line_2 . '<br>' : ''; 
                echo $employer_location->address_line_3 != '' ? $employer_location->address_line_3 . '<br>' : ''; 
                echo $employer_location->address_line_4 != '' ? $employer_location->address_line_4 . '<br>' : '';
                echo $employer_location->postcode != '' ? $employer_location->postcode : '';
                ?>
                </small>
            </td>
        </tr>
        <tr>
            <th class="text-bold">Apprenticeship Title</th>
            <td><?php echo $framework->getStandardCodeDesc($link); ?></td>
        </tr>
        <tr>
            <th class="text-bold">Level</th>
            <td><?php echo DAO::getSingleValue($link, "SELECT CONCAT('Level ',NotionalEndLevel) FROM lars201718.`Core_LARS_Standard` WHERE StandardCode = '{$framework->StandardCode}';"); ?></td>
        </tr>
        <tr>
            <th class="text-bold">Contracted Hours per week</th>
            <td><?php echo $tr->contracted_hours_per_week; ?></td>
        </tr>
        <tr>
            <th class="text-bold">Total Contracted Hours (full apprenticeship)</th>
            <td><?php echo $tr->total_contracted_hours_full_apprenticeship; ?></td>
        </tr>
        <tr>
            <th class="text-bold">Minimum 20% OTJ Requirement</th>
            <td><?php echo $tr->minimum_percentage_otj_training; ?> hours</td>
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
