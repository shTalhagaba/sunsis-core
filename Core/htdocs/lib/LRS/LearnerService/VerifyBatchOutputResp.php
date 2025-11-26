<?php

class VerifyBatchOutputResp extends LearnerServiceWrappedResponse
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
     * @var string $JobStartedDateTime
     */
    protected $JobStartedDateTime = null;

    /**
     * @var string $JobFinishedDateTime
     */
    protected $JobFinishedDateTime = null;

    /**
     * @var MIAPVerifiedBatchLearner[] $VerifiedLearner
     */
    protected $VerifiedLearner = null;

    
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
     * @return VerifyBatchOutputResp
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
     * @return VerifyBatchOutputResp
     */
    public function setJobStatus($JobStatus)
    {
      $this->JobStatus = $JobStatus;
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
     * @return VerifyBatchOutputResp
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
     * @return VerifyBatchOutputResp
     */
    public function setJobFinishedDateTime($JobFinishedDateTime)
    {
      $this->JobFinishedDateTime = $JobFinishedDateTime;
      return $this;
    }

    /**
     * @return MIAPVerifiedBatchLearner[]
     */
    public function getVerifiedLearner()
    {
      return $this->VerifiedLearner;
    }

    /**
     * @param MIAPVerifiedBatchLearner[] $VerifiedLearner
     * @return VerifyBatchOutputResp
     */
    public function setVerifiedLearner(array $VerifiedLearner = null)
    {
      $this->VerifiedLearner = $VerifiedLearner;
      return $this;
    }

}
