<?php
class save_screen1 implements IAction
{

	public function execute(PDO $link)
	{
		$id = isset($_GET['cps']) ? $_GET['cps'] : '';

		$vo = new Screen1();
		$vo->cps = $id;
		$vo->populate($_POST);

		$vo->save($link);

		http_redirect('do.php?_action=read_screen1&cps='.$vo->cps);
	}
}
?>