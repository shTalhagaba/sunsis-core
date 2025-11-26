<?php

class UpdateLearnerSubsetFields
{

    /**
     * @var InvokingOrganisation $invokingOrganisation
     */
    protected $invokingOrganisation = null;

    /**
     * @var string $userType
     */
    protected $userType = null;

    /**
     * @var int $vendorId
     */
    protected $vendorId = null;

    /**
     * @var string $language
     */
    protected $language = null;

    /**
     * @var string $uln
     */
    protected $uln = null;

    /**
     * @var int $versionNumber
     */
    protected $versionNumber = null;

    /**
     * @var string $emailAddress
     */
    protected $emailAddress = null;

    /**
     * @var string $telephoneNumber
     */
    protected $telephoneNumber = null;

    /**
     * @var AbilityToShare $abilityToShare
     */
    protected $abilityToShare = null;

    /**
     * @var string $schoolAtAge16
     */
    protected $schoolAtAge16 = null;

    /**
     * @var string $preferredGivenName
     */
    protected $preferredGivenName = null;

    /**
     * @param InvokingOrganisation $invokingOrganisation
     * @param string $userType
     * @param int $vendorId
     * @param string $language
     * @param string $uln
     * @param int $versionNumber
     * @param string $emailAddress
     * @param string $telephoneNumber
     * @param AbilityToShare $abilityToShare
     * @param string $schoolAtAge16
     * @param string $preferredGivenName
     */
    public function __construct($invokingOrganisation, $userType, $vendorId, $language, $uln, $versionNumber, $emailAddress, $telephoneNumber, $abilityToShare, $schoolAtAge16, $preferredGivenName)
    {
      $this->invokingOrganisation = $invokingOrganisation;
      $this->userType = $userType;
      $this->vendorId = $vendorId;
      $this->language = $language;
      $this->uln = $uln;
      $this->versionNumber = $versionNumber;
      $this->emailAddress = $emailAddress;
      $this->telephoneNumber = $telephoneNumber;
      $this->abilityToShare = $abilityToShare;
      $this->schoolAtAge16 = $schoolAtAge16;
      $this->preferredGivenName = $preferredGivenName;
    }

    /**
     * @return InvokingOrganisation
     */
    public function getInvokingOrganisation()
    {
      return $this->invokingOrganisation;
    }

    /**
     * @param InvokingOrganisation $invokingOrganisation
     * @return UpdateLearnerSubsetFields
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
     * @return UpdateLearnerSubsetFields
     */
    public function setUserType($userType)
    {
      $this->userType = $userType;
      return $this;
    }

    /**
     * @return int
     */
    public function getVendorId()
    {
      return $this->vendorId;
    }

    /**
     * @param int $vendorId
     * @return UpdateLearnerSubsetFields
     */
    public function setVendorId($vendorId)
    {
      $this->vendorId = $vendorId;
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
     * @return UpdateLearnerSubsetFields
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
     * @return UpdateLearnerSubsetFields
     */
    public function setUln($uln)
    {
      $this->uln = $uln;
      return $this;
    }

    /**
     * @return int
     */
    public function getVersionNumber()
    {
      return $this->versionNumber;
    }

    /**
     * @param int $versionNumber
     * @return UpdateLearnerSubsetFields
     */
    public function setVersionNumber($versionNumber)
    {
      $this->versionNumber = $versionNumber;
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
     * @return UpdateLearnerSubsetFields
     */
    public function setEmailAddress($emailAddress)
    {
      $this->emailAddress = $emailAddress;
      return $this;
    }

    /**
     * @return string
     */
    public function getTelephoneNumber()
    {
      return $this->telephoneNumber;
    }

    /**
     * @param string $telephoneNumber
     * @return UpdateLearnerSubsetFields
     */
    public function setTelephoneNumber($telephoneNumber)
    {
      $this->telephoneNumber = $telephoneNumber;
      return $this;
    }

    /**
     * @return AbilityToShare
     */
    public function getAbilityToShare()
    {
      return $this->abilityToShare;
    }

    /**
     * @param AbilityToShare $abilityToShare
     * @return UpdateLearnerSubsetFields
     */
    public function setAbilityToShare($abilityToShare)
    {
      $this->abilityToShare = $abilityToShare;
      return $this;
    }

    /**
     * @return string
     */
    public function getSchoolAtAge16()
    {
      return $this->schoolAtAge16;
    }

    /**
     * @param string $schoolAtAge16
     * @return UpdateLearnerSubsetFields
     */
    public function setSchoolAtAge16($schoolAtAge16)
    {
      $this->schoolAtAge16 = $schoolAtAge16;
      return $this;
    }

    /**
     * @return string
     */
    public function getPreferredGivenName()
    {
      return $this->preferredGivenName;
    }

    /**
     * @param string $preferredGivenName
     * @return UpdateLearnerSubsetFields
     */
    public function setPreferredGivenName($preferredGivenName)
    {
      $this->preferredGivenName = $preferredGivenName;
      return $this;
    }

}
