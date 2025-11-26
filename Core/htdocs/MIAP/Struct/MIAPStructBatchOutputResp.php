<?php
/**
 * File for class MIAPStructBatchOutputResp
 * @package MIAP
 * @subpackage Structs
 * @date 2014-08-05
 * @author Mikaël DELSOL
 * @version 1
 */
/**
 * This class stands for MIAPStructBatchOutputResp originally named BatchOutputResp
 * Meta informations extracted from the WSDL
 * - from schema : {@link http://localhost//batchlearner.xsd}
 * @package MIAP
 * @subpackage Structs
 * @date 2014-08-05
 * @author Mikaël DELSOL
 * @version 1
 */
class MIAPStructBatchOutputResp extends MIAPWsdlClass
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
     * The JobStartedDateTime
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 1
     * - pattern : (((18|19|20)[0-9]{2})-(((01|03|05|07|08|10|12)-(0[1-9]|[1-2][0-9]|3[0-1]))|((04|06|09|11)-(0[1-9]|[1-2][0-9]|30))|(02-(0[1-9]|1[0-9]|2[0-8]))))|(((18|19|20)([02468][048]|[13579][26]))-02-29) ([0-1][0-9]|[2][0-3]):[0-5][0-9]:[0-5][0-9]
     * @var string
     */
    public $JobStartedDateTime;
    /**
     * The JobFinishedDateTime
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 1
     * - pattern : (((18|19|20)[0-9]{2})-(((01|03|05|07|08|10|12)-(0[1-9]|[1-2][0-9]|3[0-1]))|((04|06|09|11)-(0[1-9]|[1-2][0-9]|30))|(02-(0[1-9]|1[0-9]|2[0-8]))))|(((18|19|20)([02468][048]|[13579][26]))-02-29) ([0-1][0-9]|[2][0-3]):[0-5][0-9]:[0-5][0-9]
     * @var string
     */
    public $JobFinishedDateTime;
    /**
     * The JobStatus
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var string
     */
    public $JobStatus;
    /**
     * The NewLearnersCount
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var int
     */
    public $NewLearnersCount;
    /**
     * The ExistsUpdatedLearnersCount
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var int
     */
    public $ExistsUpdatedLearnersCount;
    /**
     * The PossibleMatchLearnersCount
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var int
     */
    public $PossibleMatchLearnersCount;
    /**
     * The LearnersNotFoundCount
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var int
     */
    public $LearnersNotFoundCount;
    /**
     * The RejectedLearnersCount
     * Meta informations extracted from the WSDL
     * - maxOccurs : 1
     * - minOccurs : 0
     * @var int
     */
    public $RejectedLearnersCount;
    /**
     * The Learner
     * Meta informations extracted from the WSDL
     * - maxOccurs : 200
     * - minOccurs : 0
     * @var MIAPStructOutputBatchLearner
     */
    public $Learner;
    /**
     * Constructor method for BatchOutputResp
     * @see parent::__construct()
     * @param string $_responseCode
     * @param string $_jobStartedDateTime
     * @param string $_jobFinishedDateTime
     * @param string $_jobStatus
     * @param int $_newLearnersCount
     * @param int $_existsUpdatedLearnersCount
     * @param int $_possibleMatchLearnersCount
     * @param int $_learnersNotFoundCount
     * @param int $_rejectedLearnersCount
     * @param MIAPStructOutputBatchLearner $_learner
     * @return MIAPStructBatchOutputResp
     */
    public function __construct($_responseCode,$_jobStartedDateTime,$_jobFinishedDateTime,$_jobStatus = NULL,$_newLearnersCount = NULL,$_existsUpdatedLearnersCount = NULL,$_possibleMatchLearnersCount = NULL,$_learnersNotFoundCount = NULL,$_rejectedLearnersCount = NULL,$_learner = NULL)
    {
        parent::__construct(array('ResponseCode'=>$_responseCode,'JobStartedDateTime'=>$_jobStartedDateTime,'JobFinishedDateTime'=>$_jobFinishedDateTime,'JobStatus'=>$_jobStatus,'NewLearnersCount'=>$_newLearnersCount,'ExistsUpdatedLearnersCount'=>$_existsUpdatedLearnersCount,'PossibleMatchLearnersCount'=>$_possibleMatchLearnersCount,'LearnersNotFoundCount'=>$_learnersNotFoundCount,'RejectedLearnersCount'=>$_rejectedLearnersCount,'Learner'=>$_learner),false);
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
     * Get JobStartedDateTime value
     * @return string
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
     * @return string
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
     * Get NewLearnersCount value
     * @return int|null
     */
    public function getNewLearnersCount()
    {
        return $this->NewLearnersCount;
    }
    /**
     * Set NewLearnersCount value
     * @param int $_newLearnersCount the NewLearnersCount
     * @return int
     */
    public function setNewLearnersCount($_newLearnersCount)
    {
        return ($this->NewLearnersCount = $_newLearnersCount);
    }
    /**
     * Get ExistsUpdatedLearnersCount value
     * @return int|null
     */
    public function getExistsUpdatedLearnersCount()
    {
        return $this->ExistsUpdatedLearnersCount;
    }
    /**
     * Set ExistsUpdatedLearnersCount value
     * @param int $_existsUpdatedLearnersCount the ExistsUpdatedLearnersCount
     * @return int
     */
    public function setExistsUpdatedLearnersCount($_existsUpdatedLearnersCount)
    {
        return ($this->ExistsUpdatedLearnersCount = $_existsUpdatedLearnersCount);
    }
    /**
     * Get PossibleMatchLearnersCount value
     * @return int|null
     */
    public function getPossibleMatchLearnersCount()
    {
        return $this->PossibleMatchLearnersCount;
    }
    /**
     * Set PossibleMatchLearnersCount value
     * @param int $_possibleMatchLearnersCount the PossibleMatchLearnersCount
     * @return int
     */
    public function setPossibleMatchLearnersCount($_possibleMatchLearnersCount)
    {
        return ($this->PossibleMatchLearnersCount = $_possibleMatchLearnersCount);
    }
    /**
     * Get LearnersNotFoundCount value
     * @return int|null
     */
    public function getLearnersNotFoundCount()
    {
        return $this->LearnersNotFoundCount;
    }
    /**
     * Set LearnersNotFoundCount value
     * @param int $_learnersNotFoundCount the LearnersNotFoundCount
     * @return int
     */
    public function setLearnersNotFoundCount($_learnersNotFoundCount)
    {
        return ($this->LearnersNotFoundCount = $_learnersNotFoundCount);
    }
    /**
     * Get RejectedLearnersCount value
     * @return int|null
     */
    public function getRejectedLearnersCount()
    {
        return $this->RejectedLearnersCount;
    }
    /**
     * Set RejectedLearnersCount value
     * @param int $_rejectedLearnersCount the RejectedLearnersCount
     * @return int
     */
    public function setRejectedLearnersCount($_rejectedLearnersCount)
    {
        return ($this->RejectedLearnersCount = $_rejectedLearnersCount);
    }
    /**
     * Get Learner value
     * @return MIAPStructOutputBatchLearner|null
     */
    public function getLearner()
    {
        return $this->Learner;
    }
    /**
     * Set Learner value
     * @param MIAPStructOutputBatchLearner $_learner the Learner
     * @return MIAPStructOutputBatchLearner
     */
    public function setLearner($_learner)
    {
        return ($this->Learner = $_learner);
    }
    /**
     * Method called when an object has been exported with var_export() functions
     * It allows to return an object instantiated with the values
     * @see MIAPWsdlClass::__set_state()
     * @uses MIAPWsdlClass::__set_state()
     * @param array $_array the exported values
     * @return MIAPStructBatchOutputResp
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
