<?php
class add_remove_lr implements IAction
{
	public function execute(PDO $link)
	{
		$tr_id = isset($_REQUEST['tr_id'])?$_REQUEST['tr_id']:'';
		$qual_to_add_id = isset($_REQUEST['qual_to_add_id'])?$_REQUEST['qual_to_add_id']:'';
		$qual_to_add_internaltitle = isset($_REQUEST['qual_to_add_internaltitle'])?$_REQUEST['qual_to_add_internaltitle']:'';
		$qualtoremove = isset($_REQUEST['qualtoremove'])?$_REQUEST['qualtoremove']:'';
		$proportion = isset($_REQUEST['proportion'])?$_REQUEST['proportion']:'';
		$proportion = ($proportion=='')?0:$proportion;
		$contract_id = isset($_REQUEST['contract_id'])?$_REQUEST['contract_id']:'';
		$submission = isset($_REQUEST['submission'])?$_REQUEST['submission']:'';
		$qualtoremove = $link->quote($qualtoremove);
		DAO::execute($link, "delete from student_qualifications where tr_id = $tr_id and concat(id,internaltitle) = $qualtoremove");

		$qual_to_add_id = str_replace("/","",$qual_to_add_id);
		$qual_to_add_internaltitle = str_replace(",","",$qual_to_add_internaltitle);
		$qualtoadd = $qual_to_add_id.$qual_to_add_internaltitle;
		$qualtoadd = $link->quote($qualtoadd);

		$tr = TrainingRecord::loadFromDatabase($link, $tr_id);

		$fid = DAO::getSingleValue($link, "select courses.framework_id from courses left join courses_tr on courses_tr.course_id = courses.id where courses_tr.tr_id = '$tr_id'");

		$sd = Date::toMySQL($tr->start_date);
		$ed = Date::toMySQL($tr->target_date);
		
		if(DB_NAME == "am_ligauk")
		{
			if($qualtoadd == "'Z0001925Non-regulated provision Lv 3 Administration'")
				$qualtoadd = "'Z0001925Non-regulated provision, Lv 3, Administration'";
			elseif($qualtoadd == "'Z0001875Non regulated provision Lv 2 Administration'")
				$qualtoadd = "'Z0001875Non regulated provision, Lv 2, Administration'";
			elseif($qualtoadd == "'Z0001875Non-regulated provision Lv 2 Administration'")
				$qualtoadd = "'Z0001875Non-regulated provision, Lv 2, Administration'";
			elseif($qualtoadd == "'Z0001926Non regulated provision Level 3 Business Management'")
				$qualtoadd = "'Z0001926Non regulated provision, Level 3, Business Management'";
			elseif($qualtoadd == "'Z0001948Non regulated provision Level 4 Retailing and Wholesaling'")
				$qualtoadd = "'Z0001948Non regulated provision, Level 4, Retailing and Wholesaling'";
			elseif($qualtoadd == "'Z0002026Non regulated provision Level 5 Business Management'")
				$qualtoadd = "'Z0002026Non regulated provision, Level 5, Business Management'";
		}

// importing qualification from framework		
		$query = <<<HEREDOC
INSERT INTO	student_qualifications
	(`id`,
  `framework_id`,
  `tr_id`,
  `internaltitle`,
  `lsc_learning_aim`,
  `awarding_body`,
  `title`,
  `description`,
  `assessment_method`,
  `structure`,
  `level`,
  `qualification_type`,
  `accreditation_start_date`,
  `operational_centre_start_date`,
  `accreditation_end_date`,
  `certification_end_date`,
  `dfes_approval_start_date`,
  `dfes_approval_end_date`,
  `evidences`,
  `units`,
  `unitsCompleted`,
  `unitsNotStarted`,
  `unitsBehind`,
  `unitsOnTrack`,
  `unitsUnderAssessment`,
  `unitsPercentage`,
  `proportion`,
  `aptitude`,
  `attitude`,
  `comments`,
  `modified`,
  `username`,
  `trading_name`,
  `auto_id`,
  `start_date`,
  `end_date`,
  `actual_end_date`,
  `achievement_date`,
  `units_required`,
  `awarding_body_reg`,
  `awarding_body_date`,
  `awarding_body_batch`,
  `a14`,
  `a18`,
  `a51a`,
  `a16`)
SELECT
id, 
'$fid', 
'$tr_id', 
internaltitle, 
lsc_learning_aim, 
awarding_body, 
title, 
description, 
assessment_method, 
structure, 
level, 
qualification_type, 
regulation_start_date, 
operational_start_date, 
operational_end_date, 
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
'$proportion', 
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
FROM qualifications
	WHERE CONCAT(replace(id,'/',''),internaltitle)=$qualtoadd and CONCAT(REPLACE(id,'/',''),internaltitle) not in (select CONCAT(REPLACE(id,'/',''),internaltitle) from student_qualifications where tr_id = '$tr_id');
HEREDOC;

		//if(SOURCE_BLYTHE_VALLEY)
		 //    pre($query);

		$st = $link->query($query);
		if(!$st)
			throw new Exception(implode($link->errorInfo()));
		// Creating milestones
		$sql = "SELECT *, timestampdiff(MONTH, start_date, end_date) as months FROM student_qualifications where tr_id = $tr_id and concat(id,internaltitle) = $qualtoadd";
		$st = $link->query($sql);
		$unit=0;
		while($row = $st->fetch())
		{
			$xml = mb_convert_encoding($row['evidences'],'UTF-8');

			//$pageDom = new DomDocument();
			//$pageDom->loadXML($xml);
			$pageDom = XML::loadXmlDom($xml);

			$evidences = $pageDom->getElementsByTagName('unit');
			foreach($evidences as $evidence)
			{
				$unit_id = $evidence->getAttribute('owner_reference');
				$tr_id = $row['tr_id'];
				$framework_id = $row['framework_id'];
				$qualification_id = $row['id'];
				$internaltitle = addslashes((string)$row['internaltitle']);

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

				$unit_id = str_replace(" ","",$unit_id);

				DAO::execute($link, "delete from student_milestones where tr_id = '$tr_id' and qualification_id not in (select id from student_qualifications where tr_id = '$tr_id')");
				DAO::execute($link, "insert into student_milestones (framework_id, qualification_id, internaltitle, unit_id, month_1, month_2, month_3, month_4, month_5, month_6, month_7, month_8, month_9, month_10, month_11, month_12, month_13, month_14, month_15, month_16, month_17, month_18, month_19, month_20, month_21, month_22, month_23, month_24, month_25, month_26, month_27, month_28, month_29, month_30, month_31, month_32, month_33, month_34, month_35, month_36, id, tr_id, chosen) values($framework_id, '$qualification_id', '$internaltitle', '$unit_id', $m[0], $m[1], $m[2], $m[3], $m[4], $m[5], $m[6], $m[7], $m[8], $m[9], $m[10], $m[11], $m[12], $m[13], $m[14], $m[15], $m[16], $m[17], $m[18], $m[19], $m[20], $m[21], $m[22], $m[23], $m[24], $m[25], $m[26], $m[27], $m[28], $m[29], $m[30], $m[31], $m[32], $m[33], $m[34], $m[35], 1, $tr_id, 1)");
			}
		}

		if($contract_id!='' && $submission!='')
			http_redirect("do.php?_action=read_training_record&id=" . $tr->id . "&contract_id=" . $contract_id . "&submission=" . $submission);
		else
			http_redirect("do.php?_action=read_training_record&id=" . $tr->id);

	}
	public static function xmlspecialchars($text)
	{
		return str_replace('&#039;', '&apos;', htmlspecialchars((string)$text, ENT_QUOTES));
	}
}
?>