<?php
class save_register_entry_note implements IAction
{
	public function execute(mysqli $link)
	{
		$vo = new RegisterEntryNote();
		$vo->populate($_REQUEST);



		// Check permissions
		if($vo->id == '')
		{
			throw new Exception("New notes may not be added through this action");
		}

		$sql = "SELECT lessons_id FROM register_entries WHERE id=".$vo->register_entries_id;
		$lessons_id = DAO::getSingleValue($link, $sql);

		$vo->save($link);

		// Instruct client on next action
		if(IS_AJAX)
		{
			header('Content-Type: text/plain; charset=ISO-8859-1');
			echo $vo->id;
		}
		else
		{
			$referer = isset($_REQUEST['referer'])?$_REQUEST['referer']:'';

			if($referer == '')
			{
				$sql = "SELECT lessons_id FROM register_entries WHERE id=".$vo->register_entries_id;
				$lessons_id = DAO::getSingleValue($link, $sql);
				http_redirect('do.php?_action=read_register&lesson_id=' . $lessons_id);
			}
			else
			{
				http_redirect($referer);
			}
		}
	}
}
?>