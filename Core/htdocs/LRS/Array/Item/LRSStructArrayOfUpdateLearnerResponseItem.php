<?php
/**
 * File for class LRSStructArrayOfUpdateLearnerResponseItem
 * @package LRS
 * @subpackage Structs
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
/**
 * This class stands for LRSStructArrayOfUpdateLearnerResponseItem originally named ArrayOfUpdateLearnerResponseItem
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://compact-soft.com/projects/api.lrs.qcf.gov.uk.model.xsd}
 * @package LRS
 * @subpackage Structs
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
class LRSStructArrayOfUpdateLearnerResponseItem extends LRSWsdlClass
{
    /**
     * The UpdateLearnerResponseItem
     * Meta informations extracted from the WSDL
     * - maxOccurs : unbounded
     * - minOccurs : 0
     * - nillable : true
     * @var LRSStructUpdateLearnerResponseItem
     */
    public $UpdateLearnerResponseItem;
    /**
     * Constructor method for ArrayOfUpdateLearnerResponseItem
     * @see parent::__construct()
     * @param LRSStructUpdateLearnerResponseItem $_updateLearnerResponseItem
     * @return LRSStructArrayOfUpdateLearnerResponseItem
     */
    public function __construct($_updateLearnerResponseItem = NULL)
    {
        parent::__construct(array('UpdateLearnerResponseItem'=>$_updateLearnerResponseItem),false);
    }
    /**
     * Get UpdateLearnerResponseItem value
     * @return LRSStructUpdateLearnerResponseItem|null
     */
    public function getUpdateLearnerResponseItem()
    {
        return $this->UpdateLearnerResponseItem;
    }
    /**
     * Set UpdateLearnerResponseItem value
     * @param LRSStructUpdateLearnerResponseItem $_updateLearnerResponseItem the UpdateLearnerResponseItem
     * @return LRSStructUpdateLearnerResponseItem
     */
    public function setUpdateLearnerResponseItem($_updateLearnerResponseItem)
    {
        return ($this->UpdateLearnerResponseItem = $_updateLearnerResponseItem);
    }
    /**
     * Returns the current element
     * @see LRSWsdlClass::current()
     * @return LRSStructUpdateLearnerResponseItem
     */
    public function current()
    {
        return parent::current();
    }
    /**
     * Returns the indexed element
     * @see LRSWsdlClass::item()
     * @param int $_index
     * @return LRSStructUpdateLearnerResponseItem
     */
    public function item($_index)
    {
        return parent::item($_index);
    }
    /**
     * Returns the first element
     * @see LRSWsdlClass::first()
     * @return LRSStructUpdateLearnerResponseItem
     */
    public function first()
    {
        return parent::first();
    }
    /**
     * Returns the last element
     * @see LRSWsdlClass::last()
     * @return LRSStructUpdateLearnerResponseItem
     */
    public function last()
    {
        return parent::last();
    }
    /**
     * Returns the element at the offset
     * @see LRSWsdlClass::last()
     * @param int $_offset
     * @return LRSStructUpdateLearnerResponseItem
     */
    public function offsetGet($_offset)
    {
        return parent::offsetGet($_offset);
    }
    /**
     * Returns the attribute name
     * @see LRSWsdlClass::getAttributeName()
     * @return string UpdateLearnerResponseItem
     */
    public function getAttributeName()
    {
        return 'UpdateLearnerResponseItem';
    }
    /**
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see LRSWsdlClass::__set_state()
     * @uses LRSWsdlClass::__set_state()
     * @param array $_array the exported values
     * @return LRSStructArrayOfUpdateLearnerResponseItem
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
