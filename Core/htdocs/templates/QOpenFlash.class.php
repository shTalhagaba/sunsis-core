<?php
/**
 * Basic Object
 * @package Q
 * @subpackage Q.Chart
 * @author Thomas Schäfer dipl.paed.thomas.schaefer@web.de
 */
abstract class QWorkingObject {

	const eq = "=";
	const plus = "+";
	const minus = "+";
	const hash = "#";
	const colon = ",";
	const dot = ",";
	const semicolon = ";";
	const pipe = "|";
	const ddot = ":";
	const uscore = "_";
	const blank = " ";
	const string = "";
	const amp = "&";
	const lb = "\n";
	const so = "so.addVariable";
	const ob = "(";
	const cb = ")";
	const hc = '"';
	const ap = "'";
	
	const openbrace = "[";
	const closebrace = "]";
	
	const gdata = "chd";
	const gcharttype = "cht";
	const gcharttypesize = "chts";
	const gchartformat = "chf";
	
	/**
	 * camelize string
	 *
	 * @param string $lower_case_and_underscored_word
	 * @return string
	 */
	public static function camelize($lower_case_and_underscored_word)
	{
		$replace = str_replace(self::blank, self::string, ucwords(str_replace(self::uscore, self::blank, $lower_case_and_underscored_word)));
		return $replace;
	}
	
	/**
	 * helper method
	 *
	 * @param string $value
	 * @return string
	 */
	public function cutLast($value){
		return substr($value,0,-1);
	}

	public function findContext($name, $data){
		foreach($data as $context => $contexts) {
			if(in_array($name, $contexts)){
				return $context;
			} 
		}
		return false;
	}
		
}

/**
 * Working Object
 * @package Q
 * @subpackage Q.Flash
 * @author Thomas Schäfer dipl.paed.thomas.schaefer@web.de
 *  */
class QFlashWorkingObject extends QWorkingObject {

	protected $options;
		
	/**
	 * interceptor __call
	 *
	 * @param string $funcname
	 * @param array $args
	 * @return object
	 */
	public function __call($funcname, $args = array())
	{
		$method = self::camelize(substr($funcname, 3));
		if(array_key_exists($method, $this->properties) ){
			$methodType = substr($funcname, 0, 3);
			switch ($methodType)
			{
				case "set":

					$dataType 	= $this->getOption("DataType",$method);
					$rule 		= $this->getOption("Rules",$method);
					if(is_integer($args[0]) && $dataType=="integer" && empty($rule)){
						$check = 1;
					}
					elseif(((is_float($args[0]) || is_integer($args[0]) && $dataType=="floatint")) && empty($rule)){
						$check = 1;
						$args[0] = floatval($args[0]);
					}
					elseif(is_string($args[0]) && $dataType=="urlencode"){
						$check = 1;
						$args[0] = urlencode($args[0]);
					}
					elseif(is_bool($args[0]) && $dataType=="string"){
						$check = 1;
						$args[0] = $args[0]==true?'true':'false';
					}
					elseif(is_string($args[0]) && $dataType=="string"){
						$check = 1;
					}
					elseif(is_string($args[0]) && $dataType=="hex"){
						$check = 1;
						$args[0] = str_replace(self::hash,self::string,$args[0]);
					}
					elseif(is_float($args[0]) && $dataType=="float"){
						$check = 1;
					}
					elseif(is_integer($args[0]) && $dataType=="integer"){
						$check = 1;
						$args[0] = intval($args[0]);
					}
					elseif(is_bool($args[0]) && $dataType=="boolean"){
						$check = 1;
					}
					elseif(is_string($args[0]) && $dataType=="list"){
						$list = explode(self::colon, $rule);
						if(in_array($args[0], $list)){
							$check = 1;
						}
					}
					elseif(is_array($args[0]) && $dataType=="array"){
						$check = 2;
						switch($rule){
							case "colon":
								$args[0] = implode(self::colon,$args[0]);
								break;
							case "hex":
								foreach($args[0] as $key => $value){
									$args[0][$key] = str_replace(self::hash,self::string,$value);
								}
								break;
							case "urlencode":
								foreach($args[0] as $key => $value){
									$args[0][$key] = urlencode($value);
								}
								break;
							case "shape":				
								if(is_array($args[0])){		
									foreach($args[0] as $key => $value){
										if($key == 0) {
											$args[0][$key] = $this->getOption("Shapes",$value);
										}
										elseif($key == 1) {
											$args[0][$key] = str_replace(self::hash,self::string,$value);
										}
									}
								}
								break;
							default:
								break;
						}
					}
					
					switch($check)
					{
						case 1:
							$this->properties[$method] = $args[0];
							break;
						case 2:
							$this->properties[$method][] = $args[0];
							break;
					}
					return $this;
					break;
				case "get":
					return $this->properties[$method];
			}
		} 
	}
	
	/**
	 * interceptor setter
	 *
	 * @param string $name
	 * @param mixed $value
	 */
	public function __set($name, $value)
	{
		$this->properties[$name] = $value;
	}

	/**
	 * interceptor getter
	 *
	 * @param string $name
	 * @return mixed
	 */
	public function __get($name)
	{
		return $this->properties[$name];
	}
	
	/**
	 * property
	 *
	 * @param string $offset
	 * @param string $name
	 * @return mixed
	 */
	public function getProperty($offset, $name=null){
		if($name) {
			return $this->properties[$offset][$name];
		} else {
			return $this->properties[$offset];
		}
	}
	
	/**
	 * set property
	 *
	 * @param string $offset
	 * @param mixed $value
	 * @param string $type
	 */
	public function setProperty($offset, $value, $type=null){
		if($type){
			$this->properties[$offset][$type] = $value;
		} else {
			$this->properties[$offset] = $value;			
		}
	}
	
