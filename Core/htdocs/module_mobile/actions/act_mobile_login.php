<?php

/**
 * iPFaces - framework for developing mobile applications
 * ----------------------------------------------
 *
 * Copyright (c) 2009 Edhouse (http://www.edhouse.eu)
 *
 * This source file is subject to the BSD license that is bundled
 * with this package in the file license.txt.
 * For other licencing options see http://www.ipfaces.org/content/bsd-license, or contact us http://www.ipfaces.org/contact
 *
 * For more information please see http://www.ipfaces.org
 *
 * ipfaces.php:
 * -------
 * This is main entry file for using iPFaces in PHP.
 *
 *
 *
 * @copyright  Copyright (c) 2009 Edhouse s.r.o.
 * @license    BSD License
 * @link       http://www.ipfaces.org
 * @package    ipfaces
 * @version    1.3
 */



/**
 * Check PHP configuration.
 */
if (version_compare(PHP_VERSION, "5.1.0", "<")) {
	die("iPFaces needs PHP 5.1.0 or newer.");
}

/**
 * Include all classes for iPFaces elements.
 */


/**
 * Base class that all controls must inherit.
 * Neccesarry for determining whether one of
 * element class is of IPF element class.
 *
 * @author Edhouse s.r.o.
 * @access public
 */
class IPFElementBase {

	/**
	 * Checks whether variable contains valid bool value
	 *
	 * @param mixed $variable
	 * @return bool
	 */
	protected function checkBoolean($variable) {
		$valid = true;
		if (!empty($variable)) {
			if (!is_bool($variable)) {
				if (is_string($variable)) {
					if (strtolower($variable) != "true" && strtolower($variable) != "false"){
						$valid = false;
					}
				}
				elseif (is_int($variable)) {
					if ($variable != 1 && $variable != 0) {
						$valid = false;
					}
				}
			}
		}
		return $valid;
	}


	/**
	 * @param mixed $variable
	 * @return bool
	 */
	protected function setBoolean($variable) {
		if ($variable === "true" || $variable == 1) {
			$variable = true;
		} else if ($variable === "false" || $variable == 0) {
			$variable = false;
		}
		return $variable;
	}

	/**
	 * @param mixed $variable
	 * @return bool
	 */
	protected function checkInteger($variable) {
		$valid = true;
		if (!is_numeric($variable)){
			$valid = false;
		}
		return $valid;
	}

	/**
	 * @param string $attribute
	 * @param mixed $value
	 * @return string
	 */
	protected function setAttributeIfNotEmpty($attribute, $value) {
		if (!empty($value)) {
			return $attribute.'="'.str_replace("\n","&#10;",htmlspecialchars((string)$value,ENT_QUOTES)).'" ';
		}

	}

	/**
	 * @param string $attribute
	 * @param mixed $value
	 * @return string
	 */
	protected function setAttribute($attribute, $value) {
		return $attribute.'="'.str_replace("\n","&#10;",htmlspecialchars((string)$value,ENT_QUOTES)).'" ';
	}
}




/**
 * Base class that all controls must inherit.
 * Currently does nothing, but is neccesarry for determining whether one of
 * element class is of our class.
 *
 * @author Edhouse s.r.o.
 * @access public
 */
class IPFScreenElementBase extends IPFElementBase {

}




/**
 * Gps class.
 *
 * @author Edhouse s.r.o.
 * @access public
 */
class Gps {

	/**
	 * @access public
	 * @var string
	 */
	public $Available;

	/**
	 * @access public
	 * @var string
	 */
	public $Latitude;

	/**
	 * @access public
	 * @var string
	 */
	public $Longitude;

	/**
	 * @access public
	 * @var string
	 */
	public $Altitude;

	/**
	 * @access public
	 * @param string $value
	 * @return Gps
	 */
	public function __construct($value) {
		$array = explode(' ', $value);
		$latitude = '';
		$longitude = '';
		$altitude = '';
		$available = false;

		if (!empty($array)) {
			if (count($array) == 2 || count($array) == 3) {
				$latitude = $array[0];
				$longitude = $array[1];
				$available = true;
				if (count($array) == 3) {
					$altitude = $array[2];
				}
			}
		}

		$this->Available = $available;
		$this->Latitude = $latitude;
		$this->Longitude = $longitude;
		$this->Altitude = $altitude;
	}
}


/**
 * Gps element class.
 *
 * @author Edhouse s.r.o.
 * @access public
 */
class IPFGps extends IPFScreenElementBase {

	/**
	 * @access public
	 * @var string
	 */
	public $Id;


	/**
	 * @access public
	 * @var string
	 */
	public $Name;

	/**
	 * @access public
	 * @var boolean
	 */
	public $Disabled;

	/**
	 * @access public
	 * @param string $name
	 * @param string $id optional
	 * @return IPFGps
	 */
	public function __construct($name, $disabled=false, $id=null) {
		$this->Id = $id;
		$this->Name = $name;
		$this->Disabled = $disabled;
	}

	/**
	 * iPFaces library internal method. It is not designated to be used directly in code.
	 * Method create particular part of XML output
	 *
	 * @access public
	 * @return string
	 */
	public function getXML() {
		$this->doElementValidation();
		$xml = "";

		//GPS and attributes
		$xml .= "<gps ";
		$xml .= self::setAttributeIfNotEmpty("id", $this->Id);
		$xml .= self::setAttributeIfNotEmpty("name", $this->Name);
		$xml .= self::setAttribute("disabled", ($this->Disabled) ? "true" : "false");
		$xml .= "/>\n";
		return $xml;
	}

	/**
	 * @access private
	 * @return boolean
	 */
	private function doElementValidation() {
		if (!self::checkBoolean($this->Disabled)) {
			throw new Exception(sprintf("Invalid IPFGps Id: %s. Incorrect attribute Disabled: \"%s\"",$this->Id
				? "\"".$this->Id."\"" : "(Id has no value)", $this->Disabled));
		}
		$this->Disabled = self::setBoolean($this->Disabled);
	}
}




/**
 * Top level element. Class represents iPFaces form.
 * IPFForm is main class that allows to create any visuals with iPFaces and render it.
 *
 * @author Edhouse s.r.o.
 * @access public

 */
class IPFForm extends IPFElementBase{

	/**
	 * Array of IPFScreen objects.
	 * Indexed by property Id of IPFScreen.
	 *
	 * @access private
	 * @var array
	 */
	private $NestedScreens;

	/**
	 * Array of IPFHiddenField objects.
	 * Indexed by property Id of IPFHiddenField.
	 *
	 * @access private
	 * @var array
	 */
	private $NestedHiddenFields;

	/**
	 * Array of IPFEnv objects.
	 * Indexed by property Id of IPFEnv.
	 *
	 * @access private
	 * @var array
	 */
	private $NestedEnv;

	/**
	 * @access public
	 * @var string
	 */
	public $Id;

	/**
	 * @access public
	 * @var string
	 * Must be UTF-8 encoded.
	 */
	public $Title;

	/**
	 * @access public
	 * @var string
	 */
	public $StyleFile;

	/**
	 * @access public
	 * @var string
	 */
	public $Style;

	/**
	 * Constants.
	 * Do not change its values, unless you know what it is. Changing can result in client cant render generated form.
	 */

	/**
	 * Default Xml namespace URI to use in output tranfer Xml.
	 *
	 * @access public
	 */
	const IPF_XMLNS = "http://www.ipfaces.org/xsd/protocol";

	/**
	 * Character encoding of Xml output
	 */
	const XML_ENCODING = "UTF-8";

	/**
	 * Character encoding of HTML output
	 */
	const HTML_ENCODING = "UTF-8";

	/*
		 * Xml Schema instance namespace
		 */
	const IPF_XMLNS_XSI = "http://www.w3.org/2001/XMLSchema-instance";



	/**
	 * Default xsi:schemaLocation for transfer Xml format (protocol).
	 *
	 * @access public
	 */
	const IPF_XSI_SCHEMALOCATION = "http://www.ipfaces.org/xsd/protocol http://www.ipfaces.org/xsd/protocol/ipfaces-protocol_1_3.xsd";

	/**
	 * Current protocol version.
	 *
	 * @access public
	 */
	const IPF_PROTOCOL_VERSION = "1.3";

	/**
	 * Default iPFaces content-type.
	 *
	 * @access public
	 */
	const IPF_CONTENT_TYPE = "application/x-ipfaces";



	/**
	 * Constructor.
	 *
	 * @access public
	 * @param string $id optional
	 * @return IPFForm
	 **/
	public function __construct($style="", $styleFile="", $id="") {
		$this->Id = $id;
		$this->NestedScreens = array();
		$this->NestedHiddenFields = array();
		$this->NestedEnv = array();
		$this->StyleFile = $styleFile;
		$this->Style = $style;
		$this->Title = "";
	}

	/**
	 * Method create new IPFScreen element. This new instance will be added to form's instance and returned.
	 *
	 * @access public
	 * @param string $title
	 * @param string $id optional
	 * @return IPFScreen
	 */
	public function addScreen($title, $style="", $id=null) {
		if (!isset($id)) {
			$screen = new IPFScreen($title, $style);
			$this->NestedScreens[] = $screen;
			return $screen;

		} else {
			$screen = new IPFScreen($title, $style, $id);
			$this->NestedScreens[$id] = $screen;
			return $screen;
		}

	}

	/**
	 * Method create new IPFHiddenField element
	 *
	 * @access public
	 * @param string $name
	 * @param string $value
	 * @return IPFHiddenField
	 */
	public function addHiddenField($name, $value) {
		if (empty($name)) {
			//script will NOT continue after exception
			throw new Exception("Name is empty. Element type: IPFHiddenField");
		}
		$this->NestedHiddenFields[$name] = new IPFHiddenField($name, $value);
		return $this->NestedHiddenFields[$name];
	}

	/**
	 * Method create new IPFEnv element
	 *
	 * @access public
	 * @param string $name
	 * @param string $type
	 * @return IPFEnv
	 */
	public function addEnv($name, $type) {
		if (empty($name)) {
			//script will NOT continue after exception
			throw new Exception("Name is empty. Element type: IPFEnv");
		}
		$this->NestedEnv[$name] = new IPFEnv($name, $type);
		return $this->NestedEnv[$name];
	}

	/**
	 * Method returns an instance of IPFScreen
	 *
	 * @access public
	 * @param string $id
	 * @return IPFScreen
	 */
	public function getScreen($id=null) {
		if (!isset($id)) {
			if (empty($this->NestedScreens)) {
				return null;
			} else {
				$keys = array_keys($this->NestedScreens);
				return $this->NestedScreens[$keys[0]];
			}
		} else {
			return $this->NestedScreens[$id];
		}
	}

	/**
	 * Method returns an instance of IPFHiddenField
	 *
	 * @access public
	 * @param string $name
	 * @return IPFHiddenField
	 */
	public function getHiddenField($name) {
		return $this->NestedHiddenFields[$name];
	}

