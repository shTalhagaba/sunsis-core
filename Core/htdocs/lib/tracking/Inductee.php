<?php
class Inductee extends Entity
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
	inductees
WHERE
	id='$key';
HEREDOC;
        $st = $link->query($query);

        $inductee = null;
        if($st)
        {
            $inductee = null;
            $row = $st->fetch();
            if($row)
            {
                $inductee = new Inductee();
                $inductee->populate($row);

                if(!is_null($row['id']) && $row['id'] != '')
                {
                    $res = DAO::getSingleColumn($link, "SELECT id FROM induction WHERE inductee_id = '" . $row['id'] . "'");
                    foreach($res AS $r)
                        $inductee->inductions[] = Induction::loadFromDatabase($link, $r);
                    $res = DAO::getSingleColumn($link, "SELECT id FROM induction_programme WHERE inductee_id = '" . $row['id'] . "'");
                    foreach($res AS $r)
                        $inductee->inductionProgramme = InductionProgramme::loadFromDatabase($link, $r);
                }
            }
        }
        else
        {
            throw new Exception("Could not execute database query to find induction record. " . '----' . $query . '----' . $link->errorCode());
        }

        return $inductee;
    }

    public function save(PDO $link)
    {
        $this->modified = "";
        $this->created = ($this->id == "") ? date('Y-m-d H:i:s') : $this->created;
        $this->created_by = ($this->id == "") ? $_SESSION['user']->id : $this->created_by;

        return DAO::saveObjectToTable($link, 'inductees', $this);
    }

    public function delete(PDO $link)
    {
	$link->beginTransaction();
		try
		{
            DAO::execute($link, "DELETE FROM induction_programme WHERE inductee_id = '{$this->id}'");
            DAO::execute($link, "DELETE FROM induction WHERE inductee_id = '{$this->id}'");
            DAO::execute($link, "DELETE FROM inductees WHERE id = '{$this->id}'");

            $link->commit();
        }
        catch(Exception $e)
		{
			$link->rollback();
			throw new WrappedException($e);
		}

        return true;
    }


    public function isSafeToDelete(PDO $link)
    {
        return false;
    }

    public function renderComments(PDO $link, $note_type)
    {
        $html = '<table class="table">';
        $html .= '<tr><th>Creation DateTime</th><th>Created By</th><th>Detail</th></tr>';
        $notes = DAO::getSingleValue($link, "SELECT inductees.{$note_type} FROM inductees WHERE inductees.id = '{$this->id}'");
        if($notes == '')
        {
            $html .= '<tr><td colspan="3"><i>No record found.</i></td></tr>';
        }
        else
        {
            $notes = XML::loadSimpleXML($notes);
            foreach($notes->Note AS $note)
            {
                $html .= '<tr>';
                $html .= '<td>' . Date::to($note->DateTime, Date::DATETIME) . '</td>';
                $html .= '<td>' . DAO::getSingleValue($link, "SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE users.id = '{$note->CreatedBy}'") . '</td>';
                $html .= '<td>' . html_entity_decode($note->Note) . '</td>';
                $html .= '</tr>';
            }
        }
        $html .= '</table>';
        return '<small>' . $html . '</small>';
    }

    public $id = NULL;
    public $firstnames = NULL;
    public $surname = NULL;
    public $dob = NULL;
    public $employer_id = NULL;
    public $employer_location_id = NULL;
    public $home_address_line_1 = NULL;
    public $home_address_line_2 = NULL;
    public $home_address_line_3 = NULL;
    public $home_address_line_4 = NULL;
    public $home_postcode = NULL;
    public $home_telephone = NULL;
    public $home_mobile = NULL;
    public $home_email = NULL;
    public $ni = NULL;
    public $created = NULL;
    public $modified = NULL;
    public $work_email = NULL;
    public $age_group = NULL;
    public $location_area = NULL;
    public $created_by = null;
    public $gender = null;
    public $employment_start_date = null;
    public $sunesis_username = null;
    public $inductee_type = null;
    public $sf_Id = null;
    public $next_of_kin = null;
    public $next_of_kin_tel = null;
    public $next_of_kin_email = null;
    public $emp_crm_contacts = null;
    public $emp_crm_contacts_notes = null;
    public $learner_id = null;
    public $learner_id_notes = null;
    public $employer_type = null;
    public $ldd = null;
    public $ldd_comments = null;
    public $paid_hours = null;
    public $salary = null;
    public $comp_issue = null;
    public $tdc = null;
    public $arm_chance_to_progress = null;
    public $ldd_set_date = null;
    public $sen_type = null;
    public $sen_date = null;
    public $preferred_name = null;
    public $general_comments = null;

    public $inductions = array();
    public $inductionProgramme = NULL;

}
?>