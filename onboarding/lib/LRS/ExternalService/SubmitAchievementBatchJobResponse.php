<?php

class SubmitAchievementBatchJobResponse extends ServiceResponseR9
{

    /**
     * @var int $JobId
     */
    protected $JobId = null;

    
    public function __construct()
    {
      parent::__construct();
    }

    /**
     * @return int
     */
    public function getJobId()
    {
      return $this->JobId;
    }

    /**
     * @param int $JobId
     * @return SubmitAchievementBatchJobResponse
     */
    public function setJobId($JobId)
    {
      $this->JobId = $JobId;
      return $this;
    }

}
