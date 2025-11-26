<?php

include_once("Repository.php");
include_once("config.php");

if(isset($_FILES))
{
	if ($_FILES["file"]["error"] == 0)
	{
		$mimes = array('application/vnd.ms-excel','text/csv');

		createSection("pfrReconciler");
		$target_directory = DATA_ROOT . '/uploads/' . DB_NAME . '/pfrReconciler/';

		//accept the CSV files only
		if(in_array($_FILES['file']['type'],$mimes))
		{
			$pathParts = pathinfo($_FILES["file"]["name"]);
			//$newName = $pathParts['filename'] . '_'.time().'.'.$pathParts['extension'];

			$newName = $pathParts['filename'] . '.'.$pathParts['extension'];

			//upload the file
			//move_uploaded_file($_FILES["file"]["tmp_name"],"upload/" . $newName);
			move_uploaded_file($_FILES["file"]["tmp_name"], $target_directory . $newName);
		}
		else
		{
			die("Please provide the CSV file only");
		}
	}


}


function createSection($section)
{
	$upload_root = DATA_ROOT . '/uploads/' .  DB_NAME . '/' . $section;
	if (!file_exists($upload_root)) {
		mkdir($upload_root);
	}
}
?>