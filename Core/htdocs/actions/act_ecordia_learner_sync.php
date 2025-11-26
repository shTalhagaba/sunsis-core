<?php
class ecordia_learner_sync extends ActionController
{
	public function indexAction(PDO $link)
    {
	    if (!$_SESSION['user']->isAdmin()) {
		    throw new UnauthorizedException();
	    }

	    $ecordia_enabled = SystemConfig::get("ecordia.soap.enabled");
	    $ecordia_username = SystemConfig::get("ecordia.soap.username");
	    $ecordia_api_key = SystemConfig::get("ecordia.soap.api_key");

	    if (!$ecordia_enabled) {
		    throw new Exception("Ecordia integartion is not switched on");
	    }
	    if (!$ecordia_username) {
		    throw new Exception("Missing configuration parameter: ecordia.username");
	    }
	    if (!$ecordia_api_key) {
		    throw new Exception("Missing configuration parameter: ecordia.api_key");
	    }

	    $filterSections = $this->_getParam("filter_sections", array('nomatchec'));
	    $filterSectionsOptions = array(
		    array('exactmatch', 'Linked records'),
		    array('nomatchec', 'No links (Sunesis)')
	    );

	    include('tpl_ecordia_learner_sync.php');

    }

	public function renderContentAction(PDO $link)
	{
		if (!$_SESSION['user']->isAdmin()) {
			throw new UnauthorizedException();
		}
		if (!SystemConfig::get("ecordia.soap.enabled")) {
			echo '<p style="font-weight: bold">Ecordia integration is not enabled for this Sunesis site</b></p>';
			return;
		}

		$ec = new Ecordia();
		$ecRecords = $ec->getLearners();
		$this->_createTempTables($link);
		DAO::multipleRowInsert($link, 'tmp_ec_learners', $ecRecords);

		if(DB_NAME=="am_ray_recruit" || DB_NAME=="am_mcq")
		{
			DAO::execute($link, "drop table if exists ec_learners");
			DAO::execute($link, "create table ec_learners select * from tmp_ec_learners");
		}

		$filterSections = $this->_getParam("filter_sections", array());

		if (in_array('exactmatch', $filterSections))
		{
			$this->_renderExactMatch($link);
		}
		if (in_array('nomatchec', $filterSections)) {
			$this->_renderNoMatchEc($link);
		}
	}

	private function _renderExactMatch(PDO $link)
	{
		set_time_limit(0);
		ini_set('memory_limit','1024M');
		$type = User::TYPE_LEARNER;

		$sql = <<<SQL
SELECT
	organisations.id AS `organisations_id`,
	organisations.legal_name AS `organisations_legal_name`,
	organisations.edrs AS `organisations_edrs`,
	locations.id AS `locations_id`,
	locations.full_name AS `locations_full_name`,
	locations.postcode AS `locations_postcode`,
	locations.locations_ecordia_id AS `locations_ecordia_id`,
	users.id,
	users.username,
	#users.ecordia_id,
	users.surname,
	users.firstnames,
	users.l45 AS `uln`,
	users.dob,
	tmp_ec_learners.*
FROM
	users INNER JOIN organisations INNER JOIN locations
		ON users.employer_id = organisations.id
		AND users.employer_location_id = locations.id
	INNER JOIN tmp_ec_learners
		ON users.l45 = tmp_ec_learners.ULN #and users.ecordia_id = tmp_ec_learners.CandidateId
WHERE
	-- Sunesis learners only
	users.type = $type
ORDER BY
	organisations.legal_name, locations.full_name, locations.id
SQL;
		$rs = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);

