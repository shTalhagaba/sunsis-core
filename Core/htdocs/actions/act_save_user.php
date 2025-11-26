<?php
class save_user implements IAction
{
	public function execute(PDO $link)
	{
		$vo = new User();
		$vo->populate($_POST);

		if(isset($_POST['age_grant']))
			$vo->age_grant = 1;
		else
			$vo->age_grant = 0;


		// Basic validation in case the picture was too large
		// and PHP lost the POST data as a result
		if(!$vo->firstnames || !$vo->surname){
			$max_file_size = str_replace("&nbsp;", " ", Repository::formatFileSize(Repository::getMaxFileSize()));
			throw new Exception("Missing form data. Have you uploaded a picture over $max_file_size in size?");
		}

		if (is_array($vo->supervisor)) {
			$vo->supervisor = @implode(",",$vo->supervisor);
		}

		// Blank password field means no value, not blank string
		if ($vo->password === '') {
			$vo->password = NULL;
		}

		// relmes #179 { 0000000044 }
		//$vo->setUserAddresses($link, $vo->username, $_POST);

		DAO::transaction_start($link);
		try
		{
			if($vo->numeracy_diagnostic == 'on')
				$vo->numeracy_diagnostic = 1;
			else
				$vo->numeracy_diagnostic = 0;

			if($vo->literacy_diagnostic == 'on')
				$vo->literacy_diagnostic = 1;
			else
				$vo->literacy_diagnostic = 0;

			if($vo->ict_diagnostic == 'on')
				$vo->ict_diagnostic = 1;
			else
				$vo->ict_diagnostic = 0;

			if($vo->esol_diagnostic == 'on')
				$vo->esol_diagnostic = 1;
			else
				$vo->esol_diagnostic = 0;

			// Get a lock on all training records belonging to this user prior to saving/updating the user
			$tr_ids = DAO::getSingleColumn($link, "SELECT id FROM tr WHERE username = " . $link->quote($vo->username) . " FOR UPDATE");
			// Save/update the user
			if(empty($vo->id))
			{
				$newUser = 1;
				$vo->who_created = $_SESSION['user']->username;
				$vo->created = date('Y-m-d');
			}
			else
				$newUser = 0;

			if(!isset($_REQUEST['induction_menus']) || $vo->induction_access == '')
				$vo->induction_menus = '';
			if(!isset($_REQUEST['op_menus']) || $vo->op_access == '')
				$vo->op_menus = '';

			$vo->save($link, $newUser);

			// Update all training records belonging to this record
			$data = new stdClass();
			$data->firstnames = $vo->firstnames;
			$data->surname = $vo->surname;
			$data->gender = $vo->gender;
			$data->dob = $vo->dob;
			$data->ethnicity = $vo->ethnicity;
			$data->home_address_line_1 = $vo->home_address_line_1;
			$data->home_address_line_2 = $vo->home_address_line_2;
			$data->home_address_line_3 = $vo->home_address_line_3;
			$data->home_address_line_4 = $vo->home_address_line_4;
			$data->home_postcode = $vo->home_postcode;
			$data->home_telephone = $vo->home_telephone;
			$data->home_fax = $vo->home_fax;
			$data->uln = $vo->l45;
			$data->home_mobile = $vo->home_mobile;
			$data->home_email = $vo->home_email;
			$data->employer_id = $vo->employer_id;
			$data->employer_location_id = $vo->employer_location_id;
			$data->work_address_line_1 = $vo->work_address_line_1;
			$data->work_address_line_2 = $vo->work_address_line_2;
			$data->work_address_line_3 = $vo->work_address_line_3;
			$data->work_address_line_4 = $vo->work_address_line_4;
			$data->work_postcode = $vo->work_postcode;
			$data->work_telephone = $vo->work_telephone;
			$data->work_mobile = $vo->work_mobile;
			$data->work_fax = $vo->work_fax;
			$data->work_email = $vo->work_email;
			foreach ($tr_ids as $id) {
				$data->id = $id;
				DAO::saveObjectToTable($link, 'tr', $data);
			}

			// metadata saving
			$meta_data = new User();
			$meta_data->getUserMetaData($link);

			foreach ( $meta_data->user_metadata as $page => $field_array )
			{
				foreach ( $field_array as $title => $type )
				{
					$format_titles = explode("_", $title);
					if ( $format_titles[1] != "Multiple Addresses" )
					{

						$format_details = explode("_", $type);
						$format_db_column = 'string';
						switch( $format_details[1] )
						{
							case 'int':
								$format_db_column = 'int';
								break;
							case 'date':
								$format_db_column = 'date';
								break;
							default:
								$format_db_column = 'string';
						}

						$capture_title = 'meta_'.preg_replace("/ /i", "_", $format_titles[1]);

						if ( isset($_POST[$capture_title]) )
						{
							$field_value = $_POST[$capture_title];
							if ( $format_db_column != 'int' ) {
								$field_value = "'".addslashes((string)$field_value)."'";
							}

							// REPLACE INTO....
							$sql_user_metadata = "REPLACE INTO users_metadata ( userinfoid, username, ".$format_db_column."value ) VALUES (".$format_titles[0].", '".addslashes((string)$vo->username)."', ".$field_value.")";
							DAO::execute($link, $sql_user_metadata);
						}
					}
				}
			}

			// File uploads
			$target_directory = $vo->username.'/photos';
			$valid_extensions = array('gif', 'jpg', 'jpeg', 'png');
			$filepaths = Repository::processFileUploads('uploadedfile', $target_directory, $valid_extensions, 1024 * 100); // 100KB max
			if(count($filepaths) > 0){
				rename($filepaths[0], pathinfo($filepaths[0], PATHINFO_DIRNAME).'/profilePhoto.'.pathinfo($filepaths[0], PATHINFO_EXTENSION));
			}

			DAO::transaction_commit($link);
		}
		catch(Exception $e)
		{
			DAO::transaction_rollback($link, $e);
			throw new WrappedException($e);
		}


		// Right Futures Functionality
		$is_esf = (isset($_POST['is_esf']))?1:0;
		$course_id = (isset($_POST['course_id']))?$_POST['course_id']:'';
		$contract_id = (isset($_POST['contract_id']))?$_POST['contract_id']:'';
		$start_date = (isset($_POST['start_date']))?$_POST['start_date']:'';
		$end_date = (isset($_POST['end_date']))?$_POST['end_date']:'';
		if($end_date=='')$end_date=$start_date;
		$sd = Date::toMySQL($start_date);
		$ed = Date::toMySQL($end_date);
		if(isset($_POST['is_esf']))
		{
			DAO::transaction_start($link);
			try
			{
				$tr = new TrainingRecord();
				$tr->populate($vo, true);
				$tr->contract_id = $contract_id;
				$tr->start_date = $sd;
				$tr->target_date = $ed;
				$tr->status_code = 1;
				if($course_id!='')
				{
					$course = Course::loadFromDatabase($link, $course_id);
					$que = "select id from locations where organisations_id='$course->organisations_id'";
					$location_id = trim(DAO::getSingleValue($link, $que));

					$provider = Location::loadFromDatabase($link, $location_id);
					$tr->provider_id = $course->organisations_id;
					$tr->provider_location_id = $location_id;
					$tr->provider_address_line_1 = $provider->address_line_1;
					$tr->provider_address_line_2 = $provider->address_line_2;
					$tr->provider_address_line_3 = $provider->address_line_3;
					$tr->provider_address_line_4 = $provider->address_line_4;
					$tr->provider_postcode = $provider->postcode;
					$tr->provider_telephone = $provider->telephone;
				}
				$tr->ethnicity = $vo->ethnicity;
				$tr->work_experience = 0;
				$tr->l36 = 0;
				// Make it null so it does not uses users id and creates its own id
				$tr->id = NULL;
				$l03 = DAO::getSingleValue($link, "select l03 from tr where username = '$vo->username' limit 0,1");
				if($l03=='')
				{
					$l03 = (int)DAO::getSingleValue($link, "select max(l03) from tr where l03 + 0 <> 0 AND LENGTH(RTRIM(l03))=12");
					$l03 += 1;
					$tr->l03 = str_pad($l03,12,'0',STR_PAD_LEFT);
				}
				else
				{
					$l03 = str_pad($l03,12,'0',STR_PAD_LEFT);
					$tr->l03 = $l03;
				}

				$tr->save($link);

				if($course_id!='')
				{
					// enroling on a course
					$que = "select framework_id from courses where id='$course_id'";
					$framework_id = DAO::getSingleValue($link, $que);
					$query = "insert into courses_tr (course_id, tr_id, qualification_id, framework_id) values($course_id, $tr->id, '', $framework_id)";
					DAO::execute($link, $query);

					$query = "insert into student_frameworks select title, id, '$tr->id', framework_code, comments, duration_in_months from frameworks where id = '$framework_id'";
					DAO::execute($link, $query);

					// importing qualification from framework
					$query = <<<HEREDOC
insert into
	student_qualifications
(id,
framework_id,
tr_id,
internaltitle,
lsc_learning_aim,
awarding_body,
title,
description,
assessment_method,
structure,
level,
qualification_type,
accreditation_start_date,
operational_centre_start_date,
accreditation_end_date,
certification_end_date,
dfes_approval_start_date,
dfes_approval_end_date,
evidences,
units,
unitsCompleted,
unitsNotStarted,
unitsBehind,
unitsOnTrack,
unitsUnderAssessment,
unitsPercentage,
proportion,
aptitude,
attitude,
comments,
modified,
username,
trading_name,
auto_id,
start_date,
end_date,
actual_end_date,
achievement_date,
units_required,
awarding_body_reg,
awarding_body_date,
awarding_body_batch,
a14,
a18,
a51a,
a16,
certificate_applied,
certificate_received,
smart_assessor_id
)
select
id,
'$framework_id',
'$tr->id',
framework_qualifications.internaltitle,
lsc_learning_aim,
awarding_body,
title,
description,
assessment_method,
structure,
level,
qualification_type,
accreditation_start_date,
operational_centre_start_date,
accreditation_end_date,
certification_end_date,
dfes_approval_start_date,
dfes_approval_end_date,
evidences,
units,
'0',
'0',
'0',
'0',
'0',
units_required,
proportion,
0,
0,
0,
0,
0,
0,
0,
'$sd',
'$ed',
NULL,
NULL,
units_required,
NULL,
NULL,
NULL,
NULL,
NULL,
'100',
NULL,
'',
'',
''
from framework_qualifications
LEFT JOIN course_qualifications_dates on course_qualifications_dates.qualification_id = framework_qualifications.id and
course_qualifications_dates.framework_id = framework_qualifications.framework_id and
course_qualifications_dates.internaltitle = framework_qualifications.internaltitle
	where framework_qualifications.framework_id = '$framework_id' and course_qualifications_dates.course_id='$course_id';
HEREDOC;
					DAO::execute($link, $query);

					// Creating milestones
					$sql = "SELECT *, timestampdiff(MONTH, start_date, end_date) as months FROM student_qualifications where tr_id = $tr->id";
					$st = $link->query($sql);
					$unit=0;
					while($row = $st->fetch())
					{
						$xml = mb_convert_encoding($row['evidences'],'UTF-8');

						$pageDom = XML::loadXmlDom(mb_convert_encoding($xml,'UTF-8'));
						$evidences = $pageDom->getElementsByTagName('unit');
						foreach($evidences as $evidence)
						{
							$unit_id = $evidence->getAttribute('owner_reference');
							$tr_id = $row['tr_id'];
							$framework_id = $row['framework_id'];
							$qualification_id = $row['id'];
							$internaltitle = $row['internaltitle'];

							$m = Array();
							for($a = 1; $a<=$row['months']; $a++)
							{
								if($a==$row['months'])
									$m[] = 100;
								else
									$m[] = sprintf("%.1f", 100 / $row['months'] * $a);
							}
							for($a = $row['months']+1; $a<=36; $a++)
							{
								$m[] = 100;
							}
							$internaltitle = addslashes((string)$internaltitle);
							DAO::execute($link, "insert into student_milestones (framework_id, qualification_id, internaltitle, unit_id, month_1, month_2, month_3, month_4, month_5, month_6, month_7, month_8, month_9, month_10, month_11, month_12, month_13, month_14, month_15, month_16, month_17, month_18, month_19, month_20, month_21, month_22, month_23, month_24, month_25, month_26, month_27, month_28, month_29, month_30, month_31, month_32, month_33, month_34, month_35, month_36, id, tr_id, chosen) values($framework_id, '$qualification_id', '$internaltitle', '$unit_id', $m[0], $m[1], $m[2], $m[3], $m[4], $m[5], $m[6], $m[7], $m[8], $m[9], $m[10], $m[11], $m[12], $m[13], $m[14], $m[15], $m[16], $m[17], $m[18], $m[19], $m[20], $m[21], $m[22], $m[23], $m[24], $m[25], $m[26], $m[27], $m[28], $m[29], $m[30], $m[31], $m[32], $m[33], $m[34], $m[35], 1, $tr->id, 1)");
						}
					}


					$contract_year = DAO::getSingleValue($link,"select contract_year from contracts where id='$contract_id'");
					$co = Contract::loadFromDatabase($link, $contract_id);
					$submission = DAO::getSingleValue($link, "select submission from central.lookup_submission_dates where last_submission_date>=CURDATE() and contract_year = '$contract_year' and contract_type = '$co->funding_body' order by last_submission_date LIMIT 1;");
					$ilrtemplatetext = DAO::getSingleValue($link, "select template from contracts where id = '$contract_id'");
					$framework_code = DAO::getSingleValue($link, "SELECT framework_code FROM student_frameworks LEFT JOIN frameworks ON frameworks.id = student_frameworks.id WHERE tr_id = '$tr->id';");
					if($contract_year < 2012)
					{

						if($ilrtemplatetext!='')
						{
							$ilrtemplate = Ilr2011::loadFromXML($ilrtemplatetext);
						}

						//$sql = "SELECT users.ni, users.l45, users.l24, users.l14, users.l15, users.l16, users.l35, users.l48, users.l42a, users.l42b, contract_holder.upin, users.uln, users.home_email, l03, tr.l28a, tr.l28b, tr.l34a, tr.l34b, tr.l34c, tr.l34d, tr.l36, tr.l37, tr.l39, tr.l40a, tr.l40b, tr.l41a, tr.l41b, tr.l47, tr.id, tr.surname, tr.firstnames, DATE_FORMAT(tr.dob,'%d/%m/%Y') AS date_of_birth, DATE_FORMAT(closure_date, '%d/%m/%Y') AS closure_date, tr.ethnicity, tr.gender, learning_difficulties, disability,learning_difficulty, tr.home_postcode, TRIM(CONCAT(IF(tr.home_paon_start_number IS NOT NULL,TRIM(tr.home_paon_start_number),''),IF(tr.home_paon_start_suffix IS NOT NULL,TRIM(tr.home_paon_start_suffix),''),' ', IF(tr.home_paon_end_number IS NOT NULL,TRIM(tr.home_paon_end_number),''),IF(tr.home_paon_end_suffix IS NOT NULL,TRIM(tr.home_paon_end_suffix),''),' ' , IF(tr.home_paon_description IS NOT NULL,TRIM(tr.home_paon_description),''),' ',IF(tr.home_street_description IS NOT NULL,TRIM(tr.home_street_description),''))) AS L18, tr.home_locality, tr.home_town,tr.home_county, current_postcode, tr.home_telephone, country_of_domicile, tr.ni, prior_attainment_level,DATE_FORMAT(tr.start_date,'%d/%m/%Y') AS start_date, DATE_FORMAT(target_date,'%d/%m/%Y') AS target_date, status_code,provider_location_id, tr.employer_id, provider_location.postcode AS lpcode, organisations.edrs AS edrs, organisations.legal_name AS employer_name,employer_location.postcode AS epcode FROM tr LEFT JOIN locations AS provider_location ON provider_location.id = tr.provider_location_id LEFT JOIN locations AS employer_location ON employer_location.id = tr.employer_location_id LEFT JOIN organisations ON organisations.id = tr.employer_id 	LEFT JOIN contracts ON contracts.id = tr.contract_id LEFT JOIN organisations AS contract_holder ON contract_holder.id = contract_holder LEFT JOIN users ON users.username = tr.username WHERE contract_id = '$contract_id' AND tr.id = '$tr->id';";
						$sql = <<<SQL
SELECT
  users.ni,
  users.l45,
  users.l24,
  users.l14,
  users.l15,
  users.l16,
  users.l35,
  users.l48,
  users.l42a,
  users.l42b,
  contract_holder.upin,
  users.uln,
  users.home_email,
  l03,
  tr.l28a,
  tr.l28b,
  tr.l34a,
  tr.l34b,
  tr.l34c,
  tr.l34d,
  tr.l36,
  tr.l37,
  tr.l39,
  tr.l40a,
  tr.l40b,
  tr.l41a,
  tr.l41b,
  tr.l47,
  tr.id,
  tr.surname,
  tr.firstnames,
  DATE_FORMAT(tr.dob, '%d/%m/%Y') AS date_of_birth,
  DATE_FORMAT(closure_date, '%d/%m/%Y') AS closure_date,
  tr.ethnicity,
  tr.gender,
  learning_difficulties,
  disability,
  learning_difficulty,
  tr.home_address_line_1,
  tr.home_address_line_2,
  tr.home_address_line_3,
  tr.home_address_line_4,
  tr.home_postcode,
  current_postcode,
  tr.home_telephone,
  country_of_domicile,
  tr.ni,
  prior_attainment_level,
  DATE_FORMAT(tr.start_date, '%d/%m/%Y') AS start_date,
  DATE_FORMAT(target_date, '%d/%m/%Y') AS target_date,
  status_code,
  provider_location_id,
  tr.employer_id,
  provider_location.postcode AS lpcode,
  organisations.edrs AS edrs,
  organisations.legal_name AS employer_name,
  employer_location.postcode AS epcode
FROM
  tr
  LEFT JOIN locations AS provider_location
    ON provider_location.id = tr.provider_location_id
  LEFT JOIN locations AS employer_location
    ON employer_location.id = tr.employer_location_id
  LEFT JOIN organisations
    ON organisations.id = tr.employer_id
  LEFT JOIN contracts
    ON contracts.id = tr.contract_id
  LEFT JOIN organisations AS contract_holder
    ON contract_holder.id = contract_holder
  LEFT JOIN users
    ON users.username = tr.username
WHERE contract_id = '$contract_id'
  AND tr.id = '$tr->id'
SQL;

						$st = $link->query($sql);
						if($st)
						{

							while($row = $st->fetch())
							{
								// here to create ilrs for the first time from training records.
								$xml = '<ilr>';
								$xml .= "<learner>";
								$xml .= "<L01>" . $row['upin'] . "</L01>";
								$xml .= "<L02>" . "00" . "</L02>";
								$xml .= "<L03>" . $l03 . "</L03>";
								$xml .= "<L04>" . "10" . "</L04>";

								// No of learning aim data sets
								$sql ="select COUNT(*) from student_qualifications where tr_id ={$row['id']}";
								$learning_aims = DAO::getResultset($link,$sql);

								$xml .= "<L05>" . $learning_aims[0][0] . "</L05>";
								$xml .= "<L06>" . "00" . "</L06>";
								$xml .= "<L07>" . "00" . "</L07>";
								if($row['status_code']==4 || $row['status_code']=='4')
									$xml .= "<L08>" . "Y" . "</L08>";
								else
									$xml .= "<L08>" . "N" . "</L08>";
								$xml .= "<L09>" . $row['surname'] . 				"</L09>";
								$xml .= "<L10>" . $row['firstnames'] . 				"</L10>";
								$xml .= "<L11>" . $row['date_of_birth'] . 			"</L11>";
								$xml .= "<L12>" . $row['ethnicity'] . 				"</L12>";
								$xml .= "<L13>" . $row['gender'] . 					"</L13>";

								if(!isset($row['l14']) && isset($ilrtemplate->learnerinformation->L14))
									$xml .= "<L14>" . $ilrtemplate->learnerinformation->L14 . "</L14>";
								else
									$xml .= "<L14>" . $row['l14'] .	"</L14>";

								if(!isset($row['l15']) && isset($ilrtemplate->learnerinformation->L15))
									$xml .= "<L15>" . $ilrtemplate->learnerinformation->L15 . "</L15>";
								else
									$xml .= "<L15>" . $row['l15'] . 				"</L15>";

								if(!isset($row['l16']) && isset($ilrtemplate->learnerinformation->L16))
									$xml .= "<L16>" . $ilrtemplate->learnerinformation->L16 . "</L16>";
								else
									$xml .= "<L16>" . $row['l16'] .		"</L16>";

								$xml .= "<L18>" . $row['home_address_line_1'] . "</L18>";
								$xml .= "<L19>" . $row['home_address_line_2'] . 			"</L19>";
								$xml .= "<L20>" . $row['home_address_line_3'] . 				"</L20>";
								$xml .= "<L21>" . $row['home_address_line_4'] . 			"</L21>";
								$xml .= "<L22>" . $row['home_postcode'] .		"</L22>";
								$xml .= "<L23>" . $row['home_telephone'] . 			"</L23>";

								if(!isset($row['l24']) && isset($ilrtemplate->learnerinformation->L24))
									$xml .= "<L24>" . $ilrtemplate->learnerinformation->L24 . "</L24>";
								else
									$xml .= "<L24>" . $row['l24'] .		"</L24>";

								$xml .= "<L25>" . "</L25>";
								$xml .= "<L26>" . $row['ni'] . 						"</L26>";
								$xml .= "<L27>" . "1" . "</L27>";

								if(!isset($row['l28a']) && isset($ilrtemplate->learnerinformation->L28a))
									$xml .= "<L28a>" . $ilrtemplate->learnerinformation->L28a . "</L28a>";
								else
									$xml .= "<L28a>" . $row['l28a'] . "</L28a>";

								if(!isset($row['l28b']) && isset($ilrtemplate->learnerinformation->L28b))
									$xml .= "<L28b>" . $ilrtemplate->learnerinformation->L28b . "</L28b>";
								else
									$xml .= "<L28b>" . $row['l28b'] . "</L28b>";

								$xml .= "<L29>" . "00" . "</L29>";
								$xml .= "<L31>" . "000000" . "</L31>";
								$xml .= "<L32>" . "00" . "</L32>";
								$xml .= "<L33>" . "0.0000" . "</L33>";

								if(!isset($row['l34a']) && isset($ilrtemplate->learnerinformation->L34a))
									$xml .= "<L34a>" . $ilrtemplate->learnerinformation->L34a . "</L34a>";
								else
									$xml .= "<L34a>" . $row['l34a'] . "</L34a>";

								if(!isset($row['l34b']) && isset($ilrtemplate->learnerinformation->L34b))
									$xml .= "<L34b>" . $ilrtemplate->learnerinformation->L34b . "</L34b>";
								else
									$xml .= "<L34b>" . $row['l34b'] . "</L34b>";

								if(!isset($row['l34c']) && isset($ilrtemplate->learnerinformation->L34c))
									$xml .= "<L34c>" . $ilrtemplate->learnerinformation->L34c . "</L34c>";
								else
									$xml .= "<L34c>" . $row['l34c'] . "</L34c>";

								if(!isset($row['l34d']) && isset($ilrtemplate->learnerinformation->L34d))
									$xml .= "<L34d>" . $ilrtemplate->learnerinformation->L34d . "</L34d>";
								else
									$xml .= "<L34d>" . $row['l34d'] . "</L34d>";

								$xml .= "<L35>" . $row['l35'] .	"</L35>";
								$xml .= "<L36>" . '' . "</L36>";

								if(!isset($row['l37']) && isset($ilrtemplate->learnerinformation->L37))
									$xml .= "<L37>" . $ilrtemplate->learnerinformation->L37 . "</L37>";
								else
									$xml .= "<L37>" . $row['l37'] . "</L37>";

								$xml .= "<L38>" . "00" . "</L38>";

								if(!isset($row['l39']) && isset($ilrtemplate->learnerinformation->L39))
									$xml .= "<L39>" . $ilrtemplate->learnerinformation->L39 . "</L39>";
								else
									$xml .= "<L39>" . $row['l39'] . "</L39>";

								if(!isset($row['l40a']) && isset($ilrtemplate->learnerinformation->L40a))
									$xml .= "<L40a>" . $ilrtemplate->learnerinformation->L40a . "</L40a>";
								else
									$xml .= "<L40a>" . $row['l40a'] . "</L40a>";

								if(!isset($row['l40b']) && isset($ilrtemplate->learnerinformation->L40b))
									$xml .= "<L40b>" . $ilrtemplate->learnerinformation->L40b . "</L40b>";
								else
									$xml .= "<L40b>" . $row['l40b'] . "</L40b>";

								$xml .= "<L41a>" . $row['l41a'] . "</L41a>";
								$xml .= "<L41b>" . $row['l41b'] . "</L41b>";
								$xml .= "<L42a>" . $row['l42a'] . "</L42a>";
								$xml .= "<L42b>" . $row['l42b'] . "</L42b>";
								$xml .= "<L44>" . "</L44>";
								if($row['l45']!='')
									$xml .= "<L45>" . $row['l45'] . "</L45>";
								else
									$xml .= "<L45>9999999999</L45>";
								$xml .= "<L46>" . "</L46>";

								if(!isset($row['l47']) && isset($ilrtemplate->learnerinformation->L47))
									$xml .= "<L47>" . $ilrtemplate->learnerinformation->L47 . "</L47>";
								else
									$xml .= "<L47>" . $row['l47'] . "</L47>";

								$xml .= "<L48>" .  "</L48>";
								$xml .= "<L49a>00</L49a>";
								$xml .= "<L49b>00</L49b>";
								$xml .= "<L49c>00</L49c>";
								$xml .= "<L49d>00</L49d>";
								$xml .= "<L51>".$row['home_email']."</L51>";
								$xml .= "<L17>" . $row['home_postcode'] . 			"</L17>";

								// Getting no. of sub aims
								$sql ="select count(*) from student_qualifications where tr_id ={$row['id']} and qualification_type!='NVQ'";
								$sub_aims = DAO::getSingleValue($link,$sql);

								$xml .= "<subaims>" . $sub_aims . "</subaims>";
								$xml .= "</learner>";
								$xml .= "<subaims>" . $sub_aims . "</subaims>";

								// Creating Programme aim
								$xml .= "<programmeaim>";

								if(isset($ilrtemplate->programmeaim->A02))
									$xml .= "<A02>" . $ilrtemplate->programmeaim->A02 . "</A02>";
								else
									$xml .= "<A02>" . "00" . "</A02>";

								if(isset($ilrtemplate->programmeaim->A04))
									$xml .= "<A04>" . $ilrtemplate->programmeaim->A04 . "</A04>";
								else
									$xml .= "<A04>" . "1" . "</A04>";

								if(isset($ilrtemplate->programmeaim->A09))
									$xml .= "<A09>" . $ilrtemplate->programmeaim->A09 . "</A09>";
								else
									$xml .= "<A09>" . "ZPROG001" . "</A09>";

								if(isset($ilrtemplate->programmeaim->A10))
									$xml .= "<A10>" . $ilrtemplate->programmeaim->A10 . "</A10>";
								else
									$xml .= "<A10>" . "99" . "</A10>";

								if(isset($ilrtemplate->programmeaim->A11a))
									$xml .= "<A11a>" . $ilrtemplate->programmeaim->A11a . "</A11a>";
								else
									$xml .= "<A11a>" . "" . "</A11a>";

								if(isset($ilrtemplate->programmeaim->A11b))
									$xml .= "<A11b>" . $ilrtemplate->programmeaim->A11b . "</A11b>";
								else
									$xml .= "<A11b>" . "" . "</A11b>";

								if(isset($ilrtemplate->programmeaim->A14))
									$xml .= "<A14>" . $ilrtemplate->programmeaim->A14 ."</A14>";
								else
									$xml .= "<A14>" . "</A14>";

								if(isset($ilrtemplate->programmeaim->A15))
									$xml .= "<A15>" . $ilrtemplate->programmeaim->A15 ."</A15>";
								else
									$xml .= "<A15>99</A15>";

								if(isset($ilrtemplate->programmeaim->A16))
									$xml .= "<A16>" . $ilrtemplate->programmeaim->A16 . "</A16>";
								else
									$xml .= "<A16>" . "</A16>";

								if(isset($ilrtemplate->aims[0]->A18))
									$xml .= "<A18>" . $ilrtemplate->aims[0]->A18 . "</A18>";
								else
									$xml .= "<A18>" . "</A18>";


								$xml .= "<A26>0</A26>";

								$xml .= "<A27>" . $start_date . "</A27>";
								$xml .= "<A28>" . $end_date . "</A28>";
								$xml .= "<A23>" . $tr->work_postcode . "</A23>";
								$xml .= "<A51a>100</A51a>";


								if(isset($ilrtemplate->programmeaim->A46a))
									$xml .= "<A46a>" . $ilrtemplate->programmeaim->A46a .  "</A46a>";
								else
									$xml .= "<A46a>" . "</A46a>";

								if(isset($ilrtemplate->programmeaim->A46b))
									$xml .= "<A46b>" . $ilrtemplate->programmeaim->A46b . "</A46b>";
								else
									$xml .= "<A46b>" . "</A46b>";

								if(isset($ilrtemplate->programmeaim->A31))
									$xml .= "<A31>" . $ilrtemplate->programmeaim->A31 . "</A31>";
								else
									$xml .= "<A31>" . "</A31>";

								if(isset($ilrtemplate->programmeaim->A40))
									$xml .= "<A40>" . $ilrtemplate->programmeaim->A40 . "</A40>";
								else
									$xml .= "<A40>" . "</A40>";

								if(isset($ilrtemplate->programmeaim->A34))
									$xml .= "<A34>" . $ilrtemplate->programmeaim->A34 . "</A34>";
								else
									$xml .= "<A34>" . "</A34>";

								if(isset($ilrtemplate->programmeaim->A35))
									$xml .= "<A35>" . $ilrtemplate->programmeaim->A35 . "</A35>";
								else
									$xml .= "<A35>" . "</A35>";

								$xml .= "<A44>" . str_pad($row['edrs'],30,' ',STR_PAD_RIGHT) . "</A44>";
								$xml .= "<A45>" . $row['epcode'] . "</A45>";

								if(isset($ilrtemplate->programmeaim->A46a))
									$xml .= "<A46a>" . $ilrtemplate->programmeaim->A46a . "</A46a>";
								else
									$xml .= "<A46a>" . "</A46a>";

								if(isset($ilrtemplate->programmeaim->A46b))
									$xml .= "<A46b>" . $ilrtemplate->programmeaim->A46b . "</A46b>";
								else
									$xml .= "<A46b>" . "</A46b>";

								if(isset($ilrtemplate->programmeaim->A50))
									$xml .= "<A50>" . $ilrtemplate->programmeaim->A50 . "</A50>";
								else
									$xml .= "<A50>" . "</A50>";

								if(isset($ilrtemplate->programmeaim->A69))
									$xml .= "<A69>" . $ilrtemplate->programmeaim->A69 . "</A69>";
								else
									$xml .= "<A69>" . "</A69>";

								if(isset($ilrtemplate->programmeaim->A70))
									$xml .= "<A70>" . $ilrtemplate->programmeaim->A70 . "</A70>";
								else
									$xml .= "<A70>" . "</A70>";

								$xml .= "</programmeaim>";


								// Creating ESF Aim
								$xml .= "<main>";
								$xml .= "<A01>" . $row['upin'] . "</A01>";

								$xml .= "<A02>00</A02>";

								$xml .= "<A03>" . $l03 . "</A03>";


								$xml .= "<A04>" . "4" . "</A04>";
								$xml .= "<A05>" . "01" . "</A05>";
								$xml .= "<A06>" . "00" . "</A06>";
								$xml .= "<A07>" . "00" . "</A07>";
								$xml .= "<A08>" . "2" . "</A08>";
								$xml .= "<A09>ZESF0001</A09>";

								$xml .= "<A10>70</A10>";
								$xml .= "<A11a>105</A11a>";
								$xml .= "<A11b>" . "999" . "</A11b>";

								$xml .= "<A12a>" . "000" . "</A12a>";
								$xml .= "<A12b>" . "000" . "</A12b>";
								$xml .= "<A13>" . "00000" . "</A13>";

								$xml .= "<A14>" . "00" . "</A14>";
								$xml .= "<A15>99</A15>";

								$xml .= "<A16>" . "</A16>";

								$xml .= "<A17>" . "0" . "</A17>";

								$xml .= "<A18>" . "</A18>";

								$xml .= "<A19>" . "0" . "</A19>";
								$xml .= "<A20>" . "0" . "</A20>";

								$xml .= "<A21>" . "00" . "</A21>";

								$xml .= "<A22>" . "      " . "</A22>";

								$xml .= "<A23></A23>";

								$xml .= "<A24>" . "</A24>";

								$xml .= "<A26>" . $framework_code . "</A26>";

								$xml .= "<A27>" . $start_date . "</A27>";
								$xml .= "<A28>" . $start_date . "</A28>";
								$xml .= "<A31>" . $start_date . "</A31>";
								$xml .= "<A32>" . "</A32>";
								$xml .= "<A33>" . "     " . "</A33>";

								$xml .= "<A34>2</A34>";
								$xml .= "<A35>1</A35>";

								if(isset($ilrtemplate->aims[0]->A36))
									$xml .= "<A36>" . $ilrtemplate->aims[0]->A36 . "</A36>";
								else
									$xml .= "<A36>" . "   " . "</A36>";

								$xml .= "<A37></A37>";
								$xml .= "<A38></A38>";
								$xml .= "<A39>" . "0" . "</A39>";
								$xml .= "<A40></A40>";
								$xml .= "<A43>" . $row['closure_date'] . "</A43>";

								$xml .= "<A44>" . str_pad($row['edrs'],30,' ',STR_PAD_RIGHT) . "</A44>";
								$xml .= "<A45>" . $row['epcode'] . "</A45>";

								$xml .= "<A46a>999</A46a>";
								$xml .= "<A46b>999</A46b>";

								$xml .= "<A47a>" . "</A47a>";

								$xml .= "<A47b>" . "</A47b>";

								$xml .= "<A48a>" . "</A48a>";

								$xml .= "<A48b>" . "</A48b>";

								$xml .= "<A49>" . "     " . "</A49>";

								$xml .= "<A50>" . "</A50>";

								$xml .= "<A51a>100</A51a>";
								$xml .= "<A52>" . "0.000" . "</A52>";
								$xml .= "<A53>" . "</A53>";

								$xml .= "<A54>" . "</A54>";

								if($row['l45']!='')
									$xml .= "<A55>" . $row['l45'] . "</A55>";
								else
									$xml .= "<A55>9999999999</A55>";

								$xml .= "<A56>" . "</A56>";
								$xml .= "<A57>" . "00" . "</A57>";
								$xml .= "<A58>" . "</A58>";

								$xml .= "<A59>" . "</A59>";

								$xml .= "<A60>" . "</A60>";

								$xml .= "<A61>" . "</A61>";

								$xml .= "<A62>" . "</A62>";

								$xml .= "<A63>" . "</A63>";

								$xml .= "<A64>" . "</A64>";

								$xml .= "<A65>" . "</A65>";

								$xml .= "<A66>" . "</A66>";

								$xml .= "<A67>" . "</A67>";

								$xml .= "<A68>" . "</A68>";

								$xml .= "<A69>" . "</A69>";

								$xml .= "<A70>" . "</A70>";

								$xml .= "<A71>" . "</A71>";

								$xml .= "</main>";

								// Creating Sub Aims out of framework
								$sql_main = "select student_qualifications.*, tr.work_postcode, tr.start_date as lsd, tr.target_date as led from student_qualifications inner join tr on tr.id = student_qualifications.tr_id INNER JOIN framework_qualifications on framework_qualifications.framework_id = student_qualifications.framework_id AND framework_qualifications.id	 = student_qualifications.id and framework_qualifications.internaltitle	 = student_qualifications.internaltitle where tr_id = {$row['id']}";
								$st3 = $link->query($sql_main);
								if($st3)
								{
									$learningaim=2;
									while($row_sub = $st3->fetch())
									{
										$xml .= "<subaim>";
										$xml .= "<A01>" . $row['upin'] . "</A01>";
										$xml .= "<A02>00</A02>";
										$xml .= "<A03>" . $l03 .  "</A03>";
										$xml .= "<A04>4</A04>";
										$xml .= "<A05>" . str_pad($learningaim,2,'0',STR_PAD_LEFT) . "</A05>";
										$learningaim++;
										$xml .= "<A06>00</A06>";
										$xml .= "<A07>" . "00" . "</A07>";
										$xml .= "<A08>" . "2" . "</A08>";
										$xml .= "<A09>" . str_replace("/" , "", $row_sub['id']) . "</A09>";

										$xml .= "<A10>70</A10>";

										$xml .= "<A11a>105</A11a>";

										$xml .= "<A11b>999</A11b>";

										$xml .= "<A12a>" . "000" . "</A12a>";
										$xml .= "<A12b>" . "000" . "</A12b>";
										$xml .= "<A13>" . "00000" . "</A13>";

										$xml .= "<A14>" . "00" . "</A14>";

										$xml .= "<A15>99</A15>";

										$xml .= "<A16>" . "</A16>";

										$xml .= "<A17>" . "0" . "</A17>";

										$xml .= "<A18>" . "</A18>";

										$xml .= "<A19>" . "0" . "</A19>";
										$xml .= "<A20>" . "0" . "</A20>";

										$xml .= "<A21>" . "00" . "</A21>";

										$xml .= "<A22>" . "      " . "</A22>";

										$xml .= "<A23>" . $row_sub['work_postcode'] . "</A23>";

										$xml .= "<A24>" . "</A24>";

										$xml .= "<A26>" . $framework_code . "</A26>";

										$xml .= "<A27>" . substr($row_sub['lsd'],8,2) . '/' . substr($row_sub['lsd'],5,2) . '/' . substr($row_sub['lsd'],0,4) . "</A27>";
										$xml .= "<A28>" . substr($row_sub['led'],8,2) . '/' . substr($row_sub['led'],5,2) . '/' . substr($row_sub['led'],0,4) . "</A28>";
										$xml .= "<A31>" . $row_sub['actual_end_date'] . "</A31>";
										$xml .= "<A32>" . "</A32>";
										$xml .= "<A33>" . "     " . "</A33>";

										$xml .= "<A34>1</A34>";

										$xml .= "<A35>9</A35>";

										$xml .= "<A36>" . "   " . "</A36>";

										$xml .= "<A37>0</A37>";
										$xml .= "<A38>0</A38>";
										$xml .= "<A39>" . "0" . "</A39>";
										$xml .= "<A40>" . $row_sub['achievement_date'] . "</A40>";
										$xml .= "<A43>" . $row['closure_date'] . "</A43>";

										$xml .= "<A44>" . str_pad('999999999',30,' ',STR_PAD_RIGHT) . "</A44>";

										$xml .= "<A45>" . "</A45>";

										$xml .= "<A46a>" . "</A46a>";

										$xml .= "<A46b>" . "</A46b>";

										$xml .= "<A47a>" . "</A47a>";

										$xml .= "<A47b>" . "</A47b>";

										$xml .= "<A48a>" . "</A48a>";

										$xml .= "<A48b>" . "</A48b>";

										$xml .= "<A49>" . "     " . "</A49>";

										$xml .= "<A50>" . "</A50>";

										$xml .= "<A51a>100</A51a>";
										$xml .= "<A52>" . "0.000" . "</A52>";

										$xml .= "<A53>" . "</A53>";

										$xml .= "<A54>" . "</A54>";

										$xml .= "<A55>9999999999</A55>";

										$xml .= "<A56>" . "</A56>";
										$xml .= "<A57>" . "00" . "</A57>";

										$xml .= "<A59>" . "</A59>";

										$xml .= "<A60>" . "</A60>";

										$xml .= "<A61>" . "</A61>";

										$xml .= "<A62>" . "</A62>";

										$xml .= "<A63>" . "</A63>";

										$xml .= "<A66>" . "</A66>";

										$xml .= "<A67>" . "</A67>";

										$xml .= "<A68>" . "</A68>";

										$xml .= "<A69>" . "</A69>";

										$xml .= "<A70>" . "</A70>";

										$xml .= "<A71>" . "</A71>";

										$xml .= "</subaim>";
									}
								}

								// Creating Sub Aims out of additional qualifications
								$sql_sub = "select student_qualifications.*, tr.work_postcode, tr.start_date as lsd, tr.target_date as led from student_qualifications inner join tr on tr.id = student_qualifications.tr_id where tr_id = {$row['id']} and framework_id=0";

								$st4 = $link->query($sql_sub);
								if($st4)
								{
									$learningaim=2;
									while($row_sub = $st4->fetch())
									{
										$xml .= "<subaim>";
										$xml .= "<A01>" . $row['upin'] . "</A01>";
										$xml .= "<A02>00</A02>";
										$xml .= "<A03>" . $l03 . "</A03>";
										$xml .= "<A04>" . "30" . "</A04>";
										$xml .= "<A05>" . str_pad($learningaim,2,'0',STR_PAD_LEFT) . "</A05>";
										$learningaim++;
										$xml .= "<A06>00</A06>";
										$xml .= "<A07>" . "00" . "</A07>";
										$xml .= "<A08>" . "2" . "</A08>";
										$xml .= "<A09>" . str_replace("/" , "", $row_sub['id']) . "</A09>";
										$xml .= "<A10>" . "</A10>";
										$xml .= "<A11a>" . "000" . "</A11a>";
										$xml .= "<A11b>" . "000" . "</A11b>";
										$xml .= "<A12a>" . "000" . "</A12a>";
										$xml .= "<A12b>" . "000" . "</A12b>";
										$xml .= "<A13>" . "00000" . "</A13>";
										$xml .= "<A14>" . "00" . "</A14>";
										$xml .= "<A15>" . "</A15>";
										$xml .= "<A16>" . "</A16>";
										$xml .= "<A17>" . "0" . "</A17>";
										$xml .= "<A18>" . "</A18>";
										$xml .= "<A19>" . "0" . "</A19>";
										$xml .= "<A20>" . "0" . "</A20>";
										$xml .= "<A21>" . "00" . "</A21>";
										$xml .= "<A22>" . "      " . "</A22>";
										$xml .= "<A23>" . $row_sub['work_postcode'] . "</A23>";
										$xml .= "<A24>" . "</A24>";
										$xml .= "<A26>" . $framework_code . "</A26>";
										$xml .= "<A27>" . substr($row_sub['lsd'],8,2) . '/' . substr($row_sub['lsd'],5,2) . '/' . substr($row_sub['lsd'],0,4) . "</A27>";
										$xml .= "<A28>" . substr($row_sub['led'],8,2) . '/' . substr($row_sub['led'],5,2) . '/' . substr($row_sub['led'],0,4) . "</A28>";
										$xml .= "<A31>" . $row_sub['actual_end_date'] . "</A31>";
										$xml .= "<A32>" . "</A32>";
										$xml .= "<A33>" . "     " . "</A33>";
										$xml .= "<A34>" . "</A34>";
										$xml .= "<A35>" . "</A35>";
										$xml .= "<A36>" . "   " . "</A36>";
										$xml .= "<A37>" . $row_sub['unitsCompleted'] . "</A37>";
										$xml .= "<A38>" . $row_sub['units'] . "</A38>";
										$xml .= "<A39>" . "0" . "</A39>";
										$xml .= "<A40>" . $row_sub['achievement_date'] . "</A40>";
										$xml .= "<A43>" . $row['closure_date'] . "</A43>";

										if($row['edrs']!='')
											$xml .= "<A44>" . str_pad($row['edrs'],30,' ',STR_PAD_RIGHT) . "</A44>";
										else
											$xml .= "<A44>" . str_pad('999999999',30,' ',STR_PAD_RIGHT) . "</A44>";

										$xml .= "<A45>" . "</A45>";
										$xml .= "<A46a>" . "</A46a>";
										$xml .= "<A46b>" . "</A46b>";
										$xml .= "<A47a>" . "</A47a>";
										$xml .= "<A47b>" . "</A47b>";
										$xml .= "<A48a>" . "</A48a>";
										$xml .= "<A48b>" . "</A48b>";
										$xml .= "<A49>" . "     " . "</A49>";
										$xml .= "<A50>" . "</A50>";
										$xml .= "<A51a>100</A51a>";
										$xml .= "<A52>" . "0.000" . "</A52>";
										$xml .= "<A53>" . "</A53>";
										$xml .= "<A54>" . "</A54>";
										if($row['l45']!='')
											$xml .= "<A55>" . $row['l45'] . "</A55>";
										else
											$xml .= "<A55>9999999999</A55>";
										$xml .= "<A56>" . "</A56>";
										$xml .= "<A57>" . "00" . "</A57>";
										$xml .= "</subaim>";
									}
								}



								$xml .= "</ilr>";
								$xml = str_replace("&", "&amp;", $xml);
								$xml = str_replace("'", "&apos;", $xml);
								// getting contract type

								// Override template with matching A09
								if($ilrtemplatetext!='')
									$xml = Ilr2011::CopyILRFields($xml, $ilrtemplatetext);

								$xml = str_replace("'", "&apos;", $xml);

								$sql = "Select contract_type from contracts where id ='$contract_id'";
								$contract_type = DAO::getResultset($link, $sql);
								$contract_type = $contract_type[0][0];

								// $xml = addslashes((string)$xml);
								$contract = addslashes((string)$contract_id);
								$contract_type=addslashes((string)$contract_type);

								$upin = $row['upin'];
								//$l03 = $row['l03'];

								$sql = "insert into ilr (L01,L03, A09, ilr,submission,contract_type,tr_id,is_complete,is_valid,is_approved,is_active,contract_id) values('$upin','$l03','0','$xml','$submission','$contract_type','$tr->id','0','0','0','0','$contract');";
								DAO::execute($link, $sql);
							}
						}
					}
					else
					{
						if($ilrtemplatetext!='')
						{
							$ilrtemplate = Ilr2012::loadFromXML($ilrtemplatetext);
						}

						$sql = <<<SQL
SELECT
  users.ni,
  users.l45,
  users.l24,
  users.l14,
  users.l15,
  users.l16,
  users.l35,
  users.l48,
  users.l42a,
  users.l42b,
  contract_holder.upin,
  users.uln,
  users.home_email,
  l03,
  tr.l28a,
  tr.l28b,
  tr.l34a,
  tr.l34b,
  tr.l34c,
  tr.l34d,
  tr.l36,
  tr.l37,
  tr.l39,
  tr.l40a,
  tr.l40b,
  tr.l41a,
  tr.l41b,
  tr.l47,
  tr.id,
  tr.surname,
  tr.firstnames,
  DATE_FORMAT(tr.dob, '%d/%m/%Y') AS date_of_birth,
  DATE_FORMAT(closure_date, '%d/%m/%Y') AS closure_date,
  tr.ethnicity,
  tr.gender,
  learning_difficulties,
  disability,
  learning_difficulty,
  tr.home_address_line_1,
  tr.home_address_line_2,
  tr.home_address_line_3,
  tr.home_address_line_4,
  tr.home_postcode,
  current_postcode,
  tr.home_telephone,
  country_of_domicile,
  tr.ni,
  prior_attainment_level,
  DATE_FORMAT(tr.start_date, '%d/%m/%Y') AS start_date,
  DATE_FORMAT(target_date, '%d/%m/%Y') AS target_date,
  status_code,
  provider_location_id,
  tr.employer_id,
  provider_location.postcode AS lpcode,
  organisations.edrs AS edrs,
  organisations.legal_name AS employer_name,
  employer_location.postcode AS epcode
FROM
  tr
  LEFT JOIN locations AS provider_location
    ON provider_location.id = tr.provider_location_id
  LEFT JOIN locations AS employer_location
    ON employer_location.id = tr.employer_location_id
  LEFT JOIN organisations
    ON organisations.id = tr.employer_id
  LEFT JOIN contracts
    ON contracts.id = tr.contract_id
  LEFT JOIN organisations AS contract_holder
    ON contract_holder.id = contract_holder
  LEFT JOIN users
    ON users.username = tr.username
WHERE contract_id = '$contract_id'
  AND tr.id = '$tr->id'
SQL;
						$st = $link->query($sql);
						if($st)
						{
							while($row = $st->fetch())
							{
								// here to create ilrs for the first time from training records.
								$xml = '<Learner>';
								$xml .= "<LearnRefNumber>" . $tr->l03 . "</LearnRefNumber>";
								if($row['l45']!='')
									$xml .= "<ULN>" . $row['l45'] . "</ULN>";
								else
									$xml .= "<ULN>9999999999</ULN>";
								$xml .= "<FamilyName>" . $row['surname'] .	"</FamilyName>";
								$xml .= "<GivenNames>" . $row['firstnames'] . "</GivenNames>";
								$xml .= "<DateOfBirth>" . $row['date_of_birth'] . "</DateOfBirth>";
								$xml .= "<Ethnicity>" . $row['ethnicity'] .	"</Ethnicity>";
								$xml .= "<Sex>" . $row['gender'] . "</Sex>";
								if(!isset($row['l14']) && isset($ilrtemplate->LLDDHealthProb))
									$xml .= "<LLDDHealthProb>" . $ilrtemplate->LLDDHealthProb . "</LLDDHealthProb>";
								else
									$xml .= "<LLDDHealthProb>" . $row['l14'] .	"</LLDDHealthProb>";

								if($course->programme_type!='6')
									$xml .= "<NINumber>" . $row['ni'] . "</NINumber>";

								if($course->programme_type!='6' && $course->programme_type!='5')
									if(!isset($row['l24']) && isset($ilrtemplate->Domicile))
										$xml .= "<Domicile>" . $ilrtemplate->Domicile . "</Domicile>";
									else
										$xml .= "<Domicile>" . $row['l24'] .		"</Domicile>";

								if($course->programme_type!='6')
									$xml .= "<PriorAttain>" . $row['l35'] .	"</PriorAttain>";

								if(!isset($row['l39']) && isset($ilrtemplate->Dest))
									$xml .= "<Dest>" . $ilrtemplate->Dest . "</Dest>";
								else
									$xml .= "<Dest>" . $row['l39'] . "</Dest>";

								$xml .= "<LearnerContact><LocType>2</LocType><ContType>1</ContType><PostCode>" . $row['home_postcode'] . "</PostCode></LearnerContact>";
								$xml .= "<LearnerContact><LocType>1</LocType><ContType>2</ContType><PostAdd>";
								$xml .= "<AddLine1>" . $row['home_address_line_1'] . "</AddLine1>";
								$xml .= "<AddLine2>" . $row['home_address_line_2'] . "</AddLine2>";
								$xml .= "<AddLine3>" . $row['home_address_line_3'] . "</AddLine3>";
								$xml .= "<AddLine4>" . $row['home_address_line_4'] . "</AddLine4>";
								$xml .= "</PostAdd></LearnerContact>";
								$xml .= "<LearnerContact><LocType>2</LocType><ContType>2</ContType><PostCode>" . $row['home_postcode'] . "</PostCode></LearnerContact>";
								$xml .= "<LearnerContact><LocType>3</LocType><ContType>2</ContType><TelNumber>" . $row['home_telephone'] . "</TelNumber></LearnerContact>";
								$xml .= "<LearnerContact><LocType>4</LocType><ContType>2</ContType><Email>" . $row['home_email'] . "</Email></LearnerContact>";
								$xml .= "<LLDDandHealthProblem><LLDDType>DS</LLDDType><LLDDCode>" . $row['l15'] . "</LLDDCode></LLDDandHealthProblem>";
								$xml .= "<LLDDandHealthProblem><LLDDType>LD</LLDDType><LLDDCode>" . $row['l16'] . "</LLDDCode></LLDDandHealthProblem>";
								$xml .= "<LearnerFAM><LearnFAMType>EFE</LearnFAMType><LearnFAMCode>" . $row['l28a'] . "</LearnFAMCode></LearnerFAM>";
								$xml .= "<LearnerFAM><LearnFAMType>LSR</LearnFAMType><LearnFAMCode>" . $row['l34a'] . "</LearnFAMCode></LearnerFAM>";
								$xml .= "<LearnerFAM><LearnFAMType>LSR</LearnFAMType><LearnFAMCode>" . $row['l34b'] . "</LearnFAMCode></LearnerFAM>";
								$xml .= "<LearnerFAM><LearnFAMType>LSR</LearnFAMType><LearnFAMCode>" . $row['l34c'] . "</LearnFAMCode></LearnerFAM>";
								$xml .= "<LearnerFAM><LearnFAMType>LSR</LearnFAMType><LearnFAMCode>" . $row['l34d'] . "</LearnFAMCode></LearnerFAM>";
								$xml .= "<LearnerFAM><LearnFAMType>NLM</LearnFAMType><LearnFAMCode>" . $row['l40a'] . "</LearnFAMCode></LearnerFAM>";
								$xml .= "<LearnerFAM><LearnFAMType>NLM</LearnFAMType><LearnFAMCode>" . $row['l40b'] . "</LearnFAMCode></LearnerFAM>";
								$xml .= "<ProviderSpecLearnerMonitoring><ProvSpecLearnMonOccur>A</ProvSpecLearnMonOccur><ProvSpecLearnMon>" . $row['l42a'] . "</ProvSpecLearnMon></ProviderSpecLearnerMonitoring>";
								$xml .= "<ProviderSpecLearnerMonitoring><ProvSpecLearnMonOccur>B</ProvSpecLearnMonOccur><ProvSpecLearnMon>" . $row['l42b'] . "</ProvSpecLearnMon></ProviderSpecLearnerMonitoring>";

								$xml .= "<LearnerEmploymentStatus><EmpStat>" . $row['l47'] . "</EmpStat><DateEmpStatApp>" . $row['l48'] . "</DateEmpStatApp><WorkLocPostCode>" . $tr->work_postcode . "</WorkLocPostCode><EmpId>" . $row['edrs'] . "</EmpId></LearnerEmploymentStatus>";

								$xml .= "<LearningDelivery>";
								$xml .= "<LearnAimRef>ZESF0001</LearnAimRef>";
								$xml .= "<AimType>4</AimType>";
								$xml .= "<AimSeqNumber>1</AimSeqNumber>";
								$xml .= "<LearnStartDate>" . $start_date . "</LearnStartDate>";
								$xml .= "<LearnPlanEndDate>" . $start_date . "</LearnPlanEndDate>";
								$xml .= "<LearnActEndDate>" . $start_date . "</LearnActEndDate>";
								$xml .= "<FundModel>70</FundModel>";
								$xml .= "<ProgType>99</ProgType>";
								$xml .= "<FworkCode>" . $framework_code . "</FworkCode>";
								$xml .= "<DelLocPostCode>" . $tr->work_postcode . "</DelLocPostCode>";
								$xml .= "<PropFundRemain>100</PropFundRemain>";
								if(isset($ilrtemplate))
								{
									$xml .= "<ContOrg>" . $ilrtemplate->LearningDelivery->ContOrg . "</ContOrg>";
									$xml .= "<ESFProjDosNumber>" . $ilrtemplate->LearningDelivery->ESFProjDosNumber . "</ESFProjDosNumber>";
									$xml .= "<ESFLocProjNumber>" . $ilrtemplate->LearningDelivery->ESFLocProjNumber . "</ESFLocProjNumber>";
								}
								$xml .= "<CompStatus>2</CompStatus>";
								$xml .= "<Outcome>1</Outcome>";
								$xml .= "<LearningDeliveryFAM><LearnDelFAMType>SOF</LearnDelFAMType><LearnDelFAMCode>105</LearnDelFAMCode></LearningDeliveryFAM>";
								$xml .= "</LearningDelivery>";

								$AimSeqNumber = 1;
								if($course->programme_type=='2' || $course->programme_type=='3')
								{
									$AimSeqNumber++;
									$sql_main = "select framework_qualifications.main_aim, frameworks.framework_type, frameworks.framework_code, student_qualifications.*, tr.work_postcode, tr.start_date as lsd, tr.target_date as led from student_qualifications inner join tr on tr.id = student_qualifications.tr_id INNER JOIN framework_qualifications on framework_qualifications.framework_id = student_qualifications.framework_id AND framework_qualifications.id	 = student_qualifications.id and framework_qualifications.internaltitle	 = student_qualifications.internaltitle left join frameworks on frameworks.id = framework_qualifications.framework_id where tr_id = {$row['id']} order by main_aim desc limit 0,1";
									$st3 = $link->query($sql_main);
									if($st3)
									{
										while($row_sub = $st3->fetch())
										{
											$xml .= "<LearningDelivery>";
											$xml .= "<LearnAimRef>ZPROG001</LearnAimRef>";
											$xml .= "<AimType>1</AimType>";
											$xml .= "<AimSeqNumber>" . $AimSeqNumber . "</AimSeqNumber>";
											$xml .= "<LearnStartDate>" . $row_sub['lsd'] . "</LearnStartDate>";
											$xml .= "<LearnPlanEndDate>" . $row_sub['led'] . "</LearnPlanEndDate>";
											if($course->programme_type=='1' || $course->programme_type=='2')
												$xml .= "<FundModel>45</FundModel>";
											elseif($course->programme_type=='3')
												$xml .= "<FundModel>21</FundModel>";
											elseif($course->programme_type=='4')
												$xml .= "<FundModel>22</FundModel>";
											elseif($course->programme_type=='5')
												$xml .= "<FundModel>70</FundModel>";
											elseif($course->programme_type=='6')
												$xml .= "<FundModel>10</FundModel>";
											if($course->programme_type!='6')
												$xml .= "<ProgType>" . $row_sub['framework_type'] . "</ProgType>";
											if($course->programme_type!='1' && $course->programme_type!='6')
												$xml .= "<FworkCode>" . $row_sub['framework_code'] . "</FworkCode>";
											$xml .= "<DelLocPostCode>" . $tr->work_postcode . "</DelLocPostCode>";
											$xml .= "<PropFundRemain>100</PropFundRemain>";
											if(isset($ilrtemplate))
											{
												$xml .= "<ContOrg>" . $ilrtemplate->LearningDelivery->ContOrg . "</ContOrg>";
												$xml .= "<ESFProjDosNumber>" . $ilrtemplate->LearningDelivery->ESFProjDosNumber . "</ESFProjDosNumber>";
												$xml .= "<ESFLocProjNumber>" . $ilrtemplate->LearningDelivery->ESFLocProjNumber . "</ESFLocProjNumber>";
											}
											$xml .= "<CompStatus>1</CompStatus>";
											$xml .= "<LearningDeliveryFAM><LearnDelFAMType>SOF</LearnDelFAMType><LearnDelFAMCode>105</LearnDelFAMCode></LearningDeliveryFAM>";
											$xml .= "</LearningDelivery>";
										}
									}
								}

								$sql_main = "select framework_qualifications.main_aim, frameworks.framework_type, frameworks.framework_code, student_qualifications.*, tr.work_postcode, tr.start_date as lsd, tr.target_date as led from student_qualifications inner join tr on tr.id = student_qualifications.tr_id INNER JOIN framework_qualifications on framework_qualifications.framework_id = student_qualifications.framework_id AND framework_qualifications.id	 = student_qualifications.id and framework_qualifications.internaltitle	 = student_qualifications.internaltitle left join frameworks on frameworks.id = framework_qualifications.framework_id where tr_id = {$row['id']} order by main_aim desc";
								$st3 = $link->query($sql_main);
								if($st3)
								{
									while($row_sub = $st3->fetch())
									{
										$AimSeqNumber++;
										$xml .= "<LearningDelivery>";
										$xml .= "<LearnAimRef>" . str_replace("/" , "", $row_sub['id']) . "</LearnAimRef>";

										if($course->programme_type=='2' && $row_sub['main_aim']==1)
											$xml .= "<AimType>2</AimType>";
										elseif($course->programme_type=='2' && $row_sub['main_aim']!=1)
											$xml .= "<AimType>3</AimType>";
										else
											$xml .= "<AimType>4</AimType>";

										$xml .= "<AimSeqNumber>" . $AimSeqNumber . "</AimSeqNumber>";
										$xml .= "<LearnStartDate>" . $row_sub['lsd'] . "</LearnStartDate>";
										$xml .= "<LearnPlanEndDate>" . $row_sub['led'] . "</LearnPlanEndDate>";
										if($course->programme_type=='1' || $course->programme_type=='2')
											$xml .= "<FundModel>45</FundModel>";
										elseif($course->programme_type=='3')
											$xml .= "<FundModel>21</FundModel>";
										elseif($course->programme_type=='4')
											$xml .= "<FundModel>22</FundModel>";
										elseif($course->programme_type=='5')
											$xml .= "<FundModel>70</FundModel>";
										elseif($course->programme_type=='6')
											$xml .= "<FundModel>10</FundModel>";

										if($course->programme_type!='6')
											$xml .= "<ProgType>" . $row_sub['framework_type'] . "</ProgType>";

										if($course->programme_type!='1' && $course->programme_type!='6')
											$xml .= "<FworkCode>" . $row_sub['framework_code'] . "</FworkCode>";

										$xml .= "<DelLocPostCode>" . $tr->work_postcode . "</DelLocPostCode>";
										if(isset($ilrtemplate))
										{
											$xml .= "<ContOrg>" . $ilrtemplate->LearningDelivery->ContOrg . "</ContOrg>";
											$xml .= "<ESFProjDosNumber>" . $ilrtemplate->LearningDelivery->ESFProjDosNumber . "</ESFProjDosNumber>";
											$xml .= "<ESFLocProjNumber>" . $ilrtemplate->LearningDelivery->ESFLocProjNumber . "</ESFLocProjNumber>";
										}
										$xml .= "<PropFundRemain>100</PropFundRemain>";
										$xml .= "<CompStatus>1</CompStatus>";

										$xml .= "</LearningDelivery>";
									}
								}

								$xml .= "</Learner>";

								$xml = str_replace("&", "&amp;", $xml);
								$xml = str_replace("'", "&apos;", $xml);

								$sql = "Select contract_type from contracts where id ='$contract_id'";
								$contract_type = DAO::getResultset($link, $sql);
								$contract_type = $contract_type[0][0];
								$contract = addslashes((string)$contract_id);
								$contract_type=addslashes((string)$contract_type);

								$sql = "insert into ilr (L01,L03, A09, ilr,submission,contract_type,tr_id,is_complete,is_valid,is_approved,is_active,contract_id) values('','$l03','0','$xml','$submission','$contract_type','$tr_id','0','0','0','0','$contract');";
								DAO::execute($link, $sql);

							}
						}
					}
				}
				else
				{
					$contract_year = DAO::getSingleValue($link,"select contract_year from contracts where id='$contract_id'");
					$co = Contract::loadFromDatabase($link, $contract_id);
					$submission = DAO::getSingleValue($link, "select submission from central.lookup_submission_dates where last_submission_date>=CURDATE() and contract_year = '$contract_year' and contract_type = '$co->funding_body' order by last_submission_date LIMIT 1;");
					$ilrtemplatetext = DAO::getSingleValue($link, "select template from contracts where id = '$contract_id'");

					if($contract_year < 2012)
					{
						if($ilrtemplatetext!='')
						{
							$ilrtemplate = Ilr2011::loadFromXML($ilrtemplatetext);
						}

						//$sql = "SELECT users.ni, users.l45, users.l24, users.l14, users.l15, users.l16, users.l35, users.l48, users.l42a, users.l42b, contract_holder.upin, users.uln, users.home_email, l03, tr.l28a, tr.l28b, tr.l34a, tr.l34b, tr.l34c, tr.l34d, tr.l36, tr.l37, tr.l39, tr.l40a, tr.l40b, tr.l41a, tr.l41b, tr.l47, tr.id, tr.surname, tr.firstnames, DATE_FORMAT(tr.dob,'%d/%m/%Y') AS date_of_birth, DATE_FORMAT(closure_date, '%d/%m/%Y') AS closure_date, tr.ethnicity, tr.gender, learning_difficulties, disability,learning_difficulty, tr.home_postcode, TRIM(CONCAT(IF(tr.home_paon_start_number IS NOT NULL,TRIM(tr.home_paon_start_number),''),IF(tr.home_paon_start_suffix IS NOT NULL,TRIM(tr.home_paon_start_suffix),''),' ', IF(tr.home_paon_end_number IS NOT NULL,TRIM(tr.home_paon_end_number),''),IF(tr.home_paon_end_suffix IS NOT NULL,TRIM(tr.home_paon_end_suffix),''),' ' , IF(tr.home_paon_description IS NOT NULL,TRIM(tr.home_paon_description),''),' ',IF(tr.home_street_description IS NOT NULL,TRIM(tr.home_street_description),''))) AS L18, tr.home_locality, tr.home_town,tr.home_county, current_postcode, tr.home_telephone, country_of_domicile, tr.ni, prior_attainment_level,DATE_FORMAT(tr.start_date,'%d/%m/%Y') AS start_date, DATE_FORMAT(target_date,'%d/%m/%Y') AS target_date, status_code,provider_location_id, tr.employer_id, provider_location.postcode AS lpcode, organisations.edrs AS edrs, organisations.legal_name AS employer_name,employer_location.postcode AS epcode FROM tr LEFT JOIN locations AS provider_location ON provider_location.id = tr.provider_location_id LEFT JOIN locations AS employer_location ON employer_location.id = tr.employer_location_id LEFT JOIN organisations ON organisations.id = tr.employer_id 	LEFT JOIN contracts ON contracts.id = tr.contract_id LEFT JOIN organisations AS contract_holder ON contract_holder.id = contract_holder LEFT JOIN users ON users.username = tr.username WHERE contract_id = '$contract_id' AND tr.id = '$tr->id';";
						$sql = <<<SQL
SELECT
  users.ni,
  users.l45,
  users.l24,
  users.l14,
  users.l15,
  users.l16,
  users.l35,
  users.l48,
  users.l42a,
  users.l42b,
  contract_holder.upin,
  users.uln,
  users.home_email,
  l03,
  tr.l28a,
  tr.l28b,
  tr.l34a,
  tr.l34b,
  tr.l34c,
  tr.l34d,
  tr.l36,
  tr.l37,
  tr.l39,
  tr.l40a,
  tr.l40b,
  tr.l41a,
  tr.l41b,
  tr.l47,
  tr.id,
  tr.surname,
  tr.firstnames,
  DATE_FORMAT(tr.dob, '%d/%m/%Y') AS date_of_birth,
  DATE_FORMAT(closure_date, '%d/%m/%Y') AS closure_date,
  tr.ethnicity,
  tr.gender,
  learning_difficulties,
  disability,
  learning_difficulty,
  tr.home_address_line_1,
  tr.home_address_line_2,
  tr.home_address_line_3,
  tr.home_address_line_4,
  tr.home_postcode,
  current_postcode,
  tr.home_telephone,
  country_of_domicile,
  tr.ni,
  prior_attainment_level,
  DATE_FORMAT(tr.start_date, '%d/%m/%Y') AS start_date,
  DATE_FORMAT(target_date, '%d/%m/%Y') AS target_date,
  status_code,
  provider_location_id,
  tr.employer_id,
  provider_location.postcode AS lpcode,
  organisations.edrs AS edrs,
  organisations.legal_name AS employer_name,
  employer_location.postcode AS epcode
FROM
  tr
  LEFT JOIN locations AS provider_location
    ON provider_location.id = tr.provider_location_id
  LEFT JOIN locations AS employer_location
    ON employer_location.id = tr.employer_location_id
  LEFT JOIN organisations
    ON organisations.id = tr.employer_id
  LEFT JOIN contracts
    ON contracts.id = tr.contract_id
  LEFT JOIN organisations AS contract_holder
    ON contract_holder.id = contract_holder
  LEFT JOIN users
    ON users.username = tr.username
WHERE contract_id = '$contract_id'
  AND tr.id = '$tr->id'
SQL;

						$st = $link->query($sql);
						if($st)
						{

							while($row = $st->fetch())
							{
								// here to create ilrs for the first time from training records.
								$xml = '<ilr>';
								$xml .= "<learner>";
								$xml .= "<L01>" . $row['upin'] . "</L01>";
								$xml .= "<L02>" . "00" . "</L02>";
								$xml .= "<L03>" . $l03 . "</L03>";
								$xml .= "<L04>" . "10" . "</L04>";

								// No of learning aim data sets
								$sql ="select COUNT(*) from student_qualifications where tr_id ={$row['id']}";
								$learning_aims = DAO::getResultset($link,$sql);

								$xml .= "<L05>" . $learning_aims[0][0] . "</L05>";
								$xml .= "<L06>" . "00" . "</L06>";
								$xml .= "<L07>" . "00" . "</L07>";
								if($row['status_code']==4 || $row['status_code']=='4')
									$xml .= "<L08>" . "Y" . "</L08>";
								else
									$xml .= "<L08>" . "N" . "</L08>";
								$xml .= "<L09>" . $row['surname'] . 				"</L09>";
								$xml .= "<L10>" . $row['firstnames'] . 				"</L10>";
								$xml .= "<L11>" . $row['date_of_birth'] . 			"</L11>";
								$xml .= "<L12>" . $row['ethnicity'] . 				"</L12>";
								$xml .= "<L13>" . $row['gender'] . 					"</L13>";

								if(!isset($row['l14']) && isset($ilrtemplate->learnerinformation->L14))
									$xml .= "<L14>" . $ilrtemplate->learnerinformation->L14 . "</L14>";
								else
									$xml .= "<L14>" . $row['l14'] .	"</L14>";

								if(!isset($row['l15']) && isset($ilrtemplate->learnerinformation->L15))
									$xml .= "<L15>" . $ilrtemplate->learnerinformation->L15 . "</L15>";
								else
									$xml .= "<L15>" . $row['l15'] . 				"</L15>";

								if(!isset($row['l16']) && isset($ilrtemplate->learnerinformation->L16))
									$xml .= "<L16>" . $ilrtemplate->learnerinformation->L16 . "</L16>";
								else
									$xml .= "<L16>" . $row['l16'] .		"</L16>";

								$xml .= "<L18>" . $row['home_address_line_1'] . "</L18>";
								$xml .= "<L19>" . $row['home_address_line_2'] . 			"</L19>";
								$xml .= "<L20>" . $row['home_address_line_3'] . 				"</L20>";
								$xml .= "<L21>" . $row['home_address_line_4'] . 			"</L21>";
								$xml .= "<L22>" . $row['home_postcode'] .		"</L22>";
								$xml .= "<L23>" . $row['home_telephone'] . 			"</L23>";

								if(!isset($row['l24']) && isset($ilrtemplate->learnerinformation->L24))
									$xml .= "<L24>" . $ilrtemplate->learnerinformation->L24 . "</L24>";
								else
									$xml .= "<L24>" . $row['l24'] .		"</L24>";

								$xml .= "<L25>" . "</L25>";
								$xml .= "<L26>" . $row['ni'] . 						"</L26>";
								$xml .= "<L27>" . "1" . "</L27>";

								if(!isset($row['l28a']) && isset($ilrtemplate->learnerinformation->L28a))
									$xml .= "<L28a>" . $ilrtemplate->learnerinformation->L28a . "</L28a>";
								else
									$xml .= "<L28a>" . $row['l28a'] . "</L28a>";

								if(!isset($row['l28b']) && isset($ilrtemplate->learnerinformation->L28b))
									$xml .= "<L28b>" . $ilrtemplate->learnerinformation->L28b . "</L28b>";
								else
									$xml .= "<L28b>" . $row['l28b'] . "</L28b>";

								$xml .= "<L29>" . "00" . "</L29>";
								$xml .= "<L31>" . "000000" . "</L31>";
								$xml .= "<L32>" . "00" . "</L32>";
								$xml .= "<L33>" . "0.0000" . "</L33>";

								if(!isset($row['l34a']) && isset($ilrtemplate->learnerinformation->L34a))
									$xml .= "<L34a>" . $ilrtemplate->learnerinformation->L34a . "</L34a>";
								else
									$xml .= "<L34a>" . $row['l34a'] . "</L34a>";

								if(!isset($row['l34b']) && isset($ilrtemplate->learnerinformation->L34b))
									$xml .= "<L34b>" . $ilrtemplate->learnerinformation->L34b . "</L34b>";
								else
									$xml .= "<L34b>" . $row['l34b'] . "</L34b>";

								if(!isset($row['l34c']) && isset($ilrtemplate->learnerinformation->L34c))
									$xml .= "<L34c>" . $ilrtemplate->learnerinformation->L34c . "</L34c>";
								else
									$xml .= "<L34c>" . $row['l34c'] . "</L34c>";

								if(!isset($row['l34d']) && isset($ilrtemplate->learnerinformation->L34d))
									$xml .= "<L34d>" . $ilrtemplate->learnerinformation->L34d . "</L34d>";
								else
									$xml .= "<L34d>" . $row['l34d'] . "</L34d>";

								$xml .= "<L35>" . $row['l35'] .	"</L35>";
								$xml .= "<L36>" . '' . "</L36>";

								if(!isset($row['l37']) && isset($ilrtemplate->learnerinformation->L37))
									$xml .= "<L37>" . $ilrtemplate->learnerinformation->L37 . "</L37>";
								else
									$xml .= "<L37>" . $row['l37'] . "</L37>";

								$xml .= "<L38>" . "00" . "</L38>";

								if(!isset($row['l39']) && isset($ilrtemplate->learnerinformation->L39))
									$xml .= "<L39>" . $ilrtemplate->learnerinformation->L39 . "</L39>";
								else
									$xml .= "<L39>" . $row['l39'] . "</L39>";

								if(!isset($row['l40a']) && isset($ilrtemplate->learnerinformation->L40a))
									$xml .= "<L40a>" . $ilrtemplate->learnerinformation->L40a . "</L40a>";
								else
									$xml .= "<L40a>" . $row['l40a'] . "</L40a>";

								if(!isset($row['l40b']) && isset($ilrtemplate->learnerinformation->L40b))
									$xml .= "<L40b>" . $ilrtemplate->learnerinformation->L40b . "</L40b>";
								else
									$xml .= "<L40b>" . $row['l40b'] . "</L40b>";

								$xml .= "<L41a>" . $row['l41a'] . "</L41a>";
								$xml .= "<L41b>" . $row['l41b'] . "</L41b>";
								$xml .= "<L42a>" . $row['l42a'] . "</L42a>";
								$xml .= "<L42b>" . $row['l42b'] . "</L42b>";
								$xml .= "<L44>" . "</L44>";
								if($row['l45']!='')
									$xml .= "<L45>" . $row['l45'] . "</L45>";
								else
									$xml .= "<L45>9999999999</L45>";
								$xml .= "<L46>" . "</L46>";

								if(!isset($row['l47']) && isset($ilrtemplate->learnerinformation->L47))
									$xml .= "<L47>" . $ilrtemplate->learnerinformation->L47 . "</L47>";
								else
									$xml .= "<L47>" . $row['l47'] . "</L47>";

								$xml .= "<L48>" .  "</L48>";
								$xml .= "<L49a>00</L49a>";
								$xml .= "<L49b>00</L49b>";
								$xml .= "<L49c>00</L49c>";
								$xml .= "<L49d>00</L49d>";
								$xml .= "<L51>".$row['home_email']."</L51>";
								$xml .= "<L17>" . $row['home_postcode'] . 			"</L17>";

								$xml .= "<subaims>0</subaims>";
								$xml .= "</learner>";
								$xml .= "<subaims>0</subaims>";

								// Creating Programme aim
								$xml .= "<programmeaim>";

								if(isset($ilrtemplate->programmeaim->A02))
									$xml .= "<A02>" . $ilrtemplate->programmeaim->A02 . "</A02>";
								else
									$xml .= "<A02>" . "00" . "</A02>";

								if(isset($ilrtemplate->programmeaim->A04))
									$xml .= "<A04>" . $ilrtemplate->programmeaim->A04 . "</A04>";
								else
									$xml .= "<A04>" . "1" . "</A04>";

								if(isset($ilrtemplate->programmeaim->A09))
									$xml .= "<A09>" . $ilrtemplate->programmeaim->A09 . "</A09>";
								else
									$xml .= "<A09>" . "ZPROG001" . "</A09>";

								if(isset($ilrtemplate->programmeaim->A10))
									$xml .= "<A10>" . $ilrtemplate->programmeaim->A10 . "</A10>";
								else
									$xml .= "<A10>" . "99" . "</A10>";

								if(isset($ilrtemplate->programmeaim->A11a))
									$xml .= "<A11a>" . $ilrtemplate->programmeaim->A11a . "</A11a>";
								else
									$xml .= "<A11a>" . "" . "</A11a>";

								if(isset($ilrtemplate->programmeaim->A11b))
									$xml .= "<A11b>" . $ilrtemplate->programmeaim->A11b . "</A11b>";
								else
									$xml .= "<A11b>" . "" . "</A11b>";

								if(isset($ilrtemplate->programmeaim->A14))
									$xml .= "<A14>" . $ilrtemplate->programmeaim->A14 ."</A14>";
								else
									$xml .= "<A14>" . "</A14>";

								if(isset($ilrtemplate->programmeaim->A15))
									$xml .= "<A15>" . $ilrtemplate->programmeaim->A15 ."</A15>";
								else
									$xml .= "<A15>99</A15>";

								if(isset($ilrtemplate->programmeaim->A16))
									$xml .= "<A16>" . $ilrtemplate->programmeaim->A16 . "</A16>";
								else
									$xml .= "<A16>" . "</A16>";

								if(isset($ilrtemplate->aims[0]->A18))
									$xml .= "<A18>" . $ilrtemplate->aims[0]->A18 . "</A18>";
								else
									$xml .= "<A18>" . "</A18>";


								$xml .= "<A26>0</A26>";

								$xml .= "<A27>" . $start_date . "</A27>";
								$xml .= "<A28>" . $end_date . "</A28>";
								$xml .= "<A23>" . $tr->work_postcode . "</A23>";
								$xml .= "<A51a>100</A51a>";


								if(isset($ilrtemplate->programmeaim->A46a))
									$xml .= "<A46a>" . $ilrtemplate->programmeaim->A46a .  "</A46a>";
								else
									$xml .= "<A46a>" . "</A46a>";

								if(isset($ilrtemplate->programmeaim->A46b))
									$xml .= "<A46b>" . $ilrtemplate->programmeaim->A46b . "</A46b>";
								else
									$xml .= "<A46b>" . "</A46b>";

								if(isset($ilrtemplate->programmeaim->A31))
									$xml .= "<A31>" . $ilrtemplate->programmeaim->A31 . "</A31>";
								else
									$xml .= "<A31>" . "</A31>";

								if(isset($ilrtemplate->programmeaim->A40))
									$xml .= "<A40>" . $ilrtemplate->programmeaim->A40 . "</A40>";
								else
									$xml .= "<A40>" . "</A40>";

								if(isset($ilrtemplate->programmeaim->A34))
									$xml .= "<A34>" . $ilrtemplate->programmeaim->A34 . "</A34>";
								else
									$xml .= "<A34>" . "</A34>";

								if(isset($ilrtemplate->programmeaim->A35))
									$xml .= "<A35>" . $ilrtemplate->programmeaim->A35 . "</A35>";
								else
									$xml .= "<A35>" . "</A35>";

								if(isset($ilrtemplate->programmeaim->A46a))
									$xml .= "<A46a>" . $ilrtemplate->programmeaim->A46a . "</A46a>";
								else
									$xml .= "<A46a>" . "</A46a>";

								if(isset($ilrtemplate->programmeaim->A46b))
									$xml .= "<A46b>" . $ilrtemplate->programmeaim->A46b . "</A46b>";
								else
									$xml .= "<A46b>" . "</A46b>";

								if(isset($ilrtemplate->programmeaim->A50))
									$xml .= "<A50>" . $ilrtemplate->programmeaim->A50 . "</A50>";
								else
									$xml .= "<A50>" . "</A50>";

								if(isset($ilrtemplate->programmeaim->A69))
									$xml .= "<A69>" . $ilrtemplate->programmeaim->A69 . "</A69>";
								else
									$xml .= "<A69>" . "</A69>";

								if(isset($ilrtemplate->programmeaim->A70))
									$xml .= "<A70>" . $ilrtemplate->programmeaim->A70 . "</A70>";
								else
									$xml .= "<A70>" . "</A70>";

								$xml .= "</programmeaim>";


								// Creating ESF Aim
								$xml .= "<main>";
								$xml .= "<A01>" . $row['upin'] . "</A01>";

								$xml .= "<A02>00</A02>";

								$xml .= "<A03>" . $l03 . "</A03>";


								$xml .= "<A04>" . "4" . "</A04>";
								$xml .= "<A05>" . "01" . "</A05>";
								$xml .= "<A06>" . "00" . "</A06>";
								$xml .= "<A07>" . "00" . "</A07>";
								$xml .= "<A08>" . "2" . "</A08>";
								$xml .= "<A09>ZESF0001</A09>";

								$xml .= "<A10>70</A10>";
								$xml .= "<A11a>105</A11a>";
								$xml .= "<A11b>" . "999" . "</A11b>";

								$xml .= "<A12a>" . "000" . "</A12a>";
								$xml .= "<A12b>" . "000" . "</A12b>";
								$xml .= "<A13>" . "00000" . "</A13>";

								$xml .= "<A14>" . "00" . "</A14>";
								$xml .= "<A15>99</A15>";

								$xml .= "<A16>" . "</A16>";

								$xml .= "<A17>" . "0" . "</A17>";

								$xml .= "<A18>" . "</A18>";

								$xml .= "<A19>" . "0" . "</A19>";
								$xml .= "<A20>" . "0" . "</A20>";

								$xml .= "<A21>" . "00" . "</A21>";

								$xml .= "<A22>" . "      " . "</A22>";

								$xml .= "<A23></A23>";

								$xml .= "<A24>" . "</A24>";

								$xml .= "<A26>0</A26>";

								$xml .= "<A27>" . $start_date . "</A27>";
								$xml .= "<A28>" . $start_date . "</A28>";
								$xml .= "<A31>" . $start_date . "</A31>";
								$xml .= "<A32>" . "</A32>";
								$xml .= "<A33>" . "     " . "</A33>";

								$xml .= "<A34>2</A34>";
								$xml .= "<A35>1</A35>";

								if(isset($ilrtemplate->aims[0]->A36))
									$xml .= "<A36>" . $ilrtemplate->aims[0]->A36 . "</A36>";
								else
									$xml .= "<A36>" . "   " . "</A36>";

								$xml .= "<A37></A37>";
								$xml .= "<A38></A38>";
								$xml .= "<A39>" . "0" . "</A39>";
								$xml .= "<A40></A40>";
								$xml .= "<A43>" . $row['closure_date'] . "</A43>";

								$xml .= "<A44>" . str_pad('999999999',30,' ',STR_PAD_RIGHT) . "</A44>";
								$xml .= "<A45>" . $row['epcode'] . "</A45>";

								$xml .= "<A46a>999</A46a>";
								$xml .= "<A46b>999</A46b>";

								$xml .= "<A47a>" . "</A47a>";

								$xml .= "<A47b>" . "</A47b>";

								$xml .= "<A48a>" . "</A48a>";

								$xml .= "<A48b>" . "</A48b>";

								$xml .= "<A49>" . "     " . "</A49>";

								$xml .= "<A50>" . "</A50>";

								$xml .= "<A51a>100</A51a>";
								$xml .= "<A52>" . "0.000" . "</A52>";
								$xml .= "<A53>" . "</A53>";

								$xml .= "<A54>" . "</A54>";

								if($row['l45']!='')
									$xml .= "<A55>" . $row['l45'] . "</A55>";
								else
									$xml .= "<A55>9999999999</A55>";

								$xml .= "<A56>" . "</A56>";
								$xml .= "<A57>" . "00" . "</A57>";
								$xml .= "<A58>" . "</A58>";

								$xml .= "<A59>" . "</A59>";

								$xml .= "<A60>" . "</A60>";

								$xml .= "<A61>" . "</A61>";

								$xml .= "<A62>" . "</A62>";

								$xml .= "<A63>" . "</A63>";

								$xml .= "<A64>" . "</A64>";

								$xml .= "<A65>" . "</A65>";

								$xml .= "<A66>" . "</A66>";

								$xml .= "<A67>" . "</A67>";

								$xml .= "<A68>" . "</A68>";

								$xml .= "<A69>" . "</A69>";

								$xml .= "<A70>" . "</A70>";

								$xml .= "<A71>" . "</A71>";

								$xml .= "</main>";

								$xml .= "</ilr>";
								$xml = str_replace("&", "&amp;", $xml);
								$xml = str_replace("'", "&apos;", $xml);
								// getting contract type

								// Override template with matching A09
								if($ilrtemplatetext!='')
									$xml = Ilr2011::CopyILRFields($xml, $ilrtemplatetext);

								$xml = str_replace("'", "&apos;", $xml);

								$sql = "Select contract_type from contracts where id ='$contract_id'";
								$contract_type = DAO::getResultset($link, $sql);
								$contract_type = $contract_type[0][0];

								// $xml = addslashes((string)$xml);
								$contract = addslashes((string)$contract_id);
								$contract_type=addslashes((string)$contract_type);

								$upin = $row['upin'];
								//$l03 = $row['l03'];

								$sql = "insert into ilr (L01,L03, A09, ilr,submission,contract_type,tr_id,is_complete,is_valid,is_approved,is_active,contract_id) values('$upin','$l03','0','$xml','$submission','$contract_type','$tr->id','0','0','0','0','$contract');";
								DAO::execute($link, $sql);
							}
						}
					}
					else
					{
						if($ilrtemplatetext!='')
						{
							$ilrtemplate = Ilr2012::loadFromXML($ilrtemplatetext);
						}

						$sql = <<<SQL
SELECT
  users.ni,
  users.l45,
  users.l24,
  users.l14,
  users.l15,
  users.l16,
  users.l35,
  users.l48,
  users.l42a,
  users.l42b,
  contract_holder.upin,
  users.uln,
  users.home_email,
  l03,
  tr.l28a,
  tr.l28b,
  tr.l34a,
  tr.l34b,
  tr.l34c,
  tr.l34d,
  tr.l36,
  tr.l37,
  tr.l39,
  tr.l40a,
  tr.l40b,
  tr.l41a,
  tr.l41b,
  tr.l47,
  tr.id,
  tr.surname,
  tr.firstnames,
  DATE_FORMAT(tr.dob, '%d/%m/%Y') AS date_of_birth,
  DATE_FORMAT(closure_date, '%d/%m/%Y') AS closure_date,
  tr.ethnicity,
  tr.gender,
  learning_difficulties,
  disability,
  learning_difficulty,
  tr.home_address_line_1,
  tr.home_address_line_2,
  tr.home_address_line_3,
  tr.home_address_line_4,
  tr.home_postcode,
  current_postcode,
  tr.home_telephone,
  country_of_domicile,
  tr.ni,
  prior_attainment_level,
  DATE_FORMAT(tr.start_date, '%d/%m/%Y') AS start_date,
  DATE_FORMAT(target_date, '%d/%m/%Y') AS target_date,
  status_code,
  provider_location_id,
  tr.employer_id,
  provider_location.postcode AS lpcode,
  organisations.edrs AS edrs,
  organisations.legal_name AS employer_name,
  employer_location.postcode AS epcode
FROM
  tr
  LEFT JOIN locations AS provider_location
    ON provider_location.id = tr.provider_location_id
  LEFT JOIN locations AS employer_location
    ON employer_location.id = tr.employer_location_id
  LEFT JOIN organisations
    ON organisations.id = tr.employer_id
  LEFT JOIN contracts
    ON contracts.id = tr.contract_id
  LEFT JOIN organisations AS contract_holder
    ON contract_holder.id = contract_holder
  LEFT JOIN users
    ON users.username = tr.username
WHERE contract_id = '$contract_id'
  AND tr.id = '$tr->id'
SQL;
						$st = $link->query($sql);
						if($st)
						{
							while($row = $st->fetch())
							{
								// here to create ilrs for the first time from training records.
								$xml = '<Learner>';
								$xml .= "<LearnRefNumber>" . $tr->l03 . "</LearnRefNumber>";
								if($row['l45']!='')
									$xml .= "<ULN>" . $row['l45'] . "</ULN>";
								else
									$xml .= "<ULN>9999999999</ULN>";
								$xml .= "<FamilyName>" . $row['surname'] .	"</FamilyName>";
								$xml .= "<GivenNames>" . $row['firstnames'] . "</GivenNames>";
								$xml .= "<DateOfBirth>" . $row['date_of_birth'] . "</DateOfBirth>";
								$xml .= "<Ethnicity>" . $row['ethnicity'] .	"</Ethnicity>";
								$xml .= "<Sex>" . $row['gender'] . "</Sex>";
								if(!isset($row['l14']) && isset($ilrtemplate->LLDDHealthProb))
									$xml .= "<LLDDHealthProb>" . $ilrtemplate->LLDDHealthProb . "</LLDDHealthProb>";
								else
									$xml .= "<LLDDHealthProb>" . $row['l14'] .	"</LLDDHealthProb>";

								$xml .= "<NINumber>" . $row['ni'] . "</NINumber>";

								if(!isset($row['l24']) && isset($ilrtemplate->Domicile))
									$xml .= "<Domicile>" . $ilrtemplate->Domicile . "</Domicile>";
								else
									$xml .= "<Domicile>" . $row['l24'] .		"</Domicile>";

								$xml .= "<PriorAttain>" . $row['l35'] .	"</PriorAttain>";

								if(!isset($row['l39']) && isset($ilrtemplate->Dest))
									$xml .= "<Dest>" . $ilrtemplate->Dest . "</Dest>";
								else
									$xml .= "<Dest>" . $row['l39'] . "</Dest>";

								$xml .= "<LearnerContact><LocType>2</LocType><ContType>1</ContType><PostCode>" . $row['home_postcode'] . "</PostCode></LearnerContact>";
								$xml .= "<LearnerContact><LocType>1</LocType><ContType>2</ContType><PostAdd>";
								$xml .= "<AddLine1>" . $row['home_address_line_1'] . "</AddLine1>";
								$xml .= "<AddLine2>" . $row['home_address_line_2'] . "</AddLine2>";
								$xml .= "<AddLine3>" . $row['home_address_line_3'] . "</AddLine3>";
								$xml .= "<AddLine4>" . $row['home_address_line_4'] . "</AddLine4>";
								$xml .= "</PostAdd></LearnerContact>";
								$xml .= "<LearnerContact><LocType>2</LocType><ContType>2</ContType><PostCode>" . $row['home_postcode'] . "</PostCode></LearnerContact>";
								$xml .= "<LearnerContact><LocType>3</LocType><ContType>2</ContType><TelNumber>" . $row['home_telephone'] . "</TelNumber></LearnerContact>";
								$xml .= "<LearnerContact><LocType>4</LocType><ContType>2</ContType><Email>" . $row['home_email'] . "</Email></LearnerContact>";
								$xml .= "<LLDDandHealthProblem><LLDDType>DS</LLDDType><LLDDCode>" . $row['l15'] . "</LLDDCode></LLDDandHealthProblem>";
								$xml .= "<LLDDandHealthProblem><LLDDType>LD</LLDDType><LLDDCode>" . $row['l16'] . "</LLDDCode></LLDDandHealthProblem>";
								$xml .= "<LearnerFAM><LearnFAMType>EFE</LearnFAMType><LearnFAMCode>" . $row['l28a'] . "</LearnFAMCode></LearnerFAM>";
								$xml .= "<LearnerFAM><LearnFAMType>LSR</LearnFAMType><LearnFAMCode>" . $row['l34a'] . "</LearnFAMCode></LearnerFAM>";
								$xml .= "<LearnerFAM><LearnFAMType>LSR</LearnFAMType><LearnFAMCode>" . $row['l34b'] . "</LearnFAMCode></LearnerFAM>";
								$xml .= "<LearnerFAM><LearnFAMType>LSR</LearnFAMType><LearnFAMCode>" . $row['l34c'] . "</LearnFAMCode></LearnerFAM>";
								$xml .= "<LearnerFAM><LearnFAMType>LSR</LearnFAMType><LearnFAMCode>" . $row['l34d'] . "</LearnFAMCode></LearnerFAM>";
								$xml .= "<LearnerFAM><LearnFAMType>NLM</LearnFAMType><LearnFAMCode>" . $row['l40a'] . "</LearnFAMCode></LearnerFAM>";
								$xml .= "<LearnerFAM><LearnFAMType>NLM</LearnFAMType><LearnFAMCode>" . $row['l40b'] . "</LearnFAMCode></LearnerFAM>";
								$xml .= "<ProviderSpecLearnerMonitoring><ProvSpecLearnMonOccur>A</ProvSpecLearnMonOccur><ProvSpecLearnMon>" . $row['l42a'] . "</ProvSpecLearnMon></ProviderSpecLearnerMonitoring>";
								$xml .= "<ProviderSpecLearnerMonitoring><ProvSpecLearnMonOccur>B</ProvSpecLearnMonOccur><ProvSpecLearnMon>" . $row['l42b'] . "</ProvSpecLearnMon></ProviderSpecLearnerMonitoring>";

								$xml .= "<LearnerEmploymentStatus><EmpStat>" . $row['l47'] . "</EmpStat><DateEmpStatApp>" . $start_date . "</DateEmpStatApp><WorkLocPostCode>" . $tr->work_postcode . "</WorkLocPostCode><EmpId>" . $row['edrs'] . "</EmpId></LearnerEmploymentStatus>";

								$xml .= "<LearningDelivery>";
								$xml .= "<LearnAimRef>ZESF0001</LearnAimRef>";
								$xml .= "<AimType>4</AimType>";
								$xml .= "<AimSeqNumber>1</AimSeqNumber>";
								$xml .= "<LearnStartDate>" . $start_date . "</LearnStartDate>";
								$xml .= "<LearnPlanEndDate>" . $start_date . "</LearnPlanEndDate>";
								$xml .= "<LearnActEndDate>" . $start_date . "</LearnActEndDate>";
								$xml .= "<FundModel>70</FundModel>";
								$xml .= "<ProgType>99</ProgType>";
								$xml .= "<FworkCode></FworkCode>";
								$xml .= "<DelLocPostCode>" . $tr->work_postcode . "</DelLocPostCode>";
								if(isset($ilrtemplate))
								{
									$xml .= "<ContOrg>" . $ilrtemplate->LearningDelivery->ContOrg . "</ContOrg>";
									$xml .= "<ESFProjDosNumber>" . $ilrtemplate->LearningDelivery->ESFProjDosNumber . "</ESFProjDosNumber>";
									$xml .= "<ESFLocProjNumber>" . $ilrtemplate->LearningDelivery->ESFLocProjNumber . "</ESFLocProjNumber>";
								}
								$xml .= "<PropFundRemain>100</PropFundRemain>";
								$xml .= "<CompStatus>2</CompStatus>";
								$xml .= "<Outcome>1</Outcome>";
								$xml .= "<LearningDeliveryFAM><LearnDelFAMType>SOF</LearnDelFAMType><LearnDelFAMCode>105</LearnDelFAMCode></LearningDeliveryFAM>";
								$xml .= "</LearningDelivery>";

								$xml .= "</Learner>";

								$xml = str_replace("&", "&amp;", $xml);
								$xml = str_replace("'", "&apos;", $xml);

								$sql = "Select contract_type from contracts where id ='$contract_id'";
								$contract_type = DAO::getResultset($link, $sql);
								$contract_type = $contract_type[0][0];
								$contract = addslashes((string)$contract_id);
								$contract_type=addslashes((string)$contract_type);
								$tr_id = $tr->id;
								if(DB_NAME=='am_reed')
									$is_active = 0;
								else
									$is_active = 1;
								$sql = "insert into ilr (L01,L03, A09, ilr,submission,contract_type,tr_id,is_complete,is_valid,is_approved,is_active,contract_id) values('','$l03','0','$xml','$submission','$contract_type','$tr_id','0','0','0','$is_active','$contract');";
								DAO::execute($link, $sql);

							}
						}
					}
				}

				DAO::transaction_commit($link);
			}
			catch(Exception $e)
			{
				DAO::transaction_rollback($link, $e);
				throw new WrappedException($e);
			}

		}
		if ($newUser) {
			$_SESSION['bc']->pop(); // Remove the reference to Edit User from the breadcrumb trail
		}
		http_redirect("do.php?_action=read_user&username=" . $vo->username);
	}


}
?>
