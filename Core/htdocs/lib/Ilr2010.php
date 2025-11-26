<?php

class Ilr2010 extends Entity
{
	public function __construct()
	{

	}
	
	
	public static function loadFromDatabase(PDO $link, $submission, $contract_id, $tr_id, $L03)
	{
		if(is_null($submission) || is_null($contract_id) || is_null($tr_id))
		{
			return null;
		}
		
		
		$vo = new Ilr2010();
		$vo->learnerinformation = new LearnerInformation();
		$vo->aims[0] = new Aim2010();

		$c = DAO::getSingleValue($link, "select count(*) from ilr WHERE submission='$submission' and contract_id=$contract_id and tr_id = $tr_id and L03='$L03'");
		if($c==0)
		{
			$vo->programmeaim = new Aim2010();
			$vo->programmeaim->A04 = "35";
			$vo->programmeaim->A09 = "ZPROG001";
			$vo->programmeaim->A10 = "45";
			return $vo;		
		}
		
		$sql = "SELECT * FROM ilr WHERE submission='$submission' and contract_id='$contract_id' and tr_id='$tr_id' and L03='$L03'";
		$st = $link->query($sql);
		if($st)
		{
			if($row = $st->fetch())
			{	
				$vo->id = $row['L03'];
				$vo->active=$row['is_active'];
				$vo->approve = $row['is_approved'];
				// $vo->submission_date = $row['submission_date'];
				$xml = $row['ilr'];
				//$xml = str_replace("&amp;", "&", $xml);
				//$xml = str_replace("&apos;", "'", $xml);
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
					$vo->learnerinformation->L28=$learner->L28;
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
					$vo->learnerinformation->L49a=$learner->L49a;
					$vo->learnerinformation->L49b=$learner->L49b;
					$vo->learnerinformation->L49c=$learner->L49c;
					$vo->learnerinformation->L49d=$learner->L49d;
										
				}

				foreach($ilr->programmeaim as $programmeaim)
				{
					$vo->programmeaim = new Aim2010();
					$vo->programmeaim->A02=$programmeaim->A02;
					$vo->programmeaim->A04=$programmeaim->A04;
					$vo->programmeaim->A09=$programmeaim->A09;
					$vo->programmeaim->A10=$programmeaim->A10;
					$vo->programmeaim->A11a=$programmeaim->A11a;
					$vo->programmeaim->A11b=$programmeaim->A11b;
					$vo->programmeaim->A14=$programmeaim->A14;
					$vo->programmeaim->A15=$programmeaim->A15;
					$vo->programmeaim->A16=$programmeaim->A16;
					$vo->programmeaim->A23=$programmeaim->A23;
					$vo->programmeaim->A26=$programmeaim->A26;
					$vo->programmeaim->A27=$programmeaim->A27;
					$vo->programmeaim->A28=$programmeaim->A28;
					$vo->programmeaim->A31=$programmeaim->A31;
					$vo->programmeaim->A34=$programmeaim->A34;
					$vo->programmeaim->A35=$programmeaim->A35;
					$vo->programmeaim->A40=$programmeaim->A40;
					$vo->programmeaim->A46a=$programmeaim->A46a;
					$vo->programmeaim->A46b=$programmeaim->A46b;
					$vo->programmeaim->A48a=$programmeaim->A48a;
					$vo->programmeaim->A48b=$programmeaim->A48b;
					$vo->programmeaim->A50=$programmeaim->A50;
					$vo->programmeaim->A51a=$programmeaim->A51a;
					$vo->programmeaim->A64=$programmeaim->A64;
					$vo->programmeaim->A65=$programmeaim->A65;
					$vo->programmeaim->A70=$programmeaim->A70;
				}
				
				foreach($ilr->main as $aim)
				{
					$vo->aims[0] = new Aim2010(); 
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
					$vo->aims[0]->A58=$aim->A58;
					$vo->aims[0]->A59=$aim->A59;
					$vo->aims[0]->A60=$aim->A60;
					$vo->aims[0]->A61=$aim->A61;
					$vo->aims[0]->A62=$aim->A62;
					$vo->aims[0]->A63=$aim->A63;
					$vo->aims[0]->A64=$aim->A64;
					$vo->aims[0]->A65=$aim->A65;
					$vo->aims[0]->A66=$aim->A66;
					$vo->aims[0]->A67=$aim->A67;
					$vo->aims[0]->A68=$aim->A68;
					$vo->aims[0]->A69=$aim->A69;
					$vo->aims[0]->A70=$aim->A70;
				}

				$sub = 1;
				foreach($ilr->subaim as $subaim)
				{
					// #120 {0000000011} -kkhan - initial object instantiation for php 5.3 strict mode added
					$vo->aims[$sub] = new Aim2010(); 
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
					$vo->aims[$sub]->A58 = $subaim->A58;
					$vo->aims[$sub]->A59 = $subaim->A59;
					$vo->aims[$sub]->A60 = $subaim->A60;
					$vo->aims[$sub]->A61 = $subaim->A61;
					$vo->aims[$sub]->A62 = $subaim->A62;
					$vo->aims[$sub]->A63 = $subaim->A63;
					$vo->aims[$sub]->A64 = $subaim->A64;
					$vo->aims[$sub]->A65 = $subaim->A65;
					$vo->aims[$sub]->A66 = $subaim->A66;
					$vo->aims[$sub]->A67 = $subaim->A67;
					$vo->aims[$sub]->A68 = $subaim->A68;
					$vo->aims[$sub]->A69 = $subaim->A69;
					$vo->aims[$sub]->A70 = $subaim->A70;
					
					
					$sub = $sub + 1;
				}
				$vo->subaims=($sub-1);
				
				
			}
		}
	return $vo;
	}		
			
	
	public static function loadFromXML($xml)
	{
		$vo = new Ilr2010();
		$vo->learnerinformation = new LearnerInformation();
		$vo->aims[0] = new Aim2010();
		
		
				$xml = str_replace("&", "&amp;",$xml);
				
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
					$vo->learnerinformation->L49a=$learner->L49a;
					$vo->learnerinformation->L49b=$learner->L49b;
					$vo->learnerinformation->L49c=$learner->L49c;
					$vo->learnerinformation->L49d=$learner->L49d;
					$vo->learnerinformation->subaims=$learner->subaims;									
				}

				
				foreach($ilr->programmeaim as $programmeaim)
				{
					$vo->programmeaim = new Aim2010();
					$vo->programmeaim->A01=$programmeaim->A01;
					$vo->programmeaim->A02=$programmeaim->A02;
					$vo->programmeaim->A03=$programmeaim->A03;
					$vo->programmeaim->A04=$programmeaim->A04;
					$vo->programmeaim->A05=$programmeaim->A05;
					$vo->programmeaim->A06=$programmeaim->A06;
					$vo->programmeaim->A07=$programmeaim->A07;
					$vo->programmeaim->A08=$programmeaim->A08;
					$vo->programmeaim->A09=$programmeaim->A09;
					$vo->programmeaim->A10=$programmeaim->A10;
					$vo->programmeaim->A11a=$programmeaim->A11a;
					$vo->programmeaim->A11b=$programmeaim->A11b;
					$vo->programmeaim->A12=$programmeaim->A12;
					$vo->programmeaim->A13=$programmeaim->A13;
					$vo->programmeaim->A14=$programmeaim->A14;
					$vo->programmeaim->A15=$programmeaim->A15;
					$vo->programmeaim->A16=$programmeaim->A16;

					$vo->programmeaim->A23=$programmeaim->A23;
					$vo->programmeaim->A26=$programmeaim->A26;
					$vo->programmeaim->A27=$programmeaim->A27;
					$vo->programmeaim->A28=$programmeaim->A28;
					$vo->programmeaim->A31=$programmeaim->A31;
					$vo->programmeaim->A34=$programmeaim->A34;
					$vo->programmeaim->A35=$programmeaim->A35;
					$vo->programmeaim->A40=$programmeaim->A40;
					$vo->programmeaim->A46a=$programmeaim->A46a;
					$vo->programmeaim->A46b=$programmeaim->A46b;
					$vo->programmeaim->A48a=$programmeaim->A48a;
					$vo->programmeaim->A48b=$programmeaim->A48b;
					$vo->programmeaim->A50=$programmeaim->A50;
					$vo->programmeaim->A51a=$programmeaim->A51a;
					$vo->programmeaim->A64=$programmeaim->A64;
					$vo->programmeaim->A65=$programmeaim->A65;
					$vo->programmeaim->A70=$programmeaim->A70;
				}
				
				
				
				foreach($ilr->main as $aim)
				{
					$vo->aims[0] = new Aim2010(); 
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
					$vo->aims[0]->A58=$aim->A58;
					$vo->aims[0]->A59=$aim->A59;
					$vo->aims[0]->A60=$aim->A60;
					$vo->aims[0]->A61=$aim->A61;
					$vo->aims[0]->A62=$aim->A62;
					$vo->aims[0]->A63=$aim->A63;
					$vo->aims[0]->A64=$aim->A64;
					$vo->aims[0]->A65=$aim->A65;
					$vo->aims[0]->A66=$aim->A66;
					$vo->aims[0]->A67=$aim->A67;
					$vo->aims[0]->A68=$aim->A68;
					$vo->aims[0]->A69=$aim->A69;
					$vo->aims[0]->A70=$aim->A70;
				}

				$sub = 1;
				foreach($ilr->subaim as $subaim)
				{
					// #120 {0000000011} -kkhan - initial object instantiation for php 5.3 strict mode added
					$vo->aims[$sub] = new Aim2010(); 
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
					$vo->aims[$sub]->A58 = $subaim->A58;
					$vo->aims[$sub]->A59 = $subaim->A59;
					$vo->aims[$sub]->A60 = $subaim->A60;
					$vo->aims[$sub]->A61 = $subaim->A61;
					$vo->aims[$sub]->A62 = $subaim->A62;
					$vo->aims[$sub]->A63 = $subaim->A63;
					$vo->aims[$sub]->A64 = $subaim->A64;
					$vo->aims[$sub]->A65 = $subaim->A65;
					$vo->aims[$sub]->A66 = $subaim->A66;
					$vo->aims[$sub]->A67 = $subaim->A67;
					$vo->aims[$sub]->A68 = $subaim->A68;
					$vo->aims[$sub]->A69 = $subaim->A69;
					$vo->aims[$sub]->A70 = $subaim->A70;
					$sub = $sub + 1;
				}
				$vo->subaims=($sub-1);

				return $vo;		

		
	}
		
	

	
	public static function generateStream3(PDO $link, $submission, $contracts, $con1, $L25, $transmission)
	{
		if(is_null($contracts) || is_null($submission))
		{
			return null;
		}

		
		$funding_model = DAO::getSingleValue($link, "select funding_body from contracts where id in ($contracts) limit 0,1");
		if($funding_model=='2')
		{
			$l03 = '';
			$vo = new Ilr2010();
			$vo->learnerinformation = new LearnerInformation();
			$vo->aims[0] = new Aim();
			
			$no_of_aims = 0;
			
			$sql = "SELECT * FROM ilr left join contracts on contracts.id = ilr.contract_id WHERE submission = '$submission' and contract_id in ($contracts) and is_active=1 ORDER BY l03, substr(ilr,locate('<L09>',ilr)+5,(locate('</L09>',ilr)-locate('<L09>',ilr)-5)), tr_id";
	
			$st = $link->query($sql);	
			if($st)
			{
				
				// writing header information in data stream file
				print($con1->upin);
				print('00');
				print('            ');
				print('00');
				print('1011');
				
				$funding_model = DAO::getSingleValue($link, "select funding_body from contracts where id in ($contracts) limit 0,1");
				if($funding_model=='1')
					print(str_replace("W","LR",$submission));
				else
					print(str_replace("W","ER",$submission));
				
				print(str_pad($transmission,3,'0',STR_PAD_LEFT));
				print(date("dmY"));
				print('A');
				print('1');
				print(str_pad("Perspective Limited",40));
				print(str_pad("Sunesis",30));
				print(str_pad("2.0",20));
				print(str_pad(" ",255));
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
					
						if($l03 != $row['L03'])
						{
							$no_of_aims=0;
						
							print($learner->L01);
							print('00');
							print(str_pad($learner->L03,12));
							print($learner->L04);
							
							// Calculation of L05
							$l03l05 = $row['L03'];
							$l05 = 0;
							$sqll05 = "select * from ilr where l03 = '$l03l05' and submission = '$submission' and is_active=1 and contract_id in ($contracts) order by tr_id";
							$stl05 = $link->query($sqll05);	
							$l35 = 99;
							$l39 = '';
							$l28a = 99;
							$l28b = 99;
							while($rowl05 = $stl05->fetch())
							{	
								$ilrl05 = $rowl05['ilr']; 
								$ilrl05 = str_replace("&", "a", $ilrl05);
								//$ilrl05 = new SimpleXMLElement($ilrl05);
								$ilrl05 = XML::loadSimpleXML($ilrl05);
	
								foreach($ilrl05->learner as $learner)
								{
									$l35 = ((int)$learner->L35 < $l35)?$learner->L35:$l35;
									$l39 = ($l39=='95')?$l39:$learner->L39;	
									$l28a = ((int)$learner->L28a<$l28a)?$learner->L28a:$l28a;
									$l28b = ((int)$learner->L28b<$l28b)?$learner->L28b:$l28b;
									if($learner->L08=="Y")
										$l05+=0;
									elseif((@$ilrl05->main[0]->A15!='99' && @$ilrl05->main[0]->A15!='') || @$ilrl05->programmeaim[0]->A10=='70')
										$l05+=$learner->L05;
									else
										$l05+=((int)$learner->L05-1);
								}								
							}
							// Calculation End
	
							if($l05=='0' && $learner->L08!="Y")
								$l05='1';
							print(str_pad($l05,2,'0',STR_PAD_LEFT));
							
	/*						if($learner->L08=="Y")
								print(str_pad("0",2,'0',STR_PAD_LEFT));
							elseif($ilr->main[0]->A15!='99' && $ilr->main[0]->A15!='')
								print(str_pad($learner->L05,2,'0',STR_PAD_LEFT));
							else
								print(str_pad(((int)$learner->L05-1),2,'0',STR_PAD_LEFT));
	*/
							
						//	print(str_pad($learner->L06,2,'0',STR_PAD_LEFT));
							print(str_pad($learner->L07,2,'0',STR_PAD_LEFT));
							print(str_pad($learner->L08,1,$learner->L08,STR_PAD_LEFT));
							print(str_pad(str_replace("apos;","'",$learner->L09),20));
							print(str_pad($learner->L10,40));
							if($learner->L11!='00000000' && $learner->L11!='' && $learner->L11!='dd/mm/yyyy')
								print(substr($learner->L11,0,2).substr($learner->L11,3,2).substr($learner->L11,6,4));
							else
								print(str_pad($learner->L11,8,'0',STR_PAD_LEFT));
							print(str_pad($learner->L12,2,'9',STR_PAD_LEFT));
							print($learner->L13);
							print(str_pad($learner->L14,1,'9',STR_PAD_LEFT));
							print(str_pad($learner->L15,2,'0',STR_PAD_LEFT));
							print(str_pad($learner->L16,2,'0',STR_PAD_LEFT));
							print(str_pad($learner->L17,8));
							
							if(strlen($learner->L18)>30)
								print(substr($learner->L18,0,30));
							else
								print(str_pad($learner->L18,30));
							
							print(str_pad($learner->L19,30));
							print(str_pad($learner->L20,30));
							print(str_pad($learner->L21,30));
							print(str_pad($learner->L22,8));
							print(str_pad($learner->L23,15));
							print(str_pad($learner->L24,2));
							print(str_pad($learner->L26,9,' ',STR_PAD_LEFT));
							print(str_pad($learner->L27,1,'0',STR_PAD_LEFT));
							print('00');
							print(str_pad($learner->L29,2,'0',STR_PAD_LEFT));
							print(str_pad($learner->L31,6,'0',STR_PAD_LEFT));
							print(str_pad($learner->L32,2,'0',STR_PAD_LEFT));
							if(strlen($learner->L33)!=6)
								print('0.0000');
							else
								print(str_pad($learner->L33,6,'0',STR_PAD_LEFT));
							print(str_pad($learner->L34a,2,'0',STR_PAD_LEFT));
							print(str_pad($learner->L34b,2,'0',STR_PAD_LEFT));
							print(str_pad($learner->L34c,2,'0',STR_PAD_LEFT));
							print(str_pad($learner->L34d,2,'0',STR_PAD_LEFT));
							print(str_pad($l35,2,'0',STR_PAD_LEFT));
							print(str_pad($learner->L36,2,'0',STR_PAD_LEFT));
							print(str_pad($learner->L37,2,'0',STR_PAD_LEFT));
	//						print(str_pad($learner->L38,2,'0',STR_PAD_LEFT));
							print(str_pad($l39,2,'0',STR_PAD_LEFT));
							print(str_pad($learner->L40a,2,'0',STR_PAD_LEFT));
							print(str_pad($learner->L40b,2,'0',STR_PAD_LEFT));
							print(str_pad($learner->L41a,12,'0',STR_PAD_LEFT));
							print(str_pad($learner->L41b,12,'0',STR_PAD_LEFT));
							print(str_pad($learner->L42a,12));
							print(str_pad($learner->L42b,12));
							print(str_pad($learner->L45,10,'9',STR_PAD_LEFT));
							print(str_pad($learner->L46,8,'0',STR_PAD_LEFT));
							print(str_pad($learner->L47,2,'0',STR_PAD_LEFT));
							if($learner->L48!='00000000' && $learner->L48!='')
								print(substr($learner->L48,0,2).substr($learner->L48,3,2).substr($learner->L48,6,4));
							else
								print(str_pad($learner->L48,8,'0',STR_PAD_LEFT));
							print(str_pad($learner->L49a,2,'0',STR_PAD_LEFT));
							print(str_pad($learner->L49b,2,'0',STR_PAD_LEFT));
							print(str_pad($learner->L49c,2,'0',STR_PAD_LEFT));
							print(str_pad($learner->L49d,2,'0',STR_PAD_LEFT));
							print("\r\n");
							$record++;				
						}
					}
					$l03 = $row['L03'];
					if($learner->L08!="Y")
					{
						foreach($ilr->programmeaim as $aim)
						{
							if( ($aim->A15!='99' && $aim->A15!='') || $aim->A10=='70')
							{
								$no_of_aims++;
								print(str_pad($aim->A01,6,'0',STR_PAD_LEFT));
								print(str_pad($aim->A02,2,'0',STR_PAD_LEFT));
								print(str_pad($aim->A03,12));
								print($aim->A04);
								print(str_pad($no_of_aims,2,'0',STR_PAD_LEFT));
	//							print(str_pad($aim->A06,2,'0',STR_PAD_LEFT));
								print(str_pad($aim->A07,2,'0',STR_PAD_LEFT));
								print(str_pad($con1->funding_body,1,' ',STR_PAD_LEFT));
								
								if($aim->A10=='70')
									print("ZESF0001");
								else
									print($aim->A09);
	
								print($aim->A10);
								print(str_pad($aim->A11a,3,'0',STR_PAD_LEFT));
								print(str_pad($aim->A11b,3,'0',STR_PAD_LEFT));
								print("00000");
							//	print(str_pad($aim->A13,5,'0',STR_PAD_LEFT));
								print(str_pad($aim->A14,2,'0',STR_PAD_LEFT));
								print(str_pad($aim->A15,2,'0',STR_PAD_LEFT));
								print(str_pad($aim->A16,2,'0',STR_PAD_LEFT));
								print(str_pad($aim->A17,1,'0',STR_PAD_LEFT));
	
								if($aim->A10=='70')
									print(str_pad($ilr->main->A18,2,'0',STR_PAD_LEFT));
								else 
									print(str_pad($aim->A18,2,'0',STR_PAD_LEFT));
								
								print(str_pad($aim->A19,1,'0',STR_PAD_LEFT));
								print(str_pad($aim->A20,1,'0',STR_PAD_LEFT));
								print(str_pad($aim->A22,8,'0',STR_PAD_RIGHT));
								print(str_pad($aim->A23,8,' ',STR_PAD_RIGHT));
		//						print(str_pad($aim->A24,4,'0',STR_PAD_LEFT));
								print(str_pad($aim->A26,3,'0',STR_PAD_LEFT));
								if($aim->A27!='00000000' && $aim->A27!='dd/mm/yyyy' && $aim->A27!='')
									print(substr($aim->A27,0,2).substr($aim->A27,3,2).substr($aim->A27,6,4));
								else
									print(str_pad($aim->A27,8,'0',STR_PAD_LEFT));
								if($aim->A28!='00000000' && $aim->A28!='dd/mm/yyyy' && $aim->A28!='')
									print(substr($aim->A28,0,2).substr($aim->A28,3,2).substr($aim->A28,6,4));
								else
									print(str_pad($aim->A28,8,'0',STR_PAD_LEFT));
								if($aim->A31!='00000000' && $aim->A31!='dd/mm/yyyy' && $aim->A31!='')
									print(substr($aim->A31,0,2).substr($aim->A31,3,2).substr($aim->A31,6,4));
								else
									print(str_pad($aim->A31,8,'0',STR_PAD_LEFT));
								print(str_pad($aim->A32,5,'0',STR_PAD_LEFT));
								print(str_pad($aim->A34,1,'1',STR_PAD_LEFT));
								print(str_pad($aim->A35,1,'9',STR_PAD_LEFT));
								print(str_pad($aim->A36,6));
	
								if($aim->A40!='00000000' && $aim->A40!='dd/mm/yyyy' && $aim->A40!='')
									print(substr($aim->A40,0,2).substr($aim->A40,3,2).substr($aim->A40,6,4));
								else
									print(str_pad($aim->A40,8,'0',STR_PAD_LEFT));
	
								print(str_pad(substr($aim->A44,0,30),30));
								print(str_pad($aim->A45,8));
	
								if($aim->A46a!='')
									print(str_pad($aim->A46a,3,'0',STR_PAD_LEFT));
								else
									print(str_pad($aim->A46a,3,'9',STR_PAD_LEFT));
	
								if($aim->A46b!='')	
									print(str_pad($aim->A46b,3,'0',STR_PAD_LEFT));
								else
									print(str_pad($aim->A46b,3,'9',STR_PAD_LEFT));
															
								print(str_pad($aim->A47a,12,'0',STR_PAD_LEFT));
								print(str_pad($aim->A47b,12,'0',STR_PAD_LEFT));
								print(str_pad($aim->A48a,12,' ',STR_PAD_RIGHT));
								print(str_pad($aim->A48b,12,' ',STR_PAD_RIGHT));
								print(str_pad($aim->A49,5));
								print(str_pad($aim->A50,2,'0',STR_PAD_LEFT));
								
								if($aim->A51a=="undefined")
									print("000");
								else
									print(str_pad($aim->A51a,3,'0',STR_PAD_LEFT));
									
								if(strlen($aim->A52)<5)
									print("0.000");
								else
									print(str_pad($aim->A52,5,'0',STR_PAD_LEFT));
								if($aim->A09=='ZPROG001' && $aim->A10!='70')		
									print('00');
								else
									print(str_pad($aim->A53,2,'0',STR_PAD_LEFT));

								print(str_pad($learner->L45,10,'9',STR_PAD_LEFT));
								//print(str_pad($aim->A55,10,'9',STR_PAD_LEFT));
								print(str_pad($aim->A56,8,'0',STR_PAD_LEFT));
								print(str_pad($aim->A57,2,'0',STR_PAD_LEFT));
								print(str_pad($aim->A58,2,'0',STR_PAD_LEFT));
								print(str_pad($aim->A59,3,'0',STR_PAD_LEFT));
								print(str_pad($aim->A60,3,'0',STR_PAD_LEFT));
	
								if(empty($ilr->main))
								{
									print('         ');
									print('000');
									print('00');
									print('00000');
									print('00000');
									print('000000');
								}
								else 
								{
									foreach($ilr->main as $mmainaim)
									{
										print(str_pad($mmainaim->A61,9,' ',STR_PAD_LEFT));
										print(str_pad($mmainaim->A62,3,'0',STR_PAD_LEFT));
										print(str_pad($mmainaim->A63,2,'0',STR_PAD_LEFT));
										print(str_pad($aim->A64,5,'0',STR_PAD_LEFT));
										print(str_pad($aim->A65,5,'0',STR_PAD_LEFT));
										print(str_pad($mmainaim->A66,2,'0',STR_PAD_LEFT));
										print(str_pad($mmainaim->A67,2,'0',STR_PAD_LEFT));
										print(str_pad($mmainaim->A68,2,'0',STR_PAD_LEFT));
									}
								}							
	
								if($aim->A10=='70')
									print(str_pad($mmainaim->A69,2,'0',STR_PAD_LEFT));
								else 
									print(str_pad($aim->A69,2,'0',STR_PAD_LEFT));
								
								print(str_pad($aim->A70,5,' ',STR_PAD_RIGHT));
	
								print(str_pad(" ",91));
								print( "\r\n");
								$record++;
								
							}
						}
						
						
						foreach($ilr->main as $aim)
						{
							$no_of_aims++;
							print(str_pad($aim->A01,6,'0',STR_PAD_LEFT));
							print(str_pad($aim->A02,2,'0',STR_PAD_LEFT));
							print(str_pad($aim->A03,12));
							print($aim->A04);
							print(str_pad($no_of_aims,2,'0',STR_PAD_LEFT));
	//						print(str_pad($aim->A06,2,'0',STR_PAD_LEFT));
							print(str_pad($aim->A07,2,'0',STR_PAD_LEFT));
							print(str_pad($con1->funding_body,1,' ',STR_PAD_LEFT));
							print($aim->A09);
							print(str_pad($aim->A10,2,'9',STR_PAD_LEFT));
							print(str_pad($aim->A11a,3,'0',STR_PAD_LEFT));
							print(str_pad($aim->A11b,3,'0',STR_PAD_LEFT));
	//						print("000000");
							print(str_pad($aim->A13,5,'0',STR_PAD_LEFT));
							print(str_pad($aim->A14,2,'0',STR_PAD_LEFT));
							print(str_pad($aim->A15,2,'0',STR_PAD_LEFT));
							print(str_pad($aim->A16,2,'0',STR_PAD_LEFT));
							print(str_pad($aim->A17,1,'0',STR_PAD_LEFT));
							print(str_pad($aim->A18,2,'0',STR_PAD_LEFT));
							print(str_pad($aim->A19,1,'0',STR_PAD_LEFT));
							print(str_pad($aim->A20,1,'0',STR_PAD_LEFT));
							print(str_pad($aim->A22,8,'0',STR_PAD_RIGHT));
							print(str_pad($aim->A23,8,' ',STR_PAD_RIGHT));
	//						print(str_pad($aim->A24,4,'0',STR_PAD_LEFT));
							print(str_pad($aim->A26,3,'0',STR_PAD_LEFT));
							if($aim->A27!='00000000' && $aim->A27!='dd/mm/yyyy' && $aim->A27!='')
								print(substr($aim->A27,0,2).substr($aim->A27,3,2).substr($aim->A27,6,4));
							else
								print(str_pad($aim->A27,8,'0',STR_PAD_LEFT));
							if($aim->A28!='00000000' && $aim->A28!='dd/mm/yyyy' && $aim->A28!='')
								print(substr($aim->A28,0,2).substr($aim->A28,3,2).substr($aim->A28,6,4));
							else
								print(str_pad($aim->A28,8,'0',STR_PAD_LEFT));
							if($aim->A31!='00000000' && $aim->A31!='dd/mm/yyyy' && $aim->A31!='')
								print(substr($aim->A31,0,2).substr($aim->A31,3,2).substr($aim->A31,6,4));
							else
								print(str_pad($aim->A31,8,'0',STR_PAD_LEFT));
							print(str_pad($aim->A32,5,'0',STR_PAD_LEFT));
	//						print(str_pad($aim->A33,5));
							print(str_pad($aim->A34,1,'1',STR_PAD_LEFT));
							print(str_pad($aim->A35,1,'9',STR_PAD_LEFT));
							print(str_pad($aim->A36,6));
	//						print(str_pad($aim->A37,2,'0',STR_PAD_LEFT));
	//						print(str_pad($aim->A38,2,'0',STR_PAD_LEFT));
	//						print("0");
							if($aim->A40!='00000000' && $aim->A40!='dd/mm/yyyy' && $aim->A40!='')
								print(substr($aim->A40,0,2).substr($aim->A40,3,2).substr($aim->A40,6,4));
							else
								print(str_pad($aim->A40,8,'0',STR_PAD_LEFT));
	//						print("00000000");
							print(str_pad(substr($aim->A44,0,30),30));
							print(str_pad($aim->A45,8));
	
							if($aim->A46a!='')
								print(str_pad($aim->A46a,3,'0',STR_PAD_LEFT));
							else
								print(str_pad($aim->A46a,3,'9',STR_PAD_LEFT));
	
							if($aim->A46b!='')	
								print(str_pad($aim->A46b,3,'0',STR_PAD_LEFT));
							else
								print(str_pad($aim->A46b,3,'9',STR_PAD_LEFT));
							
							print(str_pad($aim->A47a,12,'0',STR_PAD_LEFT));
							print(str_pad($aim->A47b,12,'0',STR_PAD_LEFT));
							print(str_pad($aim->A48a,12,' ',STR_PAD_RIGHT));
							print(str_pad($aim->A48b,12,' ',STR_PAD_RIGHT));
							print(str_pad($aim->A49,5));
							print(str_pad($aim->A50,2,'0',STR_PAD_LEFT));
							print(str_pad($aim->A51a,3,'0',STR_PAD_LEFT));
							if(strlen($aim->A52)<5)
								print("0.000");
							else
								print(str_pad($aim->A52,5,'0',STR_PAD_LEFT));
							print(str_pad($aim->A53,2,'0',STR_PAD_LEFT));
							print(str_pad($aim->A55,10,'9',STR_PAD_LEFT));
							print(str_pad($aim->A56,8,'0',STR_PAD_LEFT));
							print(str_pad($aim->A57,2,'0',STR_PAD_LEFT));
							print(str_pad($aim->A58,2,'0',STR_PAD_LEFT));
							print(str_pad($aim->A59,3,'0',STR_PAD_LEFT));
							print(str_pad($aim->A60,3,'0',STR_PAD_LEFT));
							print(str_pad($aim->A61,9,' ',STR_PAD_LEFT));
							print(str_pad($aim->A62,3,'0',STR_PAD_LEFT));
							print(str_pad($aim->A63,2,'0',STR_PAD_LEFT));
							print(str_pad($aim->A64,5,'0',STR_PAD_LEFT));
							print(str_pad($aim->A65,5,'0',STR_PAD_LEFT));
							print(str_pad($aim->A66,2,'0',STR_PAD_LEFT));
							print(str_pad($aim->A67,2,'0',STR_PAD_LEFT));
							print(str_pad($aim->A68,2,'0',STR_PAD_LEFT));
							print(str_pad($aim->A69,2,'0',STR_PAD_LEFT));
							print(str_pad($aim->A70,5,' ',STR_PAD_RIGHT));
							
							print(str_pad(" ",91));
							print( "\r\n");
							$record++;
						}
		
						$sub = 1;
						foreach($ilr->subaim as $aim)
						{
	
							$no_of_aims++;
							print(str_pad($aim->A01,6,'0',STR_PAD_LEFT));
							print(str_pad($aim->A02,2,'0',STR_PAD_LEFT));
							print(str_pad($aim->A03,12));
							print($aim->A04);
							print(str_pad($no_of_aims,2,'0',STR_PAD_LEFT));
	//						print(str_pad($aim->A06,2,'0',STR_PAD_LEFT));
							print(str_pad($aim->A07,2,'0',STR_PAD_LEFT));
							print($con1->funding_body);
							print(str_pad($aim->A09,8,' ',STR_PAD_RIGHT));
							print(str_pad($aim->A10,2,'9',STR_PAD_LEFT));
							print(str_pad($aim->A11a,3,'0',STR_PAD_LEFT));
							print(str_pad($aim->A11b,3,'0',STR_PAD_LEFT));
	//						print("000000");
							print(str_pad($aim->A13,5,'0',STR_PAD_LEFT));
							print(str_pad($aim->A14,2,'0',STR_PAD_LEFT));
							print(str_pad($aim->A15,2,'0',STR_PAD_LEFT));
							print(str_pad($aim->A16,2,'0',STR_PAD_LEFT));
							print(str_pad($aim->A17,1,'0',STR_PAD_LEFT));
							print(str_pad($aim->A18,2,'0',STR_PAD_LEFT));
							print(str_pad($aim->A19,1,'0',STR_PAD_LEFT));
							print(str_pad($aim->A20,1,'0',STR_PAD_LEFT));
							print(str_pad($aim->A22,8,'0',STR_PAD_RIGHT));
							print(str_pad($aim->A23,8,' ',STR_PAD_RIGHT));
	//						print(str_pad($aim->A24,4,'0',STR_PAD_LEFT));
							print(str_pad($aim->A26,3,'0',STR_PAD_LEFT));
							if($aim->A27!='00000000' && $aim->A27!='dd/mm/yyyy' && $aim->A27!='')
								print(substr($aim->A27,0,2).substr($aim->A27,3,2).substr($aim->A27,6,4));
							else
								print(str_pad($aim->A27,8,'0',STR_PAD_LEFT));
							if($aim->A28!='00000000' && $aim->A28!='dd/mm/yyyy' && $aim->A28!='')
								print(substr($aim->A28,0,2).substr($aim->A28,3,2).substr($aim->A28,6,4));
							else
								print(str_pad($aim->A28,8,'0',STR_PAD_LEFT));
							if($aim->A31!='00000000' && $aim->A31!='dd/mm/yyyy' && $aim->A31!='')
								print(substr($aim->A31,0,2).substr($aim->A31,3,2).substr($aim->A31,6,4));
							else
								print(str_pad($aim->A31,8,'0',STR_PAD_LEFT));
							print(str_pad($aim->A32,5,'0',STR_PAD_LEFT));
		//					print(str_pad($aim->A33,5));
							print(str_pad($aim->A34,1,'1',STR_PAD_LEFT));
							print(str_pad($aim->A35,1,'9',STR_PAD_LEFT));
							print(str_pad($aim->A36,6));
	//						print(str_pad($aim->A37,2,'0',STR_PAD_LEFT));
	//						print(str_pad($aim->A38,2,'0',STR_PAD_LEFT));
	//						print("0");
							if($aim->A40!='00000000' && $aim->A40!='dd/mm/yyyy' && $aim->A40!='')
								print(substr($aim->A40,0,2).substr($aim->A40,3,2).substr($aim->A40,6,4));
							else
								print(str_pad($aim->A40,8,'0',STR_PAD_LEFT));
	//						print("00000000");
							print(str_pad(substr($aim->A44,0,30),30));
							print(str_pad($aim->A45,8));
							
							if($aim->A46a!='')
								print(str_pad($aim->A46a,3,'0',STR_PAD_LEFT));
							else
								print(str_pad($aim->A46a,3,'9',STR_PAD_LEFT));
	
							if($aim->A46b!='')	
								print(str_pad($aim->A46b,3,'0',STR_PAD_LEFT));
							else
								print(str_pad($aim->A46b,3,'9',STR_PAD_LEFT));
							
							print(str_pad($aim->A47a,12,'0',STR_PAD_LEFT));
							print(str_pad($aim->A47b,12,'0',STR_PAD_LEFT));
							print(str_pad($aim->A48a,12,' ',STR_PAD_RIGHT));
							print(str_pad($aim->A48b,12,' ',STR_PAD_RIGHT));
							print(str_pad($aim->A49,5));
							print(str_pad($aim->A50,2,'0',STR_PAD_LEFT));
		
							if($aim->A51a=="undefined")
								print("000");
							else
								print(str_pad($aim->A51a,3,'0',STR_PAD_LEFT));
		
							if(strlen($aim->A52)<5)
								print("0.000");
							else
								print(str_pad($aim->A52,5,'0',STR_PAD_LEFT));
							print(str_pad($aim->A53,2,'0',STR_PAD_LEFT));
							print(str_pad($aim->A55,10,'9',STR_PAD_LEFT));
							print(str_pad($aim->A56,8,'0',STR_PAD_LEFT));
							print(str_pad($aim->A57,2,'0',STR_PAD_LEFT));
							print(str_pad($aim->A58,2,'0',STR_PAD_LEFT));
							print(str_pad($aim->A59,3,'0',STR_PAD_LEFT));
							print(str_pad($aim->A60,3,'0',STR_PAD_LEFT));
							print(str_pad($aim->A61,9,' ',STR_PAD_LEFT));
							print(str_pad($aim->A62,3,'0',STR_PAD_LEFT));
							print(str_pad($aim->A63,2,'0',STR_PAD_LEFT));
							print(str_pad($aim->A64,5,'0',STR_PAD_LEFT));
							print(str_pad($aim->A65,5,'0',STR_PAD_LEFT));
							print(str_pad($aim->A66,2,'0',STR_PAD_LEFT));
							print(str_pad($aim->A67,2,'0',STR_PAD_LEFT));
							print(str_pad($aim->A68,2,'0',STR_PAD_LEFT));
							print(str_pad($aim->A69,2,'0',STR_PAD_LEFT));
							print(str_pad($aim->A70,5,' ',STR_PAD_RIGHT));
							
							print(str_pad(" ",91));
							print( "\r\n");
							$record++;
							//$no_of_aims++;
						}
							$sub = $sub + 1;
						}
						$vo->subaims=($sub-1);
					}				
				}
			// writing footer record 
			$record++;	
			print($con1->upin);
			print('00');
			print('ZZZZZZZZZZZZ');
			print('99');
			print('1011');
			
			if($funding_model == 1)
				print(str_replace("W","LR",$submission));
			else
				print(str_replace("W","ER",$submission));
			
			print(str_pad($transmission,3,'0',STR_PAD_LEFT));
			print(date("dmY"));
			print(str_pad($record,7,'0',STR_PAD_LEFT));
			print(str_pad(" ",340));
			print("\r\n");
		}
		else // Learner Responsive
		{
			$l03 = '';
			$vo = new Ilr2010();
			$vo->learnerinformation = new LearnerInformation();
			$vo->aims[0] = new Aim();
			
			$no_of_aims = 0;
			
			$sql = "SELECT * FROM ilr left join contracts on contracts.id = ilr.contract_id WHERE submission = '$submission' and contract_id in ($contracts) and is_active=1 ORDER BY l03, substr(ilr,locate('<L09>',ilr)+5,(locate('</L09>',ilr)-locate('<L09>',ilr)-5)), tr_id";
	
			$st = $link->query($sql);	
			if($st)
			{
				
				// writing header information in data stream file
				print($con1->upin);
				print('00');
				print('            ');
				print('00');
				print('1011');
				
				print(str_replace("W","LR",$submission));
				print(str_pad($transmission,3,'0',STR_PAD_LEFT));
				print(date("dmY"));
				print('A');
				print('1');
				print(str_pad("Perspective Limited",40));
				print(str_pad("Sunesis",30));
				print(str_pad("2.0",20));
				print(str_pad(" ",255));
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
					
						if($l03 != $row['L03'])
						{
							$no_of_aims=0;
						
							print($learner->L01);
							print('00');
							print(str_pad($learner->L03,12));
							print($learner->L04);
							
							// Calculation of L05
							$l03l05 = $row['L03'];
							$l05 = 0;
							$sqll05 = "select * from ilr where l03 = '$l03l05' and submission = '$submission' and is_active=1 and contract_id in ($contracts) order by tr_id";
							$stl05 = $link->query($sqll05);	
							$l35 = 99;
							$l39 = '';
							$l28a = 99;
							$l28b = 99;
							while($rowl05 = $stl05->fetch())
							{	
								$ilrl05 = $rowl05['ilr']; 
								$ilrl05 = str_replace("&", "a", $ilrl05);
								//$ilrl05 = new SimpleXMLElement($ilrl05);
								$ilrl05 = XML::loadSimpleXML($ilrl05);
	
								foreach($ilrl05->learner as $learner)
								{
									$l35 = ((int)$learner->L35 < $l35)?$learner->L35:$l35;
									$l39 = ($l39=='95')?$l39:$learner->L39;	
									$l28a = ((int)$learner->L28a<$l28a)?$learner->L28a:$l28a;
									$l28b = ((int)$learner->L28b<$l28b)?$learner->L28b:$l28b;
									if($learner->L08=="Y")
										$l05+=0;
									elseif(($ilrl05->main[0]->A15!='99' && $ilrl05->main[0]->A15!='') || $ilrl05->programmeaim[0]->A10=='70')
										$l05+=$learner->L05;
									else
										$l05+=((int)$learner->L05-1);
								}								
							}
							// Calculation End
	
							print(str_pad($l05,2,'0',STR_PAD_LEFT));
							
	/*						if($learner->L08=="Y")
								print(str_pad("0",2,'0',STR_PAD_LEFT));
							elseif($ilr->main[0]->A15!='99' && $ilr->main[0]->A15!='')
								print(str_pad($learner->L05,2,'0',STR_PAD_LEFT));
							else
								print(str_pad(((int)$learner->L05-1),2,'0',STR_PAD_LEFT));
	*/
							
						//	print(str_pad($learner->L06,2,'0',STR_PAD_LEFT));
							print(str_pad($learner->L07,2,'0',STR_PAD_LEFT));
							print(str_pad($learner->L08,1,' ',STR_PAD_LEFT));
							print(str_pad(str_replace("apos;","'",$learner->L09),20));
							print(str_pad($learner->L10,40));
							if($learner->L11!='00000000' && $learner->L11!='' && $learner->L11!='dd/mm/yyyy')
								print(substr($learner->L11,0,2).substr($learner->L11,3,2).substr($learner->L11,6,4));
							else
								print(str_pad($learner->L11,8,'0',STR_PAD_LEFT));
							print(str_pad($learner->L12,2,'9',STR_PAD_LEFT));
							print($learner->L13);
							print(str_pad($learner->L14,1,'9',STR_PAD_LEFT));
							print(str_pad($learner->L15,2,'0',STR_PAD_LEFT));
							print(str_pad($learner->L16,2,'0',STR_PAD_LEFT));
							print(str_pad($learner->L17,8));
							
							if(strlen($learner->L18)>30)
								print(substr($learner->L18,0,30));
							else
								print(str_pad($learner->L18,30));
							
							print(str_pad($learner->L19,30));
							print(str_pad($learner->L20,30));
							print(str_pad($learner->L21,30));
							print(str_pad($learner->L22,8));
							print(str_pad($learner->L23,15));
							print(str_pad($learner->L24,2));
							print(str_pad($learner->L26,9,' ',STR_PAD_LEFT));
							print(str_pad($learner->L27,1,'0',STR_PAD_LEFT));
							print(str_pad($learner->L28,2,'0',STR_PAD_LEFT));
							print(str_pad($learner->L29,2,'0',STR_PAD_LEFT));
							print(str_pad($learner->L31,6,'0',STR_PAD_LEFT));
							print(str_pad($learner->L32,2,'0',STR_PAD_LEFT));
							if(strlen($learner->L33)!=6)
								print('0.0000');
							else
								print(str_pad($learner->L33,6,'0',STR_PAD_LEFT));
							print(str_pad($learner->L34a,2,'0',STR_PAD_LEFT));
							print(str_pad($learner->L34b,2,'0',STR_PAD_LEFT));
							print(str_pad($learner->L34c,2,'0',STR_PAD_LEFT));
							print(str_pad($learner->L34d,2,'0',STR_PAD_LEFT));
							print(str_pad($l35,2,'0',STR_PAD_LEFT));
							print(str_pad('0',2,'0',STR_PAD_LEFT));
							print(str_pad($learner->L37,2,'0',STR_PAD_LEFT));
							print(str_pad($l39,2,'0',STR_PAD_LEFT));
							print(str_pad($learner->L40a,2,'0',STR_PAD_LEFT));
							print(str_pad($learner->L40b,2,'0',STR_PAD_LEFT));
							print(str_pad($learner->L41a,12,'0',STR_PAD_LEFT));
							print(str_pad($learner->L41b,12,'0',STR_PAD_LEFT));
							print(str_pad($learner->L42a,12));
							print(str_pad($learner->L42b,12));
							print(str_pad($learner->L45,10,'9',STR_PAD_LEFT));
							print(str_pad($learner->L46,8,'0',STR_PAD_LEFT));
							print(str_pad('0',2,'0',STR_PAD_LEFT));
							print(str_pad('0',8,'0',STR_PAD_LEFT));
							print(str_pad($learner->L49a,2,'0',STR_PAD_LEFT));
							print(str_pad($learner->L49b,2,'0',STR_PAD_LEFT));
							print(str_pad($learner->L49c,2,'0',STR_PAD_LEFT));
							print(str_pad($learner->L49d,2,'0',STR_PAD_LEFT));
							print("\r\n");
							$record++;				
						}
					}
					$l03 = $row['L03'];
					if($learner->L08!="Y")
					{
						foreach($ilr->programmeaim as $aim)
						{
							if( ($aim->A15!='99' && $aim->A15!='') || $aim->A10=='70')
							{
								$no_of_aims++;
								print(str_pad($aim->A01,6,'0',STR_PAD_LEFT));
								print(str_pad('0',2,'0',STR_PAD_LEFT));
								print(str_pad($aim->A03,12));
								print($aim->A04);
								print(str_pad($no_of_aims,2,'0',STR_PAD_LEFT));
								print(str_pad('0',2,'0',STR_PAD_LEFT));
								print(str_pad('1',1,' ',STR_PAD_LEFT));
								
								if($aim->A10=='70')
									print("ZESF0001");
								else
									print($aim->A09);
	
								print(str_pad($aim->A10,2,'0',STR_PAD_LEFT));
								print(str_pad($aim->A11a,3,'0',STR_PAD_LEFT));
								print(str_pad($aim->A11b,3,'0',STR_PAD_LEFT));
								print(str_pad($aim->A13,5,'0',STR_PAD_LEFT));
								print(str_pad($aim->A14,2,'0',STR_PAD_LEFT));
								print(str_pad($aim->A15,2,'0',STR_PAD_LEFT));
								print(str_pad("0",2,'0',STR_PAD_LEFT));
								print(str_pad('0',1,'0',STR_PAD_LEFT));
								print(str_pad('0',2,'0',STR_PAD_LEFT));
								print(str_pad('0',1,'0',STR_PAD_LEFT));
								print(str_pad('0',1,'0',STR_PAD_LEFT));
								print(str_pad('0',8,'0',STR_PAD_RIGHT));
								print(str_pad($aim->A23,8,' ',STR_PAD_RIGHT));
								print(str_pad($aim->A26,3,'0',STR_PAD_LEFT));
								if($aim->A27!='00000000' && $aim->A27!='dd/mm/yyyy' && $aim->A27!='')
									print(substr($aim->A27,0,2).substr($aim->A27,3,2).substr($aim->A27,6,4));
								else
									print(str_pad($aim->A27,8,'0',STR_PAD_LEFT));
								if($aim->A28!='00000000' && $aim->A28!='dd/mm/yyyy' && $aim->A28!='')
									print(substr($aim->A28,0,2).substr($aim->A28,3,2).substr($aim->A28,6,4));
								else
									print(str_pad($aim->A28,8,'0',STR_PAD_LEFT));
								if($aim->A31!='00000000' && $aim->A31!='dd/mm/yyyy' && $aim->A31!='')
									print(substr($aim->A31,0,2).substr($aim->A31,3,2).substr($aim->A31,6,4));
								else
									print(str_pad($aim->A31,8,'0',STR_PAD_LEFT));
								print(str_pad('0',5,'0',STR_PAD_LEFT));
								print(str_pad($aim->A34,1,'1',STR_PAD_LEFT));
								print(str_pad($aim->A35,1,'9',STR_PAD_LEFT));
								print(str_pad(' ',6,' '));
								print(str_pad('0',8,'0',STR_PAD_LEFT));
								print(str_pad(' ',30,' '));
								print(str_pad(' ',8,' '));
	
								if($aim->A46a!='')
									print(str_pad($aim->A46a,3,'0',STR_PAD_LEFT));
								else
									print(str_pad($aim->A46a,3,'9',STR_PAD_LEFT));
	
								if($aim->A46b!='')	
									print(str_pad($aim->A46b,3,'0',STR_PAD_LEFT));
								else
									print(str_pad($aim->A46b,3,'9',STR_PAD_LEFT));
															
								print(str_pad('0',12,'0',STR_PAD_LEFT));
								print(str_pad('0',12,'0',STR_PAD_LEFT));
								print(str_pad($aim->A48a,12,' ',STR_PAD_RIGHT));
								print(str_pad($aim->A48b,12,' ',STR_PAD_RIGHT));
								print(str_pad(' ',5,' '));
								print(str_pad($aim->A50,2,'0',STR_PAD_LEFT));
								
								if($aim->A51a=="undefined")
									print("000");
								else
									print(str_pad($aim->A51a,3,'0',STR_PAD_LEFT));
									
								print("0.000");
								
								if($aim->A10=='70')
									print(str_pad($aim->A53,2,'0',STR_PAD_LEFT));
								else
									print(str_pad('0',2,'0',STR_PAD_LEFT));
																
								print(str_pad($aim->A55,10,'9',STR_PAD_LEFT));
								print(str_pad($aim->A56,8,'0',STR_PAD_LEFT));
								print(str_pad('0',2,'0',STR_PAD_LEFT));
								print(str_pad('0',2,'0',STR_PAD_LEFT));
								print(str_pad('0',3,'0',STR_PAD_LEFT));
								print(str_pad('0',3,'0',STR_PAD_LEFT));
								print(str_pad(' ',9,' ',STR_PAD_LEFT));
								print(str_pad('0',3,'0',STR_PAD_LEFT));
								print(str_pad('0',2,'0',STR_PAD_LEFT));
								print(str_pad('0',5,'0',STR_PAD_LEFT));
								print(str_pad('0',5,'0',STR_PAD_LEFT));
								print(str_pad('0',2,'0',STR_PAD_LEFT));
								print(str_pad('0',2,'0',STR_PAD_LEFT));
								print(str_pad('0',2,'0',STR_PAD_LEFT));
								print(str_pad('0',2,'0',STR_PAD_LEFT));
								print(str_pad($aim->A70,5,' ',STR_PAD_RIGHT));
								print(str_pad(" ",91));
								print( "\r\n");
								$record++;
								
							}
						}
						
						
						foreach($ilr->main as $aim)
						{
							$no_of_aims++;
							print(str_pad($aim->A01,6,'0',STR_PAD_LEFT));
							print(str_pad('0',2,'0',STR_PAD_LEFT));
							print(str_pad($aim->A03,12));
							print($aim->A04);
							print(str_pad($no_of_aims,2,'0',STR_PAD_LEFT));
							print(str_pad($aim->A07,2,'0',STR_PAD_LEFT));
							print(str_pad('1',1,' ',STR_PAD_LEFT));
							print($aim->A09);
							print(str_pad($aim->A10,2,'9',STR_PAD_LEFT));
							print(str_pad($aim->A11a,3,'0',STR_PAD_LEFT));
							print(str_pad($aim->A11b,3,'0',STR_PAD_LEFT));
							print(str_pad($aim->A13,5,'0',STR_PAD_LEFT));
							print(str_pad($aim->A14,2,'0',STR_PAD_LEFT));
							print(str_pad($aim->A15,2,'0',STR_PAD_LEFT));
							print(str_pad('0',2,'0',STR_PAD_LEFT));
							print(str_pad($aim->A17,1,'0',STR_PAD_LEFT));
							print(str_pad($aim->A18,2,'0',STR_PAD_LEFT));
							print(str_pad($aim->A19,1,'0',STR_PAD_LEFT));
							print(str_pad($aim->A20,1,'0',STR_PAD_LEFT));
							print(str_pad($aim->A22,8,'0',STR_PAD_RIGHT));
							print(str_pad($aim->A23,8,' ',STR_PAD_RIGHT));
							print(str_pad($aim->A26,3,'0',STR_PAD_LEFT));
							if($aim->A27!='00000000' && $aim->A27!='dd/mm/yyyy' && $aim->A27!='')
								print(substr($aim->A27,0,2).substr($aim->A27,3,2).substr($aim->A27,6,4));
							else
								print(str_pad($aim->A27,8,'0',STR_PAD_LEFT));
							if($aim->A28!='00000000' && $aim->A28!='dd/mm/yyyy' && $aim->A28!='')
								print(substr($aim->A28,0,2).substr($aim->A28,3,2).substr($aim->A28,6,4));
							else
								print(str_pad($aim->A28,8,'0',STR_PAD_LEFT));
							if($aim->A31!='00000000' && $aim->A31!='dd/mm/yyyy' && $aim->A31!='')
								print(substr($aim->A31,0,2).substr($aim->A31,3,2).substr($aim->A31,6,4));
							else
								print(str_pad($aim->A31,8,'0',STR_PAD_LEFT));
							print(str_pad($aim->A32,5,'0',STR_PAD_LEFT));
							print(str_pad($aim->A34,1,'1',STR_PAD_LEFT));
							print(str_pad($aim->A35,1,'9',STR_PAD_LEFT));
							print(str_pad($aim->A36,6));
							print(str_pad('0',8,'0',STR_PAD_LEFT));
							print(str_pad(substr($aim->A44,0,30),30));
							print(str_pad($aim->A45,8));
	
							if($aim->A46a!='')
								print(str_pad($aim->A46a,3,'0',STR_PAD_LEFT));
							else
								print(str_pad($aim->A46a,3,'9',STR_PAD_LEFT));
	
							if($aim->A46b!='')	
								print(str_pad($aim->A46b,3,'0',STR_PAD_LEFT));
							else
								print(str_pad($aim->A46b,3,'9',STR_PAD_LEFT));
							
							print(str_pad($aim->A47a,12,'0',STR_PAD_LEFT));
							print(str_pad($aim->A47b,12,'0',STR_PAD_LEFT));
							print(str_pad($aim->A48a,12,' ',STR_PAD_RIGHT));
							print(str_pad($aim->A48b,12,' ',STR_PAD_RIGHT));
							print(str_pad($aim->A49,5));
							print(str_pad($aim->A50,2,'0',STR_PAD_LEFT));
							print(str_pad($aim->A51a,3,'0',STR_PAD_LEFT));
							if(strlen($aim->A52)<5)
								print("0.000");
							else
								print(str_pad($aim->A52,5,'0',STR_PAD_LEFT));
							print(str_pad('0',2,'0',STR_PAD_LEFT));
							print(str_pad($aim->A55,10,'9',STR_PAD_LEFT));
							print(str_pad($aim->A56,8,'0',STR_PAD_LEFT));
							print(str_pad($aim->A57,2,'0',STR_PAD_LEFT));
							print(str_pad($aim->A58,2,'0',STR_PAD_LEFT));
							print(str_pad($aim->A59,3,'0',STR_PAD_LEFT));
							print(str_pad($aim->A60,3,'0',STR_PAD_LEFT));
							print(str_pad($aim->A61,9,' ',STR_PAD_LEFT));
							print(str_pad($aim->A62,3,'0',STR_PAD_LEFT));
							print(str_pad($aim->A63,2,'0',STR_PAD_LEFT));
							print(str_pad('0',5,'0',STR_PAD_LEFT));
							print(str_pad('0',5,'0',STR_PAD_LEFT));
							print(str_pad($aim->A66,2,'0',STR_PAD_LEFT));
							print(str_pad($aim->A67,2,'0',STR_PAD_LEFT));
							print(str_pad($aim->A68,2,'0',STR_PAD_LEFT));
							print(str_pad('0',2,'0',STR_PAD_LEFT));
							print(str_pad($aim->A70,5,' ',STR_PAD_RIGHT));
							
							print(str_pad(" ",91));
							print( "\r\n");
							$record++;
						}
		
						$sub = 1;
						foreach($ilr->subaim as $aim)
						{
	
							$no_of_aims++;
							print(str_pad($aim->A01,6,'0',STR_PAD_LEFT));
							print(str_pad('0',2,'0',STR_PAD_LEFT));
							print(str_pad($aim->A03,12));
							print($aim->A04);
							print(str_pad($no_of_aims,2,'0',STR_PAD_LEFT));
							print(str_pad($aim->A07,2,'0',STR_PAD_LEFT));
							print($con1->funding_body);
							print(str_pad($aim->A09,8,' ',STR_PAD_RIGHT));
							print(str_pad($aim->A10,2,'9',STR_PAD_LEFT));
							print(str_pad($aim->A11a,3,'0',STR_PAD_LEFT));
							print(str_pad($aim->A11b,3,'0',STR_PAD_LEFT));
							print(str_pad($aim->A13,5,'0',STR_PAD_LEFT));
							print(str_pad($aim->A14,2,'0',STR_PAD_LEFT));
							print(str_pad($aim->A15,2,'0',STR_PAD_LEFT));
							print(str_pad('0',2,'0',STR_PAD_LEFT));
							print(str_pad($aim->A17,1,'0',STR_PAD_LEFT));
							print(str_pad($aim->A18,2,'0',STR_PAD_LEFT));
							print(str_pad($aim->A19,1,'0',STR_PAD_LEFT));
							print(str_pad($aim->A20,1,'0',STR_PAD_LEFT));
							print(str_pad($aim->A22,8,'0',STR_PAD_RIGHT));
							print(str_pad($aim->A23,8,' ',STR_PAD_RIGHT));
							print(str_pad($aim->A26,3,'0',STR_PAD_LEFT));
							if($aim->A27!='00000000' && $aim->A27!='dd/mm/yyyy' && $aim->A27!='')
								print(substr($aim->A27,0,2).substr($aim->A27,3,2).substr($aim->A27,6,4));
							else
								print(str_pad($aim->A27,8,'0',STR_PAD_LEFT));
							if($aim->A28!='00000000' && $aim->A28!='dd/mm/yyyy' && $aim->A28!='')
								print(substr($aim->A28,0,2).substr($aim->A28,3,2).substr($aim->A28,6,4));
							else
								print(str_pad($aim->A28,8,'0',STR_PAD_LEFT));
							if($aim->A31!='00000000' && $aim->A31!='dd/mm/yyyy' && $aim->A31!='')
								print(substr($aim->A31,0,2).substr($aim->A31,3,2).substr($aim->A31,6,4));
							else
								print(str_pad($aim->A31,8,'0',STR_PAD_LEFT));
							print(str_pad($aim->A32,5,'0',STR_PAD_LEFT));
							print(str_pad($aim->A34,1,'1',STR_PAD_LEFT));
							print(str_pad($aim->A35,1,'9',STR_PAD_LEFT));
							print(str_pad($aim->A36,6));
							print(str_pad('0',8,'0',STR_PAD_LEFT));
							print(str_pad(substr($aim->A44,0,30),30));
							print(str_pad($aim->A45,8));
							
							if($aim->A46a!='')
								print(str_pad($aim->A46a,3,'0',STR_PAD_LEFT));
							else
								print(str_pad($aim->A46a,3,'9',STR_PAD_LEFT));
	
							if($aim->A46b!='')	
								print(str_pad($aim->A46b,3,'0',STR_PAD_LEFT));
							else
								print(str_pad($aim->A46b,3,'9',STR_PAD_LEFT));
							
							print(str_pad($aim->A47a,12,'0',STR_PAD_LEFT));
							print(str_pad($aim->A47b,12,'0',STR_PAD_LEFT));
							print(str_pad($aim->A48a,12,' ',STR_PAD_RIGHT));
							print(str_pad($aim->A48b,12,' ',STR_PAD_RIGHT));
							print(str_pad($aim->A49,5));
							print(str_pad($aim->A50,2,'0',STR_PAD_LEFT));
		
							if($aim->A51a=="undefined")
								print("000");
							else
								print(str_pad($aim->A51a,3,'0',STR_PAD_LEFT));
		
							if(strlen($aim->A52)<5)
								print("0.000");
							else
								print(str_pad($aim->A52,5,'0',STR_PAD_LEFT));
							print(str_pad('0',2,'0',STR_PAD_LEFT));
							print(str_pad($aim->A55,10,'9',STR_PAD_LEFT));
							print(str_pad($aim->A56,8,'0',STR_PAD_LEFT));
							print(str_pad($aim->A57,2,'0',STR_PAD_LEFT));
							print(str_pad($aim->A58,2,'0',STR_PAD_LEFT));
							print(str_pad($aim->A59,3,'0',STR_PAD_LEFT));
							print(str_pad($aim->A60,3,'0',STR_PAD_LEFT));
							print(str_pad($aim->A61,9,' ',STR_PAD_LEFT));
							print(str_pad($aim->A62,3,'0',STR_PAD_LEFT));
							print(str_pad($aim->A63,2,'0',STR_PAD_LEFT));
							print(str_pad('0',5,'0',STR_PAD_LEFT));
							print(str_pad('0',5,'0',STR_PAD_LEFT));
							print(str_pad($aim->A66,2,'0',STR_PAD_LEFT));
							print(str_pad($aim->A67,2,'0',STR_PAD_LEFT));
							print(str_pad($aim->A68,2,'0',STR_PAD_LEFT));
							print(str_pad('0',2,'0',STR_PAD_LEFT));
							print(str_pad($aim->A70,5,' ',STR_PAD_RIGHT));
							
							print(str_pad(" ",91));
							print( "\r\n");
							$record++;
							//$no_of_aims++;
						}
							$sub = $sub + 1;
						}
						$vo->subaims=($sub-1);
					}				
				}
			// writing footer record 
			$record++;	
			print($con1->upin);
			print('00');
			print('ZZZZZZZZZZZZ');
			print('99');
			print('1011');
			print(str_replace("W","LR",$submission));
			print(str_pad($transmission,3,'0',STR_PAD_LEFT));
			print(date("dmY"));
			print(str_pad($record,7,'0',STR_PAD_LEFT));
			print(str_pad(" ",340));
			print("\r\n");
			
		}
		
		//fclose($handle);
		//return $file;
	}
			

	public static function getFilename(PDO $link, $contract_id, $submission, $L01)
	{
		if(is_null($contract_id))
		{
			return null;
		}
		
		$contract = Contract::loadFromDatabase($link, $contract_id);		
		
		$vo = new Ilr2010();
		$vo->learnerinformation = new LearnerInformation();
		$vo->aims[0] = new Aim();
		
		$sql = "SELECT * FROM ilr WHERE submission = '$submission' and contract_id ='$contract_id' and is_active=1;";

		// R06 record level validation starts
		$que = "select count(DISTINCT concat(L01,L03)) from ilr where submission = '$submission' and contract_id='$contract_id' and is_active=1;";
		$no_of_distinct_ilrs = trim(DAO::getSingleValue($link, $que));
		$que = "select count(concat(L01,L03)) from ilr where submission = '$submission' and contract_id = '$contract_id' and is_active=1;";
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
			$file.= $contract->L25. '091000101.';
			$file.= $submission;
		}
		return $file;
	}	
	

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
	
	public static function createILR(PDO $link, $l03)
	{
				$ttg = 0;
		
			$sql = <<<HEREDOC
		SELECT DISTINCT L03,A15,A26,A16 FROM learner INNER JOIN aim ON aim.A03 = learner.L03 where learner.L03 = '$l03';
HEREDOC;

			
		$submission = DAO::getSingleValue($link, "select submission from central.lookup_submission_dates where contract_type = '2' and last_submission_date>=CURDATE() and contract_year = '2010' order by last_submission_date LIMIT 1;");
			
		$st = $link->query($sql);
		if($st) 
		{
			while($row = $st->fetch())
			{
				$l03 = $row['L03'];
				$a15 = $row['A15'];
				$a26 = $row['A26'];
				$a16 = $row['A16'];
				$sql2 = "SELECT learner.*,aim.* FROM learner INNER JOIN aim ON aim.A03 = learner.L03 WHERE learner.L03 = '$l03' AND aim.a15 = '$a15' AND aim.a26 = '$a26' and aim.a16 = '$a16';"; 
				$st2 = $link->query($sql2);
				if($st2) 
				{
					$subaim = '';
					$programmeaim = '';
					$mainaim = '';
					$L05 = DAO::getSingleValue($link,"SELECT count(*) FROM learner INNER JOIN aim ON aim.A03 = learner.L03 WHERE learner.L03 = '$l03' AND aim.a15 = '$a15' AND aim.a26 = '$a26';");	
					$a10flag = DAO::getSingleValue($link,"SELECT count(*) FROM learner INNER JOIN aim ON aim.A03 = learner.L03 WHERE learner.L03 = '$l03' AND aim.a15 = '$a15' AND aim.a26 = '$a26' and aim.a10='46';");	
					while($row2 = $st2->fetch())
					{
						// Look for Train to Gain first
						$a15 = $row2['A15'];
						$a10 = $row2['A10'];
						if($a15=='99' && $a10!='70' || ($a15=='99' && $a10=='70' && $row2['L05']=='01'))
						{
							$ttg++;
							$ilr = "<ilr><learner>";
							$ilr .= "<L01>" . $row2['L01'] . "</L01>"; 	
							$ilr .= "<L02>" . $row2['L02'] . "</L02>";	//	Contract/ Allocation type
							$ilr .= "<L03>" . $row2['L03'] . "</L03>";	//	Learner Reference Number 
							$ilr .= "<L04>" . $row2['L04'] . "</L04>";	//	Data Set Identifier Code. It defines what type of data set it is. 10 in case of learner data set and 30 in case of subsidiary aims data sets.
							//$ilr .= "<L05>" . $row2['L05'] . "</L05>"; 	// 	How many learning aims data sets inner loop
							$ilr .= "<L05>2</L05>"; 	// 	How many learning aims data sets inner loop
							$ilr .= "<L07>" . $row2['L07'] . "</L07>"; 	// 	How many HE data sets. There isn't any in case of Toyota
							$ilr .= "<L08>" . $row2['L08'] . "</L08>";	//	Deletion Flag
							$ilr .= "<L09>" . $row2['L09'] . "</L09>";	
							$ilr .= "<L10>" . $row2['L10'] . "</L10>";	//	Forenames
							$ilr .= "<L11>" . Date::toShort($row2['L11']) . "</L11>"; // Date of Birth
							$ilr .= "<L12>" . $row2['L12'] . "</L12>";	//	Ethnicity
							$ilr .= "<L13>" . $row2['L13'] . "</L13>";	//	Sex
							$ilr .= "<L14>" . $row2['L14'] . "</L14>";	//	Learning difficulties/ disabilities/ health problems
							$ilr .= "<L15>" . $row2['L15'] . "</L15>";	//	Disability			
							$ilr .= "<L16>" . $row2['L16'] . "</L16>";	//	Learning difficulty
							$ilr .= "<L17>" . $row2['L17'] . "</L17>";	//	Home postcode
							$ilr .= "<L18>" . $row2['L18'] . "</L18>";	//	Address line 1
							$ilr .= "<L19>" . $row2['L19'] . "</L19>";	//	Address line 2
							$ilr .= "<L20>" . $row2['L20'] . "</L20>";	//	Address line 3
							$ilr .= "<L21>" . $row2['L21'] . "</L21>";	//	Address line 4
							$ilr .= "<L22>" . $row2['L22'] . "</L22>";		//	Current postcode
							$ilr .= "<L23>" . $row2['L23'] . "</L23>";	//	Home telephone
							$ilr .= "<L24>" . $row2['L24'] . "</L24>";	//	Country of domicile
							$ilr .= "<L25>" . $row2['L25'] . "</L25>";	//	LSC Number of funding LSC
							$ilr .= "<L26>" . $row2['L26'] . "</L26>";	//	National insurance number
							$ilr .= "<L27>" . $row2['L27'] . "</L27>";	//	Restricted use indicator
							$ilr .= "<L28a>" . $row2['L28a'] . "</L28a>";	//	Eligibility for enhanced funding
							$ilr .= "<L28b>" . $row2['L28b'] . "</L28b>";	//	Eligibility for enhanced funding
							$ilr .= "<L29>" . $row2['L29'] . "</L29>";	//	Additional support
							$ilr .= "<L31>" . $row2['L31'] . "</L31>";	//	Additional support cost 
							$ilr .= "<L32>" . $row2['L32'] . "</L32>";	//	Eligibility for disadvatnage uplift
							$ilr .= "<L33>" . $row2['L33'] . "</L33>";	//	Disadvatnage uplift factor
							$ilr .= "<L34a>" . $row2['L34a'] . "</L34a>";	//	Learner support reason
							$ilr .= "<L34b>" . $row2['L34b'] . "</L34b>";	//	Learner support reason
							$ilr .= "<L34c>" . $row2['L34c'] . "</L34c>";	//	Learner support reason
							$ilr .= "<L34d>" . $row2['L34d'] . "</L34d>";	//	Learner support reason
							$ilr .= "<L35>" . $row2['L35'] . "</L35>";	//	Prior attainment level
							$ilr .= "<L36>" . $row2['L36'] . "</L36>";	//	Learner status on last working day
							$ilr .= "<L37>" . $row2['L37'] . "</L37>";	//	Employment status on first day of learning
							$ilr .= "<L39>" . $row2['L39'] . "</L39>";	//	Destination
							$ilr .= "<L40a>" . $row2['L40a'] . "</L40a>";	//	National learner monitoring
							$ilr .= "<L40b>" . $row2['L40b'] . "</L40b>";	//	National learner monitoring
							$ilr .= "<L41a>" . $row2['L41a'] . "</L41a>";	//	Local learner monitoring
							$ilr .= "<L41b>" . $row2['L41b'] . "</L41b>";	//	Local learner monitoring
							$ilr .= "<L42a>" . $row2['L42a'] . "</L42a>";	//	Provider specified learner data
							$ilr .= "<L42b>" . $row2['L42b'] . "</L42b>";	//	Provider specified learner data
							$ilr .= "<L44>" . $row2['L44'] . "</L44>";	//	NES delivery LSC number
							$ilr .= "<L45>" . $row2['L45'] . "</L45>";	//	Unique learner number
							$ilr .= "<L46>" . $row2['L46'] . "</L46>";	
							$ilr .= "<L47>" . $row2['L47'] . "</L47>";	//	Current employment status
							$ilr .= "<L48>" . Date::toShort($row2['L48']) . "</L48>"; // Date employment status changed
							$ilr .= "<L49a>" . $row2['L49a'] . "</L49a>";	//	Current employment status
							$ilr .= "<L49b>" . $row2['L49b'] . "</L49b>";	//	Current employment status
							$ilr .= "<L49c>" . $row2['L49c'] . "</L49c>";	//	Current employment status
							$ilr .= "<L49d>" . $row2['L49d'] . "</L49d>";	//	Current employment status
							$ilr .= "</learner>";
							$ilr .= "<subaims>" . 0 . "</subaims>";	//	Subaims
							$ilr .= "<programmeaim>";
							$ilr .= $this->createAim($row2);
							$ilr .= "</programmeaim>";					
							$ilr .= "<main>";
							$ilr .= $this->createAim($row2);
							$ilr .= "</main>";					
							$ilr .= "</ilr>";

							$user = User::loadFromDatabase($link, $row2['L03']);
							$tr = new TrainingRecord();
							$tr->populate($user, true);
							$tr->contract_id = 3;
							$tr->start_date = $row2['A27'];
							$tr->target_date = $row2['A28'];
							//$tr->closure_date = $row['A31'];
							$tr->status_code = 1;
							$tr->ethnicity = $user->ethnicity;
							$tr->work_experience = 1;
							$tr->l03 = $row2['L03'];
							$tr->save($link); 	

							//$link->query("update aim set processid = {$tr->id} WHERE a03 = '$l03' AND a15 = '$a15' AND a26 = '$a26';"); 
							
							$L01 = $row2['L01'];
							$L03 = $row2['L03'];
							$A09m = $row2['A09'];

							$contract_type = 1;
							$tr_id = $tr->id;
							$is_complete = 0;
							$is_valid = 0;
							$is_approved = 0;
							$is_active = 1;
							$contract_id = 3;
																												
							$query = "insert into ilr (L01, L03, A09, ilr, submission, contract_type, tr_id, is_complete, is_valid, is_approved, is_active, contract_id) VALUES('$L01','$L03','$A09m','$ilr','$submission','$contract_type','$tr_id','$is_complete','$is_valid','$is_approved','$is_active','$contract_id');";
							DAO::execute($link, $query);
						}
						else
						{
							if($row2['A04']=='35' || ($row2['A09']=='ZESF0001'))
							{
								$ilr1 = "<ilr><learner>";
								$ilr1 .= "<L01>" . $row2['L01'] . "</L01>"; 	
								$ilr1 .= "<L02>" . $row2['L02'] . "</L02>";	//	Contract/ Allocation type
								$ilr1 .= "<L03>" . $row2['L03'] . "</L03>";	//	Learner Reference Number 
								$ilr1 .= "<L04>" . $row2['L04'] . "</L04>";	//	Data Set Identifier Code. It defines what type of data set it is. 10 in case of learner data set and 30 in case of subsidiary aims data sets.

								$ilr2 = "<L07>" . $row2['L07'] . "</L07>"; 	// 	How many HE data sets. There isn't any in case of Toyota
								$ilr2 .= "<L08>" . $row2['L08'] . "</L08>";	//	Deletion Flag
								$ilr2 .= "<L09>" . $row2['L09'] . "</L09>";	
								$ilr2 .= "<L10>" . $row2['L10'] . "</L10>";	//	Forenames
								$ilr2 .= "<L11>" . Date::toShort($row2['L11']) . "</L11>"; // Date of Birth
								$ilr2 .= "<L12>" . $row2['L12'] . "</L12>";	//	Ethnicity
								$ilr2 .= "<L13>" . $row2['L13'] . "</L13>";	//	Sex
								$ilr2 .= "<L14>" . $row2['L14'] . "</L14>";	//	Learning difficulties/ disabilities/ health problems
								$ilr2 .= "<L15>" . $row2['L15'] . "</L15>";	//	Disability			
								$ilr2 .= "<L16>" . $row2['L16'] . "</L16>";	//	Learning difficulty
								$ilr2 .= "<L17>" . $row2['L17'] . "</L17>";	//	Home postcode
								$ilr2 .= "<L18>" . $row2['L18'] . "</L18>";	//	Address line 1
								$ilr2 .= "<L19>" . $row2['L19'] . "</L19>";	//	Address line 2
								$ilr2 .= "<L20>" . $row2['L20'] . "</L20>";	//	Address line 3
								$ilr2 .= "<L21>" . $row2['L21'] . "</L21>";	//	Address line 4
								$ilr2 .= "<L22>" . $row2['L22'] . "</L22>";		//	Current postcode
								$ilr2 .= "<L23>" . $row2['L23'] . "</L23>";	//	Home telephone
								$ilr2 .= "<L24>" . $row2['L24'] . "</L24>";	//	Country of domicile
								$ilr2 .= "<L25>" . $row2['L25'] . "</L25>";	//	LSC Number of funding LSC
								$ilr2 .= "<L26>" . $row2['L26'] . "</L26>";	//	National insurance number
								$ilr2 .= "<L27>" . $row2['L27'] . "</L27>";	//	Restricted use indicator
								$ilr2 .= "<L28a>" . $row2['L28a'] . "</L28a>";	//	Eligibility for enhanced funding
								$ilr2 .= "<L28b>" . $row2['L28b'] . "</L28b>";	//	Eligibility for enhanced funding
								$ilr2 .= "<L29>" . $row2['L29'] . "</L29>";	//	Additional support
								$ilr2 .= "<L31>" . $row2['L31'] . "</L31>";	//	Additional support cost 
								$ilr2 .= "<L32>" . $row2['L32'] . "</L32>";	//	Eligibility for disadvatnage uplift
								$ilr2 .= "<L33>" . $row2['L33'] . "</L33>";	//	Disadvatnage uplift factor
								$ilr2 .= "<L34a>" . $row2['L34a'] . "</L34a>";	//	Learner support reason
								$ilr2 .= "<L34b>" . $row2['L34b'] . "</L34b>";	//	Learner support reason
								$ilr2 .= "<L34c>" . $row2['L34c'] . "</L34c>";	//	Learner support reason
								$ilr2 .= "<L34d>" . $row2['L34d'] . "</L34d>";	//	Learner support reason
								$ilr2 .= "<L35>" . $row2['L35'] . "</L35>";	//	Prior attainment level
								$ilr2 .= "<L36>" . $row2['L36'] . "</L36>";	//	Learner status on last working day
								$ilr2 .= "<L37>" . $row2['L37'] . "</L37>";	//	Employment status on first day of learning
								$ilr2 .= "<L39>" . $row2['L39'] . "</L39>";	//	Destination
								$ilr2 .= "<L40a>" . $row2['L40a'] . "</L40a>";	//	National learner monitoring
								$ilr2 .= "<L40b>" . $row2['L40b'] . "</L40b>";	//	National learner monitoring
								$ilr2 .= "<L41a>" . $row2['L41a'] . "</L41a>";	//	Local learner monitoring
								$ilr2 .= "<L41b>" . $row2['L41b'] . "</L41b>";	//	Local learner monitoring
								$ilr2 .= "<L42a>" . $row2['L42a'] . "</L42a>";	//	Provider specified learner data
								$ilr2 .= "<L42b>" . $row2['L42b'] . "</L42b>";	//	Provider specified learner data
								$ilr2 .= "<L44>" . $row2['L44'] . "</L44>";	//	NES delivery LSC number
								$ilr2 .= "<L45>" . $row2['L45'] . "</L45>";	//	Unique learner number
								$ilr2 .= "<L46>" . $row2['L46'] . "</L46>";	
								$ilr2 .= "<L47>" . $row2['L47'] . "</L47>";	//	Current employment status
								$ilr2 .= "<L48>" . Date::toShort($row2['L48']) . "</L48>"; // Date employment status changed
								$ilr2 .= "<L49a>" . $row2['L49a'] . "</L49a>";	//	Current employment status
								$ilr2 .= "<L49b>" . $row2['L49b'] . "</L49b>";	//	Current employment status
								$ilr2 .= "<L49c>" . $row2['L49c'] . "</L49c>";	//	Current employment status
								$ilr2 .= "<L49d>" . $row2['L49d'] . "</L49d>";	//	Current employment status
								$ilr2 .= "</learner>";
								
								$programmeaim = "<programmeaim>" . $this->createAim($row2) . "</programmeaim>";
								$user = User::loadFromDatabase($link, $row2['L03']);
								$tr = new TrainingRecord();
								$tr->populate($user, true);
								$tr->contract_id = 3;
								$tr->start_date = $row2['A27'];
								$tr->target_date = $row2['A28'];
								//$tr->closure_date = $row['A31'];
								$tr->status_code = 1;
								$tr->ethnicity = $user->ethnicity;
								$tr->work_experience = 1;
								$tr->l03 = $row2['L03'];
								$tr->save($link); 	
								
								DAO::execute($link, "update aim set processid = {$tr->id} WHERE a03 = '$l03' AND a15 = '$a15' AND a26 = '$a26';");
							
								$L01 = $row2['L01'];
								$L03 = $row2['L03'];
								$A09m = $row2['A09'];
								$contract_type = 1;
								$tr_id = $tr->id;
								$is_complete = 0;
								$is_valid = 0;
								$is_approved = 0;
								$is_active = 1;
								$contract_id = 3;
							}
							
							if($row2['A10']=='46' || ($row2['A10']=='70' && $row2['A09']!='ZESF0001') || ($row2['A04']=='30' && $row2['A10']=='21' && $row2['A05']=='2'))
							{
								$mainaim = "<main>" . $this->createAim($row2) . "</main>";
								$A09m = $row2['A09'];
								if(!in_array($row2['A09'],$qans))
									$qans[] = $row2['A09'];
							}
							
							if($row2['A04']=='30' && $row2['A09']!='ZESF0001' && ($row2['A10']=='45' || $row2['A10']=='99') || ($row2['A04']=='30' && $row2['A10']=='21' && $row2['A05']!='2'))
							{
								$subaim .=  "<subaim>" . $this->createAim($row2) . "</subaim>";								
								if(!in_array($row2['A09'],$qans))
									$qans[] = $row2['A09'];
							}							
						}
					}
					if($programmeaim!='')
					{
						
						$ilr = $ilr1 . "<L05>" . str_pad($L05,2,'0',STR_PAD_LEFT) . "</L05>" . $ilr2 . $programmeaim . $mainaim . $subaim . "</ilr>";	
						
						$query = "insert into ilr (L01, L03, A09, ilr, submission, contract_type, tr_id, is_complete, is_valid, is_approved, is_active, contract_id) VALUES('$L01','$L03','$A09m','$ilr','$submission','$contract_type','$tr_id','$is_complete','$is_valid','$is_approved','$is_active','$contract_id');";
						DAO::execute($link, $query);
					}					
				}		
			}
		}
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
	public $programmeaim = NULL;

	private $HTML_NEW_LINES = array('<br>', '<br/>', '<br />', '<BR>', '<BR/>', '<BR />', '</p>', '</P>');
}


?>