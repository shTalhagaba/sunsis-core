<?php
class LearnerGroup extends Entity
{
	public static function loadFromDatabase(PDO $link, $learnergroup_id)
	{
	
		if($learnergroup_id == '')
		{
			return null;
		}

		$key = addslashes((string)$learnergroup_id);
$query = <<<HEREDOC
SELECT
	*
FROM
	learner_groups
WHERE
	id='$key';
HEREDOC;
		$st = $link->query($query);	

		$learnergroup = null;
		if($st)
		{
			$learnergroup = null;
			$row = $st->fetch();
			if($row)
			{
				$learnergroup = new LearnerGroup();
				$learnergroup->populate($row);
				$learnergroup->id = $learnergroup_id;
			}
			
		}
		else
		{
			throw new Exception("Could not execute database query to find contract. " . '----' . $query . '----' . $link->errorCode());
		}

		return $learnergroup;	
	}
	
	public function save(PDO $link)
	{
		return DAO::saveObjectToTable($link, 'learner_groups', $this);
	}
	
	public function delete(PDO $link)
	{

		$qan = addslashes((string)$this->id);
		
		// Delete the qualification's structure and the qualification
		$sql = <<<HEREDOC
DELETE FROM
	learner_groups
WHERE
	id = '$qan';
HEREDOC;
		DAO::execute($link, $sql);
	}
	
	
	public function isSafeToDelete(PDO $link)
	{
		return false;
	}
	
	
	public $id = null;
	public $title = NULL;
	public $start_date = NULL;
	public $end_date = NULL;
	public $sector = NULL;
	public $comments = NULL;
}
?>