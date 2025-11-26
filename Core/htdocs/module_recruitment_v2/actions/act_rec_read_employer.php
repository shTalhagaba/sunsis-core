<?php
class rec_read_employer implements IAction
{
	public function execute(PDO $link)
	{
		$id = isset($_GET['id']) ? $_GET['id'] : '';
		$emp_group_id = isset($_GET['emp_group_id']) ? $_GET['emp_group_id'] : '';
		$selected_tab = isset($_REQUEST['selected_tab'])?$_REQUEST['selected_tab']:'tab1';

		if(DB_NAME=="am_demo")
			http_redirect('do.php?_action=read_employer_v3&id='.$_REQUEST['id']);

		$_SESSION['bc']->add($link, "do.php?_action=rec_read_employer&id=" . $id . "&selected_tab=" . $selected_tab, "View Employer");

		if($id == '' || !is_numeric($id))
		{
			throw new Exception("You must specify a numeric id in the querystring");
		}

		$vo = Employer::loadFromDatabase($link, $id);
		$isSafeToDelete = $vo->isSafeToDelete($link);

		$sector = DAO::getSingleValue($link, "SELECT description FROM lookup_sector_types WHERE id = '$vo->sector'");
		$group_employer = DAO::getSingleValue($link, "SELECT title FROM brands WHERE id = '$vo->manufacturer'");

		$crmNotes = ViewCrmNotes::getInstance($link, $id);
		$crmNotes->refresh($link, $_REQUEST);

		$vacancies = RecViewApprenticeVacancies::getInstance($link, $id);
		$vacancies->refresh($link, $_REQUEST);

		$viewCRMContacts = ViewCRMContacts::getInstance($link, $id);
		$viewCRMContacts->refresh($link, $_REQUEST);

        if(DB_NAME=='am_demo')
        {
            $viewEmployerAgreement = ViewEmployerAgreement::getInstance($link, $id);
            $viewEmployerAgreement->refresh($link, $_REQUEST);
        }
		// Preparation for File Repository Listing
		// Create if does not exists
		if(!file_exists(DATA_ROOT."/uploads/" . DB_NAME))
		{
			mkdir(DATA_ROOT."/uploads/" . DB_NAME);
		}
		if(!file_exists(DATA_ROOT."/uploads/" . DB_NAME . "/employers"))
		{
			mkdir(DATA_ROOT."/uploads/".DB_NAME."/employers");
		}
		if(!file_exists(DATA_ROOT."/uploads/" . DB_NAME . "/employers/" . $vo->id))
		{
			mkdir(DATA_ROOT."/uploads/".DB_NAME."/employers/".$vo->id);
		}

		$tab1 = "";
		$tab2 = "";
		$tab3 = "";
		$tab4 = "";
		$tab5 = "";
		$tab6 = "";
		$tab7 = "";
		$tab8 = "";
        $tab9 = "";
        $tab10 = "";
		if(isset($$selected_tab))
			$$selected_tab = " class='selected' ";
		else
			$tab1 = " class='selected' ";

		// Presentation
        $line_manager = EmployerContacts::loadFromDatabase($link,DAO::getSingleValue($link,"select contact_id from organisation_contact where org_id = '$id'"));

		include('tpl_rec_read_employer.php');
	}

