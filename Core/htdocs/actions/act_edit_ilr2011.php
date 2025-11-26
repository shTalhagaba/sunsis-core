<?php
class edit_ilr2011 implements IAction
{
	public function execute(PDO $link)
	{
		$submission = isset($_REQUEST['submission'])?$_REQUEST['submission']:'';
		$contract_id = isset($_REQUEST['contract_id'])?$_REQUEST['contract_id']:'';
		$tr_id = isset($_REQUEST['tr_id'])?$_REQUEST['tr_id']:'';
		$L03 = isset($_REQUEST['L03'])?$_REQUEST['L03']:'';
		$pdf = isset($_REQUEST['pdf'])?$_REQUEST['pdf']:'';
		$template = isset($_REQUEST['template'])?$_REQUEST['template']:'';
		
		$_SESSION['bc']->add($link, "do.php?_action=edit_ilr2011&submission=" . $submission . "&contract_id=" . $contract_id . "&tr_id=" . $tr_id . "&L03=" . $L03, "Add/ Edit ILR Form");

		//$linklis = new PDO("mysql:host=".DB_HOST.";dbname=lis201112;port=".DB_PORT, DB_USER, DB_PASSWORD);
		//$linklad = new PDO("mysql:host=".DB_HOST.";dbname=lad201112;port=".DB_PORT, DB_USER, DB_PASSWORD);

        $max_submission = DAO::getSingleValue($link, "SELECT submission FROM central.lookup_submission_dates WHERE CURDATE() BETWEEN start_submission_date AND last_submission_date AND contract_year IN (SELECT contract_year FROM contracts WHERE id = $contract_id);");

		$how_many = DAO::getSingleValue($link, "select count(*) from ilr where tr_id = '$tr_id'");

		if($how_many>1)
			$how_many = 0;
		else
			$how_many = 1;
		
		if($submission == '' || $contract_id=='' || $tr_id=='')
		{
			$vo = new Ilr2011();
			$vo->learnerinformation = new LearnerInformation();
			$vo->aims[0] = new Aim2011();
			$vo->programmeaim = new Aim2011();
			$vo->programmeaim->A04 = "35";
			$vo->programmeaim->A09 = "ZPROG001";
			$vo->programmeaim->A10 = "45";
			

			$tr_id = DAO::getSingleValue($link, "select max(tr_id) from ilr");
			$tr_id+=1;

		}
		else
		{	
			$vo = Ilr2011::loadFromDatabase($link, $submission, $contract_id, $tr_id, $L03);
		}

		// If this is template
		if($template==1)
		{
			$xml = DAO::getSingleValue($link, "select template from contracts where id = '$contract_id'");
			
			if($xml!='')
			{
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
					$vo->programmeaim->A50=$programmeaim->A50;
					$vo->programmeaim->A51a=$programmeaim->A51a;
					$vo->programmeaim->A64=$programmeaim->A64;
					$vo->programmeaim->A65=$programmeaim->A65;
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
			else
			{
				$vo = new Ilr2011();
				$vo->learnerinformation = new LearnerInformation();
				$vo->aims[0] = new Aim2011();
				$vo->programmeaim = new Aim2011();
				$vo->programmeaim->A04 = "35";
				$vo->programmeaim->A09 = "ZPROG001";
				$vo->programmeaim->A10 = "45";
			}
		}
		else
		{
			$template = 2;
		}
		
		$con = Contract::loadFromDatabase($link, $contract_id);
			
		if($submission!='W01')
		{
			$previous_submission = (int)substr($submission,1,2);
			$previous_submission--;
			if($previous_submission<=9)
				$previous_submission = "ER0" . $previous_submission;
			else
				$previous_submission = "ER" . $previous_submission;

			$previous_vo = Ilr2011::loadFromDatabase($link, $previous_submission, $contract_id, $tr_id, $L03);
			if($previous_vo->learnerinformation->L09=='')
				$previous_vo = $vo;
				
		}
		else
		{
			$previous_vo = $vo;
		}
		
		if($vo==null)
		{
			throw new Exception("Could not load from database");
		}
		
		// Drop down list arrays
		$type_dropdown = "SELECT distinct id, LEFT(CONCAT(id, ' - ', description),40), null FROM lookup_qual_type ORDER BY id;";
		$type_dropdown = DAO::getResultset($link, $type_dropdown, DAO::FETCH_NUM, "ILR2011 type dropdown");
		
		$L46_dropdown = "SELECT distinct UKPRN, LEFT(CONCAT(UKPRN,' ',Name),40), null from lis201112.providers order by UKPRN;";
		$L46_dropdown = DAO::getResultset($link,$L46_dropdown, DAO::FETCH_NUM, "ILR2011 L46 dropdown");
		
		$A56_dropdown = "SELECT distinct value, left(description,40),null from dropdown0809 where code='L46' order by value;";
		$A56_dropdown = DAO::getResultset($link,$A56_dropdown, DAO::FETCH_NUM, "ILR2011 A56 dropdown");
		
		$L12_dropdown = "SELECT distinct Ethnicity_Code, LEFT(CONCAT(Ethnicity_Code, ' ', Ethnicity_Desc), 40), null from lis201112.ilr_l12_ethnicity order by Ethnicity_Code;";
		$L12_dropdown = DAO::getResultset($link,$L12_dropdown, DAO::FETCH_NUM, "ILR2011 L12 dropdown");
		
		$L24_dropdown = "SELECT distinct Domicile_Code, LEFT(CONCAT(Domicile_Code, ' ', Domicile_Desc),40), null from lis201112.ilr_l24_domiciles order by Domicile_Code;";
		$L24_dropdown = DAO::getResultset($link,$L24_dropdown, DAO::FETCH_NUM, "ILR2011 L24 dropdown");
		
		$L14_dropdown = "SELECT distinct Difficulty_Disability, LEFT(CONCAT(Difficulty_Disability, ' ', Difficulty_Disability_Desc),40), null from lis201112.ilr_l14_difficulty_disability order by Difficulty_Disability;";
		$L14_dropdown = DAO::getResultset($link,$L14_dropdown, DAO::FETCH_NUM, "ILR2011 L14 dropdown");
		
		$L15_dropdown = "SELECT distinct Disability_Code, LEFT(CONCAT(Disability_Code, ' ', Disability_Desc), 40), null from lis201112.ilr_l15_disability order by Disability_Code;";
		$L15_dropdown = DAO::getResultset($link,$L15_dropdown, DAO::FETCH_NUM, "ILR2011 L15 dropdown");
		
		$L16_dropdown = "SELECT distinct Difficulty_Code, LEFT(CONCAT(Difficulty_Code,' ',Difficulty_Desc),40),null from lis201112.ilr_l16_difficulty order by Difficulty_Code;";
		$L16_dropdown = DAO::getResultset($link,$L16_dropdown, DAO::FETCH_NUM, "ILR2011 L16 dropdown");
		
		$L34_dropdown = "SELECT distinct Learner_Support_Reason_Code, LEFT(CONCAT(Learner_Support_Reason_Code, ' ', Learner_Support_Reason_Desc),40), null from lis201112.ilr_l34_learner_supp_reasons order by Learner_Support_Reason_Code;";
		$L34_dropdown = DAO::getResultset($link,$L34_dropdown, DAO::FETCH_NUM, "ILR2011 L34 dropdown");
		
		$L35_dropdown = "SELECT distinct Prior_Attainment_Level_Code, LEFT(CONCAT(Prior_Attainment_Level_Code, ' ', Prior_Attainment_Level_Desc),40), null from lis201112.ilr_l35_prior_attainment_level order by Prior_Attainment_Level_Code;";
		$L35_dropdown = DAO::getResultset($link,$L35_dropdown, DAO::FETCH_NUM, "ILR2011 L35 dropdown");
		
		$L36_dropdown = "SELECT distinct Learner_Status_Code, LEFT(CONCAT(Learner_Status_Code,' ', Learner_Status_Desc),40) ,null from lis201112.ilr_l36_learner_status order by Learner_Status_Code;";
		$L36_dropdown = DAO::getResultset($link,$L36_dropdown, DAO::FETCH_NUM, "ILR2011 L36 dropdown");
		
		$L37_dropdown = "SELECT distinct Employment_Status_First_Code, LEFT(CONCAT(Employment_Status_First_Code,' ',Employment_Status_First_Desc),40), null from lis201112.ilr_l37_employ_status_firsts  order by Employment_Status_First_Code;";
		$L37_dropdown = DAO::getResultset($link,$L37_dropdown, DAO::FETCH_NUM, "ILR2011 L37 dropdown");
		
		$L39_dropdown = "SELECT distinct Destination_Code, LEFT(CONCAT(Destination_Code,' ', Destination_Desc),40) ,null from lis201112.ilr_l39_destinations order by Destination_Code;";
		$L39_dropdown = DAO::getResultset($link,$L39_dropdown, DAO::FETCH_NUM, "ILR2011 L39 dropdown");
		
		$L47_dropdown = "SELECT distinct Current_Emp_Status_Code, LEFT(CONCAT(Current_Emp_Status_Code, ' ', Current_Emp_Status_Desc),40),null from lis201112.ilr_l47_current_emp_status order by Current_Emp_Status_Code;";
		$L47_dropdown = DAO::getResultset($link,$L47_dropdown, DAO::FETCH_NUM, "ILR2011 L47 dropdown");
		
		$L52_dropdown = "SELECT distinct Destination_Code, LEFT(CONCAT(Destination_Code, ' ', Destination_Desc),80),null from lis201112.ilr_l52_restricted_indicator order by Destination_Code;";
		$L52_dropdown = DAO::getResultset($link,$L52_dropdown, DAO::FETCH_NUM, "ILR2011 L52 dropdown");
		
		$A02_dropdown = "SELECT distinct Contract_Number_Code, CONCAT(Contract_Number_Code, ' ',Contract_Number_Desc),null from lis201112.ilr_a02_contract_number;";
		$A02_dropdown = DAO::getResultset($link,$A02_dropdown, DAO::FETCH_NUM, "ILR2011 A02 dropdown");
		
		$A10_dropdown = "SELECT distinct Funding_Model_Code, Left(concat(Funding_Model_Code,' ',Funding_Model_Desc),40),null from lis201112.ilr_a10_funding_models";
		$A10_dropdown = DAO::getResultset($link,$A10_dropdown, DAO::FETCH_NUM, "ILR2011 A10 dropdown");
		
		$A11_dropdown = "SELECT distinct Funding_Source_Code, Left(concat(Funding_Source_Code,' ',Funding_Source_Desc),40),null from lis201112.ilr_a11_funding_sources";
		$A11_dropdown = DAO::getResultset($link,$A11_dropdown, DAO::FETCH_NUM, "ILR2011 A11 dropdown");
		
		$A14_dropdown = "SELECT distinct non_payment_reason_code, LEFT(CONCAT(non_payment_reason_code, ' ', non_payment_reason_desc),40) ,null from lis201112.ilr_a14_non_payment_reasons order by non_payment_reason_code;";
		$A14_dropdown = DAO::getResultset($link,$A14_dropdown, DAO::FETCH_NUM, "ILR2011 A14 dropdown");
		
		$A15_dropdown = "SELECT distinct Programme_Type_Code, LEFT(CONCAT(Programme_Type_Code, ' ' , Programme_Type_Desc),40), null from lis201112.ilr_a15_programme_types order by Programme_Type_Code;";
		$A15_dropdown = DAO::getResultset($link,$A15_dropdown, DAO::FETCH_NUM, "ILR2011 A15 dropdown");
		
		$A16_dropdown = "SELECT distinct Programme_Route_Code, LEFT(CONCAT(Programme_Route_Code, ' ', Programme_Route_Desc), 40), null from lis201112.ilr_a16_programme_routes order by Programme_Route_Code;";
		$A16_dropdown = DAO::getResultset($link,$A16_dropdown, DAO::FETCH_NUM, "ILR2011 A16 dropdown");
		
		$A18_dropdown = "SELECT distinct Delivery_Method_Code, LEFT(CONCAT(Delivery_Method_Code, ' ', Delivery_Method_Desc), 40) ,null from lis201112.ilr_a18_delivery_methods order by Delivery_Method_Code;";
		$A18_dropdown = DAO::getResultset($link,$A18_dropdown, DAO::FETCH_NUM, "ILR2011 A18 dropdown");
		
//		$A24_dropdown = "SELECT distinct SOC2000_Code_Code , LEFT(CONCAT(SOC2000_Code_Code, ' ', SOC2000_Code_Desc),40) ,null from lad201112.soc2000_codes order by SOC2000_Code_Code;";
//		$A24_dropdown = DAO::getResultset($link,$A24_dropdown, DAO::FETCH_NUM, "ILR2011 A24 dropdown");
		
		if($vo->programmeaim->A15=='2' || $vo->programmeaim->A15=='3')
		{
			$ptype = $vo->programmeaim->A15;
			$A26_dropdown = "SELECT DISTINCT Framework_Code, LEFT(CONCAT(Framework_Code, ' ', Framework_Desc),40) ,null from lad201112.frameworks where FRAMEWORK_TYPE_CODE = '$ptype' order by Framework_Code;";
			$A26_dropdown = DAO::getResultset($link,$A26_dropdown, DAO::FETCH_NUM, "ILR2011 A26 dropdown");
		}
		else
		{
			$A26_dropdown = "SELECT DISTINCT Framework_Code, LEFT(CONCAT(Framework_Code, ' ', Framework_Desc),40) ,null from lad201112.frameworks order by Framework_Code;";
			$A26_dropdown = DAO::getResultset($link,$A26_dropdown, DAO::FETCH_NUM, "ILR2011 A26 dropdown");
		}

		$A34_dropdown = "SELECT distinct Completion_Status_Code, LEFT(CONCAT(Completion_Status_Code, ' ', Completion_Status_Desc),40), null from lis201112.ilr_a34_completion_status order by Completion_Status_Code;";
		$A34_dropdown = DAO::getResultset($link,$A34_dropdown, DAO::FETCH_NUM, "ILR2011 A34 dropdown");
		
		$A35_dropdown = "SELECT distinct Learning_Outcome_Code, LEFT(CONCAT(Learning_Outcome_Code, ' ', Learning_Outcome_Desc),40) ,null from lis201112.ilr_a35_learning_outcomes order by Learning_Outcome_Code;";
		$A35_dropdown = DAO::getResultset($link,$A35_dropdown, DAO::FETCH_NUM, "ILR2011 A35 dropdown");
		
		$A36_dropdown = "SELECT distinct Learning_Outcome_Grade_Code, LEFT(CONCAT(Learning_Outcome_Grade_Code, ' ', Learning_Outcome_Grade_Desc),40) ,null from lis201112.ilr_a36_learn_outcome_grades order by Learning_Outcome_Grade_Code;";
		$A36_dropdown = DAO::getResultset($link,$A36_dropdown, DAO::FETCH_NUM, "ILR2011 A36 dropdown");
		
		$A46_dropdown = "SELECT distinct National_Learner_Aim_Code, LEFT(CONCAT(National_Learner_Aim_Code, ' ', National_Learner_Aim_Desc),40) ,null from lis201112.ilr_a46_nat_learner_aims  order by National_Learner_Aim_Code;";
		$A46_dropdown = DAO::getResultset($link,$A46_dropdown, DAO::FETCH_NUM, "ILR2011 A46 dropdown");
		
		$A49_dropdown = "SELECT distinct Project_Code, Project_Code ,null from lis201112.ilr_a49_project_codes;";
		$A49_dropdown = DAO::getResultset($link,$A49_dropdown, DAO::FETCH_NUM, "ILR2011 A49 dropdown");
		
		$A50_dropdown = "SELECT distinct Reason_Learning_Ended_Code, LEFT(CONCAT(Reason_Learning_Ended_Code, ' ', Reason_Learning_Ended_Desc),40), null from lis201112.ilr_a50_reason_learning_ended order by Reason_Learning_Ended_Code;";
		$A50_dropdown = DAO::getResultset($link,$A50_dropdown, DAO::FETCH_NUM, "ILR2011 A50 dropdown");
		
		$A53_dropdown = "SELECT distinct Additional_Learning_Need_Code, LEFT(CONCAT(Additional_Learning_Need_Code, ' ', Additional_Learning_Need_Desc),40), null from lis201112.ilr_a53_add_learning_needs order by Additional_Learning_Need_Code;";
		$A53_dropdown = DAO::getResultset($link,$A53_dropdown, DAO::FETCH_NUM, "ILR2011 A53 dropdown");
		
		$A63_dropdown = "SELECT distinct Nat_Skills_Academy_Code, left(CONCAT(Nat_Skills_Academy_Code, ' ', Nat_Skills_Academy_Desc),40), null from lis201112.ilr_a63_nat_skills_academy order by Nat_Skills_Academy_Code;";
		$A63_dropdown = DAO::getResultset($link,$A63_dropdown, DAO::FETCH_NUM, "ILR2011 A63 dropdown");
		
		$A66_dropdown = "SELECT distinct Employment_Status_ESF_Code, left(CONCAT(Employment_Status_ESF_Code, ' ', Employment_Status_ESF_Desc),40), null from lis201112.ilr_a66_employ_status_esf order by Employment_Status_ESF_Code;";
		$A66_dropdown = DAO::getResultset($link,$A66_dropdown, DAO::FETCH_NUM, "ILR2011 A66 dropdown");
		
		$A67_dropdown = "SELECT distinct Length_Unemployment_ESF_Code, left(CONCAT(Length_Unemployment_ESF_Code, ' ', Length_Unemployment_ESF_Desc),40), null from lis201112.ilr_a67_length_unemploy_esf order by Length_Unemployment_ESF_Code;";
		$A67_dropdown = DAO::getResultset($link,$A67_dropdown, DAO::FETCH_NUM, "ILR2011 A67 dropdown");
		
		$A68_dropdown = "SELECT distinct Emp_Outcome_Funding_Code, left(CONCAT(Emp_Outcome_Funding_Code, ' ', Emp_Outcome_Funding_Desc),40), null from lis201112.ilr_a68_emp_outcome_funding;";
		$A68_dropdown = DAO::getResultset($link,$A68_dropdown, DAO::FETCH_NUM, "ILR2011 A68 dropdown");
		
		$A69_dropdown = "SELECT distinct Elig_Enhan_ER_Funding_Code, left(CONCAT(Elig_Enhan_ER_Funding_Code, ' ', Elig_Enhan_ER_Funding_Desc),40), null from lis201112.ilr_a69_elig_enhan_er_funding;";
		$A69_dropdown = DAO::getResultset($link,$A69_dropdown, DAO::FETCH_NUM, "ILR2011 A69 dropdown");
		
		$A70_dropdown = "SELECT distinct Cont_Org_Code, left(CONCAT(Cont_Org_Code, ' ', Cont_Org_Code_Desc),40), null from lis201112.ilr_a70_cont_org_code;";
		$A70_dropdown = DAO::getResultset($link,$A70_dropdown, DAO::FETCH_NUM, "ILR2011 A70 dropdown");
		
		$A71_dropdown = "SELECT distinct Code, left(CONCAT(Code, ' ', Description),40), null from lis201112.ilr_a71_funding_indicator;";
		$A71_dropdown = DAO::getResultset($link,$A71_dropdown, DAO::FETCH_NUM, "ILR2011 A71 dropdown");
		
		$L01_dropdown = "SELECT distinct CAPN, LEFT(concat(CAPN, ' ', Name),35), null from lis201112.providers order by Name;";
		$L01_dropdown = DAO::getResultset($link,$L01_dropdown, DAO::FETCH_NUM, "ILR2011 L01 dropdown");

		$A01_dropdown = "SELECT distinct CAPN, LEFT(concat(CAPN, ' ', Name),35), null from lis201112.providers order by Name;";
		$A01_dropdown = DAO::getResultset($link,$A01_dropdown, DAO::FETCH_NUM, "ILR2011 A01 dropdown");
		
		$E01_dropdown = "SELECT distinct value, description,null from dropdown0809 where code='L01' order by value;";
		$E01_dropdown = DAO::getResultset($link,$E01_dropdown, DAO::FETCH_NUM, "ILR2011 E01 dropdown");
		
		$L40_dropdown = "SELECT distinct National_Learner_Event_Code, LEFT(CONCAT(National_Learner_Event_Code,' ',National_Learner_Event_Desc),50), null from lis201112.ilr_l40_nat_learner_events order by National_Learner_Event_Code;";
		$L40_dropdown = DAO::getResultset($link,$L40_dropdown, DAO::FETCH_NUM, "ILR2011 L40 dropdown");
		
		$E11_dropdown = "SELECT distinct value, description,null from dropdown0809 where code='E11' order by value;";
		$E11_dropdown = DAO::getResultset($link,$E11_dropdown, DAO::FETCH_NUM, "ILR2011 E11 dropdown");
		
		
		$level_checkboxes = "SELECT id, CONCAT(id, ' - ', description), null FROM lookup_qual_level ORDER BY id;";
		$level_checkboxes = DAO::getResultset($link, $level_checkboxes, DAO::FETCH_NUM, "ILR2011 qual-level checkboxes");
		
		$linklis = NULL; 
		$linklad = NULL;
		

		require_once('tpl_edit_ilr2011_xml.php');
	}
	
	
	private function checkPermissions(PDO $link, Course $c_vo)
	{
		if($_SESSION['role'] == 'admin')
		{
			return true;
		}
		elseif($_SESSION['org']->org_type_id == ORG_PROVIDER)
		{
			$acl = CourseACL::loadFromDatabase($link, $c_vo->id);
			$is_employee = $_SESSION['org']->id == $c_vo->organisations_id;
			$is_local_admin = in_array('ladmin', $_SESSION['privileges']);
			$listed_in_course_acl = in_array($_SESSION['username'], $acl->usernames);
			
			return $is_employee && $is_local_admin;
		}
		elseif($_SESSION['org']->org_type_id == ORG_SCHOOL)
		{
			return false;
		}
		else
		{
			return false;
		}
	}
}
?>