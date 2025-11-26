<?php
class ViewER1 extends View
{

	public static function getInstance($contract_id, $submission)
	{
		$key = 'view_'.__CLASS__.$contract_id.$submission;
		
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
			$view = $_SESSION[$key] = new ViewER1();
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
		<th>A31 in 2009</th>
		<th>A31 in W13</th>
		<th>A09 in 2009</th>
		<th>A09 in W13</th>
		<th>Comments</th>
	</tr>
	</thead>
HEREDOC;


			echo '<tbody>';
			$n = 0;
			while($row = $st->fetch())
			{
			
				try
				{
					$ilr = Ilr2009::loadFromXML($row['ilr']);
				}
				catch(Exception $e)
				{
					throw new Exception($row['ilr']);
				}
				$tr_id = $row['tr_id'];
				$contract_id = $row['contract_id'];
				$ilr2 = DAO::getSingleValue($link, "select ilr from ilr where tr_id = $tr_id and submission = 'W02'");

				if($ilr2!='')
				{

					$ilr2 = Ilr2009::loadFromXML($ilr2);
					$n++;
					echo '<td align="center">' . HTML::cell($n) . '</td>';
					echo '<td align="left">' . HTML::cell($row['L03']) . '</td>';
					echo '<td align="left">' . HTML::cell($ilr->learnerinformation->subaims) . '</td>';
					echo '<td align="left">' . HTML::cell($ilr2->learnerinformation->subaims) . '</td>';
					if(trim($ilr->learnerinformation->subaims) == trim($ilr2->learnerinformation->subaims))
						echo '<td align="left">' . HTML::cell("Ok") . '</td>';
					else
						echo '<td align="left">' . HTML::cell("dksj fksjd fjks d") . '</td>';
					
				//	echo '<td align="left">' . HTML::cell($a091) . '</td>';
				//	echo '<td align="left">' . HTML::cell($a092) . '</td>';
					echo '</tr>';
				}
				
			}
			
			echo '</tbody></table></div align="center">';
		}
		else
		{
			throw new DatabaseException($link, $this->getSQL());
		}
		
	}
}
?>