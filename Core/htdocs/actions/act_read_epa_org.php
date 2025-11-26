<?php
class read_epa_org implements IAction
{
	public function execute(PDO $link)
	{
		$EPA_ORG_ID = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';
		if ($EPA_ORG_ID == '')
			throw new Exception('Missing querystring argument: EPA_ORG_ID');

		$EPA_Org = DAO::getObject($link, "SELECT * FROM central.epa_organisations WHERE EPA_ORG_ID = '{$EPA_ORG_ID}'");
		if (!isset($EPA_Org->EPA_ORG_ID))
			throw new Exception('Could not found EPA Organisation with ID: ' . $EPA_ORG_ID);

		$_SESSION['bc']->add($link, "do.php?_action=read_epa_org&id=" . $EPA_ORG_ID, "EPA Organisation");

		$EPA_Org_Standards = DAO::getSingleColumn($link, "SELECT Standard FROM central.epa_orgs_standards WHERE EPA_ORG_ID = '{$EPA_ORG_ID}'");

		$sql = <<<SQL
SELECT
  Core_LARS_Standard.*,
  (SELECT `SectorSubjectAreaTier1Desc` FROM lars201718.CoreReference_LARS_SectorSubjectAreaTier1_Lookup WHERE CoreReference_LARS_SectorSubjectAreaTier1_Lookup.`SectorSubjectAreaTier1` = Core_LARS_Standard.`SectorSubjectAreaTier1`) AS SSA1,
  (SELECT `SectorSubjectAreaTier2Desc` FROM lars201718.CoreReference_LARS_SectorSubjectAreaTier2_Lookup WHERE CoreReference_LARS_SectorSubjectAreaTier2_Lookup.`SectorSubjectAreaTier2` = Core_LARS_Standard.`SectorSubjectAreaTier2`) AS SSA2
FROM
  lars201718.`Core_LARS_Standard` INNER JOIN central.`epa_orgs_standards`
  ON LOWER(CONCAT(StandardName,' - Level ',NotionalEndLevel)) = LOWER(epa_orgs_standards.`Standard`)
WHERE
     epa_orgs_standards.EPA_ORG_ID = '{$EPA_ORG_ID}'
ORDER BY
     epa_orgs_standards.Standard
;
SQL;
		$EPA_Org_Standards = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);

		$EPA_Org_Assessors = DAO::getResultset($link, "SELECT * FROM epa_org_assessors WHERE EPA_Org_ID = '{$EPA_ORG_ID}' ORDER BY firstnames", DAO::FETCH_ASSOC);

		include_once('tpl_read_epa_org.php');
	}
}
