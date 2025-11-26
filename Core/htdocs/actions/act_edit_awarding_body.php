<?php
class edit_awarding_body implements IAction
{
	public function execute(PDO $link)
	{
		$subaction = isset($_REQUEST['subaction']) ? $_REQUEST['subaction'] : '';

		if($subaction == 'ajax_call')
		{
			$registration_number = isset($_REQUEST['registration_number']) ? $_REQUEST['registration_number'] : '';
			if($registration_number == '')
				echo 'nothing found';
			else
			{
				$awarding_body = DAO::getResultset($link, "SELECT * FROM central.lookup_awarding_bodies WHERE registration_number = '" . $registration_number . "'", DAO::FETCH_ASSOC);
				$awarding_body = $awarding_body[0];
				echo json_encode($awarding_body);
			}
			exit;
		}

		// Validate data entry
		$id = isset($_GET['id']) ? $_GET['id'] : '';

		$_SESSION['bc']->add($link, "do.php?_action=edit_awarding_body&id=" . $id, "Add/ Edit Awarding Body");
		
		if($id !== '' && !is_numeric($id))
		{
			throw new Exception("You must specify a numeric id in the querystring in order to edit an organisation");
		}

		if($id == '')
		{
			// New record
			$vo = new AwardingBody();
		}
		else
		{
			$vo = AwardingBody::loadFromDatabase($link, $id);
		}
		
		$lookup_awarding_body = DAO::getResultset($link, "SELECT registration_number, CONCAT(legal_name, ' - ', acronym), NULL FROM central.lookup_awarding_bodies ORDER BY legal_name;");

		// Page title
		if($vo->id == 0)
		{
			$page_title = "New Awarding Body" ;
		}
		elseif(strlen($vo->trading_name) > 50)
		{
			$page_title = substr($vo->trading_name, 0, 50).'...';
		}
		else
		{
			$page_title = $vo->trading_name;
		}
		
		// Presentation
		include('tpl_edit_awarding_body.php');
	}
}
?>