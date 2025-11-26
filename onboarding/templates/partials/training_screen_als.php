<?php 
$_als_saved_record = DAO::getObject($link, "SELECT * FROM ob_learner_additional_support WHERE tr_id = '{$id}'");
if(!isset($_als_saved_record->tr_id))
{
    $_als_saved_record = new stdClass();
    $_als_saved_record->tr_id = $id;
    $_als_saved_record = DAO::saveObjectToTable($link, "ob_learner_additional_support", $_als_saved_record);
    $_als_saved_record = DAO::getObject($link, "SELECT * FROM ob_learner_additional_support WHERE tr_id = '{$id}'");
}

$form_data = is_null($_als_saved_record->form_data) ? null : json_decode($_als_saved_record->form_data);
$funding_year = 2023;
if(
    $tr->practical_period_start_date > '2024-05-31' || 
    (isset($form_data->funding_year) && $form_data->funding_year == 2024) // this is if 2024 info is saved. 
)
{
	if(!in_array($tr->id, [2149, 2159, 2160, 2180]))
    		$funding_year = 2024;
}
?>
<div class="row">
    <div class="col-sm-12">
	<?php if($_als_saved_record->learner_sign != '' && $_als_saved_record->provider_sign != '') { ?>
        <span class="btn btn-xs btn-success pull-right" onclick="generateAlsPdf();"><i class="fa fa-file-pdf-o"></i> Generate PDF</span>
        <?php } ?>
        <table class="table table-bordered" id="T1">
            <thead>
                <tr class="bg-gray"><th style="width: 40%">Question</th><th style="width: 20%">Yes/No</th><th style="width: 40%">Comments</th></tr>
            </thead>
            <tbody>
            <?php
            $als_total_yes = 0;
            $als_total_no = 0;
            $questions = DAO::getResultset($link, "SELECT * FROM lookup_questions_als WHERE year = '{$funding_year}' AND version = 1 AND tbl_group = 1", DAO::FETCH_ASSOC);
            foreach($questions AS $question)
            {
                $answer_id = 'answer'.$question['id'];
                $comments_id = 'comments'.$question['id'];
                echo '<tr>';
                echo '<th>' . $question['question'] . '</th>';
                echo '<td>' . (isset($form_data->$answer_id) ? $form_data->$answer_id : '') . '</td>';
                echo '<td>' . (isset($form_data->$comments_id) ? $form_data->$comments_id : '') . '</td>';
                echo '</tr>';
                if(isset($form_data->$answer_id) && $form_data->$answer_id == 'Yes')
                {
                    $als_total_yes++;
                }
                if(isset($form_data->$answer_id) && $form_data->$answer_id == 'No')
                {
                    $als_total_no++;
                }
            }
            ?>
            <tr>
                <th colspan="3">
                    <span class="text-bold">Total Score: </span><span class="text-lg text-info"><?php echo $als_total_yes; ?>/<?php echo ($als_total_no+$als_total_yes); ?></span><br>
                    <span class="text-bold">Number of 'Yes': </span><span class="text-lg text-info"><?php echo $als_total_yes; ?></span><br>
                    <span class="text-bold">Does the learner agree to a referral?: </span>
                    <span class="text-lg text-info"><?php echo (isset($form_data->learnerAgreeT1) ? $form_data->learnerAgreeT1 : ''); ?></span><br>
                </th>
            </tr>
            </tbody>
        </table>
    </div>
</div>
<?php if($funding_year == 2023) { ?>
<div class="row">
    <div class="col-sm-12">
        <table class="table table-bordered" id="T2">
            <thead>
                <tr class="bg-gray"><th style="width: 40%">Question</th><th style="width: 20%">Yes/No</th><th style="width: 40%">Action</th></tr>
            </thead>
            <tbody>
            <?php
            $questions = DAO::getResultset($link, "SELECT * FROM lookup_questions_als WHERE year = '{$funding_year}' AND version = 1 AND tbl_group = 2", DAO::FETCH_ASSOC);
            foreach($questions AS $question)
            {
                $t2_answer_id = 't2_answer'.$question['id'];
                echo '<tr>';
                echo '<th>' . $question['question'] . '</th>';
                echo '<td>' . (isset($form_data->$t2_answer_id) ? $form_data->$t2_answer_id : '') . '</td>';
                echo '<td>' . $question['action'] . '</td>';
                echo '</tr>';
            }
            ?>
            </tbody>
        </table>
    </div>
</div>
<?php } elseif($funding_year == 2024) { ?>
    <div class="row">
    <div class="col-sm-12">
        <table class="table table-bordered" id="T2">
            <thead>
                <tr class="bg-gray"><th style="width: 40%">Question</th><th style="width: 20%">Yes/No</th><th style="width: 40%">Comments</th></tr>
            </thead>
            <tbody>
            <?php
            $questions = DAO::getResultset($link, "SELECT * FROM lookup_questions_als WHERE year = '{$funding_year}' AND version = 1 AND tbl_group = 2", DAO::FETCH_ASSOC);
            foreach($questions AS $question)
            {
                $answer_id = 't2_answer'.$question['id'];
                $comments_id = 't2_comments'.$question['id'];
                echo '<tr>';
                echo '<th>' . $question['question'] . '</th>';
                echo '<td>' . (isset($form_data->$answer_id) ? $form_data->$answer_id : '') . '</td>';
                echo '<td>' . (isset($form_data->$comments_id) ? $form_data->$comments_id : '') . '</td>';
                echo '</tr>';
            }
            ?>
            </tbody>
        </table>
    </div>
</div>

<?php } ?>

<div class="row">
    <div class="col-sm-12"><hr></div>
</div>