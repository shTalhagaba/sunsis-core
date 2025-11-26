<?php
class view_help_destinations implements IAction
{
	public function execute(PDO $link)
	{
		header('Content-type: application/pdf');
		readfile('./docs/SunesisHowToSheetRecordLearnerDestinations.pdf');
		exit;
	}

}
?>