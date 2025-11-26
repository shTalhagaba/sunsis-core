<?php
/**
 * File for class LRSStructUnit
 * @package LRS
 * @subpackage Structs
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
/**
 * This class stands for LRSStructUnit originally named Unit
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://compact-soft.com/projects/api.lrs.qcf.gov.uk.model.xsd}
 * @package LRS
 * @subpackage Structs
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
class LRSStructUnit extends LRSStructUnitBase
{
    /**
     * The Availability
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * @var LRSEnumUnitAvailability
     */
    public $Availability;
    /**
     * The CreditValue
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var int
     */
    public $CreditValue;
    /**
     * The ExpiryDate
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var dateTime
     */
    public $ExpiryDate;
    /**
     * The GuidedLearningHours
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var int
     */
    public $GuidedLearningHours;
    /**
     * The Level
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var LRSStructLevel
     */
    public $Level;
    /**
     * The OverallGradingStructure
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var LRSStructGradingStructure
     */
    public $OverallGradingStructure;
    /**
     * The OwningAwardingOrganisation
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var LRSStructAwardingOrganisation
     */
    public $OwningAwardingOrganisation;
    /**
     * The QualificationLevel
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var LRSStructQualificationLevel
     */
    public $QualificationLevel;
    /**
     * The QualificationSubLevel
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var LRSStructQualificationSubLevel
     */
    public $QualificationSubLevel;
    /**
     * The ReferenceNumber
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $ReferenceNumber;
    /**
     * The RestrictedAwardingOrganisations
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var LRSStructArrayOfAwardingOrganisation
     */
    public $RestrictedAwardingOrganisations;
    /**
     * The ReviewDate
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var dateTime
     */
    public $ReviewDate;
    /**
     * The SectorSubjectAreas
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var LRSStructArrayOfSectorSubjectArea
     */
    public $SectorSubjectAreas;
    /**
     * The Sensitive
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * @var boolean
     */
    public $Sensitive;
    /**
     * The Status
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * @var LRSEnumUnitStatus
     */
    public $Status;
    /**
     * The Title
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $Title;
    /**
     * The UnitGradingStructure
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * @var LRSEnumUnitGradingStructure
     */
    public $UnitGradingStructure;
    /**
     * Constructor method for Unit
     * @see parent::__construct()
     * @param LRSEnumUnitAvailability $_availability
     * @param int $_creditValue
     * @param dateTime $_expiryDate
     * @param int $_guidedLearningHours
     * @param LRSStructLevel $_level
     * @param LRSStructGradingStructure $_overallGradingStructure
     * @param LRSStructAwardingOrganisation $_owningAwardingOrganisation
     * @param LRSStructQualificationLevel $_qualificationLevel
     * @param LRSStructQualificationSubLevel $_qualificationSubLevel
     * @param string $_referenceNumber
     * @param LRSStructArrayOfAwardingOrganisation $_restrictedAwardingOrganisations
     * @param dateTime $_reviewDate
     * @param LRSStructArrayOfSectorSubjectArea $_sectorSubjectAreas
     * @param boolean $_sensitive
     * @param LRSEnumUnitStatus $_status
     * @param string $_title
     * @param LRSEnumUnitGradingStructure $_unitGradingStructure
     * @return LRSStructUnit
     */
    public function __construct($_availability = NULL,$_creditValue = NULL,$_expiryDate = NULL,$_guidedLearningHours = NULL,$_level = NULL,$_overallGradingStructure = NULL,$_owningAwardingOrganisation = NULL,$_qualificationLevel = NULL,$_qualificationSubLevel = NULL,$_referenceNumber = NULL,$_restrictedAwardingOrganisations = NULL,$_reviewDate = NULL,$_sectorSubjectAreas = NULL,$_sensitive = NULL,$_status = NULL,$_title = NULL,$_unitGradingStructure = NULL)
    {
        LRSWsdlClass::__construct(array('Availability'=>$_availability,'CreditValue'=>$_creditValue,'ExpiryDate'=>$_expiryDate,'GuidedLearningHours'=>$_guidedLearningHours,'Level'=>$_level,'OverallGradingStructure'=>$_overallGradingStructure,'OwningAwardingOrganisation'=>$_owningAwardingOrganisation,'QualificationLevel'=>$_qualificationLevel,'QualificationSubLevel'=>$_qualificationSubLevel,'ReferenceNumber'=>$_referenceNumber,'RestrictedAwardingOrganisations'=>($_restrictedAwardingOrganisations instanceof LRSStructArrayOfAwardingOrganisation)?$_restrictedAwardingOrganisations:new LRSStructArrayOfAwardingOrganisation($_restrictedAwardingOrganisations),'ReviewDate'=>$_reviewDate,'SectorSubjectAreas'=>($_sectorSubjectAreas instanceof LRSStructArrayOfSectorSubjectArea)?$_sectorSubjectAreas:new LRSStructArrayOfSectorSubjectArea($_sectorSubjectAreas),'Sensitive'=>$_sensitive,'Status'=>$_status,'Title'=>$_title,'UnitGradingStructure'=>$_unitGradingStructure),false);
    }
    /**
     * Get Availability value
     * @return LRSEnumUnitAvailability|null
     */
    public function getAvailability()
    {
        return $this->Availability;
    }
    /**
     * Set Availability value
     * @uses LRSEnumUnitAvailability::valueIsValid()
     * @param LRSEnumUnitAvailability $_availability the Availability
     * @return LRSEnumUnitAvailability
     */
    public function setAvailability($_availability)
    {
        if(!LRSEnumUnitAvailability::valueIsValid($_availability))
        {
            return false;
        }
        return ($this->Availability = $_availability);
    }
    /**
     * Get CreditValue value
     * @return int|null
     */
    public function getCreditValue()
    {
        return $this->CreditValue;
    }
    /**
     * Set CreditValue value
     * @param int $_creditValue the CreditValue
     * @return int
     */
    public function setCreditValue($_creditValue)
    {
        return ($this->CreditValue = $_creditValue);
    }
    /**
     * Get ExpiryDate value
     * @return dateTime|null
     */
    public function getExpiryDate()
    {
        return $this->ExpiryDate;
    }
    /**
     * Set ExpiryDate value
     * @param dateTime $_expiryDate the ExpiryDate
     * @return dateTime
     */
    public function setExpiryDate($_expiryDate)
    {
        return ($this->ExpiryDate = $_expiryDate);
    }
    /**
     * Get GuidedLearningHours value
     * @return int|null
     */
    public function getGuidedLearningHours()
    {
        return $this->GuidedLearningHours;
    }
    /**
     * Set GuidedLearningHours value
     * @param int $_guidedLearningHours the GuidedLearningHours
     * @return int
     */
    public function setGuidedLearningHours($_guidedLearningHours)
    {
        return ($this->GuidedLearningHours = $_guidedLearningHours);
    }
    /**
     * Get Level value
     * @return LRSStructLevel|null
     */
    public function getLevel()
    {
        return $this->Level;
    }
    /**
     * Set Level value
     * @param LRSStructLevel $_level the Level
     * @return LRSStructLevel
     */
    public function setLevel($_level)
    {
        return ($this->Level = $_level);
    }
    /**
     * Get OverallGradingStructure value
     * @return LRSStructGradingStructure|null
     */
    public function getOverallGradingStructure()
    {
        return $this->OverallGradingStructure;
    }
    /**
     * Set OverallGradingStructure value
     * @param LRSStructGradingStructure $_overallGradingStructure the OverallGradingStructure
     * @return LRSStructGradingStructure
     */
    public function setOverallGradingStructure($_overallGradingStructure)
    {
        return ($this->OverallGradingStructure = $_overallGradingStructure);
    }
    /**
     * Get OwningAwardingOrganisation value
     * @return LRSStructAwardingOrganisation|null
     */
    public function getOwningAwardingOrganisation()
    {
        return $this->OwningAwardingOrganisation;
    }
    /**
     * Set OwningAwardingOrganisation value
     * @param LRSStructAwardingOrganisation $_owningAwardingOrganisation the OwningAwardingOrganisation
     * @return LRSStructAwardingOrganisation
     */
    public function setOwningAwardingOrganisation($_owningAwardingOrganisation)
    {
        return ($this->OwningAwardingOrganisation = $_owningAwardingOrganisation);
    }
    /**
     * Get QualificationLevel value
     * @return LRSStructQualificationLevel|null
     */
    public function getQualificationLevel()
    {
        return $this->QualificationLevel;
    }
    /**
     * Set QualificationLevel value
     * @param LRSStructQualificationLevel $_qualificationLevel the QualificationLevel
     * @return LRSStructQualificationLevel
     */
    public function setQualificationLevel($_qualificationLevel)
    {
        return ($this->QualificationLevel = $_qualificationLevel);
    }
    /**
     * Get QualificationSubLevel value
     * @return LRSStructQualificationSubLevel|null
     */
    public function getQualificationSubLevel()
    {
        return $this->QualificationSubLevel;
    }
    /**
     * Set QualificationSubLevel value
     * @param LRSStructQualificationSubLevel $_qualificationSubLevel the QualificationSubLevel
     * @return LRSStructQualificationSubLevel
     */
    public function setQualificationSubLevel($_qualificationSubLevel)
    {
        return ($this->QualificationSubLevel = $_qualificationSubLevel);
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
     * Get RestrictedAwardingOrganisations value
     * @return LRSStructArrayOfAwardingOrganisation|null
     */
    public function getRestrictedAwardingOrganisations()
    {
        return $this->RestrictedAwardingOrganisations;
    }
    /**
     * Set RestrictedAwardingOrganisations value
     * @param LRSStructArrayOfAwardingOrganisation $_restrictedAwardingOrganisations the RestrictedAwardingOrganisations
     * @return LRSStructArrayOfAwardingOrganisation
     */
    public function setRestrictedAwardingOrganisations($_restrictedAwardingOrganisations)
    {
        return ($this->RestrictedAwardingOrganisations = $_restrictedAwardingOrganisations);
    }
    /**
     * Get ReviewDate value
     * @return dateTime|null
     */
    public function getReviewDate()
    {
        return $this->ReviewDate;
    }
    /**
     * Set ReviewDate value
     * @param dateTime $_reviewDate the ReviewDate
     * @return dateTime
     */
    public function setReviewDate($_reviewDate)
    {
        return ($this->ReviewDate = $_reviewDate);
    }
    /**
     * Get SectorSubjectAreas value
     * @return LRSStructArrayOfSectorSubjectArea|null
     */
    public function getSectorSubjectAreas()
    {
        return $this->SectorSubjectAreas;
    }
    /**
     * Set SectorSubjectAreas value
     * @param LRSStructArrayOfSectorSubjectArea $_sectorSubjectAreas the SectorSubjectAreas
     * @return LRSStructArrayOfSectorSubjectArea
     */
    public function setSectorSubjectAreas($_sectorSubjectAreas)
    {
        return ($this->SectorSubjectAreas = $_sectorSubjectAreas);
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
     * Get Status value
     * @return LRSEnumUnitStatus|null
     */
    public function getStatus()
    {
        return $this->Status;
    }
    /**
     * Set Status value
     * @uses LRSEnumUnitStatus::valueIsValid()
     * @param LRSEnumUnitStatus $_status the Status
     * @return LRSEnumUnitStatus
     */
    public function setStatus($_status)
    {
        if(!LRSEnumUnitStatus::valueIsValid($_status))
        {
            return false;
        }
        return ($this->Status = $_status);
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
     * Get UnitGradingStructure value
     * @return LRSEnumUnitGradingStructure|null
     */
    public function getUnitGradingStructure()
    {
        return $this->UnitGradingStructure;
    }
    /**
     * Set UnitGradingStructure value
     * @uses LRSEnumUnitGradingStructure::valueIsValid()
     * @param LRSEnumUnitGradingStructure $_unitGradingStructure the UnitGradingStructure
     * @return LRSEnumUnitGradingStructure
     */
    public function setUnitGradingStructure($_unitGradingStructure)
    {
        if(!LRSEnumUnitGradingStructure::valueIsValid($_unitGradingStructure))
        {
            return false;
        }
        return ($this->UnitGradingStructure = $_unitGradingStructure);
    }
    /**
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see LRSWsdlClass::__set_state()
     * @uses LRSWsdlClass::__set_state()
     * @param array $_array the exported values
     * @return LRSStructUnit
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
