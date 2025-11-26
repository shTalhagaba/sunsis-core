<?php
/**
 * File for class MIAPStructBatchRegistrationResp
 * @package MIAP
 * @subpackage Structs
 * @date 2014-08-05
 * @author Mikaël DELSOL
 * @version 1
 */
/**
 * This class stands for MIAPStructBatchRegistrationResp originally named BatchRegistrationResp
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://localhost//batchlearner.xsd}
 * @package MIAP
 * @subpackage Structs
 * @date 2014-08-05
 * @author Mikaël DELSOL
 * @version 1
 */
class MIAPStructBatchRegistrationResp extends MIAPWsdlClass
{
    /**
     * The ResponseCode
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 1
     * - nillable : false
     * @var string
     */
    public $ResponseCode;
    /**
     * The JobID
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 1
     * - nillable : false
     * @var long
     */
    public $JobID;
    /**
     * Constructor method for BatchRegistrationResp
     * @see parent::__construct()
     * @param string $_responseCode
     * @param long $_jobID
     * @return MIAPStructBatchRegistrationResp
     */
    public function __construct($_responseCode,$_jobID)
    {
        parent::__construct(array('ResponseCode'=>$_responseCode,'JobID'=>$_jobID),false);
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
     * Get JobID value
     * @return long
     */
    public function getJobID()
    {
        return $this->JobID;
    }
    /**
     * Set JobID value
     * @param long $_jobID the JobID
     * @return long
     */
    public function setJobID($_jobID)
    {
        return ($this->JobID = $_jobID);
    }
    /**
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see MIAPWsdlClass::__set_state()
     * @uses MIAPWsdlClass::__set_state()
     * @param array $_array the exported values
     * @return MIAPStructBatchRegistrationResp
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
