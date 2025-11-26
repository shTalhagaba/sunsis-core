<?php

class BatchOutputResp extends LearnerServiceWrappedResponse
{

    /**
     * @var string $ResponseCode
     */
    protected $ResponseCode = null;

    /**
     * @var string $JobStatus
     */
    protected $JobStatus = null;

    /**
     * @var int $NewLearnersCount
     */
    protected $NewLearnersCount = null;

    /**
     * @var int $ExistsUpdatedLearnersCount
     */
    protected $ExistsUpdatedLearnersCount = null;

    /**
     * @var int $PossibleMatchLearnersCount
     */
    protected $PossibleMatchLearnersCount = null;

    /**
     * @var int $LearnersNotFoundCount
     */
    protected $LearnersNotFoundCount = null;

    /**
     * @var int $RejectedLearnersCount
     */
    protected $RejectedLearnersCount = null;

    /**
     * @var string $JobStartedDateTime
     */
    protected $JobStartedDateTime = null;

    /**
     * @var string $JobFinishedDateTime
     */
    protected $JobFinishedDateTime = null;

    /**
     * @var OutputBatchLearner[] $Learner
     */
    protected $Learner = null;

    
    public function __construct()
    {
    
    }

    /**
     * @return string
     */
    public function getResponseCode()
    {
      return $this->ResponseCode;
    }

    /**
     * @param string $ResponseCode
     * @return BatchOutputResp
     */
    public function setResponseCode($ResponseCode)
    {
      $this->ResponseCode = $ResponseCode;
      return $this;
    }

    /**
     * @return string
     */
    public function getJobStatus()
    {
      return $this->JobStatus;
    }

    /**
     * @param string $JobStatus
     * @return BatchOutputResp
     */
    public function setJobStatus($JobStatus)
    {
      $this->JobStatus = $JobStatus;
      return $this;
    }

    /**
     * @return int
     */
    public function getNewLearnersCount()
    {
      return $this->NewLearnersCount;
    }

    /**
     * @param int $NewLearnersCount
     * @return BatchOutputResp
     */
    public function setNewLearnersCount($NewLearnersCount)
    {
      $this->NewLearnersCount = $NewLearnersCount;
      return $this;
    }

    /**
     * @return int
     */
    public function getExistsUpdatedLearnersCount()
    {
      return $this->ExistsUpdatedLearnersCount;
    }

    /**
     * @param int $ExistsUpdatedLearnersCount
     * @return BatchOutputResp
     */
    public function setExistsUpdatedLearnersCount($ExistsUpdatedLearnersCount)
    {
      $this->ExistsUpdatedLearnersCount = $ExistsUpdatedLearnersCount;
      return $this;
    }

    /**
     * @return int
     */
    public function getPossibleMatchLearnersCount()
    {
      return $this->PossibleMatchLearnersCount;
    }

    /**
     * @param int $PossibleMatchLearnersCount
     * @return BatchOutputResp
     */
    public function setPossibleMatchLearnersCount($PossibleMatchLearnersCount)
    {
      $this->PossibleMatchLearnersCount = $PossibleMatchLearnersCount;
      return $this;
    }

    /**
     * @return int
     */
    public function getLearnersNotFoundCount()
    {
      return $this->LearnersNotFoundCount;
    }

    /**
     * @param int $LearnersNotFoundCount
     * @return BatchOutputResp
     */
    public function setLearnersNotFoundCount($LearnersNotFoundCount)
    {
      $this->LearnersNotFoundCount = $LearnersNotFoundCount;
      return $this;
    }

    /**
     * @return int
     */
    public function getRejectedLearnersCount()
    {
      return $this->RejectedLearnersCount;
    }

    /**
     * @param int $RejectedLearnersCount
     * @return BatchOutputResp
     */
    public function setRejectedLearnersCount($RejectedLearnersCount)
    {
      $this->RejectedLearnersCount = $RejectedLearnersCount;
      return $this;
    }

    /**
     * @return string
     */
    public function getJobStartedDateTime()
    {
      return $this->JobStartedDateTime;
    }

    /**
     * @param string $JobStartedDateTime
     * @return BatchOutputResp
     */
    public function setJobStartedDateTime($JobStartedDateTime)
    {
      $this->JobStartedDateTime = $JobStartedDateTime;
      return $this;
    }

    /**
     * @return string
     */
    public function getJobFinishedDateTime()
    {
      return $this->JobFinishedDateTime;
    }

    /**
     * @param string $JobFinishedDateTime
     * @return BatchOutputResp
     */
    public function setJobFinishedDateTime($JobFinishedDateTime)
    {
      $this->JobFinishedDateTime = $JobFinishedDateTime;
      return $this;
    }

    /**
     * @return OutputBatchLearner[]
     */
    public function getLearner()
    {
      return $this->Learner;
    }

    /**
     * @param OutputBatchLearner[] $Learner
     * @return BatchOutputResp
     */
    public function setLearner(array $Learner = null)
    {
      $this->Learner = $Learner;
      return $this;
    }

}
