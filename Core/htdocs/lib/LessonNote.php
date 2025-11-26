<?php
class LessonNote extends ValueObject
{
	public static function loadFromDatabase(PDO $link, $id)
	{
		if($id == '' || !is_numeric($id))
		{
			throw new Exception("Invalid argument, 'id'");
		}

		$report = null;
		$sql = "SELECT * FROM lesson_notes WHERE id='".addslashes((string)$id)."'";
		$st = $link->query($sql);	
		if($st)
		{
			if($row = $st->fetch())
			{
				$report = new LessonNote();
				$report->populate($row); 
			}
			
		}
		else
		{
			throw new DatabaseException($link, $sql);
		}
		
		return $report;
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
			$this->organisations_id = $_SESSION['user']->employer_id;
			$this->created = ''; // Will write NULL to the database
		}
		else
		{
			// Editing an existing record -- find out who first created it
			$existing_record = LessonNote::loadFromDatabase($link, $this->id);
			$this->username = $existing_record->username;
		}
		

		// When public == 1, readers must be set to NULL in the database
		if($this->public == 1)
		{
			$this->readers = ''; // will clear existing value in database
		}
		
		
		if( (!($_SESSION['user']->isAdmin())) && ($_SESSION['user']->username != $this->username) )
		{
			throw new UnauthorizedException();
		}
		
		// Clean comments field
		$this->note = preg_replace('/[\n\r]+/', "\n", $this->note); // Remove superfluous newlines
		$this->note = trim(strip_tags($this->note)); // Remove HTML tags		
		
		DAO::saveObjectToTable($link, 'lesson_notes', $this);
		
		return $this->id;
	}
	
	
	public function delete(PDO $link)
	{
		if(!$this->isSafeToDelete($link))
		{
			throw new Exception("This note cannot be deleted");
		}
		
		if( ($_SESSION['user']->isAdmin()) && ($_SESSION['user']->username != $this->username) )
		{
			throw new UnauthorizedException();
		}
		
		$sql = <<<HEREDOC
DELETE FROM
	lesson_notes
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
	public $lessons_id = NULL;
	public $subject = NULL;
	public $note = NULL;
	public $username = NULL;
	public $firstnames = NULL;
	public $surname = NULL;
	public $organisation_name = NULL;
	public $organisations_id = NULL;
	public $is_audit_note = NULL;

	public $public = null;
	public $readers = null;
	public $modified = NULL;
	public $created = NULL;
}

?>