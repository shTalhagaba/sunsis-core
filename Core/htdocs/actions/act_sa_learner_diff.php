<?php
class sa_learner_diff extends ActionController
{

	public function indexAction(PDO $link)
	{
		if (!$_SESSION['user']->isAdmin()) {
			throw new UnauthorizedException();
		}

		include('smartassessor/tpl_sa_learner_diff.php');
	}


	public function renderContentAction(PDO $link)
	{
	    $type = User::TYPE_LEARNER;
		if (!$_SESSION['user']->isAdmin()) {
			throw new UnauthorizedException();
		}
		if (!SystemConfig::get("smartassessor.soap.enabled")) {
			echo '<p style="font-weight: bold">SmartAssessor integration is not enabled for this Sunesis site</b></p>';
			return;
		}

		$sa = new SmartAssessor();
		$saRecords = $sa->getLearners();
		$this->_createTempTables($link);
		DAO::multipleRowInsert($link, 'tmp_sa_learners', $saRecords);

		$duplicatedLearners = $this->_getDuplicatedLearners($link);

		$this->_renderBrokenLinksSunesis($link);
		$this->_renderBrokenLinksSmartAssessor($link);

		echo '<div style="width:800px; margin-left: auto; margin-right: auto;">';
		echo '<h3>Differences between linked records</h3>';
		echo '</div>';

		$sql = <<<SQL
SELECT
	users.id AS `users_id`,
	users.employer_location_id,
	locations.smart_assessor_id,
	users.firstnames,
	users.surname,
	users.l45,
	users.ni,
	users.dob,
	users.gender,
	users.l24,
	users.home_email,
	users.home_telephone,
	users.home_mobile,
	users.l15,
	users.l16,
	users.home_address_line_1,
	users.home_address_line_2,
	users.home_address_line_3,
	users.home_address_line_4,
	users.home_postcode,

	tmp_sa_learners.SmartAssessorId,
	tmp_sa_learners.SunesisId,
	tmp_sa_learners.GivenNames,
	tmp_sa_learners.FamilyName,
	tmp_sa_learners.ULN,
	tmp_sa_learners.NINumber,
	tmp_sa_learners.DateOfBirth,
	tmp_sa_learners.Sex,
	tmp_sa_learners.Domicile,
	tmp_sa_learners.Email,
	tmp_sa_learners.TelNumber,
	tmp_sa_learners.Mobile,
	tmp_sa_learners.LlddDisability,
	tmp_sa_learners.LlddLearningDifficulty,
	tmp_sa_learners.HomeAddressLine1,
	tmp_sa_learners.HomeAddressLocality,
	tmp_sa_learners.HomeAddressTown,
	tmp_sa_learners.HomeAddressCounty,
	tmp_sa_learners.HomeAddressPostCode
FROM
	users INNER JOIN organisations INNER JOIN locations
		ON users.employer_id = organisations.id
		AND users.employer_location_id = locations.id
	-- Learners on synchronised contracts only
	/*INNER JOIN tmp_contract_learners
		ON users.id = tmp_contract_learners.id*/
	-- Imported Smart Assessor learners
	INNER JOIN tmp_sa_learners
		ON users.id = tmp_sa_learners.SunesisId
WHERE
	-- Learners only
	users.type = $type
	-- Linked records with differing field values
	AND (
		TRIM(users.surname) != TRIM(tmp_sa_learners.FamilyName)
		OR TRIM(users.firstnames) != TRIM(tmp_sa_learners.GivenNames)
		OR TRIM(users.l45) != TRIM(tmp_sa_learners.ULN)
		OR TRIM(users.ni) != TRIM(tmp_sa_learners.NINumber)
		OR TRIM(users.dob) != TRIM(tmp_sa_learners.DateOfBirth)
		OR TRIM(users.gender) != TRIM(tmp_sa_learners.Sex)
		OR TRIM(users.l24) != TRIM(tmp_sa_learners.Domicile)
		OR TRIM(users.home_email) != TRIM(tmp_sa_learners.Email)
		OR TRIM(users.home_telephone) != TRIM(tmp_sa_learners.TelNumber)
		OR TRIM(users.home_mobile) != TRIM(tmp_sa_learners.Mobile)
		OR TRIM(users.l15) != TRIM(tmp_sa_learners.LlddDisability)
		OR TRIM(users.l16) != TRIM(tmp_sa_learners.LlddLearningDifficulty)
		OR TRIM(users.home_address_line_1) != TRIM(tmp_sa_learners.HomeAddressLine1)
		OR TRIM(users.home_address_line_2) != TRIM(tmp_sa_learners.HomeAddressLocality)
		OR TRIM(users.home_address_line_3) != TRIM(tmp_sa_learners.HomeAddressTown)
		OR TRIM(users.home_address_line_4) != TRIM(tmp_sa_learners.HomeAddressCounty)
		OR TRIM(users.home_postcode) != TRIM(tmp_sa_learners.HomeAddressPostCode) )
ORDER BY
	users.employer_id, users.employer_location_id, users.surname, users.firstnames
SQL;

		echo '<div>';

		// Sort through
		$st = DAO::query($link, $sql);
		while ($row = $st->fetch(PDO::FETCH_ASSOC)) {
			$diffs = array();
			$this->_compareFields($row, 'FamilyName', 'surname', $diffs);
			$this->_compareFields($row, 'GivenNames', 'firstnames', $diffs);
			$this->_compareFields($row, 'ULN', 'l45', $diffs);
			$this->_compareFields($row, 'NINumber', 'ni', $diffs);
			$this->_compareFields($row, 'DateOfBirth', 'dob', $diffs);
			$this->_compareFields($row, 'Sex', 'gender', $diffs);
			$this->_compareFields($row, 'Domicile', 'l24', $diffs);
			$this->_compareFields($row, 'Email', 'home_email', $diffs);
			$this->_compareFields($row, 'TelNumber', 'home_telephone', $diffs);
			$this->_compareFields($row, 'Mobile', 'home_mobile', $diffs);
			$this->_compareFields($row, 'LlddDisability', 'l15', $diffs);
			$this->_compareFields($row, 'LlddLearningDifficulty', 'l16', $diffs);
			$this->_compareFields($row, 'HomeAddressLine1', 'home_address_line_1', $diffs);
			$this->_compareFields($row, 'HomeAddressLocality', 'home_address_line_2', $diffs);
			$this->_compareFields($row, 'HomeAddressTown', 'home_address_line_3', $diffs);
			$this->_compareFields($row, 'HomeAddressCounty', 'home_address_line_4', $diffs);
			$this->_compareFields($row, 'HomeAddressPostCode', 'home_postcode', $diffs);

			echo <<<HTML
<table class="resultset CompareRecords" cellspacing="0" cellpadding="4">
HTML;
			echo '<thead>';
			echo '<th>&nbsp;</th><th>ID</th><th>GivenNames</th><th>FamilyName</th>';
			if (in_array('ULN', $diffs)) {
				echo '<th>ULN</th>';
			}
			if (in_array('NINumber', $diffs)) {
				echo '<th>NI</th>';
			}
			if (in_array('DateOfBirth', $diffs)) {
				echo '<th>DOB</th>';
			}
			if (in_array('Sex', $diffs)) {
				echo '<th>Sex</th>';
			}
			if (in_array('Domicile', $diffs)) {
				echo '<th>Domicile</th>';
			}
			if (in_array('Email', $diffs)) {
				echo '<th>Email</th>';
			}
			if (in_array('TelNumber', $diffs)) {
				echo '<th>Telephone</th>';
			}
			if (in_array('Mobile', $diffs)) {
				echo '<th>Mobile</th>';
			}
			if (in_array('LlddDisability', $diffs)) {
				echo '<th>Disability</th>';
			}
			if (in_array('LlddLearningDifficulty', $diffs)) {
				echo '<th>Learning Difficulty</th>';
			}
			if (in_array('HomeAddressLine1', $diffs)) {
				echo '<th>HomeAddress1</th>';
			}
			if (in_array('HomeAddressLocality', $diffs)) {
				echo '<th>HomeAddress2</th>';
			}
			if (in_array('HomeAddressTown', $diffs)) {
				echo '<th>HomeAddress3</th>';
			}
			if (in_array('HomeAddressCounty', $diffs)) {
				echo '<th>HomeAddress4</th>';
			}
			if (in_array('HomeAddressPostCode', $diffs)) {
				echo '<th>HomePostCode</th>';
			}

			echo '</tr>';
			echo '</thead>';
			echo '<tbody>';

			echo '<tr>';
			echo '<td align="left" style="font-weight:bold">Sunesis</td>';
			echo '<td align="left">', htmlspecialchars((string)$row['users_id']), '</td>';
			echo '<td align="left">', htmlspecialchars((string)$row['firstnames']), '</td>';
			echo '<td align="left">', htmlspecialchars((string)$row['surname']), '</td>';
			echo $this->_cell($row, 'l45', $diffs);
			echo $this->_cell($row, 'ni', $diffs);
			echo $this->_cell($row, 'dob', $diffs);
			echo $this->_cell($row, 'gender', $diffs);
			echo $this->_cell($row, 'l24', $diffs);
			echo $this->_cell($row, 'home_email', $diffs);
			echo $this->_cell($row, 'home_telephone', $diffs);
			echo $this->_cell($row, 'home_mobile', $diffs);
			echo $this->_cell($row, 'l15', $diffs);
			echo $this->_cell($row, 'l16', $diffs);
			echo $this->_cell($row, 'home_address_line_1', $diffs);
			echo $this->_cell($row, 'home_address_line_2', $diffs);
			echo $this->_cell($row, 'home_address_line_3', $diffs);
			echo $this->_cell($row, 'home_address_line_4', $diffs);
			echo $this->_cell($row, 'home_postcode', $diffs);
			echo '</tr>';


			echo '<tr>';
			echo '<td  align="left" style="font-weight:bold">SmartAssessor</td>';
			echo '<td align="left" class="SmartAssessorId">', htmlspecialchars((string)$row['SmartAssessorId']), '</td>';
			echo '<td align="left">', htmlspecialchars((string)$row['GivenNames']), '</td>';
			echo '<td align="left">', htmlspecialchars((string)$row['FamilyName']), '</td>';
			echo $this->_cell($row, 'ULN', $diffs);
			echo $this->_cell($row, 'NINumber', $diffs);
			echo $this->_cell($row, 'DateOfBirth', $diffs);
			echo $this->_cell($row, 'Sex', $diffs);
			echo $this->_cell($row, 'Domicile', $diffs);
			echo $this->_cell($row, 'Email', $diffs);
			echo $this->_cell($row, 'TelNumber', $diffs);
			echo $this->_cell($row, 'Mobile', $diffs);
			echo $this->_cell($row, 'LlddDisability', $diffs);
			echo $this->_cell($row, 'LlddLearningDifficulty', $diffs);
			echo $this->_cell($row, 'HomeAddressLine1', $diffs);
			echo $this->_cell($row, 'HomeAddressLocality', $diffs);
			echo $this->_cell($row, 'HomeAddressTown', $diffs);
			echo $this->_cell($row, 'HomeAddressCounty', $diffs);
			echo $this->_cell($row, 'HomeAddressPostCode', $diffs);
			echo '</tr>';

			echo '</tbody></table>';
		}

		echo '</div>';

	}

