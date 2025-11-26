<?php
class save_operations_tracker implements IAction
{
	public function execute(PDO $link)
	{
		//pre($_REQUEST);
		$form = isset($_REQUEST['formName'])?$_REQUEST['formName']:'';
		if($form == '')
			throw new Exception('Missing querystring argument: form name');

		$vo = null;
		if($form == 'frmTracker')
		{
			$vo = new OperationsTracker();
			$vo->populate($_REQUEST);
			$vo->frameworks = $_REQUEST['frameworks'];
		}
		elseif($form == 'frmTrackerUnits')
		{
			$vo = OperationsTracker::loadFromDatabase($link, $_REQUEST['id']);
		}


		//pre($vo);

		DAO::transaction_start($link);
		try
		{
			if($form == 'frmTracker')
			{
				$vo->save($link);
				if(isset($_REQUEST['frameworks']) && is_array($_REQUEST['frameworks']) && count($_REQUEST['frameworks']) > 0)
					$vo->saveTrackerFrameworks($link, $_REQUEST['frameworks']);
			}

			if($form == 'frmTrackerUnits')
			{
				if(isset($_REQUEST['units']) && is_array($_REQUEST['units']) && count($_REQUEST['units']) > 0)
				{
					$vo->saveTrackerUnits($link, $_REQUEST['units']);
				}
			}

			DAO::transaction_commit($link);
		}
		catch(Exception $e)
		{
			DAO::transaction_rollback($link, $e);
			throw new WrappedException($e);
		}

		if(IS_AJAX)
		{
			header("Content-Type: text/plain");
			echo 'success';
		}
		else
		{
			http_redirect("do.php?_action=edit_operations_tracker&id=" . $vo->id);
		}
	}
}
?>
