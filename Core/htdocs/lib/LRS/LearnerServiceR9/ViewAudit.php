<?php

class ViewAudit
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
     * @var string $learnerUln
     */
    protected $learnerUln = null;

    /**
     * @var string $learnerGivenName
     */
    protected $learnerGivenName = null;

    /**
     * @var string $learnerFamilyName
     */
    protected $learnerFamilyName = null;

    /**
     * @var int $page
     */
    protected $page = null;

    /**
     * @param InvokingOrganisation $invokingOrganisation
     * @param string $userType
     * @param int $vendorId
     * @param string $language
     * @param string $learnerUln
     * @param string $learnerGivenName
     * @param string $learnerFamilyName
     * @param int $page
     */
    public function __construct($invokingOrganisation, $userType, $vendorId, $language, $learnerUln, $learnerGivenName, $learnerFamilyName, $page)
    {
      $this->invokingOrganisation = $invokingOrganisation;
      $this->userType = $userType;
      $this->vendorId = $vendorId;
      $this->language = $language;
      $this->learnerUln = $learnerUln;
      $this->learnerGivenName = $learnerGivenName;
      $this->learnerFamilyName = $learnerFamilyName;
      $this->page = $page;
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
     * @return ViewAudit
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
     * @return ViewAudit
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
     * @return ViewAudit
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
     * @return ViewAudit
     */
    public function setLanguage($language)
    {
      $this->language = $language;
      return $this;
    }

    /**
     * @return string
     */
    public function getLearnerUln()
    {
      return $this->learnerUln;
    }

    /**
     * @param string $learnerUln
     * @return ViewAudit
     */
    public function setLearnerUln($learnerUln)
    {
      $this->learnerUln = $learnerUln;
      return $this;
    }

    /**
     * @return string
     */
    public function getLearnerGivenName()
    {
      return $this->learnerGivenName;
    }

    /**
     * @param string $learnerGivenName
     * @return ViewAudit
     */
    public function setLearnerGivenName($learnerGivenName)
    {
      $this->learnerGivenName = $learnerGivenName;
      return $this;
    }

    /**
     * @return string
     */
    public function getLearnerFamilyName()
    {
      return $this->learnerFamilyName;
    }

    /**
     * @param string $learnerFamilyName
     * @return ViewAudit
     */
    public function setLearnerFamilyName($learnerFamilyName)
    {
      $this->learnerFamilyName = $learnerFamilyName;
      return $this;
    }

    /**
     * @return int
     */
    public function getPage()
    {
      return $this->page;
    }

    /**
     * @param int $page
     * @return ViewAudit
     */
    public function setPage($page)
    {
      $this->page = $page;
      return $this;
    }

}
