<?php
class enrol_learners_from_csv_file implements IAction
{
	public function execute(PDO $link)
	{

		$edrs = '';
		$handle = fopen("ilr.csv","r");
		$st = fgets($handle);
		
		$tr_id=0;
		$user = new User();
		$tr = new TrainingRecord();
		$text = '';		
		
	
		while(!feof($handle))
		{

			$st = fgets($handle);
			
			$arr = explode(",",$st);
						
			$username = DAO::getSingleValue($link, "select username from users where uln = '$arr[2]'");
			$course_id = $arr[135];
			$framework_id = DAO::getSingleValue($link, "select framework_id from courses where id = $course_id");
			$group = trim($arr[136]);
//			$group_id = DAO::getSingleValue($link, "select id from groups where title = '$group' and courses_id = 29");
			$group_id = (int)$arr[137];
			$contract_id = 9;
			$start_date = Date::toMySQL($arr[84]);
			$end_date = Date::toMySQL($arr[85]);

			$exists = DAO::getSingleValue($link, "select count(*) from tr where username = '$username'");
			
			if(true)
			{
			
			if($username=='' || $framework_id == '' || $group_id == '' || $start_date=='' || $end_date =='')
			{
				throw new Exception("Username = " . $username . "\n group id = " . $group_id . "\n exists =  " . $exists . "\n L03=" . $arr[2]);
			}
			else
			{
				
				$sd = $start_date;
				$ed = $end_date;
				
				
		$user = User::loadFromDatabase($link, $username);
		$course = Course::loadFromDatabase($link, $course_id);

		$que = "select id from locations where organisations_id='$course->organisations_id'";
		$location_id = trim(DAO::getSingleValue($link, $que));
		
		$provider = Location::loadFromDatabase($link, $location_id);

		$l03 = DAO::getSingleValue($link, "select l03 from tr where username = '$username'");
//		if($count>0)
//			throw new Exception("The Learner "  . " " . $user->surname . " has already been enrolled and active");

		$link->beginTransaction();
		try
		{
			
			
		// Create training record 
		$tr = new TrainingRecord();
		$tr->populate($user, true);
		$tr->contract_id = $contract_id;
		$tr->start_date = $start_date;
		$tr->target_date = $end_date;
		$tr->status_code = 1;
		$tr->provider_id = $course->organisations_id;
		$tr->provider_location_id = $location_id;
/*		$tr->provider_saon_start_number = $provider->saon_start_number;
		$tr->provider_saon_start_suffix = $provider->saon_start_suffix; 
		$tr->provider_saon_end_number = $provider->saon_end_number;
		$tr->provider_saon_end_suffix = $provider->saon_end_suffix; 
		$tr->provider_saon_description = $provider->saon_description;
		$tr->provider_paon_start_number = $provider->paon_start_number; 
		$tr->provider_paon_start_suffix = $provider->paon_start_suffix; 
		$tr->provider_paon_end_number = $provider->paon_end_number;
		$tr->provider_paon_end_suffix = $provider->paon_end_suffix;
		$tr->provider_paon_description = $provider->paon_description; 
		$tr->provider_street_description = $provider->street_description;
		$tr->provider_locality = $provider->locality;
		$tr->provider_town = $provider->town;
		$tr->provider_county = $provider->county;*/
		$tr->provider_address_line_1 = $provider->address_line_1;
		$tr->provider_address_line_2 = $provider->address_line_2;
		$tr->provider_address_line_3 = $provider->address_line_3;
		$tr->provider_address_line_4 = $provider->address_line_4;
		$tr->provider_postcode = $provider->postcode; 
		$tr->provider_telephone = $provider->telephone;
		$tr->ethnicity = $user->ethnicity;
		$tr->work_experience = 1;

		if($l03=='')
		{		
			$l03 = (int)DAO::getSingleValue($link, "select max(cast(l03 as unsigned)) from tr");
			$l03 += 1;
		}
		
		$tr->l03 = str_pad($l03,12,'0',STR_PAD_LEFT);
		
		$tr->l03 = $arr[2];
		
		$tr->save($link); 	

		$tr_id = $tr->id;
		$identity = $user->getFullyQualifiedName();
		
		// Enrol on a course and put in a  group
		
		$framework_id = '0';
		$qualification_id = '0';
		
		if($tr_id=='' || $course_id=='')	
		{
			throw new Exception("Could not enrol on a course! insufficient information given");	
		}
		
		$que = "select main_qualification_id from courses where id='$course_id'";
		$qualification_id = DAO::getSingleValue($link, $que);
		
		if($qualification_id=='')
		{
			$qualification_id  = '0';
			$que = "select framework_id from courses where id='$course_id'";
			$framework_id = DAO::getSingleValue($link, $que);
			$fid = $framework_id;
		}
		
// enroling on a course
$query = <<<HEREDOC
insert into
	courses_tr (course_id, tr_id, qualification_id, framework_id)
values($course_id, $tr_id, '$qualification_id', $framework_id);
HEREDOC;
			DAO::execute($link, $query);


		
		
if($group_id!='')		
{		
// 	attaching to a group
$query = <<<HEREDOC
insert into
	group_members (groups_id, tr_id, member)
values($group_id, $tr_id, 0);
HEREDOC;
	DAO::execute($link, $query);
}
		
		
// Check if this course has a framework attached to it and get framework id
$que = "select framework_id from courses where id='$course_id'";
$fid = DAO::getSingleValue($link, $que);

$que = "select id from student_frameworks where tr_id='$tr_id'";
$tr_framework_id = DAO::getSingleValue($link, $que);

if($fid!='')
{
	if($tr_framework_id=='')
	{
		
// Importing framework
$query = <<<HEREDOC
insert into
	student_frameworks
select title, id, '$tr_id', sector, comments, duration_in_months
from frameworks
	where id = '$fid';
HEREDOC;
		DAO::execute($link, $query);

// importing qualification from framework		
$query = <<<HEREDOC
insert into
	student_qualifications
select 
id, 
'$fid', 
'$tr_id', 
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
NULL,
NULL 
from framework_qualifications
LEFT JOIN course_qualifications_dates on course_qualifications_dates.qualification_id = framework_qualifications.id and 
course_qualifications_dates.framework_id = framework_qualifications.framework_id and 
course_qualifications_dates.internaltitle = framework_qualifications.internaltitle
	where framework_qualifications.framework_id = '$fid' and course_qualifications_dates.course_id='$course_id';
HEREDOC;
		DAO::execute($link, $query);
		
// Importing milestones
$query = <<<HEREDOC
insert into
	student_milestones
select *, '$tr_id',1
from milestones
	where framework_id = '$fid';
HEREDOC;
		DAO::execute($link, $query);
		
	}
}


			// Creating ILR
			//$sql = "SELECT contract_holder.upin, uln, l03, l28a, l28b, l34a, l34b, l34c, l34d, l36, l37, l39, l40a, l40b, l41a, l41b, l47, tr.id, surname, firstnames, DATE_FORMAT(dob,'%d/%m/%Y') as date_of_birth, DATE_FORMAT(closure_date, '%d/%m/%Y') as closure_date, ethnicity, gender, learning_difficulties, disability,learning_difficulty, home_postcode, TRIM(CONCAT(IF(home_paon_start_number is not null,TRIM(home_paon_start_number),''),if(home_paon_start_suffix is not null,TRIM(home_paon_start_suffix),''),' ', if(home_paon_end_number is not null,TRIM(home_paon_end_number),''),if(home_paon_end_suffix is not null,TRIM(home_paon_end_suffix),''),' ' , IF(home_paon_description IS NOT NULL,TRIM(home_paon_description),''),' ',if(home_street_description is not null,TRIM(home_street_description),''))) as L18, home_locality, home_town,home_county, current_postcode, home_telephone, country_of_domicile, ni, prior_attainment_level,DATE_FORMAT(tr.start_date,'%d/%m/%Y') as start_date, DATE_FORMAT(target_date,'%d/%m/%Y') as target_date, status_code,provider_location_id, employer_id, provider_location.postcode as lpcode, organisations.edrs as edrs, organisations.legal_name as employer_name,employer_location.postcode as epcode FROM tr LEFT JOIN locations as provider_location ON provider_location.id = tr.provider_location_id LEFT JOIN locations as employer_location ON employer_location.id = tr.employer_location_id LEFT JOIN organisations ON organisations.id = tr.employer_id 	LEFT JOIN contracts on contracts.id = tr.contract_id LEFT JOIN organisations as contract_holder on contract_holder.id = contract_holder WHERE contract_id = '$contract_id' and tr.id = '$tr_id'";
			$sql = <<<SQL
SELECT
  contract_holder.upin,
  uln,
  l03,
  l28a,
  l28b,
  l34a,
  l34b,
  l34c,
  l34d,
  l36,
  l37,
  l39,
  l40a,
  l40b,
  l41a,
  l41b,
  l47,
  tr.id,
  surname,
  firstnames,
  DATE_FORMAT(dob, '%d/%m/%Y') AS date_of_birth,
  DATE_FORMAT(closure_date, '%d/%m/%Y') AS closure_date,
  ethnicity,
  gender,
  learning_difficulties,
  disability,
  learning_difficulty,
  home_address_line_1,
  home_address_line_2,
  home_address_line_3,
  home_address_line_4,
  home_postcode,
  current_postcode,
  home_telephone,
  country_of_domicile,
  ni,
  prior_attainment_level,
  DATE_FORMAT(tr.start_date, '%d/%m/%Y') AS start_date,
  DATE_FORMAT(target_date, '%d/%m/%Y') AS target_date,
  status_code,
  provider_location_id,
  employer_id,
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
WHERE contract_id = '$contract_id'
  AND tr.id = '$tr_id'
SQL;

			$contract_year = DAO::getSingleValue($link,"select contract_year from contracts where id='$contract_id'");

			$submission = DAO::getSingleValue($link, "select submission from central.lookup_submission_dates where last_submission_date>=CURDATE() and contract_year = '$contract_year' order by last_submission_date LIMIT 1;");
			
			$st = $link->query($sql);
			if($st)
			{

				while($row = $st->fetch())
				{	
					// here to create ilrs for the first time from training records.					
					$xml = '<ilr>';
					$xml .= "<learner>";
					$xml .= "<L01>" . $row['upin'] . "</L01>";
					$xml .= "<L02>" . "99" . "</L02>";
					$xml .= "<L03>" . str_pad($l03,12,'0',STR_PAD_LEFT) . "</L03>";
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
					$xml .= "<L14>" . $row['learning_difficulties'] .	"</L14>";
					$xml .= "<L15>" . $row['disability'] . 				"</L15>";
					$xml .= "<L16>" . $row['learning_difficulty'] .		"</L16>";
					$xml .= "<L17>" . $row['home_postcode'] . 			"</L17>";
					$xml .= "<L18>" . $row['home_address_line_1'] . "</L18>";
					$xml .= "<L19>" . $row['home_address_line_2'] . 			"</L19>";
					$xml .= "<L20>" . $row['home_address_line_3'] . 				"</L20>";
					$xml .= "<L21>" . $row['home_address_line_4'] . 			"</L21>";
					$xml .= "<L22>" . $row['home_postcode'] .		"</L22>";
					$xml .= "<L23>" . $row['home_telephone'] . 			"</L23>";
					$xml .= "<L24>" . $row['country_of_domicile'] .		"</L24>";
					$xml .= "<L25>" . "</L25>";
					$xml .= "<L26>" . $row['ni'] . 						"</L26>";
					$xml .= "<L27>" . "1" . "</L27>";
					$xml .= "<L28a>" . $row['l28a'] . "</L28a>";
					$xml .= "<L28b>" . $row['l28b'] . "</L28b>";
					$xml .= "<L29>" . "00" . "</L29>";
					$xml .= "<L31>" . "000000" . "</L31>";
					$xml .= "<L32>" . "00" . "</L32>";
					$xml .= "<L33>" . "0.0000" . "</L33>";
					$xml .= "<L34a>" . $row['l34a'] . "</L34a>";
					$xml .= "<L34b>" . $row['l34b'] . "</L34b>";
					$xml .= "<L34c>" . $row['l34c'] . "</L34c>";
					$xml .= "<L34d>" . $row['l34d'] . "</L34d>";
					$xml .= "<L35>" . $row['prior_attainment_level'] .	"</L35>";
					$xml .= "<L36>" . $row['l36'] . "</L36>";
					$xml .= "<L37>" . $row['l37'] . "</L37>";
					$xml .= "<L38>" . "00" . "</L38>";
					$xml .= "<L39>" . $row['l39'] . "</L39>";
					$xml .= "<L40a>" . $row['l40a'] . "</L40a>";
					$xml .= "<L40b>" . $row['l40b'] . "</L40b>";
					$xml .= "<L41a>" . $row['l41a'] . "</L41a>";	
					$xml .= "<L41b>" . $row['l41b'] . "</L41b>";	
					$xml .= "<L42a>" . "</L42a>";	
					$xml .= "<L42b>" . "</L42b>";	
					$xml .= "<L44>" . "</L44>";
//					$xml .= "<L45>" . $row['uln'] . "</L45>";	
					$xml .= "<L45>9999999999</L45>";	
					$xml .= "<L46>" . "</L46>";
					$xml .= "<L47>" . $row['l47'] . "</L47>";
					$xml .= "<L48>" . "</L48>";
					$xml .= "<L49a>00</L49a>";
					$xml .= "<L49b>00</L49b>";
					$xml .= "<L49c>00</L49c>";
					$xml .= "<L49d>00</L49d>";
										
					// Getting no. of sub aims
					$sql ="select count(*) from student_qualifications where tr_id ={$row['id']} and qualification_type!='NVQ'";
					$sub_aims = DAO::getSingleValue($link,$sql);
					
					$xml .= "<subaims>" . $sub_aims . "</subaims>";
					$xml .= "</learner>";
					$xml .= "<subaims>" . $sub_aims . "</subaims>";

					// Creating Programme aim
					$xml .= "<programmeaim>";
					$xml .= "<A02>" . "99" . "</A02>";
					$xml .= "<A04>" . "35" . "</A04>";
					$xml .= "<A09>" . "ZPROG001" . "</A09>";
					$xml .= "<A10>" . "45" . "</A10>";
					$xml .= "<A15>" . "</A15>";
					$xml .= "<A16>" . "</A16>";
					$xml .= "<A26>" . "</A26>";
					$xml .= "<A27>" . $start_date . "</A27>";
					$xml .= "<A28>" . $end_date . "</A28>";
					$xml .= "<A23>" . $tr->work_postcode . "</A23>";
					$xml .= "<A51a>" . "</A51a>";
					$xml .= "<A14>" . "</A14>";
					$xml .= "<A46a>" . "</A46a>";
					$xml .= "<A46b>" . "</A46b>";
					$xml .= "<A02>" . "</A02>";
					$xml .= "<A31>" . "</A31>";
					$xml .= "<A40>" . "</A40>";
					$xml .= "<A34>" . "</A34>";
					$xml .= "<A35>" . "</A35>";
					$xml .= "<A50>" . "</A50>";
					$xml .= "</programmeaim>";
					
					
					// Creating main aim					
					$sql_main = "select student_qualifications.*, tr.start_date as lsd,tr.work_postcode, tr.target_date as led from student_qualifications inner join tr on tr.id = student_qualifications.tr_id INNER JOIN framework_qualifications on framework_qualifications.framework_id = student_qualifications.framework_id AND framework_qualifications.id	 = student_qualifications.id and framework_qualifications.internaltitle	 = student_qualifications.internaltitle where tr_id = {$row['id']} and framework_qualifications.main_aim=1";
					$st2 = $link->query($sql_main);
					if($st2)
					{
						while($row_main = $st2->fetch())
						{	
							$xml .= "<main>";
							$xml .= "<A01>" . $row['upin'] . "</A01>";
							$xml .= "<A02>99</A02>";
							$xml .= "<A03>" . str_pad($l03,12,'0',STR_PAD_LEFT) . "</A03>";
							$xml .= "<A04>" . "30" . "</A04>";
							$xml .= "<A05>" . "01" . "</A05>";
							$xml .= "<A06>" . "00" . "</A06>";
							$xml .= "<A07>" . "00" . "</A07>";
							$xml .= "<A08>" . "2" . "</A08>";
							$xml .= "<A09>" . str_replace("/" , "", $row_main['id']) . "</A09>";
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
							$xml .= "<A23>" . $row_main['work_postcode'] . "</A23>";
							$xml .= "<A24>" . "</A24>";
							$xml .= "<A26>" . "</A26>";
							$xml .= "<A27>" . substr($row_main['lsd'],8,2) . '/' . substr($row_main['lsd'],5,2) . '/' . substr($row_main['lsd'],0,4) . "</A27>";
							$xml .= "<A28>" . substr($row_main['led'],8,2) . '/' . substr($row_main['led'],5,2) . '/' . substr($row_main['led'],0,4) . "</A28>";
							$xml .= "<A31>" . $row_main['actual_end_date'] . "</A31>";
							$xml .= "<A32>" . "</A32>";
							$xml .= "<A33>" . "     " . "</A33>";
							$xml .= "<A34>" . "</A34>";
							$xml .= "<A35>" . "</A35>";
							$xml .= "<A36>" . "   " . "</A36>";
							$xml .= "<A37>" . $row_main['unitsCompleted'] . "</A37>";
							$xml .= "<A38>" . $row_main['units'] . "</A38>";
							$xml .= "<A39>" . "0" . "</A39>";
							$xml .= "<A40>" . $row_main['achievement_date'] . "</A40>";
							$xml .= "<A43>" . $row['closure_date'] . "</A43>";

							if($row['edrs']!='')
								$xml .= "<A44>" . str_pad($row['edrs'],30,' ',STR_PAD_RIGHT) . "</A44>";
							else
								$xml .= "<A44>" . str_pad($row['employer_name'],30,' ',STR_PAD_RIGHT) . "</A44>";
							
							$xml .= "<A45>" . $row['epcode'] . "</A45>";
							$xml .= "<A46a>" . "</A46a>";
							$xml .= "<A46b>" . "</A46b>";
							$xml .= "<A47a>" . "</A47a>";
							$xml .= "<A47b>" . "</A47b>";
							$xml .= "<A48a>" . "</A48a>";
							$xml .= "<A48b>" . "</A48b>";
							$xml .= "<A49>" . "     " . "</A49>";
							$xml .= "<A50>" . "</A50>";
							$xml .= "<A51a>" ."</A51a>";
							$xml .= "<A52>" . "00000" . "</A52>";
							$xml .= "<A53>" . "</A53>";
							$xml .= "<A54>" . "</A54>";
							$xml .= "<A55>9999999999</A55>";
							$xml .= "<A56>" . "</A56>";
							$xml .= "<A57>" . "00" . "</A57>";
							$xml .= "</main>";
						}
					}
					
					
					// Creating Sub Aims out of framework
					$sql_main = "select student_qualifications.*, tr.work_postcode, tr.start_date as lsd, tr.target_date as led from student_qualifications inner join tr on tr.id = student_qualifications.tr_id INNER JOIN framework_qualifications on framework_qualifications.framework_id = student_qualifications.framework_id AND framework_qualifications.id	 = student_qualifications.id and framework_qualifications.internaltitle	 = student_qualifications.internaltitle where tr_id = {$row['id']} and framework_qualifications.main_aim<>1";
					$st3 = $link->query($sql_main);
					if($st3)
					{
						$learningaim=2;	
						while($row_sub = $st3->fetch())
						{	
							$xml .= "<subaim>";
							$xml .= "<A01>" . $row['upin'] . "</A01>";
							$xml .= "<A02>99</A02>";
							$xml .= "<A03>" . str_pad($l03,12,'0',STR_PAD_LEFT) .  "</A03>";
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
							$xml .= "<A26>" . "</A26>";
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
								$xml .= "<A44>" . str_pad($row['employer_name'],30,' ',STR_PAD_RIGHT) . "</A44>";
							
							$xml .= "<A45>" . "</A45>";
							$xml .= "<A46a>" . "</A46a>";
							$xml .= "<A46b>" . "</A46b>";
							$xml .= "<A47a>" . "</A47a>";
							$xml .= "<A47b>" . "</A47b>";
							$xml .= "<A48a>" . "</A48a>";
							$xml .= "<A48b>" . "</A48b>";
							$xml .= "<A49>" . "     " . "</A49>";
							$xml .= "<A50>" . "</A50>";
							$xml .= "<A51a>" ."</A51a>";
							$xml .= "<A52>" . "00000" . "</A52>";
							$xml .= "<A53>" . "</A53>";
							$xml .= "<A54>" . "</A54>";
							$xml .= "<A55>9999999999</A55>";
							$xml .= "<A56>" . "</A56>";
							$xml .= "<A57>" . "00" . "</A57>";
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
							$xml .= "<A02>99</A02>";
							$xml .= "<A03>" . str_pad($l03,12,'0',STR_PAD_LEFT) . "</A03>";
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
							$xml .= "<A26>" . "</A26>";
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
								$xml .= "<A44>" . str_pad($row['employer_name'],30,' ',STR_PAD_RIGHT) . "</A44>";

							$xml .= "<A45>" . "</A45>";
							$xml .= "<A46a>" . "</A46a>";
							$xml .= "<A46b>" . "</A46b>";
							$xml .= "<A47a>" . "</A47a>";
							$xml .= "<A47b>" . "</A47b>";
							$xml .= "<A48a>" . "</A48a>";
							$xml .= "<A48b>" . "</A48b>";
							$xml .= "<A49>" . "     " . "</A49>";
							$xml .= "<A50>" . "</A50>";
							$xml .= "<A51a>" ."</A51a>";
							$xml .= "<A52>" . "00000" . "</A52>";
							$xml .= "<A53>" . "</A53>";
							$xml .= "<A54>" . "</A54>";
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
				
					$sql = "Select contract_type from contracts where id ='$contract_id'";
					$contract_type = DAO::getResultset($link, $sql);
					$contract_type = $contract_type[0][0];					
					
					// $xml = addslashes((string)$xml);
					$contract = addslashes((string)$contract_id);
					$contract_type=addslashes((string)$contract_type);
					
					$upin = $row['upin'];
					//$l03 = $row['l03'];
					
					$sql = "insert into ilr (L01,L03, A09, ilr,submission,contract_type,tr_id,is_complete,is_valid,is_approved,is_active,contract_id) values('$upin','$l03','0','$xml','$submission','$contract_type','$tr_id','0','0','0','1','$contract');";
					DAO::execute($link, $sql);
				}
			}
						$link->commit();
			}
			catch(Exception $e)
			{
				$link->rollback();
				throw new WrappedException($e);
			}
				
				
			}	
			}
		}
		
		fclose($handle);
	}
}
?>