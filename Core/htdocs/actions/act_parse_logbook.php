<?php
/**
 * parse_logbook
 *
 */
class parse_logbook implements IAction
{
	public function execute(PDO $link)
	{
		
		if ( !isset($_REQUEST['qid']) ) {
			
			$qan_sql = 'SELECT qualifications.id, title, COUNT(qualification_specification.qan_id) AS units FROM qualifications ';
			$qan_sql .= 'LEFT JOIN qualification_specification ON qualifications.id = qualification_specification.qan_id ';
			$qan_sql .= 'WHERE qualifications.clients = "pearsonwbl" ';
			$qan_sql .= 'GROUP BY qualifications.id ORDER BY qualifications.id ASC';
			
			$st = $link->query($qan_sql);
			$qan_table = '<table class="resultset" >';
			$qan_table .= '<thead><tr>';
			
			$qan_table .= '<th>QAN Code</th>';
			$qan_table .= '<th>Title</th>';
			$qan_table .= '<th>Specification Built?</th>';
			$qan_table .= '<th>Specification File Present?</th>';
			$qan_table .= '<th>Compare</th>';
			$qan_table .= '<th>Build</th>';
			$qan_table .= '<th colspan="3">Outputs</th>';
			$qan_table .= '</tr></thead>';
			$qan_table .= '<tfoot></tfoot>';
			$qan_table .= '<tbody>';
			
			if( $st ) {	
				$row_count = 0;
				while( $row = $st->fetch() ) {	
								
					if ( $row_count % 2 ) {
						$row_style = 'background-color: #F9F9F9';
					}
					else {
						$row_style = 'background-color: #FFFFFF';
					}		
					$qan_row = '<td>'.$row['id'].'</td>';
					$qan_row .= '<td>'.$row['title'].'</td>';
					$qan_row .= '<td>';
					if ($row['units'] > 0) { 
						$qan_row .= 'YES ('.$row['units'].' units)'; 
					} 
					else { 
						$qan_row .= 'NO'; 
					}
					$qan_row .= '</td>';
					$qan_docx_filename = preg_replace('/\//', "", $row['id']);
					if ( file_exists(DATA_ROOT.'/uploads/'.DB_NAME.'/logbooks/'.$qan_docx_filename.'.docx') ) {
						$qan_row .= '<td>yes - <a href="#" onclick="downloadFile(\'/logbooks/'.$qan_docx_filename.'.docx\');" >download</a></td>';
						
						if ( $row['units'] > 0) { 
							$qan_row .= '<td><a href="?_action=verify_logbook&amp;qid='.$row['id'].'">Compare Qualification</a></td>';
						}
						else {
							$qan_row .= '<td>&nbsp;</td>';
						}
						
						$qan_row .= '<td><a href="?_action=parse_logbook&amp;qid='.$row['id'].'" >Build It</a></td>';	
						$qan_row .= '<td><a href="?_action=xml_export&amp;id='.$row['id'].'&amp;clients=" >XML</a></td>';	
						$qan_row .= '<td><a href="?_action=maytas_standard_export&amp;id='.$row['id'].'&amp;clients=" >F4S MMF</a></td>';	
						$qan_row .= '<td><a href="?_action=maytas_export&amp;id='.$row['id'].'&amp;clients=" >Babcock MMF</a></td>';	
						
						if ( $row['units'] <= 0 ) {
							$row_style = 'background-color: #E0EAD0';
						}	
					}
					else {
						// add in a file uploader here
						// ---
						$qan_row .= '<td>n/a</td>';
						if ( $row['units'] > 0) { 
							$qan_row .= '<td><a href="?_action=verify_logbook&amp;qid='.$row['id'].'">Compare Qualification</a></td>';
						}
						else {
							$qan_row .= '<td>&nbsp;</td>';
						}
						$qan_row .= '<td>&nbsp;</td>';
						$qan_row .= '<td><a href="?_action=xml_export&amp;id='.$row['id'].'&amp;clients=" >XML</a></td>';	
						$qan_row .= '<td><a href="?_action=maytas_standard_export&amp;id='.$row['id'].'&amp;clients=" >F4S MMF</a></td>';	
						$qan_row .= '<td><a href="?_action=maytas_export&amp;id='.$row['id'].'&amp;clients=" >Babcock MMF</a></td>';
					}
					
					$qan_table .= '<tr style="'.$row_style.'" class="shortrecord" >'.$qan_row.'</tr>';
					$row_count++;
				}
			}
			$qan_table .= '</tbody></table>';	
			
			include_once('tpl_parse_logbook.php');
			exit;
		}
		
		$qan_id = $_REQUEST['qid'];
		
		// $myFile = DATA_ROOT.'/uploads/'.DB_NAME."/testFile.txt";
		// $fh = fopen($myFile, 'w') or die("can't open file");

		// array to hold location of 'unit code:' within the document.
		$unitcode_placeholders = array();
		// array to holder location of all elements, triggered by unit code location
		$unitdetail_placeholders = array();
		
		// babcock file requirements
		// ------------
		//$qan_id = '500/7363/1';  	// still got to confirm the spec on this one
		
		//$qan_id = '500/7312/6';	// getting unit_title too long - data run on. 500/7312/6
		//$qan_id = '501/0442/1';	// doesn't conform to the standard specification ( no unit code etc )
		//$qan_id = '501/0443/3';	// doesn't conform to the standard specification ( no unit code etc )
		//$qan_id = '500/9632/1';   // doesn't conform to the standard specification ( no unit code etc )
		//$qan_id = '500/9549/3';  	// doesn't conform to the standard specification ( no unit code etc )
		
		// $qan_id = '501/1586/8';  	// + this one works 501/1586/8  // sent 21/11
		//$qan_id = '501/1813/4';	// + this one works 501/1813/4  // sent 21/11
		//$qan_id = '600/0833/7';	// + this one works 600/0833/7  // sent 21/11
		//$qan_id = '600/0872/6';	// + this one works 600/0872/6  // sent 21/11
		//$qan_id = '500/9522/5';	// + this one works 500/9522/5
		//$qan_id = '600/0871/4';	// + this one works 600/0871/4  // ready to send x
		//$qan_id = '600/0847/7';	// + this one works 600/0847/7  // ready to send x
		//$qan_id = '600/0995/0'; 	// + this one works 600/0995/0  // sent 21/11
		//$qan_id = '600/0850/7';	// + this one works 600/0850/7  // ready to send x
		//$qan_id = '600/0842/8';	// + this one works 600/0842/8  // ready to send x
		//$qan_id = '600/0852/0';	// + this one works 600/0852/0  // ready to send x
		//$qan_id = '600/0837/4';	// + this one works 600/0837/4
		//$qan_id = '600/0873/8';	// + this one works 600/0873/8
		
		//$qan_id = '500/9940/1';	// getting unit_summary too long 
		//$qan_id = '500/9504/3';	// getting unit_summary too long

		// ------------
		
		
		$docx_filename = preg_replace('/\//', "", $qan_id);
		
		// holder of the current text element within the docx file
		$current = '';
		
		 // use the logbook name as a mechanism for identifying the qualification.
        $file_manager = new Docx('logbooks/'.$docx_filename.'.docx', DATA_ROOT.'/uploads/'.DB_NAME.'/', '');
		
		
		$file_manager->extract_docx();		
		$file_manager->cleanup_docx();
		
		// load the docx_xml as a DOM object
		$docx_xml = new DOMDocument();
		$docx_xml->loadXML(mb_convert_encoding($file_manager->get_docx_xml(),'UTF-8'));
		// #TODO resove why this change doesn't work
		//$docx_xml = XML::loadXmlDom(utf8_encode($file_manager->get_docx_xml()));

		//#TODO resolve why we can't just look 
		// for the w:t using getElementsByTagName
		// this takes a while to get sorted due to the immenseness of the word doc
		$text_fields = $docx_xml->getElementsByTagName("*");
		
		
		$puretext = array();
		foreach ( $text_fields as $tfield ) {
			if ( $tfield->tagName == 'w:t' ) {
				$puretext[] = utf8_decode($tfield->nodeValue);
				// populate the place holder with the position
				// of the unit code within the document as a whole
				if ( preg_match('/^unit code\:$/i',$tfield->nodeValue) ) {
					$unitcode_placeholders[] = sizeof($puretext);
					$current = sizeof($puretext);
					$unitdetail_placeholders[$current] = array(
						'unit_id'	=> sizeof($unitdetail_placeholders)+1,
						'unit_actual_id' => sizeof($puretext),
						'unit_title' => '',
						'unit_code' => sizeof($puretext),
						'unit_reference_number' => '',
						'unit_aim'	=> '',
						'unit_summary' => '',
						'requirements' => '',
						'observation_counts' => '',
						'evidence_counts' => '',
						'assessment_criteria' => '',
						'unit_content' => '',
						'glossary' => '',
					);
				}
				elseif( preg_match('/^unit reference number\:$/i',$tfield->nodeValue) ) {
					if ( array_key_exists($current, $unitdetail_placeholders) ) {
						$unitdetail_placeholders[$current]['unit_reference_number'] = sizeof($puretext);
					}		
				}
				elseif( preg_match('/^unit aim$/i',$tfield->nodeValue) ) {
					if ( array_key_exists($current, $unitdetail_placeholders) ) {
						$unitdetail_placeholders[$current]['unit_aim'] = sizeof($puretext);
					}	
				}
				elseif( preg_match('/^unit summary$/i',$tfield->nodeValue) ) {
					if ( array_key_exists($current, $unitdetail_placeholders) ) {
						$unitdetail_placeholders[$current]['unit_summary'] = sizeof($puretext);
					}	
				}
				// elseif( preg_match('/^assessment requirements\/evidence/i',$tfield->nodeValue) || ( preg_match('/assessment criteria$/i', $tfield->nodeValue) ) ) {
				elseif( preg_match('/^assessment requirements\/evidence/i',$tfield->nodeValue) ) {
					if ( array_key_exists($current, $unitdetail_placeholders) ) {
						if ( $unitdetail_placeholders[$current]['requirements'] == '' &&  sizeof($puretext) > $unitdetail_placeholders[$current]['unit_aim'] ) {
							$unitdetail_placeholders[$current]['requirements'] = sizeof($puretext);
							$unitdetail_placeholders[$current]['observation_counts'] = sizeof($puretext);
							$unitdetail_placeholders[$current]['evidence_counts'] = sizeof($puretext);
						}
					}	
				}
				// new stuff added in 
				elseif( preg_match('/a learner should:$/i',$tfield->nodeValue) || preg_match('/assessment criteria$/i',$tfield->nodeValue) ) {
					if ( array_key_exists($current, $unitdetail_placeholders) ) {
						$unitdetail_placeholders[$current]['assessment_criteria'] = sizeof($puretext);
					}	
				}
				//more new stuff
				elseif( preg_match('/^glossary for unit/i',$tfield->nodeValue) ) {
					if ( array_key_exists($current, $unitdetail_placeholders) ) {
						$unitdetail_placeholders[$current]['glossary'] = sizeof($puretext);
					}	
				}
			}
		}
		
		// ** this bit is shady - how can we tighten up the access to information here **
		// have a holder for run on data.
		$next_location = 0;
		$assess_type = 'observe';
		
		$ferret_face = '';
		
		foreach ( $unitdetail_placeholders as $ee => $vv ) {
			foreach ( $vv as $dataset => $location ) {
				switch ($dataset) {
					case 'unit_title':
						// unit_title works back from the unit code section .
						$location = $ee;
						$unit_title_start = 0;
						$unitdetail_placeholders[$ee]['unit_title'] = '';
						while(array_key_exists($location, $puretext) && ( !preg_match("/\:$/i", $puretext[$location]) || 0 == $unit_title_start ) ) {
							// $unitdetail_placeholders[$ee]['unit_title'] .= '['.$puretext[$location].']';
							if ( preg_match("/^Unit/i", $puretext[$location]) ) {
								$unit_title_start = 1;
								$location--;	
							}
							if ( 1 == $unit_title_start ) {
								$unitdetail_placeholders[$ee]['unit_title'] = $puretext[$location].$unitdetail_placeholders[$ee]['unit_title'];
							}	
							$location--;
						}
						break;
					case 'unit_actual_id':
						$location = $ee;
						$unit_id_start = 0;
						$unitdetail_placeholders[$ee]['unit_id'] = '';
						while(array_key_exists($location, $puretext) && ( 2 != $unit_id_start ) ) {
							// $unitdetail_placeholders[$ee]['unit_title'] .= '['.$puretext[$location].']';
							if ( preg_match("/^[U]{0,1}nit/i", $puretext[$location]) ) {
								$unit_id_start++;
								// $unit_matches = array();
								// just got the unit title, move forward one
								if ( preg_match("/^[U]{0,1}nit$/i", trim($puretext[$location])) ) {
									$location++;	
								}
								elseif( preg_match("/^[U]{0,1}nit (\d*)[:]{0,1}$/i", trim($puretext[$location]), $unit_matches) ) {
									$unitdetail_placeholders[$ee]['unit_id'] = $unit_matches[1];		
									break;
								}	
							}
							$ferret_face = $puretext[$location]." ".$ferret_face;
							
							if ( 2 == $unit_id_start ) {
								while(preg_match("/^\d+[:]{0,1}$/i", trim($puretext[$location])) ) {							
									$unitdetail_placeholders[$ee]['unit_id'] .= preg_replace("/:/", "", $puretext[$location]);
									$location++;
								}
							}	
							$location--;
						}
						break;
					case 'unit_code':
						$unitdetail_placeholders[$ee]['unit_code'] = '';
						while(array_key_exists($location, $puretext) && !preg_match("/^(QCF|Unit)/i", $puretext[$location]) ) {
							$unitdetail_placeholders[$ee]['unit_code'] .= $puretext[$location];	
							$location++;
						}
						break;
					case 'unit_reference_number':
						if ( is_numeric($location) ) {
							$unitdetail_placeholders[$ee]['unit_reference_number'] = '';
							while ( array_key_exists($location, $puretext) && !preg_match("/^(QCF|Unit)/i", $puretext[$location]) ) {
								$unitdetail_placeholders[$ee]['unit_reference_number'] .= $puretext[$location];	
								$location++;
							}
						}
						break;
					case 'unit_summary':
						$unitdetail_placeholders[$ee]['unit_summary'] = '';
						while ( array_key_exists($location, $puretext) && ( !preg_match('/^assessment methodology/i',$puretext[$location]) && !preg_match('/^assessment requirements/i',$puretext[$location]) ) ) {
							// rudimentary replacement - issue with data encoding requires further thought
							$unitdetail_placeholders[$ee]['unit_summary'] .= ' '.preg_replace("/[^a-zA-Z0-9 .,!?'\"\s]/", "", $puretext[$location]);	
							$location++;
						}
						break;
					case 'unit_aim':
						$unitdetail_placeholders[$ee]['unit_aim'] = '';
						while ( array_key_exists($location, $puretext) && !stristr($puretext[$location], 'unit introduction') ) {
							// rudimentary replacement - issue with data encoding requires further thought
							// $unitdetail_placeholders[$ee]['unit_aim'] .= ' '.preg_replace("/[^a-zA-Z0-9 .,!?'\"\s]/", "", $puretext[$location]);	
							$unitdetail_placeholders[$ee]['unit_aim'] .= preg_replace("/[^a-zA-Z0-9 .,!?'\"\s]/", "", $puretext[$location]);
							$location++;
						}
						break;	
					case 'requirements':
						$unitdetail_placeholders[$ee]['requirements'] = '';
						// && !stristr($puretext[$location], 'unit'
						while ( array_key_exists($location, $puretext) && ( !preg_match('/^recording of evid/i',$puretext[$location]) && !preg_match('/^assessment methodology/i',$puretext[$location]) && !preg_match('/^There must be/i', $puretext[$location]) && !preg_match('/^On completion of/i', $puretext[$location] ) ) ) {
							// rudimentary replacement - issue with data encoding requires further thought
							$unitdetail_placeholders[$ee]['requirements'] .= preg_replace("/[^a-zA-Z0-9 .,!?'\/\"\s]/", "", $puretext[$location]);	
							$location++;
							
							// set up for run on information
							$next_location = $location;
						}
						break;
					// -----
					// add in a bit for the glossary
					case 'glossary':
						while ( array_key_exists($location, $puretext) && !stristr($puretext[$location], 'learning outcomes') ) {
							// rudimentary replacement - issue with data encoding requires further thought
							$unitdetail_placeholders[$ee]['requirements'] .= preg_replace("/[^a-zA-Z0-9 .,!?'\"\s]/", "", $puretext[$location]);
							$location++;
						}
						break;
					// -----
					case 'observation_counts':
						$unitdetail_placeholders[$ee]['observation_counts'] = '';
						// check this pattern matching for the type of count we are doing
						if ( $next_location != 0 && preg_match('/observing the learn/', $unitdetail_placeholders[$ee]['requirements']) ) {
							$location = $next_location;
							while( array_key_exists($location, $puretext) && !stristr($puretext[$location], 'unit') && !stristr($puretext[$location], 'further information') ) {
								$unitdetail_placeholders[$ee]['observation_counts'] .= ' '.preg_replace("/[^a-zA-Z0-9 .,!?'\/\"\s]/", "", $puretext[$location]);	
								$location++;	
							}
						}
						break;
					case 'evidence_counts':
						$unitdetail_placeholders[$ee]['evidence_counts'] = '';
						// check this pattern matching for the type of count we are doing
						if ( $next_location !=  0 && preg_match('/evidence of the learn/', $unitdetail_placeholders[$ee]['requirements']) ) {
							$location = $next_location;
							while( array_key_exists($location, $puretext) && !stristr($puretext[$location], 'unit') && !stristr($puretext[$location], 'further information') ) {
								$unitdetail_placeholders[$ee]['evidence_counts'] .= ' '.preg_replace("/[^a-zA-Z0-9 .,!?'\/\"\s]/", "", $puretext[$location]);	
								$location++;	
							}
						}
						break;
					case 'assessment_criteria':
						$unitdetail_placeholders[$ee]['assessment_criteria'] = '';
						while( array_key_exists($location, $puretext) && !stristr($puretext[$location], 'unit') ) {
							$unitdetail_placeholders[$ee]['assessment_criteria'] .= ' '.preg_replace("/[^a-zA-Z0-9 .,!?'\/\"\s]/", "", $puretext[$location]);	
							$location++;	
						}
						// this is assuming some document consistency that Unit Content always follows the Assessment Criteria
		//re				while( array_key_exists($location, $puretext) && !stristr($puretext[$location], 'essential') ) {
		//re					$unitdetail_placeholders[$ee]['unit_content'] .= preg_replace("/[^a-zA-Z0-9 .,!?'\/\"\s]/", "", $puretext[$location]);	
		//re					$location++;	
		//re				}
						break;
				}	
			}
		}
		
		// throw new Exception(pre($unitdetail_placeholders));
		
		// ** end of shadyness **
		$qan_table = '';
		$unit_links = '<div>';
		
		// $row_count = 0;  // phpstorm - unused
		
		// save to database 
		$delete_qan_flag = 1;
		foreach ( $unitdetail_placeholders as $unit_id => $unit_information ) {
			$unit_specification = new QualSpecification();
			$unit_specification->qan_id = $qan_id;
			// remove any existing information stored about the qan
			// - we assume we can do this as the specification is read as a whole
			// - so will not handle incremental changes or updates.
			if ( $delete_qan_flag ) {
				$unit_specification->delete($link);
				$delete_qan_flag = 0;
			}
			$unit_specification->unit_id = isset($unit_information['unit_id'])?$unit_information['unit_id']:'';
			$unit_specification->unit_title = isset($unit_information['unit_title'])?$unit_information['unit_title']:'';
			$unit_specification->unit_code = isset($unit_information['unit_code'])?$unit_information['unit_code']:'';
			$unit_specification->unit_reference_number = isset($unit_information['unit_reference_number'])?$unit_information['unit_reference_number']:'';
			$unit_specification->unit_aim = isset($unit_information['unit_aim'])?$unit_information['unit_aim']:'';
			$unit_specification->unit_summary = isset($unit_information['unit_summary'])?$unit_information['unit_summary']:'';
			$unit_specification->unit_requirements = isset($unit_information['requirements'])?$unit_information['requirements']:'';
			$unit_specification->unit_evidence = isset($unit_information['evidence_counts'])?$unit_information['evidence_counts']:'';
			$unit_specification->unit_observation = isset($unit_information['observation_counts'])?$unit_information['observation_counts']:'';
				
			// build the assessment_criteria 
			if ( isset($unit_information['assessment_criteria']) ) {
					$assessment_content = preg_split("/(\d+)/", $unit_information['assessment_criteria'],-1,PREG_SPLIT_DELIM_CAPTURE);
					$assessment_text = '';
					foreach ( $assessment_content as $asc_id => $asc_text ) {
						if ( $asc_id > 0 ) {
							$assessment_text .= $asc_text;
							if(preg_match("/[A-Za-z]+$/", trim($assessment_text)) ){
								// additional white space stripping
								// $unit_specification->assessment_criteria .= ''.$assessment_text.'|';
								$unit_specification->assessment_criteria .= ''.preg_replace('/\s\s+/', ' ', trim($assessment_text)).'|';
							 	$assessment_text = '';
							}	
						}	
					}
			}
			
			// extract only the relevant bits from the unit content
			if ( isset($unit_information['unit_content']) ) {
					$assessment_content = preg_split("/[^0-9][0-9]([^0-9])/", $unit_information['unit_content'],-1, PREG_SPLIT_DELIM_CAPTURE);
					foreach ( $assessment_content as $asc_id => $asc_text ) {
						// additional white space stripping
						$unit_specification->unit_content .= preg_replace('/\s\s+/', ' ', trim($asc_text));
						// ensure we concatenate the 1st char after the learning outcome number from the preg_split
						if ( strlen($asc_text) > 1 ) {
							$unit_specification->unit_content .= '|';		
						}
						$unit_specification->unit_content = preg_replace('/^Unit conten/','', $unit_specification->unit_content);
					}
			}
			
			// do any data tidy up 
			$unit_specification->unit_code = preg_replace('/QCF.*/', '', $unit_specification->unit_code);
			$unit_links .= '<a href="#" id="s_'.$unit_specification->unit_id.'" class="unit_display" >Unit '.$unit_specification->unit_id.'</a>&nbsp;|&nbsp;';
			
			$lo_element_short = preg_split('/(?<=[a-z\)0-9])(?=[A-Z])/x',$unit_specification->unit_content);
			$qan_table .= '<div id="c_'.$unit_specification->unit_id.'" style="display:none" class="unit_content" >';
			$qan_table .= '<table class="resultset">';
			$qan_table .= '<thead></thead>';
			$qan_table .= '<tfoot></tfoot>';
			$qan_table .= '<tbody>';
			$qan_table .= '<tr style="background-color: #F9F9F9" class="shortrecord" ><td>Unit ID</td><td style="vertical-align: top;" >'.$unit_specification->unit_id.'</td></tr>';
			$qan_table .= '<tr class="shortrecord" ><td>Unit Code</td><td style="vertical-align: top;" >'.$unit_specification->unit_code.'</td></tr>';
			$qan_table .= '<tr style="background-color: #F9F9F9" class="shortrecord" ><td>Unit Title</td><td style="vertical-align: top;" >'.$unit_specification->unit_title.'</td></tr>';
			$qan_table .= '<tr class="shortrecord"><td>Unit Ref</td><td style="vertical-align: top;" >'.$unit_specification->unit_reference_number.'</td></tr>';
			$qan_table .= '<tr style="background-color: #F9F9F9" class="shortrecord" ><td>Unit Aim</td><td style="vertical-align: top;" >'.$unit_specification->unit_aim.'</td></tr>';
			$qan_table .= '<tr class="shortrecord"><td>Unit Summary</td><td style="vertical-align: top;" >'.$unit_specification->unit_summary.'</td></tr>';
			$qan_table .= '<tr style="background-color: #F9F9F9" class="shortrecord" ><td>Assessment Criteria</td><td style="vertical-align: top;" >'.preg_replace('/\|/', '<br/><br/>', $unit_specification->assessment_criteria).'</td></tr>';

			$qan_table .= '<tr class="shortrecord"><td>Unit Content</td><td style="vertical-align: top;" ><textarea rows="50" cols="200" >';
			$display_cont = 0;
			foreach ($lo_element_short as $ucontent_desc ) {
					$qan_table .= "\n\n".preg_replace('/\|/', "\n\n", $ucontent_desc);
			}
			$qan_table .= '</textarea></td></tr>';		
			
			$qan_table .= '<tr style="background-color: #F9F9F9" class="shortrecord" ><td>Requirements</td><td style="vertical-align: top;" >'.$unit_specification->unit_requirements.'</td></tr>';
			$qan_table .= '<tr class="shortrecord"><td>Obs Counts</td><td style="vertical-align: top;" >'.$unit_specification->unit_observation.'</td></tr>';
			$qan_table .= '<tr style="background-color: #F9F9F9" class="shortrecord" ><td>Evidence Counts</td><td style="vertical-align: top;" >'.$unit_specification->unit_evidence.'</td></tr>';
			// ---
			//$qan_table .= '<tr class="shortrecord"><td>Save?</td><td style="vertical-align: top;" >';
			//$qan_table .= '<input type="submit" name="save_content" value="Save Content" class="button" /></td></tr>';
			// ---
			$qan_table .= '</tbody></table></div>';	
				
			$unit_specification->acc_start_date = '2010-01-01';
			$unit_specification->acc_end_date = '2099-01-01';
			
			if ( $unit_specification->unit_id != NULL ) {
				$unit_specification->save($link);		
			}
			else {
				//	throw new Exception("puke -->".$ferret_face);	
			}
		}
		
		
		// fclose($fh);  // phpstorm unused
		
		$qan_table = $unit_links.'<br/><br/></div>'.$qan_table;
		$qan_title = $qan_id.' Specification Extraction';
		include_once('tpl_parse_logbook.php');
	}
}
?>
