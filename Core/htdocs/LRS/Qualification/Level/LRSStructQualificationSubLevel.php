<?php
/**
 * File for class LRSStructQualificationSubLevel
 * @package LRS
 * @subpackage Structs
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
/**
 * This class stands for LRSStructQualificationSubLevel originally named QualificationSubLevel
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://compact-soft.com/projects/api.lrs.qcf.gov.uk.model.xsd}
 * @package LRS
 * @subpackage Structs
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
class LRSStructQualificationSubLevel extends LRSStructReplicatedBusinessObject
{
    /**
     * The Description
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var string
     */
    public $Description;
    /**
     * The QualificationLevel
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var LRSStructQualificationLevel
     */
    public $QualificationLevel;
    /**
     * Constructor method for QualificationSubLevel
     * @see parent::__construct()
     * @param string $_description
     * @param LRSStructQualificationLevel $_qualificationLevel
     * @return LRSStructQualificationSubLevel
     */
    public function __construct($_description = NULL,$_qualificationLevel = NULL)
    {
        LRSWsdlClass::__construct(array('Description'=>$_description,'QualificationLevel'=>$_qualificationLevel),false);
    }
    /**
     * Get Description value
     * @return string|null
     */
    public function getDescription()
    {
        return $this->Description;
    }
    /**
     * Set Description value
     * @param string $_description the Description
     * @return string
     */
    public function setDescription($_description)
    {
        return ($this->Description = $_description);
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
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see LRSWsdlClass::__set_state()
     * @uses LRSWsdlClass::__set_state()
     * @param array $_array the exported values
     * @return LRSStructQualificationSubLevel
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
