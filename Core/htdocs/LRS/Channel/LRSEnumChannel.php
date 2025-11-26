<?php
/**
 * File for class LRSEnumChannel
 * @package LRS
 * @subpackage Enumerations
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
/**
 * This class stands for LRSEnumChannel originally named Channel
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://compact-soft.com/projects/Amor.Qcf.Common.xsd}
 * @package LRS
 * @subpackage Enumerations
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
class LRSEnumChannel extends LRSWsdlClass
{
    /**
     * Constant for value 'None'
     * @return string 'None'
     */
    const VALUE_NONE = 'None';
    /**
     * Constant for value 'Batch'
     * @return string 'Batch'
     */
    const VALUE_BATCH = 'Batch';
    /**
     * Constant for value 'Portal'
     * @return string 'Portal'
     */
    const VALUE_PORTAL = 'Portal';
    /**
     * Constant for value 'WebServices'
     * @return string 'WebServices'
     */
    const VALUE_WEBSERVICES = 'WebServices';
    /**
     * Return true if value is allowed
     * @uses LRSEnumChannel::VALUE_NONE
     * @uses LRSEnumChannel::VALUE_BATCH
     * @uses LRSEnumChannel::VALUE_PORTAL
     * @uses LRSEnumChannel::VALUE_WEBSERVICES
     * @param mixed $_value value
     * @return bool true|false
     */
    public static function valueIsValid($_value)
    {
        return in_array($_value,array(LRSEnumChannel::VALUE_NONE,LRSEnumChannel::VALUE_BATCH,LRSEnumChannel::VALUE_PORTAL,LRSEnumChannel::VALUE_WEBSERVICES));
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
