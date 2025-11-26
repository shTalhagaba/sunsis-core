<?php
class ajax_is_identifier_unique implements IAction
{
	public function execute(PDO $link)
	{
		$identifier = isset($_REQUEST['identifier'])?$_REQUEST['identifier']:'';
		$onboarding = isset($_REQUEST['onboarding'])?$_REQUEST['onboarding']:'';

		if($identifier == '')
		{
			throw new Exception('Missing or empty querystring argument, \'identifier\'');
		}
		
		$key = addslashes($identifier);
		if($onboarding == 'yes')
        {
            $sql = <<<HEREDOC
SELECT
	COUNT(identifier)
FROM
	(SELECT home_email AS identifier FROM ob_learners) as t1
WHERE
	identifier = '$key';
HEREDOC;
        }
		else
        {
            $sql = <<<HEREDOC
SELECT
	COUNT(identifier)
FROM
	(SELECT username AS identifier FROM users) as t1
WHERE
	identifier = '$key';
HEREDOC;
        }
		$incidences = DAO::getSingleValue($link, $sql);
		
		// Return a boolean indication of uniqueness
		header('Content-Type: text/plain');
		echo $incidences > 0 ? '0':'1';
	}
}
?>