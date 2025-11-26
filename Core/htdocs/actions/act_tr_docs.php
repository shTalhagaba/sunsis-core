<?php
/**
 * learner_doc
 * @author: richard elmes
 * @copyright: perspective ltd 2011
 */
class tr_docs implements IAction
{
	public function execute(PDO $link) {

		// Validate data entry
		$filename = isset($_REQUEST['filename']) ? $_REQUEST['filename'] : '';
		$tr_id = isset($_REQUEST['tr_id']) ? $_REQUEST['tr_id'] : '';

		$username = $_SESSION['user']->username;

		if ( '' == $filename || '' == $tr_id) {
			throw new Exception('Learner Documents: We cannot understand your request!');
		}

		// add the extension onto the filename
		$filename .= '.docx';

		// Create Value Object
		$tr_obj = TrainingRecord::loadFromDatabase($link, $tr_id);
		if( is_null($tr_obj) ) {
			throw new Exception("No training record found with  id given '$tr_id'");
		}

		$target_path = DATA_ROOT."/uploads/".DB_NAME;
		if((file_exists($target_path."/".$username."/prior_learning"))) {
			// clean down the directory
			$this->delete_directory($target_path."/".$username."/prior_learning");
		}
		// set the locations for files
		$file_manager = new Docx($filename, DATA_ROOT.'/uploads/'.DB_NAME.'/prior_learning/', DATA_ROOT."/uploads/".DB_NAME."/".$username."/prior_learning");
		$file_manager->extract_docx();

		$que = "SELECT title FROM frameworks INNER JOIN courses_tr ON courses_tr.`framework_id` = frameworks.id AND courses_tr.`tr_id` = '$tr_id';";
		$framework_title = trim(DAO::getSingleValue($link, $que));

		// populate the document
		// re - these all look generic
		// ---
		$file_manager->update_docx('Placeholder_Student', $tr_obj->firstnames.' '.$tr_obj->surname);
		$file_manager->update_docx('Placeholder_Framework', $framework_title);
		$file_manager->update_docx('Placeholder_StartDate', Date::toShort($tr_obj->start_date));


		// write the updated document.xml to the temporary location.
		$file_manager->write_docx();
		// re-archive the temporary location files and place in write location
		$final_file = $file_manager->bundle_docx();
		// remove any temporary files created during the manipulations
		$file_manager->cleanup_docx();

		// relmes - updated to use the filename generated from the docx library
		http_redirect("do.php?_action=downloader&path=".$username."/prior_learning&f=".$final_file);

	}
	private function delete_directory($path = '') {
		if ( '' != $path ) {
			if ( $handle = @opendir($path) ) {
				$array = array();
				while (false !== ($file = readdir($handle))) {
					if ($file != "." && $file != "..") {
						if(is_dir($path."/".$file)) {
							if(!@rmdir($path."/".$file)) {
								$this->delete_directory($path."/".$file.'/'); // Not empty? Delete the files inside it
							}
						}
						else {
							@unlink($path."/".$file);
						}
					}
				}
				closedir($handle);
				@rmdir($path);
			}
		}
	}
}
?>

