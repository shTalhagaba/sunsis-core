<?php
class AdditionalSupport extends Entity
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
	additional_support
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
                $fs_progress = new AdditionalSupport();
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

        return DAO::saveObjectToTable($link, 'additional_support', $this);
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
    public $actual_date = NULL;
    public $time_from = NULL;
    public $time_to = NULL;
    public $subject_area = NULL;
    public $contact_type = NULL;
    public $manager_attendance = NULL;
    public $assessor = NULL;
    public $adobe = NULL;
    public $comments = NULL;
	public $due_date = NULL;
	public $revised_date = NULL;
    public $cancellation_comments = NULL;

    const REQUIRED = 1;
    const EXEMPTED = 2;
    const BOOKED = 3;
    const NOT_ATTENDED = 4;
    const ATTENDED = 5;

}
?>