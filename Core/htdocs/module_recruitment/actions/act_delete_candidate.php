<?php
class delete_candidate implements IAction
{
	public function execute(PDO $link)
	{
		$id = isset($_REQUEST['id'])?$_REQUEST['id']:'';

		if($id == '')
		{
			throw new Exception("Missing or empty querystring argument: id");
		}

		$vo = Candidate::loadFromDatabase($link, $id);
		if(is_null($vo))
		{
			throw new Exception("No candidate with id #$id found");
		}


		try
		{
			DAO::transaction_start($link);
			$note = new Note();
			$note->subject = "Candidate deleted";
			$note->parent_table = 'Candidate';
			$note->parent_id = $vo->id;
			$note->note = 'Firstnames = ' . $vo->firstnames . ', Surname = ' . $vo->surname . ', DOB = ' . $vo->dob . ', NI = ' . $vo->national_insurance .
				', Address = ' . $vo->address1 . ' ' . $vo->address2 . ' ' . $vo->address3 . ' ' . $vo->county . ' ' . $vo->region . ' ' . $vo->postcode .
				', Email = ' . $vo->email . ', Telephone = ' . $vo->telephone . ' ';
			foreach($vo->applications AS $key => $value)
			{
				$note->note .= ', Application ID = ' . $key;
			}

			$note->save($link);
			$vo->delete($link);
			DAO::transaction_commit($link);
		}
		catch(Exception $e)
		{
			DAO::transaction_rollback($link, $e);
			throw new WrappedException($e);
		}



		http_redirect('do.php?_action=vacancies_home');
	}

}
?>