	private function _renderBrokenLinksSunesis(PDO $link)
	{
	    $type = User::TYPE_LEARNER;
		$sql = <<<SQL
SELECT
	users.id,
	users.smart_assessor_id,
	users.firstnames,
	users.surname,
	users.dob,
	users.l45,
	organisations.legal_name,
	locations.postcode
FROM
	users INNER JOIN organisations INNER JOIN locations
		ON users.employer_id = organisations.id
		AND users.employer_location_id = locations.id
	LEFT OUTER JOIN tmp_sa_learners
		ON users.smart_assessor_id = tmp_sa_learners.SmartAssessorId
WHERE
	users.smart_assessor_id IS NOT NULL
	AND tmp_sa_learners.SunesisId IS NULL
    -- Assessors only
	AND users.type = $type
SQL;
		$rs = DAO::query($link, $sql);

		echo '<div style="width:800px; margin-left: auto; margin-right: auto;">';
		echo '<h3>Broken Links: Records in Sunesis linked to a missing record in Smart Assessor</h3>';
		echo '<table class="resultset" cellspacing="0" cellpadding="4" width="100%">';
		echo '<tr><th>Sunesis ID</th><th>SmartAssessor ID</th><th>GivenNames</th><th>FamilyName</th><th>Date of Birth</th><th>ULN</th><th>Employer</th><th>Postcode</th></tr>';
		foreach ($rs as $row) {
			echo '<tr>';
			echo '<td>', htmlspecialchars((string)$row['id']), '</td>';
			echo '<td class="SmartAssessorId BrokenLinkId">', htmlspecialchars((string)$row['smart_assessor_id']), '</td>';
			echo '<td>', htmlspecialchars((string)$row['firstnames']), '</td>';
			echo '<td>', htmlspecialchars((string)$row['surname']), '</td>';
			echo '<td>', htmlspecialchars((string)$row['dob']), '</td>';
			echo '<td>', htmlspecialchars((string)$row['l45']), '</td>';
			echo '<td>', htmlspecialchars((string)$row['legal_name']), '</td>';
			echo '<td>', htmlspecialchars((string)$row['postcode']), '</td>';
			echo '</tr>';
		}
		echo '</table>';
		echo '</div>';
	}


