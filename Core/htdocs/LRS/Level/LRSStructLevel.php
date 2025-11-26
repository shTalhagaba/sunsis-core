<?php
/**
 * File for class LRSStructLevel
 * @package LRS
 * @subpackage Structs
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
/**
 * This class stands for LRSStructLevel originally named Level
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://compact-soft.com/projects/api.lrs.qcf.gov.uk.model.xsd}
 * @package LRS
 * @subpackage Structs
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
class LRSStructLevel extends LRSStructBusinessObject
{
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
     * Constructor method for Level
     * @see parent::__construct()
     * @param LRSStructQualificationLevel $_qualificationLevel
     * @param LRSStructQualificationSubLevel $_qualificationSubLevel
     * @return LRSStructLevel
     */
    public function __construct($_qualificationLevel = NULL,$_qualificationSubLevel = NULL)
    {
        LRSWsdlClass::__construct(array('QualificationLevel'=>$_qualificationLevel,'QualificationSubLevel'=>$_qualificationSubLevel),false);
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
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see LRSWsdlClass::__set_state()
     * @uses LRSWsdlClass::__set_state()
     * @param array $_array the exported values
     * @return LRSStructLevel
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
