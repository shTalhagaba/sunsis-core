<?php
class read_module implements IAction
{
	public function execute(PDO $link)
	{
		// Validate data entry
		$id = isset($_GET['id']) ? $_GET['id'] : '';

		$_SESSION['bc']->add($link, "do.php?_action=read_module&id=" . $id, "Read Module");

		if($id !== '' && !is_numeric($id))
		{
			throw new Exception("You must specify a numeric id in the querystring");
		}

		$acl = ACL::loadFromDatabase($link, 'contract', $id); /* @var $acl ACL */


		$vo = Module::loadFromDatabase($link, $id);

		// Dropdown arrays
		$provider = DAO::getSingleValue($link, "SELECT legal_name, null FROM organisations WHERE id = '{$vo->provider_id}';");

		$lessonsAttached = DAO::getSingleValue($link, "SELECT COUNT(*) FROM lessons WHERE module = " . $id);

		include('tpl_read_module.php');
	}
}
?>