	private function renderLocations(PDO $link, Employer $vo)
	{
		$locations = $vo->getLocations($link);
		if(count($locations) > 0)
		{
			echo '<div align="left"><table class="resultset" border="0" cellspacing="0" cellpadding="10">';
			echo '<tr><th>&nbsp;</th><th>Store Number</th><th>Name</th><th>Address Line 1</th><th>Address Line 2</th><th>Address Line 3</th><th>Address Line 4</th><th>Postcode</th><th>Telephone</th><th>Contact Name</th><th>Contact Email</th><th>Contact Telephone</th><th>Health & Safety</th><th>Compliant</th><th>SFA AreaCostFactor</th><th>EFA AreaCostFactor</th></tr>';

			/* @var $loc Location */
			foreach($locations as $loc)
			{
				echo HTML::viewrow_opening_tag('do.php?_action=read_location&id=' . $loc->id);
				echo '<td><a href="do.php?_action=read_location&id=' . $loc->id . '"><img src="/images/building-icon.png" border="0" /></a></td>';
				echo '<td>' . HTML::cell($loc->lsc_number) . '</td>';
				echo '<td>' . HTML::cell($loc->full_name) . '</td>';
				echo '<td>' . HTML::cell($loc->address_line_1) . '</td>';
				echo '<td>' . HTML::cell($loc->address_line_2) . '</td>';
				echo '<td>' . HTML::cell($loc->address_line_3) . '</td>';
				echo '<td>' . HTML::cell($loc->address_line_4) . '</td>';
				echo '<td>' . HTML::cell($loc->postcode) . '</td>';
				echo '<td>' . HTML::cell($loc->telephone) . '</td>';
				echo '<td>' . HTML::cell($loc->contact_name) . '</td>';
				echo '<td>' . HTML::cell($loc->contact_email) . '</td>';
				echo '<td>' . HTML::cell($loc->contact_telephone) . '</td>';

				$sql = <<<SQL
SELECT
	CASE (SELECT complient FROM health_safety WHERE location_id = locations.id ORDER BY next_assessment DESC LIMIT 1)
		WHEN 1 THEN "<img  src='/images/green-tick.gif' border='0'> </img>"
		WHEN 2 THEN "<img  src='/images/red-cross.gif' border='0'> </img>"
		WHEN 3 THEN "<img  src='/images/warning-17.JPG' border='0'> </img>"
		ELSE "<img src='/images/notstarted.gif' border='0'> </img>"
	END AS `compliant`,

	CASE
		WHEN (SELECT DATEDIFF(next_assessment, CURDATE()) FROM health_safety WHERE location_id = locations.id ORDER BY next_assessment DESC LIMIT 1) >30 THEN "<img  src='/images/green-tick.gif' border='0'> </img>"
		WHEN (SELECT DATEDIFF(next_assessment, CURDATE()) FROM health_safety WHERE location_id = locations.id ORDER BY next_assessment DESC LIMIT 1) <0 THEN "<img  src='/images/red-cross.gif' border='0'> </img>"
		WHEN (SELECT DATEDIFF(next_assessment, CURDATE()) FROM health_safety WHERE location_id = locations.id ORDER BY next_assessment DESC LIMIT 1) >=0 AND (SELECT DATEDIFF(next_assessment, CURDATE()) FROM health_safety WHERE location_id = locations.id ORDER BY next_assessment DESC LIMIT 1) <= 30 THEN "<img  src='/images/warning-17.JPG' border='0'> </img>"
		ELSE "<img src='/images/notstarted.gif' border='0'> </img>"
	END AS `health_and_safety`
FROM
	locations
WHERE
	id = '$loc->id'
GROUP BY locations.id
;
SQL;
				$hs_n_comp = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
				echo isset($hs_n_comp[0]['health_and_safety'])?'<td align="center">' . $hs_n_comp[0]['health_and_safety'] . '</td>':'<td></td>';
				echo isset($hs_n_comp[0]['compliant'])?'<td align="center">' . $hs_n_comp[0]['compliant'] . '</td>':'<td></td>';
				echo '<td align=center>' . DAO::getSingleValue($link, "SELECT SFA_AreaCostFactor FROM central.`201415postcodeareacost` WHERE Postcode = '" . $loc->postcode . "'") . '</td>';
				echo '<td align=center>' . DAO::getSingleValue($link, "SELECT EFA_AreaCostFactor FROM central.`201415postcodeareacost` WHERE Postcode = '" . $loc->postcode . "'") . '</td>';
				echo '</tr>';
			}

			echo '</table></div>';
		}
		else
		{
			echo '<p class="sectionDescription">None entered.</p>';
		}
	}


