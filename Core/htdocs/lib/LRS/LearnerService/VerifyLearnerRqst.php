<?php

class VerifyLearnerRqst extends BaseLearnerServiceRqst
{

    /**
     * @var MIAPLearnerToVerify $LearnerToVerify
     */
    protected $LearnerToVerify = null;

    
    public function __construct()
    {
      parent::__construct();
    }

    /**
     * @return MIAPLearnerToVerify
     */
    public function getLearnerToVerify()
    {
      return $this->LearnerToVerify;
    }

    /**
     * @param MIAPLearnerToVerify $LearnerToVerify
     * @return VerifyLearnerRqst
     */
    public function setLearnerToVerify($LearnerToVerify)
    {
      $this->LearnerToVerify = $LearnerToVerify;
      return $this;
    }

}
