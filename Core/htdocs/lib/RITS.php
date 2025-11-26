<?php
/**
 * Parser for OFQUAL's Register of Regulated Qualifications (RITS)
 * 
 * @author ianss
 */
class RITS
{
	const PORT = 80;
	const HOST = 'register-archive.ofqual.gov.uk';
	//const HOST = 'ritsuatest.amorgroup.com';
	const IMPORT_QUAL = 1;
	const IMPORT_QUAL_AND_UNITS = 2;
	const XML_DATE = 'Y-m-d';
	const XML_TIMESTAMP = 'Y-m-d\TH:i:sP';
	
	
	public function __construct($proxy_addr = null, $proxy_port = null)
	{
		if($proxy_addr != '' && $proxy_port == '')
		{
			throw new Exception("If a proxy address is specified then a proxy port must be specified too");
		}
		
		$this->proxy_addr = $proxy_addr;
		$this->proxy_port = $proxy_port;
	}
	
	public function __destruct()
	{
		if($this->curl)
		{
			curl_close($this->curl);
			$this->curl = null;
		}
	}
	
	/**
	 * @param string $ref QCA reference
	 * @param boolean $unitDescriptions
	 * @param boolean $flushToBrowser
	 * @return string
	 */
	public function getQualification($ref, $downloadFullUnitDescriptions = false)
	{
		// Clear cURL in between qualification requests (there's a reported memory leak)
		if($this->curl){
			curl_close($this->curl);
			$this->curl = null;
		}
		
		$ref = trim($ref);
		if($ref == ""){
			return null;
		}
		
		// RITS has a more inflexible parser of QAN codes than NDAQ
		// If a QAN code does not contain '/' then we'll need to add them
		if(preg_match('/^([A-Za-z0-9]{3,3})([A-Za-z0-9]{4,4})([A-Za-z0-9])$/', $ref, $matches)){
			$ref = $matches[1].'/'.$matches[2].'/'.$matches[3];
		}
		
		$this->retrieval_time = $this->processing_time = 0;
		
		$start_time = microtime(TRUE);
		$url = 'http://'.RITS::HOST.'/Qualification/Details/' . preg_replace('/[^a-zA-Z0-9]/', '_', $ref);
		$html = $this->getPage($url);
		if(is_null($html)){
			return null;
		}
		
		$finish_time = microtime(true);
		$this->retrieval_time += round($finish_time - $start_time, 4);
		$start_time = $finish_time; // reset
		
		// Strip and scrub
		$html = $this->cleanHTML($html);
		
		// Load DOM and XPATH parsers
		//$doc = new DOMDocument();
		//@$doc->loadXML($html);
		$doc = XML::loadXmlDom($html);
		$xpath = new DOMXPath($doc);
		
		// Parse fields from page
		$fields = $this->getQualificationFields($doc, $xpath);

		$finish_time = microtime(true);
		$this->processing_time += round($finish_time - $start_time, 4);
		$start_time = $finish_time;		
		

		$xml = "";
		ob_start();
		try
		{
			echo '<qualification ';
			$this->formatAttribute('title', $fields, 'title');
			$this->formatAttribute('reference', $fields, 'qualification number');
			$this->formatAttribute('type', $fields, 'qualification type');
			$this->formatAttribute('subtype', $fields, 'qualification sub type');
			$this->formatAttribute('level', $fields, 'qualification level');
			$this->formatAttribute('sublevel', $fields, 'qualification sub level');
			//$this->formatAttribute('total_credits', $fields, 'total credits');
			//$this->formatAttribute('min_credits', $fields, 'min credits at/above level');
			$this->formatAttribute('guided_learning_hours', $fields, 'guided learning hours');
			$this->formatAttribute('guided_learning_hours_min', $fields, 'minimum guided learning hours');
			$this->formatAttribute('guided_learning_hours_max', $fields, 'maximum guided learning hours');
			$this->formatAttribute('grading', $fields, 'overall grading type');
			$this->formatAttribute('awarding_body', $fields, 'awarding organisation');
			$this->formatAttribute('total_credits', $fields, 'total credits');
			$this->formatAttribute('min_credits_at_above_level', $fields, 'min credits at/above level');
			$this->formatAttribute('ssa', $fields, 'ssa');
			$this->formatAttribute('regulation_start_date', $fields, 'regulation start date');
			$this->formatAttribute('operational_start_date', $fields, 'operational start date');
			$this->formatAttribute('operational_end_date', $fields, 'operational end date');
			$this->formatAttribute('certification_end_date', $fields, 'certification end date');
			$this->formatAttribute('offered_in_wales', $fields, 'offered in wales');
			$this->formatAttribute('offered_in_england', $fields, 'offered in england');
			echo ">\n";
	
			$this->formatElement('structure_requirements', $fields, 'structure requirements');
			$this->formatElement('assessment_method', $fields, 'assessment methods');
	
			// Include units
			$this->renderQualificationStructure($xpath, $downloadFullUnitDescriptions);

			// Footer
			echo '<url>';
			echo htmlspecialchars((string)$url);
			echo "</url>\n";
			echo '<timestamp>';
			echo htmlspecialchars(date(RITS::XML_TIMESTAMP));
			echo "</timestamp>\n";
			echo '<time_to_retrieve>';
			echo sprintf('%.3f',$this->retrieval_time);
			echo "</time_to_retrieve>\n";
			echo '<time_to_process>';
			echo sprintf('%.3f',$this->processing_time);
			echo "</time_to_process>\n";
			//echo '<raw_fields>' . htmlspecialchars(print_r($fields,true)) . "</raw_fields>";
			//echo "<html>".htmlspecialchars((string)$html)."</html>";
			echo "</qualification>";
			
			// Close buffer
			$xml = ob_get_clean();
		}
		catch(Exception $e)
		{
			ob_end_clean();
			throw new WrappedException($e);
		}
		
		$xml = str_replace("unit_group","units", $xml);
		$xml = str_replace("compound_group","units", $xml);
		$xml = str_replace("hybrid_group","units", $xml);
		$xml = str_replace("<structure>","<root>", $xml);
		$xml = str_replace("</structure>","</root>", $xml);
		
		return $xml;
	}
	
