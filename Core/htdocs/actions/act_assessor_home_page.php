<?php
class assessor_home_page implements IAction
{
	public function execute(PDO $link)
	{

		$photopath = $_SESSION['user']->getPhotoPath();
		if($photopath){
			$photopath = "do.php?_action=display_image&username=".rawurlencode($_SESSION['user']->username);
		} else {
			$photopath = "/images/no_photo.png";
		}

		$current_year = date('Y');
		$next_year = date('Y') + 1;

		$notifResult = $_SESSION['user']->getNotifications($link);

		$user_signature = DAO::getSingleValue($link, "SELECT signature FROM users WHERE users.id = '{$_SESSION['user']->id}'");

		$temp_notification_message = <<<HTML
<p>Due to essential server maintenance the Sunesis system will be unavailable from <strong>Friday 25th January</strong> at <strong>5pm</strong> until <strong>Monday 28th January</strong> at <strong>8am</strong>.  If you are using the system at 5pm on Friday, please log out or data may be lost.</p><p>Many thanks for you co-operation.</p><input type="checkbox" name="_t_n_msg" id="_t_n_msg" onclick="doNotShowTempNotification();" > Do not show the message again
HTML;


		if(SystemConfig::getEntityValue($link, "module_eportfolio"))
		{
			if($_SESSION['user']->type == User::TYPE_ASSESSOR)
				include_once('tpl_asssessor_home_page.php');
			if($_SESSION['user']->type == User::TYPE_VERIFIER)
				include_once('tpl_verifier_home_page.php');
		}
		else
			include_once('tpl_assessor_home_page.php');
	}


}