	/**
	 * Method returns an instance of IPFEnv
	 *
	 * @access public
	 * @param string $name
	 * @return IPFEnv
	 */
	public function getEnv($name) {
		return $this->NestedEnv[$name];
	}

	/**
	 * Method generates new response which will be sent to client.
	 * Output format is xml that iPFaces client understand to, xml format is sent only if
	 * client if recognized as iPFaces client, otherwise xml is transformed to html.
	 * Method prints xml to script's output using 'echo'.
	 *
	 * @access public
	 * @return
	 */
	public function render() {
		$this->doElementValidation();

		/**
		 * Approaches how to treat request and response headers differ
		 * if PHP is installed as an Apache module or PHP is executed as CGI script.
		 */

		$notForceXml = !isset($_GET['_ipfxml']);
		$ourContent = strpos($_SERVER["HTTP_ACCEPT"], self::IPF_CONTENT_TYPE);

		if ($ourContent === false && $notForceXml && class_exists('XSLTProcessor')){
			//XSLT transform to html
			Header("Content-Type: text/html; charset=".self::HTML_ENCODING);
			echo $this->getHTML();

		} else {
			//XML
			Header("Content-Type: ".self::IPF_CONTENT_TYPE."; charset=".self::XML_ENCODING);
			echo $this->getXML();
		}
	}

	/**
	 * Method return either XML or HTML output.
	 *
	 * @access public
	 * @param bool $useXSL If true output xml will be transformed to html format.
	 * @return string
	 */
	public function getHTML() {
		$output = $this->getXML();
		return $this->doTransformation($output);
	}

	/**
	 * Method transforms string input to HTML output via XSL file.
	 * If xsl is loaded from file, file must be present in directory that contains ipfform.php.
	 *
	 * @access private
	 * @param $output
	 * @return string
	 */
	private function doTransformation($output) {

		$xslDoc = new DOMDocument();

		global $IPF_XSL2HTML;

		if(!empty($IPF_XSL2HTML)) {
			$xslDoc->loadXML($IPF_XSL2HTML);
		} else {
			$xslDoc->load(dirname(__FILE__) . "/ipf2html.xsl");
		}
		$xmlDoc = new DOMDocument();
		$xmlDoc->loadXML($output);
		$proc = new XSLTProcessor();
		$proc->importStylesheet($xslDoc);
		return $proc->transformToXML($xmlDoc);
	}

	/**
	 * Method create final XML output for client.
	 *
	 * @access public
	 * @return string
	 */
	public function getXML() {
		$this->doElementValidation();
		$xml = "";
		//XML Header
		$xml = "<?xml version=\"1.0\" encoding=\"".self::XML_ENCODING."\"?>\n";

		// Form and attributes
		$xml .= "<form ";
		$xml .= self::setAttribute("xmlns", self::IPF_XMLNS);
		$xml .= self::setAttribute("protocolVersion", self::IPF_PROTOCOL_VERSION);
		$xml .= self::setAttribute("xmlns:xsi", self::IPF_XMLNS_XSI);
		$xml .= self::setAttribute("xsi:schemaLocation", self::IPF_XSI_SCHEMALOCATION);
		$xml .= self::setAttributeIfNotEmpty("id", $this->Id);
		$xml .= self::setAttributeIfNotEmpty("style", $this->Style);
		$xml .= self::setAttributeIfNotEmpty("styleFile", $this->StyleFile);
		$xml .= ">\n";

		//Hidden Fields
		foreach ($this->NestedHiddenFields as $hidden) {
			if (get_class($hidden) === "IPFHiddenField") {
				$xml .= $hidden->getXML();
			} else {
				throw new Exception("Array of hidden fields contains object with incorrect type (NOT IPFHiddenField)");
			}
		}

		//Env
		foreach ($this->NestedEnv as $env) {
			if (get_class($env) === "IPFEnv") {
				$xml .= $env->getXML();
			} else {
				throw new Exception("Array of env contains object with incorrect type (NOT IPFEnv)");
			}
		}

		foreach ($this->NestedScreens as $screen) {
			if (get_class($screen) === "IPFScreen") {
				$xml .= $screen->getXML();
			} else {
				throw new Exception("Array of screens contains object with incorrect type (NOT IPFScreen)");
			}
		}
		// Form Footer
		$xml .= "</form>\n";

		return $xml;
	}

	/**
	 * Validation
	 *
	 * @access private
	 * @return boolean
	 */
	private function doElementValidation() {
		//
	}

}






/**
 * Class that provides rendering of all basic elements on it.
 *
 * @author Edhouse s.r.o.
 * @access public
 */
class IPFScreen extends IPFElementBase {

	/**
	 * Array of IPFElementBase child objects.
	 * Indexed by property Id.
	 *
	 * @access private
	 * @var array
	 */
	private $NestedElements;

	/**
	 * @access public
	 * @var string
	 */
	public $Title;

	/**
	 * @access public
	 * @var string
	 */
	public $Id;

	/**
	 * @access public
	 * @var string
	 */
	public $Style;

	/**
	 * @access public
	 * @param string $title
	 * @param string $id optional
	 * @return IPFScreen
	 */
	public function __construct($title, $style="", $id=null) {
		$this->Id = $id;
		$this->Title = $title;
		$this->Style = $style;
		$this->NestedElements = array();
	}

	/**
	 * @access public
	 * @param string $name
	 * @param string $value
	 * @param string $title optional
	 * @param string $id optional
	 * @return IPFTextField
	 */
	public function addTextField($name, $value, $title="", $icon="", $style="", $readonly=false, $disabled=false, $id=null) {
		return $this->addItem(new IPFTextField($name, $value, $title, $icon, $style, $readonly, $disabled, $id));
	}

	/**
	 * @access public
	 * @param string $name
	 * @param string $value
	 * @param int $rows
	 * @param int $cols
	 * @param string $title optional
	 * @param string $id optional
	 * @return IPFTextArea
	 */
	public function addTextArea($name, $value, $title="", $icon="", $style="", $readonly=false, $disabled=false, $id=null) {
		return $this->addItem(new IPFTextArea($name, $value, $title, $icon, $style, $readonly, $disabled, $id));
	}

	/**
	 * @access public
	 * @param string $text
	 * @param string $id optional
	 * @return IPFLabel
	 */
	public function addLabel($text, $icon="", $style="", $id=null) {
		return $this->addItem(new IPFLabel($text, $icon, $style, $id));
	}

	/**
	 * @access public
	 * @param string $src
	 * @param string $id optional
	 * @return IPFImage
	 */
	public function addImage($src, $style="", $id=null) {
		return $this->addItem(new IPFImage($src, $style, $id));
	}

	/**
	 * @access public
	 * @param string $name
	 * @param string $id optional
	 * @return IPFGps
	 */
	public function addGps($name, $disabled=false, $id=null) {
		return $this->addItem(new IPFGps($name, $disabled, $id));
	}



	/**
	 * @access public
	 * @param string $name
	 * @param string $value
	 * @param string $title optional
	 * @param string $id optional
	 * @return IPFPassword
	 */
	public function addPassword($name, $value, $title="", $icon="", $style="", $readonly=false, $disabled=false, $id=null) {
		return $this->addItem(new IPFPassword($name, $value, $title, $icon, $style, $readonly, $disabled, $id));
	}

	/**
	 * @access public
	 * @param string $name
	 * @param string $value
	 * @param string $title
	 * @param string $checked optional, default value = FALSE
	 * @param string $id optional
	 * @return IPFCheckbox
	 */
	public function addCheckbox($name, $value, $title, $checked=false, $icon="", $style="", $readonly=false, $disabled=false, $id=null) {
		return $this->addItem(new IPFCheckBox($name, $value, $title, $checked, $icon, $style, $readonly, $disabled, $id));
	}

	/**
	 * @access public
	 * @param string $name
	 * @param string $value
	 * @param string $action
	 * @param string $title
	 * @param string $type optional, default value = IPFButton::BUTTON_TYPE_LINK
	 * @param string $position optional, default value = IPFButton::BUTTON_POSITION_DEFAULT
	 * @param string $id optional
	 * @return IPFButton
	 */
	public function addButton($name, $value, $title, $action, $type=IPFButton::BUTTON_TYPE_LINK, $position=IPFButton::BUTTON_POSITION_DEFAULT, $icon="", $style="", $disabled=false, $id=null) {
		return $this->addItem(new IPFButton($name, $value, $title, $action, $type, $position, $icon, $style, $disabled, $id));
	}

	/**
	 * @access public
	 * @param string $name
	 * @param string $value
	 * @param string $type optional, default value = IPFSelect::SELECT_TYPE_LIST
	 * @param string $title optional
	 * @param string $id optional
	 * @return IPFSelect
	 */
	public function addSelect($name, $value, $title="", $type=IPFSelect::SELECT_TYPE_LIST, $icon="", $style="", $readonly=false, $disabled=false, $id=null) {
		return $this->addItem(new IPFSelect($name, $value, $title, $type, $icon, $style, $readonly, $disabled, $id));
	}

	/**
	 * @access public
	 *
	 */
	public function addBarcode($name, $value, $title="", $icon="", $style="", $readonly=false, $disabled=false, $id=null, $scanTypes="", $scanTypeExt="", $scanIcon="") {
		return $this->addItem(new IPFBarcode($name, $value, $title, $icon, $style, $readonly, $disabled, $id, $scanTypes, $scanTypeExt, $scanIcon));
	}

	/**
	 * @access public
	 * @param object extends IPFElementBase $ipfElement
	 * @return object extends IPFElementBase
	 */
	public function addItem($ipfElement) {

		// allow adding only object of classes that are derived from our base class
		if (is_object($ipfElement) && get_parent_class($ipfElement)== "IPFScreenElementBase") {
			//We do not allow adding element with empty Id
			if (!isset($ipfElement->Id)) {
				$this->NestedElements[] = $ipfElement;
			} else {
				$this->NestedElements[$ipfElement->Id] = $ipfElement;
			}
			return $ipfElement;

		} else {
			throw new Exception("Element \"".print_r($ipfElement,true)."\" is NOT an element of iPFaces screen.");
		}
	}


	/**
	 * @access public
	 * @param string $id
	 * @return object extends IPFElement
	 */
	public function getElement($id) {
		return $this->NestedElements[$id];
	}

	/**
	 * Validation
	 *
	 * @access private
	 * @return boolean
	 */
	private function isElementValid() {
		return true;
	}


