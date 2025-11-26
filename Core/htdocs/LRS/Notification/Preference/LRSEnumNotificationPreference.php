<?php
/**
 * File for class LRSEnumNotificationPreference
 * @package LRS
 * @subpackage Enumerations
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
/**
 * This class stands for LRSEnumNotificationPreference originally named NotificationPreference
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://compact-soft.com/projects/api.lrs.qcf.gov.uk.model.xsd}
 * @package LRS
 * @subpackage Enumerations
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
class LRSEnumNotificationPreference extends LRSWsdlClass
{
    /**
     * Constant for value 'None'
     * @return string 'None'
     */
    const VALUE_NONE = 'None';
    /**
     * Constant for value 'Sms'
     * @return string 'Sms'
     */
    const VALUE_SMS = 'Sms';
    /**
     * Constant for value 'Email'
     * @return string 'Email'
     */
    const VALUE_EMAIL = 'Email';
    /**
     * Return true if value is allowed
     * @uses LRSEnumNotificationPreference::VALUE_NONE
     * @uses LRSEnumNotificationPreference::VALUE_SMS
     * @uses LRSEnumNotificationPreference::VALUE_EMAIL
     * @param mixed $_value value
     * @return bool true|false
     */
    public static function valueIsValid($_value)
    {
        return in_array($_value,array(LRSEnumNotificationPreference::VALUE_NONE,LRSEnumNotificationPreference::VALUE_SMS,LRSEnumNotificationPreference::VALUE_EMAIL));
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
