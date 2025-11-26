<?php
class learner_home_page implements IAction
{
	public $_type = null;
	public function execute(PDO $link)
	{
		// Learner photo
		$photopath = $_SESSION['user']->getPhotoPath();
		if($photopath){
			$photopath = "do.php?_action=display_image&username=".rawurlencode($_SESSION['user']->username);
		} else {
			$photopath = "/images/no_photo.png";
		}

		$tr_id = DAO::getSingleValue($link, "SELECT tr.id FROM tr WHERE tr.username = '{$_SESSION['user']->username}' ORDER BY tr.id DESC LIMIT 1");
		if($tr_id == '')
			pre('There is no training record for you.');
		$tr = TrainingRecord::loadFromDatabase($link, $tr_id);

		if($_SESSION['user']->type == User::TYPE_LEARNER && $_SESSION['user']->username != $tr->username)
		{
			throw new UnauthorizedException();
		}

		$events = $tr->getUpcomingEvents($link);
		$notifResult = $tr->getWorkbookNotifications($link);

		//$retail_qual = DAO::getSingleValue($link, "SELECT COUNT(*) FROM student_qualifications WHERE tr_id = '{$tr->id}' AND REPLACE(id, '/', '') = '" . Workbook::RETAIL_QAN . "'");
		//$this->_type = $retail_qual > 0 ? 'retail' : 'cs';
		$this->_type = '';
		$retail_qual = DAO::getSingleValue($link, "SELECT COUNT(*) FROM student_qualifications WHERE tr_id = '{$tr->id}' AND REPLACE(id, '/', '') = '" . Workbook::RETAIL_QAN . "'");
		if($retail_qual > 0)
        {
            $this->_type = 'retail';
        }
		$_cs_qual = DAO::getSingleValue($link, "SELECT COUNT(*) FROM student_qualifications WHERE tr_id = '{$tr->id}' AND REPLACE(id, '/', '') = '" . Workbook::CS_QAN . "'");
		if($_cs_qual > 0)
        {
            $this->_type = 'cs';
        }

		$assessor = !is_null($tr->assessor) && $tr->assessor != 0?User::loadFromDatabaseById($link, $tr->assessor):null;
		// Assessor photo
		$assessor_photopath = "/images/no_photo.png";
		if(!is_null($assessor))
		{
			if($assessor->getPhotoPath() != '')
			{
				$assessor_photopath = "do.php?_action=display_image&username=".rawurlencode($assessor->username);
			}
		}
		$tutor = !is_null($tr->tutor) && $tr->tutor != 0?User::loadFromDatabaseById($link, $tr->tutor):null;
		// Tutor photo
		$tutor_photopath = "/images/no_photo.png";
		if(!is_null($tutor))
		{
			if($tutor->getPhotoPath() != '')
			{
				$tutor_photopath = "do.php?_action=display_image&username=".rawurlencode($tutor->username);
			}
		}

		$que = "select sum(IF(aptitude=1,100,IF(unitsUnderAssessment>100,100,unitsUnderAssessment))/(SELECT SUM(proportion) FROM student_qualifications AS sq WHERE sq.tr_id = student_qualifications.tr_id AND aptitude!=1 GROUP BY student_qualifications.tr_id)*proportion) from student_qualifications where tr_id='$tr->id' and aptitude!=1";
		$achieved = trim(DAO::getSingleValue($link, $que));
		$framework = DAO::getObject($link, "SELECT framework_code, frameworks.title FROM frameworks INNER JOIN student_frameworks ON frameworks.id = student_frameworks.id WHERE student_frameworks.tr_id = '{$tr->id}'");
		$course = DAO::getObject($link, "SELECT * FROM courses INNER JOIN courses_tr ON courses.id = courses_tr.course_id WHERE courses_tr.tr_id = '{$tr->id}'");
		$listStatus = array(
			'1' => 'Continuing'
		, '2' => 'Completed'
		, '3' => 'Withdrawn'
		, '4' => 'Transferred'
		, '5' => 'Change in learning'
		, '6' => 'Temporarily withdrawn'
		);
		if($tr->status_code!='1')
		{
			$target = "100";
		}
		else
		{
			$target = DAO::getSingleValue($link, "SELECT SUM(`sub`.target*proportion/(SELECT SUM(proportion) FROM student_qualifications WHERE tr_id = tr.id AND aptitude!=1))
FROM tr
LEFT OUTER JOIN (SELECT
student_milestones.tr_id,
student_qualifications.proportion,
CASE timestampdiff(MONTH, student_qualifications.start_date, CURDATE())
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
END AS target
FROM
student_milestones
INNER JOIN student_qualifications ON student_qualifications.id = student_milestones.`qualification_id` AND student_milestones.tr_id = student_qualifications.`tr_id` AND student_milestones.`tr_id` = $tr->id and student_qualifications.aptitude!=1
GROUP BY student_milestones.`qualification_id`) AS `sub` ON `sub`.tr_id = tr.id WHERE tr.id = $tr->id;
");
		}

		$stdQuals = array();
		$sql = <<<SQL
SELECT
	REPLACE(id, '/', '') AS id,
	if(student_qualifications.end_date<CURDATE(),1,0) as passed,
	timestampdiff(MONTH, student_qualifications.start_date, CURDATE()) as cmonth,
	end_date,start_date,
	IF(student_qualifications.unitsUnderAssessment>100,100,student_qualifications.unitsUnderAssessment) as unitsUnderAssessment,
	internaltitle
FROM
	student_qualifications
WHERE
	tr_id = '$tr->id'
SQL;
		$result = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);

		foreach($result AS $row)
		{
			$qual_id = $row['id'];
			if(!isset($row['cmonth']))
				$row['cmonth'] = 100;
			$current_month_since_study_start_date = $row['cmonth'];
			$month = "month_" . ($current_month_since_study_start_date);
			$internaltitle = $row['internaltitle'];
			if(!isset($row['passed']))
				$row['passed'] = 0;

			if($row['passed']=='1')
				$target = 100;
			else
			{
				if($current_month_since_study_start_date>=1 && $current_month_since_study_start_date<=36)
				{// Calculating target month and target
					$internaltitle = addslashes((string)$internaltitle);
					$que = "select avg($month) from student_milestones LEFT JOIN student_qualifications ON student_qualifications.id = student_milestones.qualification_id AND student_qualifications.tr_id = student_milestones.tr_id where student_qualifications.aptitude!=1 and chosen=1 and REPLACE(qualification_id, '/', '')='$qual_id' and student_milestones.internaltitle='$internaltitle' and student_milestones.tr_id={$tr->id}";
					$target = trim(DAO::getSingleValue($link, $que));
				}
				else
					$target='0';
			}
			$tdate = new Date($row['end_date']);
			$cdate = new Date(date('d-m-Y'));
			if($cdate->getDate()>=$tdate->getDate())
				$target = 100;

			$sdate = new Date($row['start_date']);
			if($cdate->getDate() < $sdate->getDate())
				$target = 0;

			$objQual = new stdClass();
			$objQual->id = $row['id'];
			$objQual->title = $row['internaltitle'];
			$objQual->achieved = round($row['unitsUnderAssessment']);
			$objQual->target = round($target);

			$stdQuals[] = $objQual;
		}

		if($this->_type == 'cs')
		{
			$cs_review = CSReview::loadFromDatabaseByTrainingId($link, $tr->id);
			if(is_null($cs_review))
			{
				$cs_review = new CSReview($tr->id);
				$cs_review->save($link);
			}

			$cs_observation = CSObservation::loadFromDatabaseByTrainingId($link, $tr->id);
			if(is_null($cs_observation))
			{
				$cs_observation = new CSObservation($tr->id);
				$cs_observation->save($link);
			}

			$wb_developing_self = Workbook::loadFromDatabaseByTrainingId($link, $tr->id, 'WBDevelopingSelf');
			if(is_null($wb_developing_self))
			{
				$wb_developing_self = new WBDevelopingSelf($tr->id);
			}
			$wb_developing_self_percentage_completed = $wb_developing_self->getCompletedPercentage();

			$wb_knowing_your_customers = Workbook::loadFromDatabaseByTrainingId($link, $tr->id, 'WBKnowingYourCustomers');
			if(is_null($wb_knowing_your_customers))
			{
				$wb_knowing_your_customers = new WBKnowingYourCustomers($tr->id);
			}
			$wb_knowing_your_customers_percentage_completed = $wb_knowing_your_customers->getCompletedPercentage();

			$wb_role_responsibility = Workbook::loadFromDatabaseByTrainingId($link, $tr->id, 'WBRoleResponsibility');
			if(is_null($wb_role_responsibility))
			{
				$wb_role_responsibility = new WBRoleResponsibility($tr->id);
			}
			$wb_role_responsibility_percentage_completed = $wb_role_responsibility->getCompletedPercentage();

			$wb_team_working = Workbook::loadFromDatabaseByTrainingId($link, $tr->id, 'WBTeamWorking');
			if(is_null($wb_team_working))
			{
				$wb_team_working = new WBTeamWorking($tr->id);
			}
			$wb_team_working_percentage_completed = $wb_team_working->getCompletedPercentage();

			$wb_systems_and_resources = Workbook::loadFromDatabaseByTrainingId($link, $tr->id, 'WBSystemsAndResources');
			if(is_null($wb_systems_and_resources))
			{
				$wb_systems_and_resources = new WBSystemsAndResources($tr->id);
			}
			$wb_systems_and_resources_percentage_completed = $wb_systems_and_resources->getCompletedPercentage();

			$wb_understanding_the_organisation = Workbook::loadFromDatabaseByTrainingId($link, $tr->id, 'WBUnderstandingTheOrganisation');
			if(is_null($wb_understanding_the_organisation))
			{
				$wb_understanding_the_organisation = new WBUnderstandingTheOrganisation($tr->id);
			}
			$wb_understanding_the_organisation_percentage_completed = $wb_understanding_the_organisation->getCompletedPercentage();

			$wb_customer_experience = Workbook::loadFromDatabaseByTrainingId($link, $tr->id, 'WBCustomerExperience');
			if(is_null($wb_customer_experience))
			{
				$wb_customer_experience = new WBCustomerExperience($tr->id);
			}
			$wb_customer_experience_percentage_completed = $wb_customer_experience->getCompletedPercentage();

			$wb_meeting_regulations_and_legislation = Workbook::loadFromDatabaseByTrainingId($link, $tr->id, 'WBMeetingRegulationsAndLegislation');
			if(is_null($wb_meeting_regulations_and_legislation))
			{
				$wb_meeting_regulations_and_legislation = new WBMeetingRegulationsAndLegislation($tr->id);
			}
			$wb_meeting_regulations_and_legislation_percentage_completed = $wb_meeting_regulations_and_legislation->getCompletedPercentage();

			$wb_product_and_service = Workbook::loadFromDatabaseByTrainingId($link, $tr->id, 'WBProductAndService');
			if(is_null($wb_product_and_service))
			{
				$wb_product_and_service = new WBProductAndService($tr->id);
			}
			$wb_product_and_service_percentage_completed = $wb_product_and_service->getCompletedPercentage();
		}
		if($this->_type == 'retail')
		{
			$rt_observation = RtObservation::loadFromDatabaseByTrainingId($link, $tr->id);
			if(is_null($rt_observation))
			{
				$rt_observation = new RtObservation($tr->id);
				$rt_observation->save($link);
			}

			$wb_environment = Workbook::loadFromDatabaseByTrainingId($link, $tr->id, 'WBEnvironment');
			if(is_null($wb_environment))
			{
				$wb_environment = new WBEnvironment($tr->id);
			}
			$wb_environment_percentage_completed = $wb_environment->getCompletedPercentage();

			$wb_financial = Workbook::loadFromDatabaseByTrainingId($link, $tr->id, 'WBFinancial');
			if(is_null($wb_financial))
			{
				$wb_financial = new WBFinancial($tr->id);
			}
			$wb_financial_percentage_completed = $wb_financial->getCompletedPercentage();

			$wb_hs_and_security = Workbook::loadFromDatabaseByTrainingId($link, $tr->id, 'WBHSAndSecurity');
			if(is_null($wb_hs_and_security))
			{
				$wb_hs_and_security = new WBHSAndSecurity($tr->id);
			}
			$wb_hs_and_security_percentage_completed = $wb_hs_and_security->getCompletedPercentage();

			$wb_personal_team_performance = Workbook::loadFromDatabaseByTrainingId($link, $tr->id, 'WBPersonalTeamPerformance');
			if(is_null($wb_personal_team_performance))
			{
				$wb_personal_team_performance = new WBPersonalTeamPerformance($tr->id);
			}
			$wb_personal_team_performance_percentage_completed = $wb_personal_team_performance->getCompletedPercentage();

			$wb_technical = Workbook::loadFromDatabaseByTrainingId($link, $tr->id, 'WBTechnical');
			if(is_null($wb_technical))
			{
				$wb_technical = new WBTechnical($tr->id);
			}
			$wb_technical_percentage_completed = $wb_technical->getCompletedPercentage();

			$wb_stock = Workbook::loadFromDatabaseByTrainingId($link, $tr->id, 'WBStock');
			if(is_null($wb_stock))
			{
				$wb_stock = new WBStock($tr->id);
			}
			$wb_stock_percentage_completed = $wb_stock->getCompletedPercentage();

			$wb_business_and_brand_reputation = Workbook::loadFromDatabaseByTrainingId($link, $tr->id, 'WBBusinessAndBrandReputation');
			if(is_null($wb_business_and_brand_reputation))
			{
				$wb_business_and_brand_reputation = new WBBusinessAndBrandReputation($tr->id);
			}
			$wb_business_and_brand_reputation_percentage_completed = $wb_business_and_brand_reputation->getCompletedPercentage();

			$wb_marketing = Workbook::loadFromDatabaseByTrainingId($link, $tr->id, 'WBMarketing');
			if(is_null($wb_marketing))
			{
				$wb_marketing = new WBMarketing($tr->id);
			}
			$wb_marketing_percentage_completed = $wb_marketing->getCompletedPercentage();

			$wb_sales_promotion_marchandising = Workbook::loadFromDatabaseByTrainingId($link, $tr->id, 'WBSalesPromotionMarchandising');
			if(is_null($wb_sales_promotion_marchandising))
			{
				$wb_sales_promotion_marchandising = new WBSalesPromotionMarchandising($tr->id);
			}
			$wb_sales_promotion_marchandising_percentage_completed = $wb_sales_promotion_marchandising->getCompletedPercentage();

			$wb_customer = Workbook::loadFromDatabaseByTrainingId($link, $tr->id, 'WBCustomer');
			if(is_null($wb_customer))
			{
				$wb_customer = new WBCustomer($tr->id);
			}
			$wb_customer_percentage_completed = $wb_customer->getCompletedPercentage();

			$wb_legal_and_governance = Workbook::loadFromDatabaseByTrainingId($link, $tr->id, 'WBLegalAndGovernance');
			if(is_null($wb_legal_and_governance))
			{
				$wb_legal_and_governance = new WBCustomer($tr->id);
			}
			$wb_legal_and_governance_percentage_completed = $wb_legal_and_governance->getCompletedPercentage();

			$wb_product_and_service = Workbook::loadFromDatabaseByTrainingId($link, $tr->id, 'WBRetailProductAndService');
			if(is_null($wb_product_and_service))
			{
				$wb_product_and_service = new WBRetailProductAndService($tr->id);
			}
			$wb_product_and_service_percentage_completed = $wb_product_and_service->getCompletedPercentage();
		}

		$wb_communication = Workbook::loadFromDatabaseByTrainingId($link, $tr->id, 'WBCommunication');
		if(is_null($wb_communication))
		{
			$wb_communication = new WBCommunication($tr->id);
		}
		$wb_communication_percentage_completed = $wb_communication->getCompletedPercentage();

		$user_id = DAO::getSingleValue($link, "SELECT users.id FROM users WHERE users.username = '{$tr->username}'");

		$learner_signature = DAO::getSingleValue($link, "SELECT signature FROM users WHERE users.username = '{$tr->username}'");

		/*$btn_retailer_add_new_review_status = 'disabled';
		$number_of_retailer_reviews = DAO::getObject($link, "SELECT SUM(IF(TRUE, 1, 1)) AS total, SUM(IF(retailer_reviews.`assessor_signature` IS NOT NULL AND retailer_reviews.`learner_signature` IS NOT NULL, 1, 0)) AS completed FROM retailer_reviews WHERE tr_id = '{$tr->id}'");
		if(($number_of_retailer_reviews->total == 0 || $number_of_retailer_reviews->total == $number_of_retailer_reviews->completed) && $number_of_retailer_reviews->total <= 6)
			$btn_retailer_add_new_review_status = '';*/

		$_SESSION['bc']->index = 0;
		$_SESSION['bc']->add($link, "do.php?_action=learner_home_page", "Home");

		$temp_notification_message = <<<HTML
<p>Due to essential server maintenance the Sunesis system will be unavailable from <strong>Friday 25th January</strong> at <strong>5pm</strong> until <strong>Monday 28th January</strong> at <strong>8am</strong>.  If you are using the system at 5pm on Friday, please log out or data may be lost.</p><p>Many thanks for you co-operation.</p><input type="checkbox" name="_t_n_msg" id="_t_n_msg" onclick="doNotShowTempNotification();" > Do not show the message again
HTML;

		require_once('tpl_learner_home_page.php');
	}

