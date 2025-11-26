<?php
class delete_training_v2 implements IAction
{
	public function execute(PDO $link)
	{
		$course_id = isset($_POST['course_id']) ? $_POST['course_id'] : '';
		$learnersToRemove = isset($_POST['learnersToRemove']) ? $_POST['learnersToRemove'] : '';

		if($course_id != '' && count($learnersToRemove) > 0)
		{
			foreach($learnersToRemove AS $tr_id)
			{
				$training_record = TrainingRecord::loadFromDatabase($link, $tr_id);

				$query = <<<HEREDOC
DELETE FROM
	tr,
	courses_tr,
	group_members,
	student_frameworks,
	student_qualifications,
	student_milestones,
	acl,
	ilr,
	register_entries,
	register_entry_notes,
	attendance_reports,
	exam_results
USING
	tr
	LEFT OUTER JOIN	courses_tr ON courses_tr.tr_id = tr.id
	LEFT OUTER JOIN	group_members ON group_members.tr_id = tr.id
	LEFT OUTER JOIN	student_frameworks ON student_frameworks.tr_id = tr.id
	LEFT OUTER JOIN	student_qualifications ON student_qualifications.tr_id = tr.id
	LEFT OUTER JOIN	student_milestones ON student_milestones.tr_id = tr.id
	LEFT OUTER JOIN	acl ON acl.resource_id = tr.id and resource_category='trainingrecord'
	LEFT OUTER JOIN ilr ON ilr.tr_id = tr.id
	LEFT OUTER JOIN register_entries ON tr.id = register_entries.pot_id
	LEFT OUTER JOIN register_entry_notes ON register_entries.id = register_entry_notes.register_entries_id
	LEFT OUTER JOIN attendance_reports ON register_entries.lessons_id = attendance_reports.lesson_id
	LEFT OUTER JOIN exam_results ON exam_results.tr_id = tr.id
WHERE
	tr.id=$tr_id;
HEREDOC;
				DAO::execute($link, $query);

				$note = new Note();
				$note->subject = "Training Record Deleted";
				$note->parent_id = $tr_id;
				$note->parent_table = "tr";
				$note->note = "TR Deleted:" . PHP_EOL . "FN:" . $training_record->firstnames . ",SN:" . $training_record->surname . ",SD:" . $training_record->start_date . ",PED:" . $training_record->target_date . ",AED:" . $training_record->closure_date . ",ContractID:" . $training_record->contract_id;
				$note->save($link);
			}
		}
	}
}
?>