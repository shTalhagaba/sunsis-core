<?php
/**
 * File for class LRSStructStructure
 * @package LRS
 * @subpackage Structs
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
/**
 * This class stands for LRSStructStructure originally named Structure
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://compact-soft.com/projects/api.lrs.qcf.gov.uk.model.xsd}
 * @package LRS
 * @subpackage Structs
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
class LRSStructStructure extends LRSStructBusinessObject
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
     * The BarredUnits
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var LRSStructArrayOfBarredUnit
     */
    public $BarredUnits;
    /**
     * The Group
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var LRSStructGroup
     */
    public $Group;
    /**
     * The Id
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * @var int
     */
    public $Id;
    /**
     * The MinimumCreditsAtOrAboveLevel
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * @var int
     */
    public $MinimumCreditsAtOrAboveLevel;
    /**
     * The Name
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $Name;
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
     * The SectorSubjectArea
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var LRSStructSectorSubjectArea
     */
    public $SectorSubjectArea;
    /**
     * The TotalCredits
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * @var int
     */
    public $TotalCredits;
    /**
     * Constructor method for Structure
     * @see parent::__construct()
     * @param LRSStructAwardingOrganisation $_awardingOrganisation
     * @param LRSStructArrayOfBarredUnit $_barredUnits
     * @param LRSStructGroup $_group
     * @param int $_id
     * @param int $_minimumCreditsAtOrAboveLevel
     * @param string $_name
     * @param LRSStructQualificationLevel $_qualificationLevel
     * @param LRSStructQualificationSubLevel $_qualificationSubLevel
     * @param LRSStructSectorSubjectArea $_sectorSubjectArea
     * @param int $_totalCredits
     * @return LRSStructStructure
     */
    public function __construct($_awardingOrganisation = NULL,$_barredUnits = NULL,$_group = NULL,$_id = NULL,$_minimumCreditsAtOrAboveLevel = NULL,$_name = NULL,$_qualificationLevel = NULL,$_qualificationSubLevel = NULL,$_sectorSubjectArea = NULL,$_totalCredits = NULL)
    {
        LRSWsdlClass::__construct(array('AwardingOrganisation'=>$_awardingOrganisation,'BarredUnits'=>($_barredUnits instanceof LRSStructArrayOfBarredUnit)?$_barredUnits:new LRSStructArrayOfBarredUnit($_barredUnits),'Group'=>$_group,'Id'=>$_id,'MinimumCreditsAtOrAboveLevel'=>$_minimumCreditsAtOrAboveLevel,'Name'=>$_name,'QualificationLevel'=>$_qualificationLevel,'QualificationSubLevel'=>$_qualificationSubLevel,'SectorSubjectArea'=>$_sectorSubjectArea,'TotalCredits'=>$_totalCredits),false);
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
     * Get BarredUnits value
     * @return LRSStructArrayOfBarredUnit|null
     */
    public function getBarredUnits()
    {
        return $this->BarredUnits;
    }
    /**
     * Set BarredUnits value
     * @param LRSStructArrayOfBarredUnit $_barredUnits the BarredUnits
     * @return LRSStructArrayOfBarredUnit
     */
    public function setBarredUnits($_barredUnits)
    {
        return ($this->BarredUnits = $_barredUnits);
    }
    /**
     * Get Group value
     * @return LRSStructGroup|null
     */
    public function getGroup()
    {
        return $this->Group;
    }
    /**
     * Set Group value
     * @param LRSStructGroup $_group the Group
     * @return LRSStructGroup
     */
    public function setGroup($_group)
    {
        return ($this->Group = $_group);
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
     * Get MinimumCreditsAtOrAboveLevel value
     * @return int|null
     */
    public function getMinimumCreditsAtOrAboveLevel()
    {
        return $this->MinimumCreditsAtOrAboveLevel;
    }
    /**
     * Set MinimumCreditsAtOrAboveLevel value
     * @param int $_minimumCreditsAtOrAboveLevel the MinimumCreditsAtOrAboveLevel
     * @return int
     */
    public function setMinimumCreditsAtOrAboveLevel($_minimumCreditsAtOrAboveLevel)
    {
        return ($this->MinimumCreditsAtOrAboveLevel = $_minimumCreditsAtOrAboveLevel);
    }
    /**
     * Get Name value
     * @return string|null
     */
    public function getName()
    {
        return $this->Name;
    }
    /**
     * Set Name value
     * @param string $_name the Name
     * @return string
     */
    public function setName($_name)
    {
        return ($this->Name = $_name);
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
     * Get SectorSubjectArea value
     * @return LRSStructSectorSubjectArea|null
     */
    public function getSectorSubjectArea()
    {
        return $this->SectorSubjectArea;
    }
    /**
     * Set SectorSubjectArea value
     * @param LRSStructSectorSubjectArea $_sectorSubjectArea the SectorSubjectArea
     * @return LRSStructSectorSubjectArea
     */
    public function setSectorSubjectArea($_sectorSubjectArea)
    {
        return ($this->SectorSubjectArea = $_sectorSubjectArea);
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
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see LRSWsdlClass::__set_state()
     * @uses LRSWsdlClass::__set_state()
     * @param array $_array the exported values
     * @return LRSStructStructure
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
