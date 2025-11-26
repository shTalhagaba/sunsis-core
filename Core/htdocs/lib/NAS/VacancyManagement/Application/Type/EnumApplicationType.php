<?php
/**
 * File for class EnumApplicationType
 * @package
 * @subpackage Enumerations
 * @date 2016-12-22
 */
/**
 * This class stands for EnumApplicationType originally named ApplicationType
 * Meta informations extracted from the WSDL
 * - from schema : {@link https://soapapi.findapprenticeship.service.gov.uk/services/VacancyManagement/VacancyManagement51.svc?xsd=xsd2}
 * @package
 * @subpackage Enumerations
 * @date 2016-12-22
 */
class EnumApplicationType extends WsdlClass
{
    /**
     * Constant for value 'Online'
     * @return string 'Online'
     */
    const VALUE_ONLINE = 'Online';
    /**
     * Constant for value 'Offline'
     * @return string 'Offline'
     */
    const VALUE_OFFLINE = 'Offline';
    /**
     * Return true if value is allowed
     * @uses EnumApplicationType::VALUE_ONLINE
     * @uses EnumApplicationType::VALUE_OFFLINE
     * @param mixed $_value value
     * @return bool true|false
     */
    public static function valueIsValid($_value)
    {
        return in_array($_value,array(EnumApplicationType::VALUE_ONLINE,EnumApplicationType::VALUE_OFFLINE));
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
