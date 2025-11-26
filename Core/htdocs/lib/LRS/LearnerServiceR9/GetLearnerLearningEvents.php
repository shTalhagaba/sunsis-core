<?php

class GetLearnerLearningEvents
{

    /**
     * @var InvokingOrganisationR10 $invokingOrganisation
     */
    protected $invokingOrganisation = null;

    /**
     * @var string $userType
     */
    protected $userType = null;

    /**
     * @var int $vendorID
     */
    protected $vendorID = null;

    /**
     * @var string $language
     */
    protected $language = null;

    /**
     * @var string $uln
     */
    protected $uln = null;

    /**
     * @var string $givenName
     */
    protected $givenName = null;

    /**
     * @var string $familyName
     */
    protected $familyName = null;

    /**
     * @var string $dateOfBirth
     */
    protected $dateOfBirth = null;

    /**
     * @var string $gender
     */
    protected $gender = null;

    /**
     * @var string $getType
     */
    protected $getType = null;

    /**
     * @param InvokingOrganisationR10 $invokingOrganisation
     * @param string $userType
     * @param int $vendorID
     * @param string $language
     * @param string $uln
     * @param string $givenName
     * @param string $familyName
     * @param string $dateOfBirth
     * @param string $gender
     * @param string $getType
     */
    public function __construct($invokingOrganisation, $userType, $vendorID, $language, $uln, $givenName, $familyName, $dateOfBirth, $gender, $getType)
    {
      $this->invokingOrganisation = $invokingOrganisation;
      $this->userType = $userType;
      $this->vendorID = $vendorID;
      $this->language = $language;
      $this->uln = $uln;
      $this->givenName = $givenName;
      $this->familyName = $familyName;
      $this->dateOfBirth = $dateOfBirth;
      $this->gender = $gender;
      $this->getType = $getType;
    }

    /**
     * @return InvokingOrganisationR10
     */
    public function getInvokingOrganisation()
    {
      return $this->invokingOrganisation;
    }

    /**
     * @param InvokingOrganisationR10 $invokingOrganisation
     * @return GetLearnerLearningEvents
     */
    public function setInvokingOrganisation($invokingOrganisation)
    {
      $this->invokingOrganisation = $invokingOrganisation;
      return $this;
    }

    /**
     * @return string
     */
    public function getUserType()
    {
      return $this->userType;
    }

    /**
     * @param string $userType
     * @return GetLearnerLearningEvents
     */
    public function setUserType($userType)
    {
      $this->userType = $userType;
      return $this;
    }

    /**
     * @return int
     */
    public function getVendorID()
    {
      return $this->vendorID;
    }

    /**
     * @param int $vendorID
     * @return GetLearnerLearningEvents
     */
    public function setVendorID($vendorID)
    {
      $this->vendorID = $vendorID;
      return $this;
    }

    /**
     * @return string
     */
    public function getLanguage()
    {
      return $this->language;
    }

    /**
     * @param string $language
     * @return GetLearnerLearningEvents
     */
    public function setLanguage($language)
    {
      $this->language = $language;
      return $this;
    }

    /**
     * @return string
     */
    public function getUln()
    {
      return $this->uln;
    }

    /**
     * @param string $uln
     * @return GetLearnerLearningEvents
     */
    public function setUln($uln)
    {
      $this->uln = $uln;
      return $this;
    }

    /**
     * @return string
     */
    public function getGivenName()
    {
      return $this->givenName;
    }

    /**
     * @param string $givenName
     * @return GetLearnerLearningEvents
     */
    public function setGivenName($givenName)
    {
      $this->givenName = $givenName;
      return $this;
    }

    /**
     * @return string
     */
    public function getFamilyName()
    {
      return $this->familyName;
    }

    /**
     * @param string $familyName
     * @return GetLearnerLearningEvents
     */
    public function setFamilyName($familyName)
    {
      $this->familyName = $familyName;
      return $this;
    }

    /**
     * @return string
     */
    public function getDateOfBirth()
    {
      return $this->dateOfBirth;
    }

    /**
     * @param string $dateOfBirth
     * @return GetLearnerLearningEvents
     */
    public function setDateOfBirth($dateOfBirth)
    {
      $this->dateOfBirth = $dateOfBirth;
      return $this;
    }

    /**
     * @return string
     */
    public function getGender()
    {
      return $this->gender;
    }

    /**
     * @param string $gender
     * @return GetLearnerLearningEvents
     */
    public function setGender($gender)
    {
      $this->gender = $gender;
      return $this;
    }

    /**
     * @return string
     */
    public function getGetType()
    {
      return $this->getType;
    }

    /**
     * @param string $getType
     * @return GetLearnerLearningEvents
     */
    public function setGetType($getType)
    {
      $this->getType = $getType;
      return $this;
    }

}
