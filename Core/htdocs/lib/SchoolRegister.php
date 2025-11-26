<?php
class SchoolRegister implements Iterator
{
	###############################################
	## Iterator interface
	###############################################
	public function rewind()
	{
		reset($this->entries);
	}
	
	public function current()
	{
		return current($this->entries);
	}

	public function key()
	{
		return key($this->entries);
	}
	
	public function next()
	{
		return next($this->entries);
	}
		
	public function valid()
	{
		return (current($this->entries) !== false);
	}
	################################################
	## End Iterator interface
	################################################
	
	
	/**
	 *  Constructor
	 */
	public function __construct($date_from, PDO $link, $date_to = null)
	{
		$this->date_from = $date_from;
		$this->date_to = $date_to;
		
		if(is_null($this->date_to))
		{
			$this->date_to = $this->date_from;
		}
		
		$sql = "SELECT code, description FROM lookup_register_entry_codes ORDER BY code;";
		$this->desc_lookup = DAO::getLookupTable($link, $sql);
	}
	
	
	/**
	 * Add a register entry to the register
	 * 
	 * @param RegisterEntry $e
	 */
	public function addEntry(RegisterEntry $e)
	{
		// Assign a description if the description field is blank
		// and an attendance code has been specified
		if($e->entry != '' && $e->entry_description == '')
		{
			$e->entry_description = $this->desc_lookup[(integer)$e->entry];
		}
		
		// Assign a lesson ID to the entry if one has not already been
		// assigned. This allows register entries to have mixed lesson ids,
		// which may be useful when coalescing a day's entries into one
		// all encompassing register.
		if($e->lessons_id == '')
		{
			$e->lessons_id = $this->lesson->id;
		}
		
		$this->entries[] = $e;
	}
	

	
	public function load(PDO $link)
	{
		$sql = <<<HEREDOC
SELECT
	re.id AS id,
	l.id AS lessons_id, 
	pot.id AS pot_id,
	re.entry,
	re.created,
	lookup.description AS entry_description,

	pot.students_id AS student_id, pot.firstnames AS student_firstnames,
	pot.surname AS student_surname, pot.gender AS student_gender,
	pot.school_id, pot.start_date AS pot_start, pot.closure_date AS pot_end,
	IF(l.date < pot.start_date || (pot.closure_date IS NOT NULL AND l.date > pot.closure_date), FALSE, TRUE) AS within_pot_dates,
	schools.short_name AS school_short_name,

	notes.id AS note_id, notes.note AS note, notes.created AS note_created,
	notes.username AS note_username, notes.firstnames AS note_firstnames,
	notes.surname AS note_surname, notes.organisation_name,
	notes.entry AS note_entry
FROM
	lessons AS l INNER JOIN group_members AS gm INNER JOIN pot INNER JOIN organisations AS schools
	ON (l.groups_id = gm.groups_id AND pot.id = gm.pot_id AND pot.school_id = schools.id)
	LEFT OUTER JOIN register_entries AS re ON (re.pot_id = pot.id AND re.lessons_id = l.id)
	LEFT OUTER JOIN register_entry_notes AS notes ON notes.register_entries_id = re.id
	LEFT OUTER JOIN lookup_register_entry_codes AS lookup ON lookup.code = re.entry
WHERE
	l.id = {$this->lesson->id}
ORDER BY
	pot.surname, pot.firstnames, notes.created ASC;
HEREDOC;

		$st = $link->query($sql);
		
		if($st) 
		{
			$row = $st->fetch();
	
			while($row)
			{
				$entry = new RegisterEntry();
				$entry->populate($row); // sql query written carefully so that this will work

				$current_pot_id = $row['pot_id'];

				if(!is_null($row['note']))
				{	
					// Note present.  Add the note to the entry
					// and see if there are any further notes for this
					// entry in the following rows.
					do
					{
						$note = new RegisterEntryNote();
						$note->id = 					$row['note_id'];
						$note->register_entries_id = 	$row['id'];
						$note->note = 					$row['note'];
						$note->username = 				$row['note_username'];
						$note->firstnames = 			$row['note_firstnames'];
						$note->surname = 				$row['note_surname'];
						$note->created = 				$row['note_created'];
						$note->entry =					$row['note_entry'];
						
						$entry->addNote($note);
										
						$row = $st->fetch();
						
					} while($row['pot_id'] == $current_pot_id); // check the next row
				}
				else
				{
					// No note - move on to next record
					$row = $fetch();
				}
				
				// Add the register entry to this register
				$this->addEntry($entry);
			}
			
			$result->close();
		}
		else
		{
			throw new Exception("Could not locate lesson with id $lesson_id in order to construct a register");
		}
		
	}

	
	public function save(PDO $link)
	{
		foreach($this->entries as $e)
		{
			$e->save($link);
		}
	}
	


	
	// Value objects
	private $entries = array();
	private $desc_lookup = null;
	
	private $date_from = null;
	private $date_to = null;
}

?>