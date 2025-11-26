<?php
class add_new_lesson_learner implements IAction
{
	public function execute(PDO $link)
	{
		$lesson_id = isset($_REQUEST['lesson_id']) ? $_REQUEST['lesson_id'] : '';
		$ajax_action = isset($_GET['ajax_action']) ? $_GET['ajax_action'] : '';

		if($ajax_action)
		{
			switch($ajax_action)
			{
				case "enrol_learners":
					$this->enrol_learners($link);
					return;
					break;

				case "get_learners":
					$this->get_learners($link);
					return;
					break;

				case "get_enrolled_learners":
					$this->get_enrolled_learners($link);
					return;
					break;

				default:
					throw new Exception("Unknown ajax-action " . $ajax_action);
			}
		}

		$providers_dropdown = DAO::getResultset($link, "SELECT id, legal_name FROM organisations WHERE organisation_type = 3; ");

		array_unshift($providers_dropdown, array("0", "Please select a provider"));

		$sort_dropdown = array(
			array(0, "Learner", null),
			array(1, "Provider", null));

		exit;
	}

	private function enrol_learners(PDO $link)
	{
		$lesson_id = isset($_REQUEST['lesson_id']) ? $_REQUEST['lesson_id'] : '';
		$ids = isset($_REQUEST['learner_id']) ? $_REQUEST['learner_id'] : array();

		// It should normally be impossible to enrol no learners at all, but one of our
		// users just managed to do it. This might happen if there's a JavaScript error in
		// the web browser. 5th May 2011.
		if(count($ids) == 0){
			return;
		}

		// Start transaction
		try
		{
			DAO::transaction_start($link);

			foreach($ids as $id)
			{
				DAO::execute($link, "INSERT INTO lesson_extra_learners (tr_id, lesson_id) VALUES (" . $id . ", " . $lesson_id . ")");
			}


			DAO::transaction_commit($link);
		}
		catch(Exception $e)
		{
			DAO::transaction_rollback($link, $e);
			throw new WrappedException($e);
		}

		// Echo a simple success flag to the client
		header("Content-Type: text/plain");
		echo '1';
	}

	private function get_learners(PDO $link)
	{
		$qualification_id = DAO::getSingleValue($link, "SELECT qualification_id FROM attendance_modules WHERE attendance_modules.id = (SELECT module_id FROM attendance_module_groups WHERE id = (SELECT groups_id FROM lessons WHERE id = " . $_REQUEST['lesson_id'] . "))");

		$provider_id = isset($_GET['provider_id']) ? $_GET['provider_id'] : '';

		if( ($provider_id == '' || !is_numeric($provider_id)) )
		{
			throw new Exception("Missing or empty querystring argument 'provider_id'");
		}

		$enrolled_learners = "SELECT GROUP_CONCAT(pot_id) FROM register_entries WHERE register_entries.`lessons_id` = ".$_GET['lesson_id'];
		$enrolled_learners = DAO::getSingleValue($link, $enrolled_learners);
		$where_clause = " AND tr.id IN (SELECT tr_id FROM student_qualifications WHERE REPLACE(student_qualifications.id, '/', '') = '" . $qualification_id . "') ";
		if(trim($enrolled_learners) != '')
			$where_clause = " AND tr.id NOT IN (" . $enrolled_learners . ") ";

		$sql = <<<HEREDOC
SELECT DISTINCT
	tr.id,
	tr.firstnames,
	tr.surname,
	tr.dob,
	tr.uln,
	organisations.short_name,
	organisations.legal_name,
	tr.start_date,
	tr.target_date
FROM
	tr INNER JOIN organisations
		ON tr.provider_id = organisations.id

WHERE
	tr.provider_id=$provider_id
	AND tr.status_code = 1
	$where_clause
ORDER BY
	surname, firstnames
HEREDOC;

		if($rs = $link->query($sql))
		{

			header("Content-Type: text/xml");
			echo '<?xml version="1.0" encoding="ISO-8859-1"?>';
			echo '<learners>';

			while($row = $rs->fetch())
			{
				echo '<learner id="'.$row['id'].'" '
					.' firstnames="'.htmlspecialchars((string)$row['firstnames']).'" '
					.' surname="'.htmlspecialchars((string)$row['surname']).'" '
					.' dob="'.htmlspecialchars(Date::toShort($row['dob'])).'" '
					.' short_name="'.htmlspecialchars((string)$row['short_name']).'" '
					.' legal_name="'.htmlspecialchars((string)$row['legal_name']).'" '
					.' tr_id="'.htmlspecialchars((string)$row['id']).'" '
					.' start_date="'.htmlspecialchars((string)$row['start_date']).'" '
					.' target_date="'.htmlspecialchars((string)$row['target_date']).'" '
					.' uln="'.htmlspecialchars((string)$row['uln']).'" />';
			}

			echo "</learners>";

		}
		else
		{

		}
	}

}