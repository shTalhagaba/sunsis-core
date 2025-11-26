<?php
class save_provider implements IAction
{
	public function execute(PDO $link)
	{
		// Populate Value Object from user's <form> submission
		$vo = new OrganisationVO();
		$vo->populate($_POST);

        if ( !isset($_POST['active']) ) {
            $vo->active = 0;
        }


		// Create DAO
		$dao = new OrganisationDAO($link);
	
		if($vo->id == 0)
		{
			$vo->id = $dao->insert($vo);
		}
		else
		{
			$dao->update($vo);
		}
	
		// Presentation
		http_redirect('do.php?_action=read_provider&id=' . $vo->id);
	}
}
?>