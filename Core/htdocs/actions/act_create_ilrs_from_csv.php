<?php
class create_ilrs_from_csv implements IAction
{
	public function execute(PDO $link)
	{
	
		$xml='';
		$pro='';
		$sub='';
		$main='';

		$sql = "SELECT * FROM tr LEFT JOIN courses_tr ON courses_tr.tr_id = tr.id WHERE courses_tr.course_id = 45;";
		$sss = $link->query($sql);
		while($row = $sss->fetch())		
		{	

			$xml='';
			$pro='';
			$sub='';
			$main='';
		
			$subaims = 0;
			$tr_id = $row['id'];
			$l03 = trim($row['l03']);	
			
			$handle = fopen("ilr.csv","r");
			$st = fgets($handle);
			$sub = '';
			while(!feof($handle))
			{
				$st = fgets($handle);
				$arr = explode(",",$st);

//				try{
				$newl03 = trim($arr[2]);
//				}
//				catch(Exception $e)
//				{
//					throw new Exception($arr[8]);
//				}
				$l01 = $arr[0];

				
				if($newl03=='END')
					break;				

				if($l03 == $newl03)
				{
					
					if($arr[65]=='10026782')
					{
						
						$xml = "<learner>";
						$xml .= "<L01>" . $arr[0] . "</L01>";
						$xml .= "<L02>" . $arr[1] . "</L02>";
						$xml .= "<L03>" . $arr[2] . "</L03>";
						$xml .= "<L04>" . $arr[3] . "</L04>";
						$xml .= "<L05>" . $arr[4] . "</L05>";
						$xml .= "<L06>" . $arr[5] . "</L06>";
						$xml .= "<L07>" . $arr[6] . "</L07>";
						$xml .= "<L08>" . $arr[7] . "</L08>";
						$xml .= "<L09>" . $arr[8] . "</L09>";
						$xml .= "<L10>" . $arr[9] . "</L10>";
			
						$start_date = $arr[10];
						
						$xml .= "<L11>" . $start_date . "</L11>";
						$xml .= "<L12>" . $arr[11] . "</L12>";
						$xml .= "<L13>" . $arr[12] . "</L13>";
						$xml .= "<L14>" . $arr[13] . "</L14>";
						$xml .= "<L15>" . $arr[14] . "</L15>";
						$xml .= "<L16>" . $arr[15] . "</L16>";
						$xml .= "<L17>" . $arr[16] . "</L17>";
						$xml .= "<L18>" . $arr[17] . "</L18>";
						$xml .= "<L19>" . $arr[18] . "</L19>";
						$xml .= "<L20>" . $arr[19] . "</L20>";
						$xml .= "<L21>" . $arr[20] . "</L21>";
						$xml .= "<L22>" . $arr[21] . "</L22>";
						$xml .= "<L23>" . str_replace(" ","",$arr[22]) . "</L23>";
						$xml .= "<L24>" . $arr[23] . "</L24>";
						$xml .= "<L25>" . $arr[24] . "</L25>";
						$xml .= "<L26>" . $arr[25] . "</L26>";
						$xml .= "<L27>" . $arr[26] . "</L27>";
						$xml .= "<L28a>" . $arr[27] . "</L28a>";
						$xml .= "<L28b>" . $arr[28] . "</L28b>";
						$xml .= "<L29>" . $arr[29] . "</L29>";
						$xml .= "<L31>" . $arr[30] . "</L31>";
						$xml .= "<L32>" . $arr[31] . "</L32>";
						$xml .= "<L33>" . $arr[32] . "</L33>";
						$xml .= "<L34a>" . $arr[33] . "</L34a>";
						$xml .= "<L34b>" . $arr[34] . "</L34b>";
						$xml .= "<L34c>" . $arr[35] . "</L34c>";
						$xml .= "<L34d>" . $arr[36] . "</L34d>";
						$xml .= "<L35>" . $arr[37] . "</L35>";
						$xml .= "<L36>" . $arr[38] . "</L36>";
						$xml .= "<L37>" . $arr[39] . "</L37>";
						$xml .= "<L38>" . $arr[40] . "</L38>";
						$xml .= "<L39>" . $arr[41] . "</L39>";
						$xml .= "<L40a>" . $arr[42] . "</L40a>";
						$xml .= "<L40b>" . $arr[43] . "</L40b>";
						$xml .= "<L41a>" . $arr[44] . "</L41a>";
						$xml .= "<L41b>" . $arr[45] . "</L41b>";
						$xml .= "<L42a>" . $arr[46] . "</L42a>";
						$xml .= "<L42b>" . $arr[47] . "</L42b>";
						$xml .= "<L44>" . $arr[48] . "</L44>";
						$xml .= "<L45>" . $arr[49] . "</L45>";
						$xml .= "<L46>" . $arr[50] . "</L46>";
						$xml .= "<L47>" . $arr[51] . "</L47>";
						$xml .= "<L48>" . $arr[52] . "</L48>";
						$xml .= "<L49a>" . $arr[53] . "</L49a>";
						$xml .= "<L49b>" . $arr[54] . "</L49b>";
						$xml .= "<L49c>" . $arr[55] . "</L49c>";
						$xml .= "<L49d>" . $arr[56] . "</L49d>";

						$pro = "<programmeaim>";			
						$pro .= "<A01>" . $arr[57] . "</A01>";
						$pro .= "<A02>" . $arr[58] . "</A02>";
						$pro .= "<A03>" . $arr[59] . "</A03>";
						$pro .= "<A04>" . "35" . "</A04>";
						$pro .= "<A05>" . "01" . "</A05>";
						$pro .= "<A06>" . "00" . "</A06>";
						$pro .= "<A07>" . "00" . "</A07>";
						$pro .= "<A08>" . "2" . "</A08>";
						$pro .= "<A09>" . "ZPROG001" . "</A09>";
						$pro .= "<A10>" . "45" . "</A10>";
						$pro .= "<A11a>" . "000" . "</A11a>";
						$pro .= "<A11b>" . "000" . "</A11b>";
						$pro .= "<A12a>" . "000" . "</A12a>";
						$pro .= "<A12b>" . "000" . "</A12b>";
						$pro .= "<A13>" . "00000" . "</A13>";
						$pro .= "<A14>" . $arr[72] . "</A14>";
						$pro .= "<A15>" . $arr[73] . "</A15>";
						$pro .= "<A16>" . $arr[74] . "</A16>";
						$pro .= "<A17>" . $arr[75] . "</A17>";
						$pro .= "<A18>" . $arr[76] . "</A18>";
						$pro .= "<A19>" . $arr[77] . "</A19>";
						$pro .= "<A20>" . $arr[78] . "</A20>";
						$pro .= "<A21>" . $arr[79] . "</A21>";
						$pro .= "<A22>" . $arr[80] . "</A22>";
						$pro .= "<A23>" . $arr[81] . "</A23>";
						$pro .= "<A24>" . $arr[82] . "</A24>";
						$pro .= "<A26>" . $arr[83] . "</A26>";
			
						$start_date = $arr[84];
						$pro .= "<A27>" . $start_date . "</A27>";
						
						$start_date = $arr[85];
						$pro .= "<A28>" . $start_date . "</A28>";
			
						$start_date = $arr[86];
						$pro .= "<A31>" . $start_date . "</A31>";
						$pro .= "<A32>" . $arr[87] . "</A32>";
						$pro .= "<A33>" . $arr[88] . "</A33>";
						$pro .= "<A34>" . $arr[89] . "</A34>";
						$pro .= "<A35>" . $arr[90] . "</A35>";
						$pro .= "<A36>" . $arr[91] . "</A36>";
						$pro .= "<A37>" . $arr[92] . "</A37>";
						$pro .= "<A38>" . $arr[93] . "</A38>";
						$pro .= "<A39>" . $arr[94] . "</A39>";
			
						$start_date = $arr[95];
						$pro .= "<A40>" . $start_date . "</A40>";
						$pro .= "<A43>" . $arr[96] . "</A43>";
						$pro .= "<A44>" . $arr[97] . "</A44>";
						$pro .= "<A45>" . $arr[98] . "</A45>";
						$pro .= "<A46a>" . $arr[99] . "</A46a>";
						$pro .= "<A46b>" . $arr[100] . "</A46b>";
						$pro .= "<A47a>" . $arr[101] . "</A47a>";
						$pro .= "<A47b>" . $arr[102] . "</A47b>";
						$pro .= "<A48a>" . $arr[103] . "</A48a>";
						$pro .= "<A48b>" . $arr[104] . "</A48b>";
						$pro .= "<A49>" . $arr[105] . "</A49>";
						$pro .= "<A50>" . $arr[106] . "</A50>";
						$pro .= "<A51a>" . $arr[107] . "</A51a>";
						$pro .= "<A52>" . $arr[108] . "</A52>";
						$pro .= "<A53>" . $arr[109] . "</A53>";
						$pro .= "<A54>" . $arr[110] . "</A54>";
						$pro .= "<A55>" . $arr[111] . "</A55>";
						$pro .= "<A56>" . $arr[112] . "</A56>";
						$pro .= "<A57>" . $arr[113] . "</A57>";
						$pro .= "<A58>" . $arr[114] . "</A58>";
						$pro .= "<A59>" . $arr[115] . "</A59>";
						$pro .= "<A60>" . $arr[116] . "</A60>";
						$pro .= "</programmeaim>";		
					
						$main = "<main>";
						$main .= "<A01>" . $arr[57] . "</A01>";
						$main .= "<A02>" . $arr[58] . "</A02>";
						$main .= "<A03>" . $arr[59] . "</A03>";
						$main .= "<A04>" . $arr[60] . "</A04>";
						$main .= "<A05>" . $arr[61] . "</A05>";
						$main .= "<A06>" . $arr[62] . "</A06>";
						$main .= "<A07>" . $arr[63] . "</A07>";
						$main .= "<A08>" . $arr[64] . "</A08>";
						$main .= "<A09>" . $arr[65] . "</A09>";
						$main .= "<A10>" . $arr[66] . "</A10>";
						$main .= "<A11a>" . $arr[67] . "</A11a>";
						$main .= "<A11b>" . $arr[68] . "</A11b>";
						$main .= "<A12a>" . $arr[69] . "</A12a>";
						$main .= "<A12b>" . $arr[70] . "</A12b>";
						$main .= "<A13>" . $arr[71] . "</A13>";
						$main .= "<A14>" . $arr[72] . "</A14>";
						$main .= "<A15>" . $arr[73] . "</A15>";
						$main .= "<A16>" . $arr[74] . "</A16>";
						$main .= "<A17>" . $arr[75] . "</A17>";
						$main .= "<A18>" . $arr[76] . "</A18>";
						$main .= "<A19>" . $arr[77] . "</A19>";
						$main .= "<A20>" . $arr[78] . "</A20>";
						$main .= "<A21>" . $arr[79] . "</A21>";
						$main .= "<A22>" . $arr[80] . "</A22>";
						$main .= "<A23>" . $arr[81] . "</A23>";
						$main .= "<A24>" . $arr[82] . "</A24>";
						$main .= "<A26>" . $arr[83] . "</A26>";
			
						$start_date = $arr[84];
						$main .= "<A27>" . $start_date . "</A27>";
						
						$start_date = $arr[85];
						$main .= "<A28>" . $start_date . "</A28>";
			
						$start_date = $arr[86];
						$main .= "<A31>" . $start_date . "</A31>";
						$main .= "<A32>" . $arr[87] . "</A32>";
						$main .= "<A33>" . $arr[88] . "</A33>";
						$main .= "<A34>" . $arr[89] . "</A34>";
						$main .= "<A35>" . $arr[90] . "</A35>";
						$main .= "<A36>" . $arr[91] . "</A36>";
						$main .= "<A37>" . $arr[92] . "</A37>";
						$main .= "<A38>" . $arr[93] . "</A38>";
						$main .= "<A39>" . $arr[94] . "</A39>";
			
						$start_date = $arr[95];
						$main .= "<A40>" . $start_date . "</A40>";
						$main .= "<A43>" . $arr[96] . "</A43>";
						$main .= "<A44>" . $arr[97] . "</A44>";

						$main .= "<A45>" . $arr[98] . "</A45>";
						//$main .= "<A45>" . "DE1 9TA" . "</A45>";
						
						$main .= "<A46a>" . $arr[99] . "</A46a>";
						$main .= "<A46b>" . $arr[100] . "</A46b>";
						$main .= "<A47a>" . $arr[101] . "</A47a>";
						$main .= "<A47b>" . $arr[102] . "</A47b>";
						$main .= "<A48a>" . $arr[103] . "</A48a>";
						$main .= "<A48b>" . $arr[104] . "</A48b>";
						$main .= "<A49>" . $arr[105] . "</A49>";
						$main .= "<A50>" . $arr[106] . "</A50>";
						//$main .= "<A51a>" . $arr[107] . "</A51a>";
						$main .= "<A51a>" . "00" . "</A51a>";
						
						$main .= "<A52>" . $arr[108] . "</A52>";
						$main .= "<A53>" . $arr[109] . "</A53>";
						$main .= "<A54>" . $arr[110] . "</A54>";
						$main .= "<A55>" . $arr[111] . "</A55>";
						$main .= "<A56>" . $arr[112] . "</A56>";
						$main .= "<A57>" . $arr[113] . "</A57>";
						$main .= "<A58>" . $arr[114] . "</A58>";
						$main .= "<A59>" . $arr[115] . "</A59>";
						$main .= "<A60>" . $arr[116] . "</A60>";
						$main .= "</main>";		
					}	
					if($arr[65]!='10026782')
					{	
						$subaims++;
						$sub .= "<subaim>";
						$sub .= "<A01>" . $arr[57] . "</A01>";
						$sub .= "<A02>" . $arr[58] . "</A02>";
						$sub .= "<A03>" . $arr[59] . "</A03>";
						$sub .= "<A04>" . $arr[60] . "</A04>";
						$sub .= "<A05>" . $arr[61] . "</A05>";
						$sub .= "<A06>" . $arr[62] . "</A06>";
						$sub .= "<A07>" . $arr[63] . "</A07>";
						$sub .= "<A08>" . $arr[64] . "</A08>";
						$sub .= "<A09>" . $arr[65] . "</A09>";
						$sub .= "<A10>" . $arr[66] . "</A10>";
						$sub .= "<A11a>" . $arr[67] . "</A11a>";
						$sub .= "<A11b>" . $arr[68] . "</A11b>";
						$sub .= "<A12a>" . $arr[69] . "</A12a>";
						$sub .= "<A12b>" . $arr[70] . "</A12b>";
						$sub .= "<A13>" . $arr[71] . "</A13>";
						$sub .= "<A14>" . $arr[72] . "</A14>";
						$sub .= "<A15>" . $arr[73] . "</A15>";
						$sub .= "<A16>" . $arr[74] . "</A16>";
						$sub .= "<A17>" . $arr[75] . "</A17>";
						$sub .= "<A18>" . $arr[76] . "</A18>";
						$sub .= "<A19>" . $arr[77] . "</A19>";
						$sub .= "<A20>" . $arr[78] . "</A20>";
						$sub .= "<A21>" . $arr[79] . "</A21>";
						$sub .= "<A22>" . $arr[80] . "</A22>";
						$sub .= "<A23>" . $arr[81] . "</A23>";
						$sub .= "<A24>" . $arr[82] . "</A24>";
						$sub .= "<A26>" . $arr[83] . "</A26>";
		
						$start_date = $arr[84];
				
						$sub .= "<A27>" . $start_date . "</A27>";
						
						$start_date = $arr[85];
						$sub .= "<A28>" . $start_date . "</A28>";
			
						$start_date = $arr[86];
						
						$sub .= "<A31>" . $start_date . "</A31>";
						$sub .= "<A32>" . $arr[87] . "</A32>";
						$sub .= "<A33>" . $arr[88] . "</A33>";
						$sub .= "<A34>" . $arr[89] . "</A34>";
						$sub .= "<A35>" . $arr[90] . "</A35>";
						$sub .= "<A36>" . $arr[91] . "</A36>";
						$sub .= "<A37>" . $arr[92] . "</A37>";
						$sub .= "<A38>" . $arr[93] . "</A38>";
						$sub .= "<A39>" . $arr[94] . "</A39>";
			
						$start_date = $arr[95];
									
						$sub .= "<A40>" . $start_date . "</A40>";
						$sub .= "<A43>" . $arr[96] . "</A43>";
						$sub .= "<A44>" . $arr[97] . "</A44>";
						$sub .= "<A45>" . $arr[98] . "</A45>";
						//$sub .= "<A45>" .  . "</A45>";
						
						$sub .= "<A46a>" . $arr[99] . "</A46a>";
						$sub .= "<A46b>" . $arr[100] . "</A46b>";
						$sub .= "<A47a>" . $arr[101] . "</A47a>";
						$sub .= "<A47b>" . $arr[102] . "</A47b>";
						$sub .= "<A48a>" . $arr[103] . "</A48a>";
						$sub .= "<A48b>" . $arr[104] . "</A48b>";
						$sub .= "<A49>" . $arr[105] . "</A49>";
						$sub .= "<A50>" . $arr[106] . "</A50>";
						$sub .= "<A51a>" . "0" . "</A51a>";
						$sub .= "<A52>" . $arr[108] . "</A52>";
						$sub .= "<A53>" . $arr[109] . "</A53>";
						$sub .= "<A54>" . $arr[110] . "</A54>";
						$sub .= "<A55>" . $arr[111] . "</A55>";
						$sub .= "<A56>" . $arr[112] . "</A56>";
						$sub .= "<A57>" . $arr[113] . "</A57>";
						$sub .= "<A58>" . $arr[114] . "</A58>";
						$sub .= "<A59>" . $arr[115] . "</A59>";
						$sub .= "<A60>" . $arr[116] . "</A60>";
						$sub .= "</subaim>";		
					
					}
				}
			}
			
			
				$xml = "<ilr>" . $xml . "<subaims>" . $subaims . "</subaims></learner><subaims>" . $subaims . "</subaims>" . $pro . $main . $sub . "</ilr>";

				
				//throw new Exception($xml);
				
				$xml = str_replace("&", "&amp;", $xml);
				$xml = str_replace("'", "&apos;", $xml);
		
				$exists = DAO::getSingleValue($link, "select count(*) from ilr where l03 = '$l03'");
				if(!$exists)
				{
					$sql = "insert into ilr (L01,L03, A09, ilr,submission,contract_type,tr_id,is_complete,is_valid,is_approved,is_active,contract_id) values('$l01','$l03','0','$xml','W13','ER','$tr_id','0','0','0','1',9);";
					DAO::execute($link, $sql);
				}
					
				fclose($handle);	
		}

	}
}
?>