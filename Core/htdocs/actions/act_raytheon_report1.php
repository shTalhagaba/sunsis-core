 <?php
class raytheon_report1 implements IAction
{
	public function execute(PDO $link)
	{
		header("Content-Type: application/vnd.ms-excel");
		header('Content-Disposition: attachment; filename=file.csv');
		if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false)
		{
			header('Pragma: public');
			header('Cache-Control: max-age=0');
		}
		$line = '';

		$line .= "Assessor,L03,Surname,Firstname,";
		$line .= "Framework Title,Qualification Number,Qual Title,Exempt,Unit Reference,Credits,GLH,Unit Title,Owner Ref,Mandatory,Percentage";
		echo $line . "\r\n";

		$quals = DAO::getResultset($link, "SELECT
(SELECT CONCAT(firstnames, ' ', surname) FROM users WHERE id = tr.`assessor`) AS assessor,
tr.id AS tr_id,
tr.l03,
tr.surname,
tr.`firstnames`,
frameworks.`title` AS f_title,
student_qualifications.`id` as q_id,
student_qualifications.`internaltitle`,
student_qualifications.`aptitude`
FROM tr INNER JOIN student_qualifications ON tr.id = student_qualifications.`tr_id`
INNER JOIN frameworks ON student_qualifications.`framework_id` = frameworks.`id`
WHERE frameworks.`active` = 1
", DAO::FETCH_ASSOC);

		foreach($quals AS $q)
		{
			$units = $this->getUnits($link, $q['q_id'], $q['tr_id']);//pre($units);
			foreach($units AS $unit)
			{
				$line = $q['assessor']  . ', ';
				$line .= '"' . $q['l03']  . '", ';
				$line .= $q['surname']  . ', ';
				$line .= $q['firstnames']  . ', ';
				$line .= str_replace(',', ';', $q['f_title']) . ', ';
				$line .= $q['q_id']  . ', ';
				$line .= str_replace(',', ';', $q['internaltitle'])  . ', ';
				$line .= $q['aptitude']  . ', ';
				$line .= isset($unit['reference'])?$unit['reference'] . ", ":", ";
				$line .= isset($unit['credits'])?$unit['credits'] . ", ":", ";
				$line .= isset($unit['glh'])?$unit['glh'] . ", ":", ";
				$line .= isset($unit['title'])?str_replace(',', ';', $unit['title']) . ", ":", ";
				$line .= isset($unit['owner_reference'])?$unit['owner_reference'] . ", ":", ";
				$line .= isset($unit['mandatory'])?$unit['mandatory'] . ", ":", ";
				$line .= isset($unit['percentage'])?$unit['percentage'] . ", ":", ";
				echo $line . "\r\n";
			}
		}
		echo "\r\n";
		exit;
	}

	private function getUnits(PDO $link, $qualification_id, $tr_id)
	{
		$qualification_id = str_replace('/', '', $qualification_id);

		$sql = <<<HEREDOC
SELECT
	 student_qualifications.id,
	 student_qualifications.evidences
FROM
	 student_qualifications
WHERE
	 REPLACE(student_qualifications.id, '/', '') = '$qualification_id'
	 AND student_qualifications.tr_id = '$tr_id';
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