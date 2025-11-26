<?php
class lrs_register_single_learner implements IAction
{
	public function execute(PDO $link)
	{

		$_SESSION['bc']->index = 0;
		$_SESSION['bc']->add($link, "do.php?_action=lrs_register_single_learner", "LRS - Register Single Learner");

        $titlesDdl = [
            ['Mr', 'Mr'],
            ['Mrs', 'Mrs'],
            ['Miss', 'Miss'],
            ['Ms', 'Ms']
        ];


		include_once('tpl_lrs_register_single_learner.php');
	}


}