	/**
	 * attach value to property (concatenate)
	 *
	 * @param string $offset
	 * @param mixed $value
	 * @param string $type
	 */
	public function addProperty($offset, $value, $type = null){
		if($type){
			$this->properties[$offset][$type] .= $value;
		} else {
			$this->properties[$offset] .= $value;			
		}
	}

	/**
	 * put property as new array entry
	 *
	 * @param string $offset
	 * @param mixed $value
	 * @param string $type
	 */
	public function putProperty($offset, $value, $type = null){
		if($type){
			$this->properties[$offset][$type][] = $value;
		} else {
			$this->properties[$offset][] = $value;			
		}
	}
	
	/**
	 * property
	 *
	 * @param string $offset
	 * @param string $name
	 * @return mixed
	 */
	public function getOption($offset, $name){
		return $this->options[$offset][$name];
	}

	/**
	 * properties
	 *
	 * @return array
	 */
	public function getProperties(){
		return $this->properties;
	}
	/**
	 * options
	 *
	 * @return array
	 */
	public function getOptions(){
		return $this->options;
	}
	
}
/**
 * 
 * @author Thomas Schäfer dipl.paed.thomas.schaefer@web.de
 *
 */
class QOpenFlash extends QFlashWorkingObject {
	protected $options = array(
		"PartOf" => array(
			"Object" => array( 
				"Config" => array(
					"OutputType", "JsPath", "SWFPath", "Type", 
					"Values", "JsLine", "Base", 
				),
				"Data" => array(
					"Lines", "Y2Lines", "Data", "Links", "XLabels",
					"Line", "LineDot", "DataSets", "LineHollow",
					"Pie", "PieValues", "PieColors", "PieLabels", "PieLinks",
					"AreaHollow", "Bar", "BarFilled", "BarGlass", 
					"BarSketch", "Bar3d", "BarFade",
					"Candle", "Scatter", "HLC", "Point"
					),
				"Plot" => array(
					"Width", "Height", "Title", "TitleStyle", "ToolTip",
					"Occurence", "YMin", "YMax", "XMax", "XMin", 
					"Y2Max", "Y2Min", "XOffset", "XLegend", "XLegendColor", "XLegendSize",
					"XAxisSteps", "UniqueId", "XLabelStyle", "YLabelStyle",
					"YFormat", "NumDecimals", "IsFixedNumDecimalsForced",
					"IsDecimalSeparatorComma", "IsThousandSeparatorDisabled",
					"BgColor", "BgImage", "BgImageX", "BgImageY",
					"XTickSize", "XAxisSteps", "XAxis3d", "XAxisColor",
					"YLegend", "YLegendColor", "YLegendSize", "YLegendRight", "Y2Legend", "Y2LabelStyle",
					"YSteps", "InnerBgColor", "InnerBgColor2", "InnerBgAngle",
					"XAxisColor" , "XGridColor", "YAxisColor", "YGridColor", 
					"Y2AxisColor", 
				),
			),
		),
		"DataType" => array(
			"Line" => "array", "DataSets" => "array",
			"UniqueId" => "string", "JsPath" => "string", "SWFPath" => "string", 
			"OutputType" => "list", "Width" => "integer", "Height" => "integer", 
			"Title" => "urlencode", "Base" => "string", "ToolTip" => "urlencode",
			"TitleStyle" => "string", "Data" => "array", "Type" => "string", "Values" => "string",
			"XLegend" => "urlencode", "XLegendColor"=>"string","XLegendSize"=>"integer",
			"YLegend" => "urlencode", "YLegendColor"=>"string","YLegendSize"=>"integer",
			"YLegendRight" => "string",
			"BgColor" => "string", "Y2Lines" => "array", 
			"XMax" => "floatint", "YMax" => "floatint",
			"XMin" => "floatint", "YMin" => "floatint",
			"Y2Max" => "floatint", "Y2Min" => "floatint",
			"BgImage" => "string","BgImageX" => "integer","BgImageY" => "integer",
			"YFormat" => "integer", "NumDecimals" => "integer",
			"IsFixedNumDecimalsForced" => "string",
			"IsDecimalSeparatorComma" => "string",
			"IsThousandSepatorDisabled" => "string",
			"XOffset" => "string", "XLabels" => "array",
			"Data" => "array", "Links" => "array",
			"XAxis3d" => "integer",
			"XTickSize" => "floatint", "XAxisSteps" => "floatint",
			"XAxisColor" => "string", "XGridColor" => "string",
			"YAxisColor" => "string", "YGridColor" => "string",
			"Y2AxisColor" => "string", "YSteps" => "integer" 
		),
		"Rules" => array(
			"Data" => "colon", "Links" => "colon",
			"OutputType" => "js", "XLabels" => "urlencode",
		),
		"Shapes" => array("arrow" => "a", "cross" => "c", "diamond" => "d", "spark" => "D", "circle" => "o", "square" => "x", "x" => "x", "small_vertical_line" => "v", "big_vertical_line" => "V", "horizontal_line" => "h", "horizontal_range" => "r", "vertical_range" => "R", "square" => "x"),
	
	);
	
}

/**
 * QOpenFlash Config Object
 * @author Thomas Schäfer dipl.paed.thomas.schaefer@web.de
 *
 */
class QConfigOpenFlash extends QOpenFlash {
	
	protected $objectName = "Config";
	
	protected $properties = array(
		"Base" => "js/",
		"JsPath" => "js/",
		"SWFPath" => "",
		"Type" => "line",
		"Values" => "3,#87421f",
		"JsLine" => 'so.addVariable("line","3,#87421F");',
		"OutputType" => "",	
	);
	