	public function getUnit($id)
	{
		$id = trim($id);
		if($id == ""){
			return null;
		}
		
		$xml = "";
		ob_start();
		try
		{
			$url = 'http://'.RITS::HOST.'/Unit/Details/' . preg_replace('/[^a-zA-Z0-9]/', '_', $id);
			$unit = $this->getLinkedUnit($url);
			if(!$unit){
				return null;
			}
			
			$unit->render();
			$xml = ob_get_clean();
		}
		catch(Exception $e)
		{
			ob_end_clean();
			throw new WrappedException($e);
		}
		
		return $xml;
	}


	private function getLinkedUnit($url)
	{
		$start_time = microtime(TRUE);
		if(array_key_exists($url, $this->unit_cache))
		{
			$fields = $this->unit_cache[$url];
			$retrieval_time = 0;
			$processing_time = 0;
		}
		else
		{
			$html = $this->getPage($url);
			if(is_null($html)){
				return null;
			}
			
			$retrieval_time = round(microtime(TRUE) - $start_time, 4);
			$this->retrieval_time += $retrieval_time;
			
			$start_time = microtime(TRUE);					
			$fields = $this->getUnitFields($html);
			$processing_time = round(microtime(TRUE) - $start_time, 4);
			$this->processing_time += $processing_time;
			
			$this->unit_cache[$url] = $fields;
		}
		
		$unit = new LinkedUnit($fields);
		
		return $unit;
	}
	
	
	private function formatAttribute($attribute_name, array $array, $key)
	{
		if(isset($array[$key]))
		{
			if(Date::isDate($array[$key]))
			{
				echo ' ';
				echo $attribute_name;
				echo '="';
				echo htmlspecialchars(Date::toMySQL($array[$key]));
				echo '" ';
			}
			else
			{
				echo ' ';
				echo $attribute_name;
				echo '="';
				echo htmlspecialchars((string)$array[$key]);
				echo '" ';
			}
		}
		else
		{
			echo ' ';
			echo $attribute_name;
			echo '="" ';
		}
	}
	
	
	private function formatElement($element_name, array $array, $key)
	{
		echo '<';
		echo str_replace(' ', '_', $element_name);
		echo '>';
		
		if(isset($array[$key]))
		{
			if(Date::isDate($array[$key]))
			{
				echo htmlspecialchars(Date::toMySQL($array[$key]));
			}
			else
			{
				echo htmlspecialchars((string)$array[$key]);
			}
		}

		echo '</';
		echo str_replace(' ', '_', $element_name);
		echo '>';
	}
	
	
	private function getQualificationFields(DOMDocument $doc, DOMXPath $xpath)
	{
		$qualRoot = $xpath->query('//div[@id="pageBody"]/div[1]/fieldset[legend/text() = "Qualification" or legend/text() = "Diploma"]');
		$labels = $xpath->query('div[contains(@class,"formDisplayLabelElement")]', $qualRoot->item(0));
		$fields = array();
		
		// Read fields and values
		foreach($labels as $label){
			$fields[strtolower($label->nodeValue)] = RITS::cleanFieldValue($label->nextSibling->nodeValue);
		}
		
		// Clean fields
		if(isset($fields['minimum guided learning hours']) && $fields['minimum guided learning hours'] == "0"){
			$fields['minimum guided learning hours'] = "";
		}
		if(isset($fields['maximum guided learning hours']) && $fields['maximum guided learning hours'] == "0"){
			$fields['maximum guided learning hours'] = "";
		}		
		if(isset($fields['qualification level'])){
			$fields['qualification level'] = str_ireplace('entry', '0', $fields['qualification level']);
			if(preg_match_all('/\d+\b/', $fields['qualification level'], $matches) > 0){
				$fields['qualification level'] = implode(',', $matches[0]);
			}
		}
		if(isset($fields['qualification sub level'])){
			if($fields['qualification sub level'] == "None"){
				$fields['qualification sub level'] = null;
			}
			if(preg_match_all('/\d+\b/', $fields['qualification sub level'], $matches) > 0){
				$fields['qualification sub level'] = implode(',', $matches[0]);
			}
		}
		if(isset($fields['qualification type']) && array_key_exists(strtolower($fields['qualification type']), $this->mapQualType)){
			$fields['qualification type'] = $this->mapQualType[strtolower($fields['qualification type'])];
		}
		if(isset($fields['qualification sub type']) && $fields['qualification sub type'] == "None"){
			$fields['qualification sub type'] = "";
		}
		if(isset($fields['regulation start date']) && $fields['regulation start date'] != ""){
			$d = new DateTime($fields['regulation start date']);
			$fields['regulation start date'] = $d->format(RITS::XML_DATE);
		}
		if(isset($fields['operational start date']) && $fields['operational start date'] != ""){
			$d = new DateTime($fields['operational start date']);
			$fields['operational start date'] = $d->format(RITS::XML_DATE);
		}
		if(isset($fields['operational end date']) && $fields['operational end date'] != ""){
			$d = new DateTime($fields['operational end date']);
			$fields['operational end date'] = $d->format(RITS::XML_DATE);
		}
		if(isset($fields['certification end date']) && $fields['certification end date'] != ""){
			$d = new DateTime($fields['certification end date']);
			$fields['certification end date'] = $d->format(RITS::XML_DATE);
		}
		
		// Add calculated field "guided learning hours" for backwards compatibility with old NDAQ XML
		$glh_min = isset($fields['minimum guided learning hours']) ? $fields['minimum guided learning hours'] : "";
		$glh_max = isset($fields['maximum guided learning hours']) ? $fields['maximum guided learning hours'] : "";
		$glh_min = $glh_min == "0" ? "":$glh_min;
		$glh_max = $glh_max == "0" ? "":$glh_max;
		if($glh_min && $glh_max && ($glh_min != $glh_max))
		{
			$fields['guided learning hours'] = $glh_min.'-'.$glh_max;
		}
		elseif($glh_min || $glh_max)
		{
			$fields['guided learning hours'] = ($glh_min ? $glh_min : $glh_max);
		}
		
		return $fields;
	}
	

	
	
