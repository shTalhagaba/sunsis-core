<?php
class email_absence_notification implements IAction
{
	public function execute(PDO $link)
	{
	
		$done=0;
	
		$lesson_id = isset($_GET['lesson_id']) ? $_GET['lesson_id'] : '';
	
		$reg = new Register($lesson_id, $link);
		$reg->load($link);

		foreach($reg as $entry) /* @var $entry RegisterEntry */
		{
			if($entry->entry==4 || $entry->entry==5)
			{

			
				$tr = TrainingRecord::loadFromDatabase($link, $entry->pot_id);
				$loc = Location::loadFromDatabase($link, $tr->employer_location_id);
				$loc2 = Location::loadFromDatabase($link, $tr->provider_location_id);
				
				$to = $loc->contact_email;
				//$to = "khushnoodahmedkhan@hotmail.com";
				$from = $loc2->contact_email;
				//$from = "khushnood.khan@perspective-uk.com";
				$subject = "Absence Notification";

				$message = DAO::getSingleValue($link, "select value from configuration where entity='absence_notification'");
				
				if($to=='')
					throw new Exception("School/ Employer's Email address is missing");
					
				if($from=='')
					throw new Exception("Provider's Email address is missing");
					
				if($message=='')
					throw new Exception("Email contents are missing");
				

				$m1 =	"To\n\n" . $loc->contact_name . "\n\n This email is to confirm non attendance of " . $entry->student_firstnames . ' ' . $entry->student_surname . " for today's training";
				$m2 = 	"\n\n Can you confirm non attendance via email before the end of the day.\n\n Kind Regards \n\n";

				$a = Array();
				$a = explode(",", $message);
				
				$message = implode("\n", $a);
				
				$message = $m1 . $m2 . $message;	

				mail($to, $subject, $message, "From: $from\r\nCc: $from", "-f ".$from);
				$done = 1;

			}
		}

		if($done==1)
			echo "<html><body><script language='javascript'>alert('Email notifications have been sent');</script></body></html>";
		else
			echo "<html><body><script language='javascript'>alert('Email notifications could not be sent');</script></body></html>";
		
		http_redirect($_SESSION['bc']->getPrevious());
	}
}
?>