	private function renderVideoEvidencesPanel(PDO $link, TrainingRecord $tr, $type)
	{
		$qan = $type == 'retail' ? Workbook::RETAIL_QAN : Workbook::CS_QAN;
		$student_qualification = DAO::getObject($link, "SELECT * FROM student_qualifications WHERE tr_id = '{$tr->id}' AND REPLACE(id, '/', '') = '{$qan}'");
		if(!isset($student_qualification->id))
			return 'No qualification found.';

		$evidence = XML::loadSimpleXML($student_qualification->evidences);

		$units = $evidence->xpath('//unit');

		$html = '<table class="table table-bordered"> ';
		$html .= '<thead><tr><th>Unit Ref.</th><th>Unit Title</th><th>Video Evidences</th><th>Action</th></tr></thead>';
		foreach ($units AS $unit)
		{
			$temp = (array)$unit->attributes();
			$temp = $temp['@attributes'];
			$html .= '<tr>';
			$html .= '<td>' . $temp['owner_reference'] . '</td>';
			$html .= '<td class="small">' . $temp['title'] . '</td>';
			$html .= '<td>';
			$videos = DAO::getResultset($link, "SELECT * FROM video_files WHERE tr_id = '{$tr->id}' AND qan = '{$qan}' AND unit_ref = '{$temp['owner_reference']}'", DAO::FETCH_ASSOC);
			foreach($videos AS $video)
			{
				$class = 'btn-info';
				if($video['status'] == 1)
					$class = 'btn-success';
				elseif($video['status'] == 2)
					$class = 'btn-danger';
				$html .= '<span class="btn btn-xs '.$class.'" onclick="window.open(\'http://sunesis/do.php?_action=play_video_file&video_file_id='.$video['id'].'&username='.$tr->username.'\', \'_blank\');">';
				$html .= '<i class="fa fa-file-video-o"></i> </span> ';
			}
			$html .= '</td>';
			$html .= '<td><span class="btn btn-xs btn-primary" onclick="window.location.href=\'do.php?_action=upload_video_evidence&tr_id='.$tr->id.'&unit_ref='.$temp['owner_reference'].'&qan='.$qan.'&title='.$temp['title'].'\'"><i class="fa fa-cloud-upload"></i> </span> </td>';
			$html .= '</tr>';
		}
		$html .= '</table>';
		return $html;
	}

