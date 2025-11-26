<?php
class save_framework_qualification implements IAction
{
	public function execute(PDO $link)
	{
		$qan = isset($_REQUEST['id'])?$_REQUEST['id']:'';
		$qan_before_editing = isset($_REQUEST['qan_before_editing'])?$_REQUEST['qan_before_editing']:'';
		$xml = isset($_REQUEST['xml'])?$_REQUEST['xml']:'';
		$fid = isset($_REQUEST['framework_id'])?$_REQUEST['framework_id']:'';
		$blob = isset($_REQUEST['blob'])?$_REQUEST['blob']:'';
		$units = isset($_REQUEST['units'])?$_REQUEST['units']:'';
		$proportion = isset($_REQUEST['proportion'])?$_REQUEST['proportion']:'';
		$milestones = isset($_REQUEST['milestones'])?$_REQUEST['milestones']:'';
		$mandatory = isset($_REQUEST['mandatoryunits'])?$_REQUEST['mandatoryunits']:'';

		if($xml == '')
		{
			throw new Exception("Missing or empty argument 'xml'");
		}

		// If the QAN has changed, delete the old qualification before
		// writing the new one, otherwise we end up with more than one qualification
		// registered against a course. Allowing for the future possibility of delivering
		// more than one qualification per course is deliberate, but this is unwanted
		// at the moment.


		if( ($qan != $qan_before_editing) && ($qan_before_editing != '') )
		{
			$old_qualification = FrameworkQualification::loadFromDatabase($link, $qan_before_editing, $framework_id, $internaltitle); /* @var $old_qualification Qualification */
			$old_qualification->delete($link);
		}

		$blob = str_replace(' op_title="null" ', ' ', $blob);
		// POST data submitted by modern browsers will be in UTF-8
		$xml = '<?xml version="1.0" encoding="utf-8"?>' . $xml;
		$qualification = FrameworkQualification::loadFromXML($xml); /* @var $qualification Qualification */
		$qualification->framework_id = $fid; // Khushnood
		$qualification->evidences = $blob;
		$qualification->units = $units;
		$qualification->units_required = $proportion == 'NaN' ? 0 : $proportion;
		$qualification->mandatory_units = $mandatory;
		$qualification->save($link, $fid);

		/*
  if(DB_NAME!='am_edexcel')
  {
		  $values = '';
		  $xmlmilestones = new SimpleXMLElement($milestones);
		  foreach($xmlmilestones->unit as $unit)
		  {
			  $values .= '(' . $fid . ',' . '"' . $qualification->id . '"' . ',' . '"' . $qualification->internaltitle . '"' . ',' . '"' . $unit['value'] . '"';

			  foreach($unit->month as $month)
			  {
				  $values .= ',' . $month;
			  }

			  $values .= '),';
		  }

		  $values = substr($values, 0, -1);


  // Delete existing milestones
		  $sql2 = <<<HEREDOC
  delete from
	  milestones
  where framework_id = '$fid' and qualification_id = '$qualification->id' and internaltitle = '$qualification->internaltitle'
  HEREDOC;
		  $st = $link->query($sql2);
		  if(!($st))
				  throw new Exception(implode($link->errorInfo()).'----'.$sql2);

  if($values!='')
  {
  // Add new milestones
		  $sql2 = <<<HEREDOC
  insert into
	  milestones
  values
	  $values;
  HEREDOC;

		  $st = $link->query($sql2);
		  if(!($st))
					  throw new Exception(implode($link->errorInfo()).'----'.$sql2);
  }
  }

  */
	}
}
?>