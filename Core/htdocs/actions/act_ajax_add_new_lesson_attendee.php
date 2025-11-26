<?php
class ajax_add_new_lesson_attendee implements IAction
{
	public function execute(PDO $link)
	{
		$lesson_id = isset($_REQUEST['lesson_id'])?$_REQUEST['lesson_id']:'';
		$attendee_id = isset($_REQUEST['attendee_id'])?$_REQUEST['attendee_id']:'';
		$firstnames = isset($_REQUEST['attendee_firstnames'])?$_REQUEST['attendee_firstnames']:'';
		$surname = isset($_REQUEST['attendee_surname'])?$_REQUEST['attendee_surname']:'';
		$dob = isset($_REQUEST['attendee_dob'])?$_REQUEST['attendee_dob']:'';
		$ni = isset($_REQUEST['attendee_ni'])?$_REQUEST['attendee_ni']:'';
		$postcode = isset($_REQUEST['attendee_postcode'])?$_REQUEST['attendee_postcode']:'';

		$firstnames = addslashes((string)$firstnames);
		$surname = addslashes((string)$surname);
		$ni = addslashes((string)$ni);
		$postcode = addslashes((string)$postcode);
        if($dob!='')
        {
            $inputDate = DateTime::createFromFormat('d/m/Y', $dob);
            $mySQLDate = $inputDate->format('Y-m-d');
        }


        if($attendee_id == '')
		{
			$sql = <<<SQL
			INSERT INTO
				attendees (firstnames, surname, dob, ni, postcode)
			VALUES
				('$firstnames', '$surname', '$mySQLDate', '$ni', '$postcode')
SQL;

			DAO::execute($link, $sql);

			$new_attendee_id= $link->lastInsertId();

		}
		else
		{
			$new_attendee_id = $attendee_id;
		}

		$sql = <<<SQL
			INSERT INTO
				lesson_extra_attendees (attendee_id, lesson_id)
			VALUES
				('$new_attendee_id', '$lesson_id')
SQL;

		DAO::execute($link, $sql);

		echo 'A new attendee record has been added to this register.';
		exit;
	}
}