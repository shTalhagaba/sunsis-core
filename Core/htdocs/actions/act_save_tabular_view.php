<?php
class save_tabular_view implements IAction
{
	public function execute(PDO $link)
	{
		$qualification_id = isset($_REQUEST['qualification_id'])?$_REQUEST['qualification_id']:'';
		$internaltitle = isset($_REQUEST['internaltitle'])?$_REQUEST['internaltitle']:'';
		$framework_id = isset($_REQUEST['framework_id'])?$_REQUEST['framework_id']:'';
		$tr_id = isset($_REQUEST['tr_id'])?$_REQUEST['tr_id']:'';
		$data = isset($_REQUEST['data'])?$_REQUEST['data']:'';
		$data2 = isset($_REQUEST['data2'])?$_REQUEST['data2']:'';
		$proportion = isset($_REQUEST['proportion'])?$_REQUEST['proportion']:0;
		$filenames = isset($_REQUEST['filenames'])?$_REQUEST['filenames']:'';


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
			$units[$node->getAttribute('owner_reference')]['grade'] = $node->getAttribute('grade');
			$units[$node->getAttribute('owner_reference')]['ustatus'] = $node->getAttribute('ustatus');
			$units[$node->getAttribute('owner_reference')]['comments'] = $node->getAttribute('comments');
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
			$evidences[$index]['f'] = $node->getAttribute('f');
			$evidences[$index]['status'] = $node->getAttribute('status');
			$evidences[$index]['date'] = $node->getAttribute('date');
			$evidences[$index]['comments'] = addslashes((string)$node->getAttribute('comments'));
		}

