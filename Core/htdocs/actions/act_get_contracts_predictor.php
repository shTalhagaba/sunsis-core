<?php
class get_contracts_predictor implements IAction
{
	public function execute(PDO $link)
	{
		$destination = isset($_REQUEST['destination']) ? $_REQUEST['destination']:'';
		$stage = isset($_REQUEST['stage']) ? $_REQUEST['stage']:'';

		//if((DB_NAME=="am_ligauk") && $destination == 'funding_prediction' && !SOURCE_BLYTHE_VALLEY && !SOURCE_HOME)
		//	pre('Funding Predictor is currently undergoing routine maintenance. Sorry for any inconvenience.');

		$_SESSION['bc']->index=0;
		$_SESSION['bc']->add($link, "do.php?_action=get_contracts_predictor&destination=" . $destination, "Select Contracts");

		//if($destination=="learner_import" or $destination=="bulk_update")
		//{
			$view = GetContractsPredictor::getInstance();
			$view->refresh($link, $_REQUEST);
		//}

		$submissions = DAO::getResultset($link,"SELECT id, description from lookup_er_submissions");
		$contract_years = DAO::getResultset($link,"SELECT distinct contract_year, contract_year from contracts order by contract_year desc");
		$submission = DAO::getSingleValue($link, "SELECT right(submission,2) FROM central.`lookup_submission_dates` WHERE CURDATE() BETWEEN census_start_date AND census_end_date;");
		$submission = (int)$submission;
		require_once('tpl_get_contracts_predictor.php');
	}
}
?>
