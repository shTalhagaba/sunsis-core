<?php
class EmployerPool extends Entity
{
	public static function loadFromDatabase(PDO $link, $id)
	{
		if($id == '')
		{
			return null;
		}

		if(is_numeric($id))
		{
			$sql = "SELECT * FROM pool WHERE id = '{$id}'";
		}

		$org = null;
		if($obj = DAO::getObject($link, $sql))
		{
			$org = new EmployerPool();
			$org->populate($obj);
		}

		return $org;
	}

	public function save(PDO $link)
	{
		$this->created = $this->id == '' ? date('Y-m-d H:i:s') : $this->created;
		$this->creator = $this->id == '' ? $_SESSION['user']->id : $this->creator;
		$this->modified = date('Y-m-d H:i:s');

		return DAO::saveObjectToTable($link, 'pool', $this);
	}


	public function delete(PDO $link)
	{
		$sql = <<<HEREDOC
DELETE

FROM
	pool
WHERE
	pool.id={$this->id}
HEREDOC;
		DAO::execute($link, $sql);
	}


	public function getContacts(PDO $link, $id)
	{
		$sql = <<<HEREDOC

SELECT CONCAT(contact_email,'*',contact_name) AS contact, contact_name FROM pool_contact WHERE pool_id = $id

HEREDOC;

		return DAO::getResultset($link, $sql, DAO::FETCH_NUM);

	}


	public $id = NULL;
	public $organisation_type = NULL;
	public $legal_name = NULL;
	public $trading_name = NULL;
	public $company_number = NULL;
	public $client_id = NULL;
	public $associated_org_id = NULL;
	public $description = NULL;
	public $sector = NULL;
	public $region = NULL;
	public $STATUS = NULL;
	public $edrs = NULL;
	public $creator = NULL;
	public $parent_org = NULL;
	public $active = NULL;
	public $site_employees = NULL;
	public $levy = NULL;
	public $year_founded = NULL;
	public $website = NULL;
	public $web_techs = NULL;
	public $credit_rating = NULL;
	public $credit_limit = NULL;
	public $incorporation_date = NULL;
	public $annual_turnover = NULL;
	public $pre_tax_profit = NULL;
	public $net_worth = NULL;
	public $auditors = NULL;
	public $director_title = NULL;
	public $director_forename = NULL;
	public $director_surname = NULL;
	public $director_position = NULL;
	public $director_dob = NULL;
	public $created = NULL;
	public $modified = NULL;
	public $email_domain = NULL;
	public $domain_name = NULL;
	public $linked_in_page = NULL;
	public $twitter_handle = NULL;
	public $facebook_page = NULL;
	public $company_owner = NULL;
	public $industry = NULL;
	public $company_rating = NULL;
	public $employer_id = NULL;
}
?>