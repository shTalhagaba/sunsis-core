<?php
class save_op_learner implements IAction
{
	public function execute(PDO $link)
	{

		$tr_id = isset($_REQUEST['id'])?$_REQUEST['id']:'';
		$tracker_id = isset($_REQUEST['tracker_id'])?$_REQUEST['tracker_id']:'';
		if($tr_id == '')
			throw new Exception('Missing querystring argument: tr_id');

		if(isset($_REQUEST['formName']) && $_REQUEST['formName'] == 'frmOpLearnerFiles') // this is just uploading the files for the op learner
		{
			$username = isset($_REQUEST['username'])?$_REQUEST['username']:'';
			$target_directory = "/{$username}/operations";
			$valid_extensions = array('pdf', 'doc', 'docx', 'xls', 'xlsx', 'csv', 'txt', 'xml', 'zip', 'rar', '7z');
			$r = Repository::processFileUploads('uploaded_op_learner_file', $target_directory, $valid_extensions);
			if(!isset($r[0]))
				throw new Exception('Error uploading progression evidence, please try again.');

			$vo = new stdClass();
			$vo->tr_id = $tr_id;
			$vo->file_name = basename($r[0]);
			$vo->file_type = isset($_REQUEST['uploaded_op_learner_file_type'])?$_REQUEST['uploaded_op_learner_file_type']:'';
			$vo->uploaded_by = $_SESSION['user']->id;
			$vo->uploaded_date = date('Y-m-d H:i:s');
			DAO::saveObjectToTable($link, 'tr_files', $vo);
			http_redirect("do.php?_action=view_edit_op_learner&tr_id=" . $tr_id . "&tracker_id=" . $tracker_id);
		}
		$op_details = new TROperationsVO();
		$op_details->populate($_REQUEST, true);
		$op_details->tr_id = $tr_id;

		if( isset($_REQUEST['formName']) && $_REQUEST['formName'] == 'frmLearner' )
		{
			$op_details->support_conversation = isset($_REQUEST['support_conversation']) && $_REQUEST['support_conversation'] == '' ? '' : $_REQUEST['support_conversation'];
			$op_details->epa_reasonable_adjustment = isset($_REQUEST['epa_reasonable_adjustment']) && $_REQUEST['epa_reasonable_adjustment'] == '' ? '' : $_REQUEST['epa_reasonable_adjustment'];
			$op_details->als_plan = !isset($_REQUEST['als_plan']) ? 0 : $_REQUEST['als_plan'];
			$op_details->diagnosis_evidence_required = !isset($_REQUEST['diagnosis_evidence_required']) ? 0 : $_REQUEST['diagnosis_evidence_required'];
		}

		if(isset($_REQUEST['formName']) && $_REQUEST['formName'] == 'frmPdprep') 
		{
			if(isset($_REQUEST['chk_save_project_checkin']) && trim($_REQUEST['chk_save_project_checkin']) == '1')
			{
				$op_details->project_checkin = $this->saveProjectCheckin($link, $op_details, $_REQUEST);
			}
			if(isset($_POST['total_mock_interviews']))
			{
				$mock_interview_xml = XML::loadSimpleXML('<Mock></Mock>');
				$ic = 0;
				for($i = 1; $i <= $_POST['total_mock_interviews']; $i++)
				{
					if( 
						(isset($_POST['mock_interview_planned_date_'.$i]) && trim($_POST['mock_interview_planned_date_'.$i]) != '' ) || 
						(isset($_POST['mock_interview_actual_date_'.$i]) && trim($_POST['mock_interview_actual_date_'.$i]) != '' ) || 
						(isset($_POST['mock_interview_completed_'.$i]) && trim($_POST['mock_interview_completed_'.$i]) != '' ) 
					)
					{
						$new_set = $mock_interview_xml->addChild('Set');
						$new_set->Iteration = ++$ic;
						$new_set->PlannedDate = trim($_POST['mock_interview_planned_date_'.$i]);
						$new_set->ActualDate = trim($_POST['mock_interview_actual_date_'.$i]);
						$new_set->Completed = trim($_POST['mock_interview_completed_'.$i]);
					}
				}
				$dom = new DOMDocument;
				$dom->preserveWhiteSpace = FALSE;
				@$dom->loadXML($mock_interview_xml->saveXML());
				$dom->formatOutput = TRUE;
				$modified_xml = $dom->saveXml();
				$modified_xml = str_replace('<?xml version="1.0"?>', '', $modified_xml);
				$op_details->epa_mock_interview = $modified_xml;
			}
			if(isset($_POST['total_project_prep_session']))
			{
				$project_prep_session = XML::loadSimpleXML('<Mock></Mock>');
				$ic = 0;
				for($i = 1; $i <= $_POST['total_project_prep_session']; $i++)
				{
					if( 
						(isset($_POST['project_prep_session_planned_date_'.$i]) && trim($_POST['project_prep_session_planned_date_'.$i]) != '' ) || 
						(isset($_POST['project_prep_session_interview_actual_date_'.$i]) && trim($_POST['project_prep_session_interview_actual_date_'.$i]) != '' ) || 
						(isset($_POST['project_prep_session_completed_'.$i]) && trim($_POST['project_prep_session_completed_'.$i]) != '' ) 
					)
					{
						$new_set = $project_prep_session->addChild('Set');
						$new_set->Iteration = ++$ic;
						$new_set->PlannedDate = trim($_POST['project_prep_session_planned_date_'.$i]);
						$new_set->ActualDate = trim($_POST['project_prep_session_interview_actual_date_'.$i]);
						$new_set->Completed = trim($_POST['project_prep_session_completed_'.$i]);
					}
				}
				$dom = new DOMDocument;
				$dom->preserveWhiteSpace = FALSE;
				@$dom->loadXML($project_prep_session->saveXML());
				$dom->formatOutput = TRUE;
				$modified_xml = $dom->saveXml();
				$modified_xml = str_replace('<?xml version="1.0"?>', '', $modified_xml);
				$op_details->project_prep_session = $modified_xml;
			}
			DAO::saveObjectToTable($link, 'tr_operations', $op_details);
			http_redirect("do.php?_action=view_edit_op_learner&tr_id=" . $tr_id . "&tracker_id=" . $tracker_id);
		}

		if(isset($_REQUEST['week_3_call_notes']) && trim($_REQUEST['week_3_call_notes']) != '')
			$op_details->week_3_call_notes = $this->saveOperationsNotes($link, $op_details, 'week_3_call_notes', $_REQUEST['week_3_call_notes']);
		//if(isset($_REQUEST['lar']) && trim($_REQUEST['lar']) != '')
		if(isset($_REQUEST['chk_save_lar']) && $_REQUEST['chk_save_lar'] == '1')
			$op_details->lar_details = $this->saveLAR($link, $op_details, $_REQUEST);
		//if(isset($_REQUEST['lras_status']) && trim($_REQUEST['lras_status']) != '')
		if(isset($_REQUEST['chk_save_lras']) && $_REQUEST['chk_save_lras'] == '1')
			$op_details->lras_details = $this->saveLras($link, $op_details, $_REQUEST);
		if(isset($_REQUEST['added_to_lms_notes']) && trim($_REQUEST['added_to_lms_notes']) != '')
			$op_details->added_to_lms_notes = $this->saveOperationsNotes($link, $op_details, 'added_to_lms_notes', $_REQUEST['added_to_lms_notes']);
		if(isset($_REQUEST['hour_48_call_notes']) && trim($_REQUEST['hour_48_call_notes']) != '')
			$op_details->hour_48_call_notes = $this->saveOperationsNotes($link, $op_details, 'hour_48_call_notes', $_REQUEST['hour_48_call_notes']);
		if(isset($_REQUEST['day_7_call_notes']) && trim($_REQUEST['day_7_call_notes']) != '')
			$op_details->day_7_call_notes = $this->saveOperationsNotes($link, $op_details, 'day_7_call_notes', $_REQUEST['day_7_call_notes']);
		if(isset($_REQUEST['welcome_call_notes']) && trim($_REQUEST['welcome_call_notes']) != '')
			$op_details->welcome_call_notes = $this->saveOperationsNotes($link, $op_details, 'welcome_call_notes', $_REQUEST['welcome_call_notes']);
		if(isset($_REQUEST['leaver_form_notes']) && trim($_REQUEST['leaver_form_notes']) != '')
			$op_details->leaver_form_notes = $this->saveOperationsNotes($link, $op_details, 'leaver_form_notes', $_REQUEST['leaver_form_notes']);
		if(isset($_REQUEST['last_learn_evidence']) && trim($_REQUEST['last_learn_evidence']) != '')
			$op_details->last_learning_evidence = $this->saveLastLearningEvidence($link, $op_details, $_REQUEST);
		//if(isset($_REQUEST['break_in_learning']) && trim($_REQUEST['break_in_learning']) != '')
		if(isset($_REQUEST['chk_save_bil']) && trim($_REQUEST['chk_save_bil']) == '1')
			$op_details->bil_details = $this->saveBIL($link, $op_details, $_REQUEST);
		// if(isset($_REQUEST['leaver']) && trim($_REQUEST['leaver']) != '')
		if(isset($_REQUEST['chk_save_leaver']) && trim($_REQUEST['chk_save_leaver']) == '1')
		{
			$op_details->leaver_details = $this->saveLeaver($link, $op_details, $_REQUEST);
			if($_REQUEST['leaver'] == 'Y')
			{
				Induction::updateInduction($link, $tr_id);
			}
		}
		if(isset($_REQUEST['peed_status']) && trim($_REQUEST['peed_status']) != '')
			$op_details->peed_details = $this->savePeed($link, $op_details, $_REQUEST);
		if(isset($_REQUEST['lras_comments']) && trim($_REQUEST['lras_comments']) != '')
			$op_details->lras_comments = $this->saveLrasComments($link, $op_details, $_REQUEST['lras_comments']);

		//pre($op_details);

		if(isset($_REQUEST['formName']) && $_REQUEST['formName'] == 'frmAdditionalInfo')
		{
			$this->saveAdditionalInformation($link, $op_details);
			http_redirect("do.php?_action=view_edit_op_learner&tr_id=" . $tr_id . "&tracker_id=" . $tracker_id);
		}
		if(isset($_REQUEST['formName']) && $_REQUEST['formName'] == 'frmEPA')
		{
			$this->saveEPA($link, $op_details);
			http_redirect("do.php?_action=view_edit_op_learner&tr_id=" . $tr_id . "&tracker_id=" . $tracker_id);
		}


		DAO::transaction_start($link);
		try
		{
			if($_REQUEST['main_contact_id'] == '')
				$op_details->main_contact_id = array();

			$op_details->additional_support = trim($op_details->additional_support) == '' ? '' : $op_details->additional_support;
			$op_details->general_comments = trim($op_details->general_comments) == '' ? '' : $op_details->general_comments;

			DAO::saveObjectToTable($link, 'tr_operations', $op_details);

			// save induction information
			$inductee_id = isset($_REQUEST['inductee_id'])?$_REQUEST['inductee_id']:'';
			if($inductee_id != '')
			{
				$inductee = Inductee::loadFromDatabase($link, $inductee_id);
				$inductee->learner_id = $_REQUEST['learner_id'];
				if(isset($_REQUEST['learner_id_notes']) && $_REQUEST['learner_id_notes'] != '')
					$inductee->learner_id_notes = $this->saveInducteeNotes($link, $inductee, 'learner_id_notes', $_REQUEST['learner_id_notes']);
				$inductee->sen_date = isset($_REQUEST['sen_date']) ? $_REQUEST['sen_date'] : '';
				$inductee->save($link);
			}

			//if tr_operations record is complete then update the status in tr this is for the filter to work
			if($op_details->is_completed == 'Y')
				DAO::execute($link, "UPDATE tr SET tr.operations_status = 'Y' WHERE tr.id = '{$op_details->tr_id}'");
			else
				DAO::execute($link, "UPDATE tr SET tr.operations_status = 'N' WHERE tr.id = '{$op_details->tr_id}'");

			// tr fields
			$training_record = TrainingRecord::loadFromDatabase($link, $tr_id)	;
			$training_record->ad_arrangement_req = isset($_REQUEST['ad_arrangement_req']) ? $_REQUEST['ad_arrangement_req'] : '';
			$training_record->ad_arrangement_agr = isset($_REQUEST['ad_arrangement_agr']) ? $_REQUEST['ad_arrangement_agr'] : '';
			$training_record->save($link);

			DAO::transaction_commit($link);
		}
		catch(Exception $e)
		{
			DAO::transaction_rollback($link, $e);
			throw new WrappedException($e);
		}

		if(IS_AJAX)
		{
			header("Content-Type: text/plain");
			echo 'success';
		}
		else
		{
			http_redirect("do.php?_action=view_edit_op_learner&tr_id=" . $op_details->tr_id . "&tracker_id=" . $tracker_id);
		}
	}

