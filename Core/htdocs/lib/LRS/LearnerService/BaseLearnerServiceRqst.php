<?php

abstract class BaseLearnerServiceRqst extends BaseLearnerServiceRequestPart
{

    /**
     * @var string $OrganisationRef
     */
    protected $OrganisationRef = null;

    /**
     * @var string $UKPRN
     */
    protected $UKPRN = null;

    /**
     * @var string $OrgPassword
     */
    protected $OrgPassword = null;

    /**
     * @var string $UserName
     */
    protected $UserName = null;

    
    public function __construct()
    {
    
    }

    /**
     * @return string
     */
    public function getOrganisationRef()
    {
      return $this->OrganisationRef;
    }

    /**
     * @param string $OrganisationRef
     * @return BaseLearnerServiceRqst
     */
    public function setOrganisationRef($OrganisationRef)
    {
      $this->OrganisationRef = $OrganisationRef;
      return $this;
    }

    /**
     * @return string
     */
    public function getUKPRN()
    {
      return $this->UKPRN;
    }

    /**
     * @param string $UKPRN
     * @return BaseLearnerServiceRqst
     */
    public function setUKPRN($UKPRN)
    {
      $this->UKPRN = $UKPRN;
      return $this;
    }

    /**
     * @return string
     */
    public function getOrgPassword()
    {
      return $this->OrgPassword;
    }

    /**
     * @param string $OrgPassword
     * @return BaseLearnerServiceRqst
     */
    public function setOrgPassword($OrgPassword)
    {
      $this->OrgPassword = $OrgPassword;
      return $this;
    }

    /**
     * @return string
     */
    public function getUserName()
    {
      return $this->UserName;
    }

    /**
     * @param string $UserName
     * @return BaseLearnerServiceRqst
     */
    public function setUserName($UserName)
    {
      $this->UserName = $UserName;
      return $this;
    }

}