	private function renderPersonnel(PDO $link, Employer $vo)
	{
		$personnel = $vo->getPersonnel($link);
		if(count($personnel) > 0)
		{
			echo '<div align="left"><table class="resultset" border="0" cellspacing="0" cellpadding="10">';
			echo '<tr><th>&nbsp;</th><th>Firstnames</th><th>Surname</th><th>Work Telephone</th><th>Role</th></tr>';

			foreach($personnel as $per)
			{
				echo HTML::viewrow_opening_tag('do.php?_action=read_user&username=' . $per->username);
				echo '<td><a href="do.php?_action=read_personnel&id=' . $per->username . '"><img src="/images/blue-person.png" border="0" /></a></td>';
				echo '<td>' . HTML::cell($per->firstnames) . '</td>';
				echo '<td>' . HTML::cell($per->surname) . '</td>';
				echo '<td>' . HTML::cell($per->work_telephone) . '</td>';
				$que = "SELECT people_type FROM lookup_people_type WHERE id = '$per->type'";
				$type = trim(DAO::getSingleValue($link, $que));
				echo '<td>' . HTML::cell($type) . '</td>';
				echo '</tr>';
			}
			echo '</table></div>';
		}
		else
		{
			echo '<p class="sectionDescription">None entered.</p>';
		}
	}

	private function renderLearners(PDO $link, Employer $vo)
	{
		$personnel = $vo->getLearners($link);
		if(count($personnel) > 0)
		{
			echo '<div align="left"><table class="resultset" border="0" cellspacing="0" cellpadding="10">';
			echo '<tr><th>&nbsp;</th><th>Firstnames</th><th>Surname</th><th>Date of Birth</th><th>NI</th><th>Home Postcode</th><th>Home Telephone</th><th>Home Mobile</th><th>Work Telephone</th><th>Work Postcode</th><th>Created</th></tr>';

			foreach($personnel AS $per)
			{
				echo HTML::viewrow_opening_tag('do.php?_action=read_user&username=' . $per->username);
				if($per->gender == 'M')
					echo '<td><a href="do.php?_action=read_user&username=' . $per->username . '"><img src="/images/boy-blonde-hair.gif" border="0" /></a></td>';
				elseif($per->gender == 'F')
					echo '<td><a href="do.php?_action=read_user&username=' . $per->username . '"><img src="/images/girl-black-hair.gif" border="0" /></a></td>';
				else
					echo '<td><a href="do.php?_action=read_user&username=' . $per->username . '"><img src="/images/blue-person.gif" border="0" /></a></td>';
				echo '<td>' . HTML::cell($per->firstnames) . '</td>';
				echo '<td>' . HTML::cell($per->surname) . '</td>';
				echo '<td>' . HTML::cell($per->dob) . '</td>';
				echo '<td>' . HTML::cell($per->ni) . '</td>';
				echo '<td>' . HTML::cell($per->home_postcode) . '</td>';
				echo '<td>' . HTML::cell($per->home_telephone) . '</td>';
				echo '<td>' . HTML::cell($per->home_mobile) . '</td>';
				echo '<td>' . HTML::cell($per->work_telephone) . '</td>';
				echo '<td>' . HTML::cell($per->work_postcode) . '</td>';
				echo '<td>' . Date::toShort($per->created) . '</td>';
				echo '</tr>';
			}

			echo '</table></div>';
		}
		else
		{
			echo '<p class="sectionDescription">None entered.</p>';
		}
	}

