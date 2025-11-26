<?php
class sd_form implements IAction
{
    public function execute(PDO $link)
    {
		$review_id = isset($_REQUEST['review_id']) ? $_REQUEST['review_id'] : '';
		$tr_id = isset($_REQUEST['tr_id']) ? $_REQUEST['tr_id'] : '';

	    if($review_id == '' || $tr_id == '')
		    throw new Exception('Missing querystring argument(s)');

	    $review = DAO::getObject($link, "SELECT * FROM assessor_review WHERE id = '{$_REQUEST['review_id']}'");
	    if(!isset($review->id))
		    throw new Exception('Invalid review_id');

	    $tr = TrainingRecord::loadFromDatabase($link, $_REQUEST['tr_id']);
	    if(is_null($tr))
		    throw new Exception('Invalid tr_id');

		if(!isset($_REQUEST['subaction']))
	    	$_SESSION['bc']->add($link, "do.php?_action=sd_form&review_id={$review_id}&tr_id={$tr_id}", "Learner Review Form");

	    $review_form = DAO::getObject($link, "SELECT * FROM reviews_forms WHERE review_id = '{$review->id}'");
	    if(!isset($review_form->review_id))
	    {
		    $review_form = SDReviewFormHelper::getFullReviewFormVO();
	    }

	    $employer = Organisation::loadFromDatabase($link, $tr->employer_id);

	    $superdrug = true;
	    $savers = true;
	    if ((preg_match('/\Savers\b/', $employer->legal_name)) || $employer->manufacturer == 1)
	    {
		    $superdrug = false;
	    }
	    else
	    {
		    $savers = false;
	    }

	    $qualification = DAO::getSingleValue($link, "SELECT COUNT(*) FROM student_qualifications WHERE tr_id = '{$tr->id}' AND REPLACE(id, '/', '') = '" . Workbook::RETAIL_QAN . "'");
	    $qualification = $qualification > 0 ? Workbook::RETAIL_QAN : Workbook::CS_QAN;
	    $qualification = DAO::getObject($link, "SELECT * FROM student_qualifications WHERE tr_id = '{$tr->id}' AND REPLACE(id, '/', '') = '{$qualification}'");

	    $framework = Framework::loadFromDatabase($link, DAO::getSingleValue($link, "SELECT id FROM student_frameworks WHERE tr_id = '{$tr->id}'"));
	    $is_workbook_learner = true;
	    if(!in_array($framework->id, [49, 50]))
		    $is_workbook_learner = false;
	    if(in_array($tr->id, [5815, 5858]))
		    $is_workbook_learner = false;
	    if(!$is_workbook_learner)
		{
		    $_main_aim_id = DAO::getSingleValue($link, "SELECT REPLACE(id, '/', '') FROM framework_qualifications WHERE framework_id = '{$framework->id}' AND main_aim = '1'");
		    $qualification = DAO::getObject($link, "SELECT * FROM student_qualifications WHERE tr_id = '{$tr->id}' AND REPLACE(id, '/', '') = '{$_main_aim_id}'");
		    if(!isset($qualification->id))
			    $qualification = DAO::getObject($link, "SELECT * FROM framework_qualifications WHERE framework_id = '{$framework->id}' AND main_aim = '1'");
	    }

	    $disable_save = false;
	    if( // form is fully completed and signed
		    !is_null($review_form->a_sign) &&
		    !is_null($review_form->l_sign) &&
		    !is_null($review_form->m_sign)
	    )
		    $disable_save = true;

	    $l_sign_img = $review_form->l_sign != '' ?
		    $review_form->l_sign :
		    DAO::getSingleValue($link, "SELECT users.signature FROM users WHERE users.username = '{$tr->username}'");
	    $a_sign_img = $review_form->a_sign != '' ?
		    $review_form->a_sign :
		    DAO::getSingleValue($link, "SELECT users.signature FROM users WHERE users.id = '{$tr->assessor}'");
	
		if(isset($_REQUEST['subaction']) && $_REQUEST['subaction'] == 'export')
	    {
		    $details = [
		        'employer' => $employer,
				'superdrug' => $superdrug,
			    'savers' => $savers,
			    'qualification' => $qualification,
			    'framework' => $framework,
			    'is_workbook_learner' => $is_workbook_learner,
		    ];
		    $this->export_to_pdf($link, $review_id, $tr_id, $details);
		    exit;
	    }

		

	    include_once('tpl_sd_form.php');
    }