	/**
	 * iPFaces library internal method. It is not designated to be used directly in code.
	 * Method create Screen's part of XML output for client
	 *
	 * @access public
	 * @param DOMDocument $doc
	 * @return DOMDocument
	 */
	public function getXML() {
		$xml = "";

		if ($this->isElementValid() == true) {

			// Screen and attributes
			$xml .= "<screen ";
			$xml .= self::setAttributeIfNotEmpty("id", $this->Id);
			$xml .= self::setAttributeIfNotEmpty("title", $this->Title);
			$xml .= self::setAttributeIfNotEmpty("style", $this->Style);
			$xml .= ">\n";

			$forwardButtonAlreadyPresent = false;
			$backwardButtonAlreadyPresent = false;
			// Nested Elements
			foreach ($this->NestedElements as $element){
				if (is_object($element) && get_parent_class($element) == "IPFScreenElementBase") {
					if (get_class($element) === "IPFButton") {
						if ($element->Position == IPFButton::BUTTON_POSITION_BACK) {
							if ($backwardButtonAlreadyPresent == false) {
								$backwardButtonAlreadyPresent = true;
							} else {
								throw new Exception("Only one button with position Back is allowed within Screen");
							}

						} else if ($element->Position == IPFButton::BUTTON_POSITION_FORWARD) {
							if ($forwardButtonAlreadyPresent == false) {
								$forwardButtonAlreadyPresent = true;
							} else {
								throw new Exception("Only one button with position Forward is allowed within Screen");
							}
						}
					}

					// Each nested element return own peace of XML output
					$xml .= $element->getXML();

				} else {
					throw new Exception("Element \"".print_r($ipfElement,true)."\" is NOT an element of iPFaces screen.");
				}
			}
			// Screen End
			$xml .= "</screen>\n";

			return $xml;

		} else {
			throw new Exception("Invalid screen");
		}
	}
}




/**
 * Text Field class.
 *
 * @author Edhouse s.r.o.
 * @access public
 */
class IPFTextField extends IPFScreenElementBase{

	/**
	 * @access public
	 * @var string
	 */
	public  $Id;

	/**
	 * @access public
	 * @var string
	 */
	public $Style;

	/**
	 * @access public
	 * @var string
	 */
	public $Name;

	/**
	 * @access public
	 * @var string
	 */
	public $Value;

	/**
	 * @access public
	 * @var boolean
	 */
	public $Disabled;

	/**
	 * @access public
	 * @var boolean
	 */
	public $Readonly;

	/**
	 * @access public
	 * @var string
	 */
	public $Title;

	/**
	 * @access public
	 * @var string
	 */
	public $Icon;

	/**
	 * @access public
	 * @param string $name
	 * @param string $value
	 * @param string $title optional
	 * @param string $id optional
	 * @return IPFTextField
	 */
	public function __construct($name, $value, $title="", $icon="", $style="", $readonly=false, $disabled=false, $id=null){
		$this->Id = $id;
		$this->Name = $name;
		$this->Value = $value;
		$this->Title = $title;
		$this->Disabled = $disabled;
		$this->Readonly = $readonly;
		$this->Icon = $icon;
		$this->Style = $style;
	}

	/**
	 * iPFaces library internal method. It is not designated to be used directly in code.
	 * Method create particular part of XML output
	 *
	 * @access public
	 * @param DOMDocument $doc
	 * @return DOMDocument
	 */
	public function getXML() {
		$this->doElementValidation();
		$xml = "";

		//Textfield and attributes
		$xml .= "<textfield ";
		$xml .= self::setAttributeIfNotEmpty("id", $this->Id);
		$xml .= self::setAttributeIfNotEmpty("style", $this->Style);
		$xml .= self::setAttribute("disabled", ($this->Disabled) ? "true" : "false");
		$xml .= self::setAttributeIfNotEmpty("name", $this->Name);
		$xml .= self::setAttributeIfNotEmpty("title", $this->Title);
		$xml .= self::setAttribute("readonly", ($this->Readonly) ? "true" : "false");
		$xml .= self::setAttributeIfNotEmpty("value", $this->Value);
		$xml .= self::setAttributeIfNotEmpty("icon", $this->Icon);
		$xml .= "/>\n";
		return $xml;

	}

	/**
	 * Validation
	 *
	 * @access private
	 * @return string
	 */
	private function doElementValidation(){
		if (!self::checkBoolean($this->Disabled)) {
			throw new Exception(sprintf("Invalid IPFTextField Id: %s. Incorrect attribute Disabled: \"%s\"",$this->Id ? "\"".$this->Id."\"" : "(Id has no value)", $this->Disabled));
		}

		$this->Disabled = self::setBoolean($this->Disabled);

		if (!self::checkBoolean($this->Readonly)) {
			throw new Exception(sprintf("Invalid IPFTextField Id: %s. Incorrect attribute Readonly: \"%s\"",$this->Id ? "\"".$this->Id."\"" : "(Id has no value)", $this->Readonly));

		}
		$this->Readonly = self::setBoolean($this->Readonly);

	}
}





/**
 * Textarea class.
 *
 * @author Edhouse s.r.o.
 * @access public
 */
class IPFTextArea extends IPFScreenElementBase {

	/**
	 * @access public
	 * @var string
	 */
	public $Id;

	/**
	 * @access public
	 * @var string
	 */
	public $Style;

	/**
	 * @access public
	 * @var string
	 */
	public $Name;

	/**
	 * @access public
	 * @var string
	 */
	public $Value;

	/**
	 * @access public
	 * @var boolean
	 */
	public $Disabled;

	/**
	 * @access public
	 * @var boolean
	 */
	public $Readonly;

	/**
	 * @access public
	 * @var string
	 */
	public $Title;

	/**
	 * @access public
	 * @var string
	 */
	public $Icon;

	/**
	 * @access public
	 * @param string $name
	 * @param string $value
	 * @param string $title optional
	 * @param string $id optional
	 * @return IPFTextArea
	 */
	public function __construct($name, $value, $title="", $icon="", $style="", $readonly=false, $disabled=false, $id=null) {
		$this->Id = $id;
		$this->Name = $name;
		$this->Value = $value;
		$this->Title = $title;
		$this->Disabled = $disabled;
		$this->Readonly = $readonly;
		$this->Icon = $icon;
		$this->Style = $style;
	}


	/**
	 * iPFaces library internal method. It is not designated to be used directly in code.
	 * Method create particular part of XML output
	 *
	 * @access public
	 * @param DOMDocument $doc
	 * @return DOMDocument
	 */
	public function getXML() {
		$this->doElementValidation();
		$xml = "";

		//Textarea and attributes
		$xml .= "<textarea ";
		$xml .= self::setAttribute("disabled", ($this->Disabled) ? "true" : "false");
		$xml .= self::setAttributeIfNotEmpty("name", $this->Name);
		$xml .= self::setAttributeIfNotEmpty("title", $this->Title);
		$xml .= self::setAttributeIfNotEmpty("id", $this->Id);
		$xml .= self::setAttributeIfNotEmpty("style", $this->Style);
		$xml .= self::setAttributeIfNotEmpty("value", $this->Value);
		$xml .= self::setAttributeIfNotEmpty("icon", $this->Icon);
		$xml .= self::setAttribute("readonly", ($this->Readonly) ? "true" : "false");
		$xml .= "/>\n";
		return $xml;
	}

	/**
	 * Validation
	 *
	 * @access private
	 * @return boolean
	 */
	private function doElementValidation(){
		if (!self::checkBoolean($this->Disabled)) {
			throw new Exception(sprintf("Invalid IPFTextArea Id: %s. Incorrect attribute Disabled: \"%s\"",$this->Id ? "\"".$this->Id."\"" : "(Id has no value)", $this->Disabled));
		}

		$this->Disabled = self::setBoolean($this->Disabled);

		if (!self::checkBoolean($this->Readonly)) {
			throw new Exception(sprintf("Invalid IPFTextArea Id: %s. Incorrect attribute Readonly: \"%s\"",$this->Id ? "\"".$this->Id."\"" : "(Id has no value)", $this->Readonly));
		}

		$this->Readonly = self::setBoolean($this->Readonly);
	}
}





/**
 * Label class.
 *
 * @author Edhouse s.r.o.
 * @access public
 */
class IPFLabel extends IPFScreenElementBase {

	/**
	 * @access public
	 * @var string
	 */
	public $Id;

	/**
	 * @access public
	 * @var string
	 */
	public $Style;

	/**
	 * @access public
	 * @var string
	 */
	public $Text;

	/**
	 * @access public
	 * @var string
	 */
	public $Icon;

	/**
	 * @access public
	 * @param string $text
	 * @param string $id optional
	 * @return IPFLabel
	 */
	public function __construct($text, $icon="", $style="", $id=null) {
		$this->Id = $id;
		$this->Text = $text;
		$this->Icon = $icon;
		$this->Style = $style;
	}

	/**
	 * iPFaces library internal method. It is not designated to be used directly in code.
	 * Method create particular part of XML output
	 *
	 * @access public
	 * @param DOMDocument $doc
	 * @return DOMDocument
	 */
	public function getXML() {
		$this->doElementValidation();
		$xml = "";

		//Label and attributes
		$xml .= "<label ";
		$xml .= self::setAttribute("text", $this->Text);
		$xml .= self::setAttributeIfNotEmpty("id", $this->Id);
		$xml .= self::setAttributeIfNotEmpty("style", $this->Style);
		$xml .= self::setAttributeIfNotEmpty("icon", $this->Icon);
		$xml .= "/>\n";
		return $xml;
	}

	/**
	 * Validation
	 *
	 * @access private
	 * @return boolean
	 */
	private function doElementValidation() {

	}
}




/**
 * Image class.
 *
 * @author Edhouse s.r.o.
 * @access public
 */
class IPFImage extends IPFScreenElementBase {

	/**
	 * @access public
	 * @var string
	 */
	public $Id;

	/**
	 * @access public
	 * @var string
	 */
	public $Style;

	/**
	 * @access public
	 * @var string
	 */
	public $Src;

	/**
	 * @access public
	 * @param string $src
	 * @param string $id optional
	 * @return IPFImage
	 */
	public function __construct($src, $style="", $id=null) {
		$this->Id = $id;
		$this->Src = $src;
		$this->Style = $style;
	}

	/**
	 * iPFaces library internal method. It is not designated to be used directly in code.
	 * Method create particular part of XML output
	 *
	 * @access public
	 * @param DOMDocument $doc
	 * @return DOMDocument
	 */
	public function getXML() {
		$this->doElementValidation();
		$xml = "";

		//Image and attributes
		$xml .= "<img ";
		$xml .= self::setAttributeIfNotEmpty("id", $this->Id);
		$xml .= self::setAttributeIfNotEmpty("style", $this->Style);
		$xml .= self::setAttribute("src", $this->Src);
		$xml .= "/>\n";
		return $xml;
	}

	/**
	 * Validation
	 *
	 * @access private
	 * @return boolean
	 */
	private function doElementValidation() {

	}
}





/**
 * Password class.
 *
 * @author Edhouse s.r.o.
 * @access public
 */
