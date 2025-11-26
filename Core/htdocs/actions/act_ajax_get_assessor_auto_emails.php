<?php
class ajax_get_assessor_auto_emails implements IAction
{
	public function execute(PDO $link)
	{
		$tr_id = isset($_REQUEST['tr_id'])?$_REQUEST['tr_id']: '';
		if($tr_id == '')
			throw new Exception("No ID provided");

        echo "<h3>Auto Emails Audit2</h3>";

        $sql = <<<SQL
SELECT * FROM forms_audit
INNER JOIN assessor_review ON assessor_review.`id` = forms_audit.`form_id` AND assessor_review.`tr_id` = '$tr_id'
WHERE description IN ("Review Form 24HR Emailed to Learner","Review Form 48HR Emailed to Learner","Review Form 72HR Emailed to Learner","Review Form 72HR Emailed to Employer",
"Review Form 120HR Emailed to Employer","Review Form 168HR Emailed to Employer","Review Form Emailed to Learner","Review Form Emailed to Employer","Review Form 72HR Bsuiness Letter","Welcome Review Form Emailed to Learner","Welcome Review Form Emailed to Employer");
SQL;
        $records = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
        echo '<table class="resultset" cellpadding="6" cellspacing="0">';
        echo '<tr><th>Type</th><th>Review Date</th><th>Description</th><th>Date/ time</th><th>User</th><th>Contents</th></tr>';
        foreach($records AS $row)
        {
            $user = ($row['user']!='')?$row['user']:"Auto";
            $actual_date = Date::toShort(DAO::getSingleValue($link, "SELECT review_date FROM assessor_review_forms_assessor1 WHERE review_id = {$row['form_id']}"));
            echo '<tr><td>Assessor Review</td><td>' . Date::toShort($actual_date) . '</td><td>' . $row['description'] . '</td><td>' . Date::to($row['date'], Date::DATETIME) . '</td><td>' . $user . '</td>';
            echo "<td style='text-align: center'><a href='do.php?_action=generate_email_pdf&tr_id=$tr_id&review_id={$row['form_id']}&desc={$row['description']}'><img src='/images/pdf_icon.png' width='32px' height='32px'/></a></td>";
        }


        echo '</table><br><br>';

        $sql = <<<SQL
SELECT * FROM forms_audit
INNER JOIN assessment_plan_log_submissions ON assessment_plan_log_submissions.id = forms_audit.`form_id`
INNER JOIN assessment_plan_log ON assessment_plan_log.`id` = assessment_plan_log_submissions.`assessment_plan_id` AND assessment_plan_log.`tr_id` = '$tr_id'
WHERE description IN ("Assessment Plan Prompt 1 sent","Assessment Plan Prompt 2 sent","Assessment Plan Chaser 1 sent","Assessment Plan Chaser 2 sent");
SQL;
        $records = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
        echo '<h3>Assessment Plans</h3>';
        echo '<table class="resultset" cellpadding="6" cellspacing="0">';
        echo '<tr><th>Type</th><th>Set Date</th><th>Description</th><th>Date/ time</th><th>User</th><th>Contents</th></tr>';
        foreach($records AS $row)
        {
            $user = ($row['user']!='')?$row['user']:"Auto";
            echo '<tr><td>Assessment Plan</td><td>' . Date::toShort($row['set_date']) . '</td><td>' . $row['description'] . '</td><td>' . Date::to($row['date'], Date::DATETIME) . '</td><td>' . $user . '</td>';
            echo "<td style='text-align: center'><a href='do.php?_action=generate_email_pdf&tr_id=$tr_id&review_id={$row['form_id']}&desc={$row['description']}'><img src='/images/pdf_icon.png' width='32px' height='32px'/></a></td>";
        }
        echo '</table>';


        $sql = <<<SQL
SELECT * FROM forms_audit
INNER JOIN project_submissions ON project_submissions.id = forms_audit.form_id
INNER JOIN tr_projects ON tr_projects.id = project_submissions.project_id AND tr_projects.`tr_id` = '$tr_id'
WHERE description IN ("Project Prompt 1 sent","Project Prompt 2 sent","Project Chaser 1 sent","Project Chaser 2 sent");
SQL;
        $records = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
        echo '<h3>Projects</h3>';
        echo '<table class="resultset" cellpadding="6" cellspacing="0">';
        echo '<tr><th>Type</th><th>Set Date</th><th>Description</th><th>Date/ time</th><th>User</th><th>Contents</th></tr>';
        foreach($records AS $row)
        {
            $user = ($row['user']!='')?$row['user']:"Auto";
            echo '<tr><td>Assessment Plan</td><td>' . Date::toShort($row['set_date']) . '</td><td>' . $row['description'] . '</td><td>' . Date::to($row['date'], Date::DATETIME) . '</td><td>' . $user . '</td>';
            echo "<td style='text-align: center'><a href='do.php?_action=generate_email_pdf&tr_id=$tr_id&review_id={$row['form_id']}&desc={$row['description']}'><img src='/images/pdf_icon.png' width='32px' height='32px'/></a></td>";
        }
        echo '</table>';


	}
}