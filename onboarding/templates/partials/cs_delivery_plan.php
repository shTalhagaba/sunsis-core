<?php
/* @var $ob_learner OnboardingLearner */
/* @var $tr TrainingRecord */

?>

<div class="row">
    <div class="col-sm-12">
        <p class="lead text-bold">Section 3 - Training to be Delivered</p>
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <div class="table-responsive" style="font-size: medium;">
            <table class="table table-bordered">
                <tr class="bg-light-blue">
                    <th>Training to be delivered</th>
                    <th>Level</th>
                    <th>Details</th>
                    <th>Start Date</th>
                    <th>Planned End Date</th>
                    <th>Number of months</th>
                    <th>Delivery location</th>
                    <th>Mode of attendance</th>
                    <th>Day of week</th>
                    <th>No. of days or weeks</th>
                </tr>
                <?php
                $ob_quals_sql = <<<SQL
SELECT 
    ob_learner_quals.*,
    framework_qualifications.level,
    framework_qualifications.qualification_type,
    TIMESTAMPDIFF(MONTH, qual_start_date, qual_end_date) AS no_of_months
FROM
    ob_learner_quals
    LEFT JOIN framework_qualifications ON REPLACE(ob_learner_quals.qual_id, '/', '') = REPLACE(framework_qualifications.id, '/', '') 
WHERE
    ob_learner_quals.tr_id = '{$tr->id}' AND 
    framework_qualifications.framework_id = '{$tr->framework_id}'   
SQL;
                $ob_quals = DAO::getResultset($link, $ob_quals_sql, DAO::FETCH_ASSOC);
                foreach($ob_quals AS $qual)
                {
                    echo '<tr>';
                    echo '<td>' . $qual['qual_id'] .  ' ' . $qual['qual_title'] . '</td>';
                    echo '<td>' . $qual['level'] . '</td>';
                    echo '<td>' . DAO::getSingleValue($link, "SELECT description FROM lookup_qual_type WHERE id = '{$qual['qualification_type']}'") . '</td>';
                    echo '<td>' . Date::toShort($qual['qual_start_date']) . '</td>';
                    echo '<td>' . Date::toShort($qual['qual_end_date']) . '</td>';
                    echo '<td>' . $qual['no_of_months'] . ' months</td>';
                    echo is_null($qual['qual_dl']) ?
                        '<td></td>' :
                        '<td>' . LookupHelper::getListDeliveryLocation($qual['qual_dl']) . '</td>';
                    echo is_null($qual['qual_ma']) ?
                        '<td></td>' :
                        '<td>' . LookupHelper::getListModeOfAttendance($qual['qual_ma']) . '</td>';
                    echo '<td>' . $qual['qual_dow'] . '</td>';
                    echo '<td>' . $qual['qual_d_or_w'] . '</td>';
                    echo '</tr>';
                }
                ?>
            </table>
        </div>
    </div>
</div>