	private function renderQualificationStructure(DOMXPath $xpath, $downloadFullUnitDescriptions = false)
	{
		$rootNode = $this->buildStructuralNodes($xpath, $downloadFullUnitDescriptions);
		
		echo "<structure>\n";
		if(!is_null($rootNode))
		{
			$rootNode->render();
		}
		echo "</structure>\n";
	}
	
	private function buildStructuralNodes(DOMXPath $xpath, $downloadFullUnitDescriptions = false)
	{
		$rootFieldset = $xpath->query('//div[@id="pageBody"]/div[1]/fieldset[legend/text() = "Unit Group" or legend/text() = "Compound Group" or legend/text() = "Hybrid Group"]');
		
		// There *should* only be one overarching group
		if($rootFieldset->length > 0)
		{
			return $this->buildStructuralNode($rootFieldset->item(0), $xpath, $downloadFullUnitDescriptions);
		}
		else
		{
			return null;
		}
	}
	
	private function buildStructuralNode(DOMElement $fieldset, DOMXPath $xpath, $downloadFullUnitDescriptions = false)
	{
		$node = null;
			
		$legend = $fieldset->firstChild->nodeValue;
		switch($legend)
		{
			case "Compound Group":
				$fields = array();
				$labels = $xpath->query('div[2]/div[contains(@class,"formDisplayLabelElement")]', $fieldset);
				foreach($labels as $label){
					$fields[strtolower($label->nodeValue)] = RITS::cleanFieldValue($label->nextSibling->nodeValue);
				}
				$node = new CompoundGroup($fields);
				break;
				
			case "Hybrid Group":
				$fields = array();
				$labels = $xpath->query('div[2]/div[contains(@class,"formDisplayLabelElement")]', $fieldset);
				foreach($labels as $label){
					$fields[strtolower($label->nodeValue)] = RITS::cleanFieldValue($label->nextSibling->nodeValue);
				}
				$node = new HybridGroup($fields);
				break;
				
			case "Other Credit Group":
				$fields = array();
				$labels = $xpath->query('div[2]/div[contains(@class,"formDisplayLabelElement")]', $fieldset);
				foreach($labels as $label){
					$fields[strtolower($label->nodeValue)] = RITS::cleanFieldValue($label->nextSibling->nodeValue);
				}
				$node = new OtherCreditGroup($fields);
				break;	
				
			case "Unit Group":
				$fields = array();
				$labels = $xpath->query('div[2]/div[contains(@class,"formDisplayLabelElement")]', $fieldset);
				foreach($labels as $label){
					$fields[strtolower($label->nodeValue)] = RITS::cleanFieldValue($label->nextSibling->nodeValue);
				}
				$node = new UnitGroup($fields);
				break;
				
			case "Linked Unit":
			case "Legacy Linked Unit":
				$fields = array();
				$labels = $xpath->query('div[2]/fieldset[legend/text() = "Unit"]/div[contains(@class,"formDisplayLabelElement")]', $fieldset);
				foreach($labels as $label){
					$fields[strtolower($label->nodeValue)] = RITS::cleanFieldValue($label->nextSibling->nodeValue);
				}
				$node = new LinkedUnit($fields, $downloadFullUnitDescriptions);
				
				if($downloadFullUnitDescriptions)
				{
					$url = 'http://'.RITS::HOST.'/Unit/Details/' . preg_replace('/[^a-zA-Z0-9]/', '_', $node->referenceNumber);
					$unit = $this->getLinkedUnit($url);
					if(!is_null($unit))
					{
						// Append extra fields from the unit record
						$node->outcomes = $unit->outcomes;
						$node->ssa = $unit->ssa;
						$node->dateOfWithdrawal = $unit->dateOfWithdrawal;
						$node->grading = $unit->grading;
						$node->qualificationFramework = $unit->qualificationFramework;
						$node->assessmentGuidance = $unit->assessmentGuidance;
					}
					else
					{
						//throw new Exception("Could not download unit ".$node->referenceNumber." from The Register of Regulated Qualifications.");
					}
				}
				
				break;
				
			default:
				throw new Exception("Unexpected fieldset legend: ".$legend);
				break;
		}
		
		// Build children
		$nextNode = $fieldset->nextSibling;
		if($nextNode && $nextNode->nodeType == XML_ELEMENT_NODE
				&& $nextNode->nodeName == "div"
				&& strpos($nextNode->getAttribute("class"), "indent") !== FALSE)
		{
			/* @var $nextNode DOMElement */
			$fieldsets = $xpath->query('fieldset', $nextNode);
			foreach($fieldsets as $fs)
			{
				$node->children[] = $this->buildStructuralNode($fs, $xpath, $downloadFullUnitDescriptions);
			}
		}
		
		return $node;
	}
	

	
	private function getUnitFields($html)
	{
		// Strip and scrub
		$html = $this->cleanHTML($html);
		
		// Load DOM and XPATH parsers
		//$doc = new DOMDocument();
		//@$doc->loadXML($html);
		$doc = XML::loadXmlDom($html);
		$xpath = new DOMXPath($doc);
		
		$qualRoot = $xpath->query('//div[@id="pageBody"]/div[1]/fieldset[legend/text() = "Unit"]');
		$labels = $xpath->query('div[contains(@class,"formDisplayLabelElement")]', $qualRoot->item(0));
		
		// Read fields and values
		foreach($labels as $label){
			$fields[strtolower($label->nodeValue)] = RITS::cleanFieldValue($label->nextSibling->nodeValue);
		}
		
		// Clean dates
		if(isset($fields['date of withdrawal']) && $fields['date of withdrawal'] != ""){
			$d = new DateTime($fields['date of withdrawal']);
			$fields['date of withdrawal'] = $d->format(RITS::XML_DATE);
		}
		
		// Get learner outcomes
		$rows = $xpath->query('//fieldset[legend/text()="Learning Outcomes and Assessment Criteria"]/table[@id="LearningOutcomeList"]/tbody/tr');
		$outcomes = array();
		$outcome = null;
		foreach($rows as $tr)
		{
			$cells = $xpath->query("td", $tr);
			$td1 = $cells->item(0);
			$td2 = $cells->item(1);
			$rowspan = $td1->getAttribute("rowspan") !== "" ? $td1->getAttribute("rowspan") : null;
			if($rowspan === "0")
			{
				// New outcome (no criteria)
				$index = $td1->nodeValue;
				$description = Text::utf8_to_latin1(RITS::extractText($td2));
				$outcome = $outcomes[] = new Outcome($index, $description);					
			}
			elseif(is_null($outcome) || $rowspan)
			{
				// New outcome (rowspan="1" or higher)
				$index = $td1->nodeValue;
				$description = Text::utf8_to_latin1(RITS::extractText($td2));
				$outcome = $outcomes[] = new Outcome($index, $description);

				// Add first criterion (if present)
				if($cells->length == 4)
				{
					$td3 = $cells->item(2);
					$td4 = $cells->item(3);
					$index = $td3->nodeValue;
					$description = Text::utf8_to_latin1(RITS::extractText($td4));
					$outcome->children[] = new Criterion($index, $description);
				}
			}
			else
			{
				// Existing outcome (no rowspan attribute)
				
				// Add next criterion
				$index = $td1->nodeValue;
				$description = Text::utf8_to_latin1(RITS::extractText($td2));
				$outcome->children[] = new Criterion($index, $description);
			}
		}
		$fields['outcomes'] = $outcomes;

		
		
		
		return $fields;			
	}
		
