<?php
/**
 * File for class LogicMelonStructArrayOfNameValue
 * @package LogicMelon
 * @subpackage Structs
 * @date 2014-09-22
 * @author Mikaël DELSOL
 * @version 1
 */
/**
 * This class stands for LogicMelonStructArrayOfNameValue originally named ArrayOfNameValue
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://api.logicmelon.co.uk/SOAP/multiposter.asmx?WSDL}
 * @package LogicMelon
 * @subpackage Structs
 * @date 2014-09-22
 * @author Mikaël DELSOL
 * @version 1
 */
class LogicMelonStructArrayOfNameValue extends LogicMelonWsdlClass
{
    /**
     * The NameValue
     * Meta informations extracted from the WSDL
     * - maxOccurs : unbounded
     * - minOccurs : 0
     * - nillable : true
     * @var LogicMelonStructNameValue
     */
    public $NameValue;
    /**
     * Constructor method for ArrayOfNameValue
     * @see parent::__construct()
     * @param LogicMelonStructNameValue $_nameValue
     * @return LogicMelonStructArrayOfNameValue
     */
    public function __construct($_nameValue = NULL)
    {
        parent::__construct(array('NameValue'=>$_nameValue),false);
    }
    /**
     * Get NameValue value
     * @return LogicMelonStructNameValue|null
     */
    public function getNameValue()
    {
        return $this->NameValue;
    }
    /**
     * Set NameValue value
     * @param LogicMelonStructNameValue $_nameValue the NameValue
     * @return LogicMelonStructNameValue
     */
    public function setNameValue($_nameValue)
    {
        return ($this->NameValue = $_nameValue);
    }
    /**
     * Returns the current element
     * @see LogicMelonWsdlClass::current()
     * @return LogicMelonStructNameValue
     */
    public function current()
    {
        return parent::current();
    }
    /**
     * Returns the indexed element
     * @see LogicMelonWsdlClass::item()
     * @param int $_index
     * @return LogicMelonStructNameValue
     */
    public function item($_index)
    {
        return parent::item($_index);
    }
    /**
     * Returns the first element
     * @see LogicMelonWsdlClass::first()
     * @return LogicMelonStructNameValue
     */
    public function first()
    {
        return parent::first();
    }
    /**
     * Returns the last element
     * @see LogicMelonWsdlClass::last()
     * @return LogicMelonStructNameValue
     */
    public function last()
    {
        return parent::last();
    }
    /**
     * Returns the element at the offset
     * @see LogicMelonWsdlClass::last()
     * @param int $_offset
     * @return LogicMelonStructNameValue
     */
    public function offsetGet($_offset)
    {
        return parent::offsetGet($_offset);
    }
    /**
     * Returns the attribute name
     * @see LogicMelonWsdlClass::getAttributeName()
     * @return string NameValue
     */
    public function getAttributeName()
    {
        return 'NameValue';
    }
    /**
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see LogicMelonWsdlClass::__set_state()
     * @uses LogicMelonWsdlClass::__set_state()
     * @param array $_array the exported values
     * @return LogicMelonStructArrayOfNameValue
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