	private function _renderBrokenLinksSmartAssessor(PDO $link)
	{
		$sql = <<<SQL
SELECT
	tmp_sa_learners.GivenNames,
	tmp_sa_learners.FamilyName,
	tmp_sa_learners.SmartAssessorId,
	tmp_sa_learners.SunesisId,
	tmp_sa_learners.DateOfBirth,
	tmp_sa_learners.ULN,
	locations.postcode,
	organisations.legal_name
FROM
	tmp_sa_learners LEFT OUTER JOIN users
		ON tmp_sa_learners.SunesisId = users.id
	LEFT OUTER JOIN locations
		ON tmp_sa_learners.EmployerSmartAssessorId = locations.smart_assessor_id
	LEFT OUTER JOIN organisations
		ON locations.organisations_id = organisations.id
WHERE
	users.id IS NULL
	AND tmp_sa_learners.SunesisId IS NOT NULL
SQL;
		$rs = DAO::query($link, $sql);

		echo '<div style="width:800px; margin-left: auto; margin-right: auto;">';
		echo '<h3>Broken Links: Records in Smart Assessor linked to a missing record in Sunesis</h3>';
		echo '<table class="resultset" cellspacing="0" cellpadding="4" width="100%">';
		echo '<tr><th>Sunesis ID</th><th>SmartAssessor ID</th><th>GivenNames</th><th>FamilyName</th><th>Date of Birth</th><th>ULN</th><th>Sunesis Employer</th><th>Sunesis Postcode</th></tr>';
		foreach ($rs as $row) {
			echo '<tr>';
			echo '<td class="BrokenLinkId" align="left" >', htmlspecialchars((string)$row['SunesisId']), '</td>';
			echo '<td class="SmartAssessorId">', htmlspecialchars((string)$row['SmartAssessorId']), '</td>';
			echo '<td>', htmlspecialchars((string)$row['GivenNames']), '</td>';
			echo '<td>', htmlspecialchars((string)$row['FamilyName']), '</td>';
			echo '<td>', htmlspecialchars((string)$row['DateOfBirth']), '</td>';
			echo '<td>', htmlspecialchars((string)$row['ULN']), '</td>';
			echo '<td>', htmlspecialchars((string)$row['legal_name']), '</td>';
			echo '<td>', htmlspecialchars((string)$row['postcode']), '</td>';
			echo '</tr>';
		}
		echo '</table>';
		echo '</div>';
	}


