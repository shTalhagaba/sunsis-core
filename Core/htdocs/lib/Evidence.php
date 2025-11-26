<?php
class Evidence extends Entity
{
	public static function loadFromDatabase(PDO $link, $tr_id, $evidence_id)
	{
		if($evidence_id == '')
		{
			return null;
		}

		$key = addslashes((string)$evidence_id);
$query = <<<HEREDOC
SELECT
	*
FROM
	evidence_template
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
				$org = new Evidence();
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
		return DAO::saveObjectToTable($link, 'evidence_template', $this);
	}
	
	public function delete(PDO $link)
	{
		$qan = addslashes((string)$this->id);
		
		// Delete the evidence
		$sql = <<<HEREDOC
DELETE FROM
	evidence_template
WHERE
	id = $qan;
HEREDOC;
		DAO::execute($link, $sql);
	}
	
	
	
	public function isSafeToDelete(PDO $link)
	{
		return false;
	}
	
	
	public $id = null;
	public $title = NULL;
	public $type = NULL;
	public $content = NULL;
	public $date = NULL;
	public $assessor = NULL;
	public $tr_id = NULL;
	public $qualification_id = NULL;
	public $internaltitle = NULL;
	public $framework_id = NULL;
	public $page_no = NULL;
	public $verified = 0;
	public $category = NULL;
	public $reference = NULL;
	public $comments = NULL;
	
}
?>