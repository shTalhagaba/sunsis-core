<?php
class save_forecast_learners implements IAction
{

	public function execute(PDO $link)
	{
		$learner_types = DAO::getResultset($link, "SELECT id FROM lookup_learner_types ORDER BY id", DAO::FETCH_ASSOC);

		$year = $_REQUEST['year'];

		$sql = "";
		foreach($learner_types AS $type)
		{
			$sql .= "REPLACE INTO forecast_learners (`year`, `jan`, `feb`, `mar`, `apr`, `may`, `jun`, `jul`, `aug`, `sep`, `oct`, `nov`, `dec`, `username`, `type`) ";
			$sql .= " VALUES (";
			$sql .= $_REQUEST['year'] . ', ';
			$sql .= $_REQUEST[$type['id'] . '_jan'] . ', ';
			$sql .= $_REQUEST[$type['id'] . '_feb'] . ', ';
			$sql .= $_REQUEST[$type['id'] . '_mar'] . ', ';
			$sql .= $_REQUEST[$type['id'] . '_apr'] . ', ';
			$sql .= $_REQUEST[$type['id'] . '_may'] . ', ';
			$sql .= $_REQUEST[$type['id'] . '_jun'] . ', ';
			$sql .= $_REQUEST[$type['id'] . '_jul'] . ', ';
			$sql .= $_REQUEST[$type['id'] . '_aug'] . ', ';
			$sql .= $_REQUEST[$type['id'] . '_sep'] . ', ';
			$sql .= $_REQUEST[$type['id'] . '_oct'] . ', ';
			$sql .= $_REQUEST[$type['id'] . '_nov'] . ', ';
			$sql .= $_REQUEST[$type['id'] . '_dec'] . ', ';
			$sql .= '"' . $_SESSION['user']->username . '", ';
			$sql .= $type['id'];
			$sql .= ');';
		}

		DAO::execute($link, $sql);
		http_redirect('do.php?_action=edit_forecast_learners');
	}
}
?>