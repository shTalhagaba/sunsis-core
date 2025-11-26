<?php
class baltic_ajax_check_candidate_mandatory_info implements IAction
{
	public function execute(PDO $link)
	{
		$candidate_id = isset($_REQUEST['candidate_id'])?$_REQUEST['candidate_id']:'';
		$sql = "SELECT firstnames, surname, gender, dob, county, postcode FROM candidate WHERE id = '" . $candidate_id . "' ";

		$resultingString = '';
		$st = $link->query($sql);
		if($st)
		{
			while($row = $st->fetch())
			{
				if($row['firstnames'] == '')
					$resultingString .= 'First Name' . PHP_EOL;
				if($row['surname'] == '')
					$resultingString .= 'Surname' . PHP_EOL;
				if($row['gender'] == '')
					$resultingString .= 'Gender' . PHP_EOL;
				if($row['dob'] == '' || $row['dob'] == '0000-00-00')
					$resultingString .= 'Date of Birth' . PHP_EOL;
				if($row['county'] == '')
					$resultingString .= 'County' . PHP_EOL;
				if($row['postcode'] == '')
					$resultingString .= 'Postcode' . PHP_EOL;
			}
		}
		else
		{
			throw new DatabaseException($link, $sql);
		}
		echo($resultingString);
	}
}
?>