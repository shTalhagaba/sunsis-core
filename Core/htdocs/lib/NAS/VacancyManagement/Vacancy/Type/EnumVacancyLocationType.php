<?php
/**
 * File for class EnumVacancyLocationType
 * @package
 * @subpackage Enumerations
 * @date 2016-12-22
 */
/**
 * This class stands for EnumVacancyLocationType originally named VacancyLocationType
 * Meta informations extracted from the WSDL
 * - from schema : {@link https://soapapi.findapprenticeship.service.gov.uk/services/VacancyManagement/VacancyManagement51.svc?xsd=xsd2}
 * @package
 * @subpackage Enumerations
 * @date 2016-12-22
 */
class EnumVacancyLocationType extends WsdlClass
{
    /**
     * Constant for value 'Standard'
     * @return string 'Standard'
     */
    const VALUE_STANDARD = 'Standard';
    /**
     * Constant for value 'MultipleLocation'
     * @return string 'MultipleLocation'
     */
    const VALUE_MULTIPLELOCATION = 'MultipleLocation';
    /**
     * Constant for value 'National'
     * @return string 'National'
     */
    const VALUE_NATIONAL = 'National';
    /**
     * Return true if value is allowed
     * @uses EnumVacancyLocationType::VALUE_STANDARD
     * @uses EnumVacancyLocationType::VALUE_MULTIPLELOCATION
     * @uses EnumVacancyLocationType::VALUE_NATIONAL
     * @param mixed $_value value
     * @return bool true|false
     */
    public static function valueIsValid($_value)
    {
        return in_array($_value,array(EnumVacancyLocationType::VALUE_STANDARD,EnumVacancyLocationType::VALUE_MULTIPLELOCATION,EnumVacancyLocationType::VALUE_NATIONAL));
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