	public function saveProjectCheckin(PDO $link, TROperationsVO $op_details, $data)
	{
		$project_checkin_date_week1 = isset($data['project_checkin_date_week1']) ? $data['project_checkin_date_week1'] : '';
		$project_checkin_date_week2 = isset($data['project_checkin_date_week2']) ? $data['project_checkin_date_week2'] : '';
		$project_checkin_date_week3 = isset($data['project_checkin_date_week3']) ? $data['project_checkin_date_week3'] : '';
		$project_checkin_date_week4 = isset($data['project_checkin_date_week4']) ? $data['project_checkin_date_week4'] : '';
		$project_checkin_done_week1 = isset($data['project_checkin_done_week1']) ? $data['project_checkin_done_week1'] : '';
		$project_checkin_done_week2 = isset($data['project_checkin_done_week2']) ? $data['project_checkin_done_week2'] : '';
		$project_checkin_done_week3 = isset($data['project_checkin_done_week3']) ? $data['project_checkin_done_week3'] : '';
		$project_checkin_done_week4 = isset($data['project_checkin_done_week4']) ? $data['project_checkin_done_week4'] : '';
		$project_checkin_comments = isset($data['project_checkin_comments']) ? $data['project_checkin_comments'] : '';
		$project_checkin_comments = str_replace("�", "&pound;", $project_checkin_comments);
		$project_checkin_comments = Text::utf8_to_latin1($project_checkin_comments);

		$project_checkin_comments = htmlspecialchars((string)$project_checkin_comments, 16);
		$xml = '';
		$xml = DAO::getSingleValue($link, "SELECT project_checkin FROM tr_operations WHERE tr_id = '{$op_details->tr_id}'");
		if(is_null($xml) || $xml == '')
			$xml = '<Notes></Notes>';
		$xml = XML::loadSimpleXML($xml);
		$new_note = $xml->addChild('Note');
		$new_note->DateTime = date('Y-m-d H:i:s');
		$new_note->CreatedBy = $_SESSION['user']->id;
		$new_note->DateWeek1 = $project_checkin_date_week1;
		$new_note->DateWeek2 = $project_checkin_date_week2;
		$new_note->DateWeek3 = $project_checkin_date_week3;
		$new_note->DateWeek4 = $project_checkin_date_week4;
		$new_note->CheckInDoneWeek1 = $project_checkin_done_week1;
		$new_note->CheckInDoneWeek2 = $project_checkin_done_week2;
		$new_note->CheckInDoneWeek3 = $project_checkin_done_week3;
		$new_note->CheckInDoneWeek4 = $project_checkin_done_week4;
		$new_note->Comments = $project_checkin_comments;
		$dom = new DOMDocument;
		$dom->preserveWhiteSpace = FALSE;
		@$dom->loadXML($xml->saveXML());
		$dom->formatOutput = TRUE;
		$modified_xml = $dom->saveXml();
		$modified_xml = str_replace('<?xml version="1.0"?>', '', $modified_xml);

		return $modified_xml;
	}

