<?php
/**
 * File for class LRSStructArrayOfLinkedUnit
 * @package LRS
 * @subpackage Structs
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
/**
 * This class stands for LRSStructArrayOfLinkedUnit originally named ArrayOfLinkedUnit
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://compact-soft.com/projects/api.lrs.qcf.gov.uk.model.xsd}
 * @package LRS
 * @subpackage Structs
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
class LRSStructArrayOfLinkedUnit extends LRSWsdlClass
{
    /**
     * The LinkedUnit
     * Meta informations extracted from the WSDL
     * - maxOccurs : unbounded
     * - minOccurs : 0
     * - nillable : true
     * @var LRSStructLinkedUnit
     */
    public $LinkedUnit;
    /**
     * Constructor method for ArrayOfLinkedUnit
     * @see parent::__construct()
     * @param LRSStructLinkedUnit $_linkedUnit
     * @return LRSStructArrayOfLinkedUnit
     */
    public function __construct($_linkedUnit = NULL)
    {
        parent::__construct(array('LinkedUnit'=>$_linkedUnit),false);
    }
    /**
     * Get LinkedUnit value
     * @return LRSStructLinkedUnit|null
     */
    public function getLinkedUnit()
    {
        return $this->LinkedUnit;
    }
    /**
     * Set LinkedUnit value
     * @param LRSStructLinkedUnit $_linkedUnit the LinkedUnit
     * @return LRSStructLinkedUnit
     */
    public function setLinkedUnit($_linkedUnit)
    {
        return ($this->LinkedUnit = $_linkedUnit);
    }
    /**
     * Returns the current element
     * @see LRSWsdlClass::current()
     * @return LRSStructLinkedUnit
     */
    public function current()
    {
        return parent::current();
    }
    /**
     * Returns the indexed element
     * @see LRSWsdlClass::item()
     * @param int $_index
     * @return LRSStructLinkedUnit
     */
    public function item($_index)
    {
        return parent::item($_index);
    }
    /**
     * Returns the first element
     * @see LRSWsdlClass::first()
     * @return LRSStructLinkedUnit
     */
    public function first()
    {
        return parent::first();
    }
    /**
     * Returns the last element
     * @see LRSWsdlClass::last()
     * @return LRSStructLinkedUnit
     */
    public function last()
    {
        return parent::last();
    }
    /**
     * Returns the element at the offset
     * @see LRSWsdlClass::last()
     * @param int $_offset
     * @return LRSStructLinkedUnit
     */
    public function offsetGet($_offset)
    {
        return parent::offsetGet($_offset);
    }
    /**
     * Returns the attribute name
     * @see LRSWsdlClass::getAttributeName()
     * @return string LinkedUnit
     */
    public function getAttributeName()
    {
        return 'LinkedUnit';
    }
    /**
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see LRSWsdlClass::__set_state()
     * @uses LRSWsdlClass::__set_state()
     * @param array $_array the exported values
     * @return LRSStructArrayOfLinkedUnit
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
