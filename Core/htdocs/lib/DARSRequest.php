<?php
class DARSRequest extends Entity
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
	dars_requests
WHERE
	id='$key';
HEREDOC;
		$st = $link->query($query);

		$request = null;
		if($st)
		{
			$request = null;
			$row = $st->fetch();
			if($row)
			{
				$request = new DARSRequest();
				$request->populate($row);
			}
		}
		else
		{
			throw new Exception("Could not execute database query to find request. " . '----' . $query . '----' . $link->errorCode());
		}

		return $request;
	}

	public function getDarsFiles(PDO $link)
	{
		return DAO::getSingleColumn($link, "SELECT file_name FROM dars_files WHERE dars_id WHERE '{$this->id}'");
	}

	public function getDarsHistory(PDO $link)
	{
		return DAO::getSingleColumn($link, "SELECT notes FROM dars_history WHERE dars_id WHERE '{$this->id}'");
	}

	public function save(PDO $link)
	{
		$this->modified = "";
		$this->created = ($this->id == "") ? date('Y-m-d H:i:s') : $this->created;

		return DAO::saveObjectToTable($link, 'dars_requests', $this);
	}

	public function delete(PDO $link)
	{

	}

	public static function getRequestTypes()
	{
		return  array(
			array('1', 'General Enquiry'),
			array('2', 'How to?'),
			array('3', 'Incident'),
			array('4', 'Inputting/Data Collection'),
			array('5', 'Log-in issues'),
			array('6', 'Programming bug')
		);
	}

	public static function getRequestPriorityList()
	{
		return  array(
			array('1', 'Low'),
			array('2', 'Medium'),
			array('3', 'High'),
			array('4', 'Critical')
		);
	}

	public static function getRequestStatusList()
	{
		return  array(
			array('1', 'Being Looked Into'),
			array('2', 'Escalated'),
			array('3', 'Requiring Feedback'),
			array('4', 'Submitted'),
			array('5', 'Withdrawn'),
			array('6', 'New'),
			array('7', 'Reopened'),
			array('8', 'Closed')
		);
	}

	public function getTypeDescription()
	{
		return isset($this->types[$this->type])?$this->types[$this->type]:'';
	}

	public function getStatusDescription()
	{
		return isset($this->statuses[$this->status])?$this->statuses[$this->status]:'';
	}

	public function getPriorityDescription()
	{
		return isset($this->priorities[$this->priority])?$this->priorities[$this->priority]:'';
	}

	public function isSafeToDelete(PDO $link)
	{
		return false;
	}

	public $id = NULL;
	public $requester = NULL;
	public $type = NULL;
	public $status = NULL;
	public $details = NULL;
	public $priority = NULL;
	public $participants = NULL;
	public $created = NULL;
	public $modified = NULL;
	public $attachment = NULL;
	public $resolved = 0;
	private $types = array('1' => 'General Enquiry', '2' => 'How To?', '3' => 'Incident', '4' => 'Inputting/Data Collection', '5' => 'Programming Bug', '6' => 'Log-in issues');
	private $priorities = array('1' => 'Low', '2' => 'Medium', '3' => 'High', '4' => 'Critical');
	private $statuses = array('1' => 'Being Looked Into', '2' => 'Escalated', '3' => 'Requiring Feedback', '4' => 'Submitted', '5' => 'Withdrawn', '6' => 'New', '7' => 'Reopened', '8' => 'Closed');

}
?>