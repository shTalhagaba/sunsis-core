<?php
class lrs_save_register_single_learner implements IAction
{
	public function execute(PDO $link)
	{
        echo json_encode($_POST);
	}
}
