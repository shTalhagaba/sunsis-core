<?php

class Achievement
{

    /**
     * @var \DateTime $AchievementAwardDate
     */
    protected $AchievementAwardDate = null;

    /**
     * @var int $Action
     */
    protected $Action = null;

    /**
     * @var \DateTime $ActionDate
     */
    protected $ActionDate = null;

    /**
     * @var string $ActionReason
     */
    protected $ActionReason = null;

    /**
     * @var \DateTime $DateofBirth
     */
    protected $DateofBirth = null;

    /**
     * @var string $FamilyName
     */
    protected $FamilyName = null;

    /**
     * @var int $Gender
     */
    protected $Gender = null;

    /**
     * @var string $GivenName
     */
    protected $GivenName = null;

    /**
     * @var string $Grade
     */
    protected $Grade = null;

    /**
     * @var string $LanguageForAssessment
     */
    protected $LanguageForAssessment = null;

    /**
     * @var string $LearnerPostcode
     */
    protected $LearnerPostcode = null;

    /**
     * @var string $MisIdentifier
     */
    protected $MisIdentifier = null;

    /**
     * @var string $ProviderUkprn
     */
    protected $ProviderUkprn = null;

    /**
     * @var string $Uln
     */
    protected $Uln = null;

    /**
     * @var string $UnitOrQualificationReferenceNumber
     */
    protected $UnitOrQualificationReferenceNumber = null;

    /**
     * @param \DateTime $AchievementAwardDate
     * @param int $Action
     * @param \DateTime $ActionDate
     */
    public function __construct(\DateTime $AchievementAwardDate, $Action, \DateTime $ActionDate)
    {
      $this->AchievementAwardDate = $AchievementAwardDate->format(\DateTime::ATOM);
      $this->Action = $Action;
      $this->ActionDate = $ActionDate->format(\DateTime::ATOM);
    }

    /**
     * @return \DateTime
     */
    public function getAchievementAwardDate()
    {
      if ($this->AchievementAwardDate == null) {
        return null;
      } else {
        try {
          return new \DateTime($this->AchievementAwardDate);
        } catch (\Exception $e) {
          return false;
        }
      }
    }

    /**
     * @param \DateTime $AchievementAwardDate
     * @return Achievement
     */
    public function setAchievementAwardDate(\DateTime $AchievementAwardDate)
    {
      $this->AchievementAwardDate = $AchievementAwardDate->format(\DateTime::ATOM);
      return $this;
    }

    /**
     * @return int
     */
    public function getAction()
    {
      return $this->Action;
    }

    /**
     * @param int $Action
     * @return Achievement
     */
    public function setAction($Action)
    {
      $this->Action = $Action;
      return $this;
    }

    /**
     * @return \DateTime
     */
    public function getActionDate()
    {
      if ($this->ActionDate == null) {
        return null;
      } else {
        try {
          return new \DateTime($this->ActionDate);
        } catch (\Exception $e) {
          return false;
        }
      }
    }

    /**
     * @param \DateTime $ActionDate
     * @return Achievement
     */
    public function setActionDate(\DateTime $ActionDate)
    {
      $this->ActionDate = $ActionDate->format(\DateTime::ATOM);
      return $this;
    }

    /**
     * @return string
     */
    public function getActionReason()
    {
      return $this->ActionReason;
    }

    /**
     * @param string $ActionReason
     * @return Achievement
     */
    public function setActionReason($ActionReason)
    {
      $this->ActionReason = $ActionReason;
      return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDateofBirth()
    {
      if ($this->DateofBirth == null) {
        return null;
      } else {
        try {
          return new \DateTime($this->DateofBirth);
        } catch (\Exception $e) {
          return false;
        }
      }
    }

    /**
     * @param \DateTime $DateofBirth
     * @return Achievement
     */
    public function setDateofBirth(\DateTime $DateofBirth = null)
    {
      if ($DateofBirth == null) {
       $this->DateofBirth = null;
      } else {
        $this->DateofBirth = $DateofBirth->format(\DateTime::ATOM);
      }
      return $this;
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
     * @return Achievement
     */
    public function setFamilyName($FamilyName)
    {
      $this->FamilyName = $FamilyName;
      return $this;
    }

    /**
     * @return int
     */
    public function getGender()
    {
      return $this->Gender;
    }

    /**
     * @param int $Gender
     * @return Achievement
     */
    public function setGender($Gender)
    {
      $this->Gender = $Gender;
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
     * @return Achievement
     */
    public function setGivenName($GivenName)
    {
      $this->GivenName = $GivenName;
      return $this;
    }

    /**
     * @return string
     */
    public function getGrade()
    {
      return $this->Grade;
    }

    /**
     * @param string $Grade
     * @return Achievement
     */
    public function setGrade($Grade)
    {
      $this->Grade = $Grade;
      return $this;
    }

    /**
     * @return string
     */
    public function getLanguageForAssessment()
    {
      return $this->LanguageForAssessment;
    }

    /**
     * @param string $LanguageForAssessment
     * @return Achievement
     */
    public function setLanguageForAssessment($LanguageForAssessment)
    {
      $this->LanguageForAssessment = $LanguageForAssessment;
      return $this;
    }

    /**
     * @return string
     */
    public function getLearnerPostcode()
    {
      return $this->LearnerPostcode;
    }

    /**
     * @param string $LearnerPostcode
     * @return Achievement
     */
    public function setLearnerPostcode($LearnerPostcode)
    {
      $this->LearnerPostcode = $LearnerPostcode;
      return $this;
    }

    /**
     * @return string
     */
    public function getMisIdentifier()
    {
      return $this->MisIdentifier;
    }

    /**
     * @param string $MisIdentifier
     * @return Achievement
     */
    public function setMisIdentifier($MisIdentifier)
    {
      $this->MisIdentifier = $MisIdentifier;
      return $this;
    }

    /**
     * @return string
     */
    public function getProviderUkprn()
    {
      return $this->ProviderUkprn;
    }

    /**
     * @param string $ProviderUkprn
     * @return Achievement
     */
    public function setProviderUkprn($ProviderUkprn)
    {
      $this->ProviderUkprn = $ProviderUkprn;
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
     * @return Achievement
     */
    public function setUln($Uln)
    {
      $this->Uln = $Uln;
      return $this;
    }

    /**
     * @return string
     */
    public function getUnitOrQualificationReferenceNumber()
    {
      return $this->UnitOrQualificationReferenceNumber;
    }

    /**
     * @param string $UnitOrQualificationReferenceNumber
     * @return Achievement
     */
    public function setUnitOrQualificationReferenceNumber($UnitOrQualificationReferenceNumber)
    {
      $this->UnitOrQualificationReferenceNumber = $UnitOrQualificationReferenceNumber;
      return $this;
    }

}