	public function getManagerChecklist()
	{
		return
			array(
				'm_punc' => 'Punctual',
				'm_well_pres' => 'Well presented',
				'm_respectful' => 'Respectful to others',
				'm_team_player' => 'A team player',
				'm_show_pd' => 'Showing personal development',
				'm_demo_skills' => 'Demonstrating new skills',
				'm_show_conf' => 'Showing an increase in confidence',
				'm_attendance' => 'Good attendance record',
				'm_creative' => 'Creativity and imagination'
			);
	}

	public function RTWorkbooksShortNames($wb_title = '')
	{
		$list = [
			"WBHSAndSecurity" => "Unit 01"
			,"WBCustomer" => "Unit 02"
			,"WBRetailProductAndService" => "Unit 06"
			,"WBCommunication" => "Unit 03"
			,"WBTechnical" => "Unit 04"
			,"WBPersonalTeamPerformance" => "Unit 05"
			,"WBStock" => "Unit 09"
			,"WBFinancial" => "Unit 12"
			,"WBEnvironment" => "Unit 13"
			,"WBSalesPromotionMarchandising" => "Unit 10"
			,"WBBusinessAndBrandReputation" => "Unit 07"
			,"WBMarketing" => "Unit 08"
			,"WBLegalAndGovernance" => "Unit 11"
		];
		if($wb_title == '')
			return $list;
		return isset($list[$wb_title]) ? $list[$wb_title] : $wb_title;
	}

	public function CSWorkbooksShortNames($wb_title = '')
	{
		$list = [
			"WBDevelopingSelf" => "Unit 02"
			,"WBCustomerExperience" => "Unit 03"
			,"WBKnowingYourCustomers" => "Unit 04"
			,"WBRoleResponsibility" => "Unit 05"
			,"WBTeamWorking" => "Unit 06"
			,"WBCommunication" => "Unit 07"
			,"WBSystemsAndResources" => "Unit 08"
			,"WBUnderstandingTheOrganisation" => "Unit 09"
			,"WBMeetingRegulationsAndLegislation" => "Unit 11"
			,"WBProductAndService" => "Unit 10"
		];
		if($wb_title == '')
			return $list;
		return isset($list[$wb_title]) ? $list[$wb_title] : $wb_title;
	}

	public function renderWorkbookProgress(PDO $link, $qualification, TrainingRecord $tr)
	{
		echo '<caption style="display: table-caption;text-align: center" class="text-bold">Workbooks</caption>';
		echo '<tbody>';
		$_list = '';
		if($qualification->id == Workbook::CS_QAN)
			$_list = $this->CSWorkbooksShortNames();
		elseif($qualification->id == Workbook::RETAIL_QAN)
			$_list = $this->RTWorkbooksShortNames();
		echo '<tr>';
		foreach($_list AS $key => $value)
		{
			echo '<th title="' . $key . '">' . $value . '</th>';
		}
		echo '</tr>';

		echo '<tr>';
		foreach($_list AS $key => $value)
		{
			$wb_id = DAO::getSingleValue($link, "SELECT id FROM workbooks WHERE tr_id = '{$tr->id}' AND wb_title = '{$key}'");
			if($wb_id == '')
				echo '<td></td>';
			else
			{
				$wb = Workbook::loadFromDatabase($link, $wb_id);
				$total = 0;
				$completed = 0;
				if($wb->wb_content != '')
				{
					$xml = $wb->wb_content;
					foreach($xml->Feedback AS $section => $content)
					{
						$c = (array)$content;
						$total = count($c);
						foreach($c as $key => $value)
						{
							if(isset($value->Status) && $value->Status->__toString() == 'A')
								$completed++;
						}
					}
				}
				echo '<td align="center" title="' . Workbook::getWBStatusTitle($wb->wb_status) . '">' . round(($completed/$total)*100) . '%</td>';
			}
		}
		echo '</tr>';
		echo '<tr>';
		foreach($_list AS $key => $value)
		{
			$wb_id = DAO::getSingleValue($link, "SELECT id FROM workbooks WHERE tr_id = '{$tr->id}' AND wb_title = '{$key}'");
			if($wb_id == '')
				echo '<td></td>';
			else
			{
				$wb = Workbook::loadFromDatabase($link, $wb_id);
				echo '<td align="center" title="' . Workbook::getWBStatusTitle($wb->wb_status) . '"><i class="' . Workbook::getWBStatusIcon($wb->wb_status) . '"></i></td>';
			}
		}
		echo '</tr>';
		echo '</tbody>';
	}

