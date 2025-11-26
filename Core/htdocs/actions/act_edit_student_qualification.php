<?php
class edit_student_qualification implements IAction
{
	public function execute(PDO $link)
	{
		$qualification_id = isset($_REQUEST['qualification_id'])?$_REQUEST['qualification_id']:'';
		$framework_id = isset($_REQUEST['framework_id'])?$_REQUEST['framework_id']:'';
		$tr_id = isset($_REQUEST['tr_id'])?$_REQUEST['tr_id']:'';
		$internaltitle = isset($_REQUEST['internaltitle'])?$_REQUEST['internaltitle']:'';
		$target= isset($_REQUEST['target'])?$_REQUEST['target']:'';
		$achieved= isset($_REQUEST['achieved'])?$_REQUEST['achieved']:'';

		$_SESSION['bc']->add($link, "do.php?_action=edit_student_qualification&qualification_id=" . "'" . $qualification_id . "'" . "&framework_id=" . $framework_id . "&tr_id=" . $tr_id . "&internaltitle=" . "'" . $internaltitle . "'" . "&achieved=" . $achieved . "&target=" . $target, "Edit Learner Qualification Tree");
		
		$que = "select concat(firstnames, ' ',surname) from tr where id='$tr_id'";
		$names = trim(DAO::getSingleValue($link, $que));
		
		$que = "select title from frameworks where id='$framework_id'";
		$framework = trim(DAO::getSingleValue($link, $que));
		
		$que = "select start_date from tr where id='$tr_id'";
		$study_start_date = trim(DAO::getSingleValue($link, $que));
		
		$que = "select target_date from tr where id='$tr_id'";
		$study_end_date = trim(DAO::getSingleValue($link, $que));
		
		if($qualification_id != '' && $framework_id!='' && $tr_id!='' && $internaltitle!=='')
		{
			$vo = StudentQualification::loadFromDatabase($link, $qualification_id, $framework_id, $tr_id, $internaltitle);
			$qualification_start_date = $vo->start_date;
			$qualification_end_date = $vo->end_date;
		}
		else
		{
			$vo = new StudentQualification(); // Blank qualification
		}

		$query = "select surname from tr where id='$tr_id'";
		$name = trim(DAO::getSingleValue($link, $query));

		$que = "select title from student_frameworks where tr_id='$tr_id'";
		$framework_title = trim(DAO::getSingleValue($link, $que));
	 
	 	// Calculating current month since course start date
		$que = "select DATE_FORMAT(start_date,'%m') from tr where id='$tr_id'";
		$study_start_month = (int)trim(DAO::getSingleValue($link, $que));
		$que = "select DATE_FORMAT(start_date,'%Y') from tr where id='$tr_id'";
		$study_start_year = (int)trim(DAO::getSingleValue($link, $que));
		$current_year = (int)date("Y");
		$current_month = (int)date("m");
		$current_month_since_study_start_date = ($current_year - $study_start_year) * 12;

		if($current_month > $study_start_month)
			$current_month_since_study_start_date += ($current_month - $study_start_month + 1);
		else
			$current_month_since_study_start_date += ($current_month - $study_start_month + 1);
		
		if($framework_title==NULL || $framework_title=='')
			$current_month_since_study_start_date = NULL;
			
		$month = "month_" . ($current_month_since_study_start_date-1);	

		if($current_month_since_study_start_date>1 && $current_month_since_study_start_date<=36)
		{
			// Calculating target month and target
			$internaltitle = addslashes((string)$internaltitle);
			$que = <<<QUERY
SELECT
	avg($month)
FROM
	student_milestones
WHERE
	framework_id = $framework_id
AND
	chosen = 1
AND
	qualification_id = '$qualification_id'
AND
	internaltitle = '$internaltitle'
AND
	tr_id = $tr_id
QUERY;
			//$que = "select avg($month) from student_milestones where framework_id = '$framework_id' and chosen=1 and qualification_id='$qualification_id' and internaltitle='$internaltitle' and tr_id='$tr_id'";
			$target = trim(DAO::getSingleValue($link, $que));
		}
		else
			$target='';		
		
		$que = "select DATE_FORMAT(target_date,'%d/%m/%Y') from tr where id='$tr_id'";
		$end_date = trim(DAO::getSingleValue($link, $que));
		

		// Getting milestones for this month
		$miles2 = Array(); 
		if($current_month_since_study_start_date>1 && $current_month_since_study_start_date<=36)
		{
			$internaltitle = addslashes((string)$internaltitle);
			$que = <<<QUERY
SELECT
	unit_id, $month
FROM
	student_milestones
WHERE
	framework_id = $framework_id
AND
	qualification_id = '$qualification_id'
AND
	internaltitle = '$internaltitle'
AND
	tr_id = $tr_id
QUERY;
			//$que = "select unit_id, $month from student_milestones where framework_id = '$framework_id' and qualification_id='$qualification_id' and internaltitle='$internaltitle' and tr_id='$tr_id'";
			$st = $link->query($que);			
			if($st) 
			{
				while($row = $st->fetch())
				{
					$miles2[$row['unit_id']]= $row[$month];
				}
	
			} 
		}
		else
		{
			$internaltitle = addslashes((string)$internaltitle);
			$que = <<<QUERY
SELECT
	unit_id
FROM
	student_milestones
WHERE
	framework_id = $framework_id
AND
	qualification_id = '$qualification_id'
AND
	internaltitle = '$internaltitle'
AND
	tr_id = $tr_id
QUERY;
			//$que = "select unit_id from student_milestones where framework_id = '$framework_id' and qualification_id='$qualification_id' and internaltitle='$internaltitle' and tr_id='$tr_id'";
			$st2 = $link->query($que);
			if($st2) 
			{
				while($row = $st2->fetch())
				{
					$miles2[$row['unit_id']]= 0;
				}
	
			} 
		}
		
		// Drop down list arrays
		$evidence_type_dropdown = "SELECT id, CONCAT(id, ' - ', description), null FROM lookup_assess_type ORDER BY id;";
		$evidence_type_dropdown = DAO::getResultset($link, $evidence_type_dropdown);
		$status_dropdown = "SELECT id, CONCAT(id, ' - ', description), null FROM lookup_unit_status ORDER BY id;";
		$status_dropdown = DAO::getResultset($link, $status_dropdown);
		$type_dropdown = "SELECT id, CONCAT(id, ' - ', description), null FROM lookup_qual_type ORDER BY id;";
		$type_dropdown = DAO::getResultset($link, $type_dropdown);
		$assess_dropdown = "SELECT id, CONCAT(id, ' - ', description), null FROM lookup_assess_type ORDER BY id;";
		$assess_dropdown = DAO::getResultset($link, $assess_dropdown);
		$level_checkboxes = "SELECT id, CONCAT(id, ' - ', description), null FROM lookup_qual_level ORDER BY id;";
		$level_checkboxes = DAO::getResultset($link, $level_checkboxes);

		$evidence_dropdown = "SELECT title, CONCAT(title, ' - ', DATE_FORMAT(date,'%d-%m-%Y') , ' - ' , assessor), null FROM evidence_template where tr_id='$tr_id' ORDER BY id;";
		$evidence_dropdown = DAO::getResultset($link, $evidence_dropdown);
		
		$assessment_method_dropdown = "SELECT id, type, null FROM lookup_evidence_type ORDER BY id;";
		$assessment_method_dropdown = DAO::getResultset($link, $assessment_method_dropdown);
		
		$evidence_type_dropdown = "SELECT id, content, null FROM lookup_evidence_content ORDER BY id;";
		$evidence_type_dropdown = DAO::getResultset($link, $evidence_type_dropdown);

		$category_dropdown = "SELECT id, category, null FROM lookup_evidence_categories ORDER BY id;";
		$category_dropdown = DAO::getResultset($link, $category_dropdown);

		$qualification_dropdown = "SELECT CONCAT(id,'*',internaltitle), CONCAT(id, ' - ',LEFT(internaltitle,80), ' [',`mandatory_units`, '/', units - `mandatory_units`,']'), LEFT(id,3) FROM qualifications ORDER BY id;";
		$qualification_dropdown = DAO::getResultset($link, $qualification_dropdown);

		$importUnits = array();


		$status = array(
		array('1', 'Achieved', ''),
		array('0', 'Outstanding', ''),
		array('2', 'Reset', ''));

		$fs_opt_in = array(
			array('Yes', 'Yes', ''),
			array('No', 'No', ''));
			

		$internaltitle = stripslashes($internaltitle);
		require_once('tpl_edit_student_qualification.php');
	}
	
	
	private function checkPermissions(PDO $link, Course $c_vo)
	{
		if($_SESSION['role'] == 'admin')
		{
			return true;
		}
		elseif($_SESSION['org']->org_type_id == ORG_PROVIDER)
		{
			$acl = CourseACL::loadFromDatabase($link, $c_vo->id);
			$is_employee = $_SESSION['org']->id == $c_vo->organisations_id;
			$is_local_admin = in_array('ladmin', $_SESSION['privileges']);
			$listed_in_course_acl = in_array($_SESSION['username'], $acl->usernames);
			
			return $is_employee && $is_local_admin;
		}
		elseif($_SESSION['org']->org_type_id == ORG_SCHOOL)
		{
			return false;
		}
		else
		{
			return false;
		}
	}
}
?>