	/**
	 * Takes a RITS URI as an argument and returns the RITS page
	 * @param string $uri
	 */
	private function getPage($url)
	{
		if(!$this->curl)
		{
			$this->curl = curl_init();
			curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($this->curl, CURLOPT_CONNECTTIMEOUT, 48); // seconds
			curl_setopt($this->curl, CURLOPT_TIMEOUT, 1600); // seconds
			curl_setopt($this->curl, CURLOPT_FOLLOWLOCATION, true);
			//curl_setopt($this->curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 6.1; en-GB; rv:1.9.2) Gecko/20100115 Firefox/3.6");
			curl_setopt($this->curl, CURLOPT_USERAGENT, "Perspective/Sunesis");
			if($this->proxy_addr && $this->proxy_port)
			{
				curl_setopt($this->curl, CURLOPT_PROXY, $this->proxy_addr.':'.$this->proxy_port);
			}
		}

		curl_setopt($this->curl, CURLOPT_URL, $url);
		$result = curl_exec ($this->curl);
		if(curl_error($this->curl))
		{
			throw new Exception("Error downloading ". $url . ". " .curl_error($this->curl));
		}
		
		$effective_url = curl_getinfo($this->curl, CURLINFO_EFFECTIVE_URL);
		if(stripos($effective_url, "error.aspx") !== FALSE)
		{
			return null;
		}
		else
		{
			return $result;
		}
	}
	
