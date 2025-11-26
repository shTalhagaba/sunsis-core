<?php
/**
 * File for class LRSStructStoreLearnerKeyResponse
 * @package LRS
 * @subpackage Structs
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
/**
 * This class stands for LRSStructStoreLearnerKeyResponse originally named StoreLearnerKeyResponse
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://compact-soft.com/projects/tempuri.org.xsd}
 * @package LRS
 * @subpackage Structs
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
class LRSStructStoreLearnerKeyResponse extends LRSWsdlClass
{
    /**
     * The StoreLearnerKeyResult
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var LRSStructCreateLearnerKeyResponse
     */
    public $StoreLearnerKeyResult;
    /**
     * Constructor method for StoreLearnerKeyResponse
     * @see parent::__construct()
     * @param LRSStructCreateLearnerKeyResponse $_storeLearnerKeyResult
     * @return LRSStructStoreLearnerKeyResponse
     */
    public function __construct($_storeLearnerKeyResult = NULL)
    {
        parent::__construct(array('StoreLearnerKeyResult'=>$_storeLearnerKeyResult),false);
    }
    /**
     * Get StoreLearnerKeyResult value
     * @return LRSStructCreateLearnerKeyResponse|null
     */
    public function getStoreLearnerKeyResult()
    {
        return $this->StoreLearnerKeyResult;
    }
    /**
     * Set StoreLearnerKeyResult value
     * @param LRSStructCreateLearnerKeyResponse $_storeLearnerKeyResult the StoreLearnerKeyResult
     * @return LRSStructCreateLearnerKeyResponse
     */
    public function setStoreLearnerKeyResult($_storeLearnerKeyResult)
    {
        return ($this->StoreLearnerKeyResult = $_storeLearnerKeyResult);
    }
    /**
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see LRSWsdlClass::__set_state()
     * @uses LRSWsdlClass::__set_state()
     * @param array $_array the exported values
     * @return LRSStructStoreLearnerKeyResponse
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
