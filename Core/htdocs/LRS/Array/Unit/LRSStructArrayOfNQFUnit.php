<?php
/**
 * File for class LRSStructArrayOfNQFUnit
 * @package LRS
 * @subpackage Structs
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
/**
 * This class stands for LRSStructArrayOfNQFUnit originally named ArrayOfNQFUnit
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://compact-soft.com/projects/api.lrs.qcf.gov.uk.model.xsd}
 * @package LRS
 * @subpackage Structs
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
class LRSStructArrayOfNQFUnit extends LRSWsdlClass
{
    /**
     * The NQFUnit
     * Meta informations extracted from the WSDL
     * - maxOccurs : unbounded
     * - minOccurs : 0
     * - nillable : true
     * @var LRSStructNQFUnit
     */
    public $NQFUnit;
    /**
     * Constructor method for ArrayOfNQFUnit
     * @see parent::__construct()
     * @param LRSStructNQFUnit $_nQFUnit
     * @return LRSStructArrayOfNQFUnit
     */
    public function __construct($_nQFUnit = NULL)
    {
        parent::__construct(array('NQFUnit'=>$_nQFUnit),false);
    }
    /**
     * Get NQFUnit value
     * @return LRSStructNQFUnit|null
     */
    public function getNQFUnit()
    {
        return $this->NQFUnit;
    }
    /**
     * Set NQFUnit value
     * @param LRSStructNQFUnit $_nQFUnit the NQFUnit
     * @return LRSStructNQFUnit
     */
    public function setNQFUnit($_nQFUnit)
    {
        return ($this->NQFUnit = $_nQFUnit);
    }
    /**
     * Returns the current element
     * @see LRSWsdlClass::current()
     * @return LRSStructNQFUnit
     */
    public function current()
    {
        return parent::current();
    }
    /**
     * Returns the indexed element
     * @see LRSWsdlClass::item()
     * @param int $_index
     * @return LRSStructNQFUnit
     */
    public function item($_index)
    {
        return parent::item($_index);
    }
    /**
     * Returns the first element
     * @see LRSWsdlClass::first()
     * @return LRSStructNQFUnit
     */
    public function first()
    {
        return parent::first();
    }
    /**
     * Returns the last element
     * @see LRSWsdlClass::last()
     * @return LRSStructNQFUnit
     */
    public function last()
    {
        return parent::last();
    }
    /**
     * Returns the element at the offset
     * @see LRSWsdlClass::last()
     * @param int $_offset
     * @return LRSStructNQFUnit
     */
    public function offsetGet($_offset)
    {
        return parent::offsetGet($_offset);
    }
    /**
     * Returns the attribute name
     * @see LRSWsdlClass::getAttributeName()
     * @return string NQFUnit
     */
    public function getAttributeName()
    {
        return 'NQFUnit';
    }
    /**
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see LRSWsdlClass::__set_state()
     * @uses LRSWsdlClass::__set_state()
     * @param array $_array the exported values
     * @return LRSStructArrayOfNQFUnit
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
