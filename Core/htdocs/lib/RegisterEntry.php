<?php
class RegisterEntry extends ValueObject implements Iterator
{
	###############################################
	## Iterator interface
	###############################################
	public function rewind()
	{
		reset($this->notes);
	}
	
	public function current()
	{
		return current($this->notes);
	}

	public function key()
	{
		return key($this->notes);
	}
	
	public function next()
	{
		return next($this->notes);
	}
		
	public function valid()
	{
		return (current($this->notes) !== false);
	}
	################################################
	## End Iterator interface
	################################################
	
	
	public function addNote(RegisterEntryNote $n)
	{
		$n->entry = $this->entry;
		$n->created = date('Y-m-d G:i:s');
		$this->notes[] = $n;
	}
	
	
	public function hasNotes()
	{
		return count($this->notes) > 0;
	}
	
	
	public function save(PDO $link)
	{
		$exclude_fields = array(
			'id',
			'description',
			'student_id',
			'student_firstnames',
			'student_surname',
			// #170 - relmes - username for photography
			'student_username',		
			'entry_description',
			'pot_status',
			'pot_start',
			'port_end',
			'within_pot_dates'.
			'school_id',
			'school_short_name',
			'provider_short_name',
			'location_short_name',
			'course_title',
			'lesson_start_time',
			'lesson_end_time');
		
		if(!$this->id)
		{
			// New record - set created
			$this->created = date('Y-m-d G:i:s');
			
			// New record
			$sql = "INSERT INTO register_entries SET " . $this->toNameValuePairs($exclude_fields) . ";";
		}
		else
		{
			// Existing register entry
			
			// Log any changes made to this entry
			$sql = "SELECT re.*, codes.description FROM register_entries AS re LEFT OUTER JOIN lookup_register_entry_codes AS codes ON re.entry = codes.code WHERE id=" . $this->id;
			$st2 = $link->query($sql);
			if($st2 && $st2->rowCount() > 0)
			{
				$row = $st2->fetch();
				
				// If the attendence entry has changed..
				if($row['entry'] != $this->entry)
				{
					$n = new RegisterEntryNote();
					$n->is_audit_note = 1;
					$n->addParagraph("Attendance changed from '" . $row['description'] . "' to '" . $this->entry_description . "'.");
					$this->addNote($n);
				}
				
			}
			else
			{
				throw new Exception("Unable to find an existing register entry with id " . $this->id);
			}
			
			$sql = "UPDATE register_entries SET " . $this->toNameValuePairs($exclude_fields) . " WHERE id=" . $this->id . ";";
		}
		
		// Save to database
		/*$st = $link->query($sql);
		if($st== false)
		{
			throw new Exception("Unable to save register entry " . $this->id . "...." . implode($link->errorInfo()));
		}*/
		DAO::execute($link, $sql);
		
		// Update ID if necessary
		if($this->id == 0)
		{
			$this->id = $link->lastInsertId();
		}
		
		
		// Save notes to database
		foreach($this as $note) /* @var $note RegisterEntryNote */
		{
			$note->register_entries_id = $this->id;
			
			// Only save new notes with content
			if( ($note->id == 0) && (!is_null($note->note)) && ($note->note != '') )
			{
				$note->save($link);
			}
		}
		
	}
	
	
	// Core table fields
	public $id = 0;
	public $lessons_id = null;
	public $pot_id = null;
	public $entry = null;
	public $lesson_contribution = null;
	public $created = null;
	public $school_id = null;
	public $late_starter = null;
	public $pot_closed = null;

	
	// Non-core fields that make programming a lot easier
	public $entry_description = null;
	public $student_id = null;
	// #170 - relmes - username for photography
	public $student_username = null;
	
	public $student_firstnames = null;
	public $student_surname = null;
	public $student_gender = null;
	public $pot_status = null;
	public $pot_start = null;
	public $pot_end = null;
	public $within_pot_dates = null;
//	public $school_id = null;
	public $school_short_name = null;
	
	public $provider_short_name = null;
	public $location_short_name = null;
	public $course_title = null;
	public $lesson_date = null;
	public $lesson_start_time = null;
	public $lesson_end_time = null;
	public $relective_comments_learner = null;

	public $l03 = null;
	
	
	private $notes = array();
}
?>