	public function __construct(){}
	
	protected function __clone(){}
}

/**
 * QOpenFlash DataObject
 * @author Thomas Schäfer dipl.paed.thomas.schaefer@web.de
 *
 */
class QDataOpenFlash extends QOpenFlash {
	
	protected $objectName = "Data";
	
	protected $properties = array(
		"Lines" => array(),
		"Y2Lines" => array(),
		"Data" => array(),
		"DataSets" => array(),
		"Links" => array(),
		"XLabels" => array(),
		"Pie" => "",
		"PieValues" => "",
		"PieColors" => "",
		"PieLabels" => "",
		"PieLinks" => "",
	
	);	
	
	public function __construct(){}
	
	protected function __clone(){}

	protected function nextLine(){
		$line = '';
		if( count( $this->getProperty("Lines")) > 0 ){
			$line = self::uscore . (count( $this->getProperty("Lines") ) + 1);
		}
		return $line;
		
	}
	
	
	public function setLine($width, $color='', $text='', $size=-1, $circles=-1 ){
		$type = 'line'. $this->nextLine();
		$string = self::string;
		if( $width > 0 ){
			$string .= $width . self::colon . $color;
		}
		if( strlen( $text ) > 0 ){
			$string .= self::colon . $text . self::colon . $size;
		}
		if( $circles > 0 ) {
			$string .= self::colon . $circles;
		}
		$this->setProperty("Lines", $string, $type);
		return $this;
	}
	
	public function setLineDot( $width, $dotSize, $color, $text='', $fontSize='' ){
		$this->setLineType("line_dot", $width, $dotSize, $color, $text, $fontSize);
		return $this;
	}
	
	public function setLineHollow( $width, $dotSize, $color, $text='', $fontSize='' ){
		$this->setLineType("line_hollow", $width, $dotSize, $color, $text, $fontSize);
		return $this;
	}
	
	protected function setLineType($name, $width, $dotSize, $color, $text='', $fontSize='' ){
		$type = $name . $this->nextLine();
		$string = $width . self::colon . $color. self::colon. $text;
		if( strlen( $fontSize ) > 0 ){
			$string .= self::colon . $fontSize . self::colon . $dotSize;
		}
		$this->setProperty("Lines", $string, $type);
		return $this;
	}
	
	public function setAreaHollow( $width, $dotSize, $color, $alpha, $text='', $fontSize='', $fillColor='' ){
		$type = 'area_hollow'. $this->nextLine();
		$string = $width . self::colon . $dotSize . self::colon . $color . self::colon . $alpha;
		if( strlen( $text ) > 0 ) $string .= self::colon . $text . self::colon . $fontSize;
		if( strlen( $fillColor ) > 0 ) $string .= self::colon . $fillColor;
		$this->setProperty("Lines", $string, $type);
		return $this;
	}
		
	public function setBar($alpha, $color='', $text='', $size=-1 ){
		$this->setBarType("bar", $alpha, $color, $text, $size);
		return $this;
	}

	public function setBarFilled($alpha, $color, $colorOutline, $text='', $size=-1 ){
		$this->setBarType("bar_filled", $alpha, $color, $text, $size, $colorOutline);
		return $this;
	}
	
	public function setBarSketched($alpha, $offset, $color, $colorOutline, $text='', $size=-1 ){
		$this->setBarType("bar_sketched", $alpha, $color, $text, $size, $colorOutline, $offset);
		return $this;
	}
	
	public function setBarGlass($alpha, $color, $colorOutline, $text='', $size=-1 ){
		$this->setBarType("bar_glass", $alpha, $color, $text, $size, $colorOutline);
		return $this;
	}
	
	public function setBar3d($alpha, $color, $colorOutline, $text='', $size=-1 ){
		$this->setBarType("bar_3d", $alpha, $color, $text, $size, $colorOutline);
		return $this;
	}
	
	public function setBarFade($alpha, $color, $colorOutline, $text='', $size=-1 ){
		$this->setBarType("bar_fade", $alpha, $color, $text, $size, $colorOutline);
		return $this;
	}
	
	protected function setBarType( $name, $alpha, $color='', $text='', $size=-1 , $colorOutline = false, $offset = false){
		$type = $name . $this->nextLine();
		switch($name){
			case "bar_fade":
			case "bar_3d":
			case "bar":
				$string = $alpha . self::colon . $color . self::colon . $text . self::colon . $size;
				break;
			case "bar_sketched":
				$string = $alpha . self::colon . $offset . self::colon . $color . self::colon . $colorOutline . self::colon . $text . self::colon . $size;
				break;	
			case "bar_glass":
			case "bar_filled":
				$string = $alpha . self::colon . $color . self::colon . $colorOutline . self::colon . $text . self::colon . $size;
				break;
		}
		$this->setProperty("Lines", $string, $type);
		return $this;
	}
	
	public function setCandle($data, $alpha, $lineWidth, $color , $text='', $size=-1){
		$this->setDataType("candle", $data, $lineWidth, $color, $text, $size, $alpha);
		return $this;
	}
	
	public function setHLC($data, $alpha, $lineWidth, $color , $text='', $size=-1){
		$this->setDataType("hlc", $data, $lineWidth, $color, $text, $size, $alpha);
		return $this;
	}
	
	public function setScatter($data, $lineWidth, $color , $text='', $size=-1){
		$this->setDataType("scatter", $data, $lineWidth, $color, $text, $size);
		return $this;
	}
	
