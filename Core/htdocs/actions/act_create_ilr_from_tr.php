<?php
class create_ilr_from_tr implements IAction
{
	public function execute(PDO $link)
	{
		
		$id = isset($_REQUEST['id'])?$_REQUEST['id']:'';
	
		$contract = (int)substr($id,3);
		$submission = substr($id,0,3);
 
		// Deleting Ilrs if their corresponding training records have been deleted
		$sql = "delete from ilr where tr_id NOT IN (select id from tr) and submission = '$submission'";
		DAO::execute($link, $sql);
		
		// check if new training recrods have been created and create ilrs for them.
		//$sql = "SELECT contract_holder.upin, uln, l03, l28a, l28b, l34a, l34b, l34c, l34d, l36, l37, l39, l40a, l40b, l41a, l41b, l47, tr.id, surname, firstnames, DATE_FORMAT(dob,'%d/%m/%Y') as date_of_birth, DATE_FORMAT(closure_date, '%d/%m/%Y') as closure_date, ethnicity, gender, learning_difficulties, disability,learning_difficulty, home_postcode, CONCAT(TRIM(home_saon_start_number), TRIM(home_saon_start_suffix), ' ', TRIM(home_saon_end_number), TRIM(home_saon_end_suffix), ' ' , TRIM(home_saon_description)) as L18, home_locality, home_town,home_county, current_postcode, home_telephone, country_of_domicile, ni, prior_attainment_level,DATE_FORMAT(start_date,'%d/%m/%Y') as start_date, DATE_FORMAT(target_date,'%d/%m/%Y') as target_date, status_code,provider_location_id, employer_id, provider_location.postcode as lpcode, organisations.legal_name as employer_name,employer_location.postcode as epcode FROM tr LEFT JOIN locations as provider_location ON provider_location.id = tr.provider_location_id LEFT JOIN locations as employer_location ON employer_location.id = tr.employer_location_id LEFT JOIN organisations ON organisations.id = tr.employer_id LEFT JOIN contracts on contracts.id = tr.contract_id LEFT JOIN organisations as contract_holder on contract_holder.id = contract_holder where contract_id = '$contract' and tr.id NOT IN (select tr_id from ilr where submission='$submission');";
		$sql = <<<SQL
SELECT
	contract_holder.upin,
	uln,
	l03,
	l28a,
	l28b,
	l34a,
	l34b,
	l34c,
	l34d,
	l36,
	l37,
	l39,
	l40a,
	l40b,
	l41a,
	l41b,
	l47,
	tr.id,
	surname,
	firstnames,
	DATE_FORMAT(dob,'%d/%m/%Y') AS date_of_birth,
	DATE_FORMAT(closure_date, '%d/%m/%Y') AS closure_date,
	ethnicity,
	gender,
	learning_difficulties,
	disability,
	learning_difficulty,
	home_postcode,
	CONCAT(TRIM(home_saon_start_number),
	TRIM(home_saon_start_suffix),
	' ',
	TRIM(home_saon_end_number),
	TRIM(home_saon_end_suffix),
	' ',
	home_address_line_1,
	home_address_line_2,
	home_address_line_3,
	home_address_line_4,
	home_postcode,
	home_telephone,
	country_of_domicile,
	ni,
	prior_attainment_level,
	DATE_FORMAT(start_date,'%d/%m/%Y') AS start_date,
	DATE_FORMAT(target_date,'%d/%m/%Y') AS target_date,
	status_code,
	provider_location_id,
	employer_id,
	provider_location.postcode AS lpcode,
	organisations.legal_name AS employer_name,
	employer_location.postcode AS epcode
FROM
	tr LEFT JOIN locations AS provider_location
		ON provider_location.id = tr.provider_location_id
	LEFT JOIN locations AS employer_location
		ON employer_location.id = tr.employer_location_id
	LEFT JOIN organisations
		ON organisations.id = tr.employer_id
	LEFT JOIN contracts
		ON contracts.id = tr.contract_id
	LEFT JOIN organisations AS contract_holder
		ON contract_holder.id = contract_holder
WHERE
	contract_id = '$contract'
	AND tr.id NOT IN (SELECT tr_id FROM ilr WHERE submission='$submission');
SQL;

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

				// No. of learning aims
				$sql ="select count(*) from student_qualifications where tr_id ={$row['id']}";
				$learning_aims = DAO::getResultset($link,$sql);
				
				$xml .= "<L05>" . $learning_aims[0][0] . "</L05>";
				$xml .= "<L06>" . "00" . "</L06>";
				$xml .= "<L07>" . "00" . "</L07>";
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
				$xml .= "<L18>" . $row['home_address_line_1'] .		"</L18>";
				$xml .= "<L19>" . $row['home_address_line_2'] . 	"</L19>";
				$xml .= "<L20>" . $row['home_address_line_3'] . 	"</L20>";
				$xml .= "<L21>" . $row['home_address_line_4'] . 	"</L21>";
				$xml .= "<L22>" . $row['home_postcode'] .		    "</L22>"; // Current postcode
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

				
				// Creating main aim					
				$sql_main = "select * from student_qualifications where tr_id = {$row['id']} and qualification_type='NVQ'";
				$st_main = $link->query($sql_main);				
				if($st_main)
				{
					while($row_main = $st_main->fetch())
					{	
						$xml .= "<main>";
						$xml .= "<A01>" . "</A01>";
						$xml .= "<A02>" . "</A02>";
						$xml .= "<A03>" . "</A03>";
						$xml .= "<A04>" . "30" . "</A04>";
						$xml .= "<A05>" . "01" . "</A05>";
						$xml .= "<A06>" . "</A06>";
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
				$st_sub = $link->query($sql_sub);	
				if($st_sub)
				{
					$learningaim = 2;
					while($row_sub = $st_sub->fetch())
					{	
						$xml .= "<subaim>";
						$xml .= "<A01>" . "</A01>";
						$xml .= "<A02>" . "</A02>";
						$xml .= "<A03>" . "</A03>";
						$xml .= "<A04>" . "30" . "</A04>";
						$xml .= "<A05>" . str_pad($learningaim,2,'0',STR_PAD_LEFT) . "</A05>";
						$learningaim++;
						$xml .= "<A06>" . "</A06>";
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
						$xml .= "<A44>                              </A44>";
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
				
				$sql = "insert into ilr (L01, ilr,submission,contract_type,tr_id,is_complete,is_valid,is_approved,is_active,contract_id) values('$upin',$xml','W01','$contract_type','$tr_id','0','0','0','1','$contract');";
				DAO::execute($link, $sql);
			}
		}
	echo "<script language='javascript'> window.history.go(-1); </script>";
	}
}	
?>