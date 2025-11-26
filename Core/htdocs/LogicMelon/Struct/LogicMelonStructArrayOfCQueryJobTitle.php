<?php
/**
 * File for class LogicMelonStructArrayOfCQueryJobTitle
 * @package LogicMelon
 * @subpackage Structs
 * @date 2014-09-22
 * @author Mikaël DELSOL
 * @version 1
 */
/**
 * This class stands for LogicMelonStructArrayOfCQueryJobTitle originally named ArrayOfCQueryJobTitle
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://api.logicmelon.co.uk/SOAP/multiposter.asmx?WSDL}
 * @package LogicMelon
 * @subpackage Structs
 * @date 2014-09-22
 * @author Mikaël DELSOL
 * @version 1
 */
class LogicMelonStructArrayOfCQueryJobTitle extends LogicMelonWsdlClass
{
    /**
     * The CQueryJobTitle
     * Meta informations extracted from the WSDL
     * - maxOccurs : unbounded
     * - minOccurs : 0
     * - nillable : true
     * @var LogicMelonStructCQueryJobTitle
     */
    public $CQueryJobTitle;
    /**
     * Constructor method for ArrayOfCQueryJobTitle
     * @see parent::__construct()
     * @param LogicMelonStructCQueryJobTitle $_cQueryJobTitle
     * @return LogicMelonStructArrayOfCQueryJobTitle
     */
    public function __construct($_cQueryJobTitle = NULL)
    {
        parent::__construct(array('CQueryJobTitle'=>$_cQueryJobTitle),false);
    }
    /**
     * Get CQueryJobTitle value
     * @return LogicMelonStructCQueryJobTitle|null
     */
    public function getCQueryJobTitle()
    {
        return $this->CQueryJobTitle;
    }
    /**
     * Set CQueryJobTitle value
     * @param LogicMelonStructCQueryJobTitle $_cQueryJobTitle the CQueryJobTitle
     * @return LogicMelonStructCQueryJobTitle
     */
    public function setCQueryJobTitle($_cQueryJobTitle)
    {
        return ($this->CQueryJobTitle = $_cQueryJobTitle);
    }
    /**
     * Returns the current element
     * @see LogicMelonWsdlClass::current()
     * @return LogicMelonStructCQueryJobTitle
     */
    public function current()
    {
        return parent::current();
    }
    /**
     * Returns the indexed element
     * @see LogicMelonWsdlClass::item()
     * @param int $_index
     * @return LogicMelonStructCQueryJobTitle
     */
    public function item($_index)
    {
        return parent::item($_index);
    }
    /**
     * Returns the first element
     * @see LogicMelonWsdlClass::first()
     * @return LogicMelonStructCQueryJobTitle
     */
    public function first()
    {
        return parent::first();
    }
    /**
     * Returns the last element
     * @see LogicMelonWsdlClass::last()
     * @return LogicMelonStructCQueryJobTitle
     */
    public function last()
    {
        return parent::last();
    }
    /**
     * Returns the element at the offset
     * @see LogicMelonWsdlClass::last()
     * @param int $_offset
     * @return LogicMelonStructCQueryJobTitle
     */
    public function offsetGet($_offset)
    {
        return parent::offsetGet($_offset);
    }
    /**
     * Returns the attribute name
     * @see LogicMelonWsdlClass::getAttributeName()
     * @return string CQueryJobTitle
     */
    public function getAttributeName()
    {
        return 'CQueryJobTitle';
    }
    /**
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see LogicMelonWsdlClass::__set_state()
     * @uses LogicMelonWsdlClass::__set_state()
     * @param array $_array the exported values
     * @return LogicMelonStructArrayOfCQueryJobTitle
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
