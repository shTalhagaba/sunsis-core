<?php

class AchievementBatchJobResponse extends ServiceResponseR9
{

    /**
     * @var string $AchievementBatchErrorFile
     */
    protected $AchievementBatchErrorFile = null;

    /**
     * @var int $AchievementsCreated
     */
    protected $AchievementsCreated = null;

    /**
     * @var int $AchievementsPromotedToFinal
     */
    protected $AchievementsPromotedToFinal = null;

    /**
     * @var int $AchievementsReinstated
     */
    protected $AchievementsReinstated = null;

    /**
     * @var int $AchievementsUpdated
     */
    protected $AchievementsUpdated = null;

    /**
     * @var int $AchievementsWithdrawn
     */
    protected $AchievementsWithdrawn = null;

    /**
     * @var int $BusinessRuleErrors
     */
    protected $BusinessRuleErrors = null;

    /**
     * @var string $Duration
     */
    protected $Duration = null;

    /**
     * @var \DateTime $EndDate
     */
    protected $EndDate = null;

    /**
     * @var int $FieldDataErrors
     */
    protected $FieldDataErrors = null;

    /**
     * @var string $Filename
     */
    protected $Filename = null;

    /**
     * @var int $JobID
     */
    protected $JobID = null;

    /**
     * @var int $LearnerDataErrors
     */
    protected $LearnerDataErrors = null;

    /**
     * @var int $NumberOfRecords
     */
    protected $NumberOfRecords = null;

    /**
     * @var float $PercentageSuccessfulRecords
     */
    protected $PercentageSuccessfulRecords = null;

    /**
     * @var string $RejectionReason
     */
    protected $RejectionReason = null;

    /**
     * @var \DateTime $StartDate
     */
    protected $StartDate = null;

    /**
     * @var AchievementBatchJobStatus $Status
     */
    protected $Status = null;

    /**
     * @var \DateTime $SubmittedDate
     */
    protected $SubmittedDate = null;

    /**
     * @var int $SuccessfulFinalAchievements
     */
    protected $SuccessfulFinalAchievements = null;

    /**
     * @var int $SuccessfulProvisionalAchievements
     */
    protected $SuccessfulProvisionalAchievements = null;

    /**
     * @var string $Type
     */
    protected $Type = null;

    /**
     * @var string $User
     */
    protected $User = null;

    
    public function __construct()
    {
      parent::__construct();
    }

    /**
     * @return string
     */
    public function getAchievementBatchErrorFile()
    {
      return $this->AchievementBatchErrorFile;
    }

    /**
     * @param string $AchievementBatchErrorFile
     * @return AchievementBatchJobResponse
     */
    public function setAchievementBatchErrorFile($AchievementBatchErrorFile)
    {
      $this->AchievementBatchErrorFile = $AchievementBatchErrorFile;
      return $this;
    }

    /**
     * @return int
     */
    public function getAchievementsCreated()
    {
      return $this->AchievementsCreated;
    }

    /**
     * @param int $AchievementsCreated
     * @return AchievementBatchJobResponse
     */
    public function setAchievementsCreated($AchievementsCreated)
    {
      $this->AchievementsCreated = $AchievementsCreated;
      return $this;
    }

    /**
     * @return int
     */
    public function getAchievementsPromotedToFinal()
    {
      return $this->AchievementsPromotedToFinal;
    }

    /**
     * @param int $AchievementsPromotedToFinal
     * @return AchievementBatchJobResponse
     */
    public function setAchievementsPromotedToFinal($AchievementsPromotedToFinal)
    {
      $this->AchievementsPromotedToFinal = $AchievementsPromotedToFinal;
      return $this;
    }

    /**
     * @return int
     */
    public function getAchievementsReinstated()
    {
      return $this->AchievementsReinstated;
    }

