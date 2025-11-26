<?php

class Ilr0708 extends Entity
{
	public function __construct()
	{
		
		
	}
	
	
	public static function loadFromDatabase(PDO $link, $submission, $contract_id, $tr_id)
	{
		if(is_null($submission) || is_null($contract_id) || is_null($tr_id))
		{
			return null;
		}
		
		$vo = new Ilr0708();
		$vo->learnerinformation = new LearnerInformation();
		$vo->aims[0] = new Aim();
		
		$sql = "SELECT * FROM ilr WHERE submission='$submission' and contract_id='$contract_id' and tr_id='$tr_id'";
		$st = $link->query($sql);	
		if($st)
		{
			if($row = $st->fetch())
			{
				$vo->id = $row['L03'];
				$vo->active=$row['is_active'];
				$vo->approve = $row['is_approved'];
				// $vo->submission_date = $row['submission_date'];
				//$ilr = new SimpleXMLElement($row['ilr']);
				$ilr = XML::loadSimpleXML($row['ilr']);
				
				foreach($ilr->learner as $learner)
				{
					$vo->learnerinformation->L01=$learner->L01;
					$vo->learnerinformation->L02=$learner->L02;
					$vo->learnerinformation->L03=$learner->L03;
					$vo->learnerinformation->L04=$learner->L04;
					$vo->learnerinformation->L05=$learner->L05;
					$vo->learnerinformation->L06=$learner->L06;
					$vo->learnerinformation->L07=$learner->L07;
					$vo->learnerinformation->L08=$learner->L08;
					$vo->learnerinformation->L09=$learner->L09;
					$vo->learnerinformation->L10=$learner->L10;
					$vo->learnerinformation->L11=$learner->L11;
					$vo->learnerinformation->L12=$learner->L12;
					$vo->learnerinformation->L13=$learner->L13;
					$vo->learnerinformation->L14=$learner->L14;
					$vo->learnerinformation->L15=$learner->L15;
					$vo->learnerinformation->L16=$learner->L16;
					$vo->learnerinformation->L17=$learner->L17;
					$vo->learnerinformation->L18=$learner->L18;
					$vo->learnerinformation->L19=$learner->L19;
					$vo->learnerinformation->L20=$learner->L20;
					$vo->learnerinformation->L21=$learner->L21;
					$vo->learnerinformation->L22=$learner->L22;
					$vo->learnerinformation->L23=$learner->L23;
					$vo->learnerinformation->L24=$learner->L24;
					$vo->learnerinformation->L25=$learner->L25;
					$vo->learnerinformation->L26=$learner->L26;
					$vo->learnerinformation->L27=$learner->L27;
					$vo->learnerinformation->L28a=$learner->L28a;
					$vo->learnerinformation->L28b=$learner->L28b;
					$vo->learnerinformation->L29=$learner->L29;
					$vo->learnerinformation->L31=$learner->L31;
					$vo->learnerinformation->L32=$learner->L32;
					$vo->learnerinformation->L33=$learner->L33;
					$vo->learnerinformation->L34a=$learner->L34a;
					$vo->learnerinformation->L34b=$learner->L34b;
					$vo->learnerinformation->L34c=$learner->L34c;
					$vo->learnerinformation->L34d=$learner->L34d;
					$vo->learnerinformation->L35=$learner->L35;
					$vo->learnerinformation->L36=$learner->L36;
					$vo->learnerinformation->L37=$learner->L37;
					$vo->learnerinformation->L38=$learner->L38;
					$vo->learnerinformation->L39=$learner->L39;
					$vo->learnerinformation->L40a=$learner->L40a;
					$vo->learnerinformation->L40b=$learner->L40b;
					$vo->learnerinformation->L41a=$learner->L41a;
					$vo->learnerinformation->L41b=$learner->L41b;
					$vo->learnerinformation->L42a=$learner->L42a;
					$vo->learnerinformation->L42b=$learner->L42b;
					$vo->learnerinformation->L44=$learner->L44;
					$vo->learnerinformation->L45=$learner->L45;
					$vo->learnerinformation->L46=$learner->L46;
					$vo->learnerinformation->L47=$learner->L47;
					$vo->learnerinformation->L48=$learner->L48;
						
					
				}

				foreach($ilr->main as $aim)
				{
					$vo->aims[0] = new Aim(); 
					$vo->aims[0]->A01=$aim->A01;
					$vo->aims[0]->A02=$aim->A02;
					$vo->aims[0]->A03=$aim->A03;
					$vo->aims[0]->A04=$aim->A04;
					$vo->aims[0]->A05=$aim->A05;
					$vo->aims[0]->A06=$aim->A06;
					$vo->aims[0]->A07=$aim->A07;
					$vo->aims[0]->A08=$aim->A08;
					$vo->aims[0]->A09=$aim->A09;
					$vo->aims[0]->A10=$aim->A10;
					$vo->aims[0]->A11a=$aim->A11a;
					$vo->aims[0]->A11b=$aim->A11b;
					$vo->aims[0]->A12a=$aim->A12a;
					$vo->aims[0]->A12b=$aim->A12b;
					$vo->aims[0]->A13=$aim->A13;
					$vo->aims[0]->A14=$aim->A14;
					$vo->aims[0]->A15=$aim->A15;
	 				$vo->aims[0]->A16=$aim->A16;
					$vo->aims[0]->A17=$aim->A17;
					$vo->aims[0]->A18=$aim->A18;
					$vo->aims[0]->A19=$aim->A19;
					$vo->aims[0]->A20=$aim->A20;
					$vo->aims[0]->A21=$aim->A21;
					$vo->aims[0]->A22=$aim->A22;
					$vo->aims[0]->A23=$aim->A23;
					$vo->aims[0]->A24=$aim->A24;
					$vo->aims[0]->A26=$aim->A26;
					$vo->aims[0]->A27=$aim->A27;
					$vo->aims[0]->A28=$aim->A28;
					$vo->aims[0]->A31=$aim->A31;
					$vo->aims[0]->A32=$aim->A32;
					$vo->aims[0]->A33=$aim->A33;
					$vo->aims[0]->A34=$aim->A34;
					$vo->aims[0]->A35=$aim->A35;
					$vo->aims[0]->A36=$aim->A36;
					$vo->aims[0]->A37=$aim->A37;
					$vo->aims[0]->A38=$aim->A38;
					$vo->aims[0]->A39=$aim->A39;
					$vo->aims[0]->A40=$aim->A40;
					$vo->aims[0]->A43=$aim->A43;
					$vo->aims[0]->A44=$aim->A44;
					$vo->aims[0]->A45=$aim->A45;
					$vo->aims[0]->A46a=$aim->A46a;
					$vo->aims[0]->A46b=$aim->A46b;
					$vo->aims[0]->A47a=$aim->A47a;
					$vo->aims[0]->A47b=$aim->A47b;
					$vo->aims[0]->A48a=$aim->A48a;
					$vo->aims[0]->A48b=$aim->A48b;
					$vo->aims[0]->A49=$aim->A49;
					$vo->aims[0]->A50=$aim->A50;
					$vo->aims[0]->A51a=$aim->A51a;
					$vo->aims[0]->A52=$aim->A52;
					$vo->aims[0]->A53=$aim->A53;
					$vo->aims[0]->A54=$aim->A54;
					$vo->aims[0]->A55=$aim->A55;
					$vo->aims[0]->A56=$aim->A56;
					$vo->aims[0]->A57=$aim->A57;
					$vo->aims[0]->E01 = $aim->E01;
					$vo->aims[0]->E02 = $aim->E02;
					$vo->aims[0]->E03 = $aim->E03;
					$vo->aims[0]->E04 = $aim->E04;
					$vo->aims[0]->E05 = $aim->E05;
					$vo->aims[0]->E06 = $aim->E06;
					$vo->aims[0]->E07 = $aim->E07;
					$vo->aims[0]->E08 = $aim->E08;
					$vo->aims[0]->E09 = $aim->E09;
					$vo->aims[0]->E10 = $aim->E10;
					$vo->aims[0]->E11 = $aim->E11;
					$vo->aims[0]->E12 = $aim->E12;
					$vo->aims[0]->E13 = $aim->E13;
					$vo->aims[0]->E14 = $aim->E14;
					$vo->aims[0]->E15 = $aim->E15;
					$vo->aims[0]->E16a = $aim->E16a;
					$vo->aims[0]->E16b = $aim->E16b;
					$vo->aims[0]->E16c = $aim->E16c;
					$vo->aims[0]->E16d = $aim->E16d;
					$vo->aims[0]->E16e = $aim->E16e;
					$vo->aims[0]->E17a = $aim->E17a;
					$vo->aims[0]->E17b = $aim->E17b;
					$vo->aims[0]->E17c = $aim->E17c;
					$vo->aims[0]->E17d = $aim->E17d;
					$vo->aims[0]->E17e = $aim->E17e;
					$vo->aims[0]->E18a = $aim->E18a;
					$vo->aims[0]->E18b = $aim->E18b;
					$vo->aims[0]->E18c = $aim->E18c;
					$vo->aims[0]->E18d = $aim->E18d;
					$vo->aims[0]->E19a = $aim->E19a;
					$vo->aims[0]->E19b = $aim->E19b;
					$vo->aims[0]->E19c = $aim->E19c;
					$vo->aims[0]->E19d = $aim->E19d;
					$vo->aims[0]->E19e = $aim->E19e;
					$vo->aims[0]->E20a = $aim->E20a;
					$vo->aims[0]->E20b = $aim->E20b;
					$vo->aims[0]->E20c = $aim->E20c;
					$vo->aims[0]->E21 = $aim->E21;
					$vo->aims[0]->E22 = $aim->E22;
					$vo->aims[0]->E23 = $aim->E23;
					$vo->aims[0]->E24 = $aim->E24;
					$vo->aims[0]->E25 = $aim->E25;
				}

				$sub = 1;
				foreach($ilr->subaim as $subaim)
				{
					$vo->aims[$sub]->A01 = $subaim->A01;
					$vo->aims[$sub]->A02 = $subaim->A02;
					$vo->aims[$sub]->A03 = $subaim->A03;
					$vo->aims[$sub]->A04 = $subaim->A04;
					$vo->aims[$sub]->A05 = $subaim->A05;
					$vo->aims[$sub]->A06 = $subaim->A06;
					$vo->aims[$sub]->A07 = $subaim->A07;
					$vo->aims[$sub]->A08 = $subaim->A08;
					$vo->aims[$sub]->A09 = $subaim->A09;
					$vo->aims[$sub]->A10 = $subaim->A10;
					$vo->aims[$sub]->A11a = $subaim->A11a;
					$vo->aims[$sub]->A11b = $subaim->A11b;
					$vo->aims[$sub]->A12a = $subaim->A12a;
					$vo->aims[$sub]->A12b = $subaim->A12b;
					$vo->aims[$sub]->A13 = $subaim->A13;
					$vo->aims[$sub]->A14 = $subaim->A14;
					$vo->aims[$sub]->A15 = $subaim->A15;
					$vo->aims[$sub]->A16 = $subaim->A16;
					$vo->aims[$sub]->A17 = $subaim->A17;
					$vo->aims[$sub]->A18 = $subaim->A18;
					$vo->aims[$sub]->A19 = $subaim->A19;
					$vo->aims[$sub]->A20 = $subaim->A20;
					$vo->aims[$sub]->A21 = $subaim->A21;
					$vo->aims[$sub]->A22 = $subaim->A22;
					$vo->aims[$sub]->A23 = $subaim->A23;
					$vo->aims[$sub]->A24 = $subaim->A24;
					$vo->aims[$sub]->A26 = $subaim->A26;
					$vo->aims[$sub]->A27 = $subaim->A27;
					$vo->aims[$sub]->A28 = $subaim->A28;
					$vo->aims[$sub]->A31 = $subaim->A31;
					$vo->aims[$sub]->A32 = $subaim->A32;
					$vo->aims[$sub]->A33 = $subaim->A33;
					$vo->aims[$sub]->A34 = $subaim->A34;
					$vo->aims[$sub]->A35 = $subaim->A35;
					$vo->aims[$sub]->A36 = $subaim->A36;
					$vo->aims[$sub]->A37 = $subaim->A37;
					$vo->aims[$sub]->A38 = $subaim->A38;
					$vo->aims[$sub]->A39 = $subaim->A39;
					$vo->aims[$sub]->A40 = $subaim->A40;
					$vo->aims[$sub]->A43 = $subaim->A43;
					$vo->aims[$sub]->A44 = $subaim->A44;
					$vo->aims[$sub]->A45 = $subaim->A45;
					$vo->aims[$sub]->A46a = $subaim->A46a;
					$vo->aims[$sub]->A46b = $subaim->A46b;
					$vo->aims[$sub]->A47a = $subaim->A47a;
					$vo->aims[$sub]->A47b = $subaim->A47b;
					$vo->aims[$sub]->A48a = $subaim->A48a;
					$vo->aims[$sub]->A48b = $subaim->A48b;
					$vo->aims[$sub]->A49 = $subaim->A49;
					$vo->aims[$sub]->A50 = $subaim->A50;
					$vo->aims[$sub]->A51a = $subaim->A51a;
					$vo->aims[$sub]->A52 = $subaim->A52;
					$vo->aims[$sub]->A53 = $subaim->A53;
					$vo->aims[$sub]->A54 = $subaim->A54;
					$vo->aims[$sub]->A55 = $subaim->A55;
					$vo->aims[$sub]->A56 = $subaim->A56;
					$vo->aims[$sub]->A57 = $subaim->A57;
					$vo->aims[$sub]->E01 = $subaim->E01;
					$vo->aims[$sub]->E02 = $subaim->E02;
					$vo->aims[$sub]->E03 = $subaim->E03;
					$vo->aims[$sub]->E04 = $subaim->E04;
					$vo->aims[$sub]->E05 = $subaim->E05;
					$vo->aims[$sub]->E06 = $subaim->E06;
					$vo->aims[$sub]->E07 = $subaim->E07;
					$vo->aims[$sub]->E08 = $subaim->E08;
					$vo->aims[$sub]->E09 = $subaim->E09;
					$vo->aims[$sub]->E10 = $subaim->E10;
					$vo->aims[$sub]->E11 = $subaim->E11;
					$vo->aims[$sub]->E12 = $subaim->E12;
					$vo->aims[$sub]->E13 = $subaim->E13;
					$vo->aims[$sub]->E14 = $subaim->E14;
					$vo->aims[$sub]->E15 = $subaim->E15;
					$vo->aims[$sub]->E16a = $subaim->E16a;
					$vo->aims[$sub]->E16b = $subaim->E16b;
					$vo->aims[$sub]->E16c = $subaim->E16c;
					$vo->aims[$sub]->E16d = $subaim->E16d;
					$vo->aims[$sub]->E16e = $subaim->E16e;
					$vo->aims[$sub]->E17a = $subaim->E17a;
					$vo->aims[$sub]->E17b = $subaim->E17b;
					$vo->aims[$sub]->E17c = $subaim->E17c;
					$vo->aims[$sub]->E17d = $subaim->E17d;
					$vo->aims[$sub]->E17e = $subaim->E17e;
					$vo->aims[$sub]->E18a = $subaim->E18a;
					$vo->aims[$sub]->E18b = $subaim->E18b;
					$vo->aims[$sub]->E18c = $subaim->E18c;
					$vo->aims[$sub]->E18d = $subaim->E18d;
					$vo->aims[$sub]->E19a = $subaim->E19a;
					$vo->aims[$sub]->E19b = $subaim->E19b;
					$vo->aims[$sub]->E19c = $subaim->E19c;
					$vo->aims[$sub]->E19d = $subaim->E19d;
					$vo->aims[$sub]->E19e = $subaim->E19e;
					$vo->aims[$sub]->E20a = $subaim->E20a;
					$vo->aims[$sub]->E20b = $subaim->E20b;
					$vo->aims[$sub]->E20c = $subaim->E20c;
					$vo->aims[$sub]->E21 = $subaim->E21;
					$vo->aims[$sub]->E22 = $subaim->E22;
					$vo->aims[$sub]->E23 = $subaim->E23;
					$vo->aims[$sub]->E24 = $subaim->E24;
					$vo->aims[$sub]->E25 = $subaim->E25;
										
					
					$sub = $sub + 1;
				}
				$vo->subaims=($sub-1);
				
				
			}
		}
	return $vo;
	}		
			
	
	public static function loadFromXML($xml)
	{
		$vo = new Ilr0708();
		$vo->learnerinformation = new LearnerInformation();
		$vo->aims[0] = new Aim();
		
		
				//$ilr = new SimpleXMLElement($xml);
				$ilr = XML::loadSimpleXML($xml);
				
				foreach($ilr->learner as $learner)
				{
					$vo->learnerinformation->L01=$learner->L01;
					$vo->learnerinformation->L02=$learner->L02;
					$vo->learnerinformation->L03=$learner->L03;
					$vo->learnerinformation->L04=$learner->L04;
					$vo->learnerinformation->L05=$learner->L05;
					$vo->learnerinformation->L06=$learner->L06;
					$vo->learnerinformation->L07=$learner->L07;
					$vo->learnerinformation->L08=$learner->L08;
					$vo->learnerinformation->L09=$learner->L09;
					$vo->learnerinformation->L10=$learner->L10;
					$vo->learnerinformation->L11=$learner->L11;
					$vo->learnerinformation->L12=$learner->L12;
					$vo->learnerinformation->L13=$learner->L13;
					$vo->learnerinformation->L14=$learner->L14;
					$vo->learnerinformation->L15=$learner->L15;
					$vo->learnerinformation->L16=$learner->L16;
					$vo->learnerinformation->L17=$learner->L17;
					$vo->learnerinformation->L18=$learner->L18;
					$vo->learnerinformation->L19=$learner->L19;
					$vo->learnerinformation->L20=$learner->L20;
					$vo->learnerinformation->L21=$learner->L21;
					$vo->learnerinformation->L22=$learner->L22;
					$vo->learnerinformation->L23=$learner->L23;
					$vo->learnerinformation->L24=$learner->L24;
					$vo->learnerinformation->L25=$learner->L25;
					$vo->learnerinformation->L26=$learner->L26;
					$vo->learnerinformation->L27=$learner->L27;
					$vo->learnerinformation->L28a=$learner->L28a;
					$vo->learnerinformation->L28b=$learner->L28b;
					$vo->learnerinformation->L29=$learner->L29;
					$vo->learnerinformation->L31=$learner->L31;
					$vo->learnerinformation->L32=$learner->L32;
					$vo->learnerinformation->L33=$learner->L33;
					$vo->learnerinformation->L34a=$learner->L34a;
					$vo->learnerinformation->L34b=$learner->L34b;
					$vo->learnerinformation->L34c=$learner->L34c;
					$vo->learnerinformation->L34d=$learner->L34d;
					$vo->learnerinformation->L35=$learner->L35;
					$vo->learnerinformation->L36=$learner->L36;
					$vo->learnerinformation->L37=$learner->L37;
					$vo->learnerinformation->L38=$learner->L38;
					$vo->learnerinformation->L39=$learner->L39;
					$vo->learnerinformation->L40a=$learner->L40a;
					$vo->learnerinformation->L40b=$learner->L40b;
					$vo->learnerinformation->L41a=$learner->L41a;
					$vo->learnerinformation->L41b=$learner->L41b;
					$vo->learnerinformation->L42a=$learner->L42a;
					$vo->learnerinformation->L42b=$learner->L42b;
					$vo->learnerinformation->L44=$learner->L44;
					$vo->learnerinformation->L45=$learner->L45;
					$vo->learnerinformation->L46=$learner->L46;
					$vo->learnerinformation->L47=$learner->L47;
					$vo->learnerinformation->L48=$learner->L48;
					$vo->learnerinformation->subaims=$learner->subaims;									
				}

				foreach($ilr->main as $aim)
				{
					$vo->aims[0] = new Aim(); 
					$vo->aims[0]->A01=$aim->A01;
					$vo->aims[0]->A02=$aim->A02;
					$vo->aims[0]->A03=$aim->A03;
					$vo->aims[0]->A04=$aim->A04;
					$vo->aims[0]->A05=$aim->A05;
					$vo->aims[0]->A06=$aim->A06;
					$vo->aims[0]->A07=$aim->A07;
					$vo->aims[0]->A08=$aim->A08;
					$vo->aims[0]->A09=$aim->A09;
					$vo->aims[0]->A10=$aim->A10;
					$vo->aims[0]->A11a=$aim->A11a;
					$vo->aims[0]->A11b=$aim->A11b;
					$vo->aims[0]->A12a=$aim->A12a;
					$vo->aims[0]->A12b=$aim->A12b;
					$vo->aims[0]->A13=$aim->A13;
					$vo->aims[0]->A14=$aim->A14;
					$vo->aims[0]->A15=$aim->A15;
					$vo->aims[0]->A16=$aim->A16;
					$vo->aims[0]->A17=$aim->A17;
					$vo->aims[0]->A18=$aim->A18;
					$vo->aims[0]->A19=$aim->A19;
					$vo->aims[0]->A20=$aim->A20;
					$vo->aims[0]->A21=$aim->A21;
					$vo->aims[0]->A22=$aim->A22;
					$vo->aims[0]->A23=$aim->A23;
					$vo->aims[0]->A24=$aim->A24;
					$vo->aims[0]->A26=$aim->A26;
					$vo->aims[0]->A27=$aim->A27;
					$vo->aims[0]->A28=$aim->A28;
					$vo->aims[0]->A31=$aim->A31;
					$vo->aims[0]->A32=$aim->A32;
					$vo->aims[0]->A33=$aim->A33;
					$vo->aims[0]->A34=$aim->A34;
					$vo->aims[0]->A35=$aim->A35;
					$vo->aims[0]->A36=$aim->A36;
					$vo->aims[0]->A37=$aim->A37;
					$vo->aims[0]->A38=$aim->A38;
					$vo->aims[0]->A39=$aim->A39;
					$vo->aims[0]->A40=$aim->A40;
					$vo->aims[0]->A43=$aim->A43;
					$vo->aims[0]->A44=$aim->A44;
					$vo->aims[0]->A45=$aim->A45;
					$vo->aims[0]->A46a=$aim->A46a;
					$vo->aims[0]->A46b=$aim->A46b;
					$vo->aims[0]->A47a=$aim->A47a;
					$vo->aims[0]->A47b=$aim->A47b;
					$vo->aims[0]->A48a=$aim->A48a;
					$vo->aims[0]->A48b=$aim->A48b;
					$vo->aims[0]->A49=$aim->A49;
					$vo->aims[0]->A50=$aim->A50;
					$vo->aims[0]->A51a=$aim->A51a;
					$vo->aims[0]->A52=$aim->A52;
					$vo->aims[0]->A53=$aim->A53;
					$vo->aims[0]->A54=$aim->A54;
					$vo->aims[0]->A55=$aim->A55;
					$vo->aims[0]->A56=$aim->A56;
					$vo->aims[0]->A57=$aim->A57;
					$vo->aims[0]->E01 = $aim->E01;
					$vo->aims[0]->E02 = $aim->E02;
					$vo->aims[0]->E03 = $aim->E03;
					$vo->aims[0]->E04 = $aim->E04;
					$vo->aims[0]->E05 = $aim->E05;
					$vo->aims[0]->E06 = $aim->E06;
					$vo->aims[0]->E07 = $aim->E07;
					$vo->aims[0]->E08 = $aim->E08;
					$vo->aims[0]->E09 = $aim->E09;
					$vo->aims[0]->E10 = $aim->E10;
					$vo->aims[0]->E11 = $aim->E11;
					$vo->aims[0]->E12 = $aim->E12;
					$vo->aims[0]->E13 = $aim->E13;
					$vo->aims[0]->E14 = $aim->E14;
					$vo->aims[0]->E15 = $aim->E15;
					$vo->aims[0]->E16a = $aim->E16a;
					$vo->aims[0]->E16b = $aim->E16b;
					$vo->aims[0]->E16c = $aim->E16c;
					$vo->aims[0]->E16d = $aim->E16d;
					$vo->aims[0]->E16e = $aim->E16e;
					$vo->aims[0]->E17a = $aim->E17a;
					$vo->aims[0]->E17b = $aim->E17b;
					$vo->aims[0]->E17c = $aim->E17c;
					$vo->aims[0]->E17d = $aim->E17d;
					$vo->aims[0]->E17e = $aim->E17e;
					$vo->aims[0]->E18a = $aim->E18a;
					$vo->aims[0]->E18b = $aim->E18b;
					$vo->aims[0]->E18c = $aim->E18c;
					$vo->aims[0]->E18d = $aim->E18d;
					$vo->aims[0]->E19a = $aim->E19a;
					$vo->aims[0]->E19b = $aim->E19b;
					$vo->aims[0]->E19c = $aim->E19c;
					$vo->aims[0]->E19d = $aim->E19d;
					$vo->aims[0]->E19e = $aim->E19e;
					$vo->aims[0]->E20a = $aim->E20a;
					$vo->aims[0]->E20b = $aim->E20b;
					$vo->aims[0]->E20c = $aim->E20c;
					$vo->aims[0]->E21 = $aim->E21;
					$vo->aims[0]->E22 = $aim->E22;
					$vo->aims[0]->E23 = $aim->E23;
					$vo->aims[0]->E24 = $aim->E24;
					$vo->aims[0]->E25 = $aim->E25;
				}

				$sub = 1;
				foreach($ilr->subaim as $subaim)
				{
					$vo->aims[$sub]->A01 = $subaim->A01;
					$vo->aims[$sub]->A02 = $subaim->A02;
					$vo->aims[$sub]->A03 = $subaim->A03;
					$vo->aims[$sub]->A04 = $subaim->A04;
					$vo->aims[$sub]->A05 = $subaim->A05;
					$vo->aims[$sub]->A06 = $subaim->A06;
					$vo->aims[$sub]->A07 = $subaim->A07;
					$vo->aims[$sub]->A08 = $subaim->A08;
					$vo->aims[$sub]->A09 = $subaim->A09;
					$vo->aims[$sub]->A10 = $subaim->A10;
					$vo->aims[$sub]->A11a = $subaim->A11a;
					$vo->aims[$sub]->A11b = $subaim->A11b;
					$vo->aims[$sub]->A12a = $subaim->A12a;
					$vo->aims[$sub]->A12b = $subaim->A12b;
					$vo->aims[$sub]->A13 = $subaim->A13;
					$vo->aims[$sub]->A14 = $subaim->A14;
					$vo->aims[$sub]->A15 = $subaim->A15;
					$vo->aims[$sub]->A16 = $subaim->A16;
					$vo->aims[$sub]->A17 = $subaim->A17;
					$vo->aims[$sub]->A18 = $subaim->A18;
					$vo->aims[$sub]->A19 = $subaim->A19;
					$vo->aims[$sub]->A20 = $subaim->A20;
					$vo->aims[$sub]->A21 = $subaim->A21;
					$vo->aims[$sub]->A22 = $subaim->A22;
					$vo->aims[$sub]->A23 = $subaim->A23;
					$vo->aims[$sub]->A24 = $subaim->A24;
					$vo->aims[$sub]->A26 = $subaim->A26;
					$vo->aims[$sub]->A27 = $subaim->A27;
					$vo->aims[$sub]->A28 = $subaim->A28;
					$vo->aims[$sub]->A31 = $subaim->A31;
					$vo->aims[$sub]->A32 = $subaim->A32;
					$vo->aims[$sub]->A33 = $subaim->A33;
					$vo->aims[$sub]->A34 = $subaim->A34;
					$vo->aims[$sub]->A35 = $subaim->A35;
					$vo->aims[$sub]->A36 = $subaim->A36;
					$vo->aims[$sub]->A37 = $subaim->A37;
					$vo->aims[$sub]->A38 = $subaim->A38;
					$vo->aims[$sub]->A39 = $subaim->A39;
					$vo->aims[$sub]->A40 = $subaim->A40;
					$vo->aims[$sub]->A43 = $subaim->A43;
					$vo->aims[$sub]->A44 = $subaim->A44;
					$vo->aims[$sub]->A45 = $subaim->A45;
					$vo->aims[$sub]->A46a = $subaim->A46a;
					$vo->aims[$sub]->A46b = $subaim->A46b;
					$vo->aims[$sub]->A47a = $subaim->A47a;
					$vo->aims[$sub]->A47b = $subaim->A47b;
					$vo->aims[$sub]->A48a = $subaim->A48a;
					$vo->aims[$sub]->A48b = $subaim->A48b;
					$vo->aims[$sub]->A49 = $subaim->A49;
					$vo->aims[$sub]->A50 = $subaim->A50;
					$vo->aims[$sub]->A51a = $subaim->A51a;
					$vo->aims[$sub]->A52 = $subaim->A52;
					$vo->aims[$sub]->A53 = $subaim->A53;
					$vo->aims[$sub]->A54 = $subaim->A54;
					$vo->aims[$sub]->A55 = $subaim->A55;
					$vo->aims[$sub]->A56 = $subaim->A56;
					$vo->aims[$sub]->A57 = $subaim->A57;
					$vo->aims[$sub]->E01 = $subaim->E01;
					$vo->aims[$sub]->E02 = $subaim->E02;
					$vo->aims[$sub]->E03 = $subaim->E03;
					$vo->aims[$sub]->E04 = $subaim->E04;
					$vo->aims[$sub]->E05 = $subaim->E05;
					$vo->aims[$sub]->E06 = $subaim->E06;
					$vo->aims[$sub]->E07 = $subaim->E07;
					$vo->aims[$sub]->E08 = $subaim->E08;
					$vo->aims[$sub]->E09 = $subaim->E09;
					$vo->aims[$sub]->E10 = $subaim->E10;
					$vo->aims[$sub]->E11 = $subaim->E11;
					$vo->aims[$sub]->E12 = $subaim->E12;
					$vo->aims[$sub]->E13 = $subaim->E13;
					$vo->aims[$sub]->E14 = $subaim->E14;
					$vo->aims[$sub]->E15 = $subaim->E15;
					$vo->aims[$sub]->E16a = $subaim->E16a;
					$vo->aims[$sub]->E16b = $subaim->E16b;
					$vo->aims[$sub]->E16c = $subaim->E16c;
					$vo->aims[$sub]->E16d = $subaim->E16d;
					$vo->aims[$sub]->E16e = $subaim->E16e;
					$vo->aims[$sub]->E17a = $subaim->E17a;
					$vo->aims[$sub]->E17b = $subaim->E17b;
					$vo->aims[$sub]->E17c = $subaim->E17c;
					$vo->aims[$sub]->E17d = $subaim->E17d;
					$vo->aims[$sub]->E17e = $subaim->E17e;
					$vo->aims[$sub]->E18a = $subaim->E18a;
					$vo->aims[$sub]->E18b = $subaim->E18b;
					$vo->aims[$sub]->E18c = $subaim->E18c;
					$vo->aims[$sub]->E18d = $subaim->E18d;
					$vo->aims[$sub]->E19a = $subaim->E19a;
					$vo->aims[$sub]->E19b = $subaim->E19b;
					$vo->aims[$sub]->E19c = $subaim->E19c;
					$vo->aims[$sub]->E19d = $subaim->E19d;
					$vo->aims[$sub]->E19e = $subaim->E19e;
					$vo->aims[$sub]->E20a = $subaim->E20a;
					$vo->aims[$sub]->E20b = $subaim->E20b;
					$vo->aims[$sub]->E20c = $subaim->E20c;
					$vo->aims[$sub]->E21 = $subaim->E21;
					$vo->aims[$sub]->E22 = $subaim->E22;
					$vo->aims[$sub]->E23 = $subaim->E23;
					$vo->aims[$sub]->E24 = $subaim->E24;
					$vo->aims[$sub]->E25 = $subaim->E25;
					$sub = $sub + 1;
				}
				$vo->subaims=($sub-1);

				return $vo;		

		
	}
		
	

