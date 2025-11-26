<?php
class CRMHoliday extends Entity
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
	crm_holidays
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
                $org = new CrmNote();
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
        return DAO::saveObjectToTable($link, 'crm_holidays', $this);
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
    public $contact_id = NULL;
    public $holiday_start_date = NULL;
    public $holiday_end_date = NULL;
    public $comments = NULL;

}
?>