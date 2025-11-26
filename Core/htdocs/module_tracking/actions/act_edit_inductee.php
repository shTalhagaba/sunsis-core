<?php
class edit_inductee implements IAction
{
	public function execute(PDO $link)
	{
		$id = isset($_REQUEST['id'])?$_REQUEST['id']:'';

		if($id == '')
		{
			$inductee = new Inductee();
			$_SESSION['bc']->add($link, "do.php?_action=edit_inductee&id=" . $inductee->id, "Create Induction");
			$ddlEmployersLocations = array(array('', 'Select an employer to populate locations'));
			$ddlEmployerContacts = array();
			$ddlEmployers = DAO::getResultset($link, "SELECT id, legal_name, null FROM organisations WHERE organisation_type = '2' AND organisations.active = '1' ORDER BY legal_name");
			$inductee->learner_id = "O";
		}
		else
		{
			$inductee = Inductee::loadFromDatabase($link, $id);
			$_SESSION['bc']->add($link, "do.php?_action=edit_inductee&id=" . $inductee->id, "Edit Induction");
			$sql = <<<SQL
SELECT
  locations.id,
  CONCAT(COALESCE(locations.`full_name`), ' (',COALESCE(`address_line_1`,''),',',COALESCE(`postcode`,''), ')') AS detail,
  null
FROM
  locations
WHERE
	locations.id = '$inductee->employer_location_id'
ORDER BY full_name ;
SQL;
			$ddlEmployersLocations = DAO::getResultset($link, $sql);
			$ddlEmployerContacts = DAO::getResultset($link, "SELECT contact_id, CONCAT(
  COALESCE(contact_name),
  ' (',
  COALESCE(`contact_department`, ''),
  ', ',
  COALESCE(`contact_email`, ''),
  ', ',
  COALESCE(`contact_telephone`, ''),
  ', ',
  COALESCE(`contact_mobile`, ''),
  ')'
), null FROM organisation_contact WHERE org_id = '{$inductee->employer_id}' ORDER BY contact_name");
			$ddlEmployers = DAO::getResultset($link, "SELECT id, legal_name, null FROM organisations WHERE organisation_type = '2' AND organisations.active = '1' ORDER BY legal_name");
		}
		$ddlDeliveryLocations = InductionHelper::getDDLDeliveryLocations($link);
		$ddlInductionOwners = InductionHelper::getDDLInductionOwners($link);
		$ddlInductionAssessors = InductionHelper::getDDLInductionAssessors($link);
		$ddlAssignedAssessors = InductionHelper::getDDLInductionAssessors($link, 'assigned');
		$ddlAssignedCoordinators = InductionHelper::getDDLInductionCoordinators($link);
		$ddlYesNo = InductionHelper::getDDLYesNo();
		$ddlYesNoNA = InductionHelper::getDDLYesNo(true);
		$ddlInductionStatus = InductionHelper::getDDLInductionStatus();
		$ddlInductionHeadset = InductionHelper::getDDLInductionHeadset();
		$ddlIAG = InductionHelper::getDDLIAG();
		$ddlICT = InductionHelper::getDDLICT();

		if(count($inductee->inductions) == 0)
			$induction = new Induction();
		else
			$induction = $inductee->inductions[0];

		$inductionProgramme = $inductee->inductionProgramme;
		if(is_null($inductionProgramme))
			$inductionProgramme = new InductionProgramme();

		$ddlCourseProgramme = InductionHelper::getDDLInductionProgramme($link, $inductionProgramme->programme_id);
		
		$disabled = ' disabled="disabled" ';
		if($_SESSION['user']->isAdmin() || $_SESSION['user']->induction_access == 'W')
			$disabled = "";

		$expertProviderPilot = DAO::getSingleValue($link, "SELECT epp FROM organisations WHERE organisations.id = '{$inductee->employer_id}'");
		$expertProviderTransactor = DAO::getSingleValue($link, "SELECT ept FROM organisations WHERE organisations.id = '{$inductee->employer_id}'");

		//include_once('tpl_edit_inductee.php');
		if(true || DB_NAME == "am_baltic_demo")
		{
			include_once('tpl_edit_inductee_v2.php');
		}
		else
		{
			include_once('tpl_edit_inductee.php');
		}
	}
}