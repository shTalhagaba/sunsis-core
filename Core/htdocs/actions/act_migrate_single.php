<?php
class migrate_single implements IAction
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
				
					//$ilr2 = new SimpleXMLElement($ilr);
					$ilr2 = XML::loadSimpleXML($ilr);

					foreach($ilr2->learner as $learner)
					{
						$learner->L25 = $L25;
						if($learner->L39=='93' || $learner->L39=='94')
							$learner->L39='98';
							
						$learner->L36 = '0';
						if($learner->L08=='Y')
							$deleted = 1;	
						
						
					}
					
					foreach($ilr2->programmeaim as $programmeaim)
					{

						if($programmeaim->A31=='' || $programmeaim->A31=='dd/mm/yyyy' || $programmeaim->A31=='00000000')
							$migrate = 1;
						else
						{
							$A31	= new Date($programmeaim->A31);
							$d 		= new Date('31/07/2009');
							if($A31->getDate()>$d->getDate())
								$migrate = 1;
							else
								$migrate = 0;	
						}
						
				
						if($programmeaim->A02=='00')
							$programmeaim->A02='99';	
						if($programmeaim->A10=='80')
							throw new Exception("A10 is 80");
						if(($programmeaim->A10=='45' || $programmeaim->A10=='46') && ($programmeaim->A14=='10' || $programmeaim->A14=='99'))
							$programmeaim->A14 = '32';								
						$programmeaim->A21='00';	
						$programmeaim->A22='        ';	
						$programmeaim->A32='00000';	
						if($programmeaim->A46a=='046' || $programmeaim->A46a=='047' || $programmeaim->A46a=='048' || $programmeaim->A46a=='048' || $programmeaim->A46a=='049' || $programmeaim->A46a=='050' || $programmeaim->A46a=='051' || $programmeaim->A46a=='052' || $programmeaim->A46a=='053' || $programmeaim->A46a=='054' || $programmeaim->A46a=='055' || $programmeaim->A46a=='056' || $programmeaim->A46a=='057' || $programmeaim->A46a=='058' || $programmeaim->A46a=='059' || $programmeaim->A46a=='060')
							$programmeaim->A46a = '999';								
						if($programmeaim->A46b=='046' || $programmeaim->A46b=='047' || $programmeaim->A46b=='048' || $programmeaim->A46b=='048' || $programmeaim->A46b=='049' || $programmeaim->A46b=='050' || $programmeaim->A46b=='051' || $programmeaim->A46b=='052' || $programmeaim->A46b=='053' || $programmeaim->A46b=='054' || $programmeaim->A46b=='055' || $programmeaim->A46b=='056' || $programmeaim->A46b=='057' || $programmeaim->A46b=='058' || $programmeaim->A46b=='059' || $programmeaim->A46b=='060')
							$programmeaim->A46b = '999';								
//						if(( ($programmeaim->A50=='00' || $programmeaim->A50=='') && ($programmeaim->A31=='' || $programmeaim->A31=='00000000' || $programmeaim->A31=='dd/mm/yyyy'))
//							$programmeaim->A50 = '96';								
//						if(( ($programmeaim->A50=='00' || $programmeaim->A50=='') && ($programmeaim->A31!='' && $programmeaim->A31!='00000000' && $programmeaim->A31!='dd/mm/yyyy'))
//							$programmeaim->A50 = '98';								
//						if($programmeaim->A51a=='00' || $programmeaim->A51a='')
//							$programmeaim->A51a='100';	
						$programmeaim->addChild('A61','         ');	
						$programmeaim->addChild('A62','000');	

						if($programmeaim->A04=='35')
							$programmeaim->addChild('A63','00');	
						else
						if($programmeaim->A04=='30' && ($programmeaim->A46a=='046' || $programmeaim->A46b=='046'))
							$programmeaim->addChild('A63','01');	
						else
						if($programmeaim->A04=='30' && ($programmeaim->A46a=='047' || $programmeaim->A46b=='047'))
							$programmeaim->addChild('A63','02');	
						else
						if($programmeaim->A04=='30' && ($programmeaim->A46a=='048' || $programmeaim->A46b=='048'))
							$programmeaim->addChild('A63','03');	
						else
						if($programmeaim->A04=='30' && ($programmeaim->A46a=='049' || $programmeaim->A46b=='049'))
							$programmeaim->addChild('A63','04');	
						else
						if($programmeaim->A04=='30' && ($programmeaim->A46a=='050' || $programmeaim->A46b=='050'))
							$programmeaim->addChild('A63','05');	
						else
						if($programmeaim->A04=='30' && ($programmeaim->A46a=='051' || $programmeaim->A46b=='051'))
							$programmeaim->addChild('A63','06');	
						else
						if($programmeaim->A04=='30' && ($programmeaim->A46a=='052' || $programmeaim->A46b=='052'))
							$programmeaim->addChild('A63','07');	
						else
						if($programmeaim->A04=='30' && ($programmeaim->A46a=='053' || $programmeaim->A46b=='053'))
							$programmeaim->addChild('A63','08');	
						else
						if($programmeaim->A04=='30' && ($programmeaim->A46a=='054' || $programmeaim->A46b=='054'))
							$programmeaim->addChild('A63','09');
						else
						if($programmeaim->A04=='30' && ($programmeaim->A46a=='055' || $programmeaim->A46b=='055'))
							$programmeaim->addChild('A63','10');
						else
						if($programmeaim->A04=='30' && ($programmeaim->A46a=='056' || $programmeaim->A46b=='056'))
							$programmeaim->addChild('A63','11');
						else
						if($programmeaim->A04=='30' && ($programmeaim->A46a=='057' || $programmeaim->A46b=='057'))
							$programmeaim->addChild('A63','12');
						else
						if($programmeaim->A04=='30' && ($programmeaim->A46a=='058' || $programmeaim->A46b=='058'))
							$programmeaim->addChild('A63','13');
						else
							$programmeaim->addChild('A63','99');
							
					}

					foreach($ilr2->main as $main)
					{
	
						if($main->A31=='' || $main->A31=='dd/mm/yyyy' || $main->A31=='00000000')
							$migrate = 1;
						else
						{
							$A31	= new Date($main->A31);
							$d 		= new Date('31/07/2009');
							if($A31->getDate()>$d->getDate())
								$migrate = 1;
							else
								$migrate = 0;	
						}
							
						if($main->A02=='00')
							$main->A02='99';	
						if($main->A10=='80')
							throw new Exception("A10 is 80");
						if(($main->A10=='45' || $main->A10=='46') && ($main->A14=='10' || $main->A14=='99'))
							$main->A14 = '32';								
						$main->A21='00';	
						$main->A22='        ';	
						$main->A32='00000';	
						if($main->A46a=='046' || $main->A46a=='047' || $main->A46a=='048' || $main->A46a=='048' || $main->A46a=='049' || $main->A46a=='050' || $main->A46a=='051' || $main->A46a=='052' || $main->A46a=='053' || $main->A46a=='054' || $main->A46a=='055' || $main->A46a=='056' || $main->A46a=='057' || $main->A46a=='058' || $main->A46a=='059' || $main->A46a=='060')
							$main->A46a = '999';								
						if($main->A46b=='046' || $main->A46b=='047' || $main->A46b=='048' || $main->A46b=='048' || $main->A46b=='049' || $main->A46b=='050' || $main->A46b=='051' || $main->A46b=='052' || $main->A46b=='053' || $main->A46b=='054' || $main->A46b=='055' || $main->A46b=='056' || $main->A46b=='057' || $main->A46b=='058' || $main->A46b=='059' || $main->A46b=='060')
							$main->A46b = '999';								
//						if(( ($main->A50=='00' || $main->A50=='') && ($main->A31=='' || $main->A31=='00000000' || $main->A31=='dd/mm/yyyy'))
//							$main->A50 = '96';								
//						if(( ($main->A50=='00' || $main->A50=='') && ($main->A31!='' && $main->A31!='00000000' && $main->A31!='dd/mm/yyyy'))
//							$main->A50 = '98';								
						if($main->A51a=='00' || $main->A51a='' || $main->A51a='0')
							$main->A51a='100';	
						$main->addChild('A61','         ');	
						$main->addChild('A62','000');	

						if($main->A04=='35')
							$main->addChild('A63','00');	
						else
						if($main->A04=='30' && ($main->A46a=='046' || $main->A46b=='046'))
							$main->addChild('A63','01');	
						else
						if($main->A04=='30' && ($main->A46a=='047' || $main->A46b=='047'))
							$main->addChild('A63','02');	
						else
						if($main->A04=='30' && ($main->A46a=='048' || $main->A46b=='048'))
							$main->addChild('A63','03');	
						else
						if($main->A04=='30' && ($main->A46a=='049' || $main->A46b=='049'))
							$main->addChild('A63','04');	
						else
						if($main->A04=='30' && ($main->A46a=='050' || $main->A46b=='050'))
							$main->addChild('A63','05');	
						else
						if($main->A04=='30' && ($main->A46a=='051' || $main->A46b=='051'))
							$main->addChild('A63','06');	
						else
						if($main->A04=='30' && ($main->A46a=='052' || $main->A46b=='052'))
							$main->addChild('A63','07');	
						else
						if($main->A04=='30' && ($main->A46a=='053' || $main->A46b=='053'))
							$main->addChild('A63','08');	
						else
						if($main->A04=='30' && ($main->A46a=='054' || $main->A46b=='054'))
							$main->addChild('A63','09');
						else
						if($main->A04=='30' && ($main->A46a=='055' || $main->A46b=='055'))
							$main->addChild('A63','10');
						else
						if($main->A04=='30' && ($main->A46a=='056' || $main->A46b=='056'))
							$main->addChild('A63','11');
						else
						if($main->A04=='30' && ($main->A46a=='057' || $main->A46b=='057'))
							$main->addChild('A63','12');
						else
						if($main->A04=='30' && ($main->A46a=='058' || $main->A46b=='058'))
							$main->addChild('A63','13');
						else
							$main->addChild('A63','99');
					}

					foreach($ilr2->subaim as $subaim)
					{
						if($subaim->A31=='' || $subaim->A31=='dd/mm/yyyy' || $subaim->A31=='00000000')
						{
							$migrate = 1;
							break;
						}
						else
						{
							$A31	= new Date($subaim->A31);
							$d 		= new Date('31/07/2009');
							if($A31->getDate()>$d->getDate())
								$migrate = 1;
							else
								$migrate = 0;	
						}
							
						if($subaim->A02=='00')
							$subaim->A02='99';	
						if($subaim->A10=='80')
							throw new Exception("A10 is 80");
						if(($subaim->A10=='45' || $subaim->A10=='46') && ($subaim->A14=='10' || $subaim->A14=='99'))
							$subaim->A14 = '32';								
						$subaim->A21='00';	
						$subaim->A22='        ';	
						$subaim->A32='00000';	
						if($subaim->A46a=='046' || $subaim->A46a=='047' || $subaim->A46a=='048' || $subaim->A46a=='048' || $subaim->A46a=='049' || $subaim->A46a=='050' || $subaim->A46a=='051' || $subaim->A46a=='052' || $subaim->A46a=='053' || $subaim->A46a=='054' || $subaim->A46a=='055' || $subaim->A46a=='056' || $subaim->A46a=='057' || $subaim->A46a=='058' || $subaim->A46a=='059' || $subaim->A46a=='060')
							$subaim->A46a = '999';								
						if($subaim->A46b=='046' || $subaim->A46b=='047' || $subaim->A46b=='048' || $subaim->A46b=='048' || $subaim->A46b=='049' || $subaim->A46b=='050' || $subaim->A46b=='051' || $subaim->A46b=='052' || $subaim->A46b=='053' || $subaim->A46b=='054' || $subaim->A46b=='055' || $subaim->A46b=='056' || $subaim->A46b=='057' || $subaim->A46b=='058' || $subaim->A46b=='059' || $subaim->A46b=='060')
							$subaim->A46b = '999';								
//						if(( ($subaim->A50=='00' || $subaim->A50=='') && ($subaim->A31=='' || $subaim->A31=='00000000' || $subaim->A31=='dd/mm/yyyy'))
//							$subaim->A50 = '96';								
//						if(( ($subaim->A50=='00' || $subaim->A50=='') && ($subaim->A31!='' && $subaim->A31!='00000000' && $subaim->A31!='dd/mm/yyyy'))
//							$subaim->A50 = '98';								
						if($subaim->A51a=='00' || $subaim->A51a='' || $subaim->A51a='0')
							$subaim->A51a='100';	
						$subaim->addChild('A61','         ');	
						$subaim->addChild('A62','000');	

						if($subaim->A04=='35')
							$subaim->addChild('A63','00');	
						else
						if($subaim->A04=='30' && ($subaim->A46a=='046' || $subaim->A46b=='046'))
							$subaim->addChild('A63','01');	
						else
						if($subaim->A04=='30' && ($subaim->A46a=='047' || $subaim->A46b=='047'))
							$subaim->addChild('A63','02');	
						else
						if($subaim->A04=='30' && ($subaim->A46a=='048' || $subaim->A46b=='048'))
							$subaim->addChild('A63','03');	
						else
						if($subaim->A04=='30' && ($subaim->A46a=='049' || $subaim->A46b=='049'))
							$subaim->addChild('A63','04');	
						else
						if($subaim->A04=='30' && ($subaim->A46a=='050' || $subaim->A46b=='050'))
							$subaim->addChild('A63','05');	
						else
						if($subaim->A04=='30' && ($subaim->A46a=='051' || $subaim->A46b=='051'))
							$subaim->addChild('A63','06');	
						else
						if($subaim->A04=='30' && ($subaim->A46a=='052' || $subaim->A46b=='052'))
							$subaim->addChild('A63','07');	
						else
						if($subaim->A04=='30' && ($subaim->A46a=='053' || $subaim->A46b=='053'))
							$subaim->addChild('A63','08');	
						else
						if($subaim->A04=='30' && ($subaim->A46a=='054' || $subaim->A46b=='054'))
							$subaim->addChild('A63','09');
						else
						if($subaim->A04=='30' && ($subaim->A46a=='055' || $subaim->A46b=='055'))
							$subaim->addChild('A63','10');
						else
						if($subaim->A04=='30' && ($subaim->A46a=='056' || $subaim->A46b=='056'))
							$subaim->addChild('A63','11');
						else
						if($subaim->A04=='30' && ($subaim->A46a=='057' || $subaim->A46b=='057'))
							$subaim->addChild('A63','12');
						else
						if($subaim->A04=='30' && ($subaim->A46a=='058' || $subaim->A46b=='058'))
							$subaim->addChild('A63','13');
						else
							$subaim->addChild('A63','99');
					}

					$ilr3 = substr($ilr2->asXML(),22);
					if($migrate==1 && $deleted==0)
					{	
						DAO::execute($link, "INSERT INTO ilr values('$L01', '$L03', '$A09', '$ilr3', 'W01', 'ER', $tr_id, $is_complete, $is_valid, $is_approved, $is_active, $contract_id2);");
						DAO::execute($link, "update tr set contract_id = '$contract_id2' where id = '$tr_id'");
					}
				} 
			}
	}
}

?>