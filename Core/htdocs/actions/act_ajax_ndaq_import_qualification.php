<?php
class ajax_ndaq_import_qualification implements IAction
{
	public function execute(PDO $link)
	{
		 
		$id = isset($_REQUEST['id'])?$_REQUEST['id']:'';
		$options = isset($_REQUEST['options'])?$_REQUEST['options']:'1';
		$filled = isset($_REQUEST['filled'])?$_REQUEST['filled']:'';
		

		if($id == '')
		{
			throw new Exception("No qualification reference number supplied");
		}
		
		header('Content-Type: text/xml; charset=iso-8859-1');
		$notfound = 0;
		// First try to get it from cache
		$q = new Qualification();
		$id2 = str_replace("/","",$id);
		$sql = "SELECT * FROM central.qualifications WHERE replace(id,'/','')='$id2'";
		$st = $link->query($sql);	
		if($st)
		{
			if($row = $st->fetch())
			{
				$q->populate($row);
				if($row['evidences']=='')
				{
					$q->evidences=null;	
				}
			}
			else
			{
				$notfound = 1;
				$q=null;
			}
		}

		if($q!=null)
		{
			$xml = '<qualification mainarea="' . $q->mainarea . '" subarea="' . $q->subarea . '" title="' . $q->title . '" reference = "' . $q->id . '" type = "' . $q->qualification_type . '" subtype="" level="' . $q->level . '" sublevel="" guided_learning_hours="' . $q->guided_learning_hours . '" grades="' . $q->grades . '" awarding_body="' . $q->awarding_body . '" credits="' . $q->credits . '" regulation_start_date="' . $q->regulation_start_date . '" operational_start_date="' . $q->operational_start_date . '" operational_end_date="' . $q->operational_end_date . '" certification_end_date="' . $q->certification_end_date . '">'; 
			$xml .= '<description>' . $q->description . '</description>';
			$xml .= '<assessment_method>' . $q->assessment_method . '</assessment_method>';
			$xml .= $q->evidences;
			$xml .= '<url></url>'; 
			$xml .= '<timestamp></timestamp>';
  			$xml .= '<time_to_retrieve></time_to_retrieve>';
  			$xml .= '<time_to_process></time_to_process>';
  			$xml .= '</qualification>';
			echo '<?xml version="1.0" encoding="iso-8859-1"?>'.$xml;

		}
		else
		{
			if(DB_NAME=='am_edexcel')
			{
				$awarding_body = DAO::getSingleValue($link, "select AWARDING_BODY_CODE from lad201011.learning_aim where LEARNING_AIM_REF = '$id2'");
				if($awarding_body!='')
					echo '<?xml version="1.0" encoding="iso-8859-1"?><qualification awarding_body="' . $awarding_body . '"></qualification>';
				else
					throw new Exception("There is a technical issue with downloading this qualification");
			}
			else
			{
				$rits = new RITS();
				$xml = $rits->getQualification($id, $options);
				if(is_null($xml))
				{
					$xml = $rits->getUnit($id);
					if(is_null($xml))
						throw new Exception("No qualification or unit could be found in the QCA database with reference number $id");
					else
					{
						//$pageDom = new DomDocument();
						//$pageDom->loadXML($xml);
						$pageDom = XML::loadXmlDom($xml);
						$units = $pageDom->getElementsByTagName('unit');
						foreach($units as $unit)
						{
							$title = $unit->getAttribute('title');
							$level = $unit->getAttribute('level');
							$glh = $unit->getAttribute('glh');
							$credits = $unit->getAttribute('credits');
							$qualification_type = $unit->getAttribute('qualification_framework');
						}
						echo '<?xml version="1.0" encoding="iso-8859-1"?><qualification mainarea="" subarea="" title="'. $title .'" reference = "" type = "'.$qualification_type.'" subtype="" level="'.$level.'" sublevel="" guided_learning_hours="'.$glh.'" grades="" awarding_body="" credits="'.$credits.'" regulation_start_date="" operational_start_date="" operational_end_date="" certification_end_date=""><description>No Description</description><assessment_method></assessment_method><root percentage="0">'.$xml . '</root></qualification>';
					}
				}
				else
				{
					echo '<?xml version="1.0" encoding="iso-8859-1"?>'.$xml;
				}
			}
		}		
	}
}
?>