	public static function generateStream2(PDO $link, $subm, $L01)
	{
		if(is_null($subm))
		{
			return null;
		}
		
		$vo = new Ilr0708();
		$vo->learnerinformation = new LearnerInformation();
		$vo->aims[0] = new Aim();
		
		$sql = "SELECT * FROM ilr WHERE concat(submission,contract_id)='$subm' and is_active=1;";
		$st = $link->query($sql);
		if($st)
		{
			$file='A';
			$file.=$L01;
			$file.='00';
			$file.='002070800101.';
			$file.= substr($subm,0,3);
			if(file_exists($file))
				unlink($file);
			$handle = fopen($file,'w');
			
			// writing header information in data stream file
			fwrite($handle,$L01);
			fwrite($handle,'00');
			fwrite($handle,'            ');
			fwrite($handle,'00');
			fwrite($handle,'002');
			fwrite($handle,'0708');
			fwrite($handle,substr($subm,0,3));
			fwrite($handle,'001');
			fwrite($handle,date("dmY"));
			fwrite($handle,'A');
			fwrite($handle,'2');
			fwrite($handle,str_pad("Perspective Limited",40));
			fwrite($handle,str_pad("Apprenticeship Manager",30));
			fwrite($handle,str_pad("1.0",20));
			fwrite($handle,str_pad(" ",258));
			fwrite($handle,"\r\n");
			$record=1;
			while($row = $st->fetch())
			{	
				$vo->id = $row['L03'];
				//$vo->submission_date = $row['submission_date'];
				//$ilr = new SimpleXMLElement($row['ilr']);
				$ilr = XML::loadSimpleXML($row['ilr']);
				
				foreach($ilr->learner as $learner)
				{
					fwrite($handle,$learner->L01);
					fwrite($handle,$learner->L02);
					fwrite($handle,str_pad($learner->L03,12));
					fwrite($handle,$learner->L04);
					fwrite($handle,$learner->L05);
					fwrite($handle,$learner->L06);
					fwrite($handle,$learner->L07);
					fwrite($handle,$learner->L08);
					fwrite($handle,str_pad($learner->L09,20));
					fwrite($handle,str_pad($learner->L10,40));
					if($learner->L11!='00000000')
						fwrite($handle,substr($learner->L11,0,2).substr($learner->L11,3,2).substr($learner->L11,6,4));
					else
						fwrite($handle,str_pad($learner->L11,8,'0',STR_PAD_LEFT));
					fwrite($handle,$learner->L12);
					fwrite($handle,$learner->L13);
					fwrite($handle,$learner->L14);
					fwrite($handle,$learner->L15);
					fwrite($handle,$learner->L16);
					fwrite($handle,str_pad($learner->L17,8));
					fwrite($handle,str_pad($learner->L18,30));
					fwrite($handle,str_pad($learner->L19,30));
					fwrite($handle,str_pad($learner->L20,30));
					fwrite($handle,str_pad($learner->L21,30));
					fwrite($handle,str_pad($learner->L22,8));
					fwrite($handle,str_pad($learner->L23,15));
					fwrite($handle,$learner->L24);
					fwrite($handle,$learner->L25);
					fwrite($handle,$learner->L26);
					fwrite($handle,$learner->L27);
					fwrite($handle,$learner->L28a);
					fwrite($handle,$learner->L28b);
					fwrite($handle,$learner->L29);
					fwrite($handle,str_pad($learner->L31,6,'0',STR_PAD_LEFT));
					fwrite($handle,$learner->L32);
					fwrite($handle,$learner->L33);
					fwrite($handle,$learner->L34a);
					fwrite($handle,$learner->L34b);
					fwrite($handle,$learner->L34c);
					fwrite($handle,$learner->L34d);
					fwrite($handle,$learner->L35);
					fwrite($handle,$learner->L36);
					fwrite($handle,$learner->L37);
					fwrite($handle,$learner->L38);
					fwrite($handle,$learner->L39);
					fwrite($handle,$learner->L40a);
					fwrite($handle,$learner->L40b);
					fwrite($handle,str_pad($learner->L41a,12,'0',STR_PAD_LEFT));
					fwrite($handle,str_pad($learner->L41b,12,'0',STR_PAD_LEFT));
					fwrite($handle,str_pad($learner->L42a,12));
					fwrite($handle,str_pad($learner->L42b,12));
					fwrite($handle,$learner->L44);
					fwrite($handle,str_pad($learner->L45,10,'0',STR_PAD_LEFT));
					fwrite($handle,$learner->L46);
					fwrite($handle,$learner->L47);
					if($learner->L48!='00000000')
						fwrite($handle,substr($learner->L48,0,2).substr($learner->L48,3,2).substr($learner->L48,6,4));
					else
						fwrite($handle,str_pad($learner->L48,8,'0',STR_PAD_LEFT));
					fwrite($handle,"\r\n");
					$record++;				
				}

				foreach($ilr->main as $aim)
				{
					fwrite($handle,$aim->A01);
					fwrite($handle,$aim->A02);
					fwrite($handle,str_pad($aim->A03,12));
					fwrite($handle,$aim->A04);
					fwrite($handle,str_pad($aim->A05,2,'0',STR_PAD_LEFT));
					fwrite($handle,$aim->A06);
					fwrite($handle,$aim->A07);
					fwrite($handle,$aim->A08);
					fwrite($handle,$aim->A09);
					fwrite($handle,$aim->A10);
					fwrite($handle,$aim->A11a);
					fwrite($handle,$aim->A11b);
					fwrite($handle,$aim->A12a);
					fwrite($handle,$aim->A12b);
					fwrite($handle,$aim->A13);
					fwrite($handle,$aim->A14);
					fwrite($handle,$aim->A15);
					fwrite($handle,$aim->A16);
					fwrite($handle,$aim->A17);
					fwrite($handle,str_pad($aim->A18,2,'0',STR_PAD_LEFT));
					fwrite($handle,$aim->A19);
					fwrite($handle,$aim->A20);
					fwrite($handle,$aim->A21);
					fwrite($handle,$aim->A22);
					fwrite($handle,str_pad($aim->A23,8));
					fwrite($handle,$aim->A24);
					fwrite($handle,$aim->A26);
					if($aim->A27!='00000000')
						fwrite($handle,substr($aim->A27,0,2).substr($aim->A27,3,2).substr($aim->A27,6,4));
					else
						fwrite($handle,str_pad($aim->A27,8,'0',STR_PAD_LEFT));
					if($aim->A28!='00000000')
						fwrite($handle,substr($aim->A28,0,2).substr($aim->A28,3,2).substr($aim->A28,6,4));
					else
						fwrite($handle,str_pad($aim->A28,8,'0',STR_PAD_LEFT));
					if($aim->A31!='00000000')
						fwrite($handle,substr($aim->A31,0,2).substr($aim->A31,3,2).substr($aim->A31,6,4));
					else
						fwrite($handle,str_pad($aim->A31,8,'0',STR_PAD_LEFT));
					fwrite($handle,str_pad($aim->A32,5,'0',STR_PAD_LEFT));
					fwrite($handle,str_pad($aim->A33,5));
					fwrite($handle,$aim->A34);
					fwrite($handle,$aim->A35);
					fwrite($handle,str_pad($aim->A36,3));
					fwrite($handle,str_pad($aim->A37,2,'0',STR_PAD_LEFT));
					fwrite($handle,str_pad($aim->A38,2,'0',STR_PAD_LEFT));
					fwrite($handle,$aim->A39);
					if($aim->A40!='00000000')
						fwrite($handle,substr($aim->A40,0,2).substr($aim->A40,3,2).substr($aim->A40,6,4));
					else
						fwrite($handle,str_pad($aim->A40,8,'0',STR_PAD_LEFT));
					if($aim->A43!='00000000')
						fwrite($handle,substr($aim->A43,0,2).substr($aim->A43,3,2).substr($aim->A43,6,4));
					else
						fwrite($handle,str_pad($aim->A43,8,'0',STR_PAD_LEFT));
					fwrite($handle,str_pad($aim->A44,30));
					fwrite($handle,str_pad($aim->A45,8));
					fwrite($handle,$aim->A46a);
					fwrite($handle,$aim->A46b);
					fwrite($handle,str_pad($aim->A47a,12,'0',STR_PAD_LEFT));
					fwrite($handle,str_pad($aim->A47b,12,'0',STR_PAD_LEFT));
					fwrite($handle,str_pad($aim->A48a,12,'0',STR_PAD_LEFT));
					fwrite($handle,str_pad($aim->A48b,12,'0',STR_PAD_LEFT));
					fwrite($handle,str_pad($aim->A49,5));
					fwrite($handle,$aim->A50);
					fwrite($handle,str_pad($aim->A51a,2,'0',STR_PAD_LEFT));
					fwrite($handle,$aim->A52);
					fwrite($handle,str_pad($aim->A53,2,'0',STR_PAD_LEFT));
					fwrite($handle,str_pad($aim->A54,10));
					fwrite($handle,str_pad($aim->A55,10,'0',STR_PAD_LEFT));
					fwrite($handle,$aim->A56);
					fwrite($handle,$aim->A57);
					fwrite($handle,str_pad(" ",107));
					fwrite($handle, "\r\n");
					$record++;
					if($aim->A06=='01')
					{
						fwrite($handle,$aim->E01);
						fwrite($handle,$aim->E02);
						fwrite($handle,$aim->E03);
						fwrite($handle,$aim->E04);
						fwrite($handle,$aim->E05);
						fwrite($handle,$aim->E06);
						fwrite($handle,$aim->E07);
						fwrite($handle,$aim->E08);
						fwrite($handle,$aim->E09);
						fwrite($handle,$aim->E10);
						fwrite($handle,$aim->E11);
						fwrite($handle,$aim->E12);
						fwrite($handle,$aim->E13);
						fwrite($handle,$aim->E14);
						fwrite($handle,$aim->E15);
						fwrite($handle,$aim->E16a);
						fwrite($handle,$aim->E16b);
						fwrite($handle,$aim->E16c);
						fwrite($handle,$aim->E16d);
						fwrite($handle,$aim->E16e);
						fwrite($handle,$aim->E17a);
						fwrite($handle,$aim->E17b);
						fwrite($handle,$aim->E17c);
						fwrite($handle,$aim->E17d);
						fwrite($handle,$aim->E17e);
						fwrite($handle,$aim->E18a);
						fwrite($handle,$aim->E18b);
						fwrite($handle,$aim->E18c);
						fwrite($handle,$aim->E18d);
						fwrite($handle,$aim->E19a);
						fwrite($handle,$aim->E19b);
						fwrite($handle,$aim->E19c);
						fwrite($handle,$aim->E19d);
						fwrite($handle,$aim->E19e);
						fwrite($handle,$aim->E20a);
						fwrite($handle,$aim->E20b);
						fwrite($handle,$aim->E20c);
						fwrite($handle,$aim->E21);
						fwrite($handle,$aim->E22);
						fwrite($handle,$aim->E23);
						fwrite($handle,$aim->E24);
						fwrite($handle,$aim->E25);
						fwrite($handle,'\r\n');
						$record++;
					}
				}

				$sub = 1;
				foreach($ilr->subaim as $aim)
				{
					fwrite($handle,$aim->A01);
					fwrite($handle,$aim->A02);
					fwrite($handle,str_pad($aim->A03,12));
					fwrite($handle,$aim->A04);
					fwrite($handle,str_pad($aim->A05,2,'0',STR_PAD_LEFT));
					fwrite($handle,$aim->A06);
					fwrite($handle,$aim->A07);
					fwrite($handle,$aim->A08);
					fwrite($handle,$aim->A09);
					fwrite($handle,$aim->A10);
					fwrite($handle,$aim->A11a);
					fwrite($handle,$aim->A11b);
					fwrite($handle,$aim->A12a);
					fwrite($handle,$aim->A12b);
					fwrite($handle,$aim->A13);
					fwrite($handle,$aim->A14);
					fwrite($handle,$aim->A15);
					fwrite($handle,$aim->A16);
					fwrite($handle,$aim->A17);
					fwrite($handle,str_pad($aim->A18,2,'0',STR_PAD_LEFT));
					fwrite($handle,$aim->A19);
					fwrite($handle,$aim->A20);
					fwrite($handle,$aim->A21);
					fwrite($handle,$aim->A22);
					fwrite($handle,str_pad($aim->A23,8));
					fwrite($handle,$aim->A24);
					fwrite($handle,$aim->A26);
					if($aim->A27!='00000000')
						fwrite($handle,substr($aim->A27,0,2).substr($aim->A27,3,2).substr($aim->A27,6,4));
					else
						fwrite($handle,str_pad($aim->A27,8,'0',STR_PAD_LEFT));
					if($aim->A28!='00000000')
						fwrite($handle,substr($aim->A28,0,2).substr($aim->A28,3,2).substr($aim->A28,6,4));
					else
						fwrite($handle,str_pad($aim->A28,8,'0',STR_PAD_LEFT));
					if($aim->A31!='00000000')
						fwrite($handle,substr($aim->A31,0,2).substr($aim->A31,3,2).substr($aim->A31,6,4));
					else
						fwrite($handle,str_pad($aim->A31,8,'0',STR_PAD_LEFT));
					fwrite($handle,str_pad($aim->A32,5,'0',STR_PAD_LEFT));
					fwrite($handle,str_pad($aim->A33,5));
					fwrite($handle,$aim->A34);
					fwrite($handle,$aim->A35);
					fwrite($handle,str_pad($aim->A36,3));
					fwrite($handle,str_pad($aim->A37,2,'0',STR_PAD_LEFT));
					fwrite($handle,str_pad($aim->A38,2,'0',STR_PAD_LEFT));
					fwrite($handle,$aim->A39);
					if($aim->A40!='00000000')
						fwrite($handle,substr($aim->A40,0,2).substr($aim->A40,3,2).substr($aim->A40,6,4));
					else
						fwrite($handle,str_pad($aim->A40,8,'0',STR_PAD_LEFT));
					if($aim->A43!='00000000')
						fwrite($handle,substr($aim->A43,0,2).substr($aim->A43,3,2).substr($aim->A43,6,4));
					else
						fwrite($handle,str_pad($aim->A43,8,'0',STR_PAD_LEFT));
					fwrite($handle,str_pad($aim->A44,30));
					fwrite($handle,str_pad($aim->A45,8));
					fwrite($handle,$aim->A46a);
					fwrite($handle,$aim->A46b);
					fwrite($handle,str_pad($aim->A47a,12,'0',STR_PAD_LEFT));
					fwrite($handle,str_pad($aim->A47b,12,'0',STR_PAD_LEFT));
					fwrite($handle,str_pad($aim->A48a,12,'0',STR_PAD_LEFT));
					fwrite($handle,str_pad($aim->A48b,12,'0',STR_PAD_LEFT));
					fwrite($handle,str_pad($aim->A49,5));
					fwrite($handle,$aim->A50);
					fwrite($handle,str_pad($aim->A51a,2,'0',STR_PAD_LEFT));
					fwrite($handle,$aim->A52);
					fwrite($handle,str_pad($aim->A53,2,'0',STR_PAD_LEFT));
					fwrite($handle,str_pad($aim->A54,10));
					fwrite($handle,str_pad($aim->A55,10,'0',STR_PAD_LEFT));
					fwrite($handle,$aim->A56);
					fwrite($handle,$aim->A57);
					fwrite($handle,str_pad(" ",107));
					fwrite($handle, "\r\n");
					$record++;
					if($aim->A06=='01')
					{
						fwrite($handle,$aim->E01);
						fwrite($handle,$aim->E02);
						fwrite($handle,$aim->E03);
						fwrite($handle,$aim->E04);
						fwrite($handle,$aim->E05);
						fwrite($handle,$aim->E06);
						fwrite($handle,$aim->E07);
						fwrite($handle,$aim->E08);
						fwrite($handle,$aim->E09);
						fwrite($handle,$aim->E10);
						fwrite($handle,$aim->E11);
						fwrite($handle,$aim->E12);
						fwrite($handle,$aim->E13);
						fwrite($handle,$aim->E14);
						fwrite($handle,$aim->E15);
						fwrite($handle,$aim->E16a);
						fwrite($handle,$aim->E16b);
						fwrite($handle,$aim->E16c);
						fwrite($handle,$aim->E16d);
						fwrite($handle,$aim->E16e);
						fwrite($handle,$aim->E17a);
						fwrite($handle,$aim->E17b);
						fwrite($handle,$aim->E17c);
						fwrite($handle,$aim->E17d);
						fwrite($handle,$aim->E17e);
						fwrite($handle,$aim->E18a);
						fwrite($handle,$aim->E18b);
						fwrite($handle,$aim->E18c);
						fwrite($handle,$aim->E18d);
						fwrite($handle,$aim->E19a);
						fwrite($handle,$aim->E19b);
						fwrite($handle,$aim->E19c);
						fwrite($handle,$aim->E19d);
						fwrite($handle,$aim->E19e);
						fwrite($handle,$aim->E20a);
						fwrite($handle,$aim->E20b);
						fwrite($handle,$aim->E20c);
						fwrite($handle,$aim->E21);
						fwrite($handle,$aim->E22);
						fwrite($handle,$aim->E23);
						fwrite($handle,$aim->E24);
						fwrite($handle,$aim->E25);
						fwrite($handle,'\r\n');
						$sub = $sub + 1;
						$record++;
					}
				}
					$sub = $sub + 1;
				}
				$vo->subaims=($sub-1);
				
				
			}
		// writing footer record 
		$record++;	
		fwrite($handle,$L01);
		fwrite($handle,'00');
		fwrite($handle,'ZZZZZZZZZZZZ');
		fwrite($handle,'99');
		fwrite($handle,'002');
		fwrite($handle,'0708');
		fwrite($handle,substr($subm,0,3));
		fwrite($handle,'001');
		fwrite($handle,date("dmY"));
		fwrite($handle,str_pad($record,7,'0',STR_PAD_LEFT));
		fwrite($handle,'00000000');
		fwrite($handle,str_pad(" ",335));
		fwrite($handle,"\r\n");
		
		fclose($handle);
		return $file;
	}
		
				
				