	protected function setDataType($name, $data, $lineWidth, $color , $text='', $size=-1, $alpha = 0){
		$type = $name . $this->nextLine();
		switch($name){
			case "scatter":
				$string = $lineWidth . self::colon . $color . self::colon . $text . self::colon . $size;
				break;			
			default:
				$string = $alpha . self::colon . $lineWidth . self::colon . $color . self::colon . $text . self::colon . $size;
				break;
		}
		$this->setProperty("Lines", $string, $type);
		$array = array();
		foreach( $data as $object ){
			$array[] = $object->toString();
		}
		$this->putProperty("Data", implode(',',$array));
		return $this;
	}
	
	public function setPie( $alpha, $lineColor, $style, $gradient = true, $borderSize = false )
	{
		$this->setProperty("Pie", $alpha.self::colon.$lineColor.self::colon.$style);
		if( !$gradient ){
			$this->addProperty("Pie", self::colon . '0');
		}
		if ($borderSize){
			if ($gradient === false){
				$this->addProperty("Pie", self::colon);
			}
			$this->addProperty("Pie", self::colon.$borderSize);
		}
		return $this;
	}

	public function setPieValues( $values, $labels=array(), $links=array() ){
		$this->setProperty("PieValues", implode(self::colon, $values));
		$this->setProperty("PieLabels", implode(self::colon, $labels));
		$this->setProperty("PieLinks", implode(self::colon, $links));
		return $this;
	}
	
	public function setPieColors( $values ){
		$this->setProperty("PieColors", implode(self::colon, $values));
		return $this;
	}
	
	public function setDataSets(){		
		if(func_num_args() > 0){
			$args = func_get_args();
			foreach($args as $key => $arg){
				$this->putProperty("DataSets", $arg);
			}
		}
		return $this;
	}
}

/**
 * QOpenFlash Plot Object
 * @author Thomas Schäfer dipl.paed.thomas.schaefer@web.de
 *
 */
class QPlotOpenFlash extends QOpenFlash {	

	protected $objectName = "Plot";
	
	protected $properties = array(
		"Width" => 300,
		"Height" => 300,
		"YMin" => 0,
		"YMax" => 20,
		"XMin" => 0,
		"XMax" => 0,
		"XAxisSteps" => 1,
		"UniqueId" => 0,
		"YSteps" => 5,
		"Title" => "",
		"TitleStyle" => "",
		"XOffset" => "",
		"XTickSize" => -1,
		"Y2Max" => null,
		"Y2Min" => null,
		"XAxisColor" => "",
		"XAxis3d" => "",
		"XGridColor" => "",
		"YAxisColor" => "",
		"YGridColor" => "",
		"Y2AxisColor" => "",
		"XLabelStyle" => "",
		"YLabelStyle" => "",
		"YLabelStyleRight" => "",
		"XLegend" => "",
		"XLegendSize" => 20,
		"XLegendColor" => "#000000",
		"YLegend" => "",
		"YLegendSize" => "",
		"YLegendColor" => "",
		"YLegendRight" => "",
		"BgColor" => "",
		"BgImage" => "",
		"BgImageX" => 0,
		"BgImageY" => 0,
		"InnerBgColor" => "",
		"InnerBgColor2" => "",
		"InnerBgAngle" => "",
		"ToolTip" => "",
		"Y2Lines" => "",
		"YFormat" => "",
		"NumDecimals" => "",
		"IsFixedNumDecimalsForced" => "",
		"IsDecimalSeparatorComma" => "",
		"IsThousandSepatorDisabled" => "",
	);
	public function __construct(){}
	protected function __clone(){}
	
	public function setXAxisColor( $axis, $grid='' ){
		$this->setProperty("XAxisColor", $axis);
		$this->setProperty("XGridColor", $axis);
	}
	
	public function setYAxisColor( $axis, $grid='' ){
		$this->setProperty("YAxisColor", $axis);
		$this->setProperty("YGridColor", $axis);
	}
	
	
}

/**
 * QOpenFlash Object
 * @author Thomas Schäfer dipl.paed.thomas.schaefer@web.de
 *
 */
class QChartOpenFlash extends QOpenFlash {

	protected $Config;
	protected $Plot;
	protected $Data;

	/**
	 * contructor
	 */
	public function __construct(){
		
		$this->Config = new QConfigOpenFlash();
		$this->Plot = new QPlotOpenFlash();
		$this->Data = new QDataOpenFlash();
		//$this->setUniqueId(uniqid(rand(),true));
		
	}

	/**
	 * generic Chart object getter
	 *
	 * @return object
	 */
	public function Plot(){
		return $this->Plot;
	}

	/**
	 * genric data object getter
	 *
	 * @return object
	 */
	public function Data(){
		return $this->Data;
	}
	
	/**
	 * genric config object getter
	 *
	 * @return object
	 */
	public function Config(){
		return $this->Config;
	}
	
	/**
	 * interceptor __call
	 *
	 * @param string $funcname
	 * @param array $args
	 * @return object
	 */
	public function __call($funcname, $args = array())
	{
		$method = self::camelize(substr($funcname, 3));
		$methodType = substr($funcname, 0, 3);
		switch ($methodType)
		{
			case "set": 
				$context = $this->findContext($method, $this->getOption("PartOf","Object"));
				if($context || array_key_exists($funcname, array_flip(get_class_methods($this->{$objectName}() ) ))){
					$numArgs = is_array($args) ? count($args) : false;
					switch($numArgs){
						default:
						case 0: $this->{$context}()->{$funcname}(); break;
						case 1: $this->{$context}()->{$funcname}($args[0]); break;
						case 2: $this->{$context}()->{$funcname}($args[0], $args[1]); break;
						case 3: $this->{$context}()->{$funcname}($args[0], $args[1], $args[2]); break;
						case 4: $this->{$context}()->{$funcname}($args[0], $args[1], $args[2], $args[3]); break;
						case 5: $this->{$context}()->{$funcname}($args[0], $args[1], $args[2], $args[3], $args[4]); break;
						case 6: $this->{$context}()->{$funcname}($args[0], $args[1], $args[2], $args[3], $args[4], $args[5]); break;
						case 7: $this->{$context}()->{$funcname}($args[0], $args[1], $args[2], $args[3], $args[4], $args[5], $args[6]); break;
					}
					return $this;
				}
				break;
			case "get": 
				$context = $this->findContext($method, $this->getOption("PartOf","Object"));
				if($context || array_key_exists($funcname, array_flip(get_class_methods($this->{$objectName}() ) ))){
					return $this->{$context}()->{$funcname}();
				}
				break;
		}
		
	}
	
