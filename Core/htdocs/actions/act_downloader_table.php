<?php
/**
 * Created By: Perspective Ltd.
 * User: Richard Elmes
 * Date: 10/08/12
 * Time: 14:13
 *
 * This needs a bit of data validation / cleansing to ensure its safe
 */
class downloader_table implements IAction
{
	public function execute(PDO $link)	{

		$filename = "table_export.csv";
		if ( isset($_REQUEST['csv_name']) && $_REQUEST['csv_name'] != "" ) {
			$filename = $_REQUEST['csv_name'].".csv";
		}
		header("Content-type: application/octet-stream");
		header("Content-Disposition: attachment; filename=\"".$filename."\"");
		$data = preg_replace('/\|\|/', "\n", $_REQUEST['csv_text']);
		echo $data;
		exit;
	}
}
?>