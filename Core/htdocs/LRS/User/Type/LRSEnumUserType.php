<?php
/**
 * File for class LRSEnumUserType
 * @package LRS
 * @subpackage Enumerations
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
/**
 * This class stands for LRSEnumUserType originally named UserType
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://compact-soft.com/projects/api.lrs.qcf.gov.uk.model.xsd}
 * @package LRS
 * @subpackage Enumerations
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
class LRSEnumUserType extends LRSWsdlClass
{
    /**
     * Constant for value 'Unknown'
     * @return string 'Unknown'
     */
    const VALUE_UNKNOWN = 'Unknown';
    /**
     * Constant for value 'LNR'
     * @return string 'LNR'
     */
    const VALUE_LNR = 'LNR';
    /**
     * Constant for value 'ORG'
     * @return string 'ORG'
     */
    const VALUE_ORG = 'ORG';
    /**
     * Constant for value 'SER'
     * @return string 'SER'
     */
    const VALUE_SER = 'SER';
    /**
     * Return true if value is allowed
     * @uses LRSEnumUserType::VALUE_UNKNOWN
     * @uses LRSEnumUserType::VALUE_LNR
     * @uses LRSEnumUserType::VALUE_ORG
     * @uses LRSEnumUserType::VALUE_SER
     * @param mixed $_value value
     * @return bool true|false
     */
    public static function valueIsValid($_value)
    {
        return in_array($_value,array(LRSEnumUserType::VALUE_UNKNOWN,LRSEnumUserType::VALUE_LNR,LRSEnumUserType::VALUE_ORG,LRSEnumUserType::VALUE_SER));
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
