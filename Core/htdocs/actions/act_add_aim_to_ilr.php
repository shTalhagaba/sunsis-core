<?php
class add_aim_to_ilr implements IAction
{
	public function execute(PDO $link)
	{
		$qualification_id = isset($_REQUEST['qualification_id'])?$_REQUEST['qualification_id']:'';
		$submission = isset($_REQUEST['submission'])?$_REQUEST['submission']:'';
		$contract_id = isset($_REQUEST['contract_id'])?$_REQUEST['contract_id']:'';
		$tr_id = isset($_REQUEST['tr_id'])?$_REQUEST['tr_id']:'';

		if($qualification_id=='' || $submission=='' || $contract_id=='' || $tr_id=='')
			throw new Exception("Required Data is missing");

		$training_record = TrainingRecord::loadFromDatabase($link, $tr_id);
		if(is_null($training_record))
			throw new Exception('Training Record Not Found.');

		$ilr = DAO::getSingleValue($link, "select ilr from ilr where tr_id = '$tr_id' and submission = '$submission' and contract_id = '$contract_id'");

		$start_date = DAO::getSingleValue($link, "select start_date from tr where id = '$tr_id'");
		$target_date = DAO::getSingleValue($link, "select target_date from tr where id = '$tr_id'");
		$start_date = Date::toShort($start_date);
		$target_date = Date::toShort($target_date);

		$contract_year = DAO::getSingleValue($link, "select contract_year from contracts where id = '$contract_id'");

		if($contract_year<2012)
		{
			// If main aim does not exists then add as main aim
			if(strpos($ilr, "<main>")===false)
			{
				$ilr = str_replace("</ilr>","",$ilr) . "<main>" . $this->createAim(str_replace("/","",$qualification_id), $start_date, $target_date) . "</main></ilr>";
				$ilr = addslashes((string)$ilr);
				$sql2 = "update ilr set ilr = '$ilr' where contract_id ='$contract_id' and tr_id = '$tr_id' and submission = '$submission'";
				DAO::execute($link, $sql2);
			}
			else
			{
				$ilr = str_replace("</ilr>","",$ilr) . "<subaim>" . $this->createAim(str_replace("/","",$qualification_id), $start_date, $target_date) . "</subaim></ilr>";
				$ilr = addslashes((string)$ilr);
				$sql2 = "update ilr set ilr = '$ilr' where contract_id ='$contract_id' and tr_id = '$tr_id' and submission = '$submission'";
				DAO::execute($link, $sql2);
			}
		}
		else
		{
			if(SOURCE_LOCAL || DB_NAME == "am_reed_demo" || DB_NAME == "am_reed")
			{
				$ld_count = DAO::getSingleValue($link, "SELECT extractvalue(ilr, 'COUNT(/Learner/LearningDelivery[LearnAimRef=\'$qualification_id\'])') AS ld FROM ilr WHERE tr_id = $tr_id AND contract_id = $contract_id AND submission = '$submission' ");
				if(isset($ld_count) && $ld_count == 0)
				{
					// File uploads
					$target_directory = $training_record->username;
					$valid_extensions = array('doc', 'docx', 'pdf');
					$max_file_upload = Repository::parseFileSize(ini_get("upload_max_filesize"));
					if(Repository::getRemainingSpace() < $max_file_upload){
						$max_file_upload = Repository::getRemainingSpace();
					}
					$file_name = str_replace('/', '', $qualification_id) . '_EvidenceOfStart';
					$r = Repository::processFileUploads('aim_evidence_upload', $target_directory, $valid_extensions, $max_file_upload, $file_name); // 6.0MB max
					$start_date = isset($_REQUEST['aim_start_date'])?$_REQUEST['aim_start_date']:'';
					$aim_planned_end_date = isset($_REQUEST['aim_planned_end_date'])?$_REQUEST['aim_planned_end_date']:'';
					if($start_date == '')
						throw new Exception('Blank Start Date Given');
					if($aim_planned_end_date == '')
						throw new Exception('Blank Planned End Date Given');
					DAO::execute($link, "UPDATE student_qualifications SET compstatus = '1', start_date = '" . Date::to($start_date, Date::MYSQL) . "', end_date = '" . Date::to($aim_planned_end_date, Date::MYSQL) . "' WHERE tr_id = '" . $tr_id . "' AND REPLACE(id, '/', '') = '" . str_replace('/', '', $qualification_id) . "'");
					$ilr = str_replace("</Learner>","",$ilr) . "<LearningDelivery>" . $this->createAimXML(str_replace("/","",$qualification_id), $start_date, $aim_planned_end_date, $ilr, $contract_id, $link) . "</LearningDelivery></Learner>";
					$ilr = addslashes((string)$ilr);
					$sql2 = "update ilr set ilr = '$ilr' where contract_id ='$contract_id' and tr_id = '$tr_id' and submission = '$submission'";
					DAO::execute($link, $sql2);
					DAO::execute($link, "INSERT INTO tr_files (tr_id, file_name, file_type, uploaded_by) VALUES ('" . $tr_id . "', '" . basename($r[0]) . "', '23', '" . $_SESSION['user']->id . "')");
					$participant = User::loadFromDatabase($link, $training_record->username);

					if($participant->participant_status == 2 || $participant->participant_status == 5 || $participant->participant_status == 4)
					{
						$current_status_desc = $participant->getParticipantStatusDesc();
						$participant->participant_status = 3; // move to 'in training'
						$participant->save($link);
						$note = new Note();
						$note->subject = "Record Edited";
						$note->note = "[Auto][Participant Status] changed from '{$current_status_desc}' to 'In Training'\n";
						$note->is_audit_note = true;
						$note->parent_table = 'users';
						$note->parent_id = $participant->id;
						$note->save($link);
					}

					$training_record->closure_date = '';
					$training_record->status_code = '1';
					$training_record->save($link);
				}
			}
			else
			{
				$ilr = str_replace("</Learner>","",$ilr) . "<LearningDelivery>" . $this->createAimXML(str_replace("/","",$qualification_id), $start_date, $target_date, $ilr, $contract_id, $link) . "</LearningDelivery></Learner>";
				$ilr = addslashes((string)$ilr);
				$sql2 = "update ilr set ilr = '$ilr' where contract_id ='$contract_id' and tr_id = '$tr_id' and submission = '$submission'";
				DAO::execute($link, $sql2);

                // Check if needs to add to 2015
                if($contract_year==2016)
                {
                    $start_date_date = new Date($start_date);
                    if($start_date_date->before('01/08/2016'))
                    {
                        $contract_id = DAO::getSingleValue($link, "select contract_id from ilr inner join contracts on contracts.id = ilr.contract_id where tr_id ='$tr_id' and contract_year=2015");
                        if($contract_id)
                        {
                            $submission="W13";
                            $ilr = DAO::getSingleValue($link, "select ilr from ilr where tr_id = '$tr_id' and submission = '$submission' and contract_id = '$contract_id'");
                            $ilr = str_replace("</Learner>","",$ilr) . "<LearningDelivery>" . $this->createAimXML(str_replace("/","",$qualification_id), $start_date, $target_date, $ilr, $contract_id, $link) . "</LearningDelivery></Learner>";
                            $ilr = addslashes((string)$ilr);
                            $sql2 = "update ilr set ilr = '$ilr' where contract_id ='$contract_id' and tr_id = '$tr_id' and submission = '$submission'";
                            DAO::execute($link, $sql2);
                        }
                    }
                }
			}
		}



		http_redirect('do.php?_action=read_training_record&id='.$tr_id);
	}

