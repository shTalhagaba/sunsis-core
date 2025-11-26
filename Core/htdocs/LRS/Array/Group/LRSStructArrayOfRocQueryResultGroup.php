<?php
/**
 * File for class LRSStructArrayOfRocQueryResultGroup
 * @package LRS
 * @subpackage Structs
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
/**
 * This class stands for LRSStructArrayOfRocQueryResultGroup originally named ArrayOfRocQueryResultGroup
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://compact-soft.com/projects/api.lrs.qcf.gov.uk.model.xsd}
 * @package LRS
 * @subpackage Structs
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
class LRSStructArrayOfRocQueryResultGroup extends LRSWsdlClass
{
    /**
     * The RocQueryResultGroup
     * Meta informations extracted from the WSDL
     * - maxOccurs : unbounded
     * - minOccurs : 0
     * - nillable : true
     * @var LRSStructRocQueryResultGroup
     */
    public $RocQueryResultGroup;
    /**
     * Constructor method for ArrayOfRocQueryResultGroup
     * @see parent::__construct()
     * @param LRSStructRocQueryResultGroup $_rocQueryResultGroup
     * @return LRSStructArrayOfRocQueryResultGroup
     */
    public function __construct($_rocQueryResultGroup = NULL)
    {
        parent::__construct(array('RocQueryResultGroup'=>$_rocQueryResultGroup),false);
    }
    /**
     * Get RocQueryResultGroup value
     * @return LRSStructRocQueryResultGroup|null
     */
    public function getRocQueryResultGroup()
    {
        return $this->RocQueryResultGroup;
    }
    /**
     * Set RocQueryResultGroup value
     * @param LRSStructRocQueryResultGroup $_rocQueryResultGroup the RocQueryResultGroup
     * @return LRSStructRocQueryResultGroup
     */
    public function setRocQueryResultGroup($_rocQueryResultGroup)
    {
        return ($this->RocQueryResultGroup = $_rocQueryResultGroup);
    }
    /**
     * Returns the current element
     * @see LRSWsdlClass::current()
     * @return LRSStructRocQueryResultGroup
     */
    public function current()
    {
        return parent::current();
    }
    /**
     * Returns the indexed element
     * @see LRSWsdlClass::item()
     * @param int $_index
     * @return LRSStructRocQueryResultGroup
     */
    public function item($_index)
    {
        return parent::item($_index);
    }
    /**
     * Returns the first element
     * @see LRSWsdlClass::first()
     * @return LRSStructRocQueryResultGroup
     */
    public function first()
    {
        return parent::first();
    }
    /**
     * Returns the last element
     * @see LRSWsdlClass::last()
     * @return LRSStructRocQueryResultGroup
     */
    public function last()
    {
        return parent::last();
    }
    /**
     * Returns the element at the offset
     * @see LRSWsdlClass::last()
     * @param int $_offset
     * @return LRSStructRocQueryResultGroup
     */
    public function offsetGet($_offset)
    {
        return parent::offsetGet($_offset);
    }
    /**
     * Returns the attribute name
     * @see LRSWsdlClass::getAttributeName()
     * @return string RocQueryResultGroup
     */
    public function getAttributeName()
    {
        return 'RocQueryResultGroup';
    }
    /**
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see LRSWsdlClass::__set_state()
     * @uses LRSWsdlClass::__set_state()
     * @param array $_array the exported values
     * @return LRSStructArrayOfRocQueryResultGroup
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
