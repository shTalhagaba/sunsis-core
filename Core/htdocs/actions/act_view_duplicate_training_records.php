<?php
class view_duplicate_training_records implements IAction
{
	public function execute(PDO $link)
	{
		$view = View::getViewFromSession('primaryView', 'view_duplicate_training_records'); /* @var $view View */
		if(is_null($view))
		{
			// Create new view object
			$view = $_SESSION['primaryView'] = $this->buildView($link);
		}

		$view->refresh($_REQUEST, $link);

		$_SESSION['bc']->index=0;
		$_SESSION['bc']->add($link, "do.php?_action=view_duplicate_training_records" , "View Duplicate Training Records");

		$format = isset($_REQUEST['format']) ? $_REQUEST['format'] : NULL;
		if($format == 'csv')
		{
			$columns = array(
				'0' => 'tr_id',
				'1' => 'l03',
				'2' => 'firstnames',
				'3' => 'surname',
				'4' => 'start_date',
				'5' => 'planned_end_date',
				'6' => 'actual_end_date',
				'7' => 'course_title',
				'8' => 'contract_title',
				'9' => 'employer_name',
				'10' => 'provider_name'
			);
			//$view->exportToCSV($link);
		}
		else
		{
			require_once('tpl_view_duplicate_training_records.php');
		}
	}

	private function buildView(PDO $link)
	{
		if($_SESSION['user']->isAdmin())
		{
			$sql = <<<HEREDOC
SELECT
  t1.status_code,
  (SELECT COUNT(*) FROM assessor_review WHERE tr_id = t1.id) AS reviews,
  (SELECT COUNT(*) FROM student_events WHERE tr_id = t1.id) AS compliance_events,
  (SELECT COUNT(*) FROM appointments WHERE tr_id = t1.id) AS appointments,
  (SELECT COUNT(*) FROM exam_results WHERE tr_id = t1.id) AS exam_results,
  t1.username,
  t1.id AS tr_id,
  t1.`l03`,
  t1.`surname`,
  t1.`firstnames`,
  DATE_FORMAT(t1.`start_date`, '%d/%m/%Y') AS start_date,
  DATE_FORMAT(t1.`target_date`, '%d/%m/%Y') AS planned_end_date,
  DATE_FORMAT(t1.`closure_date`, '%d/%m/%Y') AS actual_end_date,
  courses.`id` AS course_id,
  courses.`title` AS course_title,
  contracts.`title` AS contract_title,
  contracts.`id` AS contract_id,
  employers.`legal_name` AS employer_name,
  providers.`legal_name` AS provider_name
FROM
  tr t1
  INNER JOIN
    (SELECT
      *
    FROM
      tr t2
    GROUP BY l03,
      username,
      contract_id,
      start_date,
      target_date
    HAVING COUNT(*) > 1) AS t3
    ON t1.`username` = t3.username
    AND t1.`target_date` = t3.target_date
    AND t1.`start_date` = t3.start_date
  LEFT JOIN courses_tr
    ON t1.id = courses_tr.`tr_id`
  LEFT JOIN courses
    ON courses_tr.`course_id` = courses.`id`
  LEFT JOIN organisations AS employers
    ON t1.`employer_id` = employers.id
  LEFT JOIN organisations AS providers
    ON t1.`provider_id` = providers.`id`
  LEFT JOIN contracts
    ON t1.`contract_id` = contracts.id
ORDER BY t1.username ;


HEREDOC;
		}

		$view = new VoltView('view_duplicate_training_records', $sql);
		return $view;
	}

	private function renderView(PDO $link, VoltView $view)
	{
		$tr_duplication_count = DAO::getSingleValue($link, "SELECT COUNT(*) AS cnt FROM tr GROUP BY l03, username, start_date, target_date HAVING cnt > 2;");
		$fn = "getSelectedRow(this);";
		if($tr_duplication_count != '')
			$fn = "";
		$sql = $view->getSQLStatement()->__toString();

		$st = $link->query($sql);
		if(!$st)
		{
			throw new DatabaseException($link, $sql);
		}

		echo '<div align="center"><table class="resultset" id="MwDataList" border="0" cellspacing="0" cellpadding="6">';

		echo '<thead><tr>';
		echo '<th></th><th>L03</th><th>First Name</th><th >Surname</th><th >Contract</th><th >Course</th><th >Start Date</th><th >Planned End Date</th><th >Actual End Date</th><th >Employer</th><th>Provider</th>';
		echo '<th>Reviews</th><th>Compliance Events</th><th>Appointments</th><th>Exam Results</th><th></th>';
		echo '</tr></thead>';
		echo '<tbody>';
		$counter=0;
		while($row = $st->fetch())
		{
			echo '<tr>';
			echo '<td align="center"><input id="' . ++$counter . '" type="radio"  onchange="" onclick="' . $fn . '" name="choice_' . $row['username'] . '" value="' . $row['tr_id'] . '"   /></td>';
			echo '<td align="left">' . HTML::cell($row['l03']) . '</td>';
			echo '<td align="left">' . HTML::cell($row['firstnames']) . '</td>';
			echo '<td align="left">' . HTML::cell($row['surname']) . '</td>';
			echo '<td align="left">' . HTML::cell($row['contract_title']) . '</td>';
			echo '<td align="left">' . HTML::cell($row['course_title']) . '</td>';
			echo '<td align="left">' . HTML::cell($row['start_date']) . '</td>';
			echo '<td align="left">' . HTML::cell($row['planned_end_date']) . '</td>';
			echo '<td align="left">' . HTML::cell($row['actual_end_date']) . '</td>';
			echo '<td align="left">' . HTML::cell($row['employer_name']) . '</td>';
			echo '<td align="left">' . HTML::cell($row['provider_name']) . '</td>';
			echo '<td align="center">' . HTML::cell($row['reviews']) . '</td>';
			echo '<td align="center">' . HTML::cell($row['compliance_events']) . '</td>';
			echo '<td align="center">' . HTML::cell($row['appointments']) . '</td>';
			echo '<td align="center">' . HTML::cell($row['exam_results']) . '</td>';

			echo '<td align="center"><span class="button" onclick="window.location.href=\'do.php?_action=read_training_record&contract_id=' . $row['contract_id'] . '&id=' . $row['tr_id'] . '\';">View</span>';

			echo '</tr>';
		}

		echo '</tbody></table></div>';
	}


}
?>