<?php
/**
 * File for class LRSStructCompoundGroup
 * @package LRS
 * @subpackage Structs
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
/**
 * This class stands for LRSStructCompoundGroup originally named CompoundGroup
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://compact-soft.com/projects/api.lrs.qcf.gov.uk.model.xsd}
 * @package LRS
 * @subpackage Structs
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
class LRSStructCompoundGroup extends LRSStructGroup
{
    /**
     * The ChildGroups
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var LRSStructArrayOfGroup
     */
    public $ChildGroups;
    /**
     * Constructor method for CompoundGroup
     * @see parent::__construct()
     * @param LRSStructArrayOfGroup $_childGroups
     * @return LRSStructCompoundGroup
     */
    public function __construct($_childGroups = NULL)
    {
        LRSWsdlClass::__construct(array('ChildGroups'=>($_childGroups instanceof LRSStructArrayOfGroup)?$_childGroups:new LRSStructArrayOfGroup($_childGroups)),false);
    }
    /**
     * Get ChildGroups value
     * @return LRSStructArrayOfGroup|null
     */
    public function getChildGroups()
    {
        return $this->ChildGroups;
    }
    /**
     * Set ChildGroups value
     * @param LRSStructArrayOfGroup $_childGroups the ChildGroups
     * @return LRSStructArrayOfGroup
     */
    public function setChildGroups($_childGroups)
    {
        return ($this->ChildGroups = $_childGroups);
    }
    /**
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see LRSWsdlClass::__set_state()
     * @uses LRSWsdlClass::__set_state()
     * @param array $_array the exported values
     * @return LRSStructCompoundGroup
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
