<?php
/**
 * File for class LRSStructLinkedNQFUnit
 * @package LRS
 * @subpackage Structs
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
/**
 * This class stands for LRSStructLinkedNQFUnit originally named LinkedNQFUnit
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://compact-soft.com/projects/api.lrs.qcf.gov.uk.model.xsd}
 * @package LRS
 * @subpackage Structs
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
class LRSStructLinkedNQFUnit extends LRSStructReplicatedBusinessObject
{
    /**
     * The NQFUnit
     * Meta informations extracted from the WSDL
     * - minOccurs : 0
     * - nillable : true
     * @var LRSStructNQFUnit
     */
    public $NQFUnit;
    /**
     * Constructor method for LinkedNQFUnit
     * @see parent::__construct()
     * @param LRSStructNQFUnit $_nQFUnit
     * @return LRSStructLinkedNQFUnit
     */
    public function __construct($_nQFUnit = NULL)
    {
        LRSWsdlClass::__construct(array('NQFUnit'=>$_nQFUnit),false);
    }
    /**
     * Get NQFUnit value
     * @return LRSStructNQFUnit|null
     */
    public function getNQFUnit()
    {
        return $this->NQFUnit;
    }
    /**
     * Set NQFUnit value
     * @param LRSStructNQFUnit $_nQFUnit the NQFUnit
     * @return LRSStructNQFUnit
     */
    public function setNQFUnit($_nQFUnit)
    {
        return ($this->NQFUnit = $_nQFUnit);
    }
    /**
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see LRSWsdlClass::__set_state()
     * @uses LRSWsdlClass::__set_state()
     * @param array $_array the exported values
     * @return LRSStructLinkedNQFUnit
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
