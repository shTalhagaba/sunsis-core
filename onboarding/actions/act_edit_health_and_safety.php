<?php
class edit_health_and_safety implements IAction
{
	public function execute(PDO $link)
	{
		$id = isset($_REQUEST['id'])?$_REQUEST['id']:'';

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
		$na = trim(DAO::getSingleValue($link, $que));

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
		$record = 0;
		$st = $link->query($sql);
		if($st)
		{
			while($row = $st->fetch())
			{
				$record++;
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