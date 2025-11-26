<?php
class xml_export implements IAction
{
	public function execute(PDO $link)
	{

		$id = isset($_REQUEST['id'])?$_REQUEST['id']:'';
		$clients = isset($_REQUEST['clients'])?$_REQUEST['clients']:'';
        //#166 - headers already sent issue
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
 			$sql = "select * from qualifications where id = '$id' and clients = '$clients'";
        }
		else {
			$sql = "select * from qualifications where id = '$id' limit 0,1";
		}
		$st = $link->query($sql);
		if($st) {
			while( $row = $st->fetch() ) {
				
				
				// #193 {0000000282} - load up the qualification specification if present
				$qual_spec_details = QualSpecification::loadFromDatabase($link, $id);
				
				$module = 1;
				$units = 0;
				$elements = 0 ;
				$evidences = 0;
				$filename = $row['internaltitle'];
				// #166 - headers already sent issue
				// - ensure it only echos header information
				// - the once.
				if ( !isset($headers_sent) ) {
                	header('Content-Disposition: attachment; filename="'.$qan_code.' - '.$filename.'.xml"');
					echo "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\"?>\n";
					echo "<?xml-stylesheet type=\"text/xsl\"?>\n";
					echo "<!-- href=\"eLogBook.xsl\"? -->\n";
					echo "<logbook xmls=\"\">\n";
                    $headers_sent = 1;
                }

				echo "  <MainAttributes>\n";
				echo "    <Sector>".htmlspecialchars((string)$row['subarea'])."</Sector>\n";
				echo "    <QualFramework>".htmlspecialchars((string)$row['qualification_type'])."</QualFramework>\n";
				echo "    <MainHeader>".htmlspecialchars((string)$row['internaltitle'])."</MainHeader>\n";
				echo "    <SecondHeader />\n";
				echo "    <LogbookDate></LogbookDate>\n";
				echo "    <GenericFreeText/>\n";
				echo "  </MainAttributes>\n";
				echo "  <Qualification>\n";
				echo "    <Levels>\n";
				echo "      <Level>\n";
				echo "        <LevelName>Level ".htmlspecialchars((string)$row['level'])."</LevelName>\n";
				echo "        <Description>".htmlspecialchars((string)$row['internaltitle'])."</Description>\n";
				echo "        <QualificationsSize>\n";
				echo "          <QualificationSize>\n";
				echo "            <QualificationID></QualificationID>\n";
				echo "            <QualSizeName />\n";
				echo "            <QualificationTitle>".htmlspecialchars((string)$row['title'])."</QualificationTitle>\n";
				echo "            <QAN>".$row['id']."</QAN>\n";
				echo "            <RulesOfCombination>\n";
				echo "              <RulesOfCombDescription />\n";
				echo "              <QualificationCreditValue />\n";
				echo "              <MinimumCredit />\n";
				echo "              <MandatoryUnitCredit />\n";
				echo "              <OptionalUnitCredit />\n";
				echo "              <OthersUnitCredit />\n";
				echo "            </RulesOfCombination>\n";
				echo "            <QualStructure>\n";
				echo "              <QualStructureDescription>".htmlspecialchars((string)$row['description'])."</QualStructureDescription>\n";
				echo "              <QualUnits>\n";
				//$pageDom = new DomDocument();
				//$pageDom->loadXML(utf8_encode($row['evidences']));
				$pageDom = XML::loadXmlDom(mb_convert_encoding($row['evidences'],'UTF-8'));
				$e = $pageDom->getElementsByTagName('unit');
				$unit_counter = 1;
				foreach($e as $node) {
					
					$unit_id = $unit_counter;
					
					// #193 {0000000282} - present the unit details from specification
					// ensure a match on title when typos are different.
					// /[^a-zA-Z0-9 .,!?'\"\s+]/
					$qan_title = preg_replace("/[^a-zA-Z0-9!?\'\"]/", "", strtolower($node->getAttribute('title')));
					if ( preg_match("/Unit (\d+)$/", $node->getAttribute('reference'), $matches ) ) {
						$unit_id = $matches[1];
					}	
					elseif( isset($qual_spec_details->qual_specification[$qan_title]['unit_id']) ) {
						// do a check for duplicated up titles
						$duplicate_title = $qan_title.'_unit'.$node->getAttribute('reference');
						
						if ( isset($qual_spec_details->qual_specification[$duplicate_title]['unit_id']) ) {
							$qan_title = $duplicate_title;	
						}
						// RE - added in for Pearson odd specification
						// ---
						$unit_id = $qual_spec_details->qual_specification[$qan_title]['unit_id'];
						if ( is_numeric($unit_id) ) {
							$unit_id = sprintf('%02d', $unit_id);
						}
						// ---
					}
					else {
						$unit_id = sprintf('%02d', $unit_counter);
					}
					
					echo "                <QualUnit>\n";
					if ( preg_match('/mandatory/i', $node->parentNode->getAttribute('title')) ) {
		    			echo "                  <UnitStatusType>Mandatory</UnitStatusType>\n";	
		    		}
		    		else {
						echo "                  <UnitStatusType>Optional</UnitStatusType>\n";
		    		}
					echo "                  <UnitNumber>Unit ".$unit_id."</UnitNumber>\n";
					echo "                  <UnitTitle>".htmlspecialchars((string)$node->getAttribute('title'))."</UnitTitle>\n";
					echo "                  <UnitGroup />\n";
					echo "                  <UnitCode />\n";
					echo "                </QualUnit>\n";
					$unit_counter++;
				}
				echo "              </QualUnits>\n";
				echo "              <QualStrAdditionalInfo />\n";
				echo "            </QualStructure>\n";
				echo "            <LogbookInfo>\n";
				echo "              <text></text>\n";
				echo "              <hyperlink></hyperlink>\n";
				echo "            </LogbookInfo>\n";
				echo "          </QualificationSize>\n";
				echo "        </QualificationsSize>\n";
				echo "      </Level>\n";
				echo "    </Levels>\n";
				echo "    <GenericFreeText />\n";
				echo "  </Qualification>\n";
				echo "  <Units>\n";
				$unit_counter = 1;
				foreach( $e as $node ) { 
					echo "    <Unit>\n";
					$unit_id = $unit_counter;
					
					// #193 {0000000282} - present the unit details from specification
					// ensure a match on title when typos are different.
					// /[^a-zA-Z0-9 .,!?'\"\s+]/
					$qan_title = preg_replace("/[^a-zA-Z0-9!?\'\"]/", "", strtolower($node->getAttribute('title')));
					if ( preg_match("/Unit (\d+)$/", $node->getAttribute('reference'), $matches ) ) {
						$unit_id = $matches[1];
					}	
					elseif( isset($qual_spec_details->qual_specification[$qan_title]['unit_id']) ) {
						// do a check for duplicated up titles
						$duplicate_title = $qan_title.'_unit'.$node->getAttribute('reference');
						
						if ( isset($qual_spec_details->qual_specification[$duplicate_title]['unit_id']) ) {
							$qan_title = $duplicate_title;	
						}
						
						// RE - added in for Pearson odd specification
						// ---
						$unit_id = $qual_spec_details->qual_specification[$qan_title]['unit_id'];
						if ( is_numeric($unit_id) ) {
							$unit_id = sprintf('%02d', $unit_id);
						}
						// $unit_id = sprintf('%02d', $qual_spec_details->qual_specification[$qan_title]['unit_id']);
						// ---				
					}
					else {
						$unit_id = sprintf('%02d', $unit_counter);
					} 
					
					echo "      <UnitID>".$unit_id."</UnitID>\n";
					echo "      <KnowledgeAtUnitLevel></KnowledgeAtUnitLevel>\n";
					echo "      <RangeAtUnitLevel></RangeAtUnitLevel>\n";				
					echo "      <UnitNumber>Unit ".$unit_id."</UnitNumber>\n";			
					echo "      <UnitTitle>".htmlspecialchars((string)$node->getAttribute('title'))." (Credit Value ".sprintf('%02d', $node->getAttribute('credits')).")</UnitTitle>\n";;
					echo "      <UnitCode />\n";
					// #184 {0000000199} - level added
					echo "      <UnitLevel>Level ".htmlspecialchars((string)$row['level'])."</UnitLevel>\n";
					echo "      <CreditValue>".$node->getAttribute('credits')."</CreditValue>\n";
                	echo "      <GuidedLearningHours>".$node->getAttribute('glh')."</GuidedLearningHours>\n";
                	echo "      <UnitSummary>\n";
                	echo "        <Unit_Aim>\n";
                	echo "          <Title></Title>\n";
                	echo "          <Description></Description>\n";
                	echo "        </Unit_Aim>\n";
                	echo "        <Unit_Introduction>\n";
                	echo "          <Title></Title>\n";
                	echo "          <Description />\n";
                	echo "        </Unit_Introduction>\n";
                	echo "      </UnitSummary>\n";
                	echo "      <LearningOutcomes>\n";           
                	echo "        <Title></Title>\n";
                	echo "        <GenericDescription />\n";
                	$e2 = $node->getElementsByTagName('element');
					foreach( $e2 as $node2 ) {
                		echo "        <LearningOutcome>\n";
                		echo "          <Code></Code>\n";
                		echo "          <MainHeader>".htmlspecialchars((string)$node2->getAttribute('title'))."</MainHeader>\n";
                		// #184 {0000000199} - moved assessment requirements into learning outcomes
						$e3 = $node2->getElementsByTagName('evidence');
						$assessment_requirements = '';
						foreach($e3 as $node3) {
                			$assessment_requirements .= "        <AssessmentRequirement>\n";
                			$assessment_requirements .= "          <Description />\n";
                			$assessment_requirements .= "          <Ranges>\n";
                			$assessment_requirements .= "            <Title>".htmlspecialchars((string)$node3->getAttribute('title'))."</Title>\n";
                			$assessment_requirements .= "            <Range>\n";
                			$assessment_requirements .= "              <Description />\n";
                			$assessment_requirements .= "              <SubItems>\n";
                			$assessment_requirements .= "                <SubItem>\n";
                			$assessment_requirements .= "                  <Description />\n";
                			$assessment_requirements .= "                </SubItem>\n";
                			$assessment_requirements .= "              </SubItems>\n";
                			$assessment_requirements .= "            </Range>\n";
                			$assessment_requirements .= "          </Ranges>\n";
                			$assessment_requirements .= "        </AssessmentRequirement>\n";
						}
						echo "      <AssessmentRequirements>\n";
                		echo "        <Title>Assessment Requirements / Evidence Requirements</Title>\n";
                		if ( '' != $assessment_requirements ) { 
							echo $assessment_requirements; 
						}
                		echo "      </AssessmentRequirements>\n";                  		
                		echo "          <Ranges>\n";
                		echo "            <Title></Title>\n";
                		echo "            <Range>\n";
                		echo "              <Description></Description>\n";
                		echo "              <SubItems>\n";
                		echo "                <SubItem>\n";
                		echo "                  <Description />\n";
                		echo "                </SubItem>\n";
                		echo "              </SubItems>\n";  
                		echo "            </Range>\n";
                		echo "          </Ranges>\n";                		
                		echo "        </LearningOutcome>\n";
						$displayindex = 0;
					}	                
                	echo "      </LearningOutcomes>\n";            
                	echo "      <UnitEssentialGuidance />\n";
					echo "    </Unit>\n";
					$unit_counter++;
				}
				echo "  </Units>\n";
				echo "</logbook>\n";
			}
		}		
	}
}

?>
