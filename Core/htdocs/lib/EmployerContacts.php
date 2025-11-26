<?php
class EmployerContacts extends Entity
{
    public static function loadFromDatabase(PDO $link, $id)	{
        $query = "SELECT * FROM organisation_contact WHERE contact_id='" . addslashes((string)$id) . "';";
        $st = $link->query($query);

        $contact = null;
        if( $st )	{
            $row = $st->fetch();
            if($row) {
                $contact = new EmployerContacts();
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
            //$query = "SELECT count(*) FROM organisation_contact WHERE contact_name='" . addslashes((string)$this->contact_name) . "' AND org_id = '$this->org_id';";
            //if ( DAO::getSingleValue($link, $query) <= 0 ) {
            DAO::saveObjectToTable($link, 'organisation_contact', $this);
            //}
        }
        return $this->contact_id;
    }


    public function delete(PDO $link) {
        if( !$this->isSafeToDelete($link) ) {
            throw new Exception("Contact #{$this->contact_id} cannot be deleted");
        }

        $query = <<<HEREDOC
DELETE FROM
	organisation_contact
WHERE contact_id = {$this->contact_id})
HEREDOC;
        DAO::execute($link, $query);

        return true;
    }


    public function isSafeToDelete(PDO $link) {
        return true;
    }

    public $org_id = NULL;
    public $contact_id = NULL;
    public $contact_title = NULL;
    public $contact_name = NULL;
    public $contact_telephone = NULL;
    public $contact_mobile = NULL;
    public $contact_department = NULL;
    public $contact_email = NULL;
    public $contact_type = NULL;
    public $job_role = NULL;
	public $job_title = NULL;
	public $comments = NULL;
	public $left_employer = NULL;
	public $left_employer_notes = NULL;
}
?>