<?php
/**
 * File for class LRSStructArrayOfRtaOneQueryResult
 * @package LRS
 * @subpackage Structs
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
/**
 * This class stands for LRSStructArrayOfRtaOneQueryResult originally named ArrayOfRtaOneQueryResult
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://compact-soft.com/projects/api.lrs.qcf.gov.uk.model.xsd}
 * @package LRS
 * @subpackage Structs
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
class LRSStructArrayOfRtaOneQueryResult extends LRSWsdlClass
{
    /**
     * The RtaOneQueryResult
     * Meta informations extracted from the WSDL
     * - maxOccurs : unbounded
     * - minOccurs : 0
     * - nillable : true
     * @var LRSStructRtaOneQueryResult
     */
    public $RtaOneQueryResult;
    /**
     * Constructor method for ArrayOfRtaOneQueryResult
     * @see parent::__construct()
     * @param LRSStructRtaOneQueryResult $_rtaOneQueryResult
     * @return LRSStructArrayOfRtaOneQueryResult
     */
    public function __construct($_rtaOneQueryResult = NULL)
    {
        parent::__construct(array('RtaOneQueryResult'=>$_rtaOneQueryResult),false);
    }
    /**
     * Get RtaOneQueryResult value
     * @return LRSStructRtaOneQueryResult|null
     */
    public function getRtaOneQueryResult()
    {
        return $this->RtaOneQueryResult;
    }
    /**
     * Set RtaOneQueryResult value
     * @param LRSStructRtaOneQueryResult $_rtaOneQueryResult the RtaOneQueryResult
     * @return LRSStructRtaOneQueryResult
     */
    public function setRtaOneQueryResult($_rtaOneQueryResult)
    {
        return ($this->RtaOneQueryResult = $_rtaOneQueryResult);
    }
    /**
     * Returns the current element
     * @see LRSWsdlClass::current()
     * @return LRSStructRtaOneQueryResult
     */
    public function current()
    {
        return parent::current();
    }
    /**
     * Returns the indexed element
     * @see LRSWsdlClass::item()
     * @param int $_index
     * @return LRSStructRtaOneQueryResult
     */
    public function item($_index)
    {
        return parent::item($_index);
    }
    /**
     * Returns the first element
     * @see LRSWsdlClass::first()
     * @return LRSStructRtaOneQueryResult
     */
    public function first()
    {
        return parent::first();
    }
    /**
     * Returns the last element
     * @see LRSWsdlClass::last()
     * @return LRSStructRtaOneQueryResult
     */
    public function last()
    {
        return parent::last();
    }
    /**
     * Returns the element at the offset
     * @see LRSWsdlClass::last()
     * @param int $_offset
     * @return LRSStructRtaOneQueryResult
     */
    public function offsetGet($_offset)
    {
        return parent::offsetGet($_offset);
    }
    /**
     * Returns the attribute name
     * @see LRSWsdlClass::getAttributeName()
     * @return string RtaOneQueryResult
     */
    public function getAttributeName()
    {
        return 'RtaOneQueryResult';
    }
    /**
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see LRSWsdlClass::__set_state()
     * @uses LRSWsdlClass::__set_state()
     * @param array $_array the exported values
     * @return LRSStructArrayOfRtaOneQueryResult
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
