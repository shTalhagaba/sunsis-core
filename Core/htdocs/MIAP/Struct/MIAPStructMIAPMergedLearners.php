<?php
/**
 * File for class MIAPStructMIAPMergedLearners
 * @package MIAP
 * @subpackage Structs
 * @date 2014-08-05
 * @author Mikaël DELSOL
 * @version 1
 */
/**
 * This class stands for MIAPStructMIAPMergedLearners originally named MIAPMergedLearners
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://localhost//learner.xsd}
 * @package MIAP
 * @subpackage Structs
 * @date 2014-08-05
 * @author Mikaël DELSOL
 * @version 1
 */
class MIAPStructMIAPMergedLearners extends MIAPWsdlClass
{
    /**
     * The MergedLearner
     * Meta informations extracted from the WSDL
     * - maxOccurs : unbounded
     * - minOccurs : 0
     * @var MIAPStructMIAPMergedLearner
     */
    public $MergedLearner;
    /**
     * Constructor method for MIAPMergedLearners
     * @see parent::__construct()
     * @param MIAPStructMIAPMergedLearner $_mergedLearner
     * @return MIAPStructMIAPMergedLearners
     */
    public function __construct($_mergedLearner = NULL)
    {
        parent::__construct(array('MergedLearner'=>$_mergedLearner),false);
    }
    /**
     * Get MergedLearner value
     * @return MIAPStructMIAPMergedLearner|null
     */
    public function getMergedLearner()
    {
        return $this->MergedLearner;
    }
    /**
     * Set MergedLearner value
     * @param MIAPStructMIAPMergedLearner $_mergedLearner the MergedLearner
     * @return MIAPStructMIAPMergedLearner
     */
    public function setMergedLearner($_mergedLearner)
    {
        return ($this->MergedLearner = $_mergedLearner);
    }
    /**
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see MIAPWsdlClass::__set_state()
     * @uses MIAPWsdlClass::__set_state()
     * @param array $_array the exported values
     * @return MIAPStructMIAPMergedLearners
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
