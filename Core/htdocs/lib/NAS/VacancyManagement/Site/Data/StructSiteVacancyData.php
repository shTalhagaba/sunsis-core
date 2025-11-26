<?php
/**
 * File for class StructSiteVacancyData
 * @package
 * @subpackage Structs
 * @date 2016-12-22
 */
/**
 * This class stands for StructSiteVacancyData originally named SiteVacancyData
 * Meta informations extracted from the WSDL
 * - from schema : {@link https://soapapi.findapprenticeship.service.gov.uk/services/VacancyManagement/VacancyManagement51.svc?xsd=xsd0}
 * @package
 * @subpackage Structs
 * @date 2016-12-22
 */
class StructSiteVacancyData extends WsdlClass
{
    /**
     * The AddressDetails
     * Meta informations extracted from the WSDL
     * - nillable : true
     * @var StructAddressData
     */
    public $AddressDetails;
    /**
     * The NumberOfVacancies
     * @var short
     */
    public $NumberOfVacancies;
    /**
     * The EmployerWebsite
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $EmployerWebsite;
    /**
     * Constructor method for SiteVacancyData
     * @see parent::__construct()
     * @param StructAddressData $_addressDetails
     * @param short $_numberOfVacancies
     * @param string $_employerWebsite
     * @return StructSiteVacancyData
     */
    public function __construct($_addressDetails = NULL,$_numberOfVacancies = NULL,$_employerWebsite = NULL)
    {
        parent::__construct(array('AddressDetails'=>$_addressDetails,'NumberOfVacancies'=>$_numberOfVacancies,'EmployerWebsite'=>$_employerWebsite),false);
    }
    /**
     * Get AddressDetails value
     * @return StructAddressData|null
     */
    public function getAddressDetails()
    {
        return $this->AddressDetails;
    }
    /**
     * Set AddressDetails value
     * @param StructAddressData $_addressDetails the AddressDetails
     * @return StructAddressData
     */
    public function setAddressDetails($_addressDetails)
    {
        return ($this->AddressDetails = $_addressDetails);
    }
    /**
     * Get NumberOfVacancies value
     * @return short|null
     */
    public function getNumberOfVacancies()
    {
        return $this->NumberOfVacancies;
    }
    /**
     * Set NumberOfVacancies value
     * @param short $_numberOfVacancies the NumberOfVacancies
     * @return short
     */
    public function setNumberOfVacancies($_numberOfVacancies)
    {
        return ($this->NumberOfVacancies = $_numberOfVacancies);
    }
    /**
     * Get EmployerWebsite value
     * @return string|null
     */
    public function getEmployerWebsite()
    {
        return $this->EmployerWebsite;
    }
    /**
     * Set EmployerWebsite value
     * @param string $_employerWebsite the EmployerWebsite
     * @return string
     */
    public function setEmployerWebsite($_employerWebsite)
    {
        return ($this->EmployerWebsite = $_employerWebsite);
    }
    /**
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see WsdlClass::__set_state()
     * @uses WsdlClass::__set_state()
     * @param array $_array the exported values
     * @return StructSiteVacancyData
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
