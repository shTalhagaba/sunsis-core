<?php
class reply_dars_request implements IAction
{
	public function execute(PDO $link)
	{
		$id = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';
		$saved = isset($_REQUEST['saved']) ? $_REQUEST['saved'] : '';
		$case_number = isset($_REQUEST['case_number']) ? $_REQUEST['case_number'] : '';

		if(isset($_REQUEST['subaction']) && $_REQUEST['subaction'] == 'save_reply')
		{
			$this->saveReply($link);
		}

		$_SESSION['bc']->add($link, "do.php?_action=reply_dars_request&id=" . $id, "Reply DARS request");

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

		$download_link = "";
		if(!is_null($request->attachment) && file_exists(Repository::getRoot() . '/dars_requests/' . $request->attachment))
			$download_link = "dars_requests/".$request->attachment;

		include('tpl_reply_dars_request.php');
	}

	private function saveReply(PDO $link)
	{
		//pre($_REQUEST);
		$id = isset($_REQUEST['id'])?$_REQUEST['id']:'';
		if($id == '')
			throw new Exception('Missing querystring: request id');

		$vo = DARSRequest::loadFromDatabase($link, $id);
		$vo->status = $_REQUEST['status'];
		if(isset($_REQUEST['resolved']) && ($_REQUEST['resolved'] == 'on' || $_REQUEST['resolved'] == '1'))
			$vo->resolved = 1;
		else
			$vo->resolved = 0;

		DAO::transaction_start($link);
		try
		{
			$vo->save($link);

			$objHistory = new stdClass();
			$objHistory->dars_id = $vo->id;
			$objHistory->notes = htmlspecialchars((string)$_REQUEST['notes']);
			$objHistory->by = $_SESSION['user']->id;
			DAO::saveObjectToTable($link, 'dars_history', $objHistory);

			DAO::transaction_commit($link);
		}
		catch(Exception $e)
		{
			DAO::transaction_rollback($link, $e);
			throw new WrappedException($e);
		}

		http_redirect('do.php?_action=view_dars_requests_admin');
	}
}
?>