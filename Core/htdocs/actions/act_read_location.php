<?php
class read_location implements IAction
{
	public function execute(PDO $link)
	{
		// Validate data entry
		$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';

		$_SESSION['bc']->add($link, "do.php?_action=read_location&id=" . $id, "Read Location");
		
		if($id == '' || !is_numeric($id))
		{
			throw new Exception("You must specify a numeric id in the querystring");
		}

		$emp_id = DAO::getSingleValue($link, "SELECT organisations_id FROM locations WHERE id = " . $id);
		$section = isset($_REQUEST['section']) ? basename(trim($_REQUEST['section'])) : '';
		$subaction = isset($_REQUEST['subaction']) ? basename(trim($_REQUEST['subaction'])) : '';
		if (preg_match('/[^A-Za-z0-9 \\-_]/', $section)) {
			throw new Exception("Invalid character in section title '".$section."'");
		}
		$section_options = $this->buildSectionDropDownOptions($emp_id, $id);
		switch($subaction)
		{
			case 'createsection':
				$this->createSection($section, $emp_id, $id);
				break;

			case 'deletesection':
				$this->deleteSection($section, $emp_id, $id);
				exit;

			default:
				break;
		}

		// Create value object
		$vo = Location::loadFromDatabase($link, $id);
		$isSafeToDelete = $vo->isSafeToDelete($link);
	
		// Create organisation value object
		$o_vo = Organisation::loadFromDatabase($link, $vo->organisations_id); /* @var $o_vo Organisation */
		
		// Create Address presentation helper
		$bs7666 = new Address();
		$bs7666->set($vo);

		// Preparation for File Repository Listing
		$db = DB_NAME;

		// Create if does not exists
		if(!file_exists(DATA_ROOT."/uploads/$db"))
		{
			mkdir(DATA_ROOT."/uploads/$db");
		}
		if(!file_exists(DATA_ROOT."/uploads/$db/employers"))
		{
			mkdir(DATA_ROOT."/uploads/".DB_NAME."/employers");
		}
		if(!file_exists(DATA_ROOT."/uploads/$db/employers/$emp_id"))
		{
			mkdir(DATA_ROOT."/uploads/".DB_NAME."/employers/".$emp_id);
		}
		if(!file_exists(DATA_ROOT."/uploads/$db/employers/$emp_id/locations"))
		{
			mkdir(DATA_ROOT."/uploads/".DB_NAME."/employers/$emp_id/locations");
		}
		if(!file_exists(DATA_ROOT."/uploads/$db/employers/$emp_id/locations/$id"))
		{
			mkdir(DATA_ROOT."/uploads/".DB_NAME."/employers/$emp_id/locations/$id");
		}


		$show_file_repository = true;
		if($section != '')
			$html2 = $show_file_repository ? $this->getFileDownloads($emp_id, $id, $section):"";
		else
			$html2 = $show_file_repository ? $this->getFileDownloads($emp_id, $id):"";

		// Presentation
		include('tpl_read_location.php');
	}

	private function _renderUserSummary(PDO $link, Location $vo)
	{
		$sql = <<<SQL
SELECT
	users.type,
	COUNT(*) AS `count`
FROM
	users
WHERE
	users.employer_location_id={$vo->id}
SQL;
		$rows = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);

