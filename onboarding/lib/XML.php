<?php
/**
 * Factory class for building common XML parsers.
 * @author ianss
 */
class XML
{
	/**
	 * Parse an XML string and return a SimpleXMLElement object
	 * @param string $xml XML string, in UTF-8 or ISO-8859-1 (latin1) encoding
	 * @param integer $libxml_options Bitwise OR of libxml constants
	 * @return SimpleXMLElement SimpleXMLElement object on success
	 * @throws XMLException
	 */
	public static function loadSimpleXML($xml, $libxml_options = null)
	{
		$xml = trim($xml);
		if (!$xml) {
			throw new XMLException("Empty argument \$xml", 1);
		}

		$xml = XML::cleanCharacterEncoding($xml);
		// $xml = XML::escapeFreeAmpersands($xml);

		libxml_clear_errors();
		libxml_use_internal_errors(true);

		$document = simplexml_load_string($xml, null, $libxml_options ?? 0);

		if ($document === false) {
			throw new XMLException(XML::getErrorString(), 1, $xml);
		}

		libxml_use_internal_errors(false);
		return $document;
	}

	
	
	/**
	 * Parse an XML string and return a DOMDocument object
	 * @param string $xml XML string, in UTF-8 or ISO-8859-1 (latin1) encoding
	 * @param integer $libxml_options Bitwise OR of libxml constants
	 * @return DOMDocument DOMDocument object on success
	 * @throws XMLException
	 */
	public static function loadXmlDom($xml, $libxml_options = null)
	{
		$xml = trim($xml);
		if(!$xml){
			throw new XMLException("Empty argument \$xml", 1);
		}
		$xml = XML::cleanCharacterEncoding($xml);
		//$xml = XML::escapeFreeAmpersands($xml);
		libxml_clear_errors();
		libxml_use_internal_errors(true);
		$dom = new DOMDocument();
		if($dom->loadXML($xml, $libxml_options) === false)
		{
			throw new XMLException(XML::getErrorString(), 1, $xml);
		}
		libxml_use_internal_errors(false);
		return $dom;
	}
	
	
	/**
	 * Parse an HTML string and return a DOMDocument object
	 * @param string $xml , in UTF-8 or ISO-8859-1 (latin1) encoding
	 * @param integer $libxml_options Bitwise OR of libxml constants
	 * @return DOMDocument DOMDocument object on success
	 * @throws XMLException
	 */
	public static function loadHtmlDom($xml, $libxml_options = null)
	{
		$xml = trim($xml);
		if(!$xml){
			throw new XMLException("Empty argument \$xml", 1);
		}
		$xml = XML::cleanCharacterEncoding($xml);
		//$xml = XML::escapeFreeAmpersands($xml);
		libxml_clear_errors();
		libxml_use_internal_errors(true);
		$dom = new DOMDocument();
		if($dom->loadHTML($xml, $libxml_options) === false)
		{
			throw new XMLException(XML::getErrorString(), 1, $xml);
		}
		libxml_use_internal_errors(false);
		return $dom;
	}
	
	
	/**
	 * Parse an XML string and return a XMLReader object
	 * @param string $xml XML string, in UTF-8 or ISO-8859-1 (latin1) encoding
	 * @param integer $libxml_options Bitwise OR of libxml constants
	 * @return XMLReader XMLReader object on success
	 * @throws XMLException
	 */
	public static function loadXmlReader($xml, $libxml_options = null)
	{
		$xml = trim($xml);
		if(!$xml){
			throw new XMLException("Empty argument \$xml", 1);
		}
		$xml = XML::cleanCharacterEncoding($xml);
		//$xml = XML::escapeFreeAmpersands($xml);
		libxml_clear_errors();
		libxml_use_internal_errors(true);
		$reader = new XMLReader();
		if($reader->xml($xml, null, $libxml_options) === false)
		{
			throw new XMLException(XML::getErrorString(), 1, $xml);
		}
		libxml_use_internal_errors(false);
		return $reader;
	}
	
	/**
	 * @static
	 * @return null|string
	 */
	private static function getErrorString()
	{
		$errors = libxml_get_errors();
		if(count($errors) == 0){
			return null;
		}
		
		$msg = "";
		$error = $errors[0];
		switch($error->level)
		{
			case LIBXML_ERR_WARNING:
				$msg = "XML Warning ";
				break;
			case LIBXML_ERR_ERROR:
				$msg = "XML Error ";
				break;
			case LIBXML_ERR_FATAL:
				$msg = "XML Fatal Error ";
				break;
			default:
				$msg = "XML Problem ";
				break;
		}
		
		$msg .= " (line ".$error->line.", col ".$error->column."): ".$error->message;
		
		return $msg;
	}
	
	
	/**
	 * Corrects discrepancies between XML-declared and actual character encodings.
	 * @param string $xml
	 * @return string UTF-8 encoded string or a string encoded in the XML-declared encoding
	 */
	private static function cleanCharacterEncoding($xml)
	{
		$actual_encoding = mb_detect_encoding($xml, array("UTF-8", "ISO-8859-1", "Windows-1251"), true);
		$declared_encoding = "";
		if(preg_match('/<\\?xml.*?encoding\\s*=\\s*["\'](.*?)["\'].*?\\?>/i', $xml, $matches)){
			$declared_encoding = strtoupper($matches[1]);
			if(preg_match('/UTF([0-9]+)/', $declared_encoding, $matches)){
				$declared_encoding = "UTF-".$matches[1]; // restore the hyphen to the UTF encoding name
			}
		}
		
		if($declared_encoding)
		{
			if($actual_encoding != $declared_encoding){
				$xml = mb_convert_encoding($xml, $declared_encoding, $actual_encoding); // convert to the declared encoding
			}
		}
		else
		{
			if($actual_encoding != "UTF-8"){
				$xml = mb_convert_encoding($xml, "UTF-8", $actual_encoding); // no declared encoding, convert to UTF-8
			}
		}
		
		return $xml;
	}

	/**
	 * Encode free ampersands as a valid XML entity. Do not encode
	 * ampersands that are already part of entity references.
	 * @static
	 * @param string $xml
	 * @return string mixed
	 */
	private static function escapeFreeAmpersands($xml)
	{
		$re = "/&(?!([a-zA-Z]+;|#[0-9]+;))/"; // ampersands not followed by an entity reference
		$xml = preg_replace($re, '&amp;', $xml);
		return $xml;
	}
}
?>