		echo '<table class="resultset" cellspacing="0" cellpadding="4">';
		echo '<col width="100"/>';  // Name
		echo '<col width="80"/>';   // Postcode
		echo '<col width="80"/>';   // ID
		echo '<col width="100"/>';  // Surname
		echo '<col width="100"/>';  // Firstnames
		echo '<col width="80"/>';   // DOB
		echo '<col width="80"/>';   // ULN
		echo '<col width="100"/>';  // ID
		echo '<col width="100"/>';  // Surname
		echo '<col width="100"/>';  // Firstnames
		echo '<col width="80"/>';   // DOB
		echo '<col width="80"/>';   // ULN
		echo '<col width="100"/>';  // Action
		echo '<caption>Linked learner records</caption>';
		echo '<tr><th colspan="7">Sunesis</th><th colspan="5">Ecordia</th><th width="100" rowspan=2">Action</th></tr>';
		echo '<tr><th rowspan="1" colspan="2">Employer</th><th colspan="5">Learner</th><th colspan="5">Learner</th></tr>';
		echo '<tr><th>Name</th><th>Postcode</th><th>Username</th><th>Surname</th><th>Firstnames</th><th>DOB</th><th>ULN</th>'
			. '<th>Username</th><th>Surname</th><th>Firstnames</th><th>DOB</th><th>ULN</th><th><input type="checkbox" class="SelectAll"/></th></tr>';
		foreach ($rs as $row)
		{
			echo '<tr class="Data">';

			// Employer
			echo '<td align="left">' . htmlspecialchars((string)$row['organisations_legal_name']) . '</td>';
			echo '<td align="left">' . htmlspecialchars((string)$row['locations_postcode']) . '</td>';

			// Learner
//			echo '<td align="right"><a href="/do.php?_action=read_user&id=' . $row['id'] . '" target="_blank">' . htmlspecialchars((string)$row['id']) . '</a></td>';
			echo '<td align="right"><a href="/do.php?_action=read_user&username=' . $row['username'] . '" target="_blank">' . htmlspecialchars((string)$row['username']) . '</a></td>';
			echo '<td align="left">' . htmlspecialchars((string)$row['surname']) . '</td>';
			echo '<td align="left">' . htmlspecialchars((string)$row['firstnames']) . '</td>';
			echo '<td align="left">' . htmlspecialchars((string)$row['dob']) . '</td>';
			echo '<td align="left">' . htmlspecialchars((string)$row['uln']) . '</td>';

			// Smart assessor fields
			echo '<td align="left" class="SmartAssessorId">' . htmlspecialchars((string)$row['EcordiaUsername']) . '</td>';
			echo '<td align="left">' . htmlspecialchars((string)$row['LastName']) . '</td>';
			echo '<td align="left">' . htmlspecialchars((string)$row['FirstName']) . '</td>';
			echo '<td align="left">' . htmlspecialchars((string)$row['DateOfBirth']) . '</td>';
			echo '<td align="left">' . htmlspecialchars((string)$row['ULN']) . '</td>';

			echo sprintf('<td align="center"><input type="checkbox" class="SelectRow" value="%s"/></td>', htmlspecialchars((string)$row['id'] . ':' . $row['CandidateId']));

			echo '</tr>';
		}
		//echo '<tr><td colspan="12">&nbsp;</td><td align="center"><input type="button" id="BtnUpdate" value="Update" disabled="disabled"/></td></tr>';
		echo '<tr><td colspan="12">&nbsp;</td><td align="center"><input type="button" id="BtnUnlink" value="Unlink" style="color:red"/></td></tr>';