	public function saveOperationsNotes(PDO $link, TROperationsVO $op_details, $note_type, $notes)
	{
		if(trim($notes) == '')
			return;

		$notes = str_replace("�", "&pound;", $notes);
		$notes = Text::utf8_to_latin1($notes);

		$notes = htmlspecialchars((string)$notes, 16);
		$xml = '';
		$xml = DAO::getSingleValue($link, "SELECT {$note_type} FROM tr_operations WHERE tr_id = '{$op_details->tr_id}'");
		if(is_null($xml) || $xml == '')
			$xml = '<Notes></Notes>';
		$xml = XML::loadSimpleXML($xml);
		$new_note = $xml->addChild('Note');
		$new_note->DateTime = date('Y-m-d H:i:s');
		$new_note->CreatedBy = $_SESSION['user']->id;
		$new_note->NoteType = $note_type;
		$new_note->Note = $notes;
		$dom = new DOMDocument;
		$dom->preserveWhiteSpace = FALSE;
		@$dom->loadXML($xml->saveXML());
		$dom->formatOutput = TRUE;
		$modified_xml = $dom->saveXml();
		$modified_xml = str_replace('<?xml version="1.0"?>', '', $modified_xml);

		return $modified_xml;
	}

	public function saveLastLearningEvidence(PDO $link, TROperationsVO $op_details, $IncomingInfo)
	{
		$IncomingInfo['last_learn_evidence_notes'] = str_replace("�", "&pound;", $IncomingInfo['last_learn_evidence_notes']);
		$IncomingInfo['last_learn_evidence_notes'] = Text::utf8_to_latin1($IncomingInfo['last_learn_evidence_notes']);
		$IncomingInfo['last_learn_evidence_notes'] = htmlspecialchars((string)$IncomingInfo['last_learn_evidence_notes'], 16);
		$xml = '';
		$xml = DAO::getSingleValue($link, "SELECT last_learning_evidence FROM tr_operations WHERE tr_id = '{$op_details->tr_id}'");
		if(is_null($xml) || $xml == '')
			$xml = '<Evidences></Evidences>';
		$xml = XML::loadSimpleXML($xml);
		$new_note = $xml->addChild('Evidence');
		$new_note->Type = $IncomingInfo['last_learn_evidence'];
		$new_note->Date = $IncomingInfo['last_learning_evidence_date'];
		$new_note->Note = $IncomingInfo['last_learn_evidence_notes'];
		$new_note->CreatedBy = $_SESSION['user']->id;
		$new_note->DateTime = date('Y-m-d H:i:s');
		$dom = new DOMDocument;
		$dom->preserveWhiteSpace = FALSE;
		@$dom->loadXML($xml->saveXML());
		$dom->formatOutput = TRUE;
		$modified_xml = $dom->saveXml();
		$modified_xml = str_replace('<?xml version="1.0"?>', '', $modified_xml);

		return $modified_xml;
	}

