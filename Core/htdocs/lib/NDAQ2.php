<?php
/**
 * National Database of Accredited Qualifications
 *
 */
class NDAQ2
{
	const PORT = 80;
	const HOST_NAME = 'www.accreditedqualifications.org.uk';
	//const QUAL_PAGE = '/qualification/';
	//const UNIT_PAGE = '/UnitDetailPrint.aspx?Printable=Y&UnitReference=';


	const QUALIFICATION_ONLY = 1;
	const QUALIFICATION_AND_STRUCTURE = 2;

	
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
	 * @param integer $import What to import
	 * @return string Qualification in XML format
	 */
	public function getQualification($ref, $import = NDAQ::QUALIFICATION_AND_STRUCTURE)
	{
		$this->retrieval_time = $this->processing_time = 0;
		
		$start_time = microtime(TRUE);
		$uri = '/qualification/' . preg_replace('/[^a-zA-Z0-9]/', '', $ref) . '.seo.aspx';
		$html = $this->getPage($uri);
		$finish_time = microtime(true);
		$this->retrieval_time += round($finish_time - $start_time, 4);
		$start_time = $finish_time; // reset
		
		if(stripos($html, "<html><head><title>Object moved</title></head><body>") !== false)
		{
			return null;
		}
		
		// Silently remove all control characters except for CR, LF and TAB
		$html = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F]/', '', $html);
		
		// Correct XHTML error number one: extra </div>
		$index = strpos($html, '>Qualification level<');
		if($index !== false)
		{
			$index = strpos($html, '<div class="rightContainer">', $index);
			$index = strpos($html, '</div>', $index);
			$html = substr($html, 0, $index).substr($html, $index+6);
		}
		else
		{
			throw new Exception("Could not correct HTML error number one"); 
		}
		
		// Correct XHTML error number two: missing </div>
		$index = strpos($html, '>Qualification type<');
		if($index !== false)
		{
			$index = strpos($html, '<div class="rightContainer">', $index);
			$index = strpos($html, '</div>', $index);
			$html = substr($html, 0, $index).'</div>'.substr($html, $index);
		}
		else
		{
			throw new Exception("Could not correct HTML error number one"); 
		}
		
		// Help the PHP DOM parser -- remove JavaScript that contains HTML tags
		$re = '#<script[^>]*>.*</script>#sU';
		$html = preg_replace($re, '', $html);
		
		// Remove header
		$re = '#<head>.*</head>#sU';
		$html = preg_replace($re, '', $html);
		
		
		// Load DOM and XPATH parsers (DOM parser appears to be Latin1 in, UTF-8 out!)
		$html = Text::utf8_to_latin1($html);
		//$doc = new DOMDocument('1.0', 'ISO-8859-1');
		//@$doc->loadHTML($html);
		$doc = XML::loadHtmlDom($html);
		$xpath = new DOMXPath($doc);
		
		// Parse fields from page
		$fields = $this->getQualificationFields($doc, $xpath);
		$finish_time = microtime(true);
		$this->processing_time += round($finish_time - $start_time, 4);
		$start_time = $finish_time;
		
		$xml = '<qualification ';
		
		$title = str_replace("'","",$fields['title']);
		$title = str_replace("&","and",$title);
		
//		$xml .= $this->formatAttribute('title', $fields, 'title');
		$xml .= ' title="' . $title . '"';
		$xml .= $this->formatAttribute('type', $fields, 'qualification type');
		$xml .= $this->formatAttribute('level', $fields, 'qualification level');
		$xml .= $this->formatAttribute('reference', $fields, 'qualification reference number');
		$xml .= $this->formatAttribute('awarding_body', $fields, 'awarding body');
		$this->formatAttribute('credits', $fields, 'qualification credit value');		
		$xml .= $this->formatAttribute('guided_learning_hours', $fields, 'qualification guided learning hours');
		$xml .= $this->formatAttribute('accreditation_start_date', $fields, 'accreditation start date');
		$xml .= $this->formatAttribute('operational_centre_start_date', $fields, 'operational start date in centres');
		$xml .= $this->formatAttribute('accreditation_end_date', $fields, 'accreditation end date');
		$xml .= $this->formatAttribute('certification_end_date', $fields, 'certification end date');
		$xml .= $this->formatAttribute('dfes_approval_start_date', $fields, 'approval start date');
		$xml .= $this->formatAttribute('dfes_approval_end_date', $fields, 'approval end date');
		$xml .= $this->formatAttribute('mainarea', $fields, 'mainarea');
		$xml .= $this->formatAttribute('subarea', $fields, 'subarea');
		
