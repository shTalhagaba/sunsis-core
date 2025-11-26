<?php
class ajax_save_update_course_learners implements IAction
{
	public function execute(PDO $link)
	{
		try {
			DAO::transaction_start($link);
			$this->process($link);
			DAO::transaction_commit($link);
		}
		catch(Exception $e) {
			DAO::transaction_rollback($link);
			throw $e;
		}
	}

	public function process(PDO $link)
	{
		$course_id = isset($_REQUEST['course_id'])?$_REQUEST['course_id']:'';
		$addQuals = isset($_REQUEST['addQuals'])?$_REQUEST['addQuals']:'';
		$addUnits = isset($_REQUEST['addUnits'])?$_REQUEST['addUnits']:'';
		if($course_id==''){
			throw new Exception("Missing querystring argument, course_id");
		}

		$fid = DAO::getSingleValue($link, "select framework_id from courses where id = '$course_id'");
		
		if($addQuals=='true')
		{
			// Remove Quals first that are not part of the  framework
			DAO::execute($link, "delete from student_qualifications where tr_id in (select tr_id from courses_tr where course_id = $course_id) and unitsUnderAssessment=0 and replace(id,'/','') not in (select replace(id,'/','') from framework_qualifications where framework_id = $fid)");
			
			// Add new Quals
			$sql = "SELECT tr_id from courses_tr where course_id = '$course_id'";
			$ids = DAO::getSingleColumn($link, $sql);
			foreach($ids as $tr_id)
			{
				$tr = TrainingRecord::loadFromDatabase($link, $tr_id);
				if(!$tr){
					continue;
				}
				$sd = Date::toMySQL($tr->start_date);
				$ed = Date::toMySQL($tr->target_date);

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
	where framework_qualifications.framework_id = '$fid' and framework_qualifications.id NOT IN (SELECT id FROM student_qualifications WHERE tr_id = '$tr_id');
HEREDOC;
				DAO::execute($link, $query);
			}
		}
		
		if($addUnits=='true')
		{
			$sql = "SELECT framework_qualifications.evidences AS source, student_qualifications.evidences AS target, student_qualifications.auto_id FROM student_qualifications LEFT JOIN framework_qualifications ON student_qualifications.id = framework_qualifications.id AND student_qualifications.framework_id = framework_qualifications.framework_id LEFT JOIN courses_tr ON courses_tr.tr_id = student_qualifications.tr_id WHERE courses_tr.course_id  = '$course_id' AND student_qualifications.framework_id != 0 AND framework_qualifications.evidences IS NOT NULL;";
			$st = $link->query($sql);
			if($st) 
			{
				while($row = $st->fetch())
				{
					$auto_id = $row['auto_id'];
					$percentage_array = Array();
					$proportion_array = Array();	
					//$pageDom = new DomDocument();
					//$pageDom->loadXML($row['target']);
					$pageDom = XML::loadXmlDom($row['target']);
					$units = $pageDom->getElementsByTagName('unit');
					foreach($units as $unit)
					{
						$percentage_array[$unit->getAttribute("reference")] = $unit->getAttribute("percentage");
					}		

					//$pageDom = new DomDocument();
					//$pageDom->loadXML($row['source']);
					$pageDom = XML::loadXmlDom($row['source']);
					$units = $pageDom->getElementsByTagName('unit');
					foreach($units as $unit)
					{
						if(array_key_exists($unit->getAttribute("reference"),$percentage_array))
							$unit->setAttribute("percentage",$percentage_array[$unit->getAttribute("reference")]);
						else 
							$unit->setAttribute("percentage","0");
					}		
	
					$qual = $pageDom->saveXML();
					$qual = substr($qual,21);
					$qual = str_replace("'","apos;",$qual);
					DAO::execute($link, "update student_qualifications set evidences = '$qual' where auto_id = '$auto_id'");
				}			
			}
			
			//$sql = "SELECT student_qualifications.* FROM student_qualifications LEFT JOIN framework_qualifications ON student_qualifications.id = framework_qualifications.id AND student_qualifications.framework_id = framework_qualifications.framework_id LEFT JOIN courses_tr ON courses_tr.tr_id = student_qualifications.tr_id WHERE courses_tr.course_id  = '$course_id';";
			//$st = $link->query($sql);
		}
	}
}
?>