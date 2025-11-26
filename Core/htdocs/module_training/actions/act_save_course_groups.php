<?php
class save_course_groups implements IAction
{
	public function execute( PDO $link )
	{
		$course_id = isset($_POST['course_id']) ? $_POST['course_id'] : '';
		$group_id = isset($_POST['group_id']) ? $_POST['group_id'] : '';
		$save_type = isset($_POST['save_type']) ? $_POST['save_type'] : '';
		if($save_type == 'multiple')
		{
			for($i = 1; $i <= 10; $i++)
			{
				if(isset($_POST['title'.$i]) && trim($_POST['title'.$i]) != '')
				{
					$group = new stdClass();
					$group->courses_id = $course_id;
					$group->title = $_POST['title'.$i];
					$group->tutor = $_POST['tutor'.$i];
					$group->assessor = $_POST['assessor'.$i];
					$group->verifier = $_POST['verifier'.$i];
					DAO::saveObjectToTable($link, 'groups', $group);
				}
			}
		}
		if($save_type == 'single')
		{
			$tg = new stdClass();
			$tg->id = $_POST['tg_id'];
			$tg->title = $_POST['title'];
			$tg->group_id = $_POST['group_id'];
			DAO::saveObjectToTable($link, 'training_groups', $tg);

			foreach($_POST['members'] AS $member)
			{
				DAO::execute($link, "UPDATE tr SET tr.tg_id = '{$tg->id}' WHERE tr.id = '{$member}'");
			}
		}

		http_redirect("do.php?_action=read_course_v2&id={$course_id}&subview=groups");
	}
}