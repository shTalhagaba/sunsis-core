<?php
class sa_surveylink_sync extends ActionController
{
	/**
	 * Migrate learner survey link in Smart Assessor with Learner's link in Sunesis
	 * @param PDO $link
	 * @override
	 * @throws UnauthorizedException
	 */
	public function indexAction(PDO $link)
	{
		if (!$_SESSION['user']->isAdmin()) {
			throw new UnauthorizedException();
		}

		$filterSections = $this->_getParam("filter_sections", array('exactmatch', 'nomatchsun'));
		$filterSectionsOptions = array(
			array('exactmatch', 'Linked records'),
			array('nomatchsun', 'No links (Sunesis)')
		);

		include('smartassessor/tpl_sa_surveylink_sync.php');
	}

	/**
	 * @param PDO $link
	 * @return mixed
	 * @throws UnauthorizedException
	 */
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
		$saRecords = $sa->getLearners();
		$this->_createTempTables($link);
		DAO::multipleRowInsert($link, 'tmp_sa_learners', $saRecords);

		$filterSections = $this->_getParam("filter_sections", array());

		if (in_array('exactmatch', $filterSections)) {
			$this->_renderExactMatch($link);
		}
		if (in_array('nomatchsun', $filterSections)) {
			$this->_renderNoMatchSun($link);
		}

