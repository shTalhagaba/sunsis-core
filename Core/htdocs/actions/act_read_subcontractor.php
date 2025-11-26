<?php
class read_subcontractor implements IAction
{
	public function execute(PDO $link)
	{
		// Validate data entry
		$id = isset($_GET['id']) ? $_GET['id'] : '';
		
		if($id == '' || !is_numeric($id))
		{
			throw new Exception("You must specify a numeric id in the querystring");
		}
	
		$vo = SubContractor::loadFromDatabase($link, $id);
		$isSafeToDelete = $vo->isSafeToDelete($link);
		
		// Load categories of organisation
		$lookup_org_type = "SELECT id, org_type FROM lookup_org_type ORDER BY id;";
		$lookup_org_type = DAO::getLookupTable($link, $lookup_org_type);
		
		// Page title
		if($vo->id == 0)
		{
			$page_title = "New Sub Contractor";
		}
		elseif(strlen($vo->trading_name) > 50)
		{
			$page_title = substr($vo->trading_name, 0, 50).'...';
		}
		else
		{
			$page_title = $vo->trading_name;
		}
		
		$type_checkboxes = "SELECT id, CONCAT(id, ' - ', org_type), null FROM lookup_org_type ORDER BY id;";
		$type_checkboxes = DAO::getResultset($link, $type_checkboxes);
		
		$vo3 = ViewOrganisationLearners::getInstance($link, $id);
		$vo3->refresh($link, $_REQUEST);
		
		$vo5 = ViewOrganisationOtherLearners::getInstance($link, $id);
		$vo5->refresh($link, $_REQUEST);
		
		$vo4 = ViewEmployerTrainingRecords::getInstance($link, $id);
		$vo4->refresh($link, $_REQUEST);

		$data = $vo4->getStats($link);
		
		$locations = ViewOrganisationLocations::getInstance($link, $id);
		$locations->refresh($link, $_REQUEST);
				
		// Presentation
		include('tpl_read_subcontractor.php');
	}

	private function renderLocations(PDO $link, SubContractor $vo)
	{
		$locations = $vo->getLocations($link);
		if(count($locations) > 0)
		{
			echo '<table class="resultset" border="0" cellpadding="6" cellspacing="0" style="margin-left:10px">';
			echo '<tr><th>&nbsp;</th><th>Name</th><th>Locality</th><th>Town</th><th>County</th><th>Telephone</th></tr>';
			
			/* @var $loc Location */
			foreach($locations as $loc)
			{
				echo HTML::viewrow_opening_tag('do.php?_action=read_location&id=' . $loc->id);
				echo '<td><a href="do.php?_action=read_location&id=' . $loc->id . '"><img src="/images/building-icon.png" border="0" /></a></td>';
				echo '<td>' . HTML::cell($loc->full_name) . '</td>';
				echo '<td>' . HTML::cell($loc->address_line_2) . '</td>';
				echo '<td>' . HTML::cell($loc->address_line_3) . '</td>';
				echo '<td>' . HTML::cell($loc->address_line_4) . '</td>';
				echo '<td>' . HTML::cell($loc->telephone) . '</td>';
				echo '</tr>';
			}
			
			echo '</table>';
		}
		else
		{
			echo '<p class="sectionDescription">None entered.</p>';
		}		
	}	
	
	
	private function renderPersonnel(PDO $link, SubContractor $vo)
	{
		$personnel = $vo->getPersonnel($link);
		if(count($personnel) > 0)
		{
			echo '<table class="resultset" border="0" cellpadding="6" cellspacing="0" style="margin-left:10px">';
			echo '<tr><th>&nbsp;</th><th>Surname</th><th>Firstnames</th><th>Telephone</th><th>Role</th></tr>';
			
			foreach($personnel as $per)
			{
				echo HTML::viewrow_opening_tag('do.php?_action=read_user&username=' . $per->username);
				echo '<td><a href="do.php?_action=read_personnel&id=' . $per->username . '"><img src="/images/blue-person.png" border="0" /></a></td>';
				echo '<td>' . HTML::cell($per->surname) . '</td>';
				echo '<td>' . HTML::cell($per->firstnames) . '</td>';
				echo '<td>' . HTML::cell($per->work_telephone) . '</td>';
				
				$que = "select people_type from lookup_people_type where id='$per->type'";
				$type = trim(DAO::getSingleValue($link, $que));
				
				echo '<td>' . HTML::cell($type) . '</td>';

				
				echo '</tr>';
			}
			
			echo '</table>';
		}
		else
		{
			echo '<p class="sectionDescription">None entered.</p>';
		}	
	}

	private function renderLearners(PDO $link, SubContractor $vo)
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
	
	
	
}
?>