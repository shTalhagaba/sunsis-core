<?php
/**
 * File for class LRSStructUpdateLearnerByUlnKeyResponse
 * @package LRS
 * @subpackage Structs
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
/**
 * This class stands for LRSStructUpdateLearnerByUlnKeyResponse originally named UpdateLearnerByUlnKeyResponse
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://compact-soft.com/projects/tempuri.org.xsd}
 * @package LRS
 * @subpackage Structs
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
class LRSStructUpdateLearnerByUlnKeyResponse extends LRSWsdlClass
{
    /**
     * The UpdateLearnerByUlnKeyResult
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var LRSStructArrayOfUpdateLearnerResponseItem
     */
    public $UpdateLearnerByUlnKeyResult;
    /**
     * Constructor method for UpdateLearnerByUlnKeyResponse
     * @see parent::__construct()
     * @param LRSStructArrayOfUpdateLearnerResponseItem $_updateLearnerByUlnKeyResult
     * @return LRSStructUpdateLearnerByUlnKeyResponse
     */
    public function __construct($_updateLearnerByUlnKeyResult = NULL)
    {
        parent::__construct(array('UpdateLearnerByUlnKeyResult'=>($_updateLearnerByUlnKeyResult instanceof LRSStructArrayOfUpdateLearnerResponseItem)?$_updateLearnerByUlnKeyResult:new LRSStructArrayOfUpdateLearnerResponseItem($_updateLearnerByUlnKeyResult)),false);
    }
    /**
     * Get UpdateLearnerByUlnKeyResult value
     * @return LRSStructArrayOfUpdateLearnerResponseItem|null
     */
    public function getUpdateLearnerByUlnKeyResult()
    {
        return $this->UpdateLearnerByUlnKeyResult;
    }
    /**
     * Set UpdateLearnerByUlnKeyResult value
     * @param LRSStructArrayOfUpdateLearnerResponseItem $_updateLearnerByUlnKeyResult the UpdateLearnerByUlnKeyResult
     * @return LRSStructArrayOfUpdateLearnerResponseItem
     */
    public function setUpdateLearnerByUlnKeyResult($_updateLearnerByUlnKeyResult)
    {
        return ($this->UpdateLearnerByUlnKeyResult = $_updateLearnerByUlnKeyResult);
    }
    /**
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see LRSWsdlClass::__set_state()
     * @uses LRSWsdlClass::__set_state()
     * @param array $_array the exported values
     * @return LRSStructUpdateLearnerByUlnKeyResponse
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
