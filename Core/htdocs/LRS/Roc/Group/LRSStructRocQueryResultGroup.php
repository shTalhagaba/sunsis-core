<?php
/**
 * File for class LRSStructRocQueryResultGroup
 * @package LRS
 * @subpackage Structs
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
/**
 * This class stands for LRSStructRocQueryResultGroup originally named RocQueryResultGroup
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://compact-soft.com/projects/api.lrs.qcf.gov.uk.model.xsd}
 * @package LRS
 * @subpackage Structs
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
class LRSStructRocQueryResultGroup extends LRSStructBusinessObject
{
    /**
     * The DecisionTaken
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * @var boolean
     */
    public $DecisionTaken;
    /**
     * The Group
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var LRSStructGroupBase
     */
    public $Group;
    /**
     * The IsAcheived
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * @var boolean
     */
    public $IsAcheived;
    /**
     * The IsMandatory
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * @var boolean
     */
    public $IsMandatory;
    /**
     * The TotalCredits
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * @var int
     */
    public $TotalCredits;
    /**
     * The TotalCreditsAboveLevel
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * @var int
     */
    public $TotalCreditsAboveLevel;
    /**
     * The TotalCreditsAtOrAboveLevel
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * @var int
     */
    public $TotalCreditsAtOrAboveLevel;
    /**
     * The TotalSubcomponents
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * @var int
     */
    public $TotalSubcomponents;
    /**
     * Constructor method for RocQueryResultGroup
     * @see parent::__construct()
     * @param boolean $_decisionTaken
     * @param LRSStructGroupBase $_group
     * @param boolean $_isAcheived
     * @param boolean $_isMandatory
     * @param int $_totalCredits
     * @param int $_totalCreditsAboveLevel
     * @param int $_totalCreditsAtOrAboveLevel
     * @param int $_totalSubcomponents
     * @return LRSStructRocQueryResultGroup
     */
    public function __construct($_decisionTaken = NULL,$_group = NULL,$_isAcheived = NULL,$_isMandatory = NULL,$_totalCredits = NULL,$_totalCreditsAboveLevel = NULL,$_totalCreditsAtOrAboveLevel = NULL,$_totalSubcomponents = NULL)
    {
        LRSWsdlClass::__construct(array('DecisionTaken'=>$_decisionTaken,'Group'=>$_group,'IsAcheived'=>$_isAcheived,'IsMandatory'=>$_isMandatory,'TotalCredits'=>$_totalCredits,'TotalCreditsAboveLevel'=>$_totalCreditsAboveLevel,'TotalCreditsAtOrAboveLevel'=>$_totalCreditsAtOrAboveLevel,'TotalSubcomponents'=>$_totalSubcomponents),false);
    }
    /**
     * Get DecisionTaken value
     * @return boolean|null
     */
    public function getDecisionTaken()
    {
        return $this->DecisionTaken;
    }
    /**
     * Set DecisionTaken value
     * @param boolean $_decisionTaken the DecisionTaken
     * @return boolean
     */
    public function setDecisionTaken($_decisionTaken)
    {
        return ($this->DecisionTaken = $_decisionTaken);
    }
    /**
     * Get Group value
     * @return LRSStructGroupBase|null
     */
    public function getGroup()
    {
        return $this->Group;
    }
    /**
     * Set Group value
     * @param LRSStructGroupBase $_group the Group
     * @return LRSStructGroupBase
     */
    public function setGroup($_group)
    {
        return ($this->Group = $_group);
    }
    /**
     * Get IsAcheived value
     * @return boolean|null
     */
    public function getIsAcheived()
    {
        return $this->IsAcheived;
    }
    /**
     * Set IsAcheived value
     * @param boolean $_isAcheived the IsAcheived
     * @return boolean
     */
    public function setIsAcheived($_isAcheived)
    {
        return ($this->IsAcheived = $_isAcheived);
    }
    /**
     * Get IsMandatory value
     * @return boolean|null
     */
    public function getIsMandatory()
    {
        return $this->IsMandatory;
    }
    /**
     * Set IsMandatory value
     * @param boolean $_isMandatory the IsMandatory
     * @return boolean
     */
    public function setIsMandatory($_isMandatory)
    {
        return ($this->IsMandatory = $_isMandatory);
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
     * Get TotalCreditsAboveLevel value
     * @return int|null
     */
    public function getTotalCreditsAboveLevel()
    {
        return $this->TotalCreditsAboveLevel;
    }
    /**
     * Set TotalCreditsAboveLevel value
     * @param int $_totalCreditsAboveLevel the TotalCreditsAboveLevel
     * @return int
     */
    public function setTotalCreditsAboveLevel($_totalCreditsAboveLevel)
    {
        return ($this->TotalCreditsAboveLevel = $_totalCreditsAboveLevel);
    }
    /**
     * Get TotalCreditsAtOrAboveLevel value
     * @return int|null
     */
    public function getTotalCreditsAtOrAboveLevel()
    {
        return $this->TotalCreditsAtOrAboveLevel;
    }
    /**
     * Set TotalCreditsAtOrAboveLevel value
     * @param int $_totalCreditsAtOrAboveLevel the TotalCreditsAtOrAboveLevel
     * @return int
     */
    public function setTotalCreditsAtOrAboveLevel($_totalCreditsAtOrAboveLevel)
    {
        return ($this->TotalCreditsAtOrAboveLevel = $_totalCreditsAtOrAboveLevel);
    }
    /**
     * Get TotalSubcomponents value
     * @return int|null
     */
    public function getTotalSubcomponents()
    {
        return $this->TotalSubcomponents;
    }
    /**
     * Set TotalSubcomponents value
     * @param int $_totalSubcomponents the TotalSubcomponents
     * @return int
     */
    public function setTotalSubcomponents($_totalSubcomponents)
    {
        return ($this->TotalSubcomponents = $_totalSubcomponents);
    }
    /**
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see LRSWsdlClass::__set_state()
     * @uses LRSWsdlClass::__set_state()
     * @param array $_array the exported values
     * @return LRSStructRocQueryResultGroup
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
