<?php
class edit_health_and_safety implements IAction
{
	public function execute(PDO $link)
	{

        $id = isset($_REQUEST['id'])?$_REQUEST['id']:'';

        // Add new record if required
	if(!in_array(DB_NAME, ["am_ela"]))
	{
		$count = DAO::getSingleValue($link, "select count(*) from health_safety where location_id = '$id'");
		if($count==0)
		{
			$health_and_safety = new StdClass();
			$health_and_safety->id = NULL;
			$health_and_safety->location_id = $id;
			DAO::saveObjectToTable($link, 'health_safety', $health_and_safety);
		}
		else
		{
			$st = $link->query("SELECT location_id, next_assessment, ADDDATE(next_assessment, INTERVAL 1 YEAR) AS next_assessment2 FROM health_safety WHERE next_assessment IS NOT NULL AND next_assessment!=\"0000-00-00\" AND next_assessment < NOW() AND id = (SELECT MAX(id) FROM health_safety AS hs2 WHERE hs2.`location_id` = health_safety.`location_id`);");
			if($st)
			{
				while($row = $st->fetch())
				{
					$health_and_safety = new StdClass();
					$health_and_safety->id = NULL;
					$health_and_safety->location_id = $row['location_id'];
					$health_and_safety->last_assessment = $row['next_assessment'];
					$health_and_safety->next_assessment = $row['next_assessment2'];
					DAO::saveObjectToTable($link, 'health_safety', $health_and_safety);
				}
			}
		}
	}
		$_SESSION['bc']->add($link, "do.php?_action=edit_health_and_safety&id=" . $id, "Edit Health & Safety");
		
		$l = Location::loadFromDatabase($link, $id);
		$vo = Organisation::loadFromDatabase($link, $l->organisations_id);
		
		
$sql = <<<HEREDOC
SELECT
	health_safety.*
FROM
	health_safety
where location_id='$id' order by last_assessment, next_assessment;
HEREDOC;
		
		$que = "select DATEDIFF(max(next_assessment), CURDATE()) from health_safety where location_id='$id'";
		$na = trim(DAO::getSingleValue($link, $que) ?: '');

		$last_assessment = Array();
		$next_assessment = Array();
		$assessor = Array();
		$comments = Array();
		$complient = Array();
		$paperwork = Array();
		$age_range = Array();
		$pl_date = Array();
		$pl_insurance = Array();
        $el_date = Array();
        $el_insurance = Array();
        $ids = Array();
		$record = 0;
		$st = $link->query($sql);
		if($st)
		{
			while($row = $st->fetch())
			{
				$record++;
                $ids[$record] = $row['id'];
				$last_assessment[$record] = $row['last_assessment'];
				$next_assessment[$record] = $row['next_assessment'];
				$assessor[$record] = $row['assessor'];
				$comments[$record] = $row['comments'];
				$compliant[$record] = $row['complient'];
				$paperwork[$record] = $row['paperwork_received'];
				$age_range[$record] = $row['age_range'];
				$pl_date[$record] = $row['pl_date'];
				$pl_insurance[$record] = $row['pl_insurance'];
                $el_date[$record] = $row['el_date'];
                $el_insurance[$record] = $row['el_insurance'];
			}
		}
		
		include('tpl_edit_health_and_safety.php');
	}
}
?>