	public function setTitle( $title, $style='' ){
		$this->Plot()->setProperty("Title", urlencode($title));
		if( strlen( $style ) > 0 ){
			$this->Plot()->setProperty("TitleStyle", $style);
		}
		return $this;
	}

	public function setXLabels( $array ){
		$tmp = array();
		foreach( $array as $item ){
			$tmp[] = urlencode( $item );
		}
		$this->Data()->setProperty("XLabels", $tmp);
		return $this;
	}
	
	public function setXLabelStyle( $size, $color='', $orientation=0, $step=-1, $gridColor=''){
		$this->Plot()->setProperty("XLabelStyle", $size);
		if( strlen( $color ) > 0 ) {
			$this->Plot()->addProperty("XLabelStyle", self::colon . $color);
		}
		if( $orientation > -1 ){
			$this->Plot()->addProperty("XLabelStyle", self::colon . $orientation);
		}
		if( $step > 0 ){
			$this->Plot()->addProperty("XLabelStyle", self::colon . $step);
		}
		if( strlen( $gridColor ) > 0 ){
			$this->Plot()->addProperty("XLabelStyle", self::colon . $gridColor);
		}
		return $this;
	}
	
	public function setInnerBgColor( $col, $col2='', $angle=-1 ){
		$this->Plot()->setProperty("InnerBgColor", $col);
		if( strlen($col2) > 0 ) $this->Plot()->setProperty("InnerBgColor2", $col2);
		if( $angle != -1 ) $this->Plot()->setProperty("InnerBgAngle", $angle);
		return $this;
	}

	public function setYLabelStyle( $size, $color=''){
		$this->Plot()->setProperty("YLabelStyle", $size);
		if(strlen($color)>0) {
			$this->Plot()->addProperty("YLabelStyle", self::colon . $color);
		}
		return $this;
	}

	public function setXLegend( $text, $size=-1, $color=''){
		$this->Plot()->setProperty("XLegend", urlencode($text));
		if($size > -1) {
			$this->Plot()->addProperty("XLegendSize", $size);
		}
		if(strlen($color)>0) {
			$this->Plot()->addProperty("XLegendColor", $color);
		}
		return $this;
	}
	
	public function setYLegend( $text, $size=-1, $color=''){
		$this->Plot()->setProperty("YLegend", urlencode($text));
		if($size > -1) {
			$this->Plot()->addProperty("YLegendSize", $size);
		}
		if(strlen($color)>0) {
			$this->Plot()->addProperty("YLegendColor", $color);
		}
		return $this;
	}

	protected function formatOutput($function,$values){
		if($this->Config()->getProperty("OutputType")=="js"){
			$tmp = 'so.addVariable("'. $function .'","'. $values . '");';
		} else {
			$tmp = '&'. $function .'='. $values .'&';
		}
		return $tmp;
	}
	
