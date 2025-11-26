<?php
/**
 * File for class LRSStructListSavedRtaQueriesResponse
 * @package LRS
 * @subpackage Structs
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
/**
 * This class stands for LRSStructListSavedRtaQueriesResponse originally named ListSavedRtaQueriesResponse
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://compact-soft.com/projects/tempuri.org.xsd}
 * @package LRS
 * @subpackage Structs
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
class LRSStructListSavedRtaQueriesResponse extends LRSWsdlClass
{
    /**
     * The ListSavedRtaQueriesResult
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var LRSStructRtaListResponse
     */
    public $ListSavedRtaQueriesResult;
    /**
     * Constructor method for ListSavedRtaQueriesResponse
     * @see parent::__construct()
     * @param LRSStructRtaListResponse $_listSavedRtaQueriesResult
     * @return LRSStructListSavedRtaQueriesResponse
     */
    public function __construct($_listSavedRtaQueriesResult = NULL)
    {
        parent::__construct(array('ListSavedRtaQueriesResult'=>$_listSavedRtaQueriesResult),false);
    }
    /**
     * Get ListSavedRtaQueriesResult value
     * @return LRSStructRtaListResponse|null
     */
    public function getListSavedRtaQueriesResult()
    {
        return $this->ListSavedRtaQueriesResult;
    }
    /**
     * Set ListSavedRtaQueriesResult value
     * @param LRSStructRtaListResponse $_listSavedRtaQueriesResult the ListSavedRtaQueriesResult
     * @return LRSStructRtaListResponse
     */
    public function setListSavedRtaQueriesResult($_listSavedRtaQueriesResult)
    {
        return ($this->ListSavedRtaQueriesResult = $_listSavedRtaQueriesResult);
    }
    /**
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see LRSWsdlClass::__set_state()
     * @uses LRSWsdlClass::__set_state()
     * @param array $_array the exported values
     * @return LRSStructListSavedRtaQueriesResponse
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
