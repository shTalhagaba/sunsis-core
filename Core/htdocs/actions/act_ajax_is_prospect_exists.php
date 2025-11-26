<?php
class ajax_is_prospect_exists implements IAction
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
		SELECT company FROM central.`emp_pool` WHERE company LIKE '%$key%';
HEREDOC;


		$matching_employers = "";

		$result = $link->query($sql);

		if($result)
		{
			while($row = $result->fetch())
			{
				$matching_employers .= $row['company'] . "\n";
			}
		}

		echo $matching_employers;
	}
}
?>