	/**
	 * Experimental method that takes an array of URLs
	 * and downloads them in parallel
	 * @param array $urls
	 * @return array
	 */
	private function getPages(array $urls)
	{
		$multi = curl_multi_init();
		
		$curl = array();
		foreach($urls as $url)
		{
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3); // seconds
			curl_setopt($ch, CURLOPT_TIMEOUT, 5); // seconds
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
			curl_setopt($ch, CURLOPT_USERAGENT, "Perspective/CLM");
			curl_setopt($ch, CURLOPT_URL, $url);
			$curl[] = $ch;
			
			curl_multi_add_handle($multi, $ch);
		}
		
		$active = null;
		$mrc = curl_multi_exec($multi, $active);
		while($active && $mrc == CURLM_CALL_MULTI_PERFORM){
			usleep(100000);
			$mrc = curl_multi_exec($multi, $active);
		}
		
		while ($active && $mrc == CURLM_OK)
		{
			if(curl_multi_select($multi) != -1)
			{
				do{
					usleep(100000);
					$mrc = curl_multi_exec($multi, $active);
				} while ($mrc == CURLM_CALL_MULTI_PERFORM);
			}
		}
		
		// Retrieve content
		$pages = array();
		foreach($curl as $ch)
		{
			if(curl_error($curl) != CURLE_OK){
				$pages[] = null;
				continue;
			}
			
			$effective_url = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
			if(stripos($effective_url, "error.aspx") !== FALSE){
				$pages[] = null;
				continue;
			}

			$pages[] = curl_multi_getcontent($ch);
		}
		
		// Clean up
		foreach($curl as $ch)
		{
			curl_multi_remove_handle($multi, $ch);
			curl_close($ch);
		}
		curl_multi_close($multi);
		
		return $pages;
	}
	

	
	public static function cleanFieldValue($fieldValue, $isUTF8 = true)
	{
		$fieldValue = Text::utf8_to_latin1($fieldValue);
		$fieldValue = Text::remove_html_markup($fieldValue);
		$fieldValue = trim($fieldValue);
		return $fieldValue;
	}
	
	public static function extractText(DOMElement $node)
	{
		$text = '';
		
		/* @var $child DOMElement */
		foreach($node->childNodes as $child)
		{
			if($child->nodeType == XML_ELEMENT_NODE)
			{
				// RE - Add an asterisks character for list elements
				// -------------------------------------------------
				//    - !!IMPORTANT!! This is required for the  
				//    - Qualification Manager outputs
				// ------------------------------------------------- 
				switch($child->tagName)
				{
					case 'li':
						$text .= "* \n";
						break;
					default:
				}
				
				if($child->hasChildNodes())
				{
					$text .= RITS::extractText($child);
				}
				
				// Add a newline character for block-level elements and table rows
				switch($child->tagName)
				{
					case 'br':
					case 'div':
					case 'p':
					case 'address':
					case 'blockquote':
					case 'center':
					case 'dir':
					case 'dl':
					case 'h1':
					case 'h2':
					case 'h3':
					case 'h4':
					case 'h5':
					case 'h6':
					case 'h7':
					case 'hr':
					case 'pre':
					case 'tr':
						$text .= "\n";
						break;
					
					default:
				}
			}
			elseif($child->nodeType == XML_TEXT_NODE)
			{
				$text .= $child->nodeValue;
			}
		}
		
		return trim($text);
	}
	
	/**
	 * Strips and scrubs RITS returned HTML so that it is suitable for
	 * use with DOMDocument and DOMXPath
	 * @param string $html
	 */
	private function cleanHTML($html)
	{
		$html = str_replace('<br>', '', $html);

		// RITS has illegal characters in attribute values - 27th May 2011
		$html = preg_replace('#(="[^"]+)<#', "$1&lt;", $html);
		$html = preg_replace('#(="[^"]+)>#', "$1&gt;", $html);
		
		// Silently remove all control characters except for CR, LF and TAB
		$html = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F]/', '', $html);
		
		// Remove JavaScript (which may contain HTML tag-like constructs)
		$re = '#<script[^>]*>.*</script>#msU'; 
		$html = preg_replace($re, '', $html);
		
		 // Remove HTML header
		$re = '#<head[^>]*>.*</head>#msU';
		$html = preg_replace($re, '', $html);
		
		// Replace free ampersands with proper entities
		$html = str_replace("& ", "&amp; ", $html);
		
		// Replace unescaped reserved syntax
		$patterns = array('/&(?![a-zA-Z0-9]+;)/', '#<(?![a-zA-Z\!/])#');
		$replacements = array('&amp;', '&lt;');
		$html = preg_replace($patterns, $replacements, $html);
		
		// Remove all namespace declarations (XPath does not support default namespaces)
		$html = preg_replace('/xmlns=".*?"/', '', $html);
		
		// Remove all Microsoft Rich Text Control formatting (it's not perfect....) 
 		$patterns = array("#<span[^>]*>#", "#<p[^>]*>#", "#</span>#", "#</p>#"); 
 		$html = preg_replace($patterns, "", $html); 		
		
		return $html;
	}
	
	
	/**
	 * Cut & pasted from the PHP.net site
	 *
	 * @param string $str
	 */
	private function utf2html($str)
	{
	   $ret = "";
	   $max = strlen($str);
	   $last = 0;  // keeps the index of the last regular character
	   for ($i=0; $i<$max; $i++)
	   {
	       $c = $str{$i};
	       $c1 = ord($c);
	       if ($c1>>5 == 6)
	       {  // 110x xxxx, 110 prefix for 2 bytes unicode
	           $ret .= substr($str, $last, $i-$last); // append all the regular characters we've passed
	           $c1 &= 31; // remove the 3 bit two bytes prefix
	           $c2 = ord($str{++$i}); // the next byte
	           $c2 &= 63;  // remove the 2 bit trailing byte prefix
	           $c2 |= (($c1 & 3) << 6); // last 2 bits of c1 become first 2 of c2
	           $c1 >>= 2; // c1 shifts 2 to the right
	           $ret .= "&#" . ($c1 * 0x100 + $c2) . ";"; // this is the fastest string concatenation
	           $last = $i+1;     
	       }
	       elseif ($c1>>4 == 14) 
	       {  // 1110 xxxx, 110 prefix for 3 bytes unicode
	           $ret .= substr($str, $last, $i-$last); // append all the regular characters we've passed
	           $c2 = ord($str{++$i}); // the next byte
	           $c3 = ord($str{++$i}); // the third byte
	           $c1 &= 15; // remove the 4 bit three bytes prefix
	           $c2 &= 63;  // remove the 2 bit trailing byte prefix
	           $c3 &= 63;  // remove the 2 bit trailing byte prefix
	           $c3 |= (($c2 & 3) << 6); // last 2 bits of c2 become first 2 of c3
	           $c2 >>=2; //c2 shifts 2 to the right
	           $c2 |= (($c1 & 15) << 4); // last 4 bits of c1 become first 4 of c2
	           $c1 >>= 4; // c1 shifts 4 to the right
	           $ret .= '&#' . (($c1 * 0x10000) + ($c2 * 0x100) + $c3) . ';'; // this is the fastest string concatenation
	           $last = $i+1;     
	       }
	   }
	   $ret .= substr($str, $last, $i); // append the last batch of regular characters
	      
	   return $ret;
	}
	
	
	private $HTML_NEW_LINES = array('<br>', '<br/>', '<br />', '<BR>', '<BR/>', '<BR />', '</p>', '</P>');
	
	private $retrieval_time = 0;
	private $processing_time = 0;
	
	private $unit_cache = array();
	
	private $proxy_addr = null;
	private $proxy_port = null;
	
	private $curl = null;
	
	private $mapQualType = array(
		"advanced extension award"=>"AEA",
		"basic skills"=>"BS",
		"diploma"=>"DIP",
		"entry level"=>"EL",
		"english for speakers of other languages"=>"ESOL",
		"functional skills"=>"FS",
		"functional skills (qcf)"=>"FS QCF",
		"free standing mathematics qualification"=>"FSMQ",
		"gce a level"=>"GCE",
		"gce as level"=>"GCE AS",
		"general certificate of secondary education"=>"GCSE",
		"general national vocational qualification"=>"GNVQ",
		"higher level"=>"HL",
		"key skills"=>"KS",
		"national vocational qualification"=>"NVQ",
		"other general qualification"=>"OG",
		"occupational qualification"=>"OQ",
		"principal learning"=>"PL",
		"project"=>"PROJ",
		"qcf"=>"QCF",
		"vocational certificate of education"=>"VCE",
		"vce advanced subsidiary level"=>"VCE AS",
		"vocationally-related qualification"=>"VRQ"
	);
}



