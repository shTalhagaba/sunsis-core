<?php
class ViewOnboardingReport extends View
{

	public static function getInstance(PDO $link)
	{
		$key = 'view_'.__CLASS__;

		if(!isset($_SESSION[$key]))
		{
			$sql = new SQLStatement("
SELECT DISTINCT
	CASE TRUE
	    WHEN tr.id IS NULL THEN 'Added'
	    WHEN tr.id IS NOT NULL AND (ob_learners.`is_finished` = 'N' OR ob_learners.`is_finished` IS NULL) THEN 'Awaiting Learner'
	    WHEN tr.id IS NOT NULL AND ob_learners.`is_finished` = 'Y' AND ob_learners.`learner_signature` IS NOT NULL AND ob_learners.`employer_signature` IS NULL THEN 'Learner Completed And Awaiting Employer'
	    WHEN tr.id IS NOT NULL AND ob_learners.`is_finished` = 'Y' AND ob_learners.`learner_signature` IS NOT NULL AND ob_learners.`employer_signature` IS NOT NULL THEN 'Fully Completed'
	  END AS stage,
	tr.l03,tr.id AS tr_id,
	ob_learners.`learner_title`,
	ob_learners.`firstnames`,
	ob_learners.`surname`,
	DATE_FORMAT(ob_learners.`dob`, '%d/%m/%Y') AS dob,
	ob_learners.`ni` AS NI,
	CASE ob_learners.`gender`
		WHEN 'F' THEN 'Female'
		WHEN 'M' THEN 'Male'
		WHEN 'U' THEN 'Unknown'
		WHEN 'W' THEN 'Withheld'
		WHEN '' THEN ''
	END AS gender,
	(SELECT Ethnicity_Desc FROM lis201213.ilr_ethnicity WHERE Ethnicity = ob_learners.`ethnicity`) AS ethnicity,
	ob_learners.`home_address_line_1`,
	ob_learners.`home_address_line_2`,
	ob_learners.`home_address_line_3`,
	ob_learners.`home_address_line_4`,
	ob_learners.`home_postcode`,
	ob_learners.`home_email` AS personal_email,
	ob_learners.`home_telephone`,
	ob_learners.`home_mobile`,
	CONCAT(ob_learners.`em_con_title`, ' ', ob_learners.`em_con_name`) AS emergency_contact_person,
	ob_learners.`em_con_rel` AS emergency_contact_relation,
	ob_learners.`em_con_tel` AS emergency_contact_telephone,
	ob_learners.`em_con_mob` AS emergency_contact_mobile,
	ob_learners.`RUI`,
	ob_learners.`PMC`,
	CASE ob_learners.`LLDD`
		WHEN '' THEN ''
		WHEN 'N' THEN 'No'
		WHEN 'P' THEN 'Prefer not to say'
		WHEN 'Y' THEN 'Yes'
	END AS LLDD,
	ob_learners.`llddcat`,
	ob_learners.`primary_lldd`,
	#ob_learners.`EligibilityList`,
	CASE ob_learners.`EmploymentStatus`
		WHEN '' THEN ''
		WHEN '10' THEN 'In paid employment'
		WHEN '11' THEN 'Not in paid employment, looking for work and available to start work'
		WHEN '12' THEN 'Not in paid employment, not looking for work and/or not available to start work'
		WHEN '98' THEN 'Not known/dont want to provide'
	END AS employment_status,
	CASE ob_learners.`SEI`
		WHEN '0' THEN 'No'
		WHEN '1' THEN 'Yes'
	END AS self_employed,
	CASE ob_learners.`work_curr_emp`
		WHEN '0' THEN 'No'
		WHEN '1' THEN 'Yes'
	END AS employed_with_employer_before_apprenticeship,
	ob_learners.`empStatusEmployer` AS employer_name,
	CASE ob_learners.`SEM`
		WHEN '0' THEN 'No'
		WHEN '1' THEN 'Yes'
	END AS small_employer,
	CASE ob_learners.`LOE`
		WHEN '1' THEN 'Up to 3 months'
		WHEN '2' THEN '4-6 months'
		WHEN '3' THEN '7-12 months'
		WHEN '4' THEN 'more than 12 months'
	END AS length_of_employment,
	ob_learners.`EII` AS hours_worked_each_week,
	CASE ob_learners.`LOU`
		WHEN '1' THEN 'for less than 6 months'
		WHEN '2' THEN 'for 6-11 months'
		WHEN '3' THEN 'for 12-23 months'
		WHEN '4' THEN 'for 24-35 months'
		WHEN '5' THEN 'for over 36 months'
	END AS length_of_unemployment,
	CASE ob_learners.`BSI`
		WHEN '1' THEN 'JSA'
		WHEN '2' THEN 'ESA WRAG'
		WHEN '3' THEN 'Another state benefit'
		WHEN '4' THEN 'Universal Credit'
	END AS benefits_received,
	CASE ob_learners.`PEI`
		WHEN '0' THEN 'No'
		WHEN '1' THEN 'Yes'
	END AS in_full_time_edu_or_training,
	CASE ob_learners.`EHC_Plan`
		WHEN '0' THEN 'No'
		WHEN '1' THEN 'Yes'
	END AS EHC_Plan,
	CASE ob_learners.`care_leaver`
		WHEN '0' THEN 'No'
		WHEN '1' THEN 'Yes'
	END AS care_leaver,
	(SELECT country_name FROM lookup_countries WHERE `id` = ob_learners.`country_of_birth`) AS country_of_birth,
	(SELECT description FROM lookup_country_list WHERE `code` = ob_learners.`nationality`) AS nationality,
	CASE ob_learners.`is_non_eu_resident`
		WHEN '0' THEN 'No'
		WHEN '1' THEN 'Yes'
	END AS is_non_eu_resident,
	DATE_FORMAT(date_of_first_uk_entry, '%d/%m/%Y') AS date_of_first_entry_to_uk,
	DATE_FORMAT(date_of_most_recent_uk_entry, '%d/%m/%Y') AS date_of_most_recent_uk_entry,
	CASE ob_learners.need_visa_to_study
		WHEN '0' THEN 'No'
		WHEN '1' THEN 'Yes'
	END AS need_visa_to_study,
	ob_learners.`passport_number`,
	ob_learners.immigration_category,
	employers.legal_name AS employer_name_in_sunesis,
	(SELECT description FROM lookup_job_titles WHERE id = ob_learners.`job_title`) AS job_title,
	(SELECT legal_name FROM organisations WHERE id = ob_learners.`college_id`) AS college,
	DATE_FORMAT(tr.`start_date`, '%d/%m/%Y') AS start_date,
	DATE_FORMAT(tr.`target_date`, '%d/%m/%Y') AS planned_end_date,
	DATE_FORMAT(ob_learners.`target_date_practical_period`, '%d/%m/%Y') AS est_end_date_of_practical_period,
	ob_learners.`planned_otj_hours` AS planned_off_the_job_hours,
	frameworks.`title` AS framework,
	(SELECT courses.title FROM courses WHERE id = ob_learners.`course_id`) AS course,
	ob_learners.`main_aim`,
	ob_learners.tech_cert AS technical_certificate,
	ob_learners.`l2_found_competence` AS l2_foundation_comp,
	ob_learners.`fs_maths`,
	ob_learners.`fs_eng`,
	ob_learners.`fs_ict`,
	IF(ob_learners.ERR = 1, 'Yes', 'No') AS ERR,
	IF(ob_learners.PLTS = 1, 'Yes', 'No') AS PLTS,
	ob_learners.`other_qual`,
	(SELECT GROUP_CONCAT(brands.`title` SEPARATOR '; ') FROM brands INNER JOIN employer_business_codes ON brands.id = employer_business_codes.`brands_id` WHERE employer_business_codes.`employer_id` = employers.id) AS business_code,
	CONCAT(app_coordinators.firstnames, ' ', app_coordinators.surname) AS app_coordinator,
	tr.username,
	'' AS uploaded_files

FROM ob_learners
LEFT JOIN users ON ob_learners.`user_id` = users.`id`
LEFT JOIN tr ON users.`username` = tr.`username`
LEFT JOIN frameworks ON ob_learners.`framework_id` = frameworks.`id`
LEFT JOIN organisations AS employers ON employers.id = ob_learners.`employer_id`
LEFT JOIN users AS app_coordinators ON tr.`programme` = app_coordinators.id
LEFT JOIN contracts ON tr.`contract_id` = contracts.id
");

			if(!$_SESSION['user']->isAdmin())
				$sql->setClause("WHERE ob_learners.employer_id = '{$_SESSION['user']->employer_id}'");

			$view = $_SESSION[$key] = new ViewOnboardingReport();
			$view->setSQL($sql->__toString());

			$options = array(
				0 => array('Added', 'Added', null, 'HAVING stage = "Added"')
			,1 => array('Awaiting Learner', 'Awaiting Learner', null, 'HAVING stage = "Awaiting Learner"')
			,2 => array('Learner Completed And Awaiting Employer', 'Learner Completed And Awaiting Employer', null, 'HAVING stage = "Learner Completed And Awaiting Employer"')
			,3 => array('Fully Completed', 'Fully Completed', null, 'HAVING stage = "Fully Completed"')
			);
			$f = new DropDownViewFilter('filter_stage', $options, null, true);
			$f->setDescriptionFormat("Stage: %s");
			$view->addFilter($f);

			$f = new TextboxViewFilter('filter_surname', "WHERE ob_learners.surname LIKE '%s%%'", null);
			$f->setDescriptionFormat("Surname: %s");
			$view->addFilter($f);

			$f = new TextboxViewFilter('filter_firstnames', "WHERE ob_learners.firstnames LIKE '%s%%'", null);
			$f->setDescriptionFormat("Firstname: %s");
			$view->addFilter($f);

			$f = new TextboxViewFilter('filter_l03', "WHERE tr.l03 LIKE '%s%%'", null);
			$f->setDescriptionFormat("L03: %s");
			$view->addFilter($f);

			$options = array(
				0=>array(0, 'Show all', null, null),
				1=>array(1, 'Female', null, 'WHERE ob_learners.gender = "F"'),
				2=>array(2, 'Male', null, 'WHERE ob_learners.gender = "M"'),
				3=>array(3, 'Unknown', null, 'WHERE ob_learners.gender = "U"'),
				4=>array(4, 'Withheld', null, 'WHERE ob_learners.gender = "W"'));
			$f = new DropDownViewFilter('filter_gender', $options, null, 0, false);
			$f->setDescriptionFormat("Gender: %s");
			$view->addFilter($f);

			$options = array(
				0=>array(0, 'Show all', null, null),
				1=>array(1, 'No', null, 'WHERE ob_learners.`LLDD` = "N"'),
				2=>array(2, 'Yes', null, 'WHERE ob_learners.`LLDD` = "Y"'),
				3=>array(3, 'Prefer not to say', null, 'WHERE ob_learners.`LLDD` = "P"'));
			$f = new DropDownViewFilter('filter_lldd', $options, null, 0, false);
			$f->setDescriptionFormat("LLDD: %s");
			$view->addFilter($f);

			$options = array(
				0=>array(0, 'Show all', null, null),
				1=>array(1, 'No', null, 'WHERE ob_learners.`SEI` = "0"'),
				2=>array(2, 'Yes', null, 'WHERE ob_learners.`SEI` = "1"'));
			$f = new DropDownViewFilter('filter_SEI', $options, null, 0, false);
			$f->setDescriptionFormat("SEI: %s");
			$view->addFilter($f);

			$options = array(
				0=>array(0, 'Show all', null, null),
				1=>array(1, 'In paid employment', null, 'WHERE ob_learners.`EmploymentStatus` = "10"'),
				2=>array(2, 'Not in paid employment, looking for work and available to start work', null, 'WHERE ob_learners.`EmploymentStatus` = "11"'),
				3=>array(3, 'Not in paid employment, not looking for work and/or not available to start work', null, 'WHERE ob_learners.`EmploymentStatus` = "12"'),
				4=>array(4, 'Not known/dont want to provide', null, 'WHERE ob_learners.`EmploymentStatus` = "98"'));
			$f = new DropDownViewFilter('filter_emp_status', $options, null, 0, false);
			$f->setDescriptionFormat("Employment Status: %s");
			$view->addFilter($f);

			$options = "SELECT Ethnicity, Ethnicity_Desc, null, CONCAT('WHERE ob_learners.ethnicity=', CHAR(39), Ethnicity, CHAR(39)) FROM lis201213.ilr_ethnicity ORDER BY Ethnicity;";
			$f = new DropDownViewFilter('filter_ethnicity', $options, null, true);
			$f->setDescriptionFormat("Ethnicity: %s");
			$view->addFilter($f);

			$options = "SELECT DISTINCT contract_year, CONCAT(contract_year,'-',contract_year-2000+1), NULL, CONCAT('WHERE contracts.contract_year=', contract_year) FROM contracts ORDER BY contract_year DESC";
			$f = new DropDownViewFilter('filter_contract_year', $options, null, true);
			$f->setDescriptionFormat("Contract Year: %s");
			$view->addFilter($f);

			$options = "SELECT organisations.id, organisations.`legal_name`, LEFT(organisations.`legal_name`, 1), CONCAT('WHERE employers.id = ', CHAR(39), organisations.id, CHAR(39)) FROM organisations WHERE organisations.`organisation_type` = '2' ORDER BY organisations.`legal_name`;";
			$f = new DropDownViewFilter('filter_employer', $options, null, true);
			$f->setDescriptionFormat("Employer: %s");
			$view->addFilter($f);

			$options = "SELECT organisations.id, organisations.`legal_name`, LEFT(organisations.`legal_name`, 1), CONCAT('WHERE ob_learners.college_id = ', CHAR(39), organisations.id, CHAR(39)) FROM organisations WHERE organisations.`organisation_type` = '7' ORDER BY organisations.`legal_name`;";
			$f = new DropDownViewFilter('filter_college', $options, null, true);
			$f->setDescriptionFormat("College: %s");
			$view->addFilter($f);

			$options = "SELECT brands.id, brands.title, NULL, CONCAT('HAVING business_code LIKE ', CHAR(39), '%', brands.title, '%', CHAR(39)) FROM brands  ORDER BY brands.title;";
			$f = new DropDownViewFilter('filter_business_code', $options, null, true);
			$f->setDescriptionFormat("Business Code: %s");
			$view->addFilter($f);

			$options = array(
				0=>array(20,20,null,null),
				1=>array(50,50,null,null),
				2=>array(100,100,null,null),
				3=>array(200,200,null,null),
				4=>array(300,300,null,null),
				5=>array(400,400,null,null),
				6=>array(500,500,null,null),
				7=>array(0, 'No limit', null, null));
			$f = new DropDownViewFilter(View::KEY_PAGE_SIZE, $options, 20, false);
			$f->setDescriptionFormat("Records per page: %s");
			$view->addFilter($f);

			$options = array(
				0=>array(1, 'OB Learner creation date(asc.)', null, 'ORDER BY ob_learners.created'),
				1=>array(2, 'OB Learner creation date(desc.)', null, 'ORDER BY ob_learners.created DESC'));

			$f = new DropDownViewFilter('order_by', $options, 1, false);
			$f->setDescriptionFormat("Sort by: %s");
			$view->addFilter($f);
		}

		return $_SESSION[$key];
	}

	public function render(PDO $link)
	{
		$RUI = array('1' => 'About courses or learning opportunities', '2' => 'For surveys and research');
		$PMC = array('1' => 'By post', '2' => 'By phone');
		$LLDDCat = array(
			'4' => 'Visual impairment',
			'5' => 'Hearing impairment',
			'6' => 'Disability affecting mobility',
			'7' => 'Profound complex disabilities',
			'8' => 'Social and emotional difficulties',
			'9' => 'Mental health difficulty',
			'10' => 'Moderate learning difficulty',
			'11' => 'Severe learning difficulty',
			'12' => 'Dyslexia',
			'13' => 'Dyscalculia',
			'14' => 'Autism spectrum disorder',
			'15' => 'Asperger\'s syndrome',
			'16' => 'Temporary disability after illness (for example post-viral) or accident',
			'17' => 'Speech, Language and Communication Needs',
			'93' => 'Other physical disability',
			'94' => 'Other specific learning difficulty (e.g. Dyspraxia)',
			'95' => 'Other medical condition (for example epilepsy, asthma, diabetes)',
			'96' => 'Other learning difficulty',
			'97' => 'Other disability',
			'98' => 'Prefer not to say'
		);

		$columns = '';
		$rows = array();
		$result = DAO::getResultset($link, $this->getSQLStatement()->__toString(), DAO::FETCH_ASSOC);

		foreach($result AS $rs)
		$rows[] = $rs;
		unset($result);

		echo $this->getViewNavigator();
		echo '<div align="center"><table id="tblLearners" class="table table-bordered small">';
		echo '<thead><tr>';
		foreach($rows AS $row)
		{
			$columns = array_keys($row);
			foreach($columns AS $column)
				echo '<th class="bottomRow">' . ucwords(str_replace("_"," ",str_replace("_and_"," & ", $column))) . '</th>';
			break;
		}
		echo '</tr></thead>';
		echo '<tbody>';
		foreach($rows AS $row)
		{
			if(!is_null($row['tr_id']))
				echo HTML::viewrow_opening_tag('do.php?_action=read_training_record&amp;id=' . $row['tr_id']);
			else
				echo '<tr>';
			foreach($columns AS $column)
			{
				if($column == 'stage')
				{
					if($row[$column] == 'Added')
						echo '<td>' . ((isset($row[$column]))?(($row[$column]=='')?'&nbsp':'<span class="label label-warning">'.$row[$column].'</span>'):'&nbsp') . '</td>';
					elseif($row[$column] == 'Awaiting Learner')
						echo '<td>' . ((isset($row[$column]))?(($row[$column]=='')?'&nbsp':'<span class="label label-danger">'.$row[$column].'</span>'):'&nbsp') . '</td>';
					elseif($row[$column] == 'Learner Completed And Awaiting Employer')
						echo '<td>' . ((isset($row[$column]))?(($row[$column]=='')?'&nbsp':'<span class="label label-info">'.$row[$column].'</span>'):'&nbsp') . '</td>';
					elseif($row[$column] == 'Fully Completed')
						echo '<td>' . ((isset($row[$column]))?(($row[$column]=='')?'&nbsp':'<span class="label label-success">'.$row[$column].'</span>'):'&nbsp') . '</td>';
					else
						echo '<td></td>';
				}
				elseif($column == 'RUI')
				{
					echo '<td>';
					foreach(explode(',', $row['RUI']) AS $v)
						echo isset($RUI[$v]) ? $RUI[$v] . '; ' : '';
					echo '</td>';
				}
				elseif($column == 'PMC')
				{
					echo '<td>';
					foreach(explode(',', $row['PMC']) AS $v)
						echo isset($PMC[$v]) ? $PMC[$v] . '; ' : '';
					echo '</td>';
				}
				elseif($column == 'llddcat')
				{
					echo '<td>';
					foreach(explode(',', $row['llddcat']) AS $v)
						echo isset($LLDDCat[$v]) ? $LLDDCat[$v] . '; ' : '';
					echo '</td>';
				}
				elseif($column == 'primary_lldd')
				{
					echo isset($LLDDCat[$row[$column]])?'<td>'.$LLDDCat[$row[$column]].'</td>':'<td></td>';
				}
				elseif($column == 'uploaded_files')
				{
					$dir = Repository::getRoot() . '/' . $row['username'] . '/Certificates';
					if(is_dir($dir))
					{
						$files = Repository::readDirectory($dir);
						echo '<td>';
						foreach($files AS $f) /* @var $f RepositoryFile */
						{
							echo '<a href="' . $f->getDownloadURL() . '">' . str_replace(' ', '&nbsp;', $f->getName()) . '</a><br>';
						}
						echo '</td>';
					}
					else
						echo '<td></td>';
				}
				else
					echo '<td>' . ((isset($row[$column]))?(($row[$column]=='')?'&nbsp':$row[$column]):'&nbsp') . '</td>';
			}
			echo '</tr>';
		}
		echo '</tbody></table></div>';
		echo $this->getViewNavigator();
	}
}
?>