<?php
class learner_home_page_all implements IAction
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

		$notifResult = new stdClass();
        $notifResult->unread_notifications = 0;
        $notifResult->total_notifications = 0;
        $notifResult->notifications = array();
        $notifications = array();
        $results = DAO::getResultset($link, "SELECT * FROM user_notifications WHERE `user_id` = '{$_SESSION['user']->id}' ORDER BY created DESC ", DAO::FETCH_ASSOC);
        foreach($results AS $row)
        {
            $notifResult->unread_notifications += $row['checked'] == 0 ? 1 : 0;

            $item = '';
            $item .= $row['checked'] == 0 ? '<li class="bg-gray">' : '<li>';
            $item .= '<a class="clsNotificationsMenuItem" id="' . $row['id'] . '" href="' . $row['link'] . '">' . $row['detail'] . '<br><span class="fa fa-clock-o"></span> ' . Date::to($row['created'], Date::DATETIME) . '</a>';
            $item .= '</li>';
            $notifications[] = $item;
        }
        $notifResult->total_notifications = count($notifications);
        $notifResult->notifications = $notifications;

		$assessor = !is_null($tr->assessor) && $tr->assessor != 0?User::loadFromDatabaseById($link, $tr->assessor):new User();
		// Assessor photo
		$assessor_photopath = "/images/no_photo.png";
		if(!is_null($assessor))
		{
			if($assessor->getPhotoPath() != '')
			{
				$assessor_photopath = "do.php?_action=display_image&username=".rawurlencode($assessor->username);
			}
		}
		$tutor = !is_null($tr->tutor) && $tr->tutor != 0?User::loadFromDatabaseById($link, $tr->tutor):new User();
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

		$user_id = DAO::getSingleValue($link, "SELECT users.id FROM users WHERE users.username = '{$tr->username}'");

		$learner_signature = DAO::getSingleValue($link, "SELECT signature FROM users WHERE users.username = '{$tr->username}'");

		$_SESSION['bc']->index = 0;
		$_SESSION['bc']->add($link, "do.php?_action=learner_home_page_all", "Home");

		$temp_notification_message = <<<HTML
<p>Due to essential server maintenance the Sunesis system will be unavailable from <strong>Friday 25th January</strong> at <strong>5pm</strong> until <strong>Monday 28th January</strong> at <strong>8am</strong>.  If you are using the system at 5pm on Friday, please log out or data may be lost.</p><p>Many thanks for you co-operation.</p><input type="checkbox" name="_t_n_msg" id="_t_n_msg" onclick="doNotShowTempNotification();" > Do not show the message again
HTML;

		require_once('tpl_learner_home_page_all.php');
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
		$reviews = DAO::getResultset($link, "SELECT * FROM assessor_review WHERE assessor_review.`tr_id` = '{$tr->id}' ORDER BY assessor_review.`meeting_date` DESC LIMIT 1;", DAO::FETCH_ASSOC);
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