		echo '</table>';
	}

	private function _renderNoMatchEc(PDO $link) // No links in Sunesis
	{
		$type = User::TYPE_LEARNER;
		$requiredFields = array('CandidateId', 'FirstName', 'LastName', 'ULN', 'DateOfBirth', 'WorkplaceName', 'WorkplacePostcode');

		$sql = <<<SQL
SELECT
  tmp.*
FROM
  tmp_ec_learners tmp
WHERE tmp.ULN IS NOT NULL
  AND tmp.ULN NOT IN
  (SELECT
    l45
  FROM
    users
  WHERE TYPE = 5
    AND users.l45 IS NOT NULL) ;
SQL;

		$rs = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);

		echo '<table class="resultset" cellspacing="0" cellpadding="4">';
		echo '<colgroup span="7">';
		echo '<col width="100"/>';  // Name
		echo '<col width="80"/>';   // Postcode
		echo '<col width="50"/>';   // ID
		echo '<col width="100"/>';  // Surname
		echo '<col width="100"/>';  // Firstnames
		echo '<col width="80"/>';   // DOB
		echo '<col width="80"/>';   // ULN
		echo '</colgroup>';
		echo '<colgroup span="5" style="background-color:#FAFAFA">';
		echo '<col width="100"/>';  // ID
		echo '<col width="100"/>';  // Surname
		echo '<col width="100"/>';  // Firstnames
		echo '<col width="80"/>';   // DOB
		echo '<col width="80"/>';   // ULN
		echo '</colgroup>';
		echo '<col width="100"/>';  // Action
		echo '<caption>Ecordia learners with no record in Sunesis (Pull)</caption>';
		echo '<tr><th colspan="5">Sunesis</th><th colspan="7">Ecordia</th><th width="100" rowspan=2">Action</th></tr>';
		echo '<tr><th colspan="5">Learner</th><th colspan="2">Ecordia Workplace</th><th colspan="5">Learner</th></tr>';
		echo '<tr><th>Name</th><th>Postcode</th><th>ID</th><th>Surname</th><th>Firstnames</th><th>Name</th><th>Postcode</th>'
			. '<th>ID</th><th>Surname</th><th>Firstnames</th><th>DOB</th><th>ULN</th><th><input type="checkbox" class="SelectAll"/></th></tr>';
		foreach ($rs as $row)
		{
			$rowValid = true;
			foreach ($requiredFields as $field)
			{
				$rowValid = $rowValid && !empty($row[$field]);
				if (!$rowValid)
				{
					break;
				}
			}

			if (!$rowValid)
				echo '<tr style="color:gray" class="Data">';
			else
				echo '<tr class="Data">';

			echo '<td colspan="5">&nbsp;</td>';
			echo '<td align="right">' . htmlspecialchars((string)$row['WorkplaceName']) . '</td>';
			echo '<td align="right">' . htmlspecialchars((string)$row['WorkplacePostcode']) . '</td>';
			echo '<td align="left" class="SmartAssessorId">' . htmlspecialchars((string)$row['CandidateId']) . '</td>';
			echo '<td align="left">' . htmlspecialchars((string)$row['LastName']) . '</td>';
			echo '<td align="left">' . htmlspecialchars((string)$row['FirstName']) . '</td>';
			echo '<td align="left">' . htmlspecialchars((string)$row['DateOfBirth']) . '</td>';
			echo '<td align="left">' . htmlspecialchars((string)$row['ULN']) . '</td>';


			// Action checkbox
			if ($rowValid)
			{
				echo sprintf('<td align="center"><input type="checkbox" class="SelectRow" value="%s"/></td>', htmlspecialchars((string)$row['ULN'])); // Valid record for creation in Smart Assessor
			}
			else
			{
				echo '<td>&nbsp;</td>'; // Invalid (missing fields)
			}

			echo '</tr>';
		}
		echo '<tr><td colspan="12">&nbsp;</td><td align="center"><input type="button" id="BtnCreateinSunesis" value="Import"/></td></tr>';
		echo '</table>';
	}

	/**
	 * @param PDO $link
	 * @throws UnauthorizedException
	 * @throws Exception
	 */
	public function createRecordsinSunesisAction(PDO $link)
	{

		if (!$_SESSION['user']->isAdmin()) {
			throw new UnauthorizedException();
		}
		if (!SystemConfig::get("ecordia.soap.enabled")) {
			throw new Exception("Ecordia integration is not enabled for this Sunesis site.");
		}

		// Create temporary table and Insert records from Smart Assessor
		$ec = new Ecordia();
		$ecRecords = $ec->getLearners();
		$this->_createTempTables($link);
		DAO::multipleRowInsert($link, 'tmp_ec_learners', $ecRecords);

		// Allow longer execution time
		set_time_limit(180); // 3 minutes

		$ids = (array) $this->_getParam("ids");
		if (count($ids) == 0) {
			throw new Exception("No records selected to create");
		}

		$Ecordia_ids = DAO::pdo_implode($ids);

		$sql = <<<SQL
SELECT
	ec.*
FROM
	tmp_ec_learners AS ec
WHERE
    ec.ULN IN ($Ecordia_ids);
SQL;
		$rows = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);

		try
		{
			$ec = new Ecordia(false);
			foreach ($rows as $row)
			{
				$ec->createLearnerinSunesis($row);
			}
		}
		catch(Exception $e)
		{
			throw new Exception("An error occurred while creating learner records. Operation aborted.", 1, $e);
		}
	}

	private function _createTempTables(PDO $link)
	{
		DAO::execute($link, "DROP TEMPORARY TABLE IF EXISTS tmp_ec_learners");
		$sql = <<<SQL
CREATE TEMPORARY TABLE tmp_ec_learners (
	`CandidateId` CHAR(36) NOT NULL,
	`SunesisId` BIGINT,
	`Title` VARCHAR(10),
	`FirstName` VARCHAR(100),
	`MiddleName` VARCHAR(100),
	`LastName` VARCHAR(100),
	`Email` VARCHAR(100),
	`PhoneNumber` VARCHAR(50),
	`DateOfBirth` DATE,
	`ULN` VARCHAR(50),
	`LearnRefNumber` VARCHAR(12),
	`WorkplaceName` VARCHAR(250),
	`WorkplacePostcode` VARCHAR(10),
	`CourseCode` VARCHAR(100),
	`TargetStartDate` DATE,
	`TargetEndDate` DATE,
	`EcordiaUsername` VARCHAR(100),
	`PortfolioStatus` VARCHAR(50),
	PRIMARY KEY (`CandidateId`),
	KEY (`SunesisId`),
	KEY (`ULN`)
);
SQL;
		DAO::execute($link, $sql);
	}
}