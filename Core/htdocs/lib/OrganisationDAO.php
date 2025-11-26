<?php
/**
 * User DAO
 */
class OrganisationDAO
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
		$query = "SELECT * FROM organisations WHERE id=" . addslashes((string)$id) . ";";
		$st = $link->query($query);

		$vo = new OrganisationVO();
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
			throw new Exception("Could not execute database query to find organisation. " . '----' . $query . '----' . $link->errorCode());
		}


		return $vo;
	}


	public function insert(OrganisationVO $vo)
	{
		$exclude = array('id');
		$query = "INSERT INTO organisations SET " . $vo->toNameValuePairs($exclude) . ';';
		$st = $this->link->query($query);

		if($st == false)
		{
			throw new Exception("Error inserting record with name " . $vo->legal_name . ". " . $st->errorCode() );
		}

		return $this->link->lastInsertId();
	}


	public function update(OrganisationVO $vo)
	{
		$exclude = array('id');
		$query = "UPDATE organisations SET " . $vo->toNameValuePairs($exclude) . ' WHERE id=' . $vo->id . ';';
		$st = $this->link->query($query);

		if($st == false)
		{
			throw new Exception("Error updating record with id " . $vo->id . ". " . $st->errorCode());
		}

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
			throw new Exception("Organisation #$id cannot be deleted because it has associated records");
		}
		


		$sql = <<<HEREDOC
DELETE FROM
	organisations, locations, personnel
USING
	organisations LEFT OUTER JOIN locations ON organisations.id = locations.organisations_id
	LEFT OUTER JOIN personnel ON organisations.id = personnel.organisations_id
WHERE
	organisations.id = $id
HEREDOC;
		$st = $this->link->query($sql);
		if($st== false)
		{
			throw new Exception($st->errorCode());
		}

		
		return true;
	}
	
	
	public function isSafeToDelete($id)
	{
		$o_vo = $this->find($id); /* @var $o_vo OrganisationVO */
		
		switch($o_vo->org_type_id)
		{
			case 1:
				$num_students = "SELECT COUNT(*) FROM students WHERE school_id=$id;";
				$num_pot = "SELECT COUNT(*) FROM pot WHERE school_id=$id;";
				$num_users = "SELECT COUNT(*) FROM users WHERE organisations_id=$id;";
				$num_students = DAO::getSingleValue($this->link, $num_students);
				$num_pot = DAO::getSingleValue($this->link, $num_pot);
				$num_users = DAO::getSingleValue($this->link, $num_users);
				return ($num_students == 0) && ($num_pot == 0) && ($num_users == 0);
				break;
			
			case 2:
				$num_courses = "SELECT COUNT(*) FROM courses WHERE organisations_id=$id;";
				$num_users = "SELECT COUNT(*) FROM users WHERE organisations_id=$id;";
				$num_courses = DAO::getSingleValue($this->link, $num_courses);
				$num_users = DAO::getSingleValue($this->link, $num_users);
				return ($num_courses == 0) && ($num_users == 0);
				break;
			
			case 3:
				return false; // no deleting for now - placeholder
				break;
				
			default:
				throw new Exception("Organisation uses unknown type code");
				break;
		}
	}
	

	private $link = null;
}
?>