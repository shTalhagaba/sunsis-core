<?php
/**
 * File for class MIAPStructMIAPUnmergedLearners
 * @package MIAP
 * @subpackage Structs
 * @date 2014-08-05
 * @author Mikaël DELSOL
 * @version 1
 */
/**
 * This class stands for MIAPStructMIAPUnmergedLearners originally named MIAPUnmergedLearners
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://localhost//learner.xsd}
 * @package MIAP
 * @subpackage Structs
 * @date 2014-08-05
 * @author Mikaël DELSOL
 * @version 1
 */
class MIAPStructMIAPUnmergedLearners extends MIAPWsdlClass
{
    /**
     * The UnmergedLearner
     * Meta informations extracted from the WSDL
     * - maxOccurs : unbounded
     * - minOccurs : 0
     * @var MIAPStructMIAPUnmergedLearner
     */
    public $UnmergedLearner;
    /**
     * Constructor method for MIAPUnmergedLearners
     * @see parent::__construct()
     * @param MIAPStructMIAPUnmergedLearner $_unmergedLearner
     * @return MIAPStructMIAPUnmergedLearners
     */
    public function __construct($_unmergedLearner = NULL)
    {
        parent::__construct(array('UnmergedLearner'=>$_unmergedLearner),false);
    }
    /**
     * Get UnmergedLearner value
     * @return MIAPStructMIAPUnmergedLearner|null
     */
    public function getUnmergedLearner()
    {
        return $this->UnmergedLearner;
    }
    /**
     * Set UnmergedLearner value
     * @param MIAPStructMIAPUnmergedLearner $_unmergedLearner the UnmergedLearner
     * @return MIAPStructMIAPUnmergedLearner
     */
    public function setUnmergedLearner($_unmergedLearner)
    {
        return ($this->UnmergedLearner = $_unmergedLearner);
    }
    /**
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see MIAPWsdlClass::__set_state()
     * @uses MIAPWsdlClass::__set_state()
     * @param array $_array the exported values
     * @return MIAPStructMIAPUnmergedLearners
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