class CompoundGroup
{
	public function __construct(array $fields)
	{
		$this->label = $this->getFieldValue('label', $fields);
		$this->name = $this->getFieldValue('name', $fields);
		$this->description = $this->getFieldValue('description', $fields);
		$this->minCreditValue = $this->getFieldValue('minimum credit value', $fields);
		$this->maxCreditValue = $this->getFieldValue('maximum credit value', $fields);
		$this->minSubComponents = $this->getFieldValue('minimum subcomponents', $fields);
		$this->maxSubComponents = $this->getFieldValue('maximum subcomponents', $fields);
		$this->mandatoryWithinGroup = $this->getFieldValue('mandatory within group', $fields);
		$this->pathwayReference = $this->getFieldValue('pathway reference', $fields);
		
		$this->mandatoryWithinGroup = strtolower($this->mandatoryWithinGroup) == "yes";
	}
	
	protected function getFieldValue($fieldName, array $fields)
	{
		return array_key_exists($fieldName, $fields) ? $fields[$fieldName] : null;
	}
	
	public function render()
	{
		echo "<compound_group ";
		echo 'label="';
		echo htmlspecialchars((string)$this->label);
		echo '" name="';
		echo htmlspecialchars((string)$this->name);

		// Khushnood Starts
		echo '" title="';
		echo htmlspecialchars((string)$this->label) . ' - ' . htmlspecialchars((string)$this->name);
		// Khushnood end

		echo '" mandatory="';
		echo $this->mandatoryWithinGroup ? 'yes':'no';
		echo '" min_credit_value="';
		echo htmlspecialchars((string)$this->minCreditValue);
		echo '" max_credit_value="';
		echo htmlspecialchars((string)$this->maxCreditValue);
		echo '" min_components="';
		echo htmlspecialchars((string)$this->minSubComponents);
		echo '" max_components="';
		echo htmlspecialchars((string)$this->maxSubComponents);
		echo '" pathway_reference="';
		echo htmlspecialchars((string)$this->pathwayReference);
		echo '">';
		
		echo "<description>";
		echo htmlspecialchars((string)$this->description);
		echo "</description>\n";
		
		foreach($this->children as $child)
		{
			$child->render();
		}
		
		echo '</compound_group>';
	}
	
