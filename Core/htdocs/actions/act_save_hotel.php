<?php
class save_hotel implements IAction
{
	public function execute(PDO $link)
	{
		if(isset($_REQUEST['subaction']) && $_REQUEST['subaction'] == 'validateEDRS')
		{
			echo $this->validateEDRS($_REQUEST['edrs']);
			exit;
		}

		$org = new Employer();

		$org->populate($_POST);

		if($org->creator == '')
			$org->creator = $_SESSION['user']->username;
		if($org->parent_org == '')
			$org->parent_org = $_SESSION['user']->employer_id;

		$loc = new Location();
		$loc->populate($_POST);
		$loc->id = $_POST['main_location_id'];

		DAO::transaction_start($link);
		try
		{
			$org->save($link);

			$loc->organisations_id = $org->id;
			$loc->is_legal_address = 1;
			$loc->save($link);

			DAO::transaction_commit($link);
		}
		catch(Exception $e)
		{
			DAO::transaction_rollback($link, $e);
			throw new WrappedException($e);
		}

		http_redirect("do.php?_action=read_hotel&id=" . $org->id);
	}

	public function validateEDRS($edrs)
	{
		$A44 = $edrs;
		if($A44 != '')
		{
			$flag1 = true;
			for($a = 0; $a <= 8; $a++)
				if(!($this->isDigit(substr($A44, $a, 1))))
					$flag1 = false;

			$flag2 = true;
			if(strlen($A44) > 9)
				for($a=9; $a <= 29; $a++)
					if((substr($A44, $a, 1) != ' ') && (substr($A44, $a, 1) != ''))
						$flag2 = false;

			if($flag1 && $flag2)
			{
				$res = 11-((9*(int)substr($A44,0,1)+8*(int)substr($A44,1,1)+7*(int)substr($A44,2,1)+6*(int)substr($A44,3,1)+5*(int)substr($A44,4,1)+4*(int)substr($A44,5,1) + 3*(int)substr($A44,6,1) + 2*(int)substr($A44,7,1)) % 11);
				if($res==11)
					$AD03='0';
				else
					if($res==10)
						$AD03='X';
					else
						$AD03=$res;
			}
			else
				$AD03 = 'T';

			if($AD03=='T')
			{
				return 0;
			}
		}

		return 1;
	}

	public static function isDigit($ch)
	{
		if(ord($ch)>=48 && ord($ch)<=57)
			return true;
		else
			return false;
	}
}


?>