<?php
class view_inductee implements IAction
{
	public function execute(PDO $link)
	{
		$id = isset($_REQUEST['id'])?$_REQUEST['id']:'';

		if($id == '')
		{
			throw new Exception('Missing querystring argument: induction learner id');
		}

		$inductee = Inductee::loadFromDatabase($link, $id);
		if(is_null($inductee))
			throw new Exception('No record found with id: '.$id);

		$_SESSION['bc']->add($link, "do.php?_action=view_inductee&id=" . $inductee->id, "View Induction Learner");

		$employer = Organisation::loadFromDatabase($link, $inductee->employer_id);
		$employer_location = Location::loadFromDatabase($link, $inductee->employer_location_id);
		$listGender = InductionHelper::getListGender();
		$listDeliveryLocation = InductionHelper::getListDeliveryLocations($link);
		$listMIAP = InductionHelper::getListMIAP();
		$listHeadset = InductionHelper::getListInductionHeadset();
		$listYesNo = InductionHelper::getListYesNo();
		$listYesNoNA = InductionHelper::getListYesNo(true);
		$listProgrammes = InductionHelper::getListInductionProgramme($link);
		$listIAG = InductionHelper::getListIAG();
		$listICT = InductionHelper::getListICT();
		$listJoinTime = InductionHelper::getListAMPM();
		$listSLAReceived = InductionHelper::getListSLAReceived();
		$listEWFDAssessment = InductionHelper::getListYesNoExempt();
		$listDiplomaWSDelivery = InductionHelper::getListQualityCategory();
		$listCommitmentStatement = InductionHelper::getListCommitmentStatement();
		$listEligibilityTestStatus = InductionHelper::getListEligibilityTestStatus();
		$listLearnerID = InductionHelper::getListLearnerID();

		if(count($inductee->inductions) == 0)
			$induction = new Induction();
		else
			$induction = $inductee->inductions[0];

		$inductionProgramme = $inductee->inductionProgramme;
		if(is_null($inductionProgramme))
			$inductionProgramme = new InductionProgramme();

		$days_in_current_induction_status = '';
		if($induction->id != '')
			$days_in_current_induction_status = DAO::getSingleValue($link, "SELECT DATEDIFF(CURRENT_DATE(),modified) FROM notes WHERE parent_id = '{$induction->id}' AND LOCATE('[Induction Status]', note) > 0 ORDER BY id DESC LIMIT 1;");
		if($days_in_current_induction_status == '0' || $days_in_current_induction_status == '')
			$days_in_current_induction_status = '1';

		include_once('tpl_view_inductee.php');
	}
}