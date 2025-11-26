<?php
/**
 * File for class StructVacancyUploadData
 * @package
 * @subpackage Structs
 * @date 2016-12-22
 */
/**
 * This class stands for StructVacancyUploadData originally named VacancyUploadData
 * Meta informations extracted from the WSDL
 * - from schema : {@link https://soapapi.findapprenticeship.service.gov.uk/services/VacancyManagement/VacancyManagement51.svc?xsd=xsd0}
 * @package
 * @subpackage Structs
 * @date 2016-12-22
 */
class StructVacancyUploadData extends WsdlClass
{
    /**
     * The VacancyId
     * Meta informations extracted from the WSDL
     * - pattern : [\da-fA-F]{8}-[\da-fA-F]{4}-[\da-fA-F]{4}-[\da-fA-F]{4}-[\da-fA-F]{12}
     * @var string
     */
    public $VacancyId;
    /**
     * The Title
     * Meta informations extracted from the WSDL
     * - nillable : true
     * @var string
     */
    public $Title;
    /**
     * The ShortDescription
     * Meta informations extracted from the WSDL
     * - nillable : true
     * @var string
     */
    public $ShortDescription;
    /**
     * The LongDescription
     * Meta informations extracted from the WSDL
     * - nillable : true
     * @var string
     */
    public $LongDescription;
    /**
     * The Employer
     * Meta informations extracted from the WSDL
     * - nillable : true
     * @var StructEmployerData
     */
    public $Employer;
    /**
     * The Vacancy
     * Meta informations extracted from the WSDL
     * - nillable : true
     * @var StructVacancyData
     */
    public $Vacancy;
    /**
     * The Application
     * Meta informations extracted from the WSDL
     * - nillable : true
     * @var StructApplicationData
     */
    public $Application;
    /**
     * The Apprenticeship
     * Meta informations extracted from the WSDL
     * - nillable : true
     * @var StructApprenticeshipData
     */
    public $Apprenticeship;
    /**
     * The ContractedProviderUkprn
     * Meta informations extracted from the WSDL
     * - nillable : true
     * @var int
     */
    public $ContractedProviderUkprn;
    /**
     * The VacancyOwnerEdsUrn
     * @var int
     */
    public $VacancyOwnerEdsUrn;
    /**
     * The VacancyManagerEdsUrn
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var int
     */
    public $VacancyManagerEdsUrn;
    /**
     * The DeliveryProviderEdsUrn
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var int
     */
    public $DeliveryProviderEdsUrn;
    /**
     * The IsDisplayRecruitmentAgency
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var boolean
     */
    public $IsDisplayRecruitmentAgency;
    /**
     * The IsSmallEmployerWageIncentive
     * @var boolean

    public $IsSmallEmployerWageIncentive;
    /**
     * Constructor method for VacancyUploadData
     * @see parent::__construct()
     * @param string $_vacancyId
     * @param string $_title
     * @param string $_shortDescription
     * @param string $_longDescription
     * @param StructEmployerData $_employer
     * @param StructVacancyData $_vacancy
     * @param StructApplicationData $_application
     * @param StructApprenticeshipData $_apprenticeship
     * @param int $_contractedProviderUkprn
     * @param int $_vacancyOwnerEdsUrn
     * @param int $_vacancyManagerEdsUrn
     * @param int $_deliveryProviderEdsUrn
     * @param boolean $_isDisplayRecruitmentAgency
     * @param boolean $_isSmallEmployerWageIncentive
     * @return StructVacancyUploadData
     */
    public function __construct($_vacancyId = NULL,$_title = NULL,$_shortDescription = NULL,$_longDescription = NULL,$_employer = NULL,$_vacancy = NULL,$_application = NULL,$_apprenticeship = NULL,$_contractedProviderUkprn = NULL,$_vacancyOwnerEdsUrn = NULL,$_vacancyManagerEdsUrn = NULL,$_deliveryProviderEdsUrn = NULL,$_isDisplayRecruitmentAgency = NULL,$_isSmallEmployerWageIncentive = NULL)
    {
        parent::__construct(array('VacancyId'=>$_vacancyId,'Title'=>$_title,'ShortDescription'=>$_shortDescription,'LongDescription'=>$_longDescription,'Employer'=>$_employer,'Vacancy'=>$_vacancy,'Application'=>$_application,'Apprenticeship'=>$_apprenticeship,'ContractedProviderUkprn'=>$_contractedProviderUkprn,'VacancyOwnerEdsUrn'=>$_vacancyOwnerEdsUrn,'VacancyManagerEdsUrn'=>$_vacancyManagerEdsUrn,'DeliveryProviderEdsUrn'=>$_deliveryProviderEdsUrn,'IsDisplayRecruitmentAgency'=>$_isDisplayRecruitmentAgency,'IsSmallEmployerWageIncentive'=>$_isSmallEmployerWageIncentive),false);
    }
    /**
     * Get VacancyId value
     * @return string|null
     */
    public function getVacancyId()
    {
        return $this->VacancyId;
    }
    /**
     * Set VacancyId value
     * @param string $_vacancyId the VacancyId
     * @return string
     */
    public function setVacancyId($_vacancyId)
    {
        return ($this->VacancyId = $_vacancyId);
    }
    /**
     * Get Title value
     * @return string|null
     */
    public function getTitle()
    {
        return $this->Title;
    }
    /**
     * Set Title value
     * @param string $_title the Title
     * @return string
     */
    public function setTitle($_title)
    {
        return ($this->Title = $_title);
    }
    /**
     * Get ShortDescription value
     * @return string|null
     */
    public function getShortDescription()
    {
        return $this->ShortDescription;
    }
    /**
     * Set ShortDescription value
     * @param string $_shortDescription the ShortDescription
     * @return string
     */
    public function setShortDescription($_shortDescription)
    {
        return ($this->ShortDescription = $_shortDescription);
    }
    /**
     * Get LongDescription value
     * @return string|null
     */
    public function getLongDescription()
    {
        return $this->LongDescription;
    }
    /**
     * Set LongDescription value
     * @param string $_longDescription the LongDescription
     * @return string
     */
    public function setLongDescription($_longDescription)
    {
        return ($this->LongDescription = $_longDescription);
    }
    /**
     * Get Employer value
     * @return StructEmployerData|null
     */
    public function getEmployer()
    {
        return $this->Employer;
    }
    /**
     * Set Employer value
     * @param StructEmployerData $_employer the Employer
     * @return StructEmployerData
     */
    public function setEmployer($_employer)
    {
        return ($this->Employer = $_employer);
    }
    /**
     * Get Vacancy value
     * @return StructVacancyData|null
     */
    public function getVacancy()
    {
        return $this->Vacancy;
    }
    /**
     * Set Vacancy value
     * @param StructVacancyData $_vacancy the Vacancy
     * @return StructVacancyData
     */
    public function setVacancy($_vacancy)
    {
        return ($this->Vacancy = $_vacancy);
    }
    /**
     * Get Application value
     * @return StructApplicationData|null
     */
    public function getApplication()
    {
        return $this->Application;
    }
    /**
     * Set Application value
     * @param StructApplicationData $_application the Application
     * @return StructApplicationData
     */
    public function setApplication($_application)
    {
        return ($this->Application = $_application);
    }
    /**
     * Get Apprenticeship value
     * @return StructApprenticeshipData|null
     */
    public function getApprenticeship()
    {
        return $this->Apprenticeship;
    }
    /**
     * Set Apprenticeship value
     * @param StructApprenticeshipData $_apprenticeship the Apprenticeship
     * @return StructApprenticeshipData
     */
    public function setApprenticeship($_apprenticeship)
    {
        return ($this->Apprenticeship = $_apprenticeship);
    }
    /**
     * Get ContractedProviderUkprn value
     * @return int|null
     */
    public function getContractedProviderUkprn()
    {
        return $this->ContractedProviderUkprn;
    }
    /**
     * Set ContractedProviderUkprn value
     * @param int $_contractedProviderUkprn the ContractedProviderUkprn
     * @return int
     */
    public function setContractedProviderUkprn($_contractedProviderUkprn)
    {
        return ($this->ContractedProviderUkprn = $_contractedProviderUkprn);
    }
    /**
     * Get VacancyOwnerEdsUrn value
     * @return int|null
     */
    public function getVacancyOwnerEdsUrn()
    {
        return $this->VacancyOwnerEdsUrn;
    }
    /**
     * Set VacancyOwnerEdsUrn value
     * @param int $_vacancyOwnerEdsUrn the VacancyOwnerEdsUrn
     * @return int
     */
    public function setVacancyOwnerEdsUrn($_vacancyOwnerEdsUrn)
    {
        return ($this->VacancyOwnerEdsUrn = $_vacancyOwnerEdsUrn);
    }
    /**
     * Get VacancyManagerEdsUrn value
     * @return int|null
     */
    public function getVacancyManagerEdsUrn()
    {
        return $this->VacancyManagerEdsUrn;
    }
    /**
     * Set VacancyManagerEdsUrn value
     * @param int $_vacancyManagerEdsUrn the VacancyManagerEdsUrn
     * @return int
     */
    public function setVacancyManagerEdsUrn($_vacancyManagerEdsUrn)
    {
        return ($this->VacancyManagerEdsUrn = $_vacancyManagerEdsUrn);
    }
    /**
     * Get DeliveryProviderEdsUrn value
     * @return int|null
     */
    public function getDeliveryProviderEdsUrn()
    {
        return $this->DeliveryProviderEdsUrn;
    }
    /**
     * Set DeliveryProviderEdsUrn value
     * @param int $_deliveryProviderEdsUrn the DeliveryProviderEdsUrn
     * @return int
     */
    public function setDeliveryProviderEdsUrn($_deliveryProviderEdsUrn)
    {
        return ($this->DeliveryProviderEdsUrn = $_deliveryProviderEdsUrn);
    }
    /**
     * Get IsDisplayRecruitmentAgency value
     * @return boolean|null
     */
    public function getIsDisplayRecruitmentAgency()
    {
        return $this->IsDisplayRecruitmentAgency;
    }
    /**
     * Set IsDisplayRecruitmentAgency value
     * @param boolean $_isDisplayRecruitmentAgency the IsDisplayRecruitmentAgency
     * @return boolean
     */
    public function setIsDisplayRecruitmentAgency($_isDisplayRecruitmentAgency)
    {
        return ($this->IsDisplayRecruitmentAgency = $_isDisplayRecruitmentAgency);
    }
    /**
     * Get IsSmallEmployerWageIncentive value
     * @return boolean|null
     */
    public function getIsSmallEmployerWageIncentive()
    {
        return $this->IsSmallEmployerWageIncentive;
    }
    /**
     * Set IsSmallEmployerWageIncentive value
     * @param boolean $_isSmallEmployerWageIncentive the IsSmallEmployerWageIncentive
     * @return boolean
     */
    public function setIsSmallEmployerWageIncentive($_isSmallEmployerWageIncentive)
    {
        return ($this->IsSmallEmployerWageIncentive = $_isSmallEmployerWageIncentive);
    }
    /**
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see WsdlClass::__set_state()
     * @uses WsdlClass::__set_state()
     * @param array $_array the exported values
     * @return StructVacancyUploadData
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
