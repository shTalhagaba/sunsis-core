<?php

class UpdateLearnerRqst extends BaseLearnerServiceRqst
{

    /**
     * @var LearnerToUpdate $Learner
     */
    protected $Learner = null;

    
    public function __construct()
    {
      parent::__construct();
    }

    /**
     * @return LearnerToUpdate
     */
    public function getLearner()
    {
      return $this->Learner;
    }

    /**
     * @param LearnerToUpdate $Learner
     * @return UpdateLearnerRqst
     */
    public function setLearner($Learner)
    {
      $this->Learner = $Learner;
      return $this;
    }

}
