<?php
class LessonNoteDAO
{
	public function __construct(PDO $link)
	{
		if(!$link)
		{
			throw new Exception('Missing argument, $link');
		}
		$this->link = $link;
	}


	public function find($id)
	{

		$query = "SELECT * FROM lesson_notes WHERE id=" . addslashes((string)$id) . ";";
		
		$st = $this->link->query($query);
		
		$vo = new LessonNoteVO();
		if($st)
		{
			$row = $st->fetch();
			if($row)
			{
				$vo->populate($row);
			}
			else
			{
				throw new Exception("Could not find a record with id #$id in the database");
			}
		}
		else
		{
			throw new DatabaseException($this->link, $query);
		}


		return $vo;
	}


	public function insert(LessonNoteVO $vo)
	{
		$exclude = array('id');
		$query = "INSERT INTO lesson_notes SET " . $vo->toNameValuePairs($exclude) . ';';
		$st = $this->link->query($query);
		if($st== false)
		{
			throw new Exception("Could not add note to lesson. " . $st->errorCode());
		}
		
		$query = "UPDATE lessons SET `modified`=CURRENT_TIMESTAMP WHERE id=".$vo->lessons_id;
		$st = $this->link->query($query);
		if($st== false)
		{
			// Ignore -- we can live with this error
		}

		return $this->link->lastInsertId();
	}


	public function update(PotNoteVO $vo)
	{
		$exclude = array('id');
		$query = "UPDATE lesson_notes SET " . $vo->toNameValuePairs($exclude) . ' WHERE id=' . $vo->id . ';';
		$st = $this->link->query($query);

		if($st == false)
		{
			throw new Exception("Could not update lesson note. " . $st->errorCode());
		}

		return true;
	}


	public function delete($id)
	{
		if(!is_numeric($id))
		{
			throw new Exception("You must specify a numeric id.");
		}

		if(!$this->isSafeToDelete($id))
		{
			throw new Exception("Note #$id cannot be deleted.");
		}
		
		$query = "DELETE FROM lesson_notes WHERE id=$id;";
		$st = $this->link->query($query);

		if($st == false)
		{
			throw new Exception("Could not delete note with id $id. " . $st->errorCode());
		}

		// To do -- add code for deleting related data

		return true;
	}

	
	public function isSafeToDelete($id)
	{
		return false; // For now, deletion of official notes is forbidden
	}
	
	
	private $link = null;
}
?>