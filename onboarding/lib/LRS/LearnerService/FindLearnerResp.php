<?php

class FindLearnerResp extends LearnerServiceWrappedResponse
{

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
     * @var string $LastKnownPostCode
     */
    protected $LastKnownPostCode = null;

    /**
     * @var string $PreviousFamilyName
     */
    protected $PreviousFamilyName = null;

    /**
     * @var string $SchoolAtAge16
     */
    protected $SchoolAtAge16 = null;

    /**
     * @var string $PlaceOfBirth
     */
    protected $PlaceOfBirth = null;

    /**
     * @var string $EmailAddress
     */
    protected $EmailAddress = null;

    /**
     * @var Learner[] $Learner
     */
    protected $Learner = null;

    
    public function __construct()
    {
    
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
     * @return FindLearnerResp
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
     * @return FindLearnerResp
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
     * @return FindLearnerResp
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
     * @return FindLearnerResp
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
     * @return FindLearnerResp
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
     * @return FindLearnerResp
     */
    public function setGender($Gender)
    {
      $this->Gender = $Gender;
      return $this;
    }

    /**
     * @return string
     */
    public function getLastKnownPostCode()
    {
      return $this->LastKnownPostCode;
    }

    /**
     * @param string $LastKnownPostCode
     * @return FindLearnerResp
     */
    public function setLastKnownPostCode($LastKnownPostCode)
    {
      $this->LastKnownPostCode = $LastKnownPostCode;
      return $this;
    }

    /**
     * @return string
     */
    public function getPreviousFamilyName()
    {
      return $this->PreviousFamilyName;
    }

    /**
     * @param string $PreviousFamilyName
     * @return FindLearnerResp
     */
    public function setPreviousFamilyName($PreviousFamilyName)
    {
      $this->PreviousFamilyName = $PreviousFamilyName;
      return $this;
    }

    /**
     * @return string
     */
    public function getSchoolAtAge16()
    {
      return $this->SchoolAtAge16;
    }

    /**
     * @param string $SchoolAtAge16
     * @return FindLearnerResp
     */
    public function setSchoolAtAge16($SchoolAtAge16)
    {
      $this->SchoolAtAge16 = $SchoolAtAge16;
      return $this;
    }

    /**
     * @return string
     */
    public function getPlaceOfBirth()
    {
      return $this->PlaceOfBirth;
    }

    /**
     * @param string $PlaceOfBirth
     * @return FindLearnerResp
     */
    public function setPlaceOfBirth($PlaceOfBirth)
    {
      $this->PlaceOfBirth = $PlaceOfBirth;
      return $this;
    }

    /**
     * @return string
     */
    public function getEmailAddress()
    {
      return $this->EmailAddress;
    }

    /**
     * @param string $EmailAddress
     * @return FindLearnerResp
     */
    public function setEmailAddress($EmailAddress)
    {
      $this->EmailAddress = $EmailAddress;
      return $this;
    }

    /**
     * @return Learner[]
     */
    public function getLearner()
    {
      return $this->Learner;
    }

    /**
     * @param Learner[] $Learner
     * @return FindLearnerResp
     */
    public function setLearner(array $Learner = null)
    {
      $this->Learner = $Learner;
      return $this;
    }

}