		//$this->_renderTemporaryTable($link);
	}

	/**
	 * @param PDO $link
	 * @throws UnauthorizedException
	 * @throws Exception
	 */
	public function updateRecordsAction(PDO $link)
	{
		if (!$_SESSION['user']->isAdmin()) {
			throw new UnauthorizedException();
		}
		if (!SystemConfig::get("smartassessor.soap.enabled")) {
			throw new Exception("SmartAssessor integration is not enabled for this Sunesis site.");
		}

		// Allow longer execution time
		set_time_limit(180); // 3 minutes

        $ids = (array) $this->_getParam("ids");
		if (count($ids) == 0) {
			throw new Exception("No records selected to create");
		}
		/*foreach($ids as $id) {
			if (!is_numeric($id)) {
				throw new Exception("Illegal non-numeric value for id: " . $id);
			}
		}*/
		$user_smart_assessor_ids = DAO::pdo_implode($ids);

		$type = User::TYPE_LEARNER;
		$sql = <<<SQL
SELECT
	users.id AS `SunesisId`,
    users.smart_assessor_id AS LearnerSmartAssessorId
FROM
	users INNER JOIN locations
		ON users.employer_location_id = locations.id
WHERE
 	users.smart_assessor_id IS NOT NULL
 	AND users.type = {$type}
	AND users.smart_assessor_id IN ($user_smart_assessor_ids);
SQL;
		$rows = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);

		try
		{
			$sa = new SmartAssessor(false);
			foreach ($rows as $row) {
			    $row['LearnersLink'] = 'http://'.$_SERVER['HTTP_HOST'].'/do.php?_action=survey_form&uid=' . $row['SunesisId'];
				$sa->updateSurveyLinktoLearner($row);
			}
		}
		catch(Exception $e)
		{
			throw new Exception("An error occurred while updating learner records. Operation aborted.", 1, $e);
		}
	}


	private function _renderNoMatchSun(PDO $link)
	{
		$type = User::TYPE_LEARNER;

		$sql = <<<SQL
SELECT
	organisations.id AS `organisations_id`,
	organisations.legal_name AS `organisations_legal_name`,
	organisations.edrs AS `organisations_edrs`,
	locations.id AS `locations_id`,
	locations.full_name AS `locations_full_name`,
	locations.postcode AS `locations_postcode`,
	locations.smart_assessor_id AS `locations_smart_assessor_id`,
	users.id,
	users.employer_location_id,
	users.smart_assessor_id,
	users.surname,
	users.firstnames,
	users.l45 AS `uln`,
	users.dob,
	tmp_sa_learners.*
FROM
	users INNER JOIN organisations INNER JOIN locations
		ON users.employer_id = organisations.id
		AND users.employer_location_id = locations.id
	/*INNER JOIN tmp_contract_learners
		ON users.id = tmp_contract_learners.id */
	INNER JOIN tmp_sa_learners
		ON users.id = tmp_sa_learners.SunesisId
WHERE
	-- Sunesis learners only
	users.type = $type
    -- Learners with Survey link is not migrated
    AND tmp_sa_learners.LearnerLink IS NULL
ORDER BY
	organisations.legal_name, locations.full_name, locations.id
SQL;
		$rs = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);

		echo '<table class="resultset" cellspacing="0" cellpadding="4">';
		echo '<col width="100"/>';  // Name
		echo '<col width="80"/>';   // Postcode
		echo '<col width="50"/>';   // ID
		echo '<col width="100"/>';  // Surname
		echo '<col width="100"/>';  // Firstnames
        echo '<col width="150"/>';   // DOB
		echo '<col width="100"/>';  // ID
		echo '<col width="100"/>';  // Surname
		echo '<col width="100"/>';  // Firstnames
		echo '<col width="100"/>';  // Action
		echo '<caption>Migrated Sunesis-SA Learners have No Survey Link</caption>';
		echo '<tr><th colspan="6">Sunesis</th><th colspan="3">Smart Assessor</th><th width="100" rowspan=2">Action</th></tr>';
		echo '<tr><th rowspan="1" colspan="2">Employer</th><th colspan="4">Learner</th><th colspan="3">Learner</th></tr>';
		echo '<tr><th>Name</th><th>Postcode</th><th>ID</th><th>Surname</th><th>Firstnames</th><th>Survey URL</th>'
			. '<th>ID</th><th>Surname</th><th>Firstnames</th><th><input type="checkbox" class="SelectAll"/></th></tr>';
		foreach ($rs as $row) {
			echo '<tr class="Data">';

			// Employer
			echo '<td align="left">' . htmlspecialchars((string)$row['organisations_legal_name']) . '</td>';
			echo '<td align="left">' . htmlspecialchars((string)$row['locations_postcode']) . '</td>';

			// Learner
			echo '<td align="right"><a href="/do.php?_action=read_user&id=' . $row['id'] . '" target="_blank">' . htmlspecialchars((string)$row['id']) . '</a></td>';
			echo '<td align="left">' . htmlspecialchars((string)$row['surname']) . '</td>';
			echo '<td align="left">' . htmlspecialchars((string)$row['firstnames']) . '</td>';
            echo '<td align="left">http://'.$_SERVER['HTTP_HOST'].'/do.php?_action=survey_form&uid=' . $row['id'] . '</td>';

			// Smart assessor fields
			echo '<td align="left" class="SmartAssessorId">' . htmlspecialchars((string)$row['SmartAssessorId']) . '</td>';
			echo '<td align="left">' . htmlspecialchars((string)$row['FamilyName']) . '</td>';
			echo '<td align="left">' . htmlspecialchars((string)$row['GivenNames']) . '</td>';

			echo sprintf('<td align="center"><input type="checkbox" class="SelectRow" value="%s"/></td>', htmlspecialchars((string)$row['SmartAssessorId']));

			echo '</tr>';
		}
		//echo '<tr><td colspan="12">&nbsp;</td><td align="center"><input type="button" id="BtnUpdate" value="Update" disabled="disabled"/></td></tr>';
		echo '<tr><td colspan="9">&nbsp;</td><td align="center"><input type="button" id="BtnUpdateInSA" value="Update in SA"/></td></tr>';

		echo '</table>';
	}



	private function _renderExactMatch(PDO $link)
	{
		$type = User::TYPE_LEARNER;

		$sql = <<<SQL
SELECT
	organisations.id AS `organisations_id`,
	organisations.legal_name AS `organisations_legal_name`,
	organisations.edrs AS `organisations_edrs`,
	locations.id AS `locations_id`,
	locations.full_name AS `locations_full_name`,
	locations.postcode AS `locations_postcode`,
	locations.smart_assessor_id AS `locations_smart_assessor_id`,
	users.id,
	users.employer_location_id,
	users.smart_assessor_id,
	users.surname,
	users.firstnames,
	users.l45 AS `uln`,
	users.dob,
	tmp_sa_learners.*
FROM
	users INNER JOIN organisations INNER JOIN locations
		ON users.employer_id = organisations.id
		AND users.employer_location_id = locations.id
	/*INNER JOIN tmp_contract_learners
		ON users.id = tmp_contract_learners.id */
	INNER JOIN tmp_sa_learners
		ON users.id = tmp_sa_learners.SunesisId
WHERE
	-- Sunesis learners only
	users.type = $type
    -- Learners with Survey link is not migrated
    AND tmp_sa_learners.LearnerLink IS NOT NULL
ORDER BY
	organisations.legal_name, locations.full_name, locations.id
SQL;
		$rs = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);

		echo '<table class="resultset" cellspacing="0" cellpadding="4">';
		echo '<col width="100"/>';  // Name
		echo '<col width="80"/>';   // Postcode
		echo '<col width="50"/>';   // ID
		echo '<col width="100"/>';  // Surname
		echo '<col width="100"/>';  // Firstnames
        echo '<col width="150"/>';   // DOB
		echo '<col width="100"/>';  // ID
		echo '<col width="100"/>';  // Surname
		echo '<col width="100"/>';  // Firstnames
		echo '<col width="100"/>';  // Action
		echo '<caption>Migrated Sunesis-SA Learners have Survey Link with SA learners</caption>';
		echo '<tr><th colspan="6">Sunesis</th><th colspan="3">Smart Assessor</th><th width="100" rowspan=2">Action</th></tr>';
		echo '<tr><th rowspan="1" colspan="2">Employer</th><th colspan="4">Learner</th><th colspan="3">Learner</th></tr>';
		echo '<tr><th>Name</th><th>Postcode</th><th>ID</th><th>Surname</th><th>Firstnames</th><th>Survey URL</th>'
			. '<th>ID</th><th>Surname</th><th>Firstnames</th><th><input type="checkbox" class="SelectAll"/></th></tr>';
		foreach ($rs as $row) {
			echo '<tr class="Data">';

			// Employer
			echo '<td align="left">' . htmlspecialchars((string)$row['organisations_legal_name']) . '</td>';
			echo '<td align="left">' . htmlspecialchars((string)$row['locations_postcode']) . '</td>';

			// Learner
			echo '<td align="right"><a href="/do.php?_action=read_user&id=' . $row['id'] . '" target="_blank">' . htmlspecialchars((string)$row['id']) . '</a></td>';
			echo '<td align="left">' . htmlspecialchars((string)$row['surname']) . '</td>';
			echo '<td align="left">' . htmlspecialchars((string)$row['firstnames']) . '</td>';
            echo '<td align="left">http://'.$_SERVER['HTTP_HOST'].'/do.php?_action=survey_form&uid=' . $row['id'] . '</td>';

			// Smart assessor fields
			echo '<td align="left" class="SmartAssessorId">' . htmlspecialchars((string)$row['SmartAssessorId']) . '</td>';
			echo '<td align="left">' . htmlspecialchars((string)$row['FamilyName']) . '</td>';
			echo '<td align="left">' . htmlspecialchars((string)$row['GivenNames']) . '</td>';

            echo '<td align="left">&nbsp;</td>';

			echo '</tr>';
		}
		echo '</table>';
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
	`ULN` VARCHAR(10),
	`NINumber` VARCHAR(10),
	`Domicile` CHAR(2),
	`DateOfBirth` DATE,
	`Sex` VARCHAR(10),
	`Email` VARCHAR(100),
	`TelNumber` VARCHAR(50),
	`Mobile` VARCHAR(30),
	`LlddLearningDifficulty` INT,
	`LlddDisability` INT,
	`HomeAddressLine1` VARCHAR(100),
	`HomeAddressLocality` VARCHAR(100),
	`HomeAddressTown` VARCHAR(100),
	`HomeAddressCounty` VARCHAR(100),
	`HomeAddressPostCode` VARCHAR(12),
    `LearnerLink` VARCHAR(250),
	PRIMARY KEY (`SmartAssessorId`),
	KEY (`SunesisId`),
	KEY (`EmployerSunesisId`),
	KEY (`ULN`),
	KEY (`EmployerSunesisId`)
);
SQL;
		DAO::execute($link, $sql);

		// All Sunesis learners on a synchronised contract
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
	AND contracts.`sync_learners_smart_assessor` = 1;
SQL;
		DAO::execute($link, $sql);
	}


	private function _renderTemporaryTable(PDO $link)
	{
		$rs = DAO::getResultset($link, "SELECT * FROM tmp_sa_learners", DAO::FETCH_ASSOC);
		HTML::renderResultset($rs);
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
}