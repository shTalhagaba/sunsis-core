<?php
class save_tabular_view2 implements IAction
{
	public function execute(PDO $link)
	{
		$qualification_id = isset($_REQUEST['qualification_id'])?$_REQUEST['qualification_id']:'';
		$internaltitle = isset($_REQUEST['internaltitle'])?$_REQUEST['internaltitle']:'';
		$framework_id = isset($_REQUEST['framework_id'])?$_REQUEST['framework_id']:'';
		$tr_id = isset($_REQUEST['tr_id'])?$_REQUEST['tr_id']:'';
		$data = isset($_REQUEST['data'])?$_REQUEST['data']:'';
		$data2 = isset($_REQUEST['data2'])?$_REQUEST['data2']:'';
		$proportion = isset($_REQUEST['proportion'])?$_REQUEST['proportion']:'';
		
		// Validation
		if(!$tr_id){
			throw new Exception("Missing value 'tr_id'. Operation aborted.");
		}
		if(!$qualification_id){
			throw new Exception("Missing value 'qualification_id'. Operation aborted.");
		}
		if(!$internaltitle){
			throw new Exception("Missing value 'internaltitle'. Operation aborted.");
		}
		if(!$data){
			throw new Exception("Missing value 'data'. Operation aborted.");
		}
		if(!$data2){
			throw new Exception("Missing value 'data2'. Operation aborted.");
		}
		
		// Parsing xml into unit Array		
		$units = Array();
		//$pageDom = new DomDocument();
		//$pageDom->loadXML($data);
		$pageDom = XML::loadXmlDom($data);
		$e = $pageDom->getElementsByTagName('unit');
		foreach($e as $node)
		{
			$units[$node->getAttribute('owner_reference')]['percentage'] = $node->getAttribute('percentage');
			$units[$node->getAttribute('owner_reference')]['chosen'] = $node->getAttribute('chosen');
			$units[$node->getAttribute('owner_reference')]['proportion'] = $node->getAttribute('proportion');
		}

		// Parsing xml into evidneces Array		
		$evidences = Array();
		$index=0;
		//$pageDom = new DomDocument();
		
		//$pageDom->loadXML($data2);
		$pageDom = XML::loadXmlDom($data2);
		$e = $pageDom->getElementsByTagName('evidence');
		foreach($e as $node)
		{	
			$index++;
			$evidences[$index]['method'] = $node->getAttribute('method');
			$evidences[$index]['reference'] = $node->getAttribute('reference');
			$evidences[$index]['status'] = $node->getAttribute('status');
			$evidences[$index]['date'] = $node->getAttribute('date');
			$evidences[$index]['comments'] = $node->getAttribute('comments');
		}
		
		
		$sql = "select evidences from student_qualifications where tr_id = '$tr_id' and id = '$qualification_id' and internaltitle = '$internaltitle' and framework_id = '$framework_id'";
		$qual = DAO::getSingleValue($link, $sql);

		if($qual!='')
		{
			//$pageDom = new DomDocument();
			//$pageDom->loadXML(utf8_encode($qual));
			$pageDom = XML::loadXmlDom(mb_convert_encoding($qual,'UTF-8'));
			$e = $pageDom->getElementsByTagName('unit');
			foreach($e as $node)
			{
				$node->setAttribute('percentage',$units[$node->getAttribute('owner_reference')]['percentage']);				
				$node->setAttribute('chosen',$units[$node->getAttribute('owner_reference')]['chosen']);				
				$node->setAttribute('proportion',$units[$node->getAttribute('owner_reference')]['proportion']);				
			}

			$index = 0;
			$e = $pageDom->getElementsByTagName('evidence');
			foreach($e as $node)
			{
				$index++;
				$node->setAttribute('method',$evidences[$index]['method']);
				$node->setAttribute('reference',$evidences[$index]['reference']);
				$node->setAttribute('status',$evidences[$index]['status']);				
				$node->setAttribute('date',$evidences[$index]['date']);				
				$node->setAttribute('comments',$evidences[$index]['comments']);				
			}
			
			
			// Recalculating percentage
			$units = $pageDom->getElementsByTagName('unit');
			$total_unit_percentage = 0;
			
			$total = 0;
			$comp = 0;
			$ns = 0;
			$behind = 0;
						
			foreach($units as $unit)
			{
				$unitPercentage = $unit->getAttribute('percentage');
				$unitProportion = $unit->getAttribute('proportion');
				$total_unit_percentage += ($unitPercentage * $unitProportion / 100);
				
				$total++;
				if($unitPercentage==100)
					$comp++;
				elseif($unitPercentage>0)
					$behind++;
				else
					$ns++;
			}
			
			$roots = $pageDom->getElementsByTagName('root');
			foreach($roots as $root)
				$root->setAttribute("percentage", $total_unit_percentage);
			

			$qual = $pageDom->saveXML();
			$qual=substr($qual,21);
			
			$qual= str_replace("'","apos;",$qual);
			
			$sql2 = "update student_qualifications set units = '$total', unitsCompleted = '$comp', unitsNotStarted = '$ns', unitsBehind = '$behind', proportion = '$proportion', unitsUnderAssessment = $total_unit_percentage, evidences = '$qual' where tr_id=$tr_id and id = '$qualification_id' and internaltitle = '$internaltitle' and framework_id = '$framework_id'";
			DAO::execute($link, $sql2);
            DAO::execute($link, "UPDATE student_qualifications SET units = (LENGTH(evidences) - LENGTH(REPLACE(evidences, 'chosen=\"true\"', ''))) / LENGTH('chosen=\"true\"') where tr_id=$tr_id and id = '$qualification_id' and internaltitle = '$internaltitle' and framework_id = '$framework_id'");

			TrainingRecord::updateProgressStatistics($link, $tr_id);
				
			//	throw new Exception(implode($link->errorInfo()).'..........'.$sql2, $link->errorCode());


			// Documents
			//$pageDom = new DomDocument();
			//$pageDom->loadXML(utf8_encode($qual));
			$pageDom = XML::loadXmlDom(mb_convert_encoding($qual,'UTF-8'));
			$e = $pageDom->getElementsByTagName('evidence');
			$no = 0;
			$qid = str_replace("/","",$qualification_id);
			foreach($e as $node)
			{
				$no++;
				$field_name = 'uploadedfile'.$no;
				$target_directory = $tr_id . "/" . $qid ."/" . $no;
				$valid_extensions = array('pdf', 'doc', 'docx', 'xls', 'xlsx', 'csv', 'txt', 'xml', 'zip', 'rar', '7z');
				Repository::processFileUploads($field_name, $target_directory, $valid_extensions);
			}
				
		}
		//http_redirect($_SESSION['bc']->getPrevious());
		http_redirect("do.php?_action=read_training_record&id=".$tr_id);		
	}
}
?>