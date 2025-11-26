<?php
class EmployerContact extends Entity
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
	employer_contact
WHERE
	id='$key';
HEREDOC;
        $st = $link->query($query);

        $fs_progress = null;
        if($st)
        {
            $fs_progress = null;
            $row = $st->fetch();
            if($row)
            {
                $fs_progress = new EmployerContact();
                $fs_progress->populate($row);
            }

        }
        else
        {
            throw new Exception("Could not execute database query to find exam result for the training record. " . '----' . $query . '----' . $link->errorCode());
        }

        return $fs_progress;
    }

    public function save(PDO $link)
    {

        return DAO::saveObjectToTable($link, 'employer_contact', $this);
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
    public $forecast_date = NULL;
    public $contact_date = NULL;
    public $contact_type = NULL;
    public $contact_name = NULL;
    public $comments = NULL;
    public $arm_attended = NULL;
    public $progression_opportunities = NULL;

    const REQUIRED = 1;
    const EXEMPTED = 2;
    const BOOKED = 3;
    const NOT_ATTENDED = 4;
    const ATTENDED = 5;

}
?>