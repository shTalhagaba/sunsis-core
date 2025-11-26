<?php
/**
 * File for class StructVacancyData
 * @package
 * @subpackage Structs
 * @date 2016-12-22
 */
/**
 * This class stands for StructVacancyData originally named VacancyData
 * Meta informations extracted from the WSDL
 * - from schema : {@link https://soapapi.findapprenticeship.service.gov.uk/services/VacancyManagement/VacancyManagement51.svc?xsd=xsd0}
 * @package
 * @subpackage Structs
 * @date 2016-12-22
 */
class StructVacancyData extends WsdlClass
{
    /**
     * The Wage
     * @var decimal
     */
    public $Wage;
    /**
     * The WageType
     * @var EnumWageType
     */
    public $WageType;
    /**
     * The WorkingWeek
     * Meta informations extracted from the WSDL
     * - nillable : true
     * @var string
     */
    public $WorkingWeek;
    /**
     * The SkillsRequired
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $SkillsRequired;
    /**
     * The QualificationRequired
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $QualificationRequired;
    /**
     * The PersonalQualities
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $PersonalQualities;
    /**
     * The FutureProspects
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $FutureProspects;
    /**
     * The OtherImportantInformation
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $OtherImportantInformation;
    /**
     * The LocationType
     * @var EnumVacancyLocationType
     */
    public $LocationType;
    /**
     * The LocationDetails
     * Meta informations extracted from the WSDL
     * - nillable : true
     * @var StructArrayOfSiteVacancyData
     */
    public $LocationDetails;
    /**
     * The RealityCheck
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $RealityCheck;
    /**
     * The SupplementaryQuestion1
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $SupplementaryQuestion1;
    /**
     * The SupplementaryQuestion2
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $SupplementaryQuestion2;
    /**
     * Constructor method for VacancyData
     * @see parent::__construct()
     * @param decimal $_wage
     * @param EnumWageType $_wageType
     * @param string $_workingWeek
     * @param string $_skillsRequired
     * @param string $_qualificationRequired
     * @param string $_personalQualities
     * @param string $_futureProspects
     * @param string $_otherImportantInformation
     * @param EnumVacancyLocationType $_locationType
     * @param StructArrayOfSiteVacancyData $_locationDetails
     * @param string $_realityCheck
     * @param string $_supplementaryQuestion1
     * @param string $_supplementaryQuestion2
     * @return StructVacancyData
     */
    public function __construct($_wage = NULL,$_wageType = NULL,$_workingWeek = NULL,$_skillsRequired = NULL,$_qualificationRequired = NULL,$_personalQualities = NULL,$_futureProspects = NULL,$_otherImportantInformation = NULL,$_locationType = NULL,$_locationDetails = NULL,$_realityCheck = NULL,$_supplementaryQuestion1 = NULL,$_supplementaryQuestion2 = NULL)
    {
        parent::__construct(array('Wage'=>$_wage,'WageType'=>$_wageType,'WorkingWeek'=>$_workingWeek,'SkillsRequired'=>$_skillsRequired,'QualificationRequired'=>$_qualificationRequired,'PersonalQualities'=>$_personalQualities,'FutureProspects'=>$_futureProspects,'OtherImportantInformation'=>$_otherImportantInformation,'LocationType'=>$_locationType,'LocationDetails'=>($_locationDetails instanceof StructArrayOfSiteVacancyData)?$_locationDetails:new StructArrayOfSiteVacancyData($_locationDetails),'RealityCheck'=>$_realityCheck,'SupplementaryQuestion1'=>$_supplementaryQuestion1,'SupplementaryQuestion2'=>$_supplementaryQuestion2),false);
    }
    /**
     * Get Wage value
     * @return decimal|null
     */
    public function getWage()
    {
        return $this->Wage;
    }
    /**
     * Set Wage value
     * @param decimal $_wage the Wage
     * @return decimal
     */
    public function setWage($_wage)
    {
        return ($this->Wage = $_wage);
    }
    /**
     * Get WageType value
     * @return EnumWageType|null
     */
    public function getWageType()
    {
        return $this->WageType;
    }
    /**
     * Set WageType value
     * @uses EnumWageType::valueIsValid()
     * @param EnumWageType $_wageType the WageType
     * @return EnumWageType
     */
    public function setWageType($_wageType)
    {
        if(!EnumWageType::valueIsValid($_wageType))
        {
            return false;
        }
        return ($this->WageType = $_wageType);
    }
    /**
     * Get WorkingWeek value
     * @return string|null
     */
    public function getWorkingWeek()
    {
        return $this->WorkingWeek;
    }
    /**
     * Set WorkingWeek value
     * @param string $_workingWeek the WorkingWeek
     * @return string
     */
    public function setWorkingWeek($_workingWeek)
    {
        return ($this->WorkingWeek = $_workingWeek);
    }
    /**
     * Get SkillsRequired value
     * @return string|null
     */
    public function getSkillsRequired()
    {
        return $this->SkillsRequired;
    }
    /**
     * Set SkillsRequired value
     * @param string $_skillsRequired the SkillsRequired
     * @return string
     */
    public function setSkillsRequired($_skillsRequired)
    {
        return ($this->SkillsRequired = $_skillsRequired);
    }
    /**
     * Get QualificationRequired value
     * @return string|null
     */
    public function getQualificationRequired()
    {
        return $this->QualificationRequired;
    }
    /**
     * Set QualificationRequired value
     * @param string $_qualificationRequired the QualificationRequired
     * @return string
     */
    public function setQualificationRequired($_qualificationRequired)
    {
        return ($this->QualificationRequired = $_qualificationRequired);
    }
    /**
     * Get PersonalQualities value
     * @return string|null
     */
    public function getPersonalQualities()
    {
        return $this->PersonalQualities;
    }
    /**
     * Set PersonalQualities value
     * @param string $_personalQualities the PersonalQualities
     * @return string
     */
    public function setPersonalQualities($_personalQualities)
    {
        return ($this->PersonalQualities = $_personalQualities);
    }
    /**
     * Get FutureProspects value
     * @return string|null
     */
    public function getFutureProspects()
    {
        return $this->FutureProspects;
    }
    /**
     * Set FutureProspects value
     * @param string $_futureProspects the FutureProspects
     * @return string
     */
    public function setFutureProspects($_futureProspects)
    {
        return ($this->FutureProspects = $_futureProspects);
    }
    /**
     * Get OtherImportantInformation value
     * @return string|null
     */
    public function getOtherImportantInformation()
    {
        return $this->OtherImportantInformation;
    }
    /**
     * Set OtherImportantInformation value
     * @param string $_otherImportantInformation the OtherImportantInformation
     * @return string
     */
    public function setOtherImportantInformation($_otherImportantInformation)
    {
        return ($this->OtherImportantInformation = $_otherImportantInformation);
    }
    /**
     * Get LocationType value
     * @return EnumVacancyLocationType|null
     */
    public function getLocationType()
    {
        return $this->LocationType;
    }
    /**
     * Set LocationType value
     * @uses EnumVacancyLocationType::valueIsValid()
     * @param EnumVacancyLocationType $_locationType the LocationType
     * @return EnumVacancyLocationType
     */
    public function setLocationType($_locationType)
    {
        if(!EnumVacancyLocationType::valueIsValid($_locationType))
        {
            return false;
        }
        return ($this->LocationType = $_locationType);
    }
    /**
     * Get LocationDetails value
     * @return StructArrayOfSiteVacancyData|null
     */
    public function getLocationDetails()
    {
        return $this->LocationDetails;
    }
    /**
     * Set LocationDetails value
     * @param StructArrayOfSiteVacancyData $_locationDetails the LocationDetails
     * @return StructArrayOfSiteVacancyData
     */
    public function setLocationDetails($_locationDetails)
    {
        return ($this->LocationDetails = $_locationDetails);
    }
    /**
     * Get RealityCheck value
     * @return string|null
     */
    public function getRealityCheck()
    {
        return $this->RealityCheck;
    }
    /**
     * Set RealityCheck value
     * @param string $_realityCheck the RealityCheck
     * @return string
     */
    public function setRealityCheck($_realityCheck)
    {
        return ($this->RealityCheck = $_realityCheck);
    }
    /**
     * Get SupplementaryQuestion1 value
     * @return string|null
     */
    public function getSupplementaryQuestion1()
    {
        return $this->SupplementaryQuestion1;
    }
    /**
     * Set SupplementaryQuestion1 value
     * @param string $_supplementaryQuestion1 the SupplementaryQuestion1
     * @return string
     */
    public function setSupplementaryQuestion1($_supplementaryQuestion1)
    {
        return ($this->SupplementaryQuestion1 = $_supplementaryQuestion1);
    }
    /**
     * Get SupplementaryQuestion2 value
     * @return string|null
     */
    public function getSupplementaryQuestion2()
    {
        return $this->SupplementaryQuestion2;
    }
    /**
     * Set SupplementaryQuestion2 value
     * @param string $_supplementaryQuestion2 the SupplementaryQuestion2
     * @return string
     */
    public function setSupplementaryQuestion2($_supplementaryQuestion2)
    {
        return ($this->SupplementaryQuestion2 = $_supplementaryQuestion2);
    }
    /**
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see WsdlClass::__set_state()
     * @uses WsdlClass::__set_state()
     * @param array $_array the exported values
     * @return StructVacancyData
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