	private function renderReviews(PDO $link, TrainingRecord $tr)
	{
		$reviews = DAO::getResultset($link, "SELECT * FROM reviews_forms INNER JOIN assessor_review ON reviews_forms.review_id = assessor_review.`id` WHERE assessor_review.`tr_id` = '{$tr->id}' AND a_sign IS NOT NULL ORDER BY assessor_review.`meeting_date` DESC LIMIT 1;", DAO::FETCH_ASSOC);
		echo '<table class="table table-bordered">';
		echo '<tr><th>Due on</th><th>Held on</th><th>Form</th></tr>';
		foreach($reviews AS $review)
		{
			echo '<tr>';
			echo '<td>' . Date::toShort($review['due_date']) . '</td>';
			echo '<td>' . Date::toShort($review['meeting_date']) . '</td>';
			if(is_null($review['a_sign']))
			{
				echo "<td style='text-align: center'><a href='#' onclick='alert(\"Form has not yet completed by your assessor, contact your assessor.\");'><i class='fa fa-file-text-o'></i> </a></td>";
			}
			elseif(!is_null($review['a_sign']) && is_null($review['l_sign']))
			{
				echo "<td style='text-align: center'><a href='do.php?_action=sd_form&tr_id=$tr->id&review_id={$review['id']}'><i class='fa fa-file-text-o fa-2x'></i> </a></td>";
			}
			elseif(!is_null($review['a_sign']) && !is_null($review['l_sign']) && is_null($review['m_sign']))
			{
				echo "<td style='text-align: center'><a href='do.php?_action=sd_form&tr_id=$tr->id&review_id={$review['id']}'><i class='fa fa-file-text-o fa-2x'></i> </a></td>";
			}
			elseif(!is_null($review['a_sign']) && !is_null($review['l_sign']) && !is_null($review['m_sign']))
			{
				echo "<td style='text-align: center'><a href='do.php?_action=sd_form&tr_id=$tr->id&review_id={$review['id']}'><i class='fa fa-file-text-o fa-2x'></i> </a></td>";
			}
			echo '</tr>';
		}
		echo '</table>';

	}
}
?>