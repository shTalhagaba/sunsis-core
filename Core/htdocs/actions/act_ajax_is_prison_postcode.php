<?php
class ajax_is_prison_postcode implements IAction
{
	public function execute(PDO $link)
	{
		$postcode = isset($_REQUEST['postcode'])?$_REQUEST['postcode']:'';

		if($postcode == '')
		{
			throw new Exception('Missing or empty querystring argument, \'postcode\'');
		}

		$key = addslashes((string)$postcode);
		$sql = <<<HEREDOC
SELECT
	COUNT(postcode)
FROM
	central.lookup_prison_postcodes
WHERE
	postcode = '$key';
HEREDOC;
		$incidences = DAO::getSingleValue($link, $sql);

		// Return a boolean indication of prison postcode
		header('Content-Type: text/plain');
		echo $incidences > 0 ? '1':'0';
	}
}
?>