class IPFPassword extends IPFScreenElementBase {

	/**
	 * @access public
	 * @var string
	 */
	public $Id;

	/**
	 * @access public
	 * @var string
	 */
	public $Style;

	/**
	 * @access public
	 * @var string
	 */
	public $Name;

	/**
	 * @access public
	 * @var string
	 */
	public $Value;

	/**
	 * @access public
	 * @var boolean
	 */
	public $Disabled;

	/**
	 * @access public
	 * @var boolean
	 */
	public $Readonly;

	/**
	 * @access public
	 * @var string
	 */
	public $Title;

	/**
	 * @access public
	 * @var string
	 */
	public $Icon;

	/**
	 * @access public
	 * @param string $name
	 * @param string $value
	 * @param string $title optional
	 * @param string $id optional
	 * @return IPFPassword
	 */
	public function __construct($name, $value, $title="", $icon="", $style="", $readonly=false, $disabled=false, $id=null) {
		$this->Id = $id;
		$this->Name = $name;
		$this->Value = $value;
		$this->Title = $title;
		$this->Disabled = $disabled;
		$this->Readonly = $readonly;
		$this->Icon = $icon;
		$this->Style = $style;
	}

	/**
	 * iPFaces library internal method. It is not designated to be used directly in code.
	 * Method create particular part of XML output
	 *
	 * @access public
	 * @param DOMDocument $doc
	 * @return DOMDocument
	 */
	public function getXML() {
		$this->doElementValidation();
		$xml = "";

		//Password and attributes
		$xml .= "<password ";
		$xml .= self::setAttributeIfNotEmpty("name", $this->Name);
		$xml .= self::setAttributeIfNotEmpty("id", $this->Id);
		$xml .= self::setAttributeIfNotEmpty("style", $this->Style);
		$xml .= self::setAttributeIfNotEmpty("title", $this->Title);
		$xml .= self::setAttributeIfNotEmpty("value", $this->Value);
		$xml .= self::setAttributeIfNotEmpty("icon", $this->Icon);
		$xml .= self::setAttribute("disabled", ($this->Disabled) ? "true" : "false");
		$xml .= self::setAttribute("readonly", ($this->Readonly) ? "true" : "false");
		$xml .= "/>\n";
		return $xml;

	}

	/**
	 * Validation
	 *
	 * @access private
	 * @return boolean
	 */
	private function doElementValidation() {
		if (!self::checkBoolean($this->Disabled)) {
			throw new Exception(sprintf("Invalid IPFPassword Id: %s. Incorrect attribute Disabled: \"%s\"",$this->Id ? "\"".$this->Id."\"" : "(Id has no value)", $this->Disabled));
		}

		$this->Disabled = self::setBoolean($this->Disabled);

		if (!self::checkBoolean($this->Readonly)) {
			throw new Exception(sprintf("Invalid IPFPassword Id: %s. Incorrect attribute Readonly: \"%s\"",$this->Id ? "\"".$this->Id."\"" : "(Id has no value)", $this->Readonly));
		}

		$this->Readonly = self::setBoolean($this->Readonly);

	}
}





/**
 * CheckBox class.
 *
 * @author Edhouse s.r.o.
 * @access public
 */
class IPFCheckBox extends IPFScreenElementBase {

	/**
	 * @access public
	 * @var string
	 */
	public $Id;

	/**
	 * @access public
	 * @var string
	 */
	public $Style;

	/**
	 * @access public
	 * @var string
	 */
	public $Name;

	/**
	 * @access public
	 * @var string
	 */
	public $Value;

	/**
	 * @access public
	 * @var boolean
	 */
	public $Disabled;

	/**
	 * @access public
	 * @var boolean
	 */
	public $Readonly;

	/**
	 * @access public
	 * @var boolean
	 */
	public $Checked;

	/**
	 * @access public
	 * @var string
	 */
	public $Title;

	/**
	 * @access public
	 * @var string
	 */
	public $Icon;


	/**
	 * @access public
	 * @param string $name
	 * @param string $value
	 * @param string $title
	 * @param bool $checked optional, default value = false
	 * @param string $id optional
	 * @return IPFCheckBox
	 */
	public function __construct($name, $value, $title, $checked=false, $icon="", $style="", $readonly=false, $disabled=false, $id=null) {
		$this->Id = $id;
		$this->Name = $name;
		$this->Value = $value;
		$this->Title = $title;
		$this->Checked = $checked;
		$this->Disabled = $disabled;
		$this->Readonly = $readonly;
		$this->Icon = $icon;
		$this->Style = $style;
	}

	/**
	 * iPFaces library internal method. It is not designated to be used directly in code.
	 * Method create particular part of XML output
	 *
	 * @access public
	 * @param DOMDocument $doc
	 * @return DOMDocument
	 */
	public function getXML() {
		$this->doElementValidation();
		$xml = "";

		//Checkbox and attributes
		$xml .= "<checkbox ";
		$xml .= self::setAttribute("checked", ($this->Checked) ? "true" : "false");
		$xml .= self::setAttributeIfNotEmpty("id", $this->Id);
		$xml .= self::setAttributeIfNotEmpty("style", $this->Style);
		$xml .= self::setAttributeIfNotEmpty("title", $this->Title);
		$xml .= self::setAttributeIfNotEmpty("name", $this->Name);
		$xml .= self::setAttribute("disabled", ($this->Disabled) ? "true" : "false");
		$xml .= self::setAttribute("readonly", ($this->Readonly) ? "true" : "false");
		$xml .= self::setAttributeIfNotEmpty("value", $this->Value);
		$xml .= self::setAttributeIfNotEmpty("icon", $this->Icon);
		$xml .= "/>\n";
		return $xml;

	}


	/**
	 * Validation
	 *
	 * @access private
	 * @return boolean
	 */
	private function doElementValidation() {
		if (!self::checkBoolean($this->Disabled)) {
			throw new Exception(sprintf("Invalid IPFCheckBox Id: %s. Incorrect attribute Disabled: \"%s\"",$this->Id ? "\"".$this->Id."\"" : "(Id has no value)", $this->Disabled));
		}

		$this->Disabled = self::setBoolean($this->Disabled);

		if (!self::checkBoolean($this->Checked)) {
			throw new Exception(sprintf("Invalid IPFChecBox Id: %s. Incorrect attribute Checked: \"%s\"",$this->Id ? "\"".$this->Id."\"" : "(Id has no value)", $this->Checked));
		}

		$this->Checked = self::setBoolean($this->Checked);

		if (!self::checkBoolean($this->Readonly)) {
			throw new Exception(sprintf("Invalid IPFCheckBox Id: %s. Incorrect attribute Readonly: \"%s\"",$this->Id ? "\"".$this->Id."\"" : "(Id has no value)", $this->Readonly));
		}

		$this->Readonly = self::setBoolean($this->Readonly);

	}
}




/**
 * Button class.
 *
 * @author Edhouse s.r.o.
 * @access public
 */
class IPFButton extends IPFScreenElementBase {

	/**
	 * @access public
	 * @var string
	 */
	public $Id;

	/**
	 * @access public
	 * @var string
	 */
	public $Style;

	/**
	 * @access public
	 * @var string
	 */
	public $Name;

	/**
	 * @access public
	 * @var string
	 */
	public $Value;

	/**
	 * @access public
	 * @var boolean
	 */
	public $Disabled;

	/**
	 * Type is defined either as "submit" or "link".
	 *
	 * Use this values:
	 * <ul>
	 * <li>BUTTON_TYPE_LINK</li>
	 * <li>BUTTON_TYPE_SUBMIT</li>
	 * </ul>
	 *
	 * @access public
	 * @var string
	 */
	public $Type;

	/**
	 * @access public
	 * @var string
	 */
	public $Action;

	/**
	 * Set this property to one of these options:
	 *
	 * <ul>
	 * <li>BUTTON_POSITION_DEFAULT</li>
	 * <li>BUTTON_POSITION_FORWARD</li>
	 * <li>BUTTON_POSITION_BACK</li>
	 * </ul>
	 *
	 * @access public
	 * @var string
	 */
	public $Position;

	/**
	 * @access public
	 * @var string
	 */
	public $Title;

	/**
	 * @access public
	 * @var string
	 */
	public $Icon;

	/**
	 * Button will be placed in form body.
	 *
	 * @access public
	 * @var string
	 */
	const BUTTON_POSITION_DEFAULT = "default";

	/**
	 * Button will be placed top right.
	 *
	 * @access public
	 * @var string
	 */
	const BUTTON_POSITION_FORWARD = "forward";

	/**
	 * Button will be placed on top left.
	 *
	 * @access public
	 * @var string
	 */
	const BUTTON_POSITION_BACK = "back";

	/**
	 * Button perfoms transition to another form without sending any data. (GET request)
	 *
	 * @access public
	 * @var string
	 */
	const BUTTON_TYPE_LINK = "link";

	/**
	 * Button sends data to form. (POST request)
	 *
	 * @access public
	 * @var string
	 */
	const BUTTON_TYPE_SUBMIT = "submit";

	/**
	 * @access public
	 * @param string $name
	 * @param string $value
	 * @param string $action
	 * @param string $title
	 * @param string $type optional, default value = IPFButton::BUTTON_TYPE_LINK
	 * @param string $position optional, default value = IPFButton::BUTTON_POSITION_DEFAULT
	 * @param string $id optional
	 * @return IPFButton
	 */
	public function __construct($name, $value, $title, $action, $type=self::BUTTON_TYPE_LINK, $position=self::BUTTON_POSITION_DEFAULT, $icon="", $style="", $disabled=false, $id=null) {
		$this->Name = $name;
		$this->Value = $value;
		$this->Action = $action;
		$this->Type = $type;
		$this->Position = $position;
		$this->Title = $title;
		$this->Disabled = $disabled;
		$this->Id = $id;
		$this->Icon = $icon;
		$this->Style = $style;
	}

	/**
	 * iPFaces library internal method. It is not designated to be used directly in code.
	 * Method create particular part of XML output
	 *
	 * @access public
	 * @param  DOMDocument $doc
	 * @return DOMDocument
	 */
	public function getXML() {
		$this->doElementValidation();
		$xml = "";

		//Button and attributes
		$xml .= "<button ";
		$xml .= self::setAttributeIfNotEmpty("action", $this->Action);
		$xml .= self::setAttributeIfNotEmpty("name", $this->Name);
		$xml .= self::setAttributeIfNotEmpty("position", $this->Position);
		$xml .= self::setAttribute("type", $this->Type);
		$xml .= self::setAttributeIfNotEmpty("id", $this->Id);
		$xml .= self::setAttributeIfNotEmpty("style", $this->Style);
		$xml .= self::setAttributeIfNotEmpty("value", $this->Value);
		$xml .= self::setAttributeIfNotEmpty("icon", $this->Icon);
		$xml .= self::setAttributeIfNotEmpty("title", $this->Title);
		$xml .= self::setAttribute("disabled", ($this->Disabled) ? "true" : "false");
		$xml .= "/>\n";
		return $xml;
	}

