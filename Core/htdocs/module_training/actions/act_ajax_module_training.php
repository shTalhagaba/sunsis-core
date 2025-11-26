<?php
class ajax_module_training implements IAction
{
	public function execute( PDO $link )
	{
		$subaction = isset($_REQUEST['subaction'])?$_REQUEST['subaction']:'';

		if($subaction != '' && $subaction == 'getTree')
		{
			$this->getTree($link);
			exit;
		}
		if($subaction != '' && $subaction == 'add_new_section')
		{
			$this->add_new_section($link);
			exit;
		}
		if($subaction != '' && $subaction == 'create_node')
		{
			$this->create_node($link);
			exit;
		}
		if($subaction != '' && $subaction == 'rename_node')
		{
			$this->rename_node($link);
			exit;
		}
		if($subaction != '' && $subaction == 'delete_node')
		{
			$this->delete_node($link);
			exit;
		}
		if($subaction != '' && $subaction == 'update_caseload_check')
		{
			$this->update_caseload_check();
			exit;
		}
		if($subaction != '' && $subaction == 'delete_training_group')
		{
			$this->delete_training_group($link);
			exit;
		}
		if($subaction != '' && $subaction == 'delete_cohort')
		{
			$this->delete_cohort($link);
			exit;
		}
	}

	public function getTree(PDO $link)
	{
		$data = [];

		$course_id = isset($_REQUEST['course_id']) ? $_REQUEST['course_id'] : '';
		if($course_id == '')
			return [];

		$course = Course::loadFromDatabase($link, $course_id);
		$tracker = $course->getKSBTemplate($link);

		foreach($tracker->sections AS $section)
		{
			$data[] = [
				"id" => $section->section_id,
				"parent" => "#",
				"text" => $section->section_title,
				"nodeType" => "section",
			];
			foreach($section->elements AS $element)
			{
				$data[] = [
					"id" => $element->element_id,
					"parent" => $element->section_id,
					"text" => $element->element_title,
					"nodeType" => "element",
				];
				foreach($element->evidences AS $evidence)
				{
					$data[] = [
						"id" => $evidence->evidence_id,
						"parent" => $evidence->element_id,
						"text" => $evidence->evidence_title,
						"nodeType" => "evidence",
					];
				}
			}
		}

		echo json_encode($data);
	}

	public function create_node(PDO $link)
	{
		$id = isset($_GET['id']) && $_GET['id'] !== '#' ? (int)$_GET['id'] : 0;
		// creating child of evidence - NOT ALLOWED
		$is_evidence = DAO::getSingleValue($link, "SELECT IF(element_id IS NOT NULL, 'Yes', 'No') AS is_evidence FROM tracking_template WHERE id = '{$id}'");
		if($is_evidence == 'Yes')
		{
			throw new Exception('evidence child not allowed.');
		}
		$course_id = isset($_GET['course_id']) && $_GET['course_id'] !== '#' ? (int)$_GET['course_id'] : 0;
		$text = isset($_GET['text']) && $_GET['text'] !== '' ? $_GET['text'] : '';

		$new_node = new stdClass();
		$new_node->id = null;
		$new_node->course_id = $course_id;
		$new_node->title = $text;

		$is_section = DAO::getSingleValue($link, "SELECT IF(section_id IS NULL, 'Yes', 'No') FROM tracking_template WHERE id = '{$id}'");
		if($is_section == 'Yes')
		{
			$new_node->section_id = $id;
		}
		else
		{
			$new_node->element_id = $id;
		}
		DAO::saveObjectToTable($link, 'tracking_template', $new_node);
		header('Content-Type: application/json; charset=utf-8');
		echo json_encode(['id' => $new_node->id]);
	}

	public function rename_node(PDO $link)
	{
		$node = isset($_GET['id']) && $_GET['id'] !== '#' ? (int)$_GET['id'] : 0;
		$nodeText = isset($_GET['text']) && $_GET['text'] !== '' ? $_GET['text'] : '';
		$sql ="UPDATE `tracking_template` SET `title`='".$nodeText."' WHERE `id`= '".$node."'";
		DAO::execute($link, $sql);
	}

