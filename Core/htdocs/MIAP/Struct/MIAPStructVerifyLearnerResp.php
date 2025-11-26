<?php
/**
 * File for class MIAPStructVerifyLearnerResp
 * @package MIAP
 * @subpackage Structs
 * @date 2014-08-05
 * @author Mikaël DELSOL
 * @version 1
 */
/**
 * This class stands for MIAPStructVerifyLearnerResp originally named VerifyLearnerResp
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://localhost//learnerreport.xsd}
 * @package MIAP
 * @subpackage Structs
 * @date 2014-08-05
 * @author Mikaël DELSOL
 * @version 1
 */
class MIAPStructVerifyLearnerResp extends MIAPWsdlClass
{
    /**
     * The VerifiedLearner
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 1
     * - nillable : false
     * @var MIAPStructMIAPVerifiedLearner
     */
    public $VerifiedLearner;
    /**
     * Constructor method for VerifyLearnerResp
     * @see parent::__construct()
     * @param MIAPStructMIAPVerifiedLearner $_verifiedLearner
     * @return MIAPStructVerifyLearnerResp
     */
    public function __construct($_verifiedLearner)
    {
        parent::__construct(array('VerifiedLearner'=>$_verifiedLearner),false);
    }
    /**
     * Get VerifiedLearner value
     * @return MIAPStructMIAPVerifiedLearner
     */
    public function getVerifiedLearner()
    {
        return $this->VerifiedLearner;
    }
    /**
     * Set VerifiedLearner value
     * @param MIAPStructMIAPVerifiedLearner $_verifiedLearner the VerifiedLearner
     * @return MIAPStructMIAPVerifiedLearner
     */
    public function setVerifiedLearner($_verifiedLearner)
    {
        return ($this->VerifiedLearner = $_verifiedLearner);
    }
    /**
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see MIAPWsdlClass::__set_state()
     * @uses MIAPWsdlClass::__set_state()
     * @param array $_array the exported values
     * @return MIAPStructVerifyLearnerResp
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
