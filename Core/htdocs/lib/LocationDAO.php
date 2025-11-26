<?php
/**
 * Location DAO
 */
class LocationDAO
{
	public function __construct($link)
	{
		if(!$link)
		{
			throw new Exception("Valid PDO link required on creation");
		}
		$this->link = $link;
	}


	public function find($id)
	{

		$query = "SELECT * FROM locations WHERE id=" . addslashes((string)$id) . ";";
		$st = $this->link->query($query);

		$vo = new LocationVO();
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
			throw new DatabaseException($this->link, $query);
		}


		return $vo;
	}


	public function insert(LocationVO $vo)
	{
		// If there is currently no main address for this location's organisation
		// then this location automatically becomes the main address
		$query = "SELECT COUNT(*) FROM locations WHERE organisations_id={$vo->organisations_id} AND is_legal_address=1;";
		$st = $this->link->query($query);	
		if($st)
		{
			if($row = $st->fetch())
			{
				if($row[0] == 0)
				{
					$vo->is_legal_address = 1;
				}
			}
			
		}
		
		// There can only be one main address...
		if($vo->is_legal_address == 1)
		{
			$query = "UPDATE locations SET is_legal_address=0 WHERE organisations_id=" . $vo->organisations_id;
			$st = $this->link->query($query);	
			if($st == false)
			{
				throw new Exception("Could not unset 'is_legal_address' flag for all locations" . $st->errorCode());
			}
		}
		
		$exclude = array('id');
		$query = "INSERT INTO locations SET " . $vo->toNameValuePairs($exclude) . ';';
		$st = $this->link->query($query);

		if($st == false)
		{
			throw new Exception("Error inserting record with name " . $vo->full_name . "... " . $query . "...." . $st->errorCode() );
		}

		return $this->link->lastInsertId();
	}


	public function update(LocationVO $vo)
	{
		// Users should not be allowed to 'unset' a record from being a main address
		// The only way to unset a location's status as main address is if another
		// location is nominated as main address.
		$current_record = $this->find($vo->id); /* @var $current_record LocationVO */
		if($current_record->is_legal_address == 1)
		{
			$vo->is_legal_address = 1;
		}
		
		// There can only be one main address...
		if($vo->is_legal_address == 1)
		{
			$query = "UPDATE locations SET is_legal_address=0 WHERE organisations_id=" . $vo->organisations_id;
			$st = $this->link->query($query);
			if($st == false)
			{
				throw new Exception("Could not unset 'is_legal_address' flag for all locations" . $st->errorCode());
			}
		}
		
		
		$exclude = array('id');
		$query = "UPDATE locations SET " . $vo->toNameValuePairs($exclude) . ' WHERE id=' . $vo->id . ';';
		$st = $this->link->query($query);

		if($st== false)
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
			throw new Exception("Location #$id cannot be deleted because either "
				. "lessons are associated with it or it is the main address for its parent organisation");
		}
		
		$query = "DELETE FROM locations WHERE id=$id;";
		$st = $this->link->query($query);

		if($st== false)
		{
			throw new Exception("Could not delete location with id $id. " . $st->errorCode());
		}

		return true;
	}

	
	public function isSafeToDelete($id)
	{
		$associated_lessons = "SELECT COUNT(*) FROM lessons WHERE location=$id;";
		$total_locations_for_org = "SELECT COUNT(*) FROM locations WHERE organisations_id = (SELECT organisations_id FROM locations WHERE id=$id);";
		$is_not_main_address = "SELECT COUNT(*) FROM locations WHERE id=$id AND is_legal_address=FALSE;";
		
		$associated_lessons = DAO::getSingleValue($this->link, $associated_lessons);
		$total_locations_for_org = DAO::getSingleValue($this->link, $total_locations_for_org);
		$is_not_main_address = DAO::getSingleValue($this->link, $is_not_main_address);
		
		return ($associated_lessons == 0) && ($total_locations_for_org > 1) && ($is_not_main_address == 1);
	}


	
	private $link = null;
}
?>