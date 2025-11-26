<?php
/**
 * File for class LRSStructExecuteRtaResponse
 * @package LRS
 * @subpackage Structs
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
/**
 * This class stands for LRSStructExecuteRtaResponse originally named ExecuteRtaResponse
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://compact-soft.com/projects/api.lrs.qcf.gov.uk.model.xsd}
 * @package LRS
 * @subpackage Structs
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
class LRSStructExecuteRtaResponse extends LRSStructServiceResponseR9
{
    /**
     * The RtaQueryResult
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var LRSStructArrayOfRtaOneQueryResult
     */
    public $RtaQueryResult;
    /**
     * Constructor method for ExecuteRtaResponse
     * @see parent::__construct()
     * @param LRSStructArrayOfRtaOneQueryResult $_rtaQueryResult
     * @return LRSStructExecuteRtaResponse
     */
    public function __construct($_rtaQueryResult = NULL)
    {
        LRSWsdlClass::__construct(array('RtaQueryResult'=>($_rtaQueryResult instanceof LRSStructArrayOfRtaOneQueryResult)?$_rtaQueryResult:new LRSStructArrayOfRtaOneQueryResult($_rtaQueryResult)),false);
    }
    /**
     * Get RtaQueryResult value
     * @return LRSStructArrayOfRtaOneQueryResult|null
     */
    public function getRtaQueryResult()
    {
        return $this->RtaQueryResult;
    }
    /**
     * Set RtaQueryResult value
     * @param LRSStructArrayOfRtaOneQueryResult $_rtaQueryResult the RtaQueryResult
     * @return LRSStructArrayOfRtaOneQueryResult
     */
    public function setRtaQueryResult($_rtaQueryResult)
    {
        return ($this->RtaQueryResult = $_rtaQueryResult);
    }
    /**
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see LRSWsdlClass::__set_state()
     * @uses LRSWsdlClass::__set_state()
     * @param array $_array the exported values
     * @return LRSStructExecuteRtaResponse
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
