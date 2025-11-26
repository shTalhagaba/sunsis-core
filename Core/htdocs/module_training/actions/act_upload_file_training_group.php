<?php
class upload_file_training_group implements IAction
{
	public function execute(PDO $link)
	{
		$course_id = isset($_REQUEST['course_id']) ? $_REQUEST['course_id']:'';
		$group_id = isset($_REQUEST['group_id']) ? $_REQUEST['group_id']:'';
		$tg_id = isset($_REQUEST['tg_id']) ? $_REQUEST['tg_id']:'';
		if(!$tg_id){
			throw new Exception("Missing querystring argument, tg_id");
		}

		$target_directory = "/t_groups/" . $tg_id;
		$valid_extensions = array('pdf', 'doc', 'docx', 'xls', 'xlsx', 'csv', 'txt', 'xml', 'zip', 'rar', '7z');

		Repository::processFileUploads('uploaded_tg_file', $target_directory, $valid_extensions);

		http_redirect("do.php?_action=read_course_v2&subview=training_group_view&id={$course_id}&group_id={$group_id}&tg_id={$tg_id}");
	}

}
?>