<?php

class Induction extends Entity
{
    public static function loadFromDatabase(PDO $link, $id = '')
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
	induction
WHERE
	id='$key';
HEREDOC;
        $st = $link->query($query);

        $induction = null;
        if($st)
        {
            $induction = null;
            $row = $st->fetch();
            if($row)
            {
                $induction = new Induction();
                $induction->populate($row);
            }

        }
        else
        {
            throw new Exception("Could not execute database query to find induction record. " . '----' . $query . '----' . $link->errorCode());
        }

        return $induction;
    }

    public function save(PDO $link)
    {
        $this->modified = "";
        $this->created = ($this->id == "") ? date('Y-m-d H:i:s') : $this->created;
        $this->created_by = ($this->id == "") ? $_SESSION['user']->id : $this->created_by;

        return DAO::saveObjectToTable($link, 'induction', $this);
    }

    public function delete(PDO $link)
    {

    }


    public function isSafeToDelete(PDO $link)
    {
        return false;
    }

    public function renderComments(PDO $link, $note_type)
    {
        if($note_type == 'induction_notes' || $note_type == 'coordinator_notes')
        {
            $html = '<div class="tab-pane" id="timeline">';
            $html .= '<ul class="timeline timeline-inverse">';
            $notes = DAO::getSingleValue($link, "SELECT induction.{$note_type} FROM induction WHERE induction.id = '{$this->id}'");
            if($notes == '')
            {
                $html .= '<div class="well">No record found.</div>';
            }
            else
            {
                $notes = XML::loadSimpleXML($notes);
                foreach($notes->Note AS $note)
                {

                    $html .= '<li class="time-label"><span class="bg-green">' . Date::toShort($note->DateTime) . '</span></li>';
                    $html .= '<li> <i class="fa fa-comment bg-aqua"></i>';
                    $html .= '<div class="timeline-item">';
                    $html .= '<span class="time"><i class="fa fa-clock-o"></i> ' . Date::to($note->DateTime, Date::HM) . '</span>';
                    $html .= '<strong class="timeline-header">' . DAO::getSingleValue($link, "SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE users.id = '{$note->CreatedBy}'") . '</strong>';
                    $html .= '<div class="timeline-body">';
                    $html .= html_entity_decode($note->Note);
                    $html .= '</div>';
                    $html .= '</div>';
                    $html .= '</li>';

                }
            }
            $html .= '</ul>';
            $html .= '</div>';
            return $html;
        }
        $caption = $note_type == 'grey_section_comments'?'Grey Section Comments:':'';
        $html = '<table class="table"><caption><strong>' . $caption . '</strong></caption>';
        $html .= '<tr><th>Creation DateTime</th><th>Created By</th><th>Detail</th></tr>';
        $notes = DAO::getSingleValue($link, "SELECT induction.{$note_type} FROM induction WHERE induction.id = '{$this->id}'");
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

    public static function updateInduction(PDO $link, $tr_id)
    {
		$inductee_id = DAO::getSingleValue($link, "SELECT inductees.id FROM inductees WHERE inductees.linked_tr_id = '{$tr_id}'");
        if($inductee_id == '')
        {
            $sql = <<<SQL
SELECT 
    induction_fields.inductee_id 
FROM 
    tr 
    INNER JOIN courses_tr ON tr.id = courses_tr.tr_id
    INNER JOIN (
        SELECT DISTINCT sunesis_username, induction_programme.`programme_id`, inductees.id AS inductee_id
        FROM inductees INNER JOIN induction ON induction.`inductee_id` = inductees.id INNER JOIN induction_programme ON induction_programme.`inductee_id` = inductees.id
    ) AS induction_fields ON (tr.`username` = induction_fields.sunesis_username AND courses_tr.`course_id` = induction_fields.`programme_id`) 
WHERE
    tr.id = '$tr_id'    
SQL;

            $inductee_id = DAO::getSingleValue($link, $sql);            
        }

        if($inductee_id != '')
        {
            DAO::execute($link, "UPDATE induction SET induction.induction_status = 'L' WHERE induction.inductee_id = '{$inductee_id}'");
        }


	}


    public function buildAuditLogString(PDO $link, Entity $new_vo, array $exclude_fields = array())
    {
        if(count($exclude_fields) == 0)
        {
            // These fields use lookup codes
            $exclude_fields = array('induction_status', 'sla_received', 'assigned_assessor');
        }

        $changes_list = parent::buildAuditLogString($link, $new_vo, $exclude_fields);

        // Test each of the exceptions separately
        if($this->induction_status != $new_vo->induction_status)
        {
            $lookup = InductionHelper::getListInductionStatus();
            $from = isset($lookup[$this->induction_status]) ? $lookup[$this->induction_status] : $this->induction_status;
            $to = isset($lookup[$new_vo->induction_status]) ? $lookup[$new_vo->induction_status] : $new_vo->induction_status;
            $changes_list .= "[Induction Status] changed from '$from' to '$to'\n";
        }
        if($this->sla_received != $new_vo->sla_received)
        {
            $lookup = InductionHelper::getListSLAReceived();
            $from = isset($lookup[$this->sla_received]) ? $lookup[$this->sla_received] : $this->sla_received;
            $to = isset($lookup[$new_vo->sla_received]) ? $lookup[$new_vo->sla_received] : $new_vo->sla_received;
            $changes_list .= "[SLA Received] changed from '$from' to '$to'\n";
        }
        if($this->assigned_assessor != $new_vo->assigned_assessor)
        {
            $lookup = InductionHelper::getListInductionAssessors($link, 'assigned');
            $from = isset($lookup[$this->assigned_assessor]) ? $lookup[$this->assigned_assessor] : $this->assigned_assessor;
            $to = isset($lookup[$new_vo->assigned_assessor]) ? $lookup[$new_vo->assigned_assessor] : $new_vo->assigned_assessor;
            $changes_list .= "[Assigned Assessor] changed from '$from' to '$to'\n";
        }

        return $changes_list;
    }

    public $id  = NULL;
    public $inductee_id  = NULL;
    public $induction_date  = NULL;
    public $cohort_date  = NULL;
    public $induction_status  = NULL;
    public $miap  = NULL;
    public $headset_issued  = NULL;
    public $moredle_account  = NULL;
    public $brm  = NULL;
    public $lead_gen  = NULL;
    public $resourcer  = NULL;
    public $sunesis_account  = NULL;
    public $join_date  = NULL;
    public $join_time  = NULL;
    public $induction_notes  = NULL;
    public $coordinator_notes  = NULL;
    public $created = NULL;
    public $modified = NULL;
    public $created_by = NULL;
    public $induction_assessor = NULL;
    public $assigned_assessor = NULL;
    public $sla_received = NULL;
    public $wfd_assessment = NULL;
    public $dip_ws_delivery = NULL;
    public $commit_statement = NULL;
    public $reinstated = NULL;
    public $commit_signed = NULL;
    public $planned_end_date = NULL;
    public $iag_numeracy = NULL;
    public $iag_literacy = NULL;
    public $iag_ict = NULL;
    public $grey_section_comments = NULL;
    public $date_moved_from_grey_section = NULL;
    public $levy_payer = NULL;
    public $comp_issue = NULL;
    public $comp_issue_notes = NULL;
    public $induction_owner = NULL;
    public $assigned_coord = null;
    public $arm = null;
    public $enrolment_form = null;
    public $diagnostics_completed = null;
    public $webcam = null;
    public $induction_arranged = null;
    public $das_account = null;
    public $fs_exempt = null;
    public $levy_app_completed = null;
    public $wpa = null;
    public $red_le = null;
    public $withdrawn_reason = null;
    public $das_comments = null;
    public $math_cert = null;
    public $eng_cert = null;
    public $induction_moved = null;
    public $induction_moved_date = null;
    public $induction_moved_reason = null;
    public $levy_comments = null;
    public $das_account_contact = null;
    public $das_account_telephone = null;
    public $das_account_email = null;
    public $emp_recruiter = null;
    public $contact_comments = null;
    public $holding_reason = null;
    public $date_added_to_hi = null;
    public $date_removed_from_hi = null;
    public $maths_gcse_elig_met = null;
    public $passed_to_admin = null;
    public $projected_induction_date = null;
    public $app_opp_concern = null;
    public $eng_gcse_grade = null;
    public $maths_gcse_grade = null;
    public $sci_gcse_grade = null;
    public $it_gcse_grade = null;
    public $das_account_created = null;
    public $placement_id = null;
    public $learner_concerns = null;

    protected $audit_fields = array(

    );
}
