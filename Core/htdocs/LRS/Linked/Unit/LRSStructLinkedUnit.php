<?php
/**
 * File for class LRSStructLinkedUnit
 * @package LRS
 * @subpackage Structs
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
/**
 * This class stands for LRSStructLinkedUnit originally named LinkedUnit
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://compact-soft.com/projects/api.lrs.qcf.gov.uk.model.xsd}
 * @package LRS
 * @subpackage Structs
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
class LRSStructLinkedUnit extends LRSStructReplicatedBusinessObject
{
    /**
     * The Unit
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var LRSStructUnit
     */
    public $Unit;
    /**
     * Constructor method for LinkedUnit
     * @see parent::__construct()
     * @param LRSStructUnit $_unit
     * @return LRSStructLinkedUnit
     */
    public function __construct($_unit = NULL)
    {
        LRSWsdlClass::__construct(array('Unit'=>$_unit),false);
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
     * @return LRSStructLinkedUnit
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
