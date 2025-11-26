<?php
class read_workplace implements IAction
{
	public function execute(PDO $link)
	{
		// Validate data entry
		$id = isset($_GET['id']) ? $_GET['id'] : '';
		
		$_SESSION['bc']->add($link, "do.php?_action=read_workplace&id=" . $id, "View Workplace");
		
		if($id == '' || !is_numeric($id))
		{
			throw new Exception("You must specify a numeric id in the querystring");
		}
	
		$vo = Workplace::loadFromDatabase($link, $id);
		$isSafeToDelete = $vo->isSafeToDelete($link);
		
		// Load categories of organisation
		$lookup_org_type = "SELECT id, org_type FROM lookup_org_type ORDER BY id;";
		$lookup_org_type = DAO::getLookupTable($link, $lookup_org_type);
		
		// Page title
		if($vo->id == 0)
		{
			$page_title = "New Workplace";
		}
		elseif(strlen($vo->trading_name) > 50)
		{
			$page_title = substr($vo->legal_name, 0, 50).'...';
		}
		else
		{
			$page_title = $vo->trading_name;
		}
		
		$type_checkboxes = "SELECT id, CONCAT(id, ' - ', org_type), null FROM lookup_org_type ORDER BY id;";
		$type_checkboxes = DAO::getResultset($link, $type_checkboxes);
		
		$view = ViewWorkplaceVisits::getInstance($link, $id);
		$view->refresh($link, $_REQUEST);

		$view2 = ViewCrmNotes::getInstance($link, $id);
		$view2->refresh($link, $_REQUEST);
		
		$locations = ViewOrganisationLocations::getInstance($link, $id);
		$locations->refresh($link, $_REQUEST);
		
		$vo5 = ViewOrganisationOtherLearners::getInstance($link, $id);
		$vo5->refresh($link, $_REQUEST);
		
		if($vo->reason_not_participating>0)
			$reason_not_participating = DAO::getSingleValue($link, "select description from lookup_reason_not_participating where id = $vo->reason_not_participating");
		else
			$reason_not_participating = '';	
		// Presentation

		$emp_id = $id;
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

		$urls2 = Array();
		$directories = Array();
		if(file_exists(DATA_ROOT."/uploads/$db/employers/$emp_id"))
		{
			$TrackDir=opendir(DATA_ROOT."/uploads/$db/employers/$emp_id");
			$n2 = 0;
			$directories = Array();
			$sections = "<select name='section' id='sections'><option value=''></option>";
			$html2 = '<table class="resultset" border="0" cellspacing="0" cellpadding="6"><thead><tr>';
			if($_SESSION['user']->isAdmin())
				$html2 .= "<th>Delete</th>";
			$html2 .= '<th>Filename</th><th>Size</th><th>Upload Date</th></tr></thead>';
			while ($file = readdir($TrackDir))
			{
				$full = DATA_ROOT."/uploads/$db/employers/$emp_id/$file";
				if(!is_dir($full))
				{
					if ($file != "." && $file != ".." && $file!='admin')
					{
						$n2++;
						if(isset($urls2[$n2]))
						{
							$urls2[$no] .=  '<a href="do.php?_action=downloader&path=employers/' . $emp_id . '/' . "&f=" . rawurlencode($file) . '"><br>'  .$file . '</a>';
							$href2 = "do.php?_action=delete_file&path=employers/" . $emp_id . '/' . "&f=" . rawurlencode($file) . "&redirect=" . rawurlencode('do.php?_action=read_employer&id='.$emp_id);
							$html2 .= "<tr>";
							$html2 .= "<td><a href=$href2><img src='/images/delete.gif'></img></a></td>";
							$html2 .= "<td>" . $urls2[$n2] . "</td></tr>";
							die($urls2[$n2]);
						}
						else
						{
							$urls2[$n2] = '<a href="do.php?_action=downloader&path=employers/' . $emp_id . '/' . "&f=" . rawurlencode($file) . '">' .$file . '</a>';
							$href2 = "do.php?_action=delete_file&path=employers/" . $emp_id . '/' . "&f=" . rawurlencode($file) . "&redirect=" . rawurlencode('do.php?_action=read_employer&id='.$emp_id);;
							$href3 = 'if(confirm("Are you sure?"))window.location.href="' . $href2 . '"';
							$html2 .= "<tr>";
							if($_SESSION['user']->isAdmin())
								$html2 .= "<td align=center><img style='cursor: pointer' onclick='$href3' src='/images/delete.gif'></img></td>";
							$html2 .= "<td>" . $urls2[$n2] . "</td><td align=right>". $this->format_size(filesize($full)) ."</td><td>" . date("d F Y H:i:s", filemtime($full)) . "</td></tr>";
						}
					}
				}
				else
				{
					if($file!="." && $file!=".." && $file!=".svn" && (substr($file,0,8)=="section_"))
					{
						$directories[] = $file;
						$sections .= "<option value='" . substr($file,8) . "'>" . substr($file,8) . "</option>";
					}
				}
			}
			$sections .= "</select>";
			$html2 .= '</table>';

			closedir($TrackDir);
		}

		foreach($directories as $directory)
		{
			if(substr($directory,0,8)=="section_")
			{
				$TrackDir=opendir(DATA_ROOT."/uploads/$db/$directory");
				//$sections = "<select name='section' id='sections'><option value=''></option>";
				$html2 .= "<br><h3>Section:" . substr($directory,8) . "</h3>";
				$html2 .= '<table class="resultset" border="0" cellspacing="0" cellpadding="6"><thead>';
				if($_SESSION['user']->isAdmin())
					$html2 .= "<th>Delete</th>";
				$html2 .= '<th>Filename</th><th>Size</th><th>Upload Date</th></thead>';
				while ($file = readdir($TrackDir))
				{
					$full = DATA_ROOT."/uploads/$db/$directory/$file";
					if(!is_dir($full))
					{
						if ($file != "." && $file != ".." && $file!='admin')
						{
							$n2++;
							if(isset($urls2[$n2]))
							{
								$urls2[$no] .=  '<a href="do.php?_action=downloader&path=' . $directory . "&f=" . rawurlencode($file) . '"><br>' . $n2 . ". " .$file . '</a>';
								$href2 = "do.php?_action=delete_file&path=" . $directory . "&f=" . rawurlencode($file) . "&redirect=" . rawurlencode('do.php?_action=read_employer&id='.$emp_id);;
								$html2 .= "<tr><td><a href=$href2><img src='/images/delete.gif'></img></a></td><td>" . $urls2[$n2] . "</td></tr>";
								die($urls2[$n2]);
							}
							else
							{
								//$path = DATA_ROOT.'/uploads/' . $db . '/' . $directory . '/';
								$urls2[$n2] = '<a href="do.php?_action=downloader&path=' . rawurlencode($directory) . "&f=" . rawurlencode($file) . '">'  . $file . '</a>';
								$href2 = "do.php?_action=delete_file&path=" . rawurlencode($directory) . "&f=" . rawurlencode($file) . "&redirect=" . rawurlencode('do.php?_action=read_employer&id='.$emp_id);
								$href3 = 'if(confirm("Are you sure?"))window.location.href="' . $href2 . '"';
								$html2 .= "<tr>";
								if($_SESSION['user']->isAdmin())
									$html2 .= "<td align=center><img style='cursor: pointer' onclick='$href3' src='/images/delete.gif'></img></td>";
								$html2 .= "<td>" . $urls2[$n2] . "</td><td align=right>". $this->format_size(filesize($full)) ."</td><td>" . date("d F Y H:i:s", filemtime($full)) . "</td></tr>";
							}
						}
					}
				}
				$html2 .= '</table>';
				closedir($TrackDir);
			}
		}


		include('tpl_read_workplace.php');
	}

	
	private function renderLearners(PDO $link, TrainingProvider $vo)
	{
		$personnel = $vo->getLearners($link);
		if(count($personnel) > 0)
		{
			echo '<table class="resultset" border="0" cellpadding="6" cellspacing="0" style="margin-left:10px">';
			echo '<tr><th>&nbsp;</th><th>Surname</th><th>Firstnames</th><th>Telephone</th></tr>';
			
			foreach($personnel as $per)
			{
				echo HTML::viewrow_opening_tag('do.php?_action=read_user&username=' . $per->username);
				echo '<td><a href="do.php?_action=read_personnel&id=' . $per->username . '"><img src="/images/blue-person.png" border="0" /></a></td>';
				echo '<td>' . HTML::cell($per->surname) . '</td>';
				echo '<td>' . HTML::cell($per->firstnames) . '</td>';
				echo '<td>' . HTML::cell($per->work_telephone) . '</td>';
				echo '</tr>';
			}
			
			echo '</table>';
		}
		else
		{
			echo '<p class="sectionDescription">None entered.</p>';
		}	
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
	
}
?>