	/**
	 * Validation
	 *
	 * @access private
	 * @return boolean
	 */
	private function doElementValidation() {
		if (!self::checkBoolean($this->Disabled)) {
			throw new Exception(sprintf("Invalid IPFButton Id: %s. Incorrect attribute Disabled: \"%s\"",$this->Id ? "\"".$this->Id."\"" : "(Id has no value)", $this->Disabled));
		}

		$this->Disabled = self::setBoolean($this->Disabled);

		if ($this->Type != self::BUTTON_TYPE_LINK && $this->Type != self::BUTTON_TYPE_SUBMIT) {
			throw new Exception(sprintf("Invalid IPFButton Id: %s. Incorrect attribute Type: \"%s\"",$this->Id ? "\"".$this->Id."\"" : "(Id has no value)", $this->Type));
		}
		if (!empty($this->Position)) {
			if ($this->Position != self::BUTTON_POSITION_BACK && $this->Position != self::BUTTON_POSITION_DEFAULT && $this->Position != self::BUTTON_POSITION_FORWARD) {
				throw new Exception(sprintf("Invalid IPFButton Id: %s. Incorrect attribute Position: \"%s\"",$this->Id ? "\"".$this->Id."\"" : "(Id has no value)", $this->Position));
			}
		}
	}
}




/**
 * Hidden Field class.
 *
 * @author Edhouse s.r.o.
 */
class IPFHiddenField extends IPFElementBase {

	/**
	 * @access public
	 * @var string
	 */
	public $Name;

	/**
	 * @access public
	 * @var string
	 */
	public $Value;

	/**
	 * @access public
	 * @param string $name
	 * @param string $value
	 * @return IPFHiddenField
	 */
	public function __construct($name, $value) {
		$this->Name = $name;
		$this->Value = $value;
	}

	/**
	 * iPFaces library internal method. It is not designated to be used directly in code.
	 * Method create particular part of XML output
	 *
	 * @access public
	 * @param DOMDocument $doc
	 * @return DOMDocument
	 */
	public function getXML() {
		$this->doElementValidation();
		$xml = "";

		// Hidden Field and attributes
		$xml .= "<hidden ";
		$xml .= self::setAttribute("name", $this->Name);
		$xml .= self::setAttribute("value", $this->Value);
		$xml .= "/>\n";
		return $xml;
	}

	/**
	 * Validation
	 *
	 * @access private
	 * @return boolean
	 */
	private function doElementValidation() {

	}
}






/**
 * Select class.
 *
 * @author Edhouse s.r.o.
 * @access public
 */
class IPFSelect extends IPFScreenElementBase{

	/**
	 * @access public
	 * @var string
	 */
	public $Id;

	/**
	 * @access public
	 * @var string
	 */
	public $Style;

	/**
	 * Array of IPFOption objects. Keys to array are property IPFOption->Value
	 *
	 * @access private
	 * @var array
	 */
	private $Options;

	/**
	 * @access public
	 * @var string
	 */
	public $Name;

	/**
	 * @access public
	 * @var string
	 */
	public $Value;

	/**
	 * @access public
	 * @var boolean
	 */
	public $Disabled;

	/**
	 * @access public
	 * @var boolean
	 */
	public $Readonly;

	/**
	 * Set this property as one of this options:
	 *
	 * <ul>
	 * <li>SELECT_TYPE_PICKER</li>
	 * <li>SELECT_TYPE_LIST</li>
	 * </ul>
	 *
	 * @access public
	 * @var string
	 */
	public $Type;

	/**
	 * @access public
	 * @var string
	 */
	public $Title;

	/**
	 * @access public
	 * @var string
	 */
	public $Icon;

	/**
	 * Select of type picker
	 *
	 * @access public
	 * @var string
	 */
	const SELECT_TYPE_PICKER = "picker";

	/**
	 * Select of type list
	 *
	 * @access public
	 * @var string
	 */
	const SELECT_TYPE_LIST = "list";

	/**
	 * @access public
	 * @param string $name
	 * @param string $value
	 * @param string $type optional, default value = SELECT_TYPE_LIST
	 * @param string $title optional
	 * @param string $id optional
	 * @return IPFSelect
	 */
	public function __construct($name, $value, $title="", $type=self::SELECT_TYPE_LIST, $icon="", $style="", $readonly=false, $disabled=false, $id=null) {
		$this->Id = $id;
		$this->Name = $name;
		$this->Value = $value;
		$this->Type = $type;
		$this->Title = $title;
		$this->Options = array();
		$this->Disabled = $disabled;
		$this->Readonly = $readonly;
		$this->Icon = $icon;
		$this->Style = $style;
	}

	/**
	 * @access public
	 * @param string $value
	 * @param string $title optional
	 * @param string $icon optional
	 * @param string $style optional
	 * @param boolean $disabled optional
	 * @return IPFOption
	 */
	public function addOption($value, $title="", $icon="", $style="", $disabled=false) {
		$this->Options[$value] = new IPFOption($value, $title, $icon, $style, $disabled);
		return $this->Options[$value];
	}

	/**
	 * Adds array of options to Options collection.
	 * Array element's key (keys are strings) will be used as IPFOption->Value and string value as IPFOption->Title.
	 *
	 * NOTE: do not use default keys like addOptions(array("option 1", "option 2"),
	 * because in that case default keys 0, 1.. will be used.
	 *
	 * @access public
	 * @param $array string[string]
	 * @return
	 */
	public function addOptions($array) {
		foreach ($array as $key => $value) {
			if (get_class($value) == "IPFOption") {
				$this->Options[$value->Value] = $value;
			} else {
				$this->Options[$key] = new IPFOption($key, $value);
			}
		}
	}

	/**
	 * iPFaces library internal method. It is not designated to be used directly in code.
	 * Method create particular part of XML output
	 *
	 * @access public
	 * @param DOMDocument $doc
	 * @return DOMDocument
	 */
	public function getXML() {
		$this->doElementValidation();
		$xml = "";

		//Select and attributes
		$xml .= "<select ";
		$xml .= self::setAttributeIfNotEmpty("name", $this->Name);
		$xml .= self::setAttributeIfNotEmpty("id", $this->Id);
		$xml .= self::setAttributeIfNotEmpty("style", $this->Style);
		$xml .= self::setAttribute("value", $this->Value);
		$xml .= self::setAttributeIfNotEmpty("type", $this->Type);
		$xml .= self::setAttributeIfNotEmpty("icon", $this->Icon);
		$xml .= self::setAttributeIfNotEmpty("title", $this->Title);
		$xml .= self::setAttribute("disabled", ($this->Disabled) ? "true" : "false");
		$xml .= self::setAttribute("readonly", ($this->Readonly) ? "true" : "false");
		$xml .= ">\n";

		foreach ($this->Options as $option) {
			if(get_class($option) === "IPFOption") {
				$xml .= $option->getXML();

				if ($this->Type == self::SELECT_TYPE_PICKER && $option->Disabled == true) {
					throw new Exception("IPFSelect of type Picker mustn't have any disabled option. Title of disabled option: ".$option->Title." Value of disabled option: ".$option->Value);
				}
			} else {
				//just throw out object of bad type
			}
		}
		$xml .= "</select>\n";
		return $xml;
	}

	/**
	 * Validation
	 *
	 * @access private
	 * @return boolean
	 */
	private function doElementValidation() {
		if (!self::checkBoolean($this->Disabled)) {
			throw new Exception(sprintf("Invalid IPFSelect Id: %s. Incorrect attribute Disabled: \"%s\"",$this->Id ? "\"".$this->Id."\"" : "(Id has no value)", $this->Disabled));
		}

		$this->Disabled = self::setBoolean($this->Disabled);

		if (!self::checkBoolean($this->Readonly)) {
			throw new Exception(sprintf("Invalid IPFSelect Id: %s. Incorrect attribute Readonly: \"%s\"",$this->Id ? "\"".$this->Id."\"" : "(Id has no value)", $this->Readonly));
		}

		$this->Readonly = self::setBoolean($this->Readonly);

	}

	/**
	 * Method return array of options to Options collection.
	 *
	 * @access public
	 * @return IPFOption[]
	 */
	public function getOptions() {
		return $this->Options;
	}

}




/**
 * Option class.
 *
 * @author Edhouse s.r.o.
 * @access public
 */
class IPFOption extends IPFElementBase{

	/**
	 * @access public
	 * @var string
	 */
	public $Value;

	/**
	 *
	 * @access public
	 * @var boolean
	 */
	public $Disabled;

	/**
	 * @access public
	 * @var string
	 */
	public $Title;

	/**
	 * @access public
	 * @var string
	 */
	public $Style;
	/**
	 * @access public
	 * @var string
	 */
	public $Icon;

	/**
	 * @access public
	 * @param string $value
	 * @param string $title optional
	 * @param string $icon optional
	 * @param string $style optional
	 * @param boolean $disabled optional
	 * @return IPFOption
	 */
	public function __construct($value, $title="", $icon="", $style="", $disabled=false) {
		$this->Title = $title;
		$this->Value = $value;
		$this->Disabled = $disabled;
		$this->Icon = $icon;
		$this->Style = $style;
	}

	/**
	 * iPFaces library internal method. It is not designated to be used directly in code.
	 * Method create particular part of XML output
	 *
	 * @access public
	 * @param DOMDocument $doc
	 * @return DOMDocument
	 */
	public function getXML() {
		$this->doElementValidation();
		$xml = "";

		//Option and attributes
		$xml .= "<option ";
		$xml .= self::setAttribute("value", $this->Value);
		$xml .= self::setAttributeIfNotEmpty("icon", $this->Icon);
		$xml .= self::setAttributeIfNotEmpty("title", $this->Title);
		$xml .= self::setAttributeIfNotEmpty("style", $this->Style);
		$xml .= self::setAttribute("disabled", ($this->Disabled) ? "true" : "false");
		$xml .= "/>\n";
		return $xml;
	}

	/**
	 * Validation
	 *
	 * @access private
	 * @return boolean
	 */
	private function doElementValidation() {
		if (!self::checkBoolean($this->Disabled)) {
			throw new Exception(sprintf("Invalid IPFOption Title: %s. Incorrect attribute Disabled: \"%s\"",$this->Title ? "\"".$this->Id."\"" : "(Id has no value)", $this->Disabled));
		}

		$this->Disabled = self::setBoolean($this->Disabled);

	}
}




/**
 * Class for creating the type of the barcode which will be able to be scanned.
 *
 * @author Edhouse s.r.o.
 * @access public
 */
class IPFBarcodeTypes {

	/**
	 * @access public
	 * @var string
	 */
	public $ScanTypes = array();

