<?php
class save_induction implements IAction
{
	public function execute(PDO $link)
	{
		//pre($_REQUEST);
		$selected_tab = isset($_REQUEST['selected_tab'])?$_REQUEST['selected_tab']:'';
		$form = isset($_REQUEST['formName'])?$_REQUEST['formName']:'';
		if($form == '')
			throw new Exception('Missing querystring argument: form name');

		if($form == 'frmLearner')
		{
			$vo = new Inductee();
			$vo->populate($_REQUEST, false, array('learner_id_notes', 'emp_crm_contacts_notes'));
			if(isset($_REQUEST['learner_id_notes']) && $_REQUEST['learner_id_notes'] != '')
				$vo->learner_id_notes = $this->saveInducteeNotes($link, $vo, 'learner_id_notes', $_REQUEST['learner_id_notes']);
			if(isset($_REQUEST['emp_crm_contacts_notes']) && $_REQUEST['emp_crm_contacts_notes'] != '')
				$vo->emp_crm_contacts_notes = $this->saveInducteeNotes($link, $vo, 'emp_crm_contacts_notes', $_REQUEST['emp_crm_contacts_notes']);
			// for lldd set date
			if($_REQUEST['id'] != '')	
			{
				$ldd_value = DAO::getSingleValue($link, "SELECT inductees.ldd FROM inductees WHERE inductees.id = '{$_REQUEST['id']}'");
				if( ($ldd_value == '' || is_null($ldd_value)) && (isset($_REQUEST['ldd']) && $_REQUEST['ldd'] != '') )
				{
					$vo->ldd_set_date = date('Y-m-d H:i:s');
				}
			}
		}
		elseif($form == 'frmLearnerInduction')
		{
			if(isset($_REQUEST['id']) && $_REQUEST['id'] != '')
				$vo = Induction::loadFromDatabase($link, $_REQUEST['id']);
			else
				$vo = new Induction();
			$vo->populate($_REQUEST, false, array('grey_section_comments', 'induction_notes', 'coordinator_notes', 'das_comments', 'contact_comments', 'levy_comments'));
			if(isset($_REQUEST['grey_section_comments']) && $_REQUEST['grey_section_comments'] != '')
				$vo->grey_section_comments = $this->saveInductionNotes($link, $vo, 'grey_section_comments', $_REQUEST['grey_section_comments']);
			if(isset($_REQUEST['das_comments']) && $_REQUEST['das_comments'] != '')
				$vo->das_comments = $this->saveInductionNotes($link, $vo, 'das_comments', $_REQUEST['das_comments']);
			if(isset($_REQUEST['levy_comments']) && $_REQUEST['levy_comments'] != '')
				$vo->levy_comments = $this->saveInductionNotes($link, $vo, 'levy_comments', $_REQUEST['levy_comments']);
			if(isset($_REQUEST['induction_notes']) && $_REQUEST['induction_notes'] != '')
				$vo->induction_notes = $this->saveInductionNotes($link, $vo, 'induction_notes', $_REQUEST['induction_notes']);
			if(isset($_REQUEST['coordinator_notes']) && $_REQUEST['coordinator_notes'] != '')
				$vo->coordinator_notes = $this->saveInductionNotes($link, $vo, 'coordinator_notes', $_REQUEST['coordinator_notes']);
			if(isset($_REQUEST['contact_comments']) && $_REQUEST['contact_comments'] != '')
				$vo->contact_comments = $this->saveInductionNotes($link, $vo, 'contact_comments', $_REQUEST['contact_comments']);	
			$vo->commit_signed = '';
			if(isset($_REQUEST['commit_signed']))
			{
				$vo->commit_signed = isset($_REQUEST['commit_signed'][0])?$_REQUEST['commit_signed'][0]:'';
				$vo->commit_signed .= isset($_REQUEST['commit_signed'][1])?$_REQUEST['commit_signed'][1]:'';
				$vo->commit_signed .= isset($_REQUEST['commit_signed'][2])?$_REQUEST['commit_signed'][2]:'';
			}
			$vo->withdrawn_reason = substr($vo->withdrawn_reason, 0, 499);
		}
		elseif($form == 'frmQuickSaveInduction')
		{
			$vo = Induction::loadFromDatabase($link, $_REQUEST['id']);
			$vo->induction_status = $_REQUEST['induction_status'];
			$vo->sla_received = isset($_REQUEST['sla_received']) ? $_REQUEST['sla_received']: '';
			$vo->levy_payer = $_REQUEST['levy_payer'];
			$vo->date_moved_from_grey_section = $_REQUEST['date_moved_from_grey_section'];
			if(isset($_REQUEST['grey_section_comments']) && $_REQUEST['grey_section_comments'] != '')
				$vo->grey_section_comments = $this->saveInductionNotes($link, $vo, 'grey_section_comments', $_REQUEST['grey_section_comments']);
			if(isset($_REQUEST['contact_comments']) && $_REQUEST['contact_comments'] != '')
				$vo->contact_comments = $this->saveInductionNotes($link, $vo, 'contact_comments', $_REQUEST['contact_comments']);
			if(isset($_REQUEST['induction_notes']) && $_REQUEST['induction_notes'] != '')
				$vo->induction_notes = $this->saveInductionNotes($link, $vo, 'induction_notes', $_REQUEST['induction_notes']);
		}
		elseif($form == 'frmLearnerProgramme')
		{
			if(isset($_REQUEST['id']) && $_REQUEST['id'] != '')
				$vo = InductionProgramme::loadFromDatabase($link, $_REQUEST['id']);
			else
				$vo = new InductionProgramme();
			$vo->populate($_REQUEST, false, array('fs_tutor_notes', 'coordinator_notes_program'));
			$vo->eligibility_test_type = '';
			if(isset($_REQUEST['eligibility_test_type']))
			{
				$vo->eligibility_test_type = isset($_REQUEST['eligibility_test_type'][0])?$_REQUEST['eligibility_test_type'][0]:'';
				$vo->eligibility_test_type .= isset($_REQUEST['eligibility_test_type'][1])?$_REQUEST['eligibility_test_type'][1]:'';
				$vo->eligibility_test_type .= isset($_REQUEST['eligibility_test_type'][2])?$_REQUEST['eligibility_test_type'][2]:'';
			}
			if(isset($_REQUEST['programme_notes']) && $_REQUEST['programme_notes'] != '')
				$vo->programme_notes = $this->saveProgrammeNotes($link, $vo, 'programme_notes', $_REQUEST['programme_notes']);
			if(isset($_REQUEST['coordinator_notes_program']) && $_REQUEST['coordinator_notes_program'] != '')
				$vo->coordinator_notes_program = $this->saveProgrammeNotes($link, $vo, 'coordinator_notes_program', $_REQUEST['coordinator_notes_program']);

		}

		$inductee_id = '';
		$auto_induction_ped = '';
		if(is_a($vo, 'Inductee'))
		{
			$inductee_id = $vo->id;
		}
		elseif(is_a($vo, 'Induction') || is_a($vo, 'InductionProgramme'))
		{
			$inductee_id = $vo->inductee_id;
		}
		if($inductee_id != '')
		{
			$induction_date = DAO::getSingleValue( $link, "SELECT induction.induction_date FROM induction WHERE induction.inductee_id = '{$inductee_id}'" );
			$induction_planned_end_date = DAO::getSingleValue( $link, "SELECT induction.planned_end_date FROM induction WHERE induction.inductee_id = '{$inductee_id}'" );
			$induction_programme_id = DAO::getSingleValue( $link, "SELECT induction_programme.programme_id FROM induction_programme WHERE induction_programme.inductee_id = '{$inductee_id}'" );
			if($induction_date != '' && $induction_programme_id != '')
			{
				$framework_duration = DAO::getSingleValue($link, "SELECT frameworks.duration_in_months FROM frameworks INNER JOIN courses ON frameworks.id = courses.framework_id WHERE courses.id = '{$induction_programme_id}'");
				if($framework_duration != '')
				{
					$auto_induction_ped = new Date($induction_date);
					$auto_induction_ped->addMonths($framework_duration);
				}
				// Change for Level 3 - add 380 days instead of months duration
				$isItLevel3Prog = DAO::getSingleValue($link, "SELECT frameworks.title FROM frameworks INNER JOIN courses ON frameworks.id = courses.framework_id WHERE courses.id = '{$induction_programme_id}' AND frameworks.`title` LIKE '%Level 3%'");
				if($isItLevel3Prog != '')
				{
					$auto_induction_ped = new Date($induction_date);
					$auto_induction_ped->addDays(380);
				}
			}
		}

		//pre($vo);

		DAO::transaction_start($link);
		try
		{
			if(is_a($vo, 'Induction'))
			{
				if($_REQUEST['id'] != '')
				{
					$existing_record = Induction::loadFromDatabase($link, $_REQUEST['id']);
					$log_string = $existing_record->buildAuditLogString($link, $vo);
					if($log_string != '')
					{
						$note = new Note();
						$note->subject = "Field Changed";
						$note->note = $log_string;
					}
				}
				else
				{
					$note = new Note();
					$note->subject = "Field Changed";
					$listInductionStatus = InductionHelper::getListInductionStatus();
					$note->note = "[Induction Status] changed from '' to '" . $listInductionStatus[$vo->induction_status] . "'";
				}

				if (isset($_REQUEST['work_email']))
					DAO::execute($link, "UPDATE inductees SET inductees.work_email = '" . $_REQUEST['work_email'] . "' WHERE inductees.id = '" . $vo->inductee_id . "'");
			}

			$vo->save($link);

			if(is_a($vo, 'Induction'))
			{
				if(isset($note) && !is_null($note))
				{
					$note->is_audit_note = true;
					$note->parent_table = 'induction';
					$note->parent_id = $vo->id;
					$note->save($link);
				}
			}

			if($auto_induction_ped != '')
			{
				DAO::execute($link, "UPDATE induction SET induction.planned_end_date = '{$auto_induction_ped->formatMySQL()}' WHERE induction.inductee_id = '{$inductee_id}'");
			}

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
			if($form == 'frmLearner')
				http_redirect("do.php?_action=edit_inductee&id=" . $vo->id);
			elseif($form == 'frmLearnerInduction')
				http_redirect("do.php?_action=edit_inductee&id=" . $vo->inductee_id);
			elseif($form == 'frmLearnerProgramme')
				http_redirect("do.php?_action=edit_inductee&id=" . $vo->inductee_id);
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

	public function saveInductionNotes(PDO $link, Induction $induction, $note_type, $notes)
	{
		if(trim($notes) == '')
			return;
		$notes = str_replace("�", "&pound;", $notes);
		$notes = Text::utf8_to_latin1($notes);

		$notes = htmlspecialchars((string)$notes, 16);
		$xml = '';
		$xml = DAO::getSingleValue($link, "SELECT {$note_type} FROM induction WHERE induction.id = '{$induction->id}'");
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

	public function saveProgrammeNotes(PDO $link, InductionProgramme $inductionProgramme, $note_type, $notes)
	{
		if(trim($notes) == '')
			return;
		$notes = str_replace("�", "&pound;", $notes);
		$notes = Text::utf8_to_latin1($notes);

		$notes = htmlspecialchars((string)$notes, 16);
		$xml = '';
		$xml = DAO::getSingleValue($link, "SELECT {$note_type} FROM induction_programme WHERE induction_programme.id = '{$inductionProgramme->id}'");
		if(is_null($xml) || $xml == '')
			$xml = '<Notes></Notes>';
		$xml = XML::loadSimpleXML($xml);
		$new_note = $xml->addChild('Note');
		$new_note->DateTime = date('Y-m-d H:i:s');
		$new_note->CreatedBy = $_SESSION['user']->id;
		$new_note->NoteType = $note_type;
		if($note_type == 'programme_notes')
			$new_note->Note = $notes;
		else	
			$new_note->Comment = $notes;
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
