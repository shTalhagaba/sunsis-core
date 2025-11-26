<?php
class ttgdcf_from_ilr implements IAction
{
	public function execute(PDO $link)
	{

		$xml = isset($_REQUEST['xml']) ? $_REQUEST['xml'] : '';
		$tr_id = isset($_REQUEST['tr_id']) ? $_REQUEST['tr_id'] : '';
		
		$vo = Ilr2008::loadFromXML($xml);

		$_SESSION['bc']->add($link, "do.php?_action=ttgdcf_from_ilr&xml=" . $xml, "TtG ILR PDF");
		
		// relmes - php 5.3 assigning the return value of new by reference change
		$pdf = new FPDI();

		$pagecount = $pdf->setSourceFile('ttgdatacaptureform.pdf');
		
		$tpl=$pdf->ImportPage(1);
		$s = $pdf->getTemplatesize($tpl);
		$pdf->AddPage('P', array($s['w'], $s['h']));
		$pdf->useTemplate($tpl);

		$pdf->SetFont('Arial', '', 10);

		if ( $vo->learnerinformation->L03 != '' )
              {
			$pcode=str_replace(" ","",$vo->learnerinformation->L03);
			preg_match_all("/([a-zA-Z0-9]{0,6})([a-zA-Z0-9]{0,6})/",$pcode,$matches);

			$pdf->Text(49,39,$this->spaceout($matches[1][0],5));
			$pdf->Text(89,39,$this->spaceout($matches[2][0],5));
		}
		
		
		$pdf->Text(33,51,strtoupper($this->spaceout($vo->learnerinformation->L25,5)));
		$pdf->Text(95,51,strtoupper($this->spaceout($vo->learnerinformation->L44,5)));
		$pdf->Text(158,51,strtoupper($this->spaceout($vo->learnerinformation->L01,5)));

		$pdf->Text(45,61,strtoupper($this->spaceout($vo->learnerinformation->L46,5)));
		
		$l03 = $vo->learnerinformation->L09;
		
		if ( $vo->learnerinformation->L45 != '' )
              {
			$pcode=str_replace(" ","",$vo->learnerinformation->L45);
			preg_match_all("/([a-zA-Z0-9]{0,5})([a-zA-Z0-9]{0,5})/",$pcode,$matches);

			$pdf->Text(133,61,$this->spaceout($matches[1][0],5));
			$pdf->Text(166,61,$this->spaceout($matches[2][0],5));
		}
		
		
		$pdf->Text(45,80,strtoupper($vo->learnerinformation->L09));

		if ( $vo->learnerinformation->L26 != '' )
		{
			preg_match_all("/(^[a-zA-Z]{2})([0-9]{2})([0-9]{2})([0-9]{2})([a-zA-Z]{1})/",$vo->learnerinformation->L26,$matches);
			$pdf->Text(140,80,$this->spaceout($matches[1][0],5));
			$pdf->Text(153,80,$this->spaceout($matches[2][0],5));
			$pdf->Text(166,80,$this->spaceout($matches[3][0],5));
			$pdf->Text(179,80,$this->spaceout($matches[4][0],5));
			$pdf->Text(192,80,$this->spaceout($matches[5][0],5));
		}

		$pdf->Text(45,90,strtoupper($vo->learnerinformation->L10));
		
		if ( $vo->learnerinformation->L11 != '' && $vo->learnerinformation->L11 != '00000000')
		{
			preg_match_all("/(^[0-9]{2})\/([0-9]{2})\/([0-9]{2})([0-9]{2})/",$vo->learnerinformation->L11,$matches);
			$pdf->Text(140,89,$this->spaceout($matches[1][0],5));
			$pdf->Text(155,89,$this->spaceout($matches[2][0],6));
			$pdf->Text(186,89,$this->spaceout($matches[4][0],5));
		}

		$matches=array();
		if ( $vo->learnerinformation->L17 != '' )
	    {
			$pcode=str_replace(" ","",$vo->learnerinformation->L17);
			preg_match_all("/(^[a-zA-Z]{1,2}[0-9]{1,2}[a-zA-Z]{0,1})([0-9]{1}[a-zA-Z]{2}$)/",$pcode,$matches);

			$pdf->Text(45,101,$this->spaceout($matches[1][0],7));
			$pdf->Text(84,101,$this->spaceout($matches[2][0],7));
		}
		
		$pdf->Text(140,98,$vo->learnerinformation->L13);
		$pdf->Text(52,115,$vo->learnerinformation->L18);

		$pdf->Text(141,112,$this->spaceout($vo->learnerinformation->L12,8));
		$pdf->Text(45,121,$vo->learnerinformation->L19);
		$pdf->Text(163,121,$this->spaceout($vo->learnerinformation->L24,0));
		$pdf->Text(45,127,strtoupper($vo->learnerinformation->L20));
		$pdf->Text(45,134,strtoupper($vo->learnerinformation->L21));
		
		if ( $vo->learnerinformation->L22 != '' )
              {
			$pcode=str_replace(" ","",$vo->learnerinformation->L22);
			preg_match_all("/(^[a-zA-Z]{1,2}[0-9]{1,2}[a-zA-Z]{0,1})([0-9]{1}[a-zA-Z]{2}$)/",$pcode,$matches);

			$pdf->Text(45,145,$this->spaceout($matches[1][0],7));
			$pdf->Text(84,145,$this->spaceout($matches[2][0],7));
		}
		
		$pdf->Text(135,146,$vo->learnerinformation->L23);
		
		$pdf->Text(172,166,$vo->learnerinformation->L14);

		$pdf->Text(172,176,$this->spaceout(str_pad($vo->learnerinformation->L15,2,'0',STR_PAD_LEFT),8));

		$pdf->Text(172,185,$this->spaceout(str_pad($vo->learnerinformation->L16,2,'0',STR_PAD_LEFT),8));

		$pdf->Text(74,196,$this->spaceout($vo->learnerinformation->L34a));
		$pdf->Text(87,196,$this->spaceout($vo->learnerinformation->L34b));
		$pdf->Text(100,196,$this->spaceout($vo->learnerinformation->L34c,6));
		
		$pdf->Text(74,206,$this->spaceout(str_pad($vo->learnerinformation->L37,2,'0',STR_PAD_LEFT)));

		$pdf->Text(186,205,$this->spaceout(str_pad($vo->learnerinformation->L47,2,'0',STR_PAD_LEFT),5));
		
		
//		$pdf->Text(186,243,$this->spaceout(str_pad($vo->learnerinformation->L36,2,'0',STR_PAD_LEFT)));

		if ( $vo->learnerinformation->L48 != '' && $vo->learnerinformation->L48 != '00000000' )
		{
			preg_match_all("/(^[0-9]{2})\/([0-9]{2})\/([0-9]{2})([0-9]{2})/",$vo->learnerinformation->L48,$matches);
			$pdf->Text(73,214,$this->spaceout($matches[1][0]));
			$pdf->Text(87,214,$this->spaceout($matches[2][0]));
			//$pdf->Text(103,214,$this->spaceout($matches[3][0],6));
			$pdf->Text(116,214,$this->spaceout($matches[4][0],5));
		}
		
		$pdf->Text(170,216,$this->spaceout($vo->learnerinformation->L28a,5));
		$pdf->Text(186,216,$this->spaceout($vo->learnerinformation->L28b,5));
		
		
		
		
		
//		switch($vo->learnerinformation->L27)
//		{
//			case '1':
//				$pdf->Image("./images/register/small-tick2.gif",243,151,5,5);
//				$pdf->Image("./images/register/small-tick2.gif",243,163,5,5);
//				break;
//			case '3':
//				$pdf->Image("./images/register/small-tick2.gif",243,163,5,5);
//				break;
//			case '4':
//				$pdf->Image("./images/register/small-tick2.gif",243,151,5,5);
//				break;
//		}

//		$pdf->Text(38,198,$this->spaceout($vo->learnerinformation->L39));
		
		$tpl=$pdf->ImportPage(2);
		$s = $pdf->getTemplatesize($tpl);
		$pdf->AddPage('P', array($s['w'], $s['h']));
		$pdf->useTemplate($tpl);

		$pdf->Text(80,29,strtoupper($vo->learnerinformation->L10 . " " . $vo->learnerinformation->L09));
		
		if ( $vo->learnerinformation->L03 != '' )
              {
			$pcode=str_replace(" ","",$vo->learnerinformation->L03);
			preg_match_all("/([a-zA-Z0-9]{0,6})([a-zA-Z0-9]{0,6})/",$pcode,$matches);

			$pdf->Text(73,38,$this->spaceout($matches[1][0],5));
			$pdf->Text(111,38,$this->spaceout($matches[2][0],5));
		}

		$pdf->Text(63,151,$this->spaceout(str_pad($vo->learnerinformation->L35,2,'0',STR_PAD_LEFT)));

		
		$tpl=$pdf->ImportPage(3);
		$s = $pdf->getTemplatesize($tpl);
		$pdf->AddPage('P', array($s['w'], $s['h']));
		$pdf->useTemplate($tpl);

		$pdf->Text(80,29,strtoupper($vo->learnerinformation->L10 . " " . $vo->learnerinformation->L09));
		
		if ( $vo->learnerinformation->L03 != '' )
              {
			$pcode=str_replace(" ","",$vo->learnerinformation->L03);
			preg_match_all("/([a-zA-Z0-9]{0,6})([a-zA-Z0-9]{0,6})/",$pcode,$matches);

			$pdf->Text(73,38,$this->spaceout($matches[1][0],5));
			$pdf->Text(111,38,$this->spaceout($matches[2][0],5));
		}
		
		$tr = TrainingRecord::loadFromDatabase($link, $tr_id);
		$employer = Organisation::loadFromDatabase($link, $tr->employer_id);
		$location = Location::loadFromDatabase($link, $tr->employer_location_id);
		
		$pdf->Text(45,120,$employer->legal_name);
		
		if($location->contact_telephone!='')
			$pdf->Text(45,129,$location->contact_telephone);
		else
			$pdf->Text(45,129,$location->telephone);
		$pdf->Text(145,120,$location->contact_name);
		$pdf->Text(132,129,$location->contact_email);

		if ( $vo->aims[0]->A09 != '' )
              {
			$pcode=str_replace(" ","",$vo->aims[0]->A09);
			preg_match_all("/([a-zA-Z0-9]{0,4})([a-zA-Z0-9]{0,4})/",$pcode,$matches);

			$pdf->Text(64,153,$this->spaceout($matches[1][0],4));
			$pdf->Text(87,153,$this->spaceout($matches[2][0],4));
		}
		
		$pdf->Text(182,155,$this->spaceout($vo->aims[0]->A10,5));
		$pdf->Text(182,165,$this->spaceout($vo->aims[0]->A53,5));

		if ( $vo->aims[0]->A27 != '' && $vo->aims[0]->A27 != '00000000')
		{
			preg_match_all("/(^[0-9]{2})\/([0-9]{2})\/([0-9]{2})([0-9]{2})/",$vo->aims[0]->A27,$matches);
			$pdf->Text(61,172,$this->spaceout($matches[1][0],7));
			$pdf->Text(80,172,$this->spaceout($matches[2][0],7));
			$pdf->Text(117,172,$this->spaceout($matches[4][0],7));
		}
		
		
//		$pdf->Text(98,18,strtoupper($this->spaceout($vo->learnerinformation->L25,5)));
//		$pdf->Text(17,37,strtoupper($this->spaceout($vo->learnerinformation->L01,5)));

//		$pdf->Text(63,37,strtoupper($this->spaceout($vo->learnerinformation->L46,5)));
		
		
//		$pdf->Text(101,21,strtoupper($this->spaceout($vo->learnerinformation->L44)));

		if ( $vo->learnerinformation->L03 != '' )
              {
			$pcode=str_replace(" ","",$vo->learnerinformation->L03);
			preg_match_all("/([a-zA-Z0-9]{0,6})([a-zA-Z0-9]{0,6})/",$pcode,$matches);

//			$pdf->Text(117,37,$this->spaceout($matches[1][0],5));
//			$pdf->Text(157,37,$this->spaceout($matches[2][0],5));
		}

		
//		$l03 = $vo->learnerinformation->L09;
		
		
		
		if ( $vo->learnerinformation->L45 != '' )
              {
			$pcode=str_replace(" ","",$vo->learnerinformation->L45);
			preg_match_all("/([a-zA-Z0-9]{0,5})([a-zA-Z0-9]{0,5})/",$pcode,$matches);

//			$pdf->Text(130,44,$this->spaceout($matches[1][0],5));
//			$pdf->Text(163,44,$this->spaceout($matches[2][0],5));
		}
			
		
			
		if ( $vo->aims[0]->A45 != '' )
        {
			$pcode=str_replace(" ","",$vo->aims[0]->A45);
			preg_match_all("/(^[a-zA-Z]{1,2}[0-9]{1,2}[a-zA-Z]{0,1})([0-9]{1}[a-zA-Z]{2}$)/",$pcode,$matches);

//			$pdf->Text(26,252,$this->spaceout($matches[1][0],7));
//			$pdf->Text(63,252,$this->spaceout($matches[2][0],7));
		}
			
		if ( $vo->aims[0]->A23 != '' )
        {
			$pcode=str_replace(" ","",$vo->aims[0]->A23);
			preg_match_all("/(^[a-zA-Z]{1,2}[0-9]{1,2}[a-zA-Z]{0,1})([0-9]{1}[a-zA-Z]{2}$)/",$pcode,$matches);

//			$pdf->Text(128,252,$this->spaceout($matches[1][0],7));
//			$pdf->Text(165,252,$this->spaceout($matches[2][0],7));
		}

		
		$tpl=$pdf->ImportPage(4);
		$s = $pdf->getTemplatesize($tpl);
		$pdf->AddPage('P', array($s['w'], $s['h']));
		$pdf->useTemplate($tpl);

		$pdf->Text(98,18,strtoupper($this->spaceout($vo->learnerinformation->L25,5)));
		$pdf->Text(17,37,strtoupper($this->spaceout($vo->learnerinformation->L01,5)));

		$pdf->Text(63,37,strtoupper($this->spaceout($vo->learnerinformation->L46,5)));
		
		
		$pdf->Text(101,21,strtoupper($this->spaceout($vo->learnerinformation->L44)));

		if ( $vo->learnerinformation->L03 != '' )
              {
			$pcode=str_replace(" ","",$vo->learnerinformation->L03);
			preg_match_all("/([a-zA-Z0-9]{0,6})([a-zA-Z0-9]{0,6})/",$pcode,$matches);

			$pdf->Text(117,37,$this->spaceout($matches[1][0],5));
			$pdf->Text(157,37,$this->spaceout($matches[2][0],5));
		}

		
		$l03 = $vo->learnerinformation->L09;
		
		
		
		if ( $vo->learnerinformation->L45 != '' )
              {
			$pcode=str_replace(" ","",$vo->learnerinformation->L45);
			preg_match_all("/([a-zA-Z0-9]{0,5})([a-zA-Z0-9]{0,5})/",$pcode,$matches);

			$pdf->Text(130,44,$this->spaceout($matches[1][0],5));
			$pdf->Text(163,44,$this->spaceout($matches[2][0],5));
		}
		
		if ( $vo->aims[0]->A09 != '' )
              {
			$pcode=str_replace(" ","",$vo->aims[0]->A09);
			preg_match_all("/([a-zA-Z0-9]{0,4})([a-zA-Z0-9]{0,4})/",$pcode,$matches);

			$pdf->Text(61,61,$this->spaceout($matches[1][0],3));
			$pdf->Text(81,61,$this->spaceout($matches[2][0],3));
		}

		$pdf->Text(63,70,$this->spaceout(str_pad($vo->aims[0]->A18,2,'0',STR_PAD_LEFT),6));
		$pdf->Text(70,80,$this->spaceout(str_pad($vo->aims[0]->A14,2,'0',STR_PAD_LEFT),5));
		$pdf->Text(163,80,$this->spaceout($vo->aims[0]->A53,5));
		$pdf->Text(147,89,$this->spaceout($vo->aims[0]->A24,3));

		if ( $vo->aims[0]->A54 != '' )
        {
			$pcode=str_replace(" ","",$vo->aims[0]->A54);
			preg_match_all("/([a-zA-Z0-9]{0,5})([a-zA-Z0-9]{0,5})/",$pcode,$matches);

			$pdf->Text(26,102,$this->spaceout($matches[1][0],6));
			$pdf->Text(64,102,$this->spaceout($matches[2][0],6));
		}

		if ( $vo->aims[0]->A27 != '' && $vo->aims[0]->A27 != '00000000')
		{
			preg_match_all("/(^[0-9]{2})\/([0-9]{2})\/([0-9]{2})([0-9]{2})/",$vo->aims[0]->A27,$matches);
			$pdf->Text(26,114,$this->spaceout($matches[1][0],7));
			$pdf->Text(45,114,$this->spaceout($matches[2][0],7));
			$pdf->Text(82,114,$this->spaceout($matches[4][0],7));
		}

		if ( $vo->aims[0]->A28 != '' && $vo->aims[0]->A28 != '00000000')
		{
			preg_match_all("/(^[0-9]{2})\/([0-9]{2})\/([0-9]{2})([0-9]{2})/",$vo->aims[0]->A28,$matches);
			$pdf->Text(121,114,$this->spaceout($matches[1][0],7));
			$pdf->Text(140,114,$this->spaceout($matches[2][0],7));
			$pdf->Text(178,114,$this->spaceout($matches[4][0],7));
		}

		$row = 200;
		
		for($a=1; $a<=$vo->subaims; $a++)
		{
			if($a==1)
				$row = 200;
			elseif($a==2)
				$row = $row + 40;
			else
				throw new Exception("There are more than two subsidiary aims");
				
			
			if ( $vo->aims[$a]->A09 != '' )
	              {
				$pcode=str_replace(" ","",$vo->aims[$a]->A09);
				preg_match_all("/([a-zA-Z0-9]{0,4})([a-zA-Z0-9]{0,4})/",$pcode,$matches);
	
				$pdf->Text(63,$row-1,$this->spaceout($matches[1][0],3));
				$pdf->Text(83,$row-1,$this->spaceout($matches[2][0],3));
			}
			
			$pdf->Text(178,$row+3,$this->spaceout($vo->aims[$a]->A14,6));
			$pdf->Text(63,$row+9,$this->spaceout(str_pad($vo->aims[$a]->A18,2,'0',STR_PAD_LEFT),5));

			if ( $vo->aims[$a]->A27 != '' && $vo->aims[$a]->A27 != '00000000')
			{
				preg_match_all("/(^[0-9]{2})\/([0-9]{2})\/([0-9]{2})([0-9]{2})/",$vo->aims[$a]->A27,$matches);
				$pdf->Text(26,$row+26,$this->spaceout($matches[1][0],7));
				$pdf->Text(45,$row+26,$this->spaceout($matches[2][0],7));
				$pdf->Text(82,$row+26,$this->spaceout($matches[4][0],7));
			}
	
			if ( $vo->aims[$a]->A28 != '' && $vo->aims[$a]->A28 != '00000000')
			{
				preg_match_all("/(^[0-9]{2})\/([0-9]{2})\/([0-9]{2})([0-9]{2})/",$vo->aims[$a]->A28,$matches);
				$pdf->Text(121,$row+26,$this->spaceout($matches[1][0],7));
				$pdf->Text(140,$row+26,$this->spaceout($matches[2][0],7));
				$pdf->Text(178,$row+26,$this->spaceout($matches[4][0],7));
			}
		}		

		$tpl=$pdf->ImportPage(5);
		$s = $pdf->getTemplatesize($tpl);
		$pdf->AddPage('P', array($s['w'], $s['h']));
		$pdf->useTemplate($tpl);

		$pdf->Text(98,18,strtoupper($this->spaceout($vo->learnerinformation->L25,5)));
		$pdf->Text(17,37,strtoupper($this->spaceout($vo->learnerinformation->L01,5)));

		$pdf->Text(63,37,strtoupper($this->spaceout($vo->learnerinformation->L46,5)));
		
		
		$pdf->Text(101,21,strtoupper($this->spaceout($vo->learnerinformation->L44)));

		if ( $vo->learnerinformation->L03 != '' )
              {
			$pcode=str_replace(" ","",$vo->learnerinformation->L03);
			preg_match_all("/([a-zA-Z0-9]{0,6})([a-zA-Z0-9]{0,6})/",$pcode,$matches);

			$pdf->Text(117,37,$this->spaceout($matches[1][0],5));
			$pdf->Text(157,37,$this->spaceout($matches[2][0],5));
		}

		
		$l03 = $vo->learnerinformation->L09;
		
		
		
		if ( $vo->learnerinformation->L45 != '' )
              {
			$pcode=str_replace(" ","",$vo->learnerinformation->L45);
			preg_match_all("/([a-zA-Z0-9]{0,5})([a-zA-Z0-9]{0,5})/",$pcode,$matches);

			$pdf->Text(130,44,$this->spaceout($matches[1][0],5));
			$pdf->Text(163,44,$this->spaceout($matches[2][0],5));
		}
		
		$pdf->Text(86,60,$this->spaceout($vo->learnerinformation->L40a));
		$pdf->Text(99,60,$this->spaceout($vo->learnerinformation->L40b));
		
		$pdf->Text(85,67,$this->spaceout($vo->learnerinformation->L41a,5));
		
		$pdf->Text(85,75,$this->spaceout($vo->learnerinformation->L42a,5));
		
		$pdf->Text(85,84,$this->spaceout($vo->aims[0]->A47a,5));
		$pdf->Text(85,92,$this->spaceout($vo->aims[0]->A47b,5));
		
/*		
		$pdf->Text(111,46,$this->spaceout($vo->aims[0]->A10));
		$pdf->Text(148,46,$this->spaceout(str_pad($vo->aims[0]->A15,2,'0',STR_PAD_LEFT)));
		$pdf->Text(186,46,$this->spaceout(str_pad($vo->aims[0]->A16,2,'0',STR_PAD_LEFT)));
		$pdf->Text(225,46,$this->spaceout($vo->aims[0]->A26));
		
		$pdf->Text(36,69,$this->spaceout($vo->aims[0]->A51a));
		$pdf->Text(131,69,$this->spaceout($vo->aims[0]->A59,5));
		$pdf->Text(185,69,$this->spaceout(str_pad($vo->aims[0]->A46a,3,'0',STR_PAD_LEFT),5));
		$pdf->Text(207,69,$this->spaceout(str_pad($vo->aims[0]->A46b,3,'0',STR_PAD_LEFT),5));
		$pdf->Text(258,69,$this->spaceout($vo->aims[0]->A49,5));

		$pdf->Text(95,80,$vo->aims[0]->A44);
		
		

		
		$pdf->Text(208,92,$this->spaceout($vo->aims[0]->A32,5));
		$pdf->Text(281,106,$this->spaceout($vo->aims[0]->A06));
		

		if ( $vo->aims[0]->A31 != '' && $vo->aims[0]->A31 != '00000000')
		{
			preg_match_all("/(^[0-9]{2})\/([0-9]{2})\/([0-9]{2})([0-9]{2})/",$vo->aims[0]->A31,$matches);
			$pdf->Text(46,124,$this->spaceout($matches[1][0]));
			$pdf->Text(61,124,$this->spaceout($matches[2][0]));
			$pdf->Text(89,124,$this->spaceout($matches[4][0]));
		}
		
		if ( $vo->aims[0]->A40 != '' && $vo->aims[0]->A40 != '00000000')
		{
			preg_match_all("/(^[0-9]{2})\/([0-9]{2})\/([0-9]{2})([0-9]{2})/",$vo->aims[0]->A40,$matches);
			$pdf->Text(163,124,$this->spaceout($matches[1][0]));
			$pdf->Text(178,124,$this->spaceout($matches[2][0]));
			$pdf->Text(206,124,$this->spaceout($matches[4][0]));
		}
		
		$pdf->Text(45,135,$vo->aims[0]->A34);
		$pdf->Text(94,135,$vo->aims[0]->A35);
		$pdf->Text(164,135,$this->spaceout($vo->aims[0]->A36));

		$pdf->Text(96,147,$this->spaceout($vo->aims[0]->A37));
		$pdf->Text(163,147,$this->spaceout($vo->aims[0]->A38));
		$pdf->Text(221,147,$this->spaceout($vo->aims[0]->A50));
		$pdf->Text(274,147,$this->spaceout($vo->aims[0]->A60));

		if ( $vo->aims[0]->A47a != '' )
              {
			$pcode=str_replace(" ","",$vo->aims[0]->A47a);
			preg_match_all("/([a-zA-Z0-9]{0,6})([a-zA-Z0-9]{0,6})/",$pcode,$matches);

			$pdf->Text(76,182,$this->spaceout($matches[1][0],6));
			$pdf->Text(121,182,$this->spaceout($matches[2][0],6));
		}
		
		if ( $vo->aims[0]->A47b != '' )
              {
			$pcode=str_replace(" ","",$vo->aims[0]->A47b);
			preg_match_all("/([a-zA-Z0-9]{0,6})([a-zA-Z0-9]{0,6})/",$pcode,$matches);

			$pdf->Text(169,182,$this->spaceout($matches[1][0],6));
			$pdf->Text(213,182,$this->spaceout($matches[2][0],6));
		}
		
		if ( $vo->aims[0]->A48a != '' )
              {
			$pcode=str_replace(" ","",$vo->aims[0]->A48a);
			preg_match_all("/([a-zA-Z0-9]{0,6})([a-zA-Z0-9]{0,6})/",$pcode,$matches);

			$pdf->Text(76,193,$this->spaceout($matches[1][0],6));
			$pdf->Text(121,193,$this->spaceout($matches[2][0],6));
		}
		
		if ( $vo->aims[0]->A48b != '' )
              {
			$pcode=str_replace(" ","",$vo->aims[0]->A48b);
			preg_match_all("/([a-zA-Z0-9]{0,6})([a-zA-Z0-9]{0,6})/",$pcode,$matches);

			$pdf->Text(169,193,$this->spaceout($matches[1][0],6));
			$pdf->Text(213,193,$this->spaceout($matches[2][0],6));
		}
		


		// Subsidiary Aims
		for($a=1; $a<=$vo->subaims; $a++)
		{

			if($vo->aims[0]->A10!='45' || $vo->aims[0]->A15!='99' || ($vo->aims[0]->A18!='22' && $vo->aims[0]->A18!='23') )		
			{
		
				if( ($a%2) != 0)
				{
					$tpl=$pdf->ImportPage(4);
					$s = $pdf->getTemplatesize($tpl);
					$pdf->AddPage('P', array($s['w'], $s['h']));
					$pdf->useTemplate($tpl);
	
					$pdf->Text(85,21,$vo->learnerinformation->L10 . ' ' . $vo->learnerinformation->L09);
					
					if ( $vo->learnerinformation->L03 != '' )
			              {
						$pcode=str_replace(" ","",$vo->learnerinformation->L03);
						preg_match_all("/([a-zA-Z0-9]{0,6})([a-zA-Z0-9]{0,6})/",$pcode,$matches);
			
						$pdf->Text(169,21,$this->spaceout($matches[1][0]));
						$pdf->Text(207,21,$this->spaceout($matches[2][0]));
					}
	
					if ( $vo->aims[$a]->A09 != '' )
			              {
						$pcode=str_replace(" ","",$vo->aims[$a]->A09);
						preg_match_all("/([a-zA-Z0-9]{0,4})([a-zA-Z0-9]{0,4})/",$pcode,$matches);
			
						$pdf->Text(41,46,$this->spaceout($matches[1][0]));
						$pdf->Text(66,46,$this->spaceout($matches[2][0]));
					}
					
					$pdf->Text(111,46,$this->spaceout($vo->aims[$a]->A10));
					//$pdf->Text(148,46,$this->spaceout($vo->aims[$a]->A15));
					$pdf->Text(186,46,$this->spaceout(str_pad($vo->aims[$a]->A16,2,'0',STR_PAD_LEFT)));
					//$pdf->Text(225,46,$this->spaceout($vo->aims[$a]->A26));
					$pdf->Text(279,46,$this->spaceout($vo->aims[$a]->A53));
					
			
					if ( $vo->aims[$a]->A27 != '' && $vo->aims[$a]->A27 != '00000000')
					{
						preg_match_all("/(^[0-9]{2})\/([0-9]{2})\/([0-9]{2})([0-9]{2})/",$vo->aims[$a]->A27,$matches);
						$pdf->Text(47,56,$this->spaceout($matches[1][0]));
						$pdf->Text(62,56,$this->spaceout($matches[2][0]));
						$pdf->Text(90,56,$this->spaceout($matches[4][0]));
					}
					
					if ( $vo->aims[$a]->A28 != '' && $vo->aims[$a]->A28 != '00000000')
					{
						preg_match_all("/(^[0-9]{2})\/([0-9]{2})\/([0-9]{2})([0-9]{2})/",$vo->aims[$a]->A28,$matches);
						$pdf->Text(138,56,$this->spaceout($matches[1][0]));
						$pdf->Text(153,56,$this->spaceout($matches[2][0]));
						$pdf->Text(181,56,$this->spaceout($matches[4][0]));
					}
			
					if ( $vo->aims[$a]->A23 != '' )
			              {
						$pcode=str_replace(" ","",$vo->aims[$a]->A23);
						preg_match_all("/(^[a-zA-Z]{1,2}[0-9]{1,2}[a-zA-Z]{0,1})([0-9]{1}[a-zA-Z]{2}$)/",$pcode,$matches);
			
						$pdf->Text(245,56,$this->spaceout($matches[1][0]));
						$pdf->Text(272,56,$this->spaceout($matches[2][0],5));
					}
					
					$pdf->Text(36,66,$this->spaceout(str_pad($vo->aims[$a]->A51a,2,'0',STR_PAD_LEFT)));
					$pdf->Text(131,66,$this->spaceout($vo->aims[$a]->A59,5));
					$pdf->Text(185,66,$this->spaceout($vo->aims[$a]->A46a,5));
					$pdf->Text(207,66,$this->spaceout($vo->aims[$a]->A46b,5));
					$pdf->Text(256,66,$this->spaceout($vo->aims[$a]->A49,5));
					
					if ( $vo->aims[$a]->A31 != '' && $vo->aims[$a]->A31 != '00000000')
					{
						preg_match_all("/(^[0-9]{2})\/([0-9]{2})\/([0-9]{2})([0-9]{2})/",$vo->aims[$a]->A31,$matches);
						$pdf->Text(44,78,$this->spaceout($matches[1][0]));
						$pdf->Text(59,78,$this->spaceout($matches[2][0]));
						$pdf->Text(87,78,$this->spaceout($matches[4][0]));
					}
					
					if ( $vo->aims[$a]->A40 != '' && $vo->aims[$a]->A40 != '00000000')
					{
						preg_match_all("/(^[0-9]{2})\/([0-9]{2})\/([0-9]{2})([0-9]{2})/",$vo->aims[$a]->A40,$matches);
						$pdf->Text(128,78,$this->spaceout($matches[1][0]));
						$pdf->Text(143,78,$this->spaceout($matches[2][0]));
						$pdf->Text(171,78,$this->spaceout($matches[4][0]));
					}
					
					$pdf->Text(210,78,$vo->aims[$a]->A34);
					$pdf->Text(240,78,$vo->aims[$a]->A35);
					$pdf->Text(271,78,$this->spaceout($vo->aims[$a]->A36));
							
					$pdf->Text(106,89,$this->spaceout($vo->aims[$a]->A37));
					$pdf->Text(171,89,$this->spaceout($vo->aims[$a]->A38));
					$pdf->Text(218,89,$this->spaceout($vo->aims[$a]->A50));
					$pdf->Text(272,89,$this->spaceout($vo->aims[$a]->A60));
				
					if ( $vo->aims[$a]->A47a != '' )
			        {
						$pcode=str_replace(" ","",$vo->aims[$a]->A47a);
						preg_match_all("/([a-zA-Z0-9]{0,6})([a-zA-Z0-9]{0,6})/",$pcode,$matches);
			
						$pdf->Text(79,101,$this->spaceout($matches[1][0],6));
						$pdf->Text(124,101,$this->spaceout($matches[2][0],6));
					}
					
					if ( $vo->aims[$a]->A47b != '' )
			        {
						$pcode=str_replace(" ","",$vo->aims[$a]->A47b);
						preg_match_all("/([a-zA-Z0-9]{0,6})([a-zA-Z0-9]{0,6})/",$pcode,$matches);
			
						$pdf->Text(172,101,$this->spaceout($matches[1][0],6));
						$pdf->Text(216,101,$this->spaceout($matches[2][0],6));
					}
					
					if ( $vo->aims[$a]->A48a != '' )
			        {
						$pcode=str_replace(" ","",$vo->aims[$a]->A48a);
						preg_match_all("/([a-zA-Z0-9]{0,6})([a-zA-Z0-9]{0,6})/",$pcode,$matches);
			
						$pdf->Text(79,109,$this->spaceout($matches[1][0],6));
						$pdf->Text(124,109,$this->spaceout($matches[2][0],6));
					}
					
					if ( $vo->aims[$a]->A48b != '' )
			        {
						$pcode=str_replace(" ","",$vo->aims[$a]->A48b);
						preg_match_all("/([a-zA-Z0-9]{0,6})([a-zA-Z0-9]{0,6})/",$pcode,$matches);
			
						$pdf->Text(172,109,$this->spaceout($matches[1][0],6));
						$pdf->Text(216,109,$this->spaceout($matches[2][0],6));
					}
					
					$pdf->Text(279,106,$this->spaceout($vo->aims[$a]->A06));
					
					
				}
				else
				{
					if ( $vo->aims[$a]->A09 != '' )
			              {
						$pcode=str_replace(" ","",$vo->aims[$a]->A09);
						preg_match_all("/([a-zA-Z0-9]{0,4})([a-zA-Z0-9]{0,4})/",$pcode,$matches);
			
						$pdf->Text(41,124,$this->spaceout($matches[1][0]));
						$pdf->Text(66,124,$this->spaceout($matches[2][0]));
					}
					
					$pdf->Text(111,124,$this->spaceout($vo->aims[$a]->A10));
					//$pdf->Text(148,46,$this->spaceout($vo->aims[$a]->A15));
					$pdf->Text(186,124,$this->spaceout($vo->aims[$a]->A16));
					//$pdf->Text(225,46,$this->spaceout($vo->aims[$a]->A26));
					$pdf->Text(279,124,$this->spaceout($vo->aims[$a]->A53));
					
			
					if ( $vo->aims[$a]->A27 != '' && $vo->aims[$a]->A27 != '00000000')
					{
						preg_match_all("/(^[0-9]{2})\/([0-9]{2})\/([0-9]{2})([0-9]{2})/",$vo->aims[$a]->A27,$matches);
						$pdf->Text(47,134,$this->spaceout($matches[1][0]));
						$pdf->Text(62,134,$this->spaceout($matches[2][0]));
						$pdf->Text(90,134,$this->spaceout($matches[4][0]));
					}
					
					if ( $vo->aims[$a]->A28 != '' && $vo->aims[$a]->A28 != '00000000')
					{
						preg_match_all("/(^[0-9]{2})\/([0-9]{2})\/([0-9]{2})([0-9]{2})/",$vo->aims[$a]->A28,$matches);
						$pdf->Text(138,134,$this->spaceout($matches[1][0]));
						$pdf->Text(153,134,$this->spaceout($matches[2][0]));
						$pdf->Text(181,134,$this->spaceout($matches[4][0]));
					}
			
					if ( $vo->aims[$a]->A23 != '' )
			              {
						$pcode=str_replace(" ","",$vo->aims[$a]->A23);
						preg_match_all("/(^[a-zA-Z]{1,2}[0-9]{1,2}[a-zA-Z]{0,1})([0-9]{1}[a-zA-Z]{2}$)/",$pcode,$matches);
			
						$pdf->Text(245,134,$this->spaceout($matches[1][0]));
						$pdf->Text(272,134,$this->spaceout($matches[2][0],5));
					}
					
					$pdf->Text(36,143,$this->spaceout($vo->aims[$a]->A51a));
					$pdf->Text(80,143,$this->spaceout($vo->aims[$a]->A14));
					$pdf->Text(131,143,$this->spaceout($vo->aims[$a]->A59,5));
					$pdf->Text(185,143,$this->spaceout($vo->aims[$a]->A46a,5));
					$pdf->Text(207,143,$this->spaceout($vo->aims[$a]->A46b,5));
					$pdf->Text(256,143,$this->spaceout($vo->aims[$a]->A49,5));
					
					if ( $vo->aims[$a]->A31 != '' && $vo->aims[$a]->A31 != '00000000')
					{
						preg_match_all("/(^[0-9]{2})\/([0-9]{2})\/([0-9]{2})([0-9]{2})/",$vo->aims[$a]->A31,$matches);
						$pdf->Text(44,155,$this->spaceout($matches[1][0]));
						$pdf->Text(59,155,$this->spaceout($matches[2][0]));
						$pdf->Text(87,155,$this->spaceout($matches[4][0]));
					}
					
					if ( $vo->aims[$a]->A40 != '' && $vo->aims[$a]->A40 != '00000000')
					{
						preg_match_all("/(^[0-9]{2})\/([0-9]{2})\/([0-9]{2})([0-9]{2})/",$vo->aims[$a]->A40,$matches);
						$pdf->Text(128,155,$this->spaceout($matches[1][0]));
						$pdf->Text(143,155,$this->spaceout($matches[2][0]));
						$pdf->Text(171,155,$this->spaceout($matches[4][0]));
					}
					
					$pdf->Text(210,155,$vo->aims[$a]->A34);
					$pdf->Text(240,155,$vo->aims[$a]->A35);
					$pdf->Text(271,155,$this->spaceout($vo->aims[$a]->A36));
							
					$pdf->Text(106,166,$this->spaceout($vo->aims[$a]->A37));
					$pdf->Text(171,166,$this->spaceout($vo->aims[$a]->A38));
					$pdf->Text(218,166,$this->spaceout($vo->aims[$a]->A50));
					$pdf->Text(272,166,$this->spaceout($vo->aims[$a]->A60));
				
					if ( $vo->aims[$a]->A47a != '' )
			        {
						$pcode=str_replace(" ","",$vo->aims[$a]->A47a);
						preg_match_all("/([a-zA-Z0-9]{0,6})([a-zA-Z0-9]{0,6})/",$pcode,$matches);
			
						$pdf->Text(79,178,$this->spaceout($matches[1][0],6));
						$pdf->Text(124,178,$this->spaceout($matches[2][0],6));
					}
					
					if ( $vo->aims[$a]->A47b != '' )
			        {
						$pcode=str_replace(" ","",$vo->aims[$a]->A47b);
						preg_match_all("/([a-zA-Z0-9]{0,6})([a-zA-Z0-9]{0,6})/",$pcode,$matches);
			
						$pdf->Text(172,178,$this->spaceout($matches[1][0],6));
						$pdf->Text(216,178,$this->spaceout($matches[2][0],6));
					}
					
					if ( $vo->aims[$a]->A48a != '' )
			        {
						$pcode=str_replace(" ","",$vo->aims[$a]->A48a);
						preg_match_all("/([a-zA-Z0-9]{0,6})([a-zA-Z0-9]{0,6})/",$pcode,$matches);
			
						$pdf->Text(79,187,$this->spaceout($matches[1][0],6));
						$pdf->Text(124,187,$this->spaceout($matches[2][0],6));
					}
					
					if ( $vo->aims[$a]->A48b != '' )
			        {
						$pcode=str_replace(" ","",$vo->aims[$a]->A48b);
						preg_match_all("/([a-zA-Z0-9]{0,6})([a-zA-Z0-9]{0,6})/",$pcode,$matches);
			
						$pdf->Text(172,187,$this->spaceout($matches[1][0],6));
						$pdf->Text(216,187,$this->spaceout($matches[2][0],6));
					}
					
					$pdf->Text(279,186,$this->spaceout($vo->aims[$a]->A06));
				
				}
			}
			else
			{
				$tpl=$pdf->ImportPage(3);
				$s = $pdf->getTemplatesize($tpl);
				$pdf->AddPage('P', array($s['w'], $s['h']));
				$pdf->useTemplate($tpl);
		
				$pdf->Text(85,21,$vo->learnerinformation->L10 . ' ' . $vo->learnerinformation->L09);
				
				if ( $vo->learnerinformation->L03 != '' )
		              {
					$pcode=str_replace(" ","",$vo->learnerinformation->L03);
					preg_match_all("/([a-zA-Z0-9]{0,6})([a-zA-Z0-9]{0,6})/",$pcode,$matches);
		
					$pdf->Text(169,21,$this->spaceout($matches[1][0]));
					$pdf->Text(207,21,$this->spaceout($matches[2][0]));
				}
				
				
				if ( $vo->aims[$a]->A09 != '' )
		              {
					$pcode=str_replace(" ","",$vo->aims[$a]->A09);
					preg_match_all("/([a-zA-Z0-9]{0,4})([a-zA-Z0-9]{0,4})/",$pcode,$matches);
		
					$pdf->Text(41,46,$this->spaceout($matches[1][0]));
					$pdf->Text(66,46,$this->spaceout($matches[2][0]));
				}
				
				$pdf->Text(111,46,$this->spaceout($vo->aims[$a]->A10));
				$pdf->Text(148,46,$this->spaceout(str_pad($vo->aims[$a]->A15,2,'0',STR_PAD_LEFT)));
				$pdf->Text(186,46,$this->spaceout(str_pad($vo->aims[$a]->A16,2,'0',STR_PAD_LEFT)));
				$pdf->Text(225,46,$this->spaceout($vo->aims[$a]->A26));
				$pdf->Text(281,46,$this->spaceout($vo->aims[$a]->A53));
				
		
				if ( $vo->aims[$a]->A27 != '' && $vo->aims[$a]->A27 != '00000000')
				{
					preg_match_all("/(^[0-9]{2})\/([0-9]{2})\/([0-9]{2})([0-9]{2})/",$vo->aims[$a]->A27,$matches);
					$pdf->Text(47,58,$this->spaceout($matches[1][0]));
					$pdf->Text(62,58,$this->spaceout($matches[2][0]));
					$pdf->Text(90,58,$this->spaceout($matches[4][0]));
				}
				
				if ( $vo->aims[$a]->A28 != '' && $vo->aims[$a]->A28 != '00000000')
				{
					preg_match_all("/(^[0-9]{2})\/([0-9]{2})\/([0-9]{2})([0-9]{2})/",$vo->aims[$a]->A28,$matches);
					$pdf->Text(138,58,$this->spaceout($matches[1][0]));
					$pdf->Text(153,58,$this->spaceout($matches[2][0]));
					$pdf->Text(181,58,$this->spaceout($matches[4][0]));
				}
		
				if ( $vo->aims[$a]->A23 != '' )
		              {
					$pcode=str_replace(" ","",$vo->aims[$a]->A23);
					preg_match_all("/(^[a-zA-Z]{1,2}[0-9]{1,2}[a-zA-Z]{0,1})([0-9]{1}[a-zA-Z]{2}$)/",$pcode,$matches);
		
					$pdf->Text(245,58,$this->spaceout($matches[1][0]));
					$pdf->Text(273,58,$this->spaceout($matches[2][0],5));
				}
				
				$pdf->Text(36,69,$this->spaceout($vo->aims[$a]->A51a));
				$pdf->Text(80,69,$this->spaceout(str_pad($vo->aims[$a]->A14,2,'0',STR_PAD_LEFT)));
				$pdf->Text(131,69,$this->spaceout($vo->aims[$a]->A59,5));
				$pdf->Text(185,69,$this->spaceout(str_pad($vo->aims[$a]->A46a,3,'0',STR_PAD_LEFT),5));
				$pdf->Text(207,69,$this->spaceout(str_pad($vo->aims[$a]->A46b,3,'0',STR_PAD_LEFT),5));
				$pdf->Text(258,69,$this->spaceout($vo->aims[$a]->A49,5));
		
				$pdf->Text(41,80,$this->spaceout($vo->aims[$a]->A24));
				$pdf->Text(95,80,$vo->aims[$a]->A44);
				
				
			// Decide what to do there is no A45 in subsdiairy aim but in main aim????	
			//	if ( $vo->aims[$a]->A45 != '' )
		     //         {
			//		$pcode=str_replace(" ","",$vo->aims[$a]->A45);
			//		preg_match_all("/(^[a-zA-Z]{1,2}[0-9]{1,2}[a-zA-Z]{0,1})([0-9]{1}[a-zA-Z]{2}$)/",$pcode,$matches);
	//	
		//			$pdf->Text(245,80,$this->spaceout($matches[1][0]));
			//		$pdf->Text(273,80,$this->spaceout($matches[2][0],5));
			//	}
				
		
				if ( $vo->aims[$a]->A54 != '' )
		              {
					$pcode=str_replace(" ","",$vo->aims[$a]->A54);
					preg_match_all("/([a-zA-Z0-9]{0,5})([a-zA-Z0-9]{0,5})/",$pcode,$matches);
		
					$pdf->Text(40,92,$this->spaceout($matches[1][0]));
					$pdf->Text(73,92,$this->spaceout($matches[2][0]));
				}
				
				$pdf->Text(208,92,$this->spaceout($vo->aims[$a]->A32,5));
				$pdf->Text(281,106,$this->spaceout($vo->aims[$a]->A06));
				
		
				if ( $vo->aims[$a]->A31 != '' && $vo->aims[$a]->A31 != '00000000')
				{
					preg_match_all("/(^[0-9]{2})\/([0-9]{2})\/([0-9]{2})([0-9]{2})/",$vo->aims[$a]->A31,$matches);
					$pdf->Text(46,124,$this->spaceout($matches[1][0]));
					$pdf->Text(61,124,$this->spaceout($matches[2][0]));
					$pdf->Text(89,124,$this->spaceout($matches[4][0]));
				}
				
				if ( $vo->aims[$a]->A40 != '' && $vo->aims[$a]->A40 != '00000000')
				{
					preg_match_all("/(^[0-9]{2})\/([0-9]{2})\/([0-9]{2})([0-9]{2})/",$vo->aims[$a]->A40,$matches);
					$pdf->Text(163,124,$this->spaceout($matches[1][0]));
					$pdf->Text(178,124,$this->spaceout($matches[2][0]));
					$pdf->Text(206,124,$this->spaceout($matches[4][0]));
				}
				
				$pdf->Text(45,135,$vo->aims[$a]->A34);
				$pdf->Text(94,135,$vo->aims[$a]->A35);
				$pdf->Text(164,135,$this->spaceout($vo->aims[$a]->A36));
		
				$pdf->Text(96,147,$this->spaceout($vo->aims[$a]->A37));
				$pdf->Text(163,147,$this->spaceout($vo->aims[$a]->A38));
				$pdf->Text(221,147,$this->spaceout($vo->aims[$a]->A50));
				$pdf->Text(274,147,$this->spaceout($vo->aims[$a]->A60));
		
				if ( $vo->aims[$a]->A47a != '' )
		              {
					$pcode=str_replace(" ","",$vo->aims[$a]->A47a);
					preg_match_all("/([a-zA-Z0-9]{0,6})([a-zA-Z0-9]{0,6})/",$pcode,$matches);
		
					$pdf->Text(76,182,$this->spaceout($matches[1][0],6));
					$pdf->Text(121,182,$this->spaceout($matches[2][0],6));
				}
				
				if ( $vo->aims[$a]->A47b != '' )
		              {
					$pcode=str_replace(" ","",$vo->aims[$a]->A47b);
					preg_match_all("/([a-zA-Z0-9]{0,6})([a-zA-Z0-9]{0,6})/",$pcode,$matches);
		
					$pdf->Text(169,182,$this->spaceout($matches[1][0],6));
					$pdf->Text(213,182,$this->spaceout($matches[2][0],6));
				}
				
				if ( $vo->aims[$a]->A48a != '' )
		              {
					$pcode=str_replace(" ","",$vo->aims[$a]->A48a);
					preg_match_all("/([a-zA-Z0-9]{0,6})([a-zA-Z0-9]{0,6})/",$pcode,$matches);
		
					$pdf->Text(76,193,$this->spaceout($matches[1][0],6));
					$pdf->Text(121,193,$this->spaceout($matches[2][0],6));
				}
				
				if ( $vo->aims[$a]->A48b != '' )
		              {
					$pcode=str_replace(" ","",$vo->aims[$a]->A48b);
					preg_match_all("/([a-zA-Z0-9]{0,6})([a-zA-Z0-9]{0,6})/",$pcode,$matches);
		
					$pdf->Text(169,193,$this->spaceout($matches[1][0],6));
					$pdf->Text(213,193,$this->spaceout($matches[2][0],6));
				}
			}
		}

*/
		
		
		//echo $pdf->Output();

		
		
		
	//Determine a temporary file name in the current directory
	$this->CleanFiles(DATA_ROOT.'/uploads/' . DB_NAME);
	$file = basename(tempnam(DATA_ROOT.'/uploads/' . DB_NAME . '/', 'tmp'));

	$file = DATA_ROOT.'/uploads/' . DB_NAME . '/' . $file;

	rename($file, $file.'.pdf');
	//Save PDF to file
	$pdf->Output($file.'.pdf', 'F');

	//Redirect
	//header('Location: '. $file.'.pdf');		

//	$len=filesize($file.'.pdf');
//	header("content-type: application/pdf");
//	header("content-length: $len");
	//header("content-disposition: attachment; filename=$filename");
//	$fp=fopen($file.'.pdf', "r");
//	fpassthru($fp);

	$download = "ilr" . $l03 . ".pdf";
	header('Content-type: application/pdf');
	header('Content-Disposition: attachment; filename=' . $download);
	readfile($file.'.pdf');	
	
	
	}
	
	public function spaceout($strvalue, $n = 4)
	{
		
		$buffer="";


		if ( $strvalue == '' )
			return $buffer;

		$j = mb_strlen($strvalue);
		for ($k = 0; $k < $j; $k++) {
			$char = mb_substr($strvalue, $k, 1);
			// do stuff with $char
			$buffer = $buffer . $char . str_repeat(' ',$n);

		}
		
		return $buffer;	
	}

	function CleanFiles($dir)
	{
	    //Delete temporary files
	    
	    $t = time();
	    $h = opendir($dir);
	    while($file=readdir($h))
	    {
	    	if(substr($file,0,3)=='tmp')
	        {
	            $path = $dir.'/'.$file;
	            if($t-filemtime($path)>3600)
	                @unlink($path);
	        }
	    }
	    closedir($h);
	}	
	
	
}
?>