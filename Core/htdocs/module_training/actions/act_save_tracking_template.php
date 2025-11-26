<?php
class save_tracking_template implements IAction
{
	public function execute(PDO $link)
	{
		$subaction = isset($_REQUEST['subaction']) ? $_REQUEST['subaction'] : '';

		if($subaction == 'add_section')
		{
			$this->add_section($link);
			exit;
		}
		if($subaction == 'add_element')
		{
			$this->add_element($link);
			exit;
		}
	}

	private function add_section(PDO $link)
	{
		$course_id = $_POST['course_id'] ? $_POST['course_id'] : '';
		$title = $_POST['new_section_title'] ? $_POST['new_section_title'] : '';

		$vo = new stdClass();
		$vo->course_id = $course_id;
		$vo->title = $title;
		DAO::saveObjectToTable($link, "tracking_template", $vo);

		http_redirect("do.php?_action=edit_tracking_template&course_id={$course_id}");
	}

	private function add_element(PDO $link)
	{
		$course_id = $_POST['course_id'] ? $_POST['course_id'] : '';
		$section_id = $_POST['section_id'] ? $_POST['section_id'] : '';
		$title = $_POST['new_element_title'] ? $_POST['new_element_title'] : '';

		$vo = new stdClass();
		$vo->course_id = $course_id;
		$vo->section_id = $section_id;
		$vo->title = $title;
		DAO::saveObjectToTable($link, "tracking_template", $vo);

		http_redirect("do.php?_action=edit_tracking_template&course_id={$course_id}");
	}
}