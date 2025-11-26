<?php
class upload_crm_file implements IAction
{
	public function execute(PDO $link)
	{
		$entity_id = isset($_REQUEST['entity_id']) ? $_REQUEST['entity_id']:'';
		$entity_type = isset($_REQUEST['entity_type']) ? $_REQUEST['entity_type']:'';
		if(!$entity_id){
			throw new Exception("Missing querystring argument, entity_id");
		}

		$target_directory = "/crm/{$entity_type}/{$entity_id}";

		$valid_extensions = array('pdf', 'doc', 'docx', 'xls', 'xlsx', 'csv', 'txt', 'xml', 'zip', 'rar', '7z');

		$r = Repository::processFileUploads('uploaded_crm_file', $target_directory, $valid_extensions);

		if(!isset($r[0]))
			throw new Exception('Error uploading progression evidence, please try again.');

		$result = new stdClass();
		$result->id = null;
		$result->entity_id = $entity_id;
		$result->entity_type = $entity_type;
		$result->file_name = basename($r[0]);
		$result->uploaded_by = $_SESSION['user']->id;
		$result->file_path = $r[0];
		DAO::saveObjectToTable($link, 'crm_entities_files', $result);

		$note = new Note();
		$note->subject = "File Uploaded";
		$note->note = 'File Name: ' . $result->file_name;
		$note->is_audit_note = true;
		if($entity_type == 'enquiry')
			$note->parent_table = 'crm_enquiries';
		elseif($entity_type == 'lead')
			$note->parent_table = 'crm_leads';
		elseif($entity_type == 'opportunity')
			$note->parent_table = 'crm_opportunities';
		$note->parent_id = $entity_id;
		$note->save($link);

		http_redirect($_SESSION['bc']->getCurrent());
	}

}
?>