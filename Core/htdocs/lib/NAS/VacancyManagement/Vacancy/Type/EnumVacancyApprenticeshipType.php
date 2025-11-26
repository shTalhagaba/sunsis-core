<?php
/**
 * File for class EnumVacancyApprenticeshipType
 * @package
 * @subpackage Enumerations
 * @date 2016-12-22
 */
/**
 * This class stands for EnumVacancyApprenticeshipType originally named VacancyApprenticeshipType
 * Meta informations extracted from the WSDL
 * - from schema : {@link https://soapapi.findapprenticeship.service.gov.uk/services/VacancyManagement/VacancyManagement51.svc?xsd=xsd2}
 * @package
 * @subpackage Enumerations
 * @date 2016-12-22
 */
class EnumVacancyApprenticeshipType extends WsdlClass
{
    /**
     * Constant for value 'Unspecified'
     * @return string 'Unspecified'
     */
    const VALUE_UNSPECIFIED = 'Unspecified';
    /**
     * Constant for value 'IntermediateLevelApprenticeship'
     * @return string 'IntermediateLevelApprenticeship'
     */
    const VALUE_INTERMEDIATELEVELAPPRENTICESHIP = 'IntermediateLevelApprenticeship';
    /**
     * Constant for value 'AdvancedLevelApprenticeship'
     * @return string 'AdvancedLevelApprenticeship'
     */
    const VALUE_ADVANCEDLEVELAPPRENTICESHIP = 'AdvancedLevelApprenticeship';
    /**
     * Constant for value 'HigherApprenticeship'
     * @return string 'HigherApprenticeship'
     */
    const VALUE_HIGHERAPPRENTICESHIP = 'HigherApprenticeship';
    /**
     * Constant for value 'Traineeship'
     * @return string 'Traineeship'
     */
    const VALUE_TRAINEESHIP = 'Traineeship';
    /**
     * Return true if value is allowed
     * @uses EnumVacancyApprenticeshipType::VALUE_UNSPECIFIED
     * @uses EnumVacancyApprenticeshipType::VALUE_INTERMEDIATELEVELAPPRENTICESHIP
     * @uses EnumVacancyApprenticeshipType::VALUE_ADVANCEDLEVELAPPRENTICESHIP
     * @uses EnumVacancyApprenticeshipType::VALUE_HIGHERAPPRENTICESHIP
     * @uses EnumVacancyApprenticeshipType::VALUE_TRAINEESHIP
     * @param mixed $_value value
     * @return bool true|false
     */
    public static function valueIsValid($_value)
    {
        return in_array($_value,array(EnumVacancyApprenticeshipType::VALUE_UNSPECIFIED,EnumVacancyApprenticeshipType::VALUE_INTERMEDIATELEVELAPPRENTICESHIP,EnumVacancyApprenticeshipType::VALUE_ADVANCEDLEVELAPPRENTICESHIP,EnumVacancyApprenticeshipType::VALUE_HIGHERAPPRENTICESHIP,EnumVacancyApprenticeshipType::VALUE_TRAINEESHIP));
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
