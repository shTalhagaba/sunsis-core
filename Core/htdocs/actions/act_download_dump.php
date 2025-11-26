<?php
class download_dump implements IAction
{
	public function execute(PDO $link)
	{
		set_time_limit(0);
		ini_set('memory_limit','2048M');

		$client_db_name = DB_NAME;
		if (!file_exists("../uploads/" . $client_db_name . "/data_dump"))
		{
			mkdir("../uploads/" . $client_db_name . "/data_dump", 0777, true);
		}
// Learners CSV
		$CSVFileName = "../uploads/" . $client_db_name . "/data_dump/learners.csv";
		$FileHandle = fopen($CSVFileName, 'w') or die("can't open file");
		fclose($FileHandle);
		$fp = fopen($CSVFileName, 'w');

		$referral_date = "";
		if(DB_NAME=="am_reed_demo" || DB_NAME=="am_reed")
		{
			$referral_date = " users.referral_date, ";
		}
		$sql = <<<HEREDOC
SELECT
DISTINCT
tr.id
,ilr_compstatus.CompStatus_Desc AS status_code
,lookup_programme_type.`description` AS programme_type
,users.`dob`
,tr.`l03`
,tr.`ni`
,users.`enrollment_no`
,users.l35 AS prior_attainment
,tr.`l36`
,IF(tr.target_date < CURDATE(),100,`student milestones subquery`.target) AS target
,IF(tr.l36>=(IF(tr.target_date < CURDATE(),100,`student milestones subquery`.target)),"YES","NO") AS on_track
,tr.ilr_status AS valid_ilr
,contracts.title AS contract
,assessor_review.meeting_date AS last_review
,assessor_review.comments AS assessment_status
,CONCAT(assessorsng.firstnames,' ',assessorsng.surname) AS assessor
,IF(CONCAT(tutorsng.firstnames,' ',tutorsng.surname) IS NOT NULL, CONCAT(tutorsng.firstnames,' ',tutorsng.surname), '') AS tutor
,tr.`start_date`
,tr.`target_date`
,tr.`closure_date`
,courses.`title` AS course_title
,frameworks.`title` AS framework_title
,(SELECT description from lookup_pre_assessment where id = users.numeracy) AS NumeracyTest
,(SELECT description from lookup_pre_assessment where id = users.literacy) AS LiteracyTest
,(SELECT description from lookup_pre_assessment where id = users.esol) AS ESOLTest
,employers.`legal_name` AS employer
,locations.`full_name` AS location
,providers.legal_name
,tr.`uln`
,users.`job_role`
,locations.`telephone`
,tr.`home_address_line_1`
,tr.`home_address_line_2`
,tr.`home_address_line_3`
,tr.`home_address_line_4`
,tr.`home_postcode`
,tr.`home_mobile`
,locations.`postcode`
,tr.`gender`
,((DATE_FORMAT(tr.start_date,'%Y') - DATE_FORMAT(tr.dob,'%Y')) - (DATE_FORMAT(tr.start_date,'00-%m-%d') < DATE_FORMAT(tr.dob,'00-%m-%d'))) AS age
,tr.`home_email`
,tr.`firstnames`
,tr.`surname`
,IF(tr.l39=95,'Continuing',IF(tr.l39=10,'Full Time Employment',IF(tr.l39=97,'Other',IF(tr.l39=98,'Destination unknown',tr.l39)))) AS destination
,tr.`ethnicity`
,users.`created` AS learner_created_date
,users.l14 AS lldd
,users.username
,users.job_role
,lookup_user_types.`description` AS USER
,frameworks.id AS framework_id
,frameworks.title AS framework_title
,frameworks.framework_code
,frameworks.`framework_type`
,frameworks.`duration_in_months`
,courses.id AS course_id
,courses.title AS course_title
,courses.`organisations_id` AS course_provider
,courses.`course_start_date`
,courses.`course_end_date`
,(SELECT GROUP_CONCAT(id) FROM groups INNER JOIN group_members ON group_members.`groups_id` = groups.id WHERE group_members.`tr_id` = tr.id) AS group_id
,(SELECT GROUP_CONCAT(CONCAT(firstnames, ' ',surname)) FROM groups INNER JOIN users ON users.id = groups.assessor INNER JOIN group_members ON group_members.`groups_id` = groups.id WHERE group_members.`tr_id` = tr.id) AS group_assessor
,(SELECT GROUP_CONCAT(CONCAT(firstnames, ' ',surname)) FROM groups INNER JOIN users ON users.id = groups.verifier INNER JOIN group_members ON group_members.`groups_id` = groups.id WHERE group_members.`tr_id` = tr.id) AS group_verifier
,(SELECT GROUP_CONCAT(CONCAT(firstnames, ' ',surname)) FROM groups INNER JOIN users ON users.id = groups.tutor INNER JOIN group_members ON group_members.`groups_id` = groups.id WHERE group_members.`tr_id` = tr.id) AS group_tutor
,(SELECT GROUP_CONCAT(title) FROM groups INNER JOIN group_members ON group_members.`groups_id` = groups.id WHERE group_members.`tr_id` = tr.id) AS group_name
,employers.id AS employer_id
,employers.legal_name AS employer_name
,employers.`edrs` AS edrs
,locations.`address_line_1` AS employer_address_line_1
,locations.`address_line_2` AS employer_address_line_2
,locations.`address_line_3` AS employer_address_line_3
,locations.`address_line_4` AS employer_address_line_4
,locations.`telephone` AS employer_telephone
,locations.postcode AS employer_postcode
,locations.`contact_name`
,locations.`contact_telephone`
,locations.`contact_email`
,employers.`retailer_code`
#,student_qualifications.id AS qualification_id
#,student_qualifications.internaltitle AS qualification_title
#,student_qualifications.`qualification_type`
#,student_qualifications.`level`
#,student_qualifications.`awarding_body`
,contracts.frequency AS frequency
,contracts.subsequent AS subsequent
,meeting_dates.all_dates
,CONCAT(advisers.firstnames, ' ',advisers.surname) AS advisor,
#users.referral_source,
(SELECT description FROM lookup_referral_source WHERE id = users.referral_source) AS referral_source,
$referral_date
users.initial_appointment_date
FROM
users
LEFT JOIN lookup_user_types ON lookup_user_types.id = users.`type`
LEFT JOIN tr ON tr.username = users.username
#LEFT JOIN student_qualifications ON student_qualifications.tr_id = tr.id
LEFT JOIN contracts ON contracts.id = tr.`contract_id`
LEFT JOIN courses_tr ON courses_tr.`tr_id` = tr.id
LEFT JOIN courses ON courses.id = courses_tr.`course_id`
LEFT JOIN frameworks ON frameworks.id = courses.`framework_id`
LEFT JOIN lookup_programme_type ON lookup_programme_type.`code` = courses.`programme_type`
LEFT JOIN assessor_review ON assessor_review.tr_id = tr.id AND CONCAT(assessor_review.id,assessor_review.meeting_date) = (SELECT MAX(CONCAT(id,meeting_date)) FROM assessor_review WHERE tr_id = tr.id AND meeting_date IS NOT NULL AND meeting_date!='0000-00-00')
#LEFT JOIN group_members ON group_members.tr_id = tr.id
#LEFT JOIN groups ON group_members.groups_id = groups.id
#LEFT JOIN users AS assessors ON groups.assessor = assessors.id
LEFT JOIN users AS assessorsng ON assessorsng.id = tr.assessor
LEFT JOIN users AS tutorsng ON tutorsng.id = tr.tutor
LEFT JOIN users AS advisers ON advisers.username = users.who_created
LEFT JOIN organisations AS employers ON employers.id = tr.`employer_id`
LEFT JOIN locations ON locations.id = tr.`employer_location_id`
LEFT JOIN organisations AS providers ON providers.id = tr.`provider_id`
LEFT JOIN lis201213.ilr_compstatus ON ilr_compstatus.CompStatus = tr.status_code
LEFT OUTER JOIN (
    SELECT
        tr_id,
        meeting_date,
        GROUP_CONCAT(meeting_date) AS all_dates
    FROM assessor_review
        GROUP BY assessor_review.tr_id HAVING meeting_date!='0000-00-00'
) AS `meeting_dates` ON `meeting_dates`.tr_id = tr.id

LEFT OUTER JOIN (
        SELECT
	tr.id,
	IF(tr.target_date < CURDATE(),100,
	(CASE TIMESTAMPDIFF(MONTH, tr.start_date, CURDATE())
		WHEN -1 THEN 0
		WHEN -2 THEN 0
		WHEN -3 THEN 0
		WHEN -4 THEN 0
		WHEN -5 THEN 0
		WHEN -6 THEN 0
		WHEN -7 THEN 0
		WHEN -8 THEN 0
		WHEN -9 THEN 0
		WHEN -10 THEN 0
		WHEN 0 THEN 0
		WHEN 1 THEN AVG(student_milestones.month_1)
		WHEN 2 THEN AVG(student_milestones.month_2)
		WHEN 3 THEN AVG(student_milestones.month_3)
		WHEN 4 THEN AVG(student_milestones.month_4)
		WHEN 5 THEN AVG(student_milestones.month_5)
		WHEN 6 THEN AVG(student_milestones.month_6)
		WHEN 7 THEN AVG(student_milestones.month_7)
		WHEN 8 THEN AVG(student_milestones.month_8)
		WHEN 9 THEN AVG(student_milestones.month_9)
		WHEN 10 THEN AVG(student_milestones.month_10)
		WHEN 11 THEN AVG(student_milestones.month_11)
		WHEN 12 THEN AVG(student_milestones.month_12)
		WHEN 13 THEN AVG(student_milestones.month_13)
		WHEN 14 THEN AVG(student_milestones.month_14)
		WHEN 15 THEN AVG(student_milestones.month_15)
		WHEN 16 THEN AVG(student_milestones.month_16)
		WHEN 17 THEN AVG(student_milestones.month_17)
		WHEN 18 THEN AVG(student_milestones.month_18)
		WHEN 19 THEN AVG(student_milestones.month_19)
		WHEN 20 THEN AVG(student_milestones.month_20)
		WHEN 21 THEN AVG(student_milestones.month_21)
		WHEN 22 THEN AVG(student_milestones.month_22)
		WHEN 23 THEN AVG(student_milestones.month_23)
		WHEN 24 THEN AVG(student_milestones.month_24)
		WHEN 25 THEN AVG(student_milestones.month_25)
		WHEN 26 THEN AVG(student_milestones.month_26)
		WHEN 27 THEN AVG(student_milestones.month_27)
		WHEN 28 THEN AVG(student_milestones.month_28)
		WHEN 29 THEN AVG(student_milestones.month_29)
		WHEN 30 THEN AVG(student_milestones.month_30)
		WHEN 31 THEN AVG(student_milestones.month_31)
		WHEN 32 THEN AVG(student_milestones.month_32)
		WHEN 33 THEN AVG(student_milestones.month_33)
		WHEN 34 THEN AVG(student_milestones.month_34)
		WHEN 35 THEN AVG(student_milestones.month_35)
		WHEN 36 THEN AVG(student_milestones.month_36)
		ELSE 100
	END))
	AS `target`
FROM
	tr
	LEFT JOIN student_milestones ON student_milestones.tr_id = tr.id
	LEFT JOIN student_qualifications ON student_qualifications.id = student_milestones.qualification_id AND student_qualifications.tr_id = student_milestones.tr_id
	WHERE chosen=1 AND student_qualifications.aptitude != 1
GROUP BY
	tr.id ) AS `student milestones subquery` ON tr.id = `student milestones subquery`.id
WHERE users.type = 5;
HEREDOC;

		$st = $link->query($sql);
		if($st)
		{
			$csv_fields = array();
			$csv_fields[0] = array();
			$csv_fields[0][] = 'Learner ID';
			$csv_fields[0][] = 'Record Status';
			$csv_fields[0][] = 'Programme Type';
			$csv_fields[0][] = 'Adviser';
			$csv_fields[0][] = 'Date of Birth';
			$csv_fields[0][] = 'Learner Reference Number';
			$csv_fields[0][] = 'NI Number';
			$csv_fields[0][] = 'Enrolment';
			$csv_fields[0][] = 'Percentage Completed';
			$csv_fields[0][] = 'Target Percentage';
			$csv_fields[0][] = 'On Track';
			$csv_fields[0][] = 'Valid ILR';
			$csv_fields[0][] = 'Contract';
			$csv_fields[0][] = 'Last Review';
			$csv_fields[0][] = 'Review Status';
			$csv_fields[0][] = 'Next Review';
			$csv_fields[0][] = 'Assessor';
			$csv_fields[0][] = 'Tutor';
			$csv_fields[0][] = 'Start Date';
			$csv_fields[0][] = 'Projected End Date';
			$csv_fields[0][] = 'Actual End Date';
			$csv_fields[0][] = 'Course';
			$csv_fields[0][] = 'Framework';
			$csv_fields[0][] = 'Location';
			$csv_fields[0][] = 'Provider';
			$csv_fields[0][] = 'ULN';
			$csv_fields[0][] = 'Job Role';
			$csv_fields[0][] = 'Work Telephone';
			$csv_fields[0][] = 'Home Address 1';
			$csv_fields[0][] = 'Home Address 2';
			$csv_fields[0][] = 'Home Address 3';
			$csv_fields[0][] = 'Home Address 4';
			$csv_fields[0][] = 'Home Postcode';
			$csv_fields[0][] = 'Mobile';
			$csv_fields[0][] = 'Work Postcode';
			$csv_fields[0][] = 'Gender';
			$csv_fields[0][] = 'Age';
			$csv_fields[0][] = 'Home Email';
			$csv_fields[0][] = 'Firstnames';
			$csv_fields[0][] = 'Surname';
			$csv_fields[0][] = 'Destination';
			$csv_fields[0][] = 'Ethnicity';
			$csv_fields[0][] = 'Learner Creation Date';
			$csv_fields[0][] = 'Health Problems';
			$csv_fields[0][] = 'Employer Id';
			$csv_fields[0][] = 'Prior Attainment';
			$csv_fields[0][] = 'Referral Source';
			if(DB_NAME=="am_reed_demo" || DB_NAME=="am_reed")
				$csv_fields[0][] = 'Referral Date';
			$csv_fields[0][] = 'Initial Appointment Date';
			$csv_fields[0][] = 'Numeracy Level';
			$csv_fields[0][] = 'Literacy Level';
			$csv_fields[0][] = 'ESOL';
			$index = 0;
			while($row = $st->fetch())
			{
				$index++;
				$csv_fields[$index][] = $row['id'];
				$csv_fields[$index][] = $row['status_code'];
				$csv_fields[$index][] = $row['programme_type'];
				$csv_fields[$index][] = $row['advisor'];
				$csv_fields[$index][] = $row['dob'];
				$csv_fields[$index][] = $row['l03'];
				$csv_fields[$index][] = $row['ni'];
				$csv_fields[$index][] = $row['enrollment_no'];
				$csv_fields[$index][] = $row['l36'];
				$csv_fields[$index][] = $row['target'];
				$csv_fields[$index][] = $row['on_track'];
				$csv_fields[$index][] = $row['valid_ilr'];
				$csv_fields[$index][] = $row['contract'];
				$csv_fields[$index][] = $row['last_review'];
				$csv_fields[$index][] = $row['assessment_status'];

				$check = new Date(date('d/m/Y'));
				// Calculate Next Review
				$tr_id = $row['id'];
				$subsequent = $row['frequency'];
				$weeks = $row['subsequent'];
				$dates = $row['all_dates'];
				if($dates!='')
				{
					$dates = explode(",",$dates);
					$next_review = new Date($row['start_date']);
					$next_review->addDays($weeks * 7);
					$color = "red";
					foreach($dates as $date)
					{
						if($next_review->before($date))
							$next_review->addDays($subsequent * 7);
						else
						{
							$next_review = new Date($date);
							$next_review->addDays($subsequent * 7);
						}
					}
				}
				else
				{
					$next_review = new Date($row['start_date']);
					$next_review->addDays($weeks * 7);
				}
				$row['next_review'] = Date::toMySQL($next_review);
				$csv_fields[$index][] = $row['next_review'];
				$csv_fields[$index][] = $row['assessor'];
				$csv_fields[$index][] = $row['tutor'];
				$csv_fields[$index][] = $row['start_date'];
				$csv_fields[$index][] = $row['target_date'];
				$csv_fields[$index][] = $row['closure_date'];
				$csv_fields[$index][] = $row['course_title'];
				$csv_fields[$index][] = $row['framework_title'];
				//$csv_fields[$index][] = $row['employer'];
				$csv_fields[$index][] = $row['location'];
				$csv_fields[$index][] = $row['legal_name'];
				$csv_fields[$index][] = $row['uln'];
				$csv_fields[$index][] = $row['job_role'];
				$csv_fields[$index][] = $row['telephone'];
				$csv_fields[$index][] = $row['home_address_line_1'];
				$csv_fields[$index][] = $row['home_address_line_2'];
				$csv_fields[$index][] = $row['home_address_line_3'];
				$csv_fields[$index][] = $row['home_address_line_4'];
				$csv_fields[$index][] = $row['home_postcode'];
				$csv_fields[$index][] = $row['home_mobile'];
				$csv_fields[$index][] = $row['postcode'];
				$csv_fields[$index][] = $row['gender'];
				$csv_fields[$index][] = $row['age'];
				$csv_fields[$index][] = $row['home_email'];
				$csv_fields[$index][] = $row['firstnames'];
				$csv_fields[$index][] = $row['surname'];
				$csv_fields[$index][] = $row['destination'];
				$csv_fields[$index][] = $row['ethnicity'];
				$csv_fields[$index][] = $row['learner_created_date'];
				$csv_fields[$index][] = $row['lldd'];
				$csv_fields[$index][] = $row['employer_id'];
				$csv_fields[$index][] = $row['prior_attainment'];
				$csv_fields[$index][] = $row['referral_source'];
				if(DB_NAME=="am_reed_demo" || DB_NAME=="am_reed")
					$csv_fields[$index][] = $row['referral_date'];
				$csv_fields[$index][] = $row['initial_appointment_date'];
				$csv_fields[$index][] = $row['NumeracyTest'];
				$csv_fields[$index][] = $row['LiteracyTest'];
				$csv_fields[$index][] = $row['ESOLTest'];
			}
		}
		foreach ($csv_fields as $fields)
		{
			fputcsv($fp, $fields);
		}
		fclose($fp);


// Users CSV
		$CSVFileName = "../uploads/" . $client_db_name . "/data_dump/users.csv";
		$FileHandle = fopen($CSVFileName, 'w') or die("can't open file");
		fclose($FileHandle);
		$fp = fopen($CSVFileName, 'w');
		$sql = <<<HEREDOC
SELECT
users.id
,username
,firstnames
,surname
,job_role
,legal_name
,full_name
,telephone
,lookup_user_types.`description` AS permission
FROM
users
LEFT JOIN organisations ON organisations.id = users.`employer_id`
LEFT JOIN locations ON locations.id = users.`employer_location_id`
LEFT JOIN lookup_user_types ON lookup_user_types.`id` = users.`type`
WHERE users.type != 5;
HEREDOC;

		$st = $link->query($sql);
		if($st)
		{
			$csv_fields = array();
			$csv_fields[0] = array();
			$csv_fields[0][] = 'User Id';
			$csv_fields[0][] = 'Username';
			$csv_fields[0][] = 'Firstnames';
			$csv_fields[0][] = 'Surname';
			$csv_fields[0][] = 'Job Role';
			$csv_fields[0][] = 'Organisation';
			$csv_fields[0][] = 'Location';
			$csv_fields[0][] = 'Work Telephone';
			$csv_fields[0][] = 'Permission';
			$index = 0;
			while($row = $st->fetch())
			{
				$index++;
				$csv_fields[$index][] = $row['id'];
				$csv_fields[$index][] = $row['username'];
				$csv_fields[$index][] = $row['firstnames'];
				$csv_fields[$index][] = $row['surname'];
				$csv_fields[$index][] = $row['job_role'];
				$csv_fields[$index][] = $row['legal_name'];
				$csv_fields[$index][] = $row['full_name'];
				$csv_fields[$index][] = $row['telephone'];
				$csv_fields[$index][] = $row['permission'];
			}
		}
		foreach ($csv_fields as $fields)
		{
			fputcsv($fp, $fields);
		}
		fclose($fp);

// Employers CSV
		$CSVFileName = "../uploads/" . $client_db_name . "/data_dump/employers.csv";
		$FileHandle = fopen($CSVFileName, 'w') or die("can't open file");
		fclose($FileHandle);
		$fp = fopen($CSVFileName, 'w');
		$sql = <<<HEREDOC
SELECT
	organisations.id as org,
	organisations.dealer_participating,
	brands.title as manufacturer,
	organisations.organisation_type as org_type,
	organisations.manufacturer AS t,
	organisations.edrs,
	company_number,
	(SELECT lookup_employer_size.description FROM lookup_employer_size WHERE lookup_employer_size.code = organisations.code) AS size,
	organisations.district,
	organisations.legal_name as name,
	tr.provider_id,

	locations.address_line_1,
	locations.address_line_2,
	locations.address_line_3,
	locations.address_line_4,
	CONCAT(IFNULL(locations.address_line_1, ''), ', ', IFNULL(locations.address_line_2, ''), ', ',
		IFNULL(locations.address_line_3, ''), ', ', IFNULL(locations.address_line_4, '')) AS `full_address`,

	locations.telephone,
	organisations.creator,
	locations.postcode,
	locations.contact_name,
	locations.contact_telephone,
	locations.contact_email,
	(select count(*) from users where type = 5 and employer_id = organisations.id) as no_of_learners,
	(SELECT COUNT(*) FROM users LEFT JOIN tr ON tr.username = users.username WHERE tr.status_code = 1 AND users.employer_id = organisations.id) AS continuing_learners,

	lookup_sector_types.description as sector,
	lookup_sector_types.id as sector_id,
	organisations.parent_org as parent_org,
	organisations.retailer_code, 				# #191 {0000000251} - retailer code for raytheon
	organisations.region,
	organisations.edrs,
	(SELECT DATE_FORMAT(last_assessment, '%d/%m/%Y') FROM health_safety WHERE location_id = locations.id ORDER BY next_assessment DESC LIMIT 1) as last_assessment,
	(SELECT DATE_FORMAT(next_assessment, '%d/%m/%Y') FROM health_safety WHERE location_id = locations.id ORDER BY next_assessment DESC LIMIT 1) as next_assessment,
	(SELECT complient FROM health_safety WHERE location_id = locations.id ORDER BY next_assessment DESC LIMIT 1) as comp2,
	(SELECT DATEDIFF(next_assessment, CURDATE()) FROM health_safety WHERE location_id = locations.id ORDER BY next_assessment DESC LIMIT 1) as timeliness,
	(SELECT paperwork_received from health_safety WHERE location_id = locations.id ORDER BY next_assessment DESC LIMIT 1) as paper,
	(SELECT assessor FROM health_safety WHERE location_id = locations.id ORDER BY next_assessment DESC LIMIT 1) as h_and_S_assessor,
	(SELECT comments FROM health_safety WHERE location_id = locations.id ORDER BY next_assessment DESC LIMIT 1) as h_and_S_comments,
	(SELECT DATE_FORMAT(pl_date, '%d/%m/%Y') FROM health_safety WHERE location_id = locations.id ORDER BY next_assessment DESC LIMIT 1) as pl_date,
	(SELECT pl_insurance FROM health_safety WHERE location_id = locations.id ORDER BY next_assessment DESC LIMIT 1) as pl_insurance,
	(SELECT IF(age_range=1,'16-18',IF(age_range=2,'19-24',IF(age_range=3,'25+',''))) FROM health_safety WHERE location_id = locations.id ORDER BY next_assessment DESC LIMIT 1) as age_range,

	CASE
		WHEN organisations.health_safety = 0 THEN '-'
		WHEN (SELECT paperwork_received FROM health_safety WHERE location_id = locations.id ORDER BY next_assessment DESC LIMIT 1) = 1 THEN "<img  src='/images/green-tick.gif'  border='0'> </img>"
		WHEN (SELECT paperwork_received FROM health_safety WHERE location_id = locations.id ORDER BY next_assessment DESC LIMIT 1) = 0 THEN "<img  src='/images/red-cross.gif'  border='0'> </img>"
		ELSE "<img src='/images/notstarted.gif' border='0'> </img>"
	END AS `paperwork_received`,

	CASE
		WHEN organisations.health_safety = 0 THEN '-'
		WHEN (SELECT complient FROM health_safety WHERE location_id = locations.id ORDER BY next_assessment DESC LIMIT 1) = 1 THEN "Yes"
		WHEN (SELECT complient FROM health_safety WHERE location_id = locations.id ORDER BY next_assessment DESC LIMIT 1) = 2 THEN "No"
		WHEN (SELECT complient FROM health_safety WHERE location_id = locations.id ORDER BY next_assessment DESC LIMIT 1) = 3 THEN "Outstanding"
		ELSE "<img src='/images/notstarted.gif' border='0'> </img>"
	END AS `compliant`,
	CASE
		WHEN organisations.health_safety = 0 THEN '-'
		WHEN (SELECT DATEDIFF(next_assessment, CURDATE()) FROM health_safety WHERE location_id = locations.id ORDER BY next_assessment DESC LIMIT 1) >30 THEN "Yes"
		WHEN (SELECT DATEDIFF(next_assessment, CURDATE()) FROM health_safety WHERE location_id = locations.id ORDER BY next_assessment DESC LIMIT 1) <0 THEN "No"
		WHEN (SELECT DATEDIFF(next_assessment, CURDATE()) FROM health_safety WHERE location_id = locations.id ORDER BY next_assessment DESC LIMIT 1) >=0 and (SELECT DATEDIFF(next_assessment, CURDATE()) FROM health_safety WHERE location_id = locations.id ORDER BY next_assessment DESC LIMIT 1) <= 30 THEN "Approaching"
		ELSE "<img src='/images/notstarted.gif' border='0'> </img>"
	END AS `health_and_safety`,
	(select sum(no_of_vacancies) from vacancies where employer_id = organisations.id) as vacancies,
	group_concat(contracts.title) as contracts
FROM
	organisations
	LEFT OUTER JOIN locations ON (locations.organisations_id=organisations.id AND locations.is_legal_address=1)
    LEFT JOIN health_safety on health_safety.location_id = locations.id
	LEFT JOIN users ON users.employer_id = organisations.id and type='5'
	LEFT JOIN tr on users.username = tr.username
	LEFT JOIN contracts on contracts.id = tr.contract_id
	LEFT JOIN lookup_sector_types on lookup_sector_types.id = organisations.sector
	LEFT JOIN brands on brands.id = organisations.manufacturer
GROUP BY
	organisations.id
HAVING
	org_type like '%2%'
HEREDOC;

		$st = $link->query($sql);
		if($st)
		{
			$csv_fields = array();
			$csv_fields[0] = array();
			$csv_fields[0][] = 'Employer Id';
			$csv_fields[0][] = 'Employer Name';
			$csv_fields[0][] = 'EDRS';
			$csv_fields[0][] = 'Size';
			$csv_fields[0][] = 'Address Line 1';
			$csv_fields[0][] = 'Address Line 2';
			$csv_fields[0][] = 'Address Line 3';
			$csv_fields[0][] = 'Address Line 4';
			$csv_fields[0][] = 'Telephone';
			$csv_fields[0][] = 'Postcode';
			$csv_fields[0][] = 'No of Learners';
			$csv_fields[0][] = 'Sector';
			$csv_fields[0][] = 'Vacancies';
			$csv_fields[0][] = 'Health and Safety';
			$csv_fields[0][] = 'Compliant';
			$csv_fields[0][] = 'Manufacturer';
			$csv_fields[0][] = 'H&S Assessor';
			$csv_fields[0][] = 'H&S Comments';
			$csv_fields[0][] = 'Last Assessment';
			$csv_fields[0][] = 'Next Assessment';
			$csv_fields[0][] = 'Age Range';
			$csv_fields[0][] = 'Paperwork Received';
			$csv_fields[0][] = 'PL Date';
			$csv_fields[0][] = 'PL Insurance';
			$csv_fields[0][] = 'District';
			$csv_fields[0][] = 'Contact Name';
			$csv_fields[0][] = 'Contact Telephone';
			$csv_fields[0][] = 'Contact Email';
			$csv_fields[0][] = 'Retailer Code';
			$index = 0;
			while($row = $st->fetch())
			{
				$index++;
				$csv_fields[$index][] = $row['org'];
				$csv_fields[$index][] = $row['name'];
				$csv_fields[$index][] = $row['edrs'];
				$csv_fields[$index][] = $row['size'];
				$csv_fields[$index][] = $row['address_line_1'];
				$csv_fields[$index][] = $row['address_line_2'];
				$csv_fields[$index][] = $row['address_line_3'];
				$csv_fields[$index][] = $row['address_line_4'];
				$csv_fields[$index][] = $row['telephone'];
				$csv_fields[$index][] = $row['postcode'];
				$csv_fields[$index][] = $row['no_of_learners'];
				$csv_fields[$index][] = $row['sector'];
				$csv_fields[$index][] = $row['vacancies'];
				$csv_fields[$index][] = $row['health_and_safety'];
				$csv_fields[$index][] = $row['compliant'];
				$csv_fields[$index][] = $row['manufacturer'];
				$csv_fields[$index][] = $row['h_and_S_assessor'];
				$csv_fields[$index][] = $row['h_and_S_comments'];
				$csv_fields[$index][] = $row['last_assessment'];
				$csv_fields[$index][] = $row['next_assessment'];
				$csv_fields[$index][] = $row['age_range'];
				$csv_fields[$index][] = $row['paper'];
				$csv_fields[$index][] = $row['pl_date'];
				$csv_fields[$index][] = $row['pl_insurance'];
				$csv_fields[$index][] = $row['district'];
				$csv_fields[$index][] = $row['contact_name'];
				$csv_fields[$index][] = $row['contact_telephone'];
				$csv_fields[$index][] = $row['contact_email'];
				$csv_fields[$index][] = $row['retailer_code'];
			}
		}
		foreach ($csv_fields as $fields)
		{
			fputcsv($fp, $fields);
		}
		fclose($fp);

// qualification_frameworks CSV
		$CSVFileName = "../uploads/" . $client_db_name . "/data_dump/qualification_frameworks.csv";
		$FileHandle = fopen($CSVFileName, 'w') or die("can't open file");
		fclose($FileHandle);
		$fp = fopen($CSVFileName, 'w');
		$sql = <<<HEREDOC
SELECT id, framework_id from framework_qualifications;
HEREDOC;

		$st = $link->query($sql);
		if($st)
		{
			$csv_fields = array();
			$csv_fields[0] = array();
			$csv_fields[0][] = 'Framework ID';
			$csv_fields[0][] = 'Qualification ID';
			$index = 0;
			while($row = $st->fetch())
			{
				$index++;
				$csv_fields[$index][] = $row['framework_id'];
				$csv_fields[$index][] = $row['id'];
			}
		}
		foreach ($csv_fields as $fields)
		{
			fputcsv($fp, $fields);
		}
		fclose($fp);

// frameworks CSV
		$CSVFileName = "../uploads/" . $client_db_name . "/data_dump/frameworks.csv";
		$FileHandle = fopen($CSVFileName, 'w') or die("can't open file");
		fclose($FileHandle);
		$fp = fopen($CSVFileName, 'w');
		$sql = <<<HEREDOC
SELECT * FROM frameworks LEFT JOIN lis201213.ilr_progtype ON ilr_progtype.ProgType = frameworks.`framework_type`;
HEREDOC;

		$st = $link->query($sql);
		if($st)
		{
			$csv_fields = array();
			$csv_fields[0] = array();
			$csv_fields[0][] = 'Framework ID';
			$csv_fields[0][] = 'Title';
			$csv_fields[0][] = 'Framework Code';
			$csv_fields[0][] = 'Framework Type';
			$csv_fields[0][] = 'Duration in months';
			$index = 0;
			while($row = $st->fetch())
			{
				$index++;
				$csv_fields[$index][] = $row['id'];
				$csv_fields[$index][] = $row['title'];
				$csv_fields[$index][] = $row['framework_code'];
				$csv_fields[$index][] = $row['ProgType_Desc'];
				$csv_fields[$index][] = $row['duration_in_months'];
			}
		}
		foreach ($csv_fields as $fields)
		{
			fputcsv($fp, $fields);
		}
		fclose($fp);

// qualification_courses CSV
		$CSVFileName = "../uploads/" . $client_db_name . "/data_dump/qualification_courses.csv";
		$FileHandle = fopen($CSVFileName, 'w') or die("can't open file");
		fclose($FileHandle);
		$fp = fopen($CSVFileName, 'w');
		$sql = <<<HEREDOC
SELECT qualification_id, course_id FROM course_qualifications_dates;
HEREDOC;

		$st = $link->query($sql);
		if($st)
		{
			$csv_fields = array();
			$csv_fields[0] = array();
			$csv_fields[0][] = 'Course ID';
			$csv_fields[0][] = 'Qualification ID';
			$index = 0;
			while($row = $st->fetch())
			{
				$index++;
				$csv_fields[$index][] = $row['course_id'];
				$csv_fields[$index][] = $row['qualification_id'];
			}
		}
		foreach ($csv_fields as $fields)
		{
			fputcsv($fp, $fields);
		}
		fclose($fp);

// qualifications CSV
		$CSVFileName = "../uploads/" . $client_db_name . "/data_dump/qualifications.csv";
		$FileHandle = fopen($CSVFileName, 'w') or die("can't open file");
		fclose($FileHandle);
		$fp = fopen($CSVFileName, 'w');
		$sql = <<<HEREDOC
SELECT id,internaltitle,qualification_type,level,active,awarding_body,mainarea,subarea,units,guided_learning_hours, concat(id,internaltitle) as uid FROM qualifications;
HEREDOC;

		$st = $link->query($sql);
		if($st)
		{
			$csv_fields = array();
			$csv_fields[0] = array();
			//$csv_fields[0][] = 'Qualification ID';
			$csv_fields[0][] = 'Title';
			$csv_fields[0][] = 'Type';
			$csv_fields[0][] = 'Level';
			$csv_fields[0][] = 'Active';
			$csv_fields[0][] = 'Awarding Body';
			$csv_fields[0][] = 'Subject Area';
			$csv_fields[0][] = 'Subject Sub Area';
			$csv_fields[0][] = 'Total Units';
			$csv_fields[0][] = 'Guided Learning Hours';
			$csv_fields[0][] = 'Qualification ID';
			$index = 0;
			while($row = $st->fetch())
			{
				$index++;
				//$csv_fields[$index][] = $row['uid'];
				$csv_fields[$index][] = $row['internaltitle'];
				$csv_fields[$index][] = $row['qualification_type'];
				$csv_fields[$index][] = $row['level'];
				$csv_fields[$index][] = $row['active'];
				$csv_fields[$index][] = $row['awarding_body'];
				$csv_fields[$index][] = $row['mainarea'];
				$csv_fields[$index][] = $row['subarea'];
				$csv_fields[$index][] = $row['units'];
				$csv_fields[$index][] = $row['guided_learning_hours'];
				$csv_fields[$index][] = $row['id'];
			}
		}
		foreach ($csv_fields as $fields)
		{
			fputcsv($fp, $fields);
		}
		fclose($fp);

// learners_groups CSV
		$CSVFileName = "../uploads/" . $client_db_name . "/data_dump/learners_groups.csv";
		$FileHandle = fopen($CSVFileName, 'w') or die("can't open file");
		fclose($FileHandle);
		$fp = fopen($CSVFileName, 'w');
		$sql = <<<HEREDOC
SELECT * FROM group_members;
HEREDOC;

		$st = $link->query($sql);
		if($st)
		{
			$csv_fields = array();
			$csv_fields[0] = array();
			$csv_fields[0][] = 'Group ID';
			$csv_fields[0][] = 'Training Record ID';
			$csv_fields[0][] = 'Updated';
			$index = 0;
			while($row = $st->fetch())
			{
				$index++;
				$csv_fields[$index][] = $row['groups_id'];
				$csv_fields[$index][] = $row['tr_id'];
				$csv_fields[$index][] = $row['updated'];
			}
		}
		foreach ($csv_fields as $fields)
		{
			fputcsv($fp, $fields);
		}
		fclose($fp);

// groups CSV
		$CSVFileName = "../uploads/" . $client_db_name . "/data_dump/groups.csv";
		$FileHandle = fopen($CSVFileName, 'w') or die("can't open file");
		fclose($FileHandle);
		$fp = fopen($CSVFileName, 'w');
		$sql = <<<HEREDOC
SELECT case status when 1 then 'Open' when 2 then 'Closed' when 3 then 'Cancelled' end as group_status, groups.* FROM groups;
HEREDOC;

		$st = $link->query($sql);
		if($st)
		{
			$csv_fields = array();
			$csv_fields[0] = array();
			$csv_fields[0][] = 'Group ID';
			$csv_fields[0][] = 'Group Assessor';
			$csv_fields[0][] = 'Group Tutor';
			$csv_fields[0][] = 'Group Verifier';
			$csv_fields[0][] = 'Group Name';
			$csv_fields[0][] = 'Capacity';
			$csv_fields[0][] = 'Group Status';
			$index = 0;
			while($row = $st->fetch())
			{
				$index++;
				$csv_fields[$index][] = $row['id'];
				$csv_fields[$index][] = $row['assessor'];
				$csv_fields[$index][] = $row['tutor'];
				$csv_fields[$index][] = $row['verifier'];
				$csv_fields[$index][] = $row['title'];
				$csv_fields[$index][] = $row['capacity'];
				$csv_fields[$index][] = $row['group_status'];
			}
		}
		foreach ($csv_fields as $fields)
		{
			fputcsv($fp, $fields);
		}
		fclose($fp);

// courses CSV
		$CSVFileName = "../uploads/" . $client_db_name . "/data_dump/courses.csv";
		$FileHandle = fopen($CSVFileName, 'w') or die("can't open file");
		fclose($FileHandle);
		$fp = fopen($CSVFileName, 'w');
		$sql = <<<HEREDOC
SELECT courses.id,title,legal_name,course_start_date,course_end_date FROM courses INNER JOIN organisations ON organisations.id = courses.`organisations_id`;
HEREDOC;

		$st = $link->query($sql);
		if($st)
		{
			$csv_fields = array();
			$csv_fields[0] = array();
			$csv_fields[0][] = 'Course ID';
			$csv_fields[0][] = 'Title';
			$csv_fields[0][] = 'Provider';
			$csv_fields[0][] = 'Start Date';
			$csv_fields[0][] = 'End Date';
			$index = 0;
			while($row = $st->fetch())
			{
				$index++;
				$csv_fields[$index][] = $row['id'];
				$csv_fields[$index][] = $row['title'];
				$csv_fields[$index][] = $row['legal_name'];
				$csv_fields[$index][] = $row['course_start_date'];
				$csv_fields[$index][] = $row['course_end_date'];
			}
		}
		foreach ($csv_fields as $fields)
		{
			fputcsv($fp, $fields);
		}
		fclose($fp);

// Learning aims CSV
		$CSVFileName = "../uploads/" . $client_db_name . "/data_dump/aim_refs.csv";
		$FileHandle = fopen($CSVFileName, 'w') or die("can't open file");
		fclose($FileHandle);
		$fp = fopen($CSVFileName, 'w');
		$sql = <<<HEREDOC
SELECT
(SELECT ilr FROM ilr INNER JOIN contracts ON contracts.id = ilr.`contract_id` WHERE tr.id = ilr.`tr_id` ORDER BY contracts.`contract_year` DESC, ilr.`submission` DESC LIMIT 0,1) AS ilr,
(SELECT contracts.`contract_year` FROM ilr INNER JOIN contracts ON contracts.id = ilr.`contract_id` WHERE tr.id = ilr.`tr_id` ORDER BY contracts.`contract_year` DESC, ilr.`submission` DESC LIMIT 0,1) AS contract_year,
tr.* FROM tr;
HEREDOC;

		$st = $link->query($sql);
		if($st)
		{
			$csv_fields = array();
			$csv_fields[0] = array();
			$csv_fields[0][] = 'Learner Id';
			$csv_fields[0][] = 'Learning Aim Reference';
			$csv_fields[0][] = 'Aim Type';
			$csv_fields[0][] = 'Learner Start Date';
			$csv_fields[0][] = 'Learner Planned End Date';
			$csv_fields[0][] = 'Learner Actual End Date';
			$csv_fields[0][] = 'Learner Completion Status';
			$csv_fields[0][] = 'Learner Outcome';
			$csv_fields[0][] = 'Learner Indicator Actual Progression Route';
			$csv_fields[0][] = 'Learner Withdrawl Reason';
			$csv_fields[0][] = 'Learner Achievement Date';
			$csv_fields[0][] = 'Learner Outcome Grade';
			$csv_fields[0][] = 'Learner Credits Achieved';
			$index = 0;
			while($row = $st->fetch())
			{
				$xml = $row['ilr'];
				if($row['contract_year']<2012)
				{
					$ilr = XML::loadSimpleXML($xml);
					foreach($ilr->programmeaim as $aim)
					{
						if( ($aim->A15!='99' && $aim->A15!='') || $aim->A10=='70')
						{
							$index++;
							$csv_fields[$index][] = $row['id'];
							$csv_fields[$index][] = $aim->A09;
							$csv_fields[$index][] = $aim->A04;
							$csv_fields[$index][] = Date::toShort($aim->A27);
							$csv_fields[$index][] = Date::toShort($aim->A28);
							$csv_fields[$index][] = $aim->A31;
						}
					}
					foreach($ilr->main as $aim)
					{
						$index++;
						$csv_fields[$index][] = $row['id'];
						$csv_fields[$index][] = $aim->A09;
						$csv_fields[$index][] = $aim->A04;
						$csv_fields[$index][] = Date::toShort($aim->A27);
						$csv_fields[$index][] = Date::toShort($aim->A28);
						$csv_fields[$index][] = $aim->A31;
					}
					foreach($ilr->subaim as $aim)
					{
						$index++;
						$csv_fields[$index][] = $row['id'];
						$csv_fields[$index][] = $aim->A09;
						$csv_fields[$index][] = $aim->A04;
						$csv_fields[$index][] = Date::toShort($aim->A27);
						$csv_fields[$index][] = Date::toShort($aim->A28);
						$csv_fields[$index][] = $aim->A31;
					}
				}
				else
				{
					$ilr = Ilr2012::loadFromXML($xml);
					foreach($ilr->LearningDelivery as $delivery)
					{
						$index++;
						$csv_fields[$index][] = $row['id'];
						$csv_fields[$index][] = $delivery->LearnAimRef;
						$csv_fields[$index][] = $delivery->AimType;
						$csv_fields[$index][] = Date::toShort($delivery->LearnStartDate);
						$csv_fields[$index][] = Date::toShort($delivery->LearnPlanEndDate);
						$csv_fields[$index][] = $delivery->LearnActEndDate;
						$csv_fields[$index][] = $delivery->CompStatus;
						$csv_fields[$index][] = $delivery->Outcome;
						$csv_fields[$index][] = $delivery->ActProgRoute;
						$csv_fields[$index][] = $delivery->WithdrawReason;
						$csv_fields[$index][] = $delivery->AchDate;
						$csv_fields[$index][] = $delivery->OutGrade;
						$csv_fields[$index][] = $delivery->CredAch;
					}
				}
			}
		}
		foreach ($csv_fields as $fields)
		{
			fputcsv($fp, $fields);
		}
		fclose($fp);

// ILR Data CSV
		$CSVFileName = "../uploads/" . $client_db_name . "/data_dump/ilr_data.csv";
		$FileHandle = fopen($CSVFileName, 'w') or die("can't open file");
		fclose($FileHandle);
		$fp = fopen($CSVFileName, 'w');
		$sql = <<<HEREDOC
SELECT
tr.id
,(SELECT ilr FROM ilr LEFT JOIN contracts ON contracts.id = ilr.`contract_id` WHERE ilr.tr_id = tr.id ORDER BY contracts.`contract_year` DESC, submission DESC LIMIT 0,1) AS ilr
,contracts.contract_year
FROM tr
LEFT JOIN contracts ON contracts.id = tr.`contract_id`;
HEREDOC;

		$st = $link->query($sql);
		if($st)
		{
			$csv_fields = array();
			$csv_fields[0] = array();
			$csv_fields[0][] = 'Learner Id';
			$csv_fields[0][] = 'Health Problems';
			$csv_fields[0][] = 'DS';
			$csv_fields[0][] = 'LD';
			$csv_fields[0][] = 'LSR1';
			$csv_fields[0][] = 'LSR2';
			$csv_fields[0][] = 'LSR3';
			$csv_fields[0][] = 'LSR4';
			$index = 0;
			while($row = $st->fetch())
			{
				$index++;
				if($row['contract_year']<2012)
				{
					$ilr = Ilr2011::loadFromXML($row['ilr']);
					$health_problems = "". $ilr->learnerinformation->L14;
					$ds = "". $ilr->learnerinformation->L15;
					$ld = "". $ilr->learnerinformation->L16;
					$lsr1 = "". $ilr->learnerinformation->L34a;
					$lsr2 = "". $ilr->learnerinformation->L34b;
					$lsr3 = "". $ilr->learnerinformation->L34c;
					$lsr4 = "". $ilr->learnerinformation->L34d;
				}
				else
				{
					$ilr = Ilr2012::loadFromXML($row['ilr']);
					$health_problems = "" . $ilr->LLDDHealthProb;
					$xpath = $ilr->xpath("/Learner/LLDDandHealthProblem[LLDDType='DS']/LLDDCode"); $ds = (empty($xpath))?'':$xpath[0];
					$xpath = $ilr->xpath("/Learner/LLDDandHealthProblem[LLDDType='LD']/LLDDCode"); $ld = (empty($xpath))?'':$xpath[0];
					$xpath = $ilr->xpath("/Learner/LearnerFAM[LearnFAMType='LSR']/LearnFAMCode"); $lsr1 = (empty($xpath[0]))?'':(string)$xpath[0];
					$xpath = $ilr->xpath("/Learner/LearnerFAM[LearnFAMType='LSR']/LearnFAMCode"); $lsr2 = (empty($xpath[1]))?'':(string)$xpath[1];
					$xpath = $ilr->xpath("/Learner/LearnerFAM[LearnFAMType='LSR']/LearnFAMCode"); $lsr3 = (empty($xpath[2]))?'':(string)$xpath[2];
					$xpath = $ilr->xpath("/Learner/LearnerFAM[LearnFAMType='LSR']/LearnFAMCode"); $lsr4 = (empty($xpath[3]))?'':(string)$xpath[3];
				}
				$csv_fields[$index][] = $row['id'];
				$csv_fields[$index][] = $health_problems;
				$csv_fields[$index][] = $ds;
				$csv_fields[$index][] = $ld;
				$csv_fields[$index][] = $lsr1;
				$csv_fields[$index][] = $lsr2;
				$csv_fields[$index][] = $lsr3;
				$csv_fields[$index][] = $lsr4;
			}
		}
		foreach ($csv_fields as $fields)
		{
			fputcsv($fp, $fields);
		}
		fclose($fp);


// employment monitoring CSV
		$CSVFileName = "../uploads/" . $client_db_name . "/data_dump/employment_monitoring.csv";
		$FileHandle = fopen($CSVFileName, 'w') or die("can't open file");
		fclose($FileHandle);
		$fp = fopen($CSVFileName, 'w');
		$sql = <<<HEREDOC
SELECT
tr.id
,(SELECT ilr FROM ilr LEFT JOIN contracts ON contracts.id = ilr.`contract_id` WHERE ilr.tr_id = tr.id ORDER BY contracts.`contract_year` DESC, submission DESC LIMIT 0,1) AS ilr
,contracts.contract_year
FROM tr
LEFT JOIN contracts ON contracts.id = tr.`contract_id`;
HEREDOC;

		$st = $link->query($sql);
		if($st)
		{
			$csv_fields = array();
			$csv_fields[0] = array();
			$csv_fields[0][] = 'Learner Id';
			$csv_fields[0][] = 'EII';
			$csv_fields[0][] = 'BSI';
			$csv_fields[0][] = 'Employer Id';
			$index = 0;
			while($row = $st->fetch())
			{
				if($row['contract_year']<2012)
				{
					$ilr = Ilr2011::loadFromXML($row['ilr']);
					$emp_id = "". $ilr->aims[0]->A44;
					$eii = "";
					$bsi = "";
					$index++;
					$csv_fields[$index][] = $row['id'];
					$csv_fields[$index][] = $emp_id;
					$csv_fields[$index][] = $eii;
					$csv_fields[$index][] = $bsi;
				}
				else
				{
					$ilr = Ilr2012::loadFromXML($row['ilr']);
					foreach($ilr->LearnerEmploymentStatus as $empstatus)
					{
						$xpath = $empstatus->xpath("./EmploymentStatusMonitoring[ESMType='EII']/ESMCode");
						$eii = (empty($xpath[0]))?'':$xpath[0];
						$xpath = $empstatus->xpath("./EmploymentStatusMonitoring[ESMType='BSI']/ESMCode");
						$bsi = (empty($xpath[0]))?'':$xpath[0];
						$index++;
						$csv_fields[$index][] = $row['id'];
						$csv_fields[$index][] = "" . $empstatus->EmpId;
						$csv_fields[$index][] = $eii;
						$csv_fields[$index][] = $bsi;
					}
				}
			}
		}
		foreach ($csv_fields as $fields)
		{
			fputcsv($fp, $fields);
		}
		fclose($fp);

// CRM Notes CSV
		$CSVFileName = "../uploads/" . $client_db_name . "/data_dump/crm_notes.csv";
		$FileHandle = fopen($CSVFileName, 'w') or die("can't open file");
		fclose($FileHandle);
		$fp = fopen($CSVFileName, 'w');
		$sql = <<<HEREDOC
SELECT organisation_id AS id,'Organisation' AS crm_note_type,name_of_person,`position`,lookup_crm_contact_type.`description` AS type_of_contact,lookup_crm_subject.`description` AS `subject`,`date`,by_whom,whom_position,date_created,agreed_action FROM crm_notes
LEFT JOIN lookup_crm_contact_type ON lookup_crm_contact_type.`id` = crm_notes.`type_of_contact`
LEFT JOIN lookup_crm_subject ON lookup_crm_subject.`id` = crm_notes.`subject`
UNION ALL
SELECT DISTINCT
  crm_notes_learner.tr_id AS id,
  'Learner' AS crm_note_type,
  name_of_person,
  `position`,
  lookup_crm_contact_type.`description` AS type_of_contact,
  lookup_crm_subject.`description` AS `subject`,
  `date`,
  by_whom,
  whom_position,
  date_created,
  agreed_action 
FROM
  crm_notes_learner 
  INNER JOIN tr 
    ON tr.id = crm_notes_learner.tr_id 
  LEFT JOIN group_members 
    ON group_members.tr_id = tr.id 
  LEFT JOIN courses_tr 
    ON courses_tr.tr_id = tr.id 
  LEFT JOIN courses 
    ON courses.id = courses_tr.course_id 
  LEFT JOIN groups 
    ON groups.courses_id = courses.id 
    AND group_members.groups_id = groups.id 
  LEFT JOIN lookup_crm_contact_type 
    ON lookup_crm_contact_type.id = crm_notes_learner.type_of_contact 
  LEFT JOIN lookup_crm_subject 
    ON lookup_crm_subject.id = crm_notes_learner.subject 
  LEFT JOIN contracts 
    ON contracts.id = tr.contract_id 
    ;
HEREDOC;

		$st = $link->query($sql);
		if($st)
		{
			$csv_fields = array();
			$csv_fields[0] = array();
			$csv_fields[0][] = 'Learner/ Organisation Id';
			$csv_fields[0][] = 'CRM Note Type';
			$csv_fields[0][] = 'Name of Person Contacted';
			$csv_fields[0][] = 'Position';
			$csv_fields[0][] = 'Type of Contact';
			$csv_fields[0][] = 'Subject';
			$csv_fields[0][] = 'Date';
			$csv_fields[0][] = 'By Whom';
			$csv_fields[0][] = 'By_Position';
			$csv_fields[0][] = 'Date Created';
			$csv_fields[0][] = 'Agreed Action';
			$index = 0;
			while($row = $st->fetch())
			{
				$index++;
				$csv_fields[$index][] = $row['id'];
				$csv_fields[$index][] = $row['crm_note_type'];
				$csv_fields[$index][] = $row['name_of_person'];
				$csv_fields[$index][] = $row['position'];
				$csv_fields[$index][] = $row['type_of_contact'];
				$csv_fields[$index][] = $row['subject'];
				$csv_fields[$index][] = $row['date'];
				$csv_fields[$index][] = $row['by_whom'];
				$csv_fields[$index][] = $row['whom_position'];
				$csv_fields[$index][] = $row['date_created'];
				$csv_fields[$index][] = $row['agreed_action'];
			}
		}
		foreach ($csv_fields as $fields)
		{
			fputcsv($fp, $fields);
		}
		fclose($fp);

// CRM Notes CSV
		$CSVFileName = "../uploads/" . $client_db_name . "/data_dump/courses_tr.csv";
		$FileHandle = fopen($CSVFileName, 'w') or die("can't open file");
		fclose($FileHandle);
		$fp = fopen($CSVFileName, 'w');
		$sql = <<<HEREDOC
select * From courses_tr;
HEREDOC;

		$st = $link->query($sql);
		if($st)
		{
			$csv_fields = array();
			$csv_fields[0] = array();
			$csv_fields[0][] = 'Course ID';
			$csv_fields[0][] = 'Learner ID';
			$index = 0;
			while($row = $st->fetch())
			{
				$index++;
				$csv_fields[$index][] = $row['course_id'];
				$csv_fields[$index][] = $row['tr_id'];
			}
		}
		foreach ($csv_fields as $fields)
		{
			fputcsv($fp, $fields);
		}
		fclose($fp);



// Lesson Schedule CSV
		$CSVFileName = "../uploads/" . $client_db_name . "/data_dump/lesson_schedule.csv";
		$FileHandle = fopen($CSVFileName, 'w') or die("can't open file");
		fclose($FileHandle);
		$fp = fopen($CSVFileName, 'w');
		$sql = <<<HEREDOC
SELECT lessons.id, `date`, start_time,end_time,groups.`courses_id`,lessons.`tutor`,groups_id,modified,modules.id as module_id FROM lessons INNER JOIN groups ON groups.`id` = lessons.`groups_id` LEFT JOIN modules ON modules.id = lessons.`module`;
HEREDOC;

		$st = $link->query($sql);
		if($st)
		{
			$csv_fields = array();
			$csv_fields[0] = array();
			$csv_fields[0][] = 'Lesson Id';
			$csv_fields[0][] = 'Lesson Date';
			$csv_fields[0][] = 'Start Time';
			$csv_fields[0][] = 'End Time';
			$csv_fields[0][] = 'Course Id';
			$csv_fields[0][] = 'Tutor';
			$csv_fields[0][] = 'Group Id';
			$csv_fields[0][] = 'Date Created';
			$csv_fields[0][] = 'Module Id';
			$index = 0;
			while($row = $st->fetch())
			{
				$index++;
				$csv_fields[$index][] = $row['id'];
				$csv_fields[$index][] = $row['date'];
				$csv_fields[$index][] = $row['start_time'];
				$csv_fields[$index][] = $row['end_time'];
				$csv_fields[$index][] = $row['courses_id'];
				$csv_fields[$index][] = $row['tutor'];
				$csv_fields[$index][] = $row['groups_id'];
				$csv_fields[$index][] = $row['modified'];
				$csv_fields[$index][] = $row['module_id'];
			}
		}
		foreach ($csv_fields as $fields)
		{
			fputcsv($fp, $fields);
		}
		fclose($fp);

// Modules CSV
		$CSVFileName = "../uploads/" . $client_db_name . "/data_dump/modules.csv";
		$FileHandle = fopen($CSVFileName, 'w') or die("can't open file");
		fclose($FileHandle);
		$fp = fopen($CSVFileName, 'w');
		$sql = <<<HEREDOC
SELECT modules.id AS id, modules.title AS title, modules.learning_hours, organisations.`legal_name`  AS provider_id  FROM modules INNER JOIN organisations ON organisations.id = modules.provider_id;
HEREDOC;

		$st = $link->query($sql);
		if($st)
		{
			$csv_fields = array();
			$csv_fields[0] = array();
			$csv_fields[0][] = 'Module Id';
			$csv_fields[0][] = 'Module Name';
			$csv_fields[0][] = 'Module Hours';
			$csv_fields[0][] = 'Module Provider';
			$index = 0;
			while($row = $st->fetch())
			{
				$index++;
				$csv_fields[$index][] = $row['id'];
				$csv_fields[$index][] = $row['title'];
				$csv_fields[$index][] = $row['learning_hours'];
				$csv_fields[$index][] = $row['provider_id'];
			}
		}
		foreach ($csv_fields as $fields)
		{
			fputcsv($fp, $fields);
		}
		fclose($fp);


// Compliance Events CSV
		$CSVFileName = "../uploads/" . $client_db_name . "/data_dump/compliance_events.csv";
		$FileHandle = fopen($CSVFileName, 'w') or die("can't open file");
		fclose($FileHandle);
		$fp = fopen($CSVFileName, 'w');
		$sql = <<<HEREDOC
SELECT DISTINCT tr.id AS tr_id, CONCAT(tr.surname, ', ',tr.firstnames) AS learner_name, lookup_programme_type.description AS programme_type, tr.`dob`, tr.`l03`,tr.`start_date`, tr.`target_date`, tr.`closure_date`,
employers.legal_name AS employer,
providers.`legal_name` AS training_provider,
events_template.`title` AS compliance_event, student_events.`event_date` AS compliance_date, student_events.`comments` AS compliance_comments
FROM tr
LEFT JOIN organisations AS employers ON employers.id = tr.`employer_id`
LEFT JOIN organisations AS providers ON providers.id = tr.`provider_id`
LEFT JOIN courses_tr ON courses_tr.tr_id = tr.id
LEFT JOIN courses ON courses.id = courses_tr.course_id
LEFT JOIN lookup_programme_type ON lookup_programme_type.code = courses.programme_type
INNER JOIN events_template ON ( IF(courses.programme_type = 5, 2 = events_template.programme_type, IF(courses.programme_type > 2, 1 = events_template.programme_type, courses.programme_type = events_template.programme_type )) ) AND events_template.`provider_id` = providers.id
LEFT JOIN student_events ON student_events.`event_id` = events_template.`id` AND student_events.tr_id = tr.id
HEREDOC;

		$st = $link->query($sql);
		if($st)
		{
			$csv_fields = array();
			$csv_fields[0] = array();
			$csv_fields[0][] = 'Learner ID';
			$csv_fields[0][] = 'Compliance Event';
			$csv_fields[0][] = 'Compliance Date';
			$csv_fields[0][] = 'Compliance Comments';
			$index = 0;
			while($row = $st->fetch())
			{
				$index++;
				$csv_fields[$index][] = $row['tr_id'];
				$csv_fields[$index][] = $row['compliance_event'];
				$csv_fields[$index][] = $row['compliance_date'];
				$csv_fields[$index][] = $row['compliance_comments'];
			}
		}
		foreach ($csv_fields as $fields)
		{
			fputcsv($fp, $fields);
		}
		fclose($fp);

// Compliance Events CSV
		$CSVFileName = "../uploads/" . $client_db_name . "/data_dump/file_repository.csv";
		$FileHandle = fopen($CSVFileName, 'w') or die("can't open file");
		fclose($FileHandle);
		$fp = fopen($CSVFileName, 'w');
		$learner_dir = Repository::getRoot();
		$files = Repository::readDirectory($learner_dir);

		$csv_fields = array();
		$csv_fields[0] = array();
		$csv_fields[0][] = 'Filename';
		$csv_fields[0][] = 'File Size';
		$csv_fields[0][] = 'Upload Time';
		$index = 0;

		foreach($files as $f)
		{
			if($f->isDir())
			{
				$dir2 = Repository::getRoot() . "/" . $f->getName();
				$files2 = Repository::readDirectory($dir2);
				foreach($files2 as $f2)
				{
					if($f2->isFile())
					{
						$index++;
						$csv_fields[$index][] = $f2->getName();
						$csv_fields[$index][] = str_replace("&nbsp;","",Repository::formatFileSize($f2->getSize()));
						$csv_fields[$index][] = date("d/m/Y H:i:s", $f2->getModifiedTime());
					}
				}
			}
			else
			{
				$index++;
				$csv_fields[$index][] = $f->getName();
				$csv_fields[$index][] = str_replace("&nbsp;","",Repository::formatFileSize($f->getSize()));
				$csv_fields[$index][] = date("d/m/Y H:i:s", $f->getModifiedTime());
			}
		}
		foreach ($csv_fields as $fields)
		{
			fputcsv($fp, $fields);
		}
		fclose($fp);

// Reviews CSV
		$CSVFileName = "../uploads/" . $client_db_name . "/data_dump/reviews.csv";
		$FileHandle = fopen($CSVFileName, 'w') or die("can't open file");
		fclose($FileHandle);
		$fp = fopen($CSVFileName, 'w');
		$sql = <<<HEREDOC
SELECT * FROM assessor_review where meeting_date is not null;
HEREDOC;

		$st = $link->query($sql);
		if($st)
		{
			$csv_fields = array();
			$csv_fields[0] = array();
			$csv_fields[0][] = 'TR ID';
			$csv_fields[0][] = 'Review ID';
			$csv_fields[0][] = 'Review Due Date';
			$csv_fields[0][] = 'Review Date';
			$csv_fields[0][] = 'Status';
			$csv_fields[0][] = 'Paperwork';
			$csv_fields[0][] = 'Comments';
			$csv_fields[0][] = 'Assessor';
			$index = 0;
			while($row = $st->fetch())
			{
				$index++;
				$csv_fields[$index][] = $row['tr_id'];
				$csv_fields[$index][] = $row['id'];
				$csv_fields[$index][] = $row['due_date'];
				$csv_fields[$index][] = $row['meeting_date'];
				$csv_fields[$index][] = $row['comments'];
				$csv_fields[$index][] = $row['paperwork_received'];
				$csv_fields[$index][] = $row['assessor_comments'];
				$csv_fields[$index][] = $row['assessor'];
			}
		}
		foreach ($csv_fields as $fields)
		{
			fputcsv($fp, $fields);
		}
		fclose($fp);

// Compliance Events CSV
		$CSVFileName = "../uploads/" . $client_db_name . "/data_dump/file_repository.csv";
		$FileHandle = fopen($CSVFileName, 'w') or die("can't open file");
		fclose($FileHandle);
		$fp = fopen($CSVFileName, 'w');
		$learner_dir = Repository::getRoot();
		$files = Repository::readDirectory($learner_dir);

		$csv_fields = array();
		$csv_fields[0] = array();
		$csv_fields[0][] = 'Filename';
		$csv_fields[0][] = 'File Size';
		$csv_fields[0][] = 'Upload Time';
		$index = 0;

		foreach($files as $f)
		{
			if($f->isDir())
			{
				$dir2 = Repository::getRoot() . "/" . $f->getName();
				$files2 = Repository::readDirectory($dir2);
				foreach($files2 as $f2)
				{
					if($f2->isFile())
					{
						$index++;
						$csv_fields[$index][] = $f2->getName();
						$csv_fields[$index][] = str_replace("&nbsp;","",Repository::formatFileSize($f2->getSize()));
						$csv_fields[$index][] = date("d/m/Y H:i:s", $f2->getModifiedTime());
					}
				}
			}
			else
			{
				$index++;
				$csv_fields[$index][] = $f->getName();
				$csv_fields[$index][] = str_replace("&nbsp;","",Repository::formatFileSize($f->getSize()));
				$csv_fields[$index][] = date("d/m/Y H:i:s", $f->getModifiedTime());
			}
		}
		foreach ($csv_fields as $fields)
		{
			fputcsv($fp, $fields);
		}
		fclose($fp);

// lesson_attendance CSV
		$CSVFileName = "../uploads/" . $client_db_name . "/data_dump/lesson_attendance.csv";
		$FileHandle = fopen($CSVFileName, 'w') or die("can't open file");
		fclose($FileHandle);
		$fp = fopen($CSVFileName, 'w');
		$sql = <<<HEREDOC
SELECT
lessons_id AS LessonID,
tr.l03 AS LearnerRefNumber,
tr.id AS LearnerID,
tr.`firstnames` AS FirstName,
tr.`surname` AS Surname,
(SELECT description FROM lookup_register_entry_codes WHERE CODE = register_entries.entry) AS Attendance,
register_entries.modified AS AttendanceUpdated,
#register_entry_notes.`note` AS RegisterNote
GROUP_CONCAT(register_entry_notes.`note` SEPARATOR '---') AS RegisterNote
FROM
tr INNER JOIN register_entries ON tr.id = register_entries.`pot_id`
LEFT JOIN register_entry_notes ON register_entries.id = register_entry_notes.`register_entries_id`
LEFT JOIN users ON tr.`username` = users.`username`
GROUP BY tr.id, lessons_id;
HEREDOC;
		$st = $link->query($sql);
		if($st)
		{
			$csv_fields = array();
			$csv_fields[0] = array();
			$csv_fields[0][] = 'Lesson ID';
			$csv_fields[0][] = 'Learning Reference Number';
			$csv_fields[0][] = 'Learner ID';
			$csv_fields[0][] = 'First Name';
			$csv_fields[0][] = 'Surname';
			$csv_fields[0][] = 'Attendance';
			$csv_fields[0][] = 'Attendance Updated';
			$csv_fields[0][] = 'Register Note';
			$index = 0;
			while($row = $st->fetch())
			{
				$index++;
				$csv_fields[$index][] = $row['LessonID'];
				$csv_fields[$index][] = $row['LearnerRefNumber'];
				$csv_fields[$index][] = $row['LearnerID'];
				$csv_fields[$index][] = $row['FirstName'];
				$csv_fields[$index][] = $row['Surname'];
				$csv_fields[$index][] = $row['Attendance'];
				$csv_fields[$index][] = $row['AttendanceUpdated'];
				$csv_fields[$index][] = $row['RegisterNote'];
			}
		}
		foreach ($csv_fields as $fields)
		{
			fputcsv($fp, $fields);
		}
		fclose($fp);


// Learner Qualifications
		$CSVFileName = "../uploads/" . $client_db_name . "/data_dump/learners_qualifications.csv";
		$FileHandle = fopen($CSVFileName, 'w') or die("can't open file");
		fclose($FileHandle);
		$fp = fopen($CSVFileName, 'w');
		$sql = <<<HEREDOC
SELECT id, tr_id, start_date, end_date,actual_end_date, achievement_date  FROM student_qualifications;
HEREDOC;
		$st = $link->query($sql);
		if($st)
		{
			$csv_fields = array();
			$csv_fields[0] = array();
			$csv_fields[0][] = 'Qualification ID';
			$csv_fields[0][] = 'TR Id';
			$csv_fields[0][] = 'Start Date';
			$csv_fields[0][] = 'Planned End Date';
			$csv_fields[0][] = 'Actual End Date';
			$csv_fields[0][] = 'Achievement Date';
			$index = 0;
			while($row = $st->fetch())
			{
				$index++;
				$csv_fields[$index][] = $row['id'];
				$csv_fields[$index][] = $row['tr_id'];
				$csv_fields[$index][] = $row['start_date'];
				$csv_fields[$index][] = $row['end_date'];
				$csv_fields[$index][] = $row['actual_end_date'];
				$csv_fields[$index][] = $row['achievement_date'];
			}
		}
		foreach ($csv_fields as $fields)
		{
			fputcsv($fp, $fields);
		}
		fclose($fp);

// Planned Learning Hours
		$CSVFileName = "../uploads/" . $client_db_name . "/data_dump/audit.csv";
		$FileHandle = fopen($CSVFileName, 'w') or die("can't open file");
		fclose($FileHandle);
		$fp = fopen($CSVFileName, 'w');
		$sql = <<<HEREDOC
SELECT
ilr_audit.username AS Username,
ilr_audit.tr_id AS LearnerId,
ilr_audit.changed AS `Field`,
ilr_audit.to AS `To`,
ilr_audit.from AS `From`,
ilr_audit.date AS `On`
FROM ilr_audit WHERE `changed` = 'PlanLearnHours';
HEREDOC;
		$st = $link->query($sql);
		if($st)
		{
			$csv_fields = array();
			$csv_fields[0] = array();
			$csv_fields[0][] = 'Username';
			$csv_fields[0][] = 'Learner ID';
			$csv_fields[0][] = 'Field';
			$csv_fields[0][] = 'From';
			$csv_fields[0][] = 'To';
			$csv_fields[0][] = 'On';
			$index = 0;
			while($row = $st->fetch())
			{
				$index++;
				$csv_fields[$index][] = $row['Username'];
				$csv_fields[$index][] = $row['LearnerId'];
				$csv_fields[$index][] = $row['Field'];
				$csv_fields[$index][] = $row['From'];
				$csv_fields[$index][] = $row['To'];
				$csv_fields[$index][] = $row['On'];
			}
		}
		foreach ($csv_fields as $fields)
		{
			fputcsv($fp, $fields);
		}
		fclose($fp);

		// create object
		$zip = new ZipArchive();
		if ($zip->open("../uploads/" . $client_db_name . "/data_dump/data.zip", ZIPARCHIVE::CREATE) !== TRUE)
		{
			die ("Could not open archive");
		}
		$zip->addFile("../uploads/" . $client_db_name . "/data_dump/users.csv", "users.csv") or die ("ERROR: Could not add file:");
		$zip->addFile("../uploads/" . $client_db_name . "/data_dump/learners.csv", "learners.csv") or die ("ERROR: Could not add file:");
		$zip->addFile("../uploads/" . $client_db_name . "/data_dump/employers.csv", "employers.csv") or die ("ERROR: Could not add file:");
		$zip->addFile("../uploads/" . $client_db_name . "/data_dump/qualification_frameworks.csv", "qualification_frameworks.csv") or die ("ERROR: Could not add file:");
		$zip->addFile("../uploads/" . $client_db_name . "/data_dump/frameworks.csv", "frameworks.csv") or die ("ERROR: Could not add file:");
		$zip->addFile("../uploads/" . $client_db_name . "/data_dump/qualification_courses.csv", "qualification_courses.csv") or die ("ERROR: Could not add file:");
		$zip->addFile("../uploads/" . $client_db_name . "/data_dump/qualifications.csv", "qualifications.csv") or die ("ERROR: Could not add file:");
		$zip->addFile("../uploads/" . $client_db_name . "/data_dump/learners_groups.csv", "learners_groups.csv") or die ("ERROR: Could not add file:");
		$zip->addFile("../uploads/" . $client_db_name . "/data_dump/groups.csv", "groups.csv") or die ("ERROR: Could not add file:");
		$zip->addFile("../uploads/" . $client_db_name . "/data_dump/courses.csv", "courses.csv") or die ("ERROR: Could not add file:");
		$zip->addFile("../uploads/" . $client_db_name . "/data_dump/aim_refs.csv", "aim_refs.csv") or die ("ERROR: Could not add file:");
		$zip->addFile("../uploads/" . $client_db_name . "/data_dump/ilr_data.csv", "ilr_data.csv") or die ("ERROR: Could not add file:");
		$zip->addFile("../uploads/" . $client_db_name . "/data_dump/employment_monitoring.csv", "employment_monitoring.csv") or die ("ERROR: Could not add file:");
		$zip->addFile("../uploads/" . $client_db_name . "/data_dump/crm_notes.csv", "crm_notes.csv") or die ("ERROR: Could not add file:");
		$zip->addFile("../uploads/" . $client_db_name . "/data_dump/lesson_schedule.csv", "lesson_schedule.csv") or die ("ERROR: Could not add file:");
		$zip->addFile("../uploads/" . $client_db_name . "/data_dump/compliance_events.csv", "compliance_events.csv") or die ("ERROR: Could not add file:");
		$zip->addFile("../uploads/" . $client_db_name . "/data_dump/file_repository.csv", "file_repository.csv") or die ("ERROR: Could not add file:");
		$zip->addFile("../uploads/" . $client_db_name . "/data_dump/courses_tr.csv", "courses_tr.csv") or die ("ERROR: Could not add file:");
		$zip->addFile("../uploads/" . $client_db_name . "/data_dump/reviews.csv", "reviews.csv") or die ("ERROR: Could not add file:");
		$zip->addFile("../uploads/" . $client_db_name . "/data_dump/lesson_attendance.csv", "lesson_attendance.csv") or die ("ERROR: Could not add file:");
		$zip->addFile("../uploads/" . $client_db_name . "/data_dump/modules.csv", "modules.csv") or die ("ERROR: Could not add file:");
		$zip->addFile("../uploads/" . $client_db_name . "/data_dump/learners_qualifications.csv", "learners_qualifications.csv") or die ("ERROR: Could not add file:");
		$zip->addFile("../uploads/" . $client_db_name . "/data_dump/audit.csv", "audit.csv") or die ("ERROR: Could not add file:");
		$zip->close();
		http_redirect("do.php?_action=downloader&path=/" . $client_db_name . "/data_dump/&f=data.zip");


	}
}
?>