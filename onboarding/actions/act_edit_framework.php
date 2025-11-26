<?php
class edit_framework implements IAction
{
	public function execute(PDO $link)
	{
		$framework_id = isset($_REQUEST['framework_id']) ? $_REQUEST['framework_id']:'';

		$_SESSION['bc']->add($link, "do.php?_action=edit_framework&framework_id={$framework_id}", "Add/ Edit Standard/ Programme");

		if($framework_id == '')
		{
			$vo = new Framework();
		}
		else
		{
			$vo = Framework::loadFromDatabase($link, $framework_id);
		}

		if($vo->framework_type!='')
			$A26_dropdown = "SELECT DISTINCT FworkCode, CONCAT(FworkCode, ' ', IssuingAuthorityTitle), NULL FROM lars201718.`Core_LARS_Framework` WHERE ProgType = '$vo->framework_type' ORDER BY FworkCode";
		else
			$A26_dropdown = "SELECT DISTINCT FworkCode, CONCAT(FworkCode, ' ', IssuingAuthorityTitle), NULL FROM lars201718.`Core_LARS_Framework` ORDER BY FworkCode;";

		$A26_dropdown = DAO::getResultset($link,$A26_dropdown);

		$A15_dropdown = "SELECT ProgType, LEFT(CONCAT(ProgType, ' ' , ProgTypeDesc),40), NULL FROM lars201718.CoreReference_LARS_ProgType_Lookup WHERE EffectiveTo IS NULL ORDER BY ProgType;";
		$A15_dropdown = DAO::getResultset($link,$A15_dropdown);

		$StandardCode_dropdown = "SELECT StandardCode, LEFT(CONCAT(StandardCode, ' ' , StandardName),40), NULL FROM lars201718.Core_LARS_Standard";
		$StandardCode_dropdown = DAO::getResultset($link, $StandardCode_dropdown);

		if($vo->framework_type!='' && $vo->framework_code!='')
			$PwayCode_dropdown = "SELECT PwayCode, LEFT(CONCAT(PwayCode, ' ' , PathwayName),40), NULL FROM lars201718.Core_LARS_Framework where FworkCode = {$vo->framework_code} and ProgType = {$vo->framework_type}";
		else
			$PwayCode_dropdown = "SELECT PwayCode, LEFT(CONCAT(PwayCode, ' ' , PathwayName),40), NULL FROM lars201718.Core_LARS_Framework";

		$PwayCode_dropdown = DAO::getResultset($link, $PwayCode_dropdown);

		$funding_stream_dropdown = "";
		if(SystemConfig::getEntityValue($link, 'module_scottish_funding'))
			$funding_stream_dropdown = DAO::getResultset($link, "SELECT id, description, null FROM lookup_funding_stream ORDER BY description ");

		$epa_organisations = DAO::getResultset($link, "SELECT EPA_ORG_ID, CONCAT(EPA_ORG_ID, ' - ', EP_Assessment_Organisations) AS description, UPPER(LEFT(EP_Assessment_Organisations, 1)) FROM central.`epa_organisations` ORDER BY EP_Assessment_Organisations;");
		$sql = <<<HEREDOC
SELECT
  id,
  CONCAT(
    COALESCE(title, ' '), ' ',
    `firstnames`, ' ',
    `surname`,
    ' (',
    COALESCE(`address1`, ''), ' ',
    COALESCE(`address4`, ' '), ' ',
    `postcode`, ') ',
    COALESCE(`email`, ''), ' '
  ) AS contact_name,
  NULL
FROM
  epa_org_assessors
WHERE epa_org_assessors.`EPA_Org_ID` = '$vo->epa_org_id'
ORDER BY epa_org_assessors.firstnames
;
HEREDOC;
		$epa_org_assessors = DAO::getResultset($link, $sql);

		

        $sql = <<<HEREDOC
SELECT users.id, CONCAT(users.`firstnames`, ' ', users.`surname`), CONCAT(organisations.`legal_name`, ' [', lookup_org_type.`org_type`, ']')
FROM users INNER JOIN organisations ON users.`employer_id` = organisations.`id` INNER JOIN lookup_org_type ON organisations.`organisation_type` = lookup_org_type.`id`
WHERE users.`type` = 8 ORDER BY organisations.`legal_name`, users.`firstnames`;
HEREDOC;
        $program_managers_ddl = DAO::getResultset($link, $sql);

	$tnp1_prices = is_null($vo->tnp1) ? [] : json_decode($vo->tnp1);
        $tnp1_costs = array_map(function ($ar) {return $ar->cost;}, $tnp1_prices);
		$tnp1_total = array_sum(array_map('floatval', $tnp1_costs));
        $tnp_total = ceil($tnp1_total + $vo->epa_price);

	$FundModel_dropdown = array(
			array('10', '10 Community Learning'),
			array('11', '11 Tailored Learning'),
			array('25', '25 16-19 EFA'),
			array('35', '35 Adult Skills'),
			array('36', '36 Apprenticeships (from 1 May 2017)'),
			array('37', '37 Skills Bootcamp'),
			array('38', '38 Adult Skills Fund'),
			array('70', '70 ESF'),
			array('81', '81 Other SFA'),
			array('82', '82 Other EFA'),
			array('99', '99 Non-funded')
		);	

		$StandardRefNo_dropdown = DAO::getResultset($link, "SELECT standard_code, CONCAT(standard_code, ' - ', apprenticeship_name), NULL FROM central.lookup_app_otj_requirements ORDER BY apprenticeship_name");

		// Presentation
		include('tpl_edit_framework.php');
	}
}
?>