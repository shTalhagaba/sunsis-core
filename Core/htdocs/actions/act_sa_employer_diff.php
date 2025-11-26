<?php
class sa_employer_diff extends ActionController
{

	public function indexAction(PDO $link)
	{
		if (!$_SESSION['user']->isAdmin()) {
			throw new UnauthorizedException();
		}

		include('smartassessor/tpl_sa_employer_diff.php');
	}


	public function renderContentAction(PDO $link)
	{
		if (!$_SESSION['user']->isAdmin()) {
			throw new UnauthorizedException();
		}
		if (!SystemConfig::get("smartassessor.soap.enabled")) {
			echo '<p style="font-weight: bold">SmartAssessor integration is not enabled for this Sunesis site</b></p>';
			return;
		}

		$sa = new SmartAssessor();
		$saRecords = $sa->getEmployers();
		$this->_createTempTables($link);
		DAO::multipleRowInsert($link, 'tmp_sa_employers', $saRecords);

		$duplicatedLearners = $this->_getDuplicatedLocations($link);

		$this->_renderBrokenLinksSunesis($link);
		$this->_renderBrokenLinksSmartAssessor($link);

		echo '<div style="width:800px; margin-left: auto; margin-right: auto;">';
		echo '<h3>Differences between linked records</h3>';
		echo '</div>';

		$type = Organisation::TYPE_EMPLOYER;
		$sql = <<<SQL
SELECT
	locations.smart_assessor_id,
	locations.id as `locations_id`,
	organisations.legal_name,
	organisations.edrs,
	locations.address_line_1,
	locations.address_line_2,
	locations.address_line_3,
	locations.address_line_4,
	locations.postcode,
	locations.telephone,
	locations.contact_name,
	locations.contact_email,

	tmp_sa_employers.`SmartAssessorId`,
	tmp_sa_employers.`SunesisId`,
	tmp_sa_employers.`Name`,
	tmp_sa_employers.`EdsId`,
	tmp_sa_employers.`AddressLine1`,
	tmp_sa_employers.`AddressLine2`,
	tmp_sa_employers.`AddressTown`,
	tmp_sa_employers.`AddressCounty`,
	tmp_sa_employers.`AddressPostCode`,
	tmp_sa_employers.`Telephone`,
	tmp_sa_employers.`KeyContactName`,
	tmp_sa_employers.`KeyContactEmail`
FROM
	organisations INNER JOIN locations
		ON organisations.id = locations.organisations_id
	-- Imported Smart Assessor employers
	INNER JOIN tmp_sa_employers
		ON locations.id = tmp_sa_employers.SunesisId
WHERE
	-- Employers only
	organisations.organisation_type = {$type}
	-- Linked records with differing field values
	AND (
		TRIM(organisations.legal_name) != TRIM(tmp_sa_employers.Name)
		OR TRIM(organisations.edrs) != TRIM(tmp_sa_employers.EdsId)
		OR TRIM(locations.address_line_1) != TRIM(tmp_sa_employers.AddressLine1)
		OR TRIM(locations.address_line_2) != TRIM(tmp_sa_employers.AddressLine2)
		OR TRIM(locations.address_line_3) != TRIM(tmp_sa_employers.AddressTown)
		OR TRIM(locations.address_line_4) != TRIM(tmp_sa_employers.AddressCounty)
		OR TRIM(locations.postcode) != TRIM(tmp_sa_employers.AddressPostCode)
		OR TRIM(locations.telephone) != TRIM(tmp_sa_employers.Telephone)
		OR TRIM(locations.contact_name) != TRIM(tmp_sa_employers.KeyContactName)
		OR TRIM(locations.contact_email) != TRIM(tmp_sa_employers.KeyContactEmail) )
ORDER BY
	organisations.legal_name, locations.postcode
SQL;

		echo '<div>';

		// Sort through
		$st = DAO::query($link, $sql);
		while ($row = $st->fetch(PDO::FETCH_ASSOC)) {
			$diffs = array();
			$this->_compareFields($row, 'Name', 'legal_name', $diffs);
			$this->_compareFields($row, 'EdsId', 'edrs', $diffs);
			$this->_compareFields($row, 'AddressLine1', 'address_line_1', $diffs);
			$this->_compareFields($row, 'AddressLine2', 'address_line_2', $diffs);
			$this->_compareFields($row, 'AddressTown', 'address_line_3', $diffs);
			$this->_compareFields($row, 'AddressCounty', 'address_line_4', $diffs);
			$this->_compareFields($row, 'AddressPostCode', 'postcode', $diffs);
			$this->_compareFields($row, 'Telephone', 'telephone', $diffs);
			$this->_compareFields($row, 'KeyContactName', 'contact_name', $diffs);
			$this->_compareFields($row, 'KeyContactEmail', 'contact_email', $diffs);

			echo <<<HTML
<table class="resultset CompareRecords" cellspacing="0" cellpadding="4">
HTML;
			echo '<thead>';
			echo '<th>&nbsp;</th><th>ID</th>';
			if (in_array('Name', $diffs)) {
				echo '<th>Name</th>';
			}
			if (in_array('EdsId', $diffs)) {
				echo '<th>Eds Id</th>';
			}
			if (in_array('AddressLine1', $diffs)) {
				echo '<th>Address Line 1</th>';
			}
			if (in_array('AddressLine2', $diffs)) {
				echo '<th>Address Line 2</th>';
			}
			if (in_array('AddressTown', $diffs)) {
				echo '<th>Address Line 3</th>';
			}
			if (in_array('AddressCounty', $diffs)) {
				echo '<th>Address Line 4</th>';
			}
			if (in_array('AddressPostCode', $diffs)) {
				echo '<th>Postcode</th>';
			}
			if (in_array('Telephone', $diffs)) {
				echo '<th>Telephone</th>';
			}
			if (in_array('KeyContactName', $diffs)) {
				echo '<th>Contact Name</th>';
			}
			if (in_array('KeyContactEmail', $diffs)) {
				echo '<th>Contact Email</th>';
			}

			echo '</tr>';
			echo '</thead>';
			echo '<tbody>';

			echo '<tr>';
			echo '<td align="left" style="font-weight:bold">Sunesis</td>';
			echo '<td align="left">', htmlspecialchars((string)$row['locations_id']), '</td>';
			echo $this->_cell($row, 'legal_name', $diffs);
			echo $this->_cell($row, 'edrs', $diffs);
			echo $this->_cell($row, 'address_line_1', $diffs);
			echo $this->_cell($row, 'address_line_2', $diffs);
			echo $this->_cell($row, 'address_line_3', $diffs);
			echo $this->_cell($row, 'address_line_4', $diffs);
			echo $this->_cell($row, 'postcode', $diffs);
			echo $this->_cell($row, 'telephone', $diffs);
			echo $this->_cell($row, 'contact_name', $diffs);
			echo $this->_cell($row, 'contact_email', $diffs);
			echo '</tr>';

			echo '<tr>';
			echo '<td align="left" style="font-weight:bold">SmartAssessor</td>';
			echo '<td align="left">', htmlspecialchars((string)$row['SmartAssessorId']), '</td>';
			echo $this->_cell($row, 'Name', $diffs);
			echo $this->_cell($row, 'EdsId', $diffs);
			echo $this->_cell($row, 'AddressLine1', $diffs);
			echo $this->_cell($row, 'AddressLine2', $diffs);
			echo $this->_cell($row, 'AddressTown', $diffs);
			echo $this->_cell($row, 'AddressCounty', $diffs);
			echo $this->_cell($row, 'AddressPostCode', $diffs);
			echo $this->_cell($row, 'Telephone', $diffs);
			echo $this->_cell($row, 'KeyContactName', $diffs);
			echo $this->_cell($row, 'KeyContactEmail', $diffs);
			echo '</tr>';

			echo '</tbody></table>';
		}

		echo '</div>';

	}

