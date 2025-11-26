<?php

class VerifyLearnerResp extends LearnerServiceWrappedResponse
{

    /**
     * @var MIAPVerifiedLearner $VerifiedLearner
     */
    protected $VerifiedLearner = null;

    
    public function __construct()
    {
    
    }

    /**
     * @return MIAPVerifiedLearner
     */
    public function getVerifiedLearner()
    {
      return $this->VerifiedLearner;
    }

    /**
     * @param MIAPVerifiedLearner $VerifiedLearner
     * @return VerifyLearnerResp
     */
    public function setVerifiedLearner($VerifiedLearner)
    {
      $this->VerifiedLearner = $VerifiedLearner;
      return $this;
    }

}
