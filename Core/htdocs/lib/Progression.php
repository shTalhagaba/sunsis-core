<?php
class Progression extends Entity
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
	progression
WHERE
	tr_id='$key';
HEREDOC;
        $st = $link->query($query);

        $org = null;
        if($st)
        {
            $org = null;
            $row = $st->fetch();
            if($row)
            {
                $org = new Progression();
                $org->populate($row);
            }
            else
            {
                $org = new Progression();
            }

        }
        else
        {
            throw new Exception("Could not execute database query to find contract. " . '----' . $query . '----' . $link->errorCode());
        }

        return $org;
    }

    public static function getDropdown($dropdown = 1)
    {
        if($dropdown==1)
            return Array(Array(1,"Definitely Progressing"),Array(2,"Likely"),Array(3,"Unlikely"),Array(4,"Not Progressing"),Array(5,"Not Applicable (Level 4 Only)"));
        else if($dropdown==2)
            return Array(Array(1,"Alternative Training/Further Education"),Array(2,"Break from learning"),Array(3,"Change of role"),Array(4,"Chose another Provider"),Array(5,"Dissatisfied with Baltic"),Array(6,"Full time role"),Array(7,"Issues with employer"),Array(8,"Learner Leaving"),Array(9,"Mental Health"),Array(10,"Problems on Level 3"),Array(11,"No route available"));
        else if($dropdown==3)
            return Array(Array(1,"Alternative Training/Further Education"),Array(2,"Change of career/ role"),Array(3,"Chose another Provider"),Array(4,"Dissatisfied with Baltic"),Array(5,"Programme not of interest"),Array(6,"Employer Decision"),Array(7,"Full time role"),Array(8,"Issues with Employer"),Array(9,"Learner Leaving"),Array(10,"Learner Performance"),Array(11,"Problems on Level 3"),Array(12,"No route available"),Array(13,"Won't support"));
    }

    public function save(PDO $link)
    {
        if($this->id == '')
            $this->created_by = $_SESSION['user']->id;

        return DAO::saveObjectToTable($link, 'progression', $this);
    }

    public function delete(PDO $link)
    {
        // Placeholder
    }


    public function isSafeToDelete(PDO $link)
    {
        return false;
    }


    public $tr_id = NULL;
    public $id = NULL;
    public $created_by = NULL;
    public $month_9_learner = NULL;
    public $month_9_learner_reason = NULL;
    public $month_9_learner_reason2 = NULL;
    public $month_9_employer = NULL;
    public $month_9_employer_reason = NULL;
    public $month_9_employer_reason2 = NULL;
    public $month_12_learner = NULL;
    public $month_12_learner_reason = NULL;
    public $month_12_learner_reason2 = NULL;
    public $month_12_employer = NULL;
    public $month_12_employer_reason = NULL;
    public $month_12_employer_reason2 = NULL;
    public $latest_learner_status = NULL;
    public $latest_learner_reason = NULL;
    public $latest_employer_status = NULL;
    public $latest_employer_reason = NULL;
    public $month_9_learner_date = NULL;
    public $month_12_learner_date = NULL;
    public $latest_learner_status_date = NULL;
    public $month_9_employer_date = NULL;
    public $month_12_employer_date = NULL;
    public $latest_employer_status_date = NULL;
    public $learner_progression_comments = NULL;
    public $employer_progression_comments = NULL;

    public $narrative = NULL;
}
?>