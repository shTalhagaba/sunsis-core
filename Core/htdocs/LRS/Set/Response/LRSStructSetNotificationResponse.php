<?php
/**
 * File for class LRSStructSetNotificationResponse
 * @package LRS
 * @subpackage Structs
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
/**
 * This class stands for LRSStructSetNotificationResponse originally named SetNotificationResponse
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://compact-soft.com/projects/tempuri.org.xsd}
 * @package LRS
 * @subpackage Structs
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
class LRSStructSetNotificationResponse extends LRSWsdlClass
{
    /**
     * The SetNotificationResult
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $SetNotificationResult;
    /**
     * Constructor method for SetNotificationResponse
     * @see parent::__construct()
     * @param string $_setNotificationResult
     * @return LRSStructSetNotificationResponse
     */
    public function __construct($_setNotificationResult = NULL)
    {
        parent::__construct(array('SetNotificationResult'=>$_setNotificationResult),false);
    }
    /**
     * Get SetNotificationResult value
     * @return string|null
     */
    public function getSetNotificationResult()
    {
        return $this->SetNotificationResult;
    }
    /**
     * Set SetNotificationResult value
     * @param string $_setNotificationResult the SetNotificationResult
     * @return string
     */
    public function setSetNotificationResult($_setNotificationResult)
    {
        return ($this->SetNotificationResult = $_setNotificationResult);
    }
    /**
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see LRSWsdlClass::__set_state()
     * @uses LRSWsdlClass::__set_state()
     * @param array $_array the exported values
     * @return LRSStructSetNotificationResponse
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
