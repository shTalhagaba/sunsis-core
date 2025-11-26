<?php
/**
 * File for class LRSStructUnitGroup
 * @package LRS
 * @subpackage Structs
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
/**
 * This class stands for LRSStructUnitGroup originally named UnitGroup
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://compact-soft.com/projects/api.lrs.qcf.gov.uk.model.xsd}
 * @package LRS
 * @subpackage Structs
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
class LRSStructUnitGroup extends LRSStructGroup
{
    /**
     * The LinkedUnits
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var LRSStructArrayOfLinkedUnit
     */
    public $LinkedUnits;
    /**
     * Constructor method for UnitGroup
     * @see parent::__construct()
     * @param LRSStructArrayOfLinkedUnit $_linkedUnits
     * @return LRSStructUnitGroup
     */
    public function __construct($_linkedUnits = NULL)
    {
        LRSWsdlClass::__construct(array('LinkedUnits'=>($_linkedUnits instanceof LRSStructArrayOfLinkedUnit)?$_linkedUnits:new LRSStructArrayOfLinkedUnit($_linkedUnits)),false);
    }
    /**
     * Get LinkedUnits value
     * @return LRSStructArrayOfLinkedUnit|null
     */
    public function getLinkedUnits()
    {
        return $this->LinkedUnits;
    }
    /**
     * Set LinkedUnits value
     * @param LRSStructArrayOfLinkedUnit $_linkedUnits the LinkedUnits
     * @return LRSStructArrayOfLinkedUnit
     */
    public function setLinkedUnits($_linkedUnits)
    {
        return ($this->LinkedUnits = $_linkedUnits);
    }
    /**
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see LRSWsdlClass::__set_state()
     * @uses LRSWsdlClass::__set_state()
     * @param array $_array the exported values
     * @return LRSStructUnitGroup
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
