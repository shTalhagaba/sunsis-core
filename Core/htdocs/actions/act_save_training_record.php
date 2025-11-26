<?php
class save_training_record implements IAction
{
	public function execute(PDO $link)
	{
		$vo = new TrainingRecord();
		$vo->populate($_POST);
		
		if(isset($_POST['work_experience']))
			$vo->work_experience = 1;
		else
			$vo->work_experience = 0;

		$vo->prior_record = isset($_POST['prior_record']) ? $_POST['prior_record'] : 0;	
		$vo->amount_transfer_learner = isset($_POST['amount_transfer_learner']) ? $_POST['amount_transfer_learner'] : 0;	
		
		$user = User::loadFromDatabase($link, $vo->username); /* @var $user User */
		
		if(in_array(DB_NAME, ["am_baltic", "am_baltic_demo"]))
		{
			foreach(["ldd_condition", "ldd_mental", "ldd_physical"] AS $ldd_checklist)
			{
				$vo->$ldd_checklist = !isset($_REQUEST[$ldd_checklist]) ? [] : $_REQUEST[$ldd_checklist];
			}
		}

		DAO::transaction_start($link);
		try
		{
			// Check authorisation for editing this training record
			$acl = ACL::loadFromDatabase($link, 'trainingrecord', $vo->id);
//			if(!$acl->isAuthorised($_SESSION['user'], 'write'))
//			{
//				throw new UnauthorizedException();
//			}
			
			if($vo->id == '')
			{

				// Audit trail
				$note = new Note();
				$note->subject = "Document created";
				
				// Set default privileges for new widget (these can always be altered below)
				$acl->appendIdentities('read', $user->getFullyQualifiedName());
				$query = "SELECT * FROM users WHERE employer_id='$user->employer_id' and type='1'";
				$st = $link->query($query);	
				
				while($row = $st->fetch())
				{
					$user2 = User::loadFromDatabase($link, $row['username']);
					$acl->appendIdentities('read',$user2->getFullyQualifiedName());
					$acl->appendIdentities('write',$user2->getFullyQualifiedName());
				}
			}
			else
			{
				$existing_record = TrainingRecord::loadFromDatabase($link, $vo->id);
				$log_string = $existing_record->buildAuditLogString($link, $vo);

				if($log_string!='')
				{
					$note = new Note();
					$note->subject = "Document changed";
					$note->note = $log_string;
				}
			}

			$previous_contract_id = DAO::getSingleValue($link, "select contract_id from tr where id = $vo->id");
			if(DB_NAME == "am_baltic")
			{
				$vo->ad_lldd = substr($vo->ad_lldd, 0, 199);
				$vo->ad_arrangement_req = substr($vo->ad_arrangement_req, 0, 199);
				$vo->ad_arrangement_agr = substr($vo->ad_arrangement_agr, 0, 199);
			}

			if($vo->employer_id == '' && $vo->employer_location_id != '')
			{
				$vo->employer_id = DAO::getSingleValue($link, "SELECT locations.`organisations_id` FROM locations WHERE locations.id = '{$vo->employer_location_id}'");
			}
			if($vo->employer_id == '' && $vo->employer_location_id == '')
			{
				$vo->employer_id = $user->employer_id;
				$vo->employer_location_id = $user->employer_location_id;
			}

			if(in_array(DB_NAME, ["am_baltic", "am_baltic_demo"]))
			{
				// if assessor field is changed then record the inherited date
				$current_assessor = DAO::getSingleValue($link, "SELECT assessor FROM tr WHERE tr.id = '{$vo->id}'");
				if(isset($_POST['assessor']) && $current_assessor != $_POST['assessor'])
				{
					$vo->inherited_date = date('Y-m-d');
				}
			}

			$vo->save($link);
			if($previous_contract_id != $vo->contract_id)
			{
				DAO::execute($link, "update ilr set contract_id = $vo->contract_id where tr_id = $vo->id and contract_id = '$previous_contract_id'");
			}
			
			
			// Update the the corresponding learner record
			$userRecord = User::loadFromDatabase($link, $vo->username);
			if ($userRecord) {
				$values = new stdClass();
				$values->id = $userRecord->id;
				$values->firstnames = $vo->firstnames;
				$values->surname = $vo->surname;
				$values->gender = $vo->gender;
				$values->dob = $vo->dob;
				$values->ethnicity = $vo->ethnicity;
/*				$values->home_paon_start_number = $vo->home_paon_start_number;
				$values->home_paon_start_suffix = $vo->home_paon_start_suffix;
				$values->home_paon_end_number = $vo->home_paon_end_number;
				$values->home_paon_end_suffix = $vo->home_paon_end_suffix;
				$values->home_paon_description = $vo->home_paon_description;
				$values->home_street_description = $vo->home_street_description;
				$values->home_locality = $vo->home_locality;
				$values->home_town = $vo->home_town;
				$values->home_county = $vo->home_county;*/
				$values->home_address_line_1 = $vo->home_address_line_1;
				$values->home_address_line_2 = $vo->home_address_line_2;
				$values->home_address_line_3 = $vo->home_address_line_3;
				$values->home_address_line_4 = $vo->home_address_line_4;
				$values->home_postcode = $vo->home_postcode;
				$values->home_telephone = $vo->home_telephone;
				$values->home_mobile = $vo->home_mobile;
				$values->home_email = $vo->home_email;
				$values->employer_id = $vo->employer_id;
				$values->employer_location_id = $vo->employer_location_id;
				$values->work_address_line_1 = $vo->work_address_line_1;
				$values->work_address_line_2 = $vo->work_address_line_2;
				$values->work_address_line_3 = $vo->work_address_line_3;
				$values->work_address_line_4 = $vo->work_address_line_4;
				$values->work_postcode = $vo->work_postcode;
				$values->work_email = $vo->work_email;
				$values->work_telephone = $vo->work_telephone;
				$values->work_mobile = $vo->work_mobile;

				DAO::saveObjectToTable($link, "users", $values);
			}



/*			$sql = "select * from users where username = '$vo->username'";
			$st = $link->query($sql);
			if($st) 
			{
				while($row = $st->fetch())
				{
					$tr = User::loadFromDatabase($link, $row['username']);
					$tr->firstnames = $vo->firstnames;
					$tr->surname = $vo->surname;
					$tr->gender = $vo->gender;
					$tr->dob = $vo->dob;
					$tr->ethnicity = $vo->ethnicity;
					$tr->home_paon_start_number = $vo->home_paon_start_number;
					$tr->home_paon_start_suffix = $vo->home_paon_start_suffix;
					$tr->home_paon_end_number = $vo->home_paon_end_number;
					$tr->home_paon_end_suffix = $vo->home_paon_end_suffix;
					$tr->home_paon_description = $vo->home_paon_description;
					$tr->home_street_description = $vo->home_street_description;
					$tr->home_locality = $vo->home_locality;
					$tr->home_town = $vo->home_town;
					$tr->home_county = $vo->home_county;
					$tr->home_postcode = $vo->home_postcode;
					$tr->home_telephone = $vo->home_telephone;
					$tr->home_mobile = $vo->home_mobile;
					$tr->home_email = $vo->home_email;				
					$tr->save($link);				
				}		
			}
			else
			{
				throw new DatabaseException($link, $sql);
			}*/
			
			
			// Update the corresponding ILR
/*			$st = $link->query("SELECT * FROM ilr INNER JOIN contracts on contracts.id = ilr.contract_id where tr_id='$vo->id' AND submission = (SELECT submission FROM lookup_submission_dates WHERE last_submission_date>=CURDATE() ORDER BY last_submission_date LIMIT 1);");
			if($st) 
			{
				while($row = $st->fetch())
				{
					$submission = $row['submission'];
					$tr_id = $row['tr_id'];
					$contract_id = $row['contract_id'];
					$ilr = $row['ilr'];

					$pos1 = strpos($ilr,"<L10>");
					$pos2 = strpos($ilr,"</L10>");
					$search = substr($ilr,$pos1, ($pos2-$pos1+6));
					$replace = "<L10>" . $vo->firstnames . "</L10>";
					$ilr = str_replace($search, $replace, $ilr);

					$pos1 = strpos($ilr,"<L09>");
					$pos2 = strpos($ilr,"</L09>");
					$search = substr($ilr,$pos1, ($pos2-$pos1+6));
					$replace = "<L09>" . $vo->surname . "</L09>";
					$ilr = str_replace($search, $replace, $ilr);
					
					$pos1 = strpos($ilr,"<L13>");
					$pos2 = strpos($ilr,"</L13>");
					$search = substr($ilr,$pos1, ($pos2-$pos1+6));
					$replace = "<L13>" . $vo->gender . "</L13>";
					$ilr = str_replace($search, $replace, $ilr);

					$pos1 = strpos($ilr,"<L11>");
					$pos2 = strpos($ilr,"</L11>");
					$search = substr($ilr,$pos1, ($pos2-$pos1+6));
					$replace = "<L11>" . $vo->dob . "</L11>";
					$ilr = str_replace($search, $replace, $ilr);

					$pos1 = strpos($ilr,"<L12>");
					$pos2 = strpos($ilr,"</L12>");
					$search = substr($ilr,$pos1, ($pos2-$pos1+6));
					$replace = "<L12>" . $vo->ethnicity . "</L12>";
					$ilr = str_replace($search, $replace, $ilr);

					$pos1 = strpos($ilr,"<L22>");
					$pos2 = strpos($ilr,"</L22>");
					$search = substr($ilr,$pos1, ($pos2-$pos1+6));
					$replace = "<L22>" . $vo->home_postcode . "</L22>";
					$ilr = str_replace($search, $replace, $ilr);

					$pos1 = strpos($ilr,"<L15>");
					$pos2 = strpos($ilr,"</L15>");
					$search = substr($ilr,$pos1, ($pos2-$pos1+6));
					$replace = "<L15>" . $vo->disability . "</L15>";
					$ilr = str_replace($search, $replace, $ilr);

					$pos1 = strpos($ilr,"<L16>");
					$pos2 = strpos($ilr,"</L16>");
					$search = substr($ilr,$pos1, ($pos2-$pos1+6));
					$replace = "<L16>" . $vo->learning_difficulty . "</L16>";
					$ilr = str_replace($search, $replace, $ilr);
					
					$sqlup = "update ilr set ilr = '$ilr' where submission='$submission' and tr_id = $tr_id and contract_id = $contract_id";
					$stup = $link->query($sqlup);
				
				}
			}	
*/			

			if(isset($note) && !is_null($note))
			{
				$note->is_audit_note = true;
				$note->parent_table = 'tr';
				$note->parent_id = $vo->id;
				$note->save($link);
			}
			
		/*	$acl->resource_id = $vo->id;
			$acl->appendIdentities('read', $acl->readACLFormField($_POST, 'acl_read'));
			$acl->removeIdentities('read', $acl->readACLFormField($_POST, 'acl_read_not'));
			$acl->appendIdentities('write', $acl->readACLFormField($_POST, 'acl_write'));
			$acl->removeIdentities('write', $acl->readACLFormField($_POST, 'acl_write_not'));			
			$acl->save($link);
		*/
			if(isset($_REQUEST['enrollment_no']) && trim($_REQUEST['enrollment_no']) != '')
				DAO::execute($link, "UPDATE users SET users.enrollment_no = '" . $_REQUEST['enrollment_no'] . "' WHERE users.username = '" . $vo->username . "'");

			if(in_array(DB_NAME, ["am_baltic", "am_baltic_demo"]) && isset($_REQUEST['account_rel_manager']))
			{
				$course_id = DAO::getSingleValue($link, "SELECT courses_tr.course_id FROM courses_tr WHERE courses_tr.tr_id = '{$vo->id}'");

				$induction_fields = DAO::getObject($link, "SELECT induction.arm, induction.inductee_id FROM induction INNER JOIN inductees ON induction.`inductee_id` = inductees.`id` WHERE inductees.`linked_tr_id` = '{$vo->id}';");
				if(!isset($induction_fields->inductee_id))
				{
					$induction_fields = DAO::getObject($link, "SELECT induction.arm, induction.inductee_id FROM induction INNER JOIN inductees ON induction.`inductee_id` = inductees.`id` 
					INNER JOIN induction_programme ON inductees.`id` = induction_programme.`inductee_id` 
					WHERE inductees.`sunesis_username` = '{$vo->username}' AND induction_programme.`programme_id` = '{$course_id}'");
				}

				$account_rel_manager = isset( $induction_fields->account_rel_manager ) ? $induction_fields->account_rel_manager : '';
				if( $account_rel_manager == '')
				{
					$account_rel_manager = DAO::getSingleValue($link, "SELECT induction.arm FROM induction INNER JOIN inductees ON induction.`inductee_id` = inductees.`id` 
					INNER JOIN induction_programme ON inductees.`id` = induction_programme.`inductee_id` 
					WHERE inductees.`sunesis_username` = '{$vo->username}' AND induction_programme.`programme_id` = '{$course_id}';
					");
				}

				if( 
					(trim($_REQUEST['account_rel_manager']) != trim($account_rel_manager)) && 
					(isset($induction_fields->inductee_id) && $induction_fields->inductee_id != '' ) 
				)
				{
					DAO::execute($link, "UPDATE induction SET induction.arm = '{$_REQUEST['account_rel_manager']}' WHERE induction.inductee_id = '{$induction_fields->inductee_id}'");
				}
			}

			DAO::transaction_commit($link);
		}
		catch(Exception $e)
		{
			DAO::transaction_rollback($link, $e);
			throw new WrappedException($e);
		}
	//	

	
	
/*		$target_path = DATA_ROOT."/uploads/";
		
		
		if($_FILES['uploadedfile']['type']!='')
		{
			// Check if correct file type has been selected to upload i.e. gif, jpg, or png		
			$type = $_FILES['uploadedfile']['type'];
			if($type!='image/gif' && $type!='image/pjpeg' && $type!='image/png' && $type!='image/jpeg' && $type!='image/jpg')
				throw new Exception("Only jpg, gif and png files are allowed to upload not $type");		
			
			
			if(!(file_exists(DATA_ROOT."/uploads/".$vo->username)))
				mkdir($target_path."/".$vo->username);
			else
			{
				$this->delete_directory(DATA_ROOT."/uploads/".$vo->username);
				mkdir($target_path."/".$vo->username);
			}
				
			$target_path = DATA_ROOT."/uploads/".$vo->username."/" . basename( $_FILES['uploadedfile']['name']);
				
			if(!(move_uploaded_file($_FILES['uploadedfile']['tmp_name'], $target_path))) 
			    throw new Exception("There was an error uploading the file, please try again!");
		}
*/
		if(IS_AJAX)
		{
			header("Content-Type: text/plain");
			echo $vo->id;
		}
		else
		{
			http_redirect('do.php?_action=read_training_record&id=' . $vo->id);
		}
	}

	/*
	public function delete_directory($dirname) 
	{
		if (is_dir($dirname))
		$dir_handle = opendir($dirname);
		if (!$dir_handle)
			return false;
		while($file = readdir($dir_handle)) 
		{
			if ($file != "." && $file != "..") 
			{
				if (!is_dir($dirname."/".$file))
					unlink($dirname."/".$file);
				else
					delete_directory($dirname.'/'.$file);
			}
		}
		closedir($dir_handle);
		rmdir($dirname);
		return true;
	}
	*/
}
?>