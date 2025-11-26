<?php
class save_import_learners implements IAction
{
	public function execute(PDO $link)
	{
		include('./lib/ProgressBar.php');
		// User has uploaded a public key
		$target_directory = "/admin_reports";
		$valid_extensions = array('csv');
		@unlink("../uploads/" . DB_NAME . "/admin_reports/" . $_FILES['uploadedfile']['name']);
		Repository::processFileUploads('uploadedfile', $target_directory, $valid_extensions);
		$time = date("Y-m-d H:i:s");
		$fileName = $_FILES['uploadedfile']['name'];
		$myFile = "../uploads/" . DB_NAME . "/admin_reports/" . $_FILES['uploadedfile']['name'];
		$csv = new CsvFileReader($myFile);

		$p1 = new ProgressBar();
		$p1->render("Please wait.......");
		$i = 1;
		$size = count(file($myFile)) - 1;
		if(DB_NAME=="am_doncaster" || DB_NAME=="am_donc_demo" || DB_NAME=="ams" || DB_NAME=="am_siemens")
		{
			foreach( $csv as $row)
			{
				$p1->setProgressBarProgress($i * 100 / $size, 'Reading file and storing records - Record ' . $i . '/' . $size);
				if(strtoupper($row[0])!="LEARNER REFERENCE NUMBER")
				{
					if(sizeof($row)==53)
					{
						if($row[1] == '')
							$row[1] = 0;
						$data = "(NULL,'" . addslashes((string)$row[0]) . "','" . addslashes((string)$row[1]) . "','" . addslashes((string)$row[2]) . "'"; //id, learner_ref_number, enrolment_no,uln

						$data .= ",'" . addslashes((string)$row[3]) . "'";// forename
						$data .= ",'" . addslashes((string)$row[4]) . "'";// middle_name
						$data .= ",'" . addslashes((string)$row[5]) . "'";// surname

						if($row[6]=='')// date of birth
							$data .= ",NULL";
						else
							$data .= ",'" . Date::toMySQL($row[6]) . "'";

						$data .= ",'" . addslashes((string)$row[7]) . "'"; // ni
						$data .= ",'" . addslashes((string)$row[8]) . "'";// prior_attain
						if($row[9] == '')
							$row[9] = 99;
						$data .= ",'" . addslashes((string)$row[9]) . "'";//eth
						$data .= ",'" . addslashes((string)$row[10]) . "'";//nationality
						$data .= ",'" . addslashes((string)$row[11]) . "'";//health_prob
						$data .= ",'" . addslashes((string)$row[12]) . "'";//diff
						$data .= ",'" . addslashes((string)$row[13]) . "'";//dis
						$data .= ",'" . addslashes((string)$row[14]) . "'";//mob
						$data .= ",'" . addslashes((string)$row[15]) . "'";//tel
						$data .= ",'" . addslashes((string)$row[16]) . "'";//add1
						$data .= ",'" . addslashes((string)$row[17]) . "'";//add2
						$data .= ",'" . addslashes((string)$row[18]) . "'";//add3
						$data .= ",'" . addslashes((string)$row[19]) . "'";//add4
						$data .= ",'" . addslashes((string)$row[20]) . "'";//postcode
						$data .= ",'" . addslashes((string)$row[21]) . "'";//type
						$data .= ",'" . addslashes((string)$row[22]) . "'";//pathway
						$data .= ",'" . addslashes((string)$row[23]) . "'";//aim_ref
						$data .= ",'" . addslashes((string)$row[24]) . "'";//title
						if($row[25]=='')//start_date
							$data .= ",NULL";
						else
							$data .= ",'" . Date::toMySQL($row[25]) . "'";
						if($row[26]=='')//planned end date
							$data .= ",NULL";
						else
							$data .= ",'" . Date::toMySQL($row[26]) . "'";
						if($row[27]=='')//actual end date
							$data .= ",NULL";
						else
							$data .= ",'" . Date::toMySQL($row[27]) . "'";

						$data .= ",'" . addslashes((string)$row[28]) . "'";//comp status
						$data .= ",'" . addslashes((string)$row[29]) . "'";//outcome
						if($row[30]=='')//achievement date
							$data .= ",NULL";
						else
							$data .= ",'" . Date::toMySQL($row[30]) . "'";
						$data .= ",'" . addslashes((string)$row[31]) . "'";//course code
						$data .= ",'" . addslashes((string)$row[32]) . "'";//framework code
						$data .= ",'" . addslashes((string)$row[33]) . "'";//assessor
						$data .= ",'" . addslashes((string)$row[34]) . "'";//edrs
						$data .= ",'" . addslashes((string)$row[35]) . "'";//curr area
						$data .= ",'" . addslashes((string)$row[36]) . "'";//emp name
						$data .= ",'" . addslashes((string)$row[37]) . "'";//add1
						$data .= ",'" . addslashes((string)$row[38]) . "'";//add2
						$data .= ",'" . addslashes((string)$row[39]) . "'";//add3
						$data .= ",'" . addslashes((string)$row[40]) . "'";//add4
						$data .= ",'" . addslashes((string)$row[41]) . "'";//town
						$data .= ",'" . addslashes((string)$row[42]) . "'";//postcpode
						$data .= ",'" . addslashes((string)$row[43]) . "'";//tel
						if($row[44]=='')//hs expiry
							$data .= ",NULL";
						else
							$data .= ",'" . Date::toMySQL($row[44]) . "'";
						$data .= ",'" . addslashes((string)$row[45]) . "'";//contact name
						if($row[46]=='')// last visit
							$data .= ",NULL";
						else
							$data .= ",'" . Date::toMySQL($row[46]) . "'";
						$data .= ",'" . addslashes((string)$row[47]) . "'";//comly
						$data .= ",'" . addslashes((string)$row[48]) . "'";//risk
						$data .= ",'" . addslashes((string)$row[49]) . "'";//pre enrol
						$data .= ",'" . addslashes((string)$row[50]) . "'";//gender
						$data .= ",'" . addslashes((string)$row[51]) . "'";//provider
						//$data .= ",'" . addslashes((string)$row[52]) . "'";//destination
						$data .= ",''";//destination
						$data .= ",'" . $time . "'";
						$data .= ",'" . "" .  "'";
						$data .= ",'". $fileName . "')";
						$sql = <<<HEREDOC
					INSERT INTO exg
            (`id`,
             `learnrefnumber`,
             `enrolment`,
             `uln`,
             `firstnames`,
             `middlename`,
             `surname`,
             `dob`,
             `ninumber`,
             `priorattain`,
             `ethnicity`,
             `nationality`,
             `healthproblems`,
             `learningdifficulty`,
             `disability`,
             `mobile`,
             `telephone`,
             `add1`,
             `add2`,
             `add3`,
             `add4`,
             `postcode`,
             `actual_prog_route`,
             `app_pathway`,
             `learnaimref`,
             `title`,
             `learnstartdate`,
             `plannedenddate`,
             `actualenddate`,
             `compstatus`,
             `outcome`,
             `achievementdate`,
             `coursecode`,
             `fworkcode`,
             `assessor`,
             `edrs`,
             `curriculum`,
             `employer`,
             `empadd1`,
             `empadd2`,
             `empadd3`,
             `empadd4`,
             `town`,
             `emppostcode`,
             `emptel`,
             `hsexpiry`,
             `contactname`,
             `lastvisit`,
             `hsstatus`,
             `risk`,
             `preemployment`,
             `gender`,
             `provider`,
             `destination`,
             `upload_time`,
             `status`,
             `filename`
)
VALUES
$data
HEREDOC;

						DAO::execute($link, $sql);
					}
					else
					{
						pre(sizeof($row));
						$internal_id = $row[0];
						DAO::execute($this->connection, "INSERT INTO exg(id,internal_id,status,upload_time,filename) VALUES (NULL,$internal_id,'Record Length Error','$time','$fileName')");
					}
				}
				$i++;
			}
		}
		elseif(DB_NAME!="am_pera")
		{
			foreach( $csv as $row)
			{
				if(strtoupper($row[1])!="EDRS")
				{
					if(sizeof($row)==58)
					{
						$data = "(NULL,'" . addslashes((string)$row[0]) . "','" . addslashes((string)$row[1]) . "','" . addslashes((string)$row[2]) . "','" . addslashes((string)$row[3]) . "','" . addslashes((string)$row[4]) . "','" . addslashes((string)$row[5]) . "','" . addslashes((string)$row[6]) . "',";
						if($row[7]=='')
							$data .= "NULL,'";
						else
							$data .= "'" . Date::toMySQL($row[7]) . "','";
						$data .= addslashes((string)$row[8]) . "','" . str_replace(" ","",addslashes((string)$row[9])) . "','" . addslashes((string)$row[10]) . "','" . addslashes((string)$row[11]) . "','" . addslashes((string)$row[12]) . "','" . addslashes((string)$row[13]) . "','";
						$data .= addslashes((string)$row[14]) . "','" . "XF" . "','" . addslashes((string)$row[15]) . "','" . addslashes((string)$row[16]) . "','" . addslashes((string)$row[17]) . "','" . addslashes((string)$row[18]) . "','" . addslashes((string)$row[19]) . "','";
						$data .= addslashes((string)$row[20]) . "','" . addslashes((string)$row[21]) . "','" . addslashes((string)$row[22]) . "','" . addslashes((string)$row[23]) . "','" . addslashes((string)$row[24]) . "','" . addslashes((string)$row[25]) . "','";
						$data .= addslashes((string)$row[26]) . "','" . addslashes((string)$row[27]) . "','" . addslashes((string)$row[28]) . "','";
						$data .= addslashes((string)$row[29]) . "','" . addslashes((string)$row[30]) . "','" . addslashes((string)$row[31]) . "','" . addslashes((string)$row[32]) . "','";
						$data .= addslashes((string)$row[33]) . "','" . addslashes((string)$row[34]) . "','" . addslashes((string)$row[35]) . "','" . addslashes((string)$row[36]) . "','" . addslashes((string)$row[37]) . "','";
						$data .= addslashes((string)$row[38]) . "','" . addslashes((string)$row[39]) . "',";
						if($row[40]=='')
							$data .= "NULL,";
						else
							$data .= "'" . Date::toMySQL($row[40]) . "',";
						if($row[41]=='')
							$data .= "NULL,";
						else
							$data .= "'" . Date::toMySQL($row[41]) . "','";
						$data .= addslashes((string)$row[42]) . "','" . "TES" . "','" . addslashes((string)$row[43]) . "','"  . ""  . "','" . addslashes((string)$row[44]) . "','"  . "" .  "',";
						if($row[45]=='')
							$data .= "NULL,'";
						else
							$data .= "'" . Date::toMySQL($row[45]) . "','";
						$data .= addslashes((string)$row[46]) . "','" . addslashes((string)$row[47]) . "','" . addslashes((string)$row[50]) . "',";
						if($row[48]=='')
							$data .= "NULL,'";
						else
							$data .= "'" . Date::toMySQL($row[48]) . "','";
						$data .= "" . "','" . $time . "','";
						$status =  "";
						$data .= $status . "','". $fileName . "','" . (int) $row[49] . "','" . (int) $row[57] . "')";

						DAO::execute($link, "insert into exg values " . $data);
					}
					else
					{
						pre(sizeof($row));
						$internal_id = $row[0];
						DAO::execute($this->connection, "INSERT INTO exg(id,internal_id,status,upload_time,filename) VALUES (NULL,$internal_id,'Record Length Error','$time','$fileName')");
					}
				}
			}
		}
		else
		{
			foreach( $csv as $row)
			{
				if($row[0]!="EmployerEDRS")
				{
					if($row[0]=="EmployerEDRS")
						continue;
					if(sizeof($row)==29)
					{
						$data = '(NULL,'; // id
						$data .= '"' . addslashes((string)$row[0]) . '",'; // edrs
						$data .= '"' . addslashes((string)$row[1]) . '",';// firstnames
						$data .= '"' . addslashes((string)$row[2]) . '",';// surname
						$data .= '"' . addslashes((string)$row[3]) . '",';// job_role
						$data .= '"' . addslashes((string)$row[4]) . '",';// gender
						$data .= '"' . addslashes((string)$row[5]) . '",';// ethnicity
						if($row[6]=='')                         // dob
							$data .= "NULL,";
						else
							$data .= '"' . Date::toMySQL($row[6]) . '",';
						$data .= '"' . addslashes((string)$row[7]) . '",';// enrolment
						$data .= '"' . addslashes((string)$row[8]) . '",';// ni
						$data .= '"' . addslashes((string)$row[9]) . '",';// uln
						$data .= '"' . addslashes((string)$row[10]) . '",';// diagnostic
						$data .= '"' . addslashes((string)$row[11]) . '",';// numeracy
						$data .= '"' . addslashes((string)$row[12]) . '",';// literacy
						$data .= '"' . addslashes((string)$row[13]) . '",';// esol
						$data .= '"' . addslashes((string)$row[14]) . '",';// domicile
						$data .= '"' . addslashes((string)$row[15]) . '",';// prior_attain
						$data .= '"' . addslashes((string)$row[16]) . '",';// health_prob
						$data .= '"' . addslashes((string)$row[17]) . '",';// disability
						$data .= '"' . addslashes((string)$row[18]) . '",';// learning_difficulty
						$data .= '"' . addslashes((string)$row[19]) . '",';// username
						$data .= '"' . addslashes((string)$row[20]) . '",';// add1
						$data .= '"' . addslashes((string)$row[21]) . '",';// add2
						$data .= '"' . addslashes((string)$row[22]) . '",';// add3
						$data .= '"' . addslashes((string)$row[23]) . '",';// add4
						$data .= '"' . addslashes((string)$row[24]) . '",';// postcode
						$data .= '"' . addslashes((string)$row[25]) . '",';// telephone
						$data .= '"' . addslashes((string)$row[26]) . '",';// mobile
						$data .= '"' . addslashes((string)$row[27]) . '",';// fax
						$data .= '"' . addslashes((string)$row[28]) . '",';// email
						$data .= '"' . $time . '",';
						$status =  '"';
						$data .= $status . '","'. $fileName . '")';

						//pre("insert into exg values " . $data);
						DAO::execute($link, "insert into exg values " . $data);
					}
					else
					{
						//pre(sizeof($row));
						$internal_id = $row[0];
						DAO::execute($this->connection, "INSERT INTO exg(id,internal_id,status,upload_time,filename) VALUES (NULL,$internal_id,'Record Length Error','$time','$fileName')");
					}
				}
			}
		}

		DAO::execute($link, "INSERT INTO data_imports (username, file_datetime) VALUES ('{$_SESSION['user']->username}', '{$time}')");
		//http_redirect('do.php?_action=view_uploads');
		$view = ViewUploads::getInstance();
		$view->refresh($link, $_REQUEST);

		require_once('tpl_view_uploads.php');
	}
}
?>