	public static function generateStream(PDO $link, $subm, $L01)
	{
		if(is_null($subm))
		{
			return null;
		}
		
		$vo = new Ilr0708();
		$vo->learnerinformation = new LearnerInformation();
		$vo->aims[0] = new Aim();
		
		$sql = "SELECT * FROM ilr WHERE concat(submission,contract_id)='$subm' and is_active=1;";

		// R06 record level validation starts
		$que = "select count(DISTINCT concat(L01,L03)) from ilr where concat(submission,contract_id)='$subm' and is_active=1;";
		$no_of_distinct_ilrs = trim(DAO::getSingleValue($link, $que));
		$que = "select count(concat(L01,L03)) from ilr where concat(submission,contract_id)='$subm' and is_active=1;";
		$no_of_total_ilrs = trim(DAO::getSingleValue($link, $que));
		if($no_of_distinct_ilrs<$no_of_total_ilrs)
			throw new Exception("R06: No two learners must have the same provider number and learner reference");
		// R06 record level validation ends

		$st = $link->query($sql);
		if($st)
		{
			$file='A';
			$file.=$L01;
			$file.='00';
			$file.='002070800101.';
			$file.= substr($subm,0,3);
			if(file_exists($file))
				unlink($file);
			$handle = fopen($file,'w');
			
			// writing header information in data stream file
			fwrite($handle,$L01);
			fwrite($handle,'00');
			fwrite($handle,'            ');
			fwrite($handle,'00');
			fwrite($handle,'002');
			fwrite($handle,'0708');
			fwrite($handle,substr($subm,0,3));
			fwrite($handle,'001');
			fwrite($handle,date("dmY"));
			fwrite($handle,'A');
			fwrite($handle,'2');
			fwrite($handle,str_pad("Perspective Limited",40));
			fwrite($handle,str_pad("Apprenticeship Manager",30));
			fwrite($handle,str_pad("1.0",20));
			fwrite($handle,str_pad(" ",258));
			fwrite($handle,"\r\n");
			$record=1;
			while($row = $st->fetch())
			{	
				$vo->id = $row['L03'];
				//$vo->submission_date = $row['submission_date'];
				//$ilr = new SimpleXMLElement($row['ilr']);
				$ilr = XML::loadSimpleXML($row['ilr']);
				
				foreach($ilr->learner as $learner)
				{
					fwrite($handle,$learner->L01);
					fwrite($handle,$learner->L02);
					fwrite($handle,str_pad($learner->L03,12));
					fwrite($handle,$learner->L04);
					fwrite($handle,$learner->L05);
					fwrite($handle,$learner->L06);
					fwrite($handle,$learner->L07);
					fwrite($handle,$learner->L08);
					fwrite($handle,str_pad($learner->L09,20));
					fwrite($handle,str_pad($learner->L10,40));
					if($learner->L11!='00000000')
						fwrite($handle,substr($learner->L11,0,2).substr($learner->L11,3,2).substr($learner->L11,6,4));
					else
						fwrite($handle,str_pad($learner->L11,8,'0',STR_PAD_LEFT));
					fwrite($handle,$learner->L12);
					fwrite($handle,$learner->L13);
					fwrite($handle,$learner->L14);
					fwrite($handle,$learner->L15);
					fwrite($handle,$learner->L16);
					fwrite($handle,str_pad($learner->L17,8));
					fwrite($handle,str_pad($learner->L18,30));
					fwrite($handle,str_pad($learner->L19,30));
					fwrite($handle,str_pad($learner->L20,30));
					fwrite($handle,str_pad($learner->L21,30));
					fwrite($handle,str_pad($learner->L22,8));
					fwrite($handle,str_pad($learner->L23,15));
					fwrite($handle,$learner->L24);
					fwrite($handle,$learner->L25);
					fwrite($handle,$learner->L26);
					fwrite($handle,$learner->L27);
					fwrite($handle,$learner->L28a);
					fwrite($handle,$learner->L28b);
					fwrite($handle,$learner->L29);
					fwrite($handle,str_pad($learner->L31,6,'0',STR_PAD_LEFT));
					fwrite($handle,$learner->L32);
					fwrite($handle,$learner->L33);
					fwrite($handle,$learner->L34a);
					fwrite($handle,$learner->L34b);
					fwrite($handle,$learner->L34c);
					fwrite($handle,$learner->L34d);
					fwrite($handle,$learner->L35);
					fwrite($handle,$learner->L36);
					fwrite($handle,$learner->L37);
					fwrite($handle,$learner->L38);
					fwrite($handle,$learner->L39);
					fwrite($handle,$learner->L40a);
					fwrite($handle,$learner->L40b);
					fwrite($handle,str_pad($learner->L41a,12,'0',STR_PAD_LEFT));
					fwrite($handle,str_pad($learner->L41b,12,'0',STR_PAD_LEFT));
					fwrite($handle,str_pad($learner->L42a,12));
					fwrite($handle,str_pad($learner->L42b,12));
					fwrite($handle,$learner->L44);
					fwrite($handle,str_pad($learner->L45,10,'0',STR_PAD_LEFT));
					fwrite($handle,$learner->L46);
					fwrite($handle,$learner->L47);
					if($learner->L48!='00000000')
						fwrite($handle,substr($learner->L48,0,2).substr($learner->L48,3,2).substr($learner->L48,6,4));
					else
						fwrite($handle,str_pad($learner->L48,8,'0',STR_PAD_LEFT));
					fwrite($handle,"\r\n");
					$record++;				
				}

				foreach($ilr->main as $aim)
				{
					fwrite($handle,$aim->A01);
					fwrite($handle,$aim->A02);
					fwrite($handle,str_pad($aim->A03,12));
					fwrite($handle,$aim->A04);
					fwrite($handle,str_pad($aim->A05,2,'0',STR_PAD_LEFT));
					fwrite($handle,$aim->A06);
					fwrite($handle,$aim->A07);
					fwrite($handle,$aim->A08);
					fwrite($handle,$aim->A09);
					fwrite($handle,$aim->A10);
					fwrite($handle,$aim->A11a);
					fwrite($handle,$aim->A11b);
					fwrite($handle,$aim->A12a);
					fwrite($handle,$aim->A12b);
					fwrite($handle,$aim->A13);
					fwrite($handle,$aim->A14);
					fwrite($handle,$aim->A15);
					fwrite($handle,$aim->A16);
					fwrite($handle,$aim->A17);
					fwrite($handle,str_pad($aim->A18,2,'0',STR_PAD_LEFT));
					fwrite($handle,$aim->A19);
					fwrite($handle,$aim->A20);
					fwrite($handle,$aim->A21);
					fwrite($handle,$aim->A22);
					fwrite($handle,str_pad($aim->A23,8));
					fwrite($handle,$aim->A24);
					fwrite($handle,$aim->A26);
					if($aim->A27!='00000000')
						fwrite($handle,substr($aim->A27,0,2).substr($aim->A27,3,2).substr($aim->A27,6,4));
					else
						fwrite($handle,str_pad($aim->A27,8,'0',STR_PAD_LEFT));
					if($aim->A28!='00000000')
						fwrite($handle,substr($aim->A28,0,2).substr($aim->A28,3,2).substr($aim->A28,6,4));
					else
						fwrite($handle,str_pad($aim->A28,8,'0',STR_PAD_LEFT));
					if($aim->A31!='00000000')
						fwrite($handle,substr($aim->A31,0,2).substr($aim->A31,3,2).substr($aim->A31,6,4));
					else
						fwrite($handle,str_pad($aim->A31,8,'0',STR_PAD_LEFT));
					fwrite($handle,str_pad($aim->A32,5,'0',STR_PAD_LEFT));
					fwrite($handle,str_pad($aim->A33,5));
					fwrite($handle,$aim->A34);
					fwrite($handle,$aim->A35);
					fwrite($handle,str_pad($aim->A36,3));
					fwrite($handle,str_pad($aim->A37,2,'0',STR_PAD_LEFT));
					fwrite($handle,str_pad($aim->A38,2,'0',STR_PAD_LEFT));
					fwrite($handle,$aim->A39);
					if($aim->A40!='00000000')
						fwrite($handle,substr($aim->A40,0,2).substr($aim->A40,3,2).substr($aim->A40,6,4));
					else
						fwrite($handle,str_pad($aim->A40,8,'0',STR_PAD_LEFT));
					if($aim->A43!='00000000')
						fwrite($handle,substr($aim->A43,0,2).substr($aim->A43,3,2).substr($aim->A43,6,4));
					else
						fwrite($handle,str_pad($aim->A43,8,'0',STR_PAD_LEFT));
					fwrite($handle,str_pad($aim->A44,30));
					fwrite($handle,str_pad($aim->A45,8));
					fwrite($handle,$aim->A46a);
					fwrite($handle,$aim->A46b);
					fwrite($handle,str_pad($aim->A47a,12,'0',STR_PAD_LEFT));
					fwrite($handle,str_pad($aim->A47b,12,'0',STR_PAD_LEFT));
					fwrite($handle,str_pad($aim->A48a,12,'0',STR_PAD_LEFT));
					fwrite($handle,str_pad($aim->A48b,12,'0',STR_PAD_LEFT));
					fwrite($handle,str_pad($aim->A49,5));
					fwrite($handle,$aim->A50);
					fwrite($handle,str_pad($aim->A51a,2,'0',STR_PAD_LEFT));
					fwrite($handle,$aim->A52);
					fwrite($handle,str_pad($aim->A53,2,'0',STR_PAD_LEFT));
					fwrite($handle,str_pad($aim->A54,10));
					fwrite($handle,str_pad($aim->A55,10,'0',STR_PAD_LEFT));
					fwrite($handle,$aim->A56);
					fwrite($handle,$aim->A57);
					fwrite($handle,str_pad(" ",107));
					fwrite($handle, "\r\n");
					$record++;
					if($aim->A06=='01')
					{
						fwrite($handle,$aim->E01);
						fwrite($handle,$aim->E02);
						fwrite($handle,$aim->E03);
						fwrite($handle,$aim->E04);
						fwrite($handle,$aim->E05);
						fwrite($handle,$aim->E06);
						fwrite($handle,$aim->E07);
						fwrite($handle,$aim->E08);
						fwrite($handle,$aim->E09);
						fwrite($handle,$aim->E10);
						fwrite($handle,$aim->E11);
						fwrite($handle,$aim->E12);
						fwrite($handle,$aim->E13);
						fwrite($handle,$aim->E14);
						fwrite($handle,$aim->E15);
						fwrite($handle,$aim->E16a);
						fwrite($handle,$aim->E16b);
						fwrite($handle,$aim->E16c);
						fwrite($handle,$aim->E16d);
						fwrite($handle,$aim->E16e);
						fwrite($handle,$aim->E17a);
						fwrite($handle,$aim->E17b);
						fwrite($handle,$aim->E17c);
						fwrite($handle,$aim->E17d);
						fwrite($handle,$aim->E17e);
						fwrite($handle,$aim->E18a);
						fwrite($handle,$aim->E18b);
						fwrite($handle,$aim->E18c);
						fwrite($handle,$aim->E18d);
						fwrite($handle,$aim->E19a);
						fwrite($handle,$aim->E19b);
						fwrite($handle,$aim->E19c);
						fwrite($handle,$aim->E19d);
						fwrite($handle,$aim->E19e);
						fwrite($handle,$aim->E20a);
						fwrite($handle,$aim->E20b);
						fwrite($handle,$aim->E20c);
						fwrite($handle,$aim->E21);
						fwrite($handle,$aim->E22);
						fwrite($handle,$aim->E23);
						fwrite($handle,$aim->E24);
						fwrite($handle,$aim->E25);
						fwrite($handle,'\r\n');
						$record++;
					}
				}

				$sub = 1;
				foreach($ilr->subaim as $aim)
				{
					fwrite($handle,$aim->A01);
					fwrite($handle,$aim->A02);
					fwrite($handle,str_pad($aim->A03,12));
					fwrite($handle,$aim->A04);
					fwrite($handle,str_pad($aim->A05,2,'0',STR_PAD_LEFT));
					fwrite($handle,$aim->A06);
					fwrite($handle,$aim->A07);
					fwrite($handle,$aim->A08);
					fwrite($handle,$aim->A09);
					fwrite($handle,$aim->A10);
					fwrite($handle,$aim->A11a);
					fwrite($handle,$aim->A11b);
					fwrite($handle,$aim->A12a);
					fwrite($handle,$aim->A12b);
					fwrite($handle,$aim->A13);
					fwrite($handle,$aim->A14);
					fwrite($handle,$aim->A15);
					fwrite($handle,$aim->A16);
					fwrite($handle,$aim->A17);
					fwrite($handle,str_pad($aim->A18,2,'0',STR_PAD_LEFT));
					fwrite($handle,$aim->A19);
					fwrite($handle,$aim->A20);
					fwrite($handle,$aim->A21);
					fwrite($handle,$aim->A22);
					fwrite($handle,str_pad($aim->A23,8));
					fwrite($handle,$aim->A24);
					fwrite($handle,$aim->A26);
					if($aim->A27!='00000000')
						fwrite($handle,substr($aim->A27,0,2).substr($aim->A27,3,2).substr($aim->A27,6,4));
					else
						fwrite($handle,str_pad($aim->A27,8,'0',STR_PAD_LEFT));
					if($aim->A28!='00000000')
						fwrite($handle,substr($aim->A28,0,2).substr($aim->A28,3,2).substr($aim->A28,6,4));
					else
						fwrite($handle,str_pad($aim->A28,8,'0',STR_PAD_LEFT));
					if($aim->A31!='00000000')
						fwrite($handle,substr($aim->A31,0,2).substr($aim->A31,3,2).substr($aim->A31,6,4));
					else
						fwrite($handle,str_pad($aim->A31,8,'0',STR_PAD_LEFT));
					fwrite($handle,str_pad($aim->A32,5,'0',STR_PAD_LEFT));
					fwrite($handle,str_pad($aim->A33,5));
					fwrite($handle,$aim->A34);
					fwrite($handle,$aim->A35);
					fwrite($handle,str_pad($aim->A36,3));
					fwrite($handle,str_pad($aim->A37,2,'0',STR_PAD_LEFT));
					fwrite($handle,str_pad($aim->A38,2,'0',STR_PAD_LEFT));
					fwrite($handle,$aim->A39);
					if($aim->A40!='00000000')
						fwrite($handle,substr($aim->A40,0,2).substr($aim->A40,3,2).substr($aim->A40,6,4));
					else
						fwrite($handle,str_pad($aim->A40,8,'0',STR_PAD_LEFT));
					if($aim->A43!='00000000')
						fwrite($handle,substr($aim->A43,0,2).substr($aim->A43,3,2).substr($aim->A43,6,4));
					else
						fwrite($handle,str_pad($aim->A43,8,'0',STR_PAD_LEFT));
					fwrite($handle,str_pad($aim->A44,30));
					fwrite($handle,str_pad($aim->A45,8));
					fwrite($handle,$aim->A46a);
					fwrite($handle,$aim->A46b);
					fwrite($handle,str_pad($aim->A47a,12,'0',STR_PAD_LEFT));
					fwrite($handle,str_pad($aim->A47b,12,'0',STR_PAD_LEFT));
					fwrite($handle,str_pad($aim->A48a,12,'0',STR_PAD_LEFT));
					fwrite($handle,str_pad($aim->A48b,12,'0',STR_PAD_LEFT));
					fwrite($handle,str_pad($aim->A49,5));
					fwrite($handle,$aim->A50);
					fwrite($handle,str_pad($aim->A51a,2,'0',STR_PAD_LEFT));
					fwrite($handle,$aim->A52);
					fwrite($handle,str_pad($aim->A53,2,'0',STR_PAD_LEFT));
					fwrite($handle,str_pad($aim->A54,10));
					fwrite($handle,str_pad($aim->A55,10,'0',STR_PAD_LEFT));
					fwrite($handle,$aim->A56);
					fwrite($handle,$aim->A57);
					fwrite($handle,str_pad(" ",107));
					fwrite($handle, "\r\n");
					$record++;
					if($aim->A06=='01')
					{
						fwrite($handle,$aim->E01);
						fwrite($handle,$aim->E02);
						fwrite($handle,$aim->E03);
						fwrite($handle,$aim->E04);
						fwrite($handle,$aim->E05);
						fwrite($handle,$aim->E06);
						fwrite($handle,$aim->E07);
						fwrite($handle,$aim->E08);
						fwrite($handle,$aim->E09);
						fwrite($handle,$aim->E10);
						fwrite($handle,$aim->E11);
						fwrite($handle,$aim->E12);
						fwrite($handle,$aim->E13);
						fwrite($handle,$aim->E14);
						fwrite($handle,$aim->E15);
						fwrite($handle,$aim->E16a);
						fwrite($handle,$aim->E16b);
						fwrite($handle,$aim->E16c);
						fwrite($handle,$aim->E16d);
						fwrite($handle,$aim->E16e);
						fwrite($handle,$aim->E17a);
						fwrite($handle,$aim->E17b);
						fwrite($handle,$aim->E17c);
						fwrite($handle,$aim->E17d);
						fwrite($handle,$aim->E17e);
						fwrite($handle,$aim->E18a);
						fwrite($handle,$aim->E18b);
						fwrite($handle,$aim->E18c);
						fwrite($handle,$aim->E18d);
						fwrite($handle,$aim->E19a);
						fwrite($handle,$aim->E19b);
						fwrite($handle,$aim->E19c);
						fwrite($handle,$aim->E19d);
						fwrite($handle,$aim->E19e);
						fwrite($handle,$aim->E20a);
						fwrite($handle,$aim->E20b);
						fwrite($handle,$aim->E20c);
						fwrite($handle,$aim->E21);
						fwrite($handle,$aim->E22);
						fwrite($handle,$aim->E23);
						fwrite($handle,$aim->E24);
						fwrite($handle,$aim->E25);
						fwrite($handle,'\r\n');
						$sub = $sub + 1;
						$record++;
					}
				}
					$sub = $sub + 1;
				}
				$vo->subaims=($sub-1);
				
				
			}
		// writing footer record 
		$record++;	
		fwrite($handle,$L01);
		fwrite($handle,'00');
		fwrite($handle,'ZZZZZZZZZZZZ');
		fwrite($handle,'99');
		fwrite($handle,'002');
		fwrite($handle,'0708');
		fwrite($handle,substr($subm,0,3));
		fwrite($handle,'001');
		fwrite($handle,date("dmY"));
		fwrite($handle,str_pad($record,7,'0',STR_PAD_LEFT));
		fwrite($handle,'00000000');
		fwrite($handle,str_pad(" ",335));
		fwrite($handle,"\r\n");
		
		fclose($handle);
		return $file;
	}
	
	
	public static function generateStream3(PDO $link, $subm, $L01)
	{
		if(is_null($subm))
		{
			return null;
		}
		
		$vo = new Ilr0708();
		$vo->learnerinformation = new LearnerInformation();
		$vo->aims[0] = new Aim();
		
		$sql = "SELECT * FROM ilr WHERE concat(submission,contract_id)='$subm' and is_active=1;";

		// R06 record level validation starts
		$que = "select count(DISTINCT concat(L01,L03)) from ilr where concat(submission,contract_id)='$subm' and is_active=1;";
		$no_of_distinct_ilrs = trim(DAO::getSingleValue($link, $que));
		$que = "select count(concat(L01,L03)) from ilr where concat(submission,contract_id)='$subm' and is_active=1;";
		$no_of_total_ilrs = trim(DAO::getSingleValue($link, $que));
		if($no_of_distinct_ilrs<$no_of_total_ilrs)
			throw new Exception("R06: No two learners must have the same provider number and learner reference");
		// R06 record level validation ends
		$st = $link->query($sql);	
		if($st)
		{
			
			// writing header information in data stream file
			print($L01);
			print('00');
			print('            ');
			print('00');
			print('002');
			print('0708');
			print(substr($subm,0,3));
			print('001');
			print(date("dmY"));
			print('A');
			print('2');
			print(str_pad("Perspective Limited",40));
			print(str_pad("Apprenticeship Manager",30));
			print(str_pad("1.0",20));
			print(str_pad(" ",258));
			print("\r\n");
			$record=1;
			while($row = $st->fetch())
			{	
				$vo->id = $row['L03'];
				//$vo->submission_date = $row['submission_date'];

				$ilr = $row['ilr']; 
				$ilr = str_replace("&", "a", $ilr);
				//$ilr = new SimpleXMLElement($ilr);
				$ilr = XML::loadSimpleXML($ilr);
				
				foreach($ilr->learner as $learner)
				{
					print($learner->L01);
					print($learner->L02);
					print(str_pad($learner->L03,12));
					print($learner->L04);
					print(str_pad($learner->L05,2,'0',STR_PAD_LEFT));
					print($learner->L06);
					print($learner->L07);
					print($learner->L08);
					print(str_pad($learner->L09,20));
					print(str_pad($learner->L10,40));
					if($learner->L11!='00000000')
						print(substr($learner->L11,0,2).substr($learner->L11,3,2).substr($learner->L11,6,4));
					else
						print(str_pad($learner->L11,8,'0',STR_PAD_LEFT));
					print($learner->L12);
					print($learner->L13);
					print($learner->L14);
					print($learner->L15);
					print($learner->L16);
					print(str_pad($learner->L17,8));
					print(str_pad($learner->L18,30));
					print(str_pad($learner->L19,30));
					print(str_pad($learner->L20,30));
					print(str_pad($learner->L21,30));
					print(str_pad($learner->L22,8));
					print(str_pad($learner->L23,15));
					print($learner->L24);
					print($learner->L25);
					print(str_pad($learner->L26,9,' ',STR_PAD_LEFT));
					print($learner->L27);
					print($learner->L28a);
					print($learner->L28b);
					print($learner->L29);
					print(str_pad($learner->L31,6,'0',STR_PAD_LEFT));
					print($learner->L32);
					print($learner->L33);
					print($learner->L34a);
					print($learner->L34b);
					print($learner->L34c);
					print($learner->L34d);
					print($learner->L35);
					print($learner->L36);
					print($learner->L37);
					print($learner->L38);
					print($learner->L39);
					print($learner->L40a);
					print($learner->L40b);
					print(str_pad($learner->L41a,12,'0',STR_PAD_LEFT));
					print(str_pad($learner->L41b,12,'0',STR_PAD_LEFT));
					print(str_pad($learner->L42a,12));
					print(str_pad($learner->L42b,12));
					print($learner->L44);
					print(str_pad($learner->L45,10,'0',STR_PAD_LEFT));
					print($learner->L46);
					print($learner->L47);
					if($learner->L48!='00000000')
						print(substr($learner->L48,0,2).substr($learner->L48,3,2).substr($learner->L48,6,4));
					else
						print(str_pad($learner->L48,8,'0',STR_PAD_LEFT));
					print("\r\n");
					$record++;				
				}

				foreach($ilr->main as $aim)
				{
					print($aim->A01);
					print($aim->A02);
					print(str_pad($aim->A03,12));
					print($aim->A04);
					print(str_pad($aim->A05,2,'0',STR_PAD_LEFT));
					print($aim->A06);
					print($aim->A07);
					print($aim->A08);
					print($aim->A09);
					print($aim->A10);
					print($aim->A11a);
					print($aim->A11b);
					print($aim->A12a);
					print($aim->A12b);
					print($aim->A13);
					print($aim->A14);
					print($aim->A15);
					print($aim->A16);
					print($aim->A17);
					print(str_pad($aim->A18,2,'0',STR_PAD_LEFT));
					print($aim->A19);
					print($aim->A20);
					print($aim->A21);
					//print($aim->A22); Not required for WBL (Only for FE)
					print("      ");
					print(str_pad($aim->A23,8));
					print($aim->A24);
					print($aim->A26);
					if($aim->A27!='00000000')
						print(substr($aim->A27,0,2).substr($aim->A27,3,2).substr($aim->A27,6,4));
					else
						print(str_pad($aim->A27,8,'0',STR_PAD_LEFT));
					if($aim->A28!='00000000')
						print(substr($aim->A28,0,2).substr($aim->A28,3,2).substr($aim->A28,6,4));
					else
						print(str_pad($aim->A28,8,'0',STR_PAD_LEFT));
					if($aim->A31!='00000000')
						print(substr($aim->A31,0,2).substr($aim->A31,3,2).substr($aim->A31,6,4));
					else
						print(str_pad($aim->A31,8,'0',STR_PAD_LEFT));
					print(str_pad($aim->A32,5,'0',STR_PAD_LEFT));
					print(str_pad($aim->A33,5));
					print($aim->A34);
					print($aim->A35);
					print(str_pad($aim->A36,3));
					print(str_pad($aim->A37,2,'0',STR_PAD_LEFT));
					print(str_pad($aim->A38,2,'0',STR_PAD_LEFT));
					print($aim->A39);
					if($aim->A40!='00000000')
						print(substr($aim->A40,0,2).substr($aim->A40,3,2).substr($aim->A40,6,4));
					else
						print(str_pad($aim->A40,8,'0',STR_PAD_LEFT));
					if($aim->A43!='00000000')
						print(substr($aim->A43,0,2).substr($aim->A43,3,2).substr($aim->A43,6,4));
					else
						print(str_pad($aim->A43,8,'0',STR_PAD_LEFT));
					print(str_pad($aim->A44,30));
					print(str_pad($aim->A45,8));
					print($aim->A46a);
					print($aim->A46b);
					print(str_pad($aim->A47a,12,'0',STR_PAD_LEFT));
					print(str_pad($aim->A47b,12,'0',STR_PAD_LEFT));
					print(str_pad($aim->A48a,12,' ',STR_PAD_RIGHT));
					print(str_pad($aim->A48b,12,' ',STR_PAD_RIGHT));
					print(str_pad($aim->A49,5));
					print($aim->A50);
					print(str_pad($aim->A51a,2,'0',STR_PAD_LEFT));
					print($aim->A52);
					print(str_pad($aim->A53,2,'0',STR_PAD_LEFT));
					print(str_pad($aim->A54,10));
					print(str_pad($aim->A55,10,'0',STR_PAD_LEFT));
					print($aim->A56);
					print($aim->A57);
					print(str_pad(" ",107));
					print( "\r\n");
					$record++;
					if($aim->A06=='01')
					{
						print($aim->E01);
						print($aim->E02);
						print($aim->E03);
						print($aim->E04);
						print($aim->E05);
						print($aim->E06);
						print($aim->E07);
						print($aim->E08);
						print($aim->E09);
						print($aim->E10);
						print($aim->E11);
						print($aim->E12);
						print($aim->E13);
						print($aim->E14);
						print($aim->E15);
						print($aim->E16a);
						print($aim->E16b);
						print($aim->E16c);
						print($aim->E16d);
						print($aim->E16e);
						print($aim->E17a);
						print($aim->E17b);
						print($aim->E17c);
						print($aim->E17d);
						print($aim->E17e);
						print($aim->E18a);
						print($aim->E18b);
						print($aim->E18c);
						print($aim->E18d);
						print($aim->E19a);
						print($aim->E19b);
						print($aim->E19c);
						print($aim->E19d);
						print($aim->E19e);
						print($aim->E20a);
						print($aim->E20b);
						print($aim->E20c);
						print($aim->E21);
						print($aim->E22);
						print($aim->E23);
						print($aim->E24);
						print($aim->E25);
						print('\r\n');
						$record++;
					}
				}

				$sub = 1;
				foreach($ilr->subaim as $aim)
				{
					print($aim->A01);
					print($aim->A02);
					print(str_pad($aim->A03,12));
					print($aim->A04);
					print(str_pad($aim->A05,2,'0',STR_PAD_LEFT));
					print($aim->A06);
					print($aim->A07);
					print($aim->A08);
					print($aim->A09);
					print($aim->A10);
					print($aim->A11a);
					print($aim->A11b);
					print($aim->A12a);
					print($aim->A12b);
					print($aim->A13);
					print($aim->A14);
					print($aim->A15);
					print($aim->A16);
					print($aim->A17);
					print(str_pad($aim->A18,2,'0',STR_PAD_LEFT));
					print($aim->A19);
					print($aim->A20);
					print($aim->A21);
					// print($aim->A22); Not required in WBL (Only in FE)
					print("      ");
					//print(str_pad($aim->A23,8)); Doesn't required in Subsidiary aims
					print("        ");					
					print($aim->A24);
					print($aim->A26);
					if($aim->A27!='00000000')
						print(substr($aim->A27,0,2).substr($aim->A27,3,2).substr($aim->A27,6,4));
					else
						print(str_pad($aim->A27,8,'0',STR_PAD_LEFT));
					if($aim->A28!='00000000')
						print(substr($aim->A28,0,2).substr($aim->A28,3,2).substr($aim->A28,6,4));
					else
						print(str_pad($aim->A28,8,'0',STR_PAD_LEFT));
					if($aim->A31!='00000000')
						print(substr($aim->A31,0,2).substr($aim->A31,3,2).substr($aim->A31,6,4));
					else
						print(str_pad($aim->A31,8,'0',STR_PAD_LEFT));
					print(str_pad($aim->A32,5,'0',STR_PAD_LEFT));
					print(str_pad($aim->A33,5));
					print($aim->A34);
					print($aim->A35);
					print(str_pad($aim->A36,3));
					print(str_pad($aim->A37,2,'0',STR_PAD_LEFT));
					print(str_pad($aim->A38,2,'0',STR_PAD_LEFT));
					print($aim->A39);
					if($aim->A40!='00000000')
						print(substr($aim->A40,0,2).substr($aim->A40,3,2).substr($aim->A40,6,4));
					else
						print(str_pad($aim->A40,8,'0',STR_PAD_LEFT));
					if($aim->A43!='00000000')
						print(substr($aim->A43,0,2).substr($aim->A43,3,2).substr($aim->A43,6,4));
					else
						print(str_pad($aim->A43,8,'0',STR_PAD_LEFT));
					print(str_pad($aim->A44,30));
					print(str_pad($aim->A45,8));
					print($aim->A46a);
					print($aim->A46b);
					print(str_pad($aim->A47a,12,'0',STR_PAD_LEFT));
					print(str_pad($aim->A47b,12,'0',STR_PAD_LEFT));
					print(str_pad($aim->A48a,12,' ',STR_PAD_RIGHT));
					print(str_pad($aim->A48b,12,' ',STR_PAD_RIGHT));
					print(str_pad($aim->A49,5));
					print($aim->A50);
					print(str_pad($aim->A51a,2,'0',STR_PAD_LEFT));
					print($aim->A52);
					print(str_pad($aim->A53,2,'0',STR_PAD_LEFT));
					print(str_pad($aim->A54,10));
					print(str_pad($aim->A55,10,'0',STR_PAD_LEFT));
					print($aim->A56);
					print($aim->A57);
					print(str_pad(" ",107));
					print( "\r\n");
					$record++;
					if($aim->A06=='01')
					{
						print($aim->E01);
						print($aim->E02);
						print($aim->E03);
						print($aim->E04);
						print($aim->E05);
						print($aim->E06);
						print($aim->E07);
						print($aim->E08);
						print($aim->E09);
						print($aim->E10);
						print($aim->E11);
						print($aim->E12);
						print($aim->E13);
						print($aim->E14);
						print($aim->E15);
						print($aim->E16a);
						print($aim->E16b);
						print($aim->E16c);
						print($aim->E16d);
						print($aim->E16e);
						print($aim->E17a);
						print($aim->E17b);
						print($aim->E17c);
						print($aim->E17d);
						print($aim->E17e);
						print($aim->E18a);
						print($aim->E18b);
						print($aim->E18c);
						print($aim->E18d);
						print($aim->E19a);
						print($aim->E19b);
						print($aim->E19c);
						print($aim->E19d);
						print($aim->E19e);
						print($aim->E20a);
						print($aim->E20b);
						print($aim->E20c);
						print($aim->E21);
						print($aim->E22);
						print($aim->E23);
						print($aim->E24);
						print($aim->E25);
						print('\r\n');
						$sub = $sub + 1;
						$record++;
					}
				}
					$sub = $sub + 1;
				}
				$vo->subaims=($sub-1);
				
				
			}
		// writing footer record 
		$record++;	
		print($L01);
		print('00');
		print('ZZZZZZZZZZZZ');
		print('99');
		print('002');
		print('0708');
		print(substr($subm,0,3));
		print('001');
		print(date("dmY"));
		print(str_pad($record,7,'0',STR_PAD_LEFT));
		print('00000000');
		print(str_pad(" ",335));
		print("\r\n");
		
		//fclose($handle);
		//return $file;
	}
	

