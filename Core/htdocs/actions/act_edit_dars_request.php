<?php
class edit_dars_request implements IAction
{
	public function execute(PDO $link)
	{
		$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';
		$saved = isset($_REQUEST['saved']) ? $_REQUEST['saved'] : '';
		$case_number = isset($_REQUEST['case_number']) ? $_REQUEST['case_number'] : '';

		if(isset($_REQUEST['subaction']) && $_REQUEST['subaction'] == 'save')
		{
			$this->saveDARSRequest($link);
		}
		elseif(isset($_REQUEST['subaction']) && $_REQUEST['subaction'] == 'update')
		{
			$this->updateDARSRequest($link);
		}

		$_SESSION['bc']->add($link, "do.php?_action=edit_dars_request&id=" . $id, "Add/Edit DARS request");

		if($id == '')
		{
			$request = new DARSRequest();
			$request->requester = $_SESSION['user']->id;
		}
		else
		{
			$request = DARSRequest::loadFromDatabase($link, $id);
		}

		$requester = User::loadFromDatabaseById($link, $request->requester);

		$sql = <<<SQL
SELECT
	users.id, CONCAT(users.id, ' - ', users.firstnames, ' ', users.surname), LEFT(users.firstnames, 1)
FROM
	users INNER JOIN tr ON (users.username = tr.username AND users.contract = tr.contract_id)
WHERE
	users.type = 5
	AND (users.adviser = '{$requester->id}' OR users.who_created = '{$requester->id}' OR tr.provider_id = '{$requester->employer_id}')
SQL;

		$participants = DAO::getResultset($link, $sql);
		$selected_participants = array();
		if(!is_null($request->participants))
		{
			$selected_participants = explode(',', $request->participants);
		}

		include('tpl_edit_dars_request.php');
	}

	private function saveDARSRequest(PDO $link)
	{
		$vo = new DARSRequest();
		$vo->populate($_REQUEST);

		$vo->status = '6'; // New status
		DAO::transaction_start($link);
		try
		{
			// File uploads
			$valid_extensions = array('doc', 'docx', 'pdf');
			$max_file_upload = Repository::parseFileSize(ini_get("upload_max_filesize"));
			if(Repository::getRemainingSpace() < $max_file_upload)
			{
				$max_file_upload = Repository::getRemainingSpace();
			}

			if(isset($_FILES['ufile']) && $_FILES['ufile']['error'] != UPLOAD_ERR_NO_FILE)
			{
				if(!file_exists(Repository::getRoot() . '/dars_requests'))
					mkdir(Repository::getRoot() . '/dars_requests', 0777, true);
				$r = Repository::processFileUploads('ufile', 'dars_requests', $valid_extensions, $max_file_upload); // 6.0MB max
				if(!isset($r[0]))
					throw new Exception('Error uploading progression evidence, please try again.');

				$vo->attachment = basename($r[0]);
			}

			$vo->save($link);

			if($_SESSION['user']->work_email == '')
				DAO::execute($link, "UPDATE users SET users.work_email = '" . addslashes((string)$_REQUEST['email']) . "' WHERE users.id = '" . $_SESSION['user']->id . "'");
			if($_SESSION['user']->work_telephone == '')
				DAO::execute($link, "UPDATE users SET users.work_telephone = '" . addslashes((string)$_REQUEST['telephone']) . "' WHERE users.id = '" . $_SESSION['user']->id . "'");
			if($_SESSION['user']->work_fax == '')
				DAO::execute($link, "UPDATE users SET users.work_fax = '" . addslashes((string)$_REQUEST['fax']) . "' WHERE users.id = '" . $_SESSION['user']->id . "'");

			DAO::transaction_commit($link);
		}
		catch(Exception $e)
		{
			DAO::transaction_rollback($link, $e);
			throw new WrappedException($e);
		}
		http_redirect('do.php?_action=edit_dars_request&saved=1&case_number='.$vo->id);
	}

	private function updateDARSRequest(PDO $link)
	{
		//pre($_REQUEST);
		$case_number = isset($_REQUEST['case_number'])?$_REQUEST['case_number']:'';
		if($case_number == '')
			throw new Exception('Missing querystring: case-number');

		$request = DARSRequest::loadFromDatabase($link, $case_number);

		if ( isset($_REQUEST['close-case']) && $_REQUEST['close-case'] != '' )
		{
			if ( !isset($_REQUEST['ts']) )
			{
				throw new Exception('No update has occurred - we cannot verify the date of this request. We have reloaded the page for you');
			}

			if(isset($_REQUEST['case-finished']) && $_REQUEST['case-finished'] == '1')
				$request->status = 8;
			else
				$request->status = 7;

			$request->save($link);

			$objHistory = new stdClass();
			$objHistory->dars_id = $request->id;
			if(isset($_REQUEST['case-finished']) && $_REQUEST['case-finished'] == '1')
				$objHistory->notes = isset($_REQUEST['case-comment'])? htmlspecialchars((string)$_REQUEST['case-comment'] . ' [Case has been closed by ' . $_SESSION['user']->firstnames . ' ' . $_SESSION['user']->surname . ']'):' [Case has been closed by ' . $_SESSION['user']->firstnames . ' ' . $_SESSION['user']->surname . ']';
			else
				$objHistory->notes = isset($_REQUEST['case-comment'])? htmlspecialchars((string)$_REQUEST['case-comment']):'';
			$objHistory->by = $_SESSION['user']->id;

			DAO::saveObjectToTable($link, 'dars_history', $objHistory);
		}
		http_redirect('do.php?_action=view_dars_requests_staff');
	}
}
?>