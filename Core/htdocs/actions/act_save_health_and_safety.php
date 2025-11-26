<?php 
class save_health_and_safety implements IAction
{
	public function execute(PDO $link)
	{
		$emp_id = isset($_REQUEST['emp_id'])?$_REQUEST['emp_id']:'';
		$xml = isset($_REQUEST['xml'])?$_REQUEST['xml']:'';

		//$xmlreviews = new SimpleXMLElement($xml);
		$xmlreviews = XML::loadSimpleXML($xml);
		$values = '';
		foreach($xmlreviews->review as $review)
		{	
            $id = $review->id;
			$comments = str_replace("'","\'",$review->comments);
			$assessor = str_replace("'","\'",$review->assessor);
			$compliant = $review->compliant;
			$paperwork = ($review->paperwork=='true')?1:0;

			$d = new Date($review->lastassessment);
			$last_assessment = "'" . $d->getYear() . '-' . $d->getMonth() . '-' . $d->getDays() . "'";

			$d = new Date($review->nextassessment);
			$next_assessment = "'" . $d->getYear() . '-' . $d->getMonth() . '-' . $d->getDays() . "'";

			$age_range = $review->agerange;
			if($review->nextassessment=='' || $review->nextassessment=='dd/mm/yyyy')
			{
				$pl_date = "NULL";
                $el_date = "NULL";
			}
			else
			{
				$d = new Date($review->pldate);
				$pl_date = "'" . $d->getYear() . '-' . $d->getMonth() . '-' . $d->getDays() . "'";
                $d = new Date($review->eldate);
                $el_date = "'" . $d->getYear() . '-' . $d->getMonth() . '-' . $d->getDays() . "'";
			}
			$pl_insurance = $review->plinsurance;
            $el_insurance = $review->elinsurance;

			$values .= '(' . $id . ',' . $emp_id . ',' . $last_assessment . ',' . $next_assessment . "," . "'" . $assessor . "'"  . ',' . "'" . $comments . "'," . $compliant . ",'" . $paperwork . "','" . $age_range . "',"  .  $pl_date . ",'" . $pl_insurance . "'," . $el_date . ",'" . $el_insurance ."'),";
		}


		$values = substr($values, 0, -1);

		$link->beginTransaction();
		try
		{

if($values!='')
{
	
//DAO::execute($link, "delete from health_safety where location_id = '$emp_id'");
	
				
$sql2 = <<<HEREDOC
replace into
health_safety (id, location_id, last_assessment, next_assessment, assessor, comments, complient, paperwork_received, age_range, pl_date, pl_insurance, el_date, el_insurance)
values 
$values;	
HEREDOC;


		//DAO::execute($link, $sql2);
		$st = $link->query($sql2);
		if(!$st)
			throw new Exception($link->errorInfo());
}

			$link->commit();
			}
			catch(Exception $e)
			{
				$link->rollback();
				throw new WrappedException($e);
			}

	}
}	
?>
