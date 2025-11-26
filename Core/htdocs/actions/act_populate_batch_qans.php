<?php
class populate_batch_qans implements IAction
{
	public function execute(PDO $link)
	{
		$filename = $_FILES['uploadedfile']['tmp_name'];
		
		$f = $_FILES['uploadedfile']['name'];
		if(substr($f,-4)!='.csv' && substr($f,-4)!='.CSV')
			throw new Exception("The batch file must be a valid CSV file");
			
		$centres = array();
		$quals = array();
		$reports = array();
			
		$content = file_get_contents($filename);
		$handle = fopen($filename,"r");
		$rows = explode("\n",$content);		
		$report = array();	
		foreach($rows as $row)		
		{

			if($row!=='')
			{
				$arr = explode(",",$row);
			}
			else
			{
				require_once('tpl_batch_upload_report.php');
				break;				
			}

			$r = '';
			$centres[] = $arr[0];
			$quals[] = $arr[1];
			
			$centre = $arr[0];
			if($centre<1 || $centre>99999)
			{
				$r .= "\nInvalid centre number";
			}
			else
			{
				$centre = str_pad($centre,5,"0",STR_PAD_LEFT);
					
				$centre_exist = DAO::getSingleValue($link, "select count(*) from users where username = '$centre'");
				// Create centre first
				if($centre_exist==0)
				{
					$r .= "\nNew centre was created";
					// TODO Default administrator created for new centre has the same employer ID as the system administrator. Is this correct?
					$u = User::loadFromDatabase($link, 'admin');
					$u->id = null;
					$u->username = $centre;
					$u->firstnames = $centre;
					$u->surname = $centre;
					$u->password = 'pa55word';
					DAO::saveObjectToTable($link, 'users', $u);
					//DAO::execute($link, "INSERT INTO users (SELECT '$centre','$centre','$centre',employer_id,employer_location_id, department,job_role,'pa55word',pwd_sha1,record_status, web_access, dob, ni, uln, upn, upi, gender, ethnicity, acl_filters, acl_adopted_identities, public_key, public_key_type, use_x509_authentication, x509_serial, x509_validity_start, x509_validity_end, x509_subject_dn, x509_issuer_dn, x509_certificate, work_saon_start_number, work_saon_start_suffix, work_saon_end_number, work_saon_end_suffix, work_saon_description, work_paon_start_number, work_paon_start_suffix, work_paon_end_number, work_paon_end_suffix, work_paon_description, work_street_description, work_locality, work_town, work_county, work_postcode, work_telephone, work_mobile, work_fax, work_email, home_saon_start_number, home_saon_start_suffix, home_saon_end_number, home_saon_end_suffix, home_saon_description, home_paon_start_number, home_paon_start_suffix, home_paon_end_number, home_paon_end_suffix, home_paon_description, home_street_description, home_locality, home_town, home_county, home_postcode, home_telephone, home_fax, home_mobile, home_email, modified, created, TYPE, ifl, crb, bennett_test, enrollment_no, numeracy, literacy, esol, supervisor, l24, l14, l15, l16, l34a, l34b, l34c, l34d, l35, l36, l37, l28a, l28b, l39, l40a, l40b, l41a, l41b, l42a, l42b, l47, l48, l45, numeracy_diagnostic, literacy_diagnostic, esol_diagnostic FROM users WHERE username = 'admin')");
					DAO::execute($link, "insert into acl (resource_category, resource_id, privilege, ident) values('application',1,'administrator','$centre')");
				}
				else
				{
					$r.= "\nCentre alread exists";
				}
	
				if(isset($arr[1]))
				{	
					$qan = trim($arr[1]);
		
					$qual_exist = DAO::getSingleValue($link, "select count(*) from qualifications where clients = '$centre' and replace(id,'/','') = '$qan'");
		
					if($qual_exist==0)
					{

						$exist_in_cache = DAO::getSingleValue($link, "select count(*) from central.qualifications where replace(id,'/','') = '$qan'");
						if($exist_in_cache)
						{
							$r .= "\nQualification has been added for the centre";
							$sql2 = "INSERT INTO qualifications SELECT id, lsc_learning_aim, awarding_body, title, description, assessment_method, structure, LEVEL, qualification_type, regulation_start_date, operational_start_date, operational_end_date, certification_end_date, dfes_approval_start_date, dfes_approval_end_date, evidences, units, internaltitle, total_proportion, unitswithevidence, elements_without_evidence, units_required, mandatory_units, '$centre', mainarea, subarea, qual_status, guided_learning_hours FROM central.qualifications WHERE replace(id,'/','') = '$qan';";
							DAO::execute($link, $sql2);
						}
						else
						{
							// what if qual is not in cache
							// check if it belongs to any other awarding body
							$awarding_body = DAO::getSingleValue($link, "select AWARDING_BODY_CODE from lad201011.learning_aim where LEARNING_AIM_REF = '$qan'");
							if($awarding_body!='' && $awarding_body!='EDEXCEL')
								$r = "\nNot downloaded because this is a " . $awarding_body . " qualification.";
							else
								$r = "\nNot downloaded due to a technical issue";							
						}
					}
					else
					{
						$r .= "\n This qualification already exists";
					}
				}
			}
			
			$reports[] = $r;
		}
	}
}
?>