	public function renderNonWorkbookProgress(PDO $link, $qualification, TrainingRecord $tr)
	{
		//if(!isset($qualification)) return;
		echo '<caption style="display: table-caption;text-align: center" class="text-bold">Qualification Units</caption>';
		echo '<tbody>';
		$evidence = XML::loadSimpleXML($qualification->evidences);
		$units = $evidence->xpath('//unit[@chosen="true"]');
		echo '<tr>';
		$unit_owner_references = [];
		foreach($units AS $unit)
		{
			$attributes = (array)$unit->attributes();
			$attributes = $attributes['@attributes'];
			if(!isset($attributes['owner_reference'])) continue;
			if($qualification->id == '60141074')
				echo '<th title="' . $attributes['owner_reference'] . '">' . $attributes['owner_reference'] . '</th>';
			else
				echo '<th title="' . $attributes['owner_reference'] . '">' . $attributes['reference'] . '</th>';
			$unit_owner_references[] = $attributes['owner_reference'];
		}
		echo '</tr>';
		$student_evidences = DAO::getSingleValue($link, "SELECT evidences FROM student_qualifications WHERE tr_id = '{$tr->id}' AND REPLACE(id, '/', '') = '{$qualification->id}'");
		if($student_evidences != '')
		{
			$student_evidences = XML::loadSimpleXML($student_evidences);
			echo '<tr>';
			foreach($unit_owner_references AS $owner_reference)
			{
				$progress = $student_evidences->xpath('//unit[@owner_reference="'.$owner_reference.'"]/@percentage');
				if(!isset($progress[0]))
				{
					echo '<td></td>';
				}
				else
				{
					$progress = $progress[0];
					echo '<td>' . $progress->percentage->__toString() . '%</td>';
				}
			}
			echo '</tr>';
		}

		echo '</tbody>';
	}

