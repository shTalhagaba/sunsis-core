<?php
/**
 * verify_logbook
 *
 */
class verify_logbook implements IAction
{
	public function execute(PDO $link)
	{
		
		$estandard_units = array();
		$get_estandards = 0;
		
		// --- 
		// save a unit elements
		if ( isset($_REQUEST['save_content']) ) {
			if ( isset($_REQUEST['qid']) && isset($_REQUEST['unit_edit']) ) {
    			$unit_title = isset($_REQUEST['unit_title'])?preg_replace('/\n/', "|", $_REQUEST['unit_title']):'';
    			$unit_aim = isset($_REQUEST['unit_aim'])?preg_replace('/\n/', "|",$_REQUEST['unit_aim']):''; 
    			$unit_summary = isset($_REQUEST['unit_summary'])?preg_replace('/\n/', "|",$_REQUEST['unit_summary']):'';
    			$assess_criteria = isset($_REQUEST['assessment_criteria'])?preg_replace('/\n/', "|",$_REQUEST['assessment_criteria']):''; 
    			$unit_content = isset($_REQUEST['unit_content'])?preg_replace('/\n/', "|",$_REQUEST['unit_content']):''; 
    			$unit_requirements = isset($_REQUEST['unit_requirements'])?preg_replace('/\n/', "|",$_REQUEST['unit_requirements']):'';
    			$unit_observation = isset($_REQUEST['unit_observation'])?preg_replace('/\n/', "|",$_REQUEST['unit_observation']):''; 
    			$unit_evidence = isset($_REQUEST['unit_evidence'])?preg_replace('/\n/', "|",$_REQUEST['unit_evidence']):''; 
    			
    			$update_sql = 'UPDATE qualification_specification set ';
    			$update_sql .= 'unit_title = "'.htmlspecialchars((string)$unit_title).'", ';
    			$update_sql .= 'unit_aim = "'.htmlspecialchars((string)$unit_aim).'", ';
    			$update_sql .= 'unit_summary = "'.htmlspecialchars((string)$unit_summary).'", ';
    			$update_sql .= 'assessment_criteria = "'.htmlspecialchars((string)$assess_criteria).'", ';
    			$update_sql .= 'unit_content = "'.htmlspecialchars((string)$unit_content).'", ';
    			$update_sql .= 'unit_requirements = "'.htmlspecialchars((string)$unit_requirements).'", ';
    			$update_sql .= 'unit_observation = "'.htmlspecialchars((string)$unit_observation).'", ';
    			$update_sql .= 'unit_evidence = "'.htmlspecialchars((string)$unit_evidence).'" ';
    			$update_sql .= 'where qan_id = "'.$_REQUEST['qid'].'" and unit_id = "'.$_REQUEST['unit_edit'].'" ';

				DAO::execute($link, $update_sql);
    			
    			$_REQUEST['mesg'] = 'Updated '.$_REQUEST['unit_edit'].' Unit'; 
			}
		}
		
		if ( isset($_REQUEST['qid']) && !isset($_REQUEST['unit_edit']) ) {
			
			$qan_id = $_REQUEST['qid'];
			
			$qual_spec_details = QualSpecification::loadFromDatabase($link, $qan_id);
			
			$qan_sql = 'SELECT qualifications.title, qualifications.evidences, qualification_specification.* FROM qualifications ';
			$qan_sql .= 'LEFT JOIN qualification_specification ON qualifications.id = qualification_specification.qan_id ';
			$qan_sql .= 'where qualifications.id = "'.$qan_id.'" and qualifications.clients = "pearsonwbl" ';
			
			$st = $link->query($qan_sql);
			
			$qan_table = '<table class="resultset" >';
			$qan_table .= '<thead><tr>';
			
			$qan_table .= '<th>Spec Unit Code</th>';
			$qan_table .= '<th>Spec Unit ID</th>';
			$qan_table .= '<th>Spec Unit Title</th>';
			$qan_table .= '<th>eStandard (QM) Unit Code</th>';
			$qan_table .= '<th>eStandard (QM) Unit Title</th>';
			
			$qan_table .= '</tr></thead>';
			$qan_table .= '<tfoot></tfoot>';
			$qan_table .= '<tbody>';
			
			$units = 0;
			
			if( $st ) {	
				$row_count = 0;
				while( $row = $st->fetch() ) {	
					
					// populate the estandard section on the first run through only.
					if ( $get_estandards === 0  ) {
						$pageDom = new DomDocument();
						$pageDom->loadXML(mb_convert_encoding($row['evidences'],'UTF-8'));
				
						$units_type = $pageDom->getElementsByTagName('units');
						$unitCnt  = $units_type->length;
						
						

						for ($idx = 0; $idx < $unitCnt; $idx++) {
							if ( $units_type->item($idx)->getElementsByTagName('units')->length > 0 ) {
					 			continue;
							}
    						$e = $units_type->item($idx)->getElementsByTagName('unit');

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
    						
							foreach( $e as $node ) {
								$units++;
								$qan_title = preg_replace("/[^a-zA-Z0-9!?\'\"]/", "", strtolower($node->getAttribute('title')));
								
								if ( preg_match("/creditvalue\d+$/", $qan_title) ) {
									$qan_title = preg_replace("/creditvalue\d+$/", "", 	$qan_title);
								}
								if ( preg_match("/Unit ([\d+]{1,3})$/", $node->getAttribute('reference'), $matches ) ) {
									$unit_number = $matches[1];
								}	
								else {
									$unit_number = $units;
								}
								$unit_number = str_pad(2,$unit_number,"0",STR_PAD_LEFT);;
								// we have extracted information from the specification file				
								if ( in_array($qan_title, array_keys($qual_spec_details->qual_specification)) ) {
									$unit_number = $qual_spec_details->qual_specification[$qan_title]['unit_id'];									
									$estandard_units[$unit_number] = array(
										'unit_ref' => $node->getAttribute('reference'),
										'unit_title' => $node->getAttribute('title')
									);
								}
								// unit with the same name 
								elseif( in_array($qan_title."_unit".$unit_number, array_keys($qual_spec_details->qual_specification)) ) {
									$unit_number = $qual_spec_details->qual_specification[$qan_title]['unit_id'];									
									$estandard_units[$unit_number] = array(
										'unit_ref' => "[[".$node->getAttribute('reference')."]]",
										'unit_title' => $node->getAttribute('title')
									);	
								}						
							}
														
						}
						$get_estandards = 1;
					}
													
					if ( $row_count % 2 ) {
						$row_style = 'background-color: #F9F9F9';
					}
					else {
						$row_style = 'background-color: #FFFFFF';
					}	
					$qan_row = '<td>'.$row['unit_code'].'</td>';
					$qan_row .= '<td>'.$row['unit_id'].'</td>';
					$qan_row .= '<td><a href="do.php?_action=verify_logbook&amp;qid='.$qan_id.'&amp;unit_edit='.$row['unit_id'].'">'.$row['unit_title'].'</a></td>';
					$qan_row .= '<td>';
					
					if ( isset($estandard_units[$row['unit_id']])  ) {
						$qan_row .= $estandard_units[$row['unit_id']]['unit_ref'];	
					}
					$qan_row .= '</td>';
					$qan_row .= '<td>';
					if ( isset($estandard_units[$row['unit_id']]) ) {
						// do a string comparison
						$spec_title = preg_split('//', $row['unit_title'], -1);
						$qm_title = preg_split('//', $estandard_units[$row['unit_id']]['unit_title'], -1);
						$spec_title_length = sizeof($spec_title);
						$qm_title_length = sizeof($qm_title);
						$spec_increment = 0;
						$qm_increment = 0;
						while($spec_increment < $spec_title_length && $qm_increment < $qm_title_length ) {
							if ( $spec_title[$spec_increment] != $qm_title[$qm_increment] ) {
								if ( preg_match('/[\'\"\,\.]/', $spec_title[$spec_increment]) ) {
									$qan_row .= '<font style="background:red;">&nbsp;'.$spec_title[$spec_increment]."&nbsp;</font>";
									$spec_increment++;	
								}
								$qan_row .= '<font style="background:yellow;">'.$qm_title[$qm_increment]."</font>";	
							}
							else {
								$qan_row .= $qm_title[$qm_increment];
							}
							$qm_increment++;
							$spec_increment++;
						}
						while( $qm_increment < $qm_title_length ) {
							$qan_row .= '<font style="background:yellow;">'.$qm_title[$qm_increment]."</font>";
							$qm_increment++;	
						}
					}
					$qan_row .= '</td>';
					
					$qan_table .= '<tr style="'.$row_style.'" class="shortrecord" >'.$qan_row.'</tr>';
					$row_count++;
				}
			}
			$qan_table .= '</tbody></table>';	
		}
		elseif ( isset($_REQUEST['qid']) && isset($_REQUEST['unit_edit']) ) {
		
			
			$qan_id = $_REQUEST['qid'];
			
			// links to other units
			$unit_links = '<p>|&nbsp;<a href="do.php?_action=verify_logbook&amp;qid='.$qan_id.'">back to qual</a>';
			$unit_sql = 'select unit_id from qualification_specification where qan_id = "'.$qan_id.'" order by unit_id asc';
			$st = $link->query($unit_sql);
			if( $st ) {	
				while( $row = $st->fetch() ) {
					if ( $_REQUEST['unit_edit'] == $row['unit_id'] ) {
						$unit_links .= '&nbsp;|&nbsp;<strong>'.$row['unit_id'].'</strong>';
					}
					else {
						$unit_links .= '&nbsp;|&nbsp;<a href="do.php?_action=verify_logbook&amp;qid='.$qan_id.'&amp;unit_edit='.$row['unit_id'].'">'.$row['unit_id'].'</a>';
					}		
				}	
			}
			$unit_links .= '&nbsp;|</p>';
			
			$qan_sql = 'SELECT qualification_specification.* FROM qualification_specification ';
			$qan_sql .= 'where qan_id = "'.$qan_id.'" and unit_id="'.$_REQUEST['unit_edit'].'"';
			
			$st = $link->query($qan_sql);
			
			$qan_table = '<table class="resultset" >';
			$qan_table .= '<tbody>';
			
			$units = 0;
			
			if( $st ) {	
				// get rid of while loop - it only does it the once....
				while( $row = $st->fetch() ) {									
					$lo_element_short = preg_split('/(?<=[a-z\)0-9])(?=[A-Z])/x',$row['unit_content']);
					$qan_table .= '<div id="c_'.$row['unit_id'].'" class="unit_content" >';
					$qan_table .= '<form action="do.php"><input type="hidden" name="qid" value="'.$qan_id.'" /><input type="hidden" name="unit_edit" value="'.$row['unit_id'].'" />';
					$qan_table .= '<input type="hidden" name="_action" value="verify_logbook" />';
					
					$qan_table .= '<table class="resultset">';
					$qan_table .= '<thead></thead>';
					$qan_table .= '<tfoot></tfoot>';
					$qan_table .= '<tbody>';
					$qan_table .= '<tr style="background-color: #F9F9F9" class="shortrecord" ><td>Unit ID</td><td style="vertical-align: top;" >'.$row['unit_id'].'</td></tr>';
					$qan_table .= '<tr class="shortrecord" ><td>Unit Code</td><td style="vertical-align: top;" >'.$row['unit_code'].'</td></tr>';
					$qan_table .= '<tr style="background-color: #F9F9F9" class="shortrecord" ><td>Unit Title</td><td style="vertical-align: top;" ><input type="text" size="200" name="unit_title" value="'.$row['unit_title'].'"/></td></tr>';
					$qan_table .= '<tr class="shortrecord"><td>Unit Ref</td><td style="vertical-align: top;" >'.$row['unit_reference_number'].'</td></tr>';
					$qan_table .= '<tr style="background-color: #F9F9F9" class="shortrecord" ><td>Unit Aim</td><td style="vertical-align: top;" >';

					$unit_aim = preg_replace('/\|/', '<br/>', $row['unit_aim']);
					$unit_aim = preg_replace("/<br\/>/","\n",$unit_aim);
					$qan_table .= '<textarea rows="2" cols="200" name="unit_aim">'.$unit_aim.'</textarea></td></tr>';
					
					$qan_table .= '<tr class="shortrecord"><td>Unit Summary</td><td style="vertical-align: top;" >';
					
					$unit_summary = preg_replace('/\|/', '<br/>', $row['unit_summary']);
					$unit_summary = preg_replace("/<br\/>/","\n",$unit_summary);
					$qan_table .= '<textarea rows="2" cols="200" name="unit_summary">'.$unit_summary.'</textarea></td></tr>';
					
					$qan_table .= '<tr style="background-color: #F9F9F9" class="shortrecord" ><td>Assessment Criteria</td><td style="vertical-align: top;" >';
					
					$assessment_criteria = preg_replace('/\|/', '<br/>', $row['assessment_criteria']);
					$assessment_criteria = preg_replace("/<br\/>/","\n",$assessment_criteria);

					$qan_table .= '<textarea rows="2" cols="200" name="assessment_criteria">'.$assessment_criteria.'</textarea></td></tr>';

					$qan_table .= '<tr class="shortrecord"><td>Unit Content</td><td style="vertical-align: top;" ><textarea rows="2" cols="200" name="unit_content">';
					$display_cont = 0;
					foreach ($lo_element_short as $ucontent_desc ) {
						$ucontent_desc = preg_replace('/\|/', "<br/>", $ucontent_desc);
						$qan_table .= "\n\n".preg_replace('/<br\/>/', "\n", $ucontent_desc);
					}
					$qan_table .= '</textarea></td></tr>';		
			
					$qan_table .= '<tr style="background-color: #F9F9F9" class="shortrecord" ><td>Requirements</td><td style="vertical-align: top;" >';
					
					$unit_requirements = preg_replace('/\|/', '<br/>', $row['unit_requirements']);
					$unit_requirements = preg_replace("/<br\/>/","\n",$unit_requirements);
					$qan_table .= '<textarea rows="2" cols="200" name="unit_requirements">'.$unit_requirements.'</textarea></td></tr>';
					
					$qan_table .= '<tr class="shortrecord"><td>Obs Counts</td><td style="vertical-align: top;" >';

					$unit_observation = preg_replace('/\|/', '<br/>', $row['unit_observation']);
					$unit_observation = preg_replace("/<br\/>/","\n",$unit_observation);
					$qan_table .= '<textarea rows="2" cols="200" name="unit_observation">'.$unit_observation.'</textarea></td></tr>';
					
					$qan_table .= '<tr style="background-color: #F9F9F9" class="shortrecord" ><td>Evidence Counts</td><td style="vertical-align: top;" >';
					
					$unit_evidence = preg_replace('/\|/', '<br/>', $row['unit_evidence']);
					$unit_evidence = preg_replace("/<br\/>/","\n",$unit_evidence);
					$qan_table .= '<textarea rows="2" cols="200" name="unit_evidence">'.$unit_evidence.'</textarea></td></tr>';
			// ---
					$qan_table .= '<tr class="shortrecord"><td>Save?</td><td style="vertical-align: top;" >';
					$qan_table .= '<input type="submit" name="save_content" value="Save Content" class="button" /></td></tr>';
			// ---
					$qan_table .= '</tbody></table></form></div>';
				}
			}
		}
		
		$qan_title = $qan_id.' Specification Extraction';
		include_once('tpl_verify_logbook.php');
	}
}
?>

