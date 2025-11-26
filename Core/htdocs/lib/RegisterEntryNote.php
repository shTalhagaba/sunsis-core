<?php
class RegisterEntryNote extends ValueObject
{
	public function __construct()
	{
		parent::__construct();
		
		//$this->username = $_SESSION['username'];
		//$this->firstnames = $_SESSION['firstnames'];
		//$this->surname = $_SESSION['surname'];
		//$this->organisation_name = $_SESSION['org']->short_name;
		//$this->created = ''; // DAO will write NULL to this field and trigger a timestamp
		
		$this->username = $_SESSION['user']->username;
		$this->username = $_SESSION['user']->firstnames;
		
	}
		
	
	public static function loadFromDatabase(PDO $link, $id)
	{
		if($id == '' || !is_numeric($id))
		{
			throw new Exception("Invalid argument, 'id'");
		}
		
		$note = null;
		$sql = "SELECT * FROM register_entry_notes WHERE id='".addslashes((string)$id)."'";
		$st = $link->query($sql);
		if($st)
		{
			if($row = $st->fetch())
			{
				$note = new RegisterEntryNote();
				$note->populate($row); 
			}
			
		}
		else
		{
			throw new DatabaseException($link, $sql);
		}
		
		return $note;
	}
	
	
	public function addParagraph($paragraph)
	{
		$this->note .= $paragraph . "\r\n";
	}
	
	
	public function save(PDO $link)
	{
		if($this->id == '')
		{
			// New record
			$this->username = $_SESSION['user']->username;
			$this->firstnames = $_SESSION['user']->firstnames;
			$this->surname = $_SESSION['user']->surname;
			$this->organisation_name = $_SESSION['user']->org_short_name;
			$this->created = ''; // Will write NULL to the database
		}
		else
		{
			// Editing an existing record -- find out who first created it
			$existing_record = RegisterEntryNote::loadFromDatabase($link, $this->id);
			$this->username = $existing_record->username;
		}
		
		if( ($_SESSION['user']->isAdmin()==false) && ($_SESSION['user']->username != $this->username) )
		{
			throw new UnauthorizedException();
		}
		
		// Clean comments field
		$this->note = preg_replace('/[\r\n]+/', "\n", $this->note); // Remove superfluous newlines
		$this->note = trim(strip_tags($this->note)); // Remove HTML tags		
		
		DAO::saveObjectToTable($link, 'register_entry_notes', $this);
		
		return $this->id;
		
		/*
		$exclude_fields = array('id');
		
		if(is_null($this->id) || $this->id == 0)
		{
			// New note
			$sql = 'INSERT INTO register_entry_notes SET ' . $this->toNameValuePairs($exclude_fields) . ';';
		}
		else
		{
			// Update (not expected to be used)
			$sql = 'UPDATE register_entry_notes SET ' . $this->toNameValuePairs($exclude_fields) . ' WHERE id=' . $this->id . ';';
		}
		
		$st = $link->query($sql);
		if($st== false)
		{
			throw new Exception("Cannot save register entry note to database. " . implode($link->errorInfo()));
		}

		if($this->id == 0)
		{
			$this->id = $link->lastInsertId();
		}
		
		// Update the timestamp on the POT record
		$sql = "UPDATE pot SET modified=CURRENT_TIMESTAMP WHERE pot.id = (SELECT pot_id FROM register_entries WHERE register_entries.id={$this->register_entries_id});";
		$st = $link->query($sql);
		if($st== false)
		{
			//throw new Exception("Cannot update POT timestamp. " . implode($link->errorInfo()));
			// Ignore the error
		}
*/	
	}
	

	public function delete(PDO $link)
	{
		if(!$this->isSafeToDelete($link))
		{
			throw new Exception("This note cannot be deleted");
		}
		
		$sql = <<<HEREDOC
DELETE FROM
	register_entry_notes
WHERE
	id = {$this->id}
HEREDOC;
		DAO::execute($link, $sql);
	}
	
	
	public function isSafeToDelete(PDO $link)
	{
		return $this->is_audit_note == false;
	}	
	
	
	public $id = NULL;
	public $register_entries_id = null;
	public $note = null;
	public $username = null;
	public $firstnames = null;
	public $surname = null;
	public $organisation_name = null;
	public $email = null;
	
	public $is_audit_note = 0;
	public $entry = null;
	
	public $modified = null;
	public $created = null;
}
?>