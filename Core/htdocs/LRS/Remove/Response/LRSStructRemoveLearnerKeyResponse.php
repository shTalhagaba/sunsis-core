<?php
/**
 * File for class LRSStructRemoveLearnerKeyResponse
 * @package LRS
 * @subpackage Structs
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
/**
 * This class stands for LRSStructRemoveLearnerKeyResponse originally named RemoveLearnerKeyResponse
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://compact-soft.com/projects/tempuri.org.xsd}
 * @package LRS
 * @subpackage Structs
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
class LRSStructRemoveLearnerKeyResponse extends LRSWsdlClass
{
    /**
     * The RemoveLearnerKeyResult
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var LRSStructDeleteLearnerKeyResponse
     */
    public $RemoveLearnerKeyResult;
    /**
     * Constructor method for RemoveLearnerKeyResponse
     * @see parent::__construct()
     * @param LRSStructDeleteLearnerKeyResponse $_removeLearnerKeyResult
     * @return LRSStructRemoveLearnerKeyResponse
     */
    public function __construct($_removeLearnerKeyResult = NULL)
    {
        parent::__construct(array('RemoveLearnerKeyResult'=>$_removeLearnerKeyResult),false);
    }
    /**
     * Get RemoveLearnerKeyResult value
     * @return LRSStructDeleteLearnerKeyResponse|null
     */
    public function getRemoveLearnerKeyResult()
    {
        return $this->RemoveLearnerKeyResult;
    }
    /**
     * Set RemoveLearnerKeyResult value
     * @param LRSStructDeleteLearnerKeyResponse $_removeLearnerKeyResult the RemoveLearnerKeyResult
     * @return LRSStructDeleteLearnerKeyResponse
     */
    public function setRemoveLearnerKeyResult($_removeLearnerKeyResult)
    {
        return ($this->RemoveLearnerKeyResult = $_removeLearnerKeyResult);
    }
    /**
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see LRSWsdlClass::__set_state()
     * @uses LRSWsdlClass::__set_state()
     * @param array $_array the exported values
     * @return LRSStructRemoveLearnerKeyResponse
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
