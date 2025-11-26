<?php
/**
 * File for class EnumWageType
 * @package
 * @subpackage Enumerations
 * @date 2016-12-22
 */
/**
 * This class stands for EnumWageType originally named WageType
 * Meta informations extracted from the WSDL
 * - from schema : {@link https://soapapi.findapprenticeship.service.gov.uk/services/VacancyManagement/VacancyManagement51.svc?xsd=xsd2}
 * @package
 * @subpackage Enumerations
 * @date 2016-12-22
 */
class EnumWageType extends WsdlClass
{
    /**
     * Constant for value 'Text'
     * @return string 'Text'
     */
    const VALUE_TEXT = 'Text';
    /**
     * Constant for value 'Weekly'
     * @return string 'Weekly'
     */
    const VALUE_WEEKLY = 'Weekly';
    /**
     * Return true if value is allowed
     * @uses EnumWageType::VALUE_TEXT
     * @uses EnumWageType::VALUE_WEEKLY
     * @param mixed $_value value
     * @return bool true|false
     */
    public static function valueIsValid($_value)
    {
        return in_array($_value,array(EnumWageType::VALUE_TEXT,EnumWageType::VALUE_WEEKLY));
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
