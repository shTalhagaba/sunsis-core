<?php

class LearnerByUln
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
     * @var string $givenName
     */
    protected $givenName = null;

    /**
     * @var string $familyName
     */
    protected $familyName = null;

    /**
     * @var string $findType
     */
    protected $findType = null;

    /**
     * @param InvokingOrganisation $invokingOrganisation
     * @param string $userType
     * @param int $vendorId
     * @param string $language
     * @param string $uln
     * @param string $givenName
     * @param string $familyName
     * @param string $findType
     */
    public function __construct($invokingOrganisation, $userType, $vendorId, $language, $uln, $givenName, $familyName, $findType)
    {
      $this->invokingOrganisation = $invokingOrganisation;
      $this->userType = $userType;
      $this->vendorId = $vendorId;
      $this->language = $language;
      $this->uln = $uln;
      $this->givenName = $givenName;
      $this->familyName = $familyName;
      $this->findType = $findType;
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
     * @return LearnerByUln
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
     * @return LearnerByUln
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
     * @return LearnerByUln
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
     * @return LearnerByUln
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
     * @return LearnerByUln
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
     * @return LearnerByUln
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
     * @return LearnerByUln
     */
    public function setFamilyName($familyName)
    {
      $this->familyName = $familyName;
      return $this;
    }

    /**
     * @return string
     */
    public function getFindType()
    {
      return $this->findType;
    }

    /**
     * @param string $findType
     * @return LearnerByUln
     */
    public function setFindType($findType)
    {
      $this->findType = $findType;
      return $this;
    }

}
