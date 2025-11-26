<?php
/**
 * File for class StructVacancyUploadRequest
 * @package
 * @subpackage Structs
 * @date 2016-12-22
 */
/**
 * This class stands for StructVacancyUploadRequest originally named VacancyUploadRequest
 * Meta informations extracted from the WSDL
 * - from schema : {@link https://soapapi.findapprenticeship.service.gov.uk/services/VacancyManagement/VacancyManagement51.svc?xsd=xsd0}
 * @package
 * @subpackage Structs
 * @date 2016-12-22
 */
class StructVacancyUploadRequest extends WsdlClass
{
    /**
     * The Vacancies
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var StructArrayOfVacancyUploadData
     */
    public $Vacancies;
    /**
     * Constructor method for VacancyUploadRequest
     * @see parent::__construct()
     * @param StructArrayOfVacancyUploadData $_vacancies
     * @return StructVacancyUploadRequest
     */
    public function __construct($_vacancies = NULL)
    {
        parent::__construct(array('Vacancies'=>($_vacancies instanceof StructArrayOfVacancyUploadData)?$_vacancies:new StructArrayOfVacancyUploadData($_vacancies)),false);
    }
    /**
     * Get Vacancies value
     * @return StructArrayOfVacancyUploadData|null
     */
    public function getVacancies()
    {
        return $this->Vacancies;
    }
    /**
     * Set Vacancies value
     * @param StructArrayOfVacancyUploadData $_vacancies the Vacancies
     * @return StructArrayOfVacancyUploadData
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
     * @return StructVacancyUploadRequest
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
