<?php
class is_exist_in_2010 implements IAction
{
	public function execute(PDO $link)
	{
		
		// Check arguments
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
		$tr_id = isset($_REQUEST['tr_id'])?$_REQUEST['tr_id']:'';
	
		$tr_id = DAO::getSingleValue($link, "select count(*) from ilr where tr_id = '$tr_id' and contract_id in (select id from contracts where contract_year=2010)");
		
		//$ilr2 = new SimpleXMLElement($xml);
		$ilr2 = XML::loadSimpleXML($xml);		
		$migrate = 0;
		foreach($ilr2->programmeaim as $programmeaim)
		{

			if($programmeaim->A31=='' || $programmeaim->A31=='dd/mm/yyyy' || $programmeaim->A31=='00000000')
				$migrate = 1;
			else
			{
				$A31	= new Date($programmeaim->A31);
				$d 		= new Date('31/07/2010');
				if($A31->getDate()>$d->getDate())
					$migrate = 1;
				else
					$migrate = 0;	
			}
		}
		foreach($ilr2->main as $main)
		{

			if($main->A31=='' || $main->A31=='dd/mm/yyyy' || $main->A31=='00000000')
				$migrate = 1;
			else
			{
				$A31	= new Date($main->A31);
				$d 		= new Date('31/07/2010');
				if($A31->getDate()>$d->getDate())
					$migrate = 1;
				else
					$migrate = 0;	
			}
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
				$d 		= new Date('31/07/2010');
				if($A31->getDate()>$d->getDate())
					$migrate = 1;
				else
					$migrate = 0;	
			}
		}
				
		header("Content-Type: text/xml");
		if($tr_id>0)
			echo "ILRFound";
		elseif($migrate==0)
			echo "NoNeedToMigrate";
		else
			echo "NeedToMigrate";
	}
}
?>