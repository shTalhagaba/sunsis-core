<?php
class delete_record_from_org implements IAction
{
	public function execute(PDO $link)
	{
		switch($_REQUEST['record_type'])
		{
			case 'system_user':
				$message = $this->delete_system_user($link, $_REQUEST);
				break;
			case 'learner':
				$message = $this->delete_learner($link, $_REQUEST);
				break;
			case 'crm_contact':
				$message = $this->delete_crm_contact($link, $_REQUEST);
				break;
			case 'location':
				$message = $this->delete_location($link, $_REQUEST);
				break;
			case 'agreement':
				$message = $this->delete_agreement($link, $_REQUEST);
				break;
			default:
				exit;
		}

		header("Content-Type: text/plain");

		echo $message;
	}

	private function delete_location(PDO $link, $record)
	{
		$record_username = isset($record['record_username']) ? $record['record_username'] : '';
		$record_id = isset($record['record_id']) ? $record['record_id'] : '';

		$message = 'Record is deleted successfully';

		if(trim($record_id) == '')
		{
			$message = 'Missing querystring arguments.';
			return $message;
		}

		if(!$_SESSION['user']->isAdmin() && $_SESSION['user']->type != User::TYPE_ADMIN)
		{
			$message = 'You are not authorised to delete this record.';
			return $message;
		}

		$location = Location::loadFromDatabase($link, $record_id);

		if($location->is_legal_address == 1)
		{
			$message = 'This is the main site/location of this employer, so cannot be deleted.';
			return $message;
		}

		$trs_count = DAO::getSingleValue($link, "SELECT COUNT(*) FROM tr WHERE employer_location_id = '{$location->id}'");
		if($trs_count > 0)
		{
			$message = 'This location has associated training records so it cannot be deleted.';
			return $message;
		}

		$hs_records_count = DAO::getSingleValue($link, "SELECT COUNT(*) FROM health_safety WHERE location_id = '{$location->id}'");
		if($hs_records_count > 0)
		{
			$message = 'This location has associated health and safety records so it cannot be deleted.';
			return $message;
		}

		$location->delete($link);

		return $message;
	}

	private function delete_crm_contact(PDO $link, $record)
	{
		$record_username = isset($record['record_username']) ? $record['record_username'] : '';
		$record_id = isset($record['record_id']) ? $record['record_id'] : '';

		$message = 'Record is deleted successfully';

		if(trim($record_id) == '')
		{
			$message = 'Missing querystring arguments.';
			return $message;
		}

		if(!$_SESSION['user']->isAdmin() && $_SESSION['user']->type != User::TYPE_ADMIN)
		{
			$message = 'You are not authorised to delete this record.';
			return $message;
		}

		$crm_notes = DAO::getSingleValue($link, "SELECT COUNT(*) FROM crm_notes_orgs WHERE org_contact_id = '{$record_id}'");
		if($crm_notes > 0)
		{
			$message = 'This CRM contact has associated CRM notes, so cannot be deleted.';
			return $message;
		}

		$r = OrganisationContact::loadFromDatabase($link, $record_id);
		$r->delete($link);

		return $message;
	}

	private function delete_learner(PDO $link, $record)
	{
		$record_username = isset($record['record_username']) ? $record['record_username'] : '';
		$record_id = isset($record['record_id']) ? $record['record_id'] : '';

		$message = 'Record is deleted successfully';

		if(trim($record_username) == '' && trim($record_id) == '')
		{
			$message = 'Missing querystring arguments.';
			return $message;
		}

		if(!$_SESSION['user']->isAdmin() && $_SESSION['user']->type != User::TYPE_ADMIN)
		{
			$message = 'You are not authorised to delete this record.';
			return $message;
		}

		$trs = DAO::getSingleValue($link, "SELECT COUNT(*) FROM tr WHERE tr.username = '{$record_username}'");
		if($trs > 0)
		{
			$message = 'This learner has '.$trs.' training record(s), so cannot be deleted.';
			return $message;
		}

		$user = User::loadFromDatabaseById($link, $record_id);
		$queries[] = "DELETE FROM users WHERE username='$user->username'";
		foreach($queries as $query)
		{
			DAO::execute($link, $query);
		}

		return $message;
	}

	private function delete_system_user(PDO $link, $record)
	{
		$record_username = isset($record['record_username']) ? $record['record_username'] : '';
		$record_id = isset($record['record_id']) ? $record['record_id'] : '';

		$message = 'Record is deleted successfully';

		if(trim($record_username) == '' && trim($record_id) == '')
		{
			$message = 'Missing querystring arguments.';
			return $message;
		}

		if(!$_SESSION['user']->isAdmin() && $_SESSION['user']->type != User::TYPE_ADMIN)
		{
			$message = 'You are not authorised to delete this record.';
			return $message;
		}

		if($_SESSION['user']->id == $record_id)
		{
			$message = 'You cannot delete your own record.';
			return $message;
		}

		$sql = <<<SQL
SELECT
	COUNT(*)
FROM
	tr
WHERE FIND_IN_SET(36, $record_id)
;	
SQL;
		$trs = DAO::getSingleValue($link, $sql);
		if($trs > 0)
		{
			$message = 'This user has associated training records so cannot be deleted.';
			return $message;
		}

		$user = User::loadFromDatabaseById($link, $record_id);
		$identities = "'".addslashes($user->username)."', '".addslashes($user->getFullyQualifiedName())."'";
		$queries[] = "DELETE FROM users WHERE username='$user->username'";
		$queries[] = "DELETE FROM acl WHERE ident IN ($identities)";
		foreach($queries as $query)
		{
			DAO::execute($link, $query);
		}


		return $message;
	}

    private function delete_agreement(PDO $link, $record)
    {
        $record_username = isset($record['record_username']) ? $record['record_username'] : '';
        $record_id = isset($record['record_id']) ? $record['record_id'] : '';

        $message = 'Record is deleted successfully';

        if(trim($record_username) == '' && trim($record_id) == '')
        {
            $message = 'Missing querystring arguments.';
            return $message;
        }

        if(!$_SESSION['user']->isAdmin() && $_SESSION['user']->type != User::TYPE_ADMIN)
        {
            $message = 'You are not authorised to delete this record.';
            return $message;
        }

        $agreement = EmployerAgreement::loadFromDatabase($link, $record_id);

        $agreement->delete($link);

        $dir_name = Repository::getRoot() . "/employers/{$agreement->employer_id}/agreements/{$agreement->id}";
        if(is_dir($dir_name))
        {
            $files = Repository::readDirectory($dir_name);
            foreach($files AS $f)
                unlink($f->getAbsolutePath());
        }

        return $message;
    }

}
?>