<?php
/**
 * File for class LRSStructRocQueryResultUnit
 * @package LRS
 * @subpackage Structs
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
/**
 * This class stands for LRSStructRocQueryResultUnit originally named RocQueryResultUnit
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://compact-soft.com/projects/api.lrs.qcf.gov.uk.model.xsd}
 * @package LRS
 * @subpackage Structs
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
class LRSStructRocQueryResultUnit extends LRSStructBusinessObject
{
    /**
     * The IsAchieved
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * @var boolean
     */
    public $IsAchieved;
    /**
     * The IsIncluded
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * @var boolean
     */
    public $IsIncluded;
    /**
     * The IsMandatory
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * @var boolean
     */
    public $IsMandatory;
    /**
     * The Unit
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var LRSStructUnit
     */
    public $Unit;
    /**
     * Constructor method for RocQueryResultUnit
     * @see parent::__construct()
     * @param boolean $_isAchieved
     * @param boolean $_isIncluded
     * @param boolean $_isMandatory
     * @param LRSStructUnit $_unit
     * @return LRSStructRocQueryResultUnit
     */
    public function __construct($_isAchieved = NULL,$_isIncluded = NULL,$_isMandatory = NULL,$_unit = NULL)
    {
        LRSWsdlClass::__construct(array('IsAchieved'=>$_isAchieved,'IsIncluded'=>$_isIncluded,'IsMandatory'=>$_isMandatory,'Unit'=>$_unit),false);
    }
    /**
     * Get IsAchieved value
     * @return boolean|null
     */
    public function getIsAchieved()
    {
        return $this->IsAchieved;
    }
    /**
     * Set IsAchieved value
     * @param boolean $_isAchieved the IsAchieved
     * @return boolean
     */
    public function setIsAchieved($_isAchieved)
    {
        return ($this->IsAchieved = $_isAchieved);
    }
    /**
     * Get IsIncluded value
     * @return boolean|null
     */
    public function getIsIncluded()
    {
        return $this->IsIncluded;
    }
    /**
     * Set IsIncluded value
     * @param boolean $_isIncluded the IsIncluded
     * @return boolean
     */
    public function setIsIncluded($_isIncluded)
    {
        return ($this->IsIncluded = $_isIncluded);
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
     * Get Unit value
     * @return LRSStructUnit|null
     */
    public function getUnit()
    {
        return $this->Unit;
    }
    /**
     * Set Unit value
     * @param LRSStructUnit $_unit the Unit
     * @return LRSStructUnit
     */
    public function setUnit($_unit)
    {
        return ($this->Unit = $_unit);
    }
    /**
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see LRSWsdlClass::__set_state()
     * @uses LRSWsdlClass::__set_state()
     * @param array $_array the exported values
     * @return LRSStructRocQueryResultUnit
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
