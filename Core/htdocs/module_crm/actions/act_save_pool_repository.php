<?php
class save_pool_repository implements IAction
{
	public function execute(PDO $link)
	{
		$pool_id = isset($_REQUEST['pool_id']) ? $_REQUEST['pool_id']:'';
		$loc_id = isset($_REQUEST['loc_id']) ? $_REQUEST['loc_id']:'';
		$section = isset($_REQUEST['section']) ? $_REQUEST['section']:'';
		if(!$pool_id){
			throw new Exception("Missing querystring argument, pool_id");
		}

		$target_directory = "/pools/" . $pool_id;
		if($loc_id != '')
			$target_directory = "/pools/" . $pool_id . "/locations/" . $loc_id;
		if($section != '')
			$target_directory = "/pools/" . $pool_id . "/locations/" . $loc_id . "/" . $section;
		$valid_extensions = array('pdf', 'doc', 'docx', 'xls', 'xlsx', 'csv', 'txt', 'xml', 'zip', 'rar', '7z');
		
		Repository::processFileUploads('uploaded_pool_file', $target_directory, $valid_extensions);

		http_redirect($_SESSION['bc']->getCurrent().'&section='.$section);
	}

}
?>