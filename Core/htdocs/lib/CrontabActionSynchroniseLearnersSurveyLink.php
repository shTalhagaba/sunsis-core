<?php
class CrontabActionSynchroniseLearnersSurveyLink extends CrontabAction
{
	public $LearnerSmartAssessorId;
	public $LearnersLink;

	public function __construct()
	{
		$this->task = 'SynchroniseLearnersSurveyLink';
	}

	public function execute(PDO $link)
	{
		if (!SystemConfig::get('smartassessor.soap.enabled')) {
			return;
		}

		// Populate tmp_sa_learners
		$sa = new SmartAssessor($this->read_only);
		$saRecords = $sa->getLearners();
		$this->_createTempTables($link);
		DAO::multipleRowInsert($link, 'tmp_sa_learners', $saRecords);

		// Update Survey link in Smart Assessor Learners
		$this->_updateLearnerSurveyLink($link, $sa);
	}


	private function _updateLearnerSurveyLink(PDO $link, SmartAssessor $sa)
	{
	    $type = User::TYPE_LEARNER;
		$sql = <<<SQL
SELECT
	users.id AS `SunesisId`,
    users.smart_assessor_id AS LearnerSmartAssessorId
FROM
	users INNER JOIN organisations INNER JOIN locations
		ON users.employer_id = organisations.id
		AND users.employer_location_id = locations.id
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

    	$rows = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);

    	$sa = new SmartAssessor(false);
        foreach ($rows as $row) {
            $row['LearnersLink'] = 'http://'.$_SERVER['HTTP_HOST'].'/do.php?_action=survey_form&uid=' . $row['SunesisId'];

            $this->log('Updating Learner in Smart Assessor: LearnerSmartAssessorId-' . $row['LearnerSmartAssessorId']
			    . ' (SunesisId #' .  $row['SunesisId'] . ')', Zend_Log::INFO);
            $sa->updateSurveyLinktoLearner($row);
        }


	}

	/**
	 * Helper method to _updateLearners()
	 * @param array $row
	 * @param string $saKey Smart Assessor field name
	 * @param string $sunKey Sunesis fieldname
	 * @param array $saArray
	 * @param array $sunArray
	 */
	private function _compareSystems(array $row, $saKey, $sunKey, array &$saArray, array &$sunArray)
	{
		if (isset($this->$saKey) && $this->$saKey && (trim($row[$sunKey]) != trim($row[$saKey]))) {
			if ($this->$saKey == 'Sunesis') {
				$saArray[$saKey] = trim($row[$sunKey]);
			} else {
				$sunArray[$sunKey] = trim($row[$saKey]);
			}
		}
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
	`TelNumber` VARCHAR(30),
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

	/**
	 * Helper method
	 * @param array $array
	 * @return string
	 */
	private function _serialiseArray(array $array)
	{
		$str = '';
		foreach ($array as $field=>$key) {
			$str .= $field . '=\'' . $key . '\', ';
		}
		return trim($str, ' ,');
	}
}