<?php
class migrate_single2010 implements IAction
{
	public function execute(PDO $link)
	{

		$qan = isset($_REQUEST['id'])?$_REQUEST['id']:'';
		$qan_before_editing = isset($_REQUEST['qan_before_editing'])?$_REQUEST['qan_before_editing']:'';
		$xml = isset($_REQUEST['xml'])?$_REQUEST['xml']:'';
		$submission_date = isset($_REQUEST['submission_date'])?$_REQUEST['submission_date']:'';
		$L01 = isset($_REQUEST['L01'])?$_REQUEST['L01']:'';
		$l28a = isset($_REQUEST['L28a'])?$_REQUEST['L28a']:'';
		$l28b = isset($_REQUEST['L28b'])?$_REQUEST['L28b']:'';
		$A09 = isset($_REQUEST['A09'])?$_REQUEST['A09']:'';
		$approve = isset($_REQUEST['approve'])?$_REQUEST['approve']:'';
		$active = isset($_REQUEST['active'])?$_REQUEST['active']:'';
		$sub = isset($_REQUEST['sub'])?$_REQUEST['sub']:'';
		$contract_id = isset($_REQUEST['contract_id'])?$_REQUEST['contract_id']:'';
		$contract_id2 = isset($_REQUEST['contract_id2'])?$_REQUEST['contract_id2']:'';
		$tr_id = isset($_REQUEST['tr_id'])?$_REQUEST['tr_id']:'';

		$L25 = DAO::getSingleValue($link, "select L25 from contracts where id = $contract_id");

		if($st = $link->query("SELECT L01,L03, A09, ilr, 'W01', contract_type, tr_id, is_complete, is_valid, is_approved, is_active, $contract_id FROM ilr WHERE contract_id = '$contract_id' AND submission='W13' and tr_id = '$tr_id'")) 
		{
			while($row = $st->fetch())
			{
				
				$migrate = 0;
				$deleted = 0;
			
				$L01 = $row['L01'];
				$L03 = $row['L03'];
				$A09 = $row['A09'];
				$ilr = $row['ilr'];
				$contract_type = $row['contract_type'];
				$tr_id = $row['tr_id'];
				$is_complete = $row['is_complete'];
				$is_valid = $row['is_valid'];
				$is_approved = $row['is_approved'];
				$is_active = $row['is_active'];

				/*
				try
				{
					$ilr2 = new SimpleXMLElement($ilr);		
				}
				catch(Exception $e)
				{
					pre($ilr);
				}
				*/
				$ilr2 = XML::loadSimpleXML($ilr);
				
				foreach($ilr2->learner as $learner)
				{
					$learner->L25 = $L25;

					if($learner->L08=='Y')
						$deleted = 1;	
	
				}

				foreach($ilr2->programmeaim as $programmeaim)
				{

					if($programmeaim->A02=='99')
						$programmeaim->A02='00';	

					if($programmeaim->A10=='45' || $programmeaim->A10=='46' || $programmeaim->A10=='70' || $programmeaim->A10=='80')
					{
						$programmeaim->A11a = '105';
						$programmeaim->A11b = '999';
					}
					elseif($programmeaim->A10=='99')
					{
						$programmeaim->A11a = '999';
						$programmeaim->A11b = '999';
					}

					if( (($learner->L28a=='14' && $learner->L28b=='15') || ($learner->L28a=='15' && $learner->L28b=='14')) && ($programmeaim->A15=='2' || $programmeaim->A15=='02' || $programmeaim->A15=='3' || $programmeaim->A15=='03' || $programmeaim->A15=='10'))
					{
						$programmeaim->A14 = '28';										
					}
					
					if( (($learner->L28a=='15' && $learner->L28b=='99') || ($learner->L28a=='99' && $learner->L28b=='15')) && ($programmeaim->A15=='2' || $programmeaim->A15=='02' || $programmeaim->A15=='3' || $programmeaim->A15=='03' || $programmeaim->A15=='10'))
					{
						$programmeaim->A14 = '1';										
					}
					
					if($programmeaim->A18=='22' || $programmeaim->A18=='23')
					{
						$programmeaim->A18 = '24';
					}							
					
					if($programmeaim->A22=='' || $programmeaim->A22=='        ')
					{
						$programmeaim->A22 = '00000000';
					}
					
					if($programmeaim->A44=='' || $programmeaim->A44=='                              ')
					{
						$programmeaim->A44 = '000000000';
					}

					if( ($programmeaim->A10=='45') && ($programmeaim->A15=='99') && ($programmeaim->A46a=='83' || $programmeaim->A46b=='83'))
					{
						$programmeaim->A44 = '888888880';
					}
					
					
					
					if($programmeaim->A04!='30')
						$programmeaim->addChild('A69','00');	
					else
					if($programmeaim->A10=='99')
						$programmeaim->addChild('A69','99');	
					else
					if($programmeaim->A15!='2' && $programmeaim->A15!='3' && $programmeaim->A15!='10')
					{
						$programmeaim->addChild('A69','99');	
					}
					else
					if( ($learner->L28a=='14' && $learner->L28b=='99') || ($learner->L28a=='99' && $learner->L28b=='14'))
						$programmeaim->addChild('A69','99');	
					else
					if( ($learner->L28a=='14' && $learner->L28b=='15') || ($learner->L28a=='15' && $learner->L28b=='14'))
					{
						$programmeaim->addChild('A69','1');
						$programmeaim->A14 = '28';
					}
					else
					if( ($learner->L28a=='15' && $learner->L28b=='99') || ($learner->L28a=='99' && $learner->L28b=='15'))
					{
						$programmeaim->addChild('A69','2');
						$programmeaim->A14 = '1';
					}
					else
						$programmeaim->addChild('A69','99');

					if($L01=='118790')
						$programmeaim->addChild('A70','SFEE');
					elseif($L01=='108459')
						$programmeaim->addChild('A70','SFSE');
					elseif($L01=='118047' || $L01=='116503' || $L01=='108458')
						$programmeaim->addChild('A70','SFNE');
					elseif($L01=='117358' || $L01 == '117954')
						$programmeaim->addChild('A70','SFNW');
					elseif($L01=='118469')
						$programmeaim->addChild('A70','SFNE');
					elseif($L01=='105060')
						$programmeaim->addChild('A70','SFWM');
					else	
						if($programmeaim->A10=='80' && ($programmeaim->A46a=='999' && $programmeaim->A46b=='999') && $programmeaim->A49=='')
						$programmeaim->addChild('A70','');

				}

				foreach($ilr2->main as $main)
				{

					if(($ilr2->programmeaim->A15!="99" && $ilr2->programmeaim->A15!=""))
					{
						if($programmeaim->A31=='' || $programmeaim->A31=='dd/mm/yyyy' || $programmeaim->A31=='00000000')
						{
							$migrate = 1;
						}
						elseif( ($programmeaim->A40=='' || $programmeaim->A40=='dd/mm/yyyy' || $programmeaim->A40=='00000000') && ($programmeaim->A35=='4' || $programmeaim->A35=='5'))
						{
							$migrate = 1;
						}

						if($programmeaim->A28!='00000000')
						{
							$A28 = new Date($programmeaim->A28);
							$d = new Date('01/08/2007');
							if($A28->getDate()<$d->getDate())
								$migrate = 0;
						}
						
						if($programmeaim->A31!='' && $programmeaim->A31!='dd/mm/yyyy' && $programmeaim->A31!='00000000')
						{
							$A31 = new Date($programmeaim->A31);
							$d = new Date('01/08/2010');
							if($A31->getDate()>=$d->getDate())
								$migrate = 1;
						}
					}
					else
					{
						if($main->A31=='' || $main->A31=='dd/mm/yyyy' || $main->A31=='00000000')
						{
							$migrate = 1;
						}
						elseif( ($main->A40=='' || $main->A40=='dd/mm/yyyy' || $main->A40=='00000000') && ($main->A35=='4' || $main->A35=='5'))
						{
							$migrate = 1;
						}
						
						$A28 = new Date($main->A28);
						$d = new Date('01/08/2007');
						if($A28->getDate()<$d->getDate())
							$migrate = 0;

						if($main->A31!='' && $main->A31!='dd/mm/yyyy' && $main->A31!='00000000')
						{
							$A31 = new Date($main->A31);
							$d = new Date('01/08/2010');
							if($A31->getDate()>=$d->getDate())
								$migrate = 1;
						}
					}
					
					
					if($main->A02=='99')
						$main->A02='00';	

					if($main->A10=='45' || $main->A10=='46' || $main->A10=='70' || $main->A10=='80')
					{
						$main->A11a = '105';
						$main->A11b = '999';
					}
					elseif($main->A10=='99')
					{
						$main->A11a = '999';
						$main->A11b = '999';
					}

					if( (($learner->L28a=='14' && $learner->L28b=='15') || ($learner->L28a=='15' && $learner->L28b=='14')) && ($main->A15=='2' || $main->A15=='02' || $main->A15=='3' || $main->A15=='03' || $main->A15=='10'))
					{
						$main->A14 = '28';										
					}
					
					if( (($learner->L28a=='15' && $learner->L28b=='99') || ($learner->L28a=='99' && $learner->L28b=='15')) && ($main->A15=='2' || $main->A15=='02' || $main->A15=='3' || $main->A15=='03' || $main->A15=='10'))
					{
						$main->A14 = '1';										
					}
					
					if($main->A18=='22' || $main->A18=='23')
					{
						$main->A18 = '24';
					}							
					
					if($main->A22=='' || $main->A22=='        ')
					{
						$main->A22 = '00000000';
					}
					
					if($main->A44=='' || $main->A44=='                              ')
					{
						$main->A44 = '000000000';
					}

					if( ($main->A10=='45') && ($main->A15=='99') && ($main->A46a=='83' || $main->A46b=='83'))
					{
						$main->A44 = '888888880';
					}
					
					if($main->A04!='30')
						$main->addChild('A69','00');	
					else
					if($main->A10=='99')
						$main->addChild('A69','99');	
					else
					if($main->A15!='2' && $main->A15!='3' && $main->A15!='10')
						$main->addChild('A69','99');	
					else
					if( ($learner->L28a=='14' && $learner->L28b=='99') || ($learner->L28a=='99' && $learner->L28b=='14'))
						$main->addChild('A69','99');	
					else
					if( ($learner->L28a=='14' && $learner->L28b=='15') || ($learner->L28a=='15' && $learner->L28b=='14'))
					{
						$main->addChild('A69','1');
						$main->A14 = '28';
					}
					else
					if( ($learner->L28a=='15' && $learner->L28b=='99') || ($learner->L28a=='99' && $learner->L28b=='15'))
					{
						$main->addChild('A69','2');
						$main->A14 = '1';
					}
					else
						$main->addChild('A69','99');

					if($L01=='118790')
						$main->addChild('A70','SFEE');
					elseif($L01=='108459')
						$main->addChild('A70','SFSE');
					elseif($L01=='118047' || $L01=='116503' || $L01=='108458')
						$main->addChild('A70','SFNE');
					elseif($L01=='117358' || $L01 == '117954')
						$main->addChild('A70','SFNW');
					elseif($L01=='118469')
						$main->addChild('A70','SFNE');
					elseif($L01=='105060')
						$main->addChild('A70','SFWM');
					else	
						if($main->A10=='80' && ($main->A46a=='999' && $main->A46b=='999') && $main->A49=='')
						$main->addChild('A70','');
				}

				foreach($ilr2->subaim as $subaim)
				{
					if($subaim->A31=='' || $subaim->A31=='dd/mm/yyyy' || $subaim->A31=='00000000')
					{
						$migrate = 1;
					}
					elseif( ($subaim->A40=='' || $subaim->A40=='dd/mm/yyyy' || $subaim->A40=='00000000') && ($subaim->A35=='4' || $subaim->A35=='5'))
					{
						$migrate = 1;
					}
					
					
					$A28 = new Date($subaim->A28);
					$d = new Date('01/08/2007');
					if($A28->getDate()<$d->getDate())
						$migrate = 0;
					
					if($subaim->A02=='99')
						$subaim->A02='00';	

					if($subaim->A10=='45' || $subaim->A10=='46' || $subaim->A10=='70' || $subaim->A10=='80')
					{
						$subaim->A11a = '105';
						$subaim->A11b = '999';
					}
					elseif($subaim->A10=='99')
					{
						$subaim->A11a = '999';
						$subaim->A11b = '999';
					}

					if( (($learner->L28a=='14' && $learner->L28b=='15') || ($learner->L28a=='15' && $learner->L28b=='14')) && ($subaim->A15=='2' || $subaim->A15=='02' || $subaim->A15=='3' || $subaim->A15=='03' || $subaim->A15=='10'))
					{
						$subaim->A14 = '28';										
					}
					
					if( (($learner->L28a=='15' && $learner->L28b=='99') || ($learner->L28a=='99' && $learner->L28b=='15')) && ($subaim->A15=='2' || $subaim->A15=='02' || $subaim->A15=='3' || $subaim->A15=='03' || $subaim->A15=='10'))
					{
						$subaim->A14 = '1';										
					}
					
					if($subaim->A18=='22' || $subaim->A18=='23')
					{
						$subaim->A18 = '24';
					}							
					
					if($subaim->A22=='' || $subaim->A22=='        ')
					{
						$subaim->A22 = '00000000';
					}
					
					if($subaim->A44=='' || $subaim->A44=='                              ')
					{
						$subaim->A44 = '000000000';
					}

					if( ($subaim->A10=='45') && ($subaim->A15=='99') && ($subaim->A46a==83 || $subaim->A46b==83))
					{
						$subaim->A44 = '888888880';
					}
					
					if($subaim->A04!='30')
						$subaim->addChild('A69','00');	
					else
					if($subaim->A10=='99')
						$subaim->addChild('A69','99');	
					else
					if($subaim->A15!='2' && $subaim->A15!='3' && $subaim->A15!='10')
						$subaim->addChild('A69','99');	
					else
					if( ($learner->L28a=='14' && $learner->L28b=='99') || ($learner->L28a=='99' && $learner->L28b=='14'))
						$subaim->addChild('A69','99');	
					else
					if( ($learner->L28a=='14' && $learner->L28b=='15') || ($learner->L28a=='15' && $learner->L28b=='14'))
					{
						$subaim->addChild('A69','1');
						$subaim->A14 = '28';
					}
					else
					if( ($learner->L28a=='15' && $learner->L28b=='99') || ($learner->L28a=='99' && $learner->L28b=='15'))
					{
						$subaim->addChild('A69','2');
						$subaim->A14 = '1';
					}
					else
						$subaim->addChild('A69','99');

						
					if($L01=='118790')
						$subaim->addChild('A70','SFEE');
					elseif($L01=='108459')
						$subaim->addChild('A70','SFSE');
					elseif($L01=='118047' || $L01=='116503' || $L01=='108458')
						$subaim->addChild('A70','SFNE');
					elseif($L01=='117358' || $L01 == '117954')
						$subaim->addChild('A70','SFNW');
					elseif($L01=='118469')
						$subaim->addChild('A70','SFNE');
					elseif($L01=='105060')
						$subaim->addChild('A70','SFWM');
					else	
					if($subaim->A10=='80' && ($subaim->A46a=='999' && $subaim->A46b=='999') && $subaim->A49=='')
						$subaim->addChild('A70','');
					
				}

				$ilr3 = substr($ilr2->asXML(),22);
				$ilr3 = str_replace("'","&nbsp;",$ilr3);
				if($migrate==1 && $deleted==0)
				{	
					DAO::execute($link, "INSERT INTO ilr values('$L01', '$L03', '$A09', '$ilr3', 'W01', 'ER', $tr_id, $is_complete, 0, $is_approved, $is_active, $contract_id2);");
				}
			} 
		}
	}
}

?>