		$inter_title = addslashes((string)$internaltitle);
		$sql = "select evidences from student_qualifications where tr_id = '$tr_id' and id = '$qualification_id' and internaltitle = '$inter_title' and framework_id = '$framework_id'";
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
				$node->setAttribute('grade',$units[$node->getAttribute('owner_reference')]['grade']);
				$node->setAttribute('ustatus',$units[$node->getAttribute('owner_reference')]['ustatus']);
				$node->setAttribute('comments',$units[$node->getAttribute('owner_reference')]['comments']);
			}

			$index = 0;
			$e = $pageDom->getElementsByTagName('evidence');
			foreach($e as $node)
			{
				$index++;
				$qid = str_replace("/","",$qualification_id);

//				if($evidences[$index]['f']=='true' && $_FILES['newfile']['name']!='')
				if(isset($evidences[$index]['f']))
				{
					if($evidences[$index]['f']=='true')
					{
						//$node->setAttribute('href',(DATA_ROOT.'/uploads/am_demo/' . $tr_id . '/' . $qid . '/'));
						//$node->setAttribute('filename',basename( $_FILES['newfile']['name']));
						$node->setAttribute('filename',$filenames);
					}
				}
				if(isset($evidences[$index]['status']))
				{
					$node->setAttribute('status',$evidences[$index]['status']);
					$node->setAttribute('date',$evidences[$index]['date']);
					$node->setAttribute('comments',$evidences[$index]['comments']);
				}
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
				elseif($unitPercentage==0)
					$ns++;
			}

			$roots = $pageDom->getElementsByTagName('root');
			foreach($roots as $root)
				$root->setAttribute("percentage", $total_unit_percentage);


			$qual = $pageDom->saveXML();
			$qual=substr($qual,21);

			$qual= str_replace("'","apos;",$qual);
			$inter_title = addslashes((string)$internaltitle);
			$proportion = $proportion == '' ? 0 : $proportion;
			$sql2 = "update student_qualifications set modified = CURDATE(), units = '$total', unitsCompleted = '$comp', unitsNotStarted = '$ns', unitsOnTrack = '0', unitsBehind = '$behind', proportion = '$proportion', unitsUnderAssessment = $total_unit_percentage, evidences = '$qual' where tr_id=$tr_id and id = '$qualification_id' and internaltitle = '$inter_title' and framework_id = '$framework_id'";
			DAO::execute($link, $sql2);
            DAO::execute($link, "UPDATE student_qualifications SET units = (LENGTH(evidences) - LENGTH(REPLACE(evidences, 'chosen=\"true\"', ''))) / LENGTH('chosen=\"true\"') where tr_id=$tr_id and id = '$qualification_id' and internaltitle = '$inter_title' and framework_id = '$framework_id';");


			TrainingRecord::updateProgressStatistics($link, $tr_id);


			http_redirect("do.php?_action=read_training_record&id=".$tr_id);

			//	throw new Exception(implode($link->errorInfo()).'..........'.$sql2, $link->errorCode());



			/*
				 if($_FILES['newfile']['name']!='')
				 {

					 //$file_size= $_FILES[$uploadedfile]['size'];

					 //if($file_size >= (100*1024))
					 //	pre("The maximum allowed file size is 100K, currently it is " . $file_size/1024);

					 if(!(file_exists(DATA_ROOT."/uploads/".DB_NAME)))
						 mkdir(DATA_ROOT."/uploads/".DB_NAME);

					 if(!(file_exists(DATA_ROOT."/uploads/".DB_NAME."/".$tr_id)))
						 mkdir(DATA_ROOT."/uploads/".DB_NAME."/".$tr_id);

					 $qid = str_replace("/","",$qualification_id);

					 if(!(file_exists(DATA_ROOT."/uploads/".DB_NAME."/".$tr_id."/".$qid)))
						 mkdir(DATA_ROOT."/uploads/".DB_NAME."/".$tr_id."/".$qid);

					 $target_path = DATA_ROOT."/uploads/".DB_NAME . "/" . $tr_id . "/" . $qid . "/" . basename( $_FILES['newfile']['name']);

					 if(!(move_uploaded_file($_FILES['newfile']['tmp_name'], $target_path)))
						 throw new Exception("There was an error uploading the file, please try again!");
				 }


			 // Documents
			 //$pageDom = new DomDocument();
			 //$pageDom->loadXML(utf8_encode($qual));
			 $pageDom = XML::loadXmlDom(utf8_encode($qual));
			 $e = $pageDom->getElementsByTagName('evidence');
			 $no = 0;
			 foreach($e as $node)
			 {
				 $no++;
				 $uploadedfile = 'uploadedfile'.$no;
				 if(isset($_FILES[$uploadedfile]['name']))
					 if($_FILES[$uploadedfile]['name']!='')
					 {

						 //$file_size= $_FILES[$uploadedfile]['size'];

						 //if($file_size >= (100*1024))
						 //	pre("The maximum allowed file size is 100K, currently it is " . $file_size/1024);

						 if(!(file_exists(DATA_ROOT."/uploads/".DB_NAME)))
							 mkdir(DATA_ROOT."/uploads/".DB_NAME);

						 if(!(file_exists(DATA_ROOT."/uploads/".DB_NAME."/".$tr_id)))
							 mkdir(DATA_ROOT."/uploads/".DB_NAME."/".$tr_id);

						 $qid = str_replace("/","",$qualification_id);

						 if(!(file_exists(DATA_ROOT."/uploads/".DB_NAME."/".$tr_id."/".$qid)))
							 mkdir(DATA_ROOT."/uploads/".DB_NAME."/".$tr_id."/".$qid);

						 if(!(file_exists(DATA_ROOT."/uploads/".DB_NAME."/".$tr_id."/".$qid."/".$no)))
							 mkdir(DATA_ROOT."/uploads/".DB_NAME."/".$tr_id."/".$qid."/".$no);


						 $target_path = DATA_ROOT."/uploads/".DB_NAME . "/" . $tr_id . "/" . $qid ."/" . $no . "/" . basename( $_FILES[$uploadedfile]['name']);

						 if(!(move_uploaded_file($_FILES[$uploadedfile]['tmp_name'], $target_path)))
							 throw new Exception("There was an error uploading the file, please try again!");
					 }
			 }
		 */
		}
		//http_redirect($_SESSION['bc']->getCurrent());
	}
}
?>