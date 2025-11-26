<?php
/**
 * File for class LRSStructArrayOfBarredUnit
 * @package LRS
 * @subpackage Structs
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
/**
 * This class stands for LRSStructArrayOfBarredUnit originally named ArrayOfBarredUnit
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://compact-soft.com/projects/api.lrs.qcf.gov.uk.model.xsd}
 * @package LRS
 * @subpackage Structs
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
class LRSStructArrayOfBarredUnit extends LRSWsdlClass
{
    /**
     * The BarredUnit
     * Meta informations extracted from the WSDL
     * - maxOccurs : unbounded
     * - minOccurs : 0
     * - nillable : true
     * @var LRSStructBarredUnit
     */
    public $BarredUnit;
    /**
     * Constructor method for ArrayOfBarredUnit
     * @see parent::__construct()
     * @param LRSStructBarredUnit $_barredUnit
     * @return LRSStructArrayOfBarredUnit
     */
    public function __construct($_barredUnit = NULL)
    {
        parent::__construct(array('BarredUnit'=>$_barredUnit),false);
    }
    /**
     * Get BarredUnit value
     * @return LRSStructBarredUnit|null
     */
    public function getBarredUnit()
    {
        return $this->BarredUnit;
    }
    /**
     * Set BarredUnit value
     * @param LRSStructBarredUnit $_barredUnit the BarredUnit
     * @return LRSStructBarredUnit
     */
    public function setBarredUnit($_barredUnit)
    {
        return ($this->BarredUnit = $_barredUnit);
    }
    /**
     * Returns the current element
     * @see LRSWsdlClass::current()
     * @return LRSStructBarredUnit
     */
    public function current()
    {
        return parent::current();
    }
    /**
     * Returns the indexed element
     * @see LRSWsdlClass::item()
     * @param int $_index
     * @return LRSStructBarredUnit
     */
    public function item($_index)
    {
        return parent::item($_index);
    }
    /**
     * Returns the first element
     * @see LRSWsdlClass::first()
     * @return LRSStructBarredUnit
     */
    public function first()
    {
        return parent::first();
    }
    /**
     * Returns the last element
     * @see LRSWsdlClass::last()
     * @return LRSStructBarredUnit
     */
    public function last()
    {
        return parent::last();
    }
    /**
     * Returns the element at the offset
     * @see LRSWsdlClass::last()
     * @param int $_offset
     * @return LRSStructBarredUnit
     */
    public function offsetGet($_offset)
    {
        return parent::offsetGet($_offset);
    }
    /**
     * Returns the attribute name
     * @see LRSWsdlClass::getAttributeName()
     * @return string BarredUnit
     */
    public function getAttributeName()
    {
        return 'BarredUnit';
    }
    /**
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see LRSWsdlClass::__set_state()
     * @uses LRSWsdlClass::__set_state()
     * @param array $_array the exported values
     * @return LRSStructArrayOfBarredUnit
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
