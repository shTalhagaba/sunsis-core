<?php
/**
 * File for class LRSStructQualification
 * @package LRS
 * @subpackage Structs
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
/**
 * This class stands for LRSStructQualification originally named Qualification
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://compact-soft.com/projects/api.lrs.qcf.gov.uk.model.xsd}
 * @package LRS
 * @subpackage Structs
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
class LRSStructQualification extends LRSStructBusinessObject
{
    /**
     * The AwardingOrganisation
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var LRSStructAwardingOrganisation
     */
    public $AwardingOrganisation;
    /**
     * The CertificationEndDate
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var dateTime
     */
    public $CertificationEndDate;
    /**
     * The DerivedTitle
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $DerivedTitle;
    /**
     * The DisplayGradingType
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $DisplayGradingType;
    /**
     * The DisplayLevel
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $DisplayLevel;
    /**
     * The DisplayOrganisation
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $DisplayOrganisation;
    /**
     * The DisplayReferenceAndTitle
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $DisplayReferenceAndTitle;
    /**
     * The DisplaySsa
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $DisplaySsa;
    /**
     * The DisplayTotalCredits
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $DisplayTotalCredits;
    /**
     * The EighteenPlus
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * @var boolean
     */
    public $EighteenPlus;
    /**
     * The GradingStructure
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var LRSStructGradingStructure
     */
    public $GradingStructure;
    /**
     * The Id
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * @var int
     */
    public $Id;
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
     * The PreSixteen
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * @var boolean
     */
    public $PreSixteen;
    /**
     * The PreferredTitle
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $PreferredTitle;
    /**
     * The ReferenceNumber
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $ReferenceNumber;
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
     * The Structure
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var LRSStructStructure
     */
    public $Structure;
    /**
     * Constructor method for Qualification
     * @see parent::__construct()
     * @param LRSStructAwardingOrganisation $_awardingOrganisation
     * @param dateTime $_certificationEndDate
     * @param string $_derivedTitle
     * @param string $_displayGradingType
     * @param string $_displayLevel
     * @param string $_displayOrganisation
     * @param string $_displayReferenceAndTitle
     * @param string $_displaySsa
     * @param string $_displayTotalCredits
     * @param boolean $_eighteenPlus
     * @param LRSStructGradingStructure $_gradingStructure
     * @param int $_id
     * @param int $_maximumGuidedLearningHours
     * @param int $_minimumGuidedLearningHours
     * @param boolean $_nineteenPlus
     * @param boolean $_offeredInEngland
     * @param boolean $_offeredInNorthernIreland
     * @param boolean $_offeredInWales
     * @param dateTime $_operationalEndDate
     * @param dateTime $_operationalStartDate
     * @param boolean $_preSixteen
     * @param string $_preferredTitle
     * @param string $_referenceNumber
     * @param boolean $_sensitive
     * @param boolean $_sixteenToEighteen
     * @param LRSStructStructure $_structure
     * @return LRSStructQualification
     */
    public function __construct($_awardingOrganisation = NULL,$_certificationEndDate = NULL,$_derivedTitle = NULL,$_displayGradingType = NULL,$_displayLevel = NULL,$_displayOrganisation = NULL,$_displayReferenceAndTitle = NULL,$_displaySsa = NULL,$_displayTotalCredits = NULL,$_eighteenPlus = NULL,$_gradingStructure = NULL,$_id = NULL,$_maximumGuidedLearningHours = NULL,$_minimumGuidedLearningHours = NULL,$_nineteenPlus = NULL,$_offeredInEngland = NULL,$_offeredInNorthernIreland = NULL,$_offeredInWales = NULL,$_operationalEndDate = NULL,$_operationalStartDate = NULL,$_preSixteen = NULL,$_preferredTitle = NULL,$_referenceNumber = NULL,$_sensitive = NULL,$_sixteenToEighteen = NULL,$_structure = NULL)
    {
        LRSWsdlClass::__construct(array('AwardingOrganisation'=>$_awardingOrganisation,'CertificationEndDate'=>$_certificationEndDate,'DerivedTitle'=>$_derivedTitle,'DisplayGradingType'=>$_displayGradingType,'DisplayLevel'=>$_displayLevel,'DisplayOrganisation'=>$_displayOrganisation,'DisplayReferenceAndTitle'=>$_displayReferenceAndTitle,'DisplaySsa'=>$_displaySsa,'DisplayTotalCredits'=>$_displayTotalCredits,'EighteenPlus'=>$_eighteenPlus,'GradingStructure'=>$_gradingStructure,'Id'=>$_id,'MaximumGuidedLearningHours'=>$_maximumGuidedLearningHours,'MinimumGuidedLearningHours'=>$_minimumGuidedLearningHours,'NineteenPlus'=>$_nineteenPlus,'OfferedInEngland'=>$_offeredInEngland,'OfferedInNorthernIreland'=>$_offeredInNorthernIreland,'OfferedInWales'=>$_offeredInWales,'OperationalEndDate'=>$_operationalEndDate,'OperationalStartDate'=>$_operationalStartDate,'PreSixteen'=>$_preSixteen,'PreferredTitle'=>$_preferredTitle,'ReferenceNumber'=>$_referenceNumber,'Sensitive'=>$_sensitive,'SixteenToEighteen'=>$_sixteenToEighteen,'Structure'=>$_structure),false);
    }
    /**
     * Get AwardingOrganisation value
     * @return LRSStructAwardingOrganisation|null
     */
    public function getAwardingOrganisation()
    {
        return $this->AwardingOrganisation;
    }
    /**
     * Set AwardingOrganisation value
     * @param LRSStructAwardingOrganisation $_awardingOrganisation the AwardingOrganisation
     * @return LRSStructAwardingOrganisation
     */
    public function setAwardingOrganisation($_awardingOrganisation)
    {
        return ($this->AwardingOrganisation = $_awardingOrganisation);
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
     * Get DisplayGradingType value
     * @return string|null
     */
    public function getDisplayGradingType()
    {
        return $this->DisplayGradingType;
    }
    /**
     * Set DisplayGradingType value
     * @param string $_displayGradingType the DisplayGradingType
     * @return string
     */
    public function setDisplayGradingType($_displayGradingType)
    {
        return ($this->DisplayGradingType = $_displayGradingType);
    }
    /**
     * Get DisplayLevel value
     * @return string|null
     */
    public function getDisplayLevel()
    {
        return $this->DisplayLevel;
    }
    /**
     * Set DisplayLevel value
     * @param string $_displayLevel the DisplayLevel
     * @return string
     */
    public function setDisplayLevel($_displayLevel)
    {
        return ($this->DisplayLevel = $_displayLevel);
    }
    /**
     * Get DisplayOrganisation value
     * @return string|null
     */
    public function getDisplayOrganisation()
    {
        return $this->DisplayOrganisation;
    }
    /**
     * Set DisplayOrganisation value
     * @param string $_displayOrganisation the DisplayOrganisation
     * @return string
     */
    public function setDisplayOrganisation($_displayOrganisation)
    {
        return ($this->DisplayOrganisation = $_displayOrganisation);
    }
    /**
     * Get DisplayReferenceAndTitle value
     * @return string|null
     */
    public function getDisplayReferenceAndTitle()
    {
        return $this->DisplayReferenceAndTitle;
    }
    /**
     * Set DisplayReferenceAndTitle value
     * @param string $_displayReferenceAndTitle the DisplayReferenceAndTitle
     * @return string
     */
    public function setDisplayReferenceAndTitle($_displayReferenceAndTitle)
    {
        return ($this->DisplayReferenceAndTitle = $_displayReferenceAndTitle);
    }
    /**
     * Get DisplaySsa value
     * @return string|null
     */
    public function getDisplaySsa()
    {
        return $this->DisplaySsa;
    }
    /**
     * Set DisplaySsa value
     * @param string $_displaySsa the DisplaySsa
     * @return string
     */
    public function setDisplaySsa($_displaySsa)
    {
        return ($this->DisplaySsa = $_displaySsa);
    }
    /**
     * Get DisplayTotalCredits value
     * @return string|null
     */
    public function getDisplayTotalCredits()
    {
        return $this->DisplayTotalCredits;
    }
    /**
     * Set DisplayTotalCredits value
     * @param string $_displayTotalCredits the DisplayTotalCredits
     * @return string
     */
    public function setDisplayTotalCredits($_displayTotalCredits)
    {
        return ($this->DisplayTotalCredits = $_displayTotalCredits);
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
     * Get GradingStructure value
     * @return LRSStructGradingStructure|null
     */
    public function getGradingStructure()
    {
        return $this->GradingStructure;
    }
    /**
     * Set GradingStructure value
     * @param LRSStructGradingStructure $_gradingStructure the GradingStructure
     * @return LRSStructGradingStructure
     */
    public function setGradingStructure($_gradingStructure)
    {
        return ($this->GradingStructure = $_gradingStructure);
    }
    /**
     * Get Id value
     * @return int|null
     */
    public function getId()
    {
        return $this->Id;
    }
    /**
     * Set Id value
     * @param int $_id the Id
     * @return int
     */
    public function setId($_id)
    {
        return ($this->Id = $_id);
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
     * Get PreferredTitle value
     * @return string|null
     */
    public function getPreferredTitle()
    {
        return $this->PreferredTitle;
    }
    /**
     * Set PreferredTitle value
     * @param string $_preferredTitle the PreferredTitle
     * @return string
     */
    public function setPreferredTitle($_preferredTitle)
    {
        return ($this->PreferredTitle = $_preferredTitle);
    }
    /**
     * Get ReferenceNumber value
     * @return string|null
     */
    public function getReferenceNumber()
    {
        return $this->ReferenceNumber;
    }
    /**
     * Set ReferenceNumber value
     * @param string $_referenceNumber the ReferenceNumber
     * @return string
     */
    public function setReferenceNumber($_referenceNumber)
    {
        return ($this->ReferenceNumber = $_referenceNumber);
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
     * Get Structure value
     * @return LRSStructStructure|null
     */
    public function getStructure()
    {
        return $this->Structure;
    }
    /**
     * Set Structure value
     * @param LRSStructStructure $_structure the Structure
     * @return LRSStructStructure
     */
    public function setStructure($_structure)
    {
        return ($this->Structure = $_structure);
    }
    /**
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see LRSWsdlClass::__set_state()
     * @uses LRSWsdlClass::__set_state()
     * @param array $_array the exported values
     * @return LRSStructQualification
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
