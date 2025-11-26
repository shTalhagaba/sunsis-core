<?php
/**
 * File for class LRSEnumAbilityToShare
 * @package LRS
 * @subpackage Enumerations
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
/**
 * This class stands for LRSEnumAbilityToShare originally named AbilityToShare
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://compact-soft.com/projects/api.lrs.qcf.gov.uk.model.xsd}
 * @package LRS
 * @subpackage Enumerations
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
class LRSEnumAbilityToShare extends LRSWsdlClass
{
    /**
     * Constant for value 'FPNNotSeen'
     * @return string 'FPNNotSeen'
     */
    const VALUE_FPNNOTSEEN = 'FPNNotSeen';
    /**
     * Constant for value 'FPNSeenShareData'
     * @return string 'FPNSeenShareData'
     */
    const VALUE_FPNSEENSHAREDATA = 'FPNSeenShareData';
    /**
     * Constant for value 'FPNSeenUnableShareData'
     * @return string 'FPNSeenUnableShareData'
     */
    const VALUE_FPNSEENUNABLESHAREDATA = 'FPNSeenUnableShareData';
    /**
     * Return true if value is allowed
     * @uses LRSEnumAbilityToShare::VALUE_FPNNOTSEEN
     * @uses LRSEnumAbilityToShare::VALUE_FPNSEENSHAREDATA
     * @uses LRSEnumAbilityToShare::VALUE_FPNSEENUNABLESHAREDATA
     * @param mixed $_value value
     * @return bool true|false
     */
    public static function valueIsValid($_value)
    {
        return in_array($_value,array(LRSEnumAbilityToShare::VALUE_FPNNOTSEEN,LRSEnumAbilityToShare::VALUE_FPNSEENSHAREDATA,LRSEnumAbilityToShare::VALUE_FPNSEENUNABLESHAREDATA));
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
