<?php
/**
 * File for class StructVacancyUploadResponse
 * @package
 * @subpackage Structs
 * @date 2016-12-22
 */
/**
 * This class stands for StructVacancyUploadResponse originally named VacancyUploadResponse
 * Meta informations extracted from the WSDL
 * - from schema : {@link https://soapapi.findapprenticeship.service.gov.uk/services/VacancyManagement/VacancyManagement51.svc?xsd=xsd0}
 * @package
 * @subpackage Structs
 * @date 2016-12-22
 */
class StructVacancyUploadResponse extends WsdlClass
{
    /**
     * The MessageId
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - pattern : [\da-fA-F]{8}-[\da-fA-F]{4}-[\da-fA-F]{4}-[\da-fA-F]{4}-[\da-fA-F]{12}
     * @var string
     */
    public $MessageId;
    /**
     * The Vacancies
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var StructArrayOfVacancyUploadResultData
     */
    public $Vacancies;
    /**
     * Constructor method for VacancyUploadResponse
     * @see parent::__construct()
     * @param string $_messageId
     * @param StructArrayOfVacancyUploadResultData $_vacancies
     * @return StructVacancyUploadResponse
     */
    public function __construct($_messageId = NULL,$_vacancies = NULL)
    {
        parent::__construct(array('MessageId'=>$_messageId,'Vacancies'=>($_vacancies instanceof StructArrayOfVacancyUploadResultData)?$_vacancies:new StructArrayOfVacancyUploadResultData($_vacancies)),false);
    }
    /**
     * Get MessageId value
     * @return string|null
     */
    public function getMessageId()
    {
        return $this->MessageId;
    }
    /**
     * Set MessageId value
     * @param string $_messageId the MessageId
     * @return string
     */
    public function setMessageId($_messageId)
    {
        return ($this->MessageId = $_messageId);
    }
    /**
     * Get Vacancies value
     * @return StructArrayOfVacancyUploadResultData|null
     */
    public function getVacancies()
    {
        return $this->Vacancies;
    }
    /**
     * Set Vacancies value
     * @param StructArrayOfVacancyUploadResultData $_vacancies the Vacancies
     * @return StructArrayOfVacancyUploadResultData
     */
    public function setVacancies($_vacancies)
    {
        return ($this->Vacancies = $_vacancies);
    }
    /**
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see WsdlClass::__set_state()
     * @uses WsdlClass::__set_state()
     * @param array $_array the exported values
     * @return StructVacancyUploadResponse
     */
    public static function __set_state(array $_array,$_className = __CLASS__)
    {
        return parent::__set_state($_array,$_className);
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
