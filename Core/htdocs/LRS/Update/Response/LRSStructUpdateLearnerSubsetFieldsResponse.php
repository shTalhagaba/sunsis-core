<?php
/**
 * File for class LRSStructUpdateLearnerSubsetFieldsResponse
 * @package LRS
 * @subpackage Structs
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
/**
 * This class stands for LRSStructUpdateLearnerSubsetFieldsResponse originally named UpdateLearnerSubsetFieldsResponse
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://compact-soft.com/projects/tempuri.org.xsd}
 * @package LRS
 * @subpackage Structs
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
class LRSStructUpdateLearnerSubsetFieldsResponse extends LRSWsdlClass
{
    /**
     * The UpdateLearnerSubsetFieldsResult
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $UpdateLearnerSubsetFieldsResult;
    /**
     * Constructor method for UpdateLearnerSubsetFieldsResponse
     * @see parent::__construct()
     * @param string $_updateLearnerSubsetFieldsResult
     * @return LRSStructUpdateLearnerSubsetFieldsResponse
     */
    public function __construct($_updateLearnerSubsetFieldsResult = NULL)
    {
        parent::__construct(array('UpdateLearnerSubsetFieldsResult'=>$_updateLearnerSubsetFieldsResult),false);
    }
    /**
     * Get UpdateLearnerSubsetFieldsResult value
     * @return string|null
     */
    public function getUpdateLearnerSubsetFieldsResult()
    {
        return $this->UpdateLearnerSubsetFieldsResult;
    }
    /**
     * Set UpdateLearnerSubsetFieldsResult value
     * @param string $_updateLearnerSubsetFieldsResult the UpdateLearnerSubsetFieldsResult
     * @return string
     */
    public function setUpdateLearnerSubsetFieldsResult($_updateLearnerSubsetFieldsResult)
    {
        return ($this->UpdateLearnerSubsetFieldsResult = $_updateLearnerSubsetFieldsResult);
    }
    /**
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see LRSWsdlClass::__set_state()
     * @uses LRSWsdlClass::__set_state()
     * @param array $_array the exported values
     * @return LRSStructUpdateLearnerSubsetFieldsResponse
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
