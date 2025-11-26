<?php
/**
 * File for class MIAPStructUpdateLearnerResp
 * @package MIAP
 * @subpackage Structs
 * @date 2014-08-05
 * @author Mikaël DELSOL
 * @version 1
 */
/**
 * This class stands for MIAPStructUpdateLearnerResp originally named UpdateLearnerResp
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://localhost//registerlearner.xsd}
 * @package MIAP
 * @subpackage Structs
 * @date 2014-08-05
 * @author Mikaël DELSOL
 * @version 1
 */
class MIAPStructUpdateLearnerResp extends MIAPWsdlClass
{
    /**
     * The ResponseCode
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 1
     * @var string
     */
    public $ResponseCode;
    /**
     * Constructor method for UpdateLearnerResp
     * @see parent::__construct()
     * @param string $_responseCode
     * @return MIAPStructUpdateLearnerResp
     */
    public function __construct($_responseCode)
    {
        parent::__construct(array('ResponseCode'=>$_responseCode),false);
    }
    /**
     * Get ResponseCode value
     * @return string
     */
    public function getResponseCode()
    {
        return $this->ResponseCode;
    }
    /**
     * Set ResponseCode value
     * @param string $_responseCode the ResponseCode
     * @return string
     */
    public function setResponseCode($_responseCode)
    {
        return ($this->ResponseCode = $_responseCode);
    }
    /**
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see MIAPWsdlClass::__set_state()
     * @uses MIAPWsdlClass::__set_state()
     * @param array $_array the exported values
     * @return MIAPStructUpdateLearnerResp
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
