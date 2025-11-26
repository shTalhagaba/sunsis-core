<?php
class save_framework implements IAction
{
	public function execute(PDO $link)
	{
		
		$org = new Framework();
		
		
/*		$quarters = (int)$_POST['quarters'];

		$data = '<targets>';		
		
		for($x = 1; $x <= $quarters; $x++)
		{
			$subs = "quarter" . $x;
			$data .= "<month>" . (int)$_POST[$subs] . "</month>";
		}
		
		$data .= "</targets>";

*/

		$org->populate($_POST);
		$org->parent_org = $_SESSION['user']->employer_id;
		$org->clients = $_SESSION['user']->username;

		// #22927 - active was always setting to one? re 02/07/2012
		$org->active = 0;
		if ( isset($_POST['active']) ) {
			$org->active = 1;
		}
        $org->track = 0;
        if ( isset($_POST['track']) ) {
            $org->track = 1;
        }

		$org->save($link);

		// 09/08/2012 - re - usability issue
		// ---
		// on a save of a framework we should load the just editted framework in view mod
		// take off the edit from the breadcrumbs
		$_SESSION['bc']->index = $_SESSION['bc']->index-1;
		http_redirect('do.php?_action=view_framework_qualifications&id='.$org->id);
		// ---
	}
}
?>