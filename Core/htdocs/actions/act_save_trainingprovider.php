<?php
class save_trainingprovider implements IAction
{
	public function execute(PDO $link)
	{

		$org = new TrainingProvider();
		$org->populate($_POST);
		
		$org->save($link);
		
		http_redirect('do.php?_action=read_trainingprovider&id=' . $org->id);
	}
}
?>