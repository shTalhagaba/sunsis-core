<?php
class send_login_details implements IAction
{
	public function execute(PDO $link)
	{
		$username = isset($_REQUEST['username'])?$_REQUEST['username']:'';
		$tr_id = isset($_REQUEST['tr_id'])?$_REQUEST['tr_id']:'';
		$subaction = isset($_REQUEST['subaction'])?$_REQUEST['subaction']:'';

		if($username == '')
			throw new Exception('Missing querystring argument: username');

		$user = User::loadFromDatabase($link, $username);
		if(is_null($user))
			throw new Exception('User record not found');

		if($subaction == 'send_email')
		{
			$sender_email = SystemConfig::getEntityValue($link, 'rec_v2_email');
			Emailer::html_mail($_REQUEST['user_email'], $sender_email, 'Sunesis Login Details', '', $_REQUEST['email_content'], array(), array('X-Mailer: PHP/' . phpversion()));

			http_redirect("/do.php?_action=read_training_record&id=$tr_id");
		}

		$_SESSION['bc']->add($link, "do.php?_action=send_login_details&username=" . $username . "&tr_id=" . $tr_id, "Send Login Details");

		include('tpl_send_login_details.php');
	}

}
?>