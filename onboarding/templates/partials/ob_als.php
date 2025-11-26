<?php
/* @var $ob_learner OnboardingLearner */
/* @var $tr TrainingRecord */

$als_records = DAO::getResultset($link, "SELECT * FROM ob_learner_als WHERE tr_id = '{$tr->id}' ORDER BY id", DAO::FETCH_ASSOC);
?>
<div class="row">
    <div class="col-sm-12">
        <div>
            <table class="table table-responsive row-border cw-table-list">
                <tr>
                    <th style="width: 15%;">Date Discussed</th>
                    <th style="width: 15%;">Support Required</th>
                    <th style="width: 20%;">Details</th>
                    <th style="width: 20%;">Date Claimed From</th>
                    <th style="width: 30%;">Additional Info.</th>
                </tr>
                <tbody>
                <?php
                if(count($als_records) == 0)
                    echo '<tr><td colspan="5"><i>No records.</i></td></tr>';
                foreach($als_records AS $als_row)
                {
                    $als_row = (object)$als_row;
                    echo '<tr>';
                    echo '<td>' . Date::toShort($als_row->date_discussed) . '</td>';
                    echo $als_row->support_required == 'Y' ? '<td>Yes</td>' : '<td>No</td>';
                    echo '<td>' . HTML::cell($als_row->details) . '</td>';
                    echo '<td>' . Date::toShort($als_row->date_claimed_from) . '</td>';
                    echo '<td>' . HTML::cell($als_row->additional_info) . '</td>';
                    echo '</tr>';
                }
                ?>
            </table>
        </div>
    </div>
</div>