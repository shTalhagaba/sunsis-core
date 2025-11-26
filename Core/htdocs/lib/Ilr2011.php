<?php

class Ilr2011 extends Entity
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
		
		
		$vo = new Ilr2011();
		$vo->learnerinformation = new LearnerInformation();
		$vo->aims[0] = new Aim2011();

		$c = DAO::getSingleValue($link, "select count(*) from ilr WHERE submission='$submission' and contract_id=$contract_id and tr_id = $tr_id and L03='$L03'");
		if($c==0)
		{
			$vo->programmeaim = new Aim2011();
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
					$vo->learnerinformation->L51=$learner->L51;
					$vo->learnerinformation->L52=$learner->L52;
					
				}

				foreach($ilr->programmeaim as $programmeaim)
				{
					$vo->programmeaim = new Aim2011();
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
					$vo->programmeaim->A69=$programmeaim->A69;
					$vo->programmeaim->A70=$programmeaim->A70;
					$vo->programmeaim->A71=$programmeaim->A71;
					$vo->programmeaim->A72a=$programmeaim->A72a;
					$vo->programmeaim->A72b=$programmeaim->A72b;
				}
				
				foreach($ilr->main as $aim)
				{
					$vo->aims[0] = new Aim2011(); 
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
					$vo->aims[0]->A44b=$aim->A44b;
					$vo->aims[0]->A45b=$aim->A45b;
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
					$vo->aims[0]->A71=$aim->A71;
					$vo->aims[0]->A72a=$aim->A72a;
					$vo->aims[0]->A72b=$aim->A72b;
				}

				$sub = 1;
				foreach($ilr->subaim as $subaim)
				{
					// #120 {0000000011} -kkhan - initial object instantiation for php 5.3 strict mode added
					$vo->aims[$sub] = new Aim2011(); 
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
					$vo->aims[$sub]->A44b = $subaim->A44b;
					$vo->aims[$sub]->A45b = $subaim->A45b;
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
					$vo->aims[$sub]->A71 = $subaim->A71;
					$vo->aims[$sub]->A72a = $subaim->A72a;
					$vo->aims[$sub]->A72b = $subaim->A72b;
					
					
					$sub = $sub + 1;
				}
				$vo->subaims=($sub-1);
				
				
			}
		}
	return $vo;
	}		
			
	
	public static function loadFromXML($xml)
	{
		$vo = new Ilr2011();
		$vo->learnerinformation = new LearnerInformation();
		$vo->aims[0] = new Aim2011();
		
		
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
					$vo->learnerinformation->L51=$learner->L51;
					$vo->learnerinformation->L52=$learner->L52;
					$vo->learnerinformation->subaims=$learner->subaims;									
				}

				
				foreach($ilr->programmeaim as $programmeaim)
				{
					$vo->programmeaim = new Aim2011();
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
					$vo->programmeaim->A69=$programmeaim->A69;
					$vo->programmeaim->A70=$programmeaim->A70;
					$vo->programmeaim->A71=$programmeaim->A71;
					$vo->programmeaim->A72a=$programmeaim->A72a;
					$vo->programmeaim->A72b=$programmeaim->A72b;
				}
				
				
				
				foreach($ilr->main as $aim)
				{
					$vo->aims[0] = new Aim2011(); 
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
					$vo->aims[0]->A44b=$aim->A44b;
					$vo->aims[0]->A45b=$aim->A45b;
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
					$vo->aims[0]->A71=$aim->A71;
					$vo->aims[0]->A72a=$aim->A72a;
					$vo->aims[0]->A72b=$aim->A72b;
				}

				$sub = 1;
				foreach($ilr->subaim as $subaim)
				{
					// #120 {0000000011} -kkhan - initial object instantiation for php 5.3 strict mode added
					$vo->aims[$sub] = new Aim2011(); 
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
					$vo->aims[$sub]->A44b = $subaim->A44b;
					$vo->aims[$sub]->A45b = $subaim->A45b;
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
					$vo->aims[$sub]->A71 = $subaim->A71;
					$vo->aims[$sub]->A72a = $subaim->A72a;
					$vo->aims[$sub]->A72b = $subaim->A72b;
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
			$vo = new Ilr2011();
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
				print($con1->ukprn);
				print('    ');
				print('00');
				print('1112');
				print('ER');
				print(str_pad($transmission,4,'0',STR_PAD_LEFT));
				print(date("dmY"));
				print('A');
				print('1');
				print(str_pad("Perspective Limited",40));
				print(str_pad("Sunesis",30));
				print(str_pad("V3.0",20));
				print(str_pad(" ",3));
				
//				$funding_model = DAO::getSingleValue($link, "select funding_body from contracts where id in ($contracts) limit 0,1");
//				if($funding_model=='1')
//					print(str_replace("W","LR",$submission));
//				else
//					print(str_replace("W","ER",$submission));
				print(str_pad(" ",354));
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
							print(str_pad(str_replace("apos;","'",substr($learner->L09,0,20)),20));
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
							print(str_pad(trim($learner->L17),8));
							
							if(strlen($learner->L18)>30)
								print(substr($learner->L18,0,30));
							else
								print(str_pad($learner->L18,30));
							
							print(str_pad($learner->L19,30));
							print(str_pad($learner->L20,30));
							print(str_pad($learner->L21,30));
							print(str_pad(trim($learner->L22),8));
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
							print(str_pad(' ',12,'0',STR_PAD_LEFT));
							print(str_pad(' ',12,'0',STR_PAD_LEFT));
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
							print(str_pad($learner->L51,100,' ',STR_PAD_LEFT));
							print(str_pad($learner->L52,1,'9',STR_PAD_LEFT));
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
								print(str_pad('0',2,'0',STR_PAD_LEFT));
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
								print(str_pad(trim($aim->A23),8,' ',STR_PAD_RIGHT));
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
	
								print(str_pad(substr($ilr->main->A44,0,30),30));
								print(str_pad(trim($ilr->main->A45),8));
	
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
								print(str_pad($aim->A71,2,'0',STR_PAD_LEFT));
								print(str_pad($aim->A72a,12,' ',STR_PAD_RIGHT));
								print(str_pad($aim->A72b,12,' ',STR_PAD_RIGHT));
								
								print(str_pad(" ",166));
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
	//						print(str_pad($aim->A06,2,'0',STR_PAD_LEFT));
							print(str_pad($aim->A07,2,'0',STR_PAD_LEFT));
							print(str_pad($con1->funding_body,1,' ',STR_PAD_LEFT));
							print($aim->A09);
							print(str_pad($aim->A10,2,'9',STR_PAD_LEFT));
							print(str_pad($aim->A11a,3,'0',STR_PAD_LEFT));
							print(str_pad($aim->A11b,3,'0',STR_PAD_LEFT));
	//						print("000000");
							print(str_pad($aim->A13,5,'0',STR_PAD_LEFT));
							print(str_pad('0',2,'0',STR_PAD_LEFT));
							print(str_pad($aim->A15,2,'0',STR_PAD_LEFT));
							print(str_pad($aim->A16,2,'0',STR_PAD_LEFT));
							print(str_pad($aim->A17,1,'0',STR_PAD_LEFT));
							print(str_pad($aim->A18,2,'0',STR_PAD_LEFT));
							print(str_pad($aim->A19,1,'0',STR_PAD_LEFT));
							print(str_pad($aim->A20,1,'0',STR_PAD_LEFT));
							print(str_pad($aim->A22,8,'0',STR_PAD_RIGHT));
							print(str_pad(trim($aim->A23),8,' ',STR_PAD_RIGHT));
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
							print(str_pad(trim($aim->A45),8));
	
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

							if(DB_NAME=='am_jmldolman')
							{
								$jmlA09 = $aim->A09;
								$nvl = DAO::getSingleValue($link, "select nvl from ebs where qual_code = '$jmlA09'");
								$a48a = DAO::getSingleValue($link, "select owning_organisation from ebs where qual_code = '$jmlA09' and nvl = '$nvl'");
								$a48b = DAO::getSingleValue($link, "select uio_id from ebs where qual_code = '$jmlA09' and nvl = '$nvl'");
								print(str_pad($a48a,12,' ',STR_PAD_RIGHT));
								print(str_pad($a48b,12,' ',STR_PAD_RIGHT));
							}
							else
							{
								print(str_pad($aim->A48a,12,' ',STR_PAD_RIGHT));
								print(str_pad($aim->A48b,12,' ',STR_PAD_RIGHT));
							}
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
							print(str_pad($aim->A71,2,'0',STR_PAD_LEFT));
							print(str_pad($aim->A72a,12,' ',STR_PAD_RIGHT));
							print(str_pad($aim->A72b,12,' ',STR_PAD_RIGHT));
							
							
							print(str_pad(" ",166));
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
	//						print(str_pad($aim->A06,2,'0',STR_PAD_LEFT));
							print(str_pad($aim->A07,2,'0',STR_PAD_LEFT));
							print($con1->funding_body);
							print(str_pad($aim->A09,8,' ',STR_PAD_RIGHT));
							print(str_pad($aim->A10,2,'9',STR_PAD_LEFT));
							print(str_pad($aim->A11a,3,'0',STR_PAD_LEFT));
							print(str_pad($aim->A11b,3,'0',STR_PAD_LEFT));
	//						print("000000");
							print(str_pad($aim->A13,5,'0',STR_PAD_LEFT));
							print(str_pad('0',2,'0',STR_PAD_LEFT));
							print(str_pad($aim->A15,2,'0',STR_PAD_LEFT));
							print(str_pad($aim->A16,2,'0',STR_PAD_LEFT));
							print(str_pad($aim->A17,1,'0',STR_PAD_LEFT));
							print(str_pad($aim->A18,2,'0',STR_PAD_LEFT));
							print(str_pad($aim->A19,1,'0',STR_PAD_LEFT));
							print(str_pad($aim->A20,1,'0',STR_PAD_LEFT));
							print(str_pad($aim->A22,8,'0',STR_PAD_RIGHT));
							print(str_pad(trim($aim->A23),8,' ',STR_PAD_RIGHT));
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
							print(str_pad(substr($ilr->main->A44,0,30),30));
							print(str_pad($aim->A45,8));
							
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

							if(DB_NAME=='am_jmldolman')
							{
								$jmlA09 = $aim->A09;
								$a48a = DAO::getSingleValue($link, "select owning_organisation from ebs where qual_code = '$jmlA09' and nvl = '$nvl'");
								$a48b = DAO::getSingleValue($link, "select uio_id from ebs where qual_code = '$jmlA09' and nvl = '$nvl'");
								print(str_pad($a48a,12,' ',STR_PAD_RIGHT));
								print(str_pad($a48b,12,' ',STR_PAD_RIGHT));
							}
							else
							{
								print(str_pad($aim->A48a,12,' ',STR_PAD_RIGHT));
								print(str_pad($aim->A48b,12,' ',STR_PAD_RIGHT));
							}
							
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
							print(str_pad($ilr->main->A71,2,'0',STR_PAD_LEFT));
							print(str_pad($aim->A72a,12,' ',STR_PAD_RIGHT));
							print(str_pad($aim->A72b,12,' ',STR_PAD_RIGHT));
							
							print(str_pad(" ",166));
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
			print($con1->ukprn);
			print('ZZZZ');
			print('99');
			print('1112');
			
			if($funding_model == 1)
				print("LR");
			else
				print("ER");
			
			print(str_pad($transmission,4,'0',STR_PAD_LEFT));
			print(date("dmY"));
			print(str_pad($record,7,'0',STR_PAD_LEFT));
			print(str_pad(" ",442));
			print("\r\n");
		}
		else // Learner Responsive
		{
			$l03 = '';
			$vo = new Ilr2011();
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
				print($con1->ukprn);
				print('    ');
				print('00');
				print('1112');
				print('LR');
				print(str_pad($transmission,4,'0',STR_PAD_LEFT));
				print(date("dmY"));
				print('A');
				print('1');
				print(str_pad("Perspective Limited",40));
				print(str_pad("Sunesis",30));
				print(str_pad("2.0",20));
				print(str_pad(" ",3));
				print(str_pad(" ",354));
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
							print(str_pad(str_replace(" ","",$learner->L23),15));
							print(str_pad($learner->L24,2));
							print(str_pad($learner->L26,9,' ',STR_PAD_LEFT));
							print(str_pad($learner->L27,1,'0',STR_PAD_LEFT));
							print(str_pad($learner->L28,2,'0',STR_PAD_LEFT));
							print(str_pad($learner->L29,2,'0',STR_PAD_LEFT));
							print(str_pad($learner->L31,6,'0',STR_PAD_LEFT));
							print(str_pad($learner->L32,2,'0',STR_PAD_LEFT));
							if($learner->L33=='')
								print('0.0000');
							else
								print(str_pad($learner->L33,6,'0',STR_PAD_RIGHT));
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
							print(str_pad($learner->L51,100,' ',STR_PAD_LEFT));
							print(str_pad($learner->L52,1,'9',STR_PAD_LEFT));
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
								if($aim->A04=='1')
									print("35");
								else
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
								print(str_pad($aim->A71,2,'0',STR_PAD_LEFT));
								print(str_pad($aim->A72a,12,' ',STR_PAD_RIGHT));
								print(str_pad($aim->A72b,12,' ',STR_PAD_RIGHT));
								print(str_pad(" ",166));
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
							if($aim->A04=='2')
								print("30");
							else
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
							if(strlen($aim->A52)<5 || $aim->A52=="00000" || $aim->A52=="")
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
							print(str_pad($aim->A71,2,'0',STR_PAD_LEFT));
							print(str_pad($aim->A72a,12,' ',STR_PAD_RIGHT));
							print(str_pad($aim->A72b,12,' ',STR_PAD_RIGHT));
							
							print(str_pad(" ",166));
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
							print("30");
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
		
							if(strlen($aim->A52)<5 || $aim->A52=="00000" || $aim->A52=="")
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
							print(str_pad($ilr->main->A71,2,'0',STR_PAD_LEFT));
							print(str_pad(' ',12,' ',STR_PAD_RIGHT));
							print(str_pad(' ',12,' ',STR_PAD_RIGHT));
							
							print(str_pad(" ",166));
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
			print($con1->ukprn);
			print('ZZZZ');
			print('99');
			print('1112');
			if($funding_model == 1)
				print("LR");
			else
				print("ER");
			
			print(str_pad($transmission,4,'0',STR_PAD_LEFT));
			print(date("dmY"));
			print(str_pad($record,7,'0',STR_PAD_LEFT));
			print(str_pad(" ",442));
			print("\r\n");
			

		
		}
		
		//fclose($handle);
		//return $file;
	}
			

	public static function generateStream4(PDO $link, $submission, $contracts, $con1, $L25, $transmission)
	{
		if(is_null($contracts) || is_null($submission))
		{
			return null;
		}

		$l03 = '';
		$vo = new Ilr2011();
		$vo->learnerinformation = new LearnerInformation();
		$vo->aims[0] = new Aim();
		
		$no_of_aims = 0;
		$funding_model = DAO::getSingleValue($link, "select funding_body from contracts where id in ($contracts) limit 0,1");

		$sqlouter = "SELECT distinct l03 FROM ilr left join contracts on contracts.id = ilr.contract_id WHERE submission = '$submission' and contract_id in ($contracts) and is_active=1 and locate('<L08>Y</L08>',ilr)=0 ORDER BY l03, substr(ilr,locate('<L09>',ilr)+5,(locate('</L09>',ilr)-locate('<L09>',ilr)-5)), tr_id";
		$stouter = $link->query($sqlouter);
		if($stouter)
		{
			// writing header information in data stream file
			print('<?xml version="1.0" encoding="utf-8"?>');
			print("\r\n");
			print('<Message xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns="http://www.theia.org.uk/ILR/2011-12/1" >');
			print("\r\n\t");
			print("<Header>");
			print("\r\n\t\t");
			print("<CollectionDetails>");
			print("\r\n\t\t\t");
			print("<Collection>ILR</Collection>\r\n\t\t\t");
			print("<Year>1112</Year>\r\n\t\t\t");
			print("<FilePreparationDate>" .	date("Y-m-d") . "</FilePreparationDate>\r\n\t\t");
			print("</CollectionDetails>\r\n\t\t");
			print("<Source>\r\n\t\t\t");
			print("<ProtectiveMarking>PROTECT-PRIVATE</ProtectiveMarking>\r\n\t\t\t");
			print("<UKPRN>" . $con1->ukprn . "</UKPRN>\r\n\t\t\t");
			print("<TransmissionNumber>" . $transmission . "</TransmissionNumber>\r\n\t\t\t");
			print("<TransmissionType>A</TransmissionType>\r\n\t\t\t");
			print("<SoftwareSupplier>Perspective UK Limited</SoftwareSupplier>\r\n\t\t\t");
			print("<SoftwarePackage>Sunesis</SoftwarePackage>\r\n\t\t\t");
			print("<Release>V 3.1</Release>\r\n\t\t\t");
			print("<SerialNo>1</SerialNo>\r\n\t\t");
			print("<DateTime>" . date('Y-m-d') . "T" . date('H:i:s') . "</DateTime>");
			print("</Source>\r\n\t");
			print("</Header>\r\n\t");
			print("<LearningProvider>\r\n\t\t");
			print("<UKPRN>" . $con1->ukprn . "</UKPRN>\r\n\t\t");
			print("<UPIN>" . $con1->upin . "</UPIN>\r\n\t");
			print("</LearningProvider>");
			
			while($rowouter = $stouter->fetch())
			{	

				$l03 = $rowouter['l03'];
				// DBS & CES Array Building Starts
				$sql = "SELECT * FROM ilr left join contracts on contracts.id = ilr.contract_id WHERE submission = '$submission' and contract_id in ($contracts) and l03 = '$l03' and is_active=1 and locate('<L08>Y</L08>',ilr)=0 ORDER BY tr_id desc";
				$st = $link->query($sql);	
				if($st)
				{
					$dbs = array();
					$ces = array();
					$l39 = '';
					while($row = $st->fetch())
					{	
						$ilr = $row['ilr']; 
						$ilr = str_replace("&", "a", $ilr);
						//$ilr = new SimpleXMLElement($ilr);
						$ilr = XML::loadSimpleXML($ilr);
						$l39 = ($l39=='95')?$l39:$ilr->learner->L39;
						$a27dbs = '"' . $ilr->main->A27 . '"';
						$dbs[$a27dbs] = "" . $ilr->main->A66;

						if(trim($ilr->main->A44b)!='')
						{
							$a4445 = '"' . trim($ilr->main->A44b) . '-' . trim($ilr->main->A45b) . '-' . $ilr->learner->L47 . '-' . $ilr->learner->L37 . '"';
							$ces[$a4445] = '"' . $ilr->main->A27 . '"';
						}
						else
						{
							$a4445 = '"' . trim($ilr->main->A44) . '-' . trim($ilr->main->A45) . '-' . $ilr->learner->L47 . '-' . $ilr->learner->L37 . '"';
							$ces[$a4445] = '"' . $ilr->main->A27 . '"';
						}


						foreach($ilr->subaim as $aim)
						{
							$a27dbs = '"' . $aim->A27 . '"'; 	
							$dbs[$a27dbs] = "" . $aim->A66;
						}
					}
					asort($dbs);
					asort($ces);
				}
				// DBS Array Building Finisahed
				
				$record=0;
				$no_of_aims=0;
				$sql = "SELECT * FROM ilr left join contracts on contracts.id = ilr.contract_id WHERE submission = '$submission' and contract_id in ($contracts) and l03 = '$l03' and is_active=1 and locate('<L08>Y</L08>',ilr)=0 ORDER BY tr_id desc";
				$st = $link->query($sql);	
				if($st)
				{
					while($row = $st->fetch())
					{	
						$vo->id = $row['L03'];
						$ilr = $row['ilr']; 
						$ilr = str_replace("&", "a", $ilr);
						$record++;		
						//$ilr = new SimpleXMLElement($ilr);
						$ilr = XML::loadSimpleXML($ilr);
			
						foreach($ilr->learner as $learner)
						{
							if($record==1)
							{
								
								print("\r\n\t<Learner>\r\n\t\t");
								print("<LearnRefNumber>" . $learner->L03 . "</LearnRefNumber>\r\n\t\t");
								print("<ULN>" . str_pad($learner->L45,10,'9',STR_PAD_LEFT) . "</ULN>\r\n\t\t");
								print("<FamilyName>" . trim(str_replace("apos;","'",substr($learner->L09,0,20))) . "</FamilyName>\r\n\t\t");							
								print("<GivenNames>" . trim($learner->L10) . "</GivenNames>\r\n\t\t");
								print("<DateOfBirth>" . Date::toMySQL($learner->L11) . "</DateOfBirth>\r\n\t\t");
								print("<Ethnicity>" . str_pad($learner->L12,2,'9',STR_PAD_LEFT) . "</Ethnicity>\r\n\t\t");
								print("<Sex>" . $learner->L13 . "</Sex>\r\n\t\t");
								print("<LLDDInd>" . str_pad($learner->L14,1,'9',STR_PAD_LEFT) . "</LLDDInd>\r\n\t\t");
								if($learner->L26!='')
									print("<NINumber>" . $learner->L26 . "</NINumber>\r\n\t\t");
								print("<Domicile>" . $learner->L24 . "</Domicile>\r\n\t\t");
								
								if($learner->L35!='')
									print("<PriorAttain>" . $learner->L35 . "</PriorAttain>\r\n\t\t");
								else
									print("<PriorAttain>98</PriorAttain>\r\n\t\t");
								
								if($l39=='')
									print("<Dest>95</Dest>\r\n\t\t");
								else
									print("<Dest>" . $l39 . "</Dest>\r\n\t\t");

								print("<LearnerContact>\r\n\t\t\t");
								print("<LocType>2</LocType>\r\n\t\t\t");
								print("<ContType>1</ContType>\r\n\t\t\t");
								print("<PostCode>" . trim($learner->L17) . "</PostCode>\r\n\t\t");
								print("</LearnerContact>\r\n\t\t");
								print("<LearnerContact>\r\n\t\t\t");
								print("<LocType>1</LocType>\r\n\t\t\t");
								print("<ContType>2</ContType>\r\n\t\t\t");
								print("<PostAdd>");
								print("\r\n\t\t\t\t<AddLine1>" . substr(trim($learner->L18),0,30) . "</AddLine1>");
								if(trim($learner->L19)!="")
									print("\r\n\t\t\t\t<AddLine2>" . trim($learner->L19) . "</AddLine2>");
								if(trim($learner->L20)!="")
									print("\r\n\t\t\t\t<AddLine3>" . trim($learner->L20) . "</AddLine3>");
								if(trim($learner->L21)!="")
									print("\r\n\t\t\t\t<AddLine4>" . substr(trim($learner->L21),0,30) . "</AddLine4>");
								print("\r\n\t\t\t</PostAdd>\r\n\t\t");
								print("</LearnerContact>\r\n\t\t");

								if(trim($learner->L22)!="")
								{
									print("<LearnerContact>\r\n\t\t\t");
									print("<LocType>2</LocType>\r\n\t\t\t");
									print("<ContType>2</ContType>\r\n\t\t\t");
									print("<PostCode>" . trim($learner->L22) . "</PostCode>");
									print("\r\n\t\t</LearnerContact>\r\n\t\t");
								}

								if(trim($learner->L23)!="")
								{
									print("<LearnerContact>\r\n\t\t\t");
									print("<LocType>3</LocType>\r\n\t\t\t");
									print("<ContType>2</ContType>\r\n\t\t\t");
									print("<TelNumber>" . trim(str_replace(" ","",$learner->L23)) . "</TelNumber>");
									print("\r\n\t\t</LearnerContact>");
								}
	
								if(trim($learner->L51)!="")
								{
									print("\r\n\t\t<LearnerContact>\r\n\t\t\t");
									print("<LocType>4</LocType>\r\n\t\t\t");
									print("<ContType>2</ContType>\r\n\t\t\t");
									print("<Email>" . trim($learner->L51) . "</Email>");							
									print("\r\n\t\t</LearnerContact>");
								}
								
								if($learner->L27!='' && $learner->L27!='9')
								{
									print("\r\n\t\t<ContactPreference>");
									if($learner->L27=='1')
									{
										print("\r\n\t\t\t<ContPrefType>RUI</ContPrefType>");
										print("\r\n\t\t\t<ContPrefCode>1</ContPrefCode>");
										print("\r\n\t\t</ContactPreference>");
										print("\r\n\t\t<ContactPreference>");
										print("\r\n\t\t\t<ContPrefType>RUI</ContPrefType>");
										print("\r\n\t\t\t<ContPrefCode>2</ContPrefCode>");
									}
									if($learner->L27=='2')
									{
										print("\r\n\t\t<ContPrefType>RUI</ContPrefType>");
										print("\r\n\t\t\t<ContPrefCode>3</ContPrefCode>");
									}
									if($learner->L27=='3')
									{
										print("\r\n\t\t<ContPrefType>RUI</ContPrefType>");
										print("\r\n\t\t\t<ContPrefCode>1</ContPrefCode>");
									}
									if($learner->L27=='4')
									{
										print("\r\n\t\t<ContPrefType>RUI</ContPrefType>");
										print("\r\n\t\t\t<ContPrefCode>2</ContPrefCode>");
									}
									print("\r\n\t\t</ContactPreference>");
								}
								
								if($learner->L14=='1')
								{
									if($learner->L15!='' && $learner->L15!='98')
									{
										print("\r\n\t\t<LLDDandHealthProblem>");
										print("\r\n\t\t\t<LLDDType>DS</LLDDType>");
										print("\r\n\t\t\t<LLDDCode>" . $learner->L15 . "</LLDDCode>");
										print("\r\n\t\t</LLDDandHealthProblem>");
									}
									if($learner->L16!='' && $learner->L16!='98')
									{
										print("\r\n\t\t<LLDDandHealthProblem>");
										print("\r\n\t\t\t<LLDDType>LD</LLDDType>");
										print("\r\n\t\t\t<LLDDCode>" . $learner->L16 . "</LLDDCode>");
										print("\r\n\t\t</LLDDandHealthProblem>");
									}
								}


								if($learner->L28=='12')
								{
									print("\r\n\t\t<LearnerFAM>");
									print("\r\n\t\t\t<LearnFAMType>EFE</LearnFAMType>");
									print("\r\n\t\t\t<LearnFAMCode>" . $learner->L28 . "</LearnFAMCode>");
									print("\r\n\t\t</LearnerFAM>");
								}

								if($learner->L34a!='' && $learner->L34a!='99')
								{
									print("\r\n\t\t<LearnerFAM>");
									print("\r\n\t\t\t<LearnFAMType>LSR</LearnFAMType>");
									print("\r\n\t\t\t<LearnFAMCode>" . $learner->L34a . "</LearnFAMCode>");
									print("\r\n\t\t</LearnerFAM>");									
								}
								if($learner->L34b!='' && $learner->L34b!='99')
								{
									print("\r\n\t\t<LearnerFAM>");
									print("\r\n\t\t\t<LearnFAMType>LSR</LearnFAMType>");
									print("\r\n\t\t\t<LearnFAMCode>" . $learner->L34b . "</LearnFAMCode>");
									print("\r\n\t\t</LearnerFAM>");									
								}
								if($learner->L34c!='' && $learner->L34c!='99')
								{
									print("\r\n\t\t<LearnerFAM>");
									print("\r\n\t\t\t<LearnFAMType>LSR</LearnFAMType>");
									print("\r\n\t\t\t<LearnFAMCode>" . $learner->L34c . "</LearnFAMCode>");
									print("\r\n\t\t</LearnerFAM>");									
								}
								if($learner->L34d!='' && $learner->L34d!='99')
								{
									print("\r\n\t\t<LearnerFAM>");
									print("\r\n\t\t\t<LearnFAMType>LSR</LearnFAMType>");
									print("\r\n\t\t\t<LearnFAMCode>" . $learner->L34d . "</LearnFAMCode>");
									print("\r\n\t\t</LearnerFAM>");									
								}
								
								if(trim($learner->L42a)!="")
								{
									print("\r\n\t\t<ProviderSpecLearnerMonitoring>");
									print("\r\n\t\t\t<LearnOccurCode>A</LearnOccurCode>");
									print("\r\n\t\t\t<ProvSpecLearnMon>" . $learner->L42a . "</ProvSpecLearnMon>");
									print("\r\n\t\t</ProviderSpecLearnerMonitoring>");
								}
								if(trim($learner->L42b)!="")
								{
									print("\r\n\t\t<ProviderSpecLearnerMonitoring>");
									print("\r\n\t\t\t<LearnOccurCode>B</LearnOccurCode>");
									print("\r\n\t\t\t<ProvSpecLearnMon>" . $learner->L42b . "</ProvSpecLearnMon>");
									print("\r\n\t\t</ProviderSpecLearnerMonitoring>");
								}
		
								foreach($ilr->programmeaim as $aim)
								{
									if( ($aim->A15!='99' && $aim->A15!='') || $aim->A10=='70')
									{
										$a27 = new Date($aim->A27);
									}
									else
									{
										foreach($ilr->main as $aim)
										{
											$a27 = new Date($aim->A27);
										}
									}
								}
								$cd = new Date('01/08/2011');
								
								if($ilr->main->A10!='21')
								{
									// FDL
									print("\r\n\t\t<LearnerEmploymentStatus>\r\n\t\t\t");
									print("<EmpStatType>FDL</EmpStatType>\r\n\t\t\t");
									if($learner->L37=='1' || $learner->L37=='6' || $learner->L37=='7')
										print("<EmpStatCode>1</EmpStatCode>\r\n\t\t\t");
									elseif($learner->L37=='2' || $learner->L37=='3' || $learner->L37=='4' || $learner->L37=='5' || $learner->L37=='8' || $learner->L37=='9' || $learner->L37=='10' || $learner->L37=='11' || $learner->L37=='12' || $learner->L37=='13' || $learner->L37=='14' || $learner->L37=='15' || $learner->L37=='16')
										print("<EmpStatCode>4</EmpStatCode>\r\n\t\t\t");
									elseif($learner->L37=='17')
										print("<EmpStatCode>6</EmpStatCode>\r\n\t\t\t");
									else
										print("<EmpStatCode>98</EmpStatCode>\r\n\t\t\t");

									print("<DateEmpStatApp>2001-08-01</DateEmpStatApp>\r\n\t\t");

									if(trim($ilr->main->A44)!="")
										print("<EmpId>" . substr(trim("" . $ilr->main->A44),0,9) . "</EmpId>");

									print("<WorkLocPostCode>" . substr($ilr->main->A45,0,9) . "</WorkLocPostCode>");
									if($learner->L37=='3')
									{
										print("<EmploymentStatusMonitoring>\r\n\t\t\t\t");
										print("<ESMType>RFU</ESMType>\r\n\t\t\t\t");
										print("<ESMCode>1</ESMCode>\r\n\t\t\t");
										print("</EmploymentStatusMonitoring>\r\n\t\t\t");
									}
									elseif($learner->L37=='4')
									{
										print("<EmploymentStatusMonitoring>\r\n\t\t\t\t");
										print("<ESMType>RFU</ESMType>\r\n\t\t\t\t");
										print("<ESMCode>2</ESMCode>\r\n\t\t\t");
										print("</EmploymentStatusMonitoring>\r\n\t\t\t");
									}
									elseif($learner->L37=='6')
									{
										print("<EmploymentStatusMonitoring>\r\n\t\t\t\t");
										print("<ESMType>EII</ESMType>\r\n\t\t\t\t");
										print("<ESMCode>1</ESMCode>\r\n\t\t\t");
										print("</EmploymentStatusMonitoring>\r\n\t\t\t");
									}
									elseif($learner->L37=='7')
									{
										print("<EmploymentStatusMonitoring>\r\n\t\t\t\t");
										print("<ESMType>EII</ESMType>\r\n\t\t\t\t");
										print("<ESMCode>2</ESMCode>\r\n\t\t\t");
										print("</EmploymentStatusMonitoring>\r\n\t\t\t");
									}
									elseif($learner->L37=='8')
									{
										print("<EmploymentStatusMonitoring>\r\n\t\t\t\t");
										print("<ESMType>RFU</ESMType>\r\n\t\t\t\t");
										print("<ESMCode>1</ESMCode>\r\n\t\t\t");
										print("</EmploymentStatusMonitoring>\r\n\t\t\t");

										print("<EmploymentStatusMonitoring>\r\n\t\t\t\t");
										print("<ESMType>BSI</ESMType>\r\n\t\t\t\t");
										print("<ESMCode>1</ESMCode>\r\n\t\t\t");
										print("</EmploymentStatusMonitoring>\r\n\t\t\t");
									}
									elseif($learner->L37=='9')
									{
										print("<EmploymentStatusMonitoring>\r\n\t\t\t\t");
										print("<ESMType>RFU</ESMType>\r\n\t\t\t\t");
										print("<ESMCode>1</ESMCode>\r\n\t\t\t");
										print("</EmploymentStatusMonitoring>\r\n\t\t\t");

										print("<EmploymentStatusMonitoring>\r\n\t\t\t\t");
										print("<ESMType>BSI</ESMType>\r\n\t\t\t\t");
										print("<ESMCode>2</ESMCode>\r\n\t\t\t");
										print("</EmploymentStatusMonitoring>\r\n\t\t\t");
									}
									elseif($learner->L37=='10')
									{
										print("<EmploymentStatusMonitoring>\r\n\t\t\t\t");
										print("<ESMType>RFU</ESMType>\r\n\t\t\t\t");
										print("<ESMCode>1</ESMCode>\r\n\t\t\t");
										print("</EmploymentStatusMonitoring>\r\n\t\t\t");
									}
									elseif($learner->L37=='11')
									{
										print("<EmploymentStatusMonitoring>\r\n\t\t\t\t");
										print("<ESMType>RFU</ESMType>\r\n\t\t\t\t");
										print("<ESMCode>2</ESMCode>\r\n\t\t\t");
										print("</EmploymentStatusMonitoring>\r\n\t\t\t");

										print("<EmploymentStatusMonitoring>\r\n\t\t\t\t");
										print("<ESMType>BSI</ESMType>\r\n\t\t\t\t");
										print("<ESMCode>1</ESMCode>\r\n\t\t\t");
										print("</EmploymentStatusMonitoring>\r\n\t\t\t");
									}
									elseif($learner->L37=='12')
									{
										print("<EmploymentStatusMonitoring>\r\n\t\t\t\t");
										print("<ESMType>RFU</ESMType>\r\n\t\t\t\t");
										print("<ESMCode>2</ESMCode>\r\n\t\t\t");
										print("</EmploymentStatusMonitoring>\r\n\t\t\t");

										print("<EmploymentStatusMonitoring>\r\n\t\t\t\t");
										print("<ESMType>BSI</ESMType>\r\n\t\t\t\t");
										print("<ESMCode>2</ESMCode>\r\n\t\t\t");
										print("</EmploymentStatusMonitoring>\r\n\t\t\t");
									}
									elseif($learner->L37=='13')
									{
										print("<EmploymentStatusMonitoring>\r\n\t\t\t\t");
										print("<ESMType>RFU</ESMType>\r\n\t\t\t\t");
										print("<ESMCode>2</ESMCode>\r\n\t\t\t");
										print("</EmploymentStatusMonitoring>\r\n\t\t\t");
									}
									elseif($learner->L37=='14')
									{
										print("<EmploymentStatusMonitoring>\r\n\t\t\t\t");
										print("<ESMType>BSI</ESMType>\r\n\t\t\t\t");
										print("<ESMCode>1</ESMCode>\r\n\t\t\t");
										print("</EmploymentStatusMonitoring>\r\n\t\t\t");
									}
									elseif($learner->L37=='15')
									{
										print("<EmploymentStatusMonitoring>\r\n\t\t\t\t");
										print("<ESMType>BSI</ESMType>\r\n\t\t\t\t");
										print("<ESMCode>2</ESMCode>\r\n\t\t\t");
										print("</EmploymentStatusMonitoring>\r\n\t\t\t");
									}


									print("</LearnerEmploymentStatus>\r\n\t\t");
								}

		
								// DBS
								if($ilr->main->A66!='')
								{
									print("<LearnerEmploymentStatus>\r\n\t\t\t");
									print("<EmpStatType>DBS</EmpStatType>\r\n\t\t\t");
									print("<EmpStatCode>"  . $ilr->main->A66 . "</EmpStatCode>\r\n\t\t\t");
									$index = 0;
									foreach($dbs as $key=>$value)
									{
										$dbs1 = new Date(str_replace('"','',$key));
										$dbs1->subtractDays(1);
										print("<DateEmpStatApp>" . Date::toMySQL($dbs1->getDate()) . "</DateEmpStatApp>\r\n\t\t");
										break;
									}

									if($ilr->main->A67=='1')
									{
										print("<EmploymentStatusMonitoring>\r\n\t\t\t\t");
										print("<ESMType>LOU</ESMType>\r\n\t\t\t\t");
										print("<ESMCode>1</ESMCode>\r\n\t\t\t");
										print("</EmploymentStatusMonitoring>\r\n\t\t\t");
									}
									elseif($ilr->main->A67=='2')
									{
										print("<EmploymentStatusMonitoring>\r\n\t\t\t\t");
										print("<ESMType>LOU</ESMType>\r\n\t\t\t\t");
										print("<ESMCode>2</ESMCode>\r\n\t\t\t");
										print("</EmploymentStatusMonitoring>\r\n\t\t\t");
									}
									elseif($ilr->main->A67=='3')
									{
										print("<EmploymentStatusMonitoring>\r\n\t\t\t\t");
										print("<ESMType>LOU</ESMType>\r\n\t\t\t\t");
										print("<ESMCode>3</ESMCode>\r\n\t\t\t");
										print("</EmploymentStatusMonitoring>\r\n\t\t\t");
									}
									elseif($ilr->main->A67=='4')
									{
										print("<EmploymentStatusMonitoring>\r\n\t\t\t\t");
										print("<ESMType>LOU</ESMType>\r\n\t\t\t\t");
										print("<ESMCode>4</ESMCode>\r\n\t\t\t");
										print("</EmploymentStatusMonitoring>\r\n\t\t\t");
									}
									elseif($ilr->main->A67=='5')
									{
										print("<EmploymentStatusMonitoring>\r\n\t\t\t\t");
										print("<ESMType>LOU</ESMType>\r\n\t\t\t\t");
										print("<ESMCode>5</ESMCode>\r\n\t\t\t");
										print("</EmploymentStatusMonitoring>\r\n\t\t\t");
									}

									print("</LearnerEmploymentStatus>\r\n\t\t");
								}

								if($funding_model!='1')
								{
									// CES
									$index = 0;
									foreach($ces as $key=>$value)
									{
										print("<LearnerEmploymentStatus>");
										print("\r\n\t\t\t<EmpStatType>CES</EmpStatType>");
										$cesarray = explode("-", $key);

										$l47 = str_replace('"','',$cesarray[2]);

										if($ilr->main->A44b!='' && $ilr->main->A44b!='000000000' && $ilr->main->A44b!='999999999' && trim($ilr->main->A45b==''))
										{
											print("\r\n\t\t\t<EmpStatCode>1</EmpStatCode>");
											$a27 = new Date(str_replace('"','',$value));
											print("\r\n\t\t\t<DateEmpStatApp>" . Date::toMySQL($a27->getDate()) . "</DateEmpStatApp>");

											if(str_replace('"','',$cesarray[0])!='')
												print("\r\n\t\t\t<EmpId>" . str_replace('"','',$cesarray[0]) . "</EmpId>");

											if(trim($cesarray[1])!='')
												print("\r\n\t\t\t<WorkLocPostCode>" . trim($cesarray[1]) . "</WorkLocPostCode>");
											print("\r\n\t\t\t<EmploymentStatusMonitoring>");
											print("\r\n\t\t\t\t<ESMType>EII</ESMType>");
											print("\r\n\t\t\t\t<ESMCode>1</ESMCode>");
											print("\r\n\t\t\t</EmploymentStatusMonitoring>");
											print("\r\n\t\t</LearnerEmploymentStatus>\r\n\t\t");
										}
										else
										{
											if($l47=='1' || $l47=='6' || $l47=='7')
											{
												print("\r\n\t\t\t<EmpStatCode>1</EmpStatCode>");
											}
											elseif($l47=='2' || $l47=='3' || $l47=='4' || $l47=='5' || $l47=='6' || $l47=='7' || $l47=='8' || $l47=='9' || $l47=='10' || $l47=='11' || $l47=='12' || $l47=='13' || $l47=='14' || $l47=='15' || $l47=='16')
											{
												print("\r\n\t\t\t<EmpStatCode>4</EmpStatCode>");
											}
											elseif($l47=='17')
											{
												print("\r\n\t\t\t<EmpStatCode>6</EmpStatCode>");
											}
											elseif($l47=='98' || $l47=='')
											{
												print("\r\n\t\t\t<EmpStatCode>98</EmpStatCode>");
											}

											$a27 = new Date(str_replace('"','',$value));
											print("\r\n\t\t\t<DateEmpStatApp>" . Date::toMySQL($a27->getDate()) . "</DateEmpStatApp>");

											if(str_replace('"','',$cesarray[0])!='')
												print("\r\n\t\t\t<EmpId>" . str_replace('"','',$cesarray[0]) . "</EmpId>");

											if(trim($cesarray[1])!='')
												print("\r\n\t\t\t<WorkLocPostCode>" . trim($cesarray[1]) . "</WorkLocPostCode>");

											if($l47=='3')
											{
												print("\r\n\t\t\t<EmploymentStatusMonitoring>");
												print("\r\n\t\t\t\t<ESMType>RFU</ESMType>");
												print("\r\n\t\t\t\t<ESMCode>1</ESMCode>");
												print("\r\n\t\t\t</EmploymentStatusMonitoring>");
											}
											elseif($l47=='4')
											{
												print("\r\n\t\t\t<EmploymentStatusMonitoring>");
												print("\r\n\t\t\t\t<ESMType>RFU</ESMType>");
												print("\r\n\t\t\t\t<ESMCode>2</ESMCode>");
												print("\r\n\t\t\t</EmploymentStatusMonitoring>");
											}
											elseif($l47=='6' || $l47=='1')
											{
												print("\r\n\t\t\t<EmploymentStatusMonitoring>");
												print("\r\n\t\t\t\t<ESMType>EII</ESMType>");
												print("\r\n\t\t\t\t<ESMCode>1</ESMCode>");
												print("\r\n\t\t\t</EmploymentStatusMonitoring>");
											}
											elseif($l47=='7')
											{
												print("\r\n\t\t\t<EmploymentStatusMonitoring>");
												print("\r\n\t\t\t\t<ESMType>EII</ESMType>");
												print("\r\n\t\t\t\t<ESMCode>2</ESMCode>");
												print("\r\n\t\t\t</EmploymentStatusMonitoring>");
											}
											elseif($l47=='8')
											{
												print("\r\n\t\t\t<EmploymentStatusMonitoring>");
												print("\r\n\t\t\t\t<ESMType>RFU</ESMType>");
												print("\r\n\t\t\t\t<ESMCode>1</ESMCode>");
												print("\r\n\t\t\t</EmploymentStatusMonitoring>");

												print("\r\n\t\t\t<EmploymentStatusMonitoring>");
												print("\r\n\t\t\t\t<ESMType>BSI</ESMType>");
												print("\r\n\t\t\t\t<ESMCode>1</ESMCode>");
												print("\r\n\t\t\t</EmploymentStatusMonitoring>");
											}
											elseif($l47=='9')
											{
												print("\r\n\t\t\t<EmploymentStatusMonitoring>");
												print("\r\n\t\t\t\t<ESMType>RFU</ESMType>");
												print("\r\n\t\t\t\t<ESMCode>1</ESMCode>");
												print("\r\n\t\t\t</EmploymentStatusMonitoring>");

												print("\r\n\t\t\t<EmploymentStatusMonitoring>");
												print("\r\n\t\t\t\t<ESMType>BSI</ESMType>");
												print("\r\n\t\t\t\t<ESMCode>2</ESMCode>");
												print("\r\n\t\t\t</EmploymentStatusMonitoring>");
											}
											elseif($l47=='10')
											{
												print("\r\n\t\t\t<EmploymentStatusMonitoring>");
												print("\r\n\t\t\t\t<ESMType>RFU</ESMType>");
												print("\r\n\t\t\t\t<ESMCode>1</ESMCode>");
												print("\r\n\t\t\t</EmploymentStatusMonitoring>");
											}
											elseif($l47=='11')
											{
												print("\r\n\t\t\t<EmploymentStatusMonitoring>");
												print("\r\n\t\t\t\t<ESMType>RFU</ESMType>");
												print("\r\n\t\t\t\t<ESMCode>2</ESMCode>");
												print("\r\n\t\t\t</EmploymentStatusMonitoring>");

												print("\r\n\t\t\t<EmploymentStatusMonitoring>");
												print("\r\n\t\t\t\t<ESMType>BSI</ESMType>");
												print("\r\n\t\t\t\t<ESMCode>1</ESMCode>");
												print("\r\n\t\t\t</EmploymentStatusMonitoring>");
											}
											elseif($l47=='12')
											{
												print("\r\n\t\t\t<EmploymentStatusMonitoring>");
												print("\r\n\t\t\t\t<ESMType>RFU</ESMType>");
												print("\r\n\t\t\t\t<ESMCode>2</ESMCode>");
												print("\r\n\t\t\t</EmploymentStatusMonitoring>");

												print("\r\n\t\t\t<EmploymentStatusMonitoring>");
												print("\r\n\t\t\t\t<ESMType>BSI</ESMType>");
												print("\r\n\t\t\t\t<ESMCode>2</ESMCode>");
												print("\r\n\t\t\t</EmploymentStatusMonitoring>");
											}
											elseif($l47=='13')
											{
												print("\r\n\t\t\t<EmploymentStatusMonitoring>");
												print("\r\n\t\t\t\t<ESMType>RFU</ESMType>");
												print("\r\n\t\t\t\t<ESMCode>2</ESMCode>");
												print("\r\n\t\t\t</EmploymentStatusMonitoring>");
											}
											elseif($l47=='14')
											{
												print("\r\n\t\t\t<EmploymentStatusMonitoring>");
												print("\r\n\t\t\t\t<ESMType>BSI</ESMType>");
												print("\r\n\t\t\t\t<ESMCode>1</ESMCode>");
												print("\r\n\t\t\t</EmploymentStatusMonitoring>");
											}
											elseif($l47=='15')
											{
												print("\r\n\t\t\t<EmploymentStatusMonitoring>");
												print("\r\n\t\t\t\t<ESMType>BSI</ESMType>");
												print("\r\n\t\t\t\t<ESMCode>2</ESMCode>");
												print("\r\n\t\t\t</EmploymentStatusMonitoring>");
											}

											print("\r\n\t\t</LearnerEmploymentStatus>\r\n\t\t");
										}
										break;
									}
								}


								// Remaining DBS
								$index = 0;
								foreach($dbs as $key=>$value)
								{
								 	if($index>0)
								 	{
										$dbs1 = new Date(str_replace('"','',$key));
										$dbs1->subtractDays(1);
										if($value!='')
										{
											print("<LearnerEmploymentStatus>\r\n\t\t\t");
											print("<EmpStatType>DBS</EmpStatType>\r\n\t\t\t");
											print("<EmpStatCode>"  . $value . "</EmpStatCode>\r\n\t\t\t");
											print("<DateEmpStatApp>" . Date::toMySQL($dbs1->getDate()) . "</DateEmpStatApp>\r\n\t\t");
											print("</LearnerEmploymentStatus>\r\n\t\t");
										}
								 	}
								 	$index++;
								}

								if($funding_model!='1')
								{
									// Remaining CES
									$index = 0;
									foreach($ces as $key=>$value)
									{
										if($index>0)
										{
											print("<LearnerEmploymentStatus>");
											print("\r\n\t\t\t<EmpStatType>CES</EmpStatType>");
											$cesarray = explode("-", $key);

											$l47 = str_replace('"','',$cesarray[2]);

											if($ilr->main->A44!='' && $ilr->main->A44!='000000000' && $ilr->main->A44!='999999999' && trim($ilr->main->A45==''))
											{
												print("\r\n\t\t\t<EmpStatCode>1</EmpStatCode>");
												$a27 = new Date(str_replace('"','',$value));
												print("\r\n\t\t\t<DateEmpStatApp>" . Date::toMySQL($a27->getDate()) . "</DateEmpStatApp>");
												if(trim($cesarray[0])!='""')
													print("\r\n\t\t\t<EmpId>" . str_replace('"','',$cesarray[0]) . "</EmpId>");
												if(trim($cesarray[1])!='')
													print("\r\n\t\t\t<WorkLocPostCode>" . trim($cesarray[1]) . "</WorkLocPostCode>");
												print("\r\n\t\t\t<EmploymentStatusMonitoring>");
												print("\r\n\t\t\t\t<ESMType>EII</ESMType>");
												print("\r\n\t\t\t\t<ESMCode>1</ESMCode>");
												print("\r\n\t\t\t</EmploymentStatusMonitoring>");
												print("\r\n\t\t</LearnerEmploymentStatus>\r\n\t\t");
											}
											else
											{
												if($l47=='1' || $l47=='6' || $l47=='7')
												{
													print("\r\n\t\t\t<EmpStatCode>1</EmpStatCode>");
												}
												elseif($l47=='2' || $l47=='3' || $l47=='4' || $l47=='5' || $l47=='6' || $l47=='7' || $l47=='8' || $l47=='9' || $l47=='10' || $l47=='11' || $l47=='12' || $l47=='13' || $l47=='14' || $l47=='15' || $l47=='16')
												{
													print("\r\n\t\t\t<EmpStatCode>4</EmpStatCode>");
												}
												elseif($l47=='17')
												{
													print("\r\n\t\t\t<EmpStatCode>6</EmpStatCode>");
												}
												elseif($l47=='98' || $l47=='')
												{
													print("\r\n\t\t\t<EmpStatCode>98</EmpStatCode>");
												}

												$a27 = new Date(str_replace('"','',$value));
												print("\r\n\t\t\t<DateEmpStatApp>" . Date::toMySQL($a27->getDate()) . "</DateEmpStatApp>");
												if(trim($cesarray[0])!='""')
													print("\r\n\t\t\t<EmpId>" . str_replace('"','',$cesarray[0]) . "</EmpId>");
												if(trim($cesarray[1])!='')
													print("\r\n\t\t\t<WorkLocPostCode>" . trim($cesarray[1]) . "</WorkLocPostCode>");

												if($l47=='3')
												{
													print("\r\n\t\t\t<EmploymentStatusMonitoring>");
													print("\r\n\t\t\t\t<ESMType>RFU</ESMType>");
													print("\r\n\t\t\t\t<ESMCode>1</ESMCode>");
													print("\r\n\t\t\t</EmploymentStatusMonitoring>");
												}
												elseif($l47=='4')
												{
													print("\r\n\t\t\t<EmploymentStatusMonitoring>");
													print("\r\n\t\t\t\t<ESMType>RFU</ESMType>");
													print("\r\n\t\t\t\t<ESMCode>2</ESMCode>");
													print("\r\n\t\t\t</EmploymentStatusMonitoring>");
												}
												elseif($l47=='6' || $l47=='1')
												{
													print("\r\n\t\t\t<EmploymentStatusMonitoring>");
													print("\r\n\t\t\t\t<ESMType>EII</ESMType>");
													print("\r\n\t\t\t\t<ESMCode>1</ESMCode>");
													print("\r\n\t\t\t</EmploymentStatusMonitoring>");
												}
												elseif($l47=='7')
												{
													print("\r\n\t\t\t<EmploymentStatusMonitoring>");
													print("\r\n\t\t\t\t<ESMType>EII</ESMType>");
													print("\r\n\t\t\t\t<ESMCode>2</ESMCode>");
													print("\r\n\t\t\t</EmploymentStatusMonitoring>");
												}
												elseif($l47=='8')
												{
													print("\r\n\t\t\t<EmploymentStatusMonitoring>");
													print("\r\n\t\t\t\t<ESMType>RFU</ESMType>");
													print("\r\n\t\t\t\t<ESMCode>1</ESMCode>");
													print("\r\n\t\t\t</EmploymentStatusMonitoring>");

													print("\r\n\t\t\t<EmploymentStatusMonitoring>");
													print("\r\n\t\t\t\t<ESMType>BSI</ESMType>");
													print("\r\n\t\t\t\t<ESMCode>1</ESMCode>");
													print("\r\n\t\t\t</EmploymentStatusMonitoring>");
												}
												elseif($l47=='9')
												{
													print("\r\n\t\t\t<EmploymentStatusMonitoring>");
													print("\r\n\t\t\t\t<ESMType>RFU</ESMType>");
													print("\r\n\t\t\t\t<ESMCode>1</ESMCode>");
													print("\r\n\t\t\t</EmploymentStatusMonitoring>");

													print("\r\n\t\t\t<EmploymentStatusMonitoring>");
													print("\r\n\t\t\t\t<ESMType>BSI</ESMType>");
													print("\r\n\t\t\t\t<ESMCode>2</ESMCode>");
													print("\r\n\t\t\t</EmploymentStatusMonitoring>");
												}
												elseif($l47=='10')
												{
													print("\r\n\t\t\t<EmploymentStatusMonitoring>");
													print("\r\n\t\t\t\t<ESMType>RFU</ESMType>");
													print("\r\n\t\t\t\t<ESMCode>1</ESMCode>");
													print("\r\n\t\t\t</EmploymentStatusMonitoring>");
												}
												elseif($l47=='11')
												{
													print("\r\n\t\t\t<EmploymentStatusMonitoring>");
													print("\r\n\t\t\t\t<ESMType>RFU</ESMType>");
													print("\r\n\t\t\t\t<ESMCode>2</ESMCode>");
													print("\r\n\t\t\t</EmploymentStatusMonitoring>");

													print("\r\n\t\t\t<EmploymentStatusMonitoring>");
													print("\r\n\t\t\t\t<ESMType>BSI</ESMType>");
													print("\r\n\t\t\t\t<ESMCode>1</ESMCode>");
													print("\r\n\t\t\t</EmploymentStatusMonitoring>");
												}
												elseif($l47=='12')
												{
													print("\r\n\t\t\t<EmploymentStatusMonitoring>");
													print("\r\n\t\t\t\t<ESMType>RFU</ESMType>");
													print("\r\n\t\t\t\t<ESMCode>2</ESMCode>");
													print("\r\n\t\t\t</EmploymentStatusMonitoring>");

													print("\r\n\t\t\t<EmploymentStatusMonitoring>");
													print("\r\n\t\t\t\t<ESMType>BSI</ESMType>");
													print("\r\n\t\t\t\t<ESMCode>2</ESMCode>");
													print("\r\n\t\t\t</EmploymentStatusMonitoring>");
												}
												elseif($l47=='13')
												{
													print("\r\n\t\t\t<EmploymentStatusMonitoring>");
													print("\r\n\t\t\t\t<ESMType>RFU</ESMType>");
													print("\r\n\t\t\t\t<ESMCode>2</ESMCode>");
													print("\r\n\t\t\t</EmploymentStatusMonitoring>");
												}
												elseif($l47=='14')
												{
													print("\r\n\t\t\t<EmploymentStatusMonitoring>");
													print("\r\n\t\t\t\t<ESMType>BSI</ESMType>");
													print("\r\n\t\t\t\t<ESMCode>1</ESMCode>");
													print("\r\n\t\t\t</EmploymentStatusMonitoring>");
												}
												elseif($l47=='15')
												{
													print("\r\n\t\t\t<EmploymentStatusMonitoring>");
													print("\r\n\t\t\t\t<ESMType>BSI</ESMType>");
													print("\r\n\t\t\t\t<ESMCode>2</ESMCode>");
													print("\r\n\t\t\t</EmploymentStatusMonitoring>");
												}

												print("\r\n\t\t</LearnerEmploymentStatus>\r\n\t\t");
											}
										}
										$index++;
									}
								}
							}
						}
						$l03 = $row['L03'];
						if($learner->L08!="Y")
						{
							foreach($ilr->programmeaim as $aim)
							{
								if( ($aim->A15!='99' && $aim->A15!=''))
								{
									$no_of_aims++;
		
									print("<LearningDelivery>\r\n\t\t\t");
									if($aim->A10=='70')
										print("<LearnAimRef>ZESF0001</LearnAimRef>\r\n\t\t\t");
									else
										print("<LearnAimRef>" . $aim->A09 . "</LearnAimRef>\r\n\t\t\t");

									if($aim->A10=='70')
										print("<AimType>4</AimType>\r\n\t\t\t");
									else
										print("<AimType>1</AimType>\r\n\t\t\t");

									print("<AimSeqNumber>" . $no_of_aims . "</AimSeqNumber>\r\n\t\t\t");
									print("<LearnStartDate>" . Date::toMySQL($aim->A27) . "</LearnStartDate>\r\n\t\t\t");				
									print("<LearnPlanEndDate>" . Date::toMySQL($aim->A28) . "</LearnPlanEndDate>\r\n\t\t\t");
									if(trim($aim->A10)=='')
									print("<FundModel>99</FundModel>\r\n\t\t\t");
										else
									print("<FundModel>" . $aim->A10 . "</FundModel>\r\n\t\t\t");
									if($aim->A10!='70'  && $aim->A10!='99' && $aim->A10!='21')
										if(trim($aim->A64)!="")
											print("<PlanGrpHrs>" . $aim->A64 . "</PlanGrpHrs>\r\n\t\t\t");
										else
											print("<PlanGrpHrs>0</PlanGrpHrs>\r\n\t\t\t");

									if($aim->A10!='70'  && $aim->A10!='99' && $aim->A10!='21')
										if(trim($aim->A65)!="")
											print("<PlanOneToOneHrs>" . $aim->A65 . "</PlanOneToOneHrs>\r\n\t\t\t");
										else
											print("<PlanOneToOneHrs>0</PlanOneToOneHrs>\r\n\t\t\t");
									
									if($aim->A15!='')
										print("<ProgType>" . $aim->A15 . "</ProgType>\r\n\t\t\t");
									else
										print("<ProgType>99</ProgType>\r\n\t\t\t");
									
									if($aim->A26!='' && $aim->A26!='0')
										print("<FworkCode>" . $aim->A26 . "</FworkCode>\r\n\t\t\t");
									
									if($aim->A16!='' && $aim->A16!='0')
										print("<ProgEntRoute>" . $aim->A16 . "</ProgEntRoute>\r\n\t\t\t");


									if(trim($aim->A23)!='')
										print("<DelLocPostCode>" . trim($aim->A23) . "</DelLocPostCode>\r\n\t\t\t");

									if($aim->A10=='70')
									{
										print("<ESFProjDosNumber>" . $ilr->main->A61 . "</ESFProjDosNumber>\r\n\t\t\t");
										print("<ESFLocProjNumber>" . $ilr->main->A62 . "</ESFLocProjNumber>\r\n\t\t\t");
									}

									if($aim->A70!='')
										print("<ContOrgCode>" . $aim->A70 . "</ContOrgCode>\r\n\t\t\t");

									if($aim->A34=='3' || $aim->A34=='4' || $aim->A34=='5')
										print("<CompStatus>" . "3" . "</CompStatus>\r\n\t\t\t");
									elseif($aim->A34!='')
										print("<CompStatus>" . $aim->A34 . "</CompStatus>\r\n\t\t\t");
									
									if($aim->A31!='' && $aim->A31!='00000000' && $aim->A34!='dd/mm/yyyy' && $aim->A34!='01-01-1970')
										print("<LearnActEndDate>" . Date::toMySQL($aim->A31) . "</LearnActEndDate>\r\n\t\t\t");
									
									if(($aim->A34=='3' || $aim->A34=='4' || $aim->A34=='5') && $aim->A10!='99')
										if(trim($aim->A50)!="" && trim($aim->A50)!="96" && trim($aim->A50)!="30" && trim($aim->A50)!="31" && trim($aim->A50)!="32" && trim($aim->A50)!="33" && trim($aim->A50)!="34" && trim($aim->A50)!="35")
											print("<WithdrawReason>" . $aim->A50 . "</WithdrawReason>\r\n\t\t\t");
										else
											print("<WithdrawReason>98</WithdrawReason>\r\n\t\t\t");
												
									if($aim->A34!='1' && trim($aim->A35)!='')
										print("<OutcomeInd>" . $aim->A35 . "</OutcomeInd>\r\n\t\t\t");
		
									if($aim->A40!="" && $aim->A40!="dd/mm/yyyy" && $aim->A40!="00000000" && $aim->A10!='99' && $aim->A10!='81' && $aim->A10!='70')
										print("<AchDate>" . Date::toMySQL($aim->A40) . "</AchDate>\r\n\t\t\t");

									if($aim->A50=='30')
									{
										print("<ActProgRoute>1</ActProgRoute>");
									}
									elseif($aim->A50=='31')
									{
										print("<ActProgRoute>2</ActProgRoute>");
									}
									elseif($aim->A50=='32' || $aim->A50=='5')
									{
										print("<ActProgRoute>3</ActProgRoute>");
									}
									elseif($aim->A50=='33')
									{
										print("<ActProgRoute>5</ActProgRoute>");
									}
									elseif($aim->A50=='34')
									{
										print("<ActProgRoute>4</ActProgRoute>");
									}
									elseif($aim->A50=='35')
									{
										print("<ActProgRoute>6</ActProgRoute>");
									}

									if($ilr->main->A63!='' && $ilr->main->A63!='99')
									{
										print("<LearningDeliveryFAM>\r\n\t\t\t\t");
										print("<LearnDelFAMType>NSA</LearnDelFAMType>\r\n\t\t\t\t");
										print("<LearnDelFAMCode>" . $ilr->main->A63 . "</LearnDelFAMCode>\r\n\t\t\t");
										print("</LearningDeliveryFAM>\r\n\t\t");
									}

									if($aim->A11a!='')
									{
										print("<LearningDeliveryFAM>\r\n\t\t\t\t");
										print("<LearnDelFAMType>SOF</LearnDelFAMType>\r\n\t\t\t\t");
										print("<LearnDelFAMCode>" . $aim->A11a . "</LearnDelFAMCode>\r\n\t\t\t");
										print("</LearningDeliveryFAM>\r\n\t\t");
									}

									if($aim->A46a!='' && $aim->A46a!='999')
									{
										print("<LearningDeliveryFAM>\r\n\t\t\t\t");
										print("<LearnDelFAMType>LDM</LearnDelFAMType>\r\n\t\t\t\t");
										print("<LearnDelFAMCode>" . $aim->A46a . "</LearnDelFAMCode>\r\n\t\t\t");
										print("</LearningDeliveryFAM>\r\n\t\t");
									}

									if($aim->A69!='' && $aim->A46a!='99' && $aim->A69!='99' && $aim->A69!='0')
									{
										print("<LearningDeliveryFAM>\r\n\t\t\t\t");
										print("<LearnDelFAMType>EEF</LearnDelFAMType>\r\n\t\t\t\t");
										print("<LearnDelFAMCode>" . $aim->A69 . "</LearnDelFAMCode>\r\n\t\t\t");
										print("</LearningDeliveryFAM>\r\n\t\t");
									}
									
									print("</LearningDelivery>\r\n\t\t");
									
								}
							}
							
							foreach($ilr->main as $aim)
							{
								$no_of_aims++;
								print("<LearningDelivery>\r\n\t\t\t");
								print("<LearnAimRef>" . $aim->A09 . "</LearnAimRef>\r\n\t\t\t");

								if($aim->A15=='99')
									print("<AimType>4</AimType>\r\n\t\t\t");
								else
									print("<AimType>2</AimType>\r\n\t\t\t");
								
								print("<AimSeqNumber>" . $no_of_aims . "</AimSeqNumber>\r\n\t\t\t");
								print("<LearnStartDate>" . Date::toMySQL($aim->A27) . "</LearnStartDate>\r\n\t\t\t");				
								print("<LearnPlanEndDate>" . Date::toMySQL($aim->A28) . "</LearnPlanEndDate>\r\n\t\t\t");
								if($aim->A10=='46')
									$a10 = '45';
								else
									$a10 = $aim->A10;

								if(trim($aim->A10)=='')
									print("<FundModel>99</FundModel>\r\n\t\t\t");
								else
									print("<FundModel>" . $a10 . "</FundModel>\r\n\t\t\t");

								if( ($aim->A15=='99' || $aim->A15=='') && $aim->A10!='70'  && $aim->A10!='99' && $aim->A10!='21')
								{
									if(trim($aim->A64)!="")
										print("<PlanGrpHrs>" . $aim->A64 . "</PlanGrpHrs>\r\n\t\t\t");
									else
										print("<PlanGrpHrs>0</PlanGrpHrs>\r\n\t\t\t");
									
									if(trim($aim->A65)!="")
										print("<PlanOneToOneHrs>" . $aim->A65 . "</PlanOneToOneHrs>\r\n\t\t\t");
									else
										print("<PlanOneToOneHrs>0</PlanOneToOneHrs>\r\n\t\t\t");
								}
								
								if(trim($aim->A59!=""))
									print("<PlanCredVal>" . (int)$aim->A59 . "</PlanCredVal>\r\n\t\t\t");
								
								if($aim->A15!='')	
									print("<ProgType>" . $aim->A15 . "</ProgType>\r\n\t\t\t");
								else
									print("<ProgType>99</ProgType>\r\n\t\t\t");

								if( ($aim->A15=='99' || $aim->A15=='') && $aim->A10!='70')
								{
									if($aim->A16!='' && $aim->A16!='0')
										print("<ProgEntRoute>" . $aim->A16 . "</ProgEntRoute>\r\n\t\t\t");
									elseif($ilr->programmeaim->A16!='' && $ilr->programmeaim->A16!='0')
										print("<ProgEntRoute>" . $ilr->programmeaim->A16 . "</ProgEntRoute>\r\n\t\t\t");
								}
								
								if($aim->A26!='' && $aim->A26!='0')
									print("<FworkCode>" . $aim->A26 . "</FworkCode>\r\n\t\t\t");
								
								if(trim($aim->A18)!="" && trim($aim->A18)!="0" && $aim->A10!='70')
									print("<MainDelMeth>" . $aim->A18 . "</MainDelMeth>\r\n\t\t\t");

								if(trim($aim->A22)!="" && trim($aim->A22)!="0" && $aim->A10!='70')
									print("<PartnerUKPRN>" . $aim->A22 . "</PartnerUKPRN>\r\n\t\t\t");
									
								if(trim($aim->A23)!='')
									print("<DelLocPostCode>" . trim($aim->A23) . "</DelLocPostCode>\r\n\t\t\t");
								
								if(trim($aim->A51a)!="" && $aim->A10!='99' && $aim->A10!='70')
									print("<PropFundRemain>" . $aim->A51a . "</PropFundRemain>\r\n\t\t\t");

								if($aim->A10=='70')
								{
									print("<ESFProjDosNumber>" . $aim->A61 . "</ESFProjDosNumber>\r\n\t\t\t");
									print("<ESFLocProjNumber>" . $aim->A62 . "</ESFLocProjNumber>\r\n\t\t\t");
								}

								if( ($aim->A15=='99' || $aim->A15=='' || $aim->A15=='19') && $aim->A70!='')
								{
									print("<ContOrgCode>" . $aim->A70 . "</ContOrgCode>\r\n\t\t\t");
								}
									
								if($aim->A34=='3' || $aim->A34=='4' || $aim->A34=='5')
									print("<CompStatus>" . "3" . "</CompStatus>\r\n\t\t\t");
								elseif($aim->A34!='')
									print("<CompStatus>" . $aim->A34 . "</CompStatus>\r\n\t\t\t");
		
								if($aim->A31!='' && $aim->A31!='00000000' && $aim->A34!='dd/mm/yyyy' && $aim->A34!='01-01-1970')
									print("<LearnActEndDate>" . Date::toMySQL($aim->A31) . "</LearnActEndDate>\r\n\t\t\t");
		
								if(($aim->A34=='3' || $aim->A34=='4' || $aim->A34=='5') && $aim->A10!='99')
									if(trim($aim->A50)!="" && trim($aim->A50)!="96" && trim($aim->A50)!="30" && trim($aim->A50)!="31" && trim($aim->A50)!="32" && trim($aim->A50)!="33" && trim($aim->A50)!="34" && trim($aim->A50)!="35")
										print("<WithdrawReason>" . $aim->A50 . "</WithdrawReason>\r\n\t\t\t");
									else
										print("<WithdrawReason>98</WithdrawReason>\r\n\t\t\t");
																		
								if($aim->A34!='1' && trim($aim->A35)!='')
									print("<OutcomeInd>" . $aim->A35 . "</OutcomeInd>\r\n\t\t\t");
		
								if($aim->A40!="" && $aim->A40!="dd/mm/yyyy" && $aim->A40!="00000000" && $aim->A10!='99' && $aim->A10!='81' && $aim->A10!='70')
									print("<AchDate>" . Date::toMySQL($aim->A40) . "</AchDate>\r\n\t\t\t");
		
								if($aim->A60!="")
									print("<CredAch>" . (int)$aim->A60 . "</CredAch>\r\n\t\t\t");
								else
									print("<CredAch>0</CredAch>\r\n\t\t\t");


								if($aim->A15=='99' && $ilr->main->A63!='99' && $ilr->main->A63!='')
									if($aim->A63!='')
									{
										print("<LearningDeliveryFAM>\r\n\t\t\t\t");
										print("<LearnDelFAMType>NSA</LearnDelFAMType>\r\n\t\t\t\t");
										print("<LearnDelFAMCode>" . $ilr->main->A63 . "</LearnDelFAMCode>\r\n\t\t\t");
										print("</LearningDeliveryFAM>\r\n\t\t");
									}

								if( ($aim->A15=='99' || $aim->A15=='' || $aim->A15=='19') && $aim->A11a!='' && $aim->A11a!='999')
								{
									print("<LearningDeliveryFAM>\r\n\t\t\t\t");
									print("<LearnDelFAMType>SOF</LearnDelFAMType>\r\n\t\t\t\t");
									print("<LearnDelFAMCode>" . $aim->A11a . "</LearnDelFAMCode>\r\n\t\t\t");
									print("</LearningDeliveryFAM>\r\n\t\t");
								}

								if($aim->A46a!='' && $aim->A46a!='999')
								{
									print("<LearningDeliveryFAM>\r\n\t\t\t\t");
									print("<LearnDelFAMType>LDM</LearnDelFAMType>\r\n\t\t\t\t");
									print("<LearnDelFAMCode>" . $aim->A46a . "</LearnDelFAMCode>\r\n\t\t\t");
									print("</LearningDeliveryFAM>\r\n\t\t");
								}
								
								if(trim($aim->A71)!="" && $aim->A71!='99')
								{
									$mainA71 = $aim->A71;	
									print("<LearningDeliveryFAM>\r\n\t\t\t\t");
									print("<LearnDelFAMType>FFI</LearnDelFAMType>\r\n\t\t\t\t");
									print("<LearnDelFAMCode>" . $aim->A71 . "</LearnDelFAMCode>\r\n\t\t");
									print("</LearningDeliveryFAM>");
								}

								if(trim($aim->A53)=='11' || $aim->A53=='13')
								{
									$mainA71 = $aim->A71;
									print("<LearningDeliveryFAM>\r\n\t\t\t\t");
									print("<LearnDelFAMType>ALN</LearnDelFAMType>\r\n\t\t\t\t");
									print("<LearnDelFAMCode>1</LearnDelFAMCode>\r\n\t\t");
									print("</LearningDeliveryFAM>");
								}

								if(trim($aim->A53)=='12' || $aim->A53=='13')
								{
									$mainA71 = $aim->A71;
									print("<LearningDeliveryFAM>\r\n\t\t\t\t");
									print("<LearnDelFAMType>ASN</LearnDelFAMType>\r\n\t\t\t\t");
									print("<LearnDelFAMCode>1</LearnDelFAMCode>\r\n\t\t");
									print("</LearningDeliveryFAM>");
								}

								if(trim($aim->A48a)!="" )
								{
									print("\r\n\t\t<ProviderSpecDeliveryMonitoring>");
									print("\r\n\t\t\t\t<LearnDelOccurCode>A</LearnDelOccurCode>");
									print("\r\n\t\t\t\t<ProvSpecLearnDelMon>" . $aim->A48a . "</ProvSpecLearnDelMon>");
									print("\r\n\t\t\t</ProviderSpecDeliveryMonitoring>");
								}
								if(trim($aim->A48b)!="" )
								{
									print("\r\n\t\t\t<ProviderSpecDeliveryMonitoring>");
									print("\r\n\t\t\t\t<LearnDelOccurCode>B</LearnDelOccurCode>");
									print("\r\n\t\t\t\t<ProvSpecLearnDelMon>" . $aim->A48b . "</ProvSpecLearnDelMon>");
									print("\r\n\t\t\t</ProviderSpecDeliveryMonitoring>");
								}
								if(trim($aim->A72a)!="" )
								{
									print("\r\n\t\t\t<ProviderSpecDeliveryMonitoring>");
									print("\r\n\t\t\t\t<LearnDelOccurCode>C</LearnDelOccurCode>");
									print("\r\n\t\t\t\t<ProvSpecLearnDelMon>" . $aim->A72a . "</ProvSpecLearnDelMon>");
									print("\r\n\t\t\t</ProviderSpecDeliveryMonitoring>");
								}
								if(trim($aim->A72b)!="" )
								{
									print("\r\n\t\t\t<ProviderSpecDeliveryMonitoring>");
									print("\r\n\t\t\t\t<LearnDelOccurCode>D</LearnDelOccurCode>");
									print("\r\n\t\t\t\t<ProvSpecLearnDelMon>" . $aim->A72b . "</ProvSpecLearnDelMon>");
									print("\r\n\t\t\t</ProviderSpecDeliveryMonitoring>");
								}
								print("\r\n\t\t\t</LearningDelivery>");
								
							}
			
							foreach($ilr->subaim as $aim)
							{
								$no_of_aims++;
								print("\r\n\t\t\t<LearningDelivery>\r\n\t\t\t");
								print("<LearnAimRef>" . $aim->A09 . "</LearnAimRef>\r\n\t\t\t");
								if($aim->A15=='99')
									print("<AimType>4</AimType>\r\n\t\t\t");
								else
									print("<AimType>3</AimType>\r\n\t\t\t");
								print("<AimSeqNumber>" . $no_of_aims . "</AimSeqNumber>\r\n\t\t\t");
								print("<LearnStartDate>" . Date::toMySQL($aim->A27) . "</LearnStartDate>\r\n\t\t\t");				
								print("<LearnPlanEndDate>" . Date::toMySQL($aim->A28) . "</LearnPlanEndDate>\r\n\t\t\t");				
								if($aim->A10=='46')
									$a10 = '45';
								else
									$a10 = $aim->A10;
								if(trim($aim->A10)=='')
									print("<FundModel>99</FundModel>\r\n\t\t\t");
								else
									print("<FundModel>" . $a10 . "</FundModel>\r\n\t\t\t");

								if( ($aim->A15=='99' || $aim->A15=='') && $aim->A10!='70'  && $aim->A10!='99' && $aim->A10!='21')
								{
									if(trim($aim->A64)!="")
										print("<PlanGrpHrs>" . $aim->A64 . "</PlanGrpHrs>\r\n\t\t\t");
									else
										print("<PlanGrpHrs>0</PlanGrpHrs>\r\n\t\t\t");
									
									if(trim($aim->A65)!="")
										print("<PlanOneToOneHrs>" . $aim->A65 . "</PlanOneToOneHrs>\r\n\t\t\t");
									else
										print("<PlanOneToOneHrs>0</PlanOneToOneHrs>\r\n\t\t\t");
								}
								
								if(trim($aim->A59)!="")
									print("<PlanCredVal>" . (int)$aim->A59 . "</PlanCredVal>\r\n\t\t\t");

								if($aim->A15!='')		
									print("<ProgType>" . $aim->A15 . "</ProgType>\r\n\t\t\t");
								else
									print("<ProgType>98</ProgType>\r\n\t\t\t");
								
								if($aim->A26!='' && $aim->A26!='0')
									print("<FworkCode>" . $aim->A26 . "</FworkCode>\r\n\t\t\t");
								
								if(trim($aim->A18)!="" && trim($aim->A18)!="0"  && $aim->A10!='70')
									print("<MainDelMeth>" . $aim->A18 . "</MainDelMeth>\r\n\t\t\t");

								if(trim($aim->A22)!="" && trim($aim->A22)!="0" && $aim->A10!='70')
									print("<PartnerUKPRN>" . $aim->A22 . "</PartnerUKPRN>\r\n\t\t\t");
									
								if(trim($aim->A23)!='')
									print("<DelLocPostCode>" . trim($aim->A23) . "</DelLocPostCode>\r\n\t\t\t");

								if($aim->A10!='99' && $aim->A10!='70')
								{
									if(trim($aim->A51a)!="")
										print("<PropFundRemain>" . $aim->A51a . "</PropFundRemain>\r\n\t\t\t");
									else
										print("<PropFundRemain>0</PropFundRemain>\r\n\t\t\t");
								}


								if($aim->A10=='70')
								{
									print("<ESFProjDosNumber>" . $aim->A61 . "</ESFProjDosNumber>\r\n\t\t\t");
									print("<ESFLocProjNumber>" . $aim->A62 . "</ESFLocProjNumber>\r\n\t\t\t");
								}


								if( ($aim->A15=='99' || $aim->A15=='' || $aim->A15=='19') && $aim->A70!='')
								{
									print("<ContOrgCode>" . $aim->A70 . "</ContOrgCode>\r\n\t\t\t");
								}
								
								if($aim->A34=='3' || $aim->A34=='4' || $aim->A34=='5')
									print("<CompStatus>" . "3" . "</CompStatus>\r\n\t\t\t");
								elseif($aim->A34!='')
									print("<CompStatus>" . $aim->A34 . "</CompStatus>\r\n\t\t\t");
																
								if($aim->A31!='' && $aim->A31!='00000000' && $aim->A34!='dd/mm/yyyy' && $aim->A34!='01-01-1970')
									print("<LearnActEndDate>" . Date::toMySQL($aim->A31) . "</LearnActEndDate>\r\n\t\t\t");
								
								if(($aim->A34=='3' || $aim->A34=='4' || $aim->A34=='5') && $aim->A10!='99')
									if(trim($aim->A50)!="" && trim($aim->A50)!="96" && trim($aim->A50)!="30" && trim($aim->A50)!="31" && trim($aim->A50)!="32" && trim($aim->A50)!="33" && trim($aim->A50)!="34" && trim($aim->A50)!="35")
										print("<WithdrawReason>" . $aim->A50 . "</WithdrawReason>\r\n\t\t\t");
									else
										print("<WithdrawReason>98</WithdrawReason>\r\n\t\t\t");
									
								if($aim->A34!='1' && trim($aim->A35)!='')
									print("<OutcomeInd>" . $aim->A35 . "</OutcomeInd>\r\n\t\t\t");
								
								if($aim->A40!="" && $aim->A40!="dd/mm/yyyy" && $aim->A40!="00000000" && $aim->A10!='99' && $aim->A10!='81' && $aim->A10!='70')
									print("<AchDate>" . Date::toMySQL($aim->A40) . "</AchDate>\r\n\t\t\t");
		
								if($aim->A60!="")
									print("<CredAch>" . (int)$aim->A60 . "</CredAch>\r\n\t\t\t");

								if( ($aim->A15=='99' || $aim->A15=='' || $aim->A15=='19') && $aim->A11a!='' && $aim->A11a!='999')
								{
									print("<LearningDeliveryFAM>\r\n\t\t\t\t");
									print("<LearnDelFAMType>SOF</LearnDelFAMType>\r\n\t\t\t\t");
									print("<LearnDelFAMCode>" . $aim->A11a . "</LearnDelFAMCode>\r\n\t\t\t");
									print("</LearningDeliveryFAM>\r\n\t\t");

									if($aim->A46a!='' && $aim->A46a!='999')
									{
										print("<LearningDeliveryFAM>\r\n\t\t\t\t");
										print("<LearnDelFAMType>LDM</LearnDelFAMType>\r\n\t\t\t\t");
										print("<LearnDelFAMCode>" . $aim->A46a . "</LearnDelFAMCode>\r\n\t\t\t");
										print("</LearningDeliveryFAM>\r\n\t\t");
									}
								}
									
								if((trim($aim->A71)!="" || $mainA71!="") && $aim->A71!='99')
								{
									if(trim($aim->A71)!="")
										$a71 = $aim->A71;
									else 
										$a71 = $mainA71;
									print("<LearningDeliveryFAM>\r\n\t\t\t\t");
									print("<LearnDelFAMType>FFI</LearnDelFAMType>\r\n\t\t\t\t");
									print("<LearnDelFAMCode>" . $a71 . "</LearnDelFAMCode>\r\n\t\t\t");
									print("</LearningDeliveryFAM>\r\n\t\t\t");
								}

								if(trim($aim->A53)=='11' || $aim->A53=='13')
								{
									print("<LearningDeliveryFAM>\r\n\t\t\t\t");
									print("<LearnDelFAMType>ALN</LearnDelFAMType>\r\n\t\t\t\t");
									print("<LearnDelFAMCode>1</LearnDelFAMCode>\r\n\t\t");
									print("</LearningDeliveryFAM>");
								}

								if(trim($aim->A53)=='12' || $aim->A53=='13')
								{
									print("<LearningDeliveryFAM>\r\n\t\t\t\t");
									print("<LearnDelFAMType>ASN</LearnDelFAMType>\r\n\t\t\t\t");
									print("<LearnDelFAMCode>1</LearnDelFAMCode>\r\n\t\t");
									print("</LearningDeliveryFAM>");
								}

								if(trim($aim->A48a)!="" )
								{
									print("<ProviderSpecDeliveryMonitoring>\r\n\t\t\t\t");
									print("<LearnDelOccurCode>A</LearnDelOccurCode>\r\n\t\t\t\t");
									print("<ProvSpecLearnDelMon>" . $aim->A48a . "</ProvSpecLearnDelMon>\r\n\t\t\t\t");
									print("</ProviderSpecDeliveryMonitoring>\r\n\t\t\t\t");
								}
								if(trim($aim->A48b)!="" )
								{
									print("<ProviderSpecDeliveryMonitoring>\r\n\t\t\t\t");
									print("<LearnDelOccurCode>B</LearnDelOccurCode>\r\n\t\t\t\t");
									print("<ProvSpecLearnDelMon>" . $aim->A48b . "</ProvSpecLearnDelMon>\r\n\t\t\t\t");
									print("</ProviderSpecDeliveryMonitoring>\r\n\t\t\t\t");
								}
								if(trim($aim->A72a)!="" )
								{
									print("<ProviderSpecDeliveryMonitoring>\r\n\t\t\t\t");
									print("<LearnDelOccurCode>C</LearnDelOccurCode>\r\n\t\t\t\t");
									print("<ProvSpecLearnDelMon>" . $aim->A72a . "</ProvSpecLearnDelMon>\r\n\t\t\t\t");
									print("</ProviderSpecDeliveryMonitoring>\r\n\t\t\t\t");
								}
								if(trim($aim->A72b)!="" )
								{
									print("<ProviderSpecDeliveryMonitoring>\r\n\t\t\t\t");
									print("<LearnDelOccurCode>D</LearnDelOccurCode>\r\n\t\t\t\t");
									print("<ProvSpecLearnDelMon>" . $aim->A72b . "</ProvSpecLearnDelMon>\r\n\t\t\t\t");
									print("</ProviderSpecDeliveryMonitoring>\r\n\t\t\t\t");
								}
								print("</LearningDelivery>");
							}
						}
					}
					print("\r\n\t\t\t</Learner>");
				}
			}
			print("</Message>");				
		}	
	}
	
	public static function getFilename(PDO $link, $contract_id, $submission, $L01)
	{
		if(is_null($contract_id))
		{
			return null;
		}
		
		$contract = Contract::loadFromDatabase($link, $contract_id);		
		
		$vo = new Ilr2011();
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
			$file.= $contract->ukprn;
			$file.= '00';
			$file.= '1112';
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
	
	public static function copyILRFields($xml, $template)
	{
		//$pageDomTemplate = new DomDocument();
		//@$pageDomTemplate->loadXML($template);
		$pageDomTemplate = XML::loadXmlDom($template);
		//$pageDomXML = new DomDocument();
		//@$pageDomXML->loadXML($xml);
		$pageDomXML = XML::loadXmlDom($xml);
		
		$evidencesTemplate = $pageDomTemplate->getElementsByTagName('subaim');
		foreach($evidencesTemplate as $evidenceTemplate)
		{
			$a09t = "" . $evidenceTemplate->getElementsByTagName('A09')->item(0)->nodeValue;
			
			$evidencesXML = $pageDomXML->getElementsByTagName('subaim');
			foreach($evidencesXML as $evidenceXML)
			{
				$a09x = "" . $evidenceXML->getElementsByTagName('A09')->item(0)->nodeValue;

				if($a09x == $a09t)
				{
					$evidenceXML->getElementsByTagName('A10')->item(0)->nodeValue = $evidenceTemplate->getElementsByTagName('A10')->item(0)->nodeValue;
					$evidenceXML->getElementsByTagName('A11a')->item(0)->nodeValue = $evidenceTemplate->getElementsByTagName('A11a')->item(0)->nodeValue;
					$evidenceXML->getElementsByTagName('A11b')->item(0)->nodeValue = $evidenceTemplate->getElementsByTagName('A11b')->item(0)->nodeValue;
					$evidenceXML->getElementsByTagName('A70')->item(0)->nodeValue = $evidenceTemplate->getElementsByTagName('A70')->item(0)->nodeValue;
					$evidenceXML->getElementsByTagName('A71')->item(0)->nodeValue = $evidenceTemplate->getElementsByTagName('A71')->item(0)->nodeValue;
					$evidenceXML->getElementsByTagName('A69')->item(0)->nodeValue = $evidenceTemplate->getElementsByTagName('A69')->item(0)->nodeValue;
					$evidenceXML->getElementsByTagName('A46a')->item(0)->nodeValue = $evidenceTemplate->getElementsByTagName('A46a')->item(0)->nodeValue;
					$evidenceXML->getElementsByTagName('A46b')->item(0)->nodeValue = $evidenceTemplate->getElementsByTagName('A46b')->item(0)->nodeValue;
					$evidenceXML->getElementsByTagName('A18')->item(0)->nodeValue = $evidenceTemplate->getElementsByTagName('A18')->item(0)->nodeValue;
					$evidenceXML->getElementsByTagName('A63')->item(0)->nodeValue = $evidenceTemplate->getElementsByTagName('A63')->item(0)->nodeValue;
					$evidenceXML->getElementsByTagName('A66')->item(0)->nodeValue = $evidenceTemplate->getElementsByTagName('A66')->item(0)->nodeValue;
					$evidenceXML->getElementsByTagName('A67')->item(0)->nodeValue = $evidenceTemplate->getElementsByTagName('A67')->item(0)->nodeValue;
					$evidenceXML->getElementsByTagName('A34')->item(0)->nodeValue = $evidenceTemplate->getElementsByTagName('A34')->item(0)->nodeValue;
					$evidenceXML->getElementsByTagName('A35')->item(0)->nodeValue = $evidenceTemplate->getElementsByTagName('A35')->item(0)->nodeValue;
					$evidenceXML->getElementsByTagName('A50')->item(0)->nodeValue = $evidenceTemplate->getElementsByTagName('A50')->item(0)->nodeValue;
					$evidenceXML->getElementsByTagName('A53')->item(0)->nodeValue = $evidenceTemplate->getElementsByTagName('A53')->item(0)->nodeValue;
					$evidenceXML->getElementsByTagName('A59')->item(0)->nodeValue = $evidenceTemplate->getElementsByTagName('A59')->item(0)->nodeValue;
					$evidenceXML->getElementsByTagName('A60')->item(0)->nodeValue = $evidenceTemplate->getElementsByTagName('A60')->item(0)->nodeValue;
					$evidenceXML->getElementsByTagName('A61')->item(0)->nodeValue = $evidenceTemplate->getElementsByTagName('A61')->item(0)->nodeValue;
					$evidenceXML->getElementsByTagName('A62')->item(0)->nodeValue = $evidenceTemplate->getElementsByTagName('A62')->item(0)->nodeValue;
					$evidenceXML->getElementsByTagName('A63')->item(0)->nodeValue = $evidenceTemplate->getElementsByTagName('A63')->item(0)->nodeValue;
					$evidenceXML->getElementsByTagName('A66')->item(0)->nodeValue = $evidenceTemplate->getElementsByTagName('A66')->item(0)->nodeValue;
					$evidenceXML->getElementsByTagName('A67')->item(0)->nodeValue = $evidenceTemplate->getElementsByTagName('A67')->item(0)->nodeValue;
					$evidenceXML->getElementsByTagName('A68')->item(0)->nodeValue = $evidenceTemplate->getElementsByTagName('A68')->item(0)->nodeValue;
					$evidenceXML->getElementsByTagName('A69')->item(0)->nodeValue = $evidenceTemplate->getElementsByTagName('A69')->item(0)->nodeValue;
					$evidenceXML->getElementsByTagName('A70')->item(0)->nodeValue = $evidenceTemplate->getElementsByTagName('A70')->item(0)->nodeValue;
					$evidenceXML->getElementsByTagName('A71')->item(0)->nodeValue = $evidenceTemplate->getElementsByTagName('A71')->item(0)->nodeValue;
				}
			}
		}
		$ilr = $pageDomXML->saveXML();
		$ilr=substr($ilr,21);
		return $ilr;
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