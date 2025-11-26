<?php
class rec_edit_question implements IAction
{
	public function execute(PDO $link)
	{
		$id = isset($_REQUEST['id'])?$_REQUEST['id']:'';
		$subaction = isset($_REQUEST['subaction'])?$_REQUEST['subaction']:'';

		if($subaction == 'save_record')
		{
			$this->saveRecord($link);
		}

		$_SESSION['bc']->add($link, "do.php?_action=rec_edit_question&id=" . $id, "Add/Edit Vacancy Question");

		$types = array(
			array('0', 'General', ''),
			array('1', 'Sector Specific', '')
		);

		$sector_types = DAO::getResultset($link, "SELECT id, description, null FROM lookup_sector_types ORDER BY description");

		$type = "";
		$question_desc = "";
		$sector_id = "";
		$sector_desc = "";

		if($id != '')
		{
			$details = DAO::getResultset($link, "SELECT * FROM rec_questions WHERE id = '{$id}'", DAO::FETCH_ASSOC);
			if(count($details) > 0 || $details == '')
			{
				$details = $details[0];
				$type = $details['type'];
				$question_desc = $details['description'];
				$sector_id = $details['sector_id'];
				$sector_desc = DAO::getSingleValue($link, "SELECT description FROM lookup_sector_types WHERE id = '{$sector_id}'");
			}
		}

		include_once('tpl_rec_edit_question.php');
	}

	private function saveRecord(PDO $link)
	{
		$vo = new stdClass();
		$vo->id = isset($_REQUEST['id'])?$_REQUEST['id']:'';
		$vo->description = isset($_REQUEST['description'])?$_REQUEST['description']:'';
		$vo->type = isset($_REQUEST['type'])?$_REQUEST['type']:'';
		$vo->sector_id = isset($_REQUEST['sector_id'])?$_REQUEST['sector_id']:'';

		DAO::saveObjectToTable($link, 'rec_questions', $vo);

		http_redirect('do.php?_action=rec_view_questions&id='.$vo->id);
	}
}