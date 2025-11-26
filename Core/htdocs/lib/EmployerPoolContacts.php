<?php
class EmployerPoolContacts extends Entity
{
	public static function loadFromDatabase(PDO $link, $id)	{
		$query = "SELECT * FROM pool_contact WHERE contact_id=" . addslashes((string)$id) . ";";
		$st = $link->query($query);

		$contact = null;
		if( $st )	{
			$row = $st->fetch();
			if($row) {
				$contact = new EmployerPoolContacts();
				$contact->populate($row);
			}

		}
		else {
			throw new DatabaseException($link, $query);
		}

		return $contact;
	}


	public function save(PDO $link)	{
		
		if ( $this->contact_name != "" ) {
			DAO::saveObjectToTable($link, 'pool_contact', $this);
		}
		return $this->contact_id;
	}


	public function delete(PDO $link) {
		if( !$this->isSafeToDelete($link) ) {
			throw new Exception("Contact #{$this->contact_id} cannot be deleted");
		}

		$query = <<<HEREDOC
DELETE FROM
	pool_contact
WHERE contact_id = '{$this->contact_id}'
HEREDOC;
		DAO::execute($link, $query);

		return true;
	}


	public function isSafeToDelete(PDO $link) {
		return true;
	}

	public $contact_id = NULL;
	public $pool_id = NULL;
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
	public $decision_maker = 0;
}
?>