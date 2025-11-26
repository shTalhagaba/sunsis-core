<?php
/**
 * File for class LRSStructListDataChallengeResponse
 * @package LRS
 * @subpackage Structs
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
/**
 * This class stands for LRSStructListDataChallengeResponse originally named ListDataChallengeResponse
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://compact-soft.com/projects/tempuri.org.xsd}
 * @package LRS
 * @subpackage Structs
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
class LRSStructListDataChallengeResponse extends LRSWsdlClass
{
    /**
     * The DataChallenges
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var LRSStructArrayOfListDataChallengeItem
     */
    public $DataChallenges;
    /**
     * The ResponseCode
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $ResponseCode;
    /**
     * The ListDataChallengeResult
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * - from schema : {@link http://compact-soft.com/projects/tempuri.org.xsd}
     * @var ListDataChallengeResponse
     */
    public $ListDataChallengeResult;
    /**
     * Constructor method for ListDataChallengeResponse
     * @see parent::__construct()
     * @param LRSStructArrayOfListDataChallengeItem $_dataChallenges
     * @param string $_responseCode
     * @param ListDataChallengeResponse $_listDataChallengeResult
     * @return LRSStructListDataChallengeResponse
     */
    public function __construct($_dataChallenges = NULL,$_responseCode = NULL,$_listDataChallengeResult = NULL)
    {
        parent::__construct(array('DataChallenges'=>($_dataChallenges instanceof LRSStructArrayOfListDataChallengeItem)?$_dataChallenges:new LRSStructArrayOfListDataChallengeItem($_dataChallenges),'ResponseCode'=>$_responseCode,'ListDataChallengeResult'=>$_listDataChallengeResult),false);
    }
    /**
     * Get DataChallenges value
     * @return LRSStructArrayOfListDataChallengeItem|null
     */
    public function getDataChallenges()
    {
        return $this->DataChallenges;
    }
    /**
     * Set DataChallenges value
     * @param LRSStructArrayOfListDataChallengeItem $_dataChallenges the DataChallenges
     * @return LRSStructArrayOfListDataChallengeItem
     */
    public function setDataChallenges($_dataChallenges)
    {
        return ($this->DataChallenges = $_dataChallenges);
    }
    /**
     * Get ResponseCode value
     * @return string|null
     */
    public function getResponseCode()
    {
        return $this->ResponseCode;
    }
    /**
     * Set ResponseCode value
     * @param string $_responseCode the ResponseCode
     * @return string
     */
    public function setResponseCode($_responseCode)
    {
        return ($this->ResponseCode = $_responseCode);
    }
    /**
     * Get ListDataChallengeResult value
     * @return ListDataChallengeResponse|null
     */
    public function getListDataChallengeResult()
    {
        return $this->ListDataChallengeResult;
    }
    /**
     * Set ListDataChallengeResult value
     * @param ListDataChallengeResponse $_listDataChallengeResult the ListDataChallengeResult
     * @return ListDataChallengeResponse
     */
    public function setListDataChallengeResult($_listDataChallengeResult)
    {
        return ($this->ListDataChallengeResult = $_listDataChallengeResult);
    }
    /**
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see LRSWsdlClass::__set_state()
     * @uses LRSWsdlClass::__set_state()
     * @param array $_array the exported values
     * @return LRSStructListDataChallengeResponse
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