	public function render(){
		$tmp = array();
		if(!headers_sent()){
			header('content-type: text; charset: utf-8');
		}
		if($this->Config()->getProperty("OutputType")=="js"){
			$this->setUniqueId();
			//$tmp[] = '<div id="' . $this->getUniqueId() . '"></div>'; // Khushnood
			$tmp[] = '<script type="text/javascript" src="' . $this->getJsPath() . 'swfobject.js"></script>';
			$tmp[] = '<script type="text/javascript">';
			$tmp[] = 'var so = new SWFObject("' . $this->getSWFPath() . 'open-flash-chart.swf", "ofc", "'. $this->getWidth() . '", "' . $this->getHeight() . '", "9", "#FFFFFF");';
			$tmp[] = 'so.addVariable("variables","true");';			
		}
		if(strlen($this->getTitle()) > 0) {
			$values = $this->getTitle();
			$values .= self::colon . $this->getTitleStyle();
			$tmp[] = $this->formatOutput('title',$values);
		}
		if( strlen( $this->getXLegend() ) > 0 ){
			$values = $this->getXLegend();
			$values .= self::colon . $this->getXLegendSize();
			$values .= self::colon . $this->getXLegendColor();
			$tmp[] = $this->formatOutput('x_legend',$values);
		}
		if( strlen( $this->getXLabelStyle() ) > 0 ) $tmp[] = $this->formatOutput('x_label_style',$this->getXLabelStyle());
		if( $this->getXTickSize() > 0 ) $tmp[] = $this->formatOutput('x_ticks',$this->getXTickSize());
		if( $this->getXAxisSteps() > 0 ) $tmp[] = $this->formatOutput('x_axis_steps',$this->getXAxisSteps());
		if( strlen( $this->getXAxis3d() ) > 0 ) $tmp[] = $this->formatOutput('x_axis_3d', $this->getXAxis3d());
		if( strlen( $this->getYLegend() ) > 0 ) $tmp[] = $this->formatOutput('y_legend', $this->getYLegend() . self::colon . $this->getYLegendSize() . self::colon . $this->getYLegendColor() );
		if( strlen( $this->getY2Legend() ) > 0 ) $tmp[] = $this->formatOutput('y2_legend',$this->getY2Legend());
		if( strlen( $this->getY2LabelStyle() ) > 0 ) $tmp[] = $this->formatOutput('y2_label_style',$this->getY2LabelStyle());

		$tmp[] = $this->formatOutput('y_ticks', '5,10,'. $this->getYSteps());

		if( count( $this->getLines() ) == 0 && count($this->getDataSets())==0 ){
			$tmp[] = $this->formatOutput($this->Config()->getProperty('Type'), $this->Config()->getProperty('Values'));	
		} else {
			foreach( $this->getLines() as $type => $string ) {
				$tmp[] = $this->formatOutput($type, $string);
			}	
		}
		
		$num = 1;
		foreach( $this->getData() as $data ){
			$tmp[] 	= ($num==1) 
					? $this->formatOutput('values', $data)
					: $this->formatOutput('values_'. $num, $data);
			$num++;
		}
		
		$num = 1;
		foreach( $this->getLinks() as $data ){
			$tmp[] 	= ($num==1) 
					? $this->formatOutput('links', $data)
					: $this->formatOutput('links_'. $num, $data);
			$num++;
		}
		
		if( count( $this->getY2Lines() ) > 0 ){
			$tmp[] = $this->formatOutput('y2_lines',implode( self::colon, $this->getY2Lines() ));
			$tmp[] = $this->formatOutput('show_y2','true');
		}
		if( count( $this->getXLabels() ) > 0 ){
			$tmp[] = $this->formatOutput('x_labels', implode(self::colon, $this->getXLabels()));
		} else {
			if( strlen($this->getXMin()) > 0 ){
				$tmp[] = $this->formatOutput('x_min',$this->getXMin());
			}
			if( strlen($this->getXMax()) > 0 ) {
				$tmp[] = $this->formatOutput('x_max',$this->getXMax());
			}			
		}
		$tmp[] = $this->formatOutput('y_min',$this->getYMin());
		$tmp[] = $this->formatOutput('y_max',$this->getYMax());
		
		if( strlen($this->getY2Min()) > 0 ){
			$tmp[] = $this->formatOutput('y2_min',$this->getY2Min());
		}
		if( strlen($this->getY2Max()) > 0 ){
			$tmp[] = $this->formatOutput('y2_max',$this->getY2Max());
		}
		
		if( strlen($this->getBgColor()) > 0 ){
			$tmp[] = $this->formatOutput('bg_colour',$this->getBgColor());
		}
		if( strlen( $this->getBgImage() ) > 0 ){
			$tmp[] = $this->formatOutput('bg_image',$this->getBgImage());
			$tmp[] = $this->formatOutput('bg_image_x',$this->getBgImageX());
			$tmp[] = $this->formatOutput('bg_image_y',$this->getBgImageY());
		}
		if( strlen( $this->getXAxisColor()) > 0 ){
			$tmp[] = $this->formatOutput('x_axis_colour',$this->getXAxisColor());
			$tmp[] = $this->formatOutput('x_grid_colour',$this->getXGridColor());
		}
		if( strlen($this->getYAxisColor()) > 0 ){
			$tmp[] = $this->formatOutput('y_axis_colour',$this->getYAxisColor());
		}
		if( strlen($this->getYGridColor()) > 0 ){
			$tmp[] = $this->formatOutput('y_grid_colour',$this->getYGridColor());
		}
		if( strlen($this->getY2AxisColor()) > 0 ){
			$tmp[] = $this->formatOutput('y2_axis_colour',$this->getY2AxisColor());
		}
		if( strlen($this->getXOffset()) > 0 ){
			$tmp[] = $this->formatOutput('x_offset',$this->getXOffset());
		}
		

		if( strlen( $this->getInnerBgColor())>0){
			$values = $this->getInnerBgColor();
			if( strlen( $this->getInnerBgColor2() ) > 0 )
			{
				$values .= self::colon. $this->getInnerBgColor();
				$values .= self::colon. $this->getInnerBgAngle();
			}
			$tmp[] = $this->formatOutput('inner_background',$values);
		}
	
		if( strlen( $this->getPie() ) > 0 ){
			$tmp[] = $this->formatOutput('pie',$this->getPie());
			$tmp[] = $this->formatOutput('values',$this->getPieValues());
			$tmp[] = $this->formatOutput('pie_labels',$this->getPieLabels());
			$tmp[] = $this->formatOutput('colours',$this->getPieColors());
			$tmp[] = $this->formatOutput('links',$this->getPieLinks());
		}

		if( strlen( $this->getToolTip() ) > 0 ){
			$tmp[] = $this->formatOutput('tool_tip',$this->getToolTip());
		}
		
		if( strlen( $this->getYFormat() ) > 0 ){
			$tmp[] = $this->formatOutput('y_format',$this->getYFormat());
		}
		if( strlen( $this->getNumDecimals() ) > 0 ){
			$tmp[] = $this->formatOutput('num_decimals',$this->getNumDecimals());
		}
		if( strlen( $this->getIsFixedNumDecimalsForced() ) > 0 ){
			$tmp[] = $this->formatOutput('is_fixed_num_decimals_forced',$this->getIsFixedNumDecimalsForced());
		}
		if( strlen( $this->getIsDecimalSeparatorComma() ) > 0 ){
			$tmp[] = $this->formatOutput('is_decimal_separator_comma',$this->getIsDecimalSeparatorComma());
		}
		if( strlen( $this->getIsThousandSeparatorDisabled() ) > 0 ){
			$tmp[] = $this->formatOutput('is_thousand_separator_disabled',$this->getIsThousandSeparatorDisabled());
		}

		$count = 1;
		foreach( $this->getDataSets() as $set ){
			if(is_object($set)){
				$tmp[] = $set->render( $this->getOutputType(), ($count>1?'_'.$count:'') );
				$count++;
			}
		}
		
		if($this->getOutputType() == 'js'){
			$tmp[] = 'so.write("' . $this->getUniqueId() . '");';
			$tmp[] = '</script>';
		}
		
		return implode(self::lb, $tmp);
	}
}

