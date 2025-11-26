<?php

class MIAPVerifiedBatchLearner
{

    /**
     * @var string $MISIdentifier
     */
    protected $MISIdentifier = null;

    /**
     * @var string $SearchedULN
     */
    protected $SearchedULN = null;

    /**
     * @var string $SearchedFamilyName
     */
    protected $SearchedFamilyName = null;

    /**
     * @var string $SearchedGivenName
     */
    protected $SearchedGivenName = null;

    /**
     * @var string $SearchedDateOfBirth
     */
    protected $SearchedDateOfBirth = null;

    /**
     * @var string $SearchedGender
     */
    protected $SearchedGender = null;

    /**
     * @var string $ResponseCode
     */
    protected $ResponseCode = null;

    /**
     * @var string $ULN
     */
    protected $ULN = null;

    /**
     * @var string $FamilyName
     */
    protected $FamilyName = null;

    /**
     * @var string $GivenName
     */
    protected $GivenName = null;

    /**
     * @var string $DateOfBirth
     */
    protected $DateOfBirth = null;

    /**
     * @var string $Gender
     */
    protected $Gender = null;

    /**
     * @var string[] $FailureFlag
     */
    protected $FailureFlag = null;

    
    public function __construct()
    {
    
    }

    /**
     * @return string
     */
    public function getMISIdentifier()
    {
      return $this->MISIdentifier;
    }

    /**
     * @param string $MISIdentifier
     * @return MIAPVerifiedBatchLearner
     */
    public function setMISIdentifier($MISIdentifier)
    {
      $this->MISIdentifier = $MISIdentifier;
      return $this;
    }

    /**
     * @return string
     */
    public function getSearchedULN()
    {
      return $this->SearchedULN;
    }

    /**
     * @param string $SearchedULN
     * @return MIAPVerifiedBatchLearner
     */
    public function setSearchedULN($SearchedULN)
    {
      $this->SearchedULN = $SearchedULN;
      return $this;
    }

    /**
     * @return string
     */
    public function getSearchedFamilyName()
    {
      return $this->SearchedFamilyName;
    }

    /**
     * @param string $SearchedFamilyName
     * @return MIAPVerifiedBatchLearner
     */
    public function setSearchedFamilyName($SearchedFamilyName)
    {
      $this->SearchedFamilyName = $SearchedFamilyName;
      return $this;
    }

    /**
     * @return string
     */
    public function getSearchedGivenName()
    {
      return $this->SearchedGivenName;
    }

    /**
     * @param string $SearchedGivenName
     * @return MIAPVerifiedBatchLearner
     */
    public function setSearchedGivenName($SearchedGivenName)
    {
      $this->SearchedGivenName = $SearchedGivenName;
      return $this;
    }

    /**
     * @return string
     */
    public function getSearchedDateOfBirth()
    {
      return $this->SearchedDateOfBirth;
    }

    /**
     * @param string $SearchedDateOfBirth
     * @return MIAPVerifiedBatchLearner
     */
    public function setSearchedDateOfBirth($SearchedDateOfBirth)
    {
      $this->SearchedDateOfBirth = $SearchedDateOfBirth;
      return $this;
    }

    /**
     * @return string
     */
    public function getSearchedGender()
    {
      return $this->SearchedGender;
    }

    /**
     * @param string $SearchedGender
     * @return MIAPVerifiedBatchLearner
     */
    public function setSearchedGender($SearchedGender)
    {
      $this->SearchedGender = $SearchedGender;
      return $this;
    }

    /**
     * @return string
     */
    public function getResponseCode()
    {
      return $this->ResponseCode;
    }

    /**
     * @param string $ResponseCode
     * @return MIAPVerifiedBatchLearner
     */
    public function setResponseCode($ResponseCode)
    {
      $this->ResponseCode = $ResponseCode;
      return $this;
    }

    /**
     * @return string
     */
    public function getULN()
    {
      return $this->ULN;
    }

    /**
     * @param string $ULN
     * @return MIAPVerifiedBatchLearner
     */
    public function setULN($ULN)
    {
      $this->ULN = $ULN;
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
     * @return MIAPVerifiedBatchLearner
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
     * @return MIAPVerifiedBatchLearner
     */
    public function setGivenName($GivenName)
    {
      $this->GivenName = $GivenName;
      return $this;
    }

    /**
     * @return string
     */
    public function getDateOfBirth()
    {
      return $this->DateOfBirth;
    }

    /**
     * @param string $DateOfBirth
     * @return MIAPVerifiedBatchLearner
     */
    public function setDateOfBirth($DateOfBirth)
    {
      $this->DateOfBirth = $DateOfBirth;
      return $this;
    }

    /**
     * @return string
     */
    public function getGender()
    {
      return $this->Gender;
    }

    /**
     * @param string $Gender
     * @return MIAPVerifiedBatchLearner
     */
    public function setGender($Gender)
    {
      $this->Gender = $Gender;
      return $this;
    }

    /**
     * @return string[]
     */
    public function getFailureFlag()
    {
      return $this->FailureFlag;
    }

    /**
     * @param string[] $FailureFlag
     * @return MIAPVerifiedBatchLearner
     */
    public function setFailureFlag(array $FailureFlag = null)
    {
      $this->FailureFlag = $FailureFlag;
      return $this;
    }

}
