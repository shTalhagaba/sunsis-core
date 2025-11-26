<?php
class get_qualification_units implements IAction
{
	public function execute(PDO $link)
	{
		header('Content-Type: text/xml');
		$qualification_id = isset($_REQUEST['qualification_id']) ? $_REQUEST['qualification_id'] : '';
		$tr_id = isset($_REQUEST['tr_id']) ? $_REQUEST['tr_id'] : '';
		if ($qualification_id == '')
		{
			echo "No qualification selected";
		}
		else
		{
			$qualification_id = str_replace('/', '', $qualification_id);
			$units = array();
			$sql = <<<HEREDOC
SELECT
	 student_qualifications.id,
	 student_qualifications.evidences
FROM
	 student_qualifications
WHERE
	 student_qualifications.tr_id = '$tr_id' AND REPLACE(student_qualifications.id, '/', '') = '$qualification_id' ;
HEREDOC;

			$student_qualifications = DAO::getResultset($link, $sql, DAO::FETCH_ASSOC);
//			echo $sql; exit;
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
                    if(DB_NAME!="am_lead")
                    {
                        if(isset($temp['chosen']) && $temp['chosen'] == 'true')
                            $q_units[] = $temp;
                    }
                    else
                    {
                        $q_units[] = $temp;
                    }
				}
				$units_ddl[] = $q_units;
			}
			//echo json_encode($units_ddl);
			$s =  "<?xml version=\"1.0\" ?>\r\n";
			$s .=  "<select>\r\n";

			// First entry is empty
			$s .= "<option value=\"\"></option>\r\n";

			foreach($units_ddl AS $unit_entry)
			{
				for($i=0;$i<count($unit_entry);$i++)
					//$s .= '<option value="' . htmlspecialchars((string)$unit_entry[$i]['reference']) . '">' . htmlspecialchars((string)$unit_entry[$i]['title']) . "</option>\r\n";
					$s .= '<option value="' . htmlspecialchars(json_encode(['id'=>$unit_entry[$i]['reference'],'title'=>$unit_entry[$i]['title']])) . '">' . htmlspecialchars((string)$unit_entry[$i]['title']) . "</option>\r\n";
			}

			$s .= '</select>';
			echo $s;
		}
	}
}

