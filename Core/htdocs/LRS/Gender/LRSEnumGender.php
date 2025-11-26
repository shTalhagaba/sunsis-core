<?php
/**
 * File for class LRSEnumGender
 * @package LRS
 * @subpackage Enumerations
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
/**
 * This class stands for LRSEnumGender originally named Gender
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://compact-soft.com/projects/api.lrs.qcf.gov.uk.model.xsd}
 * @package LRS
 * @subpackage Enumerations
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
class LRSEnumGender extends LRSWsdlClass
{
    /**
     * Constant for value 'None'
     * @return string 'None'
     */
    const VALUE_NONE = 'None';
    /**
     * Constant for value 'Unknown'
     * @return string 'Unknown'
     */
    const VALUE_UNKNOWN = 'Unknown';
    /**
     * Constant for value 'Male'
     * @return string 'Male'
     */
    const VALUE_MALE = 'Male';
    /**
     * Constant for value 'Female'
     * @return string 'Female'
     */
    const VALUE_FEMALE = 'Female';
    /**
     * Constant for value 'NotDetermined'
     * @return string 'NotDetermined'
     */
    const VALUE_NOTDETERMINED = 'NotDetermined';
    /**
     * Return true if value is allowed
     * @uses LRSEnumGender::VALUE_NONE
     * @uses LRSEnumGender::VALUE_UNKNOWN
     * @uses LRSEnumGender::VALUE_MALE
     * @uses LRSEnumGender::VALUE_FEMALE
     * @uses LRSEnumGender::VALUE_NOTDETERMINED
     * @param mixed $_value value
     * @return bool true|false
     */
    public static function valueIsValid($_value)
    {
        return in_array($_value,array(LRSEnumGender::VALUE_NONE,LRSEnumGender::VALUE_UNKNOWN,LRSEnumGender::VALUE_MALE,LRSEnumGender::VALUE_FEMALE,LRSEnumGender::VALUE_NOTDETERMINED));
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
