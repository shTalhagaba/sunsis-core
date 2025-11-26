<?php
class edit_personnel implements IAction
{
	public function execute(PDO $link)
	{
		// Validate data entry
		$per_id = isset($_GET['id']) ? $_GET['id'] : '';
		$org_id = isset($_GET['organisations_id']) ? $_GET['organisations_id'] : '';
		
		if( ($org_id == '') && ($per_id == '') )
		{
			throw new Exception("Either querystring argument id or organisations_id (or both) must be specified");
		}
		
		if($per_id !== '' && !is_numeric($per_id))
		{
			throw new Exception("Querystring argument id must be numeric");
		}

		if($org_id !== '' && !is_numeric($org_id))
		{
			throw new Exception("Querystring argument organisations_id must be numeric");
		}

		if($per_id == '')
		{
			// New record
			$p_vo = new User($link);
			$p_vo->organisations_id = $org_id;
		}
		else
		{
			$p_vo = User::find($link, $per_id);
		}
	
		// Create organisation value object
		$o_vo = OrganisationDAO::find($link, $p_vo->organisations_id); /* @var $o_vo Organisation */
		
		// Create Address presentation helper
		$bs7666 = new Address();
		$bs7666->set($p_vo);
		
		if($p_vo->id == 0)
		{
			$js_discard_changes = "window.location.replace('do.php?_action=read_provider&id=" . $p_vo->organisations_id . "');";
		}
		else
		{
			$js_discard_changes = "window.location.replace('do.php?_action=read_personnel&id=" . $p_vo->id . "');";
		}
		
		
		// Presentation
		include('tpl_edit_personnel.php');
	}
}
?>