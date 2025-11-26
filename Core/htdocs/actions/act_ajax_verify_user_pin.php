<?php
class ajax_verify_user_pin implements IAction
{
	public function execute(PDO $link)
	{
		$pos1 = isset($_REQUEST['position1'])?$_REQUEST['position1']: '';
		$pos2 = isset($_REQUEST['position2'])?$_REQUEST['position2']: '';
		$pos3 = isset($_REQUEST['position3'])?$_REQUEST['position3']: '';
		$pos4 = isset($_REQUEST['position4'])?$_REQUEST['position4']: '';
		$username = isset($_REQUEST['username'])?$_REQUEST['username']: '';

//		$username = $link->quote($_SESSION['user']->username);
		$username = $link->quote($username);
		//throw new Exception("pos1 = " . $pos1 . ",pos2 = " . $pos2 . ",pos3 = " . $pos3 . ",pos4 = " . $pos4);
		$pin = $pos1.$pos2.$pos3.$pos4;

		if($pin == '')
		{
			echo "No PIN entered";
		}
		else
		{

			$user_pin = DAO::getSingleValue($link, "SELECT pin FROM users WHERE username = " . $username);
			if($user_pin == '')
			{
				echo 'invalid';
				exit;
			}
			$saved_pin  = array_map('intval', str_split($user_pin));


			if($pos1 == '')
				$saved_pin = $saved_pin[1] . $saved_pin[2] . $saved_pin[3];
			if($pos2 == '')
				$saved_pin = $saved_pin[0] . $saved_pin[2] . $saved_pin[3];
			if($pos3 == '')
				$saved_pin = $saved_pin[0] . $saved_pin[1] . $saved_pin[3];
			if($pos4 == '')
				$saved_pin = $saved_pin[0] . $saved_pin[1] . $saved_pin[2];

			//throw new Exception($saved_pin . ', ' . $pin);
			if($saved_pin == $pin)
				echo 'valid';
			else
				echo 'invalid';

		}
	}
}