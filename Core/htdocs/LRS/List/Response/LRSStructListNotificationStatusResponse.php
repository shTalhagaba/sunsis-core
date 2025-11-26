<?php
/**
 * File for class LRSStructListNotificationStatusResponse
 * @package LRS
 * @subpackage Structs
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
/**
 * This class stands for LRSStructListNotificationStatusResponse originally named ListNotificationStatusResponse
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://compact-soft.com/projects/tempuri.org.xsd}
 * @package LRS
 * @subpackage Structs
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
class LRSStructListNotificationStatusResponse extends LRSStructServiceResponseR9
{
    /**
     * The NotificationPreference
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var LRSEnumNotificationPreference
     */
    public $NotificationPreference;
    /**
     * The ListNotificationStatusResult
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * - from schema : {@link http://compact-soft.com/projects/tempuri.org.xsd}
     * @var ServiceResponseR9
     */
    public $ListNotificationStatusResult;
    /**
     * Constructor method for ListNotificationStatusResponse
     * @see parent::__construct()
     * @param LRSEnumNotificationPreference $_notificationPreference
     * @param ServiceResponseR9 $_listNotificationStatusResult
     * @return LRSStructListNotificationStatusResponse
     */
    public function __construct($_notificationPreference = NULL,$_listNotificationStatusResult = NULL)
    {
        LRSWsdlClass::__construct(array('NotificationPreference'=>$_notificationPreference,'ListNotificationStatusResult'=>$_listNotificationStatusResult),false);
    }
    /**
     * Get NotificationPreference value
     * @return LRSEnumNotificationPreference|null
     */
    public function getNotificationPreference()
    {
        return $this->NotificationPreference;
    }
    /**
     * Set NotificationPreference value
     * @uses LRSEnumNotificationPreference::valueIsValid()
     * @param LRSEnumNotificationPreference $_notificationPreference the NotificationPreference
     * @return LRSEnumNotificationPreference
     */
    public function setNotificationPreference($_notificationPreference)
    {
        if(!LRSEnumNotificationPreference::valueIsValid($_notificationPreference))
        {
            return false;
        }
        return ($this->NotificationPreference = $_notificationPreference);
    }
    /**
     * Get ListNotificationStatusResult value
     * @return ServiceResponseR9|null
     */
    public function getListNotificationStatusResult()
    {
        return $this->ListNotificationStatusResult;
    }
    /**
     * Set ListNotificationStatusResult value
     * @param ServiceResponseR9 $_listNotificationStatusResult the ListNotificationStatusResult
     * @return ServiceResponseR9
     */
    public function setListNotificationStatusResult($_listNotificationStatusResult)
    {
        return ($this->ListNotificationStatusResult = $_listNotificationStatusResult);
    }
    /**
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see LRSWsdlClass::__set_state()
     * @uses LRSWsdlClass::__set_state()
     * @param array $_array the exported values
     * @return LRSStructListNotificationStatusResponse
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
