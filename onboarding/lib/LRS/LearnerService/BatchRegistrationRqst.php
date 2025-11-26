<?php

class BatchRegistrationRqst extends BaseLearnerServiceRqst
{

    /**
     * @var string $JobType
     */
    protected $JobType = null;

    /**
     * @var int $LearnerRecordCount
     */
    protected $LearnerRecordCount = null;

    /**
     * @var BatchLearner[] $Learner
     */
    protected $Learner = null;

    /**
     * @param int $LearnerRecordCount
     */
    public function __construct($LearnerRecordCount)
    {
      parent::__construct();
      $this->LearnerRecordCount = $LearnerRecordCount;
    }

    /**
     * @return string
     */
    public function getJobType()
    {
      return $this->JobType;
    }

    /**
     * @param string $JobType
     * @return BatchRegistrationRqst
     */
    public function setJobType($JobType)
    {
      $this->JobType = $JobType;
      return $this;
    }

    /**
     * @return int
     */
    public function getLearnerRecordCount()
    {
      return $this->LearnerRecordCount;
    }

    /**
     * @param int $LearnerRecordCount
     * @return BatchRegistrationRqst
     */
    public function setLearnerRecordCount($LearnerRecordCount)
    {
      $this->LearnerRecordCount = $LearnerRecordCount;
      return $this;
    }

    /**
     * @return BatchLearner[]
     */
    public function getLearner()
    {
      return $this->Learner;
    }

    /**
     * @param BatchLearner[] $Learner
     * @return BatchRegistrationRqst
     */
    public function setLearner(array $Learner = null)
    {
      $this->Learner = $Learner;
      return $this;
    }

}
