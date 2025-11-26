<?php
/**
 * File for class LogicMelonStructArrayOfGetValue
 * @package LogicMelon
 * @subpackage Structs
 * @date 2014-09-22
 * @author Mikaël DELSOL
 * @version 1
 */
/**
 * This class stands for LogicMelonStructArrayOfGetValue originally named ArrayOfGetValue
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://api.logicmelon.co.uk/SOAP/multiposter.asmx?WSDL}
 * @package LogicMelon
 * @subpackage Structs
 * @date 2014-09-22
 * @author Mikaël DELSOL
 * @version 1
 */
class LogicMelonStructArrayOfGetValue extends LogicMelonWsdlClass
{
    /**
     * The GetValue
     * Meta informations extracted from the WSDL
     * - maxOccurs : unbounded
     * - minOccurs : 0
     * - nillable : true
     * @var LogicMelonStructGetValue
     */
    public $GetValue;
    /**
     * Constructor method for ArrayOfGetValue
     * @see parent::__construct()
     * @param LogicMelonStructGetValue $_getValue
     * @return LogicMelonStructArrayOfGetValue
     */
    public function __construct($_getValue = NULL)
    {
        parent::__construct(array('GetValue'=>$_getValue),false);
    }
    /**
     * Get GetValue value
     * @return LogicMelonStructGetValue|null
     */
    public function getGetValue()
    {
        return $this->GetValue;
    }
    /**
     * Set GetValue value
     * @param LogicMelonStructGetValue $_getValue the GetValue
     * @return LogicMelonStructGetValue
     */
    public function setGetValue($_getValue)
    {
        return ($this->GetValue = $_getValue);
    }
    /**
     * Returns the current element
     * @see LogicMelonWsdlClass::current()
     * @return LogicMelonStructGetValue
     */
    public function current()
    {
        return parent::current();
    }
    /**
     * Returns the indexed element
     * @see LogicMelonWsdlClass::item()
     * @param int $_index
     * @return LogicMelonStructGetValue
     */
    public function item($_index)
    {
        return parent::item($_index);
    }
    /**
     * Returns the first element
     * @see LogicMelonWsdlClass::first()
     * @return LogicMelonStructGetValue
     */
    public function first()
    {
        return parent::first();
    }
    /**
     * Returns the last element
     * @see LogicMelonWsdlClass::last()
     * @return LogicMelonStructGetValue
     */
    public function last()
    {
        return parent::last();
    }
    /**
     * Returns the element at the offset
     * @see LogicMelonWsdlClass::last()
     * @param int $_offset
     * @return LogicMelonStructGetValue
     */
    public function offsetGet($_offset)
    {
        return parent::offsetGet($_offset);
    }
    /**
     * Returns the attribute name
     * @see LogicMelonWsdlClass::getAttributeName()
     * @return string GetValue
     */
    public function getAttributeName()
    {
        return 'GetValue';
    }
    /**
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see LogicMelonWsdlClass::__set_state()
     * @uses LogicMelonWsdlClass::__set_state()
     * @param array $_array the exported values
     * @return LogicMelonStructArrayOfGetValue
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
