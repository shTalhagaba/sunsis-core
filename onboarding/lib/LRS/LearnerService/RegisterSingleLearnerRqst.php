<?php

class RegisterSingleLearnerRqst extends BaseLearnerServiceRqst
{

    /**
     * @var LearnerToRegister $Learner
     */
    protected $Learner = null;

    
    public function __construct()
    {
      parent::__construct();
    }

    /**
     * @return LearnerToRegister
     */
    public function getLearner()
    {
      return $this->Learner;
    }

    /**
     * @param LearnerToRegister $Learner
     * @return RegisterSingleLearnerRqst
     */
    public function setLearner($Learner)
    {
      $this->Learner = $Learner;
      return $this;
    }

}
