<?php
class ajax_get_milestones implements IAction
{
	public function execute(PDO $link)
	{
		header('Content-Type: text/xml; charset=iso-8859-1');
		 
		$qualification_id = isset($_REQUEST['id'])?$_REQUEST['id']:'';
		$framework_id = isset($_REQUEST['framework_id'])?$_REQUEST['framework_id']:'';
		$internaltitle= isset($_REQUEST['internaltitle'])?addslashes((string)$_REQUEST['internaltitle']):'';

$sql = <<<HEREDOC
SELECT
	*
FROM
	milestones 
where framework_id = '$framework_id' and qualification_id = '$qualification_id' and internaltitle = '$internaltitle'
HEREDOC;
			$st = $link->query($sql);
			if($st) 
			{
				$mobject = '<milestones>'; 
				while($row = $st->fetch())
				{
					$mobject .= '<unit value=' . '"' . $row['unit_id'] . '">';
					$mobject .= '<month value=' . '"' . $row['month_1'] . '"></month>';
					$mobject .= '<month value=' . '"' . $row['month_2'] . '"></month>';
					$mobject .= '<month value=' . '"' . $row['month_3'] . '"></month>';
					$mobject .= '<month value=' . '"' . $row['month_4'] . '"></month>';
					$mobject .= '<month value=' . '"' . $row['month_5'] . '"></month>';
					$mobject .= '<month value=' . '"' . $row['month_6'] . '"></month>';
					$mobject .= '<month value=' . '"' . $row['month_7'] . '"></month>';
					$mobject .= '<month value=' . '"' . $row['month_8'] . '"></month>';
					$mobject .= '<month value=' . '"' . $row['month_9'] . '"></month>';
					$mobject .= '<month value=' . '"' . $row['month_10'] . '"></month>';
					$mobject .= '<month value=' . '"' . $row['month_11'] . '"></month>';
					$mobject .= '<month value=' . '"' . $row['month_12'] . '"></month>';
					$mobject .= '<month value=' . '"' . $row['month_13'] . '"></month>';
					$mobject .= '<month value=' . '"' . $row['month_14'] . '"></month>';
					$mobject .= '<month value=' . '"' . $row['month_15'] . '"></month>';
					$mobject .= '<month value=' . '"' . $row['month_16'] . '"></month>';
					$mobject .= '<month value=' . '"' . $row['month_17'] . '"></month>';
					$mobject .= '<month value=' . '"' . $row['month_18'] . '"></month>';
					$mobject .= '<month value=' . '"' . $row['month_19'] . '"></month>';
					$mobject .= '<month value=' . '"' . $row['month_20'] . '"></month>';
					$mobject .= '<month value=' . '"' . $row['month_21'] . '"></month>';
					$mobject .= '<month value=' . '"' . $row['month_22'] . '"></month>';
					$mobject .= '<month value=' . '"' . $row['month_23'] . '"></month>';
					$mobject .= '<month value=' . '"' . $row['month_24'] . '"></month>';
					$mobject .= '<month value=' . '"' . $row['month_25'] . '"></month>';
					$mobject .= '<month value=' . '"' . $row['month_26'] . '"></month>';
					$mobject .= '<month value=' . '"' . $row['month_27'] . '"></month>';
					$mobject .= '<month value=' . '"' . $row['month_28'] . '"></month>';
					$mobject .= '<month value=' . '"' . $row['month_29'] . '"></month>';
					$mobject .= '<month value=' . '"' . $row['month_30'] . '"></month>';
					$mobject .= '<month value=' . '"' . $row['month_31'] . '"></month>';
					$mobject .= '<month value=' . '"' . $row['month_32'] . '"></month>';
					$mobject .= '<month value=' . '"' . $row['month_33'] . '"></month>';
					$mobject .= '<month value=' . '"' . $row['month_34'] . '"></month>';
					$mobject .= '<month value=' . '"' . $row['month_35'] . '"></month>';
					$mobject .= '<month value=' . '"' . $row['month_36'] . '"></month>';
					$mobject .= '</unit>';					
				}
				$mobject .= '</milestones>';
									
//				if(sizeof($mobject)==0)
//					unset($mobject);
			}
		
		
		
		if(!is_null($mobject))
		{
			echo '<?xml version="1.0" encoding="iso-8859-1"?>'.$mobject;
		}
		else
		{
			/* echo '<?xml version="1.0" encoding="iso-8859-1"?><error>No qualification found with id: {$qan}</error>'; */
			echo '<?xml version="1.0" encoding="iso-8859-1"?><error></error>';
		}
	}
}
?>