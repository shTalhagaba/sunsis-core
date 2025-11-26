<?php
/**
 * File for class LRSStructArrayOfLinkedNQFUnit
 * @package LRS
 * @subpackage Structs
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
/**
 * This class stands for LRSStructArrayOfLinkedNQFUnit originally named ArrayOfLinkedNQFUnit
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://compact-soft.com/projects/api.lrs.qcf.gov.uk.model.xsd}
 * @package LRS
 * @subpackage Structs
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
class LRSStructArrayOfLinkedNQFUnit extends LRSWsdlClass
{
    /**
     * The LinkedNQFUnit
     * Meta informations extracted from the WSDL
     * - maxOccurs : unbounded
     * - minOccurs : 0
     * - nillable : true
     * @var LRSStructLinkedNQFUnit
     */
    public $LinkedNQFUnit;
    /**
     * Constructor method for ArrayOfLinkedNQFUnit
     * @see parent::__construct()
     * @param LRSStructLinkedNQFUnit $_linkedNQFUnit
     * @return LRSStructArrayOfLinkedNQFUnit
     */
    public function __construct($_linkedNQFUnit = NULL)
    {
        parent::__construct(array('LinkedNQFUnit'=>$_linkedNQFUnit),false);
    }
    /**
     * Get LinkedNQFUnit value
     * @return LRSStructLinkedNQFUnit|null
     */
    public function getLinkedNQFUnit()
    {
        return $this->LinkedNQFUnit;
    }
    /**
     * Set LinkedNQFUnit value
     * @param LRSStructLinkedNQFUnit $_linkedNQFUnit the LinkedNQFUnit
     * @return LRSStructLinkedNQFUnit
     */
    public function setLinkedNQFUnit($_linkedNQFUnit)
    {
        return ($this->LinkedNQFUnit = $_linkedNQFUnit);
    }
    /**
     * Returns the current element
     * @see LRSWsdlClass::current()
     * @return LRSStructLinkedNQFUnit
     */
    public function current()
    {
        return parent::current();
    }
    /**
     * Returns the indexed element
     * @see LRSWsdlClass::item()
     * @param int $_index
     * @return LRSStructLinkedNQFUnit
     */
    public function item($_index)
    {
        return parent::item($_index);
    }
    /**
     * Returns the first element
     * @see LRSWsdlClass::first()
     * @return LRSStructLinkedNQFUnit
     */
    public function first()
    {
        return parent::first();
    }
    /**
     * Returns the last element
     * @see LRSWsdlClass::last()
     * @return LRSStructLinkedNQFUnit
     */
    public function last()
    {
        return parent::last();
    }
    /**
     * Returns the element at the offset
     * @see LRSWsdlClass::last()
     * @param int $_offset
     * @return LRSStructLinkedNQFUnit
     */
    public function offsetGet($_offset)
    {
        return parent::offsetGet($_offset);
    }
    /**
     * Returns the attribute name
     * @see LRSWsdlClass::getAttributeName()
     * @return string LinkedNQFUnit
     */
    public function getAttributeName()
    {
        return 'LinkedNQFUnit';
    }
    /**
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see LRSWsdlClass::__set_state()
     * @uses LRSWsdlClass::__set_state()
     * @param array $_array the exported values
     * @return LRSStructArrayOfLinkedNQFUnit
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
