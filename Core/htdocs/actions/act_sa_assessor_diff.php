<?php
class sa_assessor_diff extends ActionController
{

	public function indexAction(PDO $link)
	{
		if (!$_SESSION['user']->isAdmin()) {
			throw new UnauthorizedException();
		}

		include('smartassessor/tpl_sa_assessor_diff.php');
	}


	public function renderContentAction(PDO $link)
	{
	    $type = User::TYPE_ASSESSOR;
        $type_verifier = User::TYPE_VERIFIER;

		if (!$_SESSION['user']->isAdmin()) {
			throw new UnauthorizedException();
		}
		if (!SystemConfig::get("smartassessor.soap.enabled")) {
			echo '<p style="font-weight: bold">SmartAssessor integration is not enabled for this Sunesis site</b></p>';
			return;
		}

		$sa = new SmartAssessor();
		$saRecords = $sa->getAssessors();
	    $this->_createTempTables($link);
		DAO::multipleRowInsert($link, 'tmp_sa_assessors', $saRecords);

		$duplicatedAssessors = $this->_getDuplicatedAssessors($link);

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
	users.username,
	users.home_email,
	users.home_telephone,
	users.home_mobile,
	users.home_address_line_3,

	tmp_sa_assessors.SmartAssessorId,
	tmp_sa_assessors.SunesisId,
	tmp_sa_assessors.LastName,
	tmp_sa_assessors.FirstName,
	tmp_sa_assessors.UserName,
	tmp_sa_assessors.Email,
	tmp_sa_assessors.Telephone,
	tmp_sa_assessors.Mobile,
	tmp_sa_assessors.Region
FROM
	users INNER JOIN organisations INNER JOIN locations
		ON users.employer_id = organisations.id
		AND users.employer_location_id = locations.id
	-- Imported Smart Assessor assessors
	INNER JOIN tmp_sa_assessors
		ON users.id = tmp_sa_assessors.SunesisId
WHERE
	-- Assessors only
	(users.type = $type OR users.type = $type_verifier)
	-- Linked records with differing field values
	AND (
		TRIM(users.surname) != TRIM(tmp_sa_assessors.LastName)
		OR TRIM(users.firstnames) != TRIM(tmp_sa_assessors.FirstName)
		OR TRIM(users.username) != TRIM(tmp_sa_assessors.UserName)
		OR TRIM(users.home_email) != TRIM(tmp_sa_assessors.Email)
		OR TRIM(users.home_telephone) != TRIM(tmp_sa_assessors.Telephone)
		OR TRIM(users.home_mobile) != TRIM(tmp_sa_assessors.Mobile)
		OR TRIM(users.home_address_line_3) != TRIM(tmp_sa_assessors.Region)
		)
ORDER BY
	users.employer_id, users.employer_location_id, users.surname, users.firstnames
SQL;

		echo '<div>';

		// Sort through
		$st = DAO::query($link, $sql);
		while ($row = $st->fetch(PDO::FETCH_ASSOC)) {
			$diffs = array();
			$this->_compareFields($row, 'LastName', 'surname', $diffs);
			$this->_compareFields($row, 'FirstName', 'firstnames', $diffs);
			$this->_compareFields($row, 'UserName', 'username', $diffs);
			$this->_compareFields($row, 'Email', 'home_email', $diffs);
			$this->_compareFields($row, 'Telephone', 'home_telephone', $diffs);
			$this->_compareFields($row, 'Mobile', 'home_mobile', $diffs);
			$this->_compareFields($row, 'Region', 'home_address_line_3', $diffs);

			echo <<<HTML
<table class="resultset CompareRecords" cellspacing="0" cellpadding="4">
HTML;
			echo '<thead>';
			echo '<th>&nbsp;</th><th>ID</th><th>FirstName</th><th>LastName</th>';
			if (in_array('UserName', $diffs)) {
				echo '<th>UserName</th>';
			}
			if (in_array('Email', $diffs)) {
				echo '<th>Email</th>';
			}
			if (in_array('Telephone', $diffs)) {
				echo '<th>Telephone</th>';
			}
			if (in_array('Mobile', $diffs)) {
				echo '<th>Mobile</th>';
			}
			if (in_array('Region', $diffs)) {
				echo '<th>Region</th>';
			}


			echo '</tr>';
			echo '</thead>';
			echo '<tbody>';

			echo '<tr>';
			echo '<td align="left" style="font-weight:bold">Sunesis</td>';
			echo '<td align="left">', htmlspecialchars((string)$row['users_id']), '</td>';
			echo '<td align="left">', htmlspecialchars((string)$row['firstnames']), '</td>';
			echo '<td align="left">', htmlspecialchars((string)$row['surname']), '</td>';
			echo $this->_cell($row, 'username', $diffs);
			echo $this->_cell($row, 'home_email', $diffs);
			echo $this->_cell($row, 'home_telephone', $diffs);
			echo $this->_cell($row, 'home_mobile', $diffs);
			echo $this->_cell($row, 'home_address_line_3', $diffs);
			echo '</tr>';


			echo '<tr>';
			echo '<td  align="left" style="font-weight:bold">SmartAssessor</td>';
			echo '<td align="left" class="SmartAssessorId">', htmlspecialchars((string)$row['SmartAssessorId']), '</td>';
			echo '<td align="left">', htmlspecialchars((string)$row['FirstName']), '</td>';
			echo '<td align="left">', htmlspecialchars((string)$row['LastName']), '</td>';
			echo $this->_cell($row, 'UserName', $diffs);
			echo $this->_cell($row, 'Email', $diffs);
			echo $this->_cell($row, 'Telephone', $diffs);
			echo $this->_cell($row, 'Mobile', $diffs);
			echo $this->_cell($row, 'Region', $diffs);
			echo '</tr>';

			echo '</tbody></table>';
		}

		echo '</div>';

	}

