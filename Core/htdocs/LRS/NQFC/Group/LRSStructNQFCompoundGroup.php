<?php
/**
 * File for class LRSStructNQFCompoundGroup
 * @package LRS
 * @subpackage Structs
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
/**
 * This class stands for LRSStructNQFCompoundGroup originally named NQFCompoundGroup
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://compact-soft.com/projects/api.lrs.qcf.gov.uk.model.xsd}
 * @package LRS
 * @subpackage Structs
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
class LRSStructNQFCompoundGroup extends LRSStructNQFGroup
{
    /**
     * The ChildGroups
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var LRSStructArrayOfNQFGroup
     */
    public $ChildGroups;
    /**
     * Constructor method for NQFCompoundGroup
     * @see parent::__construct()
     * @param LRSStructArrayOfNQFGroup $_childGroups
     * @return LRSStructNQFCompoundGroup
     */
    public function __construct($_childGroups = NULL)
    {
        LRSWsdlClass::__construct(array('ChildGroups'=>($_childGroups instanceof LRSStructArrayOfNQFGroup)?$_childGroups:new LRSStructArrayOfNQFGroup($_childGroups)),false);
    }
    /**
     * Get ChildGroups value
     * @return LRSStructArrayOfNQFGroup|null
     */
    public function getChildGroups()
    {
        return $this->ChildGroups;
    }
    /**
     * Set ChildGroups value
     * @param LRSStructArrayOfNQFGroup $_childGroups the ChildGroups
     * @return LRSStructArrayOfNQFGroup
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
     * @return LRSStructNQFCompoundGroup
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
