<?php
/**
 * File for class LRSStructArrayOfNQFGroup
 * @package LRS
 * @subpackage Structs
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
/**
 * This class stands for LRSStructArrayOfNQFGroup originally named ArrayOfNQFGroup
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://compact-soft.com/projects/api.lrs.qcf.gov.uk.model.xsd}
 * @package LRS
 * @subpackage Structs
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
class LRSStructArrayOfNQFGroup extends LRSWsdlClass
{
    /**
     * The NQFGroup
     * Meta informations extracted from the WSDL
     * - maxOccurs : unbounded
     * - minOccurs : 0
     * - nillable : true
     * @var LRSStructNQFGroup
     */
    public $NQFGroup;
    /**
     * Constructor method for ArrayOfNQFGroup
     * @see parent::__construct()
     * @param LRSStructNQFGroup $_nQFGroup
     * @return LRSStructArrayOfNQFGroup
     */
    public function __construct($_nQFGroup = NULL)
    {
        parent::__construct(array('NQFGroup'=>$_nQFGroup),false);
    }
    /**
     * Get NQFGroup value
     * @return LRSStructNQFGroup|null
     */
    public function getNQFGroup()
    {
        return $this->NQFGroup;
    }
    /**
     * Set NQFGroup value
     * @param LRSStructNQFGroup $_nQFGroup the NQFGroup
     * @return LRSStructNQFGroup
     */
    public function setNQFGroup($_nQFGroup)
    {
        return ($this->NQFGroup = $_nQFGroup);
    }
    /**
     * Returns the current element
     * @see LRSWsdlClass::current()
     * @return LRSStructNQFGroup
     */
    public function current()
    {
        return parent::current();
    }
    /**
     * Returns the indexed element
     * @see LRSWsdlClass::item()
     * @param int $_index
     * @return LRSStructNQFGroup
     */
    public function item($_index)
    {
        return parent::item($_index);
    }
    /**
     * Returns the first element
     * @see LRSWsdlClass::first()
     * @return LRSStructNQFGroup
     */
    public function first()
    {
        return parent::first();
    }
    /**
     * Returns the last element
     * @see LRSWsdlClass::last()
     * @return LRSStructNQFGroup
     */
    public function last()
    {
        return parent::last();
    }
    /**
     * Returns the element at the offset
     * @see LRSWsdlClass::last()
     * @param int $_offset
     * @return LRSStructNQFGroup
     */
    public function offsetGet($_offset)
    {
        return parent::offsetGet($_offset);
    }
    /**
     * Returns the attribute name
     * @see LRSWsdlClass::getAttributeName()
     * @return string NQFGroup
     */
    public function getAttributeName()
    {
        return 'NQFGroup';
    }
    /**
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see LRSWsdlClass::__set_state()
     * @uses LRSWsdlClass::__set_state()
     * @param array $_array the exported values
     * @return LRSStructArrayOfNQFGroup
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
