<?php
class get_contracts implements IAction
{
	public function execute(PDO $link)
	{
		$_SESSION['bc']->index = 0;
		$_SESSION['bc']->add($link, "do.php?_action=get_contracts", "Download Batch File");

		$view = GetContracts::getInstance();
		$view->refresh($link, $_REQUEST);

		$submissions = DAO::getResultset($link, "SELECT id, description from lookup_er_submissions");
		$contract_years = DAO::getResultset($link, "SELECT distinct contract_year, contract_year from contracts order by contract_year desc");
		$cy = DAO::getSingleValue($link, "select max(contract_year) from contracts");

		if (empty($cy)) {
			$submission = 0; // or set a default value that makes sense in your app
		} else {
			$submission = DAO::getSingleValue(
				$link,
				"SELECT right(submission,2)
         FROM central.`lookup_submission_dates`
         WHERE contract_year = '$cy'
         AND CURDATE() BETWEEN start_submission_date AND last_submission_date"
			);
		}
		$submission = (int)$submission;
		require_once('tpl_get_contracts.php');
	}
}