<?php
class ViewDataReport extends View
{
	public static function getInstance($link)
	{
		$key = 'view_'.__CLASS__;
		if(!isset($_SESSION[$key]))
		{
			$sql = new SQLStatement("
SELECT DISTINCT
	tr.id as TRID,
	tr.`firstnames`,
	tr.`surname` as `last_name`,
    concat(tr.firstnames, ' ',tr.surname) as `learner_name`,
    tr.uln as `ULN`,
    '' as `learning_status`,
    tr.status_code as status_code,
    tr.bil_withdrawal,
    tr.outcome,
    DATE_FORMAT(tr.start_date,'%d-%m-%Y') as start_date,
    DATE_FORMAT(tr.target_date,'%d-%m-%Y') as practical_period_end_date,
    DATE_FORMAT(tr.closure_date,'%d-%m-%Y') as actual_end_date,
    (SELECT GROUP_CONCAT(tags.`name` SEPARATOR '; ') FROM tags INNER JOIN taggables ON tags.`id` = taggables.`tag_id` WHERE taggables.`taggable_id` = tr.`id` AND taggables.`taggable_type` = 'Training Record') AS provision_tag,
	courses.title AS programme,
    contracts.title as contract,
    IF(CURDATE() > target_date, 'OOF', 'Funded') as `funded/OOF`,
    CONCAT(assessors.firstnames, ' ', assessors.surname) as assessor,
    CONCAT(iqas.firstnames, ' ', iqas.surname) as `IQA`,
    CONCAT(team_leaders.firstnames,' ',team_leaders.surname) AS team_leader,
    'TNP1' as `TNP1`,
    'TNP2' as `TNP2`,
    'TNP3' as `TNP3`,
    'TNP4' as `TNP4`,
    round(IF(tr.target_date <= CURDATE(), '100', (DATEDIFF(CURDATE(), tr.start_date) / DATEDIFF(tr.target_date, tr.start_date)) * 100),2) AS expected_progress_to_date,
    COALESCE(round(onefile_learners.Progress,2),0) as actual_progress,
    round(IF(tr.target_date <= CURDATE(), '100', (DATEDIFF(CURDATE(), tr.start_date) / DATEDIFF(tr.target_date, tr.start_date)) * 100),2) - COALESCE(round(onefile_learners.Progress,2),0) as progress_difference,
    (SELECT IF(AssessorSignedOn > DATE_SUB(NOW(), INTERVAL 6 WEEK),'No','Yes') FROM onefile_tlap WHERE tr.onefile_id = onefile_tlap.LearnerID ORDER BY AssessorSignedOn DESC LIMIT 1) as no_assessment_in_last_6_weeks,
    (SELECT DATE_FORMAT(AssessorSignedOn,'%d-%m-%Y') FROM onefile_tlap WHERE tr.onefile_id = onefile_tlap.LearnerID ORDER BY AssessorSignedOn DESC LIMIT 1) as last_visit_date_TLAP,
    (SELECT DATE_FORMAT(PlanOn,'%d-%m-%Y') FROM onefile_tlap WHERE tr.onefile_id = onefile_tlap.LearnerID AND PlanOn >= CURDATE() ORDER BY PlanOn LIMIT 1) as next_visit_date_TLAP,
    (SELECT IF(StartedOn > DATE_SUB(NOW(), INTERVAL 10 WEEK),'No','Yes') FROM onefile_reviews WHERE tr.onefile_id = onefile_reviews.LearnerID ORDER BY StartedOn DESC LIMIT 1) as no_reviews_in_last_10_weeks,
    (SELECT DATE_FORMAT(StartedOn,'%d-%m-%Y') FROM onefile_reviews WHERE tr.onefile_id = onefile_reviews.LearnerID ORDER BY StartedOn DESC LIMIT 1) as last_review_date,
    (SELECT DATE_FORMAT(ScheduledFor,'%d-%m-%Y') FROM onefile_reviews WHERE tr.onefile_id = onefile_reviews.LearnerID AND ScheduledFor >= CURDATE() ORDER BY ScheduledFor LIMIT 1) as next_review_date,
    (select (planned_otj * round(IF(tr.target_date <= CURDATE(), '100', (DATEDIFF(CURDATE(), tr.start_date) / DATEDIFF(tr.target_date, tr.start_date)) * 100),2) / 100) from onefile_otj where onefile_otj.onefile_learner_id = tr.onefile_id) as `expected_OTJH_to_date`,
    (select actual_hours from onefile_otj where onefile_otj.onefile_learner_id = tr.onefile_id) as `OTJH_completed_to_date`,
    (select (planned_otj * round(IF(tr.target_date <= CURDATE(), '100', (DATEDIFF(CURDATE(), tr.start_date) / DATEDIFF(tr.target_date, tr.start_date)) * 100),2) / 100) - actual_hours from onefile_otj where onefile_otj.onefile_learner_id = tr.onefile_id) as `OTJH_difference`,
    '' as ALN,
    IF(english.end_date IS NOT NULL, 'Achieved',IF(english.aptitude=1,'Exempt','')) AS english_aim,
    DATE_FORMAT(english.end_date,'%d-%m-%Y')  AS english_achievement_date,
    IF(maths.end_date IS NOT NULL, 'Achieved',IF(maths.aptitude=1,'Exempt','')) AS maths_aim,
    DATE_FORMAT(maths.end_date,'%d-%m-%Y') AS maths_achievement_date,
    CONCAT(tutors.firstnames, ' ', tutors.surname) as FS_tutor,
    DATE_FORMAT(tr.achievement_date,'%d-%m-%Y') as `date_of_achievement`,
    IF(tr.home_telephone IS NOT NULL, tr.home_telephone, tr.home_mobile) as contact_number,
    IF(tr.home_email IS NOT NULL, tr.home_email, tr.work_email) as email_address,
    tr.dob as date_of_birth,
    YEAR(CURDATE()) - YEAR(tr.dob) - (DATE_FORMAT(CURDATE(), '%m-%d') < DATE_FORMAT(tr.dob, '%m-%d')) as age,
    tr.gender as gender,
    tr.ethnicity as ethnicity,
    employers.legal_name as employer_name,
    locations.postcode as employer_postcode,
    locations.telephone as work_telephone,
    CASE tr.`sales_lead` WHEN 1 THEN 'Frontline' WHEN 2 THEN 'Links Training' WHEN 3 THEN 'MOD' WHEN 4 THEN 'Internal ELA' END AS sales_lead,
    if(tr.outcome=8, DATE_FORMAT(tr.closure_date,'%d-%m-%Y'),'') as gateway_trigger_date,
    closure_date as actual_end_date,
    '' as EPA_result
FROM tr
    LEFT JOIN courses_tr on courses_tr.tr_id = tr.id
    LEFT JOIN courses on courses.id = courses_tr.course_id
    LEFT JOIN contracts on contracts.id = tr.contract_id
    LEFT JOIN users as assessors on assessors.id = tr.assessor 
    LEFT JOIN users as tutors on tutors.id = tr.tutor 
    LEFT JOIN users as iqas on iqas.id = tr.verifier 
	LEFT JOIN users AS team_leaders ON team_leaders.username = assessors.supervisor
    LEFT JOIN onefile_learners on tr.onefile_id = onefile_learners.ID
    LEFT JOIN organisations as employers on employers.id = tr.employer_id
    LEFT JOIN locations on locations.id = tr.employer_location_id
    LEFT JOIN student_qualifications AS english ON english.tr_id = tr.id AND LOCATE('English',english.internaltitle)>0
    LEFT JOIN student_qualifications AS maths ON maths.tr_id = tr.id AND LOCATE('Maths',maths.internaltitle)>0;
			");

            //$sql->setClause("WHERE student_qualifications.framework_id != '0'");

			$view = $_SESSION[$key] = new ViewDataReport();
			$view->setSQL($sql->__toString());

			$options = array(
				0=>array('SHOW_ALL', 'Show all', null, 'WHERE status_code in (1,2,3,4,5,6,7)'),
				1=>array('1', '1. The learner is continuing ', null, 'WHERE tr.status_code=1'),
				2=>array('2', '2. The learner has completed ', null, 'WHERE tr.status_code=2'),
				3=>array('3', '3. The learner has withdrawn ', null, 'WHERE tr.status_code=3'),
				4=>array('4', '4. The learner has transferred ', null, 'WHERE tr.status_code = 4'),
				5=>array('5', '5. Changes in learning ', null, 'WHERE tr.status_code = 5'),
				6=>array('6', '6. Learner has temporarily withdrawn', null, 'WHERE tr.status_code = 6'),
				7=>array('7', '7. Delete from ILR', null, 'WHERE tr.status_code = 7'));
			$f = new CheckboxViewFilter('filter_record_status', $options, array('1'));
			$f->setDescriptionFormat("Show: %s");
			$view->addFilter($f);

			/*$f = new TextboxViewFilter('filter_l03', "WHERE tr.l03 LIKE '%s%%'", null);
			$f->setDescriptionFormat("L03: %s");
			$view->addFilter($f);

            */
			$f = new TextboxViewFilter('filter_uln', "WHERE tr.uln LIKE '%s%%'", null);
			$f->setDescriptionFormat("Learner ULN: %s");
			$view->addFilter($f);

			$f = new TextboxViewFilter('filter_firstnames', "WHERE tr.firstnames LIKE '%s%%'", null);
			$f->setDescriptionFormat("First Name: %s");
			$view->addFilter($f);

			$f = new TextboxViewFilter('filter_surname', "WHERE tr.surname LIKE '%s%%'", null);
			$f->setDescriptionFormat("Surname: %s");
			$view->addFilter($f);

            /*
			$options = "SELECT DISTINCT organisations.id, legal_name, null, CONCAT('WHERE tr.employer_id=',organisations.id) FROM organisations INNER JOIN tr ON organisations.id = tr.employer_id WHERE organisation_type LIKE '%2%' ORDER BY legal_name";
			$f = new DropDownViewFilter('filter_employer', $options, null, true);
			$f->setDescriptionFormat("Employer: %s");
			$view->addFilter($f);

			if($_SESSION['user']->type == User::TYPE_MANAGER)
				$options = "SELECT id, CONCAT(firstnames,' ',surname), null, CONCAT('WHERE groups.assessor=',char(39),id,char(39),' OR tr.assessor=' , char(39),id, char(39)) FROM users WHERE type = " . User::TYPE_ASSESSOR . " AND users.employer_id = '" . $_SESSION['user']->employer_id . "' ORDER BY firstnames, surname";
			else
				$options = "SELECT id, CONCAT(firstnames,' ',surname), null, CONCAT('WHERE groups.assessor=',char(39),id,char(39),' OR tr.assessor=' , char(39),id, char(39)) FROM users WHERE type = " . User::TYPE_ASSESSOR . " ORDER BY firstnames, surname";
			$f = new DropDownViewFilter('filter_assessor', $options, null, true);
			$f->setDescriptionFormat("Assessor: %s");
			$view->addFilter($f);

			$f = new TextboxViewFilter('filter_qan', "WHERE REPLACE(student_qualifications.id,'/','') LIKE REPLACE('%%%s%%','/','') ", null);
			$f->setDescriptionFormat("Filter by QAN: %s");
			$view->addFilter($f);

			$f = new TextboxViewFilter('filter_q_title', "WHERE student_qualifications.title LIKE '%%%s%%' ", null);
			$f->setDescriptionFormat("Filter by Title: %s");
			$view->addFilter($f);

			$options = array(
				0=>array(1, 'All qualifications', null, null),
				1=>array(2, 'Exempted', null, ' WHERE aptitude = 1'),
				2=>array(3, 'Not-exempted', null, ' WHERE aptitude != 1'));
			$f = new DropDownViewFilter('filter_exemption', $options, 1, false);
			$f->setDescriptionFormat("Exemption: %s");
			$view->addFilter($f);

			$options = <<<SQL
SELECT DISTINCT
	qualification_type, CONCAT(qualification_type, ' - ', lookup_qual_type.`description`), NULL, CONCAT(" WHERE qualification_type=",CHAR(39),qualification_type,CHAR(39))
FROM
	student_qualifications LEFT JOIN lookup_qual_type ON student_qualifications.`qualification_type` = lookup_qual_type.`id`
ORDER BY
	qualification_type;
SQL;
			;
			$f = new DropDownViewFilter('filter_q_type', $options, null, true);
			$f->setDescriptionFormat("Qualification Type: %s");
			$view->addFilter($f);

			$options = 'SELECT DISTINCT awarding_body, awarding_body, null, CONCAT(" WHERE awarding_body=",char(39),awarding_body,char(39)) FROM student_qualifications ORDER BY awarding_body';
			$f = new DropDownViewFilter('filter_awarding_body', $options, null, true);
			$f->setDescriptionFormat("Awarding Body: %s");
			$view->addFilter($f);

			if($_SESSION['user']->type == User::TYPE_MANAGER)
				$options = 'SELECT id, legal_name, null, CONCAT("WHERE tr.provider_id=",id) FROM organisations WHERE organisation_type LIKE "%3%" AND organisations.id = ' . $_SESSION['user']->employer_id . ' ORDER BY legal_name';
			else
				$options = 'SELECT id, legal_name, null, CONCAT("WHERE tr.provider_id=",id) FROM organisations WHERE organisation_type LIKE "%3%" ORDER BY legal_name';
			$f = new DropDownViewFilter('filter_provider', $options, null, true);
			$f->setDescriptionFormat("Provider: %s");
			$view->addFilter($f);

			$format = "WHERE student_qualifications.start_date >= '%s'";
			$f = new DateViewFilter('filter_from_start_date', $format, '');
			$f->setDescriptionFormat("From start date: %s");
			$view->addFilter($f);

			$format = "WHERE student_qualifications.start_date <= '%s'";
			$f = new DateViewFilter('filter_to_start_date', $format, '');
			$f->setDescriptionFormat("To start date: %s");
			$view->addFilter($f);

			$format = "WHERE student_qualifications.end_date >= '%s'";
			$f = new DateViewFilter('filter_from_end_date', $format, '');
			$f->setDescriptionFormat("From plan end date: %s");
			$view->addFilter($f);

			$format = "WHERE student_qualifications.end_date <= '%s'";
			$f = new DateViewFilter('filter_to_end_date', $format, '');
			$f->setDescriptionFormat("To plan end date: %s");
			$view->addFilter($f);

			$format = "WHERE student_qualifications.actual_end_date >= '%s'";
			$f = new DateViewFilter('filter_from_actual_end_date', $format, '');
			$f->setDescriptionFormat("From actual end date: %s");
			$view->addFilter($f);

			$format = "WHERE student_qualifications.actual_end_date <= '%s'";
			$f = new DateViewFilter('filter_to_actual_end_date', $format, '');
			$f->setDescriptionFormat("To actual end date: %s");
			$view->addFilter($f);*/

			$options = array(
				0=>array(20,20,null,null),
				1=>array(50,50,null,null),
				2=>array(100,100,null,null),
				3=>array(0,'No limit',null,null));
			$f = new DropDownViewFilter(View::KEY_PAGE_SIZE, $options, 20, false);
			$f->setDescriptionFormat("Records per page: %s");
			$view->addFilter($f);
            /*    
			$options = array(
				0=>array(1, 'Surname (asc)', null, 'ORDER BY surname,firstnames'),
				1=>array(2, 'Qualification Title (asc)', null, 'ORDER BY title'));
			$f = new DropDownViewFilter('order_by', $options, 1, false);
			$f->setDescriptionFormat("Sort by: %s");
			$view->addFilter($f);*/

		}
		return $_SESSION[$key];
	}


	public function render(PDO $link, $columns)
	{
		//if(SOURCE_BLYTHE_VALLEY) pr($this->getSQL());
		/* @var $result pdo_result */
		$st = DAO::query($link, $this->getSQL());

		if($st)
		{
			echo $this->getViewNavigator();
			//echo '<div class="table-responsive"><table id="tblDataReport" class="table table-bordered">';
			echo '<div><table class="table table-bordered">';
			echo '<thead><tr>';

			foreach($columns as $column)
			{
				echo '<th>' . ucwords(str_replace("_"," ",str_replace("_and_"," & ",$column))) . '</th>';
			}

			echo '<tbody>';
			while($row = $st->fetch())
			{

                $tr_id = $row['TRID'];
                //$llddhealthprob =  '"' . "/Learner/LLDDHealthProb|ilr/learner/L14" . '"';
                //$disability =  '"' . "/Learner/LLDDandHealthProblem[LLDDType=\'DS\']/LLDDCode|/ilr/learner/L15" . '"';
                //$learning_difficulty = '"' . "/Learner/LLDDandHealthProblem[LLDDType='LD']/LLDDCode|/ilr/learner/L16" . '"';
                //$provspec_a = '"' . "/Learner/ProviderSpecLearnerMonitoring[ProvSpecLearnMonOccur='A']/ProvSpecLearnMon" . '"';
                //$provspec_b = '"' . "/Learner/ProviderSpecLearnerMonitoring[ProvSpecLearnMonOccur='B']/ProvSpecLearnMon" . '"';
                //$program_type = '"' . "/Learner/LearningDelivery[LearnAimRef=\'ZPROG001\']/ProgType" . '"';
                //$pathway_code = '"' . "/Learner/LearningDelivery[LearnAimRef=\'ZPROG001\']/PwayCode" . '"';
                //$achievement_date = '"' . "/Learner/LearningDelivery[LearnAimRef=\'ZPROG001\']/AchDate" . '"';
                //$provspecdelmona = '"' . "/Learner/LearningDelivery[LearnAimRef=\'ZPROG001\']/ProviderSpecDeliveryMonitoring[ProvSpecDelMonOccur=\'A\']/ProvSpecDelMon" . '"';
                //$provspecdelmonb = '"' . "/Learner/LearningDelivery[LearnAimRef=\'ZPROG001\']/ProviderSpecDeliveryMonitoring[ProvSpecDelMonOccur=\'B\']/ProvSpecDelMon" . '"';
                //$provspecdelmonc = '"' . "/Learner/LearningDelivery[LearnAimRef=\'ZPROG001\']/ProviderSpecDeliveryMonitoring[ProvSpecDelMonOccur=\'C\']/ProvSpecDelMon" . '"';
                //$provspecdelmond = '"' . "/Learner/LearningDelivery[LearnAimRef=\'ZPROG001\']/ProviderSpecDeliveryMonitoring[ProvSpecDelMonOccur=\'D\']/ProvSpecDelMon" . '"';
                //$prior_attain = '"' . "/Learner/PriorAttain" . '"';
                //$ilr_destination = '"' . "/Learner/Dest" . '"';
                //$WithdrawReason = '"' . "/Learner/LearningDelivery[LearnAimRef=\'ZPROG001\']/WithdrawReason" . '"';
                //$ilr_restart_field = '"' . "/Learner/LearningDelivery[LearnAimRef=\'ZPROG001\']/LearningDeliveryFAM[LearnDelFAMType=\'RES\']/LearnDelFAMCode" . '"';
                //$primary_lldd = '"' . "/Learner/LLDDandHealthProblem[PrimaryLLDD=\'1\']/LLDDCat" . '"';
                $tnp1 = '"' . "/Learner/LearningDelivery[AimType=1]/TrailblazerApprenticeshipFinancialRecord[TBFinType='TNP' and TBFinCode=1]/TBFinAmount" . '"';
                $tnp2 = '"' . "/Learner/LearningDelivery[AimType=1]/TrailblazerApprenticeshipFinancialRecord[TBFinType='TNP' and TBFinCode=2]/TBFinAmount" . '"';
                $tnp3 = '"' . "/Learner/LearningDelivery[AimType=1]/TrailblazerApprenticeshipFinancialRecord[TBFinType='TNP' and TBFinCode=3]/TBFinAmount" . '"';
                $tnp4 = '"' . "/Learner/LearningDelivery[AimType=1]/TrailblazerApprenticeshipFinancialRecord[TBFinType='TNP' and TBFinCode=4]/TBFinAmount" . '"';
                $aln = '"' . "/Learner/LearningDelivery[AimType=1]/LearningDeliveryFAM[LearnDelFAMType='LSF']/LearnDelFAMCode[last()]" . '"';
                $outgrade = '"' . "/Learner/LearningDelivery[AimType=1]/OutGrade" . '"';
                $res = DAO::getResultset($link, "SELECT extractvalue(ilr, $tnp1),extractvalue(ilr,$tnp2),extractvalue(ilr,$tnp3),extractvalue(ilr,$tnp4),extractvalue(ilr,$aln),extractvalue(ilr,$outgrade) FROM ilr WHERE ilr.tr_id = $tr_id  ORDER BY ilr.`contract_id` DESC, submission DESC LIMIT 0,1");
                $row['TNP1'] = @$res[0][0];
                $row['TNP2'] = @$res[0][1];
                $row['TNP3'] = @$res[0][2];
                $row['TNP4'] = @$res[0][3];
                $row['ALN'] = (@$res[0][4]==1)?'Yes':'No';
                $row['EPA_result'] = @$res[0][5];
                //$row['provspeclearnmona'] = @$res[0][1];
                //$row['provspeclearnmonb'] = @$res[0][2];
                //$row['disability'] = @$res[0][3];
                //$row['learning_difficulty'] = @$res[0][4];
                //$row['program_type'] = @$res[0][5];
                //$row['pathway_code'] = @$res[0][6];
                //$row['prior_attain'] = @$res[0][7];
                //$row['ilr_destination'] = @$res[0][8];
                //$row['withdraw_reason'] = @$res[0][9];
                //$row['ilr_restart_field'] = @$res[0][10];
                //if(isset($row['primary_lldd']))
                 //   $row['primary_lldd'] = @$res[0][11];
                //$row['achievement_date'] = @$res[0][12];
                //$row['provspecdelmona'] = @$res[0][13];
                //$row['provspecdelmonb'] = @$res[0][14];
                //$row['provspecdelmonc'] = @$res[0][15];
                //$row['provspecdelmond'] = @$res[0][16];


                /*$tr_id = $row['tr_id'];
				$minutes_attended = DAO::getSingleValue($link, "SELECT (SUM(HOUR(TIMEDIFF(time_to, time_from)))*60) + (SUM(MINUTE(TIMEDIFF(time_to, time_from)))) FROM otj WHERE tr_id = '{$tr_id}'");
				$hours_attended = ViewOTJ::convertToHoursMins($minutes_attended, '%02d hours %02d minutes');
				$minutes_remaining = ($row['otj_hours_due'] * 60) - $minutes_attended;
				$hours_remaining = ViewOtj::convertToHoursMins($minutes_remaining, '%02d hours %02d minutes');
				$row['otj_hours_actual'] = $hours_attended;
				$row['otj_hours_remain'] = $hours_remaining;

				$LearnAimRef = str_replace("/","",$row['a09']);
				if($row['contract_year']<2012)
				{
					$x = '"' . "/ilr/programmeaim[A09='$LearnAimRef' AND A15!='99']/A34|/ilr/main[A09='$LearnAimRef']/A34|/ilr/subaim[A09='$LearnAimRef']/A34" . '"';
					$y = '"' . "/ilr/programmeaim[A09='$LearnAimRef' AND A15!='99']/A35|/ilr/main[A09='$LearnAimRef']/A35|/ilr/subaim[A09='$LearnAimRef']/A35" . '"';
					$z = "0";
				}
				else
				{
					$x = '"' . "/Learner/LearningDelivery[LearnAimRef='$LearnAimRef']/CompStatus" . '"';
					$y = '"' . "/Learner/LearningDelivery[LearnAimRef='$LearnAimRef']/Outcome" . '"';
					$z = '"' . "/Learner/LearningDelivery[LearnAimRef='$LearnAimRef']/LearningDeliveryFAM[LearnDelFAMType='RES']/LearnDelFAMCode" . '"';
				}
				$res = DAO::getResultset($link, "select extractvalue(ilr,$x),extractvalue(ilr,$y),extractvalue(ilr,$z) from ilr LEFT JOIN contracts ON contracts.id = ilr.contract_id where tr_id = $tr_id  order by contract_year DESC, submission DESC LIMIT 1");
				$row['comp_status'] = isset($res[0][0])? $res[0][0]: '&nbsp';
				$row['outcome'] = isset($res[0][1])?$res[0][1]: '&nbsp';
				$row['res'] = isset($res[0][2])?$res[0][2]: '&nbsp';

				if($row['contract_year']<2012)
					$row['res'] = '';

				echo HTML::viewrow_opening_tag('do.php?_action=read_training_record&id=' . $tr_id);
				echo '<td>';
				$folderColour = $row['gender'] == 'M' ? 'blue' : 'red';
				$textStyle = '';
				switch($row['status_code'])
				{
					case 1:
						echo "<img src=\"/images/folder-$folderColour.png\" border=\"0\" alt=\"\" />";
						break;

					case 2:
						echo "<img src=\"/images/folder-$folderColour-happy.png\" border=\"0\" alt=\"\" />";
						break;

					case 3:
						echo "<img src=\"/images/folder-$folderColour-sad.png\" border=\"0\" alt=\"\" />";
						break;

					case 4:
					case 5:
					case 6:
						echo "<img src=\"/images/folder-$folderColour.png\" border=\"0\" style=\"opacity:0.3\" alt=\"\" />";
						$textStyle = 'text-decoration:line-through;color:gray';
						break;

					default:
						echo '?';
						break;
				}
				echo '</td>';*/

				foreach($columns as $column)
				{
                    if($column=='learning_status')
                    {
                        if($row['status_code']=='1')
                            echo '<td>In-learning</td>';
                        elseif($row['status_code']=='6')
                            echo '<td>Break-in-learning</td>';
                        elseif($row['status_code']=='3')
                            echo '<td>Withdrawn</td>';
                        elseif($row['status_code']=='2')
                            echo '<td>Achieved</td>';
                        elseif($row['bil_withdrawal']=='1')
                            echo '<td>Under consideration for BIL</td>';
                        elseif($row['bil_withdrawal']=='2')
                            echo '<td>Under consideration for withdrawal</td>';
                        elseif($row['outcome']=='8')
                            echo '<td>At EPA</td>';
                    }
                    else
                        echo '<td>' . ((isset($row[$column]))?(($row[$column]=='')?'&nbsp':$row[$column]):'&nbsp') . '</td>';
				}

				echo '</tr>';
			}

			echo '</tbody></table></div>';
			echo $this->getViewNavigator();
		}
		else
		{
			throw new DatabaseException($link, $this->getSQL());
		}
	}
}
?>