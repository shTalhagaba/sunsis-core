<?php
class edit_trainingprovider implements IAction
{
	public function execute(PDO $link)
	{
		// Validate data entry
		$id = isset($_GET['id']) ? $_GET['id'] : '';

		$_SESSION['bc']->add($link, "do.php?_action=edit_trainingprovider&id=" . $id, "Add/ Edit Training Provider");

		if($id !== '' && !is_numeric($id))
		{
			throw new Exception("You must specify a numeric id in the querystring in order to edit an organisation");
		}

		if($id == '')
		{
			// New record
			$tp = new TrainingProvider();
            $tp->active = 1;
            $tp->organisation_type = Organisation::TYPE_TRAINING_PROVIDER;
            $mainLocation = new Location();
            $mainLocation->is_legal_address = 1;
        }
		else
		{
			$tp = TrainingProvider::loadFromDatabase($link, $id);
            $mainLocation = $tp->getMainLocation($link);
		}

		$L46_dropdown = "SELECT DISTINCT UKPRN, LEFT(CONCAT(Name,' ',UKPRN),50),CONCAT('-----------', LEFT(NAME, 1), '-----------') from lis201415.providers order by Name;";
		$L46_dropdown = DAO::getResultset($link,$L46_dropdown);

		// Presentation
		include('tpl_edit_trainingprovider.php');
	}
}
?>