	public static function getFilename(PDO $link, $subm, $L01)
	{
		if(is_null($subm))
		{
			return null;
		}
		
		$vo = new Ilr0708();
		$vo->learnerinformation = new LearnerInformation();
		$vo->aims[0] = new Aim();
		
		$sql = "SELECT * FROM ilr WHERE concat(submission,contract_id)='$subm' and is_active=1;";

		// R06 record level validation starts
		$que = "select count(DISTINCT concat(L01,L03)) from ilr where concat(submission,contract_id)='$subm' and is_active=1;";
		$no_of_distinct_ilrs = trim(DAO::getSingleValue($link, $que));
		$que = "select count(concat(L01,L03)) from ilr where concat(submission,contract_id)='$subm' and is_active=1;";
		$no_of_total_ilrs = trim(DAO::getSingleValue($link, $que));
		if($no_of_distinct_ilrs<$no_of_total_ilrs)
			throw new Exception("R06: No two learners must have the same provider number and learner reference");
		// R06 record level validation ends
		$st = $link->query($sql);	
		if($st)
		{
			$file='A';
			$file.=$L01;
			$file.='00';
			$file.='002070800101.';
			$file.= substr($subm,0,3);
		}
		return $file;
	}	
	
	/**
	 * This should be called in the context of a transaction
	 *
	 * @param pdo $link
	 */
	public function save(PDO $link)
	{
		// Clean up text fields
		$this->description = $this->cleanTextField($this->description);
		$this->structure = $this->cleanTextField($this->structure);
		$this->assessment_method = $this->cleanTextField($this->assessment_method);
		
		DAO::saveObjectToTable($link, 'qualifications', $this);
		
		return $this->id;
	}
	
	
	/**
	 * Deletes the qualification and its structure, but leaves the units untouched.
	 * Maybe later we could add a routine to delete unused units?
	 * Should be called in a transaction
	 */