	/**
	 * @access public
	 * @var string
	 */
	public $ScanTypesExt = array();

	/**
	 * All barcode types are allowed.
	 *
	 * @access public
	 * @var string
	 */
	const BARCODE_TYPE_ALL = "ALL";

	/**
	 * Barcode will be able to be entered manually.
	 *
	 * @access public
	 * @var string
	 */
	const BARCODE_TYPE_MANUAL = "MANUAL";

	/**
	 * Barcode of type EAN8 will be able to be scanned.
	 *
	 * @access public
	 * @var string
	 */
	const BARCODE_TYPE_EAN8 = "EAN8";

	/**
	 * Barcode of type EAN13 will be able to be scanned.
	 *
	 * @access public
	 * @var string
	 */
	const BARCODE_TYPE_EAN13 = "EAN13";

	/**
	 * Barcode of type UPCA will be able to be scanned.
	 *
	 * @access public
	 * @var string
	 */
	const BARCODE_TYPE_UPCA = "UPCA";

	/**
	 * Barcode of type UPCE will be able to be scanned.
	 *
	 * @access public
	 * @var string
	 */
	const BARCODE_TYPE_UPCE = "UPCE";

	/**
	 * Barcode of type ISBN10 will be able to be scanned.
	 *
	 * @access public
	 * @var string
	 */
	const BARCODE_TYPE_ISBN10 = "ISBN10";

	/**
	 * Barcode of type ISBN13 will be able to be scanned.
	 *
	 * @access public
	 * @var string
	 */
	const BARCODE_TYPE_ISBN13 = "ISBN13";

	/**
	 * Barcode of type I25 will be able to be scanned.
	 *
	 * @access public
	 * @var string
	 */
	const BARCODE_TYPE_I25 = "I25";

	/**
	 * Barcode of type DATABAR will be able to be scanned.
	 *
	 * @access public
	 * @var string
	 */
	const BARCODE_TYPE_DATABAR = "DATABAR";

	/**
	 * Barcode of type DATABAR_EXP will be able to be scanned.
	 *
	 * @access public
	 * @var string
	 */
	const BARCODE_TYPE_DATABAR_EXP = "DATABAR_EXP";

	/**
	 * Barcode of type CODE39 will be able to be scanned.
	 *
	 * @access public
	 * @var string
	 */
	const BARCODE_TYPE_CODE39 = "CODE39";

	/**
	 * Barcode of type CODE93 will be able to be scanned.
	 *
	 * @access public
	 * @var string
	 */
	const BARCODE_TYPE_CODE93 = "CODE93";

	/**
	 * Barcode of type CODE128 will be able to be scanned.
	 *
	 * @access public
	 * @var string
	 */
	const BARCODE_TYPE_CODE128 = "CODE128";

	/**
	 * Barcode of type PARTIAL will be able to be scanned.
	 *
	 * @access public
	 * @var string
	 */
	const BARCODE_TYPE_PARTIAL = "PARTIAL";

	/**
	 * Barcode of type PDF417 will be able to be scanned.
	 *
	 * @access public
	 * @var string
	 */
	const BARCODE_TYPE_PDF417 = "PDF417";

	/**
	 * Barcode of type QRCODE will be able to be scanned.
	 *
	 * @access public
	 * @var string
	 */
	const BARCODE_TYPE_QRCODE = "QRCODE";

	/**
	 * All barcode extended types are allowed.
	 *
	 * @access public
	 * @var string
	 */
	const BARCODE_TYPE_EXT_ALL = "ALL";

	/**
	 * Barcode of extended type EAN2 will be able to be scanned.
	 *
	 * @access public
	 * @var string
	 */
	const BARCODE_TYPE_EXT_EAN2 = "EAN2";

	/**
	 * Barcode of ectended type EAN5 will be able to be scanned.
	 *
	 * @access public
	 * @var string
	 */
	const BARCODE_TYPE_EXT_EAN5 = "EAN5";


	/**
	 * @access public
	 * @param string $scanType
	 * @return void
	 */
	public function addScanType($scanType) {
		if ($scanType != null) {
			$this->ScanTypes[] = $scanType;
		}
	}

	/**
	 * @access public
	 * @return string
	 */
	public function getScanTypesString() {
		if (!empty($this->ScanTypes)) {
			$scanTypesString = '';
			$first = true;
			foreach($this->ScanTypes as $scanType) {
				if ($first === true) {
					$first = false;
				} else {
					$scanTypesString .= ', ';
				}
				$scanTypesString .= $scanType;
			}
			return $scanTypesString;
		} else {
			return '';
		}
	}

	/**
	 * @access public
	 * @param string $scanTypeExt
	 * @return void
	 */
	public function addScanTypeExt($scanTypeExt) {
		if ($scanTypeExt != null) {
			$this->ScanTypesExt[] = $scanTypeExt;
		}
	}

	/**
	 * @access public
	 * @return string
	 */
	public function getScanTypesExtString() {
		if (!empty($this->ScanTypesExt)) {
			$scanTypesExtString = '';
			$first = true;
			foreach($this->ScanTypesExt as $scanTypeExt) {
				if ($first === true) {
					$first = false;
				} else {
					$scanTypesExtString .= ', ';
				}
				$scanTypesExtString .= $scanTypeExt;
			}
			return $scanTypesExtString;
		} else {
			return '';
		}
	}

	/**
	 * @access public
	 * @return void
	 */
	public function clearScanTypes() {
		$this->ScanTypes = array();
		$this->ScabTypesExt = array();
	}
}


/**
 * Barcode class.
 *
 * @author Edhouse s.r.o.
 * @access public
 */
class Barcode {

	/**
	 * @access public
	 * @var string
	 */
	public $Type;

	/**
	 * @access public
	 * @var string
	 */
	public $Code;

	/**
	 * @access public
	 * @param string $value
	 * @return Barcode
	 */
	public function __construct($value) {
		$array = explode(' ', $value, 2);
		$type = '';
		$code = '';
		if (!empty($array)) {
			if (count($array) == 2) {
				$type = $array[0];
				$code = $array[1];

			} else if (count($array) == 1) {
				$type = IPFBarcodeTypes::BARCODE_TYPE_MANUAL;
				$code = $array[0];
			}
		}

		$this->Type = $type;
		$this->Code = $code;
	}
}


/**
 * Barcode class.
 *
 * @author Edhouse s.r.o.
 * @access public
 */
class IPFBarcode extends IPFScreenElementBase {

	/**
	 * @access public
	 * @var string
	 */
	public $Id;

	/**
	 * @access public
	 * @var string
	 */
	public $Style;

	/**
	 * @access public
	 * @var string
	 */
	public $Name;

	/**
	 * @access public
	 * @var string
	 */
	public $Value;

	/**
	 * @access public
	 * @var boolean
	 */
	public $Disabled;

	/**
	 * @access public
	 * @var boolean
	 */
	public $Readonly;

	/**
	 * @access public
	 * @var string
	 */
	public $Title;

	/**
	 * @access public
	 * @var string
	 */
	public $Icon;

	/**
	 * @access public
	 * @var string
	 */
	public $ScanTypes;

	/**
	 * @access public
	 * @var string
	 */
	public $ScanTypesExt;

	/**
	 * @acces public
	 * @var string
	 */
	public $ScanIcon;


	/**
	 * @access public
	 * @param string $name
	 * @param string $value
	 * @param string $title optional
	 * @param string $id optional
	 * @return IPFBarcode
	 */
	public function __construct($name, $value, $title="", $icon="", $style="", $readonly=false, $disabled=false, $id=null, $scanTypes="", $scanTypeExt="", $scanIcon=""){
		$this->Id = $id;
		$this->Name = $name;
		$this->Value = $value;
		$this->Title = $title;
		$this->Disabled = $disabled;
		$this->Readonly = $readonly;
		$this->Icon = $icon;
		$this->Style = $style;
		$this->ScanTypes = $scanTypes;
		$this->ScanTypeExt = $scanTypeExt;
		$this->ScanIcon = $scanIcon;
	}

	/**
	 * iPFaces library internal method. It is not designated to be used directly in code.
	 * Method create particular part of XML output
	 *
	 * @access public
	 * @param DOMDocument $doc
	 * @return DOMDocument
	 */
	public function getXML() {
		$this->doElementValidation();
		$xml = "";

		//Barcode and attributes
		$xml .= "<barcode ";
		$xml .= self::setAttributeIfNotEmpty("id", $this->Id);
		$xml .= self::setAttributeIfNotEmpty("style", $this->Style);
		$xml .= self::setAttribute("disabled", ($this->Disabled) ? "true" : "false");
		$xml .= self::setAttributeIfNotEmpty("name", $this->Name);
		$xml .= self::setAttributeIfNotEmpty("title", $this->Title);
		$xml .= self::setAttribute("readonly", ($this->Readonly) ? "true" : "false");
		$xml .= self::setAttributeIfNotEmpty("value", $this->Value);
		$xml .= self::setAttributeIfNotEmpty("icon", $this->Icon);
		$xml .= self::setAttributeIfNotEmpty("scanTypes", $this->ScanTypes);
		$xml .= self::setAttributeIfNotEmpty("scanTypeExt", $this->ScanTypesExt);
		$xml .= self::setAttributeIfNotEmpty("scanIcon", $this->ScanIcon);
		$xml .= "/>\n";
		return $xml;

	}

	/**
	 * Validation
	 *
	 * @access private
	 * @return string
	 */
	private function doElementValidation(){
		if (!self::checkBoolean($this->Disabled)) {
			throw new Exception(sprintf("Invalid IPFBarcode Id: %s. Incorrect attribute Disabled: \"%s\"",$this->Id ? "\"".$this->Id."\"" : "(Id has no value)", $this->Disabled));
		}

		$this->Disabled = self::setBoolean($this->Disabled);

		if (!self::checkBoolean($this->Readonly)) {
			throw new Exception(sprintf("Invalid IPFBarcode Id: %s. Incorrect attribute Readonly: \"%s\"",$this->Id ? "\"".$this->Id."\"" : "(Id has no value)", $this->Readonly));

		}
		$this->Readonly = self::setBoolean($this->Readonly);

	}
}





/**
 * Env class.
 *
 * @author Edhouse s.r.o.
 */
class IPFEnv extends IPFElementBase {

	/**
	 * @access public
	 * @var string
	 */
	public $Name;

	/**
	 * @access public
	 * @var string
	 */
	public $Type;

	/**
	 * @access public
	 * @param string $name
	 * @param string $value
	 * @return IPFEnv
	 */
	public function __construct($name, $type) {
		$this->Name = $name;
		$this->Type = $type;
	}

