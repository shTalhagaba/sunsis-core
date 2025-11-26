<?php
/**
 * File for class LRSStructFindLearnerByUlnKeyResponse
 * @package LRS
 * @subpackage Structs
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
/**
 * This class stands for LRSStructFindLearnerByUlnKeyResponse originally named FindLearnerByUlnKeyResponse
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://compact-soft.com/projects/tempuri.org.xsd}
 * @package LRS
 * @subpackage Structs
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
class LRSStructFindLearnerByUlnKeyResponse extends LRSWsdlClass
{
    /**
     * The FindLearnerByUlnKeyResult
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var LRSStructLearnerByUlnKeyResponse
     */
    public $FindLearnerByUlnKeyResult;
    /**
     * Constructor method for FindLearnerByUlnKeyResponse
     * @see parent::__construct()
     * @param LRSStructLearnerByUlnKeyResponse $_findLearnerByUlnKeyResult
     * @return LRSStructFindLearnerByUlnKeyResponse
     */
    public function __construct($_findLearnerByUlnKeyResult = NULL)
    {
        parent::__construct(array('FindLearnerByUlnKeyResult'=>$_findLearnerByUlnKeyResult),false);
    }
    /**
     * Get FindLearnerByUlnKeyResult value
     * @return LRSStructLearnerByUlnKeyResponse|null
     */
    public function getFindLearnerByUlnKeyResult()
    {
        return $this->FindLearnerByUlnKeyResult;
    }
    /**
     * Set FindLearnerByUlnKeyResult value
     * @param LRSStructLearnerByUlnKeyResponse $_findLearnerByUlnKeyResult the FindLearnerByUlnKeyResult
     * @return LRSStructLearnerByUlnKeyResponse
     */
    public function setFindLearnerByUlnKeyResult($_findLearnerByUlnKeyResult)
    {
        return ($this->FindLearnerByUlnKeyResult = $_findLearnerByUlnKeyResult);
    }
    /**
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see LRSWsdlClass::__set_state()
     * @uses LRSWsdlClass::__set_state()
     * @param array $_array the exported values
     * @return LRSStructFindLearnerByUlnKeyResponse
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