	public function saveLAR(PDO $link, TROperationsVO $op_details, $IncomingInfo)
	{
		$textfields = [
			//"lar_notes",
			"lar_summary",
			//"lar_communication",
			//"lar_contact_history",
			"lar_next_action_summary",
		];
		foreach($textfields AS $textfield_name)
		{
			if(!isset($IncomingInfo[$textfield_name]))
				continue;
			
			$IncomingInfo[$textfield_name] = str_replace("�", "&pound;", $IncomingInfo[$textfield_name]);
			$IncomingInfo[$textfield_name] = Text::utf8_to_latin1($IncomingInfo[$textfield_name]);
			$IncomingInfo[$textfield_name] = htmlspecialchars((string)$IncomingInfo[$textfield_name], 16);
		}
		// $IncomingInfo['lar_notes'] = str_replace("�", "&pound;", $IncomingInfo['lar_notes']);
		// $IncomingInfo['lar_notes'] = Text::utf8_to_latin1($IncomingInfo['lar_notes']);
		// $IncomingInfo['lar_notes'] = htmlspecialchars((string)$IncomingInfo['lar_notes'], 16);
		$xml = '';
		$xml = DAO::getSingleValue($link, "SELECT lar_details FROM tr_operations WHERE tr_id = '{$op_details->tr_id}'");
		if(is_null($xml) || $xml == '')
			$xml = '<Notes></Notes>';
		$xml = XML::loadSimpleXML($xml);
		$new_note = $xml->addChild('Note');
		$new_note->Type = $IncomingInfo['lar'];
		$new_note->Date = isset($IncomingInfo['lar_date']) ? $IncomingInfo['lar_date'] : '';
		$new_note->OpenDate = isset($IncomingInfo['lar_open_date']) ? $IncomingInfo['lar_open_date'] : '';
		$new_note->ClosedDate = isset($IncomingInfo['lar_closed_date']) ? $IncomingInfo['lar_closed_date'] : '';
		$new_note->Destination = isset($IncomingInfo['lar_destination']) ? $IncomingInfo['lar_destination'] : '';
		$new_note->Note = ''; //$IncomingInfo['lar_notes'];
		//$new_note->Reason = isset($IncomingInfo['lar_reason'])?$IncomingInfo['lar_reason']:'';
		if(isset($IncomingInfo['lar_reason']) && is_array($IncomingInfo['lar_reason']) && count($IncomingInfo['lar_reason']) > 0)
		{
			$new_note->Reason = implode(",", $IncomingInfo['lar_reason']);
		}
		else
		{
			$new_note->Reason = '';
		}
		//$new_note->SecondReason = isset($IncomingInfo['sec_lar_reason'])?$IncomingInfo['sec_lar_reason']:'';
		if(isset($IncomingInfo['sec_lar_reason']) && is_array($IncomingInfo['sec_lar_reason']) && count($IncomingInfo['sec_lar_reason']) > 0)
		{
			$new_note->SecondReason = implode(",", $IncomingInfo['sec_lar_reason']);
		}
		else
		{
			$new_note->SecondReason = '';
		}
		$new_note->Retention = isset($IncomingInfo['lar_retention_category'])?$IncomingInfo['lar_retention_category']:'';
		//$new_note->RetentionOther = isset($IncomingInfo['lar_retention_other'])?$IncomingInfo['lar_retention_other']:'';
		$new_note->RAG = isset($IncomingInfo['lar_rag'])?$IncomingInfo['lar_rag']:'';
		$new_note->NextActionDate = $IncomingInfo['next_action_date'];
		$new_note->Owner = $IncomingInfo['lar_op_owner'];
		$new_note->RiskOf = isset($IncomingInfo['lar_at_risk_of']) ? $IncomingInfo['lar_at_risk_of'] : '';
		//$new_note->LeaverDecision = $IncomingInfo['leaver_decision_made'];
		$new_note->NoContact = isset($IncomingInfo['leaver_no_contact']) ? $IncomingInfo['leaver_no_contact'] : '';
		if(isset($IncomingInfo['actively_involved_in']) && is_array($IncomingInfo['actively_involved_in']) && count($IncomingInfo['actively_involved_in']) > 0)
		{
			$new_note->ActivelyInvolved = implode(",", $IncomingInfo['actively_involved_in']);
		}
		else
		{
			$new_note->ActivelyInvolved = '';
		}
		$new_note->Summary = isset($IncomingInfo['lar_summary'])?$IncomingInfo['lar_summary']:'';
		$new_note->Communication = isset($IncomingInfo['lar_communication'])?$IncomingInfo['lar_communication']:'';
		$new_note->ContactHistory = isset($IncomingInfo['lar_contact_history'])?$IncomingInfo['lar_contact_history']:'';
		$new_note->NextActionHistory = isset($IncomingInfo['lar_next_action_summary'])?$IncomingInfo['lar_next_action_summary']:'';
		// get the next action of the previous (last) saved note which will become the last action of this note
		$last_action = DAO::getSingleValue($link, "SELECT extractvalue(tr_operations.`lar_details`, '/Notes/Note[last()]/NextActionDate') FROM tr_operations WHERE tr_id = '{$op_details->tr_id}'");
		$new_note->LastActionDate = $last_action;
		$new_note->SalesDeadlineDate = isset($IncomingInfo['sales_deadline_date']) ? $IncomingInfo['sales_deadline_date'] : '';
		$new_note->lar_30_day = isset($IncomingInfo['lar_30_day']) ? $IncomingInfo['lar_30_day']: '';
		$new_note->lar_removal_date = isset($IncomingInfo['lar_removal_date']) ? $IncomingInfo['lar_removal_date']: '';
		$new_note->lar_potential_leaver = isset($IncomingInfo['lar_potential_leaver']) ? 'Yes' : '';
		$new_note->CreatedBy = $_SESSION['user']->id;
		$new_note->DateTime = date('Y-m-d H:i:s');
		$dom = new DOMDocument;
		$dom->preserveWhiteSpace = FALSE;
		@$dom->loadXML($xml->saveXML());
		$dom->formatOutput = TRUE;
		$modified_xml = $dom->saveXml();
		$modified_xml = str_replace('<?xml version="1.0"?>', '', $modified_xml);

		return $modified_xml;
	}

