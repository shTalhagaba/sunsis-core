<?php
/**
 * File for class LRSStructExecuteRtaQueryResponse
 * @package LRS
 * @subpackage Structs
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
/**
 * This class stands for LRSStructExecuteRtaQueryResponse originally named ExecuteRtaQueryResponse
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://compact-soft.com/projects/tempuri.org.xsd}
 * @package LRS
 * @subpackage Structs
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
class LRSStructExecuteRtaQueryResponse extends LRSWsdlClass
{
    /**
     * The ExecuteRtaQueryResult
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var LRSStructExecuteRtaResponse
     */
    public $ExecuteRtaQueryResult;
    /**
     * Constructor method for ExecuteRtaQueryResponse
     * @see parent::__construct()
     * @param LRSStructExecuteRtaResponse $_executeRtaQueryResult
     * @return LRSStructExecuteRtaQueryResponse
     */
    public function __construct($_executeRtaQueryResult = NULL)
    {
        parent::__construct(array('ExecuteRtaQueryResult'=>$_executeRtaQueryResult),false);
    }
    /**
     * Get ExecuteRtaQueryResult value
     * @return LRSStructExecuteRtaResponse|null
     */
    public function getExecuteRtaQueryResult()
    {
        return $this->ExecuteRtaQueryResult;
    }
    /**
     * Set ExecuteRtaQueryResult value
     * @param LRSStructExecuteRtaResponse $_executeRtaQueryResult the ExecuteRtaQueryResult
     * @return LRSStructExecuteRtaResponse
     */
    public function setExecuteRtaQueryResult($_executeRtaQueryResult)
    {
        return ($this->ExecuteRtaQueryResult = $_executeRtaQueryResult);
    }
    /**
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see LRSWsdlClass::__set_state()
     * @uses LRSWsdlClass::__set_state()
     * @param array $_array the exported values
     * @return LRSStructExecuteRtaQueryResponse
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
