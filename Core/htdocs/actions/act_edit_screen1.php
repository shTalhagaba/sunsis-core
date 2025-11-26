<?php
class edit_screen1 implements IAction
{
	public function execute(PDO $link)
	{
		// Validate data entry
		$id = isset($_REQUEST['cps']) ? $_REQUEST['cps'] : '';

		$_SESSION['bc']->add($link, "do.php?_action=edit_screen1&cps=" . $id, "Add/ Edit Goods Receipt Note");

		if($id !== '' && !is_numeric($id))
		{
			throw new Exception("You must specify a numeric CPS in the querystring");
		}

		if($id == '')
		{
			// New record
			$vo = new Screen1();
		}
		else
		{
			$vo = Screen1::loadFromDatabase($link, $id);
		}


		// Cancel button URL
		if($vo->cps == 0)
		{
			$js_cancel = "window.location.replace('do.php?_action=view_screen1');";
		}
		else
		{
			$js_cancel = "window.location.replace('do.php?_action=read_screen1&cps={$vo->cps}');";
		}


		include('tpl_edit_screen1.php');
	}
}
?>