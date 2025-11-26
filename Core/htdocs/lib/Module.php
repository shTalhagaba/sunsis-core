<?php
class Module extends Entity
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
	modules
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
                $org = new Module();
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
        return DAO::saveObjectToTable($link, 'modules', $this);
    }

    public function delete(PDO $link, $id)
    {
        $lessonsAttached = DAO::getSingleValue($link, "SELECT COUNT(*) FROM lessons WHERE module = " . $id);
	    if($lessonsAttached != 0)
		    throw new Exception("There are lessons attached to this module, it cannot be deleted.");
	    else
	    {
		    DAO::execute($link, "DELETE FROM modules WHERE id = " . $id);
		    return true;
	    }
    }


    public function isSafeToDelete(PDO $link)
    {
        return false;
    }

    public $id = null;
    public $title = NULL;
    public $provider_id = NULL;
    public $learning_hours = NULL;

}
?>