		echo '<h4>User Accounts</h4>';
		echo '<table class="resultset" cellpadding="4" cellspacing="0" style="margin-left:10px">';
		echo '<tr><th>Account Type</th><th>Count</th></tr>';
		foreach ($rows as $row) {
			echo '<tr>';
			echo '<td>', User::getTypeAsString($row['type']), '</td>';
			echo '<td align="right">', $row['count'], '</td>';
			echo '</tr>';
		}
		echo '</table>';
	}

	private function _renderRelatedRecordsSummary(PDO $link, Location $vo)
	{
		$users = DAO::getSingleValue($link, "SELECT COUNT(*) FROM users WHERE users.employer_location_id=" . $vo->id);
		$trs = DAO::getSingleValue($link, "SELECT COUNT(*) FROM tr WHERE provider_location_id=" . $vo->id . " OR employer_location_id=" . $vo->id);
		$lessons = DAO::getSingleValue($link, "SELECT COUNT(*) FROM lessons WHERE location = " . $vo->id);
		if(SystemConfig::getEntityValue($link, 'module_recruitment_v2'))
			$vacancies = DAO::getSingleValue($link, "SELECT COUNT(*) FROM vacancies WHERE location_id = " . $vo->id);
		else
			$vacancies = DAO::getSingleValue($link, "SELECT COUNT(*) FROM vacancies WHERE location = " . $vo->id);
		$contracts = DAO::getSingleValue($link, "SELECT COUNT(*) FROM contracts WHERE contract_location = " . $vo->id);
		$hs = DAO::getSingleValue($link, "SELECT COUNT(*) FROM health_safety WHERE location_id = " . $vo->id);
		$cqd = DAO::getSingleValue($link, "SELECT COUNT(*) FROM course_qualifications_dates WHERE location_id = " . $vo->id);
		$calendarEvents = DAO::getSingleValue($link, "SELECT COUNT(*) FROM calendar_event WHERE location = " . $vo->id);

		echo '<table class="resultset" cellpadding="4" cellspacing="0" style="margin-left:10px">';
		echo '<tr><th>Record</th><th>Count</th></tr>';
		echo '<tr><td>User Accounts</td><td align="right">', $users ,'</td></tr>';
		echo '<tr><td>Training Records</td><td align="right">', $trs ,'</td></tr>';
		echo '<tr><td>Lessons</td><td align="right">', $lessons ,'</td></tr>';
		echo '<tr><td>Vacancies</td><td align="right">', $vacancies ,'</td></tr>';
		echo '<tr><td>Contracts</td><td align="right">', $contracts ,'</td></tr>';
		echo '<tr><td>Course Qualifications Dates</td><td align="right">', $cqd ,'</td></tr>';
		echo '<tr><td>Calendar Events</td><td align="right">', $calendarEvents ,'</td></tr>';
		echo '<tr><td>Health &amp; Safety</td><td align="right">', $hs ,'</td></tr>';
		echo '</table>';
	}

	private function getFileDownloads($emp_id, $loc_id, $section = null)
	{
		$location_dir = DATA_ROOT."/uploads/".DB_NAME."/employers/$emp_id/locations/".$loc_id;
		if(!is_null($section))
			$location_dir = $location_dir.'/'.$section;
		$files = Repository::readDirectory($location_dir);
		if(count($files) == 0){
			return "";
		}

		$html = "";

		$html .= <<<HEREDOC
<div class="Directory">
<table cellspacing="0" style="table-layout:fixed; width:570">
<col width="310"/><col width="70"/><col width="170"/>
<tr>
	<th>Filename</th><th>Size</th><th>Upload Date</th><th>&nbsp;</th>
</tr>
HEREDOC;

		/* @var $f RepositoryFile */
		foreach($files as $f)
		{
			if($f->isDir()){
				continue;
			}
			$html .= "<tr>\r\n";
			$html .= '<td align="left" style="cursor:pointer;word-wrap:break-word;" onclick="downloadFile(\''.$f->getRelativePath().'\');" title="Download file">'.htmlspecialchars((string)$f->getName()).'</td>';
			$html .= '<td align="right" style="font-family:monospace" width="70">'.Repository::formatFileSize($f->getSize()).'</td>';
			$html .= '<td align="right" style="font-family:monospace" width="170">'.date("d/m/Y H:i:s", $f->getModifiedTime()).'</td>';
			if($_SESSION['user']->isAdmin())
			{
				$html .= '<td align="right" width="20"><img src="/images/trash_can.png" title="Delete file" onclick="deleteFile(\''.$f->getRelativePath().'\');" style="cursor:pointer"/></td>';
			}
			else
			{
				$html .= '<td align="right" width="20">&nbsp;</td>';
			}
			$html .= "\r\n</tr>\r\n";
		}

		$html .= "</table>\r\n";
		$html .= "</div>\r\n";

		return $html;
	}


	private function format_size($size)
	{
		$sizes = array(" B", " KB", " MB", " GB", " TB", " PB", " EB", " ZB", " YB");
		if($size == 0)
		{
			return('n/a');
		}
		else
		{
			$i = 0;
			$s = $size;
			while($size > 1024){
				$size = $size/1024;
				$i++;
			}
			return sprintf("%.1f" . $sizes[$i], $size);
			//return sprintf("%.1f",($size/pow(1024, ($i = floor(log($size, 1024))))) . $sizes[$i]);
		}
	}

	private function isSectionEmpty($section, $emp_id, $loc_id)
	{
		$db = DB_NAME;
		$path = DATA_ROOT."/uploads/$db/employers/$emp_id/locations/$loc_id";
		if($section != '')
			$path = DATA_ROOT."/uploads/$db/employers/$emp_id/locations/$loc_id"."/".basename($section);

		$files = Repository::readDirectory($path);
		return count($files) == 0;
	}


	private function buildSectionDropDownOptions($emp_id, $id)
	{
		$db = DB_NAME;
		$sections = array(array("")); // default section
		$files = Repository::readDirectory(DATA_ROOT."/uploads/$db/employers/$emp_id/locations/$id");
		foreach ($files as $f) {
			if ($f->isDir()) {
				$sections[] = array($f->getName()); // additional section
			}
		}

//		var_dump($sections);
		return $sections;
	}

	private function createSection($section, $emp_id, $loc_id)
	{
		$db = DB_NAME;
		if (!$section) {
			return;
		}
		$path = DATA_ROOT."/uploads/$db/employers/$emp_id/locations/$loc_id";
		if($section != '')
			$path = DATA_ROOT."/uploads/$db/employers/$emp_id/locations/$loc_id"."/".basename($section);

		if (!file_exists($path)) {
			mkdir($path, 0777, true);
		}
	}

	private function deleteSection($section, $emp_id, $loc_id)
	{
		$db = DB_NAME;
		if (!$section) {
			return;
		}

		$path = DATA_ROOT."/uploads/$db/employers/$emp_id/locations/$loc_id";
		if($section != '')
			$path = DATA_ROOT."/uploads/$db/employers/$emp_id/locations/$loc_id"."/".basename($section);

		if (!is_dir($path)) {
			return;
		}

		$files = Repository::readDirectory($path);
		if (count($files) > 0) {
			return;
		}

		rmdir($path);
	}


}
?>