		$xml .= ">\n";

		$xml .= $this->formatElement('description', $fields, 'qualification summary');
		$xml .= $this->formatElement('assessment_method', $fields, 'overall assessment method for the qualification');
		$xml .= $this->formatElement('structure', $fields, 'qualification structure summary');
		
		// Include units if requested
		if($import > NDAQ2::QUALIFICATION_ONLY  && isset($fields['qualification structure']) )
		{
			$structure = $this->getUnitStructure($fields['qualification structure'], $xpath);
			
			if(!is_null($structure))
			{
				// $xml .= "<units title=\"Structure\">\n"; Khushnood
				$xml .= "<root>"; // Khushnood	
				$currentLevel = 0;
	
				foreach($structure as $node)
				{
					if($node['depth'] < $currentLevel)
					{
						$xml .= str_repeat("</units>\n", $currentLevel - $node['depth']);
					}
					$currentLevel = $node['depth'];
					
					if($node['type'] == 'unit group')
					{
						$xml .= '<units title="'.htmlspecialchars((string)$node['content'])."\">\n";
						$currentLevel++;
					}
					else
					{
						$xml .= $this->getUnit($node['content']);
					}
				}
				$xml .= str_repeat("</units>\n", $currentLevel - 1);
				
				// $xml .= "</units>\n";
				$xml .= "</root>"; //Khushnood				
			}
		}
		

		// Add performance figures
		$xml .= $this->getPerformanceFigures($fields);
		

		$xml .= '<url>'.htmlspecialchars('http://'.NDAQ2::HOST_NAME.$uri)."</url>\n";
//		$xml .= '<timestamp>'.htmlspecialchars(date('Y-m-d\TH:i:sP'))."</timestamp>\n";
//		$xml .= '<time_to_retrieve>'.$this->retrieval_time."</time_to_retrieve>\n";
//		$xml .= '<time_to_process>'.$this->processing_time."</time_to_process>\n";
		$xml .= "</qualification>";

		$xml = str_replace("&","&amp;",$xml);
		return $xml;
	}
	

	/**
	 * @param string $ref QCA unit reference
	 */
	public function getUnit($uri)
	{
		$start_time = microtime(TRUE);
		if(array_key_exists($uri, $this->unit_cache))
		{
			$fields = $this->unit_cache[$uri];
			$retrieval_time = 0;
			$processing_time = 0;
		}
		else
		{
			//$uri = '/unit/' . preg_replace('/[^a-zA-Z0-9]/', '', $ref) . '.seo.aspx';
			$html = $this->getPage($uri);
			$retrieval_time = round(microtime(TRUE) - $start_time, 4);
			$this->retrieval_time += $retrieval_time;
		
			$start_time = microtime(TRUE);
			
			// Silently remove all control characters except for CR, LF and TAB
			$html = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F]/', '', $html);
			
			// Help the PHP DOM parser -- remove JavaScript that contains HTML tags
			$re = '#<script[^>]*>.*</script>#sU';
			$html = preg_replace($re, '', $html);
			
			// Remove header
			$re = '#<head>.*</head>#sU';
			$html = preg_replace($re, '', $html);		
		
			$fields = $this->getUnitFields($html);
	
			$processing_time = round(microtime(TRUE) - $start_time, 4);
			$this->processing_time += $processing_time;
			
			$this->unit_cache[$uri] = $fields;
		}
		
		$xml = '<unit ';
		$xml .= $this->formatAttribute('title', $fields, 'title');
		$xml .= $this->formatAttribute('reference', $fields, 'unit reference number');
//		$xml .= $this->formatAttribute('owner', $fields, 'unit owner name');
		$xml .= $this->formatAttribute('owner_reference', $fields, 'unit owner reference number');
		$xml .= $this->formatAttribute('level', $fields, 'unit level');
