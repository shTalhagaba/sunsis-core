<?php /* print training records */  

	// Get logo
	$filename = DAO::getSingleValue($link, "Select value from configuration where entity='logo'"); 
	$filename = ($filename=='')?'perspective.png':$filename;
	$logo1 = "1_" . $filename;

	//$isPng = str( $filename[, "png"

	$st = $link->query($view->getSQL());	
	if($st)
	{
		header('Content-Type: application/pdf');


		//$all = $pdf->openObject();
		//$pdf->saveState();
		//$pdf->restoreState();
		//$pdf->closeObject();
		//$pdf->addObject($all,'all');
	
		// relmes - php 5.3 assigning the return value of new by reference change
		$pdf = new Cezpdf($paper='A4',$orientation='portrait');
		$pdf->ezSetCmMargins( 1, 2, 1, 1 );
		$pdf->selectFont( "./lib/Helvetica.afm" );
		//$pdf->selectFont( "./lib/Courier.afm" );
		//$pdf->line(20,822,578,822);
		//$pdf->ezImage( getcwd().'/images/logos/'.$logo1);

		while($row = $st->fetch())
		{

		//	$pdf->ezStartPageNumbers(100,20,8,'','',1);
			$tr = TrainingRecord::loadFromDatabase($link,$row['tr_id']);
		//	$pdf->ezText(  $row['surname'] . $tr->dob  . getcwd().'/images/logos/'.$filename.'----' . DB_NAME . '/'.$_SESSION['user']->username);
			$pdf->addJpegFromFile(getcwd().'/images/logos/'.$logo1,176,731,260);
			$data = TrainingRecord::loadData($link,$row['tr_id']);
			$xml = XML::loadSimpleXML($data);
			$pdf->ezSetY(731);
			$pdf->ezText("<b>".$xml->FrameworkTitle."</b>",14,array('spacing'=>'1','justification'=>'centre'));
			$pdf->ezText('<b>Programme:</b> '. $xml->CourseTitle."\n",12,array('spacing'=>'1.5','justification'=>'centre'));
			$pdf->ezText('Progress Report as from: ' . date('d/m/Y'),12,array('justification'=>'centre') );
			$pdf->ezSetDy(-10);

			$data2=array();
			$data2[] = array('STUDENT' => $xml->FirstNames . ' ' . $xml->Surname, 'SCHOOL' => $xml->EmployerName);

			$pdf->ezTable($data2,array('STUDENT'=>'<b>Student</b>','SCHOOL'=>'<b>School</b>'),'',array('width'=>'525'));
			$pdf->ezSetDy(-10);
			$pdf->ezText("<b>QUALIFICATIONS</b>",14);
			$pdf->ezSetDy(-10);
			foreach($xml->children() as $child)
			{
				if ( $child->getName() == 'Qualifications' )
				{
					$count=1;
					foreach($child as $qual)
					{
						$pdf->saveState();
						$pdf->ezText( '<c:uline><b>' . (strval($count)) . ') ' . $qual->QualificationTitle .'</b></c:uline>',12);
						$pdf->restoreState();
						$pdf->ezSetDy(-10);
						$pdf->ezText("<b>Completed Units</b>",12);
						$pdf->ezSetDy(-5);

						$count += 1;
                                         $count2=1;
						foreach($qual->CompletedUnits as $units)
						{
							
							$count2=0;
							$data1=array();
							foreach($units as $unit) {
								$count2 +=1;
                                                       
								$data1[] = array('#'=> (string)$count2,'UNIT' => $unit , 'Cmplt' => $unit->attributes()->percentage );
								//$pdf->ezText((strval($count2)) . ') ' . $unit . ' '.$unit->attributes()->percentage,10 );
							}

							if ( $units->children() )
								$pdf->ezTable($data1,array('#'=>'#','UNIT'=>'UNIT','Cmplt'=>'Cmplt'),'',array('fontSize'=>'6','width'=>'525'));
							else
								$pdf->ezText("No units completed");


						}

						$pdf->ezSetDy(-10);


						$pdf->ezText("<b>Units Outstanding</b>",12);
						$pdf->ezSetDy(-5);


						
						foreach($qual->ToBeCompletedUnits as $units)
						{      
							$count2=0;
							
							$data3=array();
							foreach($units as $unit){
								$count2 +=1;
                                                       
								$data3[] = array('#'=> (string)$count2,'UNIT' => $unit , 'Cmplt' => $unit->attributes()->percentage );
									    
 

								//$pdf->ezText((strval($count2)) . ') ' . $unit . ' '.$unit->attributes()->percentage,10 );

	
							}
							if ( $units->children() )
								$pdf->ezTable($data3,array('#'=>'#','UNIT'=>'UNIT','Cmplt'=>'Cmplt'),'',array('fontSize'=>'6','width'=>'525'));
							else
								$pdf->ezText("All units completed");

						}
						$pdf->ezSetDy(-10);
						

					}


				}
			}
			
			$pdf->ezSetDy(-10);
			$pdf->ezText("<b>Attendance</b>",12);
			$pdf->ezSetDy(-5);
			$data4=array();
			$data4[] = array('a'=> (string)$tr->registered_lessons ,'b' => (string)$tr->attendances , 'c' => (string)$tr->lates );
			$pdf->ezTable($data4,array('a'=>'Total Lessons','b'=>'Lessons Attended','c'=>'Lessons Late'),'',array('fontSize'=>'6','width'=>'525'));


			$pdf->ezNewPage();
			// $pdf->ezStopPageNumbers();
		}

				
		$pdf->ezText( "End of Documents");

		if(!(file_exists(DATA_ROOT."/uploads/".DB_NAME)))
			mkdir(DATA_ROOT."/uploads/".DB_NAME);

		$target_path = DATA_ROOT."/uploads/".DB_NAME . "/";

		if(!(file_exists(DATA_ROOT."/uploads/".DB_NAME."/".$_SESSION['user']->username.'_reports')))
			mkdir($target_path."/".$_SESSION['user']->username.'_reports');

		$pdfcode = $pdf->ezOutput();
		//This path is hardcoded for now ( /data/srv/www/am_common ) 

		$fp=fopen(DATA_ROOT.'/uploads/'. DB_NAME . '/'.$_SESSION['user']->username.'_reports'.'/report1.pdf','wb');
		fwrite($fp,$pdfcode);
		fclose($fp);
		$pdf->ezStream();
	}
	else
	{

		echo "No Records";

	}

?>