	private function _renderBrokenLinksSunesis(PDO $link)
	{
		$sql = <<<SQL
SELECT
	locations.id,
	locations.smart_assessor_id,
	organisations.legal_name,
	locations.postcode
FROM
	organisations INNER JOIN locations
		ON organisations.id = locations.organisations_id
	LEFT OUTER JOIN tmp_sa_employers
		ON locations.smart_assessor_id = tmp_sa_employers.SmartAssessorId
WHERE
	locations.smart_assessor_id IS NOT NULL
	AND tmp_sa_employers.SunesisId IS NULL
SQL;
		$rs = DAO::query($link, $sql);

		echo '<div style="width:800px; margin-left: auto; margin-right: auto;">';
		echo '<h3>Broken Links: Records in Sunesis linked to a missing record in Smart Assessor</h3>';
		echo '<table class="resultset" cellspacing="0" cellpadding="4" width="100%">';
		echo '<col width="25%"/><col width="25%"/><col width="25%"/><col width="25%"/>';
		echo '<tr><th>Sunesis ID</th><th>SmartAssessor ID</th><th>Name</th><th>Postcode</th></tr>';
		foreach ($rs as $row) {
			echo '<tr>';
			echo '<td align="left">', htmlspecialchars((string)$row['id']), '</td>';
			echo '<td class="BrokenLinkId" align="left">', htmlspecialchars((string)$row['smart_assessor_id']), '</td>';
			echo '<td align="left">', htmlspecialchars((string)$row['legal_name']), '</td>';
			echo '<td align="left">', htmlspecialchars((string)$row['postcode']), '</td>';
			echo '</tr>';
		}
		echo '</table>';
		echo '</div>';
	}


