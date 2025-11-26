<?php
/**
 * File for class LRSStructRtaOneQueryResult
 * @package LRS
 * @subpackage Structs
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
/**
 * This class stands for LRSStructRtaOneQueryResult originally named RtaOneQueryResult
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://compact-soft.com/projects/api.lrs.qcf.gov.uk.model.xsd}
 * @package LRS
 * @subpackage Structs
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
class LRSStructRtaOneQueryResult extends LRSStructBusinessObject
{
    /**
     * The AccreditationNumber
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $AccreditationNumber;
    /**
     * The AwardingOrganisationName
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $AwardingOrganisationName;
    /**
     * The CertificationEndDate
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var dateTime
     */
    public $CertificationEndDate;
    /**
     * The CompletedUnitCount
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * @var int
     */
    public $CompletedUnitCount;
    /**
     * The CreditsTowards
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * @var int
     */
    public $CreditsTowards;
    /**
     * The DerivedTitle
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $DerivedTitle;
    /**
     * The EighteenPlus
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * @var boolean
     */
    public $EighteenPlus;
    /**
     * The MaximumGuidedLearningHours
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * @var int
     */
    public $MaximumGuidedLearningHours;
    /**
     * The MinimumGuidedLearningHours
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * @var int
     */
    public $MinimumGuidedLearningHours;
    /**
     * The NineteenPlus
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * @var boolean
     */
    public $NineteenPlus;
    /**
     * The OfferedInEngland
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * @var boolean
     */
    public $OfferedInEngland;
    /**
     * The OfferedInNorthernIreland
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * @var boolean
     */
    public $OfferedInNorthernIreland;
    /**
     * The OfferedInWales
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * @var boolean
     */
    public $OfferedInWales;
    /**
     * The OperationalEndDate
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var dateTime
     */
    public $OperationalEndDate;
    /**
     * The OperationalStartDate
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var dateTime
     */
    public $OperationalStartDate;
    /**
     * The OrganisationAcronym
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $OrganisationAcronym;
    /**
     * The PreSixteen
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * @var boolean
     */
    public $PreSixteen;
    /**
     * The QualificationLevelDescription
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $QualificationLevelDescription;
    /**
     * The QualificationSize
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $QualificationSize;
    /**
     * The QualificationSubLevelDescription
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $QualificationSubLevelDescription;
    /**
     * The Sensitive
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * @var boolean
     */
    public $Sensitive;
    /**
     * The SixteenToEighteen
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * @var boolean
     */
    public $SixteenToEighteen;
    /**
     * The SsaCode
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $SsaCode;
    /**
     * The SsaDescription
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $SsaDescription;
    /**
     * The TotalCredits
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * @var int
     */
    public $TotalCredits;
    /**
     * The Type
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $Type;
    /**
     * Constructor method for RtaOneQueryResult
     * @see parent::__construct()
     * @param string $_accreditationNumber
     * @param string $_awardingOrganisationName
     * @param dateTime $_certificationEndDate
     * @param int $_completedUnitCount
     * @param int $_creditsTowards
     * @param string $_derivedTitle
     * @param boolean $_eighteenPlus
     * @param int $_maximumGuidedLearningHours
     * @param int $_minimumGuidedLearningHours
     * @param boolean $_nineteenPlus
     * @param boolean $_offeredInEngland
     * @param boolean $_offeredInNorthernIreland
     * @param boolean $_offeredInWales
     * @param dateTime $_operationalEndDate
     * @param dateTime $_operationalStartDate
     * @param string $_organisationAcronym
     * @param boolean $_preSixteen
     * @param string $_qualificationLevelDescription
     * @param string $_qualificationSize
     * @param string $_qualificationSubLevelDescription
     * @param boolean $_sensitive
     * @param boolean $_sixteenToEighteen
     * @param string $_ssaCode
     * @param string $_ssaDescription
     * @param int $_totalCredits
     * @param string $_type
     * @return LRSStructRtaOneQueryResult
     */
    public function __construct($_accreditationNumber = NULL,$_awardingOrganisationName = NULL,$_certificationEndDate = NULL,$_completedUnitCount = NULL,$_creditsTowards = NULL,$_derivedTitle = NULL,$_eighteenPlus = NULL,$_maximumGuidedLearningHours = NULL,$_minimumGuidedLearningHours = NULL,$_nineteenPlus = NULL,$_offeredInEngland = NULL,$_offeredInNorthernIreland = NULL,$_offeredInWales = NULL,$_operationalEndDate = NULL,$_operationalStartDate = NULL,$_organisationAcronym = NULL,$_preSixteen = NULL,$_qualificationLevelDescription = NULL,$_qualificationSize = NULL,$_qualificationSubLevelDescription = NULL,$_sensitive = NULL,$_sixteenToEighteen = NULL,$_ssaCode = NULL,$_ssaDescription = NULL,$_totalCredits = NULL,$_type = NULL)
    {
        LRSWsdlClass::__construct(array('AccreditationNumber'=>$_accreditationNumber,'AwardingOrganisationName'=>$_awardingOrganisationName,'CertificationEndDate'=>$_certificationEndDate,'CompletedUnitCount'=>$_completedUnitCount,'CreditsTowards'=>$_creditsTowards,'DerivedTitle'=>$_derivedTitle,'EighteenPlus'=>$_eighteenPlus,'MaximumGuidedLearningHours'=>$_maximumGuidedLearningHours,'MinimumGuidedLearningHours'=>$_minimumGuidedLearningHours,'NineteenPlus'=>$_nineteenPlus,'OfferedInEngland'=>$_offeredInEngland,'OfferedInNorthernIreland'=>$_offeredInNorthernIreland,'OfferedInWales'=>$_offeredInWales,'OperationalEndDate'=>$_operationalEndDate,'OperationalStartDate'=>$_operationalStartDate,'OrganisationAcronym'=>$_organisationAcronym,'PreSixteen'=>$_preSixteen,'QualificationLevelDescription'=>$_qualificationLevelDescription,'QualificationSize'=>$_qualificationSize,'QualificationSubLevelDescription'=>$_qualificationSubLevelDescription,'Sensitive'=>$_sensitive,'SixteenToEighteen'=>$_sixteenToEighteen,'SsaCode'=>$_ssaCode,'SsaDescription'=>$_ssaDescription,'TotalCredits'=>$_totalCredits,'Type'=>$_type),false);
    }
    /**
     * Get AccreditationNumber value
     * @return string|null
     */
    public function getAccreditationNumber()
    {
        return $this->AccreditationNumber;
    }
    /**
     * Set AccreditationNumber value
     * @param string $_accreditationNumber the AccreditationNumber
     * @return string
     */
    public function setAccreditationNumber($_accreditationNumber)
    {
        return ($this->AccreditationNumber = $_accreditationNumber);
    }
    /**
     * Get AwardingOrganisationName value
     * @return string|null
     */
    public function getAwardingOrganisationName()
    {
        return $this->AwardingOrganisationName;
    }
    /**
     * Set AwardingOrganisationName value
     * @param string $_awardingOrganisationName the AwardingOrganisationName
     * @return string
     */
    public function setAwardingOrganisationName($_awardingOrganisationName)
    {
        return ($this->AwardingOrganisationName = $_awardingOrganisationName);
    }
    /**
     * Get CertificationEndDate value
     * @return dateTime|null
     */
    public function getCertificationEndDate()
    {
        return $this->CertificationEndDate;
    }
    /**
     * Set CertificationEndDate value
     * @param dateTime $_certificationEndDate the CertificationEndDate
     * @return dateTime
     */
    public function setCertificationEndDate($_certificationEndDate)
    {
        return ($this->CertificationEndDate = $_certificationEndDate);
    }
    /**
     * Get CompletedUnitCount value
     * @return int|null
     */
    public function getCompletedUnitCount()
    {
        return $this->CompletedUnitCount;
    }
    /**
     * Set CompletedUnitCount value
     * @param int $_completedUnitCount the CompletedUnitCount
     * @return int
     */
    public function setCompletedUnitCount($_completedUnitCount)
    {
        return ($this->CompletedUnitCount = $_completedUnitCount);
    }
    /**
     * Get CreditsTowards value
     * @return int|null
     */
    public function getCreditsTowards()
    {
        return $this->CreditsTowards;
    }
    /**
     * Set CreditsTowards value
     * @param int $_creditsTowards the CreditsTowards
     * @return int
     */
    public function setCreditsTowards($_creditsTowards)
    {
        return ($this->CreditsTowards = $_creditsTowards);
    }
    /**
     * Get DerivedTitle value
     * @return string|null
     */
    public function getDerivedTitle()
    {
        return $this->DerivedTitle;
    }
    /**
     * Set DerivedTitle value
     * @param string $_derivedTitle the DerivedTitle
     * @return string
     */
    public function setDerivedTitle($_derivedTitle)
    {
        return ($this->DerivedTitle = $_derivedTitle);
    }
    /**
     * Get EighteenPlus value
     * @return boolean|null
     */
    public function getEighteenPlus()
    {
        return $this->EighteenPlus;
    }
    /**
     * Set EighteenPlus value
     * @param boolean $_eighteenPlus the EighteenPlus
     * @return boolean
     */
    public function setEighteenPlus($_eighteenPlus)
    {
        return ($this->EighteenPlus = $_eighteenPlus);
    }
    /**
     * Get MaximumGuidedLearningHours value
     * @return int|null
     */
    public function getMaximumGuidedLearningHours()
    {
        return $this->MaximumGuidedLearningHours;
    }
    /**
     * Set MaximumGuidedLearningHours value
     * @param int $_maximumGuidedLearningHours the MaximumGuidedLearningHours
     * @return int
     */
    public function setMaximumGuidedLearningHours($_maximumGuidedLearningHours)
    {
        return ($this->MaximumGuidedLearningHours = $_maximumGuidedLearningHours);
    }
    /**
     * Get MinimumGuidedLearningHours value
     * @return int|null
     */
    public function getMinimumGuidedLearningHours()
    {
        return $this->MinimumGuidedLearningHours;
    }
    /**
     * Set MinimumGuidedLearningHours value
     * @param int $_minimumGuidedLearningHours the MinimumGuidedLearningHours
     * @return int
     */
    public function setMinimumGuidedLearningHours($_minimumGuidedLearningHours)
    {
        return ($this->MinimumGuidedLearningHours = $_minimumGuidedLearningHours);
    }
    /**
     * Get NineteenPlus value
     * @return boolean|null
     */
    public function getNineteenPlus()
    {
        return $this->NineteenPlus;
    }
    /**
     * Set NineteenPlus value
     * @param boolean $_nineteenPlus the NineteenPlus
     * @return boolean
     */
    public function setNineteenPlus($_nineteenPlus)
    {
        return ($this->NineteenPlus = $_nineteenPlus);
    }
    /**
     * Get OfferedInEngland value
     * @return boolean|null
     */
    public function getOfferedInEngland()
    {
        return $this->OfferedInEngland;
    }
    /**
     * Set OfferedInEngland value
     * @param boolean $_offeredInEngland the OfferedInEngland
     * @return boolean
     */
    public function setOfferedInEngland($_offeredInEngland)
    {
        return ($this->OfferedInEngland = $_offeredInEngland);
    }
    /**
     * Get OfferedInNorthernIreland value
     * @return boolean|null
     */
    public function getOfferedInNorthernIreland()
    {
        return $this->OfferedInNorthernIreland;
    }
    /**
     * Set OfferedInNorthernIreland value
     * @param boolean $_offeredInNorthernIreland the OfferedInNorthernIreland
     * @return boolean
     */
    public function setOfferedInNorthernIreland($_offeredInNorthernIreland)
    {
        return ($this->OfferedInNorthernIreland = $_offeredInNorthernIreland);
    }
    /**
     * Get OfferedInWales value
     * @return boolean|null
     */
    public function getOfferedInWales()
    {
        return $this->OfferedInWales;
    }
    /**
     * Set OfferedInWales value
     * @param boolean $_offeredInWales the OfferedInWales
     * @return boolean
     */
    public function setOfferedInWales($_offeredInWales)
    {
        return ($this->OfferedInWales = $_offeredInWales);
    }
    /**
     * Get OperationalEndDate value
     * @return dateTime|null
     */
    public function getOperationalEndDate()
    {
        return $this->OperationalEndDate;
    }
    /**
     * Set OperationalEndDate value
     * @param dateTime $_operationalEndDate the OperationalEndDate
     * @return dateTime
     */
    public function setOperationalEndDate($_operationalEndDate)
    {
        return ($this->OperationalEndDate = $_operationalEndDate);
    }
    /**
     * Get OperationalStartDate value
     * @return dateTime|null
     */
    public function getOperationalStartDate()
    {
        return $this->OperationalStartDate;
    }
    /**
     * Set OperationalStartDate value
     * @param dateTime $_operationalStartDate the OperationalStartDate
     * @return dateTime
     */
    public function setOperationalStartDate($_operationalStartDate)
    {
        return ($this->OperationalStartDate = $_operationalStartDate);
    }
    /**
     * Get OrganisationAcronym value
     * @return string|null
     */
    public function getOrganisationAcronym()
    {
        return $this->OrganisationAcronym;
    }
    /**
     * Set OrganisationAcronym value
     * @param string $_organisationAcronym the OrganisationAcronym
     * @return string
     */
    public function setOrganisationAcronym($_organisationAcronym)
    {
        return ($this->OrganisationAcronym = $_organisationAcronym);
    }
    /**
     * Get PreSixteen value
     * @return boolean|null
     */
    public function getPreSixteen()
    {
        return $this->PreSixteen;
    }
    /**
     * Set PreSixteen value
     * @param boolean $_preSixteen the PreSixteen
     * @return boolean
     */
    public function setPreSixteen($_preSixteen)
    {
        return ($this->PreSixteen = $_preSixteen);
    }
    /**
     * Get QualificationLevelDescription value
     * @return string|null
     */
    public function getQualificationLevelDescription()
    {
        return $this->QualificationLevelDescription;
    }
    /**
     * Set QualificationLevelDescription value
     * @param string $_qualificationLevelDescription the QualificationLevelDescription
     * @return string
     */
    public function setQualificationLevelDescription($_qualificationLevelDescription)
    {
        return ($this->QualificationLevelDescription = $_qualificationLevelDescription);
    }
    /**
     * Get QualificationSize value
     * @return string|null
     */
    public function getQualificationSize()
    {
        return $this->QualificationSize;
    }
    /**
     * Set QualificationSize value
     * @param string $_qualificationSize the QualificationSize
     * @return string
     */
    public function setQualificationSize($_qualificationSize)
    {
        return ($this->QualificationSize = $_qualificationSize);
    }
    /**
     * Get QualificationSubLevelDescription value
     * @return string|null
     */
    public function getQualificationSubLevelDescription()
    {
        return $this->QualificationSubLevelDescription;
    }
    /**
     * Set QualificationSubLevelDescription value
     * @param string $_qualificationSubLevelDescription the QualificationSubLevelDescription
     * @return string
     */
    public function setQualificationSubLevelDescription($_qualificationSubLevelDescription)
    {
        return ($this->QualificationSubLevelDescription = $_qualificationSubLevelDescription);
    }
    /**
     * Get Sensitive value
     * @return boolean|null
     */
    public function getSensitive()
    {
        return $this->Sensitive;
    }
    /**
     * Set Sensitive value
     * @param boolean $_sensitive the Sensitive
     * @return boolean
     */
    public function setSensitive($_sensitive)
    {
        return ($this->Sensitive = $_sensitive);
    }
    /**
     * Get SixteenToEighteen value
     * @return boolean|null
     */
    public function getSixteenToEighteen()
    {
        return $this->SixteenToEighteen;
    }
    /**
     * Set SixteenToEighteen value
     * @param boolean $_sixteenToEighteen the SixteenToEighteen
     * @return boolean
     */
    public function setSixteenToEighteen($_sixteenToEighteen)
    {
        return ($this->SixteenToEighteen = $_sixteenToEighteen);
    }
    /**
     * Get SsaCode value
     * @return string|null
     */
    public function getSsaCode()
    {
        return $this->SsaCode;
    }
    /**
     * Set SsaCode value
     * @param string $_ssaCode the SsaCode
     * @return string
     */
    public function setSsaCode($_ssaCode)
    {
        return ($this->SsaCode = $_ssaCode);
    }
    /**
     * Get SsaDescription value
     * @return string|null
     */
    public function getSsaDescription()
    {
        return $this->SsaDescription;
    }
    /**
     * Set SsaDescription value
     * @param string $_ssaDescription the SsaDescription
     * @return string
     */
    public function setSsaDescription($_ssaDescription)
    {
        return ($this->SsaDescription = $_ssaDescription);
    }
    /**
     * Get TotalCredits value
     * @return int|null
     */
    public function getTotalCredits()
    {
        return $this->TotalCredits;
    }
    /**
     * Set TotalCredits value
     * @param int $_totalCredits the TotalCredits
     * @return int
     */
    public function setTotalCredits($_totalCredits)
    {
        return ($this->TotalCredits = $_totalCredits);
    }
    /**
     * Get Type value
     * @return string|null
     */
    public function getType()
    {
        return $this->Type;
    }
    /**
     * Set Type value
     * @param string $_type the Type
     * @return string
     */
    public function setType($_type)
    {
        return ($this->Type = $_type);
    }
    /**
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see LRSWsdlClass::__set_state()
     * @uses LRSWsdlClass::__set_state()
     * @param array $_array the exported values
     * @return LRSStructRtaOneQueryResult
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
