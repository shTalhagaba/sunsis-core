<?php
/* @var $ob_learner OnboardingLearner */
/* @var $tr TrainingRecord */

$employment_records = DAO::getResultset($link, "SELECT * FROM ob_learners_ea WHERE tr_id = '{$tr->id}' ORDER BY ea_date_from DESC", DAO::FETCH_ASSOC);
?>
<div class="row">
    <div class="col-sm-12">
        <div>
            <table class="table table-responsive row-border cw-table-list">
                <tr>
                    <th style="width: 15%;">Date From</th>
                    <th style="width: 15%;">Date To</th>
                    <th style="width: 20%;">Employer</th>
                    <th style="width: 20%;">Role</th>
                    <th style="width: 30%;">Responsibilities</th>
                </tr>
                <tbody>
                <?php
                foreach($employment_records AS $record)
                {
                    $record = (object)$record;
                    echo '<tr>';
                    echo '<td>' . Date::toShort($record->ea_date_from) . '</td>';
                    echo '<td>' . Date::toShort($record->ea_date_to) . '</td>';
                    echo '<td>' . $record->ea_employer . '</td>';
                    echo '<td>' . $record->ea_role . '</td>';
                    echo '<td>' . $record->ea_resp . '</td>';
                    echo '</tr>';
                }
                ?>
            </table>
        </div>
    </div>
</div>