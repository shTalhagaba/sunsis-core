<?php
class save_learner implements IAction
{
	public function execute(PDO $link)
	{
		$id = isset($_POST['id']) ? $_POST['id'] : '';
		$username = isset($_POST['username']) ? $_POST['username'] : '';
		if($id == '' && $username == '')
			throw new Exception('Incomplete information: id, username cannot be null');

		$vo = null;
		if($username != '')
			$vo = User::loadFromDatabase($link, $username);
		else
			$vo = User::loadFromDatabaseById($link, $id);

		if(is_null($vo))
			throw new Exception('Invalid details: id, username');

		$vo->populate($_POST);

		foreach(['numeracy_diagnostic', 'literacy_diagnostic', 'chk_numeracy_diagnostic', 'chk_literacy_diagnostic', 'ict_diagnostic', 'esol_diagnostic', 'other_diagnostic'] AS $checkbox)
		{
			if(!isset($_POST[$checkbox]))
			{
				$vo->$checkbox = 0;
			}
		}

		DAO::transaction_start($link);
		try
		{
			$vo->web_access = (isset($_POST['web_access']) && $_POST['web_access'] == 1) ? 1 : 0;
			if($vo->id == '')
				$vo->save($link, true);
			else
				$vo->save($link, false);

			// File uploads
			$target_directory = $vo->username.'/photos';
			$valid_extensions = array('gif', 'jpg', 'jpeg', 'png');
			$filepaths = Repository::processFileUploads('uploadedfile', $target_directory, $valid_extensions, 1024 * 500); // 100KB max
			if(count($filepaths) > 0)
			{
				rename($filepaths[0], pathinfo($filepaths[0], PATHINFO_DIRNAME).'/profilePhoto.'.pathinfo($filepaths[0], PATHINFO_EXTENSION));
			}

			DAO::transaction_commit($link);
		}
		catch(Exception $e)
		{
			DAO::transaction_rollback($link, $e);
			throw new WrappedException($e);
		}

		$selected_tab = isset($_REQUEST['selected_tab']) ? $_REQUEST['selected_tab'] : 'tabPersonalDetails';

		http_redirect("do.php?_action=edit_learner&id={$vo->id}&username={$vo->username}&selected_tab={$selected_tab}&toastr_message=Details are saved successfully.");
	}


}
?>