	public $label;
	public $name;
	public $description;
	public $minCreditValue;
	public $maxCreditValue;
	public $minSubComponents;
	public $maxSubComponents;
	public $mandatoryWithinGroup;
	public $pathwayReference;
	
	public $children = array();
}

class HybridGroup extends CompoundGroup
{
	public function __construct(array $fields)
	{
		parent::__construct($fields);
	}
	
	public function render()
	{
		echo "<hybrid_group ";
		echo 'label="';
		echo htmlspecialchars((string)$this->label);
		echo '" name="';
		echo htmlspecialchars((string)$this->name);
		
		echo '" title="';
		echo htmlspecialchars((string)$this->label) . ' ' . htmlspecialchars((string)$this->name);
		
		echo '" mandatory="';
		echo $this->mandatoryWithinGroup ? 'yes':'no';
		echo '" min_credit_value="';
		echo htmlspecialchars((string)$this->minCreditValue);
		echo '" max_credit_value="';
		echo htmlspecialchars((string)$this->maxCreditValue);
		echo '" min_components="';
		echo htmlspecialchars((string)$this->minSubComponents);
		echo '" max_components="';
		echo htmlspecialchars((string)$this->maxSubComponents);
		echo '">';
		
		echo "<description>";
		echo htmlspecialchars((string)$this->description);
		echo "</description>\n";
		
		foreach($this->children as $child)
		{
			$child->render();
		}
		
		echo '</hybrid_group>';
	}
}


class OtherCreditGroup extends CompoundGroup
{
	public function __construct(array $fields)
	{
		parent::__construct($fields);
		$this->qualificationLevel = $this->getFieldValue("qualification level", $fields);
		$this->qualificationSublevel = $this->getFieldValue("qualification sub level", $fields);
		$this->ssa = $this->getFieldValue("ssas", $fields);
	}
	
	public function render()
	{
		echo "<other_credit_group ";
		echo 'label="';
		echo htmlspecialchars((string)$this->label);
		echo '" name="';
		
		echo '" title="';
		echo htmlspecialchars((string)$this->label) . ' ' . htmlspecialchars((string)$this->name);		
		
		echo htmlspecialchars((string)$this->name);
		echo '" mandatory="';
		echo $this->mandatoryWithinGroup ? 'yes':'no';
		echo '" min_credit_value="';
		echo htmlspecialchars((string)$this->minCreditValue);
		echo '" max_credit_value="';
		echo htmlspecialchars((string)$this->maxCreditValue);
		echo '" qualification_level="';
		echo htmlspecialchars((string)$this->qualificationLevel);
		echo '" qualification_sublevel="';
		echo htmlspecialchars((string)$this->qualificationSublevel);
		echo '" ssa="';
		echo htmlspecialchars((string)$this->ssa);
		echo '">';
		
		echo "<description>";
		echo htmlspecialchars((string)$this->description);
		echo "</description>\n";
		
		foreach($this->children as $child)
		{
			$child->render();
		}
		
		echo '</other_credit_group>';
	}
	
	private $qualificationLevel;
	private $qualificationSublevel;
	private $ssa;
}



class UnitGroup
{
	public function __construct(array $fields)
	{		
		$this->label = $this->getFieldValue('label', $fields);
		$this->name = $this->getFieldValue('name', $fields);
		$this->description = $this->getFieldValue('description', $fields);
		$this->minCreditValue = $this->getFieldValue('minimum credit value', $fields);
		$this->maxCreditValue = $this->getFieldValue('maximum credit value', $fields);
		$this->minSubComponents = $this->getFieldValue('minimum subcomponents', $fields);
		$this->maxSubComponents = $this->getFieldValue('maximum subcomponents', $fields);
		$this->mandatoryWithinGroup = $this->getFieldValue('mandatory within group', $fields);
		
		$this->mandatoryWithinGroup = strtolower($this->mandatoryWithinGroup) == "yes";

	}
	
	private function getFieldValue($fieldName, array $fields)
	{
		return array_key_exists($fieldName, $fields) ? $fields[$fieldName] : null;
	}
	
	public function render()
	{
		echo "<unit_group ";
		echo 'label="';
		echo htmlspecialchars((string)$this->label);
		echo '" name="';
		echo htmlspecialchars((string)$this->name);

		// Khushnood Start
		echo '" title="';
		echo htmlspecialchars((string)$this->label) . ' - ' . htmlspecialchars((string)$this->name);
		// Khushnood End
		
		echo '" mandatory="';
		echo $this->mandatoryWithinGroup ? 'yes':'no';
		echo '" min_credit_value="';
		echo htmlspecialchars((string)$this->minCreditValue);
		echo '" max_credit_value="';
		echo htmlspecialchars((string)$this->maxCreditValue);
		echo '" min_components="';
		echo htmlspecialchars((string)$this->minSubComponents);
		echo '" max_components="';
		echo htmlspecialchars((string)$this->maxSubComponents);
		echo '">';
		
		echo "<description>";
		echo htmlspecialchars((string)$this->description);
		echo "</description>\n";
		
		foreach($this->children as $child)
		{
			$child->render();
		}
		
		echo '</unit_group>';
	}
	
