<?php

class VerifyBatchOutputRqst extends BaseLearnerServiceRqst
{

    /**
     * @var int $JobID
     */
    protected $JobID = null;

    /**
     * @param int $JobID
     */
    public function __construct($JobID)
    {
      parent::__construct();
      $this->JobID = $JobID;
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
     * @return VerifyBatchOutputRqst
     */
    public function setJobID($JobID)
    {
      $this->JobID = $JobID;
      return $this;
    }

}
