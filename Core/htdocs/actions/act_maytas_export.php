<?php
class maytas_export implements IAction
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

			while( $row = $st->fetch() ) {				
				$module = 1;
				$units = 0;
				$elements = 0;
				$evidences = 0;
				$filename = $row['internaltitle'];
				if ( !isset($headers_sent) ) {
                	header('Content-Disposition: attachment; filename="'.$qan_code.' - ' . $filename . '.mmf"');
                	echo '<?xml version="1.0" standalone="yes"?>'."\n";
					echo '<diffgr:diffgram xmlns:msdata="urn:schemas-microsoft-com:xml-msdata" xmlns:diffgr="urn:schemas-microsoft-com:xml-diffgram-v1">'."\n";
                    $headers_sent = 1;
                }
				echo "  <NewDataSet>\n";
				echo '    <MODULE diffgr:id="MODULE1" msdata:rowOrder="0">'."\n";
				// riche - added in hyphen after 00TT...
				echo '      <MODULEID>0001-00TT-' . str_pad($module,6,'0',STR_PAD_LEFT) . '</MODULEID>'."\n";
				// riche - changed to 00TT from 0001...
				echo '      <Q_ADMINCENTREID>00TT</Q_ADMINCENTREID>'."\n";
				echo '      <Q_ORGID>0001</Q_ORGID>'."\n";
				echo '      <MODCODE>' . str_replace('/','',$row['id']) . '</MODCODE>'."\n";
				echo '      <MODTITLE>' .htmlspecialchars((string)$row['title']). '</MODTITLE>'."\n";
				echo '      <MODCATEGORY>Base</MODCATEGORY>'."\n";
				echo '      <MODDESCRP />'."\n";
				// re 28/09/2011 - value still required ?? - dummy data
				echo '      <MANDATORY>N</MANDATORY>'."\n";
				echo '      <OPTIONSGROUP />'."\n";
				echo '      <DELIVERYCOST>0</DELIVERYCOST>'."\n";
				echo '      <FUNDINGAMOUNT>0</FUNDINGAMOUNT>'."\n";
				echo '      <ACTIVEDATE>'.$row['operational_start_date'].'T00:00:00+00:00</ACTIVEDATE>'."\n";
				// check qual_spec_detail exists
				if ( $qual_spec_details->acc_start_date ) {
					echo '      <OBSOLETEDATE>'.$qual_spec_details->acc_end_date.'T00:00:00+00:00</OBSOLETEDATE>'."\n";
					echo '      <ACCSTARTDATE>'.$qual_spec_details->acc_start_date.'T00:00:00+00:00</ACCSTARTDATE>'."\n";
					echo '      <ACCENDDATE>'.$qual_spec_details->acc_end_date.'T00:00:00+00:00</ACCENDDATE>'."\n";
				}
				echo '      <USEDBYCENTRES />'."\n";
				// riche - value required - dummy data
				echo '      <VERSION>0</VERSION>'."\n";
				echo '      <MODLEVEL>' . $row['level'] . '</MODLEVEL>'."\n";
				//? first three
				echo '      <AWARDINGBODY>' . substr($row['awarding_body'],0,3) . '</AWARDINGBODY>'."\n";
				// all ?
				echo '      <NVQREF>' . str_replace('/','',$row['id']) . '</NVQREF>'."\n";
				// ?
				echo '      <RELATIVESTART>0</RELATIVESTART>'."\n";
				// ?
				echo '      <RELATIVETARGET>0</RELATIVETARGET>'."\n";
				// ?
				echo '      <RELATIVEPERCENTAGE>0</RELATIVEPERCENTAGE>'."\n";
				// re 28/09/2011 - value still required ?? - dummy data
				echo '      <IncludeOnReturn>N</IncludeOnReturn>'."\n";
				$final_cert_date = ($row['certification_end_date'] =='')?'2020-12-30T00:00:00+00:00':$row['certification_end_date'];
				echo '      <FinalCertDate>'.$final_cert_date.'</FinalCertDate>'."\n";
				echo '      <AREAOFLEARNING />'."\n";
				echo '      <QUALTYPE>' . $row['qualification_type'] . '</QUALTYPE>'."\n";
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
				
				echo "      <UNITGROUP />\n";
				echo "      <RANGESTATEMENT />\n";
				echo "      <EVIDENCE />\n";
				echo "      <PERFORMANCE />\n";
				echo "      <STRUCTURE />\n";
				echo "      <LSCFUNDINGSTREAM />\n";
				echo "      <SOURCEOFFUNDING />\n";
				echo "      <IMPLIEDRATEOFLSCFUNDING>0</IMPLIEDRATEOFLSCFUNDING>\n";
				echo "      <DELIVERYMODE />\n";
				echo "      <MAINDELIVERYMETHOD />\n";
				echo "      <FRANCHISEDOUTPARTNER />\n";
				echo "      <FRANCHISEDOUTPARTNERNUMBER />\n";
				echo "      <SOC />\n";
				echo "      <LSC_PROJECTSPILOTS />\n";
				echo "      <LSC_DISTANCEFUNDING>0</LSC_DISTANCEFUNDING>\n";
				echo "      <DELIVERYUPIN />\n";
				echo "      <ALTRANGECAPTION />\n";
				echo "      <ALTKNOWLEDGECAPTION />\n";
				echo "      <ALTPERFORMANCECAPTION />\n";
				
				echo '      <AWARDREF>' . str_replace('/','',$row['id']) . '</AWARDREF>'."\n";
				
				echo "     <PROGRAMMETYPE>99</PROGRAMMETYPE>\n";
				echo "      <LSC_ASLPROVISIONTYPE />\n";
				echo "      <NOIV>N</NOIV>\n";
				// riche - value required - dummy data
				echo "      <CLAIMLEVEL>0</CLAIMLEVEL>\n";
				// riche - value required - dummy data
				echo "      <QCFpoints>0</QCFpoints>\n";
				echo "    </MODULE>\n";
				
				//$pageDom = new DomDocument();
				//$pageDom->loadXML(utf8_encode($row['evidences']));
				$pageDom = XML::loadXmlDom(mb_convert_encoding($row['evidences'],'UTF-8'));
				
				$units_type = $pageDom->getElementsByTagName('units');
				$unitCnt  = $units_type->length;

				for ($idx = 0; $idx < $unitCnt; $idx++) {
					// re - get all the individual units in this units block
					//    - is this always mandatory & optional
					//    - if so we have units->units(mandatory) & units->units(optional)
					//    - need to disregard the initial units
					if ( $units_type->item($idx)->getElementsByTagName('units')->length > 0 ) {
					 	continue;
					}
    				$e = $units_type->item($idx)->getElementsByTagName('unit');
    				
    				// re - try and do some sorting if possible
    				//	  - as despite utilising the all the ordering elements
    				//    - possible in the MMF file, it seems to display based on the
    				//    - order of the units in the mmf file ( top -> bottom )
    				//    - this relies on the specification data to sort
    				$fish = '';
    				if ( is_array($qual_spec_details->qual_specification) && !empty($qual_spec_details->qual_specification) ) {
    					$no_of_units = $e->length;
    			   				
    					$units_in_order = array();
    					    				
    					for($udx = 0; $udx < $no_of_units; $udx++ ) {
    						$for_order_qan_title = preg_replace("/[^a-zA-Z0-9!?\'\"]/", "", strtolower($e->item($udx)->getAttribute('title')));					
    						
    						if( in_array($for_order_qan_title, array_keys($qual_spec_details->qual_specification)) ) {
    							$units_in_order[$qual_spec_details->qual_specification[$for_order_qan_title]['unit_id']] = $e->item($udx);
    						}  					
							// re - potentially added in (credit value xx) at the end - need to remove?
							elseif ( preg_match("/creditvalue\d+$/", $for_order_qan_title) ) {
								$for_order_qan_title = preg_replace("/creditvalue\d+$/", "", $for_order_qan_title);	
								if( in_array($for_order_qan_title, array_keys($qual_spec_details->qual_specification)) ) {
                           			$units_in_order[$qual_spec_details->qual_specification[$for_order_qan_title]['unit_id']] = $e->item($udx);
                            	}
							}
    					}
    					
    					// re - sort on the keys to get in right order
    					ksort($units_in_order);
   					
    					// replace the original node listings with the reordered ones
    					$e = $units_in_order;
    				}
    				    				
    				$displayindex = 1;
    				// $elements = 1;
					foreach( $e as $node ) {
						// #193 {0000000282} - present the unit details from specification
						// ensure a match on title when typos are different.
						// /[^a-zA-Z0-9 .,!?'\"\s+]/
						$qan_title = preg_replace("/[^a-zA-Z0-9!?\'\"]/", "", strtolower($node->getAttribute('title')));
						
						if ( preg_match("/creditvalue\d+$/", $qan_title) ) {
							$qan_title = preg_replace("/creditvalue\d+$/", "", 	$qan_title);
						}
						
						$module++;
						$units++;

						if ( preg_match("/Unit (\d+)$/", $node->getAttribute('reference'), $matches ) ) {
							$unit_number = $matches[1];
							
						}	
						else {
							$unit_number = $units;
						}
						$unit_number = str_pad(2,$unit_number,"0",STR_PAD_LEFT);
						// we have extracted information from the specification file				
						if ( in_array($qan_title, array_keys($qual_spec_details->qual_specification)) ) {
							$unit_number = $qual_spec_details->qual_specification[$qan_title]['unit_id'];
							$unit_number = str_pad($unit_number, 2, '0', STR_PAD_LEFT);
							echo '    <MODULE diffgr:id="MODULE'.$module.'" msdata:rowOrder="'.($module-1).'">';
							echo "\n";
							// riche - added in hyphen 
							echo '      <MODULEID>0001-0000-' . str_pad($qual_spec_details->qual_specification[$qan_title]['unit_id'],6,'0',STR_PAD_LEFT) . '</MODULEID>';
							echo "\n";
							// riche - default value required
				    			echo '      <Q_ADMINCENTREID>0000</Q_ADMINCENTREID>'; // 00TT
							echo "\n";
							// riche - default value required
				    			echo '      <Q_ORGID>0000</Q_ORGID>'; //0001
							echo "\n";
				    			echo '      <MODCODE>' . str_replace('/','',$row['id']) . '.'.$unit_number.'</MODCODE>';
							echo "\n";
				    			echo '      <PARENTMOD>'. str_replace('/','',$row['id']) . '</PARENTMOD>';
							echo "\n";
				    			echo '      <MODCATEGORY>Unit</MODCATEGORY>';
							echo "\n";
							echo '      <DISPLAYINDEX>'.$displayindex.'</DISPLAYINDEX>';
							echo "\n";
							$displayindex++;
							echo '      <MODNUMBER>'.$qual_spec_details->qual_specification[$qan_title]['unit_id'].'</MODNUMBER>';
							echo "\n";
							echo '      <MODTITLE>Unit '.$qual_spec_details->qual_specification[$qan_title]['unit_id'].': ';
							// echo htmlspecialchars( $node->getAttribute('title')).' '.$qual_spec_details->qual_specification[$qan_title]['unit_code'].'</MODTITLE>';
							echo htmlspecialchars( $node->getAttribute('title')).' (Credit Value '.$node->getAttribute('credits').')';
							if ( isset($qual_spec_details->qual_specification[$qan_title]['unit_reference_number']) ) {
								echo ' '.htmlspecialchars((string)$qual_spec_details->qual_specification[$qan_title]['unit_reference_number']);	
							}
							echo '</MODTITLE>';
							echo "\n";
							echo '      <MODREF>'.htmlspecialchars((string)$qual_spec_details->qual_specification[$qan_title]['unit_reference_number']).'</MODREF>';
							echo "\n";
							if ( $qual_spec_details->qual_specification[$qan_title]['unit_aim'] ) {
				    			echo '      <MODDESCRP>'.htmlspecialchars(trim($qual_spec_details->qual_specification[$qan_title]['unit_aim'])).'</MODDESCRP>';
							}
							else {
								echo '      <MODDESCRP>'.htmlspecialchars(trim($qual_spec_details->qual_specification[$qan_title]['unit_summary'])).'</MODDESCRP>';
							}
							echo "\n";
							// concatenate line breaks in the MODREQS field
							$mod_reqs = '';
							if ( isset($qual_spec_details->qual_specification[$qan_title]['unit_requirements']) )  {
								$mod_reqs = preg_replace(array('/\|/', '/\\n/', '/\\r/'), '', $qual_spec_details->qual_specification[$qan_title]['unit_requirements']);
							}
							echo '      <MODREQS>'.htmlspecialchars((string)$mod_reqs).'</MODREQS>';
							echo "\n";
							echo '      <MODEVIDENCECNT>'.htmlspecialchars((string)$qual_spec_details->qual_specification[$qan_title]['unit_evidence']).'</MODEVIDENCECNT>';
							echo "\n";
							echo '      <MODOBSERVECNT>'.htmlspecialchars((string)$qual_spec_details->qual_specification[$qan_title]['unit_observation']).'</MODOBSERVECNT>';
							echo "\n";
						}
						else {
					    		echo '    <MODULE diffgr:id="MODULE'.$module.'" msdata:rowOrder="'.($module-1).'">';
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
				    			echo '      <MODCODE>' . str_replace('/','',$row['id']) . '.' . ($unit_number) . '</MODCODE>';
							echo "\n";
				    			echo '      <PARENTMOD>'. str_replace('/','',$row['id']) . '</PARENTMOD>';
							echo "\n";
				    			echo '      <MODCATEGORY>Unit</MODCATEGORY>';
							echo "\n";
							echo '      <DISPLAYINDEX>'.$displayindex.'</DISPLAYINDEX>';
							echo "\n";
							$displayindex++;
							
							echo '      <MODTITLE>'.htmlspecialchars((string)$node->getAttribute('title')).'</MODTITLE>';
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
						$elements++;
						echo '      <MODULEEVIDENCE diffgr:id="MODULEEVIDENCE'.$elements.'" msdata:rowOrder="'.($elements-1).'">';
		    			echo "\n";
						echo '        	<EVIDENCEID>'.$elements.'</EVIDENCEID>';
		    			echo "\n";
		    			// riche - needs to be PC / RANGE / KNOWLEDGE
						echo '        	<EVIDENCECATEGORY>UO</EVIDENCECATEGORY>';
		    			echo "\n";
						// RE - TESTING USING THE MODCODE AS DISCOVERED WITH FIRST4SKILLS
						// echo '        	<MODCODE>'.str_replace('/','',$row['id']).'.'.$unit_number.'.'.$elements.'</MODCODE>';
						echo '               <MODCODE>'.str_replace('/','',$row['id']).'.'.$unit_number.'</MODCODE>';
		    			echo "\n";
		    			echo '        	<DESCRIPTION>UNIT AIM</DESCRIPTION>';
		    			echo "\n";
		    			
		    			echo "			<EVGROUP>-2</EVGROUP>\n";
						echo '        	<DISPLAYINDEX>'.$displayindex.'</DISPLAYINDEX>';
		    			echo "\n";
						echo '      </MODULEEVIDENCE>';
		    			echo "\n";
		    			$displayindex++;
		    			$elements++;
					if ( is_array($qual_spec_details->qual_specification) && !empty($qual_spec_details->qual_specification) ) {	
						
						$unit_aim_summary = ''; 
						if ( isset($qual_spec_details->qual_specification[$qan_title]['unit_aim']) && $qual_spec_details->qual_specification[$qan_title]['unit_aim'] != "" ) {
							$unit_aim_summary = $qual_spec_details->qual_specification[$qan_title]['unit_aim'];	
						}
						elseif(isset($qual_spec_details->qual_specification[$qan_title]['unit_summary']) && $qual_spec_details->qual_specification[$qan_title]['unit_summary'] != "" ) {
							$unit_aim_summary = $qual_spec_details->qual_specification[$qan_title]['unit_summary'];
						}
						
						if ( $unit_aim_summary != '' ) {
							preg_replace('/ and apos;/i','\'', $unit_aim_summary);	
		    				echo '		<MODULEEVIDENCE diffgr:id="MODULEEVIDENCE'.$elements.'" msdata:rowOrder="'.($elements-1).'">';
		    				echo "\n";
        					echo '			<EVIDENCEID>'.$elements.'</EVIDENCEID>';
        					echo "\n";
        					echo '			<EVIDENCECATEGORY>UO</EVIDENCECATEGORY>';
        					echo "\n";
							// RE - TESTING USING THE MODCODE AS DISCOVERED WITH FIRST4SKILLS
        					// echo '			<MODCODE>'.str_replace('/','',$row['id']).'.'.$unit_number.'.'.$elements.'</MODCODE>';
							echo '			<MODCODE>'.str_replace('/','',$row['id']).'.'.$unit_number.'</MODCODE>';
							echo "\n";
							echo '		    <DESCRIPTION>'.htmlspecialchars((string)$unit_aim_summary).'</DESCRIPTION>';
        					echo "\n";
        					echo '			<DISPLAYINDEX>'.$displayindex.'</DISPLAYINDEX>';
        					echo "\n";
        					echo "			<EVGROUP>-2</EVGROUP>\n";
        					echo '      </MODULEEVIDENCE>';
		    				echo "\n";
						}
						if ( isset($qual_spec_details->qual_specification[$qan_title]['unit_requirements']) ) {								
							$unit_requirements = explode("|", $qual_spec_details->qual_specification[$qan_title]['unit_requirements'] );
							foreach ( $unit_requirements as $req_line ) {
								$req_line = trim($req_line);
								if ( $req_line != "" ) {
									preg_replace('/ and apos;/i','\'', $req_line);	
									$displayindex++;
		    						$elements++;
		    						echo '		<MODULEEVIDENCE diffgr:id="MODULEEVIDENCE'.$elements.'" msdata:rowOrder="'.($elements-1).'">';
		    						echo "\n";
        							echo '			<EVIDENCEID>'.$elements.'</EVIDENCEID>';
        							echo "\n";
        							echo '			<EVIDENCECATEGORY>UO</EVIDENCECATEGORY>';
        							echo "\n";
									echo '        	<MODCODE>'.str_replace('/','',$row['id']).'.'.$unit_number.'</MODCODE>';
									echo "\n";
					    			echo '			<DESCRIPTION>'.htmlspecialchars((string)$req_line).'</DESCRIPTION>';
									echo "\n";
        							echo '			<DISPLAYINDEX>'.$displayindex.'</DISPLAYINDEX>';
        							echo "\n";
        							echo "			<EVGROUP>-2</EVGROUP>\n";
        							echo '      </MODULEEVIDENCE>';
		    						echo "\n";
								}
							}	
						}
					}
					
				
					$unit_content = array();
					
					if ( !empty( $qual_spec_details->qual_specification[$qan_title]['unit_content'] ) ) {
							$unit_content = explode("|", $qual_spec_details->qual_specification[$qan_title]['unit_content'] );
							
					}
					else {
					// 	throw new Exception(pre($qual_spec_details->qual_specification));
					}
					
					$e2 = $node->getElementsByTagName('element');
					
					
					// this is a bit dubious - tighten this up
					$lo_count = 1;
						
					foreach($e2 as $node2) {
						$this_element = htmlspecialchars((string)$node2->getAttribute('title'));
						// see if this works?
						preg_replace('/ and apos;/i','\'', $this_element);
						if ( preg_match('/^\d+/', $this_element) ) {
							$elements++;
							echo '      <MODULEEVIDENCE diffgr:id="MODULEEVIDENCE'.$elements.'" msdata:rowOrder="'.($elements-1).'">';
		    				echo "\n";
							echo '        	<EVIDENCEID>'.$elements.'</EVIDENCEID>';
		    				echo "\n";
		    				// riche - needs to be PC / RANGE / KNOWLEDGE
							echo '        	<EVIDENCECATEGORY>LO</EVIDENCECATEGORY>';
		    				echo "\n";
							// RE - TESTING USING THE MODCODE AS DISCOVERED WITH FIRST4SKILLS	
							// echo '        	<MODCODE>'.str_replace('/','',$row['id']).'.'.$unit_number.'.'.$elements.'</MODCODE>';
							echo '          <MODCODE>'.str_replace('/','',$row['id']).'.'.$unit_number.'</MODCODE>';
		    				echo "\n";
		    				echo '        	<DESCRIPTION>OUTCOME '.strtoupper($this_element).'</DESCRIPTION>';
		    			    echo "\n";
							echo '        	<DISPLAYINDEX>'.$displayindex.'</DISPLAYINDEX>';
		    				echo "\n";
		    				echo '		<EVGROUP>-2</EVGROUP>';
		    				echo "\n";
							echo '      </MODULEEVIDENCE>';
		    				echo "\n";	    				
		    				$displayindex++;
		    				$elements++;
		    				echo '		<MODULEEVIDENCE diffgr:id="MODULEEVIDENCE'.$elements.'" msdata:rowOrder="'.($elements-1).'">';
		    				echo "\n";
        					echo '			<EVIDENCEID>'.$elements.'</EVIDENCEID>';
        					echo "\n";
        					echo '			<EVIDENCECATEGORY>LO</EVIDENCECATEGORY>';
        					echo "\n";
							// RE - TESTING USING THE MODCODE AS DISCOVERED WITH FIRST4SKILLS
        					// echo '			<MODCODE>'.str_replace('/','',$row['id']).'.'.$unit_number.'.'.$elements.'</MODCODE>';
							echo '                  <MODCODE>'.str_replace('/','',$row['id']).'.'.$unit_number.'</MODCODE>';
        					echo "\n";
        					echo "			<DESCRIPTION>The learner can:</DESCRIPTION>\n";
        					echo '			<DISPLAYINDEX>'.$displayindex.'</DISPLAYINDEX>'."\n";
        					echo "			<EVGROUP>-2</EVGROUP>\n";
        					echo "      </MODULEEVIDENCE>\n";
							$displayindex++;
							$e3 = $node2->getElementsByTagName('evidence');
							foreach( $e3 as $node3 ) {
								$this_evidence = htmlspecialchars((string)$node3->getAttribute('title'));
								if ( preg_match('/^\d+/', $this_evidence) ) {
									// ignore any evidences without descriptions
									if ( strlen($this_evidence) <= 1 ) {
										continue;
									}
									$title_content = preg_replace('/0$/', '', $node3->getAttribute('title'));
									// added additional 
									$title_content = preg_replace('/ and apos;/i','\'', $title_content);
									
									
									$bullet_list_content = preg_split('/\*/x', $title_content);
									
									foreach ($bullet_list_content as $bl_id => $bl_text ) {

										// remove odd punctuation in learning outcome sections
										$bl_text = preg_replace('/ \. /', " ", $bl_text);
										
										
										$elements++;
										echo '      <MODULEEVIDENCE diffgr:id="MODULEEVIDENCE' . $elements . '" msdata:rowOrder="' . ($elements-1) . '">'."\n";
										echo '        	<EVIDENCEID>'.$elements.'</EVIDENCEID>'."\n";
										echo '        	<EVIDENCECATEGORY>LO</EVIDENCECATEGORY>'."\n";
										// RE - TESTING USING THE MODCODE AS DISCOVERED WITH FIRST4SKILLS	
										//echo '        	<MODCODE>'.str_replace('/','',$row['id']).'.'.$unit_number.'.'.$elements.'</MODCODE>'."\n";
										echo '          <MODCODE>'.str_replace('/','',$row['id']).'.'.$unit_number.'</MODCODE>'."\n";
										$output_text = htmlspecialchars(trim($bl_text));
										if ( preg_match('/^(\d+)/', $output_text) ) { 
											echo '        	<DESCRIPTION>'.htmlspecialchars(trim($bl_text)).'</DESCRIPTION>'."\n";
											if ( preg_match('/\:$/', $output_text) ) {
												echo "			<EVGROUP>-2</EVGROUP>\n";		
											}	
										}
										else {
											echo '        	<DESCRIPTION> - '.htmlspecialchars(trim($bl_text)).'</DESCRIPTION>'."\n";
										}
										echo '        	<DISPLAYINDEX>'.$displayindex.'</DISPLAYINDEX>'."\n";
										echo "      </MODULEEVIDENCE>\n";
		    			    			$displayindex++;
									}
								}
							}				
						}
					}
					echo '    </MODULE>';
					echo "\n";
				}
				}
				echo '  </NewDataSet>';
				echo "\n";
				echo '</diffgr:diffgram>';
			}
		}		
	}
	
	
	// split the length of data routine....
	static function split_descriptions($string, $max = 150)
	{
    	$words = preg_split('/\s/', $string);
    	$lines = array();
    	$line = '';
   
    	foreach ($words as $k => $word) {
        	$length = strlen($line . ' ' . $word);
        	if ($length <= $max) {
            	$line .= ' ' . $word;
        	} else if ($length > $max) {
            	if (!empty($line)) {
            		$lines[] = trim($line);
            	}
            	$line = $word;
        	} else {
            	$lines[] = trim($line) . ' ' . $word;
            	$line = '';
        	}
    	}
    	$lines[] = ($line = trim($line)) ? $line : $word;
    	return $lines;
	} 
}

?>
