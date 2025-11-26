<?php
class save_op_session_register implements IAction
{
	public function execute(PDO $link)
	{
		//pre($_REQUEST);

		$session_id = isset($_REQUEST['session_id'])?$_REQUEST['session_id']:'';
		if($session_id == '')
			throw new Exception('Session id is blank');

		$send_fs_email_notification = false;

		// coordinator is only adding/updating notes for trainer
		if(isset($_REQUEST['validation']) && $_REQUEST['validation'] == 'Y')
		{
			$objSession = OperationsSession::loadFromDatabase($link, $session_id);

			if(isset($_REQUEST['status']) && $_REQUEST['status'] == 'S')
			{
				if($objSession->event_type == "EX")
				{
					foreach($objSession->entries AS $session_entry)
					{
						$obj = new stdClass();
						$obj->tr_id = $session_entry['entry_tr_id'];
						$obj->unit_ref = $session_entry['entry_exam_name'];
						$obj->code = $session_entry['entry_op_tracker_status'] == 'P' ? 'P' : 'R';
						$obj->comments = $session_entry['entry_mock_result'];
						$obj->created_by = $_SESSION['user']->id;
						$obj->register_id = $session_id;
						$obj->failed = $session_entry['entry_op_tracker_status'] == 'F' ? '1' : '0';

						DAO::saveObjectToTable($link, "op_tracker_unit_sch", $obj);

						FSProgress::updateFromSessionRegister($link, $session_entry);
					}
				}
				elseif(in_array($objSession->event_type, ["CRS", "WRK", "SUP"]))
				{
					foreach($objSession->entries AS $session_entry)
					{
						$unit_refs_in_session = explode(",", $objSession->unit_ref);
						foreach($unit_refs_in_session AS $u_ref)
						{
							$_chk = DAO::getSingleValue($link, "SELECT extractvalue(evidences, \"//unit[@op_title='".addslashes((string)$u_ref)."' and @track='true']/@title\") AS chk FROM framework_qualifications INNER JOIN student_frameworks ON framework_qualifications.framework_id = student_frameworks.id WHERE student_frameworks.tr_id = '" . $session_entry['entry_tr_id'] . "'  HAVING chk != ''");
							if($_chk != '')
							{
								$obj = new stdClass();
								$obj->tr_id = $session_entry['entry_tr_id'];
								$obj->unit_ref = $u_ref;
								$obj->code = $session_entry['entry_op_tracker_status'];
								$obj->comments = $session_entry['entry_mock_result'];
								$obj->created_by = $_SESSION['user']->id;
								$obj->register_id = $session_id;

								DAO::saveObjectToTable($link, "op_tracker_unit_sch", $obj);
							}
						}
					}
				}
				$send_fs_email_notification = true;
			}

			$objSession->comments = isset($_REQUEST['comments'])?$_REQUEST['comments']:'';
			$objSession->status = isset($_REQUEST['status'])?$_REQUEST['status']:'';
			$objSession->vm_shutdown = isset($_REQUEST['vm_shutdown'])?$_REQUEST['vm_shutdown']:'';
			$objSession->save($link);
		}
		else
		{
			DAO::transaction_start($link);
			try
			{
				$objSession = OperationsSession::loadFromDatabase($link, $session_id);
				$objSession->learner_of_week = isset($_REQUEST['learner_of_week'])?$_REQUEST['learner_of_week']:'';
				$objSession->status = isset($_REQUEST['status'])?$_REQUEST['status']:'';
				$objSession->vm_shutdown = isset($_REQUEST['vm_shutdown'])?$_REQUEST['vm_shutdown']:'';
				$objSession->save($link);

				foreach($_REQUEST AS $key => $value)
				{
					if($this->isJson($key))
					{
						$decoded_info = json_decode($key);

						$objSessionEntries = DAO::getObject($link, "SELECT * FROM session_entries WHERE session_entries.entry_id = '{$decoded_info->entry_id}'");
						$comments = isset($_REQUEST['entry_comments'.$decoded_info->entry_id])?$_REQUEST['entry_comments'.$decoded_info->entry_id]:' ';
						//if($comments != '')
						$objSessionEntries->entry_comments = $comments . ' ';
						$mock_result = isset($_REQUEST['entry_mock_result'.$decoded_info->entry_id])?$_REQUEST['entry_mock_result'.$decoded_info->entry_id]:'';
						//if($mock_result != '')
						$objSessionEntries->entry_mock_result = $mock_result;
						$ab_checked = isset($_REQUEST['entry_ab_checked'.$decoded_info->entry_id])?$_REQUEST['entry_ab_checked'.$decoded_info->entry_id]:'';
						if($ab_checked != '')
							$objSessionEntries->entry_ab_checked = $ab_checked;
						$c_id_check = isset($_REQUEST['entry_c_id_check'.$decoded_info->entry_id])?$_REQUEST['entry_c_id_check'.$decoded_info->entry_id]:'';
						if($c_id_check != '')
							$objSessionEntries->entry_c_id_check = $c_id_check;
						$t_id_check = isset($_REQUEST['entry_t_id_check'.$decoded_info->entry_id])?$_REQUEST['entry_t_id_check'.$decoded_info->entry_id]:'';
						if($t_id_check != '')
							$objSessionEntries->entry_t_id_check = $t_id_check;
						$entry_skilsure_check = isset($_REQUEST['entry_skilsure_check'.$decoded_info->entry_id])?$_REQUEST['entry_skilsure_check'.$decoded_info->entry_id]:'';
						if($entry_skilsure_check != '')
							$objSessionEntries->entry_skilsure_check = $entry_skilsure_check;
						$objSessionEntries->entry_op_tracker_status = isset($_REQUEST['entry_op_tracker_status'.$decoded_info->entry_id])?$_REQUEST['entry_op_tracker_status'.$decoded_info->entry_id]:'';
						$objSessionEntries->entry_mock_1 = isset($_REQUEST['entry_mock_1'.$decoded_info->entry_id])?$_REQUEST['entry_mock_1'.$decoded_info->entry_id]:'';
						$objSessionEntries->entry_mock_2 = isset($_REQUEST['entry_mock_2'.$decoded_info->entry_id])?$_REQUEST['entry_mock_2'.$decoded_info->entry_id]:'';
						$objSessionEntries->entry_mock_3 = isset($_REQUEST['entry_mock_3'.$decoded_info->entry_id])?$_REQUEST['entry_mock_3'.$decoded_info->entry_id]:'';
						$objSessionEntries->entry_mock_pass_fail = isset($_REQUEST['entry_mock_pass_fail'.$decoded_info->entry_id])?$_REQUEST['entry_mock_pass_fail'.$decoded_info->entry_id]:'';
						//$objSessionEntries->entry_vm_shutdown = isset($_REQUEST['entry_vm_shutdown'.$decoded_info->entry_id])?$_REQUEST['entry_vm_shutdown'.$decoded_info->entry_id]:'';
						$objSessionEntries->entry_learner_trainer = isset($_REQUEST['entry_learner_trainer'.$decoded_info->entry_id])?$_REQUEST['entry_learner_trainer'.$decoded_info->entry_id]:'';

						DAO::saveObjectToTable($link, 'session_entries', $objSessionEntries);

						$objSessionAttendance = new stdClass();
						$objSessionAttendance->session_entry_id = $objSessionEntries->entry_id;
						$objSessionAttendance->attendance_code = $value;
						$objSessionAttendance->attendance_date = $decoded_info->entry_date;
						$objSessionAttendance->attendance_day = $decoded_info->entry_day;

						DAO::saveObjectToTable($link, 'session_attendance', $objSessionAttendance);
					}
				}

				//$send_fs_email_notification = true;

				DAO::transaction_commit($link);
			}
			catch(Exception $e)
			{
				DAO::transaction_rollback($link, $e);
				throw new WrappedException($e);
			}
		}

		if( $send_fs_email_notification && isset($objSession->id) )
		{
			$unit_refs_in_session = explode(",", $objSession->unit_ref);
			$fs_units = [
				"Functional Skills English", "Functional Skills English Reading Test", "NCFE Level 2 Functional Skills Qualification in Mathematics", "NCFE Level 2 Functional Skills Qualification in Mathematics Test",
				"Functional Skills Writing Test", "SLC", "Functional Skills Mathematics", "Functional Skills Mathematics Test",
			];
			
			if( count(array_intersect($unit_refs_in_session, $fs_units)) > 0 )
			{
				$objSession->sendCompletionNotification($link);
			}
		}

		if(IS_AJAX)
		{
			header("Content-Type: text/plain");
			echo 'success';
		}
		else
		{
			http_redirect($_SESSION['bc']->getPrevious());
		}
	}

	private function isJson($string)
	{
		json_decode($string);
		return (json_last_error() == JSON_ERROR_NONE);
	}
}
?>
