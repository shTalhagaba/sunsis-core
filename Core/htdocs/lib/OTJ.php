<?php
class OTJ extends Entity
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
	otj
WHERE
	id='$key';
HEREDOC;
        $st = $link->query($query);

        $otj = null;
        if($st)
        {
            $otj = null;
            $row = $st->fetch();
            if($row)
            {
                $otj = new OTJ();
                $otj->populate($row);
            }

        }
        else
        {
            throw new Exception("Could not execute database query to find appointment for the training record. " . '----' . $query . '----' . $link->errorCode());
        }

        if(preg_match('/^(\d\d:\d\d)/', $otj->time_from, $matches))
        {
            $otj->time_from = $matches[1];
        }

        if(preg_match('/^(\d\d:\d\d)/', $otj->time_to, $matches))
        {
            $otj->time_to = $matches[1];
        }

        return $otj;
    }

    public function save(PDO $link)
    {
        $this->created = ($this->id == "") ? date('Y-m-d H:i:s') : $this->created;

        return DAO::saveObjectToTable($link, 'otj', $this);
    }

	public static function deleteEntityRelatedRecords(PDO $link, $tr_ids, $entity_type, $entity_id)
	{
		if($entity_id == '' || $entity_type == '' || !is_array($tr_ids))
			return 0;

		if(count($tr_ids) == 0)
			return 0;

		$sql_delete_otj = "DELETE FROM otj WHERE entity_type = '{$entity_type}' AND entity_id = '{$entity_id}' AND tr_id IN (" . implode(",", $tr_ids) . ")";

		return DAO::execute($link, $sql_delete_otj);
	}

    public $id = NULL;
	public $tr_id = NULL;
	public $date = NULL;
	public $time_from = NULL;
	public $time_to = NULL;
	public $duration_hours = NULL;
	public $duration_minutes = NULL;
	public $type = NULL;
	public $comments = NULL;
	public $entity_type = NULL;
	public $entity_id = NULL;

    public $created = NULL;
    public $modified = NULL;

}
?>