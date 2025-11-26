<?php
class ajax_get_interview_log implements IUnauthenticatedAction
{
	public function execute(PDO $link)
	{
		$interview_id = isset($_REQUEST['interview_id'])?$_REQUEST['interview_id']: '';
		if($interview_id == '')
			throw new Exception("No Interview ID provided");

		echo Note::renderNotes($link, "interviews", $interview_id);


	}
}