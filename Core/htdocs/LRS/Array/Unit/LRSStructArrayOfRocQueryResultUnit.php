<?php
/**
 * File for class LRSStructArrayOfRocQueryResultUnit
 * @package LRS
 * @subpackage Structs
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
/**
 * This class stands for LRSStructArrayOfRocQueryResultUnit originally named ArrayOfRocQueryResultUnit
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://compact-soft.com/projects/api.lrs.qcf.gov.uk.model.xsd}
 * @package LRS
 * @subpackage Structs
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
class LRSStructArrayOfRocQueryResultUnit extends LRSWsdlClass
{
    /**
     * The RocQueryResultUnit
     * Meta informations extracted from the WSDL
     * - maxOccurs : unbounded
     * - minOccurs : 0
     * - nillable : true
     * @var LRSStructRocQueryResultUnit
     */
    public $RocQueryResultUnit;
    /**
     * Constructor method for ArrayOfRocQueryResultUnit
     * @see parent::__construct()
     * @param LRSStructRocQueryResultUnit $_rocQueryResultUnit
     * @return LRSStructArrayOfRocQueryResultUnit
     */
    public function __construct($_rocQueryResultUnit = NULL)
    {
        parent::__construct(array('RocQueryResultUnit'=>$_rocQueryResultUnit),false);
    }
    /**
     * Get RocQueryResultUnit value
     * @return LRSStructRocQueryResultUnit|null
     */
    public function getRocQueryResultUnit()
    {
        return $this->RocQueryResultUnit;
    }
    /**
     * Set RocQueryResultUnit value
     * @param LRSStructRocQueryResultUnit $_rocQueryResultUnit the RocQueryResultUnit
     * @return LRSStructRocQueryResultUnit
     */
    public function setRocQueryResultUnit($_rocQueryResultUnit)
    {
        return ($this->RocQueryResultUnit = $_rocQueryResultUnit);
    }
    /**
     * Returns the current element
     * @see LRSWsdlClass::current()
     * @return LRSStructRocQueryResultUnit
     */
    public function current()
    {
        return parent::current();
    }
    /**
     * Returns the indexed element
     * @see LRSWsdlClass::item()
     * @param int $_index
     * @return LRSStructRocQueryResultUnit
     */
    public function item($_index)
    {
        return parent::item($_index);
    }
    /**
     * Returns the first element
     * @see LRSWsdlClass::first()
     * @return LRSStructRocQueryResultUnit
     */
    public function first()
    {
        return parent::first();
    }
    /**
     * Returns the last element
     * @see LRSWsdlClass::last()
     * @return LRSStructRocQueryResultUnit
     */
    public function last()
    {
        return parent::last();
    }
    /**
     * Returns the element at the offset
     * @see LRSWsdlClass::last()
     * @param int $_offset
     * @return LRSStructRocQueryResultUnit
     */
    public function offsetGet($_offset)
    {
        return parent::offsetGet($_offset);
    }
    /**
     * Returns the attribute name
     * @see LRSWsdlClass::getAttributeName()
     * @return string RocQueryResultUnit
     */
    public function getAttributeName()
    {
        return 'RocQueryResultUnit';
    }
    /**
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see LRSWsdlClass::__set_state()
     * @uses LRSWsdlClass::__set_state()
     * @param array $_array the exported values
     * @return LRSStructArrayOfRocQueryResultUnit
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
