<?php
class edit_framework implements IAction
{
	public function execute(PDO $link)
	{
		// Validate data entry
		$framework_id = isset($_REQUEST['framework_id']) ? $_REQUEST['framework_id']:'';

		$_SESSION['bc']->add($link, "do.php?_action=edit_framework&framework_id=" . $framework_id, "Add/ Edit Framework");

		if($framework_id == '')
		{
			// New record
			$vo = new Framework();
		}
		else
		{
			$vo = Framework::loadFromDatabase($link, $framework_id);
		}

//		$sector_dropdown = "SELECT id, CONCAT(id, ' - ', description), null FROM lookup_sector_types ORDER BY id;";
//		$sector_dropdown = DAO::getResultset($link, $sector_dropdown);

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

		// Presentation
		include('tpl_edit_framework.php');
	}
}
?>