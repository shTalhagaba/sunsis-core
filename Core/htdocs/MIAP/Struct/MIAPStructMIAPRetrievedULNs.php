<?php
/**
 * File for class MIAPStructMIAPRetrievedULNs
 * @package MIAP
 * @subpackage Structs
 * @date 2014-08-05
 * @author Mikaël DELSOL
 * @version 1
 */
/**
 * This class stands for MIAPStructMIAPRetrievedULNs originally named MIAPRetrievedULNs
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://localhost//learner.xsd}
 * @package MIAP
 * @subpackage Structs
 * @date 2014-08-05
 * @author Mikaël DELSOL
 * @version 1
 */
class MIAPStructMIAPRetrievedULNs extends MIAPWsdlClass
{
    /**
     * The VerifiedULN
     * Meta informations extracted from the WSDL
     * - maxOccurs : 200
     * - minOccurs : 1
     * @var MIAPStructMIAPRetrievedULN
     */
    public $VerifiedULN;
    /**
     * Constructor method for MIAPRetrievedULNs
     * @see parent::__construct()
     * @param MIAPStructMIAPRetrievedULN $_verifiedULN
     * @return MIAPStructMIAPRetrievedULNs
     */
    public function __construct($_verifiedULN)
    {
        parent::__construct(array('VerifiedULN'=>$_verifiedULN),false);
    }
    /**
     * Get VerifiedULN value
     * @return MIAPStructMIAPRetrievedULN
     */
    public function getVerifiedULN()
    {
        return $this->VerifiedULN;
    }
    /**
     * Set VerifiedULN value
     * @param MIAPStructMIAPRetrievedULN $_verifiedULN the VerifiedULN
     * @return MIAPStructMIAPRetrievedULN
     */
    public function setVerifiedULN($_verifiedULN)
    {
        return ($this->VerifiedULN = $_verifiedULN);
    }
    /**
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see MIAPWsdlClass::__set_state()
     * @uses MIAPWsdlClass::__set_state()
     * @param array $_array the exported values
     * @return MIAPStructMIAPRetrievedULNs
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
