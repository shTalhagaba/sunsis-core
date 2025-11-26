<?php
class ViewER2 extends View
{

	public static function getInstance($contract_id, $submission)
	{
		$key = 'view_'.__CLASS__;
		
		if(!isset($_SESSION[$key]))
		{
			// Create new view object
			$sql = <<<HEREDOC
SELECT 
	* 
FROM 
	ilr 
	LEFT JOIN contracts on contracts.id = ilr.contract_id
WHERE 
	contract_id = $contract_id AND submission = '$submission'
HEREDOC;
			$view = $_SESSION[$key] = new ViewER2();
			$view->setSQL($sql);
			
			
			// Add view filters
			$options = array(
				0=>array(20,20,null,null),
				1=>array(50,50,null,null),
				2=>array(100,100,null,null),
				3=>array(200,200,null,null),
				4=>array(0, 'No limit', null, null));
			$f = new DropDownViewFilter(View::KEY_PAGE_SIZE, $options, 0, false);
			$f->setDescriptionFormat("Records per page: %s");
			$view->addFilter($f);
/*
			$options = array(
			0=>array(1, 'Assessor', null, 'ORDER BY assessor, group_code, employer, learner_name, last_review_date'),
			1=>array(2, 'L03', null, 'ORDER BY l03'),
			2=>array(3, 'Leaner', null, 'ORDER BY learner_name'));

			$f = new DropDownViewFilter('order_by', $options, 1, false);
			$f->setDescriptionFormat("Sort by: %s");
			$view->addFilter($f);
			
			// Date filters	
			$dateInfo = getdate();
			$weekday = $dateInfo['wday']; // 0 (Sun) -> 6 (Sat)
			$timestamp = time()  - ((60*60*24) * $weekday);
			
			// Rewind by a further 1 week
			$timestamp = $timestamp - ((60*60*24) * 7);
					
			// Start Date Filter
			$format = "WHERE DATE_ADD(IF(assessment_date_subquery.assessment_date IS NOT NULL,assessment_date_subquery.assessment_date, tr.start_date), INTERVAL contracts.frequency WEEK) >= '%s'";
			$f = new DateViewFilter('start_date', $format, '');
			$f->setDescriptionFormat("From: %s");
			$view->addFilter($f);
	
			// Calculate the timestamp for the end of this week
			$timestamp = time() + ((60*60*24) * (7 - $weekday));
			
			$format = "WHERE DATE_ADD(IF(assessment_date_subquery.assessment_date IS NOT NULL,assessment_date_subquery.assessment_date, tr.start_date), INTERVAL contracts.frequency WEEK) <= '%s'";
			$f = new DateViewFilter('end_date', $format, '');
			$f->setDescriptionFormat("To: %s");
			$view->addFilter($f);	
			
			$options = "SELECT username, CONCAT(firstnames,' ',surname), null, CONCAT('WHERE groups.assessor=',char(39),username,char(39),' or tr.assessor=' , char(39),username, char(39)) FROM users where type=3";
			$f = new DropDownViewFilter('filter_assessor', $options, null, true);
			$f->setDescriptionFormat("Assessor: %s");
			$view->addFilter($f);
			
			$options = array(
				0=>array(0, 'All reviews', null,null),
				1=>array(1, 'Future reviews', null, 'WHERE DATE_ADD(IF(assessment_date_subquery.assessment_date IS NOT NULL,assessment_date_subquery.assessment_date, tr.start_date), INTERVAL contracts.frequency WEEK) > CURRENT_DATE'),
				2=>array(2, 'Missed reviews', null, 'WHERE DATE_ADD(IF(assessment_date_subquery.assessment_date IS NOT NULL,assessment_date_subquery.assessment_date, tr.start_date), INTERVAL contracts.frequency WEEK) < CURRENT_DATE'));
				
			$f = new DropDownViewFilter('filter_assessor_status', $options, null, false);
			$f->setDescriptionFormat("Reviews: %s");
			$view->addFilter($f);
*/			
			
		}

		return $_SESSION[$key];
	}
	
	
	public function render(PDO $link)
	{
		/* @var $result pdo_result */
		$st = $link->query($this->getSQL());
		if($st) 
		{
			//echo $this->getViewNavigator();
			echo '<div align="center"><table class="resultset" border="0" cellspacing="0" cellpadding="6">';
			echo <<<HEREDOC
	<thead>
	<tr>
		<th>L03</th>
		<th>Aims in ILR</th>
		<th>Aims at Training Record</th>
	</tr>
	</thead>
HEREDOC;


			echo '<tbody>';
			$count=0;
			while($row = $st->fetch())
			{
				//$ilrxml = new SimpleXMLElement($row['ilr']);
				$ilrxml = XML::loadSimpleXML($row['ilr']);
				
				foreach ($ilrxml->main as $item) 
				{
					$a14 = $item->A14;
					$a18 = $item->A18;
					$a51a = $item->A51a;
					$a16 = $item->A16;
					$a09 = $item->A09;
					$a27 = Date::toMySQL($item->A27);
					$a28 = Date::toMySQL($item->A28);
					
					
					$a31 = $item->A31;
					if($a31=='' || $a31=='00000000' || $a31 == 'dd/mm/yyyy')
					{	
						$a31 = 'NULL';
						$h = "IS";			
					}
					else
					{
						$a31 = "'" .Date::toMySQL($item->A31) . "'";
						$h= "=";
					}
					
					
					$a40 = $item->A40;
					if($a40=='' || $a40=='00000000' || $a40 == 'dd/mm/yyyy')
					{
						$a40 = 'NULL';
						$h2 = "IS";				
					}
					else
					{
						$a40 = "'" . Date::toMySQL($item->A40) . "'";
						$h2 = "=";
					}
											
					$sql = "select count(*) from student_qualifications where tr_id = {$row['tr_id']} and REPLACE(id,'/','') = '$a09' and aptitude=0 and start_date='$a27' and end_date = '$a28' and actual_end_date $h $a31 and achievement_date $h2 $a40 and a14='$a14' and a16='$a16' and a18='$a18' and a51a='$a51a'";
				//	if($row['tr_id']==2995)
				//		throw new Exception($sql); 
					$synched = DAO::getSingleValue($link, $sql);
					if($synched==0)
					{
						$count++;
						echo HTML::viewrow_opening_tag('do.php?_action=edit_ilr2009&submission=' . rawurlencode($row['submission']) . '&tr_id=' . rawurlencode($row['tr_id']) . '&contract_id=' . rawurlencode($row['contract_id']) . '&L03=' . rawurlencode($row['L03']) );
						echo '<td align="left">' . $count . '</td>';
						echo '<td align="left">' . HTML::cell($row['L03']) . '</td>';
						//echo '<td align="left">' . HTML::cell($aims) . '</td>';
						//echo '<td align="left">' . HTML::cell($sqs) . '</td>';
						echo '</tr>';
					}
				}
				
				foreach ($ilrxml->subaim as $item) 
				{
					$a14 = $item->A14;
					$a18 = $item->A18;
					$a51a = $item->A51a;
					$a16 = $item->A16;
					$a09 = $item->A09;
					$a27 = $item->A27;
					$a28 = $item->A28;
					$a31 = $item->A31;
					$a40 = $item->A40;

					$synched = DAO::getSingleValue($link, "select count(*) from student_qualifications where tr_id = {$row['tr_id']} and REPLACE(id,'/','') = '$a09' and aptitude=0");
					if($synched==0)
					{
						echo '<td align="left">' . HTML::cell($row['L03']) . '</td>';
						//echo '<td align="left">' . HTML::cell($aims) . '</td>';
						//echo '<td align="left">' . HTML::cell($sqs) . '</td>';
						echo '</tr>';
					}
				}
			}
			
			echo '</tbody></table></div align="center">';
			//echo $this->getViewNavigator();
			
		}
		else
		{
			throw new DatabaseException($link, $this->getSQL());
		}
		
	}
}
?>