	/**
	 * iPFaces library internal method. It is not designated to be used directly in code.
	 * Method create particular part of XML output
	 *
	 * @access public
	 * @param DOMDocument $doc
	 * @return DOMDocument
	 */
	public function getXML() {
		$this->doElementValidation();
		$xml = "";

		// Env element and attributes
		$xml .= "<env ";
		$xml .= self::setAttribute("name", $this->Name);
		$xml .= self::setAttribute("type", $this->Type);
		$xml .= "/>\n";
		return $xml;
	}

	/**
	 * Validation
	 *
	 * @access private
	 * @return boolean
	 */
	private function doElementValidation() {

	}
}






/**
 * Properties which came from the client.
 * After creating the instance of this class, in its attributes, it contains the information sent in the header from the client
 *
 * @author Edhouse s.r.o.
 * @access public

 */
class IPFClientProperties {

	/**
	 * iPFaces protocol version.
	 *
	 * @access public
	 * @var string
	 */
	public $IpfVersion;

	/**
	 * iPFaces client type (Android, iPhone, BlackBerry)
	 *
	 * @access public
	 * @var string
	 */
	public $IpfType;

	/**
	 * Device type (e.g. HTC Desire, iPhone 4 etc.).
	 *
	 * @access public
	 * @var string
	 */
	public $Device;

	/**
	 * Client OS type (Android, iPhone OS).
	 *
	 * @access public
	 * @var string
	 */
	public $OsType;

	/**
	 * Client OS version (4.2.1, 2.3.1).
	 *
	 * @access public
	 * @var string
	 */
	public $OsVersion;

	/**
	 * Client display resolution (e.g. 320x480).
	 *
	 * @access public
	 * @var string
	 */
	public $Resolution;


	/**
	 * Constructor.
	 * regular expression: iPFaces/(?<ipf_version>\d+\.\d+)/(?<ipf_type>\w+)\s\((?<device>[\w]+);\s*(?<os_type>[^;]+);\s*(?<os_version>[^;]+);\s*(?<resolution>[^\)]*)\)
	 *
	 * @access public
	 * @return IPFClientProperties
	 **/
	public function __construct() {
		$env_header = $_SERVER['HTTP_USER_AGENT'];
		$env_match = preg_match("/iPFaces\/(?P<ipf_version>\d+\.\d+)\/(?P<ipf_type>[\w\s]+)\s\((?P<device>[^;]+);\s*(?P<os_type>[^;]+);\s*(?P<os_version>[^;]+);\s*(?P<resolution>[^\)]*)\)/", $env_header, $matches);

		if (env_match !== false) {
			$this->IpfVersion = $matches['ipf_version'];
			$this->IpfType = $matches['ipf_type'];
			$this->Device = $matches['device'];
			$this->OsType = $matches['os_type'];
			$this->OsVersion = $matches['os_version'];
			$this->Resolution = $matches['resolution'];
		}
	}
}




$FLATTENFILE_TEMPLATE = <<<EOT
<?xml version="1.0" encoding="UTF-8"?>

<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform"
        xmlns:xhtml="http://www.w3.org/1999/xhtml"
        xmlns:ipf="http://www.ipfaces.org/xsd/protocol"
        exclude-result-prefixes="xhtml xsl ipf ">

    <xsl:output method="html" version="4.0" encoding="UTF-8" doctype-public="-//W3C//DTD HTML 4.01" indent="yes"/>

    <xsl:template match="ipf:form">
        <html>
            <head>
                <meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;" />
                <meta name="HandheldFriendly" content="True" />
                <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />

                <xsl:call-template name="css"/>

                <title>
                    <xsl:value-of select="@title" />
                </title>
            </head>
             <body>
                <xsl:if test="@styleFile">
                    <link rel="stylesheet" type="text/css" href="{@styleFile}">
                    </link>
                </xsl:if>
                <div class="template">
                <form method="post">
                    <xsl:apply-templates select="@*"/>
                    <xsl:apply-templates select="ipf:screen"/>

                     <xsl:if test="count(ipf:env) > 0">
                        <table width="320px" cellspacing="0">
                            <tbody>
                                <xsl:apply-templates select="ipf:env"/>
                            </tbody>
                        </table>
                     </xsl:if>

                        <xsl:for-each select="ipf:hidden" >
                        <input type="hidden" name="{@name}" value="{@value}">
                            <xsl:apply-templates/>
                        </input>
                    </xsl:for-each>
                </form>
                </div>
            </body>
        </html>
    </xsl:template>

    <xsl:template match="ipf:screen" >
        <table width="320px" cellspacing="0">
            <tbody>
                <tr>
                    <th>
                        <xsl:call-template name="navigation-button">
                            <xsl:with-param name="buttonPosition" >back</xsl:with-param>
                        </xsl:call-template>
                    </th>
                    <th>
                        <xsl:value-of select="@title"/>
                    </th>
                    <th width="80px">
                        <xsl:call-template name="navigation-button">
                            <xsl:with-param name="buttonPosition">forward</xsl:with-param>
                        </xsl:call-template>
                    </th>
                </tr>
            </tbody>
        </table>
        <table width="320px" cellspacing="0">
            <col width="40px" align="left"/>
            <col width="100%" align="left"/>
            <col width="25px" align="left"/>

            <tbody>
                <xsl:apply-templates/>
            </tbody>
        </table>
    </xsl:template>

    <xsl:template name="navigation-button">
        <xsl:param name="buttonPosition"/>
        <xsl:variable name="button" select="ipf:button[@position=\$buttonPosition]" />
        <xsl:if test="\$button">
            <xsl:choose>
                <xsl:when test="\$button/@type='submit'">
                    <input type="submit">
                        <xsl:attribute name="name">
                            <xsl:value-of select="\$button/@name" />
                        </xsl:attribute>
                        <xsl:attribute name="value">
                            <xsl:value-of select="\$button/@title" />
                        </xsl:attribute>
                        <xsl:attribute name="title">
                            <xsl:value-of select="\$button/@title" />
                        </xsl:attribute>
                        <xsl:attribute name="onclick">
                            <xsl:text>document.forms[0].action='</xsl:text>
                            <xsl:value-of select="\$button/@action" />
                            <xsl:text>';this.value='</xsl:text>
                            <xsl:value-of select="\$button/@value" />
                            <xsl:text>';</xsl:text>
                        </xsl:attribute>
                    </input>
                </xsl:when>
                <xsl:otherwise>
                    <a>
                        <xsl:attribute name="href">
                            <xsl:value-of select="\$button/@action"/>
                        </xsl:attribute>
                        <xsl:value-of select="\$button/@title"/>
                    </a>
                </xsl:otherwise>
            </xsl:choose>
        </xsl:if>
    </xsl:template>

    <xsl:template match="ipf:select">
        <tr>
            <xsl:call-template name="component-icon"/>

            <td colspan="3">
                <xsl:if test="@icon">
                    <xsl:attribute name="colspan">2</xsl:attribute>
                </xsl:if>
                <xsl:value-of select="@title"/>
                <br/>
                <select>
                    <xsl:apply-templates select="@*"/>
                    <xsl:if test="@disabled = 'true' or @readonly = 'true'">
                        <xsl:attribute name="disabled">disabled</xsl:attribute>
                    </xsl:if>
                    <xsl:apply-templates/>
                </select>
                <xsl:if test="@readonly = 'true'">
                    <input type="hidden">
                        <xsl:apply-templates select="@name"/>
                        <xsl:apply-templates select="@value"/>
                    </input>
                </xsl:if>
            </td>
        </tr>
    </xsl:template>

    <xsl:template match="ipf:option">
        <option>
            <xsl:if test="parent::*/@value = @value">
                <xsl:attribute name="selected">selected</xsl:attribute>
            </xsl:if>
            <xsl:apply-templates select="@disabled"/>
            <xsl:apply-templates select="@*"/>
            <xsl:value-of select="@title"/>
        </option>
    </xsl:template>

    <xsl:template match="ipf:label">
        <tr>
            <xsl:call-template name="component-icon"/>

            <td colspan="3">
                <xsl:if test="@icon">
                    <xsl:attribute name="colspan">2</xsl:attribute>
                </xsl:if>
                <label>
                    <xsl:apply-templates select="@*"/>
                    <xsl:call-template name="break">
                        <xsl:with-param name="text" select="@text"/>
                    </xsl:call-template>
                </label>
            </td>
        </tr>
    </xsl:template>

    <xsl:template match="ipf:textfield">
        <tr>
            <xsl:call-template name="component-icon"/>

            <td colspan="3">
                <xsl:if test="@icon">
                    <xsl:attribute name="colspan">2</xsl:attribute>
                </xsl:if>
                <xsl:value-of select="@title"/>
                <br/>
                <input type="text">
                    <xsl:apply-templates select="@*"/>
                </input>
            </td>
        </tr>
    </xsl:template>

    <xsl:template match="ipf:env">

        <xsl:variable name="envTypeSmall" select="translate(@type, \$capsLetters, \$smallCaseLetters)"/>

        <tr>
            <td colspan="3">
                <xsl:value-of select="@type"/>
                <br/>

                <xsl:choose>
                    <xsl:when test="\$envTypeSmall='ipf_type'">
                        <select name="{@name}">
                            <option value="iPhone">iPhone</option>
                            <option value="Android">Android</option>
                            <option value="Blackberry">Blackberry</option>
                            <option value="J2ME">J2ME</option>
                        </select>
                    </xsl:when>
                    <xsl:when test="\$envTypeSmall='ipf_version'">
                        <input type="text" name="{@name}" value="1.3"/>
                    </xsl:when>
                    <xsl:when test="\$envTypeSmall='gps'">
                        <input type="radio" name="{@name}" value="true" class="radioOption">Available</input>
                        <input type="radio" name="{@name}" value="false" checked="checked" class="radioOption">Not available</input>
                    </xsl:when>
                    <xsl:when test="\$envTypeSmall='barcode_scanner'">
                        <input type="radio" name="{@name}" value="none" checked="checked" class="radioOption">None</input>
                        <input type="radio" name="{@name}" value="manual" class="radioOption">Manual</input>
                        <input type="radio" name="{@name}" value="auto" class="radioOption">Auto</input>
                    </xsl:when>
                    <xsl:when test="\$envTypeSmall='locale'">
                        <input type="text" name="{@name}" value="en" />
                    </xsl:when>
                    <xsl:otherwise>
                        <input type="text" name="{@name}"/>
                    </xsl:otherwise>
                </xsl:choose>

            </td>
        </tr>
    </xsl:template>

    <xsl:template match="ipf:barcode">
        <tr>
            <xsl:call-template name="component-icon"/>

            <td colspan="2">
                <xsl:if test="@icon">
                    <xsl:attribute name="colspan">1</xsl:attribute>
                </xsl:if>
                <xsl:value-of select="@title"/>
                <br/>
                <input type="text">
                    <xsl:apply-templates select="@*"/>
                </input>
            </td>

            <td colspan="1" width="39px">
             <xsl:choose>
                 <xsl:when test="@scanIcon">
                <img class="scanIcon" src="{@scanIcon}">
                    <xsl:choose>
                        <xsl:when test = "@disabled = 'true' or @readonly = 'true'">
                            <xsl:attribute name="disabled">disabled</xsl:attribute>
                        </xsl:when>
                        <xsl:otherwise>
                            <xsl:attribute name="onclick">alert('Allowed scan types: <xsl:value-of select="@scanTypes"/>');</xsl:attribute>
                        </xsl:otherwise>
                    </xsl:choose>
                </img>
                 </xsl:when>
                 <xsl:otherwise>
                     <span class="scanIcon">
                         <xsl:choose>
                             <xsl:when test = "@disabled = 'true' or @readonly = 'true'">
                                <xsl:attribute name="disabled">disabled</xsl:attribute>
                             </xsl:when>
                             <xsl:otherwise>
                                 <xsl:attribute name="onclick">alert('Allowed scan types: <xsl:value-of select="@scanTypes"/>');</xsl:attribute>
                             </xsl:otherwise>
                         </xsl:choose>
                         ?|?|
                     </span>
                 </xsl:otherwise>
                </xsl:choose>
            </td>
        </tr>
    </xsl:template>

    <xsl:template match="ipf:gps">
        <tr>
            <td colspan="3">
                Location:&#xa0;
                <input type="text" value="49.224 17.658" style="width:70%" onchange="if (this.value.trim()=='') this.value='unknown'">
                    <xsl:apply-templates select="@name"/>
                </input>
            </td>
        </tr>
    </xsl:template>

    <xsl:template match="ipf:checkbox">
        <tr>
            <xsl:call-template name="component-icon"/>

            <label >
                <td colspan="2">
                    <xsl:if test="@icon">
                        <xsl:attribute name="colspan">1</xsl:attribute>
                    </xsl:if>

                    <xsl:apply-templates  select="@*"/>
                    <xsl:value-of select="@title"/>
                </td>
                <td>
                    <input type="checkbox">
                        <xsl:apply-templates  select="@*"/>
                        <xsl:if test="@readonly = 'true'">
                            <xsl:attribute name="onclick">return false;</xsl:attribute>
                        </xsl:if>
                    </input>
                </td>
            </label>
        </tr>
        <!-- the hidden input is appended because Spring MVC needs-->
        <xsl:if test="not(@disabled = 'true')">
            <input type="hidden" name="_{@name}" value="on"/>
        </xsl:if>
    </xsl:template>

    <xsl:template match="ipf:textarea">
        <tr>
            <xsl:call-template name="component-icon"/>

            <td colspan="3">
                <xsl:if test="@icon">
                    <xsl:attribute name="colspan">2</xsl:attribute>
                </xsl:if>
                <xsl:value-of select="@title"/>
                <br/>
                <textarea>
                    <xsl:apply-templates select="@*"/>
                    <xsl:choose>
                        <xsl:when test="@value= ''">
                            <xsl:text >&#x0A;</xsl:text>
                        </xsl:when>
                        <xsl:when test="@value">
                            <xsl:value-of select="@value" />
                        </xsl:when>
                        <xsl:otherwise>
                            <xsl:text >&#x0A;</xsl:text>
                        </xsl:otherwise>
                    </xsl:choose>
                </textarea>
            </td>
        </tr>
    </xsl:template>

    <xsl:template match="ipf:password">
        <tr>
            <xsl:call-template name="component-icon"/>

            <td colspan="3">
                <xsl:if test="@icon">
                    <xsl:attribute name="colspan">2</xsl:attribute>
                </xsl:if>
                <xsl:value-of select="@title"/>
                <br/>
                <input type="password">
                    <xsl:apply-templates select="@*"/>
                </input>
            </td>
        </tr>
    </xsl:template>
    <xsl:template match="ipf:button[not(@position) or (@position != 'back' and @position != 'forward')]">
        <tr>
            <xsl:call-template name="component-icon"/>

            <td colspan="3">
                <xsl:if test="@icon">
                    <xsl:attribute name="colspan">2</xsl:attribute>
                </xsl:if>
                <xsl:if test="@type='submit'">
                    <xsl:attribute name="style">text-align:center;font-weight:bold</xsl:attribute>
                </xsl:if>
                <xsl:apply-templates select="@style"/>
                <xsl:choose>
                    <xsl:when test="@type='submit'">
                        <input class="_ipfButtonDefaultPosition" type="submit"
                            onclick="document.forms[0].action='{@action}';this.value='{@value}';">
                            <xsl:copy-of select="@id | @name | @style"/>
                            <xsl:attribute name="value">
                                <xsl:value-of select="@title"/>
                            </xsl:attribute>
                        </input>
                    </xsl:when>
                    <xsl:otherwise>
                        <a href="{@action}">
                            <xsl:copy-of select="@id | @style"/>
                            <xsl:attribute name="type">button</xsl:attribute>
                            <xsl:value-of select="@title"/>
                            <xsl:text>&#xa0;&#xa0;&gt;</xsl:text>
                        </a>
                    </xsl:otherwise>
                </xsl:choose>
            </td>
        </tr>
    </xsl:template>

    <xsl:template match="ipf:img">
        <tr>
            <xsl:call-template name="row-style"/>
            <td colspan="3">
                <div class="image">
                    <xsl:apply-templates select="@style"/>
                    <img>
                        <xsl:apply-templates select="@*"/>
                    </img>
                </div>
            </td>
        </tr>
    </xsl:template>

    <!-- replaces each 0x0a char with html line break <br/> -->
    <xsl:template name="break">
        <xsl:param name="text"/>
        <xsl:choose>
            <xsl:when test="contains(\$text, '&#xa;')">
                <xsl:value-of select="substring-before(\$text, '&#xa;')"/>
                <br/>
                <xsl:call-template name="break">
                    <xsl:with-param name="text" select="substring-after(\$text,'&#xa;')"/>
                </xsl:call-template>
            </xsl:when>
            <xsl:otherwise>
                <xsl:value-of select="\$text"/>
            </xsl:otherwise>
        </xsl:choose>
    </xsl:template>

    <xsl:template match="@id | @name | @value | @style | @type | @src">
        <xsl:attribute name="{name()}">
            <xsl:value-of select="." />
        </xsl:attribute>
    </xsl:template>

    <xsl:template match="@*"/>

    <xsl:template match="@disabled">
        <xsl:if test=". = 'true'">
            <xsl:attribute name="disabled">disabled</xsl:attribute>
        </xsl:if>
    </xsl:template>

    <xsl:template match="@readonly">
        <xsl:if test=". = 'true'">
            <xsl:attribute name="readonly">readonly</xsl:attribute>
        </xsl:if>
    </xsl:template>

    <xsl:template match="@checked">
        <xsl:if test=". = 'true'">
            <xsl:attribute name="checked">checked</xsl:attribute>
        </xsl:if>
    </xsl:template>

    <xsl:template name="component-icon">
        <xsl:call-template name="row-style"/>
        <xsl:if test="@icon">
            <xsl:variable name="iconSrc" select="@icon"/>
            <td width="39px">
                <img class="icon" src="{\$iconSrc}" style="{@style}"/>
            </td>
        </xsl:if>
    </xsl:template>

    <xsl:template name="row-style">

        <xsl:if test="@style and contains(@style, 'background-color:')">
            <xsl:attribute name="style">
                <xsl:text>background-color:</xsl:text>
                <xsl:variable name="color" select="substring-after(@style, 'background-color:')"/>

                <xsl:choose>
                    <xsl:when test="contains(\$color, ';')">
                        <xsl:value-of select="substring-before(\$color,';')"/>
                    </xsl:when>
                    <xsl:otherwise>
                        <xsl:value-of select="\$color"/>
                    </xsl:otherwise>
                </xsl:choose>
            </xsl:attribute>
        </xsl:if>
    </xsl:template>

    <xsl:template name="css">
        <style type="text/css">


body {
background:#dcdcdc;
color:#000000;
font-family:Helvetica;
font-size:18px;
width: 320px;
margin: 3px auto;
}

th {
background:#7388A5;
border-bottom: none;
color:#FFFFFF;
font-weight:bold;
text-align:center;
min-height: 22px;
padding: 5px;
}

*[disabled] {
    color: #999999;
}

._ipfButtonDefaultPosition {
    text-decoration: none;
	color: black;
	cursor: pointer;
	margin: 2px;
	vertical-align:middle;
	background: none;
	border: none;
}

tr {
	border-collapse: collapse;
	height:35px;
}


td {
	color: black;
	padding: 3px 3px 3px 3px;
	border-style: solid none;border-color: #CCCCCC;
	border-width: 1px;
	border-collapse: collapse;
}

a {
	text-decoration: none;
	color: black;
	cursor: pointer;
	margin: 2px;
	vertical-align:middle;
	background: #d7e0fb;
}

th a {
	background: #4169e1;
	font-weight:bold;
	font-size: 13px;
	padding: 2px 5px;
	border-color: darkgray;
	border-style: outset;
	border-width: 2px;
	color:white;
	width:60px;
}

th input {
	background: #4169e1;
	font-weight:bold;
	font-size: 13px;
	padding: 2px 5px;
	border-color: darkgray;
	border-style: outset;
	border-width: 2px;
	color:white;
	width:98%;
}

input, select, textarea {
	font-size: 18px;
	margin: 2px;
	width:98%;
	vertical-align:middle;
}

table {
	width: 320px;
}

input[type=checkbox] {
	width: auto;
	margin-right:10px;
}

td input[type=button],
td input[type=submit] {
	background: #d7e0fb;
    color: black;
    cursor: pointer;
}

img {
max-width:300px;
/* IE Image max-width */
width: expression(this.width > 300 ? 300: true);
display:block;
}

img.icon {
max-width:39px;
margin: 2px;
vertical-align:middle;
/* IE Image max-width */
width: expression(this.width > 39 ? 39: true);
}

.scanIcon[disabled="disabled"] {
    cursor: default;
}

.scanIcon {
    cursor: pointer;
}

.template {
    background: white;
}

.radioOption {
 width: 1%;
 margin-left:15px;
}
        </style>
    </xsl:template>

    <xsl:variable name="capsLetters" select="'ABCDEFGHIJKLMNOPQRSTUVWXYZ'" />
    <xsl:variable name="smallCaseLetters" select="'abcdefghijklmnopqrstuvwxyz'" />

</xsl:stylesheet>
EOT;


if(isset($FLATTENFILE_TEMPLATE)){
	$IPF_XSL2HTML = & $FLATTENFILE_TEMPLATE;
}


 $ipf_form = new IPFForm("form1", "Form");
 $ipf_screen = $ipf_form->addScreen("Sunesis App", "screen1");
 $text = $_SESSION;
 $ipf_screen->addLabel($text);
 $ipf_form->render();

?>
