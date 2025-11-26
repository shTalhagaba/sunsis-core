<?php
class edit_tr_compliance implements IAction
{
	public function execute(PDO $link)
	{
		$tr_id = isset($_GET['tr_id']) ? $_GET['tr_id'] : '';

		if($tr_id !== '' && !is_numeric($tr_id))
		{
			throw new Exception("You must specify a numeric id in the querystring");
		}

		$tr = TrainingRecord::loadFromDatabase($link, $tr_id);
		if(is_null($tr))
		{
			throw new Exception('Invalid training id');
		}

		$_SESSION['bc']->add($link, "do.php?_action=edit_tr_compliance&tr_id=" . $tr->id, "Edit Learner Compliance");

		$framework_id = DAO::getSingleValue($link, "SELECT id FROM student_frameworks WHERE tr_id = '{$tr->id}'");
		$framework = Framework::loadFromDatabase($link, $framework_id);

		$sql = <<<SQL
SELECT
  compliance_checklist.`id`,
  compliance_checklist.`c_event`,
  compliance_checklist.`sub_events` AS sub_events_xml,
  tr_compliance.*
FROM
  compliance_checklist
  LEFT JOIN tr_compliance
    ON (
      compliance_checklist.`id` = tr_compliance.`compliance_item_id`
      AND tr_compliance.`tr_id` = '{$tr->id}'
    )
WHERE
  compliance_checklist.framework_id = '{$framework->id}'
ORDER BY
    compliance_checklist.sorting;
SQL;

		$compliance_result = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);

		$ddlStatus1 = [
			['CP', 'Checked and processed'],
			['Q', 'Query'],
			['RA', 'Received and awaiting processing'],
		];

		$course_id = DAO::getSingleValue($link, "SELECT course_id FROM courses_tr WHERE tr_id = '{$tr->id}'");
		$course = Course::loadFromDatabase($link, $course_id);

		$group_id = "SELECT groups.* FROM groups INNER JOIN group_members ON groups.id = group_members.groups_id
		WHERE groups.courses_id = '{$course->id}' AND group_members.tr_id = '{$tr->id}'";
		$group = DAO::getObject($link, $group_id);

		$tg = DAO::getObject($link, "SELECT training_groups.* FROM training_groups WHERE id = '{$tr->tg_id}' AND group_id = '{$group->id}'");

		include('tpl_edit_tr_compliance.php');
	}
}
?>