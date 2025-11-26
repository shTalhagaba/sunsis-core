<?php
/**
 * File for class LogicMelonStructArrayOfAPIAdvert
 * @package LogicMelon
 * @subpackage Structs
 * @date 2014-09-22
 * @author Mikaël DELSOL
 * @version 1
 */
/**
 * This class stands for LogicMelonStructArrayOfAPIAdvert originally named ArrayOfAPIAdvert
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://api.logicmelon.co.uk/SOAP/multiposter.asmx?WSDL}
 * @package LogicMelon
 * @subpackage Structs
 * @date 2014-09-22
 * @author Mikaël DELSOL
 * @version 1
 */
class LogicMelonStructArrayOfAPIAdvert extends LogicMelonWsdlClass
{
    /**
     * The APIAdvert
     * Meta informations extracted from the WSDL
     * - maxOccurs : unbounded
     * - minOccurs : 0
     * - nillable : true
     * @var LogicMelonStructAPIAdvert
     */
    public $APIAdvert;
    /**
     * Constructor method for ArrayOfAPIAdvert
     * @see parent::__construct()
     * @param LogicMelonStructAPIAdvert $_aPIAdvert
     * @return LogicMelonStructArrayOfAPIAdvert
     */
    public function __construct($_aPIAdvert = NULL)
    {
        parent::__construct(array('APIAdvert'=>$_aPIAdvert),false);
    }
    /**
     * Get APIAdvert value
     * @return LogicMelonStructAPIAdvert|null
     */
    public function getAPIAdvert()
    {
        return $this->APIAdvert;
    }
    /**
     * Set APIAdvert value
     * @param LogicMelonStructAPIAdvert $_aPIAdvert the APIAdvert
     * @return LogicMelonStructAPIAdvert
     */
    public function setAPIAdvert($_aPIAdvert)
    {
        return ($this->APIAdvert = $_aPIAdvert);
    }
    /**
     * Returns the current element
     * @see LogicMelonWsdlClass::current()
     * @return LogicMelonStructAPIAdvert
     */
    public function current()
    {
        return parent::current();
    }
    /**
     * Returns the indexed element
     * @see LogicMelonWsdlClass::item()
     * @param int $_index
     * @return LogicMelonStructAPIAdvert
     */
    public function item($_index)
    {
        return parent::item($_index);
    }
    /**
     * Returns the first element
     * @see LogicMelonWsdlClass::first()
     * @return LogicMelonStructAPIAdvert
     */
    public function first()
    {
        return parent::first();
    }
    /**
     * Returns the last element
     * @see LogicMelonWsdlClass::last()
     * @return LogicMelonStructAPIAdvert
     */
    public function last()
    {
        return parent::last();
    }
    /**
     * Returns the element at the offset
     * @see LogicMelonWsdlClass::last()
     * @param int $_offset
     * @return LogicMelonStructAPIAdvert
     */
    public function offsetGet($_offset)
    {
        return parent::offsetGet($_offset);
    }
    /**
     * Returns the attribute name
     * @see LogicMelonWsdlClass::getAttributeName()
     * @return string APIAdvert
     */
    public function getAttributeName()
    {
        return 'APIAdvert';
    }
    /**
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see LogicMelonWsdlClass::__set_state()
     * @uses LogicMelonWsdlClass::__set_state()
     * @param array $_array the exported values
     * @return LogicMelonStructArrayOfAPIAdvert
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
