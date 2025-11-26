<?php
/**
 * File for class LogicMelonStructArrayOfAPIAdvertValue
 * @package LogicMelon
 * @subpackage Structs
 * @date 2014-09-22
 * @author Mikaël DELSOL
 * @version 1
 */
/**
 * This class stands for LogicMelonStructArrayOfAPIAdvertValue originally named ArrayOfAPIAdvertValue
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://api.logicmelon.co.uk/SOAP/multiposter.asmx?WSDL}
 * @package LogicMelon
 * @subpackage Structs
 * @date 2014-09-22
 * @author Mikaël DELSOL
 * @version 1
 */
class LogicMelonStructArrayOfAPIAdvertValue extends LogicMelonWsdlClass
{
    /**
     * The APIAdvertValue
     * Meta informations extracted from the WSDL
     * - maxOccurs : unbounded
     * - minOccurs : 0
     * - nillable : true
     * @var LogicMelonStructAPIAdvertValue
     */
    public $APIAdvertValue;
    /**
     * Constructor method for ArrayOfAPIAdvertValue
     * @see parent::__construct()
     * @param LogicMelonStructAPIAdvertValue $_aPIAdvertValue
     * @return LogicMelonStructArrayOfAPIAdvertValue
     */
    public function __construct($_aPIAdvertValue = NULL)
    {
        parent::__construct(array('APIAdvertValue'=>$_aPIAdvertValue),false);
    }
    /**
     * Get APIAdvertValue value
     * @return LogicMelonStructAPIAdvertValue|null
     */
    public function getAPIAdvertValue()
    {
        return $this->APIAdvertValue;
    }
    /**
     * Set APIAdvertValue value
     * @param LogicMelonStructAPIAdvertValue $_aPIAdvertValue the APIAdvertValue
     * @return LogicMelonStructAPIAdvertValue
     */
    public function setAPIAdvertValue($_aPIAdvertValue)
    {
        return ($this->APIAdvertValue = $_aPIAdvertValue);
    }
    /**
     * Returns the current element
     * @see LogicMelonWsdlClass::current()
     * @return LogicMelonStructAPIAdvertValue
     */
    public function current()
    {
        return parent::current();
    }
    /**
     * Returns the indexed element
     * @see LogicMelonWsdlClass::item()
     * @param int $_index
     * @return LogicMelonStructAPIAdvertValue
     */
    public function item($_index)
    {
        return parent::item($_index);
    }
    /**
     * Returns the first element
     * @see LogicMelonWsdlClass::first()
     * @return LogicMelonStructAPIAdvertValue
     */
    public function first()
    {
        return parent::first();
    }
    /**
     * Returns the last element
     * @see LogicMelonWsdlClass::last()
     * @return LogicMelonStructAPIAdvertValue
     */
    public function last()
    {
        return parent::last();
    }
    /**
     * Returns the element at the offset
     * @see LogicMelonWsdlClass::last()
     * @param int $_offset
     * @return LogicMelonStructAPIAdvertValue
     */
    public function offsetGet($_offset)
    {
        return parent::offsetGet($_offset);
    }
    /**
     * Returns the attribute name
     * @see LogicMelonWsdlClass::getAttributeName()
     * @return string APIAdvertValue
     */
    public function getAttributeName()
    {
        return 'APIAdvertValue';
    }
    /**
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see LogicMelonWsdlClass::__set_state()
     * @uses LogicMelonWsdlClass::__set_state()
     * @param array $_array the exported values
     * @return LogicMelonStructArrayOfAPIAdvertValue
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
