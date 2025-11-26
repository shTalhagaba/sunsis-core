<?php
/**
 * File for class LRSEnumUnitStatus
 * @package LRS
 * @subpackage Enumerations
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
/**
 * This class stands for LRSEnumUnitStatus originally named UnitStatus
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://compact-soft.com/projects/Amor.Qcf.Model.xsd}
 * @package LRS
 * @subpackage Enumerations
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
class LRSEnumUnitStatus extends LRSWsdlClass
{
    /**
     * Constant for value 'InProgress'
     * @return string 'InProgress'
     */
    const VALUE_INPROGRESS = 'InProgress';
    /**
     * Constant for value 'Banked'
     * @return string 'Banked'
     */
    const VALUE_BANKED = 'Banked';
    /**
     * Constant for value 'Withdrawn'
     * @return string 'Withdrawn'
     */
    const VALUE_WITHDRAWN = 'Withdrawn';
    /**
     * Return true if value is allowed
     * @uses LRSEnumUnitStatus::VALUE_INPROGRESS
     * @uses LRSEnumUnitStatus::VALUE_BANKED
     * @uses LRSEnumUnitStatus::VALUE_WITHDRAWN
     * @param mixed $_value value
     * @return bool true|false
     */
    public static function valueIsValid($_value)
    {
        return in_array($_value,array(LRSEnumUnitStatus::VALUE_INPROGRESS,LRSEnumUnitStatus::VALUE_BANKED,LRSEnumUnitStatus::VALUE_WITHDRAWN));
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
