<?php
class files implements IAction
{

	public function execute(PDO $link)
	{

		if( !array_key_exists('HTTP_REFERER', $_SERVER) ) exit('No direct script access allowed');

		$root = Repository::getRoot();
		if( !$root ) exit("ERROR: Root filesystem directory not set in jqueryFileTree.php");

		$postDir = rawurldecode($root.(isset($_POST['dir']) ? $_POST['dir'] : null ));

		// set checkbox if multiSelect set to true
		$checkbox = ( isset($_POST['multiSelect']) && $_POST['multiSelect'] == 'true' ) ? "<input type='checkbox' />" : null;
		$onlyFolders = ( isset($_POST['onlyFolders']) && $_POST['onlyFolders'] == 'true' ) ? true : false;
		$onlyFiles = ( isset($_POST['onlyFiles']) && $_POST['onlyFiles'] == 'true' ) ? true : false;

		if( file_exists($postDir) )
		{

			//$files		= scandir($postDir);
			$files		= $this->sort_dir_files($postDir);
			$returnDir	= substr($postDir, strlen($root));

			//natcasesort($files);

			if(count($files) == 2)
			{
				echo "<ul class='jqueryFileTree'><li><i>No files to show</i></li></ul>";
			}
			else
			{
				if( count($files) > 2 )
				{ // The 2 accounts for . and ..

					echo "<ul class='jqueryFileTree'>";

					foreach( $files as $file )
					{
						$htmlRel	= htmlentities($returnDir . $file,ENT_QUOTES);
						$htmlName	= htmlentities($file);
						$ext		= preg_replace('/^.*\./', '', $file);

						if( file_exists($postDir . $file) && $file != '.' && $file != '..' )
						{
							if( is_dir($postDir . $file) && (!$onlyFiles || $onlyFolders) )
								echo "<li class='directory collapsed'>{$checkbox}<a rel='" . $htmlRel. "/'>" . $htmlName . "</a></li>";
							else if (!$onlyFolders || $onlyFiles)
								echo "<li class='file ext_{$ext}'>{$checkbox}<a href='do.php?_action=downloader&f=" . $htmlRel . "'>" . $htmlName . "</a></li>";
						}
					}

					echo "</ul>";
				}
			}
		}
	}

	function sort_dir_files($dir)
	{
		$sortedData = array();
		foreach(scandir($dir) as $file)
		{
			if(is_file($dir.'/'.$file))
				array_push($sortedData, $file);
			else
				array_unshift($sortedData, $file);
		}
		return $sortedData;
	}

}