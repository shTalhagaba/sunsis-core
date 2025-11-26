<?php
class create_submission implements IAction
{
	public function execute(PDO $link)
	{
		
		$contract_id = isset($_REQUEST['contract_id'])?$_REQUEST['contract_id']:'';
		$submission = isset($_REQUEST['submission'])?$_REQUEST['submission']:'';
		
		
		$submissiontocreate = (int)substr($submission,1);
		$previoussubmission = 'W'.str_pad($submissiontocreate-1,2,'0',STR_PAD_LEFT);

		DAO::execute($link, "insert into ilr (select l01,l03,A09,ilr,'$submission',contract_type,tr_id, is_complete,is_valid, is_approved, is_active, contract_id from ilr where submission='$previoussubmission' and contract_id = '$contract_id' and tr_id not in (select tr_id from ilr where submission='$submission' and contract_id = '$contract_id'));");
		echo "<script language='javascript'> window.history.go(-1); </script>";
/*		
		if($submission=='W01')
		{
			$sql ="select contract_year from contracts where id =$contract_id";
			$contract_year = DAO::getSingleValue($link,$sql);
			
			$sql ="select count(*) from ilr where contract_id ='$contract_id' and submission='W01'";
			$ilrs = DAO::getResultset($link,$sql);
			//if((int)$ilrs[0][0]>0)
				//throw new Exception("You have already got ILRs for this submission");	
 
			$sql ="select count(*) from tr where contract_id ='$contract_id'";
			$trs = DAO::getResultset($link,$sql);
			if((int)$trs[0][0]==0)
				throw new Exception("There are no training records for this contract yet");	

			$sql = "SELECT contract_holder.upin, uln, l03, l28a, l28b, l34a, l34b, l34c, l34d, l36, l37, l39, l40a, l40b, l41a, l41b, l47, tr.id, surname, firstnames, DATE_FORMAT(dob,'%d/%m/%Y') as date_of_birth, DATE_FORMAT(closure_date, '%d/%m/%Y') as closure_date, ethnicity, gender, learning_difficulties, disability,learning_difficulty, home_postcode, CONCAT(TRIM(home_saon_start_number), TRIM(home_saon_start_suffix), ' ', TRIM(home_saon_end_number), TRIM(home_saon_end_suffix), ' ' , TRIM(home_saon_description)) as L18, home_locality, home_town,home_county, current_postcode, home_telephone, country_of_domicile, ni, prior_attainment_level,DATE_FORMAT(tr.start_date,'%d/%m/%Y') as start_date, DATE_FORMAT(target_date,'%d/%m/%Y') as target_date, status_code,provider_location_id, employer_id, provider_location.postcode as lpcode, organisations.legal_name as employer_name,employer_location.postcode as epcode FROM tr LEFT JOIN locations as provider_location ON provider_location.id = tr.provider_location_id LEFT JOIN locations as employer_location ON employer_location.id = tr.employer_location_id LEFT JOIN organisations ON organisations.id = tr.employer_id 	LEFT JOIN contracts on contracts.id = tr.contract_id LEFT JOIN organisations as contract_holder on contract_holder.id = contract_holder WHERE contract_id = '$contract_id' and tr.id not in (select tr_id from ilr)";

			$st = $link->query($sql);
			if($st)
			{

				while($row = $st->fetch())
				{	
					// here to create ilrs for the first time from training records.					
					$xml = '<ilr>';
					$xml .= "<learner>";
					$xml .= "<L01>" . $row['upin'] . "</L01>";
					$xml .= "<L02>" . "00" . "</L02>";
					$xml .= "<L03>" . $row['l03'] . "</L03>";
					$xml .= "<L04>" . "10" . "</L04>";

					// No of learning aim data sets
					$sql ="select COUNT(*) from student_qualifications where tr_id ={$row['id']}";
					$learning_aims = DAO::getResultset($link,$sql);
					
					$xml .= "<L05>" . $learning_aims[0][0] . "</L05>";
					$xml .= "<L06>" . "00" . "</L06>";
					$xml .= "<L07>" . "00" . "</L07>";
					if($row['status_code']==4 || $row['status_code']=='4')
						$xml .= "<L08>" . "Y" . "</L08>";
					else
						$xml .= "<L08>" . "N" . "</L08>";
					$xml .= "<L09>" . $row['surname'] . 				"</L09>";
					$xml .= "<L10>" . $row['firstnames'] . 				"</L10>";
					$xml .= "<L11>" . $row['date_of_birth'] . 			"</L11>";
					$xml .= "<L12>" . $row['ethnicity'] . 				"</L12>";
					$xml .= "<L13>" . $row['gender'] . 					"</L13>";
					$xml .= "<L14>" . $row['learning_difficulties'] .	"</L14>";
					$xml .= "<L15>" . $row['disability'] . 				"</L15>";
					$xml .= "<L16>" . $row['learning_difficulty'] .		"</L16>";
					$xml .= "<L17>" . $row['home_postcode'] . 			"</L17>";
					$xml .= "<L18>" . $row['L18'] .	"</L18>";
					$xml .= "<L19>" . $row['home_locality'] . 			"</L19>";
					$xml .= "<L20>" . $row['home_town'] . 				"</L20>";
					$xml .= "<L21>" . $row['home_county'] . 			"</L21>";
					$xml .= "<L22>" . $row['current_postcode'] .		"</L22>";
					$xml .= "<L23>" . $row['home_telephone'] . 			"</L23>";
					$xml .= "<L24>" . $row['country_of_domicile'] .		"</L24>";
					$xml .= "<L25>" . "</L25>";
					$xml .= "<L26>" . $row['ni'] . 						"</L26>";
					$xml .= "<L27>" . "1" . "</L27>";
					$xml .= "<L28a>" . $row['l28a'] . "</L28a>";
					$xml .= "<L28b>" . $row['l28b'] . "</L28b>";
					$xml .= "<L29>" . "00" . "</L29>";
					$xml .= "<L31>" . "000000" . "</L31>";
					$xml .= "<L32>" . "00" . "</L32>";
					$xml .= "<L33>" . "0.0000" . "</L33>";
					$xml .= "<L34a>" . $row['l34a'] . "</L34a>";
					$xml .= "<L34b>" . $row['l34b'] . "</L34b>";
					$xml .= "<L34c>" . $row['l34c'] . "</L34c>";
					$xml .= "<L34d>" . $row['l34d'] . "</L34d>";
					$xml .= "<L35>" . $row['prior_attainment_level'] .	"</L35>";
					$xml .= "<L36>" . $row['l36'] . "</L36>";
					$xml .= "<L37>" . $row['l37'] . "</L37>";
					$xml .= "<L38>" . "00" . "</L38>";
					$xml .= "<L39>" . $row['l39'] . "</L39>";
					$xml .= "<L40a>" . $row['l40a'] . "</L40a>";
					$xml .= "<L40b>" . $row['l40b'] . "</L40b>";
					$xml .= "<L41a>" . $row['l41a'] . "</L41a>";	
					$xml .= "<L41b>" . $row['l41b'] . "</L41b>";	
					$xml .= "<L42a>" . "</L42a>";	
					$xml .= "<L42b>" . "</L42b>";	
					$xml .= "<L44>" . "</L44>";
					$xml .= "<L45>" . $row['uln'] . "</L45>";	
					$xml .= "<L46>" . "</L46>";
					$xml .= "<L47>" . $row['l47'] . "</L47>";
					$xml .= "<L48>" . "</L48>";
					
					// Getting no. of sub aims
					$sql ="select count(*) from student_qualifications where tr_id ={$row['id']} and qualification_type!='NVQ'";
					$sub_aims = DAO::getResultset($link,$sql);
					
					$xml .= "<subaims>" . $sub_aims . "</subaims>";
					$xml .= "</learner>";
					$xml .= "<subaims>" . $sub_aims . "</subaims>";

					// Creating Programme aim
					if($contract_year=='2008')
					{
						$xml .= "<programmeaim>";
						$xml .= "<A04>" . "35" . "</A04>";
						$xml .= "<A09>" . "ZPROG001" . "</A09>";
						$xml .= "<A10>" . "45" . "</A10>";
						$xml .= "<A15>" . "</A15>";
						$xml .= "<A16>" . "</A16>";
						$xml .= "<A26>" . "</A26>";
						$xml .= "<A27>" . "</A27>";
						$xml .= "<A28>" . "</A28>";
						$xml .= "<A23>" . "</A23>";
						$xml .= "<A51a>" . "</A51a>";
						$xml .= "<A14>" . "</A14>";
						$xml .= "<A46a>" . "</A46a>";
						$xml .= "<A46b>" . "</A46b>";
						$xml .= "<A02>" . "</A02>";
						$xml .= "<A31>" . "</A31>";
						$xml .= "<A40>" . "</A40>";
						$xml .= "<A34>" . "</A34>";
						$xml .= "<A35>" . "</A35>";
						$xml .= "<A50>" . "</A50>";
						$xml .= "</programmeaim>";
					}
					
					
					// Creating main aim					
					$sql_main = "select * from student_qualifications where tr_id = {$row['id']} and qualification_type='NVQ'";
					$st2 = $link->query($sql_main);
					if($st2)
					{
						while($row_main = $st2->fetch())
						{	
							$xml .= "<main>";
							$xml .= "<A01>" . $row['upin'] . "</A01>";
							$xml .= "<A02>" . "</A02>";
							$xml .= "<A03>" . "</A03>";
							$xml .= "<A04>" . "30" . "</A04>";
							$xml .= "<A05>" . "01" . "</A05>";
							$xml .= "<A06>" . "00" . "</A06>";
							$xml .= "<A07>" . "00" . "</A07>";
							$xml .= "<A08>" . "2" . "</A08>";
							$xml .= "<A09>" . str_replace("/" , "", $row_main['id']) . "</A09>";
							$xml .= "<A10>" . "</A10>";
							$xml .= "<A11a>" . "000" . "</A11a>";
							$xml .= "<A11b>" . "000" . "</A11b>";
							$xml .= "<A12a>" . "000" . "</A12a>";
							$xml .= "<A12b>" . "000" . "</A12b>";
							$xml .= "<A13>" . "00000" . "</A13>";
							$xml .= "<A14>" . "00" . "</A14>";
							$xml .= "<A15>" . "</A15>";
							$xml .= "<A16>" . "</A16>";
							$xml .= "<A17>" . "0" . "</A17>";
							$xml .= "<A18>" . "</A18>";
							$xml .= "<A19>" . "0" . "</A19>";
							$xml .= "<A20>" . "0" . "</A20>";
							$xml .= "<A21>" . "00" . "</A21>";
							$xml .= "<A22>" . "      " . "</A22>";
							$xml .= "<A23>" . $row['lpcode'] . "</A23>";
							$xml .= "<A24>" . "</A24>";
							$xml .= "<A26>" . "</A26>";
							$xml .= "<A27>" . substr($row_main['start_date'],8,2) . '/' . substr($row_main['start_date'],5,2) . '/' . substr($row_main['start_date'],0,4) . "</A27>";
							$xml .= "<A28>" . substr($row_main['end_date'],8,2) . '/' . substr($row_main['end_date'],5,2) . '/' . substr($row_main['end_date'],0,4) . "</A28>";
							$xml .= "<A31>" . $row_main['actual_end_date'] . "</A31>";
							$xml .= "<A32>" . "</A32>";
							$xml .= "<A33>" . "     " . "</A33>";
							$xml .= "<A34>" . "</A34>";
							$xml .= "<A35>" . "</A35>";
							$xml .= "<A36>" . "   " . "</A36>";
							$xml .= "<A37>" . $row_main['unitsCompleted'] . "</A37>";
							$xml .= "<A38>" . $row_main['units'] . "</A38>";
							$xml .= "<A39>" . "0" . "</A39>";
							$xml .= "<A40>" . $row_main['achievement_date'] . "</A40>";
							$xml .= "<A43>" . $row['closure_date'] . "</A43>";
							$xml .= "<A44>" . str_pad($row['employer_name'],30,' ',STR_PAD_RIGHT) . "</A44>";
							$xml .= "<A45>" . $row['epcode'] . "</A45>";
							$xml .= "<A46a>" . "</A46a>";
							$xml .= "<A46b>" . "</A46b>";
							$xml .= "<A47a>" . "</A47a>";
							$xml .= "<A47b>" . "</A47b>";
							$xml .= "<A48a>" . "</A48a>";
							$xml .= "<A48b>" . "</A48b>";
							$xml .= "<A49>" . "     " . "</A49>";
							$xml .= "<A50>" . "</A50>";
							$xml .= "<A51a>" ."</A51a>";
							$xml .= "<A52>" . "00000" . "</A52>";
							$xml .= "<A53>" . "</A53>";
							$xml .= "<A54>" . "</A54>";
							$xml .= "<A55>" . "</A55>";
							$xml .= "<A56>" . "</A56>";
							$xml .= "<A57>" . "00" . "</A57>";
							$xml .= "</main>";
						}
					}
					
					
					// Creating Sub Aims out of framework
					$sql_sub = "select * from student_qualifications where tr_id = {$row['id']} and qualification_type!='NVQ'";
					$st3 = $link->query($sql_sub);
					if($st3)
					{
						$learningaim=2;	
						while($row_sub = $st3->fetch())
						{	
							$xml .= "<subaim>";
							$xml .= "<A01>" . $row['upin'] . "</A01>";
							$xml .= "<A02>" . "</A02>";
							$xml .= "<A03>" . "</A03>";
							$xml .= "<A04>" . "30" . "</A04>";
							$xml .= "<A05>" . str_pad($learningaim,2,'0',STR_PAD_LEFT) . "</A05>";
							$learningaim++;
							$xml .= "<A06>00</A06>";
							$xml .= "<A07>" . "00" . "</A07>";
							$xml .= "<A08>" . "2" . "</A08>";
							$xml .= "<A09>" . str_replace("/" , "", $row_sub['id']) . "</A09>";
							$xml .= "<A10>" . "</A10>";
							$xml .= "<A11a>" . "000" . "</A11a>";
							$xml .= "<A11b>" . "000" . "</A11b>";
							$xml .= "<A12a>" . "000" . "</A12a>";
							$xml .= "<A12b>" . "000" . "</A12b>";
							$xml .= "<A13>" . "00000" . "</A13>";
							$xml .= "<A14>" . "00" . "</A14>";
							$xml .= "<A15>" . "</A15>";
							$xml .= "<A16>" . "</A16>";
							$xml .= "<A17>" . "0" . "</A17>";
							$xml .= "<A18>" . "</A18>";
							$xml .= "<A19>" . "0" . "</A19>";
							$xml .= "<A20>" . "0" . "</A20>";
							$xml .= "<A21>" . "00" . "</A21>";
							$xml .= "<A22>" . "      " . "</A22>";
							$xml .= "<A23>" . $row['lpcode'] . "</A23>";
							$xml .= "<A24>" . "</A24>";
							$xml .= "<A26>" . "</A26>";
							$xml .= "<A27>" . substr($row_sub['start_date'],8,2) . '/' . substr($row_sub['start_date'],5,2) . '/' . substr($row_sub['start_date'],0,4) . "</A27>";
							$xml .= "<A28>" . substr($row_sub['end_date'],8,2) . '/' . substr($row_sub['end_date'],5,2) . '/' . substr($row_sub['end_date'],0,4) . "</A28>";
							$xml .= "<A31>" . $row_sub['actual_end_date'] . "</A31>";
							$xml .= "<A32>" . "</A32>";
							$xml .= "<A33>" . "     " . "</A33>";
							$xml .= "<A34>" . "</A34>";
							$xml .= "<A35>" . "</A35>";
							$xml .= "<A36>" . "   " . "</A36>";
							$xml .= "<A37>" . $row_sub['unitsCompleted'] . "</A37>";
							$xml .= "<A38>" . $row_sub['units'] . "</A38>";
							$xml .= "<A39>" . "0" . "</A39>";
							$xml .= "<A40>" . $row_sub['achievement_date'] . "</A40>";
							$xml .= "<A43>" . $row['closure_date'] . "</A43>";
							$xml .= "<A44>" . "</A44>";
							$xml .= "<A45>" . "</A45>";
							$xml .= "<A46a>" . "</A46a>";
							$xml .= "<A46b>" . "</A46b>";
							$xml .= "<A47a>" . "</A47a>";
							$xml .= "<A47b>" . "</A47b>";
							$xml .= "<A48a>" . "</A48a>";
							$xml .= "<A48b>" . "</A48b>";
							$xml .= "<A49>" . "     " . "</A49>";
							$xml .= "<A50>" . "</A50>";
							$xml .= "<A51a>" ."</A51a>";
							$xml .= "<A52>" . "00000" . "</A52>";
							$xml .= "<A53>" . "</A53>";
							$xml .= "<A54>" . "</A54>";
							$xml .= "<A55>" . "</A55>";
							$xml .= "<A56>" . "</A56>";
							$xml .= "<A57>" . "00" . "</A57>";
							$xml .= "</subaim>";
						}
					}

					// Creating Sub Aims out of additional qualifications
					$sql_sub = "select * from student_qualifications where tr_id = {$row['id']} and framework_id=0";
					$st4 = $link->query($sql_sub);	
					if($st4)
					{
						$learningaim=2;	
						while($row_sub = $st4->fetch())
						{	
							$xml .= "<subaim>";
							$xml .= "<A01>" . $row['upin'] . "</A01>";
							$xml .= "<A02>" . "</A02>";
							$xml .= "<A03>" . "</A03>";
							$xml .= "<A04>" . "30" . "</A04>";
							$xml .= "<A05>" . str_pad($learningaim,2,'0',STR_PAD_LEFT) . "</A05>";
							$learningaim++;
							$xml .= "<A06>00</A06>";
							$xml .= "<A07>" . "00" . "</A07>";
							$xml .= "<A08>" . "2" . "</A08>";
							$xml .= "<A09>" . str_replace("/" , "", $row_sub['id']) . "</A09>";
							$xml .= "<A10>" . "</A10>";
							$xml .= "<A11a>" . "000" . "</A11a>";
							$xml .= "<A11b>" . "000" . "</A11b>";
							$xml .= "<A12a>" . "000" . "</A12a>";
							$xml .= "<A12b>" . "000" . "</A12b>";
							$xml .= "<A13>" . "00000" . "</A13>";
							$xml .= "<A14>" . "00" . "</A14>";
							$xml .= "<A15>" . "</A15>";
							$xml .= "<A16>" . "</A16>";
							$xml .= "<A17>" . "0" . "</A17>";
							$xml .= "<A18>" . "</A18>";
							$xml .= "<A19>" . "0" . "</A19>";
							$xml .= "<A20>" . "0" . "</A20>";
							$xml .= "<A21>" . "00" . "</A21>";
							$xml .= "<A22>" . "      " . "</A22>";
							$xml .= "<A23>" . $row['lpcode'] . "</A23>";
							$xml .= "<A24>" . "</A24>";
							$xml .= "<A26>" . "</A26>";
							$xml .= "<A27>" . substr($row_sub['start_date'],8,2) . '/' . substr($row_sub['start_date'],5,2) . '/' . substr($row_sub['start_date'],0,4) . "</A27>";
							$xml .= "<A28>" . substr($row_sub['end_date'],8,2) . '/' . substr($row_sub['end_date'],5,2) . '/' . substr($row_sub['end_date'],0,4) . "</A28>";
							$xml .= "<A31>" . $row_sub['actual_end_date'] . "</A31>";
							$xml .= "<A32>" . "</A32>";
							$xml .= "<A33>" . "     " . "</A33>";
							$xml .= "<A34>" . "</A34>";
							$xml .= "<A35>" . "</A35>";
							$xml .= "<A36>" . "   " . "</A36>";
							$xml .= "<A37>" . $row_sub['unitsCompleted'] . "</A37>";
							$xml .= "<A38>" . $row_sub['units'] . "</A38>";
							$xml .= "<A39>" . "0" . "</A39>";
							$xml .= "<A40>" . $row_sub['achievement_date'] . "</A40>";
							$xml .= "<A43>" . $row['closure_date'] . "</A43>";
							$xml .= "<A44>" . "</A44>";
							$xml .= "<A45>" . "</A45>";
							$xml .= "<A46a>" . "</A46a>";
							$xml .= "<A46b>" . "</A46b>";
							$xml .= "<A47a>" . "</A47a>";
							$xml .= "<A47b>" . "</A47b>";
							$xml .= "<A48a>" . "</A48a>";
							$xml .= "<A48b>" . "</A48b>";
							$xml .= "<A49>" . "     " . "</A49>";
							$xml .= "<A50>" . "</A50>";
							$xml .= "<A51a>" ."</A51a>";
							$xml .= "<A52>" . "00000" . "</A52>";
							$xml .= "<A53>" . "</A53>";
							$xml .= "<A54>" . "</A54>";
							$xml .= "<A55>" . "</A55>";
							$xml .= "<A56>" . "</A56>";
							$xml .= "<A57>" . "00" . "</A57>";
							$xml .= "</subaim>";
						}
					}
					
					
					
					$xml .= "</ilr>";

					// getting contract type 
				
					$sql = "Select contract_type from contracts where id ='$contract_id'";
					$contract_type = DAO::getResultset($link, $sql);
					$contract_type = $contract_type[0][0];					
					
					// $xml = addslashes((string)$xml);
					$contract = addslashes((string)$contract_id);
					$contract_type=addslashes((string)$contract_type);
					$tr_id = $row['id'];
					
					$upin = $row['upin'];
					$l03 = $row['l03'];
					
					$sql = "insert into ilr (L01,L03, A09, ilr,submission,contract_type,tr_id,is_complete,is_valid,is_approved,is_active,contract_id) values('$upin','$l03','0','$xml','W01','$contract_type','$tr_id','0','0','0','1','$contract');";
					$st5 = $link->query($sql);			
					if($st5 == false)
						throw new Exception(implode($link->errorInfo()).'..........'.$sql, $link->errorCode());
											
				}
			}
				echo "<script language='javascript'> window.history.go(-1); </script>";
		}
		else
		{
		
			$submissiontocreate = (int)substr($submission,1);
			$contract = (int)$contract_id;

			
			// Check if ILRs already there
			$sql = "Select count(*) from ilr where submission = '$submission' and contract_id ='$contract_id'";
			$no_of_ilrs = DAO::getResultset($link, $sql);
			$no_of_ilrs = $no_of_ilrs[0][0];
			
			if($no_of_ilrs>0)
				throw new Exception("ILRs already exist");
			
				
			// Check if previous submission exists
			$newsubmission=$submission;
			$previoussubmission = 'W'.str_pad($submissiontocreate-1,2,'0',STR_PAD_LEFT);

			
$sql = <<<HEREDOC
SELECT
	*
FROM
	ilr where submission='$previoussubmission' and contract_id='$contract';
HEREDOC;


			$st6 = $link->query($sql);
			if($st6) 
			{
				if($st6->rowCount()>0)
				{
					while($row = $st6->fetch())
					{
						$L01_escaped = addslashes((string)$row['L01']);
						$L03_escaped = addslashes((string)$row['L03']);
						$A09_escaped = addslashes((string)$row['A09']);
						$ilr_escaped = $row['ilr'];
						
						// Check if is there is any change in previous ILR and current Training before
						// $vo = Ilr0708::loadFromDatabase($link, $id);
						$tr_id = $row['tr_id'];		
						$sql2 = "select uln, l03, l28a, l28b, l34a, l34b, l34c, l34d, l36, l37, l39, l40a, l40b, l41a, l41b, l47, id, surname, firstnames, DATE_FORMAT(dob,'%d/%m/%Y') as date_of_birth, DATE_FORMAT(closure_date, '%d/%m/%Y') as closure_date, ethnicity, gender, learning_difficulties, disability, learning_difficulty, home_postcode, CONCAT(TRIM(home_saon_start_number), TRIM(home_saon_start_suffix), ' ', TRIM(home_saon_end_number), TRIM(home_saon_end_suffix), ' ' , TRIM(home_saon_description)) as L18, home_locality, home_town, home_county, current_postcode, home_telephone, country_of_domicile, ni, prior_attainment_level, DATE_FORMAT(start_date,'%d/%m/%Y') as start_date, DATE_FORMAT(target_date,'%d/%m/%Y') as target_date, status_code from tr where id = '$tr_id'";
						$st9 = $link->query($sql2);	
						if($st9) 
						{
							if($st9->rowCount()>0)
							{
								$row2=$st9->fetch();						
								if($row2['status_code']=='4' || $row2['status_code']==4)
									$ilr_escaped =  str_replace((substr($ilr_escaped,strpos($ilr_escaped,'<L08>')+5,(strpos($ilr_escaped,'</L08>')-strpos($ilr_escaped,'<L08>')-5))),"Y",$ilr_escaped);								
								else
									$ilr_escaped =  str_replace((substr($ilr_escaped,strpos($ilr_escaped,'<L08>')+5,(strpos($ilr_escaped,'</L08>')-strpos($ilr_escaped,'<L08>')-5))),"N",$ilr_escaped);								

									
								//$ilr_escaped = str_replace((substr($ilr_escaped,strpos($ilr_escaped,'<L03>'),(strpos($ilr_escaped,'</L03>')-strpos($ilr_escaped,'<L03>')+6))),('<L03>' . $row2['l03'] . '</L03>'),$ilr_escaped);
								//$ilr_escaped = str_replace((substr($ilr_escaped,strpos($ilr_escaped,'<L09>'),(strpos($ilr_escaped,'</L09>')-strpos($ilr_escaped,'<L09>')+6))),('<L09>' . $row2['surname'] . '</L09>'),$ilr_escaped);
								//$ilr_escaped = str_replace((substr($ilr_escaped,strpos($ilr_escaped,'<L10>'),(strpos($ilr_escaped,'</L10>')-strpos($ilr_escaped,'<L10>')+6))),('<L10>' . $row2['firstnames'] . '</L10>'),$ilr_escaped);
								//$ilr_escaped = str_replace((substr($ilr_escaped,strpos($ilr_escaped,'<L11>'),(strpos($ilr_escaped,'</L11>')-strpos($ilr_escaped,'<L11>')+6))),('<L11>' . $row2['date_of_birth'] . '</L11>'),$ilr_escaped);
								//$ilr_escaped = str_replace((substr($ilr_escaped,strpos($ilr_escaped,'<L12>'),(strpos($ilr_escaped,'</L12>')-strpos($ilr_escaped,'<L12>')+6))),('<L12>' . $row2['ethnicity'] . '</L12>'),$ilr_escaped);
								//$ilr_escaped = str_replace((substr($ilr_escaped,strpos($ilr_escaped,'<L13>'),(strpos($ilr_escaped,'</L13>')-strpos($ilr_escaped,'<L13>')+6))),('<L13>' . $row2['gender'] . '</L13>'),$ilr_escaped);
								//$ilr_escaped = str_replace((substr($ilr_escaped,strpos($ilr_escaped,'<L14>'),(strpos($ilr_escaped,'</L14>')-strpos($ilr_escaped,'<L14>')+6))),('<L14>' . $row2['learning_difficulties'] . '</L14>'),$ilr_escaped);
								//$ilr_escaped = str_replace((substr($ilr_escaped,strpos($ilr_escaped,'<L15>'),(strpos($ilr_escaped,'</L15>')-strpos($ilr_escaped,'<L15>')+6))),('<L15>' . $row2['disability'] . '</L15>'),$ilr_escaped);
								//$ilr_escaped = str_replace((substr($ilr_escaped,strpos($ilr_escaped,'<L16>'),(strpos($ilr_escaped,'</L16>')-strpos($ilr_escaped,'<L16>')+6))),('<L16>' . $row2['learning_difficulty'] . '</L16>'),$ilr_escaped);
								//$ilr_escaped = str_replace((substr($ilr_escaped,strpos($ilr_escaped,'<L17>'),(strpos($ilr_escaped,'</L17>')-strpos($ilr_escaped,'<L17>')+6))),('<L17>' . $row2['home_postcode'] . '</L17>'),$ilr_escaped);
								//$ilr_escaped = str_replace((substr($ilr_escaped,strpos($ilr_escaped,'<L18>'),(strpos($ilr_escaped,'</L18>')-strpos($ilr_escaped,'<L18>')+6))),('<L18>' . $row2['L18'] . '</L18>'),$ilr_escaped);
								//$ilr_escaped = str_replace((substr($ilr_escaped,strpos($ilr_escaped,'<L19>'),(strpos($ilr_escaped,'</L19>')-strpos($ilr_escaped,'<L19>')+6))),('<L19>' . $row2['home_locality'] . '</L19>'),$ilr_escaped);
								//$ilr_escaped = str_replace((substr($ilr_escaped,strpos($ilr_escaped,'<L20>'),(strpos($ilr_escaped,'</L20>')-strpos($ilr_escaped,'<L20>')+6))),('<L20>' . $row2['home_town'] . '</L20>'),$ilr_escaped);
								//$ilr_escaped = str_replace((substr($ilr_escaped,strpos($ilr_escaped,'<L21>'),(strpos($ilr_escaped,'</L21>')-strpos($ilr_escaped,'<L21>')+6))),('<L21>' . $row2['home_county'] . '</L21>'),$ilr_escaped);
								//$ilr_escaped = str_replace((substr($ilr_escaped,strpos($ilr_escaped,'<L22>'),(strpos($ilr_escaped,'</L22>')-strpos($ilr_escaped,'<L22>')+6))),('<L22>' . $row2['current_postcode'] . '</L22>'),$ilr_escaped);
								//$ilr_escaped = str_replace((substr($ilr_escaped,strpos($ilr_escaped,'<L23>'),(strpos($ilr_escaped,'</L23>')-strpos($ilr_escaped,'<L23>')+6))),('<L23>' . $row2['home_telephone'] . '</L23>'),$ilr_escaped);
								//$ilr_escaped = str_replace((substr($ilr_escaped,strpos($ilr_escaped,'<L24>'),(strpos($ilr_escaped,'</L24>')-strpos($ilr_escaped,'<L24>')+6))),('<L24>' . $row2['country_of_domicile'] . '</L24>'),$ilr_escaped);
								//$ilr_escaped = str_replace((substr($ilr_escaped,strpos($ilr_escaped,'<L26>'),(strpos($ilr_escaped,'</L26>')-strpos($ilr_escaped,'<L26>')+6))),('<L26>' . $row2['ni'] . '</L26>'),$ilr_escaped);
								//$ilr_escaped = str_replace((substr($ilr_escaped,strpos($ilr_escaped,'<L28a>'),(strpos($ilr_escaped,'</L28a>')-strpos($ilr_escaped,'<L28a>')+6))),('<L28a>' . $row2['l28a'] . '</L28a>'),$ilr_escaped);
								//$ilr_escaped = str_replace((substr($ilr_escaped,strpos($ilr_escaped,'<L28b>'),(strpos($ilr_escaped,'</L28b>')-strpos($ilr_escaped,'<L28b>')+6))),('<L28b>' . $row2['l28b'] . '</L28b>'),$ilr_escaped);
								//$ilr_escaped = str_replace((substr($ilr_escaped,strpos($ilr_escaped,'<L34a>'),(strpos($ilr_escaped,'</L34a>')-strpos($ilr_escaped,'<L34a>')+6))),('<L34a>' . $row2['l34a'] . '</L34a>'),$ilr_escaped);
								//$ilr_escaped = str_replace((substr($ilr_escaped,strpos($ilr_escaped,'<L34b>'),(strpos($ilr_escaped,'</L34b>')-strpos($ilr_escaped,'<L34b>')+6))),('<L34b>' . $row2['l34b'] . '</L34b>'),$ilr_escaped);
								//$ilr_escaped = str_replace((substr($ilr_escaped,strpos($ilr_escaped,'<L34c>'),(strpos($ilr_escaped,'</L34c>')-strpos($ilr_escaped,'<L34c>')+6))),('<L34c>' . $row2['l34c'] . '</L34c>'),$ilr_escaped);
								//$ilr_escaped = str_replace((substr($ilr_escaped,strpos($ilr_escaped,'<L34d>'),(strpos($ilr_escaped,'</L34d>')-strpos($ilr_escaped,'<L34d>')+6))),('<L34d>' . $row2['l34d'] . '</L34d>'),$ilr_escaped);
								//$ilr_escaped = str_replace((substr($ilr_escaped,strpos($ilr_escaped,'<L35>'),(strpos($ilr_escaped,'</L35>')-strpos($ilr_escaped,'<L35>')+6))),('<L35>' . $row2['prior_attainment_level'] . '</L35>'),$ilr_escaped);
								//$ilr_escaped = str_replace((substr($ilr_escaped,strpos($ilr_escaped,'<L36>'),(strpos($ilr_escaped,'</L36>')-strpos($ilr_escaped,'<L36>')+6))),('<L36>' . $row2['l36'] . '</L36>'),$ilr_escaped);
								//$ilr_escaped = str_replace((substr($ilr_escaped,strpos($ilr_escaped,'<L37>'),(strpos($ilr_escaped,'</L37>')-strpos($ilr_escaped,'<L37>')+6))),('<L37>' . $row2['l37'] . '</L37>'),$ilr_escaped);
								//$ilr_escaped = str_replace((substr($ilr_escaped,strpos($ilr_escaped,'<L39>'),(strpos($ilr_escaped,'</L39>')-strpos($ilr_escaped,'<L39>')+6))),('<L39>' . $row2['l39'] . '</L39>'),$ilr_escaped);
								//$ilr_escaped = str_replace((substr($ilr_escaped,strpos($ilr_escaped,'<L40a>'),(strpos($ilr_escaped,'</L40a>')-strpos($ilr_escaped,'<L40a>')+6))),('<L40a>' . $row2['l40a'] . '</L40a>'),$ilr_escaped);
								//$ilr_escaped = str_replace((substr($ilr_escaped,strpos($ilr_escaped,'<L40b>'),(strpos($ilr_escaped,'</L40b>')-strpos($ilr_escaped,'<L40b>')+6))),('<L40b>' . $row2['l40b'] . '</L40b>'),$ilr_escaped);
								//$ilr_escaped = str_replace((substr($ilr_escaped,strpos($ilr_escaped,'<L41a>'),(strpos($ilr_escaped,'</L41a>')-strpos($ilr_escaped,'<L41a>')+6))),('<L41a>' . $row2['l41a'] . '</L41a>'),$ilr_escaped);
								//$ilr_escaped = str_replace((substr($ilr_escaped,strpos($ilr_escaped,'<L41b>'),(strpos($ilr_escaped,'</L41b>')-strpos($ilr_escaped,'<L41b>')+6))),('<L41b>' . $row2['l41b'] . '</L41b>'),$ilr_escaped);
								//$ilr_escaped = str_replace((substr($ilr_escaped,strpos($ilr_escaped,'<L45>'),(strpos($ilr_escaped,'</L45>')-strpos($ilr_escaped,'<L45>')+6))),('<L45>' . $row2['uln'] . '</L45>'),$ilr_escaped);
								//$ilr_escaped = str_replace((substr($ilr_escaped,strpos($ilr_escaped,'<L47>'),(strpos($ilr_escaped,'</L47>')-strpos($ilr_escaped,'<L47>')+6))),('<L47>' . $row2['l47'] . '</L47>'),$ilr_escaped);
								//$ilr_escaped = str_replace((substr($ilr_escaped,strpos($ilr_escaped,'<A27>'),(strpos($ilr_escaped,'</A27>')-strpos($ilr_escaped,'<A27>')+6))),('<A27>' . $row2['start_date'] . '</A27>'),$ilr_escaped);
								//$ilr_escaped = str_replace((substr($ilr_escaped,strpos($ilr_escaped,'<A28>'),(strpos($ilr_escaped,'</A28>')-strpos($ilr_escaped,'<A28>')+6))),('<A28>' . $row2['target_date'] . '</A28>'),$ilr_escaped);
								
							}				
						}
						
						
						$contract_type = addslashes((string)$row['contract_type']);
						$tr_id = addslashes((string)$row['tr_id']);
						$is_valid = addslashes((string)$row['is_valid']);
						$is_approved = $row['is_approved'];
						$is_active = $row['is_active'];

						$sql3 = "insert into ilr (L01,L03,A09,ilr,submission,contract_type,tr_id,is_valid,is_approved,is_active,contract_id) values('$L01_escaped','$L03_escaped','$A09_escaped','$ilr_escaped','$newsubmission','$contract_type','$tr_id','$is_valid','$is_approved','$is_active','$contract');";
						$st10 = $link->query($sql3);
						if($st10== false)
							throw new Exception(implode($link->errorInfo()).'..........'.$sql, $st->errorCode());
						}
					
				
					
					// Check if there are new Training Records for this submission!
					$sql = "SELECT contract_holder.upin, uln, l03, l28a, l28b, l34a, l34b, l34c, l34d, l36, l37, l39, l40a, l40b, l47, tr.id, surname, firstnames, DATE_FORMAT(dob,'%d/%m/%Y') as date_of_birth, DATE_FORMAT(closure_date, '%d/%m/%Y') as closure_date, ethnicity, gender, learning_difficulties, disability,learning_difficulty, home_postcode, CONCAT(TRIM(home_saon_start_number), TRIM(home_saon_start_suffix), ' ', TRIM(home_saon_end_number), TRIM(home_saon_end_suffix), ' ' , TRIM(home_saon_description)) as L18, home_locality, home_town,home_county, current_postcode, home_telephone, country_of_domicile, ni, prior_attainment_level,DATE_FORMAT(start_date,'%d/%m/%Y') as start_date, DATE_FORMAT(target_date,'%d/%m/%Y') as target_date, status_code,provider_location_id, employer_id, provider_location.postcode as lpcode, organisations.legal_name as employer_name,employer_location.postcode as epcode FROM tr LEFT JOIN locations as provider_location ON provider_location.id = tr.provider_location_id LEFT JOIN locations as employer_location ON employer_location.id = tr.employer_location_id LEFT JOIN organisations ON organisations.id = tr.employer_id LEFT JOIN contracts on contracts.id = tr.contract_id LEFT JOIN organisations as contract_holder on contract_holder.id = contract_holder where contract_id = '$contract' and tr.id NOT IN (select tr_id from ilr where submission='$newsubmission');";
					$st11 = $link->query($sql);
					if($st11)
					{
						while($row = $st11->fetch())
						{	
							// here to create ilrs for the first time from training records.					
							$xml = '<ilr>';
							$xml .= "<learner>";
							$xml .= "<L01>" . $row['upin'] . "</L01>";
							$xml .= "<L02>" . "00" . "</L02>";
							$xml .= "<L03>" . $row['l03'] . "</L03>";
							$xml .= "<L04>" . "10" . "</L04>";

							// No. of learning aims	
							$sql ="select count(*) from student_qualifications where tr_id ={$row['id']}";
							$learning_aims = DAO::getResultset($link,$sql);
							
							$xml .= "<L05>" . $learning_aims[0][0] . "</L05>";
							$xml .= "<L06>" . "00" . "</L06>";
							$xml .= "<L07>" . "00" . "</L07>";
							if($row['status_code']==4 || $row['status_code']=='4')
								$xml .= "<L08>" . "Y" . "</L08>";
							else
								$xml .= "<L08>" . "N" . "</L08>";
							$xml .= "<L09>" . $row['surname'] . 				"</L09>";
							$xml .= "<L10>" . $row['firstnames'] . 				"</L10>";
							$xml .= "<L11>" . $row['date_of_birth'] . 			"</L11>";
							$xml .= "<L12>" . $row['ethnicity'] . 				"</L12>";
							$xml .= "<L13>" . $row['gender'] . 					"</L13>";
							$xml .= "<L14>" . $row['learning_difficulties'] .	"</L14>";
							$xml .= "<L15>" . $row['disability'] . 				"</L15>";
							$xml .= "<L16>" . $row['learning_difficulty'] .		"</L16>";
							$xml .= "<L17>" . $row['home_postcode'] . 			"</L17>";
							$xml .= "<L18>" . $row['L18'] . 					"</L18>";
							$xml .= "<L19>" . $row['home_locality'] . 			"</L19>";
							$xml .= "<L20>" . $row['home_town'] . 				"</L20>";
							$xml .= "<L21>" . $row['home_county'] . 			"</L21>";
							$xml .= "<L22>" . $row['current_postcode'] .		"</L22>";
							$xml .= "<L23>" . $row['home_telephone'] . 			"</L23>";
							$xml .= "<L24>" . $row['country_of_domicile'] .		"</L24>";
							$xml .= "<L25>" . "</L25>";
							$xml .= "<L26>" . $row['ni'] . 						"</L26>";
							$xml .= "<L27>" . "1" + "</L27>";
							$xml .= "<L28a>" . $row['l28a'] . "</L28a>";
							$xml .= "<L28b>" . $row['l28b'] . "</L28b>";
							$xml .= "<L29>" . "00" . "</L29>";
							$xml .= "<L31>" . "000000" . "</L31>";
							$xml .= "<L32>" . "00" . "</L32>";
							$xml .= "<L33>" . "0.0000" . "</L33>";
							$xml .= "<L34a>" . $row['l34a'] . "</L34a>";
							$xml .= "<L34b>" . $row['l34b'] . "</L34b>";
							$xml .= "<L34c>" . $row['l34c'] . "</L34c>";
							$xml .= "<L34d>" . $row['l34d'] . "</L34d>";
							$xml .= "<L35>" . $row['prior_attainment_level'] .	"</L35>";
							$xml .= "<L36>" . $row['l36'] . "</L36>";
							$xml .= "<L37>" . $row['l37'] . "</L37>";
							$xml .= "<L38>" . "00" . "</L38>";
							$xml .= "<L39>" . $row['l39'] . "</L39>";
							$xml .= "<L40a>" . $row['l40a'] . "</L40a>";
							$xml .= "<L40b>" . $row['l40b'] . "</L40b>";
							$xml .= "<L41a>" . $row['l41a'] . "</L41a>";	
							$xml .= "<L41b>" . $row['l41b'] . "</L41b>";	
							$xml .= "<L42a>" . "</L42a>";	
							$xml .= "<L42b>" . "</L42b>";	
							$xml .= "<L44>" . "</L44>";
							$xml .= "<L45>" . $row['uln'] . "</L45>";	
							$xml .= "<L46>" . "</L46>";
							$xml .= "<L47>" . $row['l47'] . "</L47>";
							$xml .= "<L48>" . "</L48>";

							// Getting no. of sub aims
							$sql ="select count(*) from student_qualifications where tr_id ={$row['id']} and qualification_type!='NVQ'";
							$sub_aims = DAO::getResultset($link,$sql);
							
							$xml .= "<subaims>" . $sub_aims . "</subaims>";
							$xml .= "</learner>";
							$xml .= "<subaims>" . $sub_aims . "</subaims>";
		
							// Creating proggramme aim
							if($contract_year=='2008')
							{
								$xml .= "<programmeaim>";
								$xml .= "<A04>" . "35" . "</A04>";
								$xml .= "<A09>" . "ZPROG001" . "</A09>";
								$xml .= "<A10>" . "45" . "</A10>";
								$xml .= "<A15>" . "</A15>";
								$xml .= "<A16>" . "</A16>";
								$xml .= "<A26>" . "</A26>";
								$xml .= "<A27>" . "</A27>";
								$xml .= "<A28>" . "</A28>";
								$xml .= "<A23>" . "</A23>";
								$xml .= "<A51a>" . "</A51a>";
								$xml .= "<A14>" . "</A14>";
								$xml .= "<A46a>" . "</A46a>";
								$xml .= "<A46b>" . "</A46b>";
								$xml .= "<A02>" . "</A02>";
								$xml .= "<A31>" . "</A31>";
								$xml .= "<A40>" . "</A40>";
								$xml .= "<A34>" . "</A34>";
								$xml .= "<A35>" . "</A35>";
								$xml .= "<A50>" . "</A50>";
								$xml .= "</programmeaim>";
							}
							
							// Creating main aim					
							$sql_main = "select * from student_qualifications where tr_id = {$row['id']} and qualification_type='NVQ'";
							$st13 = $link->query($sql_main);
							if($st13)
							{
								while($row_main = $st13->fetch())
								{	
									$xml .= "<main>";
									$xml .= "<A01>" . $row['upin'] . "</A01>";
									$xml .= "<A02>" . "</A02>";
									$xml .= "<A03>" . "</A03>";
									$xml .= "<A04>" . "30" . "</A04>";
									$xml .= "<A05>" . "01" . "</A05>";
									$xml .= "<A06>00</A06>";
									$xml .= "<A07>" . "00" . "</A07>";
									$xml .= "<A08>" . "2" . "</A08>";
									$xml .= "<A09>" . str_replace("/" , "", $row_main['id']) . "</A09>";
									$xml .= "<A10>" . "</A10>";
									$xml .= "<A11a>" . "000" . "</A11a>";
									$xml .= "<A11b>" . "000" . "</A11b>";
									$xml .= "<A12a>" . "000" . "</A12a>";
									$xml .= "<A12b>" . "000" . "</A12b>";
									$xml .= "<A13>" . "00000" . "</A13>";
									$xml .= "<A14>" . "00" . "</A14>";
									$xml .= "<A15>" . "</A15>";
									$xml .= "<A16>" . "</A16>";
									$xml .= "<A17>" . "0" . "</A17>";
									$xml .= "<A18>" . "</A18>";
									$xml .= "<A19>" . "0" . "</A19>";
									$xml .= "<A20>" . "0" . "</A20>";
									$xml .= "<A21>" . "00" . "</A21>";
									$xml .= "<A22>" . "      " . "</A22>";
									$xml .= "<A23>" . $row['lpcode'] . "</A23>";
									$xml .= "<A24>" . "</A24>";
									$xml .= "<A26>" . "</A26>";
									$xml .= "<A27>" . "</A27>";
									$xml .= "<A28>" . "</A28>";
									$xml .= "<A31>" . $row_main['actual_end_date'] . "</A31>";
									$xml .= "<A32>" . "</A32>";
									$xml .= "<A33>" . "     " . "</A33>";
									$xml .= "<A34>" . "</A34>";
									$xml .= "<A35>" . "</A35>";
									$xml .= "<A36>" . "   " . "</A36>";
									$xml .= "<A37>" . $row_main['unitsCompleted'] . "</A37>";
									$xml .= "<A38>" . $row_main['units'] . "</A38>";
									$xml .= "<A39>" . "0" . "</A39>";
									$xml .= "<A40>" . $row_main['achievement_date'] . "</A40>";
									$xml .= "<A43>" . $row['closure_date'] . "</A43>";
									$xml .= "<A44>" . str_pad($row['employer_name'],30,' ',STR_PAD_RIGHT) . "</A44>";
									$xml .= "<A45>" . $row['epcode'] . "</A45>";
									$xml .= "<A46a>" . "</A46a>";
									$xml .= "<A46b>" . "</A46b>";
									$xml .= "<A47a>" . "</A47a>";
									$xml .= "<A47b>" . "</A47b>";
									$xml .= "<A48a>" . "</A48a>";
									$xml .= "<A48b>" . "</A48b>";
									$xml .= "<A49>" . "     " . "</A49>";
									$xml .= "<A50>" . "</A50>";
									$xml .= "<A51a>" ."</A51a>";
									$xml .= "<A52>" . "00000" . "</A52>";
									$xml .= "<A53>" . "</A53>";
									$xml .= "<A54>" . "</A54>";
									$xml .= "<A55>" . "</A55>";
									$xml .= "<A56>" . "</A56>";
									$xml .= "<A57>" . "00" . "</A57>";
									$xml .= "</main>";
								}
							}
							
							
							// Creating Sub Aims
							$sql_sub = "select * from student_qualifications where tr_id = {$row['id']} and qualification_type!='NVQ'";
							$st14 = $link->query($sql_sub);
							if($st14)
							{
								$learningaim=2;	
								while($row_sub = $st14->fetch())
								{	
									$xml .= "<subaim>";
									$xml .= "<A01>" . $row['upin'] . "</A01>";
									$xml .= "<A02>" . "</A02>";
									$xml .= "<A03>" . "</A03>";
									$xml .= "<A04>" . "30" . "</A04>";
									$xml .= "<A05>" . str_pad($learningaim,2,'0',STR_PAD_LEFT) . "</A05>";
									$learningaim++;
									$xml .= "<A06>00</A06>";
									$xml .= "<A07>" . "00" . "</A07>";
									$xml .= "<A08>" . "2" . "</A08>";
									$xml .= "<A09>" . str_replace("/" , "", $row_sub['id']) . "</A09>";
									$xml .= "<A10>" . "</A10>";
									$xml .= "<A11a>" . "000" . "</A11a>";
									$xml .= "<A11b>" . "000" . "</A11b>";
									$xml .= "<A12a>" . "000" . "</A12a>";
									$xml .= "<A12b>" . "000" . "</A12b>";
									$xml .= "<A13>" . "00000" . "</A13>";
									$xml .= "<A14>" . "00" . "</A14>";
									$xml .= "<A15>" . "</A15>";
									$xml .= "<A16>" . "</A16>";
									$xml .= "<A17>" . "0" . "</A17>";
									$xml .= "<A18>" . "</A18>";
									$xml .= "<A19>" . "0" . "</A19>";
									$xml .= "<A20>" . "0" . "</A20>";
									$xml .= "<A21>" . "00" . "</A21>";
									$xml .= "<A22>" . "      " . "</A22>";
									$xml .= "<A23>" . $row['lpcode'] . "</A23>";
									$xml .= "<A24>" . "</A24>";
									$xml .= "<A26>" . "</A26>";
									$xml .= "<A27>" . "</A27>";
									$xml .= "<A28>" . "</A28>";
									$xml .= "<A31>" . $row_sub['actual_end_date'] . "</A31>";
									$xml .= "<A32>" . "</A32>";
									$xml .= "<A33>" . "     " . "</A33>";
									$xml .= "<A34>" . "</A34>";
									$xml .= "<A35>" . "</A35>";
									$xml .= "<A36>" . "   " . "</A36>";
									$xml .= "<A37>" . $row_sub['unitsCompleted'] . "</A37>";
									$xml .= "<A38>" . $row_sub['units'] . "</A38>";
									$xml .= "<A39>" . "0" . "</A39>";
									$xml .= "<A40>" . $row_sub['achievement_date'] . "</A40>";
									$xml .= "<A43>" . $row['closure_date'] . "</A43>";
									$xml .= "<A44>" . "</A44>";
									$xml .= "<A45>" . "</A45>";
									$xml .= "<A46a>" . "</A46a>";
									$xml .= "<A46b>" . "</A46b>";
									$xml .= "<A47a>" . "</A47a>";
									$xml .= "<A47b>" . "</A47b>";
									$xml .= "<A48a>" . "</A48a>";
									$xml .= "<A48b>" . "</A48b>";
									$xml .= "<A49>" . "     " . "</A49>";
									$xml .= "<A50>" . "</A50>";
									$xml .= "<A51a>" ."</A51a>";
									$xml .= "<A52>" . "00000" . "</A52>";
									$xml .= "<A53>" . "</A53>";
									$xml .= "<A54>" . "</A54>";
									$xml .= "<A55>" . "</A55>";
									$xml .= "<A56>" . "</A56>";
									$xml .= "<A57>" . "00" . "</A57>";
									$xml .= "</subaim>";
								}
							}
							
							$xml .= "</ilr>";
							
							// getting contract type 
						
							$sql = "Select contract_type from contracts where id ='$contract'";
							$contract_type = DAO::getResultset($link, $sql);
							$contract_type = $contract_type[0][0];					
							
							// $xml = addslashes((string)$xml);
							$contract = addslashes((string)$contract);
							$contract_type=addslashes((string)$contract_type);
							$tr_id = $row['id'];
							$upin = $row['upin'];
							
							$sql = "insert into ilr (L01, ilr,submission,contract_type,tr_id,is_complete,is_valid,is_approved,is_active,contract_id) values('$upin',$xml','$newsubmission','$contract_type','$tr_id','0','0','0','1','$contract');";
							$st16 = $link->query($sql);
							if($st16== false)
								throw new Exception(implode($link->errorInfo()).'..........'.$sql, $link->errorCode());
										
							}
						}
					
					echo "<script language='javascript'> alert('ILRs for new submission have been created successfully'); </script>"; 
				}				
				else
				{
					throw new Exception("You cannot create new submission as previous does not exists");
				}

								
				echo "<script language='javascript'> window.history.go(-1); </script>";
			} 
		} */
	}
}	
?>