	public function saveLras(PDO $link, TROperationsVO $op_details, $IncomingInfo)
	{
		$textfields = [
			"lras_summary",
			"action_plan_agreed",
			"resources_provided",
		];
		foreach($textfields AS $textfield_name)
		{
			if(!isset($IncomingInfo[$textfield_name]))
				continue;
			
			$IncomingInfo[$textfield_name] = str_replace("�", "&pound;", $IncomingInfo[$textfield_name]);
			$IncomingInfo[$textfield_name] = Text::utf8_to_latin1($IncomingInfo[$textfield_name]);
			$IncomingInfo[$textfield_name] = htmlspecialchars((string)$IncomingInfo[$textfield_name], 16);
		}
		$xml = '';
		$xml = DAO::getSingleValue($link, "SELECT lras_details FROM tr_operations WHERE tr_id = '{$op_details->tr_id}'");
		if(is_null($xml) || $xml == '')
			$xml = '<Notes></Notes>';
		$xml = XML::loadSimpleXML($xml);
		$new_note = $xml->addChild('Note');
		$new_note->Status = $IncomingInfo['lras_status'];
		$new_note->Summary = $IncomingInfo['lras_summary'];
		if(isset($IncomingInfo['lras_reason']) && is_array($IncomingInfo['lras_reason']) && count($IncomingInfo['lras_reason']) > 0)
		{
			$new_note->Reason = implode(",", $IncomingInfo['lras_reason']);
		}
		else
		{
			$new_note->Reason = '';
		}
		if(isset($IncomingInfo['lras_owner']) && is_array($IncomingInfo['lras_owner']) && count($IncomingInfo['lras_owner']) > 0)
		{
			$new_note->Owner = implode(",", $IncomingInfo['lras_owner']);
		}
		else
		{
			$new_note->Owner = '';
		}
		$new_note->Category = $IncomingInfo['lras_category'];
		$new_note->LrasDate = isset($IncomingInfo['lras_date']) ? $IncomingInfo['lras_date'] : '';
		$new_note->RecommendedEndDate = isset($IncomingInfo['lras_recommended_end_date']) ? $IncomingInfo['lras_recommended_end_date'] : '';
		$new_note->ProReact = $IncomingInfo['lras_pro_react'];
		if(isset($IncomingInfo['lras_support_provider']) && is_array($IncomingInfo['lras_support_provider']) && count($IncomingInfo['lras_support_provider']) > 0)
		{
			$new_note->SupportProvider = implode(",", $IncomingInfo['lras_support_provider']);
		}
		else
		{
			$new_note->SupportProvider = '';
		}
		$new_note->ActionPlanAgreed = $IncomingInfo['action_plan_agreed'];
		$new_note->ResourcesProvided = $IncomingInfo['resources_provided'];


		$new_note->CreatedBy = $_SESSION['user']->id;
		$new_note->DateTime = date('Y-m-d H:i:s');
		$dom = new DOMDocument;
		$dom->preserveWhiteSpace = FALSE;
		@$dom->loadXML($xml->saveXML());
		$dom->formatOutput = TRUE;
		$modified_xml = $dom->saveXml();
		$modified_xml = str_replace('<?xml version="1.0"?>', '', $modified_xml);

		return $modified_xml;
	}

