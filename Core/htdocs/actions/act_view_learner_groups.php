<?php
class view_learner_groups implements IAction
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
		
		$view = VoltView::getViewFromSession('primaryView', 'view_course_groups'); /* @var $view View */
		if(is_null($view))
		{
			$_SESSION['view'] = $view = $this->buildView($link);			
		}
		
		$view->refresh($_REQUEST, $link);
		
		require_once('tpl_view_learner_groups.php');
	}
	
	
	private function buildView(PDO $link)
	{
		// Create new view object
		$identities = DAO::pdo_implode($_SESSION['user']->getIdentities());
		
		if($_SESSION['user']->isAdmin() || $_SESSION['user']->type==12)
		{
		$sql = <<<HEREDOC
SELECT
	groups.id as gid, groups.title as group_title, groups.tutor, users.firstnames as tutor_firstnames,
	users.surname as tutor_surname,
	groups.scheduled_lessons,
	groups.registered_lessons,
	groups.attendances,
	groups.lates,
	groups.authorised_absences,
	groups.unexplained_absences,
	groups.unauthorised_absences,
	groups.dismissals_uniform,
	groups.dismissals_discipline,
	(groups.attendances+
	groups.lates+
	groups.authorised_absences+
	groups.unexplained_absences+
	groups.unauthorised_absences+
	groups.dismissals_uniform+
	groups.dismissals_discipline) as `total`,
	(SELECT COUNT(*) FROM group_members WHERE group_members.groups_id = groups.id) AS student_count,
	courses.title,
	courses.id as cid,
	DATE_FORMAT(courses.course_start_date,'%d-%m-%Y') as start_date, 
	DATE_FORMAT(courses.course_end_date,'%d-%m-%Y') as end_date

FROM
	groups LEFT OUTER JOIN users ON groups.tutor=users.username
	INNER JOIN courses ON courses.id = groups.courses_id
Where groups.id IS NOT NULL;
HEREDOC;
		}
		else
		{

			$username = $_SESSION['user']->username;
		$sql = <<<HEREDOC
SELECT
	groups.id as gid, groups.title as group_title, groups.tutor, users.firstnames as tutor_firstnames,
	users.surname as tutor_surname,
	groups.scheduled_lessons,
	groups.registered_lessons,
	groups.attendances,
	groups.lates,
	groups.authorised_absences,
	groups.unexplained_absences,
	groups.unauthorised_absences,
	groups.dismissals_uniform,
	groups.dismissals_discipline,
	(groups.attendances+
	groups.lates+
	groups.authorised_absences+
	groups.unexplained_absences+
	groups.unauthorised_absences+
	groups.dismissals_uniform+
	groups.dismissals_discipline) as `total`,
	(SELECT COUNT(*) FROM group_members WHERE group_members.groups_id = groups.id) AS student_count,
	courses.title,
	courses.id as cid,
	DATE_FORMAT(courses.course_start_date,'%d-%m-%Y') as start_date, 
	DATE_FORMAT(courses.course_end_date,'%d-%m-%Y') as end_date

FROM
	groups LEFT OUTER JOIN users ON groups.tutor=users.username
	INNER JOIN courses ON courses.id = groups.courses_id
	INNER JOIN acl 
	ON acl.resource_id = groups.id and acl.resource_category = 'group'
	AND (acl.resource_id IN (select id from groups where tutor='$username') OR acl.resource_id IN (select id from groups where assessor='$username') OR acl.resource_id IN (select id from groups where verifier='$username'))
	AND (acl.privilege = 'read' OR acl.privilege ='write')
Where acl.ident IN ($identities)
HEREDOC;
		}			

		$view = new VoltView('view_course_groups', $sql); /* @var $view View */
		
		// Add view filters
		$options = array(
			0=>array(10,10,null,null),
			1=>array(20,20,null,null),
			2=>array(50,50,null,null),
			3=>array(0, 'No limit', null, null) );
		$f = new VoltDropDownViewFilter(VoltView::KEY_PAGE_SIZE, $options, 20, false);
		$f->setDescriptionFormat("Records per page: %s");
		$view->addFilter($f);
		
		$options = array(0=>array(1, 'Group title (asc)', null, 'ORDER BY groups.title ASC'),
			1=>array(2, 'Group title (desc)', null, 'ORDER BY groups.title DESC'));
		$f = new VoltDropDownViewFilter(VoltView::KEY_ORDER_BY, $options, 1, false);
		$f->setDescriptionFormat("Sort by: %s");
		$view->addFilter($f);		
		
		$options = 'SELECT id, title, null, CONCAT(" where courses.id=",id) FROM courses order by title';
		$f = new VoltDropDownViewFilter('filter_course', $options, null, true);
		$f->setDescriptionFormat("Group: %s");
		$view->addFilter($f);

		$options = 'SELECT id, title, null, CONCAT(" where courses.framework_id=",id) FROM frameworks order by title';
		$f = new VoltDropDownViewFilter('filter_framework', $options, null, true);
		$f->setDescriptionFormat("Framework: %s");
		$view->addFilter($f);
		
		return $view;
	}
}
?>