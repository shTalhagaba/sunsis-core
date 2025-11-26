<?php
/**
 * File for class MIAPStructMIAPDeletedLearners
 * @package MIAP
 * @subpackage Structs
 * @date 2014-08-05
 * @author Mikaël DELSOL
 * @version 1
 */
/**
 * This class stands for MIAPStructMIAPDeletedLearners originally named MIAPDeletedLearners
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://localhost//learner.xsd}
 * @package MIAP
 * @subpackage Structs
 * @date 2014-08-05
 * @author Mikaël DELSOL
 * @version 1
 */
class MIAPStructMIAPDeletedLearners extends MIAPWsdlClass
{
    /**
     * The DeletedLearner
     * Meta informations extracted from the WSDL
     * - maxOccurs : unbounded
     * - minOccurs : 0
     * @var MIAPStructMIAPDeletedLearner
     */
    public $DeletedLearner;
    /**
     * Constructor method for MIAPDeletedLearners
     * @see parent::__construct()
     * @param MIAPStructMIAPDeletedLearner $_deletedLearner
     * @return MIAPStructMIAPDeletedLearners
     */
    public function __construct($_deletedLearner = NULL)
    {
        parent::__construct(array('DeletedLearner'=>$_deletedLearner),false);
    }
    /**
     * Get DeletedLearner value
     * @return MIAPStructMIAPDeletedLearner|null
     */
    public function getDeletedLearner()
    {
        return $this->DeletedLearner;
    }
    /**
     * Set DeletedLearner value
     * @param MIAPStructMIAPDeletedLearner $_deletedLearner the DeletedLearner
     * @return MIAPStructMIAPDeletedLearner
     */
    public function setDeletedLearner($_deletedLearner)
    {
        return ($this->DeletedLearner = $_deletedLearner);
    }
    /**
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see MIAPWsdlClass::__set_state()
     * @uses MIAPWsdlClass::__set_state()
     * @param array $_array the exported values
     * @return MIAPStructMIAPDeletedLearners
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
