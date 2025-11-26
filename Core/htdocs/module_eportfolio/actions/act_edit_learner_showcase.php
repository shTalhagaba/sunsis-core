<?php
class edit_learner_showcase implements IAction
{
	public function execute(PDO $link)
	{
		$subaction = isset($_REQUEST['subaction']) ? $_REQUEST['subaction'] : '';
		if($subaction == 'save')
		{
			$this->saveForm($link);
		}
		if($subaction == 'upload_file')
		{
			$this->uploadFile($link);
		}

		$tr_id = isset($_REQUEST['tr_id'])?$_REQUEST['tr_id']:'';
		$is_learner = $_SESSION['user']->type == User::TYPE_LEARNER ? true : false;

		$tr = TrainingRecord::loadFromDatabase($link, $tr_id);
		if(!$is_learner && $_SESSION['user']->username != $tr->username && !$_SESSION['user']->isAdmin())
			throw new UnauthorizedException();

		$_SESSION['bc']->add($link, "do.php?_action=edit_learner_showcase&tr_id" . $tr_id, "View/ Edit Showcase");

		$showcase = DAO::getObject($link, "SELECT * FROM tr_showcases WHERE tr_id = '{$tr->id}'");
		if(!isset($showcase->tr_id))
		{
			$showcase = new stdClass();
			$showcase->tr_id = $tr_id;
			$showcase->sc_content = null;
		}

		$toastr_message = isset($_REQUEST['toastr_message']) ? $_REQUEST['toastr_message'] : '';

		include_once('tpl_edit_learner_showcase.php');
	}

	private function saveForm(PDO $link)
	{
		$vo = new stdClass();
		$vo->tr_id = $_REQUEST['tr_id'];
		$vo->sc_content = $_REQUEST['sc_content'];
		DAO::saveObjectToTable($link, 'tr_showcases', $vo);

		http_redirect('do.php?_action=edit_learner_showcase&tr_id='.$_REQUEST['tr_id'].'&toastr_message=Your information has been saved successfully.');
	}

	private function uploadFile(PDO $link)
	{
		$tr_id = isset($_REQUEST['tr_id']) ? $_REQUEST['tr_id'] : '';
		$tr = TrainingRecord::loadFromDatabase($link, $tr_id);

		$valid_extensions = array('pdf', 'doc', 'docx', 'xls', 'xlsx', 'csv', 'txt', 'xml', 'zip', 'rar', '7z');

		Repository::processFileUploads('input_file_field', '/'.$tr->username.'/showcases', $valid_extensions);

		http_redirect('do.php?_action=edit_learner_showcase&tr_id='.$_REQUEST['tr_id'].'&toastr_message=File has been uploaded successfully.');
	}
}