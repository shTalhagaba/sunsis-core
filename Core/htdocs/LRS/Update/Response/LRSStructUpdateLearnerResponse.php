<?php
/**
 * File for class LRSStructUpdateLearnerResponse
 * @package LRS
 * @subpackage Structs
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
/**
 * This class stands for LRSStructUpdateLearnerResponse originally named UpdateLearnerResponse
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://compact-soft.com/projects/tempuri.org.xsd}
 * @package LRS
 * @subpackage Structs
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
class LRSStructUpdateLearnerResponse extends LRSWsdlClass
{
    /**
     * The UpdateLearnerResult
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var LRSStructArrayOfUpdateLearnerResponseItem
     */
    public $UpdateLearnerResult;
    /**
     * Constructor method for UpdateLearnerResponse
     * @see parent::__construct()
     * @param LRSStructArrayOfUpdateLearnerResponseItem $_updateLearnerResult
     * @return LRSStructUpdateLearnerResponse
     */
    public function __construct($_updateLearnerResult = NULL)
    {
        parent::__construct(array('UpdateLearnerResult'=>($_updateLearnerResult instanceof LRSStructArrayOfUpdateLearnerResponseItem)?$_updateLearnerResult:new LRSStructArrayOfUpdateLearnerResponseItem($_updateLearnerResult)),false);
    }
    /**
     * Get UpdateLearnerResult value
     * @return LRSStructArrayOfUpdateLearnerResponseItem|null
     */
    public function getUpdateLearnerResult()
    {
        return $this->UpdateLearnerResult;
    }
    /**
     * Set UpdateLearnerResult value
     * @param LRSStructArrayOfUpdateLearnerResponseItem $_updateLearnerResult the UpdateLearnerResult
     * @return LRSStructArrayOfUpdateLearnerResponseItem
     */
    public function setUpdateLearnerResult($_updateLearnerResult)
    {
        return ($this->UpdateLearnerResult = $_updateLearnerResult);
    }
    /**
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see LRSWsdlClass::__set_state()
     * @uses LRSWsdlClass::__set_state()
     * @param array $_array the exported values
     * @return LRSStructUpdateLearnerResponse
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
