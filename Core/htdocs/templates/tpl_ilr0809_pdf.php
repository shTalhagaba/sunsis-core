<?php

		// relmes - php 5.3 assigning the return value of new by reference change
		$pdf = new Cezpdf($paper='A4',$orientation='landscape');
		$pdf->ezSetCmMargins( 0, 0, 0, 0 );
		$pdf->selectFont( "./lib/Helvetica.afm" );

		
		//$pdf->addJpegFromFile(getcwd(). "/images/logos/ilr1200809.jpg",0,0,0);
		//$pdf->ezImage(getcwd(). "/images/logos/ilr1200809.jpg");

		$pdf->rectangle(21,20,809,549);
		
		// L25 Box
		$pdf->rectangle(196,530,57,20);
		//$pdf->line(201+19,522,201+19,522+10);
		//$pdf->line(201+38,522,201+38,522+10);
		
		// L44 Box
		$pdf->rectangle(280,522,57,20);
		$pdf->line(280+19,522,280+19,522+10);
		$pdf->line(280+38,522,280+38,522+10);
		
		// L01 Box
		$pdf->rectangle(354,522,102,20);
		$pdf->line(354+17,522,354+17,522+10);
		$pdf->line(354+34,522,354+34,522+10);
		$pdf->line(354+51,522,354+51,522+10);
		$pdf->line(354+68,522,354+68,522+10);
		$pdf->line(354+85,522,354+85,522+10);
		
		// L03 Box
		$pdf->rectangle(465,522,207,20);
		$pdf->line(465+17,522,465+17,522+10);
		$pdf->line(465+34,522,465+34,522+10);
		$pdf->line(465+51,522,465+51,522+10);
		$pdf->line(465+68,522,465+68,522+10);
		$pdf->line(465+85,522,465+85,522+10);
		$pdf->line(465+102,522,465+102,522+10);
		$pdf->line(465+119,522,465+119,522+10);
		$pdf->line(465+136,522,465+136,522+10);
		$pdf->line(465+153,522,465+153,522+10);
		$pdf->line(465+170,522,465+170,522+10);
		$pdf->line(465+187,522,465+187,522+10);
		
		// Box A
		$pdf->rectangle(780,522,20,20);
		
		// L46 Box
		$pdf->rectangle(349,496,139,20);
		$pdf->line(349+17,496,349+17,496+10);
		$pdf->line(349+34,496,349+34,496+10);
		$pdf->line(349+51,496,349+51,496+10);
		$pdf->line(349+68,496,349+68,496+10);
		$pdf->line(349+85,496,349+85,496+10);
		$pdf->line(349+102,496,349+102,496+10);
		$pdf->line(349+119,496,349+119,496+10);
		
		// L45 Box
		$pdf->rectangle(610,496,159,20);
		$x1 = 610;
		$y1 = 496;
		$i = 16;
		for($a = 1; $a<=9; $a++)
		{
			$pdf->line($x1+$i, $y1, $x1+$i, $y1+10);		
			$i += 16;
		}

		$pdf->rectangle(111,437,224,20);
		$pdf->rectangle(405,437,198,20);
		
		
		
		
		$pdf->addText(134,542,10,"X");
		$pdf->addText(149,542,10,"Y");
		$pdf->addText(164,542,10,"Z");
		
		$pdf->ezStream();

?>