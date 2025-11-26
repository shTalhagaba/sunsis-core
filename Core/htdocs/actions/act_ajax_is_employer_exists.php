<?php
class ajax_is_employer_exists implements IAction
{
	public function execute(PDO $link)
	{
		$identifier = isset($_REQUEST['identifier'])?$_REQUEST['identifier']:'';

		if($identifier == '')
		{
			throw new Exception('Missing or empty querystring argument, \'identifier\'');
		}

		$key = addslashes((string)$identifier);
		$sql = <<<HEREDOC
		SELECT legal_name FROM `organisations` WHERE legal_name LIKE '%$key%';
HEREDOC;


		$matching_employers = "";

		$result = $link->query($sql);

		if($result)
		{
			while($row = $result->fetch())
			{
				$matching_employers .= $row['legal_name'] . "\n";
			}
		}

		echo $matching_employers;
	}
}
?>