<?php
class save_training_group implements IAction
{
	public function execute( PDO $link )
	{
		$course_id = isset($_POST['course_id']) ? $_POST['course_id'] : '';
		$group_id = isset($_POST['group_id']) ? $_POST['group_id'] : '';
		$save_type = isset($_POST['save_type']) ? $_POST['save_type'] : '';

		$redirect_url = "do.php?_action=read_course_v2&id={$course_id}&subview=training_groups";

		if($save_type == 'multiple')
		{
			foreach($_POST['titles'] AS $title)
			{
				if(trim($title) != '')
				{
					$tg = new stdClass();
					$tg->group_id = $group_id;
					$tg->title = $title;
					DAO::saveObjectToTable($link, 'training_groups', $tg);
				}
			}
		}
		if($save_type == 'single' && trim($_POST['title']) != '')
		{
			$tg = new stdClass();
			$tg->id = $_POST['tg_id'];
			$tg->title = $_POST['title'];
			$tg->group_id = $_POST['group_id'];
			DAO::saveObjectToTable($link, 'training_groups', $tg);

			if($_POST['tg_id'] != '')
			{
				DAO::execute($link, "UPDATE tr SET tr.tg_id = NULL WHERE tr.tg_id = '{$_POST['tg_id']}'");
			}

			if(isset($_POST['members']))
			{
				foreach($_POST['members'] AS $member)
				{
					DAO::execute($link, "UPDATE tr SET tr.tg_id = '{$tg->id}' WHERE tr.id = '{$member}'");
				}
			}

			if($_POST['tg_id'] == '')
				$redirect_url = "do.php?_action=read_course_v2&subview=add_edit_training_group&id={$course_id}&group_id={$group_id}&tg_id={$tg->id}&from_view=course_training_groups";
			else
				$redirect_url = "do.php?_action=read_course_v2&subview=training_group_view&id={$course_id}&group_id={$group_id}&tg_id={$tg->id}&from_view=course_training_groups";
		}

		http_redirect($redirect_url);
	}
}