	function createAim($a09, $a27, $a28)
	{
		$ilr = "<A01></A01>";
		$ilr .= "<A02></A02>";	//	Contract/ Allocation Type
		$ilr .= "<A03></A03>";	//	Learner reference number
		$ilr .= "<A04>4</A04>";	//	Data set identifier code
		$ilr .= "<A05></A05>";	//	Learning aim data set sequence
		$ilr .= "<A07></A07>";	//	HE data sets
		$ilr .= "<A08></A08>";	//	Data set format
		$ilr .= "<A09>" . $a09 . "</A09>";	//	Learning aim reference
		$ilr .= "<A10></A10>";	//	LSC funding stream

		if(DB_NAME=='am_crackerjack')
		{
			$ilr .= "<A11a>105</A11a>";	//	Source of funding
			$ilr .= "<A11b>999</A11b>";	//	Source of funding
		}
		else
		{
			$ilr .= "<A11a></A11a>";	//	Source of funding
			$ilr .= "<A11b></A11b>";	//	Source of funding
		}

		$ilr .= "<A13></A13>";	//	Tuition fee received for year
		$ilr .= "<A14></A14>";	//	Reason for partial or full non-peyment of tuition fee
		$ilr .= "<A15></A15>";	//	Programme type
		$ilr .= "<A16></A16>";	//	Programme entry route
		$ilr .= "<A17></A17>";	//	Delivery mode
		$ilr .= "<A18></A18>";	//	Main delivery method
		$ilr .= "<A19></A19>";	//	Employer role
		$ilr .= "<A20></A20>";	//	Resit
		$ilr .= "<A21></A21>";	//	Franchised out and partnership arrangement
		$ilr .= "<A22></A22>";	//	Franchised out and partnership delivery provider number
		$ilr .= "<A23></A23>";	//	Delivery location postcode
		$ilr .= "<A26></A26>";	//	Sector framework of learning 
		$ilr .= "<A27>" . $a27 . "</A27>"; // Learning start date
		$ilr .= "<A28>" . $a28 . "</A28>"; // Learning planned end date
		$ilr .= "<A31></A31>"; // Learning actual end date
		$ilr .= "<A32></A32>";	//	Guided learning hours
		$ilr .= "<A34></A34>";	//	Completion status
		$ilr .= "<A35></A35>";	//	Learning outcome
		$ilr .= "<A36></A36>";	//	Learning outcome grade
		$ilr .= "<A40></A40>"; // Achivement date
		$ilr .= "<A44></A44>";	//	Employer identifier
		$ilr .= "<A45></A45>";	//	Workplace location postcode
		$ilr .= "<A46a>999</A46a>";	//	National learning aim monitoring
		$ilr .= "<A46b>999</A46b>";	//	National learning aim monitoring
		$ilr .= "<A47a></A47a>";	//	Local learning aim monitoring
		$ilr .= "<A47b></A47b>";	//	Local learning aim monitoring
		$ilr .= "<A48a></A48a>";	//	Provider specified learning aim data
		$ilr .= "<A48b></A48b>";	//	Provider specified learning aim data
		$ilr .= "<A49></A49>";	//	Special projects and pilots
		$ilr .= "<A50></A50>";	//	Reason learning ended
		$ilr .= "<A51a>100</A51a>";	//	Proportion of funding remaining
		$ilr .= "<A52></A52>";	//	Distance learning funding
		$ilr .= "<A53></A53>";	//	Additional learning needs
		$ilr .= "<A54></A54>";	//	Broker contract number
		$ilr .= "<A55></A55>";	//	Unique learner number
		$ilr .= "<A56></A56>";	//	UK Provider reference number
		$ilr .= "<A57></A57>";	//	Source of tuition fees
		$ilr .= "<A58></A58>";	//	Source of tuition fees
		$ilr .= "<A59></A59>";	//	Source of tuition fees
		$ilr .= "<A60></A60>";	//	Source of tuition fees
		$ilr .= "<A61></A61>";	//	Source of tuition fees
		$ilr .= "<A62></A62>";	//	Source of tuition fees
		$ilr .= "<A63>99</A63>";	//	Source of tuition fees
		$ilr .= "<A64></A64>";	//	Source of tuition fees
		$ilr .= "<A65></A65>";	//	Source of tuition fees
		$ilr .= "<A66></A66>";	//	Source of tuition fees
		$ilr .= "<A67></A67>";	//	Source of tuition fees
		$ilr .= "<A68></A68>";	//	Source of tuition fees
		$ilr .= "<A69></A69>";	//	Source of tuition fees
		if(DB_NAME=='am_crackerjack')
			$ilr .= "<A70>SFWM</A70>";	//	Source of tuition fees
		else
			$ilr .= "<A70></A70>";	//	Source of tuition fees
		return $ilr;
	}