	public function toXML($prefix = null, $namespace = null)
	{
		if(!is_null($namespace))
		{
			if($prefix == '')
			{
				$xmlns = "xmlns=\"".htmlspecialchars((string)$namespace).'"';
			}
			else
			{
				$xmlns = "xmlns:$prefix=\"".htmlspecialchars((string)$namespace).'"';
			}
		}
		else
		{
			$xmlns = '';
		}
		
		if($prefix != '')
		{
			$p = $prefix.':';
		}
		else
		{
			$p = '';
		}
		
		
		$xml = "<{$p}qualification $xmlns "
			.$p.'reference="'.htmlspecialchars((string)$this->id).'" '
			.$p.'lsc_learning_aim="'.htmlspecialchars((string)$this->lsc_learning_aim).'" '
			.$p.'awarding_body="'.htmlspecialchars((string)$this->awarding_body).'" '
			.$p.'title="'.htmlspecialchars((string)$this->title).'" '
			.$p.'level="'.htmlspecialchars((string)$this->level).'" '
			.$p.'type="'.htmlspecialchars((string)$this->qualification_type).'" '
			.$p.'accreditation_start_date="'.htmlspecialchars(Date::toMySQL($this->accreditation_start_date)).'" '
			.$p.'operational_centre_start_date="'.htmlspecialchars(Date::toMySQL($this->operational_centre_start_date)).'" '
			.$p.'accreditation_end_date="'.htmlspecialchars(Date::toMySQL($this->accreditation_end_date)).'" '
			.$p.'certification_end_date="'.htmlspecialchars(Date::toMySQL($this->certification_end_date)).'" '
			.$p.'dfes_approval_start_date="'.htmlspecialchars(Date::toMySQL($this->dfes_approval_start_date)).'" '
			.$p.'dfes_approval_end_date="'.htmlspecialchars(Date::toMySQL($this->dfes_approval_end_date)).'">'."\n";
		
		$xml .= "<{$p}description>".htmlspecialchars((string)$this->description)."</{$p}description>\n";
		$xml .= "<{$p}assessment_method>".htmlspecialchars((string)$this->assessment_method)."</{$p}assessment_method>\n";
		$xml .= "<{$p}structure>".htmlspecialchars((string)$this->structure)."</{$p}structure>\n";
		
		// Add any units if present
		if(!is_null($this->units) && ($this->units instanceof QualificationUnits))
		{
			$xml .= $this->units->toXML($prefix);
		}

		// Add any performance figures if present
		if(!is_null($this->grades) && (count($this->grades) > 0) )
		{
			$xml .= "<{$p}performance_figures>\n";
			foreach($this->grades as $grade)
			{
				$xml .= $grade->toXML($prefix, $namespace);
			}
			$xml .= "</{$p}performance_figures>\n";
		}
		
		$xml .= "</{$p}qualification>";
			
		
		return $xml;
	}
	
	
	public function isSafeToDelete(PDO $link)
	{
		$num_courses = "SELECT COUNT(*) FROM courses WHERE main_qualification_id='{$this->id}';";
		$num_courses = DAO::getSingleValue($link, $num_courses);
		
		return $num_courses === 0;
	}
	
	
	private function cleanTextField($fieldValue)
	{
		$fieldValue = str_replace($this->HTML_NEW_LINES, "\n", $fieldValue); // Convert <br/> etc. into \n
		$fieldValue = str_replace("\r", '', $fieldValue); // Remove all carriage returns (we'll use the UNIX newline)
		$fieldValue = preg_replace('/\n{2,}/', "\n", $fieldValue); // Remove superfluous newlines
		$fieldValue = strip_tags($fieldValue); // Remove HTML tags
		
		return $fieldValue;
	}
	
	public $id = NULL;
	public $gender = NULL;
	public $firstnames = NULL;
	public $surname = NULL;
	public $postcode = NULL;
	public $town = NULL;
	public $L26 = NULL;
	public $submission_date=NULL;
	public $subaims=0;
	public $learnerinformation = NULL;
	public $aims = array();
	public $active = NULL;
	public $approve = NULL;

	private $HTML_NEW_LINES = array('<br>', '<br/>', '<br />', '<BR>', '<BR/>', '<BR />', '</p>', '</P>');
}


?>