	public function saveBIL(PDO $link, TROperationsVO $op_details, $IncomingInfo)
	{
		$IncomingInfo['break_in_learning_notes'] = str_replace("�", "&pound;", $IncomingInfo['break_in_learning_notes']);
		$IncomingInfo['break_in_learning_notes'] = Text::utf8_to_latin1($IncomingInfo['break_in_learning_notes']);
		$IncomingInfo['break_in_learning_notes'] = htmlspecialchars((string)$IncomingInfo['break_in_learning_notes'], 16);
		$IncomingInfo['bil_next_action_summary'] = str_replace("�", "&pound;", $IncomingInfo['bil_next_action_summary']);
		$IncomingInfo['bil_next_action_summary'] = Text::utf8_to_latin1($IncomingInfo['bil_next_action_summary']);
		$IncomingInfo['bil_next_action_summary'] = htmlspecialchars((string)$IncomingInfo['bil_next_action_summary'], 16);
		$xml = '';
		$xml = DAO::getSingleValue($link, "SELECT bil_details FROM tr_operations WHERE tr_id = '{$op_details->tr_id}'");
		if(is_null($xml) || $xml == '')
			$xml = '<Notes></Notes>';
		$xml = XML::loadSimpleXML($xml);
		$new_note = $xml->addChild('Note');
		$new_note->Type = $IncomingInfo['break_in_learning'];
		$new_note->Date = isset($IncomingInfo['bil_date']) ? $IncomingInfo['bil_date'] : '';
		$new_note->LdolDate = isset($IncomingInfo['bil_ldol_date']) ? $IncomingInfo['bil_ldol_date'] : '';
		$new_note->ClosedDate = isset($IncomingInfo['bil_closed_date']) ? $IncomingInfo['bil_closed_date'] : '';
		$new_note->FdolDate = isset($IncomingInfo['bil_fdol_date']) ? $IncomingInfo['bil_fdol_date'] : '';
		$new_note->RevisitDate = isset($IncomingInfo['bil_revisit_date']) ? $IncomingInfo['bil_revisit_date'] : '';
		$new_note->Reason = isset($IncomingInfo['bil_reason']) ? $IncomingInfo['bil_reason'] : '';
		$new_note->Retention = isset($IncomingInfo['bil_retention']) ? $IncomingInfo['bil_retention'] : '';
		$new_note->Owner = $IncomingInfo['bil_op_owner'];
		$new_note->PredictedReturn = isset($IncomingInfo['predicted_return_date']) ? $IncomingInfo['predicted_return_date']: '';
		$new_note->PredictedLeaver = isset($IncomingInfo['predicted_leaver_date']) ? $IncomingInfo['predicted_leaver_date']: '';
		$new_note->NextAction = isset($IncomingInfo['bil_next_action_date']) ? $IncomingInfo['bil_next_action_date']: '';
		$new_note->bil_30_day = isset($IncomingInfo['bil_30_day']) ? $IncomingInfo['bil_30_day']: '';
		$new_note->bil_removal_date = isset($IncomingInfo['bil_removal_date']) ? $IncomingInfo['bil_removal_date']: '';
		$new_note->bil_potential_leaver = isset($IncomingInfo['bil_potential_leaver']) ? 'Yes' : '';
		$new_note->Note = isset($IncomingInfo['break_in_learning_notes']) ? $IncomingInfo['break_in_learning_notes'] : '';
		$new_note->NextActionSummary = isset($IncomingInfo['bil_next_action_summary']) ? $IncomingInfo['bil_next_action_summary'] : '';
		$new_note->CreatedBy = $_SESSION['user']->id;
		$new_note->DateTime = date('Y-m-d H:i:s');
		$dom = new DOMDocument;
		$dom->preserveWhiteSpace = FALSE;
		@$dom->loadXML($xml->saveXML());
		$dom->formatOutput = TRUE;
		$modified_xml = $dom->saveXml();
		$modified_xml = str_replace('<?xml version="1.0"?>', '', $modified_xml);

		return $modified_xml;
	}

	public function saveLeaver(PDO $link, TROperationsVO $op_details, $IncomingInfo)
	{
		$IncomingInfo['leaver_notes'] = str_replace("�", "&pound;", $IncomingInfo['leaver_notes']);
		$IncomingInfo['leaver_notes'] = Text::utf8_to_latin1($IncomingInfo['leaver_notes']);
		$IncomingInfo['leaver_notes'] = htmlspecialchars((string)$IncomingInfo['leaver_notes'], 16);
		$xml = '';
		$xml = DAO::getSingleValue($link, "SELECT leaver_details FROM tr_operations WHERE tr_id = '{$op_details->tr_id}'");
		if(is_null($xml) || $xml == '')
			$xml = '<Notes></Notes>';
		$xml = XML::loadSimpleXML($xml);
		$new_note = $xml->addChild('Note');
		$new_note->Type = $IncomingInfo['leaver'];
		$new_note->DirectLeaver = isset($IncomingInfo['direct_leaver']) ? $IncomingInfo['direct_leaver'] : '';
		$new_note->Date = isset($IncomingInfo['leaver_date']) ? $IncomingInfo['leaver_date'] : '';
		$new_note->LdolDate = isset($IncomingInfo['leaver_ldol_date']) ? $IncomingInfo['leaver_ldol_date'] : '';
		$new_note->LdolEvidence = isset($IncomingInfo['leaver_ldol_evidence']) ? $IncomingInfo['leaver_ldol_evidence'] : '';
		$new_note->Reason = isset($IncomingInfo['leaver_reason']) ? $IncomingInfo['leaver_reason'] : '';
		$new_note->Cause = isset($IncomingInfo['leaver_cause']) ? $IncomingInfo['leaver_cause'] : '';
		$new_note->Retention = isset($IncomingInfo['leaver_retention_category'])?$IncomingInfo['leaver_retention_category']:'';
		$new_note->RetentionOther = isset($IncomingInfo['leaver_retention_other'])?$IncomingInfo['leaver_retention_other']:'';
		$new_note->Note = isset($IncomingInfo['leaver_notes']) ? $IncomingInfo['leaver_notes'] : '';
		$new_note->Owner = isset($IncomingInfo['leaver_op_owner']) ? $IncomingInfo['leaver_op_owner'] : '';
		$new_note->LeaverDecision = isset($IncomingInfo['leaver_decision_made']) ? $IncomingInfo['leaver_decision_made'] : '';
		$new_note->PositiveOutcome = isset($IncomingInfo['leaver_positive_outcome']) ? $IncomingInfo['leaver_positive_outcome'] : '';
		$new_note->PotentialReturn = isset($IncomingInfo['leaver_potential_return']) ? $IncomingInfo['leaver_potential_return'] : '';
		$new_note->CreatedBy = $_SESSION['user']->id;
		$new_note->DateTime = date('Y-m-d H:i:s');
		$dom = new DOMDocument;
		$dom->preserveWhiteSpace = FALSE;
		@$dom->loadXML($xml->saveXML());
		$dom->formatOutput = TRUE;
		$modified_xml = $dom->saveXml();
		$modified_xml = str_replace('<?xml version="1.0"?>', '', $modified_xml);

		return $modified_xml;
	}

