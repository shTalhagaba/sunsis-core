<?php /* @var $vo OrganisationVO */ ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
	<title>Funding Predictor</title>
	<link rel="stylesheet" href="/common.css" type="text/css"/>
	<script src="/js/jquery.min.js" type="text/javascript"></script>
	<script src="/common.js" type="text/javascript"></script>
</head>

<body>
<div class="banner">
	<div class="Title">Funding Predictor</div>
	<div class="ButtonBar">

	</div>
	<div class="ActionIconBar">
		<button onclick="window.print()" title="Print-friendly view"><img src="/images/btn-printer.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
		<button onclick="window.location.reload(false);" title="Refresh view (see the latest changes from other users)"><img src="/images/btn-refresh.gif" width="16" height="16" style="vertical-align:text-bottom" /></button>
	</div>
</div>

<?php 
		$submission = DAO::getSingleValue($link, "select submission from ilr where contract_id = '$contract_id' order by submission desc limit 0,1");
		$upin = DAO::getSingleValue($link, "select upin from contracts where id = '$contract_id'");
		$provider_factor = DAO::getSingleValue($link, "select L1618_Provider_Factor from lis201112.providers where CAPN  = '$upin'");
		if($upin=='117954')
			$provider_factor = 1;
		elseif($upin=='120232')
			$provider_factor = 1.186;
		if($provider_factor == '')
			$provider_factor = 1;
		$st = $link->query("select * from ilr where contract_id = '$contract_id' and submission = '$submission'");
		if($st) 
		{
			echo '<div align="center"><table class="resultset" border="0" cellspacing="0" cellpadding="6">';
			echo '<thead><tr><th>Learner Name</th><th>Learning Aim Ref</th><th>GLH</th><th>SLN</th><th>Rate</th><th>Provider Factor</th><th>ALS</th><th>Amount</th></tr></thead>';
			echo '<tbody>';
			$gtotal = 0;
			$gals = 0;
			while($row = $st->fetch())
			{
				try
				{
					$ilr = Ilr2011::loadFromDatabase($link, $row['submission'], $row['contract_id'], $row['tr_id'], $row['L03']);
				}
				catch(Exception $e)
				{
					throw new Exception($row['ilr']);
				}
				$glh = 0;
				$total_sln = 0;
				$learner_total = 0;
				$gals = 0;
				$total_als = 0;
				$L28 = $ilr->learnerinformation->L28;
				foreach($ilr->aims as $aim)
				{
					if($aim->A71=='1')
						$rate = 2920; 
					else
						$rate = 2615;
						
					if(DB_NAME=='dv8training')
						$als = 356.25;
					else 	
						$als = 0;
					echo '<tr>';
					echo '<td align="left">' . $ilr->learnerinformation->L10 . ' ' . $ilr->learnerinformation->L09 . '</td>';
					echo '<td>' . $aim->A09 . '</td>';

					if($aim->A09=='ZFLW0001')
					{
						$start_date = new Date($aim->A27);
						$contract_start_date = new Date("01/08/2010");
						$sd = ($start_date->getDate() > $contract_start_date->getDate())?$start_date:$contract_start_date;
						
						//find reference date 
						if($submission=='W01')
							$reference_date = new Date("01/11/2010");
						elseif($submission=='W02')
							$reference_date = new Date("01/02/2011");
						elseif($submission=='W03')
							$reference_date = new Date("01/05/2011");
						elseif($submission=='W04')
							$reference_date = new Date("31/07/2011");
						elseif($submission=='W05')
							$reference_date = new Date("31/07/2011");
							
						if($aim->A31!='00000000' && $aim->A31!='00/00/0000')
						{
							$a31 = new Date($aim->A31);
							$ed = ($a31->getDate()<$reference_date->getDate())?$a31:$reference_date;
						}
						else 
						{
							$a28 = new Date($aim->A28);
							$ed = ($a28->getDate()>$reference_date->getDate())?$a28:$reference_date;
						}

				
						// No of Mondays
						$mondays = 0;
						while($sd->getDate()<$ed->getDate())
						{
							if(strpos($sd->formatLong(),"onday")>0)
								$mondays++;
							$sd->addDays(1);							
						}

				//		if($ilr->learnerinformation->L03=='000000000034')
				//			pre($mondays);	
						
						$sln = (12 * $mondays)/450;			
					}
					else
					{
						$sln = ($aim->A32/450);
					}

					// Check for eligibility 
					if($aim->A34!='3')
					{
						if(DB_NAME=='dv8training')
							$als = 356.25 * $sln;
						else
							$als = 0;
						$gals+= $als;
						$total_als += $als;
						echo '<td>' . $aim->A32 . '</td>';
						$glh += $aim->A32;
						echo '<td>' . sprintf("%.2f",($sln)) . '</td>';
						echo '<td>' . $rate . '</td>';
						//echo '<td>' . sprintf("%.2f", ($als * ($aim->A32/450))) . '</td>';
						echo '<td>' . $provider_factor . '</td>';		
						echo '<td>&pound; ' . sprintf("%.2f",$als) . '</td>';
						echo '<td>&pound; ' . sprintf("%.2f",($provider_factor * $rate * ($sln))+$als) . '</td>';
						$learner_total += ($provider_factor * $rate * ($sln)) + $als;
						$total_sln += $sln;
						$gtotal += ($provider_factor * $rate * ($sln)) + $als;
						echo '</tr>';
					}
					else
					{
						$als = 0;
						$sln = 0;
						$gals+= $als;
						$total_als += $als;
						echo '<td>' . $aim->A32 . '</td>';
						$glh += $aim->A32;
						echo '<td>' . sprintf("%.2f",($sln)) . '</td>';
						echo '<td>' . $rate . '</td>';
						//echo '<td>' . sprintf("%.2f", ($als * ($aim->A32/450))) . '</td>';
						echo '<td>' . $provider_factor . '</td>';		
						echo '<td>&pound; ' . sprintf("%.2f",$als) . '</td>';
						echo '<td>&pound; ' . sprintf("%.2f",($provider_factor * $rate * ($sln))+$als) . '</td>';
						$learner_total += ($provider_factor * $rate * ($sln)) + $als;
						$total_sln += $sln;
						$gtotal += ($provider_factor * $rate * ($sln)) + $als;
						echo '</tr>';
					}
				}


					// Check for entitlement
					if($L28=='12')
					{
						
						echo '<tr>';
						echo '<td align="left">' . $ilr->learnerinformation->L10 . ' ' . $ilr->learnerinformation->L09 . '</td>';
						echo '<td>16-18 Entitlement</td>';

						$c = Contract::loadFromDatabase($link, $row['contract_id']);
						if($c->contract_year == 2010)
						{
							echo '<td>114</td>';
							$sln = 114/450;
						}
						elseif($c->contract_year == 2011)
						{
							echo '<td>30</td>';
							$sln = 30/450;
						}
						echo '<td>' . sprintf("%.2f",($sln)) . '</td>';
						echo '<td>' . $rate . '</td>';
						//echo '<td>' . sprintf("%.2f", ($als * ($aim->A32/450))) . '</td>';
						echo '<td>' . $provider_factor . '</td>';
						$als = 356.25 * $sln;
						echo '<td>&pound; ' . sprintf("%.2f",$als) . '</td>';
						echo '<td>&pound; ' . sprintf("%.2f",($provider_factor * $rate * $sln)+ $als ) . '</td>';
						$learner_total += (($provider_factor * $rate * $sln)+ $als);
						$gtotal+=(($provider_factor * $rate * $sln)+ $als);
						echo '</tr>';
						
					}
				
					echo '<tr bgcolor="lightgrey">';
					echo '<td align="left">' . $ilr->learnerinformation->L10 . ' ' . $ilr->learnerinformation->L09 . '</td>';
					echo '<td>Total</td>';
					echo '<td>' . $glh . '</td>';
					echo '<td>' . sprintf("%.2f",($total_sln)) . '</td>';
					echo '<td>' . $rate . '</td>';
					//echo '<td>' . sprintf("%.2f", ($als * ($aim->A32/450))) . '</td>';
					echo '<td>' . $provider_factor . '</td>';		
					echo '<td>&pound; ' . sprintf("%.2f",$total_als) . '</td>';
					echo '<td>&pound; ' . sprintf("%.2f",($learner_total)) . '</td>';
					echo '</tr>';
						
				}
				echo '<tr bgcolor="CCFFCC">';
				echo '<td align="left">Grand Total</td>';
				echo '<td>&nbsp;</td>';
				echo '<td>&nbsp;</td>';
				echo '<td>&nbsp;</td>';
				echo '<td>&nbsp;</td>';
				//echo '<td>' . sprintf("%.2f", ($als * ($aim->A32/450))) . '</td>';
				echo '<td>' . $provider_factor . '</td>';		
				echo '<td>&pound;' . sprintf("%.2f",($gals)) . '</td>';
				echo '<td>&pound;' . sprintf("%.2f",($gtotal)) . '</td>';
				echo '</tr>';
		}
		

?>
</body>
</html>