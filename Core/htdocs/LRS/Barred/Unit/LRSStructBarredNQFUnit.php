<?php
/**
 * File for class LRSStructBarredNQFUnit
 * @package LRS
 * @subpackage Structs
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
/**
 * This class stands for LRSStructBarredNQFUnit originally named BarredNQFUnit
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://compact-soft.com/projects/api.lrs.qcf.gov.uk.model.xsd}
 * @package LRS
 * @subpackage Structs
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
class LRSStructBarredNQFUnit extends LRSStructBarredUnitBase
{
    /**
     * The SourceUnit
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var LRSStructNQFUnit
     */
    public $SourceUnit;
    /**
     * The TargetUnits
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var LRSStructArrayOfNQFUnit
     */
    public $TargetUnits;
    /**
     * Constructor method for BarredNQFUnit
     * @see parent::__construct()
     * @param LRSStructNQFUnit $_sourceUnit
     * @param LRSStructArrayOfNQFUnit $_targetUnits
     * @return LRSStructBarredNQFUnit
     */
    public function __construct($_sourceUnit = NULL,$_targetUnits = NULL)
    {
        LRSWsdlClass::__construct(array('SourceUnit'=>$_sourceUnit,'TargetUnits'=>($_targetUnits instanceof LRSStructArrayOfNQFUnit)?$_targetUnits:new LRSStructArrayOfNQFUnit($_targetUnits)),false);
    }
    /**
     * Get SourceUnit value
     * @return LRSStructNQFUnit|null
     */
    public function getSourceUnit()
    {
        return $this->SourceUnit;
    }
    /**
     * Set SourceUnit value
     * @param LRSStructNQFUnit $_sourceUnit the SourceUnit
     * @return LRSStructNQFUnit
     */
    public function setSourceUnit($_sourceUnit)
    {
        return ($this->SourceUnit = $_sourceUnit);
    }
    /**
     * Get TargetUnits value
     * @return LRSStructArrayOfNQFUnit|null
     */
    public function getTargetUnits()
    {
        return $this->TargetUnits;
    }
    /**
     * Set TargetUnits value
     * @param LRSStructArrayOfNQFUnit $_targetUnits the TargetUnits
     * @return LRSStructArrayOfNQFUnit
     */
    public function setTargetUnits($_targetUnits)
    {
        return ($this->TargetUnits = $_targetUnits);
    }
    /**
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see LRSWsdlClass::__set_state()
     * @uses LRSWsdlClass::__set_state()
     * @param array $_array the exported values
     * @return LRSStructBarredNQFUnit
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
