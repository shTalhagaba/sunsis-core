<?php
class add_learner extends ActionController
{
	public function indexAction(PDO $link)
	{
		$employer_id = isset($_REQUEST['organisations_id']) ? $_REQUEST['organisations_id'] : '';
		$employer_location_id = isset($_REQUEST['location_id']) ? $_REQUEST['location_id'] : '';

		if( (!$_SESSION['user']->isAdmin()) &&
			(!$_SESSION['user']->isOrgAdmin()) &&
			(!$_SESSION['user']->isPeopleCreator()) &&
			(!(int)$_SESSION['user']->type == User::TYPE_SALESPERSON) )
		{
			throw new UnauthorizedException();
		}

		$_SESSION['bc']->add($link, "do.php?_action=add_learner&organisations_id={$employer_id}&location_id={$employer_location_id}", "Add Learner");

		$ddlEmployers = DAO::getResultset($link, "SELECT id, legal_name, LEFT(legal_name, 1) FROM organisations WHERE (organisation_type = '" . Organisation::TYPE_EMPLOYER . "') ORDER BY legal_name");
		$ddlEmployersLocations = [
			['', 'Select an employer to populate locations']
		];

		if($employer_id != '')
		{
			$sql = <<<SQL
SELECT
  locations.id,
  CONCAT(COALESCE(locations.`full_name`), ' (',COALESCE(`address_line_1`,''),',',COALESCE(`postcode`,''), ')') AS detail,
  null
FROM
  locations
WHERE
	locations.organisations_id = '$employer_id'
ORDER BY full_name ;
SQL;
			$ddlEmployersLocations = DAO::getResultset($link, $sql);
		}

		$vo = new User();
		$vo->type = User::TYPE_LEARNER;
		$vo->employer_id = $employer_id;
		$vo->employer_location_id = $employer_location_id;
		$vo->gender = null;
		$vo->created_by = $_SESSION['user']->id;

		$ddlGenders = DAO::getResultset($link, "SELECT id, description, null FROM lookup_gender;");

		include('tpl_add_learner.php');
	}


	/**
	 * Returns a JSON encoded array of similar learners
	 * @param PDO $link
	 * @return mixed
	 */
	public function findSimilarRecordsAction(PDO $link)
	{
		$id = $this->_getParam("id");
		$firstnames = $this->_getParam("firstnames");
		$surname = $this->_getParam("surname");
		$dob = $this->_getParam("dob");
		$gender = $this->_getParam("gender");

		if(Date::isDate($dob))
		{
			$dob = Date::toMySQL($dob);
		}
		else
		{
			$dob = null;
		}

		$where = array();
		if ($firstnames) {
			$where[] = "SOUNDEX(SUBSTRING_INDEX(`users`.`firstnames`, ' ', 1)) = SOUNDEX(SUBSTRING_INDEX(" . $link->quote($firstnames) . ", ' ', 1))";
		}
		if ($surname) {
			$where[] = "SOUNDEX(SUBSTRING_INDEX(`users`.`surname`, ' ', -1)) = SOUNDEX(SUBSTRING_INDEX(" . $link->quote($surname) . ", ' ', 1))";
		}
		if ($dob) {
			$where[] = "(`users`.`dob` = " . $link->quote($dob) . " OR `users`.`dob` IS NULL)";
		}
		if ($gender) {
			$where[] = "`users`.`gender` = " . $link->quote($gender);
		}
		$were[] = "`users`.`type` = 5";

		// Build core WHERE clause
		$where = implode(' AND ', $where);

		// Prepend id WHERE subclause
		if ($id) {
			$where = "`users`.`id` != " . $link->quote($id) . " AND (" . $where . ")";
		}

		$sql = "SELECT id FROM users WHERE ".$where;
		$ids = DAO::getSingleColumn($link, $sql);
		if (!$ids) {
			header("Content-Type: application/json");
			echo "[]";
			return;
		}

		$ids = DAO::quote($ids);
		$sql = <<<SQL
SELECT
	users.id,
	username,
	firstnames,
	surname,
	DATE_FORMAT(dob, '%d/%m/%Y') AS dob,
	gender,
	l45,
	ni,
	organisations.legal_name AS `employer`,
	(SELECT COUNT(id) FROM tr WHERE tr.username = users.username) AS `tr_count`,
	(SELECT GROUP_CONCAT(l03) FROM tr WHERE tr.username = users.username GROUP BY tr.username) AS `l03`
FROM
	users LEFT OUTER JOIN organisations
		ON users.employer_id = organisations.id
WHERE
	users.id IN ($ids);
SQL;
		$records = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);

		header("Content-Type: application/json");
		echo Text::json_encode_latin1($records);
	}
}
?>