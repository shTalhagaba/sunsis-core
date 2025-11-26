<?php

class VerifyBatchRqst extends BaseLearnerServiceRqst
{

    /**
     * @var int $LearnerRecordCount
     */
    protected $LearnerRecordCount = null;

    /**
     * @var string $OrgEmail
     */
    protected $OrgEmail = null;

    /**
     * @var MIAPBatchLearnerToVerify[] $Learner
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
     * @return int
     */
    public function getLearnerRecordCount()
    {
      return $this->LearnerRecordCount;
    }

    /**
     * @param int $LearnerRecordCount
     * @return VerifyBatchRqst
     */
    public function setLearnerRecordCount($LearnerRecordCount)
    {
      $this->LearnerRecordCount = $LearnerRecordCount;
      return $this;
    }

    /**
     * @return string
     */
    public function getOrgEmail()
    {
      return $this->OrgEmail;
    }

    /**
     * @param string $OrgEmail
     * @return VerifyBatchRqst
     */
    public function setOrgEmail($OrgEmail)
    {
      $this->OrgEmail = $OrgEmail;
      return $this;
    }

    /**
     * @return MIAPBatchLearnerToVerify[]
     */
    public function getLearner()
    {
      return $this->Learner;
    }

    /**
     * @param MIAPBatchLearnerToVerify[] $Learner
     * @return VerifyBatchRqst
     */
    public function setLearner(array $Learner = null)
    {
      $this->Learner = $Learner;
      return $this;
    }

}
