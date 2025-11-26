<?php
class maytas_standard_export implements IAction
{
	public function execute(PDO $link)
	{

		$id = isset($_REQUEST['id'])?$_REQUEST['id']:'';
		$clients = isset($_REQUEST['clients'])?$_REQUEST['clients']:'';
		$headers_sent = NULL;
		
		$qan_code = str_replace('/',"", $id);
		
		
		header("Content-Type: application/octet-stream");

		// Internet Explorer requires two extra headers when downloading files over HTTPS
		if(strpos($_SERVER['HTTP_USER_AGENT'], 'MSIE') !== false)
		{
		 	header('Pragma: public');
		 	header('Cache-Control: max-age=0');
		}		
		if ( $clients == "" ) {
        	$clients = isset($_SESSION['user']->username)?$_SESSION['user']->username:'';
		}
        // #166 - headers already sent issue
        // - return of multiple qualifications resolved.
        if ( $clients != "" ) {
        	$sql = "select * from qualifications where id = '$id' and clients = '$clients' limit 0, 1";
        }
		else {
			$sql = "select * from qualifications where id = '$id' limit 0,1";
		}
		
		$st = $link->query($sql);
		if( $st ) {
			
			// #193 {0000000282} - load up the qualification specification if present
			$qual_spec_details = QualSpecification::loadFromDatabase($link, $id);

			$production_date = date("y_m_d h_i");
						
			while( $row = $st->fetch() ) {				
				$module = 1;
				$units = 0;
				$elements = 0 ;
				$evidences = 0;
				$filename = $row['internaltitle'];
				if ( !isset($headers_sent) ) {
                	header('Content-Disposition: attachment; filename="'.$qan_code.' - '.$filename.' - '.$production_date.'.mmf"');
                	echo '<?xml version="1.0" standalone="yes"?>';
					echo "\n";
					echo '<diffgr:diffgram xmlns:msdata="urn:schemas-microsoft-com:xml-msdata" xmlns:diffgr="urn:schemas-microsoft-com:xml-diffgram-v1">';
					echo "\n";
                    $headers_sent = 1;
                }
				echo '  <NewDataSet>';
				echo "\n";
				echo '    <MODULE diffgr:id="MODULE1" msdata:rowOrder="0">';
				echo "\n";
				// riche - added in hyphen after 00TT...
				echo '      <MODULEID>0001-00TT-' . str_pad($module,6,'0',STR_PAD_LEFT) . '</MODULEID>';
				echo "\n";
				// riche - changed to 00TT from 0001...
				echo '      <Q_ADMINCENTREID>00TT</Q_ADMINCENTREID>';
				echo "\n";
				echo '      <Q_ORGID>0001</Q_ORGID>';
				echo "\n";
				echo '      <MODCODE>' . str_replace('/','',$row['id']) . '</MODCODE>';
				echo "\n";
				echo '      <MODTITLE>' .htmlspecialchars((string)$row['title']). '</MODTITLE>';
				echo "\n";
				echo '      <MODCATEGORY>Base</MODCATEGORY>';
				echo "\n";
				echo '      <MODDESCRP />';
				echo "\n";
				// riche - value required - dummy data
				echo '      <MANDATORY>N</MANDATORY>';
				echo "\n";
				echo '      <OPTIONSGROUP />';
				echo "\n";
				echo '      <DELIVERYCOST>0</DELIVERYCOST>';
				echo "\n";
				echo '      <FUNDINGAMOUNT>0</FUNDINGAMOUNT>';
				echo "\n";
				echo '      <ACTIVEDATE>'.$row['operational_start_date'].'T00:00:00+00:00</ACTIVEDATE>'; //1980-01-01T00:00:00+00:00
				echo "\n";
				// check qual_spec_detail exists
				if ( $qual_spec_details->acc_start_date ) {
					echo '      <OBSOLETEDATE>'.$qual_spec_details->acc_end_date.'T00:00:00+00:00</OBSOLETEDATE>'; //2010-07-31T00:00:00+01:00
					echo "\n";
					echo '      <ACCSTARTDATE>'.$qual_spec_details->acc_start_date.'T00:00:00+00:00</ACCSTARTDATE>'; //1980-01-01T00:00:00+00:00
					echo "\n";
					echo '      <ACCENDDATE>'.$qual_spec_details->acc_end_date.'T00:00:00+00:00</ACCENDDATE>'; //2010-07-31T00:00:00+01:00
					echo "\n";
				}
				echo '      <USEDBYCENTRES />';
				echo "\n";
				// riche - value required - dummy data
				echo '      <VERSION>0</VERSION>'; 
				echo "\n";
				echo '      <MODLEVEL>' . $row['level'] . '</MODLEVEL>';
				echo "\n";
				echo '      <AWARDINGBODY>' . substr($row['awarding_body'],0,3) . '</AWARDINGBODY>'; //? first three
				echo "\n";
				echo '      <NVQREF>' . str_replace('/','',$row['id']) . '</NVQREF>'; // all ?
				echo "\n";
				echo '      <RELATIVESTART>0</RELATIVESTART>'; //?
				echo "\n";
				echo '      <RELATIVETARGET>0</RELATIVETARGET>'; //?
				echo "\n";
				echo '      <RELATIVEPERCENTAGE>0</RELATIVEPERCENTAGE>'; //?
				echo "\n";
				// riche - value required - dummy data
				echo '      <IncludeOnReturn>N</IncludeOnReturn>'; //?
				echo "\n";
				$final_cert_date = ($row['certification_end_date'] =='')?'2020-12-30T00:00:00+00:00':$row['certification_end_date'];
				echo '      <FinalCertDate>'.$final_cert_date.'</FinalCertDate>';
				echo "\n";
				echo '      <AREAOFLEARNING />';
				echo "\n";
				echo '      <QUALTYPE>' . $row['qualification_type'] . '</QUALTYPE>';
				echo "\n";
				// #193 {0000000282}
				// relmes - ensure this is only an integer 
				// - take the maximum value if a range
				if ( preg_match('/\d+\-\d+/', $row['guided_learning_hours']) ) {
					$guided_range = explode("-", $row['guided_learning_hours']);
					echo '      <GUIDEDLEARNINGHOURS>'.$guided_range[1].'</GUIDEDLEARNINGHOURS>';
				}
				else {
					echo '      <GUIDEDLEARNINGHOURS>'.$row['guided_learning_hours'].'</GUIDEDLEARNINGHOURS>';
				}
				echo "\n";
				echo '      <UNITGROUP />';
				echo "\n";
				echo '      <RANGESTATEMENT />';
				echo "\n";
				echo '      <EVIDENCE />';
				echo "\n";
				echo '      <PERFORMANCE />';
				echo "\n";
				echo '      <STRUCTURE />';
				echo "\n";
				echo '      <LSCFUNDINGSTREAM />';
				echo "\n";
				echo '      <SOURCEOFFUNDING />';
				echo "\n";
				echo '      <IMPLIEDRATEOFLSCFUNDING>0</IMPLIEDRATEOFLSCFUNDING>';
				echo "\n";
				echo '      <DELIVERYMODE />';
				echo "\n";
				echo '      <MAINDELIVERYMETHOD />';
				echo "\n";
				echo '      <FRANCHISEDOUTPARTNER />';
				echo "\n";
				echo '      <FRANCHISEDOUTPARTNERNUMBER />';
				echo "\n";
				echo '      <SOC />';
				echo "\n";
				echo '      <LSC_PROJECTSPILOTS />';
				echo "\n";
				echo '      <LSC_DISTANCEFUNDING>0</LSC_DISTANCEFUNDING>';
				echo "\n";
				echo '      <DELIVERYUPIN />';
				echo "\n";
				echo '      <ALTRANGECAPTION />';
				echo "\n";
				echo '      <ALTKNOWLEDGECAPTION />';
				echo "\n";
				echo '      <ALTPERFORMANCECAPTION />';
				echo "\n";
				echo '      <AWARDREF>' . str_replace('/','',$row['id']) . '</AWARDREF>';
				echo "\n";
				echo '      <PROGRAMMETYPE>99</PROGRAMMETYPE>'; //?
				echo "\n";
				echo '      <LSC_ASLPROVISIONTYPE />';
				echo "\n";
				echo '      <NOIV>N</NOIV>';
				echo "\n";
				// riche - value required - dummy data
				echo '      <CLAIMLEVEL>0</CLAIMLEVEL>';
				echo "\n";
				// riche - value required - dummy data
				echo '      <QCFpoints>0</QCFpoints>';
				echo "\n";
				echo '    </MODULE>';
				echo "\n";
				
				//$pageDom = new DomDocument();
				//$pageDom->loadXML(utf8_encode($row['evidences']));
				$pageDom = XML::loadXmlDom(mb_convert_encoding($row['evidences'],'UTF-8'));
				
				$units_type = $pageDom->getElementsByTagName('units');
				$unitCnt  = $units_type->length;
				
				
				
				for ($idx = 0; $idx < $unitCnt; $idx++) {
					// get all the individual units in this units block
					// - is this always mandatory & optional
					// - if so we have units->units(mandatory) & units->units(optional)
					// - need to disregard the initial units
					if ( $units_type->item($idx)->getElementsByTagName('units')->length > 0 ) {
						continue;
					}
    				$e = $units_type->item($idx)->getElementsByTagName('unit');
    				
    				$displayindex = 1;
    				
    				$unit_number = 0;
    				$display_unit_number = '';
    						
					foreach( $e as $node ) {
						// #193 {0000000282} - present the unit details from specification
						// ensure a match on title when typos are different.
						// /[^a-zA-Z0-9 .,!?'\"\s+]/
						$qan_title = preg_replace("/[^a-zA-Z0-9!?\'\"]/", "", strtolower($node->getAttribute('title')));
						
						$module++;
						$units++;
						if ( preg_match("/Unit (\d+)$/", $node->getAttribute('reference'), $matches ) ) {
							$unit_number = $matches[1];
							$display_unit_number = $unit_number;
						}	
						elseif( isset($qual_spec_details->qual_specification[$qan_title]['unit_id']) ) {
							// do a check for duplicated up titles
							$duplicate_title = $qan_title.'_unit'.$node->getAttribute('reference');
							
							if ( isset($qual_spec_details->qual_specification[$duplicate_title]['unit_id']) ) {
								$qan_title = $duplicate_title;	
							}
							
							// $unit_number = sprintf('%02d', $qual_spec_details->qual_specification[$qan_title]['unit_id']);	
							// ---
							$unit_number = $qual_spec_details->qual_specification[$qan_title]['unit_id'];	
							
							if ( preg_match('/^([A-Z]{1})(\d+)$/', $unit_number, $unit_matches) ) {
								$display_unit_number = $unit_matches[2].$unit_matches[1];
							}
							else {
								$display_unit_number = $unit_number;
							}
								
						}
						else {
							$unit_number = sprintf('%02d', $units);
							$display_unit_number = $unit_number;
						} 
						
						//if ( in_array($qan_title, array_keys($qual_spec_details->qual_specification)) ) {
						//	echo '    <MODULE diffgr:id="MODULE' . $module . '" msdata:rowOrder="'.$qual_spec_details->qual_specification[$qan_title]['unit_id'].'">';	
						//}
						//else {
					    	echo '    <MODULE diffgr:id="MODULE' . $module . '" msdata:rowOrder="'. ($module-1) .'">';
						//}
						echo "\n";
						// riche - added in hyphen 
						echo '      <MODULEID>0001-0000-' . str_pad($module,6,'0',STR_PAD_LEFT) . '</MODULEID>';
						echo "\n";
						// riche - default value required
				    	echo '      <Q_ADMINCENTREID>0000</Q_ADMINCENTREID>'; // 00TT
						echo "\n";
						// riche - default value required
				    	echo '      <Q_ORGID>0000</Q_ORGID>'; //0001
						echo "\n";
				    	echo '      <MODCODE>'.str_replace('/','',$row['id']).'.'.$unit_number.'</MODCODE>';
						echo "\n";
				    	echo '      <PARENTMOD>'. str_replace('/','',$row['id']) . '</PARENTMOD>';
						echo "\n";
				    	echo '      <MODCATEGORY>Unit</MODCATEGORY>';
						echo "\n";
						echo '      <DISPLAYINDEX>'.$displayindex.'</DISPLAYINDEX>';
						echo "\n";
						$displayindex++;
						// we have extracted information from the specification file				
						if ( in_array($qan_title, array_keys($qual_spec_details->qual_specification)) ) {
							// echo '      <MODNUMBER>'.$qual_spec_details->qual_specification[$qan_title]['unit_id'].'</MODNUMBER>';
							echo '      <MODNUMBER>'.$unit_number.'</MODNUMBER>';
							echo "\n";
							echo '      <MODTITLE>Unit '.$display_unit_number.': ';
							// echo htmlspecialchars( $node->getAttribute('title')).' '.$qual_spec_details->qual_specification[$qan_title]['unit_code'].'</MODTITLE>';
							echo htmlspecialchars( $node->getAttribute('title')).' (Credit Value '.sprintf('%02d', $node->getAttribute('credits')).')</MODTITLE>';
							echo "\n";
							echo '      <MODREF>'.htmlspecialchars((string)$qual_spec_details->qual_specification[$qan_title]['unit_reference_number']).'</MODREF>';
							echo "\n";
							//if ( $qual_spec_details->qual_specification[$qan_title]['unit_aim'] ) {
				    		//	echo '      <MODDESCRP>'.htmlspecialchars((string)$qual_spec_details->qual_specification[$qan_title]['unit_aim']).'</MODDESCRP>';
							//}
							//else {
							//	echo '      <MODDESCRP>'.htmlspecialchars((string)$qual_spec_details->qual_specification[$qan_title]['unit_summary']).'</MODDESCRP>';
							//}
							//echo "\n";
							//echo '      <MODREQS>'.htmlspecialchars((string)$qual_spec_details->qual_specification[$qan_title]['unit_requirements']).'</MODREQS>';
							//echo "\n";
							//echo '      <MODEVIDENCECNT>'.htmlspecialchars((string)$qual_spec_details->qual_specification[$qan_title]['unit_evidence']).'</MODEVIDENCECNT>';
							//echo "\n";
							//echo '      <MODOBSERVECNT>'.htmlspecialchars((string)$qual_spec_details->qual_specification[$qan_title]['unit_observation']).'</MODOBSERVECNT>';
							//echo "\n";
						}
						else {
							// echo '      <MODTITLE>' .htmlspecialchars( $node->getAttribute('title')). '</MODTITLE>';
							echo '      <MODTITLE>' .htmlspecialchars( $node->getAttribute('title')).' (Credit Value '.$node->getAttribute('credits').')</MODTITLE>';
							echo "\n";
							echo '      <MODESCRP />';	
							echo "\n";
						}
						if ( preg_match('/mandatory/i', $units_type->item($idx)->getAttribute('title')) ) {
		    				echo '      <MANDATORY>Y</MANDATORY>';	
		    			}
		    			else {
							echo '      <MANDATORY>N</MANDATORY>';
		    			}
						echo "\n";
						// #193 {0000000282}
						// added in for pearson / babcock requirement
						// relmes - ensure this is only an integer 
						// - take the maximum value if a range
						if ( preg_match('/\d+\-\d+/', $node->getAttribute('glh')) ) {
							$guided_range = explode("-", $node->getAttribute('glh'));
							echo '      <GUIDEDLEARNINGHOURS>'.$guided_range[1].'</GUIDEDLEARNINGHOURS>';
						}
						else {
							echo '      <GUIDEDLEARNINGHOURS>'.$node->getAttribute('glh').'</GUIDEDLEARNINGHOURS>';
						}
						echo "\n";
						// added in for pearson / babcock requirement
						echo '      <QCFpoints>'.$node->getAttribute('credits').'</QCFpoints>';
						echo "\n";
				    	echo '      <OPTIONSGROUP/>';
						echo "\n";
				    	echo '      <DELIVERYCOST>0</DELIVERYCOST>';
						echo "\n";
				    	echo '      <FUNDINGAMOUNT>0</FUNDINGAMOUNT>';
						echo "\n";
				    	echo '      <USEDBYCENTRES />';
						echo "\n";
				    	echo '      <VERSION>0</VERSION>';
						echo "\n";
				    	echo '      <MODLEVEL>0</MODLEVEL>'; //?
						echo "\n";
				    	echo '      <AWARDINGBODY />';
						echo "\n";
				    	echo '      <NVQREF />';
						echo "\n";
				    	echo '      <RELATIVESTART>0</RELATIVESTART>';
						echo "\n";
				    	echo '      <RELATIVETARGET>0</RELATIVETARGET>';
						echo "\n";
				    	echo '      <RELATIVEPERCENTAGE>0</RELATIVEPERCENTAGE>';
						echo "\n";
						// riche - default value required
				    	echo '      <IncludeOnReturn>N</IncludeOnReturn>';
						echo "\n";
						// riche - default date required
				    	echo '      <FinalCertDate>'.$final_cert_date.'</FinalCertDate>';
						echo "\n";
				    	echo '      <AREAOFLEARNING />';
						echo "\n";
				    	echo '      <QUALTYPE />';
						echo "\n";
				    	echo '      <UNITGROUP />';
						echo "\n";
				    	echo '      <RANGESTATEMENT />';
						echo "\n";
				    	echo '      <EVIDENCE />';
						echo "\n";
				    	echo '      <PERFORMANCE />';
						echo "\n";
				    	echo '      <STRUCTURE />';
						echo "\n";
				    	echo '      <LSCFUNDINGSTREAM />';
						echo "\n";
				    	echo '      <SOURCEOFFUNDING />';
						echo "\n";
				    	echo '      <IMPLIEDRATEOFLSCFUNDING>0</IMPLIEDRATEOFLSCFUNDING>';
						echo "\n";
				    	echo '      <DELIVERYMODE />';
				    	echo "\n";
				    	echo '      <MAINDELIVERYMETHOD />';
						echo "\n";
				    	echo '      <FRANCHISEDOUTPARTNER />';
						echo "\n";
				    	echo '      <FRANCHISEDOUTPARTNERNUMBER />';
						echo "\n";
				    	echo '      <SOC />';
						echo "\n";
				    	echo '      <LSC_PROJECTSPILOTS />';
						echo "\n";
						echo '      <LSC_DISTANCEFUNDING>0</LSC_DISTANCEFUNDING>';
						echo "\n";
						echo '      <DELIVERYUPIN />';
						echo "\n";
						echo '      <ALTRANGECAPTION />';
						echo "\n";
						echo '      <ALTKNOWLEDGECAPTION />';
						echo "\n";
						echo '      <ALTPERFORMANCECAPTION />';
						echo "\n";
						echo '      <PROGRAMMETYPE>99</PROGRAMMETYPE>';
						echo "\n";
						echo '      <NOIV>N</NOIV>';
						echo "\n";
						// riche - value required - dummy data
						echo '		<CLAIMLEVEL>1</CLAIMLEVEL>'; // 1
						echo "\n";
					// -----------
					// riche - value required - dummy data
		    			//$displayindex++;
		    			//$elements++;
		    			//$evidences++;
					if ( is_array($qual_spec_details->qual_specification) && !empty($qual_spec_details->qual_specification) ) {	
		    			//echo '		<MODULEEVIDENCE diffgr:id="MODULEEVIDENCE'.$evidences.'" msdata:rowOrder="'.($evidences-1).'">';
		    			//echo "\n";
        				//echo '			<EVIDENCEID>'.$evidences.'</EVIDENCEID>';
        				//echo "\n";
        				////re: ER - first for skills, was previously UO ??
        				//echo '			<EVIDENCECATEGORY>ER</EVIDENCECATEGORY>';
        				//echo "\n";
						//echo '			<MODCODE>'.str_replace('/','',$row['id']).'.'.$unit_number.'</MODCODE>';
						//echo "\n";
						//if ( isset($qual_spec_details->qual_specification[$qan_title]['unit_aim']) && $qual_spec_details->qual_specification[$qan_title]['unit_aim'] != "" ) {
				    	//	echo '      	<DESCRIPTION>'.htmlspecialchars(trim($qual_spec_details->qual_specification[$qan_title]['unit_aim'])).'</DESCRIPTION>';
						//}
						//elseif(isset($qual_spec_details->qual_specification[$qan_title]['unit_summary']) && $qual_spec_details->qual_specification[$qan_title]['unit_summary'] != "" ) {
						//	echo '		    <DESCRIPTION>'.htmlspecialchars(trim($qual_spec_details->qual_specification[$qan_title]['unit_summary'])).'</DESCRIPTION>';
						//}
        				//echo "\n";
        				//echo '			<DISPLAYINDEX>'.$displayindex.'</DISPLAYINDEX>';
        				//echo "\n";
        				////re - evgroup sets as a heading - not in first4skills samples?
        				////echo "			<EVGROUP>-2</EVGROUP>\n";
        				//echo '		</MODULEEVIDENCE>';
		    			//echo "\n";
		    			
						if ( isset($qual_spec_details->qual_specification[$qan_title]['unit_requirements']) ) {
							$unit_requirements = explode("|", $qual_spec_details->qual_specification[$qan_title]['unit_requirements'] );
							foreach ( $unit_requirements as $req_line ) {
								$req_line = trim($req_line);
								if ( $req_line != "" ) {
									$displayindex++;
		    						$evidences++;
		    						echo '		<MODULEEVIDENCE diffgr:id="MODULEEVIDENCE'.$evidences.'" msdata:rowOrder="'.($evidences-1).'">';
		    						echo "\n";
        							echo '			<EVIDENCEID>'.$evidences.'</EVIDENCEID>';
        							echo "\n";
        							//re: ER - first for skills, was previously UO ??
        							echo '			<EVIDENCECATEGORY>ER</EVIDENCECATEGORY>';
        							echo "\n";
									echo '        	<MODCODE>'.str_replace('/','',$row['id']).'.'.$unit_number.'</MODCODE>';
									echo "\n";
					    			echo '			<DESCRIPTION>'.htmlspecialchars((string)$req_line).'</DESCRIPTION>';
									echo "\n";
        							echo '			<DISPLAYINDEX>'.$displayindex.'</DISPLAYINDEX>';
        							echo "\n";
        							echo '      </MODULEEVIDENCE>';
		    						echo "\n";
								}
							}	
						}
					}
					
					// -----------
					
					echo '    </MODULE>';
					echo "\n";
					$e2 = $node->getElementsByTagName('element');
					$elements = 0;
					foreach($e2 as $node2)
					{
							  $module++;
							  $elements++;	

							  $element_code = sprintf('%02d', $elements);
							  
							  echo '    <MODULE diffgr:id="MODULE'. $module . '" msdata:rowOrder="' . ($module-1) . '">';
		    				  echo "\n";
		    				  // riche - added in the hyphen
							  echo '      <MODULEID>0001-0000-' . str_pad($module,6,'0',STR_PAD_LEFT) . '</MODULEID>';
		    				  echo "\n";
		    				  // riche - added in 00TT
							  echo '      <Q_ADMINCENTREID>0000</Q_ADMINCENTREID>';
		    				  echo "\n";
		    				  // riche - default data 
							  echo '      <Q_ORGID>0001</Q_ORGID>';
		    				  echo "\n";
							  echo '      <MODCODE>' . str_replace('/','',$row['id']) . '.' . $unit_number . '.'.$element_code.'</MODCODE>';
		    				  echo "\n";
							  echo '      <PARENTMOD>' . str_replace('/','',$row['id']) . '.' . $unit_number . '</PARENTMOD>';
		    				  echo "\n";
		    				  $modtitle_display = preg_replace('/ and apos;/', "'", $display_unit_number.'.'.htmlspecialchars((string)$node2->getAttribute('title')));
		    				  // flag these up for internal verification
		    				  // ---
		    				  if ( strlen($modtitle_display) >= 150 ) {
		    				    		$modtitle_display = "<MODTITLE>ISSUE: ".$modtitle_display.'</MODTITLE>';
		    				  }
							  else {
							  	echo '      <MODTITLE>'.$modtitle_display.'</MODTITLE>';
							  }
		    				  echo "\n";
							  echo '      <MODCATEGORY>Element</MODCATEGORY>';
		    				  echo "\n";
							  echo '      <MODDESCRP />';
		    				  echo "\n";
		    				  if ( preg_match('/mandatory/i', $units_type->item($idx)->getAttribute('title')) ) {
		    				  	echo '      <MANDATORY>Y</MANDATORY>';	
		    				  }
		    				  else {
							  	echo '      <MANDATORY>N</MANDATORY>';
		    				  }
		    				  echo "\n";
							  echo '      <OPTIONSGROUP />';
		    				  echo "\n";
							  echo '      <DELIVERYCOST>0</DELIVERYCOST>';
		    				  echo "\n";
							  echo '      <FUNDINGAMOUNT>0</FUNDINGAMOUNT>';
		    				  echo "\n";
		    				  // riche - value required - dummy data
							  // echo '      <ACTIVEDATE>'.$row['operational_start_date'].'T00:00:00+00:00</ACTIVEDATE>';
		    				  // echo "\n";
		    				  // riche - value required - dummy data
							  // echo '      <OBSOLETEDATE>'.$row['operational_end_date'].'T00:00:00+00:00</OBSOLETEDATE>';
		    				  // echo "\n";
							  echo '      <USEDBYCENTRES />';
		    				  echo "\n";
							  echo '      <VERSION>0</VERSION>';
		    				  echo "\n";
							  echo '      <MODLEVEL>0</MODLEVEL>';
		    				  echo "\n";
							  echo '      <AWARDINGBODY />';
		    				  echo "\n";
							  echo '      <NVQREF />';
		    				  echo "\n";
							  echo '      <RELATIVESTART>0</RELATIVESTART>';
		    				  echo "\n";
							  echo '      <RELATIVETARGET>0</RELATIVETARGET>';
		    				  echo "\n";
							  echo '      <RELATIVEPERCENTAGE>0</RELATIVEPERCENTAGE>';
		    				  echo "\n";
							  echo '      <IncludeOnReturn>N</IncludeOnReturn>';
		    				  echo "\n";
							  echo '      <FinalCertDate>'.$final_cert_date.'</FinalCertDate>';
		    				  echo "\n";
							  echo '      <AREAOFLEARNING />';
		    				  echo "\n";
							  echo '      <QUALTYPE />';
		    				  echo "\n";
							  echo '      <GUIDEDLEARNINGHOURS>0</GUIDEDLEARNINGHOURS>';
		    				  echo "\n";
							  echo '      <UNITGROUP />';
		    				  echo "\n";
							  echo '      <RANGESTATEMENT />';
		    				  echo "\n";
							  echo '      <EVIDENCE />';
		    				  echo "\n";
							  echo '      <PERFORMANCE />';
		    				  echo "\n";
							  echo '      <STRUCTURE />';
		    				  echo "\n";
							  echo '      <LSCFUNDINGSTREAM />';
		    				  echo "\n";
							  echo '      <SOURCEOFFUNDING />';
		    				  echo "\n";
							  echo '      <IMPLIEDRATEOFLSCFUNDING>0</IMPLIEDRATEOFLSCFUNDING>';
		    				  echo "\n";
							  echo '      <DELIVERYMODE />';
		    				  echo "\n";
							  echo '      <MAINDELIVERYMETHOD />';
		    				  echo "\n";
							  echo '      <FRANCHISEDOUTPARTNER />';
		    				  echo "\n";
							  echo '      <FRANCHISEDOUTPARTNERNUMBER />';
		    				  echo "\n";
							  echo '      <SOC />';
						      echo "\n";
							  echo '      <LSC_PROJECTSPILOTS />';
		    				  echo "\n";
							  echo '      <LSC_DISTANCEFUNDING>0</LSC_DISTANCEFUNDING>';
		    				  echo "\n";
							  echo '      <DELIVERYUPIN />';
		    				  echo "\n";
							  echo '      <ALTRANGECAPTION />';
		    				  echo "\n";
							  echo '      <ALTKNOWLEDGECAPTION />';
		    				  echo "\n";
							  echo '      <ALTPERFORMANCECAPTION />';
		    				  echo "\n";
							  echo '      <PROGRAMMETYPE></PROGRAMMETYPE>';
		    				  echo "\n";
							  echo '      <NOIV>N</NOIV>';
		    				  echo "\n";
		    				  // riche - value required - dummy data
							  echo '      <CLAIMLEVEL>0</CLAIMLEVEL>';
		    				  echo "\n";
		    				  echo '      <DISPLAYINDEX>'.$displayindex.'</DISPLAYINDEX>';
							  echo "\n";
							  $displayindex++;
						$e3 = $node2->getElementsByTagName('evidence');
						foreach( $e3 as $node3 ) {
								$title_content = preg_replace('/ and apos;/i', '\'', $node3->getAttribute('title'));
								$displayindex++;
								$evidences++;
								// $module++;
								echo '      <MODULEEVIDENCE diffgr:id="MODULEEVIDENCE' . $evidences . '" msdata:rowOrder="' . ($evidences-1) . '">';
		    				    echo "\n";
								echo '        <EVIDENCEID>'.$evidences.'</EVIDENCEID>';
		    				    echo "\n";
		    				    // riche - needs to be PC / RANGE / KNOWLEDGE
		    				    //re: AC - first for skills, was previously LO ??
								echo '        <EVIDENCECATEGORY>AC</EVIDENCECATEGORY>';
		    				    echo "\n";
								echo '        <MODCODE>'.str_replace('/','',$row['id']).'.'.$unit_number.'.'.$element_code.'</MODCODE>';
		    				    echo "\n";
								echo '        <DESCRIPTION>'.htmlspecialchars((string)$title_content).'</DESCRIPTION>';
		    				    echo "\n";
								echo '        <DISPLAYINDEX>'.$displayindex.'</DISPLAYINDEX>';
		    				    echo "\n";
								echo '      </MODULEEVIDENCE>';
		    				    echo "\n";
		    				    
							//	$this_evidence = htmlspecialchars((string)$node3->getAttribute('title'));
							//	if ( preg_match('/^\d+/', $this_evidence) ) {
							//		// ignore any evidences without descriptions
							//		if ( strlen($this_evidence) <= 1 ) {
							//			continue;
							//		}
							//		$title_content = preg_replace('/0$/', '', $node3->getAttribute('title'));
							//		// added additional 
							//		$title_content = preg_replace('/ and apos;/i','\'', $title_content);
							//									
							//		$bullet_list_content = preg_split('/\*/x', $title_content);
							//		
							//		foreach ($bullet_list_content as $bl_id => $bl_text ) {
							//
							//			// remove odd punctuation in learning outcome sections
							//			$bl_text = preg_replace('/ \. /', " ", $bl_text);
							//			
							//			$elements++;
							//			echo '      <MODULEEVIDENCE diffgr:id="MODULEEVIDENCE' . $elements . '" msdata:rowOrder="' . ($elements-1) . '">'."\n";
							//			echo '        	<EVIDENCEID>'.$elements.'</EVIDENCEID>'."\n";
							//			echo '        	<EVIDENCECATEGORY>LO</EVIDENCECATEGORY>'."\n";
							//			// RE - TESTING USING THE MODCODE AS DISCOVERED WITH FIRST4SKILLS	
							//			//echo '        	<MODCODE>'.str_replace('/','',$row['id']).'.'.$unit_number.'.'.$elements.'</MODCODE>'."\n";
							//			echo '          <MODCODE>'.str_replace('/','',$row['id']).'.'.$unit_number.'</MODCODE>'."\n";
							//			$output_text = htmlspecialchars(trim($bl_text));
							//			if ( preg_match('/^(\d+)/', $output_text) ) { 
							//				echo '        	<DESCRIPTION>'.htmlspecialchars(trim($bl_text)).'</DESCRIPTION>'."\n";
							//				// if ( preg_match('/\:$/', $output_text) ) {
							//				// 	echo "			<EVGROUP>-2</EVGROUP>\n";		
							//				// }	
							//			}
							//			else {
							//				echo '        	<DESCRIPTION> - '.htmlspecialchars(trim($bl_text)).'</DESCRIPTION>'."\n";
							//			}
							//			echo '        	<DISPLAYINDEX>'.$displayindex.'</DISPLAYINDEX>'."\n";
							//			echo "      </MODULEEVIDENCE>\n";
		    			    //			$displayindex++;
							//		}
							//	}   
							 	    
						}				
						
						echo '    </MODULE>';
		    		    echo "\n";
					}
				}
				}

				echo '  </NewDataSet>';
				echo "\n";
				echo '</diffgr:diffgram>';
			}
		}		
	}
}

?>