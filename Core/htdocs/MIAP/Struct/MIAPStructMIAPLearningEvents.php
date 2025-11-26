<?php
/**
 * File for class MIAPStructMIAPLearningEvents
 * @package MIAP
 * @subpackage Structs
 * @date 2014-08-05
 * @author Mikaël DELSOL
 * @version 1
 */
/**
 * This class stands for MIAPStructMIAPLearningEvents originally named MIAPLearningEvents
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://localhost//learnerrecord.xsd}
 * @package MIAP
 * @subpackage Structs
 * @date 2014-08-05
 * @author Mikaël DELSOL
 * @version 1
 */
class MIAPStructMIAPLearningEvents extends MIAPWsdlClass
{
    /**
     * The LearningEvent
     * Meta informations extracted from the WSDL
     * - maxOccurs : unbounded
     * - minOccurs : 0
     * @var MIAPStructMIAPLearningEvent
     */
    public $LearningEvent;
    /**
     * Constructor method for MIAPLearningEvents
     * @see parent::__construct()
     * @param MIAPStructMIAPLearningEvent $_learningEvent
     * @return MIAPStructMIAPLearningEvents
     */
    public function __construct($_learningEvent = NULL)
    {
        parent::__construct(array('LearningEvent'=>$_learningEvent),false);
    }
    /**
     * Get LearningEvent value
     * @return MIAPStructMIAPLearningEvent|null
     */
    public function getLearningEvent()
    {
        return $this->LearningEvent;
    }
    /**
     * Set LearningEvent value
     * @param MIAPStructMIAPLearningEvent $_learningEvent the LearningEvent
     * @return MIAPStructMIAPLearningEvent
     */
    public function setLearningEvent($_learningEvent)
    {
        return ($this->LearningEvent = $_learningEvent);
    }
    /**
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see MIAPWsdlClass::__set_state()
     * @uses MIAPWsdlClass::__set_state()
     * @param array $_array the exported values
     * @return MIAPStructMIAPLearningEvents
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