//		$xml .= $this->formatAttribute('grading', $fields, 'unit grading system');
		$xml .= $this->formatAttribute('credits', $fields, 'unit credit value');
		$xml .= $this->formatAttribute('glh', $fields, 'unit guided learning hours');
		$xml .= ">\n";
		$xml .= $this->formatElement('description', $fields, 'unit summary');
//		$xml .= '<url>'.htmlspecialchars('http://'.NDAQ2::HOST_NAME.$uri)."</url>\n";
//		$xml .= '<timestamp>'.htmlspecialchars(date('Y-m-d\TH:i:sP'))."</timestamp>\n";
//		$xml .= '<time_to_retrieve>'.$retrieval_time."</time_to_retrieve>\n";
//		$xml .= '<time_to_process>'.$processing_time."</time_to_process>\n";


		if(isset($fields['unit owner reference number']) && $fields['unit owner reference number']!="NMS")
		{
			
			// Add elements and evidences [Khushnood]
			if(isset($fields['elementsandevidences'])) 
			{ 
				$whatwaslast = '';
				foreach($fields['elementsandevidences'] as $key=>$value) 
				{ 
					if(!is_array($value))
					{	
						if($whatwaslast=='element')
						{
							$xml = substr($xml,0,strlen($xml)-10);
							$xml .= '<evidence reference="" title="' . $value . '"></evidence><description></description>';
							$whatwaslast = 'evidence';
							$xml .= '</element>';
						}
						else 
						{
							$xml .= '<element title="' . $value . '"><description>no description</description></element>';
							$whatwaslast = 'element';
						}
					}
					else
					{	
						$xml = substr($xml,0,strlen($xml)-10);
						foreach($value as $evi)
						{	
							if($evi!='')
								$xml .= '<evidence reference="" title="' . str_replace('"','',$evi) . '">ss</evidence><description></description>';
								$whatwaslast = 'evidence';
						}
						$xml .= '</element>';
					}
				}
			}
		}
				
		$xml .= "</unit>\n";