	public $label;
	public $name;
	public $description;
	public $minCreditValue;
	public $maxCreditValue;
	public $minSubComponents;
	public $maxSubComponents;
	public $mandatoryWithinGroup;

	public $children = array();
}

class LinkedUnit
{
	public function __construct(array $fields)
	{
		$this->referenceNumber = $this->getFieldValue('unit reference number', $fields);
		if(!$this->referenceNumber){
			$this->referenceNumber = $this->getFieldValue('reference number', $fields);
		}
		$this->qualificationFramework = $this->getFieldValue('qualification framework', $fields);
		$this->title = $this->getFieldValue('title', $fields);
		$this->organisationName = $this->getFieldValue('organisation name', $fields);
		$this->level = $this->getFieldValue('unit level', $fields);
		$this->subLevel = $this->getFieldValue('unit sub level', $fields);
		$this->guidedLearningHours = $this->getFieldValue('guided learning hours', $fields);
		$this->credits = $this->getFieldValue('unit credit value', $fields);
		$this->dateOfWithdrawal = $this->getFieldValue('date of withdrawal', $fields);
		$this->ssa = $this->getFieldValue('ssas', $fields);
		$this->grading = $this->getFieldValue('unit grading structure', $fields);
		$this->assessmentGuidance = $this->getFieldValue('assessment guidance', $fields);
		$this->outcomes = $this->getFieldValue('outcomes', $fields);

		if(is_null($this->outcomes)){
			$this->outcomes = array();
		}
		
		// Clean unit level
		if($this->level)
		{
			$this->level = str_ireplace('entry', '0', $this->level);
			if(preg_match_all('/\d+\b/', $this->level, $matches) > 0){
				$this->level = implode(',', $matches[0]);
			}
		}
		
		if($this->subLevel)
		{
			if($this->subLevel == "None"){
				$this->subLevel = null;
			}
			
			if(preg_match_all('/\d+\b/', $this->subLevel, $matches) > 0){
				$this->subLevel = implode(',', $matches[0]);
			}
		}
		
		
		if(!is_numeric($this->guidedLearningHours) || $this->guidedLearningHours == 0){
			$this->guidedLearningHours = null;
		}
		
		if(!is_numeric($this->credits)){
			$this->credits = null;
		}
	}
	
	private function getFieldValue($fieldName, array $fields)
	{
		return array_key_exists($fieldName, $fields) ? $fields[$fieldName] : null;
	}
	
	public function render()
	{
		echo "<unit ";
		echo 'reference="';
		echo htmlspecialchars((string)$this->referenceNumber);
		echo '" title="';
		echo htmlspecialchars((string)$this->title);
		echo '" owner="';
		echo htmlspecialchars((string)$this->organisationName);
		echo '" level="';
		echo htmlspecialchars((string)$this->level);
		echo '" sublevel="';
		echo htmlspecialchars((string)$this->subLevel);
		echo '" glh="';
		echo htmlspecialchars((string)$this->guidedLearningHours);
		echo '" credits="';
		echo htmlspecialchars((string)$this->credits);
		echo '" qualification_framework="';
		echo htmlspecialchars((string)$this->qualificationFramework);
		echo '" withdrawal_date="';
		echo htmlspecialchars((string)$this->dateOfWithdrawal);
		echo '" ssa="';
		echo htmlspecialchars((string)$this->ssa);
		echo '" grading="';
		echo htmlspecialchars((string)$this->grading);
		echo "\">\n";
		
		echo "<assessment_guidance>";
		echo htmlspecialchars((string)$this->assessmentGuidance);
		echo "</assessment_guidance>\n";
		
//		echo "<outcomes>";
		foreach($this->outcomes as $outcome)
		{
			$outcome->render();
		}
//		echo "</outcomes>\n";
		
		echo "</unit>\n";
	}
	
	public $referenceNumber;
	public $title;
	public $organisationName;
	public $level;
	public $subLevel;
	public $guidedLearningHours;
	public $credits;
	public $qualificationFramework;
	public $dateOfWithdrawal;
	public $ssa;
	public $grading;
	public $assessmentGuidance;

	public $outcomes = array();
}


class Outcome
{
	public function __construct($index, $description)
	{
		$this->index = $index;
		$this->description = $description;
	}
	
	public function render()
	{
		echo '<element id="';
		echo htmlspecialchars((string)$this->index);
		echo '" title = "'; // Khushnood
		echo htmlspecialchars((string)$this->index) . ' ' . htmlspecialchars((string)$this->description);
		echo "\">\n";
		echo '<description>';
		echo htmlspecialchars((string)$this->description);
		echo "</description>\n";
//		echo "<criteria>\n";
		foreach($this->children as $criterion)
		{
			$criterion->render();		
		}
//		echo "</criteria>\n";
		echo "</element>\n";
	}
	
	public $index;
	public $description;
	
	public $children = array();
}


class Criterion
{
	public function __construct($index, $description)
	{
		$this->index = $index;
		$this->description = $description;
	}
	
	public function render()
	{
		echo '<evidence reference="" title="';
		echo htmlspecialchars((string)$this->index) . ' ' . htmlspecialchars((string)$this->description,ENT_QUOTES);
		echo "\">";
	//	echo htmlspecialchars((string)$this->description);
		echo "</evidence>\n";
	}
	
	public $index;
	public $description;
}
?>