/**
 * Plot types 
 * @author Thomas Schäfer dipl.paed.thomas.schaefer@web.de
 *
 */
class QPlotType extends QOpenFlash {
	
	protected static $increment = 0;
	
	protected $properties = array(
		"LineWidth" => 1,
		"Type" => "",
		"Color" => "#000000",
		"Size" => 0,
		"Data" => array(),
		"Links" => array(),
		"Tips" => array(),
		"Key" => array(),
	);	
	
	public function __construct(){
		switch(func_num_args()){
			case 2:
				$args = func_get_args();
				$this->setProperty("LineWidth", $args[0]);
				$this->setProperty("Color", $args[1]);
			break;
			case 4:				
				$args = func_get_args();
				$this->setProperty("Type", $args[0]);
				$this->setProperty("LineWidth", $args[1]);
				$this->setProperty("Color", $args[2]);
				$this->setProperty("Size", $args[3]);
			break;
		}
	}

	public function add(){
		$args = func_get_args();
		if(func_num_args()){
			switch($args[0]){
				case "Key":
					switch(func_num_args()){
						case 3:
							$this->setProperty("Key", urlencode($args[1]), "Name");
							$this->setProperty("Key", $args[2], "Size");
						break;
					}
					break;
				case "Tips":
				case "Links":
					switch(func_num_args()){
						case 3:
							$this->putProperty("Data", $args[1]);
							$this->putProperty($args[0], urlencode($args[2]));
						break;
					}
					break;
				default:
					switch(func_num_args()){
						case 1:
							$this->putProperty("Data", $args[0]);
						break;
						case 3:							
							$this->putProperty("Data", $args[0]);
							$this->putProperty("Links", urlencode($args[1]));
							$this->putProperty("Tips", urlencode($args[2]));
						break;
					}
					break;
			}
		}	
		return $this;
	}

	public function render($outputType=null){
		
		$inc = (self::$increment>0?self::uscore.self::$increment:'');
		$values = implode(self::colon, $this->fetchList());
		$tmp = array();
		switch($output_type){
			case "js":
				$tmp[] = self::so . self::ob . self::hc . $this->getProperty("Type") . $inc . self::hc . self::colon . self::hc . $values . self::hc . self::cb . self::semicolon;
				$tmp[] = self::so . self::ob . self::hc . 'values' . $inc . self::hc . self::colon . self::hc . (implode(self::colon, $this->getProperty("Data"))) . self::hc . self::cb . self::semicolon;
				if(count($this->getProperty("Links"))>0){
					$tmp[] = self::so . self::ob . self::hc . 'links' . $inc . self::hc . self::colon . self::hc . (implode(self::colon, $this->getProperty("Links"))) . self::hc . self::cb . self::semicolon;			
				}
				if(count($this->getProperty("Tips"))>0){
					$tmp[] = self::so . self::ob . self::hc . 'tool_tips_set' . $inc . self::hc . self::colon . self::hc . (implode(self::colon, $this->getProperty("Tips"))) . self::hc . self::cb . self::semicolon;			
				}
				break;
			default:				
				$tmp[] = self::amp . $this->getProperty("Type") . $inc . self::eq . $values . self::amp;
				$tmp[] = self::amp . 'values' . $inc . self::eq . implode(self::colon, $this->getProperty("Data")) . self::amp;
				if(count($this->getProperty("Links"))>0){
					$tmp[] = self::amp . "links" . $inc. self::eq . (implode(self::colon, $this->getProperty("Links"))) . self::amp;
				}
				if(count($this->getProperty("Tips"))>0){
					$tmp[] = self::amp . "tool_tips_set" . $inc . self::eq . (implode(self::colon, $this->getProperty("Tips"))) . self::amp;
				}
				break;
		}
		self::$increment++;
		return implode( self::lb, $tmp );
	}

}
/**
 * @author Thomas Schäfer dipl.paed.thomas.schaefer@web.de
 */
class Line extends QPlotType {
	
	protected $properties = array(
		"LineWidth" => 1,
		"Type" => "line",
		"Color" => "#000000",
		"Size" => 0,
		"Data" => array(),
		"Links" => array(),
		"Tips" => array(),
		"Key" => array(),
	);
	
	protected function fetchList(){
		$values = array();
		$values[] = $this->getProperty("LineWidth");
		$values[] = $this->getProperty("Color");
		if(count($this->getProperty("Key"))>0){
			$values[] = $this->getProperty("Key","Name");
			$values[] = $this->getProperty("Key","Size");
		}
		return $values;
	}
}

/**
 * @author Thomas Schäfer dipl.paed.thomas.schaefer@web.de
 */
class LineHollow extends Line {
	
	protected $properties = array(
		"LineWidth" => 1,
		"Type" => "hollow",
		"Color" => "#000000",
		"Size" => 0,
		"Data" => array(),
		"Links" => array(),
		"Tips" => array(),
		"Key" => array(),
	);
	
	protected function fetchList(){
		$values = array();
		$values[] = $this->getProperty("LineWidth");
		$values[] = $this->getProperty("Color");
		if(count($this->getProperty("Key"))>0){
			$values[] = $this->getProperty("Key","Name");
			$values[] = $this->getProperty("Key","Size");
		} else {
			$value[] = '';
			$value[] = '';
		}
		$values[] = $this->getProperty("Size");
		return $values;
	}
}

