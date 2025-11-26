<?php
class OrganisationContact extends Entity
{
    public function __construct($org_id = '')
    {
        $this->org_id = $org_id;
    }

	public static function loadFromDatabase(PDO $link, $id)
	{
		$query = "SELECT * FROM organisation_contacts WHERE contact_id = " . addslashes($id) . ";";
		$st = $link->query($query);

		$contact = null;
		if( $st )
		{
			$row = $st->fetch();
			if($row) {
				$contact = new OrganisationContact($row['org_id']);
				$contact->populate($row);
			}

		}
		else
		{
			throw new DatabaseException($link, $query);
		}

		return $contact;
	}


	public function save(PDO $link)
	{
		DAO::saveObjectToTable($link, 'organisation_contacts', $this);

		return $this->contact_id;
	}


	public function delete(PDO $link)
	{
		if( !$this->isSafeToDelete($link) )
		{
			throw new Exception("Contact #{$this->contact_id} cannot be deleted");
		}

		$query = <<<HEREDOC
DELETE FROM
	organisation_contacts
WHERE contact_id = {$this->contact_id}
HEREDOC;
		DAO::execute($link, $query);

		return true;
	}


	public function isSafeToDelete(PDO $link)
	{
		if(!$_SESSION['user']->isAdmin())
			return false;
		else
			return true;
	}

	public $contact_id = NULL;
	public $org_id = NULL;
	public $contact_name = NULL;
	public $contact_telephone = NULL;
	public $contact_mobile = NULL;
	public $contact_title = NULL;
	public $contact_department = NULL;
	public $contact_email = NULL;
	public $contact_type = NULL;
	public $job_role = NULL;
	public $job_title = NULL;
	public $comments = NULL;
	public $left_employer = NULL;
	public $left_employer_notes = NULL;
	public $address_line_1 = NULL;
	public $address_line_2 = NULL;
	public $address_line_3 = NULL;
	public $address_line_4 = NULL;
	public $postcode = NULL;

	const TYPE_FINANCE = 4;
	const TYPE_LEVY = 5;
	const TYPE_PRIMARY = 99;
}
?>