<?php
class generate_batch_file implements IAction
{
	public function execute(PDO $link)
	{
        set_time_limit(0);
		$xml = isset($_REQUEST['xml'])?$_REQUEST['xml']:'';
		$submission = isset($_REQUEST['submission'])?$_REQUEST['submission']:'';
        $beta = isset($_REQUEST['beta'])?$_REQUEST['beta']:'';

		$contracts = Array();
		//$pageDom = new DomDocument();
		//$pageDom->loadXML($xml);
		$pageDom = XML::loadXmlDom($xml);
		$e = $pageDom->getElementsByTagName('contract');
		$prev = '';
		foreach($e as $node)
		{
			$contracts[] = $node->nodeValue;
			
			$contract_year = DAO::getSingleValue($link, "select contract_year from contracts where id = $node->nodeValue");
			if($prev=='')
				$prev = $contract_year;
				
			if ($prev!=$contract_year)
				throw new Exception("All selected contracts must belong to the same academic year");
			
			$prev=$contract_year;
			
		/*	$value = DAO::getSingleValue($link, "select count(*) from ilr where contract_id=$node->nodeValue and submission='$submission'");
			if($value==0)
			{
				$contract = DAO::getSingleValue($link, "select title from contracts where id = $node->nodeValue");	
				throw new Exception("Contract [" . $contract . "] doesn't have any ILR for submission " . $submission);
			}
		*/
		}
		
		$con1 = Contract::loadFromDatabase($link, $contracts[0]);
		
		$contractsstring = implode(",",$contracts);
		$L25s = DAO::getSingleValue($link, "select count(distinct L25) from contracts where id in ($contractsstring)");	
		
		if($L25s==1)
			$L25 = $con1->L25;
		else
			$L25 = '999';

			
		$contract_year2 = substr($con1->contract_year,-2,2).str_pad((substr($con1->contract_year,-2,2)+1),2,'0',STR_PAD_LEFT);

		
		
		
		$transmission = DAO::getSingleValue($link, "select transmission from submissions where contract_year='$contract_year' and submission = '$submission'");
		
		
		if($transmission=='')
		{
			DAO::execute($link, "insert into submissions (submission, transmission, contract_year) values('$submission', 1, '$contract_year')");
			$transmission = 1;
		}
		else
		{
			$transmission += 1;

			if(!SOURCE_BLYTHE_VALLEY)
				DAO::execute($link, "update submissions set transmission = $transmission where submission = '$submission' and contract_year = '$contract_year' ");
		}
		
		$last_transmission = DAO::getSingleValue($link, "select sum(transmission) from submissions where contract_year='$contract_year'");
		

		if($contract_year2=='0809' || $contract_year2=='0910')
			$saveasname = 'A'.$con1->upin.'00'.$L25.$contract_year2. str_pad($transmission,3,'0',STR_PAD_LEFT) . '01.'.$submission;
		elseif($contract_year2=='1011')
		{
			$funding_model = DAO::getSingleValue($link, "select funding_body from contracts where id in ($contractsstring) limit 0,1");
			if($funding_model=='1')
				$saveasname = 'A'.$con1->upin.'00'.$contract_year2.	str_pad($last_transmission,3,'0',STR_PAD_LEFT) . '01.'.str_replace("W","LR",$submission);
			else
				$saveasname = 'A'.$con1->upin.'00'.$contract_year2.	str_pad($last_transmission,3,'0',STR_PAD_LEFT) . '01.'.str_replace("W","ER",$submission);
		}
		elseif($contract_year2=='1112')
		{

			if(DB_NAME!='am_platinum' && DB_NAME!='am_dv8training') // Single ILR
			{
				$saveasname = 'ILR-A-'.$con1->ukprn . '-' . $contract_year2 . '-' . str_pad($last_transmission,4,'0',STR_PAD_LEFT) . '-01.xml';
			}
			else
			{
				$funding_model = DAO::getSingleValue($link, "select funding_body from contracts where id in ($contractsstring) limit 0,1");
				if($funding_model=='1')
					$saveasname = 'A'.$con1->ukprn.'00'.$contract_year2 . str_pad($last_transmission,4,'0',STR_PAD_LEFT) . '01.LR';
				else
					$saveasname = 'A'.$con1->ukprn.'00'.$contract_year2 . str_pad($last_transmission,4,'0',STR_PAD_LEFT) . '01.ER';
			}
		}
        elseif($contract_year2=='1516')
        {
            $saveasname = 'ILR-'.$con1->ukprn . '-' . $contract_year2 . '-' . date('Ymd-His') . '-01.xml';
        }
        elseif($contract_year2=='1617')
        {
            $saveasname = 'ILR-'.$con1->ukprn . '-' . $contract_year2 . '-' . date('Ymd-His') . '-01.xml';
        }
		else
        {
            if($beta==1)
                $saveasname = 'ILR-99999999-' . $contract_year2 . '-' . date('Ymd-His') . '-01.xml';
            else
                $saveasname = 'ILR-'.$con1->ukprn . '-' . $contract_year2 . '-' . date('Ymd-His') . '-01.xml';
        }

		require_once('tpl_download' . $contract_year . '.php');
	}
}
?>
