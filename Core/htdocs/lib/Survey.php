<?php
class Survey extends Entity
{

	public function save(PDO $link, $newRecord = false)
	{
        // Save Survey
        DAO::saveObjectToTable($link, 'learner_survey', $this);
        return true;

	}

}
?>