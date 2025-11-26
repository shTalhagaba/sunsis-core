<?php
/**
 * File for class LRSStructNQFOtherCreditGroup
 * @package LRS
 * @subpackage Structs
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
/**
 * This class stands for LRSStructNQFOtherCreditGroup originally named NQFOtherCreditGroup
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://compact-soft.com/projects/api.lrs.qcf.gov.uk.model.xsd}
 * @package LRS
 * @subpackage Structs
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
class LRSStructNQFOtherCreditGroup extends LRSStructNQFGroup
{
    /**
     * The AchievedWithinMonths
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var int
     */
    public $AchievedWithinMonths;
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
     * The SectorSubjectAreas
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var LRSStructArrayOfSectorSubjectArea
     */
    public $SectorSubjectAreas;
    /**
     * Constructor method for NQFOtherCreditGroup
     * @see parent::__construct()
     * @param int $_achievedWithinMonths
     * @param LRSStructQualificationLevel $_qualificationLevel
     * @param LRSStructQualificationSubLevel $_qualificationSubLevel
     * @param LRSStructArrayOfSectorSubjectArea $_sectorSubjectAreas
     * @return LRSStructNQFOtherCreditGroup
     */
    public function __construct($_achievedWithinMonths = NULL,$_qualificationLevel = NULL,$_qualificationSubLevel = NULL,$_sectorSubjectAreas = NULL)
    {
        LRSWsdlClass::__construct(array('AchievedWithinMonths'=>$_achievedWithinMonths,'QualificationLevel'=>$_qualificationLevel,'QualificationSubLevel'=>$_qualificationSubLevel,'SectorSubjectAreas'=>($_sectorSubjectAreas instanceof LRSStructArrayOfSectorSubjectArea)?$_sectorSubjectAreas:new LRSStructArrayOfSectorSubjectArea($_sectorSubjectAreas)),false);
    }
    /**
     * Get AchievedWithinMonths value
     * @return int|null
     */
    public function getAchievedWithinMonths()
    {
        return $this->AchievedWithinMonths;
    }
    /**
     * Set AchievedWithinMonths value
     * @param int $_achievedWithinMonths the AchievedWithinMonths
     * @return int
     */
    public function setAchievedWithinMonths($_achievedWithinMonths)
    {
        return ($this->AchievedWithinMonths = $_achievedWithinMonths);
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
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see LRSWsdlClass::__set_state()
     * @uses LRSWsdlClass::__set_state()
     * @param array $_array the exported values
     * @return LRSStructNQFOtherCreditGroup
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
