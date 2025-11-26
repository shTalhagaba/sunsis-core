<?php
/**
 * File for class LogicMelonStructArrayOfAPIApplication
 * @package LogicMelon
 * @subpackage Structs
 * @date 2014-09-22
 * @author Mikaël DELSOL
 * @version 1
 */
/**
 * This class stands for LogicMelonStructArrayOfAPIApplication originally named ArrayOfAPIApplication
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://api.logicmelon.co.uk/SOAP/multiposter.asmx?WSDL}
 * @package LogicMelon
 * @subpackage Structs
 * @date 2014-09-22
 * @author Mikaël DELSOL
 * @version 1
 */
class LogicMelonStructArrayOfAPIApplication extends LogicMelonWsdlClass
{
    /**
     * The APIApplication
     * Meta informations extracted from the WSDL
     * - maxOccurs : unbounded
     * - minOccurs : 0
     * - nillable : true
     * @var LogicMelonStructAPIApplication
     */
    public $APIApplication;
    /**
     * Constructor method for ArrayOfAPIApplication
     * @see parent::__construct()
     * @param LogicMelonStructAPIApplication $_aPIApplication
     * @return LogicMelonStructArrayOfAPIApplication
     */
    public function __construct($_aPIApplication = NULL)
    {
        parent::__construct(array('APIApplication'=>$_aPIApplication),false);
    }
    /**
     * Get APIApplication value
     * @return LogicMelonStructAPIApplication|null
     */
    public function getAPIApplication()
    {
        return $this->APIApplication;
    }
    /**
     * Set APIApplication value
     * @param LogicMelonStructAPIApplication $_aPIApplication the APIApplication
     * @return LogicMelonStructAPIApplication
     */
    public function setAPIApplication($_aPIApplication)
    {
        return ($this->APIApplication = $_aPIApplication);
    }
    /**
     * Returns the current element
     * @see LogicMelonWsdlClass::current()
     * @return LogicMelonStructAPIApplication
     */
    public function current()
    {
        return parent::current();
    }
    /**
     * Returns the indexed element
     * @see LogicMelonWsdlClass::item()
     * @param int $_index
     * @return LogicMelonStructAPIApplication
     */
    public function item($_index)
    {
        return parent::item($_index);
    }
    /**
     * Returns the first element
     * @see LogicMelonWsdlClass::first()
     * @return LogicMelonStructAPIApplication
     */
    public function first()
    {
        return parent::first();
    }
    /**
     * Returns the last element
     * @see LogicMelonWsdlClass::last()
     * @return LogicMelonStructAPIApplication
     */
    public function last()
    {
        return parent::last();
    }
    /**
     * Returns the element at the offset
     * @see LogicMelonWsdlClass::last()
     * @param int $_offset
     * @return LogicMelonStructAPIApplication
     */
    public function offsetGet($_offset)
    {
        return parent::offsetGet($_offset);
    }
    /**
     * Returns the attribute name
     * @see LogicMelonWsdlClass::getAttributeName()
     * @return string APIApplication
     */
    public function getAttributeName()
    {
        return 'APIApplication';
    }
    /**
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see LogicMelonWsdlClass::__set_state()
     * @uses LogicMelonWsdlClass::__set_state()
     * @param array $_array the exported values
     * @return LogicMelonStructArrayOfAPIApplication
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
