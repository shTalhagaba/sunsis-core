<?php
/**
 * File for class LRSStructRtaOneQuery
 * @package LRS
 * @subpackage Structs
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
/**
 * This class stands for LRSStructRtaOneQuery originally named RtaOneQuery
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://compact-soft.com/projects/api.lrs.qcf.gov.uk.model.xsd}
 * @package LRS
 * @subpackage Structs
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
class LRSStructRtaOneQuery extends LRSStructManagedBusinessObject
{
    /**
     * The ComputeCreditsTowards
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * @var boolean
     */
    public $ComputeCreditsTowards;
    /**
     * The ExtraUnitsForCreditTowards
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var LRSStructArrayOfUnit
     */
    public $ExtraUnitsForCreditTowards;
    /**
     * The IncludeLearnerUnits
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * @var boolean
     */
    public $IncludeLearnerUnits;
    /**
     * The LearnerUln
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $LearnerUln;
    /**
     * The Name
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $Name;
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
     * The QualificationLevel
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $QualificationLevel;
    /**
     * The QualificationReferenceNumber
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $QualificationReferenceNumber;
    /**
     * The QualificationSize
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $QualificationSize;
    /**
     * The QualificationTitle
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $QualificationTitle;
    /**
     * The SectorSubjectArea
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var LRSStructArrayOfSectorSubjectArea
     */
    public $SectorSubjectArea;
    /**
     * The UnitList
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var LRSStructArrayOfUnit
     */
    public $UnitList;
    /**
     * Constructor method for RtaOneQuery
     * @see parent::__construct()
     * @param boolean $_computeCreditsTowards
     * @param LRSStructArrayOfUnit $_extraUnitsForCreditTowards
     * @param boolean $_includeLearnerUnits
     * @param string $_learnerUln
     * @param string $_name
     * @param boolean $_offeredInEngland
     * @param boolean $_offeredInNorthernIreland
     * @param boolean $_offeredInWales
     * @param string $_qualificationLevel
     * @param string $_qualificationReferenceNumber
     * @param string $_qualificationSize
     * @param string $_qualificationTitle
     * @param LRSStructArrayOfSectorSubjectArea $_sectorSubjectArea
     * @param LRSStructArrayOfUnit $_unitList
     * @return LRSStructRtaOneQuery
     */
    public function __construct($_computeCreditsTowards = NULL,$_extraUnitsForCreditTowards = NULL,$_includeLearnerUnits = NULL,$_learnerUln = NULL,$_name = NULL,$_offeredInEngland = NULL,$_offeredInNorthernIreland = NULL,$_offeredInWales = NULL,$_qualificationLevel = NULL,$_qualificationReferenceNumber = NULL,$_qualificationSize = NULL,$_qualificationTitle = NULL,$_sectorSubjectArea = NULL,$_unitList = NULL)
    {
        LRSWsdlClass::__construct(array('ComputeCreditsTowards'=>$_computeCreditsTowards,'ExtraUnitsForCreditTowards'=>($_extraUnitsForCreditTowards instanceof LRSStructArrayOfUnit)?$_extraUnitsForCreditTowards:new LRSStructArrayOfUnit($_extraUnitsForCreditTowards),'IncludeLearnerUnits'=>$_includeLearnerUnits,'LearnerUln'=>$_learnerUln,'Name'=>$_name,'OfferedInEngland'=>$_offeredInEngland,'OfferedInNorthernIreland'=>$_offeredInNorthernIreland,'OfferedInWales'=>$_offeredInWales,'QualificationLevel'=>$_qualificationLevel,'QualificationReferenceNumber'=>$_qualificationReferenceNumber,'QualificationSize'=>$_qualificationSize,'QualificationTitle'=>$_qualificationTitle,'SectorSubjectArea'=>($_sectorSubjectArea instanceof LRSStructArrayOfSectorSubjectArea)?$_sectorSubjectArea:new LRSStructArrayOfSectorSubjectArea($_sectorSubjectArea),'UnitList'=>($_unitList instanceof LRSStructArrayOfUnit)?$_unitList:new LRSStructArrayOfUnit($_unitList)),false);
    }
    /**
     * Get ComputeCreditsTowards value
     * @return boolean|null
     */
    public function getComputeCreditsTowards()
    {
        return $this->ComputeCreditsTowards;
    }
    /**
     * Set ComputeCreditsTowards value
     * @param boolean $_computeCreditsTowards the ComputeCreditsTowards
     * @return boolean
     */
    public function setComputeCreditsTowards($_computeCreditsTowards)
    {
        return ($this->ComputeCreditsTowards = $_computeCreditsTowards);
    }
    /**
     * Get ExtraUnitsForCreditTowards value
     * @return LRSStructArrayOfUnit|null
     */
    public function getExtraUnitsForCreditTowards()
    {
        return $this->ExtraUnitsForCreditTowards;
    }
    /**
     * Set ExtraUnitsForCreditTowards value
     * @param LRSStructArrayOfUnit $_extraUnitsForCreditTowards the ExtraUnitsForCreditTowards
     * @return LRSStructArrayOfUnit
     */
    public function setExtraUnitsForCreditTowards($_extraUnitsForCreditTowards)
    {
        return ($this->ExtraUnitsForCreditTowards = $_extraUnitsForCreditTowards);
    }
    /**
     * Get IncludeLearnerUnits value
     * @return boolean|null
     */
    public function getIncludeLearnerUnits()
    {
        return $this->IncludeLearnerUnits;
    }
    /**
     * Set IncludeLearnerUnits value
     * @param boolean $_includeLearnerUnits the IncludeLearnerUnits
     * @return boolean
     */
    public function setIncludeLearnerUnits($_includeLearnerUnits)
    {
        return ($this->IncludeLearnerUnits = $_includeLearnerUnits);
    }
    /**
     * Get LearnerUln value
     * @return string|null
     */
    public function getLearnerUln()
    {
        return $this->LearnerUln;
    }
    /**
     * Set LearnerUln value
     * @param string $_learnerUln the LearnerUln
     * @return string
     */
    public function setLearnerUln($_learnerUln)
    {
        return ($this->LearnerUln = $_learnerUln);
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
     * Get QualificationLevel value
     * @return string|null
     */
    public function getQualificationLevel()
    {
        return $this->QualificationLevel;
    }
    /**
     * Set QualificationLevel value
     * @param string $_qualificationLevel the QualificationLevel
     * @return string
     */
    public function setQualificationLevel($_qualificationLevel)
    {
        return ($this->QualificationLevel = $_qualificationLevel);
    }
    /**
     * Get QualificationReferenceNumber value
     * @return string|null
     */
    public function getQualificationReferenceNumber()
    {
        return $this->QualificationReferenceNumber;
    }
    /**
     * Set QualificationReferenceNumber value
     * @param string $_qualificationReferenceNumber the QualificationReferenceNumber
     * @return string
     */
    public function setQualificationReferenceNumber($_qualificationReferenceNumber)
    {
        return ($this->QualificationReferenceNumber = $_qualificationReferenceNumber);
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
     * Get QualificationTitle value
     * @return string|null
     */
    public function getQualificationTitle()
    {
        return $this->QualificationTitle;
    }
    /**
     * Set QualificationTitle value
     * @param string $_qualificationTitle the QualificationTitle
     * @return string
     */
    public function setQualificationTitle($_qualificationTitle)
    {
        return ($this->QualificationTitle = $_qualificationTitle);
    }
    /**
     * Get SectorSubjectArea value
     * @return LRSStructArrayOfSectorSubjectArea|null
     */
    public function getSectorSubjectArea()
    {
        return $this->SectorSubjectArea;
    }
    /**
     * Set SectorSubjectArea value
     * @param LRSStructArrayOfSectorSubjectArea $_sectorSubjectArea the SectorSubjectArea
     * @return LRSStructArrayOfSectorSubjectArea
     */
    public function setSectorSubjectArea($_sectorSubjectArea)
    {
        return ($this->SectorSubjectArea = $_sectorSubjectArea);
    }
    /**
     * Get UnitList value
     * @return LRSStructArrayOfUnit|null
     */
    public function getUnitList()
    {
        return $this->UnitList;
    }
    /**
     * Set UnitList value
     * @param LRSStructArrayOfUnit $_unitList the UnitList
     * @return LRSStructArrayOfUnit
     */
    public function setUnitList($_unitList)
    {
        return ($this->UnitList = $_unitList);
    }
    /**
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see LRSWsdlClass::__set_state()
     * @uses LRSWsdlClass::__set_state()
     * @param array $_array the exported values
     * @return LRSStructRtaOneQuery
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