//		if($fields['unit owner reference number']=="NMS")
//			die($xml);
		
		
		return $xml;
	}
	
	
	private function formatAttribute($attribute_name, array $array, $key)
	{
		if(isset($array[$key]))
		{
			if(Date::isDate($array[$key]))
			{
				return ' '.$attribute_name.'="'.htmlspecialchars(Date::toMySQL($array[$key])).'" ';
			}
			else
			{
				return ' '.$attribute_name.'="'.htmlspecialchars((string)$array[$key]).'" ';
			}
		}
		else
		{
			return ' '.$attribute_name.'="" ';
		}
	}
	
	
	private function formatElement($element_name, array $array, $key)
	{
		$xml = '<'.str_replace(' ', '_', $element_name).'>';
		
		if(isset($array[$key]))
		{
			if(Date::isDate($array[$key]))
			{
				$xml .= htmlspecialchars(Date::toMySQL($array[$key]));
			}
			else
			{
				$xml .= htmlspecialchars((string)$array[$key]);
			}
		}

		$xml .= '</'.str_replace(' ', '_', $element_name).'>';
		
		return $xml;
	}
	
	
	private function getQualificationFields(DOMDocument $doc, DOMXPath $xpath)
	{
		// Get title
		$query = '//h2/div[@class="leftTranslate"]/div[@class="leftContainer"]/text()';
		$titles = $xpath->query($query);
		$fields['title'] = $this->cleanFieldValue($titles->item(0)->nodeValue);
		
		// Get summary
		$nodeList = $doc->documentElement->getElementsByTagName("h3");
		foreach($nodeList as $node)
		{
			if(strpos($node->textContent, 'Qualification summary') !== false)
			{
				$p = $node; 
				do
				{
					$p = $p->nextSibling;
				} while( ($p->nodeType != XML_ELEMENT_NODE) || ($p->nodeName != 'p') );
				
				$fields['qualification summary'] = $this->cleanFieldValue($this->extractText($p));
				break;
			}
		}
	
		// Get fields
		$query = '//div[@class="rowOdd" or @class="rowEven"]';
		$rows = $xpath->query($query);	
		
		foreach($rows as $row)
		{
			$query = './/div[@class="subSectionContainer"]';
			$nodeList = $xpath->query($query, $row);

			
			if($nodeList->length > 0)
			{
				// Subsection present
				$query = 'div[@class="row" or @class="row noBorder"]';
				$subrows = $xpath->query($query, $nodeList->item(0));
				foreach($subrows as $subrow)
				{
					$query = 'div[@class="left"]/div/text()';
					$labels = $xpath->query($query, $subrow);
					$label = strtolower(preg_replace('/[^a-zA-Z0-9 ]/', '', $labels->item(0)->textContent));
						
					$query = 'div[@class="right"]/div';
					$values = $xpath->query($query, $subrow);
					
					$fields[$label] = $this->cleanFieldValue($this->extractText($values->item(0)));
				}
			}
			else
			{
				$query = 'div[@class="left"]/div[@class="leftContainer"]/noscript//a/text()';
				$labels = $xpath->query($query, $row);
				$label = strtolower(preg_replace('/[^a-zA-Z0-9 ]/', '', $labels->item(0)->textContent));
				
				$query = 'div[@class="right"]/div[@class="rightContainer"]';
				$values = $xpath->query($query, $row);
				
				
				switch($label)
				{
					case 'qualification structure':
						$query = './/ul[@class="expandableList"]';
						$nodelist = $xpath->query($query, $values->item(0));
						$fields['qualification structure'] = ($nodelist->length > 0) ? $nodelist->item(0) : null;
						break;
						
						case 'progression':
						case 'potential job occupations':
						break;
						
						case 'qualification sectorsubject areabrqualification subareas':
							// Extract qualification sector subject area
							$str = $this->cleanFieldValue($this->extractText($values->item(0)));
							$mainarea = substr($str,0,strpos($str,"\n"));
							$subarea = substr($str,strpos($str,"\n")+1);
							$subarea = str_replace("\n","",$subarea);
														
							$fields['mainarea'] = $mainarea;
							$fields['subarea'] = $subarea;
							
						break;
                        case 'learning outcome  assessment criteria': 
                  	    	$fields[$label] = $this->extractLearningOutcomes($xpath, $row); 
                    	break; 
       
					case 'performance figures':
						$div = $values->item(0);
						$anchors = $div->getElementsByTagName('a');
						if($anchors->length > 0)
						{
							$fields[$label] = $anchors->item(0)->getAttribute('href');
						}
						else
						{
							$fields[$label] = null; 
						}
						break;
						
					default:
						$fields[$label] = $this->cleanFieldValue($this->extractText($values->item(0)));
				}
			}
		}
		
	
		// Clean qualification type
		if(isset($fields['qualification type']))
		{
			if(preg_match('/^(\w*)/', $fields['qualification type'], $matches) > 0)
			{
				$fields['qualification type'] = $matches[1];
			}
		}
		
		// Clean qualification level
		if(isset($fields['qualification level']))
		{
			$fields['qualification level'] = str_ireplace('entry', '0', $fields['qualification level']);
			if(preg_match_all('/\d+\b/', $fields['qualification level'], $matches) > 0)
			{
				$fields['qualification level'] = implode(',', $matches[0]);
			}
		}
		
		return $fields;
	}
	
	
	
	/**
	 * Given 
	 */
	private function extractText(DOMElement $node)
	{
		$text = '';
		
		/* @var $child DOMElement */
		foreach($node->childNodes as $child)
		{
			if($child->nodeType == XML_ELEMENT_NODE)
			{
				if($child->hasChildNodes())
				{
					$text .= $this->extractText($child);
				}
				
				// Add a newline character for block-level elements and table rows
				switch($child->tagName)
				{
					case 'br':
					case 'div':
					case 'li':
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
		
		return $text;
	}
	
	

	
	
	private function getUnitStructure(DOMElement $tree, DOMXPath $xpath)
	{
		$structure = array();
		if(!is_null($tree) && ($tree->nodeType == XML_ELEMENT_NODE) && ($tree->tagName == 'ul') )
		{
			$this->processTree($tree, $xpath, $structure, 1);
		}
		
		return $structure;
	}
	
	
	private function processTree(DOMElement $tree, DOMXPath $xpath, &$structure, $depth)
	{
		$childNodes = $tree->childNodes;
		foreach($childNodes as $child)
		{
			if( ($child->nodeType == XML_ELEMENT_NODE) && ($child->tagName == "li") )
			{
				if($child->getAttribute('class') != 'treeItem')
				{
					// Subtree of units

					// Get the heading
					$heading = '';
					foreach($child->childNodes as $n)
					{
						if($n->nodeType == XML_TEXT_NODE)
						{
							$heading .= $n->nodeValue;
						}
						elseif($n->tagName == 'b')
						{
							$heading .= $n->firstChild->nodeValue;
						}
					}
					
					$heading = $this->cleanFieldValue($heading); // clean
					$heading = preg_replace('/\n+/', ' ', $heading); // remove newlines
					$heading = trim(preg_replace('/[ ]+/', ' ', $heading) ); // remove multiple spaces
					$structure[] = array('depth'=>$depth, 'type'=>'unit group', 'content'=>$heading);
					
					$query = './/ul';
					$nodelist = $xpath->query($query, $child);
					
					// Call this method again for the subtree
					if($nodelist->length > 0)
					{
						$this->processTree($nodelist->item(0), $xpath, $structure, $depth + 1);
					}
				}
				else
				{
					// Unit reference
					$query = './/a';
					$nodelist = $xpath->query($query, $child);
					$content = str_replace(' ', '%20', $nodelist->item(0)->getAttribute('href'));
					$structure[] = array('depth'=>$depth, 'type'=>'unit', 'content'=>$content);
				}
			}
		}
	}
	
	
	private function getUnitFields($html)
	{
		// Load DOM and XPATH parsers (DOM parser appears to be Latin1 in, UTF-8 out!)
		$html = Text::utf8_to_latin1($html);
		//$doc = new DOMDocument('1.0', 'ISO-8859-1');
		//@($doc->loadHTML($html));
		$doc = XML::loadHtmlDom($html);
		$xpath = new DOMXPath($doc);
		
		// Get title
		$query = '//h2/text()';
		$titles = $xpath->query($query);
		$fields['title'] = $this->cleanFieldValue($titles->item(0)->nodeValue);
		
		// Get summary
		$nodeList = $doc->documentElement->getElementsByTagName("h3");
		foreach($nodeList as $node)
		{
			if(strpos($node->textContent, 'Unit summary') !== false)
			{
				$p = $node; 
				do
				{
					$p = $p->nextSibling;
				} while( ($p->nodeType != XML_ELEMENT_NODE) || ($p->nodeName != 'p') );
				
				$fields['unit summary'] = $this->cleanFieldValue($this->extractText($p));
				break;
			}
		}
	
		// Get fields
		$query = '//div[@class="rowOdd" or @class="rowEven"]';
		$rows = $xpath->query($query);		
		foreach($rows as $row)
		{
			$query = './/div[@class="subSectionContainer"]';
			$nodeList = $xpath->query($query, $row);
			
			if($nodeList->length > 0)
			{
				// Subsection present
				$query = 'div[@class="row" or @class="row noBorder"]';
				$subrows = $xpath->query($query, $nodeList->item(0));
				foreach($subrows as $subrow)
				{
					$query = 'div[@class="left"]/div/text()';
					$labels = $xpath->query($query, $subrow);
					$label = strtolower(preg_replace('/[^a-zA-Z0-9 ]/', '', $labels->item(0)->textContent));
						
					$query = 'div[@class="right"]/div';
					$values = $xpath->query($query, $subrow);
					
					$fields[$label] = $this->cleanFieldValue($this->extractText($values->item(0)));
				}
			}
			else
			{
				$query = 'div[@class="left"]/div[@class="leftContainer"]/noscript//a/text()';
				$labels = $xpath->query($query, $row);
				$label = strtolower(preg_replace('/[^a-zA-Z0-9 ]/', '', $labels->item(0)->textContent));
				
				$query = 'div[@class="right"]/div[@class="rightContainer"]';
				$values = $xpath->query($query, $row);
				
				$fields[$label] = $this->cleanFieldValue($this->extractText($values->item(0)));
			}
		}		
		
		// Clean unit level
		if(isset($fields['unit level']))
		{
			$fields['unit level'] = str_ireplace('entry', '0', $fields['unit level']);
			if(preg_match_all('/\d+\b/', $fields['unit level'], $matches) > 0)
			{
				$fields['unit level'] = implode(',', $matches[0]);
			}
		}
		
		// Add elements [Khushnood] 
		// Extract learning outcome table only 
		if(strpos($html,"Learning Outcome")>0)
		{
			$html = substr($html,strpos($html,"Learning Outcome"), strpos($html,"</table>",strpos($html,"Learning Outcome"))-strpos($html,"Learning Outcome"));
	
			$html = "<table><tr><th>" . $html . "</table>";
			
			// Test
			$temps = Array();
			for($a=1; $a<=20; $a++)
			{
				$temps[] = "P".$a;
				for($b=1; $b<=20; $b++)
					$temps[] = $a.".".$b;
			}		

			$temps[] = "a:";
			$temps[] = "b:";
			$temps[] = "c:";
			$temps[] = "d:";
			$temps[] = "e:";
			$temps[] = "f:";
			$temps[] = "g:";
			$temps[] = "h:";
			$temps[] = "i:";
			$temps[] = "j:";
			$temps[] = "k:";
			
			
			$html = str_replace("<br />1","ppppp1",$html);
			$html = str_replace("<br />2","ppppp2",$html);
			$html = str_replace("<br />3","ppppp3",$html);
			$html = str_replace("<br />4","ppppp4",$html);
			$html = str_replace("<br />5","ppppp5",$html);
			$html = str_replace("<br />6","ppppp6",$html);
			$html = str_replace("<br />7","ppppp7",$html);
			$html = str_replace("<br />8","ppppp8",$html);
			$html = str_replace("<br />9","ppppp9",$html);
			//$pageDom = new DomDocument();
			$html = str_replace("&","&amp;",$html);

//			throw new Exception($html);
			
			//@$pageDom->loadXML(utf8_encode($html));
			$pageDom = XML::loadXmlDom(mb_convert_encoding($html,'UTF-8'));
			$e = $pageDom->getElementsByTagName('td');
			$elements = Array();
			foreach($e as $node)
			{
				if(strpos($node->nodeValue,"ppppp")>0)	
					$elements[] = explode("ppppp",$node->nodeValue);
				else
				{
					$nodes = $node->nodeValue;
					$element = true;
					foreach($temps as $temp)
					{
						if(strpos($nodes,$temp)>0)
							$element = false;
						$nodes = str_replace($temp, ("<br />".$temp), $nodes);
					}																		
					if($element)
						$elements[] = $node->nodeValue;
					else
						$elements[] = explode("<br />",$nodes);
				}
			}
			$fields["elementsandevidences"] = $elements;
		}		
		return $fields;			
	}
	
	
	
	private function getPage($url)
	{
		if(!$this->curl)
		{
			$this->curl = curl_init();
			curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($this->curl, CURLOPT_CONNECTTIMEOUT, 3); // seconds
			curl_setopt($this->curl, CURLOPT_TIMEOUT, 5); // seconds
			curl_setopt($this->curl, CURLOPT_FOLLOWLOCATION, true);
			curl_setopt($this->curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 6.1; en-GB; rv:1.9.2) Gecko/20100115 Firefox/3.6");
			if($this->proxy_addr && $this->proxy_port)
			{
				curl_setopt($this->curl, CURLOPT_PROXY, $this->proxy_addr.':'.$this->proxy_port);
			}
		}

		curl_setopt($this->curl, CURLOPT_URL, "http://".NDAQ2::HOST_NAME.$url);
		$result = curl_exec ($this->curl);
		if(curl_error($this->curl))
		{
			throw new Exception(curl_error($this->curl));
		}

		$effective_url = curl_getinfo($this->curl, CURLINFO_EFFECTIVE_URL);
		if(strpos($effective_url, "Error.aspx") !== FALSE)
		{
			return null;
		}
		else
		{
			return $result;
		}
	}
	
	
	private function getPerformanceFigures(array $fields)
	{
		if(isset($fields['performance figures']) && ($fields['performance figures'] != '') )
		{
			$xml = "<performance_figures>\n";
			
			$start_time = microtime(TRUE);
			$html = $this->getPage($fields['performance figures']);
			$retrieval_time = round(microtime(TRUE) - $start_time, 4);
			$this->retrieval_time += $retrieval_time;
			
			$start_time = microtime(TRUE);
			
			// Level 3 tables
			$reg3 = "#<table[^>]*>[^<]*<caption></caption>[^<]*<thead>[^<]*<tr[^>]*>[^<]*"
				. "<th[^>]*>Grade</th>[^<]*"
				. "<th[^>]*>Contribution to the level 1 and 2 thresholds</th>[^<]*"
				. "<th[^>]*>Contribution to the level 3 threshold</th>[^<]*"
				. "<th[^>]*>Point Score</th>[^<]*</tr>[^<]*</thead>[^<]*<tbody>"
				. "(?:[^<]*<tr[^>]*>[^<]*"
				. "<td[^>]*>[^<]*</td>[^<]*"
				. "<td[^>]*>[^<]*</td>[^<]*"
				. "<td[^>]*>[^<]*</td>[^<]*"
				. "<td[^>]*>[^<]*</td>[^<]*"
				. "</tr>)+[^<]*</tbody>[^<]*</table>#s";
			
			// Level 2 tables
			$reg2 = "#<table[^>]*>[^<]*<caption></caption>[^<]*<thead>[^<]*<tr[^>]*>[^<]*"
				. "<th[^>]*>Grade</th>[^<]*"
				. "<th[^>]*>Contribution to the level 1 and 2 thresholds</th>[^<]*"
				. "<th[^>]*>Point Score</th>[^<]*</tr>[^<]*</thead>[^<]*<tbody>"
				. "(?:[^<]*<tr[^>]*>[^<]*"
				. "<td[^>]*>[^<]*</td>[^<]*"
				. "<td[^>]*>[^<]*</td>[^<]*"
				. "<td[^>]*>[^<]*</td>[^<]*"
				. "</tr>)+[^<]*</tbody>[^<]*</table>#s";
			
			// Level 1 tables
			$reg1 = "#<table[^>]*>[^<]*<caption></caption>[^<]*<thead>[^<]*<tr[^>]*>[^<]*"
				. "<th[^>]*>Grade</th>[^<]*"
				. "<th[^>]*>Contribution to the level 1 threshold</th>[^<]*"
				. "<th[^>]*>Point Score</th>[^<]*</tr>[^<]*</thead>[^<]*<tbody>"
				. "(?:[^<]*<tr[^>]*>[^<]*"
				. "<td[^>]*>[^<]*</td>[^<]*"
				. "<td[^>]*>[^<]*</td>[^<]*"
				. "<td[^>]*>[^<]*</td>[^<]*"
				. "</tr>)+[^<]*</tbody>[^<]*</table>#s";
				
			// Entry level tables
			$reg0 = "#<table[^>]*>[^<]*<caption></caption>[^<]*<thead>[^<]*<tr[^>]*>[^<]*"
				. "<th[^>]*>Grade</th>[^<]*"
				. "<th[^>]*>Point Score</th>[^<]*</tr>[^<]*</thead>[^<]*<tbody>"
				. "(?:[^<]*<tr[^>]*>[^<]*"
				. "<td[^>]*>[^<]*</td>[^<]*"
				. "<td[^>]*>[^<]*</td>[^<]*"
				. "</tr>)+[^<]*</tbody>[^<]*</table>#s";
			

			$grades = array();	
				
			// Get level 3 table information
			if(preg_match_all($reg3, $html, $matches, PREG_SET_ORDER) > 0)
			{
				$reg = "#<td>([^<]*)</td>[^<]*<td>([^<]*)</td>[^<]*<td>([^<]*)</td>[^<]*<td>([^<]*)</td>#";
				
				foreach($matches as $table)
				{
					preg_match_all($reg, $table[0], $rows, PREG_SET_ORDER);
					for($i = 0; $i < count($rows); $i++)
					{
						$grades[] = array(
							'grade'=>$rows[$i][1],
							'l1'=>0,
							'l12'=>str_replace('%','', $rows[$i][2]),
							'l3'=>str_replace('%', '', $rows[$i][3]),
							'points'=>$rows[$i][4]);
					}
				}
			}
			
			
			// Get level 1&2 table information
			if(preg_match_all($reg2, $html, $matches, PREG_SET_ORDER) > 0)
			{
				$reg = "#<td>([^<]*)</td>[^<]*<td>([^<]*)</td>[^<]*<td>([^<]*)</td>#";
				
				foreach($matches as $table)
				{
					preg_match_all($reg, $table[0], $rows, PREG_SET_ORDER);
					for($i = 0; $i < count($rows); $i++)
					{
						$grades[] = array(
						'grade'=>$rows[$i][1],
							'l1'=>0,
							'l12'=>str_replace('%','', $rows[$i][2]),
							'l3'=>0,
							'points'=>$rows[$i][3]);
					}
				}
			}			
			
			
			// Get level 1 table information
			if(preg_match_all($reg1, $html, $matches, PREG_SET_ORDER) > 0)
			{
				$reg = "#<td>([^<]*)</td>[^<]*<td>([^<]*)</td>[^<]*<td>([^<]*)</td>#";
				
				foreach($matches as $table)
				{
					preg_match_all($reg, $table[0], $rows, PREG_SET_ORDER);
					for($i = 0; $i < count($rows); $i++)
					{
						$grades[] = array(
							'grade'=>$rows[$i][1],
							'l1'=>str_replace('%','', $rows[$i][2]),
							'l12'=>0,
							'l3'=>0,
							'points'=>$rows[$i][3]);
					}
				}
			}				
			
			// Get entry-level table information
			if(preg_match_all($reg0, $html, $matches, PREG_SET_ORDER) > 0)
			{
				$reg = "#<td>([^<]*)</td>[^<]*<td>([^<]*)</td>#";
				
				foreach($matches as $table)
				{
					preg_match_all($reg, $table[0], $rows, PREG_SET_ORDER);
					for($i = 0; $i < count($rows); $i++)
					{
						$grades[] = array(
							'grade'=>$rows[$i][1],
							'l1'=>0,
							'l12'=>0,
							'l3'=>0,
							'points'=>$rows[$i][2]);
					}
				}
			}			
			
			
			// Write to XML
			foreach($grades as $grade)
			{
				$xml .= '<attainment grade="'.$grade['grade'].'" level_1_threshold="'
					.$grade['l1'].'" level_1_and_2_threshold="'
					.$grade['l12'].'" level_3_threshold="'
					.$grade['l3'].'" points="'
					.$grade['points'].'"></attainment>'."\n";
			}

			$processing_time = round(microtime(TRUE) - $start_time, 4);
			$this->processing_time += $processing_time;
			$xml .= '<timestamp>'.htmlspecialchars(date('Y-m-d\TH:i:sP'))."</timestamp>\n";
			$xml .= '<time_to_retrieve>'.$retrieval_time."</time_to_retrieve>\n";
			$xml .= '<time_to_process>'.$processing_time."</time_to_process>\n";
			
			$xml .= "</performance_figures>\n";
			
			return $xml;
		}

		return NULL;
	}
	
	
	private function cleanFieldValue($fieldValue, $isUTF8 = true)
	{
		//$fieldValue = Text::windows_1252_to_ascii($fieldValue);
		$fieldValue = Text::utf8_to_latin1($fieldValue);
		$fieldValue = Text::remove_html_markup($fieldValue);
		
		return trim($fieldValue);
	}
	
	
	private function getSocket()
	{
		if(DEBUG) echo "<p style=\"font-weight:bold\">getSocket()</p>";

		$socket = @socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
		if($socket == false)
		{
			throw new Exception("Cannot create socket. " . socket_strerror(socket_last_error()));
		}

		$addr = @gethostbyname($this->proxy_addr != ''?$this->proxy_addr:NDAQ2::HOST_NAME);
		$port = $this->proxy_port != ''?$this->proxy_port:NDAQ2::PORT;
		if(@socket_connect($socket, $addr, $port) == false)
		{
			throw new Exception("Socket could not connect to server. Reason: (" . socket_last_error($socket) . "): " . socket_strerror(socket_last_error($socket)));
		}

		return $socket;
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
}
?>