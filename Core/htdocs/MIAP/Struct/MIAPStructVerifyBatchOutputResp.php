<?php
/**
 * File for class MIAPStructVerifyBatchOutputResp
 * @package MIAP
 * @subpackage Structs
 * @date 2014-08-05
 * @author Mikaël DELSOL
 * @version 1
 */
/**
 * This class stands for MIAPStructVerifyBatchOutputResp originally named VerifyBatchOutputResp
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://localhost//verifybatchlearner.xsd}
 * @package MIAP
 * @subpackage Structs
 * @date 2014-08-05
 * @author Mikaël DELSOL
 * @version 1
 */
class MIAPStructVerifyBatchOutputResp extends MIAPWsdlClass
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
     * The JobStatus
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var string
     */
    public $JobStatus;
    /**
     * The JobStartedDateTime
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * - pattern : (((18|19|20)[0-9]{2})-(((01|03|05|07|08|10|12)-(0[1-9]|[1-2][0-9]|3[0-1]))|((04|06|09|11)-(0[1-9]|[1-2][0-9]|30))|(02-(0[1-9]|1[0-9]|2[0-8]))))|(((18|19|20)([02468][048]|[13579][26]))-02-29) ([0-1][0-9]|[2][0-3]):[0-5][0-9]:[0-5][0-9]
     * @var string
     */
    public $JobStartedDateTime;
    /**
     * The JobFinishedDateTime
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * - pattern : (((18|19|20)[0-9]{2})-(((01|03|05|07|08|10|12)-(0[1-9]|[1-2][0-9]|3[0-1]))|((04|06|09|11)-(0[1-9]|[1-2][0-9]|30))|(02-(0[1-9]|1[0-9]|2[0-8]))))|(((18|19|20)([02468][048]|[13579][26]))-02-29) ([0-1][0-9]|[2][0-3]):[0-5][0-9]:[0-5][0-9]
     * @var string
     */
    public $JobFinishedDateTime;
    /**
     * The VerifiedLearner
     * Meta informations extracted from the WSDL
     * - maxOccurs : unbounded
     * - minOccurs : 0
     * @var MIAPStructMIAPVerifiedBatchLearner
     */
    public $VerifiedLearner;
    /**
     * Constructor method for VerifyBatchOutputResp
     * @see parent::__construct()
     * @param string $_responseCode
     * @param string $_jobStatus
     * @param string $_jobStartedDateTime
     * @param string $_jobFinishedDateTime
     * @param MIAPStructMIAPVerifiedBatchLearner $_verifiedLearner
     * @return MIAPStructVerifyBatchOutputResp
     */
    public function __construct($_responseCode,$_jobStatus = NULL,$_jobStartedDateTime = NULL,$_jobFinishedDateTime = NULL,$_verifiedLearner = NULL)
    {
        parent::__construct(array('ResponseCode'=>$_responseCode,'JobStatus'=>$_jobStatus,'JobStartedDateTime'=>$_jobStartedDateTime,'JobFinishedDateTime'=>$_jobFinishedDateTime,'VerifiedLearner'=>$_verifiedLearner),false);
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
     * Get JobStatus value
     * @return string|null
     */
    public function getJobStatus()
    {
        return $this->JobStatus;
    }
    /**
     * Set JobStatus value
     * @param string $_jobStatus the JobStatus
     * @return string
     */
    public function setJobStatus($_jobStatus)
    {
        return ($this->JobStatus = $_jobStatus);
    }
    /**
     * Get JobStartedDateTime value
     * @return string|null
     */
    public function getJobStartedDateTime()
    {
        return $this->JobStartedDateTime;
    }
    /**
     * Set JobStartedDateTime value
     * @param string $_jobStartedDateTime the JobStartedDateTime
     * @return string
     */
    public function setJobStartedDateTime($_jobStartedDateTime)
    {
        return ($this->JobStartedDateTime = $_jobStartedDateTime);
    }
    /**
     * Get JobFinishedDateTime value
     * @return string|null
     */
    public function getJobFinishedDateTime()
    {
        return $this->JobFinishedDateTime;
    }
    /**
     * Set JobFinishedDateTime value
     * @param string $_jobFinishedDateTime the JobFinishedDateTime
     * @return string
     */
    public function setJobFinishedDateTime($_jobFinishedDateTime)
    {
        return ($this->JobFinishedDateTime = $_jobFinishedDateTime);
    }
    /**
     * Get VerifiedLearner value
     * @return MIAPStructMIAPVerifiedBatchLearner|null
     */
    public function getVerifiedLearner()
    {
        return $this->VerifiedLearner;
    }
    /**
     * Set VerifiedLearner value
     * @param MIAPStructMIAPVerifiedBatchLearner $_verifiedLearner the VerifiedLearner
     * @return MIAPStructMIAPVerifiedBatchLearner
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
     * @return MIAPStructVerifyBatchOutputResp
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
