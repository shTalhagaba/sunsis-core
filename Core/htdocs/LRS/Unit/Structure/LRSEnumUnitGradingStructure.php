<?php
/**
 * File for class LRSEnumUnitGradingStructure
 * @package LRS
 * @subpackage Enumerations
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
/**
 * This class stands for LRSEnumUnitGradingStructure originally named UnitGradingStructure
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://compact-soft.com/projects/Amor.Qcf.Model.xsd}
 * @package LRS
 * @subpackage Enumerations
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
class LRSEnumUnitGradingStructure extends LRSWsdlClass
{
    /**
     * Constant for value 'Pass'
     * @return string 'Pass'
     */
    const VALUE_PASS = 'Pass';
    /**
     * Return true if value is allowed
     * @uses LRSEnumUnitGradingStructure::VALUE_PASS
     * @param mixed $_value value
     * @return bool true|false
     */
    public static function valueIsValid($_value)
    {
        return in_array($_value,array(LRSEnumUnitGradingStructure::VALUE_PASS));
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
