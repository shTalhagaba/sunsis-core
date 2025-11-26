<?php
/**
 * File for class LRSStructExecuteRoCQueryResponse
 * @package LRS
 * @subpackage Structs
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
/**
 * This class stands for LRSStructExecuteRoCQueryResponse originally named ExecuteRoCQueryResponse
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://compact-soft.com/projects/tempuri.org.xsd}
 * @package LRS
 * @subpackage Structs
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
class LRSStructExecuteRoCQueryResponse extends LRSStructServiceResponseR9
{
    /**
     * The RoCQuery
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var LRSStructRocQueryResult
     */
    public $RoCQuery;
    /**
     * The ExecuteRoCQueryResult
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * - from schema : {@link http://compact-soft.com/projects/tempuri.org.xsd}
     * @var ServiceResponseR9
     */
    public $ExecuteRoCQueryResult;
    /**
     * Constructor method for ExecuteRoCQueryResponse
     * @see parent::__construct()
     * @param LRSStructRocQueryResult $_roCQuery
     * @param ServiceResponseR9 $_executeRoCQueryResult
     * @return LRSStructExecuteRoCQueryResponse
     */
    public function __construct($_roCQuery = NULL,$_executeRoCQueryResult = NULL)
    {
        LRSWsdlClass::__construct(array('RoCQuery'=>$_roCQuery,'ExecuteRoCQueryResult'=>$_executeRoCQueryResult),false);
    }
    /**
     * Get RoCQuery value
     * @return LRSStructRocQueryResult|null
     */
    public function getRoCQuery()
    {
        return $this->RoCQuery;
    }
    /**
     * Set RoCQuery value
     * @param LRSStructRocQueryResult $_roCQuery the RoCQuery
     * @return LRSStructRocQueryResult
     */
    public function setRoCQuery($_roCQuery)
    {
        return ($this->RoCQuery = $_roCQuery);
    }
    /**
     * Get ExecuteRoCQueryResult value
     * @return ServiceResponseR9|null
     */
    public function getExecuteRoCQueryResult()
    {
        return $this->ExecuteRoCQueryResult;
    }
    /**
     * Set ExecuteRoCQueryResult value
     * @param ServiceResponseR9 $_executeRoCQueryResult the ExecuteRoCQueryResult
     * @return ServiceResponseR9
     */
    public function setExecuteRoCQueryResult($_executeRoCQueryResult)
    {
        return ($this->ExecuteRoCQueryResult = $_executeRoCQueryResult);
    }
    /**
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see LRSWsdlClass::__set_state()
     * @uses LRSWsdlClass::__set_state()
     * @param array $_array the exported values
     * @return LRSStructExecuteRoCQueryResponse
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
