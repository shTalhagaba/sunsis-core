<?php
class raytheon_report implements IAction
{
	public function execute(PDO $link)
	{
		echo "<table border='1'>";
		echo "<tr><td>Framework Code</td><td>Framework Title</td><td>Qualification Number</td><td>Qual Title</td><td>Unit Reference</td><td>Credits</td><td>GLH</td><td>Unit Title</td></tr>";
		$quals = DAO::getResultset($link, "SELECT frameworks.`framework_code`, frameworks.`title`,  framework_qualifications.id, framework_qualifications.`internaltitle`
										   FROM framework_qualifications INNER JOIN frameworks ON framework_qualifications.`framework_id` = frameworks.id AND frameworks.`active`  = 1 ", DAO::FETCH_ASSOC);
		foreach($quals AS $q)
		{
			$units = $this->getUnits($link, $q['id']);//pre($units);
			foreach($units AS $unit)
			{
				echo "<tr>";
				echo "<td>" . $q['framework_code'] . "</td>";
				echo "<td>" . $q['title'] . "</td>";
				echo "<td>" . $q['id'] . "</td>";
				echo "<td>" . $q['internaltitle'] . "</td>";
				echo isset($unit['reference'])?"<td>" . $unit['reference'] . "</td>":"<td></td>";
				echo isset($unit['credits'])?"<td>" . $unit['credits'] . "</td>":"<td></td>";
				echo isset($unit['glh'])?"<td>" . $unit['glh'] . "</td>":"<td></td>";
				echo isset($unit['title'])?"<td>" . $unit['title'] . "</td>":"<td></td>";
				echo "</tr>";
			}
		}
		echo "</table>";
	}

	private function getUnits(PDO $link, $qualification_id)
	{
		$qualification_id = str_replace('/', '', $qualification_id);

		$sql = <<<HEREDOC
SELECT
	 qualifications.id,
	 qualifications.evidences
FROM
	 qualifications
WHERE
	 REPLACE(qualifications.id, '/', '') = '$qualification_id' ;
HEREDOC;

		$student_qualifications = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);

		$units_ddl = array();
		foreach ($student_qualifications AS $qualification)
		{
			$evidence = XML::loadSimpleXML($qualification['evidences']);

			$units = $evidence->xpath('//unit');
			$q_units = array();
			foreach ($units AS $unit)
			{
				$temp = array();
				$temp = (array)$unit->attributes();
				$temp = $temp['@attributes'];
				$temp['reference'] = str_replace('/','', $temp['reference']);
				if($temp['chosen'] == 'true')
//					$q_units[$temp['reference']] = $temp['reference'] . ' - ' . $temp['title'];
					$q_units[] = $temp;
			}
			$units_ddl[] = $q_units;
		}
		return $units_ddl[0];
		$final_ddl = array();
		foreach($units_ddl AS $unit_entry)
		{
			for($i=0;$i<count($unit_entry);$i++)
				$final_ddl[] = array($unit_entry[$i]['reference'], $unit_entry[$i]['title']);
		}
		return $final_ddl;
	}
}
?>