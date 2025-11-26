<?php
class view_course_students_v2 implements IAction
{
	public function execute(PDO $link)
	{
		if(!isset($_REQUEST['filter_course_id']))
			$_REQUEST['filter_course_id'] = $_REQUEST['id'];

		$view = VoltView::getViewFromSession('ViewCourseStudents', 'ViewCourseStudents'); /* @var $view VoltView */
		//if(is_null($view))
		{
			$view = $_SESSION['ViewCourseStudents'] = $this->buildView($link);
		}
		$view->refresh($_REQUEST, $link);

		include_once('tpl_view_course_students_v2.php');
	}

	public function ViewCourseStudents(PDO $link)
	{
		$sql = new SQLStatement("
SELECT
	tr.*
FROM
	tr INNER JOIN course_tr ON tr.id = courses_tr.tr_id
;
		");
		$view = new VoltView('ViewCourseStudents', $sql->__toString());

		$f = new VoltTextboxViewFilter('filter_course_id', "WHERE courses_tr.course_id = '%s%%'", null);
		$f->setDescriptionFormat("Course ID: %s");
		$view->addFilter($f);

		$options = array(
			0=>array(20,20,null,null),
			1=>array(50,50,null,null),
			2=>array(100,100,null,null),
			3=>array(200,200,null,null),
			4=>array(300,300,null,null),
			5=>array(400,400,null,null),
			6=>array(500,500,null,null),
			7=>array(0, 'No limit', null, null));
		$f = new VoltDropDownViewFilter(View::KEY_PAGE_SIZE, $options, 20, false);
		$f->setDescriptionFormat("Records per page: %s");
		$view->addFilter($f);

		return $view;
	}
}