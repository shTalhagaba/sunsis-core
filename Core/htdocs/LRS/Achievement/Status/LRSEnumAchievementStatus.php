<?php
/**
 * File for class LRSEnumAchievementStatus
 * @package LRS
 * @subpackage Enumerations
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
/**
 * This class stands for LRSEnumAchievementStatus originally named AchievementStatus
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://compact-soft.com/projects/Amor.Qcf.ModelCommon.xsd}
 * @package LRS
 * @subpackage Enumerations
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
class LRSEnumAchievementStatus extends LRSWsdlClass
{
    /**
     * Constant for value 'Final'
     * @return string 'Final'
     */
    const VALUE_FINAL = 'Final';
    /**
     * Constant for value 'Provisional'
     * @return string 'Provisional'
     */
    const VALUE_PROVISIONAL = 'Provisional';
    /**
     * Return true if value is allowed
     * @uses LRSEnumAchievementStatus::VALUE_FINAL
     * @uses LRSEnumAchievementStatus::VALUE_PROVISIONAL
     * @param mixed $_value value
     * @return bool true|false
     */
    public static function valueIsValid($_value)
    {
        return in_array($_value,array(LRSEnumAchievementStatus::VALUE_FINAL,LRSEnumAchievementStatus::VALUE_PROVISIONAL));
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
