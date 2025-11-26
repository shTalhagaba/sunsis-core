<?php
class ajax_remove_new_lesson_attendee implements IAction
{
	public function execute(PDO $link)
	{
		$lesson_id = isset($_REQUEST['lesson_id'])?$_REQUEST['lesson_id']:'';
		$attendee_id = isset($_REQUEST['attendee_id'])?$_REQUEST['attendee_id']:'';

		if($lesson_id == '' || $attendee_id == '')
			throw new Exception('No lesson id or attendee id specified');

		$sql = <<<SQL
			DELETE FROM
				lesson_extra_attendees
			WHERE
				attendee_id = '$attendee_id' AND lesson_id = '$lesson_id'
SQL;

		DAO::execute($link, $sql);

		echo 'Attendee record has been removed from this register.';
		exit;
	}
}