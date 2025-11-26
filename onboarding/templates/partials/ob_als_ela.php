<?php 
$als = DAO::getObject($link, "SELECT * FROM ob_learner_additional_support WHERE tr_id = '{$id}'");
if(!isset($als->tr_id))
{
    $als = new stdClass();
    $als->tr_id = $id;
    $als = DAO::saveObjectToTable($link, "ob_learner_additional_support", $als);
    $als = DAO::getObject($link, "SELECT * FROM ob_learner_additional_support WHERE tr_id = '{$id}'");
}
$form_data = is_null($als->form_data) ? null : json_decode($als->form_data);

$funding_year = 2023;
if($tr->practical_period_start_date > '2024-05-31')
{
    $funding_year = 2024;
}
?>
<input type="hidden" name="als_funding_year" value="<?php echo $funding_year; ?>" />
<div class="row">
    <div class="col-sm-12">
        <table class="table table-bordered" id="T1">
            <thead>
                <tr class="bg-gray"><th style="width: 40%">Question</th><th style="width: 20%">Yes/No</th><th style="width: 40%">Comments</th></tr>
            </thead>
            <tbody>
            <?php
            $questions = DAO::getResultset($link, "SELECT * FROM lookup_questions_als WHERE year = '{$funding_year}' AND version = 1 AND tbl_group = 1", DAO::FETCH_ASSOC);
            foreach($questions AS $question)
            {
                $answer_id = 'answer'.$question['id'];
                $comments_id = 'comments'.$question['id'];
                echo '<tr>';
                echo '<th>' . $question['question'] . '</th>';
                echo '<td>' . HTML::selectChosen('als_'.$answer_id, [['Yes', 'Yes'], ['No', 'No']], (isset($form_data->$answer_id) ? $form_data->$answer_id : null), true) . '</td>';
                echo '<td><textarea class="form-control" name="als_'.$comments_id.'">' . (isset($form_data->$comments_id) ? $form_data->$comments_id : '') . '</textarea></td>';
                echo '</tr>';
            }
            ?>
            
            </tbody>
        </table>
    </div>
</div>
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
                $t2_answer_id = 't2_answer'.$question['id'];
		$comments_id = 't2_comments'.$question['id'];
                echo '<tr>';
                echo '<th>' . $question['question'] . '</th>';
                echo '<td>' . HTML::selectChosen('als_'.$t2_answer_id, [['Yes', 'Yes'], ['No', 'No']], (isset($form_data->$t2_answer_id) ? $form_data->$t2_answer_id : null), true) . '</td>';
		echo '<td><textarea class="form-control" name="als_'.$comments_id.'">' . (isset($form_data->$comments_id) ? $form_data->$comments_id : '') . '</textarea></td>';
                
                echo '</tr>';
            }
            ?>
            </tbody>
        </table>
    </div>
</div>