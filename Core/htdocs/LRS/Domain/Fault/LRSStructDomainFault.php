<?php
/**
 * File for class LRSStructDomainFault
 * @package LRS
 * @subpackage Structs
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
/**
 * This class stands for LRSStructDomainFault originally named DomainFault
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://compact-soft.com/projects/api.lrs.qcf.gov.uk.faults.xsd}
 * @package LRS
 * @subpackage Structs
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
class LRSStructDomainFault extends LRSWsdlClass
{
    /**
     * The ErrorCode
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $ErrorCode;
    /**
     * The ErrorDetail
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var base64Binary
     */
    public $ErrorDetail;
    /**
     * Constructor method for DomainFault
     * @see parent::__construct()
     * @param string $_errorCode
     * @param base64Binary $_errorDetail
     * @return LRSStructDomainFault
     */
    public function __construct($_errorCode = NULL,$_errorDetail = NULL)
    {
        parent::__construct(array('ErrorCode'=>$_errorCode,'ErrorDetail'=>$_errorDetail),false);
    }
    /**
     * Get ErrorCode value
     * @return string|null
     */
    public function getErrorCode()
    {
        return $this->ErrorCode;
    }
    /**
     * Set ErrorCode value
     * @param string $_errorCode the ErrorCode
     * @return string
     */
    public function setErrorCode($_errorCode)
    {
        return ($this->ErrorCode = $_errorCode);
    }
    /**
     * Get ErrorDetail value
     * @return base64Binary|null
     */
    public function getErrorDetail()
    {
        return $this->ErrorDetail;
    }
    /**
     * Set ErrorDetail value
     * @param base64Binary $_errorDetail the ErrorDetail
     * @return base64Binary
     */
    public function setErrorDetail($_errorDetail)
    {
        return ($this->ErrorDetail = $_errorDetail);
    }
    /**
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see LRSWsdlClass::__set_state()
     * @uses LRSWsdlClass::__set_state()
     * @param array $_array the exported values
     * @return LRSStructDomainFault
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