	public function delete_node(PDO $link)
	{
		$id = isset($_GET['id']) && $_GET['id'] !== '#' ? (int)$_GET['id'] : 0;

		if(!$this->isSafeToDelete($link, $id))
		{
			throw new Exception('Delete aborted, this entry has associated student records.');
		}

		$existing_node = DAO::getObject($link, "SELECT * FROM tracking_template WHERE id = '{$id}'");
		$ids = [];
		$ids[] = $id;
		if($existing_node->section_id != '')
		{
			$related_ids = DAO::getSingleColumn($link, "SELECT id FROM tracking_template WHERE element_id = '{$existing_node->id}'");
		}
		else
		{
			$sql = <<<SQL
SELECT id FROM tracking_template WHERE section_id = '{$id}'
UNION ALL
SELECT id FROM tracking_template WHERE element_id IN (SELECT id FROM tracking_template WHERE section_id = '{$id}')
SQL;
			$related_ids = DAO::getSingleColumn($link, $sql);
		}
		if(count($related_ids) > 0)
			$ids = array_merge($ids, $related_ids);
		$sql ="DELETE FROM `tracking_template` WHERE `id` IN (" . implode(",", $ids) . ") ";
		DAO::execute($link, $sql);
	}

	public function add_new_section(PDO $link)
	{
		$obj = (object)[
			'id' => null,
			'course_id' => $_REQUEST['course_id'],
			'title' => $_REQUEST['text'],
		];
		DAO::saveObjectToTable($link, 'tracking_template', $obj);
	}

	public function isSafeToDelete(PDO $link, $id)
	{
		$type = DAO::getSingleValue($link, "SELECT IF(element_id IS NOT NULL, 'ev', (IF(section_id IS NOT NULL, 'el', 'se'))) FROM tracking_template WHERE id = '{$id}'");
		switch($type)
		{
			case 'ev':
				$associated_records = DAO::getSingleValue($link, "SELECT COUNT(*) FROM tr_tracking WHERE tracking_id = '{$id}'");
				break;
			case 'el':
				$associated_records = DAO::getSingleValue($link, "SELECT COUNT(*) FROM tr_tracking WHERE tracking_id IN (SELECT id FROM tracking_template WHERE element_id = '{$id}')");
				break;
			case 'se':
				$associated_records = DAO::getSingleValue($link, "SELECT COUNT(*) FROM tr_tracking WHERE tracking_id IN (SELECT id FROM tracking_template WHERE element_id IN (SELECT id FROM tracking_template WHERE section_id = '{$id}'))");
				break;
		}

		return $associated_records == 0;
	}

	public function update_caseload_check()
	{
		$state = isset($_REQUEST['state']) ? $_REQUEST['state'] : '';
		if($state == 1)
		{
			$_SESSION['caseload_learners_only'] = '1';
		}
		else
		{
			$_SESSION['caseload_learners_only'] = '0';
		}
	}

	public function delete_training_group(PDO $link)
	{
		$tg_id = isset($_POST['tg_id']) ? $_POST['tg_id'] : '';
		if($tg_id == '')
			throw new Exception('Delete aborted: Missing querystring argument: tg_id');

		$response = [
			'status' => 'success',
			'message' => 'Training group is deleted successfully.',
		];

		$learners = DAO::getSingleValue($link, "SELECT COUNT(*) FROM tr WHERE tr.tg_id = '{$tg_id}'");

		if($learners > 0)
		{
			$response = [
				'status' => 'error',
				'message' => 'Training group contains ' . $learners . ' learner(s), hence it cannot be deleted. Please remove the learners from this training group first.',
			];
		}
		else
		{
			DAO::execute($link, "DELETE FROM training_groups WHERE id = '{$tg_id}'");
		}

		echo json_encode($response);
	}

	public function delete_cohort(PDO $link)
	{
		$group_id = isset($_POST['group_id']) ? $_POST['group_id'] : '';
		if($group_id == '')
			throw new Exception('Delete aborted: Missing querystring argument: group_id');

		$response = [
			'status' => 'success',
			'message' => 'Cohort is deleted successfully.',
		];

		$training_groups = DAO::getSingleValue($link, "SELECT COUNT(*) FROM training_groups WHERE training_groups.group_id = '{$group_id}'");

		if($training_groups > 0)
		{
			$response = [
				'status' => 'error',
				'message' => 'Cohort contains ' . $training_groups . ' training group(s), hence it cannot be deleted. Please delete training groups of this cohort first.',
			];
		}
		else
		{
			$dao = new CourseGroupDAO($link);

			$dao->delete($link, $group_id);
		}

		echo json_encode($response);
	}
}