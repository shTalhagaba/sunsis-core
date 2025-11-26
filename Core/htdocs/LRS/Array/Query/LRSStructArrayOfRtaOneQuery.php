<?php
/**
 * File for class LRSStructArrayOfRtaOneQuery
 * @package LRS
 * @subpackage Structs
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
/**
 * This class stands for LRSStructArrayOfRtaOneQuery originally named ArrayOfRtaOneQuery
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://compact-soft.com/projects/api.lrs.qcf.gov.uk.model.xsd}
 * @package LRS
 * @subpackage Structs
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
class LRSStructArrayOfRtaOneQuery extends LRSWsdlClass
{
    /**
     * The RtaOneQuery
     * Meta informations extracted from the WSDL
     * - maxOccurs : unbounded
     * - minOccurs : 0
     * - nillable : true
     * @var LRSStructRtaOneQuery
     */
    public $RtaOneQuery;
    /**
     * Constructor method for ArrayOfRtaOneQuery
     * @see parent::__construct()
     * @param LRSStructRtaOneQuery $_rtaOneQuery
     * @return LRSStructArrayOfRtaOneQuery
     */
    public function __construct($_rtaOneQuery = NULL)
    {
        parent::__construct(array('RtaOneQuery'=>$_rtaOneQuery),false);
    }
    /**
     * Get RtaOneQuery value
     * @return LRSStructRtaOneQuery|null
     */
    public function getRtaOneQuery()
    {
        return $this->RtaOneQuery;
    }
    /**
     * Set RtaOneQuery value
     * @param LRSStructRtaOneQuery $_rtaOneQuery the RtaOneQuery
     * @return LRSStructRtaOneQuery
     */
    public function setRtaOneQuery($_rtaOneQuery)
    {
        return ($this->RtaOneQuery = $_rtaOneQuery);
    }
    /**
     * Returns the current element
     * @see LRSWsdlClass::current()
     * @return LRSStructRtaOneQuery
     */
    public function current()
    {
        return parent::current();
    }
    /**
     * Returns the indexed element
     * @see LRSWsdlClass::item()
     * @param int $_index
     * @return LRSStructRtaOneQuery
     */
    public function item($_index)
    {
        return parent::item($_index);
    }
    /**
     * Returns the first element
     * @see LRSWsdlClass::first()
     * @return LRSStructRtaOneQuery
     */
    public function first()
    {
        return parent::first();
    }
    /**
     * Returns the last element
     * @see LRSWsdlClass::last()
     * @return LRSStructRtaOneQuery
     */
    public function last()
    {
        return parent::last();
    }
    /**
     * Returns the element at the offset
     * @see LRSWsdlClass::last()
     * @param int $_offset
     * @return LRSStructRtaOneQuery
     */
    public function offsetGet($_offset)
    {
        return parent::offsetGet($_offset);
    }
    /**
     * Returns the attribute name
     * @see LRSWsdlClass::getAttributeName()
     * @return string RtaOneQuery
     */
    public function getAttributeName()
    {
        return 'RtaOneQuery';
    }
    /**
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see LRSWsdlClass::__set_state()
     * @uses LRSWsdlClass::__set_state()
     * @param array $_array the exported values
     * @return LRSStructArrayOfRtaOneQuery
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
