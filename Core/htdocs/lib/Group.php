<?php
class Group extends Entity
{
	public static function loadFromDatabase(PDO $link, $id)
	{
		if($id == '')
		{
			return null;
		}
		
		if(is_numeric($id))
		{
			$query = "SELECT * FROM groups WHERE id=" . addslashes((string)$id);
		}
		else
		{
			$query = "SELECT * FROM groups WHERE group_name='" . addslashes((string)$id) . "';";
		}

		$group = new Group();
		$st = $link->query($query);	
		if($st)
		{
			$row = $st->fetch();
			if($row)
			{
				$group->populate($row);
			}
			else
			{
				return null;
			}
			
		}
		else
		{
			throw new DatabaseException($link, $query);
		}
		
		$sql = "SELECT member FROM group_members WHERE groups_id=" . $group->id . ";";
		$group->members = DAO::getSingleColumn($link, $sql);
		
		return $group;
	}

	
	public function save(PDO $link)
	{
		if($this->id == '')
		{
			// Lock users and groups tables and check for duplicates
			// (FOR UPDATE without WHERE locks the whole InnoDB table)
			$sql = "SELECT username FROM users FOR UPDATE";
			$user_list = DAO::getSingleColumn($link, $sql);
			$sql = "SELECT group_name FROM groups FOR UPDATE";
			$group_list = DAO::getSingleColumn($link, $sql);
			if(in_array($this->group_name, $user_list))
			{
				throw new Exception("A username clashes with group name $this->group_name");
			}
			if(in_array($this->group_name, $group_list))
			{
				throw new Exception("A group with name $this->group_name already exists");
			}
		}
		else
		{
			// Check if the name of the group has changed, and propagate the change if it has
			$existing_record = Group::loadFromDatabase($link, $this->id);
			if(!is_null($existing_record) && ($existing_record->group_name != $this->group_name) )
			{
				// Replace all existing references to the group in the ACL entries table
				$sql = "UPDATE acl SET ident = '".addslashes((string)$this->group_name)
					."' WHERE ident='".addslashes((string)$existing_record->group_name)."'";
				DAO::execute($link, $sql);
				
				// Replace all existing references to the group in the other group definitions
				$sql = "UPDATE group_members SET members = '".addslashes((string)$this->group_name)
					."' WHERE ident='".addslashes((string)$existing_record->group_name)."'";
				DAO::execute($link, $sql);
			}
		}

		
		
		DAO::saveObjectToTable($link, 'groups', $this);
		
		
		throw new Exception("hold down");
		// Clear all existing members
/*		$sql = "DELETE FROM group_members WHERE groups_id=".$this->id;
		$st = $link->query($sql);
		if($st == false)
		{
			throw new Exception(implode($link->errorInfo()));
		}
*/		
		// Save members (except self-references)
		$values = '';
		foreach($this->members as $member)
		{
			if($member != $this->group_name)
			{
				if(strlen($values) > 0)
				{
					$values .= ',';
				}
				$values .= "({$this->id}, '".addslashes((string)$member)."')";
			}
		}
		if(strlen($values) > 0)
		{
			$sql = "INSERT INTO group_members (groups_id, member) VALUES $values";
			DAO::execute($link, $sql);
		}
	}	
	
	
	public function delete(PDO $link)
	{
		if($this->isSafeToDelete($link) == false)
		{
			throw new Exception("This group cannot be deleted");
		}
		
		$query = <<<HEREDOC
DELETE FROM
	groups, group_members, acl
USING
	groups LEFT OUTER JOIN group_members ON groups.id = group_members.groups_id
	LEFT OUTER JOIN acl ON groups.group_name = acl.ident
WHERE
	groups.id = '{$this->id}';
HEREDOC;
		DAO::execute($link, $query);
		
		return true;
	}
	
	
	public function isSafeToDelete(PDO $link)
	{
		return !$this->system_group;
	}
	
	
	public function getMembers()
	{
		return $this->members;
	}
	

	public function setMembers(PDO $link, array $members)
	{
		$this->members = $this->trim_array_elements($members);
	}

	
	/**
	 * Returns all nested groups in this group.
	 * @param $groups The growing list of groups - a defence against infinite
	 * recursion caused by circular group references.
	 */
	public function getNestedGroups(PDO $link, array $groups = array())
	{
		$sql = "SELECT `group_name` FROM groups ORDER BY `group_name`;";
		$all_groups = DAO::getSingleColumn($link, $sql);


		foreach($this->members as $member)
		{
			if(in_array($member, $all_groups) && !in_array($member, $groups))
			{
				$groups[] = $member;
				
				$g = Group::loadFromDatabase($link, $member);
				$groups = array_merge($groups, $g->getNestedGroups($link, $groups));
			}
		}
		
		sort($groups);
		return array_unique($groups);
	}
	
	
	public function getParentGroups(PDO $link)
	{
		$key = addslashes((string)$this->group_name);
		$sql = "SELECT DISTINCT member FROM group_members WHERE member='$key'";
		$groups = DAO::getSingleColumn($link, $sql);
		
		$last_count = 0;
		while(count($groups) > $last_count)
		{
			$last_count = count($groups);
			$sql = "SELECT DISTINCT member FROM group_members WHERE member IN (".DAO::pdo_implode($groups).")";
			$groups = array_unique(array_merge($groups, DAO::getSingleColumn($link, $sql)));
		}
		
		return $groups;
	}
	

	
	private function trim_array_elements(array $a)
	{
		$b = array();
		foreach($a as $element)
		{
			$trimmed = trim($element);
			if(strlen($trimmed) > 0)
			{
				$b[] = $trimmed;
			}
		}
		
		return $b;
	}
	
	public $id = NULL;
	public $group_name = NULL;
	public $description = NULL;
	public $system_group = NULL;
	
	private $members = array();
}
?>