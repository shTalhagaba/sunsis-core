<?php
/* @var $ob_learner OnboardingLearner */
/* @var $tr TrainingRecord */

$english = DAO::getObject($link, "SELECT * FROM ob_learners_pa WHERE tr_id = '{$tr->id}' AND subject = 'English' AND q_type = 'g'");
$maths = DAO::getObject($link, "SELECT * FROM ob_learners_pa WHERE tr_id = '{$tr->id}' AND subject = 'Maths' AND q_type = 'g'");
$ict = DAO::getObject($link, "SELECT * FROM ob_learners_pa WHERE tr_id = '{$tr->id}' AND subject = 'ICT' AND q_type = 'g'");
$highest = DAO::getObject($link, "SELECT * FROM ob_learners_pa WHERE tr_id = '{$tr->id}' AND subject = 'Maths' AND q_type = 'h'");
$qual_records = DAO::getResultset($link, "SELECT * FROM ob_learners_pa WHERE tr_id = '{$tr->id}' AND q_type NOT IN ('g', 'h') ORDER BY date_completed", DAO::FETCH_ASSOC);
?>
<div class="row">
    <div class="col-sm-12">
        <p>
            <span class="text-bold">Prior Attainment: </span>
            <span><?php echo DAO::getSingleValue($link, "SELECT description FROM central.lookup_prior_attainment WHERE code IN (SELECT level FROM ob_learners_pa WHERE tr_id = '{$tr->id}' AND q_type = 'h');"); ?></span>
        </p>
        <div class="well well-sm">
            <p>
                <span class="text-bold">
                    Please detail all qualifications fully or partly achieved, including any apprenticeships you may have completed or part completed, even if it is not related to the apprenticeship you are applying for.
                </span>
                (Include all qualifications that may be related to this apprenticeship, including Maths, English, ICT, Digital, Health & Safety, Manual Handling, etc.)
            </p>
        </div>
        <div style="max-height: 600px; overflow-y: scroll;">
            <table class="table table-responsive row-border cw-table-list">
                <tr><th style="width: 25%;">GCSE/A/AS Level</th><th style="width: 25%;">Subject</th><th style="width: 15%;">Predicted Grade</th><th style="width: 15%;">Actual Grade</th><th style="width: 20%;">Date Completed</th></tr>
                <tbody>
                <tr>
                    <td>GCSE</td>
                    <td>English Language</td>
                    <td><?php echo isset($english->p_grade) ? $english->p_grade : '' ; ?></td>
                    <td><?php echo isset($english->a_grade) ? $english->a_grade : '' ; ?></td>
                    <td><?php echo isset($english->date_completed) ? Date::toShort($english->date_completed) : '' ; ?></td>
                </tr>
                <tr>
                    <td>GCSE</td>
                    <td>Maths</td>
                    <td><?php echo isset($maths->p_grade) ? $maths->p_grade : '' ; ?></td>
                    <td><?php echo isset($maths->a_grade) ? $maths->a_grade : '' ; ?></td>
                    <td><?php echo isset($maths->date_completed) ? Date::toShort($maths->date_completed) : '' ; ?></td>
                </tr>
                <tr>
                    <td>GCSE</td>
                    <td>ICT</td>
                    <td><?php echo isset($ict->p_grade) ? $ict->p_grade : '' ; ?></td>
                    <td><?php echo isset($ict->a_grade) ? $ict->a_grade : '' ; ?></td>
                    <td><?php echo isset($ict->date_completed) ? Date::toShort($ict->date_completed) : '' ; ?></td>
                </tr>
                <?php
                foreach($qual_records AS $record)
                {
                    $record = (object)$record;
                    echo '<tr>';
                    echo isset($qualLevelsList[$record->level]) ? '<td>' . $qualLevelsList[$record->level] . '</td>' : '<td>' . $record->level . '</td>';
                    echo '<td>' . $record->subject . '</td>';
                    echo '<td>' . $record->p_grade . '</td>';
                    echo '<td>' . $record->a_grade . '</td>';
                    echo '<td>' . Date::toShort($record->date_completed) . '</td>';
                    echo '</tr>';
                }
                ?>
            </table>
        </div>
    </div>
</div>