	private function _renderBrokenLinksSmartAssessor(PDO $link)
	{
		$sql = <<<SQL
SELECT
	tmp_sa_employers.Name,
	tmp_sa_employers.SunesisId,
	tmp_sa_employers.SmartAssessorId,
	tmp_sa_employers.AddressPostCode
FROM
	tmp_sa_employers LEFT OUTER JOIN locations
		ON tmp_sa_employers.SunesisId = locations.id
WHERE
	locations.id IS NULL
	AND tmp_sa_employers.SunesisId IS NOT NULL
SQL;
		$rs = DAO::query($link, $sql);

		echo '<div style="width:800px; margin-left: auto; margin-right: auto;">';
		echo '<h3>Broken Links: Records in Smart Assessor linked to a missing record in Sunesis</h3>';
		echo '<table class="resultset" cellspacing="0" cellpadding="4" width="100%">';
		echo '<col width="25%"/><col width="25%"/><col width="25%"/><col width="25%"/>';
		echo '<tr><th>Sunesis ID</th><th>SmartAssessor ID</th><th>Name</th><th>Postcode</th></tr>';
		foreach ($rs as $row) {
			echo '<tr>';
			echo '<td class="BrokenLinkId" align="left">', htmlspecialchars((string)$row['SunesisId']), '</td>';
			echo '<td align="left">', htmlspecialchars((string)$row['SmartAssessorId']), '</td>';
			echo '<td align="left">', htmlspecialchars((string)$row['Name']), '</td>';
			echo '<td align="left">', htmlspecialchars((string)$row['AddressPostCode']), '</td>';
			echo '</tr>';
		}
		echo '</table>';
		echo '</div>';
	}


	private function _createTempTables(PDO $link)
	{
		DAO::execute($link, "DROP TEMPORARY TABLE IF EXISTS tmp_sa_employers");
		$sql = <<<SQL
CREATE TEMPORARY TABLE tmp_sa_employers (
	`SmartAssessorId` INTEGER NOT NULL,
	`SunesisId` BIGINT,
	`Name` VARCHAR(100),
	`EdsId` VARCHAR(15),
	`AddressLine1` VARCHAR(100),
	`AddressLine2` VARCHAR(100),
	`AddressTown` VARCHAR(100),
	`AddressCounty` VARCHAR(100),
	`AddressPostCode` VARCHAR(12),
	`Telephone` VARCHAR(20),
	`KeyContactName` VARCHAR(100),
	`KeyContactEmail` VARCHAR(100),
	PRIMARY KEY (`SmartAssessorId`),
	KEY (`SunesisId`),
	KEY (`AddressPostCode`)
);
SQL;
		DAO::execute($link, $sql);

	}


	private function _getDuplicatedLocations(PDO $link)
	{
		$sql = <<<SQL
SELECT
	GROUP_CONCAT(locations.id) AS `location_id`
FROM
	locations
WHERE
	locations.postcode IS NOT NULL
GROUP BY
	locations.`organisations_id`, locations.`postcode`
HAVING
	COUNT(*) > 1
SQL;
		$raw = DAO::getSingleColumn($link, $sql);

		$ids = array();
		foreach ($raw as $r) {
			$ids = array_merge($ids, explode(',', $r));
		}

		return $ids;
	}


	private function _compareFields(array $row, $field1, $field2, array &$diffs)
	{
		if (trim($row[$field1]) != trim($row[$field2])) {
			$diffs[] = $field1;
			$diffs[] = $field2;
		}
	}


	private function _cell(array $row, $field, $diffs)
	{
		if (in_array($field, $diffs)) {
			return '<td align="left">' . htmlspecialchars((string)$row[$field]) . '</td>';
		}
	}
}