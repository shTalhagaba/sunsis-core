<?php

class VerifyBatchResp extends LearnerServiceWrappedResponse
{

    /**
     * @var string $ResponseCode
     */
    protected $ResponseCode = null;

    /**
     * @var int $JobID
     */
    protected $JobID = null;

    /**
     * @param int $JobID
     */
    public function __construct($JobID)
    {
      $this->JobID = $JobID;
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
     * @return VerifyBatchResp
     */
    public function setResponseCode($ResponseCode)
    {
      $this->ResponseCode = $ResponseCode;
      return $this;
    }

    /**
     * @return int
     */
    public function getJobID()
    {
      return $this->JobID;
    }

    /**
     * @param int $JobID
     * @return VerifyBatchResp
     */
    public function setJobID($JobID)
    {
      $this->JobID = $JobID;
      return $this;
    }

}
