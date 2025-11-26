<?php

class OperationsSession extends Entity
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
	sessions
WHERE
	id='$key';
HEREDOC;
        $st = $link->query($query);

        $session = null;
        if($st)
        {
            $session = null;
            $row = $st->fetch();
            if($row)
            {
                $session = new OperationsSession();
                $session->populate($row);

                $records = DAO::getResultset($link, "SELECT session_entries.* FROM session_entries INNER JOIN tr ON session_entries.`entry_tr_id` = tr.`id` WHERE session_entries.entry_session_id = '{$session->id}'", DAO::FETCH_ASSOC);
                foreach($records AS $r)
                {
                    $session->entries[] = $r;
                }
                $records = DAO::getResultset($link, "SELECT session_cancellations.* FROM session_cancellations INNER JOIN tr ON session_cancellations.`tr_id` = tr.`id` WHERE session_cancellations.session_id = '{$session->id}'", DAO::FETCH_ASSOC);
                foreach($records AS $r)
                {
                    $session->cancellations[] = $r;
                }
            }
        }
        else
        {
            throw new Exception("Could not execute database query to find session record. " . '----' . $query . '----' . $link->errorCode());
        }

        return $session;
    }

    public function getPersonnelName(PDO $link)
    {
        return DAO::getSingleValue($link, "SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE id = '{$this->personnel}'");
    }

    public function getTrackerTitle(PDO $link)
    {
        return DAO::getSingleValue($link, "SELECT title FROM op_trackers WHERE id = '{$this->tracker_id}'");
    }

    public function getCreatedBy(PDO $link)
    {
        return DAO::getSingleValue($link, "SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE id = '{$this->created_by}'");
    }

    public function getFrameworkTitle(PDO $link)
    {
        return DAO::getSingleValue($link, "SELECT frameworks.title FROM frameworks WHERE id = '{$this->framework_id}'");
    }

    public function getQualificationTitle(PDO $link)
    {
        return DAO::getSingleValue($link, "SELECT framework_qualifications.internaltitle FROM framework_qualifications WHERE REPLACE(id, '/', '') = '{$this->qualification_id}'");
    }

    public function getEventTypeDescription()
    {
        $types = InductionHelper::getListEventTypes();
        return isset($types[$this->event_type])?$types[$this->event_type]:$this->event_type;
    }

    public function save(PDO $link)
    {
        $this->modified = "";
        $this->created = ($this->id == "") ? date('Y-m-d H:i:s') : $this->created;
        $this->created_by = ($this->id == "") ? $_SESSION['user']->id : $this->created_by;

        return DAO::saveObjectToTable($link, 'sessions', $this);
    }

    public function sendCompletionNotification(PDO $link)
    {
        $trainer_name = $this->getPersonnelName($link);
        $start_date = Date::toShort($this->start_date);
        $end_date = Date::toShort($this->end_date);


        $html = <<<HEREDOC

<h2>Notification of Completed Register</h2>

<p>The following course has been set as completed:</p>

<p><strong>Unit(s): </strong>{$this->unit_ref}</p>
<p><strong>Completed By / Trainer: </strong>{$trainer_name}</p>
<p><strong>Start Date & Time: </strong>{$start_date} {$this->start_time}</p>
<p><strong>End Date & Time: </strong>{$end_date} {$this->end_time}</p>

HEREDOC;

        $to = SOURCE_HOME ? "inaam.azmat@perspective-uk.com" : "Admin@balticapprenticeships.com";
        if(Emailer::html_mail($to, "no-reply@perspective-uk.com", "Functional Skills Notification", "", $html, [], array("Importance: high")))
        {
            $email_log = new stdClass();
            $email_log->entity_type = 'sessions';
            $email_log->entity_id = $this->id;
            $email_log->email_to = "Admin@balticapprenticeships.com";
            $email_log->email_from = "no-reply@perspective-uk.com";
            $email_log->email_subject = "Functional Skills Notification";
            $email_log->email_body = $html;
            $email_log->by_whom = $_SESSION['user']->id;
            $email_log->created = date('Y-m-d H:i:s');
            DAO::saveObjectToTable($link, "emails", $email_log);
        }
    }

    public function isSafeToDelete(PDO $link)
    {
        $entries = DAO::getSingleValue($link, "SELECT COUNT(*) FROM session_entries WHERE session_entries.`entry_session_id` = '{$this->id}'");
        return $entries > 0 ? false : true;
    }

    public function addEntry(PDO $link, $tr_id)
    {
        $o = new stdClass();
        $o->entry_session_id = $this->id;
        $o->entry_tr_id = $tr_id;
        $o->created = date('Y-m-d H:i:s');
        DAO::saveObjectToTable($link, 'session_entries', $o);

        $this->num_entries++;
        $this->save($link);

    }

    public function removeEntry(PDO $link, $tr_id)
    {
        DAO::execute($link, "DELETE FROM session_entries WHERE entry_session_id = '{$this->id}' AND entry_tr_id = '{$tr_id}'");

        $this->num_entries--;
        $this->save($link);
    }

    public function cancelEntry(PDO $link, $tr_id, $comments, $category = '', $type = '')
    {
        $entry = new stdClass();
	    $entry->id = null;
        $entry->session_id = $this->id;
        $entry->tr_id = $tr_id;
        $entry->cancellation_date = date('Y-m-d');
        $entry->comments = $comments;
        $entry->category = $category;
        $entry->cancellation_type = $type;
        $entry->cancelled_by = $_SESSION['user']->id;

        DAO::saveObjectToTable($link, 'session_cancellations', $entry);

        // change the event status on the tracker to "required"
        if($this->event_type == "EX")
        {
            $unit_ref = DAO::getSingleValue($link, "SELECT entry_exam_name FROM session_entries WHERE entry_session_id = '{$this->id}' AND entry_tr_id = '{$tr_id}'");
            if($unit_ref != '')
            {
                $op_tracker_unit_sch = new stdClass();
                $op_tracker_unit_sch->tr_id = $tr_id;
                $op_tracker_unit_sch->unit_ref = $unit_ref;
                $op_tracker_unit_sch->code = "R";
                $op_tracker_unit_sch->created_by = $_SESSION['user']->id;
                $op_tracker_unit_sch->cancel_id = $entry->id;
		    $op_tracker_unit_sch->comments = substr($comments, 0, 799);
                DAO::saveObjectToTable($link, 'op_tracker_unit_sch', $op_tracker_unit_sch);
            }
        }
        else
        {
            $session_units = explode(",", $this->unit_ref);
            $unit_refs = array_unique($session_units);
            foreach($unit_refs AS $u_ref)
            {
                $_chk = DAO::getSingleValue($link, "SELECT extractvalue(evidences, \"//unit[@op_title='".addslashes((string)$u_ref)."' and @track='true']/@title\") AS chk FROM student_qualifications WHERE student_qualifications.tr_id = '{$tr_id}' HAVING chk != '';");
                if($_chk != '')
                {
                    $op_tracker_unit_sch = new stdClass();
                    $op_tracker_unit_sch->tr_id = $tr_id;
                    $op_tracker_unit_sch->unit_ref = $u_ref;
                    $op_tracker_unit_sch->code = "R";
                    $op_tracker_unit_sch->created_by = $_SESSION['user']->id;
                    $op_tracker_unit_sch->cancel_id = $entry->id;
		            $op_tracker_unit_sch->comments = substr($comments, 0, 799);
                    DAO::saveObjectToTable($link, 'op_tracker_unit_sch', $op_tracker_unit_sch);
                }
            }
        }

	    $this->removeEntry($link, $tr_id);

    }

    public function getExamRegister(PDO $link)
    {
        $start_day = date('l', strtotime($this->start_date));

        $week_days_header_row = '<th>' . $start_day . '</th>';
        $date = $this->start_date;
        $i = 1;
        while(strtotime($date) < strtotime($this->end_date))
        {
            $date = date ("Y-m-d", strtotime("+1 day", strtotime($date)));
            $week_days_header_row .= '<th>' . date('l', strtotime($date)) . '</th>';
            $i++;
        }
        $learners_rows = '';
        $j = 0;
        $op_tracker_status = [
            ['P', 'Pass'],
            ['F', 'Fail'],
            ['D', 'Did not attend'],
	        ['RP', 'Result pending'],
        ];
        $learner_op_status_list = InductionHelper::getListLearnerStatus();
	if(count($this->entries) == 0 )
        {
            return 'No learners added to this register.';
        }
        foreach($this->entries AS $entry)
        {
            $tr_id = $entry['entry_tr_id'];
            $sql = <<<SQL
SELECT
  tr.id AS tr_id,
  tr.`gender`,
  tr.`firstnames`,
  tr.`surname`,
  (SELECT legal_name FROM organisations WHERE id = tr.`employer_id`) AS employer,
  (SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE users.id = tr.coordinator) AS coordinator,
  (SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE users.id = tr.assessor) AS assessor,
  tr_operations.`additional_support`,
  tr_operations.`crc_alert`,
  DATE_FORMAT(tr.`start_date`, '%d/%m/%Y') AS start_date,
  ((DATE_FORMAT(tr.start_date,'%Y') - DATE_FORMAT(tr.dob,'%Y')) - (DATE_FORMAT(tr.start_date,'00-%m-%d') < DATE_FORMAT(tr.dob,'00-%m-%d'))) AS age,
  induction_fields.learner_type,
  induction_fields.literacy_level,
  induction_fields.numeracy_level
FROM
  tr 
  LEFT JOIN tr_operations ON tr.id = tr_operations.`tr_id`
  LEFT JOIN courses_tr ON tr.`id` = courses_tr.`tr_id`
  LEFT JOIN (
  SELECT DISTINCT 
  inductees.`sunesis_username`,induction_programme.`programme_id`,
  CASE inductees.`inductee_type`
	WHEN '3AAA' THEN '3AAA Transfer'
	WHEN 'NA' THEN 'New Apprentice'
	WHEN 'P' THEN 'Progression'
	WHEN 'SSU' THEN 'New Apprentice Client Sourced'
	WHEN 'WFD' THEN 'WFD'
	WHEN 'DXC' THEN 'DXC Transfer'
	WHEN '' THEN ''
  END AS learner_type,
  CASE induction.`iag_literacy`
	WHEN 'E1' THEN 'Entry Level 1'
	WHEN 'E2' THEN 'Entry Level 2'
	WHEN 'E3' THEN 'Entry Level 3'
	WHEN 'L1' THEN 'Level 1'
	WHEN 'L2' THEN 'Level 2'
	WHEN 'L3' THEN 'Level 3'
	WHEN 'U1' THEN 'Unclassified'
	WHEN 'NA' THEN 'N/A'
	WHEN '' THEN ''
	ELSE iag_literacy
  END AS literacy_level,
    CASE induction.`iag_numeracy`
	WHEN 'E1' THEN 'Entry Level 1'
	WHEN 'E2' THEN 'Entry Level 2'
	WHEN 'E3' THEN 'Entry Level 3'
	WHEN 'L1' THEN 'Level 1'
	WHEN 'L2' THEN 'Level 2'
	WHEN 'L3' THEN 'Level 3'
	WHEN 'U1' THEN 'Unclassified'
	WHEN 'NA' THEN 'N/A'
	WHEN '' THEN ''
	ELSE iag_numeracy
  END AS numeracy_level
  
  FROM inductees INNER JOIN induction ON induction.`inductee_id` = inductees.id INNER JOIN induction_programme ON induction_programme.`inductee_id` = inductees.id
  ) AS induction_fields ON (tr.`username` = induction_fields.sunesis_username AND courses_tr.`course_id` = induction_fields.`programme_id`)
WHERE tr.id = '$tr_id' ;
SQL;
            $tr = DAO::getObject($link, $sql);
            if(!isset($tr->tr_id))
                continue;

            $learners_rows .= '<tr>';
            $learners_rows .= '<td>' . ++$j . '</td>';
            if($tr->gender == 'M')
                $learners_rows .= '<td><img  src="/images/boy-blonde-hair.gif" /></td>';
            elseif($tr->gender == 'F')
                $learners_rows .= '<td><img  src="/images/girl-black-hair.gif" /></td>';
            else
                $learners_rows .= '<td><img  src="/images/blue-person.gif" /></td>';
            $learners_rows .= '<td><a href="do.php?_action=read_training_record&id='.$tr->tr_id.'">' . $tr->firstnames . ' ' . $tr->surname . '</a></td>';
            $learners_rows .= '<td class="small">' . DAO::getSingleValue($link, "SELECT title FROM student_frameworks WHERE tr_id = '{$tr->tr_id}'") . '</td>';
            $learners_rows .= '<td>' . $tr->employer . '</td>';
            $learners_rows .= '<td>' . $tr->coordinator . '</td>';
	        $learners_rows .= '<td>' . $tr->assessor . '</td>';
            $learners_rows .= '<td>';
            $learners_rows .= 'Start&nbsp;Date:&nbsp;' . $tr->start_date . '<br>';
            $learners_rows .= 'Age:&nbsp;' . $tr->age . '<br>';
            $learners_rows .= 'Learner&nbsp;Type:&nbsp;' . $tr->learner_type . '<br>';
            $learners_rows .= 'Literacy&nbsp;Level:&nbsp;' . str_replace(' ', '&nbsp;', $tr->literacy_level) . '<br>';
            $learners_rows .= 'Numeracy&nbsp;Level:&nbsp;' . str_replace(' ', '&nbsp;', $tr->numeracy_level) . '<br>';
            $learners_rows .= 'Preferred&nbsp;Name:&nbsp;' . DAO::getSingleValue($link, "SELECT preferred_name FROM tr_operations WHERE tr_id = '{$tr->tr_id}'");
            $learners_rows .= '</td>';
            $learner_op_status = DAO::getSingleValue($link, "SELECT learner_status FROM tr_operations WHERE tr_id = '{$tr->tr_id}'");
            $learner_op_status = isset($learner_op_status_list[$learner_op_status]) ? $learner_op_status_list[$learner_op_status] : $learner_op_status;
            $learners_rows .= '<td>' . $learner_op_status . '</td>';
            $learners_rows .= '<td>' . $entry['entry_exam_name'] . '</td>';
            $entry_ab_checked = DAO::getSingleValue($link, "SELECT entry_ab_checked FROM session_entries WHERE entry_session_id = '{$this->id}' AND entry_tr_id = '{$tr->tr_id}'");
            $learners_rows .= '<td>' . HTML::select('entry_ab_checked' . $entry['entry_id'], InductionHelper::getDDLYesNo(), $entry_ab_checked, true) . '</td>';
            $learners_rows .= '<td class="small">' . $tr->additional_support . '</td>';
            $entry_c_id_check = DAO::getSingleValue($link, "SELECT entry_c_id_check FROM session_entries WHERE entry_session_id = '{$this->id}' AND entry_tr_id = '{$tr->tr_id}'");
            $learners_rows .= '<td>' . HTML::select('entry_c_id_check' . $entry['entry_id'], InductionHelper::getDDLYesNo(), $entry_c_id_check, true) . '</td>';
            $entry_t_id_check = DAO::getSingleValue($link, "SELECT entry_t_id_check FROM session_entries WHERE entry_session_id = '{$this->id}' AND entry_tr_id = '{$tr->tr_id}'");
            $learners_rows .= '<td>' . HTML::select('entry_t_id_check' . $entry['entry_id'], InductionHelper::getDDLYesNo(), $entry_t_id_check, true) . '</td>';
            $date = $this->start_date;
            $tds = '';

            $flip = false;
            while(strtotime($date) <= strtotime($this->end_date))
            {
                $flip = true;
                $name = new stdClass();
                $name->entry_id = $entry['entry_id'];
                $name->entry_tr_id = $tr->tr_id;
                $name->entry_date = $date;
                $name->entry_day = date('l', strtotime($date));

                $code = DAO::getSingleValue($link, "SELECT attendance_code FROM session_attendance WHERE session_entry_id = '{$name->entry_id}' AND attendance_date = '{$name->entry_date}' AND attendance_day = '{$name->entry_day}'");
                $select = "<select name='" . json_encode($name) . "'>";
                $select .= $code == 0 ? "<option selected='selected' value='0'></option>" : "<option value='0'></option>";
                $select .= $code == 1 ? "<option selected='selected' value='1'>Attended</option>" : "<option value='1'>Attended</option>";
                $select .= $code == 2 ? "<option selected='selected' value='2'>Late</option>" : "<option value='2'>Late</option>";
                $select .= $code == 3 ? "<option selected='selected' value='3'>Absent</option>" : "<option value='3'>Absent</option>";
                $select .= $code == 4 ? "<option selected='selected' value='4'>N/A</option>" : "<option value='4'>N/A</option>";
                $select .= "</select>";
                $tds .= '<td>' . $select . '</td>';
                $date = date ("Y-m-d", strtotime("+1 day", strtotime($date)));
            }
            if(!$flip)
            {
                $flip = true;
                $name = new stdClass();
                $name->entry_id = $entry['entry_id'];
                $name->entry_tr_id = $tr->tr_id;
                $name->entry_date = $date;
                $name->entry_day = date('l', strtotime($date));

                $code = DAO::getSingleValue($link, "SELECT attendance_code FROM session_attendance WHERE session_entry_id = '{$name->entry_id}' AND attendance_date = '{$name->entry_date}' AND attendance_day = '{$name->entry_day}'");
                $select = "<select name='" . json_encode($name) . "'>";
                $select .= $code == 0 ? "<option selected='selected' value='0'></option>" : "<option value='0'></option>";
                $select .= $code == 1 ? "<option selected='selected' value='1'>Attended</option>" : "<option value='1'>Attended</option>";
                $select .= $code == 2 ? "<option selected='selected' value='2'>Late</option>" : "<option value='2'>Late</option>";
                $select .= $code == 3 ? "<option selected='selected' value='3'>Absent</option>" : "<option value='3'>Absent</option>";
                $select .= $code == 4 ? "<option selected='selected' value='4'>N/A</option>" : "<option value='4'>N/A</option>";
                $select .= "</select>";
                $tds .= '<td>' . $select . '</td>';
                $date = date ("Y-m-d", strtotime("+1 day", strtotime($date)));
            }

            $learners_rows .= $tds;
            $_saved_op_tracker_status = DAO::getSingleValue($link, "SELECT entry_op_tracker_status FROM session_entries WHERE entry_session_id = '{$this->id}' AND entry_tr_id = '{$tr->tr_id}'");
            $learners_rows .= '<td>' . HTML::select('entry_op_tracker_status' . $entry['entry_id'], $op_tracker_status, $_saved_op_tracker_status, true) . '</td>';
	        $mocks = DAO::getObject($link, "SELECT entry_mock_1, entry_mock_2, entry_mock_3 FROM session_entries WHERE entry_session_id = '{$this->id}' AND entry_tr_id = '{$tr->tr_id}'");
            $learners_rows .= '<td>';
            $learners_rows .= '<span class="text-bold">Mock 1: </span>';
            $learners_rows .= isset($mocks->entry_mock_1) ? '<input type="text" name="entry_mock_1' . $entry['entry_id'] . '" value="' . $mocks->entry_mock_1 . '" size="5" maxlength="5" onkeypress="return numbersonlywithpoint(this);" />' : '<input type="text" name="entry_mock_1' . $entry['entry_id'] . '" size="5" maxlength="5" onkeypress="return numbersonlywithpoint(this);" />';
            $learners_rows .= '<br><span class="text-bold">Mock 2: </span>';
            $learners_rows .= isset($mocks->entry_mock_2) ? '<input type="text" name="entry_mock_2' . $entry['entry_id'] . '" value="' . $mocks->entry_mock_2 . '" size="5" maxlength="5" onkeypress="return numbersonlywithpoint(this);" />' : '<input type="text" name="entry_mock_2' . $entry['entry_id'] . '" size="5" maxlength="5" onkeypress="return numbersonlywithpoint(this);" />';
            $learners_rows .= '<br><span class="text-bold">Mock 3: </span>';
            $learners_rows .= isset($mocks->entry_mock_3) ? '<input type="text" name="entry_mock_3' . $entry['entry_id'] . '" value="' . $mocks->entry_mock_3 . '" size="5" maxlength="5" onkeypress="return numbersonlywithpoint(this);" />' : '<input type="text" name="entry_mock_3' . $entry['entry_id'] . '" size="5" maxlength="5" onkeypress="return numbersonlywithpoint(this);" />';
            $learners_rows .= '</td>';
	        $_saved_mock_pass_fail = DAO::getSingleValue($link, "SELECT entry_mock_pass_fail FROM session_entries WHERE entry_session_id = '{$this->id}' AND entry_tr_id = '{$tr->tr_id}'");
            $learners_rows .= '<td>' . HTML::select('entry_mock_pass_fail' . $entry['entry_id'], [['P', 'Pass'], ['F', 'Fail']], $_saved_mock_pass_fail, true) . '</td>';
	    //$_saved_vm_shutdown = DAO::getSingleValue($link, "SELECT entry_vm_shutdown FROM session_entries WHERE entry_session_id = '{$this->id}' AND entry_tr_id = '{$tr->tr_id}'");
            //$learners_rows .= '<td>' . HTML::select('entry_vm_shutdown' . $entry['entry_id'], [['Yes', 'Yes'], ['No', 'No']], $_saved_vm_shutdown, true) . '</td>';
	        $saved_learner_traner = DAO::getSingleValue($link, "SELECT entry_learner_trainer FROM session_entries WHERE entry_session_id = '{$this->id}' AND entry_tr_id = '{$tr->tr_id}'");
            $learners_rows .= '<td>' . HTML::select('entry_learner_trainer' . $entry['entry_id'], InductionHelper::getDDLOpTrainers($link), $saved_learner_traner, true) . '</td>';
            $learners_rows .= '<td><textarea cols="35" class="" name="entry_mock_result' . $entry['entry_id'] . '" id="' . $tr->tr_id . '" rows="4" >';
            $learners_rows .= DAO::getSingleValue($link, "SELECT entry_mock_result FROM session_entries WHERE entry_session_id = '{$this->id}' AND entry_tr_id = '{$tr->tr_id}'");
            $learners_rows .= '</textarea></td>';
            $learners_rows .= '</tr>';
        }

        $comments = $this->comments != '' ? '<h4 class="text-bold">Notes for CRC / Trainer:</h4> ' . HTML::nl2p($this->comments) : '<h4 class="text-bold">Notes for CRC / Trainer:</h4> <i class="text-muted">There are no notes added by coordinator</i>';
        if($_SESSION['user']->isAdmin() || array_key_exists($_SESSION['user']->id, InductionHelper::getListInductionCoordinators($link)) || $_SESSION['user']->op_access == 'W')
        {
            $buttons = '';
            if($this->status == 'NC')
                $buttons = '<span class="btn btn-primary btn-xs" onclick="add_notes();"><i class="fa fa-save"></i> Save Notes</span>';
            if($this->status == 'C' || $this->status == 'R')
                $buttons = ' &nbsp; <span class="btn btn-primary btn-xs" onclick="signoff_register();"><i class="fa fa-check"></i> Signoff</span> &nbsp; <span class="btn btn-danger btn-xs" onclick="reject_register();"><i class="fa fa-close"></i> Reject</span>';
            $comments = '<textarea class="form-control" rows="5" name="comments" placeholder="Any other notes for CRC / Trainer:">' . $this->comments . '</textarea>'.$buttons;
        }

        $html = <<<HTML
<div class="col-sm-12">

	<table class="table table-bordered text-center" id="tblSessionRegister">
		<thead class="small">
		<tr>
			<th>&nbsp;</th><th>&nbsp;</th><th>Learner Name</th><th>Framework</th><th>Company</th><th>Coordinator</th><th>Assessor</th>
			<th>Additional Info.</th><th>Learner Status</th><th>Exam Name</th><th class="small">Test checked<br>against AB website</th><th class="small">Additional<br>Support?</th><th class="small">Coord<br>ID Check</th><th class="small">Trainer<br>ID Check</th>
			$week_days_header_row
			<th>Status</th><th>Mock Results%</th><th>Mock Pass<br>or Fail</th><th>Trainer</th><th>Test<br>Results</th>
		</tr>
		</thead>
		<tbody>
		$learners_rows
		</tbody>
	</table>
</div>

<div class="col-sm-12" style="border: #001a35 solid 1px; padding: 5px;">
	$comments
</div>

<script type="text/javascript">
//<![CDATA[
	$(function(){
		$('input[class=radioLearnerOfWeek]').iCheck({radioClass: 'iradio_flat-green',hoverClass: 'hover'});
	});
//]]>
</script>

HTML;
        return $html;
    }

    public function getRegister(PDO $link)
    {
        if(!is_array($this->entries) || count($this->entries) == 0)
        {
            return '<p class="text-red"><i class="fa fa-warning"></i> No learner is added into this register.</p>';
        }
        $start_day = date('l', strtotime($this->start_date));

        $week_days_header_row = '<th>' . $start_day . '</th>';
        $date = $this->start_date;
        $i = 1;
        while(strtotime($date) < strtotime($this->end_date))
        {
            $date = date ("Y-m-d", strtotime("+1 day", strtotime($date)));
            $week_days_header_row .= '<th>' . date('l', strtotime($date)) . '</th>';
            $i++;
        }
        $learners_rows = '';
        $j = 0;
        $red_amber_yellow = InductionHelper::getListRedAmberYellow();
        $op_tracker_status = [
            ['U', 'Uploaded'],
            ['R', 'Did not attend'],
	        ['RP', 'Result pending'],
        ];
        $learner_op_status_list = InductionHelper::getListLearnerStatus();
        foreach($this->entries AS $entry)
        {
            $tr_id = $entry['entry_tr_id'];
            $sql = <<<SQL
SELECT
  tr.id AS tr_id,
  tr.`gender`,
  tr.`firstnames`,
  tr.`surname`,
  (SELECT legal_name FROM organisations WHERE id = tr.`employer_id`) AS employer,
  (SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE users.id = tr.coordinator) AS coordinator,
  (SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE users.id = tr.assessor) AS assessor,
  (SELECT IF(frameworks.`framework_type` = '25', 'S', 'F') FROM frameworks INNER JOIN student_frameworks ON frameworks.id = student_frameworks.`id` WHERE student_frameworks.tr_id = tr.id) AS std_fwk,
  tr_operations.`additional_support`,
  tr_operations.`crc_alert`,
  DATE_FORMAT(tr.`start_date`, '%d/%m/%Y') AS start_date,
  ((DATE_FORMAT(tr.start_date,'%Y') - DATE_FORMAT(tr.dob,'%Y')) - (DATE_FORMAT(tr.start_date,'00-%m-%d') < DATE_FORMAT(tr.dob,'00-%m-%d'))) AS age,
  induction_fields.learner_type,
  induction_fields.literacy_level,
  induction_fields.numeracy_level
FROM
  tr
  LEFT JOIN tr_operations ON tr.id = tr_operations.`tr_id`
  LEFT JOIN courses_tr ON tr.`id` = courses_tr.`tr_id`
  LEFT JOIN (
  SELECT DISTINCT 
  inductees.`sunesis_username`,induction_programme.`programme_id`,
  CASE inductees.`inductee_type`
	WHEN '3AAA' THEN '3AAA Transfer'
	WHEN 'NA' THEN 'New Apprentice'
	WHEN 'P' THEN 'Progression'
	WHEN 'SSU' THEN 'New Apprentice Client Sourced'
	WHEN 'WFD' THEN 'WFD'
	WHEN 'DXC' THEN 'DXC Transfer'
	WHEN '' THEN ''
  END AS learner_type,
  CASE induction.`iag_literacy`
	WHEN 'E1' THEN 'Entry Level 1'
	WHEN 'E2' THEN 'Entry Level 2'
	WHEN 'E3' THEN 'Entry Level 3'
	WHEN 'L1' THEN 'Level 1'
	WHEN 'L2' THEN 'Level 2'
	WHEN 'L3' THEN 'Level 3'
	WHEN 'U1' THEN 'Unclassified'
	WHEN 'NA' THEN 'N/A'
	WHEN '' THEN ''
	ELSE iag_literacy
  END AS literacy_level,
    CASE induction.`iag_numeracy`
	WHEN 'E1' THEN 'Entry Level 1'
	WHEN 'E2' THEN 'Entry Level 2'
	WHEN 'E3' THEN 'Entry Level 3'
	WHEN 'L1' THEN 'Level 1'
	WHEN 'L2' THEN 'Level 2'
	WHEN 'L3' THEN 'Level 3'
	WHEN 'U1' THEN 'Unclassified'
	WHEN 'NA' THEN 'N/A'
	WHEN '' THEN ''
	ELSE iag_numeracy
  END AS numeracy_level
  
  FROM inductees INNER JOIN induction ON induction.`inductee_id` = inductees.id INNER JOIN induction_programme ON induction_programme.`inductee_id` = inductees.id
  ) AS induction_fields ON (tr.`username` = induction_fields.sunesis_username AND courses_tr.`course_id` = induction_fields.`programme_id`)
WHERE tr.id = '$tr_id' ;
SQL;
            $tr = DAO::getObject($link, $sql);
            if(!isset($tr->tr_id))
                continue;

            $learners_rows .= '<tr>';
            $learners_rows .= '<td>' . ++$j . '</td>';
            if($tr->gender == 'M')
                $learners_rows .= '<td><img  src="/images/boy-blonde-hair.gif" /></td>';
            elseif($tr->gender == 'F')
                $learners_rows .= '<td><img  src="/images/girl-black-hair.gif" /></td>';
            else
                $learners_rows .= '<td><img  src="/images/blue-person.gif" /></td>';
            $learners_rows .= '<td><a href="do.php?_action=read_training_record&id='.$tr->tr_id.'">' . $tr->firstnames . ' ' . $tr->surname . '</a></td>';
            $learners_rows .= '<td class="small">' . DAO::getSingleValue($link, "SELECT title FROM student_frameworks WHERE tr_id = '{$tr->tr_id}'") . '</td>';
            $learners_rows .= '<td>' . $tr->employer . '</td>';
            $learners_rows .= '<td>' . $tr->coordinator . '</td>';
	        $learners_rows .= '<td>' . $tr->assessor . '</td>';
            $learners_rows .= '<td class="text-left">';
            $learners_rows .= 'Start&nbsp;Date:&nbsp;' . $tr->start_date . '<br>';
            $learners_rows .= 'Age:&nbsp;' . $tr->age . '<br>';
            $learners_rows .= 'Learner&nbsp;Type:&nbsp;' . $tr->learner_type . '<br>';
            $learners_rows .= 'Literacy&nbsp;Level:&nbsp;' . str_replace(' ', '&nbsp;', $tr->literacy_level) . '<br>';
            $learners_rows .= 'Numeracy&nbsp;Level:&nbsp;' . str_replace(' ', '&nbsp;', $tr->numeracy_level) . '<br>';
            $learners_rows .= 'Preferred&nbsp;Name:&nbsp;' . DAO::getSingleValue($link, "SELECT preferred_name FROM tr_operations WHERE tr_id = '{$tr->tr_id}'");
            $learners_rows .= '</td>';
            $learner_op_status = DAO::getSingleValue($link, "SELECT learner_status FROM tr_operations WHERE tr_id = '{$tr->tr_id}'");
            $learner_op_status = isset($learner_op_status_list[$learner_op_status]) ? $learner_op_status_list[$learner_op_status] : $learner_op_status;
            $learners_rows .= '<td>' . $learner_op_status . '</td>';
            $entry_skilsure_check = DAO::getSingleValue($link, "SELECT entry_skilsure_check FROM session_entries WHERE entry_session_id = '{$this->id}' AND entry_tr_id = '{$tr->tr_id}'");
            $learners_rows .= '<td>' . HTML::select('entry_skilsure_check' . $entry['entry_id'], InductionHelper::getDDLYesNo(), $entry_skilsure_check, true) . '</td>';
            $learners_rows .= '<td>' . $tr->additional_support . '</td>';
            $learners_rows .= '<td>' . $tr->std_fwk . '</td>';
            $date = $this->start_date;
            $tds = '';
            while(strtotime($date) <= strtotime($this->end_date))
            {
                $name = new stdClass();
                $name->entry_id = $entry['entry_id'];
                $name->entry_tr_id = $tr->tr_id;
                $name->entry_date = $date;
                $name->entry_day = date('l', strtotime($date));

                $code = DAO::getSingleValue($link, "SELECT attendance_code FROM session_attendance WHERE session_entry_id = '{$name->entry_id}' AND attendance_date = '{$name->entry_date}' AND attendance_day = '{$name->entry_day}'");
                $select = "<select name='" . json_encode($name) . "'>";
                $select .= $code == 0 ? "<option selected='selected' value='0'></option>" : "<option value='0'></option>";
                $select .= $code == 1 ? "<option selected='selected' value='1'>Attended</option>" : "<option value='1'>Attended</option>";
                $select .= $code == 2 ? "<option selected='selected' value='2'>Late</option>" : "<option value='2'>Late</option>";
                $select .= $code == 3 ? "<option selected='selected' value='3'>Absent</option>" : "<option value='3'>Absent</option>";
                $select .= $code == 4 ? "<option selected='selected' value='4'>N/A</option>" : "<option value='4'>N/A</option>";
                $select .= "</select>";
                $tds .= '<td>' . $select . '</td>';
                $date = date ("Y-m-d", strtotime("+1 day", strtotime($date)));
            }
            $learners_rows .= $tds;
            $_saved_op_tracker_status = DAO::getSingleValue($link, "SELECT entry_op_tracker_status FROM session_entries WHERE entry_session_id = '{$this->id}' AND entry_tr_id = '{$tr->tr_id}'");
            $learners_rows .= '<td>' . HTML::select('entry_op_tracker_status' . $entry['entry_id'], $op_tracker_status, $_saved_op_tracker_status, true);
            //$learners_rows .= isset($red_amber_yellow[$tr->crc_alert])?'<td>' . $red_amber_yellow[$tr->crc_alert] . '</td>':'<td>' . $tr->crc_alert . '</td>';
            if($tr->crc_alert == 'R')
                $learners_rows .= '<td title="Red"><div class="bg-red" style="border-radius: 50%; height: 50px;">&nbsp;</div> </td>';
            elseif($tr->crc_alert == 'O')
                $learners_rows .= '<td title="Orange"><div class="bg-orange" style="border-radius: 50%; height: 50px;">&nbsp;</div> </td>';
            elseif($tr->crc_alert == 'Y')
                $learners_rows .= '<td title="Yellow"><div style="background-color: #ffff00; border-radius: 50%; height: 50px;">&nbsp;</div> </td>';
            else
                $learners_rows .= '<td>' . $tr->crc_alert . '</td>';
            $mocks = DAO::getObject($link, "SELECT entry_mock_1, entry_mock_2, entry_mock_3 FROM session_entries WHERE entry_session_id = '{$this->id}' AND entry_tr_id = '{$tr->tr_id}'");
            $learners_rows .= '<td>';
            $learners_rows .= '<span class="text-bold">Mock 1: </span>';
            $learners_rows .= isset($mocks->entry_mock_1) ? '<input type="text" name="entry_mock_1' . $entry['entry_id'] . '" value="' . $mocks->entry_mock_1 . '" size="5" maxlength="5" onkeypress="return numbersonlywithpoint(this);" />' : '<input type="text" name="entry_mock_1' . $entry['entry_id'] . '" size="5" maxlength="5" onkeypress="return numbersonlywithpoint(this);" />';
            $learners_rows .= '<br><span class="text-bold">Mock 2: </span>';
            $learners_rows .= isset($mocks->entry_mock_2) ? '<input type="text" name="entry_mock_2' . $entry['entry_id'] . '" value="' . $mocks->entry_mock_2 . '" size="5" maxlength="5" onkeypress="return numbersonlywithpoint(this);" />' : '<input type="text" name="entry_mock_2' . $entry['entry_id'] . '" size="5" maxlength="5" onkeypress="return numbersonlywithpoint(this);" />';
            $learners_rows .= '<br><span class="text-bold">Mock 3: </span>';
            $learners_rows .= isset($mocks->entry_mock_3) ? '<input type="text" name="entry_mock_3' . $entry['entry_id'] . '" value="' . $mocks->entry_mock_3 . '" size="5" maxlength="5" onkeypress="return numbersonlywithpoint(this);" />' : '<input type="text" name="entry_mock_3' . $entry['entry_id'] . '" size="5" maxlength="5" onkeypress="return numbersonlywithpoint(this);" />';
            $learners_rows .= '</td>';
	        $learners_rows .= '<td>';
            $_saved_entry_mock_result = DAO::getSingleValue($link, "SELECT entry_mock_result FROM session_entries WHERE entry_session_id = '{$this->id}' AND entry_tr_id = '{$tr->tr_id}'");
            $learners_rows .= '<span>' . $_saved_entry_mock_result . '</span>';
            $learners_rows .= '<input type="hidden" name="entry_mock_result' . $entry['entry_id'] . '" id="' . $tr->tr_id . '" value="'.$_saved_entry_mock_result.'"/>';
            $learners_rows .= '</td>';
            //$learners_rows .= '<td><textarea cols="35" class="" name="entry_mock_result' . $entry['entry_id'] . '" id="' . $tr->tr_id . '" rows="4" >';
            //$learners_rows .= DAO::getSingleValue($link, "SELECT entry_mock_result FROM session_entries WHERE entry_session_id = '{$this->id}' AND entry_tr_id = '{$tr->tr_id}'");
            //$learners_rows .= '</textarea></td>';
	        $_saved_mock_pass_fail = DAO::getSingleValue($link, "SELECT entry_mock_pass_fail FROM session_entries WHERE entry_session_id = '{$this->id}' AND entry_tr_id = '{$tr->tr_id}'");
            $learners_rows .= '<td>' . HTML::select('entry_mock_pass_fail' . $entry['entry_id'], [['P', 'Pass'], ['F', 'Fail']], $_saved_mock_pass_fail, true) . '</td>';
	    //$_saved_vm_shutdown = DAO::getSingleValue($link, "SELECT entry_vm_shutdown FROM session_entries WHERE entry_session_id = '{$this->id}' AND entry_tr_id = '{$tr->tr_id}'");
            //$learners_rows .= '<td>' . HTML::select('entry_vm_shutdown' . $entry['entry_id'], [['Yes', 'Yes'], ['No', 'No']], $_saved_vm_shutdown, true) . '</td>';
	        $saved_learner_traner = DAO::getSingleValue($link, "SELECT entry_learner_trainer FROM session_entries WHERE entry_session_id = '{$this->id}' AND entry_tr_id = '{$tr->tr_id}'");
            $learners_rows .= '<td>' . HTML::select('entry_learner_trainer' . $entry['entry_id'], InductionHelper::getDDLOpTrainers($link), $saved_learner_traner, true) . '</td>';
            $learners_rows .= '<td><textarea cols="35" class="" name="entry_comments' . $entry['entry_id'] . '" id="' . $tr->tr_id . '" rows="4" >';
            $learners_rows .= DAO::getSingleValue($link, "SELECT entry_comments FROM session_entries WHERE entry_session_id = '{$this->id}' AND entry_tr_id = '{$tr->tr_id}'");
            $learners_rows .= '</textarea></td>';
            if($tr->tr_id == $this->learner_of_week)
                $learners_rows .= '<td><input checked="checked" class="radioLearnerOfWeek" type="radio" name="learner_of_week" value="' . $tr->tr_id . '" ></td>';
            else
                $learners_rows .= '<td><input class="radioLearnerOfWeek" type="radio" name="learner_of_week" value="' . $tr->tr_id . '" ></td>';
            $learners_rows .= '</tr>';
        }

        $comments = $this->comments != '' ? '<h4 class="text-bold">Notes for CRC / Trainer:</h4> ' . HTML::nl2p($this->comments) : '<h4 class="text-bold">Notes for CRC / Trainer:</h4> <i class="text-muted">There are no notes added by coordinator</i>';
        if($_SESSION['user']->isAdmin() || array_key_exists($_SESSION['user']->id, InductionHelper::getListInductionCoordinators($link)) || $_SESSION['user']->op_access == 'W')
        {
            $buttons = '';
            if($this->status == 'NC')
                $buttons = '<span class="btn btn-primary btn-xs" onclick="add_notes();"><i class="fa fa-save"></i> Save Notes</span>';
            if($this->status == 'C' || $this->status == 'R')
                $buttons = ' &nbsp; <span class="btn btn-primary btn-xs" onclick="signoff_register();"><i class="fa fa-check"></i> Signoff</span> &nbsp; <span class="btn btn-danger btn-xs" onclick="reject_register();"><i class="fa fa-close"></i> Reject</span>';
            $comments = '<textarea class="form-control" rows="5" name="comments" placeholder="Any other notes for CRC / Trainer:">' . $this->comments . '</textarea>'.$buttons;
        }

        $html = <<<HTML
<div class="col-sm-12">

	<table class="table table-bordered text-center" id="tblSessionRegister">
		<thead class="small">
		<tr>
			<th>&nbsp;</th><th>&nbsp;</th><th>Learner Name</th><th>Framework</th><th>Company</th><th>Coordinator</th><th>Assessor</th>
			<th>Additional Info.</th><th>Learner Status</th><th>Smart Assessor checked</th><th>Additional<br>Support?</th><th>S/F</th>
			$week_days_header_row
			<th>Status</th><th>FYI CRC</th><th>Mock<br>Results%</th><th>Mock<br>Results</th><th>Mock Pass<br>or Fail</th><th>Trainer</th><th>Learner Comments</th><th class="small">Learner<br>of week</th>
		</tr>
		</thead>
		<tbody>
		$learners_rows
		</tbody>
	</table>
</div>

<div class="col-sm-12" style="border: #001a35 solid 1px; padding: 5px;">
	$comments
</div>

<script type="text/javascript">
//<![CDATA[
	$(function(){
		$('input[class=radioLearnerOfWeek]').iCheck({radioClass: 'iradio_flat-green',hoverClass: 'hover'});
	});
//]]>
</script>

HTML;
        return $html;
    }

    public function isExam()
    {
        return $this->event_type == 'EX' ? true : false;
    }

    public $id  = NULL;
    public $title = NULL;
    public $personnel = NULL;
    public $event_type = NULL;
    public $start_date = NULL;
    public $end_date = NULL;
    public $start_time = NULL;
    public $end_time = NULL;
    public $max_learners = NULL;
    public $framework_id = NULL;
    public $qualification_id = NULL;
    public $unit_ref = NULL;
    public $reference = NULL;
    public $created = NULL;
    public $modified = NULL;
    public $created_by = NULL;
    public $num_entries = NULL;
    public $attendances = NULL;
    public $lates = NULL;
    public $very_lates = NULL;
    public $absences = NULL;
    public $tracker_id = NULL;
    public $comments = NULL;
    public $learner_of_week = NULL;
    public $location = NULL;
    public $test_location = NULL;
    public $status = NULL;
    public $best_case = NULL;

    public $entries = NULL;
    public $cancellations = NULL;
    public $vm_shutdown = NULL;	

}
