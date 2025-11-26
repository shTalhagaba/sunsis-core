<?php
class upload_video_evidence implements IAction
{
	public function execute(PDO $link)
	{
		$subaction = isset($_REQUEST['subaction']) ? $_REQUEST['subaction'] : '';
		if($subaction == 'upload_file')
		{
			$this->uploadFile($link);
		}
		if($subaction == 'get_video_file_record')
		{
			echo json_encode(DAO::getObject($link, "SELECT id, status, assessor_comments FROM video_files WHERE id = '{$_REQUEST['id']}'"));
			exit;
		}
		if($subaction == 'save_assessor_feedback')
		{
			$obj = DAO::getObject($link, "SELECT * FROM video_files WHERE id = '{$_REQUEST['id']}'");
			$obj->status = $_REQUEST['status'];
			$obj->assessor_comments = $_REQUEST['assessor_comments'];
			DAO::saveObjectToTable($link, 'video_files', $obj);
			http_redirect('do.php?_action=upload_video_evidence&tr_id='.$_REQUEST['tr_id'].'&unit_ref='.$_REQUEST['unit_ref'].'&qan='.$_REQUEST['qan'].'&title='.$_REQUEST['title']);
			exit;
		}

		$tr_id = isset($_REQUEST['tr_id']) ? $_REQUEST['tr_id'] : '';
		$unit_ref = isset($_REQUEST['unit_ref']) ? $_REQUEST['unit_ref'] : '';
		$qan = isset($_REQUEST['qan']) ? $_REQUEST['qan'] : '';
		$title = isset($_REQUEST['title']) ? $_REQUEST['title'] : '';

		if($tr_id == '' || $unit_ref == '')
			throw new Exception('Missing querystring arguments: tr_id, unit_ref');

		$_SESSION['bc']->add($link, "do.php?_action=upload_video_evidence&tr_id={$tr_id}&unit_ref={$unit_ref}&qan={$qan}", "Upload Video Evidences");

		$tr = TrainingRecord::loadFromDatabase($link, $tr_id);

		$student_qualification = DAO::getObject($link, "SELECT * FROM student_qualifications WHERE tr_id = '{$tr->id}' AND REPLACE(id, '/', '') = '{$qan}'");
		if(!isset($student_qualification->id))
			throw new Exception('No qualification found.');

		$statuses = array(
			array('0', 'Awaiting Assessor Feedback'),
			array('1', 'Assessor Accepted'),
			array('2', 'Referred to Learner')
		);

		include_once('tpl_upload_video_evidence.php');
	}

	private function uploadFile(PDO $link)
	{
		$tr_id = isset($_REQUEST['tr_id']) ? $_REQUEST['tr_id'] : '';
		$unit_ref = isset($_REQUEST['unit_ref']) ? $_REQUEST['unit_ref'] : '';
		$qan = isset($_REQUEST['qan']) ? $_REQUEST['qan'] : '';
		$title = isset($_REQUEST['title']) ? $_REQUEST['title'] : '';

		$tr = TrainingRecord::loadFromDatabase($link, $tr_id);

		$valid_extensions = array('mp4');

		$r = Repository::processFileUploads('input_file_field', '/'.$tr->username.'/videos', $valid_extensions);

		if(isset($r[0]) && $r[0] != '')
		{
			$obj = new stdClass();
			$obj->file_name = basename($r[0]);
			$obj->uploaded_by = $_SESSION['user']->id;
			$obj->uploaded_date = null;
			$obj->tr_id = $tr_id;
			$obj->unit_ref = $unit_ref;
			$obj->status = 0;
			$obj->qan = $qan;

			DAO::saveObjectToTable($link, 'video_files', $obj);
		}

		http_redirect('do.php?_action=upload_video_evidence&tr_id='.$tr->id.'&unit_ref='.$unit_ref.'&qan='.$qan.'&title='.$title);
	}
}