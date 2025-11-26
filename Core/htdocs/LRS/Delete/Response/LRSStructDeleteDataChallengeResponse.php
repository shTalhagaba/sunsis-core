<?php
/**
 * File for class LRSStructDeleteDataChallengeResponse
 * @package LRS
 * @subpackage Structs
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
/**
 * This class stands for LRSStructDeleteDataChallengeResponse originally named DeleteDataChallengeResponse
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://compact-soft.com/projects/tempuri.org.xsd}
 * @package LRS
 * @subpackage Structs
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
class LRSStructDeleteDataChallengeResponse extends LRSStructServiceResponseR9
{
    /**
     * The DeleteDataChallengeResult
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * - from schema : {@link http://compact-soft.com/projects/tempuri.org.xsd}
     * @var ServiceResponseR9
     */
    public $DeleteDataChallengeResult;
    /**
     * Constructor method for DeleteDataChallengeResponse
     * @see parent::__construct()
     * @param ServiceResponseR9 $_deleteDataChallengeResult
     * @return LRSStructDeleteDataChallengeResponse
     */
    public function __construct($_deleteDataChallengeResult = NULL)
    {
        LRSWsdlClass::__construct(array('DeleteDataChallengeResult'=>$_deleteDataChallengeResult),false);
    }
    /**
     * Get DeleteDataChallengeResult value
     * @return ServiceResponseR9|null
     */
    public function getDeleteDataChallengeResult()
    {
        return $this->DeleteDataChallengeResult;
    }
    /**
     * Set DeleteDataChallengeResult value
     * @param ServiceResponseR9 $_deleteDataChallengeResult the DeleteDataChallengeResult
     * @return ServiceResponseR9
     */
    public function setDeleteDataChallengeResult($_deleteDataChallengeResult)
    {
        return ($this->DeleteDataChallengeResult = $_deleteDataChallengeResult);
    }
    /**
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see LRSWsdlClass::__set_state()
     * @uses LRSWsdlClass::__set_state()
     * @param array $_array the exported values
     * @return LRSStructDeleteDataChallengeResponse
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
