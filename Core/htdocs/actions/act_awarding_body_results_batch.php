<?php
class awarding_body_results_batch implements IAction
{
	public function execute(PDO $link)
	{

		$course_id = isset($_GET['course_id']) ? $_GET['course_id'] : '';

		$course = Course::loadFromDatabase($link, $course_id);
		$centre_reference = $course->awarding_body_centre;
		$dt = date("Ymd:Hi");
		$filename = $centre_reference;
		
header("Content-Type: text/html");
header('Content-Disposition: attachment; filename="' . $filename . '.ERA"');

// Internet Explorer requires two extra headers when downloading files over HTTPS
if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false)
{
	header('Pragma: public');
	header('Cache-Control: max-age=0');
}			
		echo 'UNB+UNOA+2+' . $centre_reference . '+EDEX+' . $dt . '+0001++++++0';
		echo "\r\n";
		echo 'UNG+RESULT+EXAMS+STUDENTSERVICES+'.$dt.'+0001+BT+6:1+++';
		echo "\r\n";
		echo 'UNH+0001+RESULT+6:1++1:F';
		echo "\r\n";
		$count=0;
		$st = $link->query("SELECT DATE_FORMAT(tr.target_date,'%Y%m%d') AS target_date2,DATE_FORMAT(tr.dob,'%Y%m%d') AS dob2,tr.id AS trid,tr.*,student_qualifications.id as qid, student_qualifications.awarding_body_reg, student_qualifications.evidences FROM tr  LEFT JOIN student_qualifications  ON student_qualifications.tr_id = tr.id AND student_qualifications.awarding_body_reg IS NOT NULL AND student_qualifications.awarding_body_reg != '' LEFT JOIN courses_tr ON courses_tr.tr_id = tr.id WHERE courses_tr.course_id = $course_id AND student_qualifications.qualification_type!='KS';");
		if($st) 
		{
			while($row = $st->fetch())
			{
				try
				{
					$qualification_code = DAO::getSingleValue($link,"select code from lookup_edexcel_basedata where id='" . $row['qid'] . "'");
					$flag=0;					
					$xml = mb_convert_encoding($row['evidences'],'UTF-8');
					//$pageDom = new DomDocument();
					//$pageDom->loadXML($xml);
					$pageDom = XML::loadXmlDom($xml);
					$evidences = $pageDom->getElementsByTagName('unit');
					foreach($evidences as $evidence)
					{
						$grade = $evidence->getAttribute('grade');
						if($grade!='')
						{
							if($flag==0)
							{
								echo 'RES1+'. $row['awarding_body_reg'] . '+' . $centre_reference .'+' . $qualification_code . '++++++++' . "\r\n" ;
								$count++;
								$flag=1;
							}
							$unit_qca = $evidence->getAttribute('reference');
							$unit_code = DAO::getSingleValue($link,"select code from lookup_edexcel_basedata where id='" . $unit_qca . "'");
							echo 'RES2+' . $unit_code . '+' . $grade . "\r\n";
							$count++;
						}
					}
				}
				catch(Exception $e)
				{
					throw new Exception('Error Code: ' . $e->getCode() . '<br>Error Description' . $e->getMessage());
				}
			}
			echo 'UNT+' . $count . '+0001';
			echo "\r\n";
			echo 'UNE+1+0001';
			echo "\r\n";
			echo 'UNZ+1+0001';
		}

	}
}
?>