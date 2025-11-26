<?php
/**
 * File for class LRSEnumAwardingOrganisationStatus
 * @package LRS
 * @subpackage Enumerations
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
/**
 * This class stands for LRSEnumAwardingOrganisationStatus originally named AwardingOrganisationStatus
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://compact-soft.com/projects/api.lrs.qcf.gov.uk.model.xsd}
 * @package LRS
 * @subpackage Enumerations
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
class LRSEnumAwardingOrganisationStatus extends LRSWsdlClass
{
    /**
     * Constant for value 'Applying'
     * @return string 'Applying'
     */
    const VALUE_APPLYING = 'Applying';
    /**
     * Constant for value 'Applied'
     * @return string 'Applied'
     */
    const VALUE_APPLIED = 'Applied';
    /**
     * Constant for value 'Rejected'
     * @return string 'Rejected'
     */
    const VALUE_REJECTED = 'Rejected';
    /**
     * Constant for value 'Recognised'
     * @return string 'Recognised'
     */
    const VALUE_RECOGNISED = 'Recognised';
    /**
     * Constant for value 'Withdrawn'
     * @return string 'Withdrawn'
     */
    const VALUE_WITHDRAWN = 'Withdrawn';
    /**
     * Constant for value 'Surrendered'
     * @return string 'Surrendered'
     */
    const VALUE_SURRENDERED = 'Surrendered';
    /**
     * Return true if value is allowed
     * @uses LRSEnumAwardingOrganisationStatus::VALUE_APPLYING
     * @uses LRSEnumAwardingOrganisationStatus::VALUE_APPLIED
     * @uses LRSEnumAwardingOrganisationStatus::VALUE_REJECTED
     * @uses LRSEnumAwardingOrganisationStatus::VALUE_RECOGNISED
     * @uses LRSEnumAwardingOrganisationStatus::VALUE_WITHDRAWN
     * @uses LRSEnumAwardingOrganisationStatus::VALUE_SURRENDERED
     * @param mixed $_value value
     * @return bool true|false
     */
    public static function valueIsValid($_value)
    {
        return in_array($_value,array(LRSEnumAwardingOrganisationStatus::VALUE_APPLYING,LRSEnumAwardingOrganisationStatus::VALUE_APPLIED,LRSEnumAwardingOrganisationStatus::VALUE_REJECTED,LRSEnumAwardingOrganisationStatus::VALUE_RECOGNISED,LRSEnumAwardingOrganisationStatus::VALUE_WITHDRAWN,LRSEnumAwardingOrganisationStatus::VALUE_SURRENDERED));
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
