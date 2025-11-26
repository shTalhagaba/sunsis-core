<?php
class wb_ajax implements IAction
{
	public function execute( PDO $link )
	{
		$wb_id = isset($_REQUEST['wb_id'])?$_REQUEST['wb_id']:'';
		$subaction = isset($_REQUEST['subaction'])?$_REQUEST['subaction']:'';

		if($wb_id == '')
		{
			echo '<h4><i>No record found</i></h4>';
			exit;
		}

		if($subaction == 'assessor_feedback')
		{
			$section = isset($_REQUEST['section'])?$_REQUEST['section']:'';
			$wb = Workbook::loadFromDatabase($link, $wb_id);
			echo $wb->showAssessorFeedback($link, $section);
			unset($wb);
			exit;
		}
		if($subaction == 'section_history')
		{
			$section = isset($_REQUEST['section'])?$_REQUEST['section']:'';
			$wb = Workbook::loadFromDatabase($link, $wb_id);
			echo $wb->showSectionHistory($link, $section);
			unset($wb);
			exit;
		}
		if($subaction == 'bookmark')
		{
			$this->bookmarkPage($link, $wb_id);
			exit;
		}
	}

	public function bookmarkPage(PDO $link, $wb_id)
	{
		$page = isset($_REQUEST['page'])?$_REQUEST['page']:'';
		if($page == '')
			return;

		$exists = DAO::getSingleValue($link, "SELECT COUNT(*) FROM wb_bookmarks WHERE wb_id = '{$wb_id}' AND page = '{$page}'");
		if($exists > 0)
			DAO::execute($link, "DELETE FROM wb_bookmarks WHERE wb_id = '{$wb_id}' AND page = '{$page}'");
		else
			DAO::execute($link, "INSERT INTO wb_bookmarks (wb_id, page) VALUES ('{$wb_id}', '{$page}')");
	}

}