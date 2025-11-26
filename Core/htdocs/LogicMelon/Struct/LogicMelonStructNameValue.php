<?php
/**
 * File for class LogicMelonStructNameValue
 * @package LogicMelon
 * @subpackage Structs
 * @date 2014-09-22
 * @author Mikaël DELSOL
 * @version 1
 */
/**
 * This class stands for LogicMelonStructNameValue originally named NameValue
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://api.logicmelon.co.uk/SOAP/multiposter.asmx?WSDL}
 * @package LogicMelon
 * @subpackage Structs
 * @date 2014-09-22
 * @author Mikaël DELSOL
 * @version 1
 */
class LogicMelonStructNameValue extends LogicMelonWsdlClass
{
    /**
     * The Name
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var string
     */
    public $Name;
    /**
     * The Value
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var string
     */
    public $Value;
    /**
     * The Values
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var LogicMelonStructArrayOfString
     */
    public $Values;
    /**
     * Constructor method for NameValue
     * @see parent::__construct()
     * @param string $_name
     * @param string $_value
     * @param LogicMelonStructArrayOfString $_values
     * @return LogicMelonStructNameValue
     */
    public function __construct($_name = NULL,$_value = NULL,$_values = NULL)
    {
        parent::__construct(array('Name'=>$_name,'Value'=>$_value,'Values'=>($_values instanceof LogicMelonStructArrayOfString)?$_values:new LogicMelonStructArrayOfString($_values)),false);
    }
    /**
     * Get Name value
     * @return string|null
     */
    public function getName()
    {
        return $this->Name;
    }
    /**
     * Set Name value
     * @param string $_name the Name
     * @return string
     */
    public function setName($_name)
    {
        return ($this->Name = $_name);
    }
    /**
     * Get Value value
     * @return string|null
     */
    public function getValue()
    {
        return $this->Value;
    }
    /**
     * Set Value value
     * @param string $_value the Value
     * @return string
     */
    public function setValue($_value)
    {
        return ($this->Value = $_value);
    }
    /**
     * Get Values value
     * @return LogicMelonStructArrayOfString|null
     */
    public function getValues()
    {
        return $this->Values;
    }
    /**
     * Set Values value
     * @param LogicMelonStructArrayOfString $_values the Values
     * @return LogicMelonStructArrayOfString
     */
    public function setValues($_values)
    {
        return ($this->Values = $_values);
    }
    /**
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see LogicMelonWsdlClass::__set_state()
     * @uses LogicMelonWsdlClass::__set_state()
     * @param array $_array the exported values
     * @return LogicMelonStructNameValue
     */
    public static function __set_state(array $_array,$_className = __CLASS__)
    {
        return parent::__set_state($_array,$_className);
    }
    /**
     * Method returning the class name
     * @return string __CLASS__
     */
    public function __toString()
    {
        return __CLASS__;
    }
}