	function createAimXML($a09, $a27, $a28, $ilr, $contract_id, $link)
	{
		$ilrtemplatetext = DAO::getSingleValue($link, "select template from contracts where id = '$contract_id'");
		if($ilrtemplatetext!='')
		{
			$ilrtemplate = Ilr2015::loadFromXML($ilrtemplatetext);
		}

		$ilr = Ilr2015::loadFromXML($ilr);
		$FundModel = "";
		$ContOrg = "";
		$ProgType = "";
		$FworkCode = "";
		$AimType = "";
		$DelLocPostCode = "";
		$MainDelMeth = "";
		foreach($ilr->LearningDelivery as $delivery)
		{
			$FundModel = "" . $delivery->FundModel;
			$ContOrg = "" . $delivery->ContOrg;
			$ProgType = "" . $delivery->ProgType;
			$FworkCode = "" . $delivery->FworkCode;
			$AimType = "".$delivery->AimType;
			$DelLocPostCode = "".$delivery->DelLocPostCode;
			$MainDelMeth = "".$delivery->MainDelMeth;
		}
		$ilr = "<LearnAimRef>" . $a09 . "</LearnAimRef>";
		$ilr .= "<AimType>" . $AimType. "</AimType>";
		$ilr .= "<LearnStartDate>" . Date::toMySQL($a27) . "</LearnStartDate>";
		$ilr .= "<LearnPlanEndDate>" . Date::toMySQL($a28) . "</LearnPlanEndDate>"; // Learning planned end date
		$ilr .= "<FundModel>" . $FundModel . "</FundModel>";
		$ilr .= "<ProgType>" . $ProgType . "</ProgType>";
		$ilr .= "<FworkCode>" . $FworkCode . "</FworkCode>";
		$ilr .= "<DelLocPostCode>" . $DelLocPostCode . "</DelLocPostCode>";
		$ilr .= "<MainDelMeth>" . $MainDelMeth . "</MainDelMeth>";
		$ilr .= "<LearningDeliveryFAM><LearnDelFAMType>SOF</LearnDelFAMType><LearnDelFAMCode>105</LearnDelFAMCode></LearningDeliveryFAM>";
		$ilr .= "<PropFundRemain>100</PropFundRemain>";
		if(isset($ilrtemplate))
		{
			$ilr .= "<ContOrg>" . $ilrtemplate->LearningDelivery->ContOrg . "</ContOrg>";
			$ilr .= "<ESFProjDosNumber>" . $ilrtemplate->LearningDelivery->ESFProjDosNumber . "</ESFProjDosNumber>";
			$ilr .= "<ESFLocProjNumber>" . $ilrtemplate->LearningDelivery->ESFLocProjNumber . "</ESFLocProjNumber>";
		}
		$ilr .= "<CompStatus>1</CompStatus>";

		return $ilr;
	}

	public static function getValueFromTemplate($ilr,$LearningAimRef,$Field)
	{
		if($ilr!='')
		{
			$ilr = Ilr2013::loadFromXML($ilr);
			foreach($ilr->LearningDelivery as $delivery)
			{
				if(("".$delivery->LearnAimRef) == $LearningAimRef || ("".$delivery->LearnAimRef)=='')
					return $delivery->$Field;
			}
		}
	}

	public static function getValueFromTemplate2($ilr,$LearningAimRef,$Field)
	{
		if($ilr!='')
		{
			$ilr = Ilr2013::loadFromXML($ilr);
			foreach($ilr->LearningDelivery as $delivery)
			{
				if(("".$delivery->LearnAimRef) == $LearningAimRef || ("".$delivery->LearnAimRef)=='')
					foreach($delivery->LearningDeliveryFAM as $ldf)
						if($ldf->LearnDelFAMType==$Field)
							return $ldf->LearnDelFAMCode;

			}
		}
	}

}
?>
