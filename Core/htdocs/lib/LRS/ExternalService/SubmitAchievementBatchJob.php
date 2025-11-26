<?php

class SubmitAchievementBatchJob
{

    /**
     * @var User $invokingUser
     */
    protected $invokingUser = null;

    /**
     * @var string $emailAddress
     */
    protected $emailAddress = null;

    /**
     * @var string $submissionType
     */
    protected $submissionType = null;

    /**
     * @var ArrayOfAchievement $achievements
     */
    protected $achievements = null;

    /**
     * @param User $invokingUser
     * @param string $emailAddress
     * @param string $submissionType
     * @param ArrayOfAchievement $achievements
     */
    public function __construct($invokingUser, $emailAddress, $submissionType, $achievements)
    {
      $this->invokingUser = $invokingUser;
      $this->emailAddress = $emailAddress;
      $this->submissionType = $submissionType;
      $this->achievements = $achievements;
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
     * @return SubmitAchievementBatchJob
     */
    public function setInvokingUser($invokingUser)
    {
      $this->invokingUser = $invokingUser;
      return $this;
    }

    /**
     * @return string
     */
    public function getEmailAddress()
    {
      return $this->emailAddress;
    }

    /**
     * @param string $emailAddress
     * @return SubmitAchievementBatchJob
     */
    public function setEmailAddress($emailAddress)
    {
      $this->emailAddress = $emailAddress;
      return $this;
    }

    /**
     * @return string
     */
    public function getSubmissionType()
    {
      return $this->submissionType;
    }

    /**
     * @param string $submissionType
     * @return SubmitAchievementBatchJob
     */
    public function setSubmissionType($submissionType)
    {
      $this->submissionType = $submissionType;
      return $this;
    }

    /**
     * @return ArrayOfAchievement
     */
    public function getAchievements()
    {
      return $this->achievements;
    }

    /**
     * @param ArrayOfAchievement $achievements
     * @return SubmitAchievementBatchJob
     */
    public function setAchievements($achievements)
    {
      $this->achievements = $achievements;
      return $this;
    }

}