    /**
     * @param int $AchievementsReinstated
     * @return AchievementBatchJobResponse
     */
    public function setAchievementsReinstated($AchievementsReinstated)
    {
      $this->AchievementsReinstated = $AchievementsReinstated;
      return $this;
    }

    /**
     * @return int
     */
    public function getAchievementsUpdated()
    {
      return $this->AchievementsUpdated;
    }

    /**
     * @param int $AchievementsUpdated
     * @return AchievementBatchJobResponse
     */
    public function setAchievementsUpdated($AchievementsUpdated)
    {
      $this->AchievementsUpdated = $AchievementsUpdated;
      return $this;
    }

    /**
     * @return int
     */
    public function getAchievementsWithdrawn()
    {
      return $this->AchievementsWithdrawn;
    }

    /**
     * @param int $AchievementsWithdrawn
     * @return AchievementBatchJobResponse
     */
    public function setAchievementsWithdrawn($AchievementsWithdrawn)
    {
      $this->AchievementsWithdrawn = $AchievementsWithdrawn;
      return $this;
    }

    /**
     * @return int
     */
    public function getBusinessRuleErrors()
    {
      return $this->BusinessRuleErrors;
    }

    /**
     * @param int $BusinessRuleErrors
     * @return AchievementBatchJobResponse
     */
    public function setBusinessRuleErrors($BusinessRuleErrors)
    {
      $this->BusinessRuleErrors = $BusinessRuleErrors;
      return $this;
    }

    /**
     * @return string
     */
    public function getDuration()
    {
      return $this->Duration;
    }

    /**
     * @param string $Duration
     * @return AchievementBatchJobResponse
     */
    public function setDuration($Duration)
    {
      $this->Duration = $Duration;
      return $this;
    }

    /**
     * @return \DateTime
     */
    public function getEndDate()
    {
      if ($this->EndDate == null) {
        return null;
      } else {
        try {
          return new \DateTime($this->EndDate);
        } catch (\Exception $e) {
          return false;
        }
      }
    }

    /**
     * @param \DateTime $EndDate
     * @return AchievementBatchJobResponse
     */
    public function setEndDate(\DateTime $EndDate = null)
    {
      if ($EndDate == null) {
       $this->EndDate = null;
      } else {
        $this->EndDate = $EndDate->format(\DateTime::ATOM);
      }
      return $this;
    }

    /**
     * @return int
     */
    public function getFieldDataErrors()
    {
      return $this->FieldDataErrors;
    }

    /**
     * @param int $FieldDataErrors
     * @return AchievementBatchJobResponse
     */
    public function setFieldDataErrors($FieldDataErrors)
    {
      $this->FieldDataErrors = $FieldDataErrors;
      return $this;
    }

    /**
     * @return string
     */
    public function getFilename()
    {
      return $this->Filename;
    }

    /**
     * @param string $Filename
     * @return AchievementBatchJobResponse
     */
    public function setFilename($Filename)
    {
      $this->Filename = $Filename;
      return $this;
    }

    /**
     * @return int
     */
    public function getJobID()
    {
      return $this->JobID;
    }

    /**
     * @param int $JobID
     * @return AchievementBatchJobResponse
     */
    public function setJobID($JobID)
    {
      $this->JobID = $JobID;
      return $this;
    }

    /**
     * @return int
     */
    public function getLearnerDataErrors()
    {
      return $this->LearnerDataErrors;
    }

    /**
     * @param int $LearnerDataErrors
     * @return AchievementBatchJobResponse
     */
    public function setLearnerDataErrors($LearnerDataErrors)
    {
      $this->LearnerDataErrors = $LearnerDataErrors;
      return $this;
    }

    /**
     * @return int
     */
    public function getNumberOfRecords()
    {
      return $this->NumberOfRecords;
    }

    /**
     * @param int $NumberOfRecords
     * @return AchievementBatchJobResponse
     */
    public function setNumberOfRecords($NumberOfRecords)
    {
      $this->NumberOfRecords = $NumberOfRecords;
      return $this;
    }

