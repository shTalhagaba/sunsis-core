<?php
class ManagerInterventionReport2 extends View
{
    public static function getInstance($link)
    {
        $key = 'view_'.__CLASS__.'11';

        if(!isset($_SESSION[$key]))
        {
            $sql = <<<HEREDOC
SELECT
tr.`firstnames`,
tr.`surname`,
CONCAT(users.`firstnames`,' ', users.`surname`) AS assessor,
description,
"Review" AS review_or_plan,
'' as assessment_plan_title,
'' AS assessment_plan_due_date,
assessor_review.`meeting_date` as review_date,
`date` AS email_date
FROM forms_audit
INNER JOIN assessor_review ON assessor_review.id = forms_audit.`form_id`
INNER JOIN tr ON tr.id = assessor_review.`tr_id` AND tr.`status_code` = 1
LEFT JOIN users ON users.id = tr.`assessor`
WHERE
form_id NOT IN (SELECT review_id FROM arf_introduction WHERE `signature_employer_font` IS NOT NULL)
UNION
SELECT
tr.`firstnames`,
tr.`surname`,
CONCAT(users.`firstnames`,' ', users.`surname`) AS assessor,
forms_audit.description,
"Plan" AS review_or_plan,
lookup_assessment_plan_log_mode.`description` AS assessment_plan_title,
assessment_plan_log_submissions.due_date as assessment_plan_due_date,
'' AS review_date,
`date` AS email_date
FROM forms_audit
INNER JOIN assessment_plan_log_submissions ON assessment_plan_log_submissions.id = forms_audit.`form_id` AND assessment_plan_log_submissions.id = (SELECT MAX(s2.id) FROM assessment_plan_log_submissions AS s2 WHERE s2.`assessment_plan_id` = assessment_plan_log_submissions.`assessment_plan_id`)
INNER JOIN assessment_plan_log ON assessment_plan_log.id = assessment_plan_log_submissions.`assessment_plan_id`
INNER JOIN tr ON tr.id = assessment_plan_log.`tr_id` AND tr.`status_code` = 1
LEFT JOIN lookup_assessment_plan_log_mode ON lookup_assessment_plan_log_mode.`id` = assessment_plan_log.`mode`
LEFT JOIN users ON users.id = tr.`assessor`
WHERE
    assessment_plan_log_submissions.`completion_date` IS NULL
GROUP BY form_id;
HEREDOC;
            $view = $_SESSION[$key] = new ManagerInterventionReport2();
            $view->setSQL($sql);
        }

        return $_SESSION[$key];
    }


    public function render(PDO $link)
    {
        //if(SOURCE_BLYTHE_VALLEY)
        $rp = new ReflectionProperty('View', 'sql');
        $rp->setAccessible(true);
        $st = $link->query($rp->getValue($this));
        //$st = $link->query($this->getSQL());

        if($st)
        {
            //echo $this->getViewNavigator();
            echo '<table id="tblLogs" class="table table-bordered">';
            echo <<<HEREDOC
	<thead class="bg-gray">
	<tr>
		<th>Firstnames</th>
		<th>Surname</th>
		<th>Assessor</th>
		<th>Email Description</th>
		<th>Plan/ Review</th>
		<th>Assessment Plan Title</th>
		<th>Plan Due Date</th>
		<th>Review Date</th>
		<th>Audit Date/ time</th>
	</tr>
	</thead>
HEREDOC;

            echo '<tbody>';
            $tr_id = 0;
            while($row = $st->fetch())
            {
                $style = '';
                $submission = 1;
                echo '<tr ' . $style . '>';
                echo '<td>' . HTML::cell($row['firstnames']) . '</td>';
                echo '<td>' . HTML::cell($row['surname']) . '</td>';
                echo '<td>' . HTML::cell($row['assessor']) . '</td>';
                echo '<td>' . HTML::cell($row['description']) . '</td>';
                echo $row['review_or_plan'] == 'Review' ? '<td class="bg-green">' . HTML::cell($row['review_or_plan']) . '</td>' : '<td class="bg-info">' . HTML::cell($row['review_or_plan']) . '</td>';
                echo '<td>' . HTML::cell($row['assessment_plan_title']) . '</td>';
                echo '<td>' . Date::toShort($row['assessment_plan_due_date']) . '</td>';
                echo '<td>' . Date::toShort($row['review_date']) . '</td>';
                echo '<td>' . Date::to($row['email_date'], Date::DATETIME) . '</td>';
                echo '</tr>';
            }
            echo '</tbody></table>';

        }
        else
        {
            throw new DatabaseException($link, $this->getSQL());
        }

    }
}
?>