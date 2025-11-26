<?php
class LearnerCrmNote extends Entity
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
	crm_notes_learner
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
                $org = new LearnerCrmNote();
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
        if($this->id == '')
            $this->created_by = $_SESSION['user']->id;

        return DAO::saveObjectToTable($link, 'crm_notes_learner', $this);
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
    public $tr_id = NULL;
    public $name_of_person = NULL;
    public $position = NULL;
    public $type_of_contact = NULL;
    public $subject = NULL;
    public $date = NULL;
    public $agreed_action = NULL;
    public $by_whom = NULL;
    public $notify_assessor = NULL;
    public $whom_position = NULL;
    public $next_action_date = NULL;
    public $next_action_time = NULL;
    public $created_by = NULL;
    public $date_created = NULL;
    public $contact_time = NULL;
    public $rating = NULL;
    public $concerns = NULL;
    public $reason = NULL;
    public $for_caseload = NULL;
}
?>