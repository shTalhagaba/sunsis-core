<?php
class save_manager_signature implements IUnauthenticatedAction
{
	public function execute(PDO $link)
	{
		$id = isset($_REQUEST['crm_contact_id'])?$_REQUEST['crm_contact_id']:'';
		$signature = isset($_REQUEST['m_sign'])?$_REQUEST['m_sign']:'';

		if($signature == '')
			throw new Exception('Please provide your signature');

		DAO::execute($link, "UPDATE organisation_contact SET organisation_contact.signature = '{$signature}' WHERE organisation_contact.contact_id = '{$id}'");

		if(IS_AJAX)
		{
			header("Content-Type: text/plain");
			echo $id;
		}
		else
		{
			throw new UnauthorizedException();
		}
	}
}