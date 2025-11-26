<?php
/**
 * File for class LRSStructNQFUnitGroup
 * @package LRS
 * @subpackage Structs
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
/**
 * This class stands for LRSStructNQFUnitGroup originally named NQFUnitGroup
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://compact-soft.com/projects/api.lrs.qcf.gov.uk.model.xsd}
 * @package LRS
 * @subpackage Structs
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
class LRSStructNQFUnitGroup extends LRSStructNQFGroup
{
    /**
     * The LinkedNQFUnits
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var LRSStructArrayOfLinkedNQFUnit
     */
    public $LinkedNQFUnits;
    /**
     * Constructor method for NQFUnitGroup
     * @see parent::__construct()
     * @param LRSStructArrayOfLinkedNQFUnit $_linkedNQFUnits
     * @return LRSStructNQFUnitGroup
     */
    public function __construct($_linkedNQFUnits = NULL)
    {
        LRSWsdlClass::__construct(array('LinkedNQFUnits'=>($_linkedNQFUnits instanceof LRSStructArrayOfLinkedNQFUnit)?$_linkedNQFUnits:new LRSStructArrayOfLinkedNQFUnit($_linkedNQFUnits)),false);
    }
    /**
     * Get LinkedNQFUnits value
     * @return LRSStructArrayOfLinkedNQFUnit|null
     */
    public function getLinkedNQFUnits()
    {
        return $this->LinkedNQFUnits;
    }
    /**
     * Set LinkedNQFUnits value
     * @param LRSStructArrayOfLinkedNQFUnit $_linkedNQFUnits the LinkedNQFUnits
     * @return LRSStructArrayOfLinkedNQFUnit
     */
    public function setLinkedNQFUnits($_linkedNQFUnits)
    {
        return ($this->LinkedNQFUnits = $_linkedNQFUnits);
    }
    /**
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see LRSWsdlClass::__set_state()
     * @uses LRSWsdlClass::__set_state()
     * @param array $_array the exported values
     * @return LRSStructNQFUnitGroup
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
