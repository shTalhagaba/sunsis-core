<?php
/**
 * File for class LRSStructArrayOfLearningEvent
 * @package LRS
 * @subpackage Structs
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
/**
 * This class stands for LRSStructArrayOfLearningEvent originally named ArrayOfLearningEvent
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://compact-soft.com/projects/api.lrs.qcf.gov.uk.model.xsd}
 * @package LRS
 * @subpackage Structs
 * @author WsdlToPhp Team <contact@wsdltophp.com>
 * @version 20150429-01
 * @date 2017-08-18
 */
class LRSStructArrayOfLearningEvent extends LRSWsdlClass
{
    /**
     * The LearningEvent
     * Meta informations extracted from the WSDL
     * - maxOccurs : unbounded
     * - minOccurs : 0
     * - nillable : true
     * @var LRSStructLearningEvent
     */
    public $LearningEvent;
    /**
     * Constructor method for ArrayOfLearningEvent
     * @see parent::__construct()
     * @param LRSStructLearningEvent $_learningEvent
     * @return LRSStructArrayOfLearningEvent
     */
    public function __construct($_learningEvent = NULL)
    {
        parent::__construct(array('LearningEvent'=>$_learningEvent),false);
    }
    /**
     * Get LearningEvent value
     * @return LRSStructLearningEvent|null
     */
    public function getLearningEvent()
    {
        return $this->LearningEvent;
    }
    /**
     * Set LearningEvent value
     * @param LRSStructLearningEvent $_learningEvent the LearningEvent
     * @return LRSStructLearningEvent
     */
    public function setLearningEvent($_learningEvent)
    {
        return ($this->LearningEvent = $_learningEvent);
    }
    /**
     * Returns the current element
     * @see LRSWsdlClass::current()
     * @return LRSStructLearningEvent
     */
    public function current()
    {
        return parent::current();
    }
    /**
     * Returns the indexed element
     * @see LRSWsdlClass::item()
     * @param int $_index
     * @return LRSStructLearningEvent
     */
    public function item($_index)
    {
        return parent::item($_index);
    }
    /**
     * Returns the first element
     * @see LRSWsdlClass::first()
     * @return LRSStructLearningEvent
     */
    public function first()
    {
        return parent::first();
    }
    /**
     * Returns the last element
     * @see LRSWsdlClass::last()
     * @return LRSStructLearningEvent
     */
    public function last()
    {
        return parent::last();
    }
    /**
     * Returns the element at the offset
     * @see LRSWsdlClass::last()
     * @param int $_offset
     * @return LRSStructLearningEvent
     */
    public function offsetGet($_offset)
    {
        return parent::offsetGet($_offset);
    }
    /**
     * Returns the attribute name
     * @see LRSWsdlClass::getAttributeName()
     * @return string LearningEvent
     */
    public function getAttributeName()
    {
        return 'LearningEvent';
    }
    /**
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see LRSWsdlClass::__set_state()
     * @uses LRSWsdlClass::__set_state()
     * @param array $_array the exported values
     * @return LRSStructArrayOfLearningEvent
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