	public function savePeed(PDO $link, TROperationsVO $op_details, $IncomingInfo)
	{
		$IncomingInfo['peed_notes'] = str_replace("�", "&pound;", $IncomingInfo['peed_notes']);
		$IncomingInfo['peed_notes'] = Text::utf8_to_latin1($IncomingInfo['peed_notes']);
		$IncomingInfo['peed_notes'] = htmlspecialchars((string)$IncomingInfo['peed_notes'], 16);
		$xml = '';
		//$xml = DAO::getSingleValue($link, "SELECT peed_details FROM tr_operations WHERE tr_id = '{$op_details->tr_id}'");
		if(is_null($xml) || $xml == '')
			$xml = '<Notes></Notes>';
		$xml = XML::loadSimpleXML($xml);
		$new_note = $xml->addChild('Note');
		$new_note->Status = $IncomingInfo['peed_status'];
		$new_note->Date = $IncomingInfo['peed_date'];
		$new_note->Comments = $IncomingInfo['peed_notes'];
		$new_note->Reason = $IncomingInfo['peed_reason'];
		$new_note->Cause = $IncomingInfo['peed_cause'];
		$new_note->Revisit = $IncomingInfo['peed_revisit_date'];
		$new_note->Owner = $IncomingInfo['peed_owner'];
		$new_note->ForecastDate = $IncomingInfo['peed_forecast_date'];
		$new_note->Lsl = $IncomingInfo['peed_lsl_involvement'];
		$new_note->LslStatus = $IncomingInfo['peed_lsl_involvement_status'];
		$new_note->CompletionDate = $IncomingInfo['peed_completion_date'];
		$new_note->CreatedBy = $_SESSION['user']->id;
		$new_note->DateTime = date('Y-m-d H:i:s');

		$dom = new DOMDocument;
		$dom->preserveWhiteSpace = FALSE;
		@$dom->loadXML($xml->saveXML());
		$dom->formatOutput = TRUE;
		$modified_xml = $dom->saveXml();
		$modified_xml = str_replace('<?xml version="1.0"?>', '', $modified_xml);

		return $modified_xml;
	}

	public function saveLrasComments(PDO $link, TROperationsVO $op_details, $lras_comments)
	{
		$lras_comments = str_replace("�", "&pound;", $lras_comments);
		$lras_comments = Text::utf8_to_latin1($lras_comments);
		$lras_comments = htmlspecialchars((string)$lras_comments, 16);
		$xml = DAO::getSingleValue($link, "SELECT lras_comments FROM tr_operations WHERE tr_id = '{$op_details->tr_id}'");
		if(is_null($xml) || $xml == '')
			$xml = '<Notes></Notes>';
		$xml = XML::loadSimpleXML($xml);
		$new_note = $xml->addChild('Note');
		$new_note->Comments = $lras_comments;
		$new_note->CreatedBy = $_SESSION['user']->id;
		$new_note->DateTime = date('Y-m-d H:i:s');

		$dom = new DOMDocument;
		$dom->preserveWhiteSpace = FALSE;
		@$dom->loadXML($xml->saveXML());
		$dom->formatOutput = TRUE;
		$modified_xml = $dom->saveXml();
		$modified_xml = str_replace('<?xml version="1.0"?>', '', $modified_xml);

		return $modified_xml;
	}

	public function saveAdditionalInformation(PDO $link, TROperationsVO $op_details)
	{
		$type = isset($_REQUEST['type']) ? $_REQUEST['type'] : '';
		$date = isset($_REQUEST['date']) ? $_REQUEST['date'] : '';
		$detail = isset($_REQUEST['detail']) ? $_REQUEST['detail'] : '';

		if($type == '')
			return;

		$detail = str_replace("�", "&pound;", $detail);
		$detail = Text::utf8_to_latin1($detail);

		$detail = htmlspecialchars((string)$detail, 16);
		$xml = '';
		$xml = DAO::getSingleValue($link, "SELECT additional_info FROM tr_operations WHERE tr_id = '{$op_details->tr_id}'");
		if(is_null($xml) || $xml == '')
			$xml = '<Notes></Notes>';
		$xml = XML::loadSimpleXML($xml);
		$new_note = $xml->addChild('Note');
		$new_note->DateTime = date('Y-m-d H:i:s');
		$new_note->CreatedBy = $_SESSION['user']->id;
		$new_note->Type = $type;
		$new_note->Date = $date;
		$new_note->Detail = $detail;
		$dom = new DOMDocument;
		$dom->preserveWhiteSpace = FALSE;
		@$dom->loadXML($xml->saveXML());
		$dom->formatOutput = TRUE;
		$modified_xml = $dom->saveXml();
		$modified_xml = str_replace('<?xml version="1.0"?>', '', $modified_xml);

		$op_details->additional_info = $modified_xml;
		DAO::saveObjectToTable($link, 'tr_operations', $op_details);
	}