	public function export_to_pdf(PDO $link, $review_id, $tr_id, $details = [])
	{
		$review_form = DAO::getObject($link, "SELECT * FROM reviews_forms WHERE review_id = '{$review_id}'");
		if(!isset($review_form->review_id))
		{
			$review_form = SDReviewFormHelper::getFullReviewFormVO();
		}
		$tr = TrainingRecord::loadFromDatabase($link, $tr_id);

		include_once("./MPDF57/mpdf.php");

		$mpdf=new mPDF('','Legal','10');

		$mpdf->setAutoBottomMargin = 'stretch';

		$sunesis_stamp = md5('ghost'.date('d/m/Y').$tr->id);
		$sunesis_stamp = substr($sunesis_stamp, 0, 10);
		$date = date('d/m/Y H:i:s');
		$footer = <<<HEREDOC
		<div>
			<table width = "100%" style="border-radius: 10px; border: 1px solid #000000;">
				<tr>
					<td width = "50%" align="left">{$date}</td>
					<td width = "50%" align="right">Page {PAGENO} of {nb}<br>Print ID: $sunesis_stamp</td>
				</tr>
			</table>
		</div>
HEREDOC;

		//Beginning Buffer to save PHP variables and HTML tags
		ob_start();

		$review_form->overall_progression = $review_form->overall_progression == '' ? sprintf("%.2f", $tr->l36) : $review_form->overall_progression;
		$review_form->a_next_visit = Date::toShort($review_form->a_next_visit);

		$superdrug = $details['superdrug'] ? 'background-color: #90ee90;' : '';
		$savers = $details['savers'] ? 'background-color: #90ee90;' : '';
		$qual_or_fwk = isset($details['qualification']->id) ? $details['qualification']->id . ' ' . $details['qualification']->title : $details['framework']->title;
		$qual_or_tr_sd = isset($details['qualification']->start_date) ? Date::toShort($details['qualification']->start_date) : Date::toShort($tr->start_date);
		$assessor_name = DAO::getSingleValue($link, "SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE users.id = '{$tr->assessor}'");
		$review_form->a_visit_date = Date::toShort($review_form->a_visit_date);
		$qual_or_tr_ed = isset($details['qualification']->end_date) ? Date::toShort($details['qualification']->end_date) : Date::toShort($tr->target_date);
		$review_form->a_record_of_comm = nl2br((string) $review_form->a_record_of_comm);
		$review_form->a_qual_last_visit = nl2br((string) $review_form->a_qual_last_visit);
		$review_form->a_fs_last_visit = nl2br((string) $review_form->a_fs_last_visit);
		$review_form->a_qual_today = nl2br((string) $review_form->a_qual_today);
		$review_form->a_fs_today = nl2br((string) $review_form->a_fs_today);
		$review_form->a_qual_next_visit = nl2br((string) $review_form->a_qual_next_visit);
		$review_form->a_fs_next_visit = nl2br((string) $review_form->a_fs_next_visit);
		$review_form->a_qual_act_next_visit = nl2br((string) $review_form->a_qual_act_next_visit);
		$review_form->a_fs_act_next_visit = nl2br((string) $review_form->a_fs_act_next_visit);
		$review_form->a_booklet_abu = nl2br((string) $review_form->a_booklet_abu);
		$review_form->a_booklet_end = nl2br((string) $review_form->a_booklet_end);
		$review_form->a_booklet_hns = nl2br((string) $review_form->a_booklet_hns);
		$review_form->a_booklet_sm = nl2br((string) $review_form->a_booklet_sm);
		$review_form->a_fs_maths_ict_target_date = Date::toShort($review_form->a_fs_maths_ict_target_date);
		$review_form->a_fs_eng_target_date = Date::toShort($review_form->a_fs_eng_target_date);
		$review_form->a_fdbck_sgp = nl2br((string) $review_form->a_fdbck_sgp);
		$review_form->l_equ_div = nl2br((string) $review_form->l_equ_div);
		$review_form->l_training = nl2br((string) $review_form->l_training);
		$review_form->l_future_asp = nl2br((string) $review_form->l_future_asp);
		$review_form->l_support = nl2br((string) $review_form->l_support);
		$review_form->l_feel_safe = $review_form->l_feel_safe == 'Y' ? 'Yes' : 'No';
		$review_form->l_had_acc = $review_form->l_had_acc == 'Y' ? 'Yes' : 'No';
		$review_form->l_hav_changes = $review_form->l_hav_changes == 'Y' ? 'Yes' : 'No';
		$review_form->l_have_health_issues = $review_form->l_have_health_issues == 'Y' ? 'Yes' : 'No';
		$review_form->m_feedback = nl2br((string) $review_form->m_feedback);
		$review_form->a_feedback = nl2br((string) $review_form->a_feedback);
		$review_form->l_feedback = nl2br((string) $review_form->l_feedback);
		$review_form->a_chngs_to_notify = nl2br((string) $review_form->a_chngs_to_notify);
		$review_form->a_tests_sat = nl2br((string) $review_form->a_tests_sat);
		$url_prefix = 'http://sunesis/';
		if(DB_NAME == 'am_superdrug')
			$url_prefix = 'https://superdrug.sunesis.uk.net/';
		if(DB_NAME == 'am_sd_demo')
			$url_prefix = 'https://sd-demo.sunesis.uk.net/';
		$learner_sign = '';
		if($review_form->l_sign != '')
		{
			$signature_parts = explode('&', $review_form->l_sign);
			$title = explode('=', $signature_parts[0]);
			$font = explode('=', $signature_parts[1]);
			$size = explode('=', $signature_parts[2]);
			$signature1 = Signature::getTextImage(urldecode($title[1]), urldecode($font[1]), $size[1]);
			$learner_sign = tempnam(sys_get_temp_dir(), 'TMP_');
			imagepng($signature1, $learner_sign, 0, NULL);
			$learner_sign = '<img id="img_a_sign" src="'.$learner_sign.'" style="border: 2px solid;border-radius: 15px;" />';
		}
		$review_form->l_sign_date = Date::toShort($review_form->l_sign_date);

		$assessor_sign = '';
		if($review_form->a_sign != '')
		{
			$signature_parts = explode('&', $review_form->a_sign);
			$title = explode('=', $signature_parts[0]);
			$font = explode('=', $signature_parts[1]);
			$size = explode('=', $signature_parts[2]);
			$signature2 = Signature::getTextImage(urldecode($title[1]), urldecode($font[1]), $size[1]);
			$assessor_sign = tempnam(sys_get_temp_dir(), 'TMP_');
			imagepng($signature2, $assessor_sign, 0, NULL);
			$assessor_sign = '<img id="img_a_sign" src="'.$assessor_sign.'" style="border: 2px solid;border-radius: 15px;" />';
		}
		$review_form->a_sign_date = Date::toShort($review_form->a_sign_date);
		$manager_sign = '';
		if($review_form->m_sign != '')
		{
			$signature_parts = explode('&', $review_form->m_sign);
			$title = explode('=', $signature_parts[0]);
			$font = explode('=', $signature_parts[1]);
			$size = explode('=', $signature_parts[2]);
			$signature3 = Signature::getTextImage(urldecode($title[1]), urldecode($font[1]), $size[1]);
			$manager_sign = tempnam(sys_get_temp_dir(), 'TMP_');
			imagepng($signature3, $manager_sign, 0, NULL);
			$manager_sign = '<img id="img_a_sign" src="'.$manager_sign.'" style="border: 2px solid;border-radius: 15px;" />';
		}
		$review_form->m_sign_date = Date::toShort($review_form->m_sign_date);

		$employer = Organisation::loadFromDatabase($link, $tr->employer_id);

		echo <<<HTML
		<table style="width: 100%;">
		<caption><h3>Monthly Visit and Review Record</h3></caption>
			<tr>
				<td style="width: 40%;"><img src="images/logos/ASWatson.png" alt=""></td>
				<td style="width: 40%;" align="center">
					<h3>Overall Progression</h3>
					<h5 style="font-size: 35px;">{$review_form->overall_progression}%</h5>
				</td>
				<td style="width: 20%;">
					<h3>Next Review</h3>
					{$review_form->a_next_visit}<br>{$review_form->a_next_visit_time}
				</td>
			</tr>
		</table>

		<table style="width: 100%;" border="1">
			<tr>
				<td style="width: 33%;"><strong>Visit Details</strong></td>
				<td style="width: 33%; {$superdrug}"><strong>Superdrug</strong></td>
				<td style="width: 33%; {$savers}"><strong>Savers</strong></td>
			</tr>
		</table>
		<hr>
		<table style="width: 100%;">
			<tr>
				<td style="width: 50%;"><strong>Learner Name: </strong>{$tr->firstnames} {$tr->surname}</td>
				<td style="width: 25%;"><strong>Assessor Name: </strong>{$assessor_name}</td>
				<td style="width: 25%;"><strong>Location: </strong>{$employer->legal_name}</td>
			</tr>
			<tr>
				<td style="width: 33%;"><strong>Qualification & Level: </strong>{$qual_or_fwk}</td>
				<td style="width: 33%;"><strong>Today's Date: </strong>{$review_form->a_visit_date}</td>
				<td style="width: 33%;"><strong>Start Time/ End Time: </strong>{$review_form->a_visit_start} / {$review_form->a_visit_end}</td>
			</tr>
			<tr>
				<td style="width: 33%;"><strong>Qualification Start Date: </strong>{$qual_or_tr_sd}</td>
				<td style="width: 33%;"><strong>Expected End Date: </strong>{$qual_or_tr_ed}</td>
				<td style="width: 33%;"><strong>Contract End Date: </strong></td>
			</tr>
		</table>
		<hr>
		<table style="width: 100%;">
			<tr style="background-color: #90ee90;">
				<td><strong>Record of any communications between visits (e.g. calls, emails or text support)</strong></td>
			</tr>
			<tr>
				<td>{$review_form->a_record_of_comm}</td>
			</tr>
		</table>
		<hr>
		<table style="width: 100%;">
			<tr style="background-color: #90ee90;">
				<td colspan="2"><strong>Review of work to be completed since last visit</strong></td>
			</tr>
			<tr>
				<td><strong>Standards/ Workbooks/ E&D/ Other</strong></td>
				<td><strong>Functional Skills (if applicable)</strong></td>
			</tr>
			<tr>
				<td>{$review_form->a_qual_last_visit}</td>
				<td>{$review_form->a_fs_last_visit}</td>
			</tr>
		</table>
		<hr>
		<table style="width: 100%;">
			<tr style="background-color: #90ee90;">
				<td><strong>Record of assessment activities (as planned on last visit) undertaken today</strong></td>
			</tr>
			<tr>
				<td><strong>Standards/ Workbooks/ E&D/ Other</strong></td>
			</tr>
			<tr>
				<td>{$review_form->a_qual_today}</td>
			</tr>
			<tr>
				<td><strong>Functional Skills</strong></td>
			</tr>
			<tr>
				<td>{$review_form->a_fs_today}</td>
			</tr>
		</table>
		<hr>
		<table style="width: 100%;">
			<tr style="background-color: #90ee90;">
				<td colspan="2"><strong>Work for YOU to complete by next visit</strong></td>
			</tr>
			<tr>
				<td><strong>Standards/ Workbooks/ E&D/ Other</strong></td>
				<td><strong>Functional Skills (if applicable)</strong></td>
			</tr>
			<tr>
				<td>{$review_form->a_qual_next_visit}</td>
				<td>{$review_form->a_fs_next_visit}</td>
			</tr>
		</table>
		<hr>
		<table style="width: 100%;">
			<tr style="background-color: #90ee90;">
				<td colspan="2"><strong>Activities to take place at next visit</strong></td>
			</tr>
			<tr>
				<td><strong>Standards/ Workbooks/ E&D/ Other</strong></td>
				<td><strong>Functional Skills (if applicable)</strong></td>
			</tr>
			<tr>
				<td>{$review_form->a_qual_act_next_visit}</td>
				<td>{$review_form->a_fs_act_next_visit}</td>
			</tr>
		</table>
		<hr>
		<table style="width: 100%;">
			<caption>
				<h3 style="background-color: #90ee90;">% Progression Review</h3>
			</caption>
HTML;
		if($details['is_workbook_learner'])
		{
			$this->renderWorkbookProgress($link, $details['qualification'], $tr);
		}
		else
		{
			$this->renderNonWorkbookProgress($link, $details['qualification'], $tr);
		}
		echo <<<HTML
		</table>

		<hr>
		<table border="1" style="width: 100%;">
			<caption style="background-color: #90ee90;"><h4>Booklets</h4></caption>
			<thead><tr><th>All About You</th><th>E&D</th></tr></thead>
			<tbody>
			<tr>
				<td>{$review_form->a_booklet_abu}</td>
				<td>{$review_form->a_booklet_end}</td>
			</tr>
			<tr><th>Health & Safety</th><th>Substance Misuse</th></tr>
			<tr>
				<td>{$review_form->a_booklet_hns}</td>
				<td>{$review_form->a_booklet_sm}</td>
			</tr>
			</tbody>
		</table>
		<hr>
		<table border="1" style="width: 100%;">
			<tbody>
			<tr style="background-color: #90ee90;"><th colspan="4"><h4>Maths / ICT F.Skills Target Date: {$review_form->a_fs_maths_ict_target_date}</h4></th></tr>
			<tr><th>Maths</th><th>Level</th><th>ICT</th><th>Level</th></tr>
			<tr>
				<td align="center">{$review_form->a_maths_level1}</td><td align="center">1</td>
				<td align="center">{$review_form->a_ict_level1}</td><td align="center">1</td>
			</tr>
			<tr>
				<td align="center">{$review_form->a_maths_level2}</td><td align="center">2</td>
				<td align="center">{$review_form->a_ict_level2}</td><td align="center">2</td>
			</tr>
			<tr style="background-color: #90ee90;"><th colspan="4"><h4>English F.Skills Target Date: {$review_form->a_fs_eng_target_date}</h4></th></tr>
			<tr><th>Reading</th><th>Writing</th><th>Speaking Listening</th><th>Level</th></tr>
			<tr>
				<td align="center">{$review_form->a_eng_read_level1}</td>
				<td align="center">{$review_form->a_eng_write_level1}</td>
				<td align="center">{$review_form->a_eng_speak_listen_level1}</td>
				<td align="center">1</td>
			</tr>
			<tr>
				<td align="center">{$review_form->a_eng_read_level2}</td>
				<td align="center">{$review_form->a_eng_write_level2}</td>
				<td align="center">{$review_form->a_eng_speak_listen_level2}</td>
				<td align="center">2</td>
			</tr>
			</tbody>
		</table>
		<hr>
		<table style="width: 100%;">
			<tr style="background-color: #90ee90;">
				<td style="width: 40%;"><strong>Feedback on spellings, grammar and punctuation</strong></td>
				<td style="width: 60%;"><strong>Equality and Diversity - what have you learnt and discussed since last visit?</strong></td>
			</tr>
			<tr>
				<td>{$review_form->a_fdbck_sgp}</td>
				<td>{$review_form->l_equ_div}</td>
			</tr>
		</table>
		<hr>
		<table style="width: 100%;">
			<tr style="background-color: #90ee90;">
				<td style="width: 60%;"><strong>What training, coaching or learning have you been involved in since last visit?</strong></td>
				<td style="width: 40%;"><strong>What are your future aspirations (Short/ long term)</strong></td>
			</tr>
			<tr>
				<td>{$review_form->l_training}</td>
				<td>{$review_form->l_future_asp}</td>
			</tr>
		</table>
		<hr>
		<table style="width: 100%;">
			<tr style="background-color: #90ee90;">
				<td><strong>What support are you receiving for your qualification?</strong></td>
			</tr>
			<tr>
				<td>{$review_form->l_support}</td>
			</tr>
		</table>
		<hr>
		<table border="1" style="width: 100%;">
		<tr>
			<th style="width: 40%;">Do you feel safe at work?</th>
			<td align="center" style="width: 10%;">{$review_form->l_feel_safe}</td>
			<th style="width: 40%;">Have you had any accidents or incidents at work since our last meeting?</th>
			<td align="center" style="width: 10%;">{$review_form->l_had_acc}</td>
		</tr>
		<tr>
			<th style="width: 40%;">Have there been any changes since our last meeting?<br>
			<span style="font-size: smaller;">e.g. places of work, duties, duty manager, change of address/ name</span></th>
			<td align="center" style="width: 10%;">{$review_form->l_hav_changes}</td>
			<th style="width: 40%;">Do you have any health issues or other things which may affect your assessments?</th>
			<td align="center" style="width: 10%;">{$review_form->l_have_health_issues}</td>
		</tr>
		</table>
		<hr>
		<table border="1" style="width: 100%;">
		<caption style="background-color: #90ee90;"><h3>Duty Manager checklist</h3></caption>
		<tr>
			<th align="center" style="width: 50%;">Is your apprentice</th>
			<td align="center" style="width: 50%;">Yes / No</td>
		</tr>
HTML;
		$i = 0;
		foreach($this->getManagerChecklist() AS $key => $value)
		{
			$i++;
			echo '<tr>';
			echo '<th align="right">' . $value . '</th>';
			echo '<td align="center">';
			echo $review_form->$key == 'Y' ? 'Yes' : 'No';
			echo '</td>';
			echo '</tr>';
			if($i == 7)
			{
				echo '<tr><th align="center" style="width: 50%;">Do they have</th>';
				echo '<td align="center" style="width: 50%;">Yes / No</td></tr>';
			}
		}
		echo <<<HTML
		</table>
		<hr>
		<table style="width: 100%;">
			<tr style="background-color: #90ee90;">
				<th>Duty Manager Feedback</th>
			</tr>
			<tr>
				<td>{$review_form->m_feedback}</td>
			</tr>
		</table>
		<hr>
		<table style="width: 100%;">
			<tr style="background-color: #90ee90;">
				<th>Assessor Feedback</th>
			</tr>
			<tr>
				<td>{$review_form->a_feedback}</td>
			</tr>
		</table>
		<hr>
		<table style="width: 100%;">
			<tr style="background-color: #90ee90;">
				<th>Learner Feedback</th>
			</tr>
			<tr>
				<td>{$review_form->l_feedback}</td>
			</tr>
		</table>
		<hr>
		<table style="width: 100%;">
			<tr>
				<th style="width: 50%;background-color: #90ee90;">Changes to be notified to Admin Team. <span style="font-size: smaller;">e.g. put on break, back from break, address, job role</span></th>
				<th style="width: 50%;background-color: #90ee90;">Tests sat today or reason for non-completion</th>
			</tr>
			<tr>
				<td>{$review_form->a_chngs_to_notify}</td>
				<td>{$review_form->a_tests_sat}</td>
			</tr>
		</table>
		<hr>
		<table style="width: 100%;">
		<caption style="background-color: #90ee90;"><h3>Signatures</h3></caption>
			<tr>
				<td style="width: 10%;">Learner</td>
				<td style="width: 30%;">{$tr->firstnames} {$tr->surname}</td>
				<td style="width: 40%;">{$learner_sign}</td>
				<td style="width: 10%;">{$review_form->l_sign_date}</td>
			</tr>
			<tr>
				<td style="width: 10%;">Assessor</td>
				<td style="width: 30%;">$assessor_name</td>
				<td style="width: 40%;">{$assessor_sign}</td>
				<td style="width: 10%;">{$review_form->a_sign_date}</td>
			</tr>
			<tr>
				<td style="width: 10%;">Manager</td>
				<td style="width: 30%;">{$review_form->m_name}</td>
				<td style="width: 40%;">{$manager_sign}</td>
				<td style="width: 10%;">{$review_form->m_sign_date}</td>
			</tr>
		</table>
HTML;

		$html = ob_get_contents();

		$mpdf->SetHTMLFooter($footer);
		ob_end_clean();

		$mpdf->WriteHTML($html);

//		$mpdf->Output('asd', 'I');
		$mpdf->Output('Online MVR.pdf', 'D');
	}
}