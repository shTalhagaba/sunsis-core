<?php
/* @var $ob_learner OnboardingLearner */
/* @var $tr TrainingRecord */

echo '<table class="table table-bordered">';
echo '<tr><th colspan="2">Skills Analysis Score Overview</th></tr>';
echo '<tr><th></th><th>Score</th></tr>';
$result = DAO::getResultset($link, "SELECT unit_group, SUM(score) AS _score FROM ob_learner_ksb WHERE tr_id = '{$tr->id}' GROUP BY unit_group;", DAO::FETCH_ASSOC);
$total = 0;
//$total_planned_hours = 20;
foreach($result AS $row)
{
    echo '<tr>';
    echo '<th>' . $row['unit_group'] . '</th>';
    echo '<td>' . $row['_score'] . '</td>';
    echo '</tr>';
    $total += $row['_score'];
}
echo '<tr><th>Total</th><td>' . $total . '</td></tr>';

echo '</table>';

$scores = LookupHelper::getListKsbScores();
echo '<table class="table table-bordered">';
echo '<tr><th colspan="2">Key</th></tr>';
foreach($scores AS $key => $value)
{
    echo '<tr>';
    echo '<td>' . $key . '</td><td>' . $value . '</td>';
    echo '</tr>';
}
echo '</table>';
echo '<table class="table table-bordered">';
echo '<tr><th colspan="7">Skills Analysis Detail</th></tr>';
echo '<tr><th>KSB</th><th>Topic</th><th>Required</th><th>Score</th><th>Comments</th><th class="small">Delivery Plan Hours (100%)</th><th class="small">Delivery Plan Hours (following assessment)</th></tr>';
$result = DAO::getResultset($link, "SELECT * FROM ob_learner_ksb WHERE tr_id = '{$tr->id}' ORDER BY id", DAO::FETCH_ASSOC);

$delivery_plan_total_fa = 0;
$delivery_plan_total_ba = 0;
foreach($result AS $row)
{
    $delivery_plan_hours = 0;
    $del_hours = $row['del_hours'] != '' ? floatval($row['del_hours']) : 20;
    echo '<tr>';
    echo '<td>' . $row['unit_group'] . '</td>';
    echo '<td>' . $row['unit_title'] . '</td>';
    echo '<td>' . $row['evidence_title'] . '</td>';
    echo '<td>' . $row['score'] . '</td>';
    echo '<td class="small">' . $row['comments'] . '</td>';
    echo '<td>' . $del_hours . '</td>';
    echo '<td>';
    if($row['score'] == 5)
        $delivery_plan_hours = ceil($del_hours * 0.25);
    elseif($row['score'] == 4)
        $delivery_plan_hours = ceil($del_hours * 0.5);
    elseif($row['score'] == 3)
        $delivery_plan_hours = ceil($del_hours * 0.75);
    elseif($row['score'] == 2)
        $delivery_plan_hours = ceil($del_hours * 0.9);
    elseif($row['score'] == 1)
        $delivery_plan_hours = $del_hours;
    echo $delivery_plan_hours;
    echo '</td>';
    echo '</tr>';
}
echo '<tr><th></th><th></th><th></th><th></th><th></th>';
echo '<th class="bg-light-blue">' . $ss_stats->delivery_plan_total_ba . '</th><th class="bg-light-blue">' . $ss_stats->delivery_plan_total_fa . '</th></tr>';
echo '<tr><th colspan="7" class="text-center bg-green-gradient">' . $ss_stats->percentage_following_assessment . '%</th> </tr>';
echo '</table>';