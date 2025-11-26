<?php
/**
 * File for class LogicMelonStructArrayOfCQueryLocation
 * @package LogicMelon
 * @subpackage Structs
 * @date 2014-09-22
 * @author Mikaël DELSOL
 * @version 1
 */
/**
 * This class stands for LogicMelonStructArrayOfCQueryLocation originally named ArrayOfCQueryLocation
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://api.logicmelon.co.uk/SOAP/multiposter.asmx?WSDL}
 * @package LogicMelon
 * @subpackage Structs
 * @date 2014-09-22
 * @author Mikaël DELSOL
 * @version 1
 */
class LogicMelonStructArrayOfCQueryLocation extends LogicMelonWsdlClass
{
    /**
     * The CQueryLocation
     * Meta informations extracted from the WSDL
     * - maxOccurs : unbounded
     * - minOccurs : 0
     * - nillable : true
     * @var LogicMelonStructCQueryLocation
     */
    public $CQueryLocation;
    /**
     * Constructor method for ArrayOfCQueryLocation
     * @see parent::__construct()
     * @param LogicMelonStructCQueryLocation $_cQueryLocation
     * @return LogicMelonStructArrayOfCQueryLocation
     */
    public function __construct($_cQueryLocation = NULL)
    {
        parent::__construct(array('CQueryLocation'=>$_cQueryLocation),false);
    }
    /**
     * Get CQueryLocation value
     * @return LogicMelonStructCQueryLocation|null
     */
    public function getCQueryLocation()
    {
        return $this->CQueryLocation;
    }
    /**
     * Set CQueryLocation value
     * @param LogicMelonStructCQueryLocation $_cQueryLocation the CQueryLocation
     * @return LogicMelonStructCQueryLocation
     */
    public function setCQueryLocation($_cQueryLocation)
    {
        return ($this->CQueryLocation = $_cQueryLocation);
    }
    /**
     * Returns the current element
     * @see LogicMelonWsdlClass::current()
     * @return LogicMelonStructCQueryLocation
     */
    public function current()
    {
        return parent::current();
    }
    /**
     * Returns the indexed element
     * @see LogicMelonWsdlClass::item()
     * @param int $_index
     * @return LogicMelonStructCQueryLocation
     */
    public function item($_index)
    {
        return parent::item($_index);
    }
    /**
     * Returns the first element
     * @see LogicMelonWsdlClass::first()
     * @return LogicMelonStructCQueryLocation
     */
    public function first()
    {
        return parent::first();
    }
    /**
     * Returns the last element
     * @see LogicMelonWsdlClass::last()
     * @return LogicMelonStructCQueryLocation
     */
    public function last()
    {
        return parent::last();
    }
    /**
     * Returns the element at the offset
     * @see LogicMelonWsdlClass::last()
     * @param int $_offset
     * @return LogicMelonStructCQueryLocation
     */
    public function offsetGet($_offset)
    {
        return parent::offsetGet($_offset);
    }
    /**
     * Returns the attribute name
     * @see LogicMelonWsdlClass::getAttributeName()
     * @return string CQueryLocation
     */
    public function getAttributeName()
    {
        return 'CQueryLocation';
    }
    /**
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see LogicMelonWsdlClass::__set_state()
     * @uses LogicMelonWsdlClass::__set_state()
     * @param array $_array the exported values
     * @return LogicMelonStructArrayOfCQueryLocation
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
