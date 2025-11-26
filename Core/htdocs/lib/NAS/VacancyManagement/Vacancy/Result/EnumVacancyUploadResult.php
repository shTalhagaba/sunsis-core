<?php
/**
 * File for class EnumVacancyUploadResult
 * @package
 * @subpackage Enumerations
 * @date 2016-12-22
 */
/**
 * This class stands for EnumVacancyUploadResult originally named VacancyUploadResult
 * Meta informations extracted from the WSDL
 * - from schema : {@link https://soapapi.findapprenticeship.service.gov.uk/services/VacancyManagement/VacancyManagement51.svc?xsd=xsd2}
 * @package
 * @subpackage Enumerations
 * @date 2016-12-22
 */
class EnumVacancyUploadResult extends WsdlClass
{
    /**
     * Constant for value 'Unknown'
     * @return string 'Unknown'
     */
    const VALUE_UNKNOWN = 'Unknown';
    /**
     * Constant for value 'Success'
     * @return string 'Success'
     */
    const VALUE_SUCCESS = 'Success';
    /**
     * Constant for value 'Failure'
     * @return string 'Failure'
     */
    const VALUE_FAILURE = 'Failure';
    /**
     * Return true if value is allowed
     * @uses EnumVacancyUploadResult::VALUE_UNKNOWN
     * @uses EnumVacancyUploadResult::VALUE_SUCCESS
     * @uses EnumVacancyUploadResult::VALUE_FAILURE
     * @param mixed $_value value
     * @return bool true|false
     */
    public static function valueIsValid($_value)
    {
        return in_array($_value,array(EnumVacancyUploadResult::VALUE_UNKNOWN,EnumVacancyUploadResult::VALUE_SUCCESS,EnumVacancyUploadResult::VALUE_FAILURE));
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