	private function _renderBrokenLinksSunesis(PDO $link)
	{
	    $type = User::TYPE_ASSESSOR;
        $type_verifier = User::TYPE_VERIFIER;

		$sql = <<<SQL
SELECT
	users.id,
	users.smart_assessor_id,
	users.firstnames,
	users.surname,
	users.username,
	users.home_email
FROM
	users INNER JOIN organisations INNER JOIN locations
		ON users.employer_id = organisations.id
		AND users.employer_location_id = locations.id
	LEFT OUTER JOIN tmp_sa_assessors
		ON users.smart_assessor_id = tmp_sa_assessors.SmartAssessorId
WHERE
	users.smart_assessor_id IS NOT NULL
	AND tmp_sa_assessors.SunesisId IS NULL
    -- Assessors only
	AND (users.type = $type OR users.type = $type_verifier)
SQL;
		$rs = DAO::query($link, $sql);

		echo '<div style="width:800px; margin-left: auto; margin-right: auto;">';
		echo '<h3>Broken Links: Records in Sunesis linked to a missing record in Smart Assessor</h3>';
		echo '<table class="resultset" cellspacing="0" cellpadding="4" width="100%">';
		echo '<tr><th>Sunesis ID</th><th>SmartAssessor ID</th><th>GivenNames</th><th>FamilyName</th><th>Username</th><th>Email</th></tr>';
		foreach ($rs as $row) {
			echo '<tr>';
			echo '<td>', htmlspecialchars((string)$row['id']), '</td>';
			echo '<td class="SmartAssessorId BrokenLinkId">', htmlspecialchars((string)$row['smart_assessor_id']), '</td>';
			echo '<td>', htmlspecialchars((string)$row['firstnames']), '</td>';
			echo '<td>', htmlspecialchars((string)$row['surname']), '</td>';
			echo '<td>', htmlspecialchars((string)$row['username']), '</td>';
			echo '<td>', htmlspecialchars((string)$row['home_email']), '</td>';
			echo '</tr>';
		}
		echo '</table>';
		echo '</div>';
	}


	private function _renderBrokenLinksSmartAssessor(PDO $link)
	{
		$sql = <<<SQL
SELECT
	tmp_sa_assessors.FirstName,
	tmp_sa_assessors.LastName,
	tmp_sa_assessors.SmartAssessorId,
	tmp_sa_assessors.SunesisId,
	tmp_sa_assessors.UserName,
	tmp_sa_assessors.Email
FROM
	tmp_sa_assessors LEFT OUTER JOIN users
		ON tmp_sa_assessors.SunesisId = users.id
	LEFT OUTER JOIN locations
		ON users.employer_location_id = locations.id
	LEFT OUTER JOIN organisations
		ON locations.organisations_id = organisations.id
WHERE
	users.id IS NULL
	AND tmp_sa_assessors.SunesisId IS NOT NULL
SQL;
		$rs = DAO::query($link, $sql);

		echo '<div style="width:800px; margin-left: auto; margin-right: auto;">';
		echo '<h3>Broken Links: Records in Smart Assessor linked to a missing record in Sunesis</h3>';
		echo '<table class="resultset" cellspacing="0" cellpadding="4" width="100%">';
		echo '<tr><th>Sunesis ID</th><th>SmartAssessor ID</th><th>FirstName</th><th>LastName</th><th>UserName</th><th>Email</th></tr>';
		foreach ($rs as $row) {
			echo '<tr>';
			echo '<td class="BrokenLinkId" align="left" >', htmlspecialchars((string)$row['SunesisId']), '</td>';
			echo '<td class="SmartAssessorId">', htmlspecialchars((string)$row['SmartAssessorId']), '</td>';
			echo '<td>', htmlspecialchars((string)$row['FirstName']), '</td>';
			echo '<td>', htmlspecialchars((string)$row['LastName']), '</td>';
			echo '<td>', htmlspecialchars((string)$row['UserName']), '</td>';
			echo '<td>', htmlspecialchars((string)$row['Email']), '</td>';
			echo '</tr>';
		}
		echo '</table>';
		echo '</div>';
	}


	private function _createTempTables(PDO $link)
	{
		DAO::execute($link, "DROP TEMPORARY TABLE IF EXISTS tmp_sa_assessors");
		$sql = <<<SQL
CREATE TEMPORARY TABLE tmp_sa_assessors (
	`SmartAssessorId` CHAR(50) NOT NULL,
	`SunesisId` BIGINT,
	`FirstName` VARCHAR(100),
	`LastName` VARCHAR(100),
	`UserName` VARCHAR(100),
    `Password` VARCHAR(100),
	`Region` VARCHAR(30),
	`Email` VARCHAR(100),
	`Telephone` VARCHAR(30),
	`Mobile` VARCHAR(30),
    `UserType` VARCHAR(30),
	PRIMARY KEY (`SmartAssessorId`),
	KEY (`SunesisId`)
);
SQL;
		DAO::execute($link, $sql);

	}


	/**
	 * Returns an array of user IDs in Sunesis that are duplicates.
	 * @param PDO $link
	 * @return array
	 */
	private function _getDuplicatedAssessors(PDO $link)
	{
		$sql = <<<SQL
SELECT
	GROUP_CONCAT(users.id) AS `user_id`
FROM
	users
WHERE
	users.type = 3
GROUP BY
	users.employer_id, users.username
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