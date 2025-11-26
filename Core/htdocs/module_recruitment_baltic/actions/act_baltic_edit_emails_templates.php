<?php
class baltic_edit_emails_templates implements IAction
{
	public function execute(PDO $link)
	{
		// Validate data entry
		$id = isset($_GET['id']) ? $_GET['id'] : '';

		$_SESSION['bc']->add($link, "do.php?_action=edit_emails_templates&id=" . $id, "Edit Emails Templates");

		if($id !== '' && !is_numeric($id))
		{
			throw new Exception("You must specify a numeric id in the querystring");
		}

		if($id == '')
		{
			// New record
			$vo = new EmailTemplate();
		}
		else
		{
			$vo = EmailTemplate::loadFromDatabase($link, $id);
		}

		// Page title
		$page_title = "Emails Templates";


		include('tpl_baltic_edit_emails_templates.php');
	}
}
?>