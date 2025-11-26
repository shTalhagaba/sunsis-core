<?php

class GetAchievementBatchJob
{

    /**
     * @var User $invokingUser
     */
    protected $invokingUser = null;

    /**
     * @var int $jobId
     */
    protected $jobId = null;

    /**
     * @param User $invokingUser
     * @param int $jobId
     */
    public function __construct($invokingUser, $jobId)
    {
      $this->invokingUser = $invokingUser;
      $this->jobId = $jobId;
    }

    /**
     * @return User
     */
    public function getInvokingUser()
    {
      return $this->invokingUser;
    }

    /**
     * @param User $invokingUser
     * @return GetAchievementBatchJob
     */
    public function setInvokingUser($invokingUser)
    {
      $this->invokingUser = $invokingUser;
      return $this;
    }

    /**
     * @return int
     */
    public function getJobId()
    {
      return $this->jobId;
    }

    /**
     * @param int $jobId
     * @return GetAchievementBatchJob
     */
    public function setJobId($jobId)
    {
      $this->jobId = $jobId;
      return $this;
    }

}
