<?php
/**
 * File for class LRSEnumUnitAvailability
 * @package LRS
 * @subpackage Enumerations
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
/**
 * This class stands for LRSEnumUnitAvailability originally named UnitAvailability
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://compact-soft.com/projects/api.lrs.qcf.gov.uk.model.xsd}
 * @package LRS
 * @subpackage Enumerations
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
class LRSEnumUnitAvailability extends LRSWsdlClass
{
    /**
     * Constant for value 'Restricted'
     * @return string 'Restricted'
     */
    const VALUE_RESTRICTED = 'Restricted';
    /**
     * Constant for value 'Shared'
     * @return string 'Shared'
     */
    const VALUE_SHARED = 'Shared';
    /**
     * Return true if value is allowed
     * @uses LRSEnumUnitAvailability::VALUE_RESTRICTED
     * @uses LRSEnumUnitAvailability::VALUE_SHARED
     * @param mixed $_value value
     * @return bool true|false
     */
    public static function valueIsValid($_value)
    {
        return in_array($_value,array(LRSEnumUnitAvailability::VALUE_RESTRICTED,LRSEnumUnitAvailability::VALUE_SHARED));
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