	private function _createTempTables(PDO $link)
	{
		DAO::execute($link, "DROP TEMPORARY TABLE IF EXISTS tmp_sa_learners");
		$sql = <<<SQL
CREATE TEMPORARY TABLE tmp_sa_learners (
	`SmartAssessorId` CHAR(36) NOT NULL,
	`SunesisId` BIGINT,
	`EmployerSunesisId` BIGINT,
	`EmployerSmartAssessorId` BIGINT,
	`FamilyName` VARCHAR(100),
	`GivenNames` VARCHAR(100),
	`ULN` VARCHAR(50),
	`NINumber` VARCHAR(10),
	`Domicile` CHAR(2),
	`DateOfBirth` DATE,
	`Sex` VARCHAR(10),
	`Email` VARCHAR(100),
	`TelNumber` VARCHAR(30),
	`Mobile` VARCHAR(30),
	`LlddLearningDifficulty` INT,
	`LlddDisability` INT,
	`HomeAddressLine1` VARCHAR(100),
	`HomeAddressLocality` VARCHAR(100),
	`HomeAddressTown` VARCHAR(100),
	`HomeAddressCounty` VARCHAR(100),
	`HomeAddressPostCode` VARCHAR(12),
	PRIMARY KEY (`SmartAssessorId`),
	KEY (`SunesisId`),
	KEY (`EmployerSunesisId`),
	KEY (`ULN`),
	KEY (`EmployerSunesisId`)
);
SQL;
		DAO::execute($link, $sql);

		// Sunesis users on a synchronised contract
		DAO::execute($link, "DROP TEMPORARY TABLE IF EXISTS tmp_contract_learners");
		$sql = <<<SQL
CREATE TEMPORARY TABLE tmp_contract_learners (
	`id` BIGINT NOT NULL,
	PRIMARY KEY (`id`)
)
SELECT DISTINCT
	users.id
FROM
	users INNER JOIN tr INNER JOIN contracts
		ON users.username = tr.username
		AND tr.`contract_id` = contracts.id
WHERE
	users.type = 5
	AND contracts.`sync_learners_smart_assessor` = 1
ORDER BY
	users.id;
SQL;
		DAO::execute($link, $sql);

	}


	/**
	 * Returns an array of user IDs in Sunesis that are duplicates.
	 * @param PDO $link
	 * @return array
	 */
	private function _getDuplicatedLearners(PDO $link)
	{
		$sql = <<<SQL
SELECT
	GROUP_CONCAT(users.id) AS `user_id`
FROM
	users
WHERE
	users.type = 5
	AND users.uln IS NOT NULL
GROUP BY
	users.employer_id, users.uln
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