	private function renderFileRepository(Employer $vo)
	{
		$urls2 = Array();
		$directories = Array();
		$html2 = "";
		if(file_exists(Repository::getRoot() . '/employers/' . $vo->id))
		{
			$TrackDir = opendir(Repository::getRoot() . '/employers/' . $vo->id);
			$n2 = 0;
			$directories = Array();
			$sections = "<select name='section' id='sections'><option value=''></option>";
			$html2 = '<table class="resultset" border="0" cellspacing="0" cellpadding="6"><thead><tr>';
			if($_SESSION['user']->isAdmin())
				$html2 .= "<th>Delete</th>";
			$html2 .= '<th>Filename</th><th>Size</th><th>Upload Date</th></tr></thead>';
			while ($file = readdir($TrackDir))
			{
				$full = Repository::getRoot() . '/employers/' . $vo->id . '/' . $file;
				if(!is_dir($full))
				{
					if ($file != "." && $file != ".." && $file!='admin')
					{
						$n2++;
						if(isset($urls2[$n2]))
						{
							$urls2[$n2] .=  '<a href="do.php?_action=downloader&path=employers/' . $vo->id . '/' . "&f=" . rawurlencode($file) . '"><br>'  .$file . '</a>';
							$href2 = "do.php?_action=delete_file&path=employers/" . $vo->id . '/' . "&f=" . rawurlencode($file) . "&redirect=" . rawurlencode('do.php?_action=rec_read_employer&id='.$vo->id);
							$html2 .= "<tr>";
							$html2 .= "<td><a href=$href2><img src='/images/delete.gif' /></a></td>";
							$html2 .= "<td>" . $urls2[$n2] . "</td></tr>";
							die($urls2[$n2]);
						}
						else
						{
							$urls2[$n2] = '<a href="do.php?_action=downloader&path=employers/' . $vo->id . '/' . "&f=" . rawurlencode($file) . '">' .$file . '</a>';
							$href2 = "do.php?_action=delete_file&path=employers/" . $vo->id . '/' . "&f=" . rawurlencode($file) . "&redirect=" . rawurlencode('do.php?_action=rec_read_employer&id='.$vo->id);
							$href3 = 'if(confirm("Are you sure?"))window.location.href="' . $href2 . '"';
							$html2 .= "<tr>";
							if($_SESSION['user']->isAdmin())
								$html2 .= "<td align=center><img style='cursor: pointer' onclick='$href3' src='/images/delete.gif' /></td>";
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
				$TrackDir=opendir(Repository::getRoot() . '/' . $directory);
				$html2 .= "<br><h3>Section:" . substr($directory,8) . "</h3>";
				$html2 .= '<table class="resultset" border="0" cellspacing="0" cellpadding="6"><thead>';
				if($_SESSION['user']->isAdmin())
					$html2 .= "<th>Delete</th>";
				$html2 .= '<th>Filename</th><th>Size</th><th>Upload Date</th></thead>';
				while ($file = readdir($TrackDir))
				{
					$full = Repository::getRoot() . '/' . $directory . '/' . $file;
					if(!is_dir($full))
					{
						if ($file != "." && $file != ".." && $file!='admin')
						{
							$n2++;
							if(isset($urls2[$n2]))
							{
								$urls2[$no] .=  '<a href="do.php?_action=downloader&path=' . $directory . "&f=" . rawurlencode($file) . '"><br>' . $n2 . ". " .$file . '</a>';
								$href2 = "do.php?_action=delete_file&path=" . $directory . "&f=" . rawurlencode($file) . "&redirect=" . rawurlencode('do.php?_action=rec_read_employer&id='.$vo->id);
								$html2 .= "<tr><td><a href=$href2><img src='/images/delete.gif'></img></a></td><td>" . $urls2[$n2] . "</td></tr>";
								die($urls2[$n2]);
							}
							else
							{
								$urls2[$n2] = '<a href="do.php?_action=downloader&path=' . rawurlencode($directory) . "&f=" . rawurlencode($file) . '">'  . $file . '</a>';
								$href2 = "do.php?_action=delete_file&path=" . rawurlencode($directory) . "&f=" . rawurlencode($file) . "&redirect=" . rawurlencode('do.php?_action=rec_read_employer&id='.$vo->id);
								$href3 = 'if(confirm("Are you sure?"))window.location.href="' . $href2 . '"';
								$html2 .= "<tr>";
								if($_SESSION['user']->isAdmin())
									$html2 .= "<td align=center><img style='cursor: pointer' onclick='$href3' src='/images/delete.gif' /></td>";
								$html2 .= "<td>" . $urls2[$n2] . "</td><td align=right>". $this->format_size(filesize($full)) ."</td><td>" . date("d F Y H:i:s", filemtime($full)) . "</td></tr>";
							}
						}
					}
				}
				$html2 .= '</table>';
				closedir($TrackDir);
			}
		}
		echo $html2;
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