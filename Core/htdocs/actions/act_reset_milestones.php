<?php
class reset_milestones implements IAction
{
	public function execute(PDO $link)
	{
		require_once('tpl_reset_milestones.php');
	}
}
?>