	public function saveEPA(PDO $link, TROperationsVO $op_details)
	{
		$task_id = isset($_REQUEST['task_id']) ? $_REQUEST['task_id'] : '';
		$task = isset($_REQUEST['task']) ? $_REQUEST['task'] : '';
		$task_applicable = isset($_REQUEST['task_applicable']) ? $_REQUEST['task_applicable'] : '';
		$task_status = isset($_REQUEST['task_status']) ? $_REQUEST['task_status'] : '';
		$task_date = isset($_REQUEST['task_date']) ? $_REQUEST['task_date'] : '';
		$task_actual_date = isset($_REQUEST['task_actual_date']) ? $_REQUEST['task_actual_date'] : '';
		$task_peed_forecast_date = isset($_REQUEST['task_peed_forecast_date']) ? $_REQUEST['task_peed_forecast_date'] : '';
		$task_type = isset($_REQUEST['task_type']) ? $_REQUEST['task_type'] : '';
		$task_comments = isset($_REQUEST['task_comments']) ? $_REQUEST['task_comments'] : '';
		$task_comments = Text::utf8_to_latin1($task_comments);
		$potential_achievement_month = isset($_REQUEST['potential_achievement_month']) ? $_REQUEST['potential_achievement_month'] : '';
		$task_epa_risk = isset($_REQUEST['task_epa_risk']) ? $_REQUEST['task_epa_risk'] : '';
		$task_lsl = isset($_REQUEST['task_lsl']) ? $_REQUEST['task_lsl'] : '';
		$task_peed_cause = isset($_REQUEST['task_peed_cause']) ? $_REQUEST['task_peed_cause'] : '';
		$task_epao = isset($_REQUEST['task_epao']) ? $_REQUEST['task_epao'] : '';
		$task_assessment_method1 = isset($_REQUEST['task_assessment_method1']) ? $_REQUEST['task_assessment_method1'] : '';
		$task_assessment_method2 = isset($_REQUEST['task_assessment_method2']) ? $_REQUEST['task_assessment_method2'] : '';
		$task_end_date = isset($_REQUEST['task_end_date']) ? $_REQUEST['task_end_date'] : '';
		$task_end_time = isset($_REQUEST['task_end_time']) ? $_REQUEST['task_end_time'] : '';

		$op_epa = new stdClass();
		$op_epa->id = $task_id;
		$op_epa->tr_id = $op_details->tr_id;
		$op_epa->task = $task;
		$op_epa->task_applicable = $task_applicable;
		$op_epa->task_status = $task_status;
		$op_epa->task_date = $task_date;
		$op_epa->task_actual_date = $task_actual_date;
		$op_epa->task_peed_forecast_date = $task_peed_forecast_date;
		$op_epa->task_type = $task_type;
		$op_epa->task_comments = str_replace("�", "'", $task_comments);
		$op_epa->potential_achievement_month = $potential_achievement_month;
		$op_epa->task_epa_risk = $task_epa_risk;
		$op_epa->task_lsl = $task_lsl;
		$op_epa->task_peed_cause = $task_peed_cause;
		$op_epa->task_epao = $task_epao;
		$op_epa->task_assessment_method1 = $task_assessment_method1;
		$op_epa->task_assessment_method2 = $task_assessment_method2;
		$op_epa->task_end_date = $task_end_date;
		$op_epa->task_end_time = $task_end_time;

		DAO::saveObjectToTable($link, 'op_epa', $op_epa);

		if($task_id != '')
		{
			$op_log = new stdClass();
			$op_log->id = null;
			$op_log->op_epa_id = $op_epa->id;
			$op_log->created = date('Y-m-d H:i:s');
			$op_log->created_by = $_SESSION['user']->id;
			$op_log->log = json_encode($op_epa);
			DAO::saveObjectToTable($link, 'op_epa_log', $op_log);
		}
	}

	public function saveInducteeNotes(PDO $link, Inductee $inductee, $note_type, $notes)
	{
		if(trim($notes) == '')
			return;
		$notes = str_replace("�", "&pound;", $notes);
		$notes = Text::utf8_to_latin1($notes);

		$notes = htmlspecialchars((string)$notes, 16);
		$xml = '';
		$xml = DAO::getSingleValue($link, "SELECT {$note_type} FROM inductees WHERE inductees.id = '{$inductee->id}'");
		if(is_null($xml) || $xml == '')
			$xml = '<Notes></Notes>';
		$xml = XML::loadSimpleXML($xml);
		$new_note = $xml->addChild('Note');
		$new_note->DateTime = date('Y-m-d H:i:s');
		$new_note->CreatedBy = $_SESSION['user']->id;
		$new_note->NoteType = $note_type;
		$new_note->Note = $notes;
		$dom = new DOMDocument;
		$dom->preserveWhiteSpace = FALSE;
		@$dom->loadXML($xml->saveXML());
		$dom->formatOutput = TRUE;
		$modified_xml = $dom->saveXml();
		$modified_xml = str_replace('<?xml version="1.0"?>', '', $modified_xml);

		return $modified_xml;
	}
}
?>
