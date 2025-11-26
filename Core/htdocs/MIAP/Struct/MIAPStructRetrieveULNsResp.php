<?php
/**
 * File for class MIAPStructRetrieveULNsResp
 * @package MIAP
 * @subpackage Structs
 * @date 2014-08-05
 * @author Mikaël DELSOL
 * @version 1
 */
/**
 * This class stands for MIAPStructRetrieveULNsResp originally named RetrieveULNsResp
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://localhost//learnerreport.xsd}
 * @package MIAP
 * @subpackage Structs
 * @date 2014-08-05
 * @author Mikaël DELSOL
 * @version 1
 */
class MIAPStructRetrieveULNsResp extends MIAPWsdlClass
{
    /**
     * The RetrievedULNs
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 1
     * - nillable : false
     * @var MIAPStructMIAPRetrievedULNs
     */
    public $RetrievedULNs;
    /**
     * Constructor method for RetrieveULNsResp
     * @see parent::__construct()
     * @param MIAPStructMIAPRetrievedULNs $_retrievedULNs
     * @return MIAPStructRetrieveULNsResp
     */
    public function __construct($_retrievedULNs)
    {
        parent::__construct(array('RetrievedULNs'=>$_retrievedULNs),false);
    }
    /**
     * Get RetrievedULNs value
     * @return MIAPStructMIAPRetrievedULNs
     */
    public function getRetrievedULNs()
    {
        return $this->RetrievedULNs;
    }
    /**
     * Set RetrievedULNs value
     * @param MIAPStructMIAPRetrievedULNs $_retrievedULNs the RetrievedULNs
     * @return MIAPStructMIAPRetrievedULNs
     */
    public function setRetrievedULNs($_retrievedULNs)
    {
        return ($this->RetrievedULNs = $_retrievedULNs);
    }
    /**
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see MIAPWsdlClass::__set_state()
     * @uses MIAPWsdlClass::__set_state()
     * @param array $_array the exported values
     * @return MIAPStructRetrieveULNsResp
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
