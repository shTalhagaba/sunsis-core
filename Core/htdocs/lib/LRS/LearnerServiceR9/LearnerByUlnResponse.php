<?php

class LearnerByUlnResponse extends ServiceResponseR9
{

    /**
     * @var string $FamilyName
     */
    protected $FamilyName = null;

    /**
     * @var string $GivenName
     */
    protected $GivenName = null;

    /**
     * @var Learner $Learner
     */
    protected $Learner = null;

    /**
     * @var string $Uln
     */
    protected $Uln = null;

    
    public function __construct()
    {
      parent::__construct();
    }

    /**
     * @return string
     */
    public function getFamilyName()
    {
      return $this->FamilyName;
    }

    /**
     * @param string $FamilyName
     * @return LearnerByUlnResponse
     */
    public function setFamilyName($FamilyName)
    {
      $this->FamilyName = $FamilyName;
      return $this;
    }

    /**
     * @return string
     */
    public function getGivenName()
    {
      return $this->GivenName;
    }

    /**
     * @param string $GivenName
     * @return LearnerByUlnResponse
     */
    public function setGivenName($GivenName)
    {
      $this->GivenName = $GivenName;
      return $this;
    }

    /**
     * @return Learner
     */
    public function getLearner()
    {
      return $this->Learner;
    }

    /**
     * @param Learner $Learner
     * @return LearnerByUlnResponse
     */
    public function setLearner($Learner)
    {
      $this->Learner = $Learner;
      return $this;
    }

    /**
     * @return string
     */
    public function getUln()
    {
      return $this->Uln;
    }

    /**
     * @param string $Uln
     * @return LearnerByUlnResponse
     */
    public function setUln($Uln)
    {
      $this->Uln = $Uln;
      return $this;
    }

}
