<?php
class ajax_is_identifier_unique implements IAction
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
SELECT
	COUNT(identifier)
FROM
	((SELECT username AS identifier FROM users)
		UNION
	(SELECT	`group_name` as identifier FROM groups)) as t3
WHERE
	identifier = '$key';
HEREDOC;
		$incidences = DAO::getSingleValue($link, $sql);
		
		// Return a boolean indication of uniqueness
		header('Content-Type: text/plain');
		echo $incidences > 0 ? '0':'1';
	}
}
?>