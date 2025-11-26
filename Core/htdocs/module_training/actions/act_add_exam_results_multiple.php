<?php
class add_exam_results_multiple implements IAction
{
	public function execute(PDO $link)
	{
		$subaction = isset($_REQUEST['subaction']) ? $_REQUEST['subaction'] : '';
		$course_id = isset($_REQUEST['course_id']) ? $_REQUEST['course_id'] : '';
		$group_id = isset($_REQUEST['group_id']) ? $_REQUEST['group_id'] : '';
		$tg_id = isset($_REQUEST['tg_id']) ? $_REQUEST['tg_id'] : '';

		if($subaction == 'load_groups')
		{
			$this->load_groups($link);
			exit;
		}
		if($subaction == 'load_training_groups')
		{
			$this->load_training_groups($link);
			exit;
		}
		if($subaction == 'get_qualification_units')
		{
			$this->get_qualification_units($link);
			exit;
		}
		if($subaction == 'save_exam_results')
		{
			$this->save_exam_results($link);
			exit;
		}
		if($subaction == 'showSavedExamInfo')
		{
			$this->showSavedExamInfo($link);
			exit;
		}

		$courses_select = DAO::getResultset($link, "SELECT courses.id, courses.title FROM courses WHERE courses.active = 1 ORDER BY courses.title");
		$groups_select = $course_id == '' ? [] : DAO::getResultset($link, "SELECT groups.id, groups.title FROM groups WHERE groups.courses_id = '{$course_id}' ORDER BY groups.title");
		$tgs_select = $group_id == '' ? [] : DAO::getResultset($link, "SELECT id, title FROM training_groups WHERE group_id = '{$group_id}' ORDER BY training_groups.title");

		$attempts_ddl = array();
		for($i = 1; $i <= 15; $i++)
		{
			$attempts_ddl[] = array($i, $i);
		}

		$exam_results_select[] = ['Pass', 'Passed'];
		$exam_results_select[] = ['Fail', 'Failed'];
		$exam_results_select[] = ['NR', 'Not Required'];

		$_SESSION['bc']->add($link, "do.php?_action=add_exam_results_multiple&subaction={$subaction}&course_id={$course_id}&group_id={$group_id}&tg_id={$tg_id}", "Record exam results");

		include_once('tpl_add_exam_results_multiple.php');
	}

	private function load_groups(PDO $link)
	{
		$sql = "SELECT id, title, null FROM groups WHERE groups.courses_id = '{$_REQUEST['course_id']}' ORDER BY title";
		$st = $link->query($sql);
		if($st)
		{
			echo "<option value=\"\"></option>";
			if($st->rowCount() == 0)
			{
				echo '<option value="">No groups/cohorts found</option>';
			}
			else
			{
				while($row = $st->fetch())
				{
					echo '<option value="' . htmlspecialchars((string)$row[0]) . '">' . htmlspecialchars((string)$row[1]) . "</option>";
				}
			}
		}
		else
		{
			throw new DatabaseException($link, $sql);
		}
	}

	private function load_training_groups(PDO $link)
	{
		$sql = "SELECT id, title, null FROM training_groups WHERE training_groups.group_id = '{$_REQUEST['group_id']}' ORDER BY title";
		$st = $link->query($sql);
		if($st)
		{
			echo "<option value=\"\"></option>";
			if($st->rowCount() == 0)
			{
				echo '<option value="">No training groups found</option>';
			}
			else
			{
				while($row = $st->fetch())
				{
					echo '<option value="' . htmlspecialchars((string)$row[0]) . '">' . htmlspecialchars((string)$row[1]) . "</option>";
				}
			}
		}
		else
		{
			throw new DatabaseException($link, $sql);
		}
	}

	public function get_qualification_units(PDO $link)
	{
		$qualification_id = isset($_REQUEST['qualification_id']) ? $_REQUEST['qualification_id'] : '';
		$framework_id = isset($_REQUEST['framework_id']) ? $_REQUEST['framework_id'] : '';

		if($qualification_id == '' || $framework_id == '')
			throw new Exception('Missing querystring arguments: qualification_id, framework_id');

		$sql = <<<HEREDOC
SELECT
	 framework_qualifications.evidences
FROM
	 framework_qualifications
WHERE
	 framework_qualifications.framework_id = '{$framework_id}' AND REPLACE(framework_qualifications.id, '/', '') = '{$qualification_id}' ;
HEREDOC;
		$evidence = XML::loadSimpleXML(DAO::getSingleValue($link, $sql));
		$units = $evidence->xpath('//unit');
		echo "<option value=\"\"></option>";
		foreach ($units AS $unit)
		{
			$temp = (array)$unit->attributes();
			$temp = $temp['@attributes'];
			echo '<option value="' . htmlspecialchars(str_replace('/','', $temp['reference'])) . '">' . htmlspecialchars((string)$temp['title']) . '</option>';
		}
	}

	private function save_exam_results(PDO $link)
	{
		$learnersTRIDs = isset($_POST['learnersTRIDs']) ? $_POST['learnersTRIDs'] : [];
		if(count($learnersTRIDs) == 0)
		{
			throw new Exception('No learners selected for the save operation.');
		}

		foreach($learnersTRIDs AS $tr_id)
		{
			$exam = new ExamResult();
			$exam->tr_id = $tr_id;
			$exam->qualification_id = isset($_POST['qualification_id']) ? $_POST['qualification_id'] : null;
			$exam->qualification_title = isset($_POST['qualification_title']) ? $_POST['qualification_title'] : null;
			$exam->unit_reference = isset($_POST['unit_reference']) ? $_POST['unit_reference'] : null;
			$exam->unit_title = isset($_POST['unit_title']) ? $_POST['unit_title'] : null;
			$exam->exam_date = isset($_POST['exam_date']) ? $_POST['exam_date'] : null;
			$exam->attempt_no = isset($_POST['attempt_no']) ? $_POST['attempt_no'] : null;
			$exam->exam_result = isset($_POST['exam_result_'.$tr_id]) ? $_POST['exam_result_'.$tr_id] : null;
			DAO::saveObjectToTable($link, 'exam_results', $exam);
		}

		http_redirect($_SESSION['bc']->getPrevious());
	}

	private function print_labels(PDO $link, $tr_id)
	{

	}

	public function showSavedExamInfo(PDO $link)
	{
		$tr_id = isset($_REQUEST['tr_id'])?$_REQUEST['tr_id']:'';

		if($tr_id == '')
			throw new Exception('No id given');

		$html = '<table class="table callout">';
		$html .= '<tr><th>QAN</th><th>Title</th><th>Unit Ref</th><th>Unit Title</th><th>Attempt</th><th>Result</th></tr>';
		$notes = DAO::getResultset($link, "SELECT * FROM exam_results WHERE tr_id = '{$tr_id}' ORDER BY id", DAO::FETCH_ASSOC);
		if(count($notes) == 0)
		{
			$html .= '<tr><td colspan="6"><i>No existing record found.</i></td></tr>';
		}
		else
		{
			foreach($notes AS $note)
			{
				$html .= '<tr>';
				$html .= '<td>' . $note['qualification_id'] . '</td>';
				$html .= '<td>' . $note['qualification_title'] . '</td>';
				$html .= '<td>' . $note['unit_reference'] . '</td>';
				$html .= '<td>' . $note['unit_title'] . '</td>';
				$html .= '<td>' . $note['attempt_no'] . '</td>';
				$html .= '<td>' . $note['exam_result'] . '</td>';
				$html .= '</tr>';
			}
		}
		$html .= '</table>';
		echo '<small>' . $html . '</small>';
	}
}
?>