<?php
class save_batch implements IAction
{
	public function execute(PDO $link)
	{
		$tr_id = isset($_REQUEST['tr_id'])?$_REQUEST['tr_id']:'';
		$qualification_id = isset($_REQUEST['qualification_id'])?$_REQUEST['qualification_id']:'';
		$internaltitle = isset($_REQUEST['internaltitle'])?$_REQUEST['internaltitle']:'';
		$framework_id = isset($_REQUEST['framework_id'])?$_REQUEST['framework_id']:'';
		$qualstructure = isset($_REQUEST['evidences'])?$_REQUEST['evidences']:'';
		$percentage= isset($_REQUEST['percentage'])?$_REQUEST['percentage']:'';
		$units = isset($_REQUEST['units'])?$_REQUEST['units']:'';
		$unitscompleted = isset($_REQUEST['unitscompleted'])?$_REQUEST['unitscompleted']:'';
		$unitsnotstarted = isset($_REQUEST['unitsnotstarted'])?$_REQUEST['unitsnotstarted']:'';
		$unitsbehind = isset($_REQUEST['unitsbehind'])?$_REQUEST['unitsbehind']:'';
		$unitsontrack = isset($_REQUEST['unitsontrack'])?$_REQUEST['unitsontrack']:'';
		$unitsunderassessment = isset($_REQUEST['unitsunderassessment'])?$_REQUEST['unitsunderassessment']:''; // Not being used at the moment
		$audit = isset($_REQUEST['audit'])?$_REQUEST['audit']:'';
		$auto_id = isset($_REQUEST['auto_id'])?$_REQUEST['auto_id']:'';
		$actual_end_date= isset($_REQUEST['actual_end_date'])?$_REQUEST['actual_end_date']:'nodate';
		$achievement_date= isset($_REQUEST['achievement_date'])?$_REQUEST['achievement_date']:'nodate';
		$learners = isset($_REQUEST['learners'])?$_REQUEST['learners']:'';

		
		$achieved = Array();
		$dates = Array();
		//$pageDom = new DomDocument;
		//$pageDom->loadXML($qualstructure);
		$pageDom = XML::loadXmlDom($qualstructure);
		$e = $pageDom->getElementsByTagName('evidence');
		foreach($e as $node)
		{
			if($node->getAttribute('status')=='a')
			{	
				if($node->parentNode->nodeName == 'unit')
					$unitreference = $node->parentNode->getAttribute('reference');
				elseif($node->parentNode->parentNode->nodeName == 'unit')
					$unitreference = $node->parentNode->parentNode->getAttribute('reference');
				elseif($node->parentNode->parentNode->parentNode->nodeName == 'unit')
					$unitreference = $node->parentNode->parentNode->parentNode->getAttribute('reference');
				elseif($node->parentNode->parentNode->parentNode->parentNode->nodeName == 'unit')
					$unitreference = $node->parentNode->parentNode->parentNode->parentNode->getAttribute('reference');
				else
					throw new Exception("This evidence is too deep down in the qualification");	
					
				$achieved[] = $unitreference.$node->getAttribute('title');
				$dates[] = $node->getAttribute('date');

			}	
		}

		
		
		//$ilrxml = new SimpleXMLElement($learners);
		$ilrxml = XML::loadSimpleXML($learners);
		foreach ($ilrxml->learner as $learner) 
		{

			$sql = "select evidences from student_qualifications where tr_id = $learner and id = '$qualification_id' and internaltitle = '$internaltitle' and framework_id = $framework_id";
			$qual = DAO::getSingleValue($link, $sql);

			//$pageDom = new DomDocument();
			//$pageDom->loadXML($qual);
			$pageDom = XML::loadXmlDom($qual);
			$e = $pageDom->getElementsByTagName('evidence');
			foreach($e as $node)
			{

				if($node->parentNode->nodeName == 'unit')
					$unitreference = $node->parentNode->getAttribute('reference');
				elseif($node->parentNode->parentNode->nodeName == 'unit')
					$unitreference = $node->parentNode->parentNode->getAttribute('reference');
				elseif($node->parentNode->parentNode->parentNode->nodeName == 'unit')
					$unitreference = $node->parentNode->parentNode->parentNode->getAttribute('reference');
				elseif($node->parentNode->parentNode->parentNode->parentNode->nodeName == 'unit')
					$unitreference = $node->parentNode->parentNode->parentNode->parentNode->getAttribute('reference');
				
				if(in_array(($unitreference.$node->getAttribute('title')),$achieved))
				{
					$index = array_search(($unitreference.$node->getAttribute('title')),$achieved);
					$node->setAttribute('status','a');
					$node->setAttribute('date',$dates[$index]);				
				}
			}

			// Recalculating percentage
		
			$units = $pageDom->getElementsByTagName('unit');
			$total_unit_percentage = 0;
			foreach($units as $unit)
			{
				$no_of_elements = 0;
				$total_element_percentage = 0;
				$elements = $unit->getElementsByTagName('element');
				foreach($elements as $element)
				{
					$no_of_elements++;
					
					$evidences = $element->getElementsByTagName('evidence');
					$achieved_evidences=0;
					$no_of_evidences = 0;
					foreach($evidences as $evidence)
					{
						$no_of_evidences++;
						if($evidence->getAttribute('status')=='a')
							$achieved_evidences++;
					}
					
					$elementPercentage = $achieved_evidences / $no_of_evidences * 100;
					$total_element_percentage += $elementPercentage;
					$element->setAttribute("percentage",$elementPercentage);
				}
				
				$unitPercentage = $total_element_percentage / $no_of_elements;
				$unitProportion = $unit->getAttribute('proportion');
				$total_unit_percentage += ($unitPercentage * $unitProportion / 100);
				$unit->setAttribute("percentage",$unitPercentage);
			}
			
			$roots = $pageDom->getElementsByTagName('root');
			foreach($roots as $root)
				$root->setAttribute("percentage", $total_unit_percentage);
			

			$qual = $pageDom->saveXML();
			$qual=substr($qual,21);
			
			$qual= str_replace("'","apos;",$qual);
			
			$sql2 = "update student_qualifications set unitsUnderAssessment = $total_unit_percentage, evidences = '$qual' where tr_id=$learner and id = '$qualification_id' and internaltitle = '$internaltitle' and framework_id = $framework_id";
			DAO::execute($link, $sql2);

			TrainingRecord::updateProgressStatistics($link, $learner);
						
		}
	}
}
?>