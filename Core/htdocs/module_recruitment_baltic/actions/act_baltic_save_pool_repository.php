<?php
class baltic_save_pool_repository implements IAction
{
	public function execute(PDO $link)
	{
		$emp_id = isset($_REQUEST['emp_id']) ? $_REQUEST['emp_id']:'';
		if(!$emp_id){
			throw new Exception("Missing querystring argument, emp_id");
		}

		$target_directory = "/recruitment/" . $emp_id;
		$valid_extensions = array('pdf', 'doc', 'docx', 'xls', 'xlsx', 'csv', 'txt', 'xml', 'zip', 'rar', '7z');

		// re - 01/03/2012 - changed the form element name #22414
		//    - there are too many things called uploadFile in the tpl
		//    - for clarity.  Removed camelcase and replaced with
		//    - underscored word separation as above support request
		//    - was caused by camelcase issue.
		// ---
		Repository::processFileUploads('uploaded_employer_file', $target_directory, $valid_extensions);

		http_redirect($_SESSION['bc']->getCurrent());
	}

}
?>