    /**
     * @return float
     */
    public function getPercentageSuccessfulRecords()
    {
      return $this->PercentageSuccessfulRecords;
    }

    /**
     * @param float $PercentageSuccessfulRecords
     * @return AchievementBatchJobResponse
     */
    public function setPercentageSuccessfulRecords($PercentageSuccessfulRecords)
    {
      $this->PercentageSuccessfulRecords = $PercentageSuccessfulRecords;
      return $this;
    }

    /**
     * @return string
     */
    public function getRejectionReason()
    {
      return $this->RejectionReason;
    }

    /**
     * @param string $RejectionReason
     * @return AchievementBatchJobResponse
     */
    public function setRejectionReason($RejectionReason)
    {
      $this->RejectionReason = $RejectionReason;
      return $this;
    }

    /**
     * @return \DateTime
     */
    public function getStartDate()
    {
      if ($this->StartDate == null) {
        return null;
      } else {
        try {
          return new \DateTime($this->StartDate);
        } catch (\Exception $e) {
          return false;
        }
      }
    }

    /**
     * @param \DateTime $StartDate
     * @return AchievementBatchJobResponse
     */
    public function setStartDate(\DateTime $StartDate = null)
    {
      if ($StartDate == null) {
       $this->StartDate = null;
      } else {
        $this->StartDate = $StartDate->format(\DateTime::ATOM);
      }
      return $this;
    }

    /**
     * @return AchievementBatchJobStatus
     */
    public function getStatus()
    {
      return $this->Status;
    }

    /**
     * @param AchievementBatchJobStatus $Status
     * @return AchievementBatchJobResponse
     */
    public function setStatus($Status)
    {
      $this->Status = $Status;
      return $this;
    }

    /**
     * @return \DateTime
     */
    public function getSubmittedDate()
    {
      if ($this->SubmittedDate == null) {
        return null;
      } else {
        try {
          return new \DateTime($this->SubmittedDate);
        } catch (\Exception $e) {
          return false;
        }
      }
    }

    /**
     * @param \DateTime $SubmittedDate
     * @return AchievementBatchJobResponse
     */
    public function setSubmittedDate(\DateTime $SubmittedDate = null)
    {
      if ($SubmittedDate == null) {
       $this->SubmittedDate = null;
      } else {
        $this->SubmittedDate = $SubmittedDate->format(\DateTime::ATOM);
      }
      return $this;
    }

    /**
     * @return int
     */
    public function getSuccessfulFinalAchievements()
    {
      return $this->SuccessfulFinalAchievements;
    }

    /**
     * @param int $SuccessfulFinalAchievements
     * @return AchievementBatchJobResponse
     */
    public function setSuccessfulFinalAchievements($SuccessfulFinalAchievements)
    {
      $this->SuccessfulFinalAchievements = $SuccessfulFinalAchievements;
      return $this;
    }

    /**
     * @return int
     */
    public function getSuccessfulProvisionalAchievements()
    {
      return $this->SuccessfulProvisionalAchievements;
    }

    /**
     * @param int $SuccessfulProvisionalAchievements
     * @return AchievementBatchJobResponse
     */
    public function setSuccessfulProvisionalAchievements($SuccessfulProvisionalAchievements)
    {
      $this->SuccessfulProvisionalAchievements = $SuccessfulProvisionalAchievements;
      return $this;
    }

    /**
     * @return string
     */
    public function getType()
    {
      return $this->Type;
    }

    /**
     * @param string $Type
     * @return AchievementBatchJobResponse
     */
    public function setType($Type)
    {
      $this->Type = $Type;
      return $this;
    }

    /**
     * @return string
     */
    public function getUser()
    {
      return $this->User;
    }

    /**
     * @param string $User
     * @return AchievementBatchJobResponse
     */
    public function setUser($User)
    {
      $this->User = $User;
      return $this;
    }

}
