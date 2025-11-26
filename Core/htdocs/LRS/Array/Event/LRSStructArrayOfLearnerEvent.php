<?php
/**
 * File for class LRSStructArrayOfLearnerEvent
 * @package LRS
 * @subpackage Structs
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
/**
 * This class stands for LRSStructArrayOfLearnerEvent originally named ArrayOfLearnerEvent
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://compact-soft.com/projects/api.lrs.qcf.gov.uk.model.xsd}
 * @package LRS
 * @subpackage Structs
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
class LRSStructArrayOfLearnerEvent extends LRSWsdlClass
{
    /**
     * The LearnerEvent
     * Meta informations extracted from the WSDL
     * - maxOccurs : unbounded
     * - minOccurs : 0
     * - nillable : true
     * @var LRSStructLearnerEvent
     */
    public $LearnerEvent;
    /**
     * Constructor method for ArrayOfLearnerEvent
     * @see parent::__construct()
     * @param LRSStructLearnerEvent $_learnerEvent
     * @return LRSStructArrayOfLearnerEvent
     */
    public function __construct($_learnerEvent = NULL)
    {
        parent::__construct(array('LearnerEvent'=>$_learnerEvent),false);
    }
    /**
     * Get LearnerEvent value
     * @return LRSStructLearnerEvent|null
     */
    public function getLearnerEvent()
    {
        return $this->LearnerEvent;
    }
    /**
     * Set LearnerEvent value
     * @param LRSStructLearnerEvent $_learnerEvent the LearnerEvent
     * @return LRSStructLearnerEvent
     */
    public function setLearnerEvent($_learnerEvent)
    {
        return ($this->LearnerEvent = $_learnerEvent);
    }
    /**
     * Returns the current element
     * @see LRSWsdlClass::current()
     * @return LRSStructLearnerEvent
     */
    public function current()
    {
        return parent::current();
    }
    /**
     * Returns the indexed element
     * @see LRSWsdlClass::item()
     * @param int $_index
     * @return LRSStructLearnerEvent
     */
    public function item($_index)
    {
        return parent::item($_index);
    }
    /**
     * Returns the first element
     * @see LRSWsdlClass::first()
     * @return LRSStructLearnerEvent
     */
    public function first()
    {
        return parent::first();
    }
    /**
     * Returns the last element
     * @see LRSWsdlClass::last()
     * @return LRSStructLearnerEvent
     */
    public function last()
    {
        return parent::last();
    }
    /**
     * Returns the element at the offset
     * @see LRSWsdlClass::last()
     * @param int $_offset
     * @return LRSStructLearnerEvent
     */
    public function offsetGet($_offset)
    {
        return parent::offsetGet($_offset);
    }
    /**
     * Returns the attribute name
     * @see LRSWsdlClass::getAttributeName()
     * @return string LearnerEvent
     */
    public function getAttributeName()
    {
        return 'LearnerEvent';
    }
    /**
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see LRSWsdlClass::__set_state()
     * @uses LRSWsdlClass::__set_state()
     * @param array $_array the exported values
     * @return LRSStructArrayOfLearnerEvent
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
