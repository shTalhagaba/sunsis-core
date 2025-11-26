<?php

class GetOrganisation
{

    /**
     * @var InvokingOrganisation $invokingOrganisation
     */
    protected $invokingOrganisation = null;

    /**
     * @var UserType $userType
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
     * @var string $ukPrn
     */
    protected $ukPrn = null;

    /**
     * @var string $orgRef
     */
    protected $orgRef = null;

    /**
     * @param InvokingOrganisation $invokingOrganisation
     * @param UserType $userType
     * @param int $vendorId
     * @param string $language
     * @param string $ukPrn
     * @param string $orgRef
     */
    public function __construct($invokingOrganisation, $userType, $vendorId, $language, $ukPrn, $orgRef)
    {
      $this->invokingOrganisation = $invokingOrganisation;
      $this->userType = $userType;
      $this->vendorId = $vendorId;
      $this->language = $language;
      $this->ukPrn = $ukPrn;
      $this->orgRef = $orgRef;
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
     * @return GetOrganisation
     */
    public function setInvokingOrganisation($invokingOrganisation)
    {
      $this->invokingOrganisation = $invokingOrganisation;
      return $this;
    }

    /**
     * @return UserType
     */
    public function getUserType()
    {
      return $this->userType;
    }

    /**
     * @param UserType $userType
     * @return GetOrganisation
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
     * @return GetOrganisation
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
     * @return GetOrganisation
     */
    public function setLanguage($language)
    {
      $this->language = $language;
      return $this;
    }

    /**
     * @return string
     */
    public function getUkPrn()
    {
      return $this->ukPrn;
    }

    /**
     * @param string $ukPrn
     * @return GetOrganisation
     */
    public function setUkPrn($ukPrn)
    {
      $this->ukPrn = $ukPrn;
      return $this;
    }

    /**
     * @return string
     */
    public function getOrgRef()
    {
      return $this->orgRef;
    }

    /**
     * @param string $orgRef
     * @return GetOrganisation
     */
    public function setOrgRef($orgRef)
    {
      $this->orgRef = $orgRef;
      return $this;
    }

}
