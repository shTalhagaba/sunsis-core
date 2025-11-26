<?php
class view_course_groups implements IAction
{
	public function execute(PDO $link)
	{
		// Retrieve cached view
		$view = VoltView::getViewFromSession('view', 'view_course_groups'); /* @var $view View */

		// Retrieve course ID from the cached view or from the querystring
		// (The current version of the View class caches all querystring
		// variables not recognised as filters as 'preferences')
		if(!is_null($view))
		{
			$course_id = $view->getPreference('course_id');
		}
		else
		{
			if(!array_key_exists('course_id', $_REQUEST))
			{
				throw new Exception("Missing querystring argument: course_id");
			}
			else
			{
				$course_id = $_REQUEST['course_id'];
			}
		}
		
		$_SESSION['bc']->add($link, "do.php?_action=view_course_groups&course_id=" . $course_id, "View Course Groups");
		
		
		$view = VoltView::getViewFromSession('primaryView', 'view_course_groups'); /* @var $view View */
		if(is_null($view))
		{
			$_SESSION['view'] = $view = $this->buildView($link, $course_id);			
		}
		
		$view->refresh($_REQUEST, $link);
		
		// Create Value Objects
		$c_vo = Course::loadFromDatabase($link, $course_id);
		
		$dao = new OrganisationDAO($link);
		$o_vo = $dao->find($link, (integer) $c_vo->organisations_id); /* @var $o_vo OrganisationVO */
		
		
/*		if($this->checkPermissions($link, $c_vo) == false)
		{
			throw new UnauthorizedException();
		}
*/		
		require_once('tpl_view_course_groups.php');
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
			
			return $is_employee && ($is_local_admin || $listed_in_course_acl);
		}
		elseif($_SESSION['org']->org_type_id == ORG_SCHOOL)
		{
			$num_pupils_on_course = "SELECT COUNT(*) FROM pot WHERE pot.courses_id={$c_vo->id} "
				. "AND pot.school_id={$_SESSION['org']->id};";
			$num_pupils_on_course = DAO::getSingleValue($link, $num_pupils_on_course);
			
			return $num_pupils_on_course > 0;
		}
		else
		{
			return false;
		}
	}
	
	
	private function buildView(PDO $link, $course_id)
	{

		if($_SESSION['user']->isAdmin() || $_SESSION['user']->type==12 || $_SESSION['user']->type==15)
		{
			$where = '';
		}
		elseif($_SESSION['user']->isOrgAdmin() || $_SESSION['user']->type==8 || $_SESSION['user']->type==13 || $_SESSION['user']->type==14)
		{
			$emp = $_SESSION['user']->employer_id;
			$where = '';
		}
		elseif($_SESSION['user']->type==2)
		{
            $id = $_SESSION['user']->id;
			$where = ' and (groups.tutor = '. '"' . $id . '"' . ' or course_qualifications_dates.tutor_username = "' . $id . '")';
		}
		elseif($_SESSION['user']->type==3)
		{
			$id = $_SESSION['user']->id;
			$where = ' and groups.assessor = '. '"' . $id . '"';
		}
		elseif($_SESSION['user']->type==4)
		{
			$id = $_SESSION['user']->id;
			$where = ' and groups.verifier = '. '"' . $id . '"';
		}
        elseif($_SESSION['user']->type==20)
        {
            $username = $_SESSION['user']->username;
            $where = ' and groups.assessor in (select assessor from tr where programme = '. '"' . $username . '")';
        }
		elseif($_SESSION['user']->type==21)
		{
			$username = $_SESSION['user']->username;
			//$where = ' and courses.director = '. '"' . $username . '"';
			$where = ' and find_in_set("' . $username . '", courses.director) ';
		}
		// Create new view object
		$sql = <<<HEREDOC
SELECT DISTINCT
	groups.id, groups.title, groups.tutor, users.firstnames as tutor_firstnames,
	users.surname as tutor_surname,
	assessors.firstnames as afirstnames,
	assessors.surname as asurname,
	groups.`scheduled_lessons`,
	groups.`registered_lessons`,
	groups.`attendances`,
	groups.`lates`,
	groups.`very_lates`,
	groups.`authorised_absences`,
	groups.`unexplained_absences`,
	groups.`unauthorised_absences`,
	groups.`dismissals_uniform`,
	groups.`dismissals_discipline`,
	(groups.attendances+
	groups.very_lates+
	groups.lates+
	groups.authorised_absences+
	groups.unexplained_absences+
	groups.unauthorised_absences+
	groups.dismissals_uniform+
	groups.dismissals_discipline) as `total`,
	groups.group_capacity,
	(SELECT COUNT(*) FROM group_members WHERE group_members.groups_id = groups.id) AS student_count,
	(SELECT COUNT(*) FROM group_members INNER JOIN tr ON group_members.`tr_id` = tr.id WHERE group_members.groups_id = groups.id AND tr.status_code = 1) AS active,
	(SELECT COUNT(*) FROM group_members INNER JOIN tr ON group_members.`tr_id` = tr.id WHERE group_members.groups_id = groups.id AND tr.status_code = 2) AS successful,
	(SELECT COUNT(*) FROM group_members INNER JOIN tr ON group_members.`tr_id` = tr.id WHERE group_members.groups_id = groups.id AND tr.status_code = 3) AS unsuccessful,
	(SELECT COUNT(*) FROM group_members INNER JOIN tr ON group_members.`tr_id` = tr.id WHERE group_members.groups_id = groups.id AND tr.status_code > 3) AS withdrawn
FROM
	groups 
	LEFT OUTER JOIN users ON groups.tutor=users.id
	LEFT JOIN courses on courses.id = groups.courses_id
	LEFT JOIN course_qualifications_dates on course_qualifications_dates.course_id = courses.id 
	LEFT JOIN users as assessors on assessors.id = groups.assessor
WHERE
	groups.courses_id=$course_id $where
HEREDOC;
		
/*		if($_SESSION['org']->org_type_id == ORG_SCHOOL)
		{
			// Confine schools to viewing groups that contain one or more of their
			// pupils
			$sql .= <<<HEREDOC
	AND groups.id IN (
		SELECT DISTINCT
			group_members.groups_id
		FROM
			group_members INNER JOIN pot
			ON group_members.pot_id = pot.id
		WHERE
			pot.school_id = {$_SESSION['org']->id}
		ORDER BY
			group_members.groups_id) 			
HEREDOC;
		}

*/		
		$view = new VoltView('view_course_groups', $sql); /* @var $view View */
		
		// Add view filters
		$options = array(
			0=>array(10,10,null,null),
			1=>array(20,20,null,null),
			2=>array(50,50,null,null),
			3=>array(0, 'No limit', null, null) );
		$f = new VoltDropDownViewFilter(VoltView::KEY_PAGE_SIZE, $options, 0, false);
		$f->setDescriptionFormat("Records per page: %s");
		$view->addFilter($f);
		
		$options = array(0=>array(1, 'Group title (asc)', null, 'ORDER BY groups.title ASC'),
			1=>array(2, 'Group title (desc)', null, 'ORDER BY groups.title DESC'));
		$f = new VoltDropDownViewFilter(VoltView::KEY_ORDER_BY, $options, 1, false);
		$f->setDescriptionFormat("Sort by: %s");
		$view->addFilter($f);		
		
		
		return $view;
	}
}
?>