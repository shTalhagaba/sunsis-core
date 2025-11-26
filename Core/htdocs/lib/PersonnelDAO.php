<?php
/**
 * Personnel DAO
 */
class PersonnelDAO
{
	public function __construct($link)
	{
		if(!$link)
		{
			throw new Exception("Valid PDO link required on creation");
		}
		$this->link = $link;
	}


	public function find(PDO $link, $id)
	{

		$query = "SELECT * FROM users WHERE username=" . "'" . $id . "'" . ";"; // Changed by Khushnood
		$st = $link->query($query);

		$vo = new PersonnelVO();
		if($st)
		{
			$row = $st->fetch();
			if($row)
			{
				$vo->populate($row);
			}
			else
			{
				return null;
			}
		}
		else
		{
			throw new Exception("Could not execute database query to find record. " . '----' . $query . '----' . $link->errorCode());
		}


		return $vo;
	}


	public function insert(PersonnelVO $vo)
	{
		$exclude = array('id');
		$query = "INSERT INTO personnel SET " . $vo->toNameValuePairs($exclude) . ';';

		$st = $this->link->query($query);
		if($st== false)
		{
			throw new Exception("Error inserting record with name " . $vo->surname . ". " . $st->errorCode() );
		}

		return $this->link->lastInsertId();
	}


	public function update(PersonnelVO $vo)
	{
		$exclude = array('id');
		$query = "UPDATE personnel SET " . $vo->toNameValuePairs($exclude) . ' WHERE id=' . $vo->id . ';';
		DAO::execute($this->link, $query);

		return true;
	}


	public function delete($id)
	{
		if(!is_numeric($id))
		{
			throw new Exception("You must specify a numeric id in order to delete a record.");
		}

		if(!$this->isSafeToDelete($id))
		{
			throw new Exception("Staff member #$id cannot be deleted because they have associated records (courses, groups and/or lessons).");
		}
		
		$query = "DELETE FROM personnel WHERE id=$id;";
		$st = $this->link->query($query);

		if($st == false)
		{
			throw new Exception("Could not delete record with id $id. " . $st->errorCode());
		}

		// To do -- add code for deleting related data

		return true;
	}

	
	public function isSafeToDelete($id)
	{
		// If the staff member is not referenced in courses, groups or lessons
		// then it is safe to be deleted
		$num_courses = "SELECT count(*) FROM courses WHERE director = $id;";
		$num_groups = "SELECT count(*) FROM groups WHERE tutor = $id;";
		$num_lessons = "SELECT count(*) FROM lessons WHERE tutor = $id;";
		$num_courses = DAO::getSingleValue($this->link, $num_courses);
		$num_groups = DAO::getSingleValue($this->link, $num_groups);
		$num_lessons = DAO::getSingleValue($this->link, $num_lessons);
		

		return ($num_courses === 0) && ($num_groups === 0) && ($num_lessons === 0);
	}
	
	
	
	private $link = null;
}
?>