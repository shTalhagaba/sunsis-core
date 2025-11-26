<?php
/**
 * File for class LogicMelonStructArrayOfString
 * @package LogicMelon
 * @subpackage Structs
 * @date 2014-09-22
 * @author Mikaël DELSOL
 * @version 1
 */
/**
 * This class stands for LogicMelonStructArrayOfString originally named ArrayOfString
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://api.logicmelon.co.uk/SOAP/multiposter.asmx?WSDL}
 * @package LogicMelon
 * @subpackage Structs
 * @date 2014-09-22
 * @author Mikaël DELSOL
 * @version 1
 */
class LogicMelonStructArrayOfString extends LogicMelonWsdlClass
{
    /**
     * The string
     * Meta informations extracted from the WSDL
     * - maxOccurs : unbounded
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $string;
    /**
     * Constructor method for ArrayOfString
     * @see parent::__construct()
     * @param string $_string
     * @return LogicMelonStructArrayOfString
     */
    public function __construct($_string = NULL)
    {
        parent::__construct(array('string'=>$_string),false);
    }
    /**
     * Get string value
     * @return string|null
     */
    public function getString()
    {
        return $this->string;
    }
    /**
     * Set string value
     * @param string $_string the string
     * @return string
     */
    public function setString($_string)
    {
        return ($this->string = $_string);
    }
    /**
     * Returns the current element
     * @see LogicMelonWsdlClass::current()
     * @return string
     */
    public function current()
    {
        return parent::current();
    }
    /**
     * Returns the indexed element
     * @see LogicMelonWsdlClass::item()
     * @param int $_index
     * @return string
     */
    public function item($_index)
    {
        return parent::item($_index);
    }
    /**
     * Returns the first element
     * @see LogicMelonWsdlClass::first()
     * @return string
     */
    public function first()
    {
        return parent::first();
    }
    /**
     * Returns the last element
     * @see LogicMelonWsdlClass::last()
     * @return string
     */
    public function last()
    {
        return parent::last();
    }
    /**
     * Returns the element at the offset
     * @see LogicMelonWsdlClass::last()
     * @param int $_offset
     * @return string
     */
    public function offsetGet($_offset)
    {
        return parent::offsetGet($_offset);
    }
    /**
     * Returns the attribute name
     * @see LogicMelonWsdlClass::getAttributeName()
     * @return string string
     */
    public function getAttributeName()
    {
        return 'string';
    }
    /**
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see LogicMelonWsdlClass::__set_state()
     * @uses LogicMelonWsdlClass::__set_state()
     * @param array $_array the exported values
     * @return LogicMelonStructArrayOfString
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
