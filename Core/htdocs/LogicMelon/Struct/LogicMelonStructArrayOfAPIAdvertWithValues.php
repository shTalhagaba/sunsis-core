<?php
/**
 * File for class LogicMelonStructArrayOfAPIAdvertWithValues
 * @package LogicMelon
 * @subpackage Structs
 * @date 2014-09-22
 * @author Mikaël DELSOL
 * @version 1
 */
/**
 * This class stands for LogicMelonStructArrayOfAPIAdvertWithValues originally named ArrayOfAPIAdvertWithValues
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://api.logicmelon.co.uk/SOAP/multiposter.asmx?WSDL}
 * @package LogicMelon
 * @subpackage Structs
 * @date 2014-09-22
 * @author Mikaël DELSOL
 * @version 1
 */
class LogicMelonStructArrayOfAPIAdvertWithValues extends LogicMelonWsdlClass
{
    /**
     * The APIAdvertWithValues
     * Meta informations extracted from the WSDL
     * - maxOccurs : unbounded
     * - minOccurs : 0
     * - nillable : true
     * @var LogicMelonStructAPIAdvertWithValues
     */
    public $APIAdvertWithValues;
    /**
     * Constructor method for ArrayOfAPIAdvertWithValues
     * @see parent::__construct()
     * @param LogicMelonStructAPIAdvertWithValues $_aPIAdvertWithValues
     * @return LogicMelonStructArrayOfAPIAdvertWithValues
     */
    public function __construct($_aPIAdvertWithValues = NULL)
    {
        parent::__construct(array('APIAdvertWithValues'=>$_aPIAdvertWithValues),false);
    }
    /**
     * Get APIAdvertWithValues value
     * @return LogicMelonStructAPIAdvertWithValues|null
     */
    public function getAPIAdvertWithValues()
    {
        return $this->APIAdvertWithValues;
    }
    /**
     * Set APIAdvertWithValues value
     * @param LogicMelonStructAPIAdvertWithValues $_aPIAdvertWithValues the APIAdvertWithValues
     * @return LogicMelonStructAPIAdvertWithValues
     */
    public function setAPIAdvertWithValues($_aPIAdvertWithValues)
    {
        return ($this->APIAdvertWithValues = $_aPIAdvertWithValues);
    }
    /**
     * Returns the current element
     * @see LogicMelonWsdlClass::current()
     * @return LogicMelonStructAPIAdvertWithValues
     */
    public function current()
    {
        return parent::current();
    }
    /**
     * Returns the indexed element
     * @see LogicMelonWsdlClass::item()
     * @param int $_index
     * @return LogicMelonStructAPIAdvertWithValues
     */
    public function item($_index)
    {
        return parent::item($_index);
    }
    /**
     * Returns the first element
     * @see LogicMelonWsdlClass::first()
     * @return LogicMelonStructAPIAdvertWithValues
     */
    public function first()
    {
        return parent::first();
    }
    /**
     * Returns the last element
     * @see LogicMelonWsdlClass::last()
     * @return LogicMelonStructAPIAdvertWithValues
     */
    public function last()
    {
        return parent::last();
    }
    /**
     * Returns the element at the offset
     * @see LogicMelonWsdlClass::last()
     * @param int $_offset
     * @return LogicMelonStructAPIAdvertWithValues
     */
    public function offsetGet($_offset)
    {
        return parent::offsetGet($_offset);
    }
    /**
     * Returns the attribute name
     * @see LogicMelonWsdlClass::getAttributeName()
     * @return string APIAdvertWithValues
     */
    public function getAttributeName()
    {
        return 'APIAdvertWithValues';
    }
    /**
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see LogicMelonWsdlClass::__set_state()
     * @uses LogicMelonWsdlClass::__set_state()
     * @param array $_array the exported values
     * @return LogicMelonStructArrayOfAPIAdvertWithValues
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
