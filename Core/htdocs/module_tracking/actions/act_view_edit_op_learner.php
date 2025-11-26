<?php
class view_edit_op_learner implements IAction
{
    public function execute(PDO $link)
    {
        $tr_id = isset($_REQUEST['tr_id'])?$_REQUEST['tr_id']:'';
        $tracker_id = isset($_REQUEST['tracker_id'])?$_REQUEST['tracker_id']:'';

        if($tr_id == '')
        {
            throw new Exception('Missing querystring argument: tr_id');
        }
        else
        {
            $op_details = DAO::getObject($link, "SELECT * FROM tr_operations WHERE tr_id = '{$tr_id}'");
            if(!isset($op_details->tr_id))
            {
                $op_details = new stdClass();
                $records = DAO::getSingleColumn($link, "SHOW COLUMNS FROM tr_operations");
                foreach($records AS $key => $value)
                    $op_details->$value = null;
                $op_details->tr_id = $tr_id;
                // if the learner has come from Induction module and it is the first time the record is being opened, then copy the crm contacts from induction to operations
                $op_details->main_contact_id = DAO::getSingleValue($link, "SELECT inductees.emp_crm_contacts FROM inductees INNER JOIN tr ON inductees.sunesis_username = tr.username WHERE tr.id = '{$tr_id}'");
            }
        }
        $_SESSION['bc']->add($link, "do.php?_action=view_edit_op_learner&tr_id=".$tr_id."&tracker_id=".$tracker_id, "View Edit Learner Info");

        $tr = TrainingRecord::loadFromDatabase($link, $tr_id);
        $employer = Organisation::loadFromDatabase($link, $tr->employer_id);
        $employer_location = Location::loadFromDatabase($link, $tr->employer_location_id);
        $listGender = InductionHelper::getListGender();

	if(is_null($tr->employer_id) && !is_null($employer_location))
        {
            $tr->employer_id = $employer_location->organisations_id;
            $tr->save($link);
	    $employer = Organisation::loadFromDatabase($link, $tr->employer_id);
        }

        $inductee_sql = <<<SQL
SELECT
  inductees.id
FROM
  inductees INNER JOIN induction_programme ON inductees.`id` = induction_programme.`inductee_id`
  INNER JOIN tr ON inductees.sunesis_username = tr.username
  INNER JOIN courses_tr ON tr.id = courses_tr.`tr_id`
WHERE tr.id = '$tr->id' AND induction_programme.`programme_id` = courses_tr.`course_id`
;
SQL;
        //$inductee_id = DAO::getSingleValue($link, "SELECT inductees.id FROM inductees INNER JOIN tr ON inductees.sunesis_username = tr.username WHERE tr.id = '{$tr->id}'");
        $inductee_id = DAO::getSingleValue($link, $inductee_sql);
        $inductee = Inductee::loadFromDatabase($link, $inductee_id);
        $induction = '';
        if(isset($inductee->inductions) && count($inductee->inductions) > 0)
        {
            $induction = $inductee->inductions[0];
        }

        $learner_48_hour_call_max_date = new Date($tr->created);
        $learner_48_hour_call_max_date->addDays(2);
        $learner_3_week_max_date = new Date($tr->created);
        $learner_3_week_max_date->addDays(21);

        //check is it a restart learner
        $_contract = Contract::loadFromDatabase($link, $tr->contract_id);
        $c_sub = DAO::getSingleValue($link, "SELECT submission FROM central.`lookup_submission_dates` WHERE CURDATE() BETWEEN start_submission_date AND last_submission_date AND contract_year = '{$_contract->contract_year}';");
        $c_year = date('Y')-1;
        $bil_tr_id = "";
        $restart = DAO::getSingleValue($link, "SELECT
  extractvalue (
    ilr,
    '/Learner/LearningDelivery[LearnAimRef=\"ZPROG001\"]/LearningDeliveryFAM[LearnDelFAMType=\"RES\"]/LearnDelFAMCode'
  ) AS restart
FROM
  ilr
  INNER JOIN contracts
    ON ilr.`contract_id` = contracts.id
WHERE
  ilr.tr_id = '$tr->id'
  AND contract_id = '$tr->contract_id'
  AND submission = '$c_sub'
HAVING restart = '1'
;");
        if(in_array($tr->id, ["35768"]))
        {
            $restart = 1;
        }
	if($restart == 1)
        {
            $bil_tr_id = DAO::getSingleValue($link, "SELECT tr.id FROM tr WHERE tr.username = '{$tr->username}' AND tr.status_code IN ('3', '6') ORDER BY tr.id DESC LIMIT 1");
            $already_merged = DAO::getSingleValue($link, "SELECT COUNT(*) FROM op_bil_merged_records WHERE bil_id = '{$bil_tr_id}' AND con_id = '{$tr->id}'");
            if(in_array($tr->id, ['30679', '31005']))
                $already_merged = '1';
            $restart = $already_merged > 0 ? '' : $restart;
            $bil_tr_id = $already_merged > 0 ? '' : $bil_tr_id;
        }

        if($op_details->last_learning_evidence == '')
        {
            $last_learning_evidence = XML::loadSimpleXML('<Evidence><Type></Type><Date></Date><Note></Note><CreatedBy></CreatedBy><DateTime></DateTime></Evidence>');
        }
        else
        {
            $last_learning_evidence = XML::loadSimpleXML($op_details->last_learning_evidence);
            $last_learning_evidence = $last_learning_evidence->Evidence;
            $max = count($last_learning_evidence);
            $last_learning_evidence = $last_learning_evidence[$max-1];
        }

        if($op_details->lar_details == '')
        {
            $lar_details = XML::loadSimpleXML('<Note><Type></Type><Date></Date><Note></Note><RAG></RAG><NextActionDate></NextActionDate><CreatedBy></CreatedBy><DateTime></DateTime></Note>');
        }
        else
        {
            $lar_details = XML::loadSimpleXML($op_details->lar_details);
            $lar_details = $lar_details->Note;
            $max = count($lar_details);
            $lar_details = $lar_details[$max-1];
        }

        if($op_details->leaver_details == '')
        {
            $leaver_details = XML::loadSimpleXML('<Note><Type></Type><Date></Date><Note></Note><CreatedBy></CreatedBy><DateTime></DateTime></Note>');
        }
        else
        {
            $leaver_details = XML::loadSimpleXML($op_details->leaver_details);
            $leaver_details = $leaver_details->Note;
            $max = count($leaver_details);
            $leaver_details = $leaver_details[$max-1];
        }
        if($op_details->bil_details == '')
        {
            $bil_details = XML::loadSimpleXML('<Note><Type></Type><Date></Date><Note></Note><CreatedBy></CreatedBy><DateTime></DateTime></Note>');
        }
        else
        {
            $bil_details = XML::loadSimpleXML($op_details->bil_details);
            $bil_details = $bil_details->Note;
            $max = count($bil_details);
            $bil_details = $bil_details[$max-1];
        }
        if($op_details->peed_details == '')
        {
            $peed_details = XML::loadSimpleXML('<Note><Status></Status><Date></Date><Comments></Comments><Reason></Reason><Cause></Cause><Revisit></Revisit><Owner></Owner><ForecastDate></ForecastDate><Lsl></Lsl><LslStatus></LslStatus><CompletionDate></CompletionDate><CreatedBy></CreatedBy><DateTime></DateTime></Note>');
        }
        else
        {
            $peed_details = XML::loadSimpleXML($op_details->peed_details);
            $peed_details = $peed_details->Note;
            $max = count($peed_details);
            $peed_details = $peed_details[$max-1];
        }
	if($op_details->lras_comments == '')
        {
            $lras_comments = XML::loadSimpleXML('<Note><Comments></Comments><CreatedBy></CreatedBy><DateTime></DateTime></Note>');
        }
        else
        {
            $lras_comments = XML::loadSimpleXML($op_details->lras_comments);
            $lras_comments = $lras_comments->Note;
            $max = count($lras_comments);
            $lras_comments = $lras_comments  [$max-1];
        }
	if($op_details->lras_details == '')
        {
            $lras_details = XML::loadSimpleXML('<Note><Status></Status><Summary></Summary><Reason></Reason><Category></Category><CreatedBy></CreatedBy><DateTime></DateTime></Note>');
        }
        else
        {
            $lras_details = XML::loadSimpleXML($op_details->lras_details);
            $lras_details = $lras_details->Note;
            $max = count($lras_details);
            $lras_details = $lras_details[$max-1];
        }
	if($op_details->project_checkin == '')
        {
            $project_checkin = XML::loadSimpleXML('<Note><Date></Date><Comments></Comments><CreatedBy></CreatedBy><DateTime></DateTime></Note>');
        }
        else
        {
            $project_checkin = XML::loadSimpleXML($op_details->project_checkin);
            $project_checkin = $project_checkin->Note;
            $max = count($project_checkin);
            $project_checkin = $project_checkin[$max-1];
        }

        $op_epa_extra = DAO::getObject($link, "SELECT * FROM op_epa_extra WHERE tr_id = '{$tr_id}'");
        if(!isset($op_epa_extra->tr_id))
        {
            $op_epa_extra = new stdClass();
            $records = DAO::getSingleColumn($link, "SHOW COLUMNS FROM op_epa_extra");
            foreach($records AS $key => $value)
                $op_epa_extra->$value = null;
            $op_epa_extra->tr_id = $tr_id;
        }

        $complaints_counter = '';
        $_count = DAO::getSingleValue($link, "SELECT COUNT(*) FROM complaints WHERE complaints.record_id = '{$tr_id}'");
        if($_count > 0)
            $complaints_counter = '<span class="label label-danger">' . $_count . '</span>';

        $show_additional_support_message = "";
        if($tr->ad_lldd != '' && $op_details->additional_support == '' )
        {
            $show_additional_support_message = "There appears to be some disability information on this learner's training record. Do you need to add any additional support requirements?";
            $show_additional_support_message = '<p class="text-info"><i class="fa fa-info-circle"></i> ' . $show_additional_support_message . '</p>';
        }
	$induction_ldd_set_date = DAO::getSingleValue($link, "SELECT inductees.ldd_set_date FROM inductees WHERE inductees.linked_tr_id = '{$tr->id}'");
        $show_additional_support_message .= $induction_ldd_set_date != '' ? '<p class="text-info"><i class="fa fa-info-circle"></i> Additional support identified: ' . Date::toShort($induction_ldd_set_date) . '</p>' : '';

	$restart_withdrawn = DAO::getSingleValue($link, "SELECT
  extractvalue (
    ilr,
    '/Learner/LearningDelivery[LearnAimRef=\"ZPROG001\"]/LearningDeliveryFAM[LearnDelFAMType=\"RES\"]/LearnDelFAMCode'
  ) AS restart
FROM
  ilr
  INNER JOIN contracts
    ON ilr.`contract_id` = contracts.id
WHERE
  ilr.tr_id = '$tr->id'
  AND contract_id = '$tr->contract_id'
  AND submission = '$c_sub'
HAVING restart = '1'
;");
	
	//$tr->updateOperationsStatus($link);        

	$mock_interviews = $op_details->epa_mock_interview != '' ? XML::loadSimpleXML($op_details->epa_mock_interview) : null;
        $project_prep_session = $op_details->project_prep_session != '' ? XML::loadSimpleXML($op_details->project_prep_session) : null;

	$LLDDCat_list = [
            '1' => 'Emotional/behavioural difficulties',
            '2' => 'Multiple disabilities',
            '3' => 'Multiple learning difficulties',
            '4' => 'Vision impairment',
            '5' => 'Hearing impairment',
            '6' => 'Disability affecting mobility',
            '7' => 'Profound complex disabilities',
            '8' => 'Social and emotional difficulties',
            '9' => 'Mental health difficulty',
            '10' => 'Moderate learning difficulty',
            '11' => 'Severe learning difficulty',
            '12' => 'Dyslexia',
            '13' => 'Dyscalculia',
            '14' => 'Autism spectrum disorder',
            '15' => 'Aspergers syndrome',
            '16' => 'Temporary disability after illness (for example post-viral) or accident',
            '17' => 'Speech, Language and Communication Needs',
            '93' => 'Other physical disability',
            '94' => 'Other specific learning difficulty (e.g. Dyspraxia)',
            '95' => 'Other medical condition (for example epilepsy, asthma, diabetes)',
            '96' => 'Other learning difficulty',
            '97' => 'Other disability',
            '98' => 'Prefer not to say',
        ];

        $learnerIlr = DAO::getSingleValue(
            $link, 
            "SELECT ilr FROM ilr INNER JOIN contracts ON ilr.`contract_id` = contracts.id WHERE ilr.tr_id = '$tr->id' AND contract_id = '$tr->contract_id' AND submission = '$c_sub' AND EXTRACTVALUE(ilr, '/Learner/LLDDHealthProb') = '1';"
        );

        $llddIlrInfo = '';
        if($learnerIlr != '')
        {
            $llddIlrInfo = '<i class="fa fa-info-circle"></i> LLDD info recorded in ILR:<br>';
            $learnerIlr = XML::loadSimpleXML( $learnerIlr );
            $primaryLldd = '';
            foreach($learnerIlr->LLDDandHealthProblem AS $LLDDandHealthProblem)
            {
                $llddIlrInfo .= isset($LLDDCat_list[$LLDDandHealthProblem->LLDDCat->__toString()]) ? $LLDDCat_list[$LLDDandHealthProblem->LLDDCat->__toString()] . ' | ' : '';
                if(isset($LLDDandHealthProblem->PrimaryLLDD) && $LLDDandHealthProblem->PrimaryLLDD->__toString() == '1')
                {
                    $primaryLldd = '<br>Primary Category: ' . ( isset($LLDDCat_list[$LLDDandHealthProblem->LLDDCat->__toString()]) ? $LLDDCat_list[$LLDDandHealthProblem->LLDDCat->__toString()] : '' );
                }
            }
	    $llddIlrInfo .= $primaryLldd;
        }

        include_once('tpl_view_edit_op_learner.php');
    }

    public function renderComments(PDO $link, $tr_id, $note_type)
    {
        $html = '<table class="table">';
        $html .= '<tr><th>Creation DateTime</th><th>Created By</th><th>Detail</th></tr>';
        $notes = DAO::getSingleValue($link, "SELECT tr_operations.{$note_type} FROM tr_operations WHERE tr_id = '{$tr_id}'");
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

    public function getInducteeNotes(PDO $link, $inductee_id, $note_type)
    {
        if($inductee_id == '')
            throw new Exception('No id given');

        $html = '<table class="table callout">';
        $html .= '<tr><th>Creation DateTime</th><th>Created By</th><th>Detail</th></tr>';
        $notes = DAO::getSingleValue($link, "SELECT inductees.{$note_type} FROM inductees WHERE inductees.id = '{$inductee_id}'");
        if($notes == '')
        {
            $html .= '<tr><td colspan="3"><i>No existing record found.</i></td></tr>';
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

    public function renderAdditionalInformation(PDO $link, $notes_xml)
    {
        $html = '<table class="table">';
        $html .= '<tr><th>Creation DateTime</th><th>Created By</th><th>Type</th><th>Date</th><th>Detail</th></tr>';
        if($notes_xml == '')
        {
            $html .= '<tr><td colspan="5"><i>No record found.</i></td></tr>';
        }
        else
        {
            $notes = XML::loadSimpleXML($notes_xml);
            foreach($notes->Note AS $note)
            {
                $html .= '<tr>';
                $html .= '<td>' . Date::to($note->DateTime, Date::DATETIME) . '</td>';
                $html .= '<td>' . DAO::getSingleValue($link, "SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE users.id = '{$note->CreatedBy}'") . '</td>';
                $html .= '<td>' . DAO::getSingleValue($link, "SELECT description FROM lookup_op_add_details_types WHERE id = '" . html_entity_decode($note->Type) . "'") . '</td>';
                $html .= '<td>' . html_entity_decode($note->Date) . '</td>';
                $html .= '<td>' . html_entity_decode($note->Detail) . '</td>';
                $html .= '</tr>';
            }
        }
        $html .= '</table>';
        return '<small>' . $html . '</small>';
    }

    public function renderComposeNewMessageBox(PDO $link, TrainingRecord $tr, $tracker_id)
    {
        $email_templates = DAO::getResultset($link, "SELECT id, template_type, null FROM lookup_learner_email_templates");
        array_unshift($email_templates, array('','Email template:',''));
        $ddlTemplates =  HTML::selectChosen('frmEmailTemplate', $email_templates, '', false);
        $html = <<<HTML
<form name="frmEmail" id="frmEmail" action="do.php?_action=ajax_tracking" method="post">
	<input type="hidden" name="subaction" value="send_email_to_learner" />
	<input type="hidden" name="tr_id" value="$tr->id" />
	<input type="hidden" name="tracker_id" value="$tracker_id" />
	<input type="hidden" name="by_whom" value="{$_SESSION['user']->id}" />
	<div class="box box-primary">
		<div class="box-header with-border"><h2 class="box-title">Compose New Message</h2></div>
		<div class="box-body">
			<div class="form-group"><div class="row"> <div class="col-sm-8"> $ddlTemplates </div><div class="col-sm-4"> <span class="btn btn-sm btn-default" onclick="load_email_template();">Load template</span></div> </div></div>
			<div class="form-group">To: <input name="frmEmailTo" id="frmEmailTo" class="form-control compulsory" placeholder="To:" value="$tr->home_email"></div>
			<div class="form-group">From: <input name="frmEmailFrom" id="frmEmailFrom" class="form-control compulsory" placeholder="From:" value="{$_SESSION['user']->work_email}"></div>
			<div class="form-group">Subject: <input name="frmEmailSubject" id="frmEmailSubject" class="form-control compulsory" placeholder="Subject:"></div>
			<div class="form-group"><textarea name="compose-textarea" id="compose-textarea" class="form-control compulsory" style="height: 300px"></textarea></div>
		</div>
		<div class="box-footer">
			<div class="pull-right"><span class="btn btn-primary" onclick="sendEmail();"><i class="fa fa-envelope-o"></i> Send</span></div>
			<span class="btn btn-default" onclick="$('#btnCompose').show(); $('#mailBox').show(); $('#composeNewMessageBox').hide();"><i class="fa fa-times"></i> Discard</span>
		</div>
	</div>
</form>
HTML;

        return $html;
    }

    public function renderMailbox(PDO $link, TrainingRecord $tr)
    {
        $sql = <<<SQL
SELECT
	learners_emails.id,
	learners_emails.subject,
	learners_emails.learner_email,
	learners_emails.email_body,
	learners_emails.by_whom AS by_whom_id,
	(SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE users.id = learners_emails.by_whom) AS by_whom_name,
	learners_emails.created,
	learners_emails.email_to,
	learners_emails.email_from
FROM
	learners_emails
WHERE
	learners_emails.tr_id = '{$tr->id}'
ORDER BY
	learners_emails.created DESC
SQL;
        $records = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
        $email_count = count($records);
        $trs = '';
        if($email_count == 0)
        {
            $trs .= '<tr><td colspan="4"><i class="text-muted">No records found</i></td> </tr>';
        }
        foreach($records AS $row)
        {
            $trs .= '<tr onclick="showEmail(\''.$row['id'].'\');" style="cursor: pointer;">';
            $trs .= '<td class="mailbox-name">' . $row['by_whom_name'] . '<br><span class="small">'.$row['email_from'].'</span></td>';
            $trs .= '<td class="mailbox-name">' . $tr->firstnames . ' ' . $tr->surname . '<br><span class="small">'.$row['email_to'].'</span></td>';
            $trs .= '<td class="mailbox-subject"><b>' . $row['subject'] . '</b></td>';
            $trs .= '<td class="">' . substr(strip_tags($row['email_body']), 0, 150) . ' ...</td>';
            $trs .= '<td class="mailbox-date">' . Date::to($row['created'], Date::DATETIME) . '</td>';
            $trs .= '</tr>';
        }
        $html = <<<HTML
<div class="box box-primary">
	<div class="box-header with-border"><h2 class="box-title">Mailbox <small>{$email_count} emails sent</small> </h2></div>
	<div class="box-body no-padding">
		<div class="table-responsive mailbox-messages">
			<table class="table table-hover table-striped">
				<tbody>
				$trs
				</tbody>
			</table>
		</div>
	</div>
</div>
HTML;

        return $html;
    }

    private function renderComplaintsTable(PDO $link, TrainingRecord $tr)
    {
        $sql = new SQLStatement("SELECT * FROM complaints");
        $sql->setClause("WHERE complaints.complaint_type = '" . Complaint::LEARNER_COMPLAINT . "'");
        $sql->setClause("WHERE complaints.record_id = '{$tr->id}'");
        $sql->setClause("ORDER BY complaints.created DESC");
        $records = DAO::getResultset($link, $sql->__toString(), DAO::FETCH_ASSOC);
        $trs = '';
        if(count($records) == 0)
        {
            $trs .= '<tr><td colspan="5"><i class="text-muted">No records found</i></td> </tr>';
        }
        foreach($records AS $row)
        {
            $trs .= Complaint::userWithEditAccess($_SESSION['user']->username) ? '<tr onclick="window.location.href=\'do.php?_action=edit_complaint_learner&id='.$row['id'].'&record_id='.$row['record_id'].'\';" style="cursor:pointer;">' : '<tr>';
            $trs .= '<td><dl class="dl-vertical">';
            $trs .= '<dt>Reference: </dt><dd>'.$row['reference'] . '</dd>';
            $trs .= $row['outcome'] == 'C' ? '<dt>Outcome: </dt><dd><span class="label label-success"> &nbsp; Closed &nbsp; </span> </dd>' : '<dt>Outcome: </dt><dd><span class="label label-danger"> &nbsp; Open &nbsp; </label> </dd>';
            $trs .= '<dt>Summary: </dt><dd class="small">'.HTML::nl2p($row['complaint_summary']) . '</dd>';
            $trs .= '</dl></td>';
            $trs .= '<td><dl class="dl-vertical">';
            $trs .= '<dt>Date of complaint: </dt><dd>'.Date::toShort($row['date_of_complaint']) . '</dd>';
            $trs .= '<dt>Date of event: </dt><dd>'.Date::toShort($row['date_of_event']) . '</dd>';
            $trs .= '<dt>Created: </dt><dd>'.Date::to($row['created'], Date::DATETIME) . '</dd>';
            $trs .= '<dt>Created By: </dt><dd>'.DAO::getSingleValue($link, "SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE users.id = '{$row['created_by']}'") . '</dd>';
            $trs .= '</dl></td>';
            $trs .= '<td><dl class="dl-vertical">';
            $trs .= '<dt>Person: </dt><dd>'.$row['related_person'] . '</dd>';
            $trs .= '<dt>Department(s): </dt>';
            $trs .= '<dd>';
            $depts = InductionHelper::getListRelatedDepartments();
            foreach(explode(',', $row['related_department']) AS $d)
                $trs .= isset($depts[$d]) ? '<i class="fa fa-angle-right"></i> '.$depts[$d] . '<br>' : '<i class="fa fa-angle-right"></i> '.$d.'<br>';
            $trs .= '</dd>';
            $trs .= '</dl></td>';
            $trs .= '<td><dl class="dl-vertical">';
            $trs .= $row['investigation_needed'] == 'Y' ? '<dt>Investigation needed: </dt><dd><span class="label bg-red"> &nbsp; Yes &nbsp; </span> </dd>' : '<dt>Investigation needed: </dt><dd><span class="label bg-primary"> &nbsp; No &nbsp; </label> </dd>';
            $trs .= $row['investigation_form_sent'] == 'Y' ? '<dt>Investigation form sent: </dt><dd><span class="label bg-red"> &nbsp; Yes &nbsp; </span> </dd>' : '<dt>Investigation form sent: </dt><dd><span class="label bg-primary"> &nbsp; No &nbsp; </label> </dd>';
            $trs .= '<dt>Investigation form sent to: </dt>';
            $trs .= '<dd>';
            $sent_to = InductionHelper::getListOpInternalManagers($link);
            foreach(explode(',', $row['investigation_form_to']) AS $d)
                $trs .= isset($sent_to[$d]) ? '<i class="fa fa-angle-right"></i> '.$sent_to[$d] . '<br>' : '<i class="fa fa-angle-right"></i> '.$d.'<br>';
            $trs .= '</dd>';
            $trs .= '</dl></td>';
            $trs .= '<td><dl class="dl-vertical">';
            $trs .= '<dt>Date of response: </dt><dd>'.Date::toShort($row['date_of_response']) . '</dd>';
            $trs .= $row['corrective_action_taken'] == 'Y' ? '<dt>Corrective action taken: </dt><dd><span class="label label-success"> &nbsp; Yes &nbsp; </span> </dd>' : '<dt>Corrective action taken: </dt><dd><span class="label label-danger"> &nbsp; No &nbsp; </label> </dd>';
            $trs .= '<dt>Baltic values: </dt>';
            $trs .= '<dd>';
            $b_vals = InductionHelper::getListBalticValues();
            foreach(explode(',', $row['baltic_values']) AS $d)
                $trs .= isset($b_vals[$d]) ? $b_vals[$d] . ', ' : $d.', ';
            $trs .= '</dd>';
            $trs .= '<dt>Summary: </dt><dd class="small">'.HTML::nl2p($row['response_summary']) . '</dd>';
            $trs .= '</dl></td>';
            $trs .= '</tr>';
        }
        $html = <<<HTML

<div class="box-body no-padding">
	<div class="table-responsive">
		<table class="table table-hover table-bordered">
			<thead><tr><th>Detail</th><th style="width: 10%;">Dates</th><th style="width: 15%;">Related Person / Department</th><th style="width: 15%;">Investigation</th><th>Response</th></tr></thead>
			<tbody>$trs</tbody>
		</table>
	</div>
</div>

HTML;

        return $html;

    }
}