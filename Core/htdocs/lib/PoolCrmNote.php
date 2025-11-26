<?php
class PoolCrmNote extends Entity
{
	public static function loadFromDatabase(PDO $link, $id)
	{
		if($id == '')
		{
			return null;
		}
		
		$key = addslashes((string)$id);
		$query = <<<HEREDOC
SELECT
	*
FROM
	employerpool_notes
WHERE
	id='$key';
HEREDOC;
		$st = $link->query($query);

		$org = null;
		if($st)
		{
			$org = null;
			$row = $st->fetch();
			if($row)
			{
				$org = new PoolCrmNote();
				$org->populate($row);
			}
			
		}
		else
		{
			throw new Exception("Could not execute database query to find contract. " . '----' . $query . '----' . $link->errorCode());
		}

		return $org;	
	}
	
	public function save(PDO $link)
	{
		return DAO::saveObjectToTable($link, 'employerpool_notes', $this);
	}
	
	public function delete(PDO $link)
	{
		// Placeholder
	}
	
	
	public function isSafeToDelete(PDO $link)
	{
		return false;
	}
	
	
	public $id = null;
	public $organisation_id = NULL;
	public $name_of_person = NULL;
	public $position = NULL;
	public $type_of_contact = NULL;
	public $subject = NULL;
	public $date = NULL;
	public $agreed_action = NULL;
	public $by_whom = NULL;
	public $whom_position = NULL;
	public $audit_info = NULL;
	public $priority = NULL;
	public $outcome = NULL;	
	public $timeset = NULL;
	public $next_action_date = NULL;
	public $next_action = NULL;
}
?>