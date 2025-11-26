<?php
class save_contract implements IAction
{

	public function execute(PDO $link)
	{
		$vo = new Contract();
		$vo->populate($_POST);


		//		if($vo->contract_year>2008 && DB_NAME!='am_sunesis' && DB_NAME!='ams')
		//			throw new Exception("Funding module for 2009/10 is under development");

		DAO::transaction_start($link);
		try {
			// Check authorisation for editing this beneficiary
			$acl = ACL::loadFromDatabase($link, 'trainingrecord', $vo->id);
			if ($_SESSION['user']->type != User::TYPE_MANAGER) {
				if (!$acl->isAuthorised($_SESSION['user'], 'write')) {
					throw new UnauthorizedException();
				}
			}

			if ($vo->id == '') {
				// Set default privileges for new widget (these can always be altered below)
				//	$acl->appendIdentities('read', '*/'.$_SESSION['user']->org_short_name);
				//	$acl->appendIdentities('write', $_SESSION['user']->getFullyQualifiedName());
			}

			if (is_array($vo->active)) {
				if ($vo->active[0] === '' || $vo->active[0] === null) {
					$vo->active = 0;
				} else {
					$vo->active = $vo->active[0];
				}
			} else {
				// already a scalar (int/string), just normalize it
				$vo->active = (int) $vo->active;
			}
			$vo->save($link);

			/*	$acl->resource_id = $vo->id;
			$acl->appendIdentities('read', $acl->readACLFormField($_POST, 'acl_read'));
			$acl->removeIdentities('read', $acl->readACLFormField($_POST, 'acl_read_not'));
			$acl->appendIdentities('write', $acl->readACLFormField($_POST, 'acl_write'));
			$acl->removeIdentities('write', $acl->readACLFormField($_POST, 'acl_write_not'));			
			$acl->save($link); */

			DAO::transaction_commit($link);
		} catch (Exception $e) {
			DAO::transaction_rollback($link, $e);
			throw new WrappedException($e);
		}

		if (IS_AJAX) {
			header("Content-Type: text/plain");
			echo $vo->id;
		} else {
			http_redirect('do.php?_action=view_contracts');
		}
	}
}
