<?php
class ViewER3 extends View
{

	public static function getInstance()
	{
		$key = 'view_'.__CLASS__;
		
		if(!isset($_SESSION[$key]))
		{
			// Create new view object
			$sql = <<<HEREDOC
SELECT 
	* 
FROM 
	tr
HEREDOC;
			$view = $_SESSION[$key] = new ViewER3();
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
		<th>A27 in 2009</th>
		<th>A27 in W13</th>
		<th>A09 in 2009</th>
		<th>A09 in W13</th>
		<th>Comments</th>
	</tr>
	</thead>
HEREDOC;


			echo '<tbody>';
			$n = 0;
			$a27 = '';
			$a09 = '';
			$l03 = '';
			$submission = '';
			while($row = $st->fetch())
			{
			
				$tr_id = $row['id'];
				$data = Array();
				$sql2 = "SELECT * FROM ilr LEFT join contracts on contracts.id = ilr.contract_id WHERE tr_id = $tr_id and contracts.contract_year = 2008 ORDER BY contract_id, submission;";

				// and (submission='W13' or submission='W04')
				
				if($st2 = $link->query($sql2))
				{

					while($row2 = $st2->fetch())
					{

						$contract_year = $row2['contract_year'];
						
						if($contract_year == 2008)
							$ilr = Ilr2008::loadFromXML($row2['ilr']);
						elseif($contract_year == 2009)
							$ilr = Ilr2009::loadFromXML($row2['ilr']);						
						else
							throw new Exception($contract_year);
							
						for($sa=0;$sa<=(int)$ilr->learnerinformation->subaims;$sa++)
						{
							
							if(trim($a27)!=trim($ilr->aims[$sa]->A31) && trim($l03)==trim($row2['L03']) && trim($a09)==trim($ilr->aims[$sa]->A09))
							{

								if($ilr->aims[$sa]->A31!='' && $ilr->aims[$sa]->A31!='00000000')
								{
									$a311 = new Date($ilr->aims[$sa]->A31);
						//			$d = new Date('01/08/2009');
						//			if($a311->getDate()<$d->getDate() && $row2['submission']=="W04")
									{

										$tr = $row2['tr_id'];
										$start_date = DAO::getSingleValue($link, "select start_date from student_qualifications where tr_id = $tr and REPLACE(id, '/', '') = '$a09'");
										
										$n++;
										echo '<td align="left">' . HTML::cell($n) . '</td>';
										echo '<td align="left">' . HTML::cell($l03) . '</td>';
									//	echo '<td align="left">' . HTML::cell($contract_year) . '</td>';
										echo '<td align="left">' . HTML::cell($submission) . '</td>';
										echo '<td align="left">' . HTML::cell($a09) . '</td>';
										echo '<td align="left">' . HTML::cell($a27) . '</td>';
										echo '<td align="left">' . HTML::cell($row2['contract_year']) . '</td>';
										echo '<td align="left">' . HTML::cell($row2['submission']) . '</td>';
										echo '<td align="left">' . HTML::cell($ilr->aims[$sa]->A09) . '</td>';
										echo '<td align="left">' . HTML::cell($ilr->aims[$sa]->A31) . '</td>';
								//		echo '<td align="left">' . HTML::cell($start_date) . '</td>';
										
										echo '</tr>';
									}
								}
							}
							$contract_year = $row2['contract_year'];
							$a27 = $ilr->aims[$sa]->A31;
							$a09 = $ilr->aims[$sa]->A09;
							$l03 = $row2['L03'];
							$submission = $row2['submission'];
							
						}
					}
					
					//throw new Exception(implode($data));
				}

//				echo '<td align="left">' . HTML::cell(implode($data)) . '</td>';
//				echo '</tr>';
				
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