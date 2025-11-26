<?php
class InternalValidation extends Entity
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
	internal_validation
WHERE
	id='$key';
HEREDOC;
		$st = $link->query($query);

		$internal_validation = null;
		if($st)
		{
			$internal_validation = null;
			$row = $st->fetch();
			if($row)
			{
				$internal_validation = new InternalValidation();
				$internal_validation->populate($row);
				if($internal_validation->id != '')
					$internal_validation->iv_units = DAO::getSingleColumn($link, "SELECT unit_reference FROM internal_validation_unit_details WHERE internal_validation_id = " . $internal_validation->id);

			}

		}
		else
		{
			throw new Exception("Could not execute database query to find internal validation record for the training record. " . '----' . $query . '----' . $link->errorCode());
		}

		if(preg_match('/^(\d\d:\d\d)/', $internal_validation->iv_date, $matches))
		{
			$internal_validation->iv_date = $matches[1];
		}
		if(preg_match('/^(\d\d:\d\d)/', $internal_validation->iv_action_date, $matches))
		{
			$internal_validation->iv_action_date = $matches[1];
		}

		return $internal_validation;
	}

	public function save(PDO $link)
	{
		return DAO::saveObjectToTable($link, 'internal_validation', $this);
	}

	public function saveIVUnits(PDO $link, array $units)
	{
		DAO::execute($link, "DELETE FROM internal_validation_unit_details WHERE internal_validation_id = " . $this->id);

		$data = array();
		foreach($units as $unit)
		{
			$data[] = array('internal_validation_id' => $this->id, 'unit_reference' => $unit);
		}
		DAO::multipleRowInsert($link, 'internal_validation_unit_details', $data);
	}

	public function delete(PDO $link)
	{

	}


	public function isSafeToDelete(PDO $link)
	{
		return false;
	}

	public $id = NULL;
	public $tr_id = NULL;
	public $iv_user_id = NULL;
	public $iv_date = NULL;
	public $iv_type = NULL;
	public $comments = NULL;
	public $iv_qualification_id = NULL;
	public $iv_units = NULL;
	public $iv_action_date = NULL;
	public $evidence = NULL;

}
?>