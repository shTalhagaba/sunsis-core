<?php
class ilr_report_export implements IAction
{
	public function execute(PDO $link)
	{

		$contract_id = isset($_REQUEST['contract_id'])?$_REQUEST['contract_id']:'';
		$submission = isset($_REQUEST['submission'])?$_REQUEST['submission']:'';
		$member_numbers = isset($_REQUEST['member_numbers'])?$_REQUEST['member_numbers']:'';

		$filename = "ilr_report";
		
header("Content-Type: application/vnd.ms-excel");
header('Content-Disposition: attachment; filename="' . $filename . '.CSV"');

// Internet Explorer requires two extra headers when downloading files over HTTPS
if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false)
{
	header('Pragma: public');
	header('Cache-Control: max-age=0');
}			

			$sql = <<<HEREDOC
SELECT 
	ilr.*, contracts.*
FROM 
	ilr 
	LEFT JOIN contracts on contracts.id = ilr.contract_id
WHERE 
	contract_id in ($contract_id) AND submission = '$submission'
	order by L03
HEREDOC;

		$st = $link->query($sql);
		if($st) 
		{

			$data = '';
			$filters = array();
			$ilr_fields = $member_numbers;
			if($ilr_fields!='')
			{
				$ilr_fields = explode(",", $ilr_fields);
				foreach($ilr_fields as $mn)
				{
					if(!strpos($mn,"="))
						$data .=  "," . $mn;
					else
						$filters[] = explode("=",$mn);
				}
			}
			
			echo "L03,L09,L10,A09,A27,A28,A31,A34,A35,A40,Programme Type,Age,A14";
			echo $data;
			echo "\n";			
					$n = 0;
					while($row = $st->fetch())
					{
						try
						{
							$ilr = Ilr2011::loadFromXML($row['ilr']);
						}
						catch(Exception $e)
						{
							throw new Exception($row['ilr']);
						}
		
							$contract_year = $row['contract_year'];
							$tr_id = $row['tr_id'];
							$submission = $row['submission'];
							$l03 = $row['L03'];
							$contract_id = $row['contract_id'];
							
							$subaims = -2;
							$ilrtemp = $row['ilr'];
							//$pageDom = new DomDocument();
							$ilrtemp = str_replace("&","&amp;",$ilrtemp);
							//$pageDom->loadXML($ilrtemp);
							$pageDom = XML::loadXmlDom($ilrtemp);
							$e = $pageDom->getElementsByTagName('A09');
							foreach($e as $node)
							{
								$subaims++;	
							}				
						
						// Programme Aim Starts
						if($ilr->programmeaim->A10=="70" || ($ilr->programmeaim->A15!="99" && $ilr->programmeaim->A15!="" && $ilr->programmeaim->A15!="0") && $row['contract_year']>=2008)
						{
							if(!empty($filters))
							{
								foreach($filters as $filter)
								{
									$f = $filter[0];
									$fv = $ilr->programmeaim->$f;
									if($ilr->programmeaim->$f == $filter[1])
									{
										echo $ilr->learnerinformation->L03;
										echo "," . strtoupper($ilr->learnerinformation->L09);
										echo "," . strtoupper($ilr->learnerinformation->L10);
										
										if($ilr->programmeaim->A15=="2" || $ilr->programmeaim->A15=="02")
											$programme_type = "Advanced Apprenticeship";
										elseif($ilr->programmeaim->A15=="3" || $ilr->programmeaim->A15=="03")
											$programme_type = "Apprenticeship";
										elseif($ilr->programmeaim->A15=="99")
											$programme_type = "Adult NVQ";
										else
											$programme_type = "Unknown";
										
										$dob = Date::toMySQL($ilr->learnerinformation->L11);
										$start_date = Date::toMySQL($ilr->programmeaim->A27);
										$sql = "SELECT ((DATE_FORMAT('$start_date','%Y') - DATE_FORMAT('$dob','%Y')) - (DATE_FORMAT('$start_date','00-%m-%d') < DATE_FORMAT('$dob','00-%m-%d')));";
										$age = DAO::getSingleValue($link, $sql);
										
										if($ilr->programmeaim->A10=='70')
											echo ",ZESF0001";
										else
											echo ",ZPROG001";
										
										$tr = TrainingRecord::loadFromDatabase($link, $row['tr_id']);
					
										if($row['contract_year']>=2008)
										{
					
											$trA27 = new Date($tr->start_date);
											$a27 = new Date($ilr->programmeaim->A27);
											echo "," . $ilr->programmeaim->A27;
											
											$trA28 = new Date($tr->target_date);
											$a28 = new Date($ilr->programmeaim->A28);
											echo "," . $ilr->programmeaim->A28;
					
								
											if($tr->closure_date!='' && $ilr->programmeaim->A31!='00000000' && $ilr->programmeaim->A31!='00/00/0000' && $ilr->programmeaim->A31!='' )
											{	
												$trA31 = new Date($tr->closure_date);
												$a31 = new Date($ilr->programmeaim->A31);
												echo "," . $ilr->programmeaim->A31;
											}
											else
											{
												echo "," . $ilr->programmeaim->A31;
											}
										}
										else
										{
											echo "," . $ilr->programmeaim->A27;
											echo "," . $ilr->programmeaim->A28;
											echo "," . $ilr->programmeaim->A31;
										}
										
										echo "," . $ilr->programmeaim->A40;
										echo "," . $programme_type;
										echo "," . $age."+";
										echo "," . $ilr->programmeaim->A14;
					
										$ilr_fields = str_replace(" ", "", $ilr_fields);
										if($ilr_fields!='')
										{
											foreach($ilr_fields as $mn)
											{
												if(!strpos($mn,"="))
												{
													if(substr($mn,0,1)=='L')
														echo "," . preg_replace('/\s\s+/', ' ',strtoupper($ilr->learnerinformation->$mn));
													else
														echo "," . preg_replace('/\s\s+/', ' ',strtoupper($ilr->programmeaim->$mn));
												}
											}
										}		
										echo "\n";
									}
								}
							}
							else
							{
								echo $ilr->learnerinformation->L03;
								echo "," . strtoupper($ilr->learnerinformation->L09);
								echo "," . strtoupper($ilr->learnerinformation->L10);
								
								if($ilr->programmeaim->A15=="2" || $ilr->programmeaim->A15=="02")
									$programme_type = "Advanced Apprenticeship";
								elseif($ilr->programmeaim->A15=="3" || $ilr->programmeaim->A15=="03")
									$programme_type = "Apprenticeship";
								elseif($ilr->programmeaim->A15=="99")
									$programme_type = "Adult NVQ";
								else
									$programme_type = "Unknown";
								
								if($ilr->learnerinformation->L11!='00/00/0000')
								{
									$dob = Date::toMySQL($ilr->learnerinformation->L11);
									$start_date = Date::toMySQL($ilr->programmeaim->A27);
									$sql = "SELECT ((DATE_FORMAT('$start_date','%Y') - DATE_FORMAT('$dob','%Y')) - (DATE_FORMAT('$start_date','00-%m-%d') < DATE_FORMAT('$dob','00-%m-%d')));";
									$age = DAO::getSingleValue($link, $sql);
								}
								else
								{
									$age = '';
								}
								
								if($ilr->programmeaim->A10=='70')
									echo ",ZESF0001";
								else
									echo ",ZPROG001";
								
								$tr = TrainingRecord::loadFromDatabase($link, $row['tr_id']);
			
								if($row['contract_year']>=2008)
								{
									$a27 = new Date($ilr->programmeaim->A27);
									echo "," . $ilr->programmeaim->A27;
									echo "," . $ilr->programmeaim->A28;
									echo "," . $ilr->programmeaim->A31;
								}
								echo "," . $ilr->programmeaim->A34;
								echo "," . $ilr->programmeaim->A35;

								echo "," . $ilr->programmeaim->A40;
								echo "," . $programme_type;
								echo "," . $age."+";
								echo "," . $ilr->programmeaim->A14;
			
								$ilr_fields = str_replace(" ", "", $ilr_fields);
								if($ilr_fields!='')
								{
									foreach($ilr_fields as $mn)
									{
										if(!strpos($mn,"="))
										{
											if(substr($mn,0,1)=='L')
												echo "," . preg_replace('/\s\s+/', ' ',strtoupper($ilr->learnerinformation->$mn));
											else
												echo "," . preg_replace('/\s\s+/', ' ',strtoupper($ilr->programmeaim->$mn));
										}
									}
								}		
								echo "\n";
							}
						}			
							
						
						for($sa=0;$sa<=$subaims;$sa++)
						{
		
							if(!empty($filters))
							{
								foreach($filters as $filter)
								{
									$f = $filter[0];
									$fv = $ilr->aims[$sa]->$f;
									if($ilr->aims[$sa]->$f == $filter[1])
									{

										echo $ilr->learnerinformation->L03;
										echo "," . $ilr->learnerinformation->L09;
										echo "," . $ilr->learnerinformation->L10;
										
										if($ilr->aims[$sa]->A15=="2" || $ilr->aims[$sa]->A15=="02")
											$programme_type = "Advanced Apprenticeship";
										elseif($ilr->aims[$sa]->A15=="3" || $ilr->aims[$sa]->A15=="03")
											$programme_type = "Apprenticeship";
										elseif($ilr->aims[$sa]->A15=="99")
											$programme_type = "Adult NVQ";
										else
											$programme_type = "Unknown";
										
										$dob = Date::toMySQL($ilr->learnerinformation->L11);
										$start_date = Date::toMySQL($ilr->aims[$sa]->A27);
										$sql = "SELECT ((DATE_FORMAT('$start_date','%Y') - DATE_FORMAT('$dob','%Y')) - (DATE_FORMAT('$start_date','00-%m-%d') < DATE_FORMAT('$dob','00-%m-%d')));";
										$age = DAO::getSingleValue($link, $sql);
										
										$a09 = $ilr->aims[$sa]->A09;
										$qual_title = DAO::getSingleValue($link, "select internaltitle from qualifications where replace(id,'/','') = '$a09'");
																		
										echo "," . $ilr->aims[$sa]->A09;
										echo "," . $ilr->aims[$sa]->A27;
										echo "," . $ilr->aims[$sa]->A28;
										echo "," . $ilr->aims[$sa]->A31;
										echo "," . $ilr->aims[$sa]->A40;
										echo "," . $programme_type;
										echo "," . $age."+";
										echo "," . $ilr->aims[$sa]->A14;
					
										$ilr_fields = str_replace(" ", "", $ilr_fields);
										if($ilr_fields!='')
										{
											foreach($ilr_fields as $mn)
											{
												if(!strpos($mn,"="))
												{
													if(substr($mn,0,1)=='L')
														echo "," . preg_replace('/\s\s+/', ' ',strtoupper($ilr->learnerinformation->$mn));
													else
														echo "," . preg_replace('/\s\s+/', ' ',strtoupper($ilr->aims[$sa]->$mn));
												}
											}
										}		
										echo "\n";
									}
								}
							}
							else
							{
								if(!isset($ilr->aims[$sa]))
									continue;
								echo $ilr->learnerinformation->L03;
								echo "," . strtoupper($ilr->learnerinformation->L09);
								echo "," . strtoupper($ilr->learnerinformation->L10);
								
								if($ilr->aims[$sa]->A15=="2" || $ilr->aims[$sa]->A15=="02")
									$programme_type = "Advanced Apprenticeship";
								elseif($ilr->aims[$sa]->A15=="3" || $ilr->aims[$sa]->A15=="03")
									$programme_type = "Apprenticeship";
								elseif($ilr->aims[$sa]->A15=="99")
									$programme_type = "Adult NVQ";
								else
									$programme_type = "Unknown";
								
								if($ilr->learnerinformation->L11!='00/00/0000')
								{
									$dob = Date::toMySQL($ilr->learnerinformation->L11);
									$start_date = Date::toMySQL($ilr->aims[$sa]->A27);
									$sql = "SELECT ((DATE_FORMAT('$start_date','%Y') - DATE_FORMAT('$dob','%Y')) - (DATE_FORMAT('$start_date','00-%m-%d') < DATE_FORMAT('$dob','00-%m-%d')));";
									$age = DAO::getSingleValue($link, $sql);
								}
								else
								{
									$age = '';
								}
										
								$a09 = $ilr->aims[$sa]->A09;
								$qual_title = DAO::getSingleValue($link, "select internaltitle from qualifications where replace(id,'/','') = '$a09'");
								
								echo "," . $ilr->aims[$sa]->A09;
								echo "," . $ilr->aims[$sa]->A27;
								echo "," . $ilr->aims[$sa]->A28;
								echo "," . $ilr->aims[$sa]->A31;

								echo "," . $ilr->aims[$sa]->A34;
								echo "," . $ilr->aims[$sa]->A35;

								echo "," . $ilr->aims[$sa]->A40;
								echo "," . $programme_type;
								echo "," . $age."+";
								echo "," . $ilr->aims[$sa]->A14;
			
								$ilr_fields = str_replace(" ", "", $ilr_fields);
								if($ilr_fields!='')
								{
									foreach($ilr_fields as $mn)
									{
										if(!strpos($mn,"="))
										{
											if(substr($mn,0,1)=='L')
												echo "," . preg_replace('/\s\s+/', ' ',strtoupper($ilr->learnerinformation->$mn));
											else
												echo "," . preg_replace('/\s\s+/', ' ',strtoupper($ilr->aims[$sa]->$mn));
										}
									}
								}		
								echo "\n";
								
							}
						}
					}
		}		
	}
}
?>