/**
 * @author Thomas Schäfer dipl.paed.thomas.schaefer@web.de
 */
class LineDot extends LineHollow {
	protected $properties = array(
		"LineWidth" => 1,
		"Type" => "dot",
		"Color" => "#000000",
		"Size" => 0,
		"Data" => array(),
		"Links" => array(),
		"Tips" => array(),
		"Key" => array(),
	);
}

/**
 * @author Thomas Schäfer dipl.paed.thomas.schaefer@web.de
 */
class Bar extends QPlotType {
	
	protected $properties = array(
		"Alpha" => "",
		"Type" => "bar",
		"Color" => "#000000",
		"Data" => array(),
		"Links" => array(),
		"Tips" => array(),
		"Key" => array(),
	);
	
	public function __construct(){
		switch(func_num_args()){
			case 2:
				$args = func_get_args();
				$this->setProperty("Alpha", $args[0]);
				$this->setProperty("Color", $args[1]);				
			break;
			case 3:
				$args = func_get_args();
				$this->setProperty("Alpha", $args[0]);
				$this->setProperty("Color", $args[1]);				
				$this->setProperty("OutlineColor", $args[2]);				
			break;
			case 4:
				$args = func_get_args();
				switch(get_class($this)){
					case "BarSketch":
						$this->setProperty("Alpha", $args[0]);
						$this->setProperty("Offset", $args[1]);				
						$this->setProperty("Color", $args[2]);				
						$this->setProperty("OutlineColor", $args[3]);
						break;
					default:
						
						break;
				}				
			break;
		}
	}

	protected function fetchList(){
		$values = array();
		$values[] = $this->getProperty("Alpha");
		$values[] = $this->getProperty("Color");
		if(count($this->getProperty("Key"))>0){
			$values[] = $this->getProperty("Key","Name");
			$values[] = $this->getProperty("Key","Size");
		}
		return $values;
	}
	
}

/**
 * @author Thomas Schäfer dipl.paed.thomas.schaefer@web.de
 */
class Bar3D extends Bar {
	protected $properties = array(
		"Alpha" => "",
		"Type" => "bar_3d",
		"Color" => "#000000",
		"Data" => array(),
		"Links" => array(),
		"Tips" => array(),
		"Key" => array(),
	);
}


/**
 * @author Thomas Schäfer dipl.paed.thomas.schaefer@web.de
 */
class BarFade extends Bar {
	protected $properties = array(
		"Alpha" => "",
		"Type" => "bar_fade",
		"Color" => "#000000",
		"Data" => array(),
		"Links" => array(),
		"Tips" => array(),
		"Key" => array(),
	);
}

/**
 * @author Thomas Schäfer dipl.paed.thomas.schaefer@web.de
 */
class BarOutline extends Bar {
	protected $properties = array(
		"Alpha" => "",
		"Type" => "bar_outline",
		"Color" => "#000000",
		"OutlineColor" => "#000000",
		"Data" => array(),
		"Links" => array(),
		"Tips" => array(),
		"Key" => array(),
	);

	protected function fetchList(){
		$values = array();
		$values[] = $this->getProperty("Alpha");
		$values[] = $this->getProperty("Color");
		$values[] = $this->getProperty("OutlineColor");
		if(count($this->getProperty("Key"))>0){
			$values[] = $this->getProperty("Key","Name");
			$values[] = $this->getProperty("Key","Size");
		}
		return $values;
	}	
}

/**
 * @author Thomas Schäfer dipl.paed.thomas.schaefer@web.de
 */
class BarGlass extends BarOutline {
	protected $properties = array(
		"Alpha" => "",
		"Type" => "bar_glass",
		"Color" => "#000000",
		"OutlineColor" => "#000000",
		"Data" => array(),
		"Links" => array(),
		"Tips" => array(),
		"Key" => array(),
	);
}

/**
 * @author Thomas Schäfer dipl.paed.thomas.schaefer@web.de
 */
class BarSketch extends Bar {
	protected $properties = array(
		"Alpha" => "",
		"Type" => "bar_sketch",
		"Color" => "#000000",
		"Offset" => 0,
		"OutlineColor" => "#000000",
		"Data" => array(),
		"Links" => array(),
		"Tips" => array(),
		"Key" => array(),
	);

	protected function fetchList(){
		$values = array();
		$values[] = $this->getProperty("Alpha");
		$values[] = $this->getProperty("Offset");
		$values[] = $this->getProperty("Color");
		$values[] = $this->getProperty("OutlineColor");
		if(count($this->getProperty("Key"))>0){
			$values[] = $this->getProperty("Key","Name");
			$values[] = $this->getProperty("Key","Size");
		}
		return $values;
	}	
}

/**
 * @author Thomas Schäfer dipl.paed.thomas.schaefer@web.de
 */
class QComplexPlotOpenFlash extends QOpenFlash {
	protected $data = array();
	public function __construct(){
		if(func_num_args()>0){
			$args = func_get_args();
			foreach($args as $argument){
				$this->data[] = $argument;
			}
		}
	}
	
	public function toString(){
		return self::openbrace . implode(self::colon, $this->data) . self::closebrace;
	}
}

/**
 * @author Thomas Schäfer dipl.paed.thomas.schaefer@web.de
 */
class Candle extends QComplexPlotOpenFlash {}

/**
 * @author Thomas Schäfer dipl.paed.thomas.schaefer@web.de
 */
class Point extends QComplexPlotOpenFlash {}

/**
 * @author Thomas Schäfer dipl.paed.thomas.schaefer@web.de
 */
class HLC extends QComplexPlotOpenFlash {}

?>