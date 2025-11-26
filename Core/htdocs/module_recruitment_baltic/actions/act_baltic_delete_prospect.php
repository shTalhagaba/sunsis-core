<?php
class baltic_delete_prospect implements IAction
{
	public function execute(PDO $link)
	{
		$id = isset($_REQUEST['id'])?$_REQUEST['id']:'';

		if($id == '')
		{
			throw new Exception("Missing or empty querystring argument: id");
		}

		$vo = EmployerPool::loadFromDatabase($link, $id);
		if(is_null($vo))
		{
			throw new Exception("No prospect with id #$id found");
		}


		try
		{
			DAO::transaction_start($link);
			$note = new Note();
			$note->subject = "Prospect deleted";
			$note->parent_table = 'emp_pool';
			$note->parent_id = $vo->auto_id;
			$note->note = 'Company = ' . $vo->company .
				', Address = ' . $vo->address1 . ' ' . $vo->address2 . ' ' . $vo->address3 . ' ' . $vo->address4 . ' ' . $vo->address5 . ' ' . $vo->postcode .
				', Email = ' . $vo->primary_email_address . ', Telephone = ' . $vo->telephone . ' ';

			$note->save($link);
			$vo->delete($link);
			DAO::transaction_commit($link);
		}
		catch(Exception $e)
		{
			DAO::transaction_rollback($link, $e);
			throw new WrappedException($e);
		}



		http_redirect('do.php?_action=empengage_home');
	}

}
?>
