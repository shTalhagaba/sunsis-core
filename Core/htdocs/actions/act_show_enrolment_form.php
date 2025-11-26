<?php
class show_enrolment_form implements IAction
{
	public function execute(PDO $link)
	{
		$tr_id = isset($_REQUEST['tr_id'])?$_REQUEST['tr_id']:'';
		
		$evidence_view = CoursePicker::getInstance($tr_id);
		$evidence_view->refresh($link, $_REQUEST);
		
/*		$que = "select surname from tr where id='$tr_id'";
		$learner = trim(DAO::getSingleValue($link, $que));
*/		
		// Drop down list arrays
			$sql = <<<HEREDOC
SELECT
	student_qualifications.id, student_qualifications.title, null
FROM
	student_qualifications 
	INNER JOIN student_frameworks
	ON student_frameworks.tr_id = student_qualifications.tr_id OR student_qualifications.framework_id=0
where student_qualifications.tr_id = $tr_id
HEREDOC;
		
		$qual_dropdown = DAO::getResultset($link, $sql);

		$director_select = array(0=>array('','',null));
		$director = '';
		
/*		$assess_dropdown = "SELECT id, CONCAT(id, ' - ', description), null FROM lookup_assess_type ORDER BY id;";
		$assess_dropdown = DAO::getResultset($link, $assess_dropdown);
		$level_checkboxes = "SELECT id, CONCAT(id, ' - ', description), null FROM lookup_qual_level ORDER BY id;";
		$level_checkboxes = DAO::getResultset($link, $level_checkboxes);

		$evidence_dropdown = "SELECT id, CONCAT(title, ' - ', type , ' - ', content , ' - ' , date , ' - ' , assessor), null FROM evidence_template ORDER BY id;";
		$evidence_dropdown = DAO::getResultset($link, $evidence_dropdown